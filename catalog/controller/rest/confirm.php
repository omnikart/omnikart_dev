<?php
/**
 * confirm.php
 *
 * Confirm order
 *
 * @author     Makai Lajos
 * @copyright  2015
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://opencart2oauth.opencart-api.com/schema_v2.0_oauth/
 */
require_once (DIR_SYSTEM . 'engine/restcontroller.php');
class ControllerRestConfirm extends RestController {
	/*
	 * Confirm order
	 */
	public function confirmOrder($page = "confirm") {
		$json = array (
				'success' => true 
		);
		if ($this->cart->hasShipping ()) {
			// Validate if shipping address has been set.
			if (! isset ( $this->session->data ['shipping_address'] )) {
				$json ["error"] = "Empty shipping address.";
				$json ["success"] = false;
			}
			
			// Validate if shipping method has been set.
			if (! isset ( $this->session->data ['shipping_method'] )) {
				$json ["error"] = "Validate if shipping method has been set failed.";
				$json ["success"] = false;
			}
		} else {
			unset ( $this->session->data ['shipping_address'] );
			unset ( $this->session->data ['shipping_method'] );
			unset ( $this->session->data ['shipping_methods'] );
		}
		
		// Validate if payment address has been set.
		if (! isset ( $this->session->data ['payment_address'] )) {
			$json ["error"] = "Empty payment address.";
			$json ["success"] = false;
		}
		
		// Validate if payment method has been set.
		if (! isset ( $this->session->data ['payment_method'] )) {
			$json ["error"] = "Empty payment method.";
			$json ["success"] = false;
		}
		
		// Validate cart has products and has stock.
		if ((! $this->cart->hasProducts () && empty ( $this->session->data ['vouchers'] )) || (! $this->cart->hasStock () && ! $this->config->get ( 'config_stock_checkout' ))) {
			$json ["error"] = "Validate cart has products and has stock failed";
			$json ["success"] = false;
		}
		
		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts ();
		
		foreach ( $products as $product ) {
			$product_total = 0;
			
			foreach ( $products as $product_2 ) {
				if ($product_2 ['product_id'] == $product ['product_id']) {
					$product_total += $product_2 ['quantity'];
				}
			}
			
			if ($product ['minimum'] > $product_total) {
				$json ["error"] = "Product minimum is greater than product total";
				$json ["success"] = false;
				
				break;
			}
		}
		
		if ($json ["success"]) {
			$order_data = array ();
			
			$order_data ['totals'] = array ();
			$total = 0;
			$taxes = $this->cart->getTaxes ();
			
			$this->load->model ( 'extension/extension' );
			
			$sort_order = array ();
			
			$results = $this->model_extension_extension->getExtensions ( 'total' );
			
			foreach ( $results as $key => $value ) {
				$sort_order [$key] = $this->config->get ( $value ['code'] . '_sort_order' );
			}
			
			array_multisort ( $sort_order, SORT_ASC, $results );
			
			foreach ( $results as $result ) {
				if ($this->config->get ( $result ['code'] . '_status' )) {
					$this->load->model ( 'total/' . $result ['code'] );
					
					$this->{'model_total_' . $result ['code']}->getTotal ( $order_data ['totals'], $total, $taxes );
				}
			}
			
			$sort_order = array ();
			
			foreach ( $order_data ['totals'] as $key => $value ) {
				$sort_order [$key] = $value ['sort_order'];
			}
			
			array_multisort ( $sort_order, SORT_ASC, $order_data ['totals'] );
			
			$this->load->language ( 'checkout/checkout' );
			
			$order_data ['invoice_prefix'] = $this->config->get ( 'config_invoice_prefix' );
			$order_data ['store_id'] = $this->config->get ( 'config_store_id' );
			$order_data ['store_name'] = $this->config->get ( 'config_name' );
			
			if ($order_data ['store_id']) {
				$order_data ['store_url'] = $this->config->get ( 'config_url' );
			} else {
				$order_data ['store_url'] = HTTP_SERVER;
			}
			
			if ($this->customer->isLogged ()) {
				$this->load->model ( 'account/customer' );
				
				$customer_info = $this->model_account_customer->getCustomer ( $this->customer->getId () );
				
				$order_data ['customer_id'] = $this->customer->getId ();
				$order_data ['customer_group_id'] = $customer_info ['customer_group_id'];
				$order_data ['firstname'] = $customer_info ['firstname'];
				$order_data ['lastname'] = $customer_info ['lastname'];
				$order_data ['email'] = $customer_info ['email'];
				$order_data ['telephone'] = $customer_info ['telephone'];
				$order_data ['fax'] = $customer_info ['fax'];
				
				if (strpos ( VERSION, '2.1.' ) === false) {
					$order_data ['custom_field'] = unserialize ( $customer_info ['custom_field'] );
				} else {
					$order_data ['custom_field'] = json_decode ( $customer_info ['custom_field'], true );
				}
			} elseif (isset ( $this->session->data ['guest'] )) {
				$order_data ['customer_id'] = 0;
				$order_data ['customer_group_id'] = $this->session->data ['guest'] ['customer_group_id'];
				$order_data ['firstname'] = $this->session->data ['guest'] ['firstname'];
				$order_data ['lastname'] = $this->session->data ['guest'] ['lastname'];
				$order_data ['email'] = $this->session->data ['guest'] ['email'];
				$order_data ['telephone'] = $this->session->data ['guest'] ['telephone'];
				$order_data ['fax'] = $this->session->data ['guest'] ['fax'];
				$order_data ['custom_field'] = $this->session->data ['guest'] ['custom_field'];
			} else {
				$json ["error"] = "Please login or set user data";
				$json ["success"] = false;
			}
			
			if ($json ["success"]) {
				$order_data ['payment_firstname'] = $this->session->data ['payment_address'] ['firstname'];
				$order_data ['payment_lastname'] = $this->session->data ['payment_address'] ['lastname'];
				$order_data ['payment_company'] = $this->session->data ['payment_address'] ['company'];
				$order_data ['payment_address_1'] = $this->session->data ['payment_address'] ['address_1'];
				$order_data ['payment_address_2'] = $this->session->data ['payment_address'] ['address_2'];
				$order_data ['payment_city'] = $this->session->data ['payment_address'] ['city'];
				$order_data ['payment_postcode'] = $this->session->data ['payment_address'] ['postcode'];
				$order_data ['payment_zone'] = $this->session->data ['payment_address'] ['zone'];
				$order_data ['payment_zone_id'] = $this->session->data ['payment_address'] ['zone_id'];
				$order_data ['payment_country'] = $this->session->data ['payment_address'] ['country'];
				$order_data ['payment_country_id'] = $this->session->data ['payment_address'] ['country_id'];
				$order_data ['payment_address_format'] = $this->session->data ['payment_address'] ['address_format'];
				$order_data ['payment_custom_field'] = $this->session->data ['payment_address'] ['custom_field'];
				
				if (isset ( $this->session->data ['payment_method'] ['title'] )) {
					$order_data ['payment_method'] = $this->session->data ['payment_method'] ['title'];
				} else {
					$order_data ['payment_method'] = '';
				}
				
				if (isset ( $this->session->data ['payment_method'] ['code'] )) {
					$order_data ['payment_code'] = $this->session->data ['payment_method'] ['code'];
				} else {
					$order_data ['payment_code'] = '';
				}
				
				if ($this->cart->hasShipping ()) {
					$order_data ['shipping_firstname'] = $this->session->data ['shipping_address'] ['firstname'];
					$order_data ['shipping_lastname'] = $this->session->data ['shipping_address'] ['lastname'];
					$order_data ['shipping_company'] = $this->session->data ['shipping_address'] ['company'];
					$order_data ['shipping_address_1'] = $this->session->data ['shipping_address'] ['address_1'];
					$order_data ['shipping_address_2'] = $this->session->data ['shipping_address'] ['address_2'];
					$order_data ['shipping_city'] = $this->session->data ['shipping_address'] ['city'];
					$order_data ['shipping_postcode'] = $this->session->data ['shipping_address'] ['postcode'];
					$order_data ['shipping_zone'] = $this->session->data ['shipping_address'] ['zone'];
					$order_data ['shipping_zone_id'] = $this->session->data ['shipping_address'] ['zone_id'];
					$order_data ['shipping_country'] = $this->session->data ['shipping_address'] ['country'];
					$order_data ['shipping_country_id'] = $this->session->data ['shipping_address'] ['country_id'];
					$order_data ['shipping_address_format'] = $this->session->data ['shipping_address'] ['address_format'];
					$order_data ['shipping_custom_field'] = $this->session->data ['shipping_address'] ['custom_field'];
					
					if (isset ( $this->session->data ['shipping_method'] ['title'] )) {
						$order_data ['shipping_method'] = $this->session->data ['shipping_method'] ['title'];
					} else {
						$order_data ['shipping_method'] = '';
					}
					
					if (isset ( $this->session->data ['shipping_method'] ['code'] )) {
						$order_data ['shipping_code'] = $this->session->data ['shipping_method'] ['code'];
					} else {
						$order_data ['shipping_code'] = '';
					}
				} else {
					$order_data ['shipping_firstname'] = '';
					$order_data ['shipping_lastname'] = '';
					$order_data ['shipping_company'] = '';
					$order_data ['shipping_address_1'] = '';
					$order_data ['shipping_address_2'] = '';
					$order_data ['shipping_city'] = '';
					$order_data ['shipping_postcode'] = '';
					$order_data ['shipping_zone'] = '';
					$order_data ['shipping_zone_id'] = '';
					$order_data ['shipping_country'] = '';
					$order_data ['shipping_country_id'] = '';
					$order_data ['shipping_address_format'] = '';
					$order_data ['shipping_custom_field'] = array ();
					$order_data ['shipping_method'] = '';
					$order_data ['shipping_code'] = '';
				}
				
				$order_data ['products'] = array ();
				
				foreach ( $this->cart->getProducts () as $product ) {
					$option_data = array ();
					
					foreach ( $product ['option'] as $option ) {
						$option_data [] = array (
								'product_option_id' => $option ['product_option_id'],
								'product_option_value_id' => $option ['product_option_value_id'],
								'option_id' => $option ['option_id'],
								'option_value_id' => $option ['option_value_id'],
								'name' => $option ['name'],
								'value' => $option ['value'],
								'type' => $option ['type'] 
						);
					}
					
					$order_data ['products'] [] = array (
							'product_id' => $product ['product_id'],
							'name' => $product ['name'],
							'model' => $product ['model'],
							'option' => $option_data,
							'download' => $product ['download'],
							'quantity' => $product ['quantity'],
							'subtract' => $product ['subtract'],
							'price' => $product ['price'],
							'total' => $product ['total'],
							'tax' => $this->tax->getTax ( $product ['price'], $product ['tax_class_id'] ),
							'reward' => $product ['reward'] 
					);
				}
				
				// Gift Voucher
				$order_data ['vouchers'] = array ();
				
				if (! empty ( $this->session->data ['vouchers'] )) {
					foreach ( $this->session->data ['vouchers'] as $voucher ) {
						$order_data ['vouchers'] [] = array (
								'description' => $voucher ['description'],
								'code' => substr ( md5 ( mt_rand () ), 0, 10 ),
								'to_name' => $voucher ['to_name'],
								'to_email' => $voucher ['to_email'],
								'from_name' => $voucher ['from_name'],
								'from_email' => $voucher ['from_email'],
								'voucher_theme_id' => $voucher ['voucher_theme_id'],
								'message' => $voucher ['message'],
								'amount' => $voucher ['amount'] 
						);
					}
				}
				
				$order_data ['comment'] = $this->session->data ['comment'];
				$order_data ['total'] = $total;
				
				if (isset ( $this->request->cookie ['tracking'] )) {
					$order_data ['tracking'] = $this->request->cookie ['tracking'];
					
					$subtotal = $this->cart->getSubTotal ();
					
					// Affiliate
					$this->load->model ( 'affiliate/affiliate' );
					
					$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode ( $this->request->cookie ['tracking'] );
					
					if ($affiliate_info) {
						$order_data ['affiliate_id'] = $affiliate_info ['affiliate_id'];
						$order_data ['commission'] = ($subtotal / 100) * $affiliate_info ['commission'];
					} else {
						$order_data ['affiliate_id'] = 0;
						$order_data ['commission'] = 0;
					}
					
					// Marketing
					$this->load->model ( 'checkout/marketing' );
					
					$marketing_info = $this->model_checkout_marketing->getMarketingByCode ( $this->request->cookie ['tracking'] );
					
					if ($marketing_info) {
						$order_data ['marketing_id'] = $marketing_info ['marketing_id'];
					} else {
						$order_data ['marketing_id'] = 0;
					}
				} else {
					$order_data ['affiliate_id'] = 0;
					$order_data ['commission'] = 0;
					$order_data ['marketing_id'] = 0;
					$order_data ['tracking'] = '';
				}
				
				$order_data ['language_id'] = $this->config->get ( 'config_language_id' );
				$order_data ['currency_id'] = $this->currency->getId ();
				$order_data ['currency_code'] = $this->currency->getCode ();
				$order_data ['currency_value'] = $this->currency->getValue ( $this->currency->getCode () );
				$order_data ['ip'] = $this->request->server ['REMOTE_ADDR'];
				
				if (! empty ( $this->request->server ['HTTP_X_FORWARDED_FOR'] )) {
					$order_data ['forwarded_ip'] = $this->request->server ['HTTP_X_FORWARDED_FOR'];
				} elseif (! empty ( $this->request->server ['HTTP_CLIENT_IP'] )) {
					$order_data ['forwarded_ip'] = $this->request->server ['HTTP_CLIENT_IP'];
				} else {
					$order_data ['forwarded_ip'] = '';
				}
				
				if (isset ( $this->request->server ['HTTP_USER_AGENT'] )) {
					$order_data ['user_agent'] = $this->request->server ['HTTP_USER_AGENT'];
				} else {
					$order_data ['user_agent'] = '';
				}
				
				if (isset ( $this->request->server ['HTTP_ACCEPT_LANGUAGE'] )) {
					$order_data ['accept_language'] = $this->request->server ['HTTP_ACCEPT_LANGUAGE'];
				} else {
					$order_data ['accept_language'] = '';
				}
				
				$this->load->model ( 'checkout/order' );
				
				if (! isset ( $this->request->get ['page'] ) && $page == "confirm") {
					$this->session->data ['order_id'] = $this->model_checkout_order->addOrder ( $order_data );
					$data ['order_id'] = $this->session->data ['order_id'];
				}
				
				$this->load->model ( 'tool/upload' );
				
				$data ['products'] = array ();
				
				foreach ( $this->cart->getProducts () as $product ) {
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
					
					$recurring = '';
					
					if ($product ['recurring']) {
						$frequencies = array (
								'day' => $this->language->get ( 'text_day' ),
								'week' => $this->language->get ( 'text_week' ),
								'semi_month' => $this->language->get ( 'text_semi_month' ),
								'month' => $this->language->get ( 'text_month' ),
								'year' => $this->language->get ( 'text_year' ) 
						);
						
						if ($product ['recurring_trial']) {
							$recurring = sprintf ( $this->language->get ( 'text_trial_description' ), $this->currency->format ( $this->tax->calculate ( $product ['recurring'] ['trial_price'] * $product ['quantity'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) ), $product ['recurring'] ['trial_cycle'], $frequencies [$product ['recurring'] ['trial_frequency']], $product ['recurring'] ['trial_duration'] ) . ' ';
						}
						
						if ($product ['recurring_duration']) {
							$recurring .= sprintf ( $this->language->get ( 'text_payment_description' ), $this->currency->format ( $this->tax->calculate ( $product ['recurring'] ['price'] * $product ['quantity'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) ), $product ['recurring'] ['cycle'], $frequencies [$product ['recurring'] ['frequency']], $product ['recurring'] ['duration'] );
						} else {
							$recurring .= sprintf ( $this->language->get ( 'text_payment_until_canceled_description' ), $this->currency->format ( $this->tax->calculate ( $product ['recurring'] ['price'] * $product ['quantity'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) ), $product ['recurring'] ['cycle'], $frequencies [$product ['recurring'] ['frequency']], $product ['recurring'] ['duration'] );
						}
					}
					
					$data ['products'] [] = array (
							'key' => isset ( $product ['cart_id'] ) ? $product ['cart_id'] : (isset ( $product ['key'] ) ? $product ['key'] : ""),
							'product_id' => $product ['product_id'],
							'name' => $product ['name'],
							'model' => $product ['model'],
							'option' => $option_data,
							'recurring' => $recurring,
							'quantity' => $product ['quantity'],
							'subtract' => $product ['subtract'],
							'price' => $this->currency->format ( $this->tax->calculate ( $product ['price'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) ),
							'total' => $this->currency->format ( $this->tax->calculate ( $product ['price'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) * $product ['quantity'] ),
							'href' => "" 
					);
				}
				
				// Gift Voucher
				$data ['vouchers'] = array ();
				
				if (! empty ( $this->session->data ['vouchers'] )) {
					foreach ( $this->session->data ['vouchers'] as $voucher ) {
						$data ['vouchers'] [] = array (
								'description' => $voucher ['description'],
								'amount' => $this->currency->format ( $voucher ['amount'] ) 
						);
					}
				}
				
				$data ['totals'] = array ();
				
				foreach ( $order_data ['totals'] as $total ) {
					$data ['totals'] [] = array (
							'title' => $total ['title'],
							'text' => $this->currency->format ( $total ['value'] ) 
					);
				}
				
				$json ["data"] = $data;
			}
		}
		
		if (! isset ( $this->request->get ['page'] ) && $page == "confirm") {
			$this->sendResponse ( $json );
		} else {
			if (isset ( $this->session->data ['order_id'] ) && ! empty ( $this->session->data ['order_id'] )) {
				return $this->pay ( $data );
			} else {
				$json ["success"] = false;
				$json ["error"] = "No order in session";
				$this->sendResponse ( $json );
			}
		}
	}
	
	/* Save order to Db */
	public function saveOrderToDatabase() {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'checkout/order' );
		
		if (isset ( $this->session->data ['order_id'] )) {
			$this->model_checkout_order->addOrderHistory ( $this->session->data ['order_id'], $this->config->get ( 'cod_order_status_id' ), $this->session->data ['comment'] );
			
			if (isset ( $this->session->data ['order_id'] )) {
				$json ['order_id'] = $this->session->data ['order_id'];
				$this->cart->clear ();
				
				// Add to activity log
				$this->load->model ( 'account/activity' );
				
				if ($this->customer->isLogged ()) {
					$activity_data = array (
							'customer_id' => $this->customer->getId (),
							'name' => $this->customer->getFirstName () . ' ' . $this->customer->getLastName (),
							'order_id' => $this->session->data ['order_id'] 
					);
					
					$this->model_account_activity->addActivity ( 'order_account', $activity_data );
				} else {
					$activity_data = array (
							'name' => $this->session->data ['guest'] ['firstname'] . ' ' . $this->session->data ['guest'] ['lastname'],
							'order_id' => $this->session->data ['order_id'] 
					);
					
					$this->model_account_activity->addActivity ( 'order_guest', $activity_data );
				}
				
				unset ( $this->session->data ['shipping_method'] );
				unset ( $this->session->data ['shipping_methods'] );
				unset ( $this->session->data ['payment_method'] );
				unset ( $this->session->data ['payment_methods'] );
				unset ( $this->session->data ['guest'] );
				unset ( $this->session->data ['comment'] );
				unset ( $this->session->data ['order_id'] );
				unset ( $this->session->data ['coupon'] );
				unset ( $this->session->data ['reward'] );
				unset ( $this->session->data ['voucher'] );
				unset ( $this->session->data ['vouchers'] );
				unset ( $this->session->data ['totals'] );
			}
		} else {
			$json ["success"] = false;
			$json ["error"] = "No order in session";
		}
		
		$this->sendResponse ( $json );
	}
	
	/* Start payment */
	private function pay($data) {
		$this->response->addHeader ( 'Content-Type: text/html' );
		
		$this->data ['styles'] = $this->document->getStyles ();
		$this->data ['scripts'] = $this->document->getScripts ();
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/checkout/restapi_pay.tpl' )) {
			$this->template = $this->config->get ( 'config_template' ) . '/template/checkout/restapi_pay.tpl';
		} else {
			$this->template = 'default/template/checkout/restapi_pay.tpl';
		}
		
		$data ['payment'] = $this->load->controller ( 'payment/' . $this->session->data ['payment_method'] ['code'] );
		$data ['autosubmit'] = false;
		
		if (isset ( static::$paymentMethods [$this->session->data ['payment_method'] ['code']] )) {
			$method = static::$paymentMethods [$this->session->data ['payment_method'] ['code']];
			if ($method [self::IS_DIRECT_PAYMENT] === true) {
				if ($method [self::AUTO_CLICK_BUTTON] === true) {
					$data ['autosubmit'] = true;
				}
			} else {
				$method = static::$paymentMethods [$this->session->data ['payment_method'] ['code']];
				$action = new Action ( $method [self::CONFIRMATION_ROUTE] );
				$this->request->executeRoute ( $action );
			}
		}
		
		$this->response->setOutput ( $this->load->view ( $this->template, $data ) );
	}
	const IS_DIRECT_PAYMENT = 'is_direct_payment';
	const CONFIRMATION_ROUTE = 'confirmation_route';
	const AUTO_CLICK_BUTTON = 'auto_click_button';
	private static $paymentMethods = array (
			"authorizenet_aim" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => false 
			),
			"bank_transfer" => array (
					self::IS_DIRECT_PAYMENT => false,
					self::CONFIRMATION_ROUTE => "payment/bank_transfer/confirm",
					self::AUTO_CLICK_BUTTON => false 
			),
			"cheque" => array (
					self::IS_DIRECT_PAYMENT => false,
					self::CONFIRMATION_ROUTE => "payment/cheque/confirm",
					self::AUTO_CLICK_BUTTON => false 
			),
			"cod" => array (
					self::IS_DIRECT_PAYMENT => false,
					self::CONFIRMATION_ROUTE => "payment/cod/confirm",
					self::AUTO_CLICK_BUTTON => false 
			),
			"free_checkout" => array (
					self::IS_DIRECT_PAYMENT => false,
					self::CONFIRMATION_ROUTE => "payment/free_checkout/confirm",
					self::AUTO_CLICK_BUTTON => false 
			),
			"klarna_account" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => false 
			),
			"klarna_invoice" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => false 
			),
			"liqpay" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"moneybookers" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"nochex" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"paymate" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"paypoint" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"payza" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"perpetual_payments" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => false 
			),
			"pp_express" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"pp_pro" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => false 
			),
			"pp_pro_iframe" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => false 
			),
			"pp_pro_pf" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => false 
			),
			"pp_pro_uk" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => false 
			),
			"pp_standard" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"sagepay" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"sagepay_direct" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => false 
			),
			"sagepay_us" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => false 
			),
			"twocheckout" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"worldpay" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"multisafepay" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"multisafepay_amex" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"multisafepay_banktrans" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"multisafepay_dirdeb" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"multisafepay_directbank" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"multisafepay_giropay" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"multisafepay_ideal" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => false 
			),
			"multisafepay_maestro" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"multisafepay_mastercard" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"multisafepay_mistercash" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"multisafepay_payafter" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"multisafepay_paypal" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"multisafepay_visa" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"multisafepay_wallet" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"bpm" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			),
			"pasargad" => array (
					self::IS_DIRECT_PAYMENT => true,
					self::CONFIRMATION_ROUTE => null,
					self::AUTO_CLICK_BUTTON => true 
			) 
	);
	
	/*
	 * ORDER CONFIRMATION AND SAVE FUNCTIONS
	 */
	public function confirm() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'PUT') {
			$this->saveOrderToDatabase ();
		} else if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
			$this->confirmOrder ();
		} else if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			$this->confirmOrder ( "payment" );
		}
	}
}