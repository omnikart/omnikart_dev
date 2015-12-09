<?php 
/**
 * guest_shipping.php
 *
 * Guest customer shipping management
 *
 * @author     Makai Lajos
 * @copyright  2015
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://opencart2oauth.opencart-api.com/schema_v2.0_oauth/
 */
require_once(DIR_SYSTEM . 'engine/restcontroller.php');

class ControllerRestGuestShipping extends RestController {

	/* 
	* Get guest shipping information
	*/
	public function getGuestShipping() {
		
		$json = array('success' => true);


		if (isset($this->session->data['shipping_address']['firstname'])) {
			$data['firstname'] = $this->session->data['shipping_address']['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->session->data['shipping_address']['lastname'])) {
			$data['lastname'] = $this->session->data['shipping_address']['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (isset($this->session->data['shipping_address']['company'])) {
			$data['company'] = $this->session->data['shipping_address']['company'];
		} else {
			$data['company'] = '';
		}

		if (isset($this->session->data['shipping_address']['address_1'])) {
			$data['address_1'] = $this->session->data['shipping_address']['address_1'];
		} else {
			$data['address_1'] = '';
		}

		if (isset($this->session->data['shipping_address']['address_2'])) {
			$data['address_2'] = $this->session->data['shipping_address']['address_2'];
		} else {
			$data['address_2'] = '';
		}

		if (isset($this->session->data['shipping_address']['postcode'])) {
			$data['postcode'] = $this->session->data['shipping_address']['postcode'];
		} else {
			$data['postcode'] = '';
		}

		if (isset($this->session->data['shipping_address']['city'])) {
			$data['city'] = $this->session->data['shipping_address']['city'];
		} else {
			$data['city'] = '';
		}

		if (isset($this->session->data['shipping_address']['country_id'])) {
			$data['country_id'] = $this->session->data['shipping_address']['country_id'];
		} else {
			$data['country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->session->data['shipping_address']['zone_id'])) {
			$data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
		} else {
			$data['zone_id'] = '';
		}

		//$this->load->model('localisation/country');

		//$data['countries'] = $this->model_localisation_country->getCountries();

		// Custom Fields
		$this->load->model('account/custom_field');

		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields($this->session->data['guest']['customer_group_id']);

		if (isset($this->session->data['shipping_address']['custom_field'])) {
			$data['address_custom_field'] = $this->session->data['shipping_address']['custom_field'];
		} else {
			$data['address_custom_field'] = array();
		}


		
		$json["data"] = $data;

        $this->sendResponse($json);
	}
	
	/* 
	* Save guest shipping address
	*/
	public function saveGuestShipping($data) {
		$this->language->load('checkout/checkout');
		
		$json = array('success' => true);
		
		// Validate if customer is logged in.
		if ($this->customer->isLogged()) {
			$json["error"] = "User is logged in, not guest user";
			$json["success"] = false;
		} 			
		
		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {

			$json["error"] = "Validate cart has products and has stock failed";
			$json["success"] = false;	
		}
		
		// Check if guest checkout is avaliable.	
		if (!$this->config->get('config_checkout_guest') || $this->config->get('config_customer_price') || $this->cart->hasDownload()) {
			$json["error"] = "Guest checkout is not avaliable";
			$json["success"] = false;
		} 
		
		if ($json['success']) {
			if(isset($data['firstname'])){
				if ((utf8_strlen($data['firstname']) < 1) || (utf8_strlen($data['firstname']) > 32)) {
					$json['error']['firstname'] = $this->language->get('error_firstname');
					$json['success'] = false;
				}
			}else{
				$json['error']['firstname'] = $this->language->get('error_firstname');
				$json['success'] = false;		
			}
			
			if(isset($data['firstname'])){
				if ((utf8_strlen($data['lastname']) < 1) || (utf8_strlen($data['lastname']) > 32)) {
					$json['error']['lastname'] = $this->language->get('error_lastname');
					$json['success'] = false;
				}
			}else{
				$json['error']['lastname'] = $this->language->get('error_lastname');
				$json['success'] = false;		
			}
			
			if(isset($data['address_1'])){
				if ((utf8_strlen($data['address_1']) < 3) || (utf8_strlen($data['address_1']) > 128)) {
					$json['error']['address_1'] = $this->language->get('error_address_1');
					$json['success'] = false;
				}
			}else{
				$json['error']['address_1'] = $this->language->get('error_address_1');
				$json['success'] = false;		
			}

			if(isset($data['city'])){
				if ((utf8_strlen($data['city']) < 2) || (utf8_strlen($data['city']) > 128)) {
					$json['error']['city'] = $this->language->get('error_city');
					$json['success'] = false;
				}
			}else{
				$json['error']['city'] = $this->language->get('error_city');
				$json['success'] = false;		
			}


			// Custom field validation
			$this->load->model('account/custom_field');

			$custom_fields = $this->model_account_custom_field->getCustomFields($this->session->data['guest']['customer_group_id']);

			foreach ($custom_fields as $custom_field) {
				if (($custom_field['location'] == 'address') && $custom_field['required'] && empty($data['custom_field'][$custom_field['custom_field_id']])) {
					$json['success'] = false;
					$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				}
			}

			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($data['country_id']);
			
			if ($country_info && $country_info['postcode_required'] && (utf8_strlen($data['postcode']) < 2) || (utf8_strlen($data['postcode']) > 10)) {
				$json['error']['postcode'] = $this->language->get('error_postcode');
				$json['success'] = false;
			}
	
			if ($data['country_id'] == '') {
				$json['error']['country'] = $this->language->get('error_country');
				$json['success'] = false;
			}
			
			if (!isset($data['zone_id']) || $data['zone_id'] == '') {
				$json['error']['zone'] = $this->language->get('error_zone');
				$json['success'] = false;
			}	
		}
		
		if ($json['success']) {
			$this->session->data['shipping_address']['firstname'] = $data['firstname'];
			$this->session->data['shipping_address']['lastname'] = $data['lastname'];
			$this->session->data['shipping_address']['company'] = $data['company'];
			$this->session->data['shipping_address']['address_1'] = $data['address_1'];
			$this->session->data['shipping_address']['address_2'] = $data['address_2'];
			$this->session->data['shipping_address']['postcode'] = $data['postcode'];
			$this->session->data['shipping_address']['city'] = $data['city'];
			$this->session->data['shipping_address']['country_id'] = $data['country_id'];
			$this->session->data['shipping_address']['zone_id'] = $data['zone_id'];

			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($data['country_id']);

			if ($country_info) {
				$this->session->data['shipping_address']['country'] = $country_info['name'];
				$this->session->data['shipping_address']['iso_code_2'] = $country_info['iso_code_2'];
				$this->session->data['shipping_address']['iso_code_3'] = $country_info['iso_code_3'];
				$this->session->data['shipping_address']['address_format'] = $country_info['address_format'];
			} else {
				$this->session->data['shipping_address']['country'] = '';
				$this->session->data['shipping_address']['iso_code_2'] = '';
				$this->session->data['shipping_address']['iso_code_3'] = '';
				$this->session->data['shipping_address']['address_format'] = '';
			}

			$this->load->model('localisation/zone');

			$zone_info = $this->model_localisation_zone->getZone($data['zone_id']);

			if ($zone_info) {
				$this->session->data['shipping_address']['zone'] = $zone_info['name'];
				$this->session->data['shipping_address']['zone_code'] = $zone_info['code'];
			} else {
				$this->session->data['shipping_address']['zone'] = '';
				$this->session->data['shipping_address']['zone_code'] = '';
			}

			if (isset($data['custom_field'])) {
				$this->session->data['shipping_address']['custom_field'] = $data['custom_field'];
			} else {
				$this->session->data['shipping_address']['custom_field'] = array();
			}

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);

		}
        $this->sendResponse($json);
	}

	/*
	* GUEST	SHIPPING FUNCTIONS
	*/	
	public function guestshipping() {

		$this->checkPlugin();

		if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
			//get guest shipping
			$this->getGuestShipping();
		}else if( $_SERVER['REQUEST_METHOD'] === 'POST' ){
			//save customer shipping
			$requestjson = file_get_contents('php://input');
		
			$requestjson = json_decode($requestjson, true);           

			if (!empty($requestjson)) {
				$this->saveGuestShipping($requestjson);
			}else {
                $this->sendResponse(array('success' => false));
			}
		}
    }
}