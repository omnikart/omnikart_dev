<?php
class ModelCustomerpartnerPartner extends Model {
	private $data = array ();
	public function removeCustomerpartnerTable() {
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_product`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_download`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_feedbacks`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_flatshipping`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_orders_pay`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_product_attributes`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_product_description`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_product_image`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_product_to_category`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_shipping`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_sold_tracking`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_to_customer`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customer_partner_rel`" );
		
		// old v2 tables
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_product_attribute`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_product_discount`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_product_filter`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_product_option`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_product_option_value`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_product_related`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_product_reward" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_product_special`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_product_to_download`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_commission_manual`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_to_product`" );
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "customerpartner_mail`" );
		
		$this->createCustomerpartnerTable ();
	}
	public function createCustomerpartnerTable() {
		
		// Table structure for table `customerpartner_commission_manual`
		// $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX ."customerpartner_commission_manual` (
		// `id` int(11) NOT NULL AUTO_INCREMENT,
		// `name` varchar(100) NOT NULL,
		// `fixed` float NOT NULL,
		// `percentage` float NOT NULL,
		// PRIMARY KEY (`id`)
		// ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1") ;
		
		// Table structure for table `customerpartner_commission_category`
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customerpartner_commission_category` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `category_id` int(100) NOT NULL,
		  `fixed` float NOT NULL,
		  `percentage` float NOT NULL,		  
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
		
		// Table structure for table `customerpartner_to_customer`
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customerpartner_to_customer` (
		  `customer_id` int(11) NOT NULL,
		  `is_partner` int(1) NOT NULL,
		  `screenname` varchar(255) NOT NULL,
		  `gender` varchar(255) NOT NULL,
		  `shortprofile` text NOT NULL,
		  `avatar` varchar(255) NOT NULL,
		  `twitterid` varchar(255) NOT NULL,
		  `paypalid` varchar(255) NOT NULL,
		  `country` varchar(255) NOT NULL,
		  `facebookid` varchar(255) NOT NULL,
		  `backgroundcolor` varchar(255) NOT NULL,
		  `companybanner` varchar(255) NOT NULL,
		  `companylogo` varchar(255) NOT NULL,
		  `companylocality` varchar(255) NOT NULL,
		  `companyname` varchar(255) NOT NULL,
		  `companydescription` text NOT NULL,
		  `countrylogo` varchar(1000) NOT NULL,
		  `otherpayment` text NOT NULL,
		  `commission` decimal(10,2) NOT NULL,
		  PRIMARY KEY (`customer_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
		
		// Table structure for table `customerpartner_to_commission`
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customerpartner_to_commission` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `customer_id` int(100) NOT NULL,
		  `commission_id` int(100) NOT NULL,		  
		  `fixed` float NOT NULL,
		  `percentage` float NOT NULL,		  
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
		
		// Table structure for table `customerpartner_customer_group`
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customerpartner_customer_group` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `rights` varchar(100) NOT NULL,
		  `isParent` int(11) NOT NULL,		  
		  `status` varchar(10) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
		
		// Table structure for table `customerpartner_customer_group_name`
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customerpartner_customer_group_name` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `customer_group_id` int(11) NOT NULL,
		  `language_id` int(11) NOT NULL,		  
		  `name` varchar(100) NOT NULL,
		  `description` varchar(500) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
		
		// Table structure for table `customerpartner_order_review`
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customerpartner_order_review` (
		  `order_review_id` int(11) NOT NULL AUTO_INCREMENT,
		  `customer_id` int(11) NOT NULL,
		  `admin_id` int(11) NOT NULL,		  
		  `customer_cart` text NOT NULL,
		  `approve_cart` text NOT NULL,
		  `disapprove_cart` text NOT NULL,
		  `status` text NOT NULL,
		  PRIMARY KEY (`order_review_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
		
		// Table structure for table `customerpartner_seller_customer_mapping`
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customerpartner_seller_customer_mapping` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `seller_id` int(11) NOT NULL,
		  `customer_id` int(11) NOT NULL,		  
		  `status` varchar(10) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
		
		// Table structure for table `customerpartner_to_product`
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customerpartner_to_product` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `customer_id` int(100) NOT NULL,
		  `product_id` int(100) NOT NULL,
		  `price` float NOT NULL,
		  `quantity` int(100) NOT NULL,	  
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
		
		// Table structure for table `customerpartner_feedbacks`
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customerpartner_to_feedback` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `customer_id` smallint(6) NOT NULL,
		  `seller_id` smallint(6) NOT NULL,
		  `feedprice` smallint(6) NOT NULL,
		  `feedvalue` smallint(6) NOT NULL,
		  `feedquality` smallint(6) NOT NULL,
		  `nickname` varchar(255) NOT NULL,
		  `summary` text NOT NULL,
		  `review` text NOT NULL,
		  `createdate` datetime NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
		
		// Table structure for table `customerpartner_orders`
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customerpartner_to_order` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `order_id` int(11) NOT NULL,
		  `customer_id` int(11) NOT NULL,		  
		  `product_id` int(11) NOT NULL,
		  `order_product_id` int(100) NOT NULL,
		  `price` float NOT NULL,
		  `quantity` float(11) NOT NULL,
		  `shipping` varchar(255) NOT NULL,
		  `shipping_rate` float NOT NULL, 
		  `payment` varchar(255) NOT NULL,
		  `payment_rate` float NOT NULL, 
		  `admin` float NOT NULL,  	
		  `customer` float NOT NULL,		  	  
		  `details` varchar(255) NOT NULL,
		  `paid_status` tinyint(1) NOT NULL,
		  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  PRIMARY KEY (`id`) 
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
		
		// Table structure for table `customerpartner_transaction`
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customerpartner_to_transaction` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `customer_id` int(11) NOT NULL,
		  `order_id` varchar(500) NOT NULL,
		  `order_product_id` varchar(500) NOT NULL,
		  `amount` float NOT NULL,
		  `text` varchar(255) NOT NULL,
		  `details` varchar(255) NOT NULL,
		  `date_added` date NOT NULL DEFAULT '0000-00-00',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
		
		// Table structure for table `customerpartner_payment`
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customerpartner_to_payment` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `customer_id` int(11) NOT NULL,		  
		  `amount` float NOT NULL,
		  `text` varchar(255) NOT NULL,
		  `details` varchar(255) NOT NULL,
		  `request_type` varchar(255) NOT NULL,
		  `paid` int(10) NOT NULL,
		  `balance_reduced` int(10) NOT NULL,
		  `payment` varchar(255) NOT NULL,
		  `date_added` date NOT NULL DEFAULT '0000-00-00',
		  `date_modified` date NOT NULL DEFAULT '0000-00-00',
		  `added_by` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
		
		// Table structure for table `customerpartner_download
		$this->db->query ( "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "customerpartner_download (
		  `download_id` int(11) NOT NULL AUTO_INCREMENT,
		  `seller_id` int(11) NOT NULL,
		  PRIMARY KEY (`download_id`)		  
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci" );
		
		// Table structure for table `customerpartner_flatshipping
		$this->db->query ( "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "customerpartner_flatshipping (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`partner_id` int(11) NOT NULL,
			`amount` float,
			`tax_class_id` float NOT NULL,
			PRIMARY KEY (`id`)
		)" );
		
		// Table structure for table `customerpartner_shipping
		$this->db->query ( "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "customerpartner_shipping (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `seller_id` int(10) NOT NULL ,
            `country_code` varchar(100) NOT NULL ,
            `zip_to` varchar(100) NOT NULL ,
            `zip_from` varchar(100) NOT NULL ,
            `price` float NOT NULL ,
            `weight_from` float NOT NULL ,
            `weight_to` float NOT NULL ,                                    
            PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8 ;" );
		
		// Table structure for table `customerpartner_sold_tracking
		$this->db->query ( "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "customerpartner_sold_tracking (
			`product_id` int(11) NOT NULL,
			`order_id` int(11) NOT NULL, 
			`customer_id` int(11) NOT NULL,
			`date_added` date NOT NULL DEFAULT '0000-00-00', 
			`tracking` varchar(100) NOT NULL)" );
		
		// Table structure for table `customerpartner_mail`
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customerpartner_mail` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(100) NOT NULL,
		  `subject` varchar(1000) NOT NULL,	  
		  `message` varchar(5000) NOT NULL,	  
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
	}
	public function deleteCustomer($customer_id) {
		$this->load->model ( 'catalog/product' );
		if ($this->config->get ( 'wk_mpaddproduct_status' )) {
			$this->load->model ( 'module/wk_mpaddproduct' );
			$otherSellerProducts = $this->db->query ( "SELECT product_id FROM " . DB_PREFIX . "price_comparison WHERE customer_id = '" . ( int ) $customer_id . "' " )->rows;
			if ($otherSellerProducts) { // if assign seller
				foreach ( $otherSellerProducts as $key => $otherproducts ) {
					$chk = $this->db->query ( "SELECT product_option_id FROM " . DB_PREFIX . "product_option WHERE product_id = '" . $otherproducts ['product_id'] . "' AND option_id = '" . $this->config->get ( 'wk_mpaddproduct_option_id' ) . "' " )->row; // chk select list on that product
					if ($chk) {
						$option_value = $this->db->query ( "SELECT option_value_id FROM " . DB_PREFIX . "option_mapping WHERE customer_id = '" . ( int ) $customer_id . "' " )->row; // get option value id regarding current customer
						$product_entry_chk = $this->db->query ( "SELECT product_option_value_id FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . ( int ) $chk ['product_option_id'] . "' AND product_id = '" . ( int ) $otherproducts ['product_id'] . "' AND option_id = '" . $this->config->get ( 'wk_mpaddproduct_option_id' ) . "' AND option_value_id = '" . ( int ) $option_value ['option_value_id'] . "' " )->row; // chk product option value id on that product
						if ($product_entry_chk) {
							$this->db->query ( "DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . ( int ) $otherproducts ['product_id'] . "' AND product_option_value_id = '" . ( int ) $product_entry_chk ['product_option_value_id'] . "' " ); // delete that product option
						}
					}
					$this->db->query ( "DELETE FROM " . DB_PREFIX . "price_comparison WHERE product_id = '" . ( int ) $otherproducts ['product_id'] . "' AND customer_id = '" . $customer_id . "' " ); // delete assign seller entry from price comparison
				}
				$this->model_module_wk_mpaddproduct->deleteSeller ( $customer_id ); // delete from mapping table
			} else {
				$baseSeller_products = $this->db->query ( "SELECT product_id FROM " . DB_PREFIX . "customerpartner_to_product WHERE customer_id = '" . ( int ) $customer_id . "'" )->rows;
				if ($baseSeller_products) { // if base seller
					foreach ( $baseSeller_products as $baseproducts ) {
						$chk = $this->db->query ( "SELECT product_option_id FROM " . DB_PREFIX . "product_option WHERE product_id = '" . $baseproducts ['product_id'] . "' AND option_id = '" . $this->config->get ( 'wk_mpaddproduct_option_id' ) . "' " )->row;
						if ($chk) {
							$option_value = $this->db->query ( "SELECT option_value_id FROM " . DB_PREFIX . "option_mapping WHERE customer_id = '" . ( int ) $customer_id . "' " )->row;
							$product_entry_chk = $this->db->query ( "SELECT product_option_value_id FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . ( int ) $chk ['product_option_id'] . "' AND product_id = '" . ( int ) $baseproducts ['product_id'] . "' AND option_id = '" . $this->config->get ( 'wk_mpaddproduct_option_id' ) . "' AND option_value_id = '" . ( int ) $option_value ['option_value_id'] . "' " )->row;
							if ($product_entry_chk) {
								$this->db->query ( "DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . ( int ) $baseproducts ['product_id'] . "' AND product_option_value_id = '" . ( int ) $product_entry_chk ['product_option_value_id'] . "' " ); // delete product option values
								$this->db->query ( "DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . ( int ) $baseproducts ['product_id'] . "' AND option_id = '" . ( int ) $this->config->get ( 'wk_mpaddproduct_option_id' ) . "' " ); // delete option of product
							}
						}
						$seller_id = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "price_comparison WHERE product_id = '" . ( int ) $baseproducts ['product_id'] . "'" )->rows;
						// send mail to assign seller after original seller deleted
						$this->load->model ( 'customerpartner/mail' );
						foreach ( $seller_id as $key => $seller ) {
							$this->model_customerpartner_mail->mail ( $seller, 'seller_assign_mail' );
						}
						$this->db->query ( "DELETE FROM " . DB_PREFIX . "price_comparison WHERE product_id = '" . ( int ) $baseproducts ['product_id'] . "'" ); // delete other seller entry from pc
						$this->model_catalog_product->deleteProduct ( $baseproducts ['product_id'] ); // product delete from store also
					}
				}
				$this->model_module_wk_mpaddproduct->deleteSeller ( $customer_id );
			}
		}
		$partner_products = $this->db->query ( "SELECT product_id FROM " . DB_PREFIX . "customerpartner_to_product WHERE customer_id = '" . ( int ) $customer_id . "'" )->rows;
		
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "customerpartner_download WHERE seller_id = '" . ( int ) $customer_id . "'" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "customerpartner_flatshipping WHERE partner_id = '" . ( int ) $customer_id . "'" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "customerpartner_shipping WHERE seller_id = '" . ( int ) $customer_id . "'" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "customerpartner_to_feedback WHERE seller_id = '" . ( int ) $customer_id . "'" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "customerpartner_to_customer WHERE customer_id = '" . ( int ) $customer_id . "'" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "customerpartner_sold_tracking WHERE customer_id = '" . ( int ) $customer_id . "'" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "customerpartner_to_order WHERE customer_id = '" . ( int ) $customer_id . "'" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "customerpartner_to_payment WHERE customer_id = '" . ( int ) $customer_id . "'" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "customerpartner_to_product WHERE customer_id = '" . ( int ) $customer_id . "'" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "customerpartner_to_transaction WHERE customer_id = '" . ( int ) $customer_id . "'" );
		
		if ($partner_products) {
			foreach ( $partner_products as $products ) {
				
				if ($this->config->get ( 'wk_mpaddproduct_status' )) {
					if ($otherSellerProducts) {
						$this->db->query ( "DELETE FROM " . DB_PREFIX . "customerpartner_to_product WHERE product_id = '" . ( int ) $products ['product_id'] . "' AND customer_id = '" . $customer_id . "' " );
					} else {
						$this->db->query ( "DELETE FROM " . DB_PREFIX . "customerpartner_to_product WHERE product_id = '" . ( int ) $products ['product_id'] . "'" );
					}
				} else {
					$this->db->query ( "DELETE FROM " . DB_PREFIX . "customerpartner_to_product WHERE product_id = '" . ( int ) $products ['product_id'] . "'" );
				}
			}
		}
	}
	public function getCustomer($customer_id) {
		$query = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . ( int ) $customer_id . "'" );
		
		return $query->row;
	}
	public function getAllCustomers() {
		$sql = $this->db->query ( "SELECT c.email FROM " . DB_PREFIX . "customerpartner_to_customer c2c LEFT JOIN " . DB_PREFIX . "customer c ON (c2c.customer_id = c.customer_id) WHERE c2c.is_partner = '1'" );
		
		return $sql->rows;
	}
	public function getCustomers($data = array()) {
		if (isset ( $data ['filter_all'] ) and $data ['filter_all'] == '1') {
			$add = '';
		} elseif (isset ( $data ['filter_all'] ) and $data ['filter_all'] == '2') {
			$add = ' c2c.is_partner = 0 AND';
		} else {
			$add = ' c2c.is_partner = 1 AND';
		}
		
		if (isset ( $data ['product_id'] ) && (! empty ( $data ['product_id'] ))) {
			$add2 = "c.customer_id NOT IN (SELECT c2p.customer_id FROM " . DB_PREFIX . "customerpartner_to_product c2p WHERE (c2p.customer_id = c.customer_id) AND c2p.product_id=" . ( int ) $data ['product_id'] . ") AND ";
		} else {
			$add2 = '';
		}
		
		$sql = "SELECT *,c.status, CONCAT(c.firstname, ' ', c.lastname) AS name,c.customer_id AS customer_id, c2c.is_partner,cgd.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) LEFT JOIN " . DB_PREFIX . "customerpartner_to_customer c2c ON (c2c.customer_id = c.customer_id) WHERE " . $add2 . $add . " cgd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "'";
		
		$implode = array ();
		
		if (! empty ( $data ['filter_name'] )) {
			$implode [] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '%" . $this->db->escape ( utf8_strtolower ( $data ['filter_name'] ) ) . "%'";
		}
		
		if (! empty ( $data ['filter_email'] )) {
			$implode [] = "LCASE(c.email) LIKE '" . $this->db->escape ( utf8_strtolower ( $data ['filter_email'] ) ) . "%'";
		}
		
		if (isset ( $data ['filter_newsletter'] ) && ! is_null ( $data ['filter_newsletter'] )) {
			$implode [] = "c.newsletter = '" . ( int ) $data ['filter_newsletter'] . "'";
		}
		
		if (isset ( $data ['filter_customer_group_id'] ) && ! empty ( $data ['filter_customer_group_id'] )) {
			$implode [] = "c.customer_group_id = '" . ( int ) $data ['filter_customer_group_id'] . "'";
		}
		
		if (isset ( $data ['filter_ip'] ) && ! empty ( $data ['filter_ip'] )) {
			$implode [] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape ( $data ['filter_ip'] ) . "')";
		}
		
		if (isset ( $data ['filter_status'] ) && ! is_null ( $data ['filter_status'] )) {
			$implode [] = "c.status = '" . ( int ) $data ['filter_status'] . "'";
		}
		
		if (isset ( $data ['filter_approved'] ) && ! is_null ( $data ['filter_approved'] )) {
			$implode [] = "c.approved = '" . ( int ) $data ['filter_approved'] . "'";
		}
		
		if (isset ( $data ['filter_date_added'] ) && ! empty ( $data ['filter_date_added'] )) {
			$implode [] = "DATE(c.date_added) = DATE('" . $this->db->escape ( $data ['filter_date_added'] ) . "')";
		}
		
		if ($implode) {
			$sql .= " AND " . implode ( " AND ", $implode );
		}
		
		$sort_data = array (
				'name',
				'c.email',
				'customer_group',
				'c.status',
				'c.approved',
				'c.ip',
				'c.date_added' 
		);
		
		if (isset ( $data ['sort'] ) && in_array ( $data ['sort'], $sort_data )) {
			$sql .= " ORDER BY " . $data ['sort'];
		} else {
			$sql .= " ORDER BY name";
		}
		
		if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 20;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
		}
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function getTotalCustomers($data = array()) {
		if (isset ( $data ['filter_all'] ) and $data ['filter_all'] == '1') {
			$add = '';
		} elseif (isset ( $data ['filter_all'] ) and $data ['filter_all'] == '2') {
			$add = ' c2c.is_partner = 0 AND';
		} else {
			$add = ' c2c.is_partner = 1 AND';
		}
		
		$sql = "SELECT *,c.status, CONCAT(c.firstname, ' ', c.lastname) AS name,c.customer_id AS customer_id, c2c.is_partner,cgd.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) LEFT JOIN " . DB_PREFIX . "customerpartner_to_customer c2c ON (c2c.customer_id = c.customer_id) WHERE " . $add . " cgd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "'";
		
		$implode = array ();
		
		$implode = array ();
		
		if (! empty ( $data ['filter_name'] )) {
			$implode [] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '%" . $this->db->escape ( utf8_strtolower ( $data ['filter_name'] ) ) . "%'";
		}
		
		if (! empty ( $data ['filter_email'] )) {
			$implode [] = "LCASE(c.email) LIKE '" . $this->db->escape ( utf8_strtolower ( $data ['filter_email'] ) ) . "%'";
		}
		
		if (isset ( $data ['filter_newsletter'] ) && ! is_null ( $data ['filter_newsletter'] )) {
			$implode [] = "c.newsletter = '" . ( int ) $data ['filter_newsletter'] . "'";
		}
		
		if (! empty ( $data ['filter_customer_group_id'] )) {
			$implode [] = "c.customer_group_id = '" . ( int ) $data ['filter_customer_group_id'] . "'";
		}
		
		if (! empty ( $data ['filter_ip'] )) {
			$implode [] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape ( $data ['filter_ip'] ) . "')";
		}
		
		if (isset ( $data ['filter_status'] ) && ! is_null ( $data ['filter_status'] )) {
			$implode [] = "c.status = '" . ( int ) $data ['filter_status'] . "'";
		}
		
		if (isset ( $data ['filter_approved'] ) && ! is_null ( $data ['filter_approved'] )) {
			$implode [] = "c.approved = '" . ( int ) $data ['filter_approved'] . "'";
		}
		
		if (! empty ( $data ['filter_date_added'] )) {
			$implode [] = "DATE(c.date_added) = DATE('" . $this->db->escape ( $data ['filter_date_added'] ) . "')";
		}
		
		if ($implode) {
			$sql .= " AND " . implode ( " AND ", $implode );
		}
		
		$sort_data = array (
				'name',
				'c.email',
				'customer_group',
				'c.status',
				'c.approved',
				'c.ip',
				'c.date_added' 
		);
		
		if (isset ( $data ['sort'] ) && in_array ( $data ['sort'], $sort_data )) {
			$sql .= " ORDER BY " . $data ['sort'];
		} else {
			$sql .= " ORDER BY name";
		}
		
		if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		$query = $this->db->query ( $sql );
		
		return count ( $query->rows );
	}
	public function approve($customer_id, $setstatus = 1) {
		if ($this->config->get ( 'wk_mpaddproduct_status' )) {
			$this->load->model ( 'module/wk_mpaddproduct' );
			if ($setstatus == 1) {
				$this->model_module_wk_mpaddproduct->insertSeller ( $customer_id );
			} else {
				$this->model_module_wk_mpaddproduct->deleteSeller ( $customer_id );
			}
		}
		
		$customer_info = $this->getCustomer ( $customer_id );
		
		if ($customer_info) {
			
			$commission = $this->config->get ( 'wkmpcommission' ) ? $this->config->get ( 'wkmpcommission' ) : 0;
			
			$seller_info = $this->getPartner ( $customer_id );
			
			if ($seller_info) {
				$this->db->query ( "UPDATE " . DB_PREFIX . "customerpartner_to_customer SET is_partner='" . ( int ) $setstatus . "' WHERE customer_id = '" . ( int ) $customer_id . "'" );
			} else {
				$this->db->query ( "INSERT INTO " . DB_PREFIX . "customerpartner_to_customer(`customer_id`, `is_partner`, `commission`) VALUES('$customer_id','" . ( int ) $setstatus . "','" . ( float ) $commission . "')" );
			}
			
			if (! $this->config->get ( 'marketplace_mail_partner_approve' ))
				return;
			
			$data = array_merge ( $customer_info, $seller_info );
			
			// send mail to Customer after request for Partnership
			$this->load->model ( 'customerpartner/mail' );
			$this->model_customerpartner_mail->mail ( $data, 'customer_applied_for_partnership_to_seller' );
		}
	}
	
	// for get commission
	public function getPartner($partner_id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "customerpartner_to_customer where customer_id='" . $partner_id . "'" );
		return ($query->row);
	}
	public function getPartnerCustomerInfo($partner_id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "customerpartner_to_customer c2c LEFT JOIN " . DB_PREFIX . "customer c ON (c2c.customer_id = c.customer_id) where c.customer_id='" . $partner_id . "'" );
		return ($query->row);
	}
	public function updatePartner($partner_id, $data) {
		if (isset ( $data ['customer'] ) and $data ['customer']) {
			
			$data = $data ['customer'];
			
			$sql = "UPDATE " . DB_PREFIX . "customerpartner_to_customer SET ";
			
			foreach ( $data as $key => $value ) {
				$sql .= $key . " = '" . $this->db->escape ( $value ) . "' ,";
			}
			
			$sql = substr ( $sql, 0, - 2 );
			$sql .= " WHERE customer_id = '" . ( int ) $partner_id . "'";
			
			$this->db->query ( $sql );
		}
	}
	
	// for update product seller
	public function updateProductSeller($partner_id, $product) {
		$product_ids = array ();
		
		if ($product and is_array ( $product )) {
			foreach ( $product as $individual_product ) {
				$product_ids [] = $individual_product ['selected'];
			}
		} else
			$product_ids [] = $product;
		
		foreach ( $product_ids as $product_id ) {
			
			$status = $this->chkProduct ( $product_id, $partner_id );
			
			if ($status == 1) {
				$this->db->query ( "INSERT INTO " . DB_PREFIX . "customerpartner_to_product SET product_id = '" . ( int ) $product_id . "', customer_id = '" . ( int ) $partner_id . "'" );
			} else {
				$this->db->query ( "UPDATE " . DB_PREFIX . "customerpartner_to_product SET customer_id = '" . ( int ) $partner_id . "' WHERE product_id = '" . ( int ) $product_id . "' ORDER BY id ASC LIMIT 1 " );
			}
		}
	}
	public function addproduct($partner_id, $data) {
		$product_ids = $data ['product_ids'];
		
		foreach ( $product_ids as $product_id ) {
			
			$status = $this->chkProduct ( $product_id, $partner_id );
			
			if ($status == 1) {
				$this->db->query ( "INSERT INTO " . DB_PREFIX . "customerpartner_to_product SET product_id = '" . ( int ) $product_id . "', customer_id = '" . ( int ) $partner_id . "'" );
			} elseif ($status > 1) {
				$this->db->query ( "UPDATE " . DB_PREFIX . "customerpartner_to_product SET customer_id = '" . ( int ) $partner_id . "' WHERE product_id = '" . ( int ) $product_id . "' ORDER BY id ASC LIMIT 1 " );
			}
		}
	}
	public function chkProduct($pid, $paid = '') {
		$sql = $this->db->query ( "SELECT quantity FROM " . DB_PREFIX . "product WHERE product_id='" . ( int ) $pid . "'" );
		
		if (isset ( $sql->row ['quantity'] )) {
			$sql = $this->db->query ( "SELECT GROUP_CONCAT( DISTINCT CAST(customer_id AS CHAR(11)) SEPARATOR \",\" ) AS customer_id FROM " . DB_PREFIX . "customerpartner_to_product WHERE product_id='" . ( int ) $pid . "'" );
			if (isset ( $sql->row ['customer_id'] )) {
				
				if (in_array ( $paid, explode ( ',', $sql->row ['customer_id'] ) ))
					return 2; // already exists
				else
					return 1; // for update return cp product id
			} else {
				return 1; // not exists so copy
			}
		} else {
			return 0; // already exists
		}
	}
	public function getPartnerAmount($partner_id) {
		$total = $this->db->query ( "SELECT SUM(c2o.quantity) quantity,SUM(c2o.price) total,SUM(c2o.admin) admin,SUM(c2o.customer) customer FROM " . DB_PREFIX . "customerpartner_to_order c2o WHERE c2o.customer_id ='" . ( int ) $partner_id . "'" )->row;
		
		$paid = $this->db->query ( "SELECT SUM(c2t.amount) total FROM " . DB_PREFIX . "customerpartner_to_transaction c2t WHERE c2t.customer_id ='" . ( int ) $partner_id . "'" )->row;
		
		$total ['paid'] = $paid ['total'];
		
		return ($total);
	}
	public function getPartnerTotal($partner_id, $filter_data = array()) {
		$sub_query = "(SELECT SUM(c2t.amount) as total FROM " . DB_PREFIX . "customerpartner_to_transaction c2t WHERE c2t.customer_id ='" . ( int ) $partner_id . "'";
		
		if (isset ( $filter_data ['date_added_from'] ) || isset ( $filter_data ['date_added_to'] )) {
			if ($filter_data ['date_added_from'] && $filter_data ['date_added_to']) {
				$sub_query .= " AND c2t.date_added >= '" . $filter_data ['date_added_from'] . "' && c2t.date_added <= '" . $filter_data ['date_added_to'] . "' ";
			} else if ($filter_data ['date_added_from'] && ! $filter_data ['date_added_to']) {
				$sub_query .= " AND c2t.date_added >= '" . $filter_data ['date_added_from'] . "' ";
			} else if (! $filter_data ['date_added_from'] && $filter_data ['date_added_to']) {
				$sub_query .= " AND c2t.date_added <= '" . $filter_data ['date_added_to'] . "' ";
			}
		}
		
		if (isset ( $filter_data ['paid_to_seller_from'] ) || isset ( $filter_data ['paid_to_seller_to'] )) {
			if ($filter_data ['paid_to_seller_from'] && $filter_data ['paid_to_seller_to']) {
				$sub_query .= " HAVING SUM(c2t.amount) > " . $filter_data ['paid_to_seller_from'] . " && SUM(c2t.amount) < " . $filter_data ['paid_to_seller_to'] . " )";
			} else if ($filter_data ['paid_to_seller_from'] && ! $filter_data ['paid_to_seller_to']) {
				$sub_query .= " HAVING SUM(c2t.amount) > " . $filter_data ['paid_to_seller_from'] . " )";
			} else if (! $filter_data ['paid_to_seller_from'] && $filter_data ['paid_to_seller_to']) {
				$sub_query .= " HAVING SUM(c2t.amount) > " . $filter_data ['paid_to_seller_to'] . " )";
			} else {
				$sub_query .= " )";
			}
		} else {
			$sub_query .= " )";
		}
		
		$sql = "SELECT SUM(c2o.quantity) AS quantity, (SUM(c2o.customer) + SUM(c2o.admin)) AS total,SUM(c2o.admin) admin,SUM(c2o.customer) AS customer, " . $sub_query . " AS paid FROM " . DB_PREFIX . "customerpartner_to_order c2o WHERE c2o.customer_id ='" . ( int ) $partner_id . "' ";
		
		if (isset ( $filter_data ['date_added_from'] ) || isset ( $filter_data ['date_added_to'] )) {
			if ($filter_data ['date_added_from'] && $filter_data ['date_added_to']) {
				$sql .= " AND c2o.date_added >= '" . $filter_data ['date_added_from'] . "' && c2o.date_added <= '" . $filter_data ['date_added_to'] . "' ";
			} else if ($filter_data ['date_added_from'] && ! $filter_data ['date_added_to']) {
				$sql .= " AND c2o.date_added >= '" . $filter_data ['date_added_from'] . "' ";
			} else if (! $filter_data ['date_added_from'] && $filter_data ['date_added_to']) {
				$sql .= " AND c2o.date_added <= '" . $filter_data ['date_added_to'] . "' ";
			}
		}
		
		$sql .= " HAVING SUM(c2o.quantity) >= 0 ";
		
		if (isset ( $filter_data ['total_amount_from'] ) || isset ( $filter_data ['total_amount_to'] )) {
			if ($filter_data ['total_amount_from'] && $filter_data ['total_amount_to']) {
				$sql .= " AND (SUM(c2o.customer) + SUM(c2o.admin)) > " . $filter_data ['total_amount_from'] . " && (SUM(c2o.customer) + SUM(c2o.admin)) < " . $filter_data ['total_amount_to'] . " ";
			} else if ($filter_data ['total_amount_from'] && ! $filter_data ['total_amount_to']) {
				$sql .= " AND (SUM(c2o.customer) + SUM(c2o.admin)) > " . $filter_data ['total_amount_from'] . " ";
			} else if (! $filter_data ['total_amount_from'] && $filter_data ['total_amount_to']) {
				$sql .= " AND (SUM(c2o.customer) + SUM(c2o.admin)) < " . $filter_data ['total_amount_to'] . "";
			}
		}
		
		if (isset ( $filter_data ['seller_amount_from'] ) || isset ( $filter_data ['seller_amount_to'] )) {
			if ($filter_data ['seller_amount_from'] && $filter_data ['seller_amount_to']) {
				$sql .= " AND SUM(c2o.customer) > " . $filter_data ['seller_amount_from'] . " && SUM(c2o.customer) < " . $filter_data ['seller_amount_to'] . " ";
			} else if ($filter_data ['seller_amount_from'] && ! $filter_data ['seller_amount_to']) {
				$sql .= " AND SUM(c2o.customer) > " . $filter_data ['seller_amount_from'] . " ";
			} else if (! $filter_data ['seller_amount_from'] && $filter_data ['seller_amount_to']) {
				$sql .= " AND SUM(c2o.customer) < " . $filter_data ['seller_amount_to'] . "";
			}
		}
		
		if (isset ( $filter_data ['admin_amount_from'] ) || isset ( $filter_data ['admin_amount_to'] )) {
			if ($filter_data ['admin_amount_from'] && $filter_data ['admin_amount_to']) {
				$sql .= " AND SUM(c2o.admin) > " . $filter_data ['admin_amount_from'] . " && SUM(c2o.admin) < " . $filter_data ['admin_amount_to'] . " ";
			} else if ($filter_data ['admin_amount_from'] && ! $filter_data ['admin_amount_to']) {
				$sql .= " AND SUM(c2o.admin) > " . $filter_data ['admin_amount_from'] . " ";
			} else if (! $filter_data ['admin_amount_from'] && $filter_data ['admin_amount_to']) {
				$sql .= " AND SUM(c2o.admin) < " . $filter_data ['admin_amount_to'] . "";
			}
		}
		
		// echo $sql;
		$total = $this->db->query ( $sql )->row;
		
		return ($total);
	}
	public function getPartnerAmountTotal($partner_id, $filter_data = array()) {
		$sql = "SELECT SUM(c2o.quantity) quantity,SUM(c2o.price) total,SUM(c2o.admin) admin,SUM(c2o.customer) customer FROM " . DB_PREFIX . "customerpartner_to_order c2o WHERE c2o.customer_id ='" . ( int ) $partner_id . "' ";
		echo $sql;
		
		if ($filter_data ['commission_from'] && $filter_data ['commission_to']) {
			$url .= '';
		} else if ($filter_data ['commission_from'] && ! $filter_data ['commission_to']) {
			$url .= '';
		} else if (! $filter_data ['commission_from'] && $filter_data ['commission_to']) {
			$url .= '';
		}
		
		$total = $this->db->query ( $sql )->row;
		
		$paid = $this->db->query ( "SELECT SUM(c2t.amount) total FROM " . DB_PREFIX . "customerpartner_to_transaction c2t WHERE c2t.customer_id ='" . ( int ) $partner_id . "'" )->row;
		
		$total ['paid'] = $paid ['total'];
		
		return (count ( $total ));
	}
	public function getSellerOrdersList($seller_id, $filter_data) {
		$sql = "SELECT DISTINCT op.order_id,c2o.paid_status,c2o.customer AS need_to_pay,o.date_added, CONCAT(o.firstname ,' ',o.lastname) AS name ,os.name AS orderstatus,op.*, (SELECT group_concat( concat( value) SEPARATOR ', ') FROM " . DB_PREFIX . "order_option oo WHERE oo.order_product_id=c2o.order_product_id ) AS value  FROM " . DB_PREFIX . "order_status os LEFT JOIN " . DB_PREFIX . "customerpartner_order cpo ON (os.order_status_id = cpo.order_status_id) LEFT JOIN " . DB_PREFIX . "order o ON (o.order_id = cpo.order_id) LEFT JOIN " . DB_PREFIX . "customerpartner_to_order c2o ON (cpo.order_id = c2o.order_id) LEFT JOIN " . DB_PREFIX . "order_product op ON (op.order_product_id=c2o.order_product_id) WHERE c2o.customer_id = '" . ( int ) $seller_id . "' AND os.language_id = '" . $this->config->get ( 'config_language_id' ) . "' ";
		if (isset ( $filter_data ['date_added_from'] ) || isset ( $filter_data ['date_added_to'] )) {
			if ($filter_data ['date_added_from'] && $filter_data ['date_added_to']) {
				$sql .= " AND o.date_added >= '" . $filter_data ['date_added_from'] . "' && o.date_added <= '" . $filter_data ['date_added_to'] . "' ";
			} else if ($filter_data ['date_added_from'] && ! $filter_data ['date_added_to']) {
				$sql .= " AND o.date_added >= '" . $filter_data ['date_added_from'] . "'' ";
			} else if (! $filter_data ['date_added_from'] && $filter_data ['date_added_to']) {
				$sql .= " AND o.date_added <= '" . $filter_data ['date_added_to'] . "' ";
			}
		}
		
		if ($filter_data ['order_id']) {
			$sql .= " AND op.order_id = '" . $filter_data ['order_id'] . "' ";
		}
		
		if ($filter_data ['payable_amount']) {
			$sql .= " AND c2o.customer = " . ( float ) $filter_data ['payable_amount'] . " ";
		}
		
		if ($filter_data ['quantity']) {
			$sql .= " AND op.quantity = '" . $filter_data ['quantity'] . "' ";
		}
		
		// if($filter_data['date_added']) {
		// $sql .= " AND o.date_added like '%".$filter_data['date_added']."' " ;
		// }
		
		if ($filter_data ['order_status']) {
			$sql .= " AND os.name = '" . $filter_data ['order_status'] . "' ";
		}
		
		if ($filter_data ['paid_status']) {
			if ($filter_data ['paid_status'] == 'paid')
				$filter_data ['paid_status'] = 1;
			else
				$filter_data ['paid_status'] = 0;
			$sql .= " AND c2o.paid_status = '" . $filter_data ['paid_status'] . "' ";
		}
		if ($filter_data ['order_by'] && $filter_data ['sort_by']) {
			$sql .= "ORDER BY " . $filter_data ['order_by'] . " " . $filter_data ['sort_by'] . " LIMIT " . $filter_data ['start'] . ", " . $filter_data ['limit'] . "";
		} else {
			$sql .= "ORDER BY o.order_id asc LIMIT " . $filter_data ['start'] . ", " . $filter_data ['limit'] . "";
		}
		$result = $this->db->query ( $sql );
		return ($result->rows);
	}
	public function getProductOptions($order_product_id) {
		return $this->db->query ( "SELECT oo.value FROM " . DB_PREFIX . "order_option oo WHERE oo.order_product_id = '" . ( int ) $order_product_id . "'" )->rows;
	}
	public function getSellerOrders($seller_id) {
		$sql = $this->db->query ( "SELECT DISTINCT o.order_id ,o.date_added, CONCAT(o.firstname ,' ',o.lastname) name ,os.name orderstatus  FROM " . DB_PREFIX . "order_status os LEFT JOIN " . DB_PREFIX . "order o ON (os.order_status_id = o.order_status_id) LEFT JOIN " . DB_PREFIX . "customerpartner_to_order c2o ON (o.order_id = c2o.order_id) WHERE c2o.customer_id = '" . ( int ) $seller_id . "' AND os.language_id = '" . $this->config->get ( 'config_language_id' ) . "' ORDER BY o.order_id DESC " );
		
		return ($sql->rows);
	}
	public function getTotalSellerOrders($seller_id, $filter_data) {
		$sql = "SELECT DISTINCT op.order_id,c2o.paid_status,c2o.customer as need_to_pay,o.date_added, CONCAT(o.firstname ,' ',o.lastname) name ,os.name orderstatus,op.*, (SELECT group_concat( concat( value) SEPARATOR ', ') FROM " . DB_PREFIX . "order_option oo WHERE oo.order_product_id=c2o.order_product_id ) as value  FROM " . DB_PREFIX . "order_status os LEFT JOIN " . DB_PREFIX . "order o ON (os.order_status_id = o.order_status_id) LEFT JOIN " . DB_PREFIX . "customerpartner_to_order c2o ON (o.order_id = c2o.order_id) LEFT JOIN " . DB_PREFIX . "order_product op ON op.order_product_id=c2o.order_product_id WHERE c2o.customer_id = '" . ( int ) $seller_id . "' AND os.language_id = '" . $this->config->get ( 'config_language_id' ) . "' ";
		
		if (isset ( $filter_data ['date_added_from'] ) || isset ( $filter_data ['date_added_to'] )) {
			if ($filter_data ['date_added_from'] && $filter_data ['date_added_to']) {
				$sql .= " AND o.date_added >= '" . $filter_data ['date_added_from'] . "' && o.date_added <= '" . $filter_data ['date_added_to'] . "' ";
			} else if ($filter_data ['date_added_from'] && ! $filter_data ['date_added_to']) {
				$sql .= " AND o.date_added >= '" . $filter_data ['date_added_from'] . "'' ";
			} else if (! $filter_data ['date_added_from'] && $filter_data ['date_added_to']) {
				$sql .= " AND o.date_added <= '" . $filter_data ['date_added_to'] . "' ";
			}
		}
		
		if ($filter_data ['order_id']) {
			$sql .= " AND op.order_id = '" . $filter_data ['order_id'] . "' ";
		}
		
		if ($filter_data ['payable_amount']) {
			$sql .= " AND c2o.customer = '" . $filter_data ['payable_amount'] . "' ";
		}
		
		if ($filter_data ['quantity']) {
			$sql .= " AND op.quantity = '" . $filter_data ['quantity'] . "' ";
		}
		
		if ($filter_data ['order_status']) {
			$sql .= " AND os.name = '" . $filter_data ['order_status'] . "' ";
		}
		
		if ($filter_data ['paid_status']) {
			$sql .= " AND c2o.paid_status = '" . $filter_data ['paid_status'] . "' ";
		}
		
		$result = $this->db->query ( $sql );
		
		return (count ( $result->rows ));
	}
	public function getSellerOrderProducts($order_id) {
		$sql = $this->db->query ( "SELECT op.*,c2o.price c2oprice FROM " . DB_PREFIX . "customerpartner_to_order c2o LEFT JOIN " . DB_PREFIX . "order_product op ON (c2o.order_product_id = op.order_product_id AND c2o.order_id = op.order_id) WHERE c2o.order_id = '" . $order_id . "' ORDER BY op.product_id " );
		
		return ($sql->rows);
	}
}
?>
