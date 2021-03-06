<?php
class ControllerProductJson extends Controller {
	public function index() {
		$this->load->model ( 'tool/image' );
		
		if (isset ( $this->request->get ['search'] )) {
			$search = $this->request->get ['search'];
		} else {
			$search = '';
		}
		if (isset ( $this->request->get ['search'] )) {
			$filter_data = array (
					'filter_name' => $search,
					'start' => 0,
					'limit' => 5 
			);
			
			$results = $this->getProducts ( $filter_data );
		} else {
			$results = array ();
		}
		
		$data ['products'] = array ();
		foreach ( $results as $result ) {
			$names = strtolower ( $result ['pname'] );
			$pos = strpos ( $result ['pname'] . ' ', ' ', strpos ( $names, $search ) + strlen ( $search ) );
			$sname = substr ( $result ['pname'], 0, $pos );
			
			if (! isset ( $data ['products'] [base64_encode ( $sname )] )) {
				$data ['products'] [base64_encode ( $sname )] = array ();
				$data ['products'] [base64_encode ( $sname )] ['name'] = $sname;
				$data ['products'] [base64_encode ( $sname )] ['href'] = $this->url->link ( 'product/search', 'search=' . $data ['products'] [base64_encode ( $sname )] ['name'] );
			}
			if (! isset ( $data ['products'] [base64_encode ( $sname )] ['categories'] [base64_encode ( $result ['name'] )] )) {
				$data ['products'] [base64_encode ( $sname )] ['categories'] [base64_encode ( $result ['name'] )] = array (
						'category_id' => $result ['category_id'],
						'name' => $result ['name'],
						'href' => $this->url->link ( 'product/category', 'path=' . $result ['category_id'] . '&mfp=search[' . $sname . ']' ) 
				);
			}
		}
		header ( 'Content-Type: application/json' );
		header ( 'Content-Type: text/html; charset=utf-8' );
		echo json_encode ( $data ['products'] );
	}
	public function getProducts($data = array()) {
		$sql = "SELECT p2c.category_id, p.product_id, cd.name as name, pd.name as pname FROM " . DB_PREFIX . "product_to_category p2c INNER JOIN " . DB_PREFIX . "product p ON (p.product_id=p2c.product_id) INNER JOIN " . DB_PREFIX . "category_description cd ON (p2c.category_id = cd.category_id) INNER JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "' AND p.status = '1' AND p.type <> '0' AND p.date_available <= NOW() AND p2s.store_id = '" . ( int ) $this->config->get ( 'config_store_id' ) . "'";
		
		if (! empty ( $data ['filter_name'] )) {
			$sql .= " AND (";
			
			if (! empty ( $data ['filter_name'] )) {
				$implode = array ();
				
				$words = explode ( ' ', trim ( preg_replace ( '/\s+/', ' ', $data ['filter_name'] ) ) );
				
				foreach ( $words as $word ) {
					$implode [] = "pd.name LIKE '%" . $this->db->escape ( $word ) . "%'";
				}
				
				if ($implode) {
					$sql .= " " . implode ( " AND ", $implode ) . "";
				}
			}
			
			if (! empty ( $data ['filter_name'] ) && ! empty ( $data ['filter_tag'] )) {
				$sql .= " OR ";
			}
			
			if (! empty ( $data ['filter_tag'] )) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape ( $data ['filter_tag'] ) . "%'";
			}
			
			if (! empty ( $data ['filter_name'] )) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape ( utf8_strtolower ( $data ['filter_name'] ) ) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape ( utf8_strtolower ( $data ['filter_name'] ) ) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape ( utf8_strtolower ( $data ['filter_name'] ) ) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape ( utf8_strtolower ( $data ['filter_name'] ) ) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape ( utf8_strtolower ( $data ['filter_name'] ) ) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape ( utf8_strtolower ( $data ['filter_name'] ) ) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape ( utf8_strtolower ( $data ['filter_name'] ) ) . "'";
			}
			
			$sql .= ")";
		}
		
		$sql .= " GROUP BY p.product_id, p2c.category_id ORDER BY p.sort_order ASC, LCASE(pd.name) ASC LIMIT 0,7";
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function enquiry_product() {
		$this->load->model ( 'tool/image' );
		
		if (isset ( $this->request->get ['search'] )) {
			$search = $this->request->get ['search'];
		} else {
			$search = '';
		}
		if (isset ( $this->request->get ['search'] )) {
			$filter_data = array (
					'filter_name' => $search,
					'start' => 0,
					'limit' => 5 
			);
			
			$results = $this->getEnquiryProducts ( $filter_data );
		} else {
			$results = array ();
		}
		
		header ( 'Content-Type: application/json' );
		header ( 'Content-Type: text/html; charset=utf-8' );
		echo json_encode ( $results );
	}
	public function getEnquiryProducts($data = array()) {
		if (! empty ( $data ['filter_name'] )) {
			$sql = "SELECT p.product_id, p.type, gpg.gp_template, pd.name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "gp_grouped gpg ON (gpg.product_id=p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "' AND p.status = '1' AND p.type <> '0' AND p.date_available <= NOW() AND p2s.store_id = '" . ( int ) $this->config->get ( 'config_store_id' ) . "'";
			
			$sql .= " AND (";
			
			if (! empty ( $data ['filter_name'] )) {
				$implode = array ();
				
				$words = explode ( ' ', trim ( preg_replace ( '/\s+/', ' ', $data ['filter_name'] ) ) );
				
				foreach ( $words as $word ) {
					$implode [] = "pd.name LIKE '%" . $this->db->escape ( $word ) . "%'";
				}
				
				if ($implode) {
					$sql .= " " . implode ( " AND ", $implode ) . "";
				}
			}
			
			if (! empty ( $data ['filter_name'] ) && ! empty ( $data ['filter_tag'] )) {
				$sql .= " OR pd.tag LIKE '%" . $this->db->escape ( $data ['filter_tag'] ) . "%'";
			}
			
			if (! empty ( $data ['filter_name'] )) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape ( utf8_strtolower ( $data ['filter_name'] ) ) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape ( utf8_strtolower ( $data ['filter_name'] ) ) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape ( utf8_strtolower ( $data ['filter_name'] ) ) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape ( utf8_strtolower ( $data ['filter_name'] ) ) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape ( utf8_strtolower ( $data ['filter_name'] ) ) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape ( utf8_strtolower ( $data ['filter_name'] ) ) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape ( utf8_strtolower ( $data ['filter_name'] ) ) . "'";
			}
			
			$sql .= ")";
			
			$sql .= " GROUP BY p.product_id  ORDER BY p.sort_order ASC, LCASE(pd.name) ASC LIMIT 0,5";
			
			$query = $this->db->query ( $sql );
			
			$products = array ();
			
			foreach ( $query->rows as $product ) {
				
				if ($product ['gp_template']) {
					$product ['type'] = 2;
				}
				
				$products [] = array (
						'name' => $product ['name'],
						'cname' => $product ['name'],
						'value' => 'p' . $product ['product_id'],
						'product_id' => $product ['product_id'],
						'type' => $product ['type'] 
				);
			}
			
			if (! $products) {
				$sql = "SELECT cd.category_id, cd.name FROM " . DB_PREFIX . "category_description cd WHERE cd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "'";
				
				$sql .= " AND (";
				
				if (! empty ( $data ['filter_name'] )) {
					$implode = array ();
					
					$words = explode ( ' ', trim ( preg_replace ( '/\s+/', ' ', $data ['filter_name'] ) ) );
					
					foreach ( $words as $word ) {
						$implode [] = "cd.name LIKE '%" . $this->db->escape ( $word ) . "%'";
					}
					
					if ($implode) {
						$sql .= " " . implode ( " AND ", $implode ) . "";
					}
				}
				
				$sql .= ")";
				
				$sql .= " GROUP BY cd.category_id ORDER BY LCASE(cd.name) ASC LIMIT 0,10";
				
				$query = $this->db->query ( $sql );
				
				foreach ( $query->rows as $product ) {
					$products [] = array (
							'name' => $product ['name'],
							'cname' => $product ['name'],
							'value' => 'c' . $product ['category_id'],
							'product_id' => $product ['category_id'],
							'type' => '0' 
					);
				}
			}
			return $products;
		}
		
		return array ();
	}
}
