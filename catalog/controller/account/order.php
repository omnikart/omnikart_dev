<?php
class ControllerAccountOrder extends Controller {
	private $error = array ();
	public function index() {
		if (! $this->customer->isLogged ()) {
			$this->session->data ['redirect'] = $this->url->link ( 'account/order', '', 'SSL' );
			
			$this->response->redirect ( $this->url->link ( 'account/login', '', 'SSL' ) );
		}
		
		$this->load->language ( 'account/order' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home' ) 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_account' ),
				'href' => $this->url->link ( 'account/account', '', 'SSL' ) 
		);
		
		$url = '';
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
		}
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'heading_title' ),
				'href' => $this->url->link ( 'account/order', $url, 'SSL' ) 
		);
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		
		$data ['text_empty'] = $this->language->get ( 'text_empty' );
		
		$data ['column_order_id'] = $this->language->get ( 'column_order_id' );
		$data ['column_status'] = $this->language->get ( 'column_status' );
		$data ['column_date_added'] = $this->language->get ( 'column_date_added' );
		$data ['column_customer'] = $this->language->get ( 'column_customer' );
		$data ['column_product'] = $this->language->get ( 'column_product' );
		$data ['column_total'] = $this->language->get ( 'column_total' );
		
		$data ['button_view'] = $this->language->get ( 'button_view' );
		$data ['button_continue'] = $this->language->get ( 'button_continue' );
		
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}

		$data['orders'] = array();

		$this->load->model('account/order');

		$order_total = $this->model_account_order->getTotalOrders();
 
     $data['pdforders_order_status_customer'] = $this->config->get('pdforders_order_status_customer');
        

		$data['heading_title'] .= '('.$order_total.')';

		$results = $this->model_account_order->getOrders(($page - 1) * 10, 10);

		foreach ($results as $result) {
			$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
			$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

			$data['orders'][] = array(
 				'pdf'       => $this->url->link('account/order/pdf', 'order_id=' . $result['order_id'], 'SSL'),
				'order_status_id' => $this->model_account_order->getOrderstatusid($result['order_id']),
  			'order_id'   => $result['order_id'],
				'name'       => $result['firstname'] . ' ' . $result['lastname'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'products'   => ($product_total + $voucher_total),
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'href'       => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL'),
			);
		}
		
		$pagination = new Pagination ();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link ( 'account/order', 'page={page}', 'SSL' );
		
		$data ['pagination'] = $pagination->render ();
		
		$data ['results'] = sprintf ( $this->language->get ( 'text_pagination' ), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil ( $order_total / 10 ) );
		
		$data ['continue'] = $this->url->link ( 'account/account', '', 'SSL' );
		
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['column_right'] = $this->load->controller ( 'common/column_right' );
		$data ['content_top'] = $this->load->controller ( 'common/content_top' );
		$data ['content_bottom'] = $this->load->controller ( 'common/content_bottom' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		$data ['header'] = $this->load->controller ( 'common/header' );
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/account/order_list.tpl' )) {
			$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/account/order_list.tpl', $data ) );
		} else {
			$this->response->setOutput ( $this->load->view ( 'default/template/account/order_list.tpl', $data ) );
		}
	}
	public function info() {
		$this->load->language ( 'account/order' );
		
		if (isset ( $this->request->get ['order_id'] )) {
			$order_id = $this->request->get ['order_id'];
		} else {
			$order_id = 0;
		}
     
    $data['pdforders_order_status_customer'] = $this->config->get('pdforders_order_status_customer');

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('account/order');

		$order_info = $this->model_account_order->getOrdersBySuppliers($order_id);

		if ($order_info) {
			$this->document->setTitle ( $this->language->get ( 'text_order' ) );
			
			$data ['breadcrumbs'] = array ();
			
			$data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'text_home' ),
					'href' => $this->url->link ( 'common/home' ) 
			);
			
			$data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'text_account' ),
					'href' => $this->url->link ( 'account/account', '', 'SSL' ) 
			);
			
			$url = '';
			
			if (isset ( $this->request->get ['page'] )) {
				$url .= '&page=' . $this->request->get ['page'];
			}
			
			$data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'heading_title' ),
					'href' => $this->url->link ( 'account/order', $url, 'SSL' ) 
			);
			
			$data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'text_order' ),
					'href' => $this->url->link ( 'account/order/info', 'order_id=' . $this->request->get ['order_id'] . $url, 'SSL' ) 
			);
			
			$data ['heading_title'] = $this->language->get ( 'text_order' );
			
			$data ['text_order_detail'] = $this->language->get ( 'text_order_detail' );
			$data ['text_invoice_no'] = $this->language->get ( 'text_invoice_no' );
			$data ['text_order_id'] = $this->language->get ( 'text_order_id' );
			$data ['text_date_added'] = $this->language->get ( 'text_date_added' );
			$data ['text_shipping_method'] = $this->language->get ( 'text_shipping_method' );
			$data ['text_shipping_address'] = $this->language->get ( 'text_shipping_address' );
			$data ['text_payment_method'] = $this->language->get ( 'text_payment_method' );
			$data ['text_payment_address'] = $this->language->get ( 'text_payment_address' );
			$data ['text_history'] = $this->language->get ( 'text_history' );
			$data ['text_comment'] = $this->language->get ( 'text_comment' );
			
			$data ['column_name'] = $this->language->get ( 'column_name' );
			$data ['column_model'] = $this->language->get ( 'column_model' );
			$data ['column_quantity'] = $this->language->get ( 'column_quantity' );
			$data ['column_price'] = $this->language->get ( 'column_price' );
			$data ['column_total'] = $this->language->get ( 'column_total' );
			$data ['column_action'] = $this->language->get ( 'column_action' );
			$data ['column_date_added'] = $this->language->get ( 'column_date_added' );
			$data ['column_status'] = $this->language->get ( 'column_status' );
			$data ['column_comment'] = $this->language->get ( 'column_comment' );
			
			$data ['button_reorder'] = $this->language->get ( 'button_reorder' );
			$data ['button_return'] = $this->language->get ( 'button_return' );
			$data ['button_continue'] = $this->language->get ( 'button_continue' );
			
			if (isset ( $this->session->data ['error'] )) {
				$data ['error_warning'] = $this->session->data ['error'];
				
				unset ( $this->session->data ['error'] );
			} else {
				$data ['error_warning'] = '';
			}
			
			if (isset ( $this->session->data ['success'] )) {
				$data ['success'] = $this->session->data ['success'];
				
				unset ( $this->session->data ['success'] );
			} else {
				$data ['success'] = '';
			}

			if ($order_info['order_info']['invoice_no']) {
				$data['invoice_no'] = $order_info['order_info']['invoice_prefix'] . $order_info['order_info']['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}

			$data['order_id'] = $this->request->get['order_id'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['order_info']['date_added']));

			if ($order_info['order_info']['payment_address_format']) {
				$format = $order_info['order_info']['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array('{firstname}','{lastname}','{company}','{address_1}','{address_2}','{city}','{postcode}','{zone}','{country}');

			$replace = array(
				'firstname' => $order_info['order_info']['payment_firstname'],
				'lastname'  => $order_info['order_info']['payment_lastname'],
				'company'   => $order_info['order_info']['payment_company'],
				'address_1' => $order_info['order_info']['payment_address_1'],
				'address_2' => $order_info['order_info']['payment_address_2'],
				'city'      => $order_info['order_info']['payment_city'],
				'postcode'  => $order_info['order_info']['payment_postcode'],
				'zone'      => $order_info['order_info']['payment_zone'],
				'country'   => $order_info['order_info']['payment_country']
			);

			$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$data['payment_method'] = $order_info['order_info']['payment_method'];

			if ($order_info['order_info']['shipping_address_format']) {
				$format = $order_info['order_info']['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array('{firstname}','{lastname}','{company}','{address_1}','{address_2}','{city}','{postcode}','{zone}','{country}');

			$replace = array(
				'firstname' => $order_info['order_info']['shipping_firstname'],
				'lastname'  => $order_info['order_info']['shipping_lastname'],
				'company'   => $order_info['order_info']['shipping_company'],
				'address_1' => $order_info['order_info']['shipping_address_1'],
				'address_2' => $order_info['order_info']['shipping_address_2'],
				'city'      => $order_info['order_info']['shipping_city'],
				'postcode'  => $order_info['order_info']['shipping_postcode'],
				'zone'      => $order_info['order_info']['shipping_zone'],
				'country'   => $order_info['order_info']['shipping_country']
			);

			$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$data['shipping_method'] = $order_info['order_info']['shipping_method'];			$this->load->model('catalog/product');
			$this->load->model('tool/upload');

			// Products
			$data['vendors'] = array();

			foreach ($order_info['vendors'] as $vendor_id => $vendor) {
				$data['vendors'][$vendor_id] = array();
				$data['vendors'][$vendor_id]['info'] = $this->model_account_customerpartner->getProfile($vendor_id);
				
				
				if ($order_info['order_info']['invoice_no']) {
					$data['vendors'][$vendor_id]['invoice_no'] = $order_info['order_info']['invoice_prefix'] .  $order_info['order_info']['invoice_no'].$vendor_id;
				} else {
					$data['vendors'][$vendor_id]['invoice_no'] = '';
				}
				
				$data['vendors'][$vendor_id]['order_status_id'] = $vendor['order_status_id'];
				$data['vendors'][$vendor_id]['order_status'] = $vendor['order_status'];

				$data['vendors'][$vendor_id]['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['order_info']['date_added']));

				foreach ($vendor['products'] as $product) {
				$option_data = array();

				$options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}

				$product_info = $this->model_catalog_product->getProduct($product['product_id']);

				if ($product_info) {
					$reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], 'SSL');
				} else {
					$reorder = '';
				}

				$data['vendors'][$vendor_id]['products'][] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'], $order_info['order_info']['currency_code'], $order_info['order_info']['currency_value']),
					'total'    => $this->currency->format($product['total'] , $order_info['order_info']['currency_code'], $order_info['order_info']['currency_value']),
					'reorder'  => $reorder,
					'return'   => $this->url->link('account/return/add', 'order_id=' . $order_info['order_info']['order_id'] . '&product_id=' . $product['product_id'], 'SSL')
				);
				}
				
				$data['vendors'][$vendor_id]['totals'] = array();
				$totals = $this->model_account_order->getOrderTotals($this->request->get['order_id'],$vendor_id);

				foreach ($totals as $total) {
					$data['vendors'][$vendor_id]['totals'][] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $order_info['order_info']['currency_code'], $order_info['order_info']['currency_value']),
					);
				}
				
				$data['vendors'][$vendor_id]['histories'] = array();
				$results = $this->model_account_order->getOrderHistories($order_id,$vendor['customerpartner_order_id']);
				
				foreach ($results as $result) {
					$data['vendors'][$vendor_id]['histories'][] = array(
						'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
						'status'     => $result['status'],
						'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
					);
				}
				
				
				$data['vendors'][$vendor_id]['pdf'] = $this->url->link('account/order/pdf', 'order_id=' . $order_id.'&vendor_id='.$vendor_id, 'SSL');

			}

			// Voucher
			$data['vouchers'] = array();

			$vouchers = $this->model_account_order->getOrderVouchers($this->request->get['order_id']);

			foreach ($vouchers as $voucher) {
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			// Totals


			$data['comment'] = nl2br($order_info['order_info']['comment']);

			$data['continue'] = $this->url->link('account/order', '', 'SSL');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_info.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/order_info.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/account/order_info.tpl', $data));
			}
		} else {
			$this->document->setTitle($this->language->get('text_order'));

			$data['heading_title'] = $this->language->get('text_order');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/order', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_order'),
				'href' => $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL')
			);

			$data['continue'] = $this->url->link('account/order', '', 'SSL');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}

	public function infosupplierwise() {
		$this->load->language('account/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (isset($this->request->get['vendor_id'])) {
			$seller_id = $this->request->get['vendor_id'];
		} else {
			$seller_id = 0;
		}

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('account/order');
		$this->load->model('account/customerpartner');
		$order_info = $this->model_account_customerpartner->getOrder($order_id,$seller_id);

		if ($order_info && $seller_id) {
			$this->document->setTitle($this->language->get('text_order'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', 'SSL')
			);

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/order', $url, 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_order'),
				'href' => $this->url->link('account/order/info', 'order_id=' . $this->request->get['order_id'] . $url, 'SSL')
			);

			$data['heading_title'] = $this->language->get('text_order');

			$data['text_order_detail'] = $this->language->get('text_order_detail');
			$data['text_invoice_no'] = $this->language->get('text_invoice_no');
			$data['text_order_id'] = $this->language->get('text_order_id');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$data['text_shipping_address'] = $this->language->get('text_shipping_address');
			$data['text_payment_method'] = $this->language->get('text_payment_method');
			$data['text_payment_address'] = $this->language->get('text_payment_address');
			$data['text_history'] = $this->language->get('text_history');
			$data['text_comment'] = $this->language->get('text_comment');

			$data['column_name'] = $this->language->get('column_name');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');
			$data['column_action'] = $this->language->get('column_action');
			$data['column_date_added'] = $this->language->get('column_date_added');
			$data['column_status'] = $this->language->get('column_status');
			$data['column_comment'] = $this->language->get('column_comment');

			$data['button_reorder'] = $this->language->get('button_reorder');
			$data['button_return'] = $this->language->get('button_return');
			$data['button_continue'] = $this->language->get('button_continue');

			if (isset($this->session->data['error'])) {
				$data['error_warning'] = $this->session->data['error'];

				unset($this->session->data['error']);
			} else {
				$data['error_warning'] = '';
			}

			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}

			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$data ['invoice_no'] = '';
			}
			
			$data ['order_id'] = $this->request->get ['order_id'];
			$data ['date_added'] = date ( $this->language->get ( 'date_format_short' ), strtotime ( $order_info ['date_added'] ) );
			
			if ($order_info ['payment_address_format']) {
				$format = $order_info ['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array('{firstname}','{lastname}','{company}','{address_1}','{address_2}','{city}','{postcode}','{zone}','{zone_code}','{country}');

			$replace = array(
				'firstname' => $order_info['payment_firstname'],
				'lastname'  => $order_info['payment_lastname'],
				'company'   => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
				'country'   => $order_info['payment_country']
			);
			
			$data ['payment_address'] = str_replace ( array (
					"\r\n",
					"\r",
					"\n" 
			), '<br />', preg_replace ( array (
					"/\s\s+/",
					"/\r\r+/",
					"/\n\n+/" 
			), '<br />', trim ( str_replace ( $find, $replace, $format ) ) ) );
			
			$data ['payment_method'] = $order_info ['payment_method'];
			
			if ($order_info ['shipping_address_format']) {
				$format = $order_info ['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array('{firstname}','{lastname}','{company}','{address_1}','{address_2}','{city}','{postcode}','{zone}','{zone_code}','{country}');

			$replace = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country'   => $order_info['shipping_country']
			);
			
			$data ['shipping_address'] = str_replace ( array (
					"\r\n",
					"\r",
					"\n" 
			), '<br />', preg_replace ( array (
					"/\s\s+/",
					"/\r\r+/",
					"/\n\n+/" 
			), '<br />', trim ( str_replace ( $find, $replace, $format ) ) ) );
			
			$data ['shipping_method'] = $order_info ['shipping_method'];
			
			$this->load->model ( 'catalog/product' );
			$this->load->model ( 'tool/upload' );
			
			// Products
			$data['products'] = array();

			$products = $this->model_account_customerpartner->getSellerOrderProducts($this->request->get['order_id']);

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
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
				
				$product_info = $this->model_catalog_product->getProduct ( $product ['product_id'] );
				
				if ($product_info) {
					$reorder = $this->url->link ( 'account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product ['order_product_id'], 'SSL' );
				} else {
					$reorder = '';
				}
				
				$data ['products'] [] = array (
						'name' => $product ['name'],
						'model' => $product ['model'],
						'option' => $option_data,
						'quantity' => $product ['quantity'],
						'price' => $this->currency->format ( $product ['price'] + ($this->config->get ( 'config_tax' ) ? $product ['tax'] : 0), $order_info ['currency_code'], $order_info ['currency_value'] ),
						'total' => $this->currency->format ( $product ['total'] + ($this->config->get ( 'config_tax' ) ? ($product ['tax'] * $product ['quantity']) : 0), $order_info ['currency_code'], $order_info ['currency_value'] ),
						'reorder' => $reorder,
						'return' => $this->url->link ( 'account/return/add', 'order_id=' . $order_info ['order_id'] . '&product_id=' . $product ['product_id'], 'SSL' ) 
				);
			}
			
			// Voucher
			$data ['vouchers'] = array ();
			
			$vouchers = $this->model_account_order->getOrderVouchers ( $this->request->get ['order_id'] );
			
			foreach ( $vouchers as $voucher ) {
				$data ['vouchers'] [] = array (
						'description' => $voucher ['description'],
						'amount' => $this->currency->format ( $voucher ['amount'], $order_info ['currency_code'], $order_info ['currency_value'] ) 
				);
			}
			
			// Totals
			$data['totals'] = array();

      $totals = $this->model_account_order->getOrderTotals($this->request->get['order_id'],$this->request->get['vendor_id']);

			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}
			
			$data ['comment'] = nl2br ( $order_info ['comment'] );
			
			// History
			$data['histories'] = array();

			$results = $this->model_account_customerpartner->getOrderHistories($order_id,$order_info['id']);
			$sort_order = array();
			
			foreach ($results as $key => $result) {
				$sort_order[$key] = strtotime($result['date_added']);
				
				$data['histories'][$key] = array(
						'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
						'status'     => $result['status'],
						'comment'    => nl2br($result['comment'])
				);
			}
			
			$data ['continue'] = $this->url->link ( 'account/order', '', 'SSL' );
			
			$data ['column_left'] = $this->load->controller ( 'common/column_left' );
			$data ['column_right'] = $this->load->controller ( 'common/column_right' );
			$data ['content_top'] = $this->load->controller ( 'common/content_top' );
			$data ['content_bottom'] = $this->load->controller ( 'common/content_bottom' );
			$data ['footer'] = $this->load->controller ( 'common/footer' );
			$data ['header'] = $this->load->controller ( 'common/header' );
			
			if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/account/order_info.tpl' )) {
				$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/account/order_info.tpl', $data ) );
			} else {
				$this->response->setOutput ( $this->load->view ( 'default/template/account/order_info.tpl', $data ) );
			}
		} else {
			$this->document->setTitle ( $this->language->get ( 'text_order' ) );
			
			$data ['heading_title'] = $this->language->get ( 'text_order' );
			
			$data ['text_error'] = $this->language->get ( 'text_error' );
			
			$data ['button_continue'] = $this->language->get ( 'button_continue' );
			
			$data ['breadcrumbs'] = array ();
			
			$data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'text_home' ),
					'href' => $this->url->link ( 'common/home' ) 
			);
			
			$data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'text_account' ),
					'href' => $this->url->link ( 'account/account', '', 'SSL' ) 
			);
			
			$data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'heading_title' ),
					'href' => $this->url->link ( 'account/order', '', 'SSL' ) 
			);
			
			$data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'text_order' ),
					'href' => $this->url->link ( 'account/order/info', 'order_id=' . $order_id, 'SSL' ) 
			);
			
			$data ['continue'] = $this->url->link ( 'account/order', '', 'SSL' );
			
			$data ['column_left'] = $this->load->controller ( 'common/column_left' );
			$data ['column_right'] = $this->load->controller ( 'common/column_right' );
			$data ['content_top'] = $this->load->controller ( 'common/content_top' );
			$data ['content_bottom'] = $this->load->controller ( 'common/content_bottom' );
			$data ['footer'] = $this->load->controller ( 'common/footer' );
			$data ['header'] = $this->load->controller ( 'common/header' );
			
			if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/error/not_found.tpl' )) {
				$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/error/not_found.tpl', $data ) );
			} else {
				$this->response->setOutput ( $this->load->view ( 'default/template/error/not_found.tpl', $data ) );
			}
		}
	}
	public function pdf() {
		$this->load->language ( 'account/order' );
		
		if (isset ( $this->request->get ['order_id'] )) {
			$order_id = $this->request->get ['order_id'];
		} else {
			$order_id = 0;
		}
		
		if (! $this->customer->isLogged ()) {
			$this->session->data ['redirect'] = $this->url->link ( 'account/order/info', 'order_id=' . $order_id, 'SSL' );
			
			$this->response->redirect ( $this->url->link ( 'account/login', '', 'SSL' ) );
		}
		
		$data ['title'] = $this->language->get ( 'text_invoice' );
		
		if ($this->request->server ['HTTPS']) {
			$data ['base'] = HTTPS_SERVER;
		} else {
			$data ['base'] = HTTP_SERVER;
		}
		
		if (is_file ( DIR_IMAGE . $this->config->get ( 'config_logo' ) )) {
			$data ['logo'] = HTTP_SERVER . 'image/' . $this->config->get ( 'config_logo' );
		} else {
			$data ['logo'] = '';
		}
		
		$data ['direction'] = $this->language->get ( 'direction' );
		$data ['lang'] = $this->language->get ( 'code' );
		
		$data ['text_invoice'] = $this->language->get ( 'text_invoice' );
		$data ['text_order_detail'] = $this->language->get ( 'text_order_detail' );
		$data ['text_order_id'] = $this->language->get ( 'text_order_id' );
		$data ['text_date_added'] = $this->language->get ( 'text_date_added' );
		$data ['text_telephone'] = $this->language->get ( 'text_telephone' );
		$data ['text_fax'] = $this->language->get ( 'text_fax' );
		$data ['text_email'] = $this->language->get ( 'text_email' );
		$data ['text_website'] = $this->language->get ( 'text_website' );
		$data ['text_vattin'] = $this->language->get ( 'text_vattin' );
		$data ['text_to'] = $this->language->get ( 'text_to' );
		$data ['text_ship_to'] = $this->language->get ( 'text_ship_to' );
		$data ['text_payment_method'] = $this->language->get ( 'text_payment_method' );
		$data ['text_shipping_method'] = $this->language->get ( 'text_shipping_method' );
		
		$data ['column_product'] = $this->language->get ( 'column_product_name' );
		$data ['column_model'] = $this->language->get ( 'column_model' );
		$data ['column_quantity'] = $this->language->get ( 'column_quantity' );
		$data ['column_price'] = $this->language->get ( 'column_price' );
		$data ['column_total'] = $this->language->get ( 'column_total' );
		$data ['column_image'] = "Image";
		$data ['column_comment'] = $this->language->get ( 'column_comment' );
		require_once ('tcpdf/tcpdf.php');
		$pdf = new TCPDF ( PDF_PAGE_ORIENTATION, PDF_UNIT, $this->config->get ( 'pdforders_format' ), true, 'UTF-8', false );
		$pdf->SetCreator ( PDF_CREATOR );
		$pdf->SetAuthor ( $this->config->get ( 'config_owner' ) );
		$pdf->SetTitle ( 'Order PDF' );
		$pdf->SetSubject ( 'PDF Invoice' );
		$pdf->SetKeywords ( 'TCPDF, PDF Invoice' );
		$pdf->setPrintHeader ( false );
		$pdf->setFooterData ( array (
				0,
				64,
				0 
		), array (
				0,
				64,
				128 
		) );
		$pdf->setFooterFont ( Array (
				PDF_FONT_NAME_DATA,
				'',
				PDF_FONT_SIZE_DATA 
		) );
		$pdf->SetDefaultMonospacedFont ( PDF_FONT_MONOSPACED );
		$pdf->SetFooterMargin ( PDF_MARGIN_FOOTER );
		$pdf->SetDefaultMonospacedFont ( PDF_FONT_MONOSPACED );
		$pdf->SetMargins ( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );
		$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
		$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
		
		$this->load->model ( 'account/order' );
		$this->load->model ( 'setting/setting' );
		
		$order_info = $this->model_account_order->getOrder ( $order_id );
		
		if ($order_info) {
			$store_info = $this->model_setting_setting->getSetting ( 'config', $order_info ['store_id'] );
			
			if ($store_info) {
				$store_address = $store_info ['config_address'];
				$store_email = $store_info ['config_email'];
				$store_telephone = $store_info ['config_telephone'];
				$store_fax = $store_info ['config_fax'];
			} else {
				$store_address = $this->config->get ( 'config_address' );
				$store_email = $this->config->get ( 'config_email' );
				$store_telephone = $this->config->get ( 'config_telephone' );
				$store_fax = $this->config->get ( 'config_fax' );
			}
			
			if ($order_info ['invoice_no']) {
				$invoice_no = $order_info ['invoice_prefix'] . $order_info ['invoice_no'];
			} else {
				$invoice_no = '';
			}
			
			if ($order_info ['payment_address_format']) {
				$format = $order_info ['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n " . '{company}' . "\n " . '{address_1}' . "\n " . '{address_2}' . "\n " . '{city} {postcode}' . "\n " . '{zone}' . "\n " . '{country}';
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
			$pdf->SetFont ( $this->config->get ( 'pdforders_fontstyle' ), '', $this->config->get ( 'pdforders_fontsize' ), '', true );
			$payment_address = str_replace ( array (
					"\r\n",
					"\r",
					"\n" 
			), '<br />', preg_replace ( array (
					"/\s\s+/",
					"/\r\r+/",
					"/\n\n+/" 
			), '<br />', trim ( str_replace ( $find, $replace, $format ) ) ) );
			
			if ($order_info ['shipping_address_format']) {
				$format = $order_info ['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n " . '{company}' . "\n " . '{address_1}' . "\n " . '{address_2}' . "\n " . '{city} {postcode}' . "\n " . '{zone}' . "\n " . '{country}';
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
			
			$shipping_address = str_replace ( array (
					"\r\n",
					"\r",
					"\n" 
			), '<br />', preg_replace ( array (
					"/\s\s+/",
					"/\r\r+/",
					"/\n\n+/" 
			), '<br />', trim ( str_replace ( $find, $replace, $format ) ) ) );
			
			$this->load->model ( 'tool/upload' );
			$this->load->model ( 'tool/image' );
			
			$product_data = array ();
			
			$products = $this->model_account_order->getorderProducts ( $order_id );
			
			$productcount = count ( $products );
			$addextrarows = false;
			if ($this->config->get ( 'pdforders_numberproducts' ) > $productcount) {
				$addextrarows = true;
			}
			
			foreach ( $products as $product ) {
				$option_data = array ();
				
				$options = $this->model_account_order->getOrderOptions ( $this->request->get ['order_id'], $product ['order_product_id'] );
				
				foreach ( $options as $option ) {
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
							'value' => $value 
					);
				}
				
				if ($this->config->get ( 'pdforders_showimage' )) {
					$image = $this->model_account_order->getProductimage ( $product ['product_id'] );
					
					if (is_file ( DIR_IMAGE . $image )) {
						$image = $this->model_tool_image->resize ( $image, 60, 60 );
						$image = str_replace ( ' ', '%20', $image );
					} else {
						$image = $this->model_tool_image->resize ( 'no_image.png', 60, 60 );
					}
				} else {
					$image = "";
				}
				
				$product_data [] = array (
						'name' => $product ['name'],
						'thumb' => $image,
						'model' => $product ['model'],
						'option' => $option_data,
						'quantity' => $product ['quantity'],
						'price' => $this->currency->format ( $product ['price'] + ($this->config->get ( 'config_tax' ) ? $product ['tax'] : 0), $order_info ['currency_code'], $order_info ['currency_value'] ),
						'total' => $this->currency->format ( $product ['total'] + ($this->config->get ( 'config_tax' ) ? ($product ['tax'] * $product ['quantity']) : 0), $order_info ['currency_code'], $order_info ['currency_value'] ) 
				);
			}
			$pdf->AddPage ( $this->config->get ( 'pdforders_orientation' ), $this->config->get ( 'pdforders_format' ) );
			$tbl = "";
			$voucher_data = array ();
			
			$total_data = array ();
			
			$totals = $this->model_account_order->getOrderTotals ( $order_id );
			
			foreach ( $totals as $total ) {
				$total_data [] = array (
						'title' => $total ['title'],
						'text' => $this->currency->format ( $total ['value'], $order_info ['currency_code'], $order_info ['currency_value'] ) 
				);
			}
			
			if ($data ['logo'] && $this->config->get ( 'pdforders_logo' )) {
				$tbl .= '<table cellpadding="1" cellspacing="1" border="0" ><tr style="text-align:left;"><td><img src="' . $data ['logo'] . '" border="0" width="auto" /></td><td align="right">' . $data ['text_invoice'] . '#' . $order_id . '</td></tr></table>';
			} else {
				$tbl .= '<table cellpadding="1" cellspacing="1" border="0" ><tr style="text-align:left;"><td>' . $data ['text_invoice'] . '#' . $order_id . '</td></tr></table>';
			}
			
			$tbl .= '<table border="0" cellpadding="4">';
			
			$tbl .= '<tbody><tr><td><b>' . $order_info ['store_name'] . '</b><br/><b>Address: </b> ' . nl2br ( $store_address ) . '<br><br><b>' . $data ['text_telephone'] . '</b> ' . $store_telephone . '<br />';
			if (! ($this->config->get ( 'pdforders_vattin' ))) {
				$tbl .= '<b>' . $data ['text_vattin'] . '</b>' . $this->config->get ( 'pdforders_vattin' ) . '<br /> ';
			}
			if ($store_fax) {
				$tbl .= '<b>' . $data ['text_fax'] . '</b>' . $store_fax . '<br /> ';
			}
			
			$tbl .= '<b>' . $data ['text_email'] . '</b>' . rtrim ( $store_email, '/' ) . '<br/><b>' . $data ['text_website'] . '</b><a href=' . rtrim ( $order_info ['store_url'], '/' ) . '>' . rtrim ( $order_info ['store_url'], '/' ) . '</a></td><td><b>' . $data ['text_date_added'] . '</b> ' . date ( $this->language->get ( 'date_format_short' ), strtotime ( $order_info ['date_added'] ) ) . '<br />';
			
			if ($invoice_no) {
				$tbl .= '<b>' . $data ['text_invoice'] . '</b> ' . $invoice_no . '<br />';
			}
			
			$tbl .= '<b>' . $data ['text_order_id'] . '</b> ' . $order_id . '<br /><b>' . $data ['text_payment_method'] . '</b> ' . $order_info ['payment_method'] . '<br/>';
			
			if ($order_info ['shipping_method']) {
				$tbl .= '<b>' . $data ['text_shipping_method'] . '</b> ' . $order_info ['shipping_method'] . '<br />';
			}
			
			$tbl .= '</td></tr></tbody></table>';
			
			$tbl .= '<table border="0.2" cellpadding="4"><thead><tr><td style="width: 50%;"><b>' . $data ['text_to'] . '</b></td><td style="width: 50%;"><b>' . $data ['text_ship_to'] . '</b></td></tr></thead><tbody><tr><td>' . $payment_address . '<br>' . $order_info ['email'] . '<br>' . $order_info ['telephone'] . '</td><td >' . $shipping_address . '</td></tr></tbody></table>';
			
			$tbl .= '<table border="0.2"  cellpadding="4" ><thead><tr>';
			if ($this->config->get ( 'pdforders_showimage' )) {
				$tbl .= '<td style="width: 12%;" align="left"><b>' . $data ['column_image'] . '</b></td>';
				$tbl .= '<td style="width: 38%;" align="left"><b>' . $data ['column_product'] . '</b></td>';
			} else {
				$tbl .= '<td style="width: 50%;" align="left"><b>' . $data ['column_product'] . '</b></td>';
			}
			$tbl .= '<td style="width: 14%;" align="left"><b>' . $data ['column_model'] . '</b></td><td style="width: 13%;" align="right"><b>' . $data ['column_quantity'] . '</b></td><td style="width: 11%;" align="right"><b>' . $data ['column_price'] . '</b></td><td style="width: 12%;" align="right"><b>' . $data ['column_total'] . '</b></td></tr></thead>';
			$tbl .= '<tbody>';
			foreach ( $product_data as $product ) {
				$tbl .= '<tr>';
				if ($this->config->get ( 'pdforders_showimage' )) {
					if ($product ['thumb']) {
						$tbl .= '<td style="width: 12%;" align="left"><img src="' . $product ['thumb'] . '" alt="' . $product ['name'] . '" title="' . $product ['name'] . '" class="img-thumbnail" /></td>';
					}
					$tbl .= '<td style="width: 38%;" align="left">' . $product ['name'];
				} else {
					$tbl .= '<td style="width: 50%;" align="left">' . $product ['name'];
				}
				
				foreach ( $product ['option'] as $option ) {
					$tbl .= '<br /><small> - ' . $option ['name'] . ': ' . $option ['value'] . '</small>';
				}
				$tbl .= ' </td><td style="width: 14%;" align="left">' . $product ['model'] . '</td><td style="width: 13%;" align="right">' . $product ['quantity'] . '</td><td style="width: 11%;" align="right">' . $product ['price'] . '</td><td style="width: 12%;" align="right">' . $product ['total'] . '</td></tr>';
			}
			if ($this->config->get ( 'pdforders_showimage' )) {
				$colspan = 5;
			} else {
				$colspan = 4;
			}
			if ($this->config->get ( 'pdforders_addextrarows' ) && $addextrarows) {
				for($i = 0; $i < $this->config->get ( 'pdforders_numberextrarows' ); $i ++) {
					$tbl .= '<tr><td colspan="' . $colspan . '"></td><td></td></tr>';
				}
			}
			
			foreach ( $voucher_data as $voucher ) {
				$tbl .= '<tr><td>' . $voucher ['description'] . '</td><td align="right">1</td><td align="right">' . $voucher ['amount'] . '</td><td align="right">' . $voucher ['amount'] . '</td></tr>';
			}
			
			foreach ( $total_data as $total ) {
				$tbl .= '<tr><td align="right" colspan="' . $colspan . '"><b>' . $total ['title'] . '</b></td><td align="right">' . $total ['text'] . '</td></tr>';
			}
			$tbl .= '</tbody></table>';
			if ($order_info ['comment']) {
				$tbl .= '<table border="0" cellpadding="4"><thead><tr><td><b>' . $data ['column_comment'] . '</b></td></tr></thead><tbody><tr><td>' . nl2br ( $order_info ['comment'] ) . '</td></tr></tbody></table>';
			}
			$message = $this->config->get ( 'pdforders_textfooter' );
			if (isset ( $message [$this->config->get ( 'config_language_id' )] )) {
				$tbl .= '<br><br><br>';
				$tbl .= '<table border="0" cellpadding="4"><tbody><tr><td align="left">' . $message [$this->config->get ( 'config_language_id' )] ['name'] . '</td></tr></tbody></table>';
			}
			$pdf->writeHTML ( $tbl, true, false, false, false, '' );
		}
		
		$pdf->Output ( 'Invoice-Order' . $order_id . '.pdf', 'I' );
	}

	public function repairorders() {
		$this->db->query("TRUNCATE ".DB_PREFIX."customerpartner_order");
		$this->db->query("INSERT INTO ".DB_PREFIX."customerpartner_order (`order_id`,`customer_id`,`order_status_id`) SELECT DISTINCT `order_id`,`customer_id`,`order_status_id` FROM `om_customerpartner_to_order` WHERE 1");
		
		$this->db->query("UPDATE `om_customerpartner_order` co SET `date_added` = (SELECT `date_added` FROM `om_order` o WHERE o.order_id=co.order_id LIMIT 1), `date_modified` = (SELECT `date_modified` FROM `om_order` o WHERE o.order_id=co.order_id LIMIT 1) WHERE 1");
	}
	public function pdf() {

		$this->load->language('account/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (isset($this->request->get['vendor_id'])) {
			$vendor_id = $this->request->get['vendor_id'];
		} else {
			$vendor_id = 0;
		}

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}


		$data['title'] = $this->language->get('text_invoice');

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = HTTP_SERVER . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		$data['text_invoice'] = $this->language->get('text_invoice');
		$data['text_order_detail'] = $this->language->get('text_order_detail');
		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_website'] = $this->language->get('text_website');
		$data['text_vattin']  = $this->language->get('text_vattin');
		$data['text_to'] = $this->language->get('text_to');
		$data['text_ship_to'] = $this->language->get('text_ship_to');
		$data['text_payment_method'] = $this->language->get('text_payment_method');
		$data['text_shipping_method'] = $this->language->get('text_shipping_method');

		$data['column_product'] = $this->language->get('column_product_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');$data['column_image'] = "Image";$data['column_comment'] = $this->language->get('column_comment');require_once('tcpdf/tcpdf.php');$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $this->config->get('pdforders_format'), true, 'UTF-8', false);$pdf->SetCreator(PDF_CREATOR);$pdf->SetAuthor($this->config->get('config_owner'));$pdf->SetTitle('Order PDF');$pdf->SetSubject('PDF Invoice');$pdf->SetKeywords('TCPDF, PDF Invoice');$pdf->setPrintHeader(false);$pdf->setFooterData(array(0,64,0), array(0,64,128));$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		$this->load->model('account/order');
		$this->load->model('setting/setting');
		$this->load->model('account/customerpartner');

		$order_info = $this->model_account_order->getOrder($order_id,$vendor_id);
		$customer_info = $this->model_account_customerpartner->getProfile($vendor_id);

		if ($order_info && $customer_info && $vendor_id) {
			$this->load->model('account/customer');
			$this->load->model('account/address');
			$supplier_address = $this->model_account_address->getAddress($customer_info['address_id']);
			
				//$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

					$store_address = $customer_info['companyname']."\n".$supplier_address['address_1'].($supplier_address['address_2']?"\n".$supplier_address['address_2']:"")."\n".$supplier_address['city']."\n".$supplier_address['postcode']."\n".$supplier_address['zone']."\n".$supplier_address['country'];
					$store_email = $customer_info['email'];
					$store_telephone = $customer_info['telephone'];
					$store_fax = "";

				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'].'-'.$vendor_id;
				} else {
					$invoice_no = '';
				}

				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n " . '{company}' .  "\n " . '{address_1}' .  "\n " . '{address_2}' .  "\n " . '{city} {postcode}' .  "\n " . '{zone}' . "\n " . '{country}';
				}

				$find = array(
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

				$replace = array(
					'firstname' => $order_info['payment_firstname'],
					'lastname'  => $order_info['payment_lastname'],
					'company'   => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'zone_code' => $order_info['payment_zone_code'],
					'country'   => $order_info['payment_country']
				);
				$pdf->SetFont($this->config->get('pdforders_fontstyle'), '',$this->config->get('pdforders_fontsize') , '', true);
				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' .  "\n " . '{company}' .  "\n " . '{address_1}' .  "\n " . '{address_2}' .  "\n ". '{city} {postcode}' .  "\n " . '{zone}' .  "\n " . '{country}';
				}

				$find = array(
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

				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country']
				);

				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$this->load->model('tool/upload');
				$this->load->model('tool/image');

				$product_data = array();

				$products = $this->model_account_order->getorderProducts($order_id,$vendor_id);

				$productcount  = count($products);$addextrarows = false;
				if($this->config->get('pdforders_numberproducts') > $productcount) {
					$addextrarows = true;
				}

				foreach ($products as $product) {
					$option_data = array();

					$options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

							if ($upload_info) {
								$value = $upload_info['name'];
							} else {
								$value = '';
							}
						}

						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $value
						);
					}

					if($this->config->get('pdforders_showimage')) {
							$image = $this->model_account_order->getProductimage($product['product_id']);

							if (is_file(DIR_IMAGE . $image)) {
								$image = $this->model_tool_image->resize($image, 60, 60);
								$image = str_replace(' ','%20',$image);
							} else {
								$image = $this->model_tool_image->resize('no_image.png', 60, 60);
							}
					} else {
						$image = "";
					} 

					$product_data[] = array(
						'name'     => $product['name'],
						'thumb'     => $image,
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
					);
				}
				$pdf->AddPage($this->config->get('pdforders_orientation'), $this->config->get('pdforders_format'));
				$tbl = "";
				$voucher_data = array();

				$total_data = array();

				$totals = $this->model_account_order->getOrderTotals($order_id,$vendor_id);

				foreach ($totals as $total) {
					$total_data[] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
					);
				} 
				
				if($data['logo'] && $this->config->get('pdforders_logo')) { 
					$tbl .=  '<table cellpadding="1" cellspacing="1" border="0" ><tr style="text-align:left;"><td><img src="' . $data['logo'] . '" border="0" width="auto" /></td><td align="right">'.$data['text_invoice'].'#' . $order_id . '</td></tr></table>';
				} else {
					$tbl .=  '<table cellpadding="1" cellspacing="1" border="0" ><tr style="text-align:left;"><td>'.$data['text_invoice'].'#' . $order_id . '</td></tr></table>';
				}

				 $tbl .= '<table border="0" cellpadding="4">';

				$tbl .= '<tbody><tr><td><b>'.$order_info['store_name'].'</b><br/><b>Address: </b> '.nl2br($store_address).'<br><br><b>'.$data['text_telephone'].'</b> '.$store_telephone . '<br />';
				 if (!($this->config->get('pdforders_vattin'))) { 
					$tbl .= '<b>'.$data['text_vattin'].'</b>'.$this->config->get('pdforders_vattin').'<br /> ';
				}
				if ($store_fax) { 
					$tbl .= '<b>'.$data['text_fax'].'</b>'.$store_fax.'<br /> ';
				}

				$tbl .= '<b>' . $data['text_email'] . '</b>'.rtrim($store_email, '/').'<br/><b>'.$data['text_website'].'</b><a href=' . rtrim($order_info['store_url'], '/') . '>'.rtrim($order_info['store_url'], '/').'</a></td><td><b>'.$data['text_date_added'].'</b> '.date($this->language->get('date_format_short'), strtotime($order_info['date_added'])).'<br />';

				if ($invoice_no) {
					$tbl .= '<b>'.$data['text_invoice'].'</b> '.$invoice_no.'<br />';
				}

				$tbl .= '<b>' . $data['text_order_id'].'</b> '.$order_id.'<br /><b>'.$data['text_payment_method'].'</b> '.$order_info['payment_method'].'<br/>';

				if ($order_info['shipping_method']) { 
					$tbl .= '<b>'.$data['text_shipping_method'].'</b> '.$order_info['shipping_method'].'<br />';
				}

				$tbl .= '</td></tr></tbody></table>';

				$tbl .= '<table border="0.2" cellpadding="4"><thead><tr><td style="width: 50%;"><b>' . $data['text_to'] . '</b></td><td style="width: 50%;"><b>' . $data['text_ship_to'] . '</b></td></tr></thead><tbody><tr><td>' . $payment_address . '<br>'.$order_info['email'].'<br>'.$order_info['telephone'].'</td><td >' . $shipping_address . '</td></tr></tbody></table>';
				
				$tbl .= '<table border="0.2"  cellpadding="4" ><thead><tr>';
				if($this->config->get('pdforders_showimage')) {
					$tbl .= '<td style="width: 12%;" align="left"><b>' . $data['column_image'] . '</b></td>';
					$tbl .= '<td style="width: 38%;" align="left"><b>' . $data['column_product'] . '</b></td>';
				}else {
					$tbl .= '<td style="width: 50%;" align="left"><b>' . $data['column_product'] . '</b></td>';
				}
				 $tbl .= '<td style="width: 14%;" align="left"><b>' . $data['column_model'] . '</b></td><td style="width: 13%;" align="right"><b>' . $data['column_quantity'] . '</b></td><td style="width: 11%;" align="right"><b>' . $data['column_price'] . '</b></td><td style="width: 12%;" align="right"><b>' . $data['column_total'] . '</b></td></tr></thead>';
				$tbl .= '<tbody>';
				foreach ($product_data as $product) { 
					$tbl .= '<tr>';
					 if($this->config->get('pdforders_showimage')) {
						if($product['thumb']) {
							$tbl .='<td style="width: 12%;" align="left"><img src="'.$product['thumb'].'" alt="'.$product['name'].'" title="'.$product['name'].'" class="img-thumbnail" /></td>';
						 }
						 $tbl .= '<td style="width: 38%;" align="left">'.$product['name'];
						}else {
						$tbl .= '<td style="width: 50%;" align="left">'.$product['name'];
						}
					
						foreach ($product['option'] as $option) {
						$tbl .= '<br /><small> - ' . $option['name'] . ': ' . $option['value'] . '</small>';
						}
					$tbl .= ' </td><td style="width: 14%;" align="left">' . $product['model'] . '</td><td style="width: 13%;" align="right">' . $product['quantity'] . '</td><td style="width: 11%;" align="right">' . str_replace('₹','Rs.',$product['price']) . '</td><td style="width: 12%;" align="right">' . str_replace('₹','Rs.',$product['total']) . '</td></tr>';
				}
				if($this->config->get('pdforders_showimage')) {$colspan = 5;} else {$colspan = 4;}
				if($this->config->get('pdforders_addextrarows') && $addextrarows) { 
					for ($i=0; $i < $this->config->get('pdforders_numberextrarows'); $i++) { 
						$tbl .= '<tr><td colspan="'.$colspan.'"></td><td></td></tr>';
					}
				}

				foreach ($voucher_data as $voucher) { 
					$tbl .= '<tr><td>' . $voucher['description'] . '</td><td align="right">1</td><td align="right">' . $voucher['amount'] . '</td><td align="right">' . $voucher['amount'] . '</td></tr>';
				}
				 
				foreach ($total_data as $total) { 
					$tbl .= '<tr><td align="right" colspan="'.$colspan.'"><b>' . $total['title'] . '</b></td><td align="right">' . str_replace('₹','Rs.',$total['text']) . '</td></tr>';
				}
				$tbl .= '</tbody></table>';
				if ($order_info['comment']) { 
					$tbl .= '<table border="0" cellpadding="4"><thead><tr><td><b>' . $data['column_comment'] . '</b></td></tr></thead><tbody><tr><td>' .  nl2br($order_info['comment']) . '</td></tr></tbody></table>'; 
				}
		$message = $this->config->get('pdforders_textfooter');
				if (isset($message[$this->config->get('config_language_id')])) { 
				 $tbl .= '<br><br><br>';
					$tbl .= '<table border="0" cellpadding="4"><tbody><tr><td align="left">' . $message[$this->config->get('config_language_id')]['name'] . '</td></tr></tbody></table>'; 
				}
				$pdf->writeHTML($tbl, true, false, false, false, '');
			}

		$pdf->Output('Invoice-Order'.$order_id.'.pdf', 'I');

	}


	public function reorder() {
		$this->load->language ( 'account/order' );
		
		if (isset ( $this->request->get ['order_id'] )) {
			$order_id = $this->request->get ['order_id'];
		} else {
			$order_id = 0;
		}
		
		$this->load->model ( 'account/order' );
		
		$order_info = $this->model_account_order->getOrder ( $order_id );
		
		if ($order_info) {
			if (isset ( $this->request->get ['order_product_id'] )) {
				$order_product_id = $this->request->get ['order_product_id'];
			} else {
				$order_product_id = 0;
			}
			
			$order_product_info = $this->model_account_order->getOrderProduct ( $order_id, $order_product_id );
			
			if ($order_product_info) {
				$this->load->model ( 'catalog/product' );
				
				$product_info = $this->model_catalog_product->getProduct ( $order_product_info ['product_id'] );
				
				if ($product_info) {
					$option_data = array ();
					
					$order_options = $this->model_account_order->getOrderOptions ( $order_product_info ['order_id'], $order_product_id );
					
					foreach ( $order_options as $order_option ) {
						if ($order_option ['type'] == 'select' || $order_option ['type'] == 'radio' || $order_option ['type'] == 'image') {
							$option_data [$order_option ['product_option_id']] = $order_option ['product_option_value_id'];
						} elseif ($order_option ['type'] == 'checkbox') {
							$option_data [$order_option ['product_option_id']] [] = $order_option ['product_option_value_id'];
						} elseif ($order_option ['type'] == 'text' || $order_option ['type'] == 'textarea' || $order_option ['type'] == 'date' || $order_option ['type'] == 'datetime' || $order_option ['type'] == 'time') {
							$option_data [$order_option ['product_option_id']] = $order_option ['value'];
						} elseif ($order_option ['type'] == 'file') {
							$option_data [$order_option ['product_option_id']] = $this->encryption->encrypt ( $order_option ['value'] );
						}
					}
					
					$this->cart->add ( $order_product_info ['product_id'], $order_product_info ['quantity'], $option_data, $vendor_id );
					
					$this->session->data ['success'] = sprintf ( $this->language->get ( 'text_success' ), $this->url->link ( 'product/product', 'product_id=' . $product_info ['product_id'] ), $product_info ['name'], $this->url->link ( 'checkout/cart' ) );
					
					unset ( $this->session->data ['shipping_method'] );
					unset ( $this->session->data ['shipping_methods'] );
					unset ( $this->session->data ['payment_method'] );
					unset ( $this->session->data ['payment_methods'] );
				} else {
					$this->session->data ['error'] = sprintf ( $this->language->get ( 'error_reorder' ), $order_product_info ['name'] );
				}
			}
		}
		
		$this->response->redirect ( $this->url->link ( 'account/order/info', 'order_id=' . $order_id ) );
	}
}
