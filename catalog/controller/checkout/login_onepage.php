<?php

class ControllerCheckoutLoginOnepage extends Controller {

    public function index($render_content=false) {
        $this->load->model('setting/setting');
        
        $mmos_checkout_extra = $this->model_setting_setting->getSetting('mmos_checkout', $this->config->get('config_store_id'));
        $mmos_checkout = $mmos_checkout_extra['mmos_checkout'];
        $view_data['css']= $mmos_checkout_extra['mmos_checkout_css'];
        $view_data['themes']=$this->config->get('mmos_checkout_themes');
        if(empty($css['checkout_theme'])||!in_array($css['checkout_theme'], $themes)){$css['checkout_theme']='standar';}
        
        
        $this->load->language('checkout/checkout');

        $view_data['text_returning_customer'] = $this->language->get('text_returning_customer');
        $view_data['text_register'] = $this->language->get('text_register');
        $view_data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
        $view_data['text_forgotten'] = $this->language->get('text_forgotten');

        $view_data['entry_email'] = $this->language->get('entry_email');
        $view_data['entry_password'] = $this->language->get('entry_password');

        $view_data['button_login'] = $this->language->get('button_login');

        $view_data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/login_onepage.tpl')) {
            $template = $this->config->get('config_template') . '/template/checkout/login_onepage.tpl';
        } else {
            $template = 'default/template/checkout/login_onepage.tpl';
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

    //login validate
    public function validate() {
        $this->session->data['account_view'] = 'returning-customer';
        $this->load->language('checkout/checkout');

        $json = array();

        if ($this->customer->isLogged()) {
            $json['redirect'] = $this->url->link('checkout/checkout_onepage', '', 'SSL');
        }

        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $json['redirect'] = $this->url->link('checkout/cart');
        }

        if (!$json) {
            if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
                $json['error']['warning'] = $this->language->get('error_login');
            }

            $this->load->model('account/customer');

            $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

            if ($customer_info && !$customer_info['approved']) {
                $json['error']['warning'] = $this->language->get('error_approved');
            }
        }

        if (!$json) {
            unset($this->session->data['guest']);
            // Default Addresses
            $this->load->model('account/address');

            $address_info = $this->model_account_address->getAddress($this->customer->getAddressId());

            if ($address_info) {
                if ($this->config->get('config_tax_customer') == 'shipping') {
                }

                if ($this->config->get('config_tax_customer') == 'payment') {
                }
            } else {
            }

            $json['redirect'] = $this->url->link('checkout/checkout_onepage', '', 'SSL');
        }

        $this->response->setOutput(json_encode($json));
    }

    public function set_account_view() {
        $json = array();
        $this->session->data['account_view'] = 'returning-customer';
        $this->response->setOutput(json_encode($json));
    }
}

?>