<?php
class ControllerCheckoutRegisterOnepage extends Controller {
	public function index($render_content = false) {
		$this->load->model ( 'setting/setting' );
		
		$mmos_checkout_extra = $this->model_setting_setting->getSetting ( 'mmos_checkout', $this->config->get ( 'config_store_id' ) );
		$mmos_checkout = $mmos_checkout_extra ['mmos_checkout'];
		$view_data ['css'] = $mmos_checkout_extra ['mmos_checkout_css'];
		$view_data ['themes'] = $this->config->get ( 'mmos_checkout_themes' );
		if (empty ( $css ['checkout_theme'] ) || ! in_array ( $css ['checkout_theme'], $themes )) {
			$css ['checkout_theme'] = 'standar';
		}
		
		if ($mmos_checkout ['quick_register'] && ! $this->config->get ( 'config_vat' ) && ! $this->cart->hasShipping ()) {
			$view_data ['quick_register'] = 1;
		} else {
			$view_data ['quick_register'] = 0;
		}
		$view_data ['use_address'] = ! empty ( $this->session->data ['register'] ['use_address'] ) ? 1 : 0;
		
		if (! empty ( $mmos_checkout ['register_telephone_require'] )) {
			$view_data ['register_telephone_require'] = 1;
		}
		if (! empty ( $mmos_checkout ['register_telephone_tax_display'] )) {
			$view_data ['register_telephone_tax_display'] = 1;
		}
		// for trigger when collapse to quick register mode
		$view_data ['config_country_id'] = $this->config->get ( 'config_country_id' );
		$view_data ['config_zone_id'] = $this->config->get ( 'config_zone_id' );
		
		// <editor-fold defaultstate="collapsed" desc="LANGUAGES">
		$this->load->language ( 'checkout/checkout' );
		
		$view_data ['text_your_details'] = $this->language->get ( 'text_your_details' );
		$view_data ['text_your_address'] = $this->language->get ( 'text_your_address' );
		$view_data ['text_your_password'] = $this->language->get ( 'text_your_password' );
		$view_data ['text_select'] = $this->language->get ( 'text_select' );
		$view_data ['text_none'] = $this->language->get ( 'text_none' );
		$view_data ['text_loading'] = $this->language->get ( 'text_loading' );
		$view_data ['error_approved'] = $this->language->get ( 'error_approved' );
		
		$view_data ['entry_firstname'] = $this->language->get ( 'entry_firstname' );
		$view_data ['entry_lastname'] = $this->language->get ( 'entry_lastname' );
		$view_data ['entry_email'] = $this->language->get ( 'entry_email' );
		$view_data ['entry_telephone'] = $this->language->get ( 'entry_telephone' );
		$view_data ['entry_fax'] = $this->language->get ( 'entry_fax' );
		$view_data ['entry_company'] = $this->language->get ( 'entry_company' );
		$view_data ['entry_customer_group'] = $this->language->get ( 'entry_customer_group' );
		$view_data ['entry_address_1'] = $this->language->get ( 'entry_address_1' );
		$view_data ['entry_address_2'] = $this->language->get ( 'entry_address_2' );
		$view_data ['entry_postcode'] = $this->language->get ( 'entry_postcode' );
		$view_data ['entry_city'] = $this->language->get ( 'entry_city' );
		$view_data ['entry_country'] = $this->language->get ( 'entry_country' );
		$view_data ['entry_zone'] = $this->language->get ( 'entry_zone' );
		$view_data ['entry_newsletter'] = sprintf ( $this->language->get ( 'entry_newsletter' ), $this->config->get ( 'config_name' ) );
		$view_data ['entry_password'] = $this->language->get ( 'entry_password' );
		$view_data ['entry_confirm'] = $this->language->get ( 'entry_confirm' );
		$view_data ['entry_shipping'] = $this->language->get ( 'entry_shipping' );
		
		$view_data ['button_continue'] = $this->language->get ( 'button_continue' );
		$view_data ['button_upload'] = $this->language->get ( 'button_upload' ); // </editor-fold>
		                                                                     // REGISTER DATA
		if (isset ( $this->session->data ['register'] ['firstname'] )) {
			$view_data ['firstname'] = $this->session->data ['register'] ['firstname'];
		} else {
			$view_data ['firstname'] = '';
		}
		
		if (isset ( $this->session->data ['register'] ['lastname'] )) {
			$view_data ['lastname'] = $this->session->data ['register'] ['lastname'];
		} else {
			$view_data ['lastname'] = '';
		}
		
		if (isset ( $this->session->data ['register'] ['email'] )) {
			$view_data ['email'] = $this->session->data ['register'] ['email'];
		} else {
			$view_data ['email'] = '';
		}
		
		if (isset ( $this->session->data ['register'] ['telephone'] )) {
			$view_data ['telephone'] = $this->session->data ['register'] ['telephone'];
		} else {
			$view_data ['telephone'] = '';
		}
		
		if (isset ( $this->session->data ['register'] ['fax'] )) {
			$view_data ['fax'] = $this->session->data ['register'] ['fax'];
		} else {
			$view_data ['fax'] = '';
		}
		
		if (isset ( $this->session->data ['register'] ['address_1'] )) {
			$view_data ['address_1'] = $this->session->data ['register'] ['address_1'];
		} else {
			$view_data ['address_1'] = '';
		}
		
		if (isset ( $this->session->data ['register'] ['address_2'] )) {
			$view_data ['address_2'] = $this->session->data ['register'] ['address_2'];
		} else {
			$view_data ['address_2'] = '';
		}
		
		if (isset ( $this->session->data ['register'] ['city'] )) {
			$view_data ['city'] = $this->session->data ['register'] ['city'];
		} else {
			$view_data ['city'] = '';
		}
		$view_data ['customer_groups'] = array ();
		
		if (is_array ( $this->config->get ( 'config_customer_group_display' ) )) {
			$this->load->model ( 'account/customer_group' );
			
			$customer_groups = $this->model_account_customer_group->getCustomerGroups ();
			
			foreach ( $customer_groups as $customer_group ) {
				if (in_array ( $customer_group ['customer_group_id'], $this->config->get ( 'config_customer_group_display' ) )) {
					$view_data ['customer_groups'] [] = $customer_group;
				}
			}
		}
		
		if (isset ( $this->session->data ['register'] ['customer_group_id'] )) {
			$view_data ['customer_group_id'] = $this->session->data ['register'] ['customer_group_id'];
		} else {
			$view_data ['customer_group_id'] = $this->config->get ( 'config_customer_group_id' );
		}
		
		if (! $mmos_checkout ['customer_group_style']) {
			$view_data ['customer_group_style'] = 0;
		} else {
			$view_data ['customer_group_style'] = 1;
		}
		
		if (isset ( $this->session->data ['register'] ['company'] )) {
			$view_data ['company'] = $this->session->data ['register'] ['company'];
		} else {
			$view_data ['company'] = '';
		}
		
		if (isset ( $this->session->data ['register'] ['postcode'] )) {
			$view_data ['postcode'] = $this->session->data ['register'] ['postcode'];
		} else {
			$view_data ['postcode'] = '';
		}
		
		if (isset ( $this->session->data ['register'] ['country_id'] )) {
			$view_data ['country_id'] = $this->session->data ['register'] ['country_id'];
		} else {
			$view_data ['country_id'] = '';
		}
		
		if (isset ( $this->session->data ['register'] ['zone_id'] )) {
			$view_data ['zone_id'] = $this->session->data ['register'] ['zone_id'];
		} else {
			$view_data ['zone_id'] = '';
		}
		
		$this->load->model ( 'localisation/country' );
		$view_data ['countries'] = $this->model_localisation_country->getCountries ();
		
		$country_info = $this->model_localisation_country->getCountry ( $view_data ['country_id'] );
		if ($country_info) {
			$this->load->model ( 'localisation/zone' );
			$view_data ['zones'] = $this->model_localisation_zone->getZonesByCountryId ( $view_data ['country_id'] );
		} else {
			$view_data ['zones'] = array ();
		}
		
		if ($this->config->get ( 'config_account_id' )) {
			$this->load->model ( 'catalog/information' );
			
			$information_info = $this->model_catalog_information->getInformation ( $this->config->get ( 'config_account_id' ) );
			
			if ($information_info) {
				$mmos_text_agree = str_replace ( '<a href="%s" class="agree">', '<a href="%s" dala-link="%s" class="mmos-agree">', $this->language->get ( 'text_agree' ) );
				$view_data ['text_agree'] = sprintf ( $mmos_text_agree, $this->url->link ( 'information/information', 'information_id=' . $this->config->get ( 'config_account_id' ), 'SSL' ), $this->url->link ( 'information/information/agree', 'information_id=' . $this->config->get ( 'config_account_id' ), 'SSL' ), $information_info ['title'], $information_info ['title'] );
			} else {
				$view_data ['text_agree'] = '';
			}
		} else {
			$view_data ['text_agree'] = '';
		}
		
		if (isset ( $this->session->data ['register'] ['agree'] )) {
			$view_data ['agree'] = $this->session->data ['register'] ['agree'];
		} else {
			$view_data ['agree'] = 1;
		}
		
		if (isset ( $this->session->data ['register'] ['newsletter'] )) {
			$view_data ['newsletter'] = $this->session->data ['register'] ['newsletter'];
		} else {
			$view_data ['newsletter'] = 1;
		}
		$view_data ['shipping_required'] = $this->cart->hasShipping ();
		
		if (isset ( $this->session->data ['register'] ['shipping_address'] )) {
			$view_data ['shipping_address'] = $this->session->data ['register'] ['shipping_address'];
		} else {
			$view_data ['shipping_address'] = 1;
		}
		
		$this->load->model ( 'account/custom_field' );
		
		$view_data ['custom_fields'] = $this->model_account_custom_field->getCustomFields ();
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/checkout/register_onepage.tpl' )) {
			$template = $this->config->get ( 'config_template' ) . '/template/checkout/register_onepage.tpl';
		} else {
			$template = 'default/template/checkout/register_onepage.tpl';
		}
		
		if (! $render_content) {
			$this->response->setOutput ( $this->load->view ( $template, $view_data ) );
		} else {
			return $this->load->view ( $template, $view_data );
		}
	}
	public function render_index() {
		return $this->index ( true );
	}
	public function validate() {
		ob_start ();
		$this->load->model ( 'setting/setting' );
		
		$mmos_checkout_extra = $this->model_setting_setting->getSetting ( 'mmos_checkout', $this->config->get ( 'config_store_id' ) );
		$mmos_checkout = $mmos_checkout_extra ['mmos_checkout'];
		
		if ($mmos_checkout ['quick_register'] && ! $this->config->get ( 'config_vat' ) && ! $this->cart->hasShipping ()) {
			$quick_register = $view_data ['quick_register'] = 1;
		} else {
			$quick_register = $view_data ['quick_register'] = 0;
		}
		if (! empty ( $mmos_checkout ['register_telephone_require'] )) {
			$view_data ['register_telephone_require'] = 1;
			$register_telephone_require = 1;
		} else {
			$view_data ['register_telephone_require'] = 0;
			$register_telephone_require = 0;
		}
		if (! empty ( $mmos_checkout ['register_telephone_tax_display'] )) {
			$view_data ['register_telephone_tax_display'] = 1;
		} else {
			$view_data ['register_telephone_tax_display'] = 0;
		}
		
		$this->load->language ( 'checkout/checkout' );
		
		$this->load->model ( 'account/customer' );
		
		$json = array ();
		
		// Validate if customer is already logged out.
		if ($this->customer->isLogged ()) {
			$json ['redirect'] = $this->url->link ( 'checkout/checkout_onepage', '', 'SSL' );
		}
		
		// Validate cart has products and has stock.
		if ((! $this->cart->hasProducts () && empty ( $this->session->data ['vouchers'] )) || (! $this->cart->hasStock () && ! $this->config->get ( 'config_stock_checkout' ))) {
			$json ['redirect'] = $this->url->link ( 'checkout/cart' );
		}
		
		// Validate minimum quantity requirments.
		$products = $this->cart->getProducts ();
		
		foreach ( $products as $product ) {
			$product_total = 0;
			
			foreach ( $products as $product_2 ) {
				if ($product_2 ['product_id'] == $product ['product_id']) {
					$product_total += $product_2 ['quantity'];
				}
			}
			
			if ($product ['minimum'] > $product_total) {
				$json ['redirect'] = $this->url->link ( 'checkout/cart' );
				
				break;
			}
		}
		
		if (! $json) {
			if ((utf8_strlen ( $this->request->post ['firstname'] ) < 1) || (utf8_strlen ( $this->request->post ['firstname'] ) > 32)) {
				$json ['error'] ['firstname'] = $this->language->get ( 'error_firstname' );
			}
			
			if ((utf8_strlen ( $this->request->post ['lastname'] ) < 1) || (utf8_strlen ( $this->request->post ['lastname'] ) > 32)) {
				$json ['error'] ['lastname'] = $this->language->get ( 'error_lastname' );
			}
			if ((utf8_strlen ( $this->request->post ['email'] ) > 96) || ! preg_match ( '/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post ['email'] )) {
				$json ['error'] ['email'] = $this->language->get ( 'error_email' );
			}
			
			if ($this->model_account_customer->getTotalCustomersByEmail ( $this->request->post ['email'] )) {
				$json ['error'] ['warning'] = $this->language->get ( 'error_exists' ); // error email exist -> remove warning
			}
			
			if ((utf8_strlen ( $this->request->post ['telephone'] ) < 3) || (utf8_strlen ( $this->request->post ['telephone'] ) > 32)) {
				$json ['error'] ['telephone'] = $this->language->get ( 'error_telephone' );
			}
			
			if ((utf8_strlen ( $this->request->post ['password'] ) < 4) || (utf8_strlen ( $this->request->post ['password'] ) > 20)) {
				$json ['error'] ['password'] = $this->language->get ( 'error_password' );
			}
			
			if ($this->request->post ['confirm'] != $this->request->post ['password']) {
				$json ['error'] ['confirm'] = $this->language->get ( 'error_confirm' );
			}
			
			// ## ADDRESS SECTION
			// Customer Group
			$this->load->model ( 'account/customer_group' );
			
			if (isset ( $this->request->post ['customer_group_id'] ) && is_array ( $this->config->get ( 'config_customer_group_display' ) ) && in_array ( $this->request->post ['customer_group_id'], $this->config->get ( 'config_customer_group_display' ) )) {
				$customer_group_id = $this->request->post ['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get ( 'config_customer_group_id' ); // default for customer group when register
			}
			
			$customer_group = $this->model_account_customer_group->getCustomerGroup ( $customer_group_id );
			
			if (! $quick_register || isset ( $this->request->post ['use-address'] )) {
				
				if ((utf8_strlen ( $this->request->post ['address_1'] ) < 3) || (utf8_strlen ( $this->request->post ['address_1'] ) > 128)) {
					$json ['error'] ['address_1'] = $this->language->get ( 'error_address_1' );
				}
				
				if ((utf8_strlen ( $this->request->post ['city'] ) < 2) || (utf8_strlen ( $this->request->post ['city'] ) > 128)) {
					$json ['error'] ['city'] = $this->language->get ( 'error_city' );
				}
				
				$this->load->model ( 'localisation/country' );
				
				$country_info = $this->model_localisation_country->getCountry ( $this->request->post ['country_id'] );
				
				if ($country_info) {
					if ($country_info ['postcode_required'] && (utf8_strlen ( $this->request->post ['postcode'] ) < 2) || (utf8_strlen ( $this->request->post ['postcode'] ) > 10)) {
						$json ['error'] ['postcode'] = $this->language->get ( 'error_postcode' );
					}
				}
				
				if ($this->request->post ['country_id'] == '') {
					$json ['error'] ['country'] = $this->language->get ( 'error_country' );
				}
				
				if (! isset ( $this->request->post ['zone_id'] ) || $this->request->post ['zone_id'] == '') {
					$json ['error'] ['zone'] = $this->language->get ( 'error_zone' );
				}
				
				$this->load->model ( 'account/custom_field' );
				
				$custom_fields = $this->model_account_custom_field->getCustomFields ( $customer_group_id );
				
				foreach ( $custom_fields as $custom_field ) {
					if ($custom_field ['required'] && empty ( $this->request->post ['custom_field'] [$custom_field ['location']] [$custom_field ['custom_field_id']] )) {
						$json ['error'] ['custom_field' . $custom_field ['custom_field_id']] = sprintf ( $this->language->get ( 'error_custom_field' ), $custom_field ['name'] );
					}
				}
			} else {
				$this->request->post ['address_1'] = '';
				$this->request->post ['address_2'] = '';
				$this->request->post ['city'] = '';
				
				$this->load->model ( 'localisation/country' );
				$country_info = $this->model_localisation_country->getCountry ( $this->config->get ( 'config_country_id' ) );
				if ($country_info) {
					$this->request->post ['postcode'] = '';
				}
				
				$this->request->post ['zone_id'] = $this->config->get ( 'config_zone_id' );
			}
			
			if ($this->config->get ( 'config_account_id' )) {
				$this->load->model ( 'catalog/information' );
				
				$information_info = $this->model_catalog_information->getInformation ( $this->config->get ( 'config_account_id' ) );
				
				if ($information_info && ! isset ( $this->request->post ['agree'] )) {
					// $json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
					$json ['error'] ['agree'] = sprintf ( $this->language->get ( 'error_agree' ), $information_info ['title'] );
				}
			}
			
			// STORE REGISTER SESSION DATA.
			$this->session->data ['register'] ['firstname'] = $this->request->post ['firstname'];
			$this->session->data ['register'] ['lastname'] = $this->request->post ['lastname'];
			$this->session->data ['register'] ['email'] = $this->request->post ['email'];
			$this->session->data ['register'] ['telephone'] = $this->request->post ['telephone'];
			// $this->session->data['register']['password'] = $this->request->post['password'];
			// $this->session->data['register']['confirm'] = $this->request->post['confirm'];
			$this->session->data ['register'] ['company'] = ! empty ( $this->request->post ['company'] ) ? $this->request->post ['company'] : '';
			$this->session->data ['register'] ['customer_group_id'] = $customer_group_id;
			$this->session->data ['register'] ['fax'] = ! empty ( $this->request->post ['fax'] ) ? $this->request->post ['fax'] : '';
			$this->session->data ['register'] ['address_1'] = $this->request->post ['address_1'];
			$this->session->data ['register'] ['address_2'] = $this->request->post ['address_2'];
			$this->session->data ['register'] ['city'] = $this->request->post ['city'];
			$this->session->data ['register'] ['postcode'] = $this->request->post ['postcode'];
			$this->session->data ['register'] ['country_id'] = $this->request->post ['country_id'];
			$this->session->data ['register'] ['zone_id'] = $this->request->post ['zone_id'];
			$this->session->data ['register'] ['agree'] = isset ( $this->request->post ['agree'] ) ? 1 : 0;
			
			// for calculating tax, estimate shipping_methods/payment_methods
			$this->session->data ['payment_address'] = $this->session->data ['shipping_address'] = $this->session->data ['register'];
		}
		
		// ADD NEW CUSTOMER AND REMOVE REGISTER SESSION DATA, GUEST SESSION DATA.
		if (! $json) {
			$this->model_account_customer->addCustomer ( $this->request->post );
			
			if ($customer_group && ! $customer_group ['approval']) { // no need to approve new customer
				$this->customer->login ( $this->request->post ['email'], $this->request->post ['password'] );
				
				$this->load->model ( 'account/address' );
				
				$this->session->data ['payment_address'] = $this->model_account_address->getAddress ( $this->customer->getAddressId () );
				
				if (! empty ( $this->request->post ['shipping_address'] )) {
					$this->session->data ['shipping_address'] = $this->model_account_address->getAddress ( $this->customer->getAddressId () );
				}
				
				$json ['redirect'] = $this->url->link ( 'checkout/checkout_onepage' );
			} else {
				$json ['redirect'] = $this->url->link ( 'account/success' );
			}
			
			unset ( $this->session->data ['guest'] );
			unset ( $this->session->data ['register'] );
			unset ( $this->session->data ['payment_address'] );
			unset ( $this->session->data ['shipping_address'] );
			unset ( $this->session->data ['shipping_method'] );
			unset ( $this->session->data ['shipping_methods'] );
			unset ( $this->session->data ['payment_method'] );
			unset ( $this->session->data ['payment_methods'] );
		}
		
		$this->session->data ['account'] = 'register';
		$this->session->data ['account_view'] = 'register';
		
		ob_clean ();
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function validate_zone() { // for zone and country
		ob_start ();
		$this->load->language ( 'checkout/checkout' );
		$json = array ();
		$this->load->model ( 'localisation/country' );
		$this->load->model ( 'setting/setting' );
		
		$mmos_checkout_extra = $this->model_setting_setting->getSetting ( 'mmos_checkout', $this->config->get ( 'config_store_id' ) );
		$mmos_checkout = $mmos_checkout_extra ['mmos_checkout'];
		if ($mmos_checkout ['quick_register'] && ! $this->config->get ( 'config_vat' ) && ! $this->cart->hasShipping ()) {
			$quick_register = 1;
		} else {
			$quick_register = 0;
		}
		if ($quick_register) {
			if (isset ( $this->request->post ['use-address'] )) {
				$this->session->data ['register'] ['use_address'] = 1;
			} else {
				$this->session->data ['register'] ['use_address'] = 0;
			}
		}
		// OpenCart may not check for the country not exist
		$country_info = $this->model_localisation_country->getCountry ( $this->request->post ['country_id'] );
		
		if (! $country_info || $this->request->post ['country_id'] == '') {
			$json ['redirect'] = $this->url->link ( 'checkout/checkout_onepage', '', 'SSL' );
			$json ['error'] ['country'] = $this->language->get ( 'error_country' );
		}
		
		if (! isset ( $this->request->post ['zone_id'] )) { // || $this->request->post['zone_id'] == '') {
			$json ['redirect'] = $this->url->link ( 'checkout/checkout_onepage', '', 'SSL' );
			$json ['error'] ['zone'] = $this->language->get ( 'error_zone' );
		}
		if (! $json) {
			$this->session->data ['register'] ['country_id'] = $this->request->post ['country_id'];
			$this->session->data ['register'] ['zone_id'] = $this->request->post ['zone_id'];
			
			if ($country_info) {
				$this->session->data ['register'] ['country'] = $country_info ['name'];
				$this->session->data ['register'] ['iso_code_2'] = $country_info ['iso_code_2'];
				$this->session->data ['register'] ['iso_code_3'] = $country_info ['iso_code_3'];
				$this->session->data ['register'] ['address_format'] = $country_info ['address_format'];
			} else {
				$this->session->data ['register'] ['country'] = '';
				$this->session->data ['register'] ['iso_code_2'] = '';
				$this->session->data ['register'] ['iso_code_3'] = '';
				$this->session->data ['register'] ['address_format'] = '';
			}
			
			$this->load->model ( 'localisation/zone' );
			
			$zone_info = $this->model_localisation_zone->getZone ( $this->request->post ['zone_id'] );
			
			if ($zone_info) {
				$this->session->data ['register'] ['zone'] = $zone_info ['name'];
				$this->session->data ['register'] ['zone_code'] = $zone_info ['code'];
			} else {
				$this->session->data ['register'] ['zone'] = '';
				$this->session->data ['register'] ['zone_code'] = '';
			}
			// Default Payment Address/shipping address
			// for calculating tax, estimate shipping_methods/payment_methods
			$this->session->data ['payment_address'] = $this->session->data ['shipping_address'] = $this->session->data ['register'];
		}
		$this->session->data ['account'] = 'register';
		$this->session->data ['account_view'] = 'register';
		// for switch between guest & register shipping method
		if (isset ( $this->session->data ['checkout_register'] ['shipping_method'] )) {
			$this->session->data ['shipping_method'] = $this->session->data ['checkout_register'] ['shipping_method'];
		}
		if (isset ( $this->session->data ['checkout_register'] ['payment_method'] )) {
			$this->session->data ['payment_method'] = $this->session->data ['checkout_register'] ['payment_method'];
		}
		ob_end_clean ();
		$this->response->setOutput ( json_encode ( $json ) );
	}
}

?>
