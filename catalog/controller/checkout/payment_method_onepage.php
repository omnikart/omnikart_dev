<?php

class ControllerCheckoutPaymentMethodOnepage extends Controller {

    public function index($render_content = false) {
        $this->load->model('setting/setting');

        $mmos_checkout_extra = $this->model_setting_setting->getSetting('mmos_checkout', $this->config->get('config_store_id'));
        $mmos_checkout = $mmos_checkout_extra['mmos_checkout'];
        $view_data['css'] = $mmos_checkout_extra['mmos_checkout_css'];
        $view_data['themes'] = $this->config->get('mmos_checkout_themes');
        if (empty($css['checkout_theme']) || !in_array($css['checkout_theme'], $themes)) {
            $css['checkout_theme'] = 'standar';
        }

        $this->load->language('checkout/checkout');

        $this->load->model('checkout/checkout_onepage');

        if ($this->customer->isLogged()) {
            $check_address = $this->model_checkout_checkout_onepage->calculate_payment($mmos_checkout, $this->session->data["payment_address"]);
        } else {
            if ($this->session->data['account'] == 'guest') {
                $check_address = $this->model_checkout_checkout_onepage->calculate_payment($mmos_checkout, $this->session->data['guest']['payment']);
            } else if ($this->session->data['account'] == 'register') {
                $check_address = $this->model_checkout_checkout_onepage->calculate_payment($mmos_checkout, $this->session->data['register']);
            }
        }

        if ($check_address) {
            $this->session->data['payment_methods'] = array();
        } else {
            if (isset($this->session->data['payment_address'])) {
                // Totals
                $total_data = array();
                $total = 0;
                $taxes = $this->cart->getTaxes();

                $this->load->model('extension/extension');

                $sort_order = array();

                $results = $this->model_extension_extension->getExtensions('total');

                foreach ($results as $key => $value) {
                    $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
                }

                array_multisort($sort_order, SORT_ASC, $results);

                foreach ($results as $result) {
                    if ($this->config->get($result['code'] . '_status')) {
                        $this->load->model('total/' . $result['code']);

                        $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                    }
                }

                // Payment Methods
                $method_data = array();

                $this->load->model('extension/extension');

                $results = $this->model_extension_extension->getExtensions('payment');

                $recurring = $this->cart->hasRecurringProducts();

                foreach ($results as $result) {
                    if ($this->config->get($result['code'] . '_status')) {
                        $this->load->model('payment/' . $result['code']);

                        $method = $this->{'model_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);

                        if ($method) {
                            if ($recurring) {
                                if (method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_payment_' . $result['code']}->recurringPayments()) {
                                    $method_data[$result['code']] = $method;
                                }
                            } else {
                                $method_data[$result['code']] = $method;
                            }
                        }
                    }
                }

                $sort_order = array();

                foreach ($method_data as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $method_data);

                $this->session->data['payment_methods'] = $method_data;
            }
        }

        $view_data['text_payment_method'] = $this->language->get('text_payment_method');

        $view_data['button_continue'] = $this->language->get('button_continue');

        if (empty($this->session->data['payment_methods'])) {
            $view_data['warning_non_address_payment'] = $mmos_checkout['warning_non_address_payment'][$this->config->get('config_language_id')];
        } else {
            $view_data['warning_non_address_payment'] = '';
        }

        if (isset($this->session->data['payment_methods'])) {
            $view_data['payment_methods'] = $this->session->data['payment_methods'];
            $view_data['error_warning'] = '';
        } else {
            $view_data['payment_methods'] = array();
            $view_data['error_warning'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
        }

        if (!$this->customer->isLogged()) {
            if ($this->session->data['account'] == 'guest') {
                $this->session->data['checkout_guest']['payment_methods'] = $this->session->data['payment_methods'];
            } else if ($this->session->data['account'] == 'register') {
                $this->session->data['checkout_register']['payment_methods'] = $this->session->data['payment_methods'];
            }
        } else {
            $this->session->data['checkout_customer']['payment_methods'] = $this->session->data['payment_methods'];
        }

        $payment_method_code = '';
        if (isset($this->session->data['payment_method']['code'])) {
            $payment_method_code = $this->session->data['payment_method']['code'];
        } else {
            $payment_method_code = '';
        }
        $is_set = false;
        foreach ($this->session->data['payment_methods'] as $payment_method) {

            if ($payment_method_code == $payment_method['code']) {
                $this->session->data['payment_method'] = $payment_method;
                $is_set = true;
                break;
            }
        }
        if ($is_set) {
            $view_data['code'] = $payment_method_code;
        } else {
            if (!empty($this->session->data['payment_methods'])) {
                $this->session->data['payment_method'] = reset($this->session->data['payment_methods']);
                $view_data['code'] = !empty($this->session->data['payment_method']['code']) ? $this->session->data['payment_method']['code'] : '';
            } else {
                $view_data['code'] = '';
            }
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/payment_method_onepage.tpl')) {
            $template = $this->config->get('config_template') . '/template/checkout/payment_method_onepage.tpl';
        } else {
            $template = 'default/template/checkout/payment_method_onepage.tpl';
        }

        if (!$render_content) {
            $this->response->setOutput($this->load->view($template, $view_data));
        } else {
            return $this->load->view($template, $view_data);
        }
    }

    public function render_index() {
        return $this->index(true);
    }

    public function validate() {

        $this->load->language('checkout/checkout');

        $json = array();

        // Validate if payment address has been set.
        $this->load->model('account/address');

        if ($this->customer->isLogged() && isset($this->session->data['payment_address_id'])) {
            $payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
        } elseif (isset($this->session->data['guest'])) {
            $payment_address = $this->session->data['guest']['payment'];
        }

        if (empty($payment_address)) {
            $json['redirect'] = $this->url->link('checkout/checkout_onepage', '', 'SSL');
        }

        // Validate cart has products and has stock.			
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $json['redirect'] = $this->url->link('checkout/cart');
        }

        // Validate minimum quantity requirments.			
        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $json['redirect'] = $this->url->link('checkout/cart');

                break;
            }
        }
        if (!$this->customer->isLogged()) {
            if ($this->session->data['account'] == 'guest') {
                $this->session->data['payment_methods'] = !empty($this->session->data['checkout_guest']['payment_methods']) ? $this->session->data['checkout_guest']['payment_methods'] : array();
            } else if ($this->session->data['account'] == 'register') {
//            $this->session->data['register']['shipping_method'] = $this->session->data['shipping_method'];
                $this->session->data['payment_methods'] = !empty($this->session->data['checkout_register']['payment_methods']) ? $this->session->data['checkout_register']['payment_methods'] : array();
            }
        } else {
            $this->session->data['payment_methods'] = !empty($this->session->data['checkout_customer']['payment_methods']) ? $this->session->data['checkout_customer']['payment_methods'] : array();
        }

        if (!$json) {
            if (!isset($this->request->post['payment_method'])) {
                $json['error']['warning'] = $this->language->get('error_payment');
            } elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
                $json['error']['warning'] = $this->language->get('error_payment');
            }


            if (!$json) {
                $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];

                //$this->session->data['comment'] = strip_tags($this->request->post['comment']);
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function quick_validate() {
        $this->load->language('checkout/checkout');

        $json = array();


        if (!isset($this->request->post['payment_method'])) {
            $json['error']['warning'] = $this->language->get('error_payment');
        } elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
            $json['error']['warning'] = $this->language->get('error_payment');
        }



        if (!$json) {
            $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];

            ##store payment method to current account(guest or post)
            if (!$this->customer->isLogged()) {
                if ($this->session->data['account'] == 'guest') {
                    $this->session->data['checkout_guest']['payment_method'] = $this->session->data['payment_method'];
                } else if ($this->session->data['account'] == 'register') {
                    $this->session->data['checkout_register']['payment_method'] = $this->session->data['payment_method'];
                }
            } else {
                //no need, because no need change between checkout states(there is only one state).
//                $this->session->data['checkout_customer']['payment_method']=$this->session->data['payment_method'];
            }
        }

        $this->response->setOutput(json_encode($json));
    }

}

?>