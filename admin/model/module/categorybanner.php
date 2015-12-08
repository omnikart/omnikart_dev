<?php
class ModelModuleCategorybanner extends Model {
	public function install(){
		$this->db->query("CREATE TABLE IF NOT EXISTS `om_design_to_category` (
		  `element_id` int(11) NOT NULL,
		  `banner_id` int(11) NOT NULL,
		  `route` varchar(255) COLLATE utf8_bin NOT NULL,
		  `width` int(5) NOT NULL DEFAULT '0',
		  `height` int(5) NOT NULL DEFAULT '0'
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
	}
	
	public function updateCategories($data = array()){
		if (isset($data['category'])) {
			$implode = array();
			foreach ($data['category'] as $key => $category){
				$this->db->query("DELETE FROM `".DB_PREFIX ."design_to_category` WHERE `element_id` = '".$key."' AND `route` = '".$category['route']."'");
				$this->db->query("INSERT INTO `".DB_PREFIX ."design_to_category` (`element_id`, `banner_id`, `route`, `width`, `height`) VALUES ('".$key."','".$category['banner_id']."','".$category['route']."','".$category['width']."','".$category['height']."')");
					
			}
		}
	}
	
	public function getCategory($data = array()){
		$query = $this->db->query("SELECT * FROM `".DB_PREFIX ."design_to_category` WHERE element_id = '".$data['category_id']."' AND route = 'product/category'");
		if ($query->num_rows)
			return $query->row;
		else
			return false;
	}		
}