<?php  

/**
 * login.php
 *
 * Login management
 *
 * @author     Makai Lajos
 * @copyright  2015
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://opencart2oauth.opencart-api.com/schema_v2.0_oauth/
 */
require_once(DIR_SYSTEM . 'engine/restcontroller.php');

class ControllerRestLogin extends RestController {
	
	/*
	* Login user
	*/
	public function login() {

		$this->checkPlugin();
		
		$json = array('success' => true);
		
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
			
			$requestjson = file_get_contents('php://input');
		
			$requestjson = json_decode($requestjson, true);

			$post		 = $requestjson;

			$this->language->load('checkout/checkout');
				
			if ($this->customer->isLogged()) {
				$json['error']		= "User already is logged";			
				$json['success']	= false;			
			}	
			
			if ($json['success']) {
				if (!$this->customer->login($post['email'], $post['password'])) {
					$json['error']['warning'] = $this->language->get('error_login');
					$json['success']	= false;
				}
			
				$this->load->model('account/customer');
			
				$customer_info = $this->model_account_customer->getCustomerByEmail($post['email']);
				
				if ($customer_info && !$customer_info['approved']) {
					$json['error']['warning'] = $this->language->get('error_approved');
					$json['success']	= false;
				}		
			}
			
			if ($json['success']) {
				unset($this->session->data['guest']);
					
				// Default Addresses
				$this->load->model('account/address');
					
				if ($this->config->get('config_tax_customer') == 'payment') {
					$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
				}	

				if ($this->config->get('config_tax_customer') == 'shipping') {
					$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
				}

				// Add to activity log
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
				);

				$this->model_account_activity->addActivity('login', $activity_data);				

				unset($customer_info['password']);
                unset($customer_info['token']);
                unset($customer_info['salt']);

				$customer_info["session"] = session_id();
				
				// Custom Fields
				$this->load->model('account/custom_field');

				$customer_info['custom_fields'] = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

                if(strpos(VERSION, '2.1.') === false){
                    $customer_info['account_custom_field'] = unserialize($customer_info['custom_field']);
                } else {
                    $customer_info['account_custom_field'] = json_decode($customer_info['custom_field'], true);
                }

				unset($customer_info['custom_field']);

				$json['data'] = $customer_info;
			}
						
		}else {
				$json["error"]		= "Only POST request method allowed";
				$json["success"]	= false;
		}

        $this->sendResponse($json);
	}
}