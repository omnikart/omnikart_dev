<?php

class ControllerCheckoutGuestShippingOnepage extends Controller {

    public function render_index() {

        return $this->index(true);
    }

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

        $view_data['button_continue'] = $this->language->get('button_continue');
        $view_data['button_upload'] = $this->language->get('button_upload');
        $view_data['button_ok'] = 'OK';

        if (isset($this->session->data['guest']['shipping']['firstname'])) {
            $view_data['firstname'] = $this->session->data['guest']['shipping']['firstname'];
        } else {
            $view_data['firstname'] = '';
        }

        if (isset($this->session->data['guest']['shipping']['lastname'])) {
            $view_data['lastname'] = $this->session->data['guest']['shipping']['lastname'];
        } else {
            $view_data['lastname'] = '';
        }

        if (isset($this->session->data['guest']['shipping']['company'])) {
            $view_data['company'] = $this->session->data['guest']['shipping']['company'];
        } else {
            $view_data['company'] = '';
        }

        if (isset($this->session->data['guest']['shipping']['address_1'])) {
            $view_data['address_1'] = $this->session->data['guest']['shipping']['address_1'];
        } else {
            $view_data['address_1'] = '';
        }

        if (isset($this->session->data['guest']['shipping']['address_2'])) {
            $view_data['address_2'] = $this->session->data['guest']['shipping']['address_2'];
        } else {
            $view_data['address_2'] = '';
        }

        if (isset($this->session->data['guest']['shipping']['postcode'])) {
            $view_data['postcode'] = $this->session->data['guest']['shipping']['postcode'];
        } else {
            $view_data['postcode'] = '';
        }

        if (isset($this->session->data['guest']['shipping']['city'])) {
            $view_data['city'] = $this->session->data['guest']['shipping']['city'];
        } else {
            $view_data['city'] = '';
        }

        if (isset($this->session->data['guest']['shipping']['country_id'])) {
            $view_data['country_id'] = $this->session->data['guest']['shipping']['country_id'];
        } else {
            $view_data['country_id'] = '';
        }



        if (isset($this->session->data['guest']['shipping']['zone_id'])) {
            $view_data['zone_id'] = $this->session->data['guest']['shipping']['zone_id'];
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

        $view_data['custom_fields'] = $this->model_account_custom_field->getCustomFields($this->session->data['guest']['customer_group_id']);

        if (isset($this->session->data['shipping_address']['custom_field'])) {
            $view_data['address_custom_field'] = $this->session->data['shipping_address']['custom_field'];
        } else {
            $view_data['address_custom_field'] = array();
        }


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/guest_shipping_onepage.tpl')) {
            $template = $this->config->get('config_template') . '/template/checkout/guest_shipping_onepage.tpl';
        } else {
            $template = 'default/template/checkout/guest_shipping_onepage.tpl';
        }


        //var_dump($this->session->data['guest']['shipping']);
        if (isset($this->request->get['shipping_address_same'])) {
            if (!empty($this->request->get['shipping_address_same'])) {
                $this->session->data['guest']['shipping_address_same'] = true;
            } else {
                $this->session->data['guest']['shipping_address_same'] = false;
            }
        }

        if (!$render_content) {
            $this->response->setOutput($this->load->view($template, $view_data));
        } else {
            return $this->load->view($template, $view_data);
        }
    }

    public function validate() {
        $this->load->language('checkout/checkout');

        $json = array();
        ob_start();
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

            if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
                $json['error']['address_1'] = $this->language->get('error_address_1');
            }

            if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
                $json['error']['city'] = $this->language->get('error_city');
            }

            $this->load->model('localisation/country');

            $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

            if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
                $json['error']['postcode'] = $this->language->get('error_postcode');
            }

            if ($this->request->post['country_id'] == '') {
                $json['error']['country'] = $this->language->get('error_country');
            }

            if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
                $json['error']['zone'] = $this->language->get('error_zone');
            }

            $this->load->model('account/custom_field');

            $custom_fields = $this->model_account_custom_field->getCustomFields($this->session->data['guest']['customer_group_id']);

            foreach ($custom_fields as $custom_field) {
                if (($custom_field['location'] == 'address') && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
                    $json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
                }
            }
        }

        if (!$json) {
            $this->session->data['guest']['shipping']['firstname'] = trim($this->request->post['firstname']);
            $this->session->data['guest']['shipping']['lastname'] = trim($this->request->post['lastname']);
            $this->session->data['guest']['shipping']['company'] = trim($this->request->post['company']);
            $this->session->data['guest']['shipping']['address_1'] = $this->request->post['address_1'];
            $this->session->data['guest']['shipping']['address_2'] = $this->request->post['address_2'];
            $this->session->data['guest']['shipping']['postcode'] = $this->request->post['postcode'];
            $this->session->data['guest']['shipping']['city'] = $this->request->post['city'];
            $this->session->data['guest']['shipping']['country_id'] = $this->request->post['country_id'];
            $this->session->data['guest']['shipping']['zone_id'] = $this->request->post['zone_id'];

            $this->load->model('localisation/country');

            $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

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

            $this->load->model('localisation/zone');

            $zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);

            if ($zone_info) {
                $this->session->data['guest']['shipping']['zone'] = $zone_info['name'];
                $this->session->data['guest']['shipping']['zone_code'] = $zone_info['code'];
            } else {
                $this->session->data['guest']['shipping']['zone'] = '';
                $this->session->data['guest']['shipping']['zone_code'] = '';
            }

            $this->session->data['shipping_address'] = $this->session->data['guest']['shipping'];
            
            if (isset($this->request->post['custom_field'])) {
                $this->session->data['shipping_address']['custom_field'] = $this->request->post['custom_field'];
            } else {
                $this->session->data['shipping_address']['custom_field'] = array();
            }

            //unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
        }
        $this->session->data['account'] = 'guest';
        $this->session->data['account_view'] = 'guest';
        ob_end_clean();
        $this->response->setOutput(json_encode($json));
    }

    public function validate_zone() {
        ob_start();
        $this->load->language('checkout/checkout');

        $json = array();
        $this->load->model('localisation/country');
        $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

        if (!$country_info || $this->request->post['country_id'] == '') {
            $json['error']['country'] = $this->language->get('error_country');
            $json['redirect'] = $this->url->link('checkout/checkout_onepage', '', 'SSL');
        }

        if (!isset($this->request->post['zone_id'])) {// || $this->request->post['zone_id'] == '') {
            $json['error']['zone'] = $this->language->get('error_zone');
            $json['redirect'] = $this->url->link('checkout/checkout_onepage', '', 'SSL');
        }


        if (!$json) {
            $this->session->data['guest']['shipping']['country_id'] = $this->request->post['country_id'];
            $this->session->data['guest']['shipping']['zone_id'] = $this->request->post['zone_id'];

//            $this->load->model('localisation/country');
//
//            $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

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

            $this->load->model('localisation/zone');

            $zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);

            if ($zone_info) {
                $this->session->data['guest']['shipping']['zone'] = $zone_info['name'];
                $this->session->data['guest']['shipping']['zone_code'] = $zone_info['code'];
            } else {
                $this->session->data['guest']['shipping']['zone'] = '';
                $this->session->data['guest']['shipping']['zone_code'] = '';
            }
            $this->session->data['shipping_address'] = $this->session->data['guest']['shipping'];
            //unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
        }

        $this->session->data['account'] = 'guest';
        $this->session->data['account_view'] = 'guest';
        // for interchange between guest shipping method and register shipping method
        if (isset($this->session->data['checkout_guest']['shipping_method'])) {
            $this->session->data['shipping_method'] = $this->session->data['checkout_guest']['shipping_method'];
        }
        if (isset($this->session->data['checkout_guest']['payment_method'])) {
            $this->session->data['payment_method'] = $this->session->data['checkout_guest']['payment_method'];
        }
        ob_end_clean();
        $this->response->setOutput(json_encode($json));
    }

}

?>