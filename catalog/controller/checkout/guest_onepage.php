<?php

class ControllerCheckoutGuestOnepage extends Controller {

    public function index($render_content = false) {
        $this->load->model('setting/setting');
        $mmos_checkout_extra = $this->model_setting_setting->getSetting('mmos_checkout', $this->config->get('config_store_id'));
        $mmos_checkout = $mmos_checkout_extra['mmos_checkout'];
        $view_data['css'] = $mmos_checkout_extra['mmos_checkout_css'];
        $view_data['themes'] = $this->config->get('mmos_checkout_themes');
        if (empty($css['checkout_theme']) || !in_array($css['checkout_theme'], $themes)) {
            $css['checkout_theme'] = 'standar';
        }

        $quick_checkout = 0;
        if ($mmos_checkout['quick_guest_checkout'] && !$this->cart->hasShipping()) {
            $quick_checkout = 1;
        }

        $view_data['quick_checkout'] = $quick_checkout;
        $view_data['use_address'] = !empty($this->session->data['guest']['use_address']) ? 1 : 0;
        if (!empty($mmos_checkout['guest_telephone_require'])) {
            $view_data['guest_telephone_require'] = 1;
        }
        if (!empty($mmos_checkout['guest_telephone_tax_display'])) {
            $view_data['guest_telephone_tax_display'] = 1;
        }

        //for trigger when collapse to quick checkout mode
        $view_data['config_country_id'] = $this->config->get('config_country_id');
        $view_data['config_zone_id'] = $this->config->get('config_zone_id');



        // <editor-fold defaultstate="collapsed" desc="LANGUAGES">
        $this->load->language('checkout/checkout');

        $view_data['text_select'] = $this->language->get('text_select');
        $view_data['text_none'] = $this->language->get('text_none');
        $view_data['text_your_details'] = $this->language->get('text_your_details');
        $view_data['text_your_account'] = $this->language->get('text_your_account');
        $view_data['text_your_address'] = $this->language->get('text_your_address');
        $view_data['text_loading'] = $this->language->get('text_loading');

        $view_data['entry_firstname'] = $this->language->get('entry_firstname');
        $view_data['entry_lastname'] = $this->language->get('entry_lastname');
        $view_data['entry_email'] = $this->language->get('entry_email');
        $view_data['entry_telephone'] = $this->language->get('entry_telephone');
        $view_data['entry_fax'] = $this->language->get('entry_fax');
        $view_data['entry_company'] = $this->language->get('entry_company');
        $view_data['entry_customer_group'] = $this->language->get('entry_customer_group');
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
        // <editor-fold defaultstate="collapsed" desc="comment">

        if (isset($this->session->data['guest']['firstname'])) {
            $view_data['firstname'] = $this->session->data['guest']['firstname'];
        } else {
            $view_data['firstname'] = '';
        }

        if (isset($this->session->data['guest']['lastname'])) {
            $view_data['lastname'] = $this->session->data['guest']['lastname'];
        } else {
            $view_data['lastname'] = '';
        }

        if (isset($this->session->data['guest']['email'])) {
            $view_data['email'] = $this->session->data['guest']['email'];
        } else {
            $view_data['email'] = '';
        }

        if (isset($this->session->data['guest']['telephone'])) {
            $view_data['telephone'] = $this->session->data['guest']['telephone'];
        } else {
            $view_data['telephone'] = '';
        }

        if (isset($this->session->data['guest']['fax'])) {
            $view_data['fax'] = $this->session->data['guest']['fax'];
        } else {
            $view_data['fax'] = '';
        }

        if (isset($this->session->data['guest']['payment']['company'])) {
            $view_data['company'] = $this->session->data['guest']['payment']['company'];
        } else {
            $view_data['company'] = '';
        }

        $this->load->model('account/customer_group');

        $view_data['customer_groups'] = array();

        if (is_array($this->config->get('config_customer_group_display'))) {
            $customer_groups = $this->model_account_customer_group->getCustomerGroups();

            foreach ($customer_groups as $customer_group) {
                if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
                    $view_data['customer_groups'][] = $customer_group;
                }
            }
        }

        if (isset($this->session->data['guest']['customer_group_id'])) {
            $view_data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
        } else if ($mmos_checkout['default_customer_group'] != -1) {
            $view_data['customer_group_id'] = $mmos_checkout['default_customer_group'];
        } else {
            $view_data['customer_group_id'] = $this->config->get('config_customer_group_id');
        }
        $this->session->data['guest']['customer_group_id'] = $view_data['customer_group_id'];

        if (!$mmos_checkout['customer_group_style']) {
            $view_data['customer_group_style'] = 0;
        } else {
            $view_data['customer_group_style'] = 1;
        }


        if (isset($this->session->data['guest']['payment']['address_1'])) {
            $view_data['address_1'] = $this->session->data['guest']['payment']['address_1'];
        } else {
            $view_data['address_1'] = '';
        }

        if (isset($this->session->data['guest']['payment']['address_2'])) {
            $view_data['address_2'] = $this->session->data['guest']['payment']['address_2'];
        } else {
            $view_data['address_2'] = '';
        }

        if (isset($this->session->data['guest']['payment']['postcode'])) {
            $view_data['postcode'] = $this->session->data['guest']['payment']['postcode'];
        } else {
            $view_data['postcode'] = '';
        }

        if (isset($this->session->data['guest']['payment']['city'])) {
            $view_data['city'] = $this->session->data['guest']['payment']['city'];
        } else {
            $view_data['city'] = '';
        }// </editor-fold>


        if (isset($this->session->data['guest']['payment']['country_id'])) {
            $view_data['country_id'] = $this->session->data['guest']['payment']['country_id'];
        } else {
            $view_data['country_id'] = '';
        }
        // var_dump($this->config->get('config_country_id'));

        if (isset($this->session->data['guest']['payment']['zone_id'])) {
            $view_data['zone_id'] = $this->session->data['guest']['payment']['zone_id'];
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

        // Custom Fields
        $this->load->model('account/custom_field');

        $view_data['custom_fields'] = $this->model_account_custom_field->getCustomFields();

        if (isset($this->session->data['guest']['custom_field'])) {
            if (isset($this->session->data['guest']['custom_field'])) {
                $guest_custom_field = $this->session->data['guest']['custom_field'];
            } else {
                $guest_custom_field = array();
            }

            if (isset($this->session->data['payment_address']['custom_field'])) {
                $address_custom_field = $this->session->data['payment_address']['custom_field'];
            } else {
                $address_custom_field = array();
            }

            $view_data['guest_custom_field'] = $guest_custom_field + $address_custom_field;
        } else {
            $view_data['guest_custom_field'] = array();
        }

        $view_data['shipping_required'] = $this->cart->hasShipping();

        //var_dump($this->session->data['guest']['shipping_address_same']);
        if (isset($this->session->data['guest']['shipping_address_same'])) {
            $view_data['shipping_address_same'] = $this->session->data['guest']['shipping_address_same'];
        }


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/guest_onepage.tpl')) {
            $template = $this->config->get('config_template') . '/template/checkout/guest_onepage.tpl';
        } else {
            $template = 'default/template/checkout/guest_onepage.tpl';
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
        ob_start();
        $this->load->model('setting/setting');
        $mmos_checkout_extra = $this->model_setting_setting->getSetting('mmos_checkout', $this->config->get('config_store_id'));
        $mmos_checkout = $mmos_checkout_extra['mmos_checkout'];
        if (!($mmos_checkout && $mmos_checkout['status'])) {
            $this->response->redirect($this->url->link('checkout/checkout'));
        }

        $quick_checkout = 0;
        if ($mmos_checkout['quick_guest_checkout'] && !$this->cart->hasShipping()) {
            $quick_checkout = 1;
        }


        $this->load->language('checkout/checkout');

        $json = array();

        // Validate if customer is logged in.
        if ($this->customer->isLogged()) {
            $json['redirect'] = $this->url->link('checkout/checkout_onepage', '', 'SSL');
        }

        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $json['redirect'] = $this->url->link('checkout/cart');
        }

        // Check if guest checkout is avaliable.			
        if (!$this->config->get('config_checkout_guest') || $this->config->get('config_customer_price') || $this->cart->hasDownload()) {
            $json['redirect'] = $this->url->link('checkout/checkout_onepage', '', 'SSL');
        }

        if (!$json) {
            if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
                $json['error']['firstname'] = $this->language->get('error_firstname');
            }

            if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
                $json['error']['lastname'] = $this->language->get('error_lastname');
            }

            if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
                $json['error']['email'] = $this->language->get('error_email');
            }

            if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
                $json['error']['telephone'] = $this->language->get('error_telephone');
            }

            ## YOUR ADDRESS SECTION
            if (!$quick_checkout || isset($this->request->post['use-address'])) {
                // Customer Group
                $this->load->model('account/customer_group');

                if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
                    $customer_group_id = $this->request->post['customer_group_id'];
                } else {
                    $customer_group_id = $this->config->get('config_customer_group_id');
                }

                $customer_group = $this->model_account_customer_group->getCustomerGroup($customer_group_id);


                if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
                    $json['error']['address_1'] = $this->language->get('error_address_1');
                }

                if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
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

                $custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

                foreach ($custom_fields as $custom_field) {
                    if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
                        $json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
                    }
                }
            } else {
                $customer_group_id = -1;
                $this->request->post['company'] = '';

                $this->request->post['address_1'] = 'guest_default_address';
                $this->request->post['address_2'] = '';
                $this->request->post['postcode'] = '';
                $this->request->post['city'] = '';
                $this->request->post['country_id'] = $this->config->get('config_country_id');
                $this->request->post['zone_id'] = $this->config->get('config_zone_id');
            }
        }

        if (!$json) {
            $this->session->data['guest']['customer_group_id'] = $customer_group_id;
            $this->session->data['guest']['firstname'] = $this->request->post['firstname'];
            $this->session->data['guest']['lastname'] = $this->request->post['lastname'];
            $this->session->data['guest']['email'] = $this->request->post['email'];
            $this->session->data['guest']['telephone'] = $this->request->post['telephone'];
            $this->session->data['guest']['fax'] = $this->request->post['fax'];

            if (isset($this->request->post['custom_field']['account'])) {
                $this->session->data['guest']['custom_field'] = $this->request->post['custom_field']['account'];
            } else {
                $this->session->data['guest']['custom_field'] = array();
            }

            $this->session->data['guest']['payment']['firstname'] = $this->request->post['firstname'];
            $this->session->data['guest']['payment']['lastname'] = $this->request->post['lastname'];

            $this->session->data['guest']['payment']['company'] = $this->request->post['company'];

            $this->session->data['guest']['payment']['address_1'] = $this->request->post['address_1'];
            $this->session->data['guest']['payment']['address_2'] = $this->request->post['address_2'];
            $this->session->data['guest']['payment']['postcode'] = $this->request->post['postcode'];
            $this->session->data['guest']['payment']['city'] = $this->request->post['city'];
            $this->session->data['guest']['payment']['country_id'] = $this->request->post['country_id'];
            $this->session->data['guest']['payment']['zone_id'] = $this->request->post['zone_id'];

            $this->load->model('localisation/country');

            $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

            if ($country_info) {
                $this->session->data['guest']['payment']['country'] = $country_info['name'];
                $this->session->data['guest']['payment']['iso_code_2'] = $country_info['iso_code_2'];
                $this->session->data['guest']['payment']['iso_code_3'] = $country_info['iso_code_3'];
                $this->session->data['guest']['payment']['address_format'] = $country_info['address_format'];
            } else {
                $this->session->data['guest']['payment']['country'] = '';
                $this->session->data['guest']['payment']['iso_code_2'] = '';
                $this->session->data['guest']['payment']['iso_code_3'] = '';
                $this->session->data['guest']['payment']['address_format'] = '';
            }

            $this->load->model('localisation/zone');

            $zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);

            if ($zone_info) {
                $this->session->data['guest']['payment']['zone'] = $zone_info['name'];
                $this->session->data['guest']['payment']['zone_code'] = $zone_info['code'];
            } else {
                $this->session->data['guest']['payment']['zone'] = '';
                $this->session->data['guest']['payment']['zone_code'] = '';
            }

            if (!empty($this->request->post['shipping_address_same'])) {// checkbox `My delivery and billing addresses are the same.`
                $this->session->data['guest']['shipping_address_same'] = true;
            } else {
                $this->session->data['guest']['shipping_address_same'] = false;
            }
            // Default Payment Address
            $this->session->data['payment_address'] = $this->session->data['guest']['payment'];
            
            if (isset($this->request->post['custom_field']['address'])) {
                $this->session->data['payment_address']['custom_field'] = $this->request->post['custom_field']['address'];
            } else {
                $this->session->data['payment_address']['custom_field'] = array();
            }


            if ($this->cart->hasShipping() && $this->session->data['guest']['shipping_address_same']) {
                $this->session->data['guest']['shipping']['firstname'] = $this->request->post['firstname'];
                $this->session->data['guest']['shipping']['lastname'] = $this->request->post['lastname'];
                $this->session->data['guest']['shipping']['company'] = $this->request->post['company'];
                $this->session->data['guest']['shipping']['address_1'] = $this->request->post['address_1'];
                $this->session->data['guest']['shipping']['address_2'] = $this->request->post['address_2'];
                $this->session->data['guest']['shipping']['postcode'] = $this->request->post['postcode'];
                $this->session->data['guest']['shipping']['city'] = $this->request->post['city'];
                $this->session->data['guest']['shipping']['country_id'] = $this->request->post['country_id'];
                $this->session->data['guest']['shipping']['zone_id'] = $this->request->post['zone_id'];

                if ($country_info) {
                    $this->session->data['guest']['shipping']['country'] = $country_info['name'];
                    $this->session->data['guest']['shipping']['iso_code_2'] = $country_info['iso_code_2'];
                    $this->session->data['guest']['shipping']['iso_code_3'] = $country_info['iso_code_3'];
                    $this->session->data['guest']['shipping']['address_format'] = $country_info['address_format'];
                } else {
                    $this->session->data['guest']['shipping']['country'] = '';
                    $this->session->data['guest']['shipping']['iso_code_2'] = '';
                    $this->session->data['guest']['shipping']['iso_code_3'] = '';
                    $this->session->data['guest']['shipping']['address_format'] = '';
                }

                if ($zone_info) {
                    $this->session->data['guest']['shipping']['zone'] = $zone_info['name'];
                    $this->session->data['guest']['shipping']['zone_code'] = $zone_info['code'];
                } else {
                    $this->session->data['guest']['shipping']['zone'] = '';
                    $this->session->data['guest']['shipping']['zone_code'] = '';
                }

                $this->session->data['shipping_address'] = $this->session->data['guest']['shipping'];
                
                if (isset($this->request->post['custom_field']['address'])) {
                    $this->session->data['shipping_address']['custom_field'] = $this->request->post['custom_field']['address'];
                } else {
                    $this->session->data['shipping_address']['custom_field'] = array();
                }

                
                unset($this->session->data['shipping_methods']);
            }
            if (!$this->cart->hasShipping()) {
                unset($this->session->data['shipping_address']);
            }


            //unset($this->session->data['shipping_method']);
            //unset($this->session->data['shipping_methods']);
            //unset($this->session->data['payment_method']);    
            unset($this->session->data['payment_methods']);
        }
        $this->session->data['account'] = 'guest';
        $this->session->data['account_view'] = 'guest';
        ob_clean();
        $this->response->setOutput(json_encode($json));
    }

    public function validate_zone() {//for zone and country
        $this->load->language('checkout/checkout');
        $json = array();
        $this->load->model('localisation/country');
        $this->load->model('setting/setting');
        $mmos_checkout_extra = $this->model_setting_setting->getSetting('mmos_checkout', $this->config->get('config_store_id'));
        $mmos_checkout = $mmos_checkout_extra['mmos_checkout'];
        $quick_checkout = 0;
        if ($mmos_checkout['quick_guest_checkout'] && !$this->cart->hasShipping()) {
            $quick_checkout = 1;
        }

        // ob_start();
        //OpenCart may not check for the country not exist
        $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

        if (!$country_info || $this->request->post['country_id'] == '') {
            $json['error']['country'] = $this->language->get('error_country');
            $json['redirect'] = $this->url->link('checkout/checkout_onepage', '', 'SSL');
        }

        if (!isset($this->request->post['zone_id'])) {// || $this->request->post['zone_id'] == '') {
            $json['error']['zone'] = $this->language->get('error_zone');
            $json['redirect'] = $this->url->link('checkout/checkout_onepage', '', 'SSL');
        }
        if ($quick_checkout) {
            if (isset($this->request->post['use-address'])) {
                $this->session->data['guest']['use_address'] = 1;
            } else {
                $this->session->data['guest']['use_address'] = 0;
            }
        }

        if (!$json) {
            $this->session->data['guest']['payment']['country_id'] = $this->request->post['country_id'];
            $this->session->data['guest']['payment']['zone_id'] = $this->request->post['zone_id'];


            if ($country_info) {
                $this->session->data['guest']['payment']['country'] = $country_info['name'];
                $this->session->data['guest']['payment']['iso_code_2'] = $country_info['iso_code_2'];
                $this->session->data['guest']['payment']['iso_code_3'] = $country_info['iso_code_3'];
                $this->session->data['guest']['payment']['address_format'] = $country_info['address_format'];
            } else {
                $this->session->data['guest']['payment']['country'] = '';
                $this->session->data['guest']['payment']['iso_code_2'] = '';
                $this->session->data['guest']['payment']['iso_code_3'] = '';
                $this->session->data['guest']['payment']['address_format'] = '';
            }

            $this->load->model('localisation/zone');

            $zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);

            if ($zone_info) {
                $this->session->data['guest']['payment']['zone'] = $zone_info['name'];
                $this->session->data['guest']['payment']['zone_code'] = $zone_info['code'];
            } else {
                $this->session->data['guest']['payment']['zone'] = '';
                $this->session->data['guest']['payment']['zone_code'] = '';
            }
            // Default Payment Address
            $this->session->data['payment_address'] = $this->session->data['guest']['payment'];
            if (!empty($this->request->post['shipping_address_same'])) {// checkbox `My delivery and billing addresses are the same.`
                $this->session->data['guest']['shipping_address_same'] = 1;
            } else {
                $this->session->data['guest']['shipping_address_same'] = 0;
            }


            if ($this->cart->hasShipping() && $this->session->data['guest']['shipping_address_same']) {
                $this->session->data['guest']['shipping']['country_id'] = $this->request->post['country_id'];
                $this->session->data['guest']['shipping']['zone_id'] = $this->request->post['zone_id'];

                if ($country_info) {
                    $this->session->data['guest']['shipping']['country'] = $country_info['name'];
                    $this->session->data['guest']['shipping']['iso_code_2'] = $country_info['iso_code_2'];
                    $this->session->data['guest']['shipping']['iso_code_3'] = $country_info['iso_code_3'];
                    $this->session->data['guest']['shipping']['address_format'] = $country_info['address_format'];
                } else {
                    $this->session->data['guest']['shipping']['country'] = '';
                    $this->session->data['guest']['shipping']['iso_code_2'] = '';
                    $this->session->data['guest']['shipping']['iso_code_3'] = '';
                    $this->session->data['guest']['shipping']['address_format'] = '';
                }

                if ($zone_info) {
                    $this->session->data['guest']['shipping']['zone'] = $zone_info['name'];
                    $this->session->data['guest']['shipping']['zone_code'] = $zone_info['code'];
                } else {
                    $this->session->data['guest']['shipping']['zone'] = '';
                    $this->session->data['guest']['shipping']['zone_code'] = '';
                }

                $this->session->data['shipping_address'] = $this->session->data['guest']['shipping'];
            }
            if (!$this->cart->hasShipping()) {
                unset($this->session->data['shipping_address']);
            }
        }

        $this->session->data['account'] = 'guest';
        $this->session->data['account_view'] = 'guest';

        if (isset($this->session->data['checkout_guest']['shipping_method'])) {
            $this->session->data['shipping_method'] = $this->session->data['checkout_guest']['shipping_method'];
        }
        if (isset($this->session->data['checkout_guest']['payment_method'])) {
            $this->session->data['payment_method'] = $this->session->data['checkout_guest']['payment_method'];
        }
        // file_put_contents('fuckfuck', ob_get_contents());
        //ob_end_clean();
        $this->response->setOutput(json_encode($json));
    }

    public function zone() {
        $output = '<option value="">' . $this->language->get('text_select') . '</option>';

        $this->load->model('localisation/zone');

        $results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);

        foreach ($results as $result) {
            $output .= '<option value="' . $result['zone_id'] . '"';

            if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
                $output .= ' selected="selected"';
            }

            $output .= '>' . $result['name'] . '</option>';
        }

        if (!$results) {
            $output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
        }

        $this->response->setOutput($output);
    }

}

?>