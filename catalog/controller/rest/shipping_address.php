<?php
/**
 * shipping_address.php
 *
 * Shipping address management
 *
 * @author     Makai Lajos
 * @copyright  2015
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://opencart2oauth.opencart-api.com/schema_v2.0_oauth/
 */
require_once (DIR_SYSTEM . 'engine/restcontroller.php');
class ControllerRestShippingAddress extends RestController {
	
	/*
	 * Get Shipping addresses
	 */
	public function listShippingAddresses() {
		$json = array (
				'success' => true 
		);
		
		$this->language->load ( 'checkout/checkout' );
		
		if (isset ( $this->session->data ['shipping_address'] ['address_id'] )) {
			$data ['address_id'] = $this->session->data ['shipping_address'] ['address_id'];
		} else {
			$data ['address_id'] = $this->customer->getAddressId ();
		}
		
		$this->load->model ( 'account/address' );
		
		$addresses = array ();
		foreach ( $this->model_account_address->getAddresses () as $address ) {
			$addresses [] = $address;
		}
		$data ['addresses'] = $addresses;
		
		if (isset ( $this->session->data ['shipping_postcode'] )) {
			$data ['postcode'] = $this->session->data ['shipping_postcode'];
		} else {
			$data ['postcode'] = '';
		}
		
		if (isset ( $this->session->data ['shipping_country_id'] )) {
			$data ['country_id'] = $this->session->data ['shipping_country_id'];
		} else {
			$data ['country_id'] = $this->config->get ( 'config_country_id' );
		}
		
		if (isset ( $this->session->data ['shipping_zone_id'] )) {
			$data ['zone_id'] = $this->session->data ['shipping_zone_id'];
		} else {
			$data ['zone_id'] = '';
		}
		
		if (count ( $data ['addresses'] ) > 0) {
			$json ["data"] = $data;
		} else {
			$json ["success"] = false;
			$json ["error"] = "No shipping address found";
		}
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * Save shipping address to database
	 */
	public function saveShippingAddress($post) {
		$this->language->load ( 'checkout/checkout' );
		
		$json = array (
				'success' => true 
		);
		
		// Validate if customer is logged in.
		if (! $this->customer->isLogged ()) {
			$json ["error"] = "User is not logged in";
			$json ["success"] = false;
		}
		
		// Validate if shipping is required. If not the customer should not have reached this page.
		if (! $this->cart->hasShipping ()) {
			$json ["error"] = "The customer should not have reached this page, becouse shipping is not required.";
			// $json["success"] = false;
		}
		
		// Validate cart has products and has stock.
		if ((! $this->cart->hasProducts () && empty ( $this->session->data ['vouchers'] )) || (! $this->cart->hasStock () && ! $this->config->get ( 'config_stock_checkout' ))) {
			$json ["error"] = "Validate cart has products and has stock failed";
			$json ["success"] = false;
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
				$json ['success'] = false;
				$json ['error'] ['minimum'] = "Product minimum > product total";
				
				break;
			}
		}
		
		if ($json ['success']) {
			if (isset ( $post ['shipping_address'] ) && $post ['shipping_address'] == 'existing') {
				$this->load->model ( 'account/address' );
				
				if (empty ( $post ['address_id'] )) {
					$json ['error'] ['warning'] = $this->language->get ( 'error_address' );
					$json ['success'] = false;
				} elseif (! in_array ( $post ['address_id'], array_keys ( $this->model_account_address->getAddresses () ) )) {
					$json ['error'] ['warning'] = $this->language->get ( 'error_address' );
					$json ['success'] = false;
				}
				
				if ($json ['success']) {
					$this->session->data ['shipping_address_id'] = $post ['address_id'];
					
					$this->session->data ['shipping_address'] = $this->model_account_address->getAddress ( $post ['address_id'] );
					
					unset ( $this->session->data ['shipping_method'] );
					unset ( $this->session->data ['shipping_methods'] );
				}
			}
			
			if ($post ['shipping_address'] == 'new') {
				if ((utf8_strlen ( $post ['firstname'] ) < 1) || (utf8_strlen ( $post ['firstname'] ) > 32)) {
					$json ['error'] ['firstname'] = $this->language->get ( 'error_firstname' );
					$json ['success'] = false;
				}
				
				if ((utf8_strlen ( $post ['lastname'] ) < 1) || (utf8_strlen ( $post ['lastname'] ) > 32)) {
					$json ['error'] ['lastname'] = $this->language->get ( 'error_lastname' );
					$json ['success'] = false;
				}
				
				if ((utf8_strlen ( $post ['address_1'] ) < 3) || (utf8_strlen ( $post ['address_1'] ) > 128)) {
					$json ['error'] ['address_1'] = $this->language->get ( 'error_address_1' );
					$json ['success'] = false;
				}
				
				if ((utf8_strlen ( $post ['city'] ) < 2) || (utf8_strlen ( $post ['city'] ) > 128)) {
					$json ['error'] ['city'] = $this->language->get ( 'error_city' );
					$json ['success'] = false;
				}
				
				$this->load->model ( 'localisation/country' );
				
				$country_info = $this->model_localisation_country->getCountry ( $post ['country_id'] );
				
				if ($country_info && $country_info ['postcode_required'] && (utf8_strlen ( $post ['postcode'] ) < 2) || (utf8_strlen ( $post ['postcode'] ) > 10)) {
					$json ['error'] ['postcode'] = $this->language->get ( 'error_postcode' );
					$json ['success'] = false;
				}
				
				if ($post ['country_id'] == '') {
					$json ['error'] ['country'] = $this->language->get ( 'error_country' );
					$json ['success'] = false;
				}
				
				if (! isset ( $post ['zone_id'] ) || $post ['zone_id'] == '') {
					$json ['error'] ['zone'] = $this->language->get ( 'error_zone' );
					$json ['success'] = false;
				}
				
				// Custom field validation
				$this->load->model ( 'account/custom_field' );
				
				$custom_fields = $this->model_account_custom_field->getCustomFields ( $this->config->get ( 'config_customer_group_id' ) );
				
				foreach ( $custom_fields as $custom_field ) {
					if (($custom_field ['location'] == 'address') && $custom_field ['required'] && empty ( $post ['custom_field'] [$custom_field ['custom_field_id']] )) {
						$json ['error'] ['custom_field' . $custom_field ['custom_field_id']] = sprintf ( $this->language->get ( 'error_custom_field' ), $custom_field ['name'] );
						$json ['success'] = false;
					}
				}
				
				if ($json ['success']) {
					// Default Shipping Address
					$this->load->model ( 'account/address' );
					$address_id = $this->model_account_address->addAddress ( $post );
					
					$this->session->data ['shipping_address'] = $this->model_account_address->getAddress ( $address_id );
					
					unset ( $this->session->data ['shipping_method'] );
					unset ( $this->session->data ['shipping_methods'] );
					
					$this->load->model ( 'account/activity' );
					
					$activity_data = array (
							'customer_id' => $this->customer->getId (),
							'name' => $this->customer->getFirstName () . ' ' . $this->customer->getLastName () 
					);
					
					$this->model_account_activity->addActivity ( 'address_add', $activity_data );
				}
			}
		}
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * SHIPPING ADDRESS FUNCTIONS
	 */
	public function shippingaddress() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			// get payment addresses
			$this->listShippingAddresses ();
		} else if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
			// save payment address information to session
			$requestjson = file_get_contents ( 'php://input' );
			
			$requestjson = json_decode ( $requestjson, true );
			
			if (! empty ( $requestjson )) {
				$this->saveShippingAddress ( $requestjson );
			} else {
				$this->sendResponse ( array (
						'success' => false 
				) );
			}
		}
	}
}