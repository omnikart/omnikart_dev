<?php
class ModelModuleEnquiry extends Model {
	public function addenquiry($data = array()) {
		$this->db->query ( "INSERT INTO " . DB_PREFIX . "enquiry SET customer_id='" . $this->customer->getId () . "', postcode='" . $data ['postcode'] . "', status='1', date_added=NOW()" );
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
		$data ['postcode'] = $query->row ['postcode'];
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
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "quote qt WHERE qt.enquiry_id='" . $enquiry_id . "' AND qt.supplier_id='" . $seller_id . "'" );
		$data = array ();
		if ($query->num_rows) {
			$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "quote_revision qt WHERE qt.quote_id='" . $query->row ['quote_id'] . "' ORDER BY qt.quote_revision_id DESC LIMIT 1" );
			$query = $this->db->query ( "SELECT *, (IFNULL((SELECT CONCAT (ct.firstname, ' ', ct.lastname) FROM " .DB_PREFIX. "customer ct WHERE ct.customer_id = qct.customer_id),(SELECT CONCAT (ct.firstname, ' ', ct.lastname) FROM " .DB_PREFIX. "customer ct WHERE ct.customer_id = '" . (int)$seller_id . "'))) AS authorname FROM " . DB_PREFIX . "quote_comment qct WHERE qct.quote_revision_id='" . $query->row ['quote_revision_id'] . "' " );
			foreach ( $query->rows as $key => $comment ) {
				$data ['comments'][]=$comment;
			}
		} else {
			$data = array ();
			$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "enquiry et WHERE et.enquiry_id='" . $enquiry_id . "'" );
			$this->db->query ( "INSERT INTO `" . DB_PREFIX . "quote` SET customer_id='" . ( int ) $query->row ['customer_id'] . "', enquiry_id='" . ( int ) $enquiry_id . "', supplier_id='" . ( int ) $seller_id . "', postcode='" . ( int ) $query->row ['postcode'] . "', date_added=NOW()" );
			$quote_id = $this->db->getLastId ();
			$this->db->query ( "INSERT INTO `" . DB_PREFIX . "quote_revision` SET quote_id='" . ( int ) $quote_id . "', status='" . ( int ) $query->row ['status'] . "', date_added=NOW()" );
		}
		return $data;
	}
	public function addEnquiryComments($enquiry_id, $seller_id, $comment) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "quote qt LEFT JOIN " . DB_PREFIX . "quote_revision qrt ON (qrt.quote_id = qt.quote_id)  WHERE qt.enquiry_id='" . $enquiry_id . "' AND qt.supplier_id='" . $seller_id . "' ORDER BY qrt.quote_revision_id DESC LIMIT 1" );
		if ($query->num_rows) {
			$query = $this->db->query ( "INSERT INTO " . DB_PREFIX . "quote_comment SET quote_revision_id='" . $query->row ['quote_revision_id'] . "', comment='" . $comment . "'" );
		}
	}
}