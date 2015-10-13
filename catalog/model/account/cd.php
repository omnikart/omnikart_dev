<?php
class ModelAccountCd extends Model {
	public function getProducts($data = array()) {
		$products = array();
		$customer_id = $this->customer->getId();
		$this->load->model("account/customerpartner");
		$seller_id = $this->model_account_customerpartner->getuserseller();
		$query2 = $this->db->query("SELECT * FROM ".DB_PREFIX."customer_to_product WHERE category_id = '". (int)$data['category_id'] ."' AND customer_id = '".$seller_id."'");
		return $query2->rows;
	}
	public function getCategories($data = array()) {
		$products = array();
		$this->load->model('catalog/product');
		$customer_id = $this->customer->getId();
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."customer_to_category cc LEFT JOIN ".DB_PREFIX."customer_to_categoryd ccd ON (cc.category_id=ccd.category_id) WHERE cc.customer_id = '". (int)$customer_id ."'");
		return $query->rows;
	}	
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."customer_to_category cc LEFT JOIN ".DB_PREFIX."customer_to_categoryd ccd ON (cc.category_id=ccd.category_id) WHERE cc.category_id = '". (int)$category_id ."'");
		return $query->row;
	}
	public function updateCategory($data) {
		$this->db->query("UPDATE ".DB_PREFIX."customer_to_category SET  WHERE category_id = '".(int)$data['category_id']."'");
		return true;
	}
	public function addProducts($data = array()) {
		$products = array();
		$product_count = 0;
		$customer_id = $this->customer->getId();
		$this->load->model("account/customerpartner");
		$seller_id = $this->model_account_customerpartner->getuserseller();
		if ($data['category_id']=='0'){
			$this->db->query("INSERT INTO ".DB_PREFIX."customer_to_category SET customer_id = '". (int)$seller_id ."'");
			$category_id = $this->db->getLastId();
			$this->db->query("INSERT INTO ".DB_PREFIX."customer_to_categoryd SET category_id= '". (int)$category_id ."',name='".$this->db->escape($data['category-name'])."'");
		} else 	$category_id = $data['category_id'];
		
		foreach($data['products'] as $product) {
			$query = $this->db->query("SELECT * FROM ".DB_PREFIX."customer_to_product WHERE customer_id = '". (int)$seller_id ."' AND product_id='".$product."' AND category_id = '".$category_id."'");
			if ($query->num_rows == 0) {
				$this->db->query("INSERT INTO ".DB_PREFIX."customer_to_product (product_id,category_id,customer_id) VALUES ('".$product."','".$category_id."','".$seller_id."')");
				$product_count++;
			} 
		}
		return $product_count;
	}
}