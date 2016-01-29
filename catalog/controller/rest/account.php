<?php
/**
 * account.php
 *
 * Account management
 *
 * @author     Makai Lajos
 * @copyright  2015
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://opencart2oauth.opencart-api.com/schema_v2.0_oauth/
 */
require_once (DIR_SYSTEM . 'engine/restcontroller.php');
class ControllerRestAccount extends RestController {
	private $error = array ();
	public function getAccount() {
		$json = array (
				'success' => true 
		);
		
		$this->language->load ( 'account/edit' );
		
		if (! $this->customer->isLogged ()) {
			$json ["error"] = "User is not logged in";
			$json ["success"] = false;
		}
		
		if ($json ["success"]) {
			
			$this->load->model ( 'account/customer' );
			
			$user = $this->model_account_customer->getCustomer ( $this->customer->getId () );
			
			// Custom Fields
			$this->load->model ( 'account/custom_field' );
			
			$user ['custom_fields'] = $this->model_account_custom_field->getCustomFields ( $this->config->get ( 'config_customer_group_id' ) );
			
			if (strpos ( VERSION, '2.1.' ) === false) {
				$user ['account_custom_field'] = unserialize ( $user ['custom_field'] );
			} else {
				$user ['account_custom_field'] = json_decode ( $user ['custom_field'], true );
			}
			
			unset ( $user ["password"] );
			unset ( $user ["salt"] );
			unset ( $user ['custom_field'] );
			$json ["data"] = $user;
		}
		
		$this->sendResponse ( $json );
	}
	public function saveAccount($post) {
		$json = array (
				'success' => true 
		);
		
		if (! $this->customer->isLogged ()) {
			$json ["error"] = "User is not logged in";
			$json ["success"] = false;
		} else {
			if ($this->validate ( $post )) {
				$this->load->model ( 'account/customer' );
				$this->model_account_customer->editCustomer ( $post );
			} else {
				$json ["error"] = $this->error;
				$json ["success"] = false;
			}
		}
		
		$this->sendResponse ( $json );
	}
	protected function validate($post) {
		$this->load->model ( 'account/customer' );
		$this->language->load ( 'account/edit' );
		
		if (isset ( $post ['firstname'] )) {
			if ((utf8_strlen ( $post ['firstname'] ) < 1) || (utf8_strlen ( $post ['firstname'] ) > 32)) {
				$this->error ['firstname'] = $this->language->get ( 'error_firstname' );
			}
		} else {
			$this->error ['firstname'] = $this->language->get ( 'error_firstname' );
		}
		
		if (isset ( $post ['lastname'] )) {
			if ((utf8_strlen ( $post ['lastname'] ) < 1) || (utf8_strlen ( $post ['lastname'] ) > 32)) {
				$this->error ['lastname'] = $this->language->get ( 'error_lastname' );
			}
		} else {
			$this->error ['lastname'] = $this->language->get ( 'error_lastname' );
		}
		
		if (isset ( $post ['email'] )) {
			if ((utf8_strlen ( $post ['email'] ) > 96) || ! preg_match ( '/^[^\@]+@.*\.[a-z]{2,6}$/i', $post ['email'] )) {
				$this->error ['email'] = $this->language->get ( 'error_email' );
			}
		} else {
			$this->error ['email'] = $this->language->get ( 'error_email' );
		}
		
		if (isset ( $post ['email'] )) {
			if (($this->customer->getEmail () != $post ['email']) && $this->model_account_customer->getTotalCustomersByEmail ( $post ['email'] )) {
				$this->error ['warning'] = $this->language->get ( 'error_exists' );
			}
		} else {
			$this->error ['email'] = "E-mail is required";
		}
		
		if (isset ( $post ['telephone'] )) {
			
			if ((utf8_strlen ( $post ['telephone'] ) < 3) || (utf8_strlen ( $post ['telephone'] ) > 32)) {
				$this->error ['telephone'] = $this->language->get ( 'error_telephone' );
			}
		} else {
			$this->error ['telephone'] = $this->language->get ( 'error_telephone' );
		}
		
		// Custom field validation
		$this->load->model ( 'account/custom_field' );
		
		$custom_fields = $this->model_account_custom_field->getCustomFields ( $this->config->get ( 'config_customer_group_id' ) );
		
		foreach ( $custom_fields as $custom_field ) {
			if (($custom_field ['location'] == 'account') && $custom_field ['required'] && empty ( $post ['custom_field'] [$custom_field ['custom_field_id']] )) {
				$this->error ['custom_field'] [$custom_field ['custom_field_id']] = sprintf ( $this->language->get ( 'error_custom_field' ), $custom_field ['name'] );
			}
		}
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * ACCOUNT FUNCTIONS
	 */
	public function account() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			// get account details
			
			$this->getAccount ();
		} else if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
			// modify account
			$requestjson = file_get_contents ( 'php://input' );
			
			$requestjson = json_decode ( $requestjson, true );
			
			if (! empty ( $requestjson )) {
				$this->saveAccount ( $requestjson );
			} else {
				$this->sendResponse ( array (
						'success' => false 
				) );
			}
		}
	}
	public function password() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'PUT') {
			// modify account password
			$requestjson = file_get_contents ( 'php://input' );
			
			$requestjson = json_decode ( $requestjson, true );
			
			if (! empty ( $requestjson )) {
				$this->changePassword ( $requestjson );
			} else {
				$this->sendResponse ( array (
						'success' => false 
				) );
			}
		}
	}
	public function changePassword($post) {
		$json = array (
				'success' => true 
		);
		
		if (! $this->customer->isLogged ()) {
			$json ["error"] = "User is not logged in";
			$json ["success"] = false;
		} else {
			
			$customer_query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape ( utf8_strtolower ( $this->customer->getEmail () ) ) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape ( $post ['old_password'] ) . "'))))) OR password = '" . $this->db->escape ( md5 ( $post ['old_password'] ) ) . "') AND status = '1' AND approved = '1'" );
			
			if (! $customer_query->num_rows) {
				$json ["error"] ['old_password'] = "Old password incorrect";
			}
			
			if ((utf8_strlen ( $post ['password'] ) < 4) || (utf8_strlen ( $post ['password'] ) > 20)) {
				$json ["error"] ['password'] = $this->language->get ( 'error_password' );
			}
			
			if ($post ['confirm'] != $post ['password']) {
				$json ["error"] ['confirm'] = $this->language->get ( 'error_confirm' );
			}
			
			if (empty ( $json ["error"] )) {
				$this->load->model ( 'account/customer' );
				
				$this->model_account_customer->editPassword ( $this->customer->getEmail (), $post ['password'] );
				
				// Add to activity log
				$this->load->model ( 'account/activity' );
				
				$activity_data = array (
						'customer_id' => $this->customer->getId (),
						'name' => $this->customer->getFirstName () . ' ' . $this->customer->getLastName () 
				);
				
				$this->model_account_activity->addActivity ( 'password', $activity_data );
			} else {
				$json ["success"] = false;
			}
		}
		
		$this->sendResponse ( $json );
	}
	public function customfield() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			
			$this->load->model ( 'account/custom_field' );
			
			// Customer Group
			if (isset ( $this->request->get ['customer_group_id'] ) && is_array ( $this->config->get ( 'config_customer_group_display' ) ) && in_array ( $this->request->get ['customer_group_id'], $this->config->get ( 'config_customer_group_display' ) )) {
				$customer_group_id = $this->request->get ['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get ( 'config_customer_group_id' );
			}
			
			$json ['custom_fields'] = $this->model_account_custom_field->getCustomFields ( $customer_group_id );
			
			/*
			 * $custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);
			 *
			 * foreach ($custom_fields as $custom_field) {
			 * $json[] = array(
			 * 'custom_field_id' => $custom_field['custom_field_id'],
			 * 'required' => $custom_field['required']
			 * );
			 * }
			 */
		} else {
			$json ["error"] = "Only GET request method allowed";
			$json ["success"] = false;
		}
		$this->sendResponse ( $json );
	}
	private function editAddress($id, $data) {
		$this->load->model ( 'account/address' );
		
		$json = $this->validateAdress ( $data );
		
		if ($json ['success']) {
			$this->model_account_address->editAddress ( $id, $data );
		}
		
		$this->sendResponse ( $json );
	}
	private function addAddress($data) {
		$json = $this->validateAdress ( $data );
		$this->load->model ( 'account/address' );
		
		if ($json ['success']) {
			$id = $this->model_account_address->addAddress ( $data );
			if ($id) {
				$json ['address_id'] = $id;
			} else {
				$json ['success'] = false;
			}
		}
		
		$this->sendResponse ( $json );
	}
	private function deleteAddress($id) {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'account/address' );
		$this->model_account_address->deleteAddress ( $id );
		$this->sendResponse ( $json );
	}
	private function getAddress($id) {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'account/address' );
		
		$address = $this->model_account_address->getAddress ( $id );
		if (! empty ( $address )) {
			// Custom Fields
			$this->load->model ( 'account/custom_field' );
			
			$address ['custom_fields'] = $this->model_account_custom_field->getCustomFields ( $this->config->get ( 'config_customer_group_id' ) );
			$json ["data"] = $address;
		} else {
			$json ['success'] = false;
		}
		
		$this->sendResponse ( $json );
	}
	private function listAddress() {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'account/address' );
		
		$data ['addresses'] = $this->model_account_address->getAddresses ();
		
		// Custom Fields
		$this->load->model ( 'account/custom_field' );
		
		$data ['custom_fields'] = $this->model_account_custom_field->getCustomFields ( $this->config->get ( 'config_customer_group_id' ) );
		
		if (count ( $data ['addresses'] ) > 0) {
			$json ["data"] ["addresses"] = array ();
			foreach ( $data ['addresses'] as $address ) {
				$json ["data"] ["addresses"] [] = $address;
			}
		} else {
			$json ["success"] = false;
			$json ["error"] = "No address found";
		}
		
		$this->sendResponse ( $json );
	}
	public function validateAdress($post) {
		$json = array (
				'success' => true 
		);
		
		if ((utf8_strlen ( trim ( $post ['firstname'] ) ) < 1) || (utf8_strlen ( trim ( $post ['firstname'] ) ) > 32)) {
			$json ['error'] ['firstname'] = $this->language->get ( 'error_firstname' );
			$json ['success'] = false;
		}
		
		if ((utf8_strlen ( trim ( $post ['lastname'] ) ) < 1) || (utf8_strlen ( trim ( $post ['lastname'] ) ) > 32)) {
			$json ['error'] ['lastname'] = $this->language->get ( 'error_lastname' );
			$json ['success'] = false;
		}
		
		if ((utf8_strlen ( trim ( $post ['address_1'] ) ) < 3) || (utf8_strlen ( trim ( $post ['address_1'] ) ) > 128)) {
			$json ['error'] ['address_1'] = $this->language->get ( 'error_address_1' );
			$json ['success'] = false;
		}
		
		if ((utf8_strlen ( $post ['city'] ) < 2) || (utf8_strlen ( $post ['city'] ) > 32)) {
			$json ['error'] ['city'] = $this->language->get ( 'error_city' );
			$json ['success'] = false;
		}
		
		$this->load->model ( 'localisation/country' );
		
		$country_info = $this->model_localisation_country->getCountry ( $post ['country_id'] );
		
		if ($country_info && $country_info ['postcode_required'] && (utf8_strlen ( trim ( $post ['postcode'] ) ) < 2 || utf8_strlen ( trim ( $post ['postcode'] ) ) > 10)) {
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
		
		return $json;
	}
	
	/*
	 * ADDRESS FUNCTIONS
	 */
	public function address() {
		$this->checkPlugin ();
		
		if (! $this->customer->isLogged ()) {
			$this->sendResponse ( array (
					'success' => false,
					"error" => "User is not logged" 
			) );
		} else {
			if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
				if (isset ( $this->request->get ['id'] ) && ctype_digit ( $this->request->get ['id'] )) {
					$this->getAddress ( $this->request->get ['id'] );
				} else {
					$this->listAddress ();
				}
			} elseif ($_SERVER ['REQUEST_METHOD'] === 'POST') {
				$requestjson = file_get_contents ( 'php://input' );
				$requestjson = json_decode ( $requestjson, true );
				
				if (! empty ( $requestjson )) {
					$this->addAddress ( $requestjson );
				} else {
					$this->sendResponse ( array (
							'success' => false 
					) );
				}
			} elseif ($_SERVER ['REQUEST_METHOD'] === 'PUT') {
				$requestjson = file_get_contents ( 'php://input' );
				$requestjson = json_decode ( $requestjson, true );
				
				if (isset ( $this->request->get ['id'] ) && ctype_digit ( $this->request->get ['id'] ) && ! empty ( $requestjson )) {
					$this->editAddress ( $this->request->get ['id'], $requestjson );
				} else {
					$this->sendResponse ( array (
							'success' => false 
					) );
				}
			} elseif ($_SERVER ['REQUEST_METHOD'] === 'DELETE') {
				if (isset ( $this->request->get ['id'] ) && ctype_digit ( $this->request->get ['id'] )) {
					$this->deleteAddress ( $this->request->get ['id'] );
				}
			}
		}
	}
}
