<?php

class ControllerCheckoutCheckoutOnepageRightContent extends Controller {

    public function index($render_content = false) {
        $this->load->model('checkout/checkout_onepage');
        $this->load->model('setting/setting');
        $mmos_checkout_extra = $this->model_setting_setting->getSetting('mmos_checkout', $this->config->get('config_store_id'));
        $view_data['mmos_checkout'] = $mmos_checkout_extra['mmos_checkout'];
        // <editor-fold defaultstate="collapsed" desc="LANGUAGES">
        $this->load->language('checkout/checkout');
        $view_data['text_checkout_shipping_method'] = $this->model_checkout_checkout_onepage->rebuid_content_text($this->language->get('text_checkout_shipping_method'));
        $view_data['text_checkout_payment_method'] = $this->model_checkout_checkout_onepage->rebuid_content_text($this->language->get('text_checkout_payment_method'));
        $view_data['text_checkout_confirm'] = $this->model_checkout_checkout_onepage->rebuid_content_text($this->language->get('text_checkout_confirm'));
        $view_data['text_modify'] = $this->language->get('text_modify');
        $view_data['text_cart'] = $this->language->get('text_cart');

        $this->load->language('checkout/voucher');

        $view_data['entry_voucher'] = $this->language->get('entry_voucher');
        
        $view_data['entry_heading_voucher'] = $this->language->get('heading_title');
         
        $this->load->language('checkout/coupon');
        $view_data['entry_coupon'] = $this->language->get('entry_coupon');

        $view_data['button_voucher'] = $this->language->get('button_voucher');
        $view_data['button_coupon'] = $this->language->get('button_coupon');
        $view_data['entry_heading_coupon'] = $this->language->get('heading_title');
        

        // </editor-fold>


        $view_data['logged'] = $this->customer->isLogged();
        $view_data['shipping_required'] = $this->cart->hasShipping();
        if (!$this->customer->isLogged()) {
            $view_data['account'] = $this->session->data['account'];
        }

        $points = $this->customer->getRewardPoints();

        $points_total = 0;

        foreach ($this->cart->getProducts() as $product) {
            if ($product['points']) {
                $points_total += $product['points'];
            }
        }

        if ($points && $points_total && $this->config->get('reward_status')) {
            $this->load->language('checkout/reward');

            $view_data['heading_title'] = sprintf($this->language->get('heading_title'), $points);

            $view_data['entry_reward'] = sprintf($this->language->get('entry_reward'), $points_total);

            $view_data['button_reward'] = $this->language->get('button_reward');

            $view_data['show_reward'] = '1';
        } else {
            $view_data['show_reward'] = '0';
        }

        // <editor-fold defaultstate="collapsed" desc="MODULE THEMES, STYLESHEETS & LANGUAGES">
        $this->load->model('setting/setting');

        $mmos_checkout_extra = $this->model_setting_setting->getSetting('mmos_checkout', $this->config->get('config_store_id'));

        $view_data['tips'] = $tips = $mmos_checkout_extra['mmos_checkout_tips'];
        $view_data['css'] = $mmos_checkout_extra['mmos_checkout_css'];
        $view_data['themes'] = $this->config->get('mmos_checkout_themes');
        if (empty($css['checkout_theme']) || !in_array($css['checkout_theme'], $themes)) {
            $css['checkout_theme'] = 'standar';
        }
        $view_data['config_language_id'] = $this->config->get('config_language_id'); // </editor-fold>
        // <editor-fold defaultstate="collapsed" desc="ADD SUB-SECTION-PAGES">
        if ($this->cart->hasShipping()) {
            $view_data['shippingMethod'] = $this->load->controller('checkout/shipping_method_onepage/render_index');
        }

        $view_data['paymentMethod'] = $this->load->controller('checkout/payment_method_onepage/render_index');

        $view_data['orderSummary'] = $this->load->controller('checkout/confirm_onepage/render_index');

        // </editor-fold>


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/checkout_onepage_right_content.tpl')) {
            $template = $this->config->get('config_template') . '/template/checkout/checkout_onepage_right_content.tpl';
        } else {
            $template = 'default/template/checkout/checkout_onepage_right_content.tpl';
        }

        //OUTPUT
        if (!$render_content) {
            $this->response->setOutput($this->load->view($template, $view_data));
        } else {
            return $this->load->view($template, $view_data);
        }
    }

    public function render_index() {

        return $this->index(true);
    }

}

?>