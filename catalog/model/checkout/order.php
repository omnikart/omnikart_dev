<?php
class ModelCheckoutOrder extends Model {
	public function addOrder($data) {
		$this->event->trigger ( 'pre.order.add', $data );
		
		$this->db->query ( "INSERT INTO `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape ( $data ['invoice_prefix'] ) . "', store_id = '" . ( int ) $data ['store_id'] . "', store_name = '" . $this->db->escape ( $data ['store_name'] ) . "', store_url = '" . $this->db->escape ( $data ['store_url'] ) . "', customer_id = '" . ( int ) $data ['customer_id'] . "', customer_group_id = '" . ( int ) $data ['customer_group_id'] . "', firstname = '" . $this->db->escape ( $data ['firstname'] ) . "', lastname = '" . $this->db->escape ( $data ['lastname'] ) . "', email = '" . $this->db->escape ( $data ['email'] ) . "', telephone = '" . $this->db->escape ( $data ['telephone'] ) . "', fax = '" . $this->db->escape ( $data ['fax'] ) . "', custom_field = '" . $this->db->escape ( isset ( $data ['custom_field'] ) ? serialize ( $data ['custom_field'] ) : '' ) . "', payment_firstname = '" . $this->db->escape ( $data ['payment_firstname'] ) . "', payment_lastname = '" . $this->db->escape ( $data ['payment_lastname'] ) . "', payment_company = '" . $this->db->escape ( $data ['payment_company'] ) . "', payment_address_1 = '" . $this->db->escape ( $data ['payment_address_1'] ) . "', payment_address_2 = '" . $this->db->escape ( $data ['payment_address_2'] ) . "', payment_city = '" . $this->db->escape ( $data ['payment_city'] ) . "', payment_postcode = '" . $this->db->escape ( $data ['payment_postcode'] ) . "', payment_country = '" . $this->db->escape ( $data ['payment_country'] ) . "', payment_country_id = '" . ( int ) $data ['payment_country_id'] . "', payment_zone = '" . $this->db->escape ( $data ['payment_zone'] ) . "', payment_zone_id = '" . ( int ) $data ['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape ( $data ['payment_address_format'] ) . "', payment_custom_field = '" . $this->db->escape ( isset ( $data ['payment_custom_field'] ) ? serialize ( $data ['payment_custom_field'] ) : '' ) . "', payment_method = '" . $this->db->escape ( $data ['payment_method'] ) . "', payment_code = '" . $this->db->escape ( $data ['payment_code'] ) . "', shipping_firstname = '" . $this->db->escape ( $data ['shipping_firstname'] ) . "', shipping_lastname = '" . $this->db->escape ( $data ['shipping_lastname'] ) . "', shipping_company = '" . $this->db->escape ( $data ['shipping_company'] ) . "', shipping_address_1 = '" . $this->db->escape ( $data ['shipping_address_1'] ) . "', shipping_address_2 = '" . $this->db->escape ( $data ['shipping_address_2'] ) . "', shipping_city = '" . $this->db->escape ( $data ['shipping_city'] ) . "', shipping_postcode = '" . $this->db->escape ( $data ['shipping_postcode'] ) . "', shipping_country = '" . $this->db->escape ( $data ['shipping_country'] ) . "', shipping_country_id = '" . ( int ) $data ['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape ( $data ['shipping_zone'] ) . "', shipping_zone_id = '" . ( int ) $data ['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape ( $data ['shipping_address_format'] ) . "', shipping_custom_field = '" . $this->db->escape ( isset ( $data ['shipping_custom_field'] ) ? serialize ( $data ['shipping_custom_field'] ) : '' ) . "', shipping_method = '" . $this->db->escape ( $data ['shipping_method'] ) . "', shipping_code = '" . $this->db->escape ( $data ['shipping_code'] ) . "', comment = '" . $this->db->escape ( $data ['comment'] ) . "', total = '" . ( float ) $data ['total'] . "', affiliate_id = '" . ( int ) $data ['affiliate_id'] . "', commission = '" . ( float ) $data ['commission'] . "', marketing_id = '" . ( int ) $data ['marketing_id'] . "', tracking = '" . $this->db->escape ( $data ['tracking'] ) . "', language_id = '" . ( int ) $data ['language_id'] . "', currency_id = '" . ( int ) $data ['currency_id'] . "', currency_code = '" . $this->db->escape ( $data ['currency_code'] ) . "', currency_value = '" . ( float ) $data ['currency_value'] . "', ip = '" . $this->db->escape ( $data ['ip'] ) . "', forwarded_ip = '" . $this->db->escape ( $data ['forwarded_ip'] ) . "', user_agent = '" . $this->db->escape ( $data ['user_agent'] ) . "', accept_language = '" . $this->db->escape ( $data ['accept_language'] ) . "', date_added = NOW(), date_modified = NOW()" );
		
		$order_id = $this->db->getLastId ();
		
		// Products
		if (isset($data['products'])) {
			foreach ($data['products'] as $product) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', vendor_id = '" . (int)$product['vendor_id'] . "', id = '" . (int)$product['id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "'");

				$order_product_id = $this->db->getLastId();

				foreach ($product['option'] as $option) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
				}
			}
		}
		
		// Gift Voucher
		$this->load->model ( 'checkout/voucher' );
		
		// Vouchers
		if (isset ( $data ['vouchers'] )) {
			foreach ( $data ['vouchers'] as $voucher ) {
				$this->db->query ( "INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . ( int ) $order_id . "', description = '" . $this->db->escape ( $voucher ['description'] ) . "', code = '" . $this->db->escape ( $voucher ['code'] ) . "', from_name = '" . $this->db->escape ( $voucher ['from_name'] ) . "', from_email = '" . $this->db->escape ( $voucher ['from_email'] ) . "', to_name = '" . $this->db->escape ( $voucher ['to_name'] ) . "', to_email = '" . $this->db->escape ( $voucher ['to_email'] ) . "', voucher_theme_id = '" . ( int ) $voucher ['voucher_theme_id'] . "', message = '" . $this->db->escape ( $voucher ['message'] ) . "', amount = '" . ( float ) $voucher ['amount'] . "'" );
				
				$order_voucher_id = $this->db->getLastId ();
				
				$voucher_id = $this->model_checkout_voucher->addVoucher ( $order_id, $voucher );
				
				$this->db->query ( "UPDATE " . DB_PREFIX . "order_voucher SET voucher_id = '" . ( int ) $voucher_id . "' WHERE order_voucher_id = '" . ( int ) $order_voucher_id . "'" );
			}
		}
		
		// Totals
		if (isset ( $data ['totals'] )) {
			foreach ( $data ['totals'] as $total ) {
				$this->db->query ( "INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . ( int ) $order_id . "', code = '" . $this->db->escape ( $total ['code'] ) . "', title = '" . $this->db->escape ( $total ['title'] ) . "', `value` = '" . ( float ) $total ['value'] . "', sort_order = '" . ( int ) $total ['sort_order'] . "'" );
			}
		}
		// Vendor Totals
		if (isset($data['vendors'])) {
			foreach ($data['vendors'] as $vendor_id => $vendor) {
				foreach ($vendor['totals'] as $total) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "customerpartner_to_order_total SET order_id = '" . (int)$order_id . "',customer_id = '" . (int)$vendor_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
				}
			}
		}
		$this->event->trigger('post.order.add', $order_id);

		return $order_id;
	}
	public function editOrder($order_id, $data) {
		$this->event->trigger ( 'pre.order.edit', $data );
		
		// Void the order first
		$this->addOrderHistory ( $order_id, 0 );
		
		$this->db->query ( "UPDATE `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape ( $data ['invoice_prefix'] ) . "', store_id = '" . ( int ) $data ['store_id'] . "', store_name = '" . $this->db->escape ( $data ['store_name'] ) . "', store_url = '" . $this->db->escape ( $data ['store_url'] ) . "', customer_id = '" . ( int ) $data ['customer_id'] . "', customer_group_id = '" . ( int ) $data ['customer_group_id'] . "', firstname = '" . $this->db->escape ( $data ['firstname'] ) . "', lastname = '" . $this->db->escape ( $data ['lastname'] ) . "', email = '" . $this->db->escape ( $data ['email'] ) . "', telephone = '" . $this->db->escape ( $data ['telephone'] ) . "', fax = '" . $this->db->escape ( $data ['fax'] ) . "', custom_field = '" . $this->db->escape ( serialize ( $data ['custom_field'] ) ) . "', payment_firstname = '" . $this->db->escape ( $data ['payment_firstname'] ) . "', payment_lastname = '" . $this->db->escape ( $data ['payment_lastname'] ) . "', payment_company = '" . $this->db->escape ( $data ['payment_company'] ) . "', payment_address_1 = '" . $this->db->escape ( $data ['payment_address_1'] ) . "', payment_address_2 = '" . $this->db->escape ( $data ['payment_address_2'] ) . "', payment_city = '" . $this->db->escape ( $data ['payment_city'] ) . "', payment_postcode = '" . $this->db->escape ( $data ['payment_postcode'] ) . "', payment_country = '" . $this->db->escape ( $data ['payment_country'] ) . "', payment_country_id = '" . ( int ) $data ['payment_country_id'] . "', payment_zone = '" . $this->db->escape ( $data ['payment_zone'] ) . "', payment_zone_id = '" . ( int ) $data ['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape ( $data ['payment_address_format'] ) . "', payment_custom_field = '" . $this->db->escape ( serialize ( $data ['payment_custom_field'] ) ) . "', payment_method = '" . $this->db->escape ( $data ['payment_method'] ) . "', payment_code = '" . $this->db->escape ( $data ['payment_code'] ) . "', shipping_firstname = '" . $this->db->escape ( $data ['shipping_firstname'] ) . "', shipping_lastname = '" . $this->db->escape ( $data ['shipping_lastname'] ) . "', shipping_company = '" . $this->db->escape ( $data ['shipping_company'] ) . "', shipping_address_1 = '" . $this->db->escape ( $data ['shipping_address_1'] ) . "', shipping_address_2 = '" . $this->db->escape ( $data ['shipping_address_2'] ) . "', shipping_city = '" . $this->db->escape ( $data ['shipping_city'] ) . "', shipping_postcode = '" . $this->db->escape ( $data ['shipping_postcode'] ) . "', shipping_country = '" . $this->db->escape ( $data ['shipping_country'] ) . "', shipping_country_id = '" . ( int ) $data ['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape ( $data ['shipping_zone'] ) . "', shipping_zone_id = '" . ( int ) $data ['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape ( $data ['shipping_address_format'] ) . "', shipping_custom_field = '" . $this->db->escape ( serialize ( $data ['shipping_custom_field'] ) ) . "', shipping_method = '" . $this->db->escape ( $data ['shipping_method'] ) . "', shipping_code = '" . $this->db->escape ( $data ['shipping_code'] ) . "', comment = '" . $this->db->escape ( $data ['comment'] ) . "', total = '" . ( float ) $data ['total'] . "', affiliate_id = '" . ( int ) $data ['affiliate_id'] . "', commission = '" . ( float ) $data ['commission'] . "', date_modified = NOW() WHERE order_id = '" . ( int ) $order_id . "'" );
		
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . ( int ) $order_id . "'" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . ( int ) $order_id . "'" );
		
		// Products
		if (isset($data['products'])) {
			foreach ($data['products'] as $product) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', vendor_id = '" . (int)$product['vendor_id'] . "', id = '" . (int)$product['id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "'");

				$order_product_id = $this->db->getLastId();

				foreach ($product['option'] as $option) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
				}
			}
		}
		
		// Gift Voucher
		$this->load->model ( 'checkout/voucher' );
		
		$this->model_checkout_voucher->disableVoucher ( $order_id );
		
		// Vouchers
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . ( int ) $order_id . "'" );
		
		if (isset ( $data ['vouchers'] )) {
			foreach ( $data ['vouchers'] as $voucher ) {
				$this->db->query ( "INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . ( int ) $order_id . "', description = '" . $this->db->escape ( $voucher ['description'] ) . "', code = '" . $this->db->escape ( $voucher ['code'] ) . "', from_name = '" . $this->db->escape ( $voucher ['from_name'] ) . "', from_email = '" . $this->db->escape ( $voucher ['from_email'] ) . "', to_name = '" . $this->db->escape ( $voucher ['to_name'] ) . "', to_email = '" . $this->db->escape ( $voucher ['to_email'] ) . "', voucher_theme_id = '" . ( int ) $voucher ['voucher_theme_id'] . "', message = '" . $this->db->escape ( $voucher ['message'] ) . "', amount = '" . ( float ) $voucher ['amount'] . "'" );
				
				$order_voucher_id = $this->db->getLastId ();
				
				$voucher_id = $this->model_checkout_voucher->addVoucher ( $order_id, $voucher );
				
				$this->db->query ( "UPDATE " . DB_PREFIX . "order_voucher SET voucher_id = '" . ( int ) $voucher_id . "' WHERE order_voucher_id = '" . ( int ) $order_voucher_id . "'" );
			}
		}
		
		// Totals
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . ( int ) $order_id . "'" );
		
		if (isset ( $data ['totals'] )) {
			foreach ( $data ['totals'] as $total ) {
				$this->db->query ( "INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . ( int ) $order_id . "', code = '" . $this->db->escape ( $total ['code'] ) . "', title = '" . $this->db->escape ( $total ['title'] ) . "', `value` = '" . ( float ) $total ['value'] . "', sort_order = '" . ( int ) $total ['sort_order'] . "'" );
			}
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "customerpartner_to_order_total WHERE order_id = '" . (int)$order_id . "'");

		if (isset($data['vendors'])) {
			foreach ($data['vendors'] as $vendor_id => $vendor) {
				foreach ($vendor['totals'] as $total) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "customerpartner_to_order_total SET order_id = '" . (int)$order_id . "',customer_id = '" . (int)$vendor_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
				}
			}
		}
		$this->event->trigger('post.order.edit', $order_id);
	}

	public function createPdf($order_id) {
		$this->load->language('account/order');
		$data['title'] = $this->language->get('text_invoice');

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
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
		
		$order_info = $this->getOrder ( $order_id );
		
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
		
		$pdf->Output($filename, 'F');
  }
	
	public function deleteOrder($order_id) {
		$this->event->trigger ( 'pre.order.delete', $order_id );
		
		// Void the order first
		$this->addOrderHistory ( $order_id, 0 );
		
		$this->db->query ( "DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . ( int ) $order_id . "'" );
		$this->db->query ( "DELETE FROM `" . DB_PREFIX . "order_product` WHERE order_id = '" . ( int ) $order_id . "'" );
		$this->db->query ( "DELETE FROM `" . DB_PREFIX . "order_option` WHERE order_id = '" . ( int ) $order_id . "'" );
		$this->db->query ( "DELETE FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . ( int ) $order_id . "'" );
		$this->db->query ( "DELETE FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . ( int ) $order_id . "'" );
		$this->db->query ( "DELETE FROM `" . DB_PREFIX . "order_history` WHERE order_id = '" . ( int ) $order_id . "'" );
		$this->db->query ( "DELETE `or`, ort FROM `" . DB_PREFIX . "order_recurring` `or`, `" . DB_PREFIX . "order_recurring_transaction` `ort` WHERE order_id = '" . ( int ) $order_id . "' AND ort.order_recurring_id = `or`.order_recurring_id" );
		$this->db->query ( "DELETE FROM `" . DB_PREFIX . "affiliate_transaction` WHERE order_id = '" . ( int ) $order_id . "'" );
		
		// Gift Voucher
		$this->load->model ( 'checkout/voucher' );
		
		$this->model_checkout_voucher->disableVoucher ( $order_id );
		
		$this->event->trigger ( 'post.order.delete', $order_id );
	}
	public function getOrder($order_id) {
		$order_query = $this->db->query ( "SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . ( int ) $order_id . "'" );
		
		if ($order_query->num_rows) {
			$country_query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . ( int ) $order_query->row ['payment_country_id'] . "'" );
			
			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row ['iso_code_2'];
				$payment_iso_code_3 = $country_query->row ['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}
			
			$zone_query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . ( int ) $order_query->row ['payment_zone_id'] . "'" );
			
			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row ['code'];
			} else {
				$payment_zone_code = '';
			}
			
			$country_query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . ( int ) $order_query->row ['shipping_country_id'] . "'" );
			
			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row ['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row ['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}
			
			$zone_query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . ( int ) $order_query->row ['shipping_zone_id'] . "'" );
			
			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row ['code'];
			} else {
				$shipping_zone_code = '';
			}
			
			$this->load->model ( 'localisation/language' );
			
			$language_info = $this->model_localisation_language->getLanguage ( $order_query->row ['language_id'] );
			
			if ($language_info) {
				$language_code = $language_info ['code'];
				$language_directory = $language_info ['directory'];
			} else {
				$language_code = '';
				$language_directory = '';
			}
			
			return array (
					'order_id' => $order_query->row ['order_id'],
					'invoice_no' => $order_query->row ['invoice_no'],
					'invoice_prefix' => $order_query->row ['invoice_prefix'],
					'store_id' => $order_query->row ['store_id'],
					'store_name' => $order_query->row ['store_name'],
					'store_url' => $order_query->row ['store_url'],
					'customer_id' => $order_query->row ['customer_id'],
					'firstname' => $order_query->row ['firstname'],
					'lastname' => $order_query->row ['lastname'],
					'email' => $order_query->row ['email'],
					'telephone' => $order_query->row ['telephone'],
					'fax' => $order_query->row ['fax'],
					'custom_field' => unserialize ( $order_query->row ['custom_field'] ),
					'payment_firstname' => $order_query->row ['payment_firstname'],
					'payment_lastname' => $order_query->row ['payment_lastname'],
					'payment_company' => $order_query->row ['payment_company'],
					'payment_address_1' => $order_query->row ['payment_address_1'],
					'payment_address_2' => $order_query->row ['payment_address_2'],
					'payment_postcode' => $order_query->row ['payment_postcode'],
					'payment_city' => $order_query->row ['payment_city'],
					'payment_zone_id' => $order_query->row ['payment_zone_id'],
					'payment_zone' => $order_query->row ['payment_zone'],
					'payment_zone_code' => $payment_zone_code,
					'payment_country_id' => $order_query->row ['payment_country_id'],
					'payment_country' => $order_query->row ['payment_country'],
					'payment_iso_code_2' => $payment_iso_code_2,
					'payment_iso_code_3' => $payment_iso_code_3,
					'payment_address_format' => $order_query->row ['payment_address_format'],
					'payment_custom_field' => unserialize ( $order_query->row ['payment_custom_field'] ),
					'payment_method' => $order_query->row ['payment_method'],
					'payment_code' => $order_query->row ['payment_code'],
					'shipping_firstname' => $order_query->row ['shipping_firstname'],
					'shipping_lastname' => $order_query->row ['shipping_lastname'],
					'shipping_company' => $order_query->row ['shipping_company'],
					'shipping_address_1' => $order_query->row ['shipping_address_1'],
					'shipping_address_2' => $order_query->row ['shipping_address_2'],
					'shipping_postcode' => $order_query->row ['shipping_postcode'],
					'shipping_city' => $order_query->row ['shipping_city'],
					'shipping_zone_id' => $order_query->row ['shipping_zone_id'],
					'shipping_zone' => $order_query->row ['shipping_zone'],
					'shipping_zone_code' => $shipping_zone_code,
					'shipping_country_id' => $order_query->row ['shipping_country_id'],
					'shipping_country' => $order_query->row ['shipping_country'],
					'shipping_iso_code_2' => $shipping_iso_code_2,
					'shipping_iso_code_3' => $shipping_iso_code_3,
					'shipping_address_format' => $order_query->row ['shipping_address_format'],
					'shipping_custom_field' => unserialize ( $order_query->row ['shipping_custom_field'] ),
					'shipping_method' => $order_query->row ['shipping_method'],
					'shipping_code' => $order_query->row ['shipping_code'],
					'comment' => $order_query->row ['comment'],
					'total' => $order_query->row ['total'],
					'order_status_id' => $order_query->row ['order_status_id'],
					'order_status' => $order_query->row ['order_status'],
					'affiliate_id' => $order_query->row ['affiliate_id'],
					'commission' => $order_query->row ['commission'],
					'language_id' => $order_query->row ['language_id'],
					'language_code' => $language_code,
					'language_directory' => $language_directory,
					'currency_id' => $order_query->row ['currency_id'],
					'currency_code' => $order_query->row ['currency_code'],
					'currency_value' => $order_query->row ['currency_value'],
					'ip' => $order_query->row ['ip'],
					'forwarded_ip' => $order_query->row ['forwarded_ip'],
					'user_agent' => $order_query->row ['user_agent'],
					'accept_language' => $order_query->row ['accept_language'],
					'date_modified' => $order_query->row ['date_modified'],
					'date_added' => $order_query->row ['date_added'] 
			);
		} else {
			return false;
		}
	}
	public function addOrderHistory($order_id, $order_status_id, $comment = '', $notify = false) {
		$this->load->model('account/customerpartner');
		$this->event->trigger('pre.order.history.add', $order_id);
		$order_info = $this->getOrder($order_id);

		if ($order_info) {
			// Fraud Detection
			$this->load->model('account/customer');
			$customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
			if ($customer_info && $customer_info['safe']) $safe = true;
			else $safe = false;

			if (!$safe) {
				// Ban IP
				$status = false;
				if ($order_info['customer_id']) {
					$results = $this->model_account_customer->getIps($order_info['customer_id']);
					foreach ($results as $result) {
						if ($this->model_account_customer->isBanIp($result['ip'])) {
							$status = true;
							break;
						}
					}
				} else $status = $this->model_account_customer->isBanIp($order_info['ip']);
				
				if ($status) $order_status_id = $this->config->get('config_order_status_id');
			
				// Anti-Fraud
				$this->load->model('extension/extension');
				$extensions = $this->model_extension_extension->getExtensions('fraud');
				foreach ($extensions as $extension) {
					if ($this->config->get($extension['code'] . '_status')) {
						$this->load->model('fraud/' . $extension['code']);
						$fraud_status_id = $this->{'model_fraud_' . $extension['code']}->check($order_info);
						if ($fraud_status_id) {
							$order_status_id = $fraud_status_id;
						}
					}
				}
			}
			
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
				
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
						
			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

      if($this->config->get('pdforders_autogenerateinvoiceno')) {
				$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");

				if ($query->row['invoice_no']) {
					$invoice_no = $query->row['invoice_no'] + 1;
				} else {
					$invoice_no = 1;
				}

				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' WHERE order_id = '" . (int)$order_id . "'");
      }

			// If order status is 0 then becomes greater then 0 send main html email
			if (!$order_info['order_status_id'] && $order_status_id) {
				// Check for any downloadable products
				$download_status = false;

				foreach ($order_product_query->rows as $order_product) {
					// Check if there are any linked downloads
					$product_download_query = $this->db->query ( "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_download` WHERE product_id = '" . ( int ) $order_product ['product_id'] . "'" );
					
					if ($product_download_query->row ['total']) {
						$download_status = true;
					}
				}
				
				// Load the language for any mails that might be required to be sent out
				$language = new Language ( $order_info ['language_directory'] );
				$language->load ( $order_info ['language_directory'] );
				$language->load ( 'mail/order' );
				
				$order_status_query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . ( int ) $order_status_id . "' AND language_id = '" . ( int ) $order_info ['language_id'] . "'" );
				
				if ($order_status_query->num_rows) {
					$order_status = $order_status_query->row ['name'];
				} else {
					$order_status = '';
				}
				
				$subject = sprintf ( $language->get ( 'text_new_subject' ), html_entity_decode ( $order_info ['store_name'], ENT_QUOTES, 'UTF-8' ), $order_id );
				
				// HTML Mail
				$data = array();

				$data['title'] = sprintf($language->get('text_new_subject'), $order_info['store_name'], $order_id);

				$data['text_greeting'] = sprintf($language->get('text_new_greeting'), $order_info['store_name']);
				$data['text_link'] = $language->get('text_new_link');
				$data['text_download'] = $language->get('text_new_download');
				$data['text_order_detail'] = $language->get('text_new_order_detail');
				$data['text_instruction'] = $language->get('text_new_instruction');
				$data['text_order_id'] = $language->get('text_new_order_id');
				$data['text_date_added'] = $language->get('text_new_date_added');
				$data['text_payment_method'] = $language->get('text_new_payment_method');
				$data['text_shipping_method'] = $language->get('text_new_shipping_method');
				$data['text_email'] = $language->get('text_new_email');
				$data['text_telephone'] = $language->get('text_new_telephone');
				$data['text_ip'] = $language->get('text_new_ip');
				$data['text_order_status'] = $language->get('text_new_order_status');
				$data['text_payment_address'] = $language->get('text_new_payment_address');
				$data['text_shipping_address'] = $language->get('text_new_shipping_address');
				$data['text_product'] = $language->get('text_new_product');
				$data['text_model'] = $language->get('text_new_model');
				$data['text_quantity'] = $language->get('text_new_quantity');
				$data['text_price'] = $language->get('text_new_price');
				$data['text_total'] = $language->get('text_new_total');
				$data['text_footer'] = $language->get('text_new_footer');
				$data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
				$data['store_name'] = $order_info['store_name'];
				$data['store_url'] = $order_info['store_url'];
				$data['customer_id'] = $order_info['customer_id'];
				$data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id;

				if ($download_status) {
					$data ['download'] = $order_info ['store_url'] . 'index.php?route=account/download';
				} else {
					$data ['download'] = '';
				}
				
				$data ['order_id'] = $order_id;
				$data ['date_added'] = date ( $language->get ( 'date_format_short' ), strtotime ( $order_info ['date_added'] ) );
				$data ['payment_method'] = $order_info ['payment_method'];
				$data ['shipping_method'] = $order_info ['shipping_method'];
				$data ['email'] = $order_info ['email'];
				$data ['telephone'] = $order_info ['telephone'];
				$data ['ip'] = $order_info ['ip'];
				$data ['order_status'] = $order_status;
				
				if ($comment && $notify) {
					$data ['comment'] = nl2br ( $comment );
				} else {
					$data ['comment'] = '';
				}
				
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
				
				$this->load->model ( 'tool/upload' );
				
				// Products
				$data ['products'] = array ();
				
				foreach ( $order_product_query->rows as $product ) {
					$option_data = array ();
					
					$order_option_query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . ( int ) $order_id . "' AND order_product_id = '" . ( int ) $product ['order_product_id'] . "'" );
					
					foreach ( $order_option_query->rows as $option ) {
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
					
					$data ['products'] [] = array (
							'name' => $product ['name'],
							'model' => $product ['model'],
							'option' => $option_data,
							'quantity' => $product ['quantity'],
							'price' => $this->currency->format ( $product ['price'] + ($this->config->get ( 'config_tax' ) ? $product ['tax'] : 0), $order_info ['currency_code'], $order_info ['currency_value'] ),
							'total' => $this->currency->format ( $product ['total'] + ($this->config->get ( 'config_tax' ) ? ($product ['tax'] * $product ['quantity']) : 0), $order_info ['currency_code'], $order_info ['currency_value'] ) 
					);
				}
				
				// Vouchers
				$data ['vouchers'] = array ();
				
				$order_voucher_query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . ( int ) $order_id . "'" );
				
				foreach ( $order_voucher_query->rows as $voucher ) {
					$data ['vouchers'] [] = array (
							'description' => $voucher ['description'],
							'amount' => $this->currency->format ( $voucher ['amount'], $order_info ['currency_code'], $order_info ['currency_value'] ) 
					);
				}
				
				// Order Totals
				$order_total_query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . ( int ) $order_id . "' ORDER BY sort_order ASC" );
				
				foreach ( $order_total_query->rows as $total ) {
					$data ['totals'] [] = array (
							'title' => $total ['title'],
							'text' => $this->currency->format ( $total ['value'], $order_info ['currency_code'], $order_info ['currency_value'] ) 
					);
				}
				
				if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/mail/order.tpl' )) {
					$html = $this->load->view ( $this->config->get ( 'config_template' ) . '/template/mail/order.tpl', $data );
				} else {
					$html = $this->load->view ( 'default/template/mail/order.tpl', $data );
				}
				
				// Can not send confirmation emails for CBA orders as email is unknown
				$this->load->model ( 'payment/amazon_checkout' );
				
				if (! $this->model_payment_amazon_checkout->isAmazonOrder ( $order_info ['order_id'] )) {
					// Text Mail
					$text = sprintf ( $language->get ( 'text_new_greeting' ), html_entity_decode ( $order_info ['store_name'], ENT_QUOTES, 'UTF-8' ) ) . "\n\n";
					$text .= $language->get ( 'text_new_order_id' ) . ' ' . $order_id . "\n";
					$text .= $language->get ( 'text_new_date_added' ) . ' ' . date ( $language->get ( 'date_format_short' ), strtotime ( $order_info ['date_added'] ) ) . "\n";
					$text .= $language->get ( 'text_new_order_status' ) . ' ' . $order_status . "\n\n";
					
					if ($comment && $notify) {
						$text .= $language->get ( 'text_new_instruction' ) . "\n\n";
						$text .= $comment . "\n\n";
					}
					
					// Products
					$text .= $language->get ( 'text_new_products' ) . "\n";
					
					foreach ( $order_product_query->rows as $product ) {
						$text .= $product ['quantity'] . 'x ' . $product ['name'] . ' (' . $product ['model'] . ') ' . html_entity_decode ( $this->currency->format ( $product ['total'] + ($this->config->get ( 'config_tax' ) ? ($product ['tax'] * $product ['quantity']) : 0), $order_info ['currency_code'], $order_info ['currency_value'] ), ENT_NOQUOTES, 'UTF-8' ) . "\n";
						
						$order_option_query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . ( int ) $order_id . "' AND order_product_id = '" . $product ['order_product_id'] . "'" );
						
						foreach ( $order_option_query->rows as $option ) {
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
							
							$text .= chr ( 9 ) . '-' . $option ['name'] . ' ' . (utf8_strlen ( $value ) > 20 ? utf8_substr ( $value, 0, 20 ) . '..' : $value) . "\n";
						}
					}
					
					foreach ( $order_voucher_query->rows as $voucher ) {
						$text .= '1x ' . $voucher ['description'] . ' ' . $this->currency->format ( $voucher ['amount'], $order_info ['currency_code'], $order_info ['currency_value'] );
					}
					
					$text .= "\n";
					
					$text .= $language->get ( 'text_new_order_total' ) . "\n";
					
					foreach ( $order_total_query->rows as $total ) {
						$text .= $total ['title'] . ': ' . html_entity_decode ( $this->currency->format ( $total ['value'], $order_info ['currency_code'], $order_info ['currency_value'] ), ENT_NOQUOTES, 'UTF-8' ) . "\n";
					}
					
					$text .= "\n";
					
					if ($order_info ['customer_id']) {
						$text .= $language->get ( 'text_new_link' ) . "\n";
						$text .= $order_info ['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "\n\n";
					}
					
					if ($download_status) {
						$text .= $language->get ( 'text_new_download' ) . "\n";
						$text .= $order_info ['store_url'] . 'index.php?route=account/download' . "\n\n";
					}
					
					// Comment
					if ($order_info ['comment']) {
						$text .= $language->get ( 'text_new_comment' ) . "\n\n";
						$text .= $order_info ['comment'] . "\n\n";
					}
					
					$text .= $language->get ( 'text_new_footer' ) . "\n\n";
					
					$mail = new Mail ();
					$mail->protocol = $this->config->get ( 'config_mail_protocol' );
					$mail->parameter = $this->config->get ( 'config_mail_parameter' );
					$mail->smtp_hostname = $this->config->get ( 'config_mail_smtp_hostname' );
					$mail->smtp_username = $this->config->get ( 'config_mail_smtp_username' );
					$mail->smtp_password = html_entity_decode ( $this->config->get ( 'config_mail_smtp_password' ), ENT_QUOTES, 'UTF-8' );
					$mail->smtp_port = $this->config->get ( 'config_mail_smtp_port' );
					$mail->smtp_timeout = $this->config->get ( 'config_mail_smtp_timeout' );
					
					$mail->setTo ( $order_info ['email'] );
					$pdforders_order_status_customer = $this->config->get ( 'pdforders_order_status_customer' );
					if ($this->config->get ( 'pdforders_attachemail' ) && (in_array ( $order_status_id, $pdforders_order_status_customer ))) {
						$this->createPdf ( $order_id );
						$mail->addAttachment ( DIR_DOWNLOAD . "order" . $order_id . ".pdf" );
					}
					$mail->setFrom ( $this->config->get ( 'config_email' ) );
					$mail->setSender ( html_entity_decode ( $order_info ['store_name'], ENT_QUOTES, 'UTF-8' ) );
					$mail->setSubject ( html_entity_decode ( $subject, ENT_QUOTES, 'UTF-8' ) );
					$mail->setHtml ( $html );
					$mail->setText ( $text );
					$mail->send ();
				}
				
				//for Marketplace management
				$this->load->model('account/customerpartnerorder');
				$mailToSellers = array();
				$shipping = 0;
				$paid_shipping = false;
				$admin_shipping_method = false;
				$resultData = $this->model_account_customerpartnerorder->sellerAdminData($this->cart->getProducts());
				if($this->config->get('marketplace_allowed_shipping_method')) {
					if(in_array($order_info['shipping_code'],$this->config->get('marketplace_allowed_shipping_method'))) {
						$admin_shipping_method = true;
						$sellers_count = count($resultData);
						foreach ($resultData as $key => $value) {
							if($value['seller'] == 'admin') {
								$sellers_count = count($resultData) - 1;
							}
						}
						if($sellers_count == 1) {
							$shipping = $this->session->data['shipping_method']['cost'];
						}
					}
				}
				
				foreach ($resultData as $key => $seller_data) {
						if ($key!='admin'){
							$this->db->query("INSERT INTO ".DB_PREFIX."customerpartner_order set order_id='".(int)$order_id."', customer_id='".(int)$key."',order_status_id='".(int)$order_status_id."',date_added=NOW(),date_modified=NOW()");
							$customerpartner_order_id = $this->db->getLastId();
							$this->db->query("INSERT INTO " . DB_PREFIX . "customerpartner_order_history SET order_id = '" . (int)$order_id . "', customerpartner_order_id='" . (int)$customerpartner_order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
						}
				}
				
				foreach ($order_product_query->rows as $product) {
					$prsql = '';
					
					$mpSellers = $this->db->query("SELECT c.email,c.telephone,c.customer_id,p.product_id,p.subtract FROM ".DB_PREFIX."product p LEFT JOIN ".DB_PREFIX."customerpartner_to_product c2p ON (p.product_id = c2p.product_id) LEFT JOIN ".DB_PREFIX."customer c ON (c2p.customer_id = c.customer_id) WHERE p.product_id = '".$product['product_id']."' AND c2p.customer_id = '".$product['vendor_id']."' $prsql ORDER BY c2p.id ASC ")->row;
					
					if (!empty($mpSellers)) {
						$option_data = array();
						$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");
						foreach ($order_option_query->rows as $option) {
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
						$product_total = $this->currency->format(($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0)),  $order_info['currency_code'], $order_info['currency_value'] , false);
						$products = array(
							'product_id' => $product['product_id'],
							'name'     => $product['name'],
							'model'    => $product['model'],
							'option'   => $option_data,
							'quantity' => $product['quantity'],
							'product_total' => $product_total,	// without symbol				
							'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
							'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
						);

						$i = 0;
						//add commission entry
						$commission_array = $this->calculateCommission($products,$mpSellers['customer_id']);
						
						if($order_info['shipping_code'] != 'wk_custom_shipping.wk_custom_shipping' && $admin_shipping_method) {
							if(!$paid_shipping) {
								$commission_array['customer'] = $commission_array['customer'] + $shipping;
								$paid_shipping = true;
							}
						} else if($admin_shipping_method) {
							$sellerDetails = array();
							$sellerDetails[$mpSellers['customer_id']] = $resultData[$mpSellers['customer_id']];
							$shipping_quote = $this->model_shipping_wk_custom_shipping->getQuote($shipping_address, $sellerDetails);
						}
						
						if(isset($shipping_quote['quote']['wk_custom_shipping']['cost']) && $shipping_quote['quote']['wk_custom_shipping']['cost']) {
							$commission_array['customer'] = $commission_array['customer'] + $shipping_quote['quote']['wk_custom_shipping']['cost'];
						}

						$this->db->query("INSERT INTO ".DB_PREFIX."customerpartner_to_order SET `order_id` = '".(int)$order_id."',`customer_id` = '".(int)$mpSellers['customer_id']."',`product_id` = '".(int)$product['product_id']."',`order_product_id` = '".(int)$product['order_product_id']."',`price` = '".(float)$product_total."',`quantity` = '".(int)$product['quantity']."',`shipping` = '".$this->db->escape($order_info['shipping_method'])."',`payment` = '".$this->db->escape($order_info['payment_method'])."',`details` = '".$this->db->escape($commission_array['type'])."',`customer` = '".$commission_array['customer']."',`admin` = '".$commission_array['commission']."',`date_added` = NOW() ");

						//for adaptive paypal transaction
						if($order_info['payment_code'] == 'wk_adaptive_pay') {
							 $this->db->query("INSERT INTO " . DB_PREFIX . "customerpartner_to_transaction SET `customer_id` = '".(int)$mpSellers['customer_id']."',`amount` = '" . $commission_array['customer'] . "',`text` = '" . $this->currency->format($commission_array['customer']) . "',`details` = '".$this->db->escape($commission_array['type'])."',`date_added` = NOW()");
						}
						if (isset($mpSellers['email']) AND !empty($mpSellers['email'])) {
						
						$email64 = base64_encode($mpSellers['email']);
							if (isset($mailToSellers[$email64])){
								$mailToSellers[$email64]['products'][] = $products;
								$mailToSellers[$email64]['total'] = (float)$mailToSellers[$email64]['total'] + (float)$product_total;
							} else {
								$mailToSellers[$email64] = array('email' => $mpSellers['email'],
									'customer_id' => $mpSellers['customer_id'],
									'seller_email' => $mpSellers['email'],
									'seller_phone' => $mpSellers['telephone'],
									'products' => array(0 => $products),
									'total' => $product_total
								);
							}
						}
					}
				} 				
				
				if ($this->config->get('marketplace_mailtoseller')) {

					// Send out order confirmation mail
					$language = new Language($order_info['language_directory']);
					$language->load('default');
					$language->load('mail/order');

					$subject = sprintf($language->get('text_new_subject'), $order_info['store_name'], $order_id);

					// HTML Mail for seller
					$data = array();

					$data['title'] = sprintf($language->get('text_new_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);

					$data['text_greeting'] = '';
					$data['text_link'] = $language->get('text_new_link');
					$data['text_download'] = $language->get('text_new_download');
					$data['text_order_detail'] = $language->get('text_new_order_detail');
					$data['text_order_status'] = $language->get('text_new_order_detail');

					$data['text_instruction'] = $language->get('text_new_instruction');
					$data['text_order_id'] = $language->get('text_new_order_id');
					$data['text_date_added'] = $language->get('text_new_date_added');
					$data['text_payment_method'] = $language->get('text_new_payment_method'); 
					$data['text_shipping_method'] = $language->get('text_new_shipping_method');
					$data['text_email'] = $language->get('text_new_email');
					$data['text_telephone'] = $language->get('text_new_telephone');
					$data['text_ip'] = $language->get('text_new_ip');
					$data['text_payment_address'] = $language->get('text_new_payment_address');
					$data['text_shipping_address'] = $language->get('text_new_shipping_address');
					$data['text_product'] = $language->get('text_new_product');
					$data['text_model'] = $language->get('text_new_model');
					$data['text_quantity'] = $language->get('text_new_quantity');
					$data['text_price'] = $language->get('text_new_price');
					$data['text_total'] = $language->get('text_new_total');
					$data['text_footer'] = $language->get('text_new_footer');
					$data['text_powered'] = $language->get('text_new_powered');

					$data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');    
					$data['store_name'] = $order_info['store_name'];
					$data['store_url'] = $order_info['store_url'];
					$data['customer_id'] = $order_info['customer_id'];
					$data['link'] = $order_info['store_url'] . 'index.php?route=account/customerpartner/orderinfo&order_id=' . $order_id;

					$data['download'] = '';

					$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

					foreach ($order_product_query->rows as $order_product) {
						// Check if there are any linked downloads
						$product_download_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_download` WHERE product_id = '" . (int)$order_product['product_id'] . "'");

						if ($product_download_query->row['total']) {
							$data['download'] = $order_info['store_url'] . 'index.php?route=account/customerpartner/download';					
						}
					}

					$data['order_id'] = $order_id;
					$data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));      
					$data['payment_method'] = $order_info['payment_method'];
					$data['shipping_method'] = $order_info['shipping_method'];
					$data['email'] = $order_info['email'];
					$data['telephone'] = $order_info['telephone'];
					$data['ip'] = $order_info['ip'];
					$data['order_status'] = $order_status;

					if ($comment) {
						$data['comment'] = nl2br($comment);
					} else {
						$data['comment'] = '';
					}

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

					$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));           

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

					$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

					// Products
					$data['products'] = array();

					// Text Mail for seller
					$textBasic = $language->get('text_new_order_id') . ' ' . $order_id . "\n";
					$textBasic .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
					$textBasic .= $language->get('text_new_order_status') . ' ' . $order_status . "\n\n"; 

					$data['vouchers'] = array();          
					$this->load->model('payment/amazon_checkout');
					if (!$this->model_payment_amazon_checkout->isAmazonOrder($order_info['order_id'])) {

						$this->load->model('customerpartner/mail');
						$this->load->model('account/customerpartner');
						foreach ($mailToSellers as $value) {                        
							$data['totals'] = array();

							//for template for seller
							$data['products'] = $value['products'];
							$data['totals'][] = array('title' => 'Total' ,'text' => $this->currency->format($value['total'], $order_info['currency_code'], 1));

							if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/order.tpl')) {
								$html = $this->load->view($this->config->get('config_template') . '/template/mail/order.tpl', $data);
							} else {
								$html = $this->load->view('default/template/mail/order.tpl', $data);
							}

							//for text for seller
							$products = $value['products'];
							$text = $textBasic;
							$textsms = '';
							foreach ($products as $product) {
								$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($product['total']) . "\n";                
								$textsms .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ')'."\n";
								foreach ($product['option'] as $option) {
									$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($option['value'])) . "\n";
								}
							}       

							$text .= $language->get('text_new_order_total') . "\n";

							$text .= $this->currency->format($value['total'], $order_info['currency_code'], 1) . ': ' . html_entity_decode($this->currency->format($value['total'], $order_info['currency_code'], 1), ENT_NOQUOTES, 'UTF-8') . "\n";

							$text .= $language->get('text_new_footer') . "\n\n";

							$data = array(
								'seller_id' => $value['customer_id'],
								'text' => $text,
								'html' => $html,
										'customer_id' => $this->customer->getId(),
										'mail_id' => $this->config->get('marketplace_mail_order'),
										'mail_from' => $this->config->get('marketplace_adminmail'),
										'mail_to' => $value['seller_email'],
							);

							$commission = $this->model_account_customerpartner->getSellerCommission($value['customer_id']);

							$values = array(
										'order' => $data['html'],
								'commission' => $commission,
									);
									
							$this->model_customerpartner_mail->mail($data,$values);
							$this->model_customerpartner_mail->sms(array('seller_id' => $value['customer_id'],
								'text' => $textsms,'sms_to'=>$value['seller_phone']),$values);
						}
					}
				}
				
				/*$this->model_account_customerpartnerorder->customerpartner($order_info,$order_status, $comment);*/
				
				// Admin Alert Mail
				if ($this->config->get ( 'config_order_mail' )) {
					$subject = sprintf ( $language->get ( 'text_new_subject' ), html_entity_decode ( $this->config->get ( 'config_name' ), ENT_QUOTES, 'UTF-8' ), $order_id );
					
					// HTML Mail
					$data ['text_greeting'] = $language->get ( 'text_new_received' );
					
					if ($comment) {
						if ($order_info ['comment']) {
							$data ['comment'] = nl2br ( $comment ) . '<br/><br/>' . $order_info ['comment'];
						} else {
							$data ['comment'] = nl2br ( $comment );
						}
					} else {
						if ($order_info ['comment']) {
							$data ['comment'] = $order_info ['comment'];
						} else {
							$data ['comment'] = '';
						}
					}
					
					$data ['text_download'] = '';
					
					$data ['text_footer'] = '';
					
					$data ['text_link'] = '';
					$data ['link'] = '';
					$data ['download'] = '';
					
					if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/mail/order.tpl' )) {
						$html = $this->load->view ( $this->config->get ( 'config_template' ) . '/template/mail/order.tpl', $data );
					} else {
						$html = $this->load->view ( 'default/template/mail/order.tpl', $data );
					}
					
					// Text
					$text = $language->get ( 'text_new_received' ) . "\n\n";
					$text .= $language->get ( 'text_new_order_id' ) . ' ' . $order_id . "\n";
					$text .= $language->get ( 'text_new_date_added' ) . ' ' . date ( $language->get ( 'date_format_short' ), strtotime ( $order_info ['date_added'] ) ) . "\n";
					$text .= $language->get ( 'text_new_order_status' ) . ' ' . $order_status . "\n\n";
					$text .= $language->get ( 'text_new_products' ) . "\n";
					
					foreach ( $order_product_query->rows as $product ) {
						$text .= $product ['quantity'] . 'x ' . $product ['name'] . ' (' . $product ['model'] . ') ' . html_entity_decode ( $this->currency->format ( $product ['total'] + ($this->config->get ( 'config_tax' ) ? ($product ['tax'] * $product ['quantity']) : 0), $order_info ['currency_code'], $order_info ['currency_value'] ), ENT_NOQUOTES, 'UTF-8' ) . "\n";
						
						$order_option_query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . ( int ) $order_id . "' AND order_product_id = '" . $product ['order_product_id'] . "'" );
						
						foreach ( $order_option_query->rows as $option ) {
							if ($option ['type'] != 'file') {
								$value = $option ['value'];
							} else {
								$value = utf8_substr ( $option ['value'], 0, utf8_strrpos ( $option ['value'], '.' ) );
							}
							
							$text .= chr ( 9 ) . '-' . $option ['name'] . ' ' . (utf8_strlen ( $value ) > 20 ? utf8_substr ( $value, 0, 20 ) . '..' : $value) . "\n";
						}
					}
					
					foreach ( $order_voucher_query->rows as $voucher ) {
						$text .= '1x ' . $voucher ['description'] . ' ' . $this->currency->format ( $voucher ['amount'], $order_info ['currency_code'], $order_info ['currency_value'] );
					}
					
					$text .= "\n";
					
					$text .= $language->get ( 'text_new_order_total' ) . "\n";
					
					foreach ( $order_total_query->rows as $total ) {
						$text .= $total ['title'] . ': ' . html_entity_decode ( $this->currency->format ( $total ['value'], $order_info ['currency_code'], $order_info ['currency_value'] ), ENT_NOQUOTES, 'UTF-8' ) . "\n";
					}
					
					$text .= "\n";
					
					if ($order_info ['comment']) {
						$text .= $language->get ( 'text_new_comment' ) . "\n\n";
						$text .= $order_info ['comment'] . "\n\n";
					}
					
					$mail = new Mail ();
					$mail->protocol = $this->config->get ( 'config_mail_protocol' );
					$mail->parameter = $this->config->get ( 'config_mail_parameter' );
					$mail->smtp_hostname = $this->config->get ( 'config_mail_smtp_hostname' );
					$mail->smtp_username = $this->config->get ( 'config_mail_smtp_username' );
					$mail->smtp_password = html_entity_decode ( $this->config->get ( 'config_mail_smtp_password' ), ENT_QUOTES, 'UTF-8' );
					$mail->smtp_port = $this->config->get ( 'config_mail_smtp_port' );
					$mail->smtp_timeout = $this->config->get ( 'config_mail_smtp_timeout' );
					
					$mail->setTo ( $this->config->get ( 'config_email' ) );
					$mail->setFrom ( $this->config->get ( 'config_email' ) );
					$mail->setSender ( html_entity_decode ( $order_info ['store_name'], ENT_QUOTES, 'UTF-8' ) );
					$mail->setSubject ( html_entity_decode ( $subject, ENT_QUOTES, 'UTF-8' ) );
					$mail->setHtml ( $html );
					$mail->setText ( $text );
					$mail->send ();
					
					// Send to additional alert emails
					$emails = explode ( ',', $this->config->get ( 'config_mail_alert' ) );
					
					foreach ( $emails as $email ) {
						if ($email && preg_match ( '/^[^\@]+@.*.[a-z]{2,15}$/i', $email )) {
							$mail->setTo ( $email );
							$mail->send ();
						}
					}
				}
			}
			
			// If order status is not 0 then send update text email
			if ($order_info ['order_status_id'] && $order_status_id && $notify) {
				$language = new Language ( $order_info ['language_directory'] );
				$language->load ( $order_info ['language_directory'] );
				$language->load ( 'mail/order' );
				
				$subject = sprintf ( $language->get ( 'text_update_subject' ), html_entity_decode ( $order_info ['store_name'], ENT_QUOTES, 'UTF-8' ), $order_id );
				
				$message = $language->get ( 'text_update_order' ) . ' ' . $order_id . "\n";
				$message .= $language->get ( 'text_update_date_added' ) . ' ' . date ( $language->get ( 'date_format_short' ), strtotime ( $order_info ['date_added'] ) ) . "\n\n";
				
				$order_status_query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . ( int ) $order_status_id . "' AND language_id = '" . ( int ) $order_info ['language_id'] . "'" );
				
				if ($order_status_query->num_rows) {
					$message .= $language->get ( 'text_update_order_status' ) . "\n\n";
					$message .= $order_status_query->row ['name'] . "\n\n";
				}
				
				if ($order_info ['customer_id']) {
					$message .= $language->get ( 'text_update_link' ) . "\n";
					$message .= $order_info ['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "\n\n";
				}
				
				if ($comment) {
					$message .= $language->get ( 'text_update_comment' ) . "\n\n";
					$message .= strip_tags ( $comment ) . "\n\n";
				}
				
				$message .= $language->get ( 'text_update_footer' );
				
				$mail = new Mail ();
				$mail->protocol = $this->config->get ( 'config_mail_protocol' );
				$mail->parameter = $this->config->get ( 'config_mail_parameter' );
				$mail->smtp_hostname = $this->config->get ( 'config_mail_smtp_hostname' );
				$mail->smtp_username = $this->config->get ( 'config_mail_smtp_username' );
				$mail->smtp_password = html_entity_decode ( $this->config->get ( 'config_mail_smtp_password' ), ENT_QUOTES, 'UTF-8' );
				$mail->smtp_port = $this->config->get ( 'config_mail_smtp_port' );
				$mail->smtp_timeout = $this->config->get ( 'config_mail_smtp_timeout' );
				
				$mail->setTo ( $order_info ['email'] );
				$mail->setFrom ( $this->config->get ( 'config_email' ) );
				$mail->setSender ( html_entity_decode ( $order_info ['store_name'], ENT_QUOTES, 'UTF-8' ) );
				$mail->setSubject ( html_entity_decode ( $subject, ENT_QUOTES, 'UTF-8' ) );
				$mail->setText ( $message );
				$mail->send ();
			}
			
			if (is_file ( DIR_DOWNLOAD . "order" . $order_id . ".pdf" )) {
				unlink ( DIR_DOWNLOAD . "order" . $order_id . ".pdf" );
				$this->load->language ( 'api/order' );
			}
			
			// If order status in the complete range create any vouchers that where in the order need to be made available.
			if (in_array($order_info['order_status_id'], $this->config->get('config_complete_status'))) {
				// Send out any gift voucher mails
				$this->load->model ( 'checkout/voucher' );
				
				$this->model_checkout_voucher->confirm ( $order_id );
			}
		
					// If current order status is not processing or complete but new status is processing or complete then commence completing the order
			if (!in_array($order_info['order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) && in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				// Stock subtraction
				
				foreach ($order_product_query->rows as $order_product) {
					if (!empty($order_product['product_id'])) {
                    $this->load->model('tool/nitro');
                    $this->model_tool_nitro->clearProductCache((int)$order_product['product_id']);
          }
					$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");

					$subtract = true; /*$this->db->query("SELECT subtract FROM " . DB_PREFIX . "product WHERE product_id='" . $mpSellers['product_id'] . "'")->row['subtract'];*/
					if($subtract) {
						$this->db->query("UPDATE " . DB_PREFIX . "customerpartner_to_product SET quantity = quantity-'".(int)$order_product['quantity']."' WHERE product_id = '".(int)$order_product['product_id']."' AND customer_id = '".(int)$order_product['vendor_id']."'");
					}
					
					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product['order_product_id'] . "'");
					
					foreach ($order_option_query->rows as $option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
						$this->db->query("UPDATE " . DB_PREFIX . "customerpartner_to_product_option SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND id='" . (int)$order_product['id'] . "'");
					}
				}
				// Redeem coupon, vouchers and reward points
				$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");

				foreach ($order_total_query->rows as $order_total) {
					$this->load->model('total/' . $order_total['code']);

					if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
						$this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);
					}
				}

				// Add commission if sale is linked to affiliate referral.
				if ($order_info['affiliate_id'] && $this->config->get('config_affiliate_auto')) {
					$this->load->model('affiliate/affiliate');

					$this->model_affiliate_affiliate->addTransaction($order_info['affiliate_id'], $order_info['commission'], $order_id);
				}
			}

			// If old order status is the processing or complete status but new status is not then commence restock, and remove coupon, voucher and reward history
			if (in_array($order_info['order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) && !in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				// Restock
				foreach($order_product_query->rows as $product) {
					if (!empty($product['product_id'])) {
							$this->load->model('tool/nitro');
							$this->model_tool_nitro->clearProductCache((int)$product['product_id']);
					}					

					$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "' AND subtract = '1'");

					$subtract = true;/*$this->db->query("SELECT subtract FROM " . DB_PREFIX . "product WHERE product_id='" . $mpSellers['product_id'] . "'")->row['subtract'];*/

					if($subtract) {
						$this->db->query("UPDATE " . DB_PREFIX . "customerpartner_to_product SET quantity = quantity+'".(int)$order_product['quantity']."' WHERE product_id = '".(int)$order_product['product_id']."' AND customer_id = '".(int)$order_product['vendor_id']."'");
					}

					$option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

					foreach ($option_query->rows as $option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
						$this->db->query("UPDATE " . DB_PREFIX . "customerpartner_to_product_option SET quantity = (quantity + " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND id='" . (int)$order_product['id'] . "'");
					}
				}

				// Remove coupon, vouchers and reward points history
				$this->load->model('account/order');
				$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
				foreach ($order_total_query->rows as $order_total) {
					$this->load->model('total/' . $order_total['code']);
					if (method_exists($this->{'model_total_' . $order_total['code']}, 'unconfirm')) {
						$this->{'model_total_' . $order_total['code']}->unconfirm($order_id);
					}
				}
				// Remove commission if sale is linked to affiliate referral.
				if ($order_info['affiliate_id']) {
					$this->load->model('affiliate/affiliate');
					$this->model_affiliate_affiliate->deleteTransaction($order_id);
				}
			}
			$this->cache->delete('product');
		}
		
		$this->event->trigger ( 'post.order.history.add', $order_id );
	}
	
	public function addSupplierOrderHistory($order_id, $order_status_id, $comment = '', $notify = false, $notifyadmin = false) {
		$this->load->model('account/customerpartner');
		$seller_id = $this->model_account_customerpartner->getuserseller();
		$seller = $this->model_account_customerpartner->getProfile($seller_id);
		$this->event->trigger('pre.order.history.add', $order_id);
		$order_info = $this->getOrder($order_id);
		if ($order_info){
			$this->load->model('account/customer');
			$customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
			if ($customer_info && $customer_info['safe']) $safe = true;
			else $safe = false;
			
			$supplier_order = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customerpartner_order` WHERE order_id='" . (int)$order_id . "' AND customer_id='" . (int)$seller_id . "'");
			
			if ($supplier_order) {
				$this->db->query("UPDATE ".DB_PREFIX."customerpartner_order set order_status_id='".(int)$order_status_id."',date_modified=NOW() WHERE order_id = '" . (int)$order_id . "' AND customer_id='" . (int)$seller_id . "'");
				
				$this->db->query("INSERT INTO " . DB_PREFIX . "customerpartner_order_history SET order_id = '" . (int)$order_id . "', customerpartner_order_id='" . (int)$supplier_order->row['customerpartner_order_id'] . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
				
				if ($notify) {
					$language = new Language($order_info['language_directory']);
					$language->load($order_info['language_directory']);
					$language->load('mail/order');

					$subject = sprintf($language->get('text_update_subject'), html_entity_decode($seller['companyname'], ENT_QUOTES, 'UTF-8'), $order_id);

					$message  = $language->get('text_update_order') . ' ' . $order_id . "\n";
					$message .= $language->get('text_update_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n\n";

					$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");

					if ($order_status_query->num_rows) {
						$message .= $language->get('text_update_order_status') . "\n\n";
						$message .= $order_status_query->row['name'] . "\n\n";
					}

					if ($order_info['customer_id']) {
						$message .= $language->get('text_update_link') . "\n";
						$message .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "\n\n";
					}

					if ($comment) {
						$message .= $language->get('text_update_comment') . "\n\n";
						$message .= strip_tags($comment) . "\n\n";
					}

					$message .= $language->get('text_update_footer');

					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

					$mail->setTo($order_info['email']);
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
					$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
					$mail->setText($message);
					$mail->send();
				}
				if ($notifyadmin) {
					$language = new Language($order_info['language_directory']);
					$language->load($order_info['language_directory']);
					$language->load('mail/order');

					$subject = sprintf($language->get('text_update_subject'), html_entity_decode($seller['companyname'], ENT_QUOTES, 'UTF-8'), $order_id);

					$message  = $language->get('text_update_order') . ' ' . $order_id . "\n";
					$message .= $language->get('text_update_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n\n";

					$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");

					if ($order_status_query->num_rows) {
						$message .= $language->get('text_update_order_status') . "\n\n";
						$message .= $order_status_query->row['name'] . "\n\n";
					}

					if ($order_info['customer_id']) {
						$message .= $language->get('text_update_link') . "\n";
						$message .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "\n\n";
					}

					if ($comment) {
						$message .= $language->get('text_update_comment') . "\n\n";
						$message .= strip_tags($comment) . "\n\n";
					}

					$message .= $language->get('text_update_footer');

					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

					$mail->setTo($this->config->get('config_email'));
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
					$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
					$mail->setText($message);
					$mail->send();
				}
			}						
		}
	}
	
	public function calculateCommission($product,$customer_id) {
		
		if($product) {
			$categories_array = $this->db->query("SELECT p2c.category_id,c.parent_id,cpcc.percentage,cpcc.fixed,cpcc.id FROM ".DB_PREFIX."product_to_category p2c LEFT JOIN ".DB_PREFIX."category c ON (p2c.category_id = c.category_id) LEFT JOIN ".DB_PREFIX."customerpartner_commission_category cpcc ON (cpcc.category_id=p2c.category_id) WHERE p2c.product_id = '".(int)$product['product_id']."' ORDER BY p2c.product_id");

			if($this->config->get('marketplace_commissionworkedon'))
				$categories = $categories_array->rows;
			else
				$categories = array($categories_array->row);

			//get commission array for priority
			$commission = $this->config->get('marketplace_boxcommission');	
			$commission_amount = 0;
			$commission_type = '';

			if($commission)
				foreach($commission as $various) {
					switch ($various) {
						case 'category': //get all parent category according to product and process
							if($categories[0]){

								foreach($categories as $category) {
									if($category['parent_id']==0){
											$commission_amount += ( $categories['percentage'] ? ($categories['percentage']*$product['product_total']/100) : 0 ) + $category_commission['fixed'];
									}
								}
								$commission_type = 'Category Based';
								if($commission_amount)
									break;	
							}			

						case 'category_child': //get all child category according to product and process
							if($categories[0]){

								foreach($categories as $category){
									if($category['parent_id'] > 0){
										$commission_amount += ( $categories['percentage'] ? ($categories['percentage']*$product['product_total']/100) : 0 ) + $category_commission['fixed'];
									}
								}	

								$commission_type = 'Category Child Based';									
								if($commission_amount)
									break;	
							}
							
						default: //just get all amount and process on that (precentage based)
							$customer_commission = $this->getCustomerCommission($customer_id);
							if($customer_commission) {
								$commission_amount += $customer_commission['commission'] ? ($customer_commission['commission']*$product['product_total']/100) : 0;
							}

							$commission_type = 'Partner Fixed Based';									
							break;
					}
					if($commission_amount)
						break;
				}

		if($this->config->get('wk_seller_group_status')) {
      		$this->load->model('account/customer_group');
      		$isMember = $this->model_account_customer_group->getSellerMembershipGroup($customer_id);
			if($isMember) {
				$membershipCommission = $this->model_account_customer_group->getMembershipGroupCommission($isMember['gid']);
				if($membershipCommission) {
					$commission_amount += $membershipCommission['group_commission'] ? ($membershipCommission['group_commission']*$product['product_total']/100) : 0;
				}
				$commission_type = 'Membership Based';									
			}
		}

			$return_array = array(
				'commission' => $commission_amount,
				'customer' => $product['product_total']- $commission_amount,
				'type' => $commission_type,
			);
			return($return_array);	
		}	
	}

	public function sellerAdminData($cart, $zip = '',$payment = false ) {
		$seller = array();
		if($cart AND is_array($cart)) {
			foreach($cart as $product) {
				$entry = 0;
				if(!$product['weight_class_id'])	$product['weight_class_id'] = $this->config->get('config_weight_class_id');
				
				if($product['weight']) $weight = $this->weight->convert($product['weight'], $product['weight_class_id'],$this->config->get('config_weight_class_id'));
				else	$weight = 0;	
				
				$weight = ($weight < 0.1 ? 0.1 : $weight);
				
				$seller_zip = $this->db->query("SELECT a.postcode,a.customer_id,a.city,c.iso_code_2 as country,z.code as state,c2c.paypalid FROM ".DB_PREFIX."product p LEFT JOIN ".DB_PREFIX."customerpartner_to_product c2p ON (p.product_id = c2p.product_id) LEFT JOIN ".DB_PREFIX."address a ON(c2p.customer_id = a.customer_id) LEFT JOIN ".DB_PREFIX."zone z ON (a.zone_id = z.code) LEFT JOIN ".DB_PREFIX."country c ON (a.country_id = c.country_id) RIGHT JOIN " . DB_PREFIX . "customerpartner_to_customer c2c ON (c2c.customer_id = a.customer_id) WHERE p.product_id='".$product['product_id']."' AND c2p.customer_id='".$product['vendor_id']."'")->row;

				if($seller_zip){
					//partner will get product tax not admin or paste this line after - after me and comment line - comment me 
					$commission_array = $this->calculateCommission(array('product_id'=>$product['product_id'], 'product_total'=>$product['total']),$seller_zip['customer_id']);
					//add taxes to seller amount
					if($this->config->get('config_tax')){
						$commission_array['customer'] += $this->tax->getTax($product['total'], $product['tax_class_id']); //comment me
						$product['total'] = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
					} // after me
					
					if (isset($seller[$seller_zip['customer_id']])){
						$seller[$seller_zip['customer_id']]['weight'] = (float)$seller[$seller_zip['customer_id']]['weight']+(float)$weight;
						$seller[$seller_zip['customer_id']]['name'] = $seller[$seller_zip['customer_id']]['name'].', '.$product['name'];
						$seller[$seller_zip['customer_id']]['total'] = (float)$seller[$seller_zip['customer_id']]['total'] + (float)$product['total'];
						$seller[$seller_zip['customer_id']]['price'] = (float)$seller[$seller_zip['customer_id']]['price']+(float)$sellers['price'];
					} else {
						$zipCode = substr($seller_zip['postcode'], 0, 6);
						$seller[$seller_zip['customer_id']] = array(
							'seller' => $seller_zip['customer_id'],							
							'zip' => $zipCode,
							'weight' => $weight,
							'name' => $product['name'],
							'city' => $seller_zip['city'],
							'country' => $seller_zip['country'],
							'state' => $seller_zip['state'],										  
							'price' => $commission_array['customer'],
							'total' => $product['total'],									  	  
							'paypalid' => $seller_zip['paypalid'],
							'primary'=> false										  
						);
					}
					//admin -> if exists seller 			
					if($payment){
						if (isset($seller['admin'])){
							$seller['admin']['weight'] = (float)$seller['admin']['weight']+(float)$weight;
							$seller['admin']['name'] = $seller['admin']['name'].', '.$product['name'];
							$seller['admin']['total'] = (float)$seller['admin']['total'] + (float)$product['total'];
							$seller['admin']['price'] = (float)$seller['admin']['price']+(float)$sellers['price'];
						} else {
							$zipCode = substr($seller_zip['postcode'], 0, 6);
							$seller['admin'] = array(
								'seller' => $seller_zip['customer_id'],							
									'zip' => $zipCode,
								'weight' => $weight,
								'name' => $product['name'],
								'city' => $seller_zip['city'],
								'country' => $seller_zip['country'],
								'state' => $seller_zip['state'],										  
									'price' => $commission_array['customer'],
								'total' => $product['total'],									  	  
								'paypalid' => $seller_zip['paypalid'],
								'primary'=> false										  
							);
						}
					}
				}else{
					//add taxes to seller amount
					if($this->config->get('config_tax'))
						$product['total'] = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
					if (isset($seller['admin'])){
						$seller['admin']['weight'] = (float)$seller['admin']['weight']+(float)$weight;
						$seller['admin']['name'] = $seller['admin']['name'].', '.$product['name'];
						$seller['admin']['total'] = (float)$seller['admin']['total'] + (float)$product['total'];
						$seller['admin']['price'] = (float)$seller['admin']['price']+(float)$sellers['price'];
					} else {
						$zipCode = substr($seller_zip['postcode'], 0, 6);
						$seller['admin'] = array(
							'seller' => $seller_zip['customer_id'],							
								'zip' => $zipCode,
							'weight' => $weight,
							'name' => $product['name'],
							'city' => $seller_zip['city'],
							'country' => $seller_zip['country'],
							'state' => $seller_zip['state'],										  
								'price' => $commission_array['customer'],
							'total' => $product['total'],									  	  
							'paypalid' => $seller_zip['paypalid'],
							'primary'=> false										  
						);
					}
				}
			}
		}
		return $seller;
	}

	public function getCategoryCommission($category_id) {
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."customerpartner_commission_category WHERE category_id = '" . (int)$category_id . "'");
		return $query->row;
	}

	public function getCustomerCommission($customer_id) {
		$query = $this->db->query("SELECT commission FROM ".DB_PREFIX."customerpartner_to_customer WHERE customer_id = '" . (int)$customer_id . "'");
		return $query->row;
	}

	public function getShippingAddressId($order_id){
		$sql = "SELECT * FROM ".DB_PREFIX."order o LEFT JOIN ".DB_PREFIX."customer c ON o.customer_id=c.customer_id WHERE order_id = '".$order_id."'  ";

		$result = $this->db->query($sql)->row;
		return $result;
	}


	
}
