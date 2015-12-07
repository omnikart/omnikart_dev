<?php
class ModelModuleCategorybanner extends Model {
	public function install(){
		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX ."design_to_category` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`category_id` int(11) NOT NULL ,
	  		`banner_id` int(11) NOT NULL ,
		  	PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1");
		
		$this->db->query("IF NOT EXISTS (SELECT * FROM `information_schema` WHERE COLUMN_NAME='route' AND TABLE_NAME='".DB_PREFIX ."design_to_category' AND TABLE_SCHEMA='the_schema='".DB_DATABASE ."') ALTER TABLE `".DB_PREFIX ."design_to_category` ADD route ");
		
	}
	
	public function updateCategories($data = array()){
		if (isset($data['category'])) {
			$implode = array();
			foreach ($data['category'] as $key => $category){
				$implode[] ="('".$key."','".$category['banner_id']."')";
			}
			$sql = "INSERT INTO `".DB_PREFIX ."design_to_category` (`category_id`, `banner_id`) VALUES ".implode(',',$implode);
		}
	}
	
	public function getCategory($data = array()){
		$query = $this->db->query("SELECT * FROM `".DB_PREFIX ."design_to_category` WHERE category_id = '".$data['category_id']."'");
		return $query->row;
	}	
}