<?php
class ModelCheckoutComboProducts extends Model {
	public function getCombo ($combo_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "combo_setting WHERE combo_id = '". $combo_id . "'");
		return $query->row;
	}
	public function getCombos (){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "combo_setting cs");
		return $query->rows;
	}
	public function getCombosinclProduct ($product_id) {
		/*$query = $this->db->query("SELECT DISTINCT cp.combo_id, cp.product_id FROM " .DB_PREFIX. "combo_products cp WHERE product_id =  '". $product_id ."'");*/
		$query = $this->db->query("SELECT DISTINCT cp.combo_id, cp.product_id FROM " .DB_PREFIX. "combo_products cp LEFT JOIN " .DB_PREFIX. "combo_setting cs ON (cp.combo_id = cs.combo_id) WHERE cp.product_id =  '". $product_id ."' AND cs.status = 1");
		return $query->rows;
	}
	public function getCombosinclCate ($cate_id) {
		/*$query = $this->db->query("SELECT DISTINCT cp.combo_id, cp.product_id FROM " .DB_PREFIX. "combo_products cp WHERE product_id =  '". $product_id ."'");*/
		$query = $this->db->query("SELECT DISTINCT cc.combo_id, cc.category_id FROM " .DB_PREFIX. "combo_category cc LEFT JOIN " .DB_PREFIX. "combo_setting cs ON (cc.combo_id = cs.combo_id) WHERE cc.category_id =  '". $cate_id ."' AND cs.status = 1 ORDER BY RAND() LIMIT 1");
		return $query->row;
	}
	public function getProducts() {
		$products_in_combo = array();

		/*$query = $this->db->query("SELECT  cp.combo_id , cp.product_id , cs.priority  FROM " . DB_PREFIX . "combo_products cp LEFT JOIN " .DB_PREFIX. "combo_setting cs ON (cp.combo_id = cs.combo_id) ORDER BY cs.priority ASC");*/
		$query = $this->db->query("SELECT  cp.combo_id , cp.product_id , cs.priority  FROM " . DB_PREFIX . "combo_products cp LEFT JOIN " .DB_PREFIX. "combo_setting cs ON (cp.combo_id = cs.combo_id) WHERE cs.status = 1 ORDER BY cs.priority ASC");

		foreach ($query->rows as $result) {
			$products_in_combo[$result['combo_id']][] = $result['product_id'];
		}
		return $products_in_combo;
	}
}
?>