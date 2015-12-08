<?php
class ModelModuleCategorybanner extends Model {
	public function install(){
		$this->db->query("CREATE TABLE IF NOT EXISTS `om_design_to_category` (
			`id` int(11) NOT NULL,
		  	`category_id` int(11) NOT NULL,
		  	`banner_id` int(11) NOT NULL,
		  	`route` varchar(255) COLLATE utf8_bin NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
		$this->db->query("ALTER TABLE `om_design_to_category` ADD PRIMARY KEY (`id`);");
		$this->db->query("ALTER TABLE `om_design_to_category` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
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