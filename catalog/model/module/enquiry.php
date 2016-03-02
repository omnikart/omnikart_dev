<?php
class ModelModuleEnquiry extends Model {
	public function addenquiry($data = array()) {
		$this->db->query ( "INSERT INTO " . DB_PREFIX . "enquiry SET customer_id='" . $this->customer->getId () . "', address_id='" . (int) $data ['address_id'] . "', status='1', date_added=NOW()" );
		$enquiry_id = $this->db->getLastId ();
		$this->db->query ( "INSERT INTO " . DB_PREFIX . "enquiry_term SET enquiry_id='" . $enquiry_id . "', term_type='payment', term_value='" . $data ['payment_terms'] . "'" );
		
		$content = array ();
		
		foreach ( $data ['enquiries'] as $enquiry ) {
			if ($enquiry ['product_id']) {
				$content ['products'] [$enquiry ['product_id']] = $enquiry ['product_id'];
			}
			if ($enquiry ['category_id']) {
				$content ['categories'] [$enquiry ['product_id']] = $enquiry ['product_id'];
			}
			$this->db->query ( "INSERT INTO " . DB_PREFIX . "enquiry_product SET enquiry_id='" . $enquiry_id . "', product_id='" . $enquiry ['product_id'] . "', category_id='" . $enquiry ['category_id'] . "', quantity='" . $enquiry ['quantity'] . "', unit_id='" . $enquiry ['unit_class'] . "'" );
			$enquiry_product_id = $this->db->getLastId ();
			$this->db->query ( "INSERT INTO " . DB_PREFIX . "enquiry_product_description SET enquiry_product_id='" . $enquiry_product_id . "', name='" . $enquiry ['name'] . "', description='" . $enquiry ['description'] . "', files='" . serialize ( $enquiry ['filenames'] ) . "'" );
		}
		if (! empty ( $content )) {
			$implode = array ();
			$suppliers = $this->getSupplierfromEnquiry ( $content );
			if (! empty ( $suppliers )) {
				foreach ( $suppliers as $supplier ) {
					$implode [] = '(' . $enquiry_id . ',' . $supplier . ')';
				}
				$this->db->query ( "INSERT INTO " . DB_PREFIX . "enquiry_to_supplier (enquiry_id,supplier_id) VALUES " . implode ( ',', $implode ) );
			}
		}
		
		/*
		 * $mail = new Mail();
		 * $mail->protocol = $this->config->get('config_mail_protocol');
		 * $mail->parameter = $this->config->get('config_mail_parameter');
		 * $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		 * $mail->smtp_username = $this->config->get('config_mail_smtp_username');
		 * $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		 * $mail->smtp_port = $this->config->get('config_mail_smtp_port');
		 * $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		 *
		 * $mail->setTo('sales@omnikart.com');
		 * $mail->setFrom($this->config->get('config_email'));
		 * $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		 * $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		 * $mail->setText($data['user_info']."\n".$data['query']);
		 * $mail->send();
		 */
	}
	private function getSupplierfromEnquiry($content) {
		if (! empty ( $content ['products'] ))
			$query = $this->db->query ( "SELECT DISTINCT c2p.customer_id FROM " . DB_PREFIX . "customerpartner_to_product c2p WHERE  c2p.product_id IN (" . implode ( ',', $content ['products'] ) . ") GROUP BY c2p.customer_id" );
		if (! empty ( $content ['categories'] ))
			$query2 = $this->db->query ( "SELECT DISTINCT c2p.customer_id FROM " . DB_PREFIX . "customerpartner_to_product c2p LEFT JOIN  FROM " . DB_PREFIX . "product_to_category p2c ON (p2c.product_id=c2p.product_id) WHERE p2c.category_id IN (" . implode ( ',', $content ['categories'] ) . ") GROUP BY c2p.customer_id" );
		
		$supplier = array ();
		
		if (isset ( $query ) && $query->num_rows > 0)
			foreach ( $query->rows as $supplier_id ) {
				$supplier [$supplier_id ['customer_id']] = $supplier_id ['customer_id'];
			}
		if (isset ( $query2 ) && $query2->num_rows > 0)
			foreach ( $query2->rows as $supplier_id ) {
				$supplier [$supplier_id ['customer_id']] = $supplier_id ['customer_id'];
			}
		return $supplier;
	}
	public function renderEnquiry($enquiry_id) {
		$data = array ();
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "enquiry e LEFT JOIN " . DB_PREFIX . "customer c ON (e.customer_id = c.customer_id) WHERE e.enquiry_id='" . $enquiry_id . "'" );
		$data ['customer_id'] = $query->row ['customer_id'];
		$data ['status'] = $query->row ['status'];
		$data ['date_added'] = $query->row ['date_added'];
		$data ['firstname'] = $query->row ['firstname'];
		$data ['lastname'] = $query->row ['lastname'];
		$data ['email'] = $query->row ['email'];
		$data ['telephone'] = $query->row ['telephone'];
		
		$data ['terms'] = array ();
		
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "enquiry_term et WHERE et.enquiry_id='" . $enquiry_id . "'" );
		
		foreach ( $query->rows as $term ) {
			if ($term ['term_type'] == 'payment') {
				$term_query = $query = $this->db->query ( "SELECT name FROM " . DB_PREFIX . "payment_term WHERE payment_term_id='" . ( int ) $term ['term_value'] . "'" );
				$term ['term_value'] = $term_query->row ['name'];
			}
			
			$data ['terms'] [] = array (
					'type' => $term ['term_type'],
					'value' => $term ['term_value'] 
			);
		}
		
		$data ['enquiries'] = array ();
		
		$query = $this->db->query ( "SELECT ep.*,epd.name AS name, epd.description AS description, epd.files AS files, ucd.title AS unit_title, uc.value AS unit_value FROM " . DB_PREFIX . "enquiry_product ep LEFT JOIN " . DB_PREFIX . "enquiry_product_description epd ON (epd.enquiry_product_id=ep.enquiry_product_id) LEFT JOIN " . DB_PREFIX . "unit_class uc ON (uc.unit_class_id = ep.unit_id) LEFT JOIN " . DB_PREFIX . "unit_class_description ucd ON (uc.unit_class_id = ucd.unit_class_id) WHERE ep.enquiry_id='" . $enquiry_id . "'" );
		if ($query->num_rows) {
			foreach ( $query->rows as $key => $enquiry ) {
				if ($enquiry ['product_id'])
					$data ['enquiries'] [$key] ['link'] = $this->url->link ( 'product/product', 'product_id=' . $enquiry ['product_id'], 'SSL' );
				if ($enquiry ['category_id'])
					$data ['enquiries'] [$key] ['link'] = $this->url->link ( 'product/category', 'category_id=' . $enquiry ['product_id'], 'SSL' );
				
				$data ['enquiries'] [$key] ['name'] = $enquiry ['name'];
				$data ['enquiries'] [$key] ['description'] = $enquiry ['description'];
				$data ['enquiries'] [$key] ['quantity'] = $enquiry ['quantity'];
				$data ['enquiries'] [$key] ['unit_title'] = $enquiry ['unit_title'];
				$data ['enquiries'] [$key] ['filenames'] = unserialize ( $enquiry ['files'] );
			}
		}
		$this->response->setOutput ( $this->load->view ( 'default/template/module/enquiry_supplier.tpl', $data ) );
	}
	public function getPaymentTerms($data = array()) {
		if (! $this->cache->get ( 'payment_terms' )) {
			$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "payment_term WHERE 1" );
			$this->cache->set ( 'payment_terms', $query->rows );
			return $query->rows;
		}
		return $this->cache->get ( 'payment_terms' );
	}
	public function getEnquiryComments($enquiry_id, $seller_id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "quote qt WHERE qt.enquiry_id='" . $enquiry_id . "' AND qt.supplier_id='" . $seller_id . "' ORDER BY qt.quote_id DESC LIMIT 1" );
		$data = array ();
		if ($query->num_rows) {
			$query = $this->db->query ( "SELECT *, (IFNULL((SELECT CONCAT (ct.firstname, ' ', ct.lastname) FROM " . DB_PREFIX . "customer ct WHERE ct.customer_id = qct.customer_id),(SELECT CONCAT (ct.firstname, ' ', ct.lastname) FROM " . DB_PREFIX . "customer ct WHERE ct.customer_id = '" . ( int ) $seller_id . "'))) AS authorname FROM " . DB_PREFIX . "quote_comment qct WHERE qct.quote_id='" . $query->row ['quote_id'] . "' " );
			foreach ( $query->rows as $key => $comment ) {
				$data ['comments'] [] = $comment;
			}
		} else {
			$this->getEnquiry($enquiry_id,$seller_id);
			return $this->getEnquiryComments($enquiry_id,$seller_id);
		}
		return $data;
	}
	public function addEnquiryComments($enquiry_id, $seller_id, $comment) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "quote qt WHERE qt.enquiry_id='" . $enquiry_id . "' AND qt.supplier_id='" . $seller_id . "' ORDER BY qt.quote_id DESC LIMIT 1" );
		$query = $this->db->query ( "INSERT INTO " . DB_PREFIX . "quote_comment SET quote_id='" . $query->row ['quote_id'] . "', comment='" . $comment . "'" );
	}
	public function getQuotationBySuppliers($enquiry_id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "enquiry_to_supplier e2s LEFT JOIN " . DB_PREFIX . "quote q ON (e2s.enquiry_id = q.enquiry_id) WHERE e2s.enquiry_id='" . $enquiry_id . "'" );
		$data['quotes'] = array ();
		foreach ( $query->rows as $enquirytoSupplier ) {
			$this->load->model ( 'account/customer' );
			$data ['quotes'] [$enquirytoSupplier ['supplier_id']] ['info'] = $this->model_account_customer->getCustomer ( $enquirytoSupplier ['supplier_id'] );
			$data ['quotes'] [$enquirytoSupplier ['supplier_id']] ['quote'] = $enquirytoSupplier;
			/* $data ['quotes'] [$enquirytoSupplier['supplier_id']] = $this->getEnquiryComments ( $enquiry_id, $enquirytoSupplier['supplier_id'] ); */
		}
		return $data;
	}
	public function getSentEnquiryComments($quote_id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "quote qt WHERE qt.quote_id='" . $quote_id . "'" );
		$data = array ();
		if ($query->num_rows) {
			$query = $this->db->query ( "SELECT *, (IFNULL((SELECT CONCAT (ct.firstname, ' ', ct.lastname) FROM " . DB_PREFIX . "customer ct WHERE ct.customer_id = qct.customer_id),(SELECT CONCAT (ct.firstname, ' ', ct.lastname) FROM " . DB_PREFIX . "customer ct WHERE ct.customer_id = '" . ( int ) $query->row['supplier_id'] . "'))) AS authorname FROM " . DB_PREFIX . "quote_comment qct WHERE qct.quote_id='" . $quote_id . "' " );
			foreach ( $query->rows as $key => $comment ) {
				$data ['comments'] [] = $comment;
			}
		}
		return $data;
	}
	public function addSentEnquiryComments($quote_id, $customer_id, $comment) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "quote qt WHERE qt.quote_id='" . $quote_id . "'" );
		$this->db->query ( "INSERT INTO " . DB_PREFIX . "quote_comment SET quote_id='" . $query->row ['quote_id'] . "', customer_id='" .$customer_id . "', comment='" . $comment . "'" );
	}
	
	public function getEnquiry($enquiry_id,$supplier_id,$quote_id=0,$quote_revision_id=0) {
		if (!$quote_id && !$quote_revision_id){
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "quote` WHERE enquiry_id='" . (int)$enquiry_id . "' AND supplier_id='" . (int)$supplier_id . "'");
			if ($query->num_rows) {
				return $this->getQuote($query->row['quote_id'],$supplier_id);
			}
		} elseif (!$quote_id && $quote_revision_id) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "quote_revision` qr LEFT JOIN `" . DB_PREFIX . "quote` q ON (qr.quote_id=q.quote_id) WHERE qr.quote_revision_id='" . (int)$quote_revision_id . "' AND q.supplier_id='" . (int)$supplier_id . "'");
			if ($query->num_rows && $quote_revision_id) {
				$query =$this->db->query("SELECT * FROM `" . DB_PREFIX . "quote_revision` WHERE quote_revision_id='" . (int)$quote_revision_id. "'");
				return $query->row;
			}
			return false;
		}
		$data = array();
		
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."enquiry e LEFT JOIN ".DB_PREFIX."customer c ON (e.customer_id = c.customer_id) WHERE e.enquiry_id='" . (int)$enquiry_id . "'");
		$data['customer_id'] = $query->row['customer_id'];
		$data['enquiry_id'] = $query->row['enquiry_id'];
		$data['address_id'] = $query->row['address_id'];
		$data['status'] = $query->row['status'];
		$data['date_added'] = $query->row['date_added'];
		$data['firstname'] = $query->row['firstname'];
		$data['lastname'] = $query->row['lastname'];
		$data['email'] = $query->row['email'];
		$data['telephone'] = $query->row['telephone'];
		

		$sql = "INSERT INTO `" . DB_PREFIX . "quote` SET";
		$sql .= " customer_id='" . (int)$data['customer_id'] . "',";
		$sql .= " enquiry_id='" . (int)$data['enquiry_id'] . "',";
		$sql .= " supplier_id='" . (int)$supplier_id . "',";
		$sql .= " address_id='" . (int)$data['address_id'] . "',";
		$sql .= " date_added=NOW()";
		
		$this->db->query($sql);
		$quote_id = $this->db->getLastId();

		$data['terms'] = array();
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."enquiry_term et WHERE et.enquiry_id='" . (int)$enquiry_id . "'");
		foreach ($query->rows as $key => $term) {
			$query = $this->db->query("INSERT INTO ".DB_PREFIX."quote_term SET quote_id='" . (int)$quote_id. "',term_type='" . $term['term_type'] . "',term_value='" . $term['term_value'] . "',sort_order='" . (int)$key . "'");
		}
	
		$data['enquiries'] = array();
		$query = $this->db->query("SELECT ep.*,epd.name AS name, epd.description AS description, epd.files AS files, ucd.title AS unit_title, uc.value AS unit_value FROM ".DB_PREFIX."enquiry_product ep LEFT JOIN ".DB_PREFIX."enquiry_product_description epd ON (epd.enquiry_product_id=ep.enquiry_product_id) LEFT JOIN " . DB_PREFIX . "unit_class uc ON (uc.unit_class_id = ep.unit_id) LEFT JOIN " . DB_PREFIX . "unit_class_description ucd ON (uc.unit_class_id = ucd.unit_class_id) WHERE ep.enquiry_id='" . $enquiry_id . "'");
		if ($query->num_rows) {
			foreach ($query->rows as $key=>$enquiry) {
				$tax_class_id = 0;
				if ($enquiry['product_id']){
					$product = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE product_id='" . (int)$enquiry['product_id'] . "'")->row;
					$tax_class_id = $product['tax_class_id'];
				}
				$sql = "INSERT INTO `" . DB_PREFIX . "quote_product` SET ";
				$sql .= " quote_id='" . (int)$quote_id . "',";
				$sql .= " product_id='" . (int)$enquiry['product_id'] . "',";
				$sql .= " category_id='" . (int)$enquiry['category_id'] . "',";
				$sql .= " name='" . $enquiry['name'] . "',";
				$sql .= " tax_class_id='" . $tax_class_id . "',";
				$sql .= " quantity='" . (int)$enquiry['quantity'] . "',";
				$sql .= " unit_id='" . (int)$enquiry['unit_id'] . "'";
				$this->db->query($sql);
			}
		}
	
		$this->db->query("INSERT INTO `" . DB_PREFIX . "quote_revision` SET quote_id='" . (int)$quote_id . "', date_added=NOW()");
		$quote_revision_id = $this->db->getLastId();
		
		return $this->getQuote($quote_id,$supplier_id);
	}

	public function getQuote($quote_id, $supplier_id, $quote_revision_id=0) {
		$data = array();
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."quote q LEFT JOIN ".DB_PREFIX."customer c ON (q.customer_id = c.customer_id) WHERE q.quote_id='" . (int)$quote_id . "' AND q.supplier_id='".(int)$supplier_id."'");
		
		$data['customer_id'] = $query->row['customer_id'];
		$data['address_id'] = $query->row['address_id'];
		$data['effective_date'] = $query->row['effective_date'];
		$data['expiration_date'] = $query->row['expiration_date'];
		$data['supplier_address_id'] = $query->row['supplier_address_id'];
		$data['date_added'] = $query->row['date_added'];
		$data['quote_id'] = $query->row['quote_id'];
		$data['supplier_id'] = $query->row['supplier_id'];
		$data['firstname'] = $query->row['firstname'];
		$data['lastname'] = $query->row['lastname'];
		$data['email'] = $query->row['email'];
		$data['telephone'] = $query->row['telephone'];
		$data['supplier_address_id'] =$query->row ['supplier_address_id'];
		$data['status'] = $query->row['status'];

		$query =$this->db->query("SELECT * FROM `" . DB_PREFIX . "quote_revision` WHERE quote_id='" . $quote_id . "' ORDER BY quote_revision_id DESC");
		$data['revisions'] = $query->rows;
		$data['terms'] = array();
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."quote_term qt WHERE qt.quote_id='" . (int)$quote_id . "'");
		foreach ($query->rows as $term) {
			if ($term['term_type']=='payment') {
				$query2 = $this->db->query("SELECT * FROM ".DB_PREFIX."payment_term WHERE payment_term_id='".$term['term_value']."'");
				$term['term_value'] = $query2->row['name']; 
				
				$data['terms'][$term['quote_term_id']] = array(
						'quote_term_id' => $term['quote_term_id'],
						'type' => 'Payment',
						'value' => $term['term_value']
				);
			} else {
				$data['terms'][$term['quote_term_id']] = array(
						'quote_term_id' => $term['quote_term_id'],
						'type' => $term['term_type'],
						'value' => $term['term_value']
				);
			}
		}
	
		$data['enquiries'] = array();
		$query = $this->db->query("SELECT qp.*,ucd.unit AS unit, ucd.title AS unit_title,tc.title AS tax_class,wcd.unit AS weight_class,lcd.unit AS length_class FROM `".DB_PREFIX."quote_product` qp LEFT JOIN " . DB_PREFIX . "unit_class uc ON (uc.unit_class_id = qp.unit_id) LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON (wcd.weight_class_id = qp.weight_class_id) LEFT JOIN " . DB_PREFIX . "length_class_description lcd ON (lcd.length_class_id = qp.length_class_id) LEFT JOIN " . DB_PREFIX . "unit_class_description ucd ON (uc.unit_class_id = ucd.unit_class_id) LEFT JOIN " . DB_PREFIX . "tax_class tc ON (tc.tax_class_id = qp.tax_class_id) WHERE qp.quote_id='" . $quote_id . "'");
		if ($query->num_rows) {
			foreach ($query->rows as $key=>$enquiry) {
				$data['enquiries'][$key] = $enquiry;
				if ($enquiry['product_id'])
					$data['enquiries'][$key]['link'] = $this->url->link('product/product','product_id='.(int)$enquiry['product_id'],'SSL');
				if ($enquiry['category_id'])
					$data['enquiries'][$key]['link'] = $this->url->link('product/category','category_id='.(int)$enquiry['product_id'],'SSL');
				$data['enquiries'][$key]['text_price'] = $this->currency->format($data['enquiries'][$key]['price']);
			}
		}
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "quote_total` WHERE quote_id='" . (int)$data['quote_id'] . "'");
		
		foreach ($query->rows as $total) {
			$data['totals'][] = array(
				'code' => $total['code'],
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value']),
				'value' => $total['value']
			);
		}
		
		return $data;
	}
	public function addQuoteRevision($data = array()){
		if (isset($data['quote_id'])){
			$this->db->query("INSERT INTO `" . DB_PREFIX . "quote_revision` VALUES ('','" . (int)$data['quote_id']. "','" . serialize($data). "',NOW())");
		}
	}

	public function getQuoteRevision($quote_revision_id = 0){
		if ($quote_revision_id){
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "quote_revision` WHERE quote_revision_id='" . (int)$quote_revision_id . "'");
			return $query->row['quote'];
		}
		return false;
	}
	
	public function getAddress($address_id) {
	$address_query = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "address WHERE address_id = '" . ( int ) $address_id . "'" );
	
	if ($address_query->num_rows) {
	$country_query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . ( int ) $address_query->row ['country_id'] . "'" );
	
	if ($country_query->num_rows) {
	$country = $country_query->row ['name'];
	$iso_code_2 = $country_query->row ['iso_code_2'];
					$iso_code_3 = $country_query->row ['iso_code_3'];
					$address_format = $country_query->row ['address_format'];
				} else {
	$country = '';
	$iso_code_2 = '';
	$iso_code_3 = '';
					$address_format = '';
	}
	
	$zone_query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . ( int ) $address_query->row ['zone_id'] . "'" );
	
	if ($zone_query->num_rows) {
	$zone = $zone_query->row ['name'];
	$zone_code = $zone_query->row ['code'];
	} else {
	$zone = '';
	$zone_code = '';
				}
	
	$address_data = array (
		'address_id' => $address_query->row ['address_id'],
	'firstname' => $address_query->row ['firstname'],
		'lastname' => $address_query->row ['lastname'],
		'company' => $address_query->row ['company'],
								'address_1' => $address_query->row ['address_1'],
	'address_2' => $address_query->row ['address_2'],
		'postcode' => $address_query->row ['postcode'],
								'city' => $address_query->row ['city'],
	'zone_id' => $address_query->row ['zone_id'],
	'zone' => $zone,
		'zone_code' => $zone_code,
	'country_id' => $address_query->row ['country_id'],
		'country' => $country,
	'iso_code_2' => $iso_code_2,
	'iso_code_3' => $iso_code_3,
		'address_format' => $address_format,
	'custom_field' => unserialize ( $address_query->row ['custom_field'] )
	);
	
	return $address_data;
	} else {
	return false;
	}
	}
	
	public function updateQuote($data  = array()){
	
		$this->db->query("UPDATE `" . DB_PREFIX . "quote` SET supplier_address_id='" . (int)$data['supplier_address_id'] . "', quotation_number='" . $this->db->escape($data['supplier_address_id']) . "', expiration_date='" . $this->db->escape($data['expiration_date']) . "', effective_date='" . $this->db->escape($data['effective_date']) . "' WHERE quote_id='" . (int)$data['quote_id'] . "'");
		
		foreach($data['product'] as $quote_product_id => $product) {
			$sql = "UPDATE `" . DB_PREFIX . "quote_product` SET ";
			$sql .= " price='" .(float)$product['unit_price']. "',";
			$sql .= " description='" .$this->db->escape($product['description']). "',";
			$sql .= " discount='" .(float)$product['discount']. "',";
			$sql .= " tax_class_id='" .(int)$product['tax_class_id'] . "',";
			$sql .= " quantity='" .$product['quantity'] . "',";
			$sql .= " total='" . (float)$product['total']. "'";
			$sql .= " WHERE  quote_product_id = '" . (int)$quote_product_id . "'";
			$this->db->query($sql);
		}
		$keys = array();
		if (isset($data['oldterm'])) {
			foreach($data['oldterm'] as $key => $dterm){
				if ($dterm['term_type'] && $dterm['term_value']) {
					$this->db->query ("UPDATE `" . DB_PREFIX . "quote_term` SET term_type='" . $dterm ['term_type'] . "', term_value='" . $dterm ['term_value'] . "' WHERE quote_term_id='" . (int)$key  . "'");
				} else {
					$keys[] = $key;
				}
			}
			$this->db->query ("DELETE FROM `" . DB_PREFIX . "quote_term` WHERE quote_term_id IN ('" . implode(',',$keys)  . "')");
		}
		if (isset($data['term'])) {
			foreach($data['term'] as $key => $dterm){
				$this->db->query ("INSERT INTO `" . DB_PREFIX . "quote_term` VALUES ('','" .$data['quote_id']. "','" . $dterm ['term_type'] . "','" . $dterm ['term_value'] . "','')" );
			}
		}
		if (isset($data['totals'])) {
			$this->db->query ("DELETE FROM `" . DB_PREFIX . "quote_total` WHERE quote_id='" . (int)$data['quote_id'] . "'");
			foreach ($data['totals'] as $total) {
				$this->db->query ("INSERT INTO `" . DB_PREFIX . "quote_total` VALUES ('','" . (int)$data['quote_id'] . "','" . $this->db->escape($total['code']) . "','" . $this->db->escape($total['title']) . "','" . (float)$total['value'] . "','" . (int)$total['sort_order'] . "')");
			}
		}
	}
		
	
	public function install() {
		/* enquiry tables */
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "enquiry` (
			`enquiry_id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
			`customer_id` int(11) NOT NULL,
	  		`postcode` varchar(11) NOT NULL,
			`status` tinyint(1) NOT NULL DEFAULT '1',
		  	`date_added` datetime NOT NULL,
		  	PRIMARY KEY (`enquiry_id`),
    		INDEX (`customer_id`,`postcode`)
		) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
	
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "enquiry_product` (
			  `enquiry_product_id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
			  `enquiry_id` int(11) NOT NULL,
			  `product_id` int(11) NOT NULL,
			  `category_id` int(11) NOT NULL,
			  `quantity` int(11) NOT NULL,
			  `unit_id` int(11) NOT NULL,
			  PRIMARY KEY (`enquiry_product_id`),
    		  INDEX (`enquiry_id`,`product_id`,`category_id`),
			  FOREIGN KEY (`enquiry_id`) REFERENCES `" . DB_PREFIX . "enquiry`(`enquiry_id`) ON UPDATE CASCADE ON DELETE RESTRICT
		) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
	
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "enquiry_product_description` (
			  `enquiry_product_id` int(11) NOT NULL,
			  `name` varchar(200) NOT NULL,
			  `description` text NOT NULL,
			  `files` text NOT NULL,
			  INDEX (`enquiry_product_id`,`name`),
			  FOREIGN KEY (`enquiry_product_id`) REFERENCES `" . DB_PREFIX . "enquiry_product`(`enquiry_product_id`) ON UPDATE CASCADE ON DELETE RESTRICT
		) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin" );
	
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "enquiry_term` (
			`enquiry_term_id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
			`enquiry_id` int(11) NOT NULL,
			`term_type`  varchar(64) NOT NULL,
	  		`term_value` varchar(64) NOT NULL,
		  	PRIMARY KEY (`enquiry_term_id`),
			FOREIGN KEY (`enquiry_id`) REFERENCES `" . DB_PREFIX . "enquiry`(`enquiry_id`) ON UPDATE CASCADE ON DELETE RESTRICT
		) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
	
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "payment_term` (
		  `payment_term_id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
		  `name` varchar(64) NOT NULL,
		  `sort_order` int(3) NOT NULL,
		  PRIMARY KEY (`payment_term_id`)
		) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
	
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "enquiry_to_supplier` (
		  `enquiry_id` int(11) NOT NULL,
		  `supplier_id` int(11) NOT NULL,
		  `status` TINYINT(1) NOT NULL,
		  PRIMARY KEY (`enquiry_id`,`supplier_id`)
		) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin" );
	
		/* quotation tables */
	
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "quote` (
			`quote_id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
			`customer_id` int(11) NOT NULL,
			`enquiry_id` int(11) NOT NULL,
	  	 	`postcode` varchar(11) NOT NULL,
		   	`date_added` datetime NOT NULL,
		   	PRIMARY KEY (`quote_id`),
    	 	INDEX (`customer_id`,`enquiry_id`,`postcode`)
		) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
	
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "quote_revision` (
			  `quote_revision_id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
			  `quote_id` int(11) NOT NULL,
			  `supplier_id` int(11) NOT NULL,
			  `status` tinyint(1) NOT NULL DEFAULT '1',
			  `date_added` datetime NOT NULL,
			   PRIMARY KEY (`quote_revision_id`),
    		  INDEX (`quote_id`,`supplier_id`),
			  FOREIGN KEY (`quote_id`) REFERENCES `" . DB_PREFIX . "quote`(`quote_id`) ON UPDATE CASCADE ON DELETE RESTRICT
		) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
	
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "quote_product` (
			  `quote_product_id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
			  `quote_revision_id` int(11) NOT NULL,
			  `name` varchar(200) NOT NULL,
			  `tax_class_id` int(11) NOT NULL,
			  `minimum` int(5) NOT NULL,
			  `unit_id` int(11) NOT NULL,
			  `weight` decimal(15,8) NOT NULL,
			  `weight_class_id` int(11) NOT NULL,
			  `length` decimal(15,8) NOT NULL,
			  `width` decimal(15,8) NOT NULL,
			  `height` decimal(15,8) NOT NULL,
			  `length_class_id` int(11) NOT NULL,
			  `total` decimal(15,4) NOT NULL,
			  PRIMARY KEY (`quote_product_id`),
			  FOREIGN KEY (`quote_revision_id`) REFERENCES `" . DB_PREFIX . "quote_revision`(`quote_revision_id`) ON UPDATE CASCADE ON DELETE RESTRICT
		) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
	
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "quote_term` (
		  `quote_term_id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
		  `quote_revision_id` int(11) NOT NULL,
		  `quote_id` int(11) NOT NULL,
		  `term_type` varchar(200) COLLATE utf8_bin NOT NULL,
		  `term_value` varchar(200) COLLATE utf8_bin NOT NULL,
		  `sort_order` int(3) NOT NULL,
		  PRIMARY KEY (`quote_term_id`)
		) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
	
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "quote_total` (
			`quote_total_id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
			`quote_revision_id` int(11) NOT NULL,
			`code` varchar(200) COLLATE utf8_bin NOT NULL,
			`title` varchar(200) COLLATE utf8_bin NOT NULL,
			`value`  int(11) NOT NULL,
		  	`sort_order` int(3) NOT NULL,
		  	PRIMARY KEY (`quote_total_id`),
			FOREIGN KEY (`quote_revision_id`) REFERENCES `" . DB_PREFIX . "quote_revision`(`quote_revision_id`) ON UPDATE CASCADE ON DELETE RESTRICT
		) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
	
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "quote_comment` (
		  `quote_comment_id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
		  `quote_revision_id` int(11) NOT NULL,
		  `customer_id` int(11) NOT NULL,
		  `supplier_id` int(11) NOT NULL,
		  `comment` text NOT NULL,
		  PRIMARY KEY (`quote_comment_id`),
          FOREIGN KEY (`quote_revision_id`) REFERENCES `" . DB_PREFIX . "quote_revision`(`quote_revision_id`) ON UPDATE CASCADE ON DELETE RESTRICT
		) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
	}

}