<?php
class ControllerAccountCustomerpartnerOrderinfo extends Controller {

	private $data = array();

	public function index() {

		if (!$this->customer->isLogged()) {
			if(isset($this->request->get['order_id'])){
				$this->session->data['redirect'] = $this->url->link('account/customerpartner/orderinfo&order_id='.$this->request->get['order_id'], '', 'SSL');
			}
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('account/customerpartner');

		//$customerRights = $this->model_account_customerpartner->getCustomerGroupRights($this->customer->getGroupId());
		$customerRights = $this->customer->getRights();
		if($customerRights && !array_key_exists('view-all-order', $customerRights['rights'])) {
			$this->response->redirect($this->url->link('account/account', '','SSL'));
		}

		$sellerId = $this->model_account_customerpartner->isSubUser($this->customer->getId());
		if(!$customerRights['isParent'] && !$sellerId) {
			$this->data['chkIsPartner'] = $this->model_account_customerpartner->chkIsPartner();
		} else if($sellerId) {
			$this->data['chkIsPartner'] = true;
		} else {
			$this->data['chkIsPartner'] = false;
		}
		$sellerId = $this->model_account_customerpartner->getuserseller();
		if(!$this->data['chkIsPartner'])
			$this->response->redirect($this->url->link('account/account'));

		$this->language->load('account/customerpartner/orderinfo');				
		
		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}	

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if($order_id){
				if(isset($this->request->post['tracking'])){
					$this->model_account_customerpartner->addOdrTracking($order_id,$this->request->post['tracking'],$sellerId);
					$this->session->data['success'] = $this->language->get('text_success');
				}
		 		$this->response->redirect($this->url->link('account/customerpartner/orderinfo&order_id='.$order_id, '', 'SSL'));	
			}			
		}

		if(isset($this->session->data['success'])){
			$this->data['success'] = $this->session->data['success'];
		}else{
			$this->data['success'] = '';
		}

		$this->data['order_id'] = $order_id;
							
		$this->load->model('account/order');
			
		$order_info = $this->model_account_customerpartner->getOrder($order_id,$sellerId);

		$this->document->setTitle($this->language->get('text_order'));
			
		$this->data['breadcrumbs'] = array();
	
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
			'separator' => false
		); 
	
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),        	
			'separator' => $this->language->get('text_separator')
		);
		
		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
					
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/customerpartner/orderlist', $url, 'SSL'),      	
			'separator' => $this->language->get('text_separator')
		);
		
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_order'),
			'href'      => $this->url->link('account/customerpartner/orderinfo', 'order_id=' . $order_id . $url, 'SSL'),
			'separator' => $this->language->get('text_separator')
		);		
				
  		$this->data['heading_title'] = $this->language->get('text_order');
  		$this->data['error_page_order'] = $this->language->get('error_page_order');
		$this->data['text_order_detail'] = $this->language->get('text_order_detail');
		$this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$this->data['text_order_id'] = $this->language->get('text_order_id');
		$this->data['text_date_added'] = $this->language->get('text_date_added');
  		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
  		$this->data['text_payment_method'] = $this->language->get('text_payment_method');
  		$this->data['text_payment_address'] = $this->language->get('text_payment_address');
  		$this->data['text_history'] = $this->language->get('text_history');
		$this->data['text_comment'] = $this->language->get('text_comment');
		$this->data['text_wait'] = $this->language->get('text_wait');
		$this->data['text_not_paid'] = $this->language->get('text_not_paid');
		$this->data['text_paid'] = $this->language->get('text_paid');

		$this->data['column_tracking_no'] = $this->language->get('column_tracking_no');
  		$this->data['column_name'] = $this->language->get('column_name');
  		$this->data['column_model'] = $this->language->get('column_model');
  		$this->data['column_quantity'] = $this->language->get('column_quantity');
  		$this->data['column_price'] = $this->language->get('column_price');
  		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
  		$this->data['column_status'] = $this->language->get('column_status');
  		$this->data['column_comment'] = $this->language->get('column_comment');
  		$this->data['column_transaction_status'] = $this->language->get('column_transaction_status');
		
		$this->data['button_invoice'] = $this->language->get('button_invoice');
  		$this->data['button_back'] = $this->language->get('button_back');
  		$this->data['button_add_history'] = $this->language->get('button_add_history');

  		$this->data['history_info'] = $this->language->get('history_info');

  		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_notify'] = $this->language->get('entry_notify');
		$this->data['entry_comment'] = $this->language->get('entry_comment');
		$this->data['entry_notifyadmin'] = $this->language->get('entry_notifyadmin');

		$this->data['errorPage'] = false;

		if ($order_info) {
			$this->data['customerpartner_order_id'] = $order_info['customerpartner_order_id'];
			$this->data['wksellerorderstatus'] = $this->config->get('marketplace_sellerorderstatus'); 

			if ($order_info['invoice_no']) {
				$this->data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$this->data['invoice_no'] = '';
			}
	
			$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
			
			if ($order_info['payment_address_format']) {
      			$format = $order_info['payment_address_format'];
    		} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
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
			
			$this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

      		$this->data['payment_method'] = $order_info['payment_method'];
			
			if ($order_info['shipping_address_format']) {
      			$format = $order_info['shipping_address_format'];
    		} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
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

			$this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$this->data['shipping_method'] = $order_info['shipping_method'];
			
			$this->data['products'] = array();
			
			$products = $this->model_account_customerpartner->getSellerOrderProducts($order_id,$sellerId);

   		foreach ($products as $product) {
				$option_data = array();
				
				$options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);

         		foreach ($options as $option) {
          			if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
					}
					
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);					
        		}

        		$product_tracking = $this->model_account_customerpartner->getOdrTracking($this->data['order_id'],$product['order_product_id'],$sellerId);

        		if($product['paid_status'] == 1) {
        			$paid_status = $this->language->get('text_paid');
        		} else {
        			$paid_status = $this->language->get('text_not_paid');
        		}

        		$this->data['products'][] = array(
          			'product_id'     => $product['product_id'],
          			'order_product_id'     => $product['order_product_id'],
          			'name'     => $product['name'],
          			'model'    => $product['model'],
          			'option'   => $option_data,
          			'tracking' => isset($product_tracking['tracking']) ? $product_tracking['tracking'] : '',
          			'quantity' => $product['quantity'],
          			'paid_status' => $paid_status,
          			'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
        		);
      		}

			// Voucher
			$this->data['vouchers'] = array();
			
			$vouchers = $this->model_account_order->getOrderVouchers($order_id);
			
			foreach ($vouchers as $voucher) {
				$this->data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}
			
      		$this->data['totals'] = array();

      		$totals = $this->model_account_customerpartner->getOrderTotals($order_id,$sellerId);

      		if($totals AND isset($totals[0]['total']))      			
				$this->data['totals'][]['total'] = $this->currency->format($totals[0]['total'], $order_info['currency_code'], 1);
			
			$this->data['comment'] = nl2br($order_info['comment']);
			
			$this->data['histories'] = array();

			$results = $this->model_account_customerpartner->getOrderHistories($order_id,$order_info['customerpartner_order_id']);
			$sort_order = array();
			
			foreach ($results as $key => $result) {
				$sort_order[$key] = strtotime($result['date_added']);
				
				$this->data['histories'][$key] = array(
						'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
						'status'     => $result['status'],
						'comment'    => nl2br($result['comment'])
				);
			}
			
			array_multisort($sort_order, SORT_ASC, $this->data['histories']);
      		//list of status

				$this->load->model('localisation/order_status');

				$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

				$this->data['order_status_id'] = $order_info['order_status_id'];

				}else{
					$this->data['errorPage'] = true;
				}
      	
  		$this->data['action'] = $this->url->link('account/customerpartner/orderinfo&order_id='.$order_id, '', 'SSL');      	
  		$this->data['continue'] = $this->url->link('account/customerpartner/orderlist', '', 'SSL');
  		$this->data['order_invoice'] = $this->url->link('account/customerpartner/soldinvoice&order_id='.$order_id, '', 'SSL');

  		/*
  		Access according to membership plan
  		 */
  		$this->data['isMember'] = true;
		if($this->config->get('wk_seller_group_status')) {
      		$this->data['wk_seller_group_status'] = true;
      		$this->load->model('account/customer_group');
			$isMember = $this->model_account_customer_group->getSellerMembershipGroup($this->customer->getId());
			if($isMember) {
				$allowedAccountMenu = $this->model_account_customer_group->getaccountMenu($isMember['gid']);
				if($allowedAccountMenu['value']) {
					$accountMenu = explode(',',$allowedAccountMenu['value']);
					if($accountMenu && !in_array('orderhistory:orderhistory', $accountMenu)) {
						$this->data['isMember'] = false;
					}
				}
			} else {
				$this->data['isMember'] = false;
			}
      	}
      	/*
      	end here
      	 */

  		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['column_right'] = $this->load->controller('common/column_right');
		$this->data['content_top'] = $this->load->controller('common/content_top');
		$this->data['content_bottom'] = $this->load->controller('common/content_bottom');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['header'] = $this->load->controller('common/header');
						
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/customerpartner/orderinfo.tpl')) {
			$this->response->setOutput($this->load->view( $this->config->get('config_template') . '/template/account/customerpartner/orderinfo.tpl' , $this->data));			
		} else {
			$this->response->setOutput($this->load->view('default/template/account/customerpartner/orderinfo.tpl' , $this->data));
		}		
    		
	}

   	public function pdf() {
		if (!$this->customer->isLogged()) {
			if(isset($this->request->get['order_id'])){
				$this->session->data['redirect'] = $this->url->link('account/customerpartner/orderinfo&order_id='.$this->request->get['order_id'], '', 'SSL');
			}
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('account/customerpartner');

		//$customerRights = $this->model_account_customerpartner->getCustomerGroupRights($this->customer->getGroupId());
		$customerRights = $this->customer->getRights();
		if($customerRights && !array_key_exists('view-all-order', $customerRights['rights'])) {
			$this->response->redirect($this->url->link('account/account', '','SSL'));
		}

		$sellerId = $this->model_account_customerpartner->isSubUser($this->customer->getId());
		if(!$customerRights['isParent'] && !$sellerId) {
			$this->data['chkIsPartner'] = $this->model_account_customerpartner->chkIsPartner();
		} else if($sellerId) {
			$this->data['chkIsPartner'] = true;
		} else {
			$this->data['chkIsPartner'] = false;
		}
			
		if(!$this->data['chkIsPartner'])
			$this->response->redirect($this->url->link('account/account'));
			
    $this->load->language('account/order');

    if (isset($this->request->get['order_id'])) {
      $order_id = $this->request->get['order_id'];
    } else {
      $order_id = 0;
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
    $sellerId = $this->model_account_customerpartner->getuserseller();
  	$order_info = $this->model_account_customerpartner->getOrder($order_id,$sellerId);
		$customer_info = $this->model_account_customerpartner->getProfile($sellerId);
		
    if ($order_info && $customer_info && $sellerId) {

			$this->load->model('account/customer');
  		$this->load->model('account/address');
  		$supplier_address = $this->model_account_address->getAddress($customer_info['address_id']);
  		
        //$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

          $store_address = $supplier_address['address_1'].($supplier_address['address_2']?" ".$supplier_address['address_2']:"")." ".$supplier_address['city']." ".$supplier_address['postcode']." ".$supplier_address['zone']." ".$supplier_address['country'];
          $store_email = $customer_info['email'];
          $store_telephone = $customer_info['telephone'];
          $store_fax = "";

        if ($order_info['invoice_no']) {
          $invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
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

        $products = $this->model_account_order->getorderProducts($order_id,$sellerId);

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

        $totals = $this->model_account_order->getOrderTotals($order_id,$sellerId);

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

        $tbl .= '<tbody><tr><td>Fullfilled by: Omnikart.com <br/>Supplied By: '.$customer_info['companyname'].'<br /><b>Address: </b> '.nl2br($store_address).'<br><b>'.$data['text_telephone'].'</b> '.$store_telephone . '<br />';
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
	public function history() {

    	$this->language->load('account/customerpartner/orderinfo');				
		$this->load->model('checkout/order');
		
		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST' AND isset($this->request->get['order_id'])) {		
			$this->model_checkout_order->addSupplierOrderHistory($this->request->get['order_id'], $this->request->post['order_status_id'],$this->request->post['comment'],$this->request->post['notify'],$this->request->post['notifyadmin']);
			$json['success'] = $this->language->get('text_success_history');			
		}
				
		$this->response->setOutput(json_encode($json));
  	}
}
?>
