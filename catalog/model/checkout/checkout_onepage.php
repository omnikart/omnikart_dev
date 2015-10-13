<?php

class ModelCheckoutCheckoutOnepage extends Model {

    public function unset_login_sessions() {
        //ensure unset all not logined sessions when have already logined
        if ($this->customer->isLogged()) {
            unset($this->session->data['account']);
            unset($this->session->data['guest']);
            unset($this->session->data['register']);
            unset($this->session->data['checkout_guest']);
            unset($this->session->data['checkout_register']);
//            unset();
        } else {
            unset($this->session->data['checkout_customer']);
        }
    }

    public function rebuid_content_text($text = '') {
        $texts = explode(':', $text);
        if (is_array($texts) && count($texts) > 1) {
            array_shift($texts);
            return trim(implode(' ', $texts));
        } else {
            return $text;
        }
    }

    public function hasCheckoutOnepage() {
        $this->load->model('setting/setting');
        $mmos_checkout_extra = $this->model_setting_setting->getSetting('mmos_checkout', $this->config->get('config_store_id'));
        $mmos_checkout = $mmos_checkout_extra['mmos_checkout'];
        if (!($mmos_checkout && $mmos_checkout['status'])) {
            return 0;
        }

        /* Validate cart has products and has stock. */
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            return -1;
        }
        return 1;
    }

    public function hasValidMinimumQtyRequirement() {
        /* Validate minimum quantity requirments. */
        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                /* $this->response->redirect($this->url->link('checkout/cart')); */
                return false;
            }
        }
        return true;
    }

    public function validate_checkout1() {
        if ($this->hasCheckoutOnepage() == 0) {
            $this->response->redirect($this->url->link('checkout/checkout'));
        } else if ($this->hasCheckoutOnepage() == -1) {
            $this->response->redirect($this->url->link('checkout/cart'));
        }

        if (!$this->hasValidMinimumQtyRequirement()) {
            $this->response->redirect($this->url->link('checkout/cart'));
        }
        return true;
    }

    public function validate_checkout() {
        $redirect = '';
        if ($this->hasCheckoutOnepage() == 0) {
            return $redirect = $this->url->link('checkout/checkout', '', 'SSL');
        } else if ($this->hasCheckoutOnepage() == -1) {
            return $redirect = $this->url->link('checkout/cart', '', 'SSL');
        }

        if (!$this->hasValidMinimumQtyRequirement()) {
            return $redirect = $this->url->link('checkout/cart', '', 'SSL');
        }
        return '';
    }

    public function calculate_shipping($setting, $shipping) {
        if ($setting['calculate_non_address_shipping'] == '1') {
            if (isset($setting['calculate_shipping_country_id']) && empty($shipping['country_id'])) {
                return true;
            }
            if (isset($setting['calculate_shipping_postcode']) && empty($shipping['postcode'])) {
                return true;
            }
            if (isset($setting['calculate_shipping_zone_id']) && empty($shipping['zone_id'])) {
                return true;
            }
            if (isset($setting['calculate_shipping_city']) && empty($shipping['city'])) {
                return true;
            }
        }
        return false;
    }

    public function calculate_payment($setting, $payment) {
        if ($setting['calculate_non_address_payment'] == '1') {
            if (isset($setting['calculate_payment_country_id']) && empty($payment['country_id'])) {
                return true;
            }
            if (isset($setting['calculate_payment_postcode']) && empty($payment['postcode'])) {
                return true;
            }
            if (isset($setting['calculate_payment_zone_id']) && empty($payment['zone_id'])) {
                return true;
            }
            if (isset($setting['calculate_payment_city']) && empty($payment['city'])) {
                return true;
            }
        }
        return false;
    }

}

?>
