<?php
/**
 * cart.php
 *
 * Cart management
 *
 * @author     Makai Lajos
 * @copyright  2015
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://opencart2oauth.opencart-api.com/schema_v2.0_oauth/
 */
require_once (DIR_SYSTEM . 'engine/restcontroller.php');
class ControllerRestCart extends RestController {
	private $error = array ();
	
	/*
	 * Get cart
	 */
	public function getCart() {
		$json = array (
				'success' => true 
		);
		
		$this->language->load ( 'checkout/cart' );
		
		if (! isset ( $this->session->data ['vouchers'] )) {
			$this->session->data ['vouchers'] = array ();
		}
		
		if ($this->cart->hasProducts () || ! empty ( $this->session->data ['vouchers'] )) {
			
			$points = $this->customer->getRewardPoints ();
			
			$points_total = 0;
			
			foreach ( $this->cart->getProducts () as $product ) {
				if ($product ['points']) {
					$points_total += $product ['points'];
				}
			}
			
			if (isset ( $this->error ['warning'] )) {
				$data ['error_warning'] = $this->error ['warning'];
			} elseif (! $this->cart->hasStock () && (! $this->config->get ( 'config_stock_checkout' ) || $this->config->get ( 'config_stock_warning' ))) {
				$data ['error_warning'] = $this->language->get ( 'error_stock' );
			} else {
				$data ['error_warning'] = '';
			}
			
			if ($this->config->get ( 'config_customer_price' ) && ! $this->customer->isLogged ()) {
				$data ['attention'] = sprintf ( $this->language->get ( 'text_login' ), $this->url->link ( 'account/login' ), $this->url->link ( 'account/register' ) );
			} else {
				$data ['attention'] = '';
			}
			
			if ($this->config->get ( 'config_cart_weight' )) {
				$data ['weight'] = $this->weight->format ( $this->cart->getWeight (), $this->config->get ( 'config_weight_class_id' ), $this->language->get ( 'decimal_point' ), $this->language->get ( 'thousand_point' ) );
			} else {
				$data ['weight'] = '';
			}
			
			$this->load->model ( 'tool/image' );
			$this->load->model ( 'tool/upload' );
			
			$data ['products'] = array ();
			
			$products = $this->cart->getProducts ();
			
			foreach ( $products as $product ) {
				
				$product_total = 0;
				
				foreach ( $products as $product_2 ) {
					if ($product_2 ['product_id'] == $product ['product_id']) {
						$product_total += $product_2 ['quantity'];
					}
				}
				
				if ($product ['minimum'] > $product_total) {
					$data ['error_warning'] = sprintf ( $this->language->get ( 'error_minimum' ), $product ['name'], $product ['minimum'] );
				}
				
				if ($product ['image']) {
					$image = $this->model_tool_image->resize ( $product ['image'], $this->config->get ( 'config_image_cart_width' ), $this->config->get ( 'config_image_cart_height' ) );
				} else {
					$image = '';
				}
				
				$option_data = array ();
				
				foreach ( $product ['option'] as $option ) {
					if ($option ['type'] != 'file') {
						$value = $option ['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode ( $option ['value'] );
						
						if ($upload_info) {
							$value = $upload_info ['name'];
						} else {
							$value = '';
						}
					}
					
					$option_data [] = array (
							'name' => $option ['name'],
							'value' => (utf8_strlen ( $value ) > 20 ? utf8_substr ( $value, 0, 20 ) . '..' : $value) 
					);
				}
				
				// Display prices
				if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
					$price = $this->currency->format ( $this->tax->calculate ( $product ['price'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
				} else {
					$price = false;
				}
				
				// Display prices
				if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
					$total = $this->currency->format ( $this->tax->calculate ( $product ['price'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) * $product ['quantity'] );
				} else {
					$total = false;
				}
				
				$data ['products'] [] = array (
						'key' => isset ( $product ['cart_id'] ) ? $product ['cart_id'] : (isset ( $product ['key'] ) ? $product ['key'] : ""),
						'thumb' => $image,
						'name' => $product ['name'],
						'model' => $product ['model'],
						'option' => $option_data,
						'quantity' => $product ['quantity'],
						'stock' => $product ['stock'] ? true : ! (! $this->config->get ( 'config_stock_checkout' ) || $this->config->get ( 'config_stock_warning' )),
						'reward' => ($product ['reward'] ? sprintf ( $this->language->get ( 'text_points' ), $product ['reward'] ) : ''),
						'price' => $price,
						'total' => $total 
				);
			}
			
			// Gift Voucher
			$data ['vouchers'] = array ();
			
			if (! empty ( $this->session->data ['vouchers'] )) {
				foreach ( $this->session->data ['vouchers'] as $key => $voucher ) {
					$data ['vouchers'] [] = array (
							'key' => $key,
							'description' => $voucher ['description'],
							'amount' => $this->currency->format ( $voucher ['amount'] ),
							'remove' => $this->url->link ( 'checkout/cart', 'remove=' . $key ) 
					);
				}
			}
			
			$data ['coupon_status'] = $this->config->get ( 'coupon_status' );
			
			if (isset ( $data ['coupon'] )) {
				$data ['coupon'] = $data ['coupon'];
			} elseif (isset ( $this->session->data ['coupon'] )) {
				$data ['coupon'] = $this->session->data ['coupon'];
			} else {
				$data ['coupon'] = '';
			}
			
			$data ['voucher_status'] = $this->config->get ( 'voucher_status' );
			
			if (isset ( $data ['voucher'] )) {
				$data ['voucher'] = $data ['voucher'];
			} elseif (isset ( $this->session->data ['voucher'] )) {
				$data ['voucher'] = $this->session->data ['voucher'];
			} else {
				$data ['voucher'] = '';
			}
			
			$data ['reward_status'] = ($points && $points_total && $this->config->get ( 'reward_status' ));
			
			if (isset ( $data ['reward'] )) {
				$data ['reward'] = $data ['reward'];
			} elseif (isset ( $this->session->data ['reward'] )) {
				$data ['reward'] = $this->session->data ['reward'];
			} else {
				$data ['reward'] = '';
			}
			
			// Totals
			$this->load->model ( 'extension/extension' );
			
			$total_data = array ();
			$total = 0;
			$taxes = $this->cart->getTaxes ();
			
			// Display prices
			if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
				$sort_order = array ();
				
				$results = $this->model_extension_extension->getExtensions ( 'total' );
				
				foreach ( $results as $key => $value ) {
					$sort_order [$key] = $this->config->get ( $value ['code'] . '_sort_order' );
				}
				
				array_multisort ( $sort_order, SORT_ASC, $results );
				
				foreach ( $results as $result ) {
					if ($this->config->get ( $result ['code'] . '_status' )) {
						$this->load->model ( 'total/' . $result ['code'] );
						
						$this->{'model_total_' . $result ['code']}->getTotal ( $total_data, $total, $taxes );
					}
				}
				
				$sort_order = array ();
				
				foreach ( $total_data as $key => $value ) {
					$sort_order [$key] = $value ['sort_order'];
				}
				
				array_multisort ( $sort_order, SORT_ASC, $total_data );
			}
			
			$data ['totals'] = array ();
			
			foreach ( $total_data as $total ) {
				$data ['totals'] [] = array (
						'title' => $total ['title'],
						'text' => $this->currency->format ( $total ['value'] ) 
				);
			}
			
			$json ["data"] = $data;
		} else {
			$json ['success'] = false;
			$json ['error'] = 'Cart is empty';
		}
		
		$this->sendResponse ( $json );
	}
	private function validateCoupon($coupon) {
		if (strpos ( VERSION, '2.1.' ) === false) {
			$this->load->model ( 'checkout/coupon' );
			$this->language->load ( 'checkout/cart' );
			$coupon_info = $this->model_checkout_coupon->getCoupon ( $coupon );
		} else {
			$this->load->model ( 'total/coupon' );
			$this->language->load ( 'total/cart' );
			$coupon_info = $this->model_total_coupon->getCoupon ( $coupon );
		}
		
		if (! $coupon_info) {
			$this->error ['warning'] = $this->language->get ( 'error_coupon' );
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	private function validateVoucher($voucher) {
		if (strpos ( VERSION, '2.1.' ) === false) {
			$this->load->model ( 'checkout/voucher' );
			$this->language->load ( 'checkout/cart' );
			$voucher_info = $this->model_checkout_voucher->getVoucher ( $voucher );
		} else {
			$this->load->model ( 'total/voucher' );
			$this->language->load ( 'total/cart' );
			$voucher_info = $this->model_total_voucher->getVoucher ( $voucher );
		}
		
		if (! $voucher_info) {
			$this->error ['warning'] = $this->language->get ( 'error_voucher' );
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	private function validateReward($reward) {
		$this->language->load ( 'checkout/cart' );
		$points = $this->customer->getRewardPoints ();
		
		$points_total = 0;
		
		foreach ( $this->cart->getProducts () as $product ) {
			if ($product ['points']) {
				$points_total += $product ['points'];
			}
		}
		
		if (empty ( $reward )) {
			$this->error ['warning'] = $this->language->get ( 'error_reward' );
		}
		
		if ($reward > $points) {
			$this->error ['warning'] = sprintf ( $this->language->get ( 'error_points' ), $reward );
		}
		
		if ($reward > $points_total) {
			$this->error ['warning'] = sprintf ( $this->language->get ( 'error_maximum' ), $points_total );
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	private function validateShipping($shipping_method) {
		$this->language->load ( 'checkout/cart' );
		
		if (! empty ( $shipping_method )) {
			$shipping = explode ( '.', $shipping_method );
			
			if (! isset ( $shipping [0] ) || ! isset ( $shipping [1] ) || ! isset ( $this->session->data ['shipping_methods'] [$shipping [0]] ['quote'] [$shipping [1]] )) {
				$this->error ['warning'] = $this->language->get ( 'error_shipping' );
			}
		} else {
			$this->error ['warning'] = $this->language->get ( 'error_shipping' );
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * Add item to cart
	 */
	public function addToCart($data) {
		$json = $this->addItemCart ( $data );
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * CART FUNCTIONS
	 */
	public function cart() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			// get cart
			$this->getCart ();
		} else if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
			// add to cart
			$requestjson = file_get_contents ( 'php://input' );
			
			$requestjson = json_decode ( $requestjson, true );
			
			if (! empty ( $requestjson )) {
				$this->addToCart ( $requestjson );
			} else {
				$this->sendResponse ( array (
						'success' => false 
				) );
			}
		} else if ($_SERVER ['REQUEST_METHOD'] === 'PUT') {
			// update cart
			$requestjson = file_get_contents ( 'php://input' );
			
			$requestjson = json_decode ( $requestjson, true );
			
			if (! empty ( $requestjson )) {
				$this->updateCart ( $requestjson );
			} else {
				$this->sendResponse ( array (
						'success' => false 
				) );
			}
		} else if ($_SERVER ['REQUEST_METHOD'] === 'DELETE') {
			$requestjson = file_get_contents ( 'php://input' );
			
			$requestjson = json_decode ( $requestjson, true );
			
			if (! empty ( $requestjson )) {
				$this->deleteCartItem ( $requestjson );
			} else {
				$this->sendResponse ( array (
						'success' => false 
				) );
			}
		}
	}
	
	/*
	 * Delete cart item
	 */
	public function deleteCartItem($data) {
		$json = array (
				'success' => true 
		);
		$this->language->load ( 'checkout/cart' );
		$this->load->model ( 'catalog/product' );
		
		$id = $data ['product_id'];
		
		// if(isset($this->session->data['cart'][$id])){
		if (! empty ( $id )) {
			$this->cart->remove ( $id );
			
			unset ( $this->session->data ['vouchers'] [$id] );
			
			unset ( $this->session->data ['shipping_method'] );
			unset ( $this->session->data ['shipping_methods'] );
			unset ( $this->session->data ['payment_method'] );
			unset ( $this->session->data ['payment_methods'] );
			unset ( $this->session->data ['reward'] );
			
			// Totals
			$this->load->model ( 'extension/extension' );
			
			$total_data = array ();
			$total = 0;
			$taxes = $this->cart->getTaxes ();
			
			// Display prices
			if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
				$sort_order = array ();
				
				$results = $this->model_extension_extension->getExtensions ( 'total' );
				
				foreach ( $results as $key => $value ) {
					$sort_order [$key] = $this->config->get ( $value ['code'] . '_sort_order' );
				}
				
				array_multisort ( $sort_order, SORT_ASC, $results );
				
				foreach ( $results as $result ) {
					if ($this->config->get ( $result ['code'] . '_status' )) {
						$this->load->model ( 'total/' . $result ['code'] );
						
						$this->{'model_total_' . $result ['code']}->getTotal ( $total_data, $total, $taxes );
					}
				}
				
				$sort_order = array ();
				
				foreach ( $total_data as $key => $value ) {
					$sort_order [$key] = $value ['sort_order'];
				}
				
				array_multisort ( $sort_order, SORT_ASC, $total_data );
			}
			
			$json ['total'] = sprintf ( $this->language->get ( 'text_items' ), $this->cart->countProducts () + (isset ( $this->session->data ['vouchers'] ) ? count ( $this->session->data ['vouchers'] ) : 0), $this->currency->format ( $total ) );
		} else {
			$json ['success'] = false;
			$json ['error'] = "No item found in cart with id: " . $id;
		}
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * Delete cart item by Id
	 */
	public function deletecartitembyid() {
		$this->checkPlugin ();
		$this->language->load ( 'checkout/cart' );
		$json = array (
				'success' => true 
		);
		
		if ($_SERVER ['REQUEST_METHOD'] === 'DELETE') {
			
			$this->load->model ( 'catalog/product' );
			
			$id = 0;
			
			if (isset ( $this->request->get ['id'] )) {
				$id = $this->request->get ['id'];
			}
			
			if (! empty ( $id )) {
				$this->cart->remove ( $id );
				
				unset ( $this->session->data ['vouchers'] [$id] );
				
				unset ( $this->session->data ['shipping_method'] );
				unset ( $this->session->data ['shipping_methods'] );
				unset ( $this->session->data ['payment_method'] );
				unset ( $this->session->data ['payment_methods'] );
				unset ( $this->session->data ['reward'] );
				
				// Totals
				$this->load->model ( 'extension/extension' );
				
				$total_data = array ();
				$total = 0;
				$taxes = $this->cart->getTaxes ();
				
				// Display prices
				if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
					$sort_order = array ();
					
					$results = $this->model_extension_extension->getExtensions ( 'total' );
					
					foreach ( $results as $key => $value ) {
						$sort_order [$key] = $this->config->get ( $value ['code'] . '_sort_order' );
					}
					
					array_multisort ( $sort_order, SORT_ASC, $results );
					
					foreach ( $results as $result ) {
						if ($this->config->get ( $result ['code'] . '_status' )) {
							$this->load->model ( 'total/' . $result ['code'] );
							
							$this->{'model_total_' . $result ['code']}->getTotal ( $total_data, $total, $taxes );
						}
					}
					
					$sort_order = array ();
					
					foreach ( $total_data as $key => $value ) {
						$sort_order [$key] = $value ['sort_order'];
					}
					
					array_multisort ( $sort_order, SORT_ASC, $total_data );
				}
				
				$json ['total'] = sprintf ( $this->language->get ( 'text_items' ), $this->cart->countProducts () + (isset ( $this->session->data ['vouchers'] ) ? count ( $this->session->data ['vouchers'] ) : 0), $this->currency->format ( $total ) );
			} else {
				$json ['success'] = false;
				$json ['error'] = "No item found in cart with id: " . $id;
			}
		} else {
			$json ["error"] = "Only DELETE request method allowed";
			$json ["success"] = false;
		}
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * Update item quantity
	 */
	private function updateCart($data) {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'catalog/product' );
		
		if (! empty ( $data ['quantity'] )) {
			
			foreach ( $data ['quantity'] as $key => $value ) {
				$this->cart->update ( $key, $value );
			}
			
			unset ( $this->session->data ['shipping_method'] );
			unset ( $this->session->data ['shipping_methods'] );
			unset ( $this->session->data ['payment_method'] );
			unset ( $this->session->data ['payment_methods'] );
			unset ( $this->session->data ['reward'] );
		} else {
			$json ['success'] = false;
		}
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * Empty cart
	 */
	public function emptycart() {
		$this->checkPlugin ();
		
		$json = array (
				'success' => true 
		);
		
		if ($_SERVER ['REQUEST_METHOD'] === 'DELETE') {
			$this->cart->clear ();
			$this->sendResponse ( $json );
		} else {
			$json ["error"] = "Only DELETE request method allowed";
			$json ["success"] = false;
		}
	}
	
	/*
	 * Set Coupon
	 */
	public function coupon() {
		$this->checkPlugin ();
		
		$json = array (
				'success' => true 
		);
		
		if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
			
			$requestjson = file_get_contents ( 'php://input' );
			
			$requestjson = json_decode ( $requestjson, true );
			$post = $requestjson;
			
			if (isset ( $post ["coupon"] ) && $this->validateCoupon ( $post ["coupon"] )) {
				$this->session->data ['coupon'] = $post ["coupon"];
				$json ['message'] = $this->language->get ( 'text_coupon' );
			} else {
				$json ['success'] = false;
				$json ['error'] = $this->error;
			}
			
			$this->sendResponse ( $json );
		} else {
			$json ["error"] = "Only POST request method allowed";
			$json ["success"] = false;
		}
	}
	
	/*
	 * Set voucher
	 */
	public function voucher() {
		$this->checkPlugin ();
		
		$json = array (
				'success' => true 
		);
		
		if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
			
			$requestjson = file_get_contents ( 'php://input' );
			
			$requestjson = json_decode ( $requestjson, true );
			
			$post = $requestjson;
			
			if (isset ( $post ["voucher"] ) && $this->validateVoucher ( $post ["voucher"] )) {
				$this->session->data ['voucher'] = $post ["voucher"];
				$json ['message'] = $this->language->get ( 'text_voucher' );
			} else {
				$json ['error'] = $this->error;
				$json ['success'] = false;
			}
			
			$this->sendResponse ( $json );
		} else {
			$json ["error"] = "Only POST request method allowed";
			$json ["success"] = false;
		}
	}
	
	/*
	 * Set Reward
	 */
	public function reward() {
		$this->checkPlugin ();
		
		$json = array (
				'success' => true 
		);
		
		if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
			$requestjson = file_get_contents ( 'php://input' );
			
			$requestjson = json_decode ( $requestjson, true );
			
			$post = $requestjson;
			
			if (isset ( $post ["reward"] ) && $this->validateReward ( $post ["reward"] )) {
				$this->session->data ['reward'] = abs ( $post ["reward"] );
				$json ['message'] = $this->language->get ( 'text_reward' );
			} else {
				$json ['error'] = $this->error;
				$json ['success'] = false;
			}
			
			$this->sendResponse ( $json );
		} else {
			$json ["error"] = "Only POST request method allowed";
			$json ["success"] = false;
		}
	}
	
	/*
	 * BULK PRODUCT ADD TO CART
	 */
	public function bulkcart() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
			$requestjson = file_get_contents ( 'php://input' );
			
			$requestjson = json_decode ( $requestjson, true );
			
			if (! empty ( $requestjson ) && count ( $requestjson ) > 0) {
				
				$this->addItemsToCart ( $requestjson );
			} else {
				$this->sendResponse ( array (
						'success' => false 
				) );
			}
		} else {
			$this->sendResponse ( array (
					'success' => false 
			) );
		}
	}
	
	/*
	 * Add more item to cart
	 */
	public function addItemsToCart($products) {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'catalog/product' );
		
		foreach ( $products as $product ) {
			$json = $this->addItemCart ( $product );
			if ($json ["success"] == false) {
				break;
			}
		}
		$this->sendResponse ( $json );
	}
	private function addItemCart($data) {
		$json = array (
				'success' => true 
		);
		
		$this->language->load ( 'checkout/cart' );
		
		if (isset ( $data ['product_id'] )) {
			$product_id = $data ['product_id'];
		} else {
			$product_id = 0;
		}
		
		$this->load->model ( 'catalog/product' );
		
		$product_info = $this->model_catalog_product->getProduct ( $product_id );
		if ($product_info) {
			if (isset ( $data ['quantity'] )) {
				$quantity = $data ['quantity'];
			} else {
				$quantity = 1;
				$data ['quantity'] = 1;
			}
			
			if (isset ( $data ['option'] )) {
				$option = array_filter ( $data ['option'] );
			} else {
				$option = array ();
			}
			
			$product_options = $this->model_catalog_product->getProductOptions ( $data ['product_id'] );
			
			foreach ( $product_options as $product_option ) {
				if ($product_option ['required'] && empty ( $option [$product_option ['product_option_id']] )) {
					$json ['error'] ['option'] [$product_option ['product_option_id']] = sprintf ( $this->language->get ( 'error_required' ), $product_option ['name'] );
					$json ['success'] = false;
				}
			}
			
			if (isset ( $data ['recurring_id'] )) {
				$recurring_id = $data ['recurring_id'];
			} else {
				$recurring_id = 0;
			}
			
			$recurrings = $this->model_catalog_product->getProfiles ( $product_info ['product_id'] );
			
			if ($recurrings) {
				$recurring_ids = array ();
				
				foreach ( $recurrings as $recurring ) {
					$recurring_ids [] = $recurring ['recurring_id'];
				}
				
				if (! in_array ( $recurring_id, $recurring_ids )) {
					$json ['error'] ['recurring'] = $this->language->get ( 'error_recurring_required' );
					$json ['success'] = false;
				}
			}
			
			if ($json ['success']) {
				$this->cart->add ( $data ['product_id'], $data ['quantity'], $option, $recurring_id );
				
				$json ['product'] ['product_id'] = $product_info ['product_id'];
				$json ['product'] ['name'] = isset ( $product_info ['name'] ) ? $product_info ['name'] : "";
				$json ['product'] ['quantity'] = $quantity;
				
				unset ( $this->session->data ['shipping_method'] );
				unset ( $this->session->data ['shipping_methods'] );
				unset ( $this->session->data ['payment_method'] );
				unset ( $this->session->data ['payment_methods'] );
				
				// Totals
				$this->load->model ( 'extension/extension' );
				
				$total_data = array ();
				$total = 0;
				$taxes = $this->cart->getTaxes ();
				
				// Display prices
				if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
					$sort_order = array ();
					
					$results = $this->model_extension_extension->getExtensions ( 'total' );
					
					foreach ( $results as $key => $value ) {
						$sort_order [$key] = $this->config->get ( $value ['code'] . '_sort_order' );
					}
					
					array_multisort ( $sort_order, SORT_ASC, $results );
					
					foreach ( $results as $result ) {
						if ($this->config->get ( $result ['code'] . '_status' )) {
							$this->load->model ( 'total/' . $result ['code'] );
							
							$this->{'model_total_' . $result ['code']}->getTotal ( $total_data, $total, $taxes );
						}
					}
					
					$sort_order = array ();
					
					foreach ( $total_data as $key => $value ) {
						$sort_order [$key] = $value ['sort_order'];
					}
					
					array_multisort ( $sort_order, SORT_ASC, $total_data );
				}
				
				$json ['total'] = sprintf ( $this->language->get ( 'text_items' ), $this->cart->countProducts () + (isset ( $this->session->data ['vouchers'] ) ? count ( $this->session->data ['vouchers'] ) : 0), $this->currency->format ( $total ) );
			} else {
				$json ['success'] = false;
			}
		} else {
			$json ['success'] = false;
			$json ['error'] = "Product not found";
		}
		
		return $json;
	}
}