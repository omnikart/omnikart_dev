<?php  
/**
 * payment_method.php
 *
 * Payment method management
 *
 * @author     Makai Lajos
 * @copyright  2015
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://opencart2oauth.opencart-api.com/schema_v2.0_oauth/
 */
require_once(DIR_SYSTEM . 'engine/restcontroller.php');

class ControllerRestPaymentMethod extends RestController {

	/*
	* Get payment methods
	*/
  	public function listPayments() {

		$json = array('success' => true);

		$this->language->load('checkout/checkout');
		
		$this->load->model('account/address');
		
		if (isset($this->session->data['payment_address'])) {
			// Selected payment methods should be from cart sub total not total!
			$total = $this->cart->getSubTotal();

			$this->load->model('extension/extension');

			$results = $this->model_extension_extension->getExtensions('payment');

			$recurring = $this->cart->hasRecurringProducts();

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('payment/' . $result['code']);

					$method = $this->{'model_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);

					if ($method) {
						if ($recurring) {
							if (method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_payment_' . $result['code']}->recurringPayments()) {
								$method_data[$result['code']] = $method;
							}
						} else {
							$method_data[$result['code']] = $method;
						}
					}
				}
			}

			$sort_order = array();

			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $method_data);

			$this->session->data['payment_methods'] = $method_data;			
		}			
		
   
		if (empty($this->session->data['payment_methods'])) {
			$data['error_warning'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
			$json["success"] = false;
		} else {
			$data['error_warning'] = '';
		}	

		if (isset($this->session->data['payment_methods'])) {
			$data['payment_methods'] = $this->session->data['payment_methods']; 
		} else {
			$data['payment_methods'] = array();
		}
	  
		if (isset($this->session->data['payment_method']['code'])) {
			$data['code'] = $this->session->data['payment_method']['code'];
		} else {
			$data['code'] = '';
		}
		
		if (isset($this->session->data['comment'])) {
			$data['comment'] = $this->session->data['comment'];
		} else {
			$data['comment'] = '';
		}
		
		
		if (isset($this->session->data['agree'])) { 
			$data['agree'] = $this->session->data['agree'];
		} else {
			$data['agree'] = '';
		}
			
		$json["data"] = $data;

        $this->sendResponse($json);
  	}
	
	/* 
	* Save selected payment method to session
	*/
	public function savePayment($data) {
		$this->language->load('checkout/checkout');
		
		$json = array('success' => true);
		
		if (!isset($this->session->data['payment_address'])) {	
			$json["error"] = "Empty payment address";
			$json["success"] = false;
		} else {		
		
			// Validate cart has products and has stock.			
			if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
				$json["error"] = "Validate cart has products and has stock failed";
				$json["success"] = false;
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
					$json['success'] = false;
					$json['error']['minimum'] = "Product minimum > product total";				
					break;
				}				
			}
		}			

		if ($json["success"]) {
			if (!isset($data['payment_method'])) {
				$json['error']['warning'] = $this->language->get('error_payment');
				$json["success"] = false;
			} else {
				if (!isset($this->session->data['payment_methods'][$data['payment_method']])) {
					$json['error']['warning'] = $this->language->get('error_payment');
					$json["success"] = false;
				}
			}	
							
			if ($this->config->get('config_checkout_id')) {
				$this->load->model('catalog/information');
				
				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));
				
				if ($information_info && !isset($data['agree'])) {
					$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
					$json["success"] = false;
				}
			}
			
			if ($json["success"]) {
				$this->session->data['payment_method'] = $this->session->data['payment_methods'][$data['payment_method']];
			 
				$this->session->data['comment'] = strip_tags($data['comment']);

				$this->session->data['agree'] = $data['agree'];
			}

		}

        $this->sendResponse($json);
	}

	/*
	* PAYMENT FUNCTIONS
	*/	
	public function payments() {

		$this->checkPlugin();

		if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
			//get payments
			$this->listPayments();
		}else if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
			//save payments information to session
			$requestjson = file_get_contents('php://input');
		
			$requestjson = json_decode($requestjson, true);           

			if (!empty($requestjson)) {
				$this->savePayment($requestjson);
			}else {
                $this->sendResponse(array('success' => false));
			}
		}

    }
}