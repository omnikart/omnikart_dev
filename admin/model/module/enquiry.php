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
		  	PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1") ;
	}
	 public function getEnquiry(){
	 	$query = $this->db->query("SELECT * FROM `".DB_PREFIX."enquiry` WHERE 1;");
	 	return $query->rows;
	 }
	
}