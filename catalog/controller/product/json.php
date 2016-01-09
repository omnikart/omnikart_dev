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
/* 			if ($result ['image']) {
				$image = $this->model_tool_image->resize ( $result ['image'], 40, 40 );
			} else {
				$image = $this->model_tool_image->resize ( 'placeholder.png', 40, 40 );
			} */
		
			$names = strtolower($result['pname']);
			$pos = strpos($result['pname'].' ', ' ',strpos($names, $search)+strlen($search));
			$sname = substr($result['pname'],0,$pos);
					
			if (!isset($data ['products'] [base64_encode($sname)])) {
				$data ['products'] [base64_encode($sname)] = array();
				$data ['products'] [base64_encode($sname)]['name'] = $sname;
				$data ['products'] [base64_encode($sname)]['href'] = $this->url->link ( 'product/search', 'search='.$data ['products'] [base64_encode($sname)]['name']);
			}
			if (!isset($data ['products'] [base64_encode($sname)] ['categories'][base64_encode($result ['name'])])) {
				$data ['products'] [base64_encode($sname)] ['categories'][base64_encode($result ['name'])] = array (
						'category_id' => $result ['category_id'],
						'name' => $result ['name'],
						'href' => $this->url->link ( 'product/category', 'path=' . $result ['category_id'].'&mfp=search['.$result['pname'].']') 
				);
			}
		}
		header ( 'Content-Type: application/json' );
		header ( 'Content-Type: text/html; charset=utf-8' );
		echo json_encode ( $data ['products'] );
	}
	public function getProducts($data = array()) {
		$sql = "SELECT DISTINCT p2c.category_id, p.product_id, cd.name as name, pd.name as pname  FROM " . DB_PREFIX . "product_to_category p2c LEFT JOIN " . DB_PREFIX . "category_description cd ON (p2c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id=p2c.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "' AND p.status = '1' AND p.type <> '0' AND p.date_available <= NOW() AND p2s.store_id = '" . ( int ) $this->config->get ( 'config_store_id' ) . "'";
		
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
				
				if (! empty ( $data ['filter_description'] )) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape ( $data ['filter_name'] ) . "%'";
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
		
		$sql .= " GROUP BY p.product_id";
		
		$sort_data = array (
				'pd.name',
				'p.model',
				'p.quantity',
				'p.price',
				'rating',
				'p.sort_order',
				'p.date_added' 
		);
		
		$sql .= " ORDER BY p.sort_order ASC, LCASE(pd.name) ASC LIMIT 0,6";
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
}
