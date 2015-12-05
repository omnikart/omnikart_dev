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
		  	PRIMARY KEY (`id`,`user_id`)
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
	  		`price`  int(11) NOT NULL,
			`total`  int(11) NOT NULL,
			`data_added`  datetime NOT NULL,
		  	PRIMARY KEY (`enquiry_product_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1");

		$this->db->query("CREATE TABLE IF NOT EXISTS `enquiry_totals` (
			`enquiry_total_id` int(10) NOT NULL,
		  	`id` int(11) NOT NULL,
		  	`code` varchar(32) NOT NULL,
		  	`title` varchar(255) NOT NULL,
		  	`value` decimal(15,4) NOT NULL DEFAULT '0.0000',
		  	`sort_order` int(3) NOT NULL,
		  	PRIMARY KEY (`enquiry_total_id`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");
	}
	 public function getEnquiries($data = array()){ // renamed from getEnquiry to getEnquiries
	 	$query = $this->db->query("SELECT * FROM `".DB_PREFIX."enquiry` WHERE status <> '0' ORDER BY date DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit']);
	 	return $query->rows;
	 }
	 public function getEnquiry($id){ // added New function
	 	$query = $this->db->query("SELECT * FROM `".DB_PREFIX."enquiry` WHERE id = '" . (int)$id."'");
	 	return $query->rows;
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
	 public function updateQuery($data = array()){
	 	$selected = $data['query'];
	 	foreach ($selected as $key => $select) {
	 		if (in_array($key,$data['selected']))
	 		$query = $this->db->query("UPDATE `".DB_PREFIX."enquiry` SET status = '".(int)$select['status']."' WHERE id = '".(int)$key."'");
	 	}
	 }
}