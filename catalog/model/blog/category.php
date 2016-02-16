<?php
class ModelBlogCategory extends Model {
	public function getCategory($category_id) {
		$query = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "blog_category c LEFT JOIN " . DB_PREFIX . "blog_category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "blog_category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.category_id = '" . ( int ) $category_id . "' AND cd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "' AND c2s.store_id = '" . ( int ) $this->config->get ( 'config_store_id' ) . "' AND c.status = 'publish'" );
		
		return $query->row;
	}
	public function getCategories($parent_id = 0) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "blog_category c LEFT JOIN " . DB_PREFIX . "blog_category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "blog_category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . ( int ) $parent_id . "' AND cd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "' AND c2s.store_id = '" . ( int ) $this->config->get ( 'config_store_id' ) . "'  AND c.status = 'publish' ORDER BY c.sort_order, LCASE(cd.name)" );
		
		return $query->rows;
	}
	public function getCategoryFilters($category_id) {
		$implode = array ();
		
		$query = $this->db->query ( "SELECT filter_id FROM " . DB_PREFIX . "blog_category_filter WHERE category_id = '" . ( int ) $category_id . "'" );
		
		foreach ( $query->rows as $result ) {
			$implode [] = ( int ) $result ['filter_id'];
		}
		
		$filter_group_data = array ();
		
		if ($implode) {
			$filter_group_query = $this->db->query ( "SELECT DISTINCT f.filter_group_id, fgd.name, fg.sort_order FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_group fg ON (f.filter_group_id = fg.filter_group_id) LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE f.filter_id IN (" . implode ( ',', $implode ) . ") AND fgd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "' GROUP BY f.filter_group_id ORDER BY fg.sort_order, LCASE(fgd.name)" );
			
			foreach ( $filter_group_query->rows as $filter_group ) {
				$filter_data = array ();
				
				$filter_query = $this->db->query ( "SELECT DISTINCT f.filter_id, fd.name FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_id IN (" . implode ( ',', $implode ) . ") AND f.filter_group_id = '" . ( int ) $filter_group ['filter_group_id'] . "' AND fd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "' ORDER BY f.sort_order, LCASE(fd.name)" );
				
				foreach ( $filter_query->rows as $filter ) {
					$filter_data [] = array (
							'filter_id' => $filter ['filter_id'],
							'name' => $filter ['name'] 
					);
				}
				
				if ($filter_data) {
					$filter_group_data [] = array (
							'filter_group_id' => $filter_group ['filter_group_id'],
							'name' => $filter_group ['name'],
							'filter' => $filter_data 
					);
				}
			}
		}
		
		return $filter_group_data;
	}
	public function post_by_category($where = array(), $order = '', $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 20;
		}
		
		$sql = "SELECT ";
		$sql .= " * ";
		$sql .= "FROM " . DB_PREFIX . "blog_category c ";
		$sql .= "LEFT JOIN " . DB_PREFIX . "blog_category_description cd ON (c.category_id = cd.category_id) ";
		$sql .= "LEFT JOIN " . DB_PREFIX . "blog_post_to_category p2c ON (cd.category_id = p2c.category_id) ";
		$sql .= "LEFT JOIN " . DB_PREFIX . "blog_post p ON (p2c.post_id = p.ID) ";
		$sql .= "LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.ID = pd.post_id) ";
		$inc = 1;
		if (is_array ( $where ) && ! empty ( $where )) {
			$sql .= "WHERE ";
			foreach ( $where as $key => $value ) {
				if ($inc == 1) {
					$sql .= $key . $value;
				} else {
					$sql .= " AND " . $key . $value;
				}
				$inc ++;
			}
			$sql .= " AND cd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "'";
			$sql .= " AND pd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "'";
		} else {
			$sql .= "WHERE ";
			$sql .= " cd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "'";
			$sql .= " pd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "'";
		}
		
		if ($order) {
			$sql .= " ORDER BY " . $order;
		}
		if ($limit) {
			$sql .= " LIMIT " . ( int ) $start . "," . ( int ) $limit;
		}
		// return $sql;
		$query = $this->db->query ( $sql );
		if ($query->num_rows) {
			return $query->rows;
		} else {
			return array ();
		}
	}
	public function getCategoryLayoutId($category_id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "blog_category_to_layout WHERE category_id = '" . ( int ) $category_id . "' AND store_id = '" . ( int ) $this->config->get ( 'config_store_id' ) . "'" );
		
		if ($query->num_rows) {
			return $query->row ['layout_id'];
		} else {
			return 0;
		}
	}
}