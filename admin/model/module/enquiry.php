<?php
class ModelModuleEnquiry extends Model {
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
	
	public function getEnquiry($enquiry_id) {
		$data = array();
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."enquiry e LEFT JOIN ".DB_PREFIX."customer c ON (e.customer_id = c.customer_id) WHERE e.enquiry_id='" . (int)$enquiry_id . "'");
		$data['customer_id'] = $query->row['customer_id'];
		$data['postcode'] = $query->row['postcode'];
		$data['status'] = $query->row['status'];
		$data['date_added'] = $query->row['date_added'];
		$data['firstname'] = $query->row['firstname'];
		$data['lastname'] = $query->row['lastname'];
		$data['email'] = $query->row['email'];
		$data['telephone'] = $query->row['telephone'];
		
		$data['terms'] = array();
		
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."enquiry_term et WHERE et.enquiry_id='" . (int)$enquiry_id . "'");
		
		foreach ($query->rows as $term) {
			if ($term['term_type']=='payment') {
				$term_query = $query = $this->db->query("SELECT name FROM ".DB_PREFIX."payment_term WHERE payment_term_id='".(int)$term['term_value']."'");
				$term['term_value'] = $term_query->row['name'];
			}
				
			$data['terms'][] = array(
						'type' => $term['term_type'],
						'value' => $term['term_value']
			);
		}
		
		$data['enquiries'] = array();
		
		$query = $this->db->query("SELECT ep.*,epd.name AS name, epd.description AS description, epd.files AS files, ucd.title AS unit_title, uc.value AS unit_value FROM ".DB_PREFIX."enquiry_product ep LEFT JOIN ".DB_PREFIX."enquiry_product_description epd ON (epd.enquiry_product_id=ep.enquiry_product_id) LEFT JOIN " . DB_PREFIX . "unit_class uc ON (uc.unit_class_id = ep.unit_id) LEFT JOIN " . DB_PREFIX . "unit_class_description ucd ON (uc.unit_class_id = ucd.unit_class_id) WHERE ep.enquiry_id='" . $enquiry_id . "'");
		if ($query->num_rows) {
			foreach ($query->rows as $key=>$enquiry) {
				if ($enquiry['product_id'])
				$data['enquiries'][$key]['link'] = $this->url->link('product/product','product_id='.(int)$enquiry['product_id'],'SSL');
				if ($enquiry['category_id'])
				$data['enquiries'][$key]['link'] = $this->url->link('product/category','category_id='.(int)$enquiry['product_id'],'SSL');
		
				$data['enquiries'][$key]['name'] = $enquiry['name'];
				$data['enquiries'][$key]['description'] = $enquiry['description'];
				$data['enquiries'][$key]['quantity'] = $enquiry['quantity'];
				$data['enquiries'][$key]['unit_title'] = $enquiry['unit_title'];
				$data['enquiries'][$key]['filenames'] = unserialize($enquiry['files']);
			}
		}
		
		return $data;
	}
	
	public function delete($enquiry_id) {
		$query = $this->db->query ( "UPDATE `" . DB_PREFIX . "enquiry` SET status = '0' WHERE id IN (" . $implode . ")" );
	}
	public function updateEnquiry($data) {
		$query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "enquiry_to_user` WHERE id = '" . $data ['enquiry_id'] . "' AND customer_id = '" . $data ['customer_id'] . "' AND user_id = '" . $data ['user_id'] . "'" );
		if (! $query->num_rows) {
			$this->db->query ( "INSERT INTO `" . DB_PREFIX . "enquiry_to_user`(id,customer_id,user_id) VALUES (" . $data ['enquiry_id'] . "," . $data ['customer_id'] . "," . $data ['user_id'] . ")" );
		}
		
		if (! $data ['revision_id']) { // if opted for new revison
			$query = $this->db->query ( "SELECT sort_order FROM `" . DB_PREFIX . "enquiry_revisions` WHERE = '" . $data ['enquiry_id'] . "' ORDER BY sort_order DESC LIMIT 1" );
			if ($query->num_rows)
				$this->db->query ( "INSERT INTO `" . DB_PREFIX . "enquiry_revisions` VALUES ('','" . $data ['enquiry_id'] . "','" . $data ['comment'] . "','" . ( int ) ($query->row ['sort_order'] + 1) . "')" );
			else
				$this->db->query ( "INSERT INTO `" . DB_PREFIX . "enquiry_revisions` VALUES ('','" . $data ['enquiry_id'] . "','" . $data ['comment'] . "','1')" );
			
			$data ['revision_id'] = $this->db->getLastId ();
			
			foreach ( $data ['products'] as $product ) {
				$this->db->query ( "INSERT INTO `" . DB_PREFIX . "enquiry_products` VALUES ('','" . $data ['revision_id'] . "','" . $product ['name'] . "','" . ($product ['minimum'] ? $product ['minimum'] : 1) . "','1','" . ( float ) $product ['price'] . "','" . ( float ) $product ['total'] . "',NOW())" );
			}
		} else {
			if ($data ['revision_id']) {
				foreach ( $data ['products'] as $product ) {
					if ($product ['enquiry_product_id']) {
						$this->db->query ( "UPDATE `" . DB_PREFIX . "enquiry_products` SET name = '" . $product ['name'] . "', quantity = '" . ($product ['minimum'] ? $product ['minimum'] : 1) . "',unit = '1', price = '" . ( float ) $product ['price'] . "', total = '" . ( float ) $product ['total'] . "' WHERE enquiry_product_id = '" . ( int ) $product ['enquiry_product_id'] . "'" );
					}
				}
			}
		}
	}
	public function updateQuery($data = array()) {
		$selected = $data ['query'];
		foreach ( $selected as $key => $select ) {
			if (in_array ( $key, $data ['selected'] ))
				$query = $this->db->query ( "UPDATE `" . DB_PREFIX . "enquiry` SET status = '" . ( int ) $select ['status'] . "' WHERE id = '" . ( int ) $key . "'" );
		}
	}

	public function getEnquiries($data) {
		$enquiries = array();
		$sql = "SELECT e.enquiry_id FROM ".DB_PREFIX."enquiry e LEFT JOIN ".DB_PREFIX."customer c ON (e.customer_id = c.customer_id) WHERE e.status<>0";
		if (isset($data['filter_name']) && $data['filter_name']){
			$sql .= " AND  (";
			$implode = array();
			foreach (explode(' ',$data['filter_name']) as $name) {
				$implode[] = "(c.firstname LIKE '%".$name."%' OR c.lastname LIKE '%".$name."%' OR c.email LIKE '%".$name."%')";
			}
			$sql .= implode(' AND ',$implode).") ";
		}
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
	
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
	
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
	
	
		$query = $this->db->query($sql);
	
		foreach ($query->rows as $enquiry_id) {
			$enquiry = array();
			$query2 = $this->db->query("SELECT * FROM ".DB_PREFIX."enquiry e LEFT JOIN ".DB_PREFIX."customer c ON (e.customer_id = c.customer_id) WHERE e.enquiry_id='" . $enquiry_id['enquiry_id'] . "'");
			$enquiry['customer_id'] = $query2->row['customer_id'];
			$enquiry['postcode'] = $query2->row['postcode'];
			$enquiry['status'] = $query2->row['status'];
			$enquiry['date_added'] = $query2->row['date_added'];
			$enquiry['firstname'] = $query2->row['firstname'];
			$enquiry['lastname'] = $query2->row['lastname'];
			$enquiry['email'] = $query2->row['email'];
			$enquiry['telephone'] = $query2->row['telephone'];
			$enquiry['enquiry_id'] = $enquiry_id['enquiry_id'];
	
			$enquiry['terms'] = array();
				
			$query2 = $this->db->query("SELECT * FROM ".DB_PREFIX."enquiry_term et WHERE et.enquiry_id='" . $enquiry_id['enquiry_id'] . "'");
				
			foreach ($query2->rows as $term) {
				$enquiry['terms'][] = array(
						'type' => $term['term_type'],
						'value' => $term['term_value']
				);
			}
				
				
			$query2 = $this->db->query("SELECT ep.*,epd.name AS name, epd.files AS files, epd.description AS description, ucd.title AS unit_title, uc.value AS unit_value FROM ".DB_PREFIX."enquiry_product ep LEFT JOIN ".DB_PREFIX."enquiry_product_description epd ON (epd.enquiry_product_id=ep.enquiry_product_id) LEFT JOIN " . DB_PREFIX . "unit_class uc ON (uc.unit_class_id = ep.unit_id) LEFT JOIN " . DB_PREFIX . "unit_class_description ucd ON (uc.unit_class_id = ucd.unit_class_id) WHERE ep.enquiry_id='" . $enquiry_id['enquiry_id'] . "' LIMIT 0,5");
				
			if ($query2->num_rows) {
				foreach ($query2->rows as $key=>$enquiry_product) {
					if ($enquiry_product['product_id'])
						$enquiry['enquiries'][$key]['link'] = $this->url->link('product/product','product_id='.$enquiry_product['product_id'],'SSL');
						if ($enquiry_product['category_id'])
							$enquiry['enquiries'][$key]['link'] = $this->url->link('product/category','category_id='.$enquiry_product['product_id'],'SSL');
	
							$enquiry['enquiries'][$key]['name'] = $enquiry_product['name'];
							$enquiry['enquiries'][$key]['description'] = $enquiry_product['description'];
							$enquiry['enquiries'][$key]['quantity'] = $enquiry_product['quantity'];
							$enquiry['enquiries'][$key]['unit_title'] = $enquiry_product['unit_title'];
							$enquiry['enquiries'][$key]['filenames'] = unserialize($enquiry_product['files']);
				}
			}
				
			$enquiries[$enquiry_id['enquiry_id']] = $enquiry;
		}
	
		return $enquiries;
	}
	public function getTotalEnquiries($data) {
		$sql = "SELECT count(*) AS total FROM ".DB_PREFIX."enquiry e LEFT JOIN ".DB_PREFIX."customer c ON (e.customer_id = c.customer_id) WHERE 1";
		if (isset($data['filter_name']) && $data['filter_name']){
			$sql .= " AND  (";
			$implode = array();
			foreach (explode(' ',$data['filter_name']) as $name) {
				$implode[] = "(c.firstname LIKE '%".$name."%' OR c.lastname LIKE '%".$name."%' OR c.email LIKE '%".$name."%')";
			}
			$sql .= implode(' AND ',$implode).") ";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
	


}