<?php

class ControllerCheckoutCheckoutOnepageLeftContent extends Controller {

    public function index($render_content = false) {
        $this->load->model('checkout/checkout_onepage');
        // <editor-fold defaultstate="collapsed" desc="LANGUAGES">
        $this->load->language('checkout/checkout');

//        $view_data['text_button_checkout_confirm'] = $this->model_checkout_checkout_onepage->rebuid_content_text($this->language->get('text_checkout_confirm'));
        $view_data['text_checkout_shipping_method'] = $this->model_checkout_checkout_onepage->rebuid_content_text($this->language->get('text_checkout_shipping_method'));
        $view_data['text_checkout_payment_method'] = $this->model_checkout_checkout_onepage->rebuid_content_text($this->language->get('text_checkout_payment_method'));
        $view_data['text_checkout_confirm'] = $this->model_checkout_checkout_onepage->rebuid_content_text($this->language->get('text_checkout_confirm'));
        $view_data['text_modify'] = $this->language->get('text_modify');
        $view_data['text_checkout_payment_address'] = $this->model_checkout_checkout_onepage->rebuid_content_text($this->language->get('text_checkout_payment_address'));
        $view_data['text_checkout_shipping_address'] = $this->model_checkout_checkout_onepage->rebuid_content_text($this->language->get('text_checkout_shipping_address'));

        ##CHECOUT OPTIONS LANGUAGE
        $view_data['text_new_customer'] = $this->language->get('text_new_customer');
        $view_data['text_returning_customer'] = $this->language->get('text_returning_customer');
        $view_data['text_checkout'] = $this->language->get('text_checkout');
        $view_data['text_register'] = $this->language->get('text_register');
        $view_data['text_guest'] = $this->language->get('text_guest');
        $view_data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
        $view_data['text_register_account'] = $this->language->get('text_register_account');
        $view_data['text_forgotten'] = $this->language->get('text_forgotten');

        $view_data['entry_email'] = $this->language->get('entry_email');
        $view_data['entry_password'] = $this->language->get('entry_password');

        $view_data['button_continue'] = $this->language->get('button_continue');
        $view_data['button_login'] = $this->language->get('button_login');

        $view_data['text_checkout_account'] = $this->model_checkout_checkout_onepage->rebuid_content_text($this->language->get('text_checkout_account'));
        $view_data['text_checkout_option'] = $this->language->get('text_checkout_option');

        // </editor-fold>


        $view_data['logged'] = $this->customer->isLogged();
        $view_data['shipping_required'] = $this->cart->hasShipping();


        // <editor-fold defaultstate="collapsed" desc="MODULE THEMES, STYLESHEETS & LANGUAGES">
        $this->load->model('setting/setting');

        $mmos_checkout_extra = $this->model_setting_setting->getSetting('mmos_checkout', $this->config->get('config_store_id'));
        $mmos_checkout = $tips = $mmos_checkout_extra['mmos_checkout'];
        $view_data['tips'] = $tips = $mmos_checkout_extra['mmos_checkout_tips'];
        $view_data['css'] = $mmos_checkout_extra['mmos_checkout_css'];
        $view_data['themes'] = $this->config->get('mmos_checkout_themes');
        if (empty($css['checkout_theme']) || !in_array($css['checkout_theme'], $themes)) {
            $css['checkout_theme'] = 'standar';
        }
        $view_data['config_language_id'] = $this->config->get('config_language_id'); // </editor-fold>

        $view_data['guest_checkout'] = $guest_checkout = ($this->config->get('config_checkout_guest') && !$this->config->get('config_customer_price') && !$this->cart->hasDownload());

        //refix account that use to estimate shipping/payment, and account that use to show checked.
        if (!$this->customer->isLogged()) {
            $account = 'register';
            //unset($this->session->data['account']);
            if (isset($this->session->data['account'])) {
                $account = $this->session->data['account'];
            } else {
                $account = $mmos_checkout['default_role'];
            }
            //validate account name
//            if (!in_array($acc, array('returning-customer', 'register', 'guest')) || (!$guest_checkout && $acc == 'guest')) {
            if (!in_array($account, array('register', 'guest')) || (!$guest_checkout && $account == 'guest')) {
                $account = 'register';
            }
            $this->session->data['account'] = $account; //either 'guest' or 'register'
            
            if ($account == 'guest') {
                if (isset($this->session->data['checkout_guest']['shipping_method'])) {
                    $this->session->data['shipping_method'] = $this->session->data['checkout_guest']['shipping_method'];
                }
                if (isset($this->session->data['checkout_guest']['payment_method'])) {
                    $this->session->data['payment_method'] = $this->session->data['checkout_guest']['payment_method'];
                }
            } else if ($account == 'register') {
                if (isset($this->session->data['checkout_register']['shipping_method'])) {
                    $this->session->data['shipping_method'] = $this->session->data['checkout_register']['shipping_method'];
                }
                if (isset($this->session->data['checkout_register']['payment_method'])) {
                    $this->session->data['payment_method'] = $this->session->data['checkout_register']['payment_method'];
                }
            }

            #account_view
            if (isset($this->session->data['account_view'])) {
                $account_view = $this->session->data['account_view'];
            } else {
                $account_view = $mmos_checkout['default_role'];
            }
            //ensure $account_view must be in specified account_views('returning-customer', 'register', 'guest')
            if(!in_array($account_view, array('returning-customer', 'register', 'guest'))){
                $account_view='register';
            }
            //ensure when $account_view!='returning-customer', $account_view & $account are equal.
            if($account_view!='returning-customer'){
                $account_view=$account;
            }
            $this->session->data['account_view']=$account_view;
            
            $account_view_title = '';
            if ($account_view == 'guest') {
                $account_view_title = $view_data['text_checkout_payment_address'];
            } else if ($account_view == 'register') {
                $account_view_title = $view_data['text_checkout_account'];
            } else if ($account_view == 'returning-customer') {
                $account_view_title = $view_data['text_returning_customer'];
            }
            
            
            $view_data['account'] = $account;
            $view_data['account_view'] = $account_view;
            $view_data['account_view_title'] = $account_view_title;
           
        }

        //prefix session: individual and global session
        if (!$this->customer->isLogged()) {
            //shipping address the same billing address?
            if (isset($this->session->data['guest']['shipping_address_same'])) {
                $shipping_address_same = $this->session->data['guest']['shipping_address_same'];
            } else {
                $shipping_address_same = 1;
            }
            $this->session->data['guest']['shipping_address_same'] = $shipping_address_same;
            $view_data['shipping_address_same'] = $shipping_address_same;

            if ($account == 'guest') {
                $quick_checkout = 0;
                if ($mmos_checkout['quick_guest_checkout'] && !$this->cart->hasShipping()) {
                    $quick_checkout = 1;
                }
                if (!isset($this->session->data['guest']['use_address'])) {
                    $this->session->data['guest']['use_address'] = 0;
                } else {
                    $this->session->data['guest']['use_address'] = $this->session->data['guest']['use_address'] ? 1 : 0;
                }

                //payment country
                if ((($quick_checkout && $this->session->data['guest']['use_address']) || !$quick_checkout) && isset($this->session->data['guest']['payment']['country_id'])) {//($quick_checkout&&!$quick_checkout)
                    $this->session->data['payment_address']['country_id'] = $this->session->data['guest']['payment']['country_id'];
                } else {//!$quick_checkout || $this->session->data['guest']['use_address']==0 || !isset($this->session->data['guest']['payment']['country_id'])
                    $this->session->data['payment_address']['country_id'] = $this->config->get('config_country_id');
                }
                $this->session->data['guest']['payment']['country_id'] = $this->session->data['payment_address']['country_id'];
                //payment zone
                if ((($quick_checkout && $this->session->data['guest']['use_address']) || !$quick_checkout) && isset($this->session->data['guest']['payment']['zone_id'])) {
                    $this->session->data['payment_address']['zone_id'] = $this->session->data['guest']['payment']['zone_id'];
                } else {
                    $this->session->data['payment_address']['zone_id'] = $this->config->get('config_zone_id');
                }
                $this->session->data['guest']['payment']['zone_id'] = $this->session->data['payment_address']['zone_id'];
                //payment postcode
                if ((($quick_checkout && $this->session->data['guest']['use_address']) || !$quick_checkout) && isset($this->session->data['guest']['payment']['postcode'])) {
                    $this->session->data['payment_address']['postcode'] = $this->session->data['guest']['payment']['postcode'];
                } else {
                    $this->session->data['payment_address']['postcode'] = '';
                }
                $this->session->data['guest']['payment']['postcode'] = $this->session->data['payment_address']['postcode'];

                #default shipping address
                if ($this->cart->hasShipping()) {
                    if ($shipping_address_same) {
                        $this->session->data['shipping_address']['country_id'] = $this->session->data['payment_address']['country_id'];
                        $this->session->data['shipping_address']['zone_id'] = $this->session->data['payment_address']['zone_id'];
                        $this->session->data['shipping_address']['postcode'] = $this->session->data['payment_address']['postcode'];
                        //also set for invidual guest shipping data
                        $this->session->data['guest']['shipping']['country_id'] = $this->session->data['shipping_address']['country_id'];
                        $this->session->data['guest']['shipping']['zone_id'] = $this->session->data['shipping_address']['zone_id'];
                        $this->session->data['guest']['shipping']['postcode'] = $this->session->data['shipping_address']['postcode'];
                    } else {
                        //shipping country
                        if (isset($this->session->data['guest']['shipping']['country_id'])) {
                            $this->session->data['shipping_address']['country_id'] = $this->session->data['guest']['shipping']['country_id'];
                        } else {
                            $this->session->data['shipping_address']['country_id'] = $this->config->get('config_country_id');
                        }
                        $this->session->data['guest']['shipping']['country_id'] = $this->session->data['shipping_address']['country_id'];
                        //shipping zone
                        if (isset($this->session->data['guest']['shipping']['zone_id'])) {
                            $this->session->data['shipping_address']['zone_id'] = $this->session->data['guest']['shipping']['zone_id'];
                        } else {
                            $this->session->data['shipping_address']['zone_id'] = $this->config->get('config_zone_id');
                        }
                        $this->session->data['guest']['shipping']['zone_id'] = $this->session->data['shipping_address']['zone_id'];
                        //shipping postcode
                        if (isset($this->session->data['guest']['shipping']['postcode'])) {
                            $this->session->data['shipping_address']['postcode'] = $this->session->data['guest']['shipping']['postcode'];
                        } else {
                            $this->session->data['shipping_address']['postcode'] = '';
                        }
                        $this->session->data['guest']['shipping']['postcode'] = $this->session->data['shipping_address']['postcode'];
                    }
                }
            } else if ($account == 'register') {
                if ($mmos_checkout['quick_register'] && !$this->config->get('config_vat') && !$this->cart->hasShipping()) {
                    $quick_register = 1;
                } else {
                    $quick_register = 0;
                }
                if (!isset($this->session->data['register']['use_address'])) {
                    $this->session->data['register']['use_address'] = 0;
                } else {
                    $this->session->data['register']['use_address'] = $this->session->data['register']['use_address'] ? 1 : 0;
                }
                //register country
                if ((($quick_register && $this->session->data['register']['use_address']) || !$quick_register) && isset($this->session->data['register']['country_id'])) {
                    $this->session->data['payment_address']['country_id'] = $this->session->data['register']['country_id'];
                } else {
                    $this->session->data['payment_address']['country_id'] = $this->config->get('config_country_id');
                }
                $this->session->data['register']['country_id'] = $this->session->data['payment_address']['country_id'];
                //register zone
                if ((($quick_register && $this->session->data['register']['use_address']) || !$quick_register) && isset($this->session->data['register']['zone_id'])) {
                    $this->session->data['payment_address']['zone_id'] = $this->session->data['register']['zone_id'];
                } else {
                    $this->session->data['payment_address']['zone_id'] = $this->config->get('config_zone_id');
                }
                $this->session->data['register']['zone_id'] = $this->session->data['payment_address']['zone_id'];
                //register postcode
                if ((($quick_register && $this->session->data['register']['use_address']) || !$quick_register) && isset($this->session->data['register']['postcode'])) {
                    $this->session->data['payment_address']['postcode'] = $this->session->data['register']['postcode'];
                } else {
                    $this->session->data['payment_address']['postcode'] = '';
                }
                $this->session->data['register']['postcode'] = $this->session->data['payment_address']['postcode'];
                if ($this->cart->hasShipping()) {
                    $this->session->data['shipping_address']['country_id'] = $this->session->data['payment_address']['country_id'];
                    $this->session->data['shipping_address']['zone_id'] = $this->session->data['payment_address']['zone_id'];
                    $this->session->data['shipping_address']['postcode'] = $this->session->data['payment_address']['postcode'];
                }
            }
            if (!$this->cart->hasShipping()) {
                unset($this->session->data['shipping_address']);
            }
        } else {
            $this->load->model('account/address');
            $addresses = $this->model_account_address->getAddresses();
            //shipping address the same billing address?
            if (isset($this->session->data['shipping_address_same'])) {
                $shipping_address_same = $this->session->data['shipping_address_same'] ? 1 : 0;
            } else {
                $shipping_address_same = 1;
            }
            $this->session->data['shipping_address_same'] = $shipping_address_same;
            $view_data['shipping_address_same'] = $shipping_address_same;

            //default payment address id
            if (isset($this->session->data['payment_address_id']) && in_array($this->session->data['payment_address_id'], array_keys($addresses))) {
                $payment_address_id = $this->session->data['payment_address_id'];
            } else {
                $payment_address_id = $this->customer->getAddressId();
            }
            $this->session->data['payment_address_id'] = $payment_address_id;

            //default shipping address id
            if ($this->cart->hasShipping()) {
                if ($shipping_address_same) {
                    $this->session->data['shipping_address_id'] = $this->session->data['payment_address_id'];
                } else {
                    if (isset($this->session->data['shipping_address_id']) && in_array($this->session->data['shipping_address_id'], array_keys($addresses))) {
                        $shipping_address_id = $this->session->data['shipping_address_id'];
                    } else {
                        $shipping_address_id = $this->customer->getAddressId();
                    }
                    $this->session->data['shipping_address_id'] = $shipping_address_id;
                }
            } else {
                unset($this->session->data['shipping_address_id']);
                unset($this->session->data['shipping_address']);
            }
        }


        // <editor-fold defaultstate="collapsed" desc="ADD SUB-SECTION-PAGES">
        if ($this->customer->isLogged()) {
            if ($this->cart->hasShipping()) {
                $view_data['shippingAddress'] = $this->load->controller('checkout/shipping_address_onepage/render_index');
            }
            $view_data['paymentAddress'] = $this->load->controller('checkout/payment_address_onepage/render_index');
        } else {
            $view_data['login'] = $this->load->controller('checkout/login_onepage/render_index');
            $view_data['register'] = $this->load->controller('checkout/register_onepage/render_index');
            if ($guest_checkout) {
                $view_data['guest'] = $this->load->controller('checkout/guest_onepage/render_index');
                if ($this->cart->hasShipping()) {
                    $view_data['guestShipping'] = $this->load->controller('checkout/guest_shipping_onepage/render_index');
                }
            }
        }
        // </editor-fold>

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/checkout_onepage_left_content.tpl')) {
            $template = $this->config->get('config_template') . '/template/checkout/checkout_onepage_left_content.tpl';
        } else {
            $template = 'default/template/checkout/checkout_onepage_left_content.tpl';
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