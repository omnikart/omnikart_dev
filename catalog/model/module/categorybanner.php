<?php
class ModelModuleCategorybanner extends Model {
	public function getCategory($data = array()){
		$query = $this->db->query("SELECT * FROM `".DB_PREFIX ."design_to_category` WHERE element_id = '".$data['category_id']."' AND route = 'product/category'");
		if ($query->num_rows)
			return $query->row;
		else 
			return false;
	}	
}