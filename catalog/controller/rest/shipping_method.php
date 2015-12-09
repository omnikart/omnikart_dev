<?php 
/**
 * shipping_method.php
 *
 * Shipping method management
 *
 * @author     Makai Lajos
 * @copyright  2015
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://opencart2oauth.opencart-api.com/schema_v2.0_oauth/
 */
require_once(DIR_SYSTEM . 'engine/restcontroller.php');

class ControllerRestShippingMethod extends RestController {
	
	/*
	* Get shipping methods
	*/
  	public function listShippingMethods() {

		$json = array('success' => true);

		$this->language->load('checkout/checkout');

		if (isset($this->session->data['shipping_address'])) {
			// Shipping Methods
			$method_data = array();

			$this->load->model('extension/extension');

			$results = $this->model_extension_extension->getExtensions('shipping');

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('shipping/' . $result['code']);

					$quote = $this->{'model_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

					if ($quote) {
						$method_data[$result['code']] = array(
							'title'      => $quote['title'],
							'quote'      => $quote['quote'],
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}
				}
			}

			$sort_order = array();

			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $method_data);

			$this->session->data['shipping_methods'] = $method_data;
		}

		if (empty($this->session->data['shipping_methods'])) {
			$data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['shipping_methods'])) {
			$data['shipping_methods'] = $this->session->data['shipping_methods'];
		} else {
			$data['shipping_methods'] = array();
		}

		if (isset($this->session->data['shipping_method']['code'])) {
			$data['code'] = $this->session->data['shipping_method']['code'];
		} else {
			$data['code'] = '';
		}

		if (isset($this->session->data['comment'])) {
			$data['comment'] = $this->session->data['comment'];
		} else {
			$data['comment'] = '';
		}


		$json["data"] = $data;

        $this->sendResponse($json);
	}

	/* 
	* Save selected shipping method to session
	*/
	public function saveShippingMethod($post) {
		
		$this->language->load('checkout/checkout');

		$json = array('success' => true);		

		// Validate if shipping is required. If not the customer should not have reached this page.
		
		if (!$this->cart->hasShipping()) {
			$json["error"] = "The customer should not have reached this page, becouse shipping is not required.";
			$json["success"] = true;	
		}

		if (!isset($this->session->data['shipping_address'])) {							
			$json["error"] = "Empty shipping address";
			$json["success"] = false;
		}

		// Validate cart has products and has stock.	
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {

			$json["error"] = "Validate cart has products and has stock failed";
			$json["success"] = false;			
		}	

		// Validate minimum quantity requirements.
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

		if (!isset($post['shipping_method'])) {
			$json['error']['warning'] = $this->language->get('error_shipping');
			$json['success'] = false;
		} else {
			$shipping = explode('.', $post['shipping_method']);

			if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$json['error']['warning'] = $this->language->get('error_shipping');
				$json['success'] = false;
			}
		}

		if ($json['success']) {
			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

			$this->session->data['comment'] = strip_tags($post['comment']);
		}

        $this->sendResponse($json);
	}

	/*
	* SHIPPING METHOD FUNCTIONS
	*/	
	public function shippingmethods() {

		$this->checkPlugin();

		if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
			//get shipping methods
			$this->listShippingMethods();
		}else if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
			//save shipping information to session
			$requestjson = file_get_contents('php://input');
		
			$requestjson = json_decode($requestjson, true);           

			if (!empty($requestjson)) {
				$this->saveShippingMethod($requestjson);
			}else {
                $this->sendResponse(array('success' => false));
			}
		}

    }
}