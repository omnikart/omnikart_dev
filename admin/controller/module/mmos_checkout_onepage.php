<?php

class ControllerModuleMMOSCheckoutOnepage extends Controller {

    private $error = array();

    public function index($render_content = false) {

        if (!isset($this->request->get['store_id'])) {
            $this->response->redirect($this->url->link('module/mmos_checkout_onepage', 'token=' . $this->session->data['token'] . '&store_id=0', 'SSL'));
        }

        $this->load->language('module/mmos_checkout_onepage');
        $this->document->setTitle($this->language->get('heading_title_inpage'));

        $this->load->model('setting/setting');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $this->model_setting_setting->editSetting('mmos_checkout', array('mmos_checkout' => $this->request->post['mmos_checkout'], 'mmos_checkout_tips' => $this->request->post['tips'], 'mmos_checkout_css' => $this->request->post['css'], 'mmos_checkout_langs' => $this->request->post['langs']), $this->request->get['store_id']);

            $this->session->data['success'] = $this->language->get('text_success');
            if (isset($this->request->post['savequit'])) {
                $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
            } else {
                $this->response->redirect($this->url->link('module/mmos_checkout_onepage', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], 'SSL'));
            }
        }

        //WWw.MMOsolution.com config data -- DO NOT REMOVE--- 
        $view_data['MMOS_version'] = '3.4';
        $view_data['MMOS_code_id'] = 'MMOSOC108';

        $view_data['heading_title'] = $this->language->get('heading_title_inpage');
        $view_data['text_save_quit'] = $this->language->get('text_save_quit');
        $view_data['text_edit'] = $this->language->get('text_edit');
        $view_data['button_save'] = $this->language->get('button_save');
        $view_data['button_cancel'] = $this->language->get('button_cancel');

        $view_data['text_enabled'] = $this->language->get('text_enabled');
        $view_data['text_disabled'] = $this->language->get('text_disabled');
        $view_data['text_choose_for_calculate'] = $this->language->get('text_choose_for_calculate');
        $view_data['text_postcode'] = $this->language->get('text_postcode');
        $view_data['text_country_id'] = $this->language->get('text_country_id');
        $view_data['text_zone_id'] = $this->language->get('text_zone_id');
        $view_data['text_city'] = $this->language->get('text_city');
        $view_data['entry_status'] = $this->language->get('entry_status');
        $view_data['entry_store'] = $this->language->get('entry_store');
        $view_data['entry_checkout_group'] = $this->language->get('entry_checkout_group');
        $view_data['help_checkout_group'] = $this->language->get('help_checkout_group');
        $view_data['help_select_group'] = $this->language->get('help_select_group');
        $view_data['text_none'] = $this->language->get('text_none');
        $view_data['text_stack'] = $this->language->get('text_stack');
        $view_data['text_select_box'] = $this->language->get('text_select_box');
        $view_data['entry_display_style'] = $this->language->get('entry_display_style');
        $view_data['entry_default_checkout'] = $this->language->get('entry_default_checkout');
        $view_data['text_guest'] = $this->language->get('text_guest');
        $view_data['text_register'] = $this->language->get('text_register');
        $view_data['text_returning'] = $this->language->get('text_returning');
        $view_data['entry_quick_register'] = $this->language->get('entry_quick_register');
        $view_data['help_quick_register'] = $this->language->get('help_quick_register');
        $view_data['text_yes'] = $this->language->get('text_yes');
        $view_data['text_no'] = $this->language->get('text_no');
        $view_data['text_register_fone'] = $this->language->get('text_register_fone');
        $view_data['text_register_fone_tax'] = $this->language->get('text_register_fone_tax');
        $view_data['entry_quick_guest_checkout'] = $this->language->get('entry_quick_guest_checkout');
        $view_data['help_quick_guest_checkout'] = sprintf($this->language->get('help_quick_guest_checkout'), $this->session->data['token']);
        $view_data['entry_button_refresh'] = $this->language->get('entry_button_refresh');
        $view_data['entry_calculate_non_address_shipping'] = $this->language->get('entry_calculate_non_address_shipping');
        $view_data['entry_warning_when_non_address_shipping'] = $this->language->get('entry_warning_when_non_address_shipping');
        $view_data['entry_calculate_non_address_payment'] = $this->language->get('entry_calculate_non_address_payment');
        $view_data['entry_warning_when_non_address_payment'] = $this->language->get('entry_warning_when_non_address_payment');

        $view_data['text_show_product_model'] = $this->language->get('text_show_product_model');
        $view_data['text_show_counpon'] = $this->language->get('text_show_counpon');
        $view_data['text_show_giftcart'] = $this->language->get('text_show_giftcart');


        $view_data['entry_enable_qtip'] = $this->language->get('entry_enable_qtip');
        $view_data['entry_debug_mode'] = $this->language->get('entry_debug_mode');
        $view_data['help_button_refresh'] = $this->language->get('help_button_refresh');
        $view_data['help_debug_mode'] = $this->language->get('help_debug_mode');
        $view_data['help_calculate_non_address_shipping'] = $this->language->get('help_calculate_non_address_shipping');
        $view_data['help_calculate_non_address_payment'] = $this->language->get('help_calculate_non_address_payment');
        $view_data['text_checkout_theme'] = $this->language->get('text_checkout_theme');
        $view_data['text_custom_style'] = $this->language->get('text_custom_style');
        $view_data['text_standar'] = $this->language->get('text_standar');
        $view_data['text_default'] = $this->language->get('text_default');
        $view_data['text_primary'] = $this->language->get('text_primary');
        $view_data['text_success'] = $this->language->get('text_success');
        $view_data['text_information'] = $this->language->get('text_information');
        $view_data['text_warning'] = $this->language->get('text_warning');
        $view_data['text_danger'] = $this->language->get('text_danger');
        $view_data['text_loading_1'] = $this->language->get('text_loading_1');
        $view_data['text_loading_2'] = $this->language->get('text_loading_2');
        $view_data['text_loading_3'] = $this->language->get('text_loading_3');
        $view_data['text_loading_4'] = $this->language->get('text_loading_4');
        $view_data['text_loading_5'] = $this->language->get('text_loading_5');
        $view_data['text_loading_6'] = $this->language->get('text_loading_6');
        $view_data['text_loading_7'] = $this->language->get('text_loading_7');
        $view_data['text_loading_8'] = $this->language->get('text_loading_8');
        $view_data['text_button_color_style'] = $this->language->get('text_button_color_style');
        $view_data['text_common_button'] = $this->language->get('text_common_button');
        $view_data['text_common_button_ok'] = $this->language->get('text_common_button_ok');
        $view_data['text_common_button_continue'] = $this->language->get('text_common_button_continue');
        $view_data['text_common_button_login'] = $this->language->get('text_common_button_login');
        $view_data['text_voucher_coupon_button'] = $this->language->get('text_voucher_coupon_button');
        $view_data['text_voucher_apply_button'] = $this->language->get('text_voucher_apply_button');
        $view_data['text_coupon_apply_button'] = $this->language->get('text_coupon_apply_button');
        $view_data['text_refresh_button'] = $this->language->get('text_refresh_button');
        $view_data['text_make_order_button'] = $this->language->get('text_make_order_button');
        $view_data['text_make_order'] = $this->language->get('text_make_order');
        $view_data['text_edit_order_button'] = $this->language->get('text_edit_order_button');
        $view_data['text_edit_order'] = $this->language->get('text_edit_order');
        $view_data['text_panel_color_style'] = $this->language->get('text_panel_color_style');
        $view_data['text_checkout_panel'] = $this->language->get('text_checkout_panel');
        $view_data['text_billing_details_panel'] = $this->language->get('text_billing_details_panel');
        $view_data['text_delivery_details_panel'] = $this->language->get('text_delivery_details_panel');
        $view_data['text_delivery_method_panel'] = $this->language->get('text_delivery_method_panel');
        $view_data['text_payment_method_panel'] = $this->language->get('text_payment_method_panel');
        $view_data['text_cart_order_panel'] = $this->language->get('text_cart_order_panel');
        $view_data['text_checkout'] = $this->language->get('text_checkout');
        $view_data['text_billing_details'] = $this->language->get('text_billing_details');
        $view_data['text_delivery_details'] = $this->language->get('text_delivery_details');
        $view_data['text_delivery_method'] = $this->language->get('text_delivery_method');
        $view_data['text_payment_method'] = $this->language->get('text_payment_method');
        $view_data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
        $view_data['text_help_tips'] = $this->language->get('text_help_tips');
        $view_data['text_checkout_tips'] = $this->language->get('text_checkout_tips');
        $view_data['text_payment_address_tips'] = $this->language->get('text_payment_address_tips');
        $view_data['text_shipping_address_tips'] = $this->language->get('text_shipping_address_tips');
        $view_data['text_shipping_method_tips'] = $this->language->get('text_shipping_method_tips');
        $view_data['text_payment_method_tips'] = $this->language->get('text_payment_method_tips');
        $view_data['text_text_make_order_button'] = $this->language->get('text_text_make_order_button');
        $view_data['text_text_edit_order_button'] = $this->language->get('text_text_edit_order_button');
        $view_data['text_text_no_shipping_button'] = $this->language->get('text_text_no_shipping_button');
        $view_data['text_loading_bar_icon'] = $this->language->get('text_loading_bar_icon');

        $view_data['tab_gerenal_setting'] = $this->language->get('tab_gerenal_setting');
        $view_data['tab_layout_setting'] = $this->language->get('tab_layout_setting');
        $view_data['tab_tips_setting'] = $this->language->get('tab_tips_setting');
        $view_data['tab_langguage_setting'] = $this->language->get('tab_langguage_setting');
        $view_data['tab_support'] = $this->language->get('tab_support');

        if (isset($this->error['warning'])) {
            $view_data['error_warning'] = $this->error['warning'];
        } else {
            $view_data['error_warning'] = '';
        }
        if (isset($this->session->data['success'])) {
            $view_data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $view_data['success'] = '';
        }

        $view_data['breadcrumbs'] = array();
        $view_data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $view_data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
//            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $view_data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_inpage'),
            'href' => $this->url->link('module/mmos_checkout_onepage', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], 'SSL'),
            'separator' => ' :: '
        );


        $view_data['action'] = $this->url->link('module/mmos_checkout_onepage', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], 'SSL');

        $view_data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        $view_data['token'] = $this->session->data['token'];

        if (isset($this->request->get['store_id'])) {
            $view_data['store_id'] = $this->request->get['store_id'];
        } else {
            $view_data['store_id'] = 0;
        }


        #LANGUAGE
        $this->load->model('localisation/language');
        $view_data['languages'] = $this->model_localisation_language->getLanguages();
        #STORE
        $this->load->model('setting/store');
        #CUSTOMER GROUPS.
        $this->load->model('sale/customer_group');
        $view_data['customer_groups'] = $customer_groups = $this->model_sale_customer_group->getCustomerGroups();

        $view_data['stores'] = array();

        $view_data['stores'][] = array(
            'store_id' => 0,
            'name' => $this->config->get('config_name') . ' (' . $this->language->get('text_default') . ')'
        );

        $stores = $this->model_setting_store->getStores();

        foreach ($stores as $store) {
            $view_data['stores'][] = array(
                'store_id' => $store['store_id'],
                'name' => $store['name']
            );
        }

        $view_data['text_default'] = $this->language->get('text_default');

        $mmos_checkout_extra = $this->model_setting_setting->getSetting('mmos_checkout', $this->request->get['store_id']);

        $view_data['module'] = $mmos_checkout_extra['mmos_checkout'];

        //tips
        $view_data['tips'] = $mmos_checkout_extra['mmos_checkout_tips'];
        $view_data['langs'] = $mmos_checkout_extra['mmos_checkout_langs'];

        //css
        $view_data['themes'] = $this->config->get('mmos_checkout_themes');
        $view_data['css'] = $mmos_checkout_extra['mmos_checkout_css'];


        //$this->template = 'module/mmos_checkout_onepage.tpl';
        //output
//        $this->children = array(
//            'common/header',
//            'common/footer'
//        );
        $view_data['header'] = $this->load->controller('common/header');
        $view_data['column_left'] = $this->load->controller('common/column_left');
        $view_data['footer'] = $this->load->controller('common/footer');
        //$this->response->setOutput($this->render());
        $this->response->setOutput($this->load->view('module/mmos_checkout_onepage.tpl', $view_data));
    }

    private function validate() {

        if (!$this->user->hasPermission('modify', 'module/mmos_checkout_onepage')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function install() {
        if ($this->user->hasPermission('modify', 'extension/module')) {
            $this->load->model('localisation/language');
            $view_data['languages'] = $languages = $this->model_localisation_language->getLanguages();
            $admin_language_id = $this->config->get('config_language_id');
            $this->load->model('setting/setting');
            $this->load->model('setting/store');
            $tips_config = array(
                'checkout_tip' => '',
                'payment_address_tip' => '',
                'shipping_address_tip' => '',
                'shipping_method_tip' => 'Delivery Method options depend on Delivery Address(country/zone).',
                'payment_method_tip' => 'Payment Method options depend on Payment Address(country/zone).'
            );
            $langs_cofig = array(
                'make_order_button' => 'Make Order',
                'edit_order_button' => 'Edit Order',
                'no_shipping_required' => 'No Shipping Required'
            );
            $themes = array(//base on bootstrap css
                'standar',
                'default',
                'primary',
                'success',
                'info',
                'warning',
                'danger'
            );
            $base_config = array(
                'status' => 0,
                'default_customer_group' => -1,
                'customer_group_style' => 0, //display stack style.
                'default_role' => 'guest', //default checkout option role
                //register
                'quick_register' => 1,
                'register_telephone_require' => 0,
                'register_telephone_tax_display' => 0,
                //guest
                'quick_guest_checkout' => 1,
                'guest_telephone_require' => 0,
                'guest_telephone_tax_display' => 0,
                'checkout_tips' => $tips_config,
                'checkout_langs' => $langs_cofig,
                'show_refresh_button' => 1,
                'calculate_non_address_shipping' => 0,
                'calculate_non_address_payment' => 0,
                'enable_giftcart' => 0,
                'enable_counpon' => 0,
                'enable_pmodel' => 0,
                'enable_qtip' => 1,
//                'themes' => $themes
                'debug' => 0
            );

            $css = array(
                'checkout_theme' => 'standar', //default theme
                //loading bar icon
                'loading_bar_icon' => 'loading_1',
                //buttons color
                'common_btn_color' => '',
                'voucher_coupon_btn_color' => '',
//                'confirm_btn_color' => '',
                'make_order_btn_color' => '',
                'edit_order_btn_color' => '',
                'refresh_btn_color' => '',
                //panels color
                'checkout_panel_color' => '',
                'billing_panel_color' => '',
                'shipping_panel_color' => '',
                'shipping_method_panel_color' => '',
                'payment_method_panel_color' => '',
//                'confirm_panel_color' => '',
                'cart_order_panel_color' => ''
            );

            $tips = array(
                'checkout_tip' => array(),
                'payment_address_tip' => array(),
                'shipping_address_tip' => array(),
                'shipping_method_tip' => array(),
                'payment_method_tip' => array()
            );


            $tips['checkout_tip'][$admin_language_id] = $tips_config['checkout_tip'];
            $tips['payment_address_tip'][$admin_language_id] = $tips_config['payment_address_tip'];
            $tips['shipping_address_tip'][$admin_language_id] = $tips_config['shipping_address_tip'];
            $tips['shipping_method_tip'][$admin_language_id] = $tips_config['shipping_method_tip'];
            $tips['payment_method_tip'][$admin_language_id] = $tips_config['payment_method_tip'];

            $langs = array(
                'make_order_button' => array(),
                'edit_order_button' => array(),
                'no_shipping_required' => array(),
            );
            $langs['make_order_button'][$admin_language_id] = $langs_cofig['make_order_button'];
            $langs['edit_order_button'][$admin_language_id] = $langs_cofig['edit_order_button'];
            $langs['no_shipping_required'][$admin_language_id] = $langs_cofig['no_shipping_required'];

            //$this->model_setting_setting->editSetting('mmos_checkout',array('mmos_checkout_module'=>array($base_config)));//for module default
            $this->model_setting_setting->editSetting('mmos_checkout_themes', array('mmos_checkout_themes' => $themes));
            $this->model_setting_setting->editSetting('mmos_checkout', array('mmos_checkout' => $base_config, 'mmos_checkout_css' => $css, 'mmos_checkout_tips' => $tips, 'mmos_checkout_langs' => $langs), 0);

            $stores = $this->model_setting_store->getStores();

            foreach ($stores as $store) {
                $this->model_setting_setting->editSetting('mmos_checkout', array('mmos_checkout' => $base_config, 'mmos_checkout_css' => $css, 'mmos_checkout_tips' => $tips, 'mmos_checkout_langs' => $langs), $store['store_id']);
            }

            $this->vqmod_protect(1);

            $this->response->redirect($this->url->link('module/mmos_checkout_onepage', 'token=' . $this->session->data['token'], 'SSL'));
        }
    }

    public function uninstall() {
//        //remove any asociate value of the module
        if ($this->user->hasPermission('modify', 'extension/module')) {
            $this->load->model('setting/setting');
            $this->load->model('setting/store');

            $this->model_setting_setting->deleteSetting('mmos_checkout_themes');
            $this->model_setting_setting->deleteSetting('mmos_checkout', 0);

            $stores = $this->model_setting_store->getStores();

            foreach ($stores as $store) {
                $this->model_setting_setting->deleteSetting('mmos_checkout', $store['store_id']);
            }

            $this->vqmod_protect();
        }
    }

    protected function vqmod_protect($action = 0) {
        // action 1 =  install; 0: uninstall
        $vqmod_file = 'MMOSolution_checkout_one.xml';
        if ($this->user->hasPermission('modify', 'extension/module')) {
            $MMOS_ROOT_DIR = substr(DIR_APPLICATION, 0, strrpos(DIR_APPLICATION, '/', -2)) . '/vqmod/xml/';
            if ($action == 1) {
                if (is_file($MMOS_ROOT_DIR . $vqmod_file . '_mmosolution')) {
                    @rename($MMOS_ROOT_DIR . $vqmod_file . '_mmosolution', $MMOS_ROOT_DIR . $vqmod_file);
                }
            } else {
                if (is_file($MMOS_ROOT_DIR . $vqmod_file)) {
                    @rename($MMOS_ROOT_DIR . $vqmod_file, $MMOS_ROOT_DIR . $vqmod_file . '_mmosolution');
                }
            }
        }
    }

}

?>
