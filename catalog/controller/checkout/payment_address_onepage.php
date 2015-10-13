<?php

class ControllerCheckoutPaymentAddressOnepage extends Controller {

    public function index($render_content = false) {
        // <editor-fold defaultstate="collapsed" desc="comment">
        $this->load->model('setting/setting');

        $mmos_checkout_extra = $this->model_setting_setting->getSetting('mmos_checkout', $this->config->get('config_store_id'));
        $mmos_checkout = $mmos_checkout_extra['mmos_checkout'];
        $view_data['css'] = $mmos_checkout_extra['mmos_checkout_css'];
        $view_data['themes'] = $this->config->get('mmos_checkout_themes');
        if (empty($css['checkout_theme']) || !in_array($css['checkout_theme'], $themes)) {
            $css['checkout_theme'] = 'standar';
        }


        $this->load->language('checkout/checkout');

        $view_data['text_address_existing'] = $this->language->get('text_address_existing');
        $view_data['text_address_new'] = $this->language->get('text_address_new');
        $view_data['text_select'] = $this->language->get('text_select');
        $view_data['text_none'] = $this->language->get('text_none');
        $view_data['text_loading'] = $this->language->get('text_loading');

        $view_data['entry_firstname'] = $this->language->get('entry_firstname');
        $view_data['entry_lastname'] = $this->language->get('entry_lastname');
        $view_data['entry_company'] = $this->language->get('entry_company');
        $view_data['entry_address_1'] = $this->language->get('entry_address_1');
        $view_data['entry_address_2'] = $this->language->get('entry_address_2');
        $view_data['entry_postcode'] = $this->language->get('entry_postcode');
        $view_data['entry_city'] = $this->language->get('entry_city');
        $view_data['entry_country'] = $this->language->get('entry_country');
        $view_data['entry_zone'] = $this->language->get('entry_zone');
        $view_data['entry_shipping'] = $this->language->get('entry_shipping');

        $view_data['button_continue'] = $this->language->get('button_continue');
        $view_data['button_upload'] = $this->language->get('button_upload');
        $view_data['button_ok'] = 'OK'; // </editor-fold>


        $this->load->model('account/address');

        //will be removed address which address_1 is empty (fake addresses)
        //check default customer address_id change
        //$view_data['trusted_addresses']=array();
        // ONLY FOR SHIPPING REQUIRED!!!!!!!

        $view_data['addresses'] = array();


        if ($this->cart->hasShipping()) {
            $shipping_required = 1;
        } else {
            $shipping_required = 0;
        }
        $view_data['shipping_required'] = $shipping_required;


        $view_data['addresses'] = $addresses = $this->model_account_address->getAddresses();


        $trusted_addresses = array();
        $fake_addresses = array();
        foreach ($addresses as $address) {
            if ($address['address_1'] == '') {
                $fake_addresses[$address['address_id']] = $address;
            } else {
                $trusted_addresses[$address['address_id']] = $address;
            }
        }
        $view_data['trusted_addresses'] = $trusted_addresses;
        $view_data['fake_addresses'] = $fake_addresses;

        if ($trusted_addresses) {//exist trusted addresses
            $trust_exist = true;
        } else {
            $trust_exist = false;
        }
        $view_data['trust_exist'] = $trust_exist;


        if ($fake_addresses) {
            $fake_exist = true;
        } else {
            $fake_exist = false;
        }
        $view_data['fake_exist'] = $fake_exist;

        if (isset($this->session->data['shipping_address_same'])) {
            $shipping_address_same = $this->session->data['shipping_address_same'] ? 1 : 0;
        } else {
            $shipping_address_same = 0;
        }
        $view_data['shipping_address_same'] = $shipping_address_same;
        $this->session->data['shipping_address_same'] = $shipping_address_same;

        if (isset($this->session->data['payment_new']) && $this->session->data['payment_new'] == 1) {//may unused
            $payment_new = 1;
        } else {
            $payment_new = 0;
        }
        $view_data['payment_new'] = $payment_new;

//        if (($addresses && !$shipping_required) || $trust_exist){// && !$payment_new) {
        if ($addresses && (!$shipping_required || $trust_exist)) {
            $use_exist = true;
        } else {
            $use_exist = false;
        }
        $view_data['use_exist'] = $use_exist;

        ###ENSURE ALWAYS SET PAYMENT_COUNTRY_ID & PAYMENT_ZONE_ID

        if ($use_exist) {
            if (!$shipping_required) {
                if (isset($this->session->data['payment_address_id']) && in_array($this->session->data['payment_address_id'], array_keys($addresses))) {
                    $address_id = $this->session->data['payment_address_id'];
                } else {
                    $address_id = $this->customer->getAddressId();
                }
                $view_data['address_id'] = $address_id;
                $this->session->data['payment_address_id'] = $address_id;
                $this->session->data['payment_address'] = $this->model_account_address->getAddress($address_id);
                //# don't set anything to shipping  #?????
            } else if ($trust_exist) {
                if (isset($this->session->data['payment_address_id']) && in_array($this->session->data['payment_address_id'], array_keys($trusted_addresses))) {
                    $address_id = $this->session->data['payment_address_id'];
                } else {
                    //set address to  first trust address
                    $trust_address = reset($trusted_addresses);
                    $address_id = $trust_address['address_id'];
                }
                $view_data['address_id'] = $address_id;

                $this->session->data['payment_address_id'] = $address_id;
                $this->session->data['payment_address'] = $this->model_account_address->getAddress($address_id);
            }
        } else {//show new address
            $this->session->data['payment_address'] = array();

            ### CLONE (FAKE) ADDRESS DETAIL TO NEW ADDRESS FORM.
            $view_data['new_address'] = $new_address = reset($addresses);

            $view_data['new_payment_address_id'] = $new_address['address_id'];
            $this->session->data['payment_address_id'] = $this->session->data['shipping_address_id'];
            $this->session->data['payment_address'] = $this->model_account_address->getAddress($new_address['address_id']);
        }

        if ($shipping_address_same && $shipping_required) {
            $this->session->data['shipping_address_id'] = $this->session->data['payment_address_id'];
            $this->session->data['shipping_address'] = $this->session->data['payment_address'];
        }


        if (isset($this->session->data['payment_address']['country_id'])) {
            $view_data['country_id'] = $this->session->data['payment_address']['country_id']; //country_id for load country list. Want to load the country_id that user choosen lasttime.
        } else {
            $view_data['country_id'] = '';
        }

        if (isset($this->session->data['payment_address']['zone_id'])) {
            $view_data['zone_id'] = $this->session->data['payment_address']['zone_id'];
        } else {
            $view_data['zone_id'] = '';
        }

        $this->load->model('localisation/country');

        $view_data['countries'] = $this->model_localisation_country->getCountries();
        $country_info = $this->model_localisation_country->getCountry($view_data['country_id']);
        if ($country_info) {
            $this->load->model('localisation/zone');
            $view_data['zones'] = $this->model_localisation_zone->getZonesByCountryId($view_data['country_id']);
        } else {
            $view_data['zones'] = array();
        }

        $this->load->model('account/custom_field');

        $view_data['custom_fields'] = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

        if (isset($this->session->data['payment_address']['custom_field'])) {
            $view_data['payment_address_custom_field'] = $this->session->data['payment_address']['custom_field'];
        } else {
            $view_data['payment_address_custom_field'] = array();
        }


//        if (isset($this->request->get['payment_existing']) && $this->request->get['payment_existing'] == '1') {
//            $view_data['payment_existing_only'] = 1;
//        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/payment_address_onepage.tpl')) {
            $template = $this->config->get('config_template') . '/template/checkout/payment_address_onepage.tpl';
        } else {
            $template = 'default/template/checkout/payment_address_onepage.tpl';
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

        // Validate if customer is logged in.
        if (!$this->customer->isLogged()) {
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

        if (!$json) {
            if (isset($this->request->post['payment_address']) && $this->request->post['payment_address'] == 'existing') {
                $this->load->model('account/address');

                if (empty($this->request->post['address_id'])) {
                    $json['error']['warning'] = $this->language->get('error_address');
                } elseif (!in_array($this->request->post['address_id'], array_keys($this->model_account_address->getAddresses()))) {
                    $json['error']['warning'] = $this->language->get('error_address');
                } else {
                    $this->load->model('account/address');
                    $address_info = $this->model_account_address->getAddress($this->request->post['address_id']);
                }

                if (!$json) {
                    if ($address_info) {
                        // Default Payment Address
                        $this->session->data['payment_address_id'] = $address_info['address_id'];
                        $this->session->data['payment_address'] = $address_info;
                        #for shipping address the same
                        if (isset($this->request->post['shipping_address_same'])) {// checkbox `My delivery and billing addresses are the same.`
                            $this->session->data['shipping_address_same'] = 1;
                        } else {
                            $this->session->data['shipping_address_same'] = 0;
                        }

                        if ($this->session->data['shipping_address_same']) {
                            $this->session->data['shipping_address_id'] = $this->session->data['payment_address_id'];
                            $this->session->data['shipping_address'] = $this->session->data['payment_address'];
                            unset($this->session->data['shipping_methods']);
                        }
                    } else {
                        unset($this->session->data['payment_address_id']);
                        $json['redirect'] = $this->url->link('checkout/checkout_onepage', '', 'SSL'); //add
                    }

                    //unset($this->session->data['payment_method']);//remove //because of this will re-use when load payment_method in sub-loading.
                    unset($this->session->data['payment_methods']); //because of reload payment_methods
                }
            }
            //new payment_address post
            else {
                if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
                    $json['error']['firstname'] = $this->language->get('error_firstname');
                }

                if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
                    $json['error']['lastname'] = $this->language->get('error_lastname');
                }

                if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
                    $json['error']['address_1'] = $this->language->get('error_address_1');
                }

                if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 32)) {
                    $json['error']['city'] = $this->language->get('error_city');
                }

                $this->load->model('localisation/country');

                $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

                if ($country_info) {
                    if ($country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
                        $json['error']['postcode'] = $this->language->get('error_postcode');
                    }
                }

                if ($this->request->post['country_id'] == '') {
                    $json['error']['country'] = $this->language->get('error_country');
                }

                if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
                    $json['error']['zone'] = $this->language->get('error_zone');
                }

                $this->load->model('account/custom_field');

                $custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

                foreach ($custom_fields as $custom_field) {
                    if (($custom_field['location'] == 'address') && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
                        $json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
                    }
                }

                if (!$json) {
                    $this->load->model('account/address');
                    $addresses = $this->model_account_address->getAddresses();
                    if (isset($this->request->post['new_payment_address_id']) && in_array((int) $this->request->post['new_payment_address_id'], array_keys($addresses)) && empty($addresses[(int) $this->request->post['new_payment_address_id']]['address_1'])) {//edit existing address when no trusted address exist.
                        //$this->session->data['show_new_address'] = true;//????????????
                        $this->model_account_address->editAddress((int) $this->request->post['new_payment_address_id'], $this->request->post);
                        $this->session->data['payment_address_id'] = (int) $this->request->post['new_payment_address_id'];
                        $this->session->data['payment_address'] = $this->model_account_address->getAddress((int) $this->request->post['new_payment_address_id']);
                    } else {
                        // Default Payment Address
                        $this->session->data['payment_address_id'] = $address_id = $this->model_account_address->addAddress($this->request->post);
                        $this->session->data['payment_address'] = $this->model_account_address->getAddress($address_id);
                    }

                    if (isset($this->request->post['shipping_address_same'])) {
                        $this->session->data['shipping_address_id'] = $this->session->data['payment_address_id'];
                        $this->session->data['shipping_address'] = $this->session->data['payment_address'];

                        unset($this->session->data['shipping_methods']);
                    }

                    //unset($this->session->data['payment_method']);//because of this will re-use when load payment_method in sub-loading.
                    unset($this->session->data['payment_methods']);
                }
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function validate_zone() {
        $this->load->language('checkout/checkout');

        $json = array();

        if ($this->request->post['country_id'] == '') {
            $json['error']['country'] = $this->language->get('error_country');
        }

        if (!isset($this->request->post['zone_id'])) {// || $this->request->post['zone_id'] == '') {
            $json['error']['zone'] = $this->language->get('error_zone');
        }

        if (!$json) {
            $this->session->data['payment_address']['country_id'] = $this->request->post['country_id'];
            $this->session->data['payment_address']['zone_id'] = $this->request->post['zone_id'];
            // Default Payment Address
            if (isset($this->request->post['shipping_address_same'])) {// checkbox `My delivery and billing addresses are the same.`
                $this->session->data['shipping_address_same'] = 1;
            } else {
                $this->session->data['shipping_address_same'] = 0;
            }

            if ($this->session->data['shipping_address_same']) {
                $this->session->data['shipping_address']['country_id'] = $this->request->post['country_id'];
                $this->session->data['shipping_address']['zone_id'] = $this->request->post['zone_id'];
            }

            //unset($this->session->data['payment_method']);//because of this will re-use when load payment_method in sub-loading.
            unset($this->session->data['payment_methods']);
        }
        $this->response->setOutput(json_encode($json));
    }

}

?>