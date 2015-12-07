<?php 
/**
 * register.php
 *
 * Registration management
 *
 * @author     Makai Lajos
 * @copyright  2015
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://opencart2oauth.opencart-api.com/schema_v2.0_oauth/
 */
require_once(DIR_SYSTEM . 'engine/restcontroller.php');

class ControllerRestRegister extends RestController {

	public function registerCustomer($data) {

		$this->language->load('checkout/checkout');

		$this->load->model('account/customer');

		$json = array('success' => true);

		// Validate if customer is logged in.
		if ($this->customer->isLogged()) {
			$json['error']		= "User already is logged";	
			$json['success'] = false;
		} 


		// Validate minimum quantity requirments.			
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}		

			if ($product['minimum'] > $product_total) {
				//$json['redirect'] = $this->url->link('checkout/cart');
				$json['success'] = false;
				$json['error']['minimum'] = "Product minimum > product total";
				break;
			}				
		}

		if ($json['success']) {					
			if ((utf8_strlen(trim($data['firstname'])) < 1) || (utf8_strlen(trim($data['firstname'])) > 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}

			if ((utf8_strlen(trim($data['lastname'])) < 1) || (utf8_strlen(trim($data['lastname'])) > 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}

			if ((utf8_strlen($data['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $data['email'])) {
				$json['error']['email'] = $this->language->get('error_email');
			}

			if ($this->model_account_customer->getTotalCustomersByEmail($data['email'])) {
				$json['error']['warning'] = $this->language->get('error_exists');
			}

			if ((utf8_strlen($data['telephone']) < 3) || (utf8_strlen($data['telephone']) > 32)) {
				$json['error']['telephone'] = $this->language->get('error_telephone');
			}

			if ((utf8_strlen(trim($data['address_1'])) < 3) || (utf8_strlen(trim($data['address_1'])) > 128)) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
			}

			if ((utf8_strlen(trim($data['city'])) < 2) || (utf8_strlen(trim($data['city'])) > 128)) {
				$json['error']['city'] = $this->language->get('error_city');
			}

			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($data['country_id']);

			if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($data['postcode'])) < 2 || utf8_strlen(trim($data['postcode'])) > 10)) {
				$json['error']['postcode'] = $this->language->get('error_postcode');
			}

			if ($data['country_id'] == '') {
				$json['error']['country'] = $this->language->get('error_country');
			}

			if (!isset($data['zone_id']) || $data['zone_id'] == '') {
				$json['error']['zone'] = $this->language->get('error_zone');
			}

			if ((utf8_strlen($data['password']) < 4) || (utf8_strlen($data['password']) > 20)) {
				$json['error']['password'] = $this->language->get('error_password');
			}

			if ($data['confirm'] != $data['password']) {
				$json['error']['confirm'] = $this->language->get('error_confirm');
			}

			if ($this->config->get('config_account_id')) {
				$this->load->model('catalog/information');

				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

				if ($information_info && !isset($data['agree'])) {
					$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
				}
			}

			// Customer Group
			if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
				$customer_group_id = $data['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}

			// Custom field validation
			$this->load->model('account/custom_field');

			$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['required'] && empty($data['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
					$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				}
			}
		}

		if (!isset($json['error']) && empty($json['error'])) {
			$customer_id = $this->model_account_customer->addCustomer($data);

			$this->session->data['account'] = 'register';

			$this->load->model('account/customer_group');

			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

			if ($customer_group_info && !$customer_group_info['approval']) {
				$this->customer->login($data['email'], $data['password']);

				$data['session_id'] = session_id();
				unset($data['password']);
				unset($data['confirm']);
				unset($data['agree']);
				$json['data'] = $data;

				// Default Payment Address
				$this->load->model('account/address');

				$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());

				if (!empty($data['shipping_address'])) {
					$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
				}
			} 

			unset($this->session->data['guest']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $customer_id,
				'name'        => $data['firstname'] . ' ' . $data['lastname']
			);

			$this->model_account_activity->addActivity('register', $activity_data);
		} else{
			$json['success'] = false;
		}

        $this->sendResponse($json);
	} 

	/*
	* GUEST FUNCTIONS
	*/	
	public function register() {

		$this->checkPlugin();

		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
			//add customer
			$requestjson = file_get_contents('php://input');
		
			$requestjson = json_decode($requestjson, true);           

			if (!empty($requestjson)) {
				$this->registerCustomer($requestjson);
			}else {
                $this->sendResponse(array('success' => false));
			}
		}else {
            $json["error"]		= "Only POST request method allowed";
            $json["success"]	= false;

            $this->sendResponse($json);
		}    
    }
}