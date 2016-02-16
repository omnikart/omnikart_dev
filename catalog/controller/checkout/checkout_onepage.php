<?php
class ControllerCheckoutCheckoutOnepage extends Controller {
	public function index($render_content = false) {
		$this->load->model ( 'checkout/checkout_onepage' );
		
		/*
		 * To check customer has access to order or not
		 */
		if ($this->config->get ( 'marketplace_status' )) {
			$this->load->model ( 'account/customerpartner' );
			$this->load->language ( 'customerpartner/orderReview' );
			
			$customerRights = $this->model_account_customerpartner->getCustomerGroupRights ( $this->customer->getGroupId () );
			
			$approvedDetails = $this->model_account_customerpartner->getApprovedProducts ( $this->customer->getId () );
			$approveProducts = unserialize ( $approvedDetails ['approve_cart'] );
			
			$total = $this->cart->getTotal ();
			
			$limit = $this->model_account_customerpartner->getUser ();
			
			if ($customerRights && ! array_key_exists ( 'make-order', $customerRights ['rights'] )) {
				$this->session->data ['forReview'] = true;
				$currentCart = $this->session->data ['cart'];
				if ($approveProducts) {
					foreach ( $currentCart as $key => $value ) {
						if (! array_key_exists ( $key, $approveProducts )) {
							$this->session->data ['warning'] = $this->language->get ( 'warning_reviewtoadmin' );
							$this->response->redirect ( $this->url->link ( 'account/customerpartner/orderReview', '', 'SSL' ) );
						}
					}
				} else if (! $approveProducts) {
					$this->session->data ['warning'] = " Warning: You are not allowed to place order without permission, please send your order for review to your administrator!";
					$this->response->redirect ( $this->url->link ( 'account/customerpartner/orderReview', '', 'SSL' ) );
				}
			}
			if ($limit && ($limit ['p_limit'] < $total)) {
				
				$this->session->data ['forReview'] = true;
				$currentCart = $this->session->data ['cart'];
				if ($approveProducts) {
					foreach ( $currentCart as $key => $value ) {
						if (! array_key_exists ( $key, $approveProducts )) {
							$this->session->data ['warning'] = $this->language->get ( 'warning_reviewtoadmin' );
							$this->response->redirect ( $this->url->link ( 'account/customerpartner/orderReview', '', 'SSL' ) );
						}
					}
				} else if (! $approveProducts) {
					$this->session->data ['warning'] = " Warning: You have crossed the purchase limit, please send your order for review to your administrator!";
					$this->response->redirect ( $this->url->link ( 'account/customerpartner/orderReview', '', 'SSL' ) );
				}
			}
		}
		/*
		 * end here
		 */
		
		if ($this->config->get ( 'config_secure' ) == '1' && ! (isset ( $this->request->server ['HTTPS'] ) && (($this->request->server ['HTTPS'] == 'on') || ($this->request->server ['HTTPS'] == '1')))) {
			$urls = $this->url->link ( 'checkout/checkout_onepage', '', true );
			
			$this->response->redirect ( $urls );
		}
		
		$redirect = $this->model_checkout_checkout_onepage->validate_checkout ();
		
		if ($redirect) {
			$this->response->redirect ( $redirect );
		}
		
		$this->model_checkout_checkout_onepage->unset_login_sessions ();
		
		$this->load->language ( 'checkout/checkout' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		// $this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
		// $this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
		$this->document->addScript ( 'catalog/view/javascript/jquery/datetimepicker/moment.js' );
		$this->document->addScript ( 'catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js' );
		$this->document->addStyle ( 'catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css' );
		
		$view_data ['breadcrumbs'] = array ();
		
		$view_data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home' ),
				'separator' => false 
		);
		
		$view_data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_cart' ),
				'href' => $this->url->link ( 'checkout/cart' ),
				'separator' => $this->language->get ( 'text_separator' ) 
		);
		
		$view_data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'heading_title' ),
				'href' => $this->url->link ( 'checkout/checkout_onepage', '', 'SSL' ),
				'separator' => $this->language->get ( 'text_separator' ) 
		);
		
		$view_data ['heading_title'] = $this->language->get ( 'heading_title' );
		
		// $_['text_checkout_account'] = 'Step 2: Account &amp; Billing Details';
		$view_data ['text_checkout_account'] = $this->model_checkout_checkout_onepage->rebuid_content_text ( $this->language->get ( 'text_checkout_account' ) );
		// $_['text_checkout_payment_address'] = 'Step 2: Billing Details';
		$view_data ['text_checkout_payment_address'] = $this->model_checkout_checkout_onepage->rebuid_content_text ( $this->language->get ( 'text_checkout_payment_address' ) );
		$view_data ['text_returning_customer'] = $this->language->get ( 'text_returning_customer' ); // 'Returning Customer'
		if ($this->config->get ( 'config_checkout_id' )) {
			$this->load->model ( 'catalog/information' );
			$information_info = $this->model_catalog_information->getInformation ( $this->config->get ( 'config_checkout_id' ) );
			
			if ($information_info) {
				$view_data ['text_error_agree'] = sprintf ( $this->language->get ( 'error_agree' ), $information_info ['title'] );
			}
		}
		
		$view_data ['logged'] = $this->customer->isLogged ();
		$view_data ['shipping_required'] = $this->cart->hasShipping ();
		
		$view_data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$view_data ['column_right'] = $this->load->controller ( 'common/column_right' );
		$view_data ['content_top'] = $this->load->controller ( 'common/content_top' );
		$view_data ['content_bottom'] = $this->load->controller ( 'common/content_bottom' );
		$view_data ['footer'] = $this->load->controller ( 'common/footer' );
		$view_data ['header'] = $this->load->controller ( 'common/header' );
		
		$view_data ['config_template'] = $this->config->get ( 'config_template' );
		
		// <editor-fold defaultstate="collapsed" desc="THEMES/STYLESHEET/LANGUAGE">
		$this->load->model ( 'setting/setting' );
		
		$mmos_checkout_extra = $this->model_setting_setting->getSetting ( 'mmos_checkout', $this->config->get ( 'config_store_id' ) );
		$view_data ['mmos_checkout'] = $mmos_checkout_extra ['mmos_checkout'];
		$view_data ['tips'] = $tips = $mmos_checkout_extra ['mmos_checkout_tips'];
		$view_data ['css'] = $mmos_checkout_extra ['mmos_checkout_css'];
		$view_data ['themes'] = $this->config->get ( 'mmos_checkout_themes' );
		if (empty ( $css ['checkout_theme'] ) || ! in_array ( $css ['checkout_theme'], $themes )) {
			$css ['checkout_theme'] = 'standar';
		}
		$view_data ['config_language_id'] = $this->config->get ( 'config_language_id' );
		
		// </editor-fold>
		// <editor-fold defaultstate="collapsed" desc="VOUCHER/COUPON">
		if (isset ( $this->session->data ['voucher_success'] )) {
			$view_data ['voucher_success'] = $this->session->data ['voucher_success'];
			
			unset ( $this->session->data ['voucher_success'] );
		} else {
			$view_data ['voucher_success'] = '';
		}
		if (isset ( $this->session->data ['coupon_success'] )) {
			$view_data ['coupon_success'] = $this->session->data ['coupon_success'];
			
			unset ( $this->session->data ['coupon_success'] );
		} else {
			$view_data ['coupon_success'] = '';
		} // </editor-fold>
		  // <editor-fold defaultstate="collapsed" desc="ADD SUB-SECTION-PAGES">
		$view_data ['content'] = $this->load->controller ( 'checkout/checkout_onepage_content/render_index' );
		
		// </editor-fold>
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/checkout/checkout_onepage.tpl' )) {
			$template = $this->config->get ( 'config_template' ) . '/template/checkout/checkout_onepage.tpl';
		} else {
			$template = 'default/template/checkout/checkout_onepage.tpl';
		}
		
		$this->response->setOutput ( $this->load->view ( $template, $view_data ) );
	}
	public function country() {
		$json = array ();
		
		$this->load->model ( 'localisation/country' );
		
		$country_info = $this->model_localisation_country->getCountry ( $this->request->get ['country_id'] );
		
		if ($country_info) {
			$this->load->model ( 'localisation/zone' );
			
			$json = array (
					'country_id' => $country_info ['country_id'],
					'name' => $country_info ['name'],
					'iso_code_2' => $country_info ['iso_code_2'],
					'iso_code_3' => $country_info ['iso_code_3'],
					'address_format' => $country_info ['address_format'],
					'postcode_required' => $country_info ['postcode_required'],
					'zone' => $this->model_localisation_zone->getZonesByCountryId ( $this->request->get ['country_id'] ),
					'status' => $country_info ['status'] 
			);
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function customfield() {
		$json = array ();
		
		$this->load->model ( 'account/custom_field' );
		
		// Customer Group
		if (isset ( $this->request->get ['customer_group_id'] ) && is_array ( $this->config->get ( 'config_customer_group_display' ) ) && in_array ( $this->request->get ['customer_group_id'], $this->config->get ( 'config_customer_group_display' ) )) {
			$customer_group_id = $this->request->get ['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get ( 'config_customer_group_id' );
		}
		
		$custom_fields = $this->model_account_custom_field->getCustomFields ( $customer_group_id );
		
		foreach ( $custom_fields as $custom_field ) {
			$json [] = array (
					'custom_field_id' => $custom_field ['custom_field_id'],
					'required' => $custom_field ['required'] 
			);
		}
		
		$this->response->addHeader ( 'Content-Type: application/json' );
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function group_approval() {
		$json = array ();
		
		$this->load->model ( 'account/customer_group' );
		
		$this->session->data ['register'] ['customer_group_id'] = $this->request->get ['customer_group_id'];
		
		// Customer Group
		if (isset ( $this->request->get ['customer_group_id'] ) && is_array ( $this->config->get ( 'config_customer_group_display' ) ) && in_array ( $this->request->get ['customer_group_id'], $this->config->get ( 'config_customer_group_display' ) )) {
			$customer_group_id = $this->request->get ['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get ( 'config_customer_group_id' );
		}
		
		$customer_group = $this->model_account_customer_group->getCustomerGroup ( $customer_group_id );
		
		if ($customer_group ['approval']) {
			$json ['gourp_approval'] = 1;
		} else {
			$json ['gourp_approval'] = 0;
		}
		
		$this->response->addHeader ( 'Content-Type: application/json' );
		$this->response->setOutput ( json_encode ( $json ) );
	}
}

?>
