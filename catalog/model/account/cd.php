<?php
class ModelAccountCd extends Model {
	public function getProducts($data = array()) {
		$products = array ();
		$customer_id = $this->customer->getId ();
		$this->load->model ( "account/customerpartner" );
		$seller_id = $this->model_account_customerpartner->getuserseller ();
		$query2 = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "customer_to_product WHERE category_id = '" . ( int ) $data ['category_id'] . "' AND customer_id = '" . $seller_id . "'" );
		return $query2->rows;
	}
	
	public function getAllProducts($start = 0, $limit = 50) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 1;
		}
		
		$products = array ();
		$customer_id = $this->customer->getId ();
		$this->load->model ( "account/customerpartner" );
		$seller_id = $this->model_account_customerpartner->getuserseller ();
		$query2 = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "customer_to_product WHERE customer_id = '" . $seller_id . "' LIMIT " . (int)$start . "," . (int)$limit );
		return $query2->rows;
	}
	
	public function gettotalProducts($data = array()) {
		$products = array ();
		$customer_id = $this->customer->getId ();
		$this->load->model ( "account/customerpartner" );
		$seller_id = $this->model_account_customerpartner->getuserseller ();
		$query2 = $this->db->query ( "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_to_product WHERE customer_id = '" . $seller_id . "'" );
		return $query2->row['total'];
	}

	public function updateproducts($data = array()) {
		$products = array ();
		$customer_id = $this->customer->getId ();
		$this->load->model ( "account/customerpartner" );
		$seller_id = $this->model_account_customerpartner->getuserseller ();
		foreach ($data['products'] as $product) {
			$query2 = $this->db->query ( "UPDATE " . DB_PREFIX . "customer_to_product SET contract_quantity='" . (int)$product['contract_quantity'] . "' WHERE customer_id = '" . $seller_id . "' AND product_id='" . (int)$product['product_id'] . "'" );
		}
	}	
	
	public function getCategories($data = array()) {
		$this->load->model ( 'account/customerpartner' );
		$customer_id = $this->model_account_customerpartner->getuserseller ();
		$products = array ();
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "customer_to_category cc LEFT JOIN " . DB_PREFIX . "customer_to_categoryd ccd ON (cc.category_id=ccd.category_id) WHERE cc.customer_id = '" . ( int ) $customer_id . "'" );
		return $query->rows;
	}
	public function getCategory($category_id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "customer_to_category cc LEFT JOIN " . DB_PREFIX . "customer_to_categoryd ccd ON (cc.category_id=ccd.category_id) WHERE cc.category_id = '" . ( int ) $category_id . "'" );
		return $query->row;
	}
	public function updateCategory($data) {
		$customer_id = $this->customer->getId ();
		$this->load->model ( "account/customerpartner" );
		$seller_id = $this->model_account_customerpartner->getuserseller ();
		echo $seller_id;
		$this->db->query ( "UPDATE " . DB_PREFIX . "customer_to_category SET image='" . $data ['category_image'] . "' WHERE category_id = '" . ( int ) $data ['category_id'] . "'" );
		foreach ( $data ['products'] as $product ) {
			$this->db->query ( "UPDATE " . DB_PREFIX . "customer_to_product SET `quantity` = '" . ( int ) $product ['quantity'] . "' WHERE `customer_id` = '" . ( int ) $seller_id . "' AND `category_id` = '" . ( int ) $data ['category_id'] . "' AND `product_id` = '" . ( int ) $product ['product_id'] . "'" );
		}
		return true;
	}
	public function addProducts($data = array()) {
		$products = array ();
		$product_count = 0;
		$customer_id = $this->customer->getId ();
		$this->load->model ( "account/customerpartner" );
		$seller_id = $this->model_account_customerpartner->getuserseller ();
		if ($data ['category_id'] == '0') {
			$this->db->query ( "INSERT INTO " . DB_PREFIX . "customer_to_category SET customer_id = '" . ( int ) $seller_id . "'" );
			$category_id = $this->db->getLastId ();
			$this->db->query ( "INSERT INTO " . DB_PREFIX . "customer_to_categoryd SET category_id= '" . ( int ) $category_id . "',name='" . $this->db->escape ( $data ['category-name'] ) . "'" );
		} else
			$category_id = $data ['category_id'];
		
		foreach ( $data ['products'] as $product ) {
			$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "customer_to_product WHERE customer_id = '" . ( int ) $seller_id . "' AND product_id='" . $product . "' AND category_id = '" . $category_id . "'" );
			if ($query->num_rows == 0) {
				$this->db->query ( "INSERT INTO " . DB_PREFIX . "customer_to_product (product_id,category_id,customer_id) VALUES ('" . $product . "','" . $category_id . "','" . $seller_id . "')" );
				$product_count ++;
			}
		}
		return $product_count;
	}
	public function removeproducts($data = array()) {
		$products = array ();
		$product_count = 0;
		$customer_id = $this->customer->getId ();
		$this->load->model ( "account/customerpartner" );
		$seller_id = $this->model_account_customerpartner->getuserseller ();
		foreach ( $data ['products'] as $product ) {
			$query = $this->db->query ( "DELETE FROM " . DB_PREFIX . "customer_to_product WHERE customer_id = '" . ( int ) $seller_id . "' AND product_id='" . $product ['product_id'] . "'" );
		}
	}

	public function getproductsale($customer_id,$product_id){
		$sql = $this->db->query("SELECT SUM(op.quantity) AS quantity, SUM(op.total) AS total FROM ".DB_PREFIX ."order_product op LEFT JOIN ".DB_PREFIX ."order o ON (o.order_id = op.order_id) WHERE o.customer_id='" . (int)$customer_id . "' AND op.product_id='" . (int)$product_id . "' GROUP BY op.product_id");
		return($sql->row);
	}
}