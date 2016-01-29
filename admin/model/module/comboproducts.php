<?php
class ModelModuleComboProducts extends Model {
	public function addCombo($data) {
		$this->event->trigger ( 'pre.admin.add.combo', $data );
		
		$combo_name = $data ['combo_name'];
		$combo_products = implode ( ",", $data ['combo_products'] );
		$discount_type = $data ['discount_type'];
		$discount_number = $data ['discount_number'];
		if (isset ( $data ['display_detail'] ))
			$display_detail = $data ['display_detail'];
		else
			$display_detail = 0;
		if (isset ( $data ['priority'] ))
			$priority = $data ['priority'];
		else
			$priority = 0;
		if (isset ( $data ['override'] ))
			$override = $data ['override'];
		else
			$override = 0;
		
		$this->db->query ( "INSERT INTO " . DB_PREFIX . "combo_setting SET combo_name = '" . $combo_name . "', product_id = '" . $combo_products . "', discount_type = '" . $discount_type . "', discount_number = '" . $discount_number . "', display_detail = '" . $display_detail . "', priority ='" . $priority . "', override ='" . $override . "'" );
		
		$combo_id = $this->db->getLastId ();
		
		foreach ( $data ['combo_products'] as $product_id ) {
			$this->db->query ( "INSERT INTO " . DB_PREFIX . "combo_products SET combo_id = '" . $combo_id . "', product_id = '" . $product_id . "'" );
		}
		if (isset ( $data ['combo_category'] )) {
			foreach ( $data ['combo_category'] as $category_id ) {
				$this->db->query ( "INSERT INTO " . DB_PREFIX . "combo_category SET combo_id = '" . $combo_id . "', category_id = '" . $category_id . "'" );
			}
		}
		
		$this->cache->delete ( 'combo' );
		
		$this->event->trigger ( 'post.admin.add.combo', $combo_id );
	}
	public function editCombo($combo_id, $data) {
		$this->event->trigger ( 'pre.admin.edit.combo', $data );
		
		$combo_name = $data ['combo_name'];
		$combo_products = implode ( ",", $data ['combo_products'] );
		$discount_type = $data ['discount_type'];
		$discount_number = $data ['discount_number'];
		if (isset ( $data ['display_detail'] ))
			$display_detail = $data ['display_detail'];
		else
			$display_detail = 0;
		if (isset ( $data ['priority'] ))
			$priority = $data ['priority'];
		else
			$priority = 0;
		if (isset ( $data ['override'] ))
			$override = $data ['override'];
		else
			$override = 0;
		
		$this->db->query ( "UPDATE " . DB_PREFIX . "combo_setting SET combo_name = '" . $combo_name . "', product_id = '" . $combo_products . "', discount_type = '" . $discount_type . "', discount_number = '" . $discount_number . "', display_detail = '" . $display_detail . "', priority ='" . $priority . "', override ='" . $override . "' WHERE combo_id = '" . ( int ) $combo_id . "'" );
		
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "combo_products WHERE combo_id = '" . ( int ) $combo_id . "'" );
		foreach ( $data ['combo_products'] as $product_id ) {
			$this->db->query ( "INSERT INTO " . DB_PREFIX . "combo_products SET combo_id = '" . $combo_id . "', product_id = '" . $product_id . "'" );
		}
		
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "combo_category WHERE combo_id = '" . ( int ) $combo_id . "'" );
		if (isset ( $data ['combo_category'] )) {
			foreach ( $data ['combo_category'] as $category_id ) {
				$this->db->query ( "INSERT INTO " . DB_PREFIX . "combo_category SET combo_id = '" . $combo_id . "', category_id = '" . $category_id . "'" );
			}
		}
		
		$this->cache->delete ( 'combo' );
		$this->event->trigger ( 'post.admin.edit.combo', $combo_id );
	}
	public function getCombo($combo_id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "combo_setting WHERE combo_id = '" . $combo_id . "'" );
		return $query->row;
	}
	public function getCombos() {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "combo_setting cs ORDER BY priority ASC" );
		return $query->rows;
	}
	public function deleteCombo($combo_id) {
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "combo_setting WHERE combo_id = '" . ( int ) $combo_id . "'" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "combo_products WHERE combo_id = '" . ( int ) $combo_id . "'" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "combo_category WHERE combo_id = '" . ( int ) $combo_id . "'" );
	}
	public function getProducts($combo_id) {
		$products_in_combo = array ();
		
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "combo_products WHERE combo_id = '" . ( int ) $combo_id . "'" );
		
		foreach ( $query->rows as $result ) {
			$products_in_combo [] = $result ['product_id'];
		}
		return $products_in_combo;
	}
	public function getCategory($combo_id) {
		$category = array ();
		
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "combo_category WHERE combo_id = '" . ( int ) $combo_id . "'" );
		
		foreach ( $query->rows as $result ) {
			$category [] = $result ['category_id'];
		}
		return $category;
	}
	public function updateStatus($combo_id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "combo_setting WHERE combo_id = '" . $combo_id . "'" );
		$result = $query->row;
		if ($result ['status'] == 1)
			$this->db->query ( "UPDATE " . DB_PREFIX . "combo_setting SET status = 0 WHERE combo_id = '" . ( int ) $combo_id . "'" );
		else
			$this->db->query ( "UPDATE " . DB_PREFIX . "combo_setting SET status = 1 WHERE combo_id = '" . ( int ) $combo_id . "'" );
		$this->cache->delete ( 'combo' );
	}
}
?>