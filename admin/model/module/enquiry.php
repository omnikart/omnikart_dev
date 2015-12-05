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
		  	PRIMARY KEY (`id`)a
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1") ;
	}
	 public function getEnquiries($data = array()){ // renamed from getEnquiry to getEnquiries
	 	$query = $this->db->query("SELECT * FROM `".DB_PREFIX."enquiry` WHERE status <> '0' ORDER BY date DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit']);
	 	return $query->rows;
	 }
	 public function getEnquiry($id){ // added New function
	 	$query = $this->db->query("SELECT * FROM `".DB_PREFIX."enquiry` WHERE id = '" . (int)$id."'");
	 	return $query->row;
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
	 public function updateEnquiry(){
	 	$this->db->query("UPDATE `".DB_PREFIX."enquiry` SET ");
	 }
	 public function updateQuery($data = array()){
	 	$selected = $data['query'];
	 	foreach ($selected as $key => $select) {
	 		if (in_array($key,$data['selected']))
	 		$query = $this->db->query("UPDATE `".DB_PREFIX."enquiry` SET status = '".(int)$select['status']."' WHERE id = '".(int)$key."'");
	 	}
	 }
}