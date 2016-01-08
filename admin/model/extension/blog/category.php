<?php
class ModelExtensionBlogCategory extends Model {
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "blog_category_path cp LEFT JOIN " . DB_PREFIX . "blog_category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path, (SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'blog_category_id=" . (int)$category_id . "') AS keyword FROM " . DB_PREFIX . "blog_category c LEFT JOIN " . DB_PREFIX . "blog_category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getCategories($data=array(), $where=array()) {

		$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.image, c1.status, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "blog_category_path cp LEFT JOIN " . DB_PREFIX . "blog_category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "blog_category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "blog_category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "blog_category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if(is_array($where) && !empty($where)) {
			foreach ($where as $key => $value) {
				$sql .= " AND " . $key . $value;
			}
		}

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status']) && in_array($data['filter_status'], array('publish','unpublish'))) {
			$sql .= " AND c1.status = '" . $data['filter_status'] . "'";
		} 

		$sql .= " GROUP BY cp.category_id";

		$sort_data = array(
			'cd1.name',
			'c1.status',
			'c1.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY cd2.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		if ($query->num_rows > 0) {
			return $query->rows;
		} else {
			return array();
		}
	}

	public function getCatDescriptions($category_id) {
		$cat_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category_description WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$cat_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'slug'      => $result['slug'],
				'meta_description'       => $result['meta_description'],
				'meta_keyword' => $result['meta_keyword'],
				'description'     => $result['description']
			);
		}

		return $cat_description_data;
	}

	public function getCategoryLayouts($category_id) {
		$category_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category_to_layout WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $category_layout_data;
	}

	public function getCategoryStores($category_id) {
		$category_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category_to_store WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_store_data[] = $result['store_id'];
		}

		return $category_store_data;
	}

	public function getCategoryFilters($category_id) {
		$category_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category_filter WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_filter_data[] = $result['filter_id'];
		}

		return $category_filter_data;
	}

	public function post_by_catname($where=array(),$order='p.post_date', $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "blog_category pc LEFT JOIN " . DB_PREFIX . "blog_post p ON pc.category_id=p.category_id ";
		$inc = 1;
		if(is_array($where) && !empty($where)) {
			$sql .= "WHERE ";
			foreach ($where as $key => $value) {
				if($inc == 1) {
					$sql .= $key ." = "."'".$value."' ";
				} else {
					$sql .= " AND " . $key ."="."'".$value."'";
				}
			}
		}
		if($order) {
			$sql .= " ORDER BY " . $order;
		}
		if($limit) {
			$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function categories_by_postid($where=array(), $order='', $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$sql = "SELECT ";
		$sql .= " * ";
		$sql .= "FROM " . DB_PREFIX . "blog_post_to_category ";
		$inc = 1;
		if(is_array($where) && !empty($where)) {
			$sql .= "WHERE ";
			foreach ($where as $key => $value) {
				if($inc == 1) {
					$sql .= $key . $value;
				} else {
					$sql .= " AND " . $key . $value;
				}
				$inc++;
			}
		}

		if($order) {
			$sql .= " ORDER BY " . $order;
		}

		if($limit) {
			$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
		}

		// return $sql;
		$query = $this->db->query($sql);
		
		$category = array();

		if ($query->num_rows) {
			foreach ($query->rows as $key => $value) {
				$cateogory_info = $this->getCategory($value['category_id']);
				$category['category_id'] = $cateogory_info['category_id'];
				$category['name'] = $cateogory_info['name'];
			}
		}

		return $category;
	}

	public function getTotalCategories($data = array()) {

		$sql = "SELECT COUNT(DISTINCT c.category_id) AS total FROM " . DB_PREFIX . "blog_category c LEFT JOIN " . DB_PREFIX . "blog_category_description cd ON (c.category_id = cd.category_id)";

		$sql .= " WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND c.status = '" . $data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}