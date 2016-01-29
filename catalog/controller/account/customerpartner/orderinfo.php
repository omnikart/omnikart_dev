<?php
class ControllerAccountCustomerpartnerOrderinfo extends Controller {
	private $data = array ();
	public function index() {
		if (! $this->customer->isLogged ()) {
			if (isset ( $this->request->get ['order_id'] )) {
				$this->session->data ['redirect'] = $this->url->link ( 'account/customerpartner/orderinfo&order_id=' . $this->request->get ['order_id'], '', 'SSL' );
			}
			$this->response->redirect ( $this->url->link ( 'account/login', '', 'SSL' ) );
		}
		
		$this->load->model ( 'account/customerpartner' );
		
		// $customerRights = $this->model_account_customerpartner->getCustomerGroupRights($this->customer->getGroupId());
		$customerRights = $this->customer->getRights ();
		if ($customerRights && ! array_key_exists ( 'view-all-order', $customerRights ['rights'] )) {
			$this->response->redirect ( $this->url->link ( 'account/account', '', 'SSL' ) );
		}
		
		$sellerId = $this->model_account_customerpartner->isSubUser ( $this->customer->getId () );
		if (! $customerRights ['isParent'] && ! $sellerId) {
			$this->data ['chkIsPartner'] = $this->model_account_customerpartner->chkIsPartner ();
		} else if ($sellerId) {
			$this->data ['chkIsPartner'] = true;
		} else {
			$this->data ['chkIsPartner'] = false;
		}
		
		if (! $this->data ['chkIsPartner'])
			$this->response->redirect ( $this->url->link ( 'account/account' ) );
		
		$this->language->load ( 'account/customerpartner/orderinfo' );
		
		if (isset ( $this->request->get ['order_id'] )) {
			$order_id = $this->request->get ['order_id'];
		} else {
			$order_id = 0;
		}
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST')) {
			
			if ($order_id) {
				
				if (isset ( $this->request->post ['tracking'] )) {
					$this->model_account_customerpartner->addOdrTracking ( $order_id, $this->request->post ['tracking'], $sellerId );
					$this->session->data ['success'] = $this->language->get ( 'text_success' );
				}
				
				$this->response->redirect ( $this->url->link ( 'account/customerpartner/orderinfo&order_id=' . $order_id, '', 'SSL' ) );
			}
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
		} else {
			$this->data ['success'] = '';
		}
		
		$this->data ['order_id'] = $order_id;
		
		$this->load->model ( 'account/order' );
		
		$order_info = $this->model_account_customerpartner->getOrder ( $order_id, $sellerId );
		
		$this->document->setTitle ( $this->language->get ( 'text_order' ) );
		
		$this->data ['breadcrumbs'] = array ();
		
		$this->data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home' ),
				'separator' => false 
		);
		
		$this->data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_account' ),
				'href' => $this->url->link ( 'account/account', '', 'SSL' ),
				'separator' => $this->language->get ( 'text_separator' ) 
		);
		
		$url = '';
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
		}
		
		$this->data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'heading_title' ),
				'href' => $this->url->link ( 'account/customerpartner/orderlist', $url, 'SSL' ),
				'separator' => $this->language->get ( 'text_separator' ) 
		);
		
		$this->data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_order' ),
				'href' => $this->url->link ( 'account/customerpartner/orderinfo', 'order_id=' . $order_id . $url, 'SSL' ),
				'separator' => $this->language->get ( 'text_separator' ) 
		);
		
		$this->data ['heading_title'] = $this->language->get ( 'text_order' );
		$this->data ['error_page_order'] = $this->language->get ( 'error_page_order' );
		$this->data ['text_order_detail'] = $this->language->get ( 'text_order_detail' );
		$this->data ['text_invoice_no'] = $this->language->get ( 'text_invoice_no' );
		$this->data ['text_order_id'] = $this->language->get ( 'text_order_id' );
		$this->data ['text_date_added'] = $this->language->get ( 'text_date_added' );
		$this->data ['text_shipping_method'] = $this->language->get ( 'text_shipping_method' );
		$this->data ['text_shipping_address'] = $this->language->get ( 'text_shipping_address' );
		$this->data ['text_payment_method'] = $this->language->get ( 'text_payment_method' );
		$this->data ['text_payment_address'] = $this->language->get ( 'text_payment_address' );
		$this->data ['text_history'] = $this->language->get ( 'text_history' );
		$this->data ['text_comment'] = $this->language->get ( 'text_comment' );
		$this->data ['text_wait'] = $this->language->get ( 'text_wait' );
		$this->data ['text_not_paid'] = $this->language->get ( 'text_not_paid' );
		$this->data ['text_paid'] = $this->language->get ( 'text_paid' );
		
		$this->data ['column_tracking_no'] = $this->language->get ( 'column_tracking_no' );
		$this->data ['column_name'] = $this->language->get ( 'column_name' );
		$this->data ['column_model'] = $this->language->get ( 'column_model' );
		$this->data ['column_quantity'] = $this->language->get ( 'column_quantity' );
		$this->data ['column_price'] = $this->language->get ( 'column_price' );
		$this->data ['column_total'] = $this->language->get ( 'column_total' );
		$this->data ['column_action'] = $this->language->get ( 'column_action' );
		$this->data ['column_date_added'] = $this->language->get ( 'column_date_added' );
		$this->data ['column_status'] = $this->language->get ( 'column_status' );
		$this->data ['column_comment'] = $this->language->get ( 'column_comment' );
		$this->data ['column_transaction_status'] = $this->language->get ( 'column_transaction_status' );
		
		$this->data ['button_invoice'] = $this->language->get ( 'button_invoice' );
		$this->data ['button_back'] = $this->language->get ( 'button_back' );
		$this->data ['button_add_history'] = $this->language->get ( 'button_add_history' );
		
		$this->data ['history_info'] = $this->language->get ( 'history_info' );
		
		$this->data ['entry_order_status'] = $this->language->get ( 'entry_order_status' );
		$this->data ['entry_notify'] = $this->language->get ( 'entry_notify' );
		$this->data ['entry_comment'] = $this->language->get ( 'entry_comment' );
		$this->data ['entry_notifyadmin'] = $this->language->get ( 'entry_notifyadmin' );
		
		$this->data ['errorPage'] = false;
		
		if ($order_info) {
			
			$this->data ['wksellerorderstatus'] = $this->config->get ( 'marketplace_sellerorderstatus' );
			
			if ($order_info ['invoice_no']) {
				$this->data ['invoice_no'] = $order_info ['invoice_prefix'] . $order_info ['invoice_no'];
			} else {
				$this->data ['invoice_no'] = '';
			}
			
			$this->data ['date_added'] = date ( $this->language->get ( 'date_format_short' ), strtotime ( $order_info ['date_added'] ) );
			
			if ($order_info ['payment_address_format']) {
				$format = $order_info ['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
			
			$find = array (
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}' 
			);
			
			$replace = array (
					'firstname' => $order_info ['payment_firstname'],
					'lastname' => $order_info ['payment_lastname'],
					'company' => $order_info ['payment_company'],
					'address_1' => $order_info ['payment_address_1'],
					'address_2' => $order_info ['payment_address_2'],
					'city' => $order_info ['payment_city'],
					'postcode' => $order_info ['payment_postcode'],
					'zone' => $order_info ['payment_zone'],
					'zone_code' => $order_info ['payment_zone_code'],
					'country' => $order_info ['payment_country'] 
			);
			
			$this->data ['payment_address'] = str_replace ( array (
					"\r\n",
					"\r",
					"\n" 
			), '<br />', preg_replace ( array (
					"/\s\s+/",
					"/\r\r+/",
					"/\n\n+/" 
			), '<br />', trim ( str_replace ( $find, $replace, $format ) ) ) );
			
			$this->data ['payment_method'] = $order_info ['payment_method'];
			
			if ($order_info ['shipping_address_format']) {
				$format = $order_info ['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
			
			$find = array (
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}' 
			);
			
			$replace = array (
					'firstname' => $order_info ['shipping_firstname'],
					'lastname' => $order_info ['shipping_lastname'],
					'company' => $order_info ['shipping_company'],
					'address_1' => $order_info ['shipping_address_1'],
					'address_2' => $order_info ['shipping_address_2'],
					'city' => $order_info ['shipping_city'],
					'postcode' => $order_info ['shipping_postcode'],
					'zone' => $order_info ['shipping_zone'],
					'zone_code' => $order_info ['shipping_zone_code'],
					'country' => $order_info ['shipping_country'] 
			);
			
			$this->data ['shipping_address'] = str_replace ( array (
					"\r\n",
					"\r",
					"\n" 
			), '<br />', preg_replace ( array (
					"/\s\s+/",
					"/\r\r+/",
					"/\n\n+/" 
			), '<br />', trim ( str_replace ( $find, $replace, $format ) ) ) );
			
			$this->data ['shipping_method'] = $order_info ['shipping_method'];
			
			$this->data ['products'] = array ();
			
			$products = $this->model_account_customerpartner->getSellerOrderProducts ( $order_id, $sellerId );
			
			foreach ( $products as $product ) {
				$option_data = array ();
				
				$options = $this->model_account_order->getOrderOptions ( $order_id, $product ['order_product_id'] );
				
				foreach ( $options as $option ) {
					if ($option ['type'] != 'file') {
						$value = $option ['value'];
					} else {
						$value = utf8_substr ( $option ['value'], 0, utf8_strrpos ( $option ['value'], '.' ) );
					}
					
					$option_data [] = array (
							'name' => $option ['name'],
							'value' => (utf8_strlen ( $value ) > 20 ? utf8_substr ( $value, 0, 20 ) . '..' : $value) 
					);
				}
				
				$product_tracking = $this->model_account_customerpartner->getOdrTracking ( $this->data ['order_id'], $product ['product_id'], $sellerId );
				
				if ($product ['paid_status'] == 1) {
					$paid_status = $this->language->get ( 'text_paid' );
				} else {
					$paid_status = $this->language->get ( 'text_not_paid' );
				}
				
				$this->data ['products'] [] = array (
						'product_id' => $product ['product_id'],
						'name' => $product ['name'],
						'model' => $product ['model'],
						'option' => $option_data,
						'tracking' => isset ( $product_tracking ['tracking'] ) ? $product_tracking ['tracking'] : '',
						'quantity' => $product ['quantity'],
						'paid_status' => $paid_status,
						'price' => $this->currency->format ( $product ['price'] + ($this->config->get ( 'config_tax' ) ? $product ['tax'] : 0), $order_info ['currency_code'], $order_info ['currency_value'] ),
						'total' => $this->currency->format ( $product ['total'] + ($this->config->get ( 'config_tax' ) ? ($product ['tax'] * $product ['quantity']) : 0), $order_info ['currency_code'], $order_info ['currency_value'] ) 
				);
			}
			
			// Voucher
			$this->data ['vouchers'] = array ();
			
			$vouchers = $this->model_account_order->getOrderVouchers ( $order_id );
			
			foreach ( $vouchers as $voucher ) {
				$this->data ['vouchers'] [] = array (
						'description' => $voucher ['description'],
						'amount' => $this->currency->format ( $voucher ['amount'], $order_info ['currency_code'], $order_info ['currency_value'] ) 
				);
			}
			
			$this->data ['totals'] = array ();
			
			$totals = $this->model_account_customerpartner->getOrderTotals ( $order_id, $sellerId );
			
			if ($totals and isset ( $totals [0] ['total'] ))
				$this->data ['totals'] [] ['total'] = $this->currency->format ( $totals [0] ['total'], $order_info ['currency_code'], 1 );
			
			$this->data ['comment'] = nl2br ( $order_info ['comment'] );
			
			$this->data ['histories'] = array ();
			
			$results = $this->model_account_customerpartner->getOrderHistories ( $order_id );
			
			foreach ( $results as $result ) {
				$this->data ['histories'] [] = array (
						'date_added' => date ( $this->language->get ( 'date_format_short' ), strtotime ( $result ['date_added'] ) ),
						'status' => $result ['status'],
						'comment' => nl2br ( $result ['comment'] ) 
				);
			}
			
			// list of status
			
			$this->load->model ( 'localisation/order_status' );
			
			$this->data ['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses ();
			
			$this->data ['order_status_id'] = $order_info ['order_status_id'];
		} else {
			$this->data ['errorPage'] = true;
		}
		
		$this->data ['action'] = $this->url->link ( 'account/customerpartner/orderinfo&order_id=' . $order_id, '', 'SSL' );
		$this->data ['continue'] = $this->url->link ( 'account/customerpartner/orderlist', '', 'SSL' );
		$this->data ['order_invoice'] = $this->url->link ( 'account/customerpartner/soldinvoice&order_id=' . $order_id, '', 'SSL' );
		
		/*
		 * Access according to membership plan
		 */
		$this->data ['isMember'] = true;
		if ($this->config->get ( 'wk_seller_group_status' )) {
			$this->data ['wk_seller_group_status'] = true;
			$this->load->model ( 'account/customer_group' );
			$isMember = $this->model_account_customer_group->getSellerMembershipGroup ( $this->customer->getId () );
			if ($isMember) {
				$allowedAccountMenu = $this->model_account_customer_group->getaccountMenu ( $isMember ['gid'] );
				if ($allowedAccountMenu ['value']) {
					$accountMenu = explode ( ',', $allowedAccountMenu ['value'] );
					if ($accountMenu && ! in_array ( 'orderhistory:orderhistory', $accountMenu )) {
						$this->data ['isMember'] = false;
					}
				}
			} else {
				$this->data ['isMember'] = false;
			}
		}
		/*
		 * end here
		 */
		
		$this->data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$this->data ['column_right'] = $this->load->controller ( 'common/column_right' );
		$this->data ['content_top'] = $this->load->controller ( 'common/content_top' );
		$this->data ['content_bottom'] = $this->load->controller ( 'common/content_bottom' );
		$this->data ['footer'] = $this->load->controller ( 'common/footer' );
		$this->data ['header'] = $this->load->controller ( 'common/header' );
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/account/customerpartner/orderinfo.tpl' )) {
			$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/account/customerpartner/orderinfo.tpl', $this->data ) );
		} else {
			$this->response->setOutput ( $this->load->view ( 'default/template/account/customerpartner/orderinfo.tpl', $this->data ) );
		}
	}
	public function history() {
		$this->language->load ( 'account/customerpartner/orderinfo' );
		$this->load->model ( 'checkout/order' );
		
		$json = array ();
		
		if ($this->request->server ['REQUEST_METHOD'] == 'POST' and isset ( $this->request->get ['order_id'] )) {
			$this->model_checkout_order->addOrderHistory ( $this->request->get ['order_id'], $this->request->post ['order_status_id'], $this->request->post ['comment'], $this->request->post ['notify'] );
			$json ['success'] = $this->language->get ( 'text_success_history' );
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
}
?>
