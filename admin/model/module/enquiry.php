<?php
class ModelModuleEnquiry extends Model {
	public function install(){
		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX ."enquiry` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`query` text,
	  		`file` varchar(200) NOT NULL,
		  	`url` varchar(200) NOT NULL,
			`user_info` text,
  			`status` int(4) unsigned NOT NULL,
  			`date` datetime NOT NULL,
		  	PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX ."enquiry_to_user` (
			`id` int(11) NOT NULL,
			`customer_id`  int(11) NOT NULL,
	  		`user_id`  int(11) NOT NULL,
		  	PRIMARY KEY (`id`,`customer_id`,`user_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX ."enquiry_revisions` (
			`revision_id` int(11) NOT NULL AUTO_INCREMENT,
			`id`  int(11) NOT NULL,
	  		`enquiry_product_id`  int(11) NOT NULL,
		  	PRIMARY KEY (`revision_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX ."enquiry_products` (
			`enquiry_product_id` int(11) NOT NULL AUTO_INCREMENT,
			`name`  varchar(200) NOT NULL,
			`minimum`  int(11) DEFAULT 1,
			`unit`  int(11) NOT NULL,
			`unit_class_id` int(11) NOT NULL,				
	  		`price`  int(11) NOT NULL,
			`tax_rate` decimal(15,8) NOT NULL DEFAULT '0.000000000',
			`total`  int(11) NOT NULL,
			`data_added`  datetime NOT NULL,
		  	PRIMARY KEY (`enquiry_product_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1");

		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX ."enquiry_totals` (
			`enquiry_total_id` int(10) NOT NULL,
		  	`id` int(11) NOT NULL,
		  	`code` varchar(32) NOT NULL,
		  	`title` varchar(255) NOT NULL,
		  	`value` decimal(15,4) NOT NULL DEFAULT '0.0000',
		  	`sort_order` int(3) NOT NULL,
		  	PRIMARY KEY (`enquiry_total_id`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX ."enquiry_terms` (
		  `revision_id` int(11) NOT NULL,
		  `term` varchar(128) COLLATE utf8_bin NOT NULL,
		  `value` text COLLATE utf8_bin NOT NULL,
		  `sort_order` int(11) NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		
	}
	 public function getEnquiries($data = array()){ // renamed from getEnquiry to getEnquiries
	 	$query = $this->db->query("SELECT * FROM `".DB_PREFIX."enquiry` WHERE status <> '0' ORDER BY date DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit']);
	 	return $query->rows;
	 }
	 public function getEnquiry($id){ // added New function
	 	$returndata = array('initial_query','revision_query','revision_comment','revision_products');
	 	
	 	$query = $this->db->query("SELECT * FROM `".DB_PREFIX."enquiry` WHERE id = '" . (int)$id."'");
	 	
	 	$returndata['initial_query'] = $query->row;
	 	
	 	$query2 = $this->db->query("SELECT * FROM `".DB_PREFIX."enquiry_to_user` WHERE id = '" . (int)$id."'"); // Check is enquiry added to quotation
	 	
	 	if ($query2->num_rows) {
	 		$query3 = $this->db->query("SELECT * FROM `".DB_PREFIX."enquiry_revisions` WHERE id = '" . (int)$id."' ORDER BY revision_id DESC LIMIT 1");// Latest Revision id
	 		$query4 = $this->db->query("SELECT * FROM `".DB_PREFIX."enquiry_products` WHERE revision_id = '".$query3->row['revision_id']."'");// Products Latest
	 		$returndata['revision_query'] = $query2->row;
	 		$returndata['revision_data'] = $query3->row;
	 		$returndata['revision_products'] = $query4->rows;
	 	}

	 	return $returndata;
	 }
	 
	 public function getTotalEnquiries($data = array()){
	 	$query = $this->db->query("SELECT COUNT(*) AS total FROM `".DB_PREFIX."enquiry` WHERE status <> '0'");
	 	return $query->row['total'];
	 }
	 public function delete($data = array()){
	 	$selected = $data['selected']; 
	 	$implode = implode(',',$selected );
	 	$query = $this->db->query("UPDATE `".DB_PREFIX."enquiry` SET status = '0' WHERE id IN (".$implode.")");
	 }
	 public function updateEnquiry($data){
	 	$query = $this->db->query("SELECT * FROM `".DB_PREFIX."enquiry_to_user` WHERE id = '".$data['enquiry_id']."' AND customer_id = '".$data['customer_id']."' AND user_id = '".$data['user_id']."'");
	 	if (!$query->num_rows){
	 		$this->db->query("INSERT INTO `".DB_PREFIX."enquiry_to_user`(id,customer_id,user_id) VALUES (".$data['enquiry_id'].",".$data['customer_id'].",".$data['user_id'].")");
	 	}
			 	
	 	if (!$data['revision_id']) { //if opted for new revison
	 		$query = $this->db->query("SELECT sort_order FROM `".DB_PREFIX."enquiry_revisions` WHERE = '".$data['enquiry_id']."' ORDER BY sort_order DESC LIMIT 1");
	 		if ($query->num_rows)
	 			$this->db->query("INSERT INTO `".DB_PREFIX."enquiry_revisions` VALUES ('','".$data['enquiry_id']."','".$data['comment']."','".(int)($query->row['sort_order']+1)."')");
	 		else 
	 			$this->db->query("INSERT INTO `".DB_PREFIX."enquiry_revisions` VALUES ('','".$data['enquiry_id']."','".$data['comment']."','1')");
		 		
	 		$data['revision_id'] = $this->db->getLastId();

	 		foreach ($data['products'] as $product) {
	 			$this->db->query("INSERT INTO `".DB_PREFIX."enquiry_products` VALUES ('','".$data['revision_id']."','".$product['name']."','".($product['minimum']?$product['minimum']:1)."','1','".(float)$product['price']."','".(float)$product['total']."',NOW())");
	 		}
	 	} else {
	 		if ($data['revision_id']){
	 			foreach ($data['products'] as $product) {
	 				if ($product['enquiry_product_id']) {
	 					$this->db->query("UPDATE `".DB_PREFIX."enquiry_products` SET name = '".$product['name']."', quantity = '".($product['minimum']?$product['minimum']:1)."',unit = '1', price = '".(float)$product['price']."', total = '".(float)$product['total']."' WHERE enquiry_product_id = '".(int)$product['enquiry_product_id']."'");
	 				}
	 			}
	 		}	
	 	}
	 }
	 
	 public function updateQuery($data = array()){
	 	$selected = $data['query'];
	 	foreach ($selected as $key => $select) {
	 		if (in_array($key,$data['selected']))
	 		$query = $this->db->query("UPDATE `".DB_PREFIX."enquiry` SET status = '".(int)$select['status']."' WHERE id = '".(int)$key."'");
	 	}
	 }
}