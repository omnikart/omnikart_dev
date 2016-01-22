<?php
class ModelExtensionBlogPost extends Model {
	public function getpost($post_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'post_id=" . (int)$post_id . "') AS keyword FROM " . DB_PREFIX . "blog_post p LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.ID = pd.post_id) WHERE p.ID = '" . (int)$post_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getposts($data=array(), $where=array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "blog_post p LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.ID = pd.post_id)  LEFT JOIN " . DB_PREFIX . "user u ON (p.post_author = u.user_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if(is_array($where) && !empty($where)) {
			foreach ($where as $key => $value) {
				$sql .= " AND " . $key . $value;
			}
		}

		if (!empty($data['filter_title'])) {
			$sql .= " AND pd.title LIKE '" . $this->db->escape($data['filter_title']) . "%'";
		}

		if (!empty($data['filter_author'])) {
			$sql .= " AND u.username LIKE '" . $this->db->escape($data['filter_author']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status']) && in_array($data['filter_status'], array('publish','unpublish'))) {
			$sql .= " AND p.post_status = '" . $data['filter_status'] . "'";
		}
		
		$sql .= " GROUP BY p.ID";

		$sort_data = array(
			'p.ID',
			'pd.title',
			'p.author',
			'p.status',
			'p.sort_order',
			'p.date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.title";
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

		if ($query->num_rows) {		
			return $query->rows;
		} else {
			return array();
		}
	}

	public function getPostByCategoryId($post_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_post p LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.ID = pd.post_id) LEFT JOIN " . DB_PREFIX . "blog_post_to_category p2c ON (p.ID = p2c.post_Id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.post_id = '" . (int)$post_id . "' ORDER BY pd.title ASC");

		return $query->rows;
	}

	public function getPostDescriptions($post_id) {
		$post_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_post_description WHERE post_id = '" . (int)$post_id . "'");

		foreach ($query->rows as $result) {
			$post_description_data[$result['language_id']] = array(
				'title'             => $result['title'],
				'content'      => $result['content'],
				'excerpt'       => $result['excerpt'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
			);
		}

		return $post_description_data;
	}

	public function getPostCategories($post_id) {
		$post_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_post_to_category WHERE post_id = '" . (int)$post_id . "'");

		foreach ($query->rows as $result) {
			$post_category_data[] = $result['category_id'];
		}

		return $post_category_data;
	}

	public function postmeta($post_id,$info=false) {
		$q = "SELECT * FROM " . DB_PREFIX . "blog_postmeta WHERE post_id = '" . (int)$post_id . "'";
		if($info) {
			$q .=  " AND meta_key='".$info."'"; 
		}

		$q .= " ORDER BY sort_order ASC";
		$query = $this->db->query($q);
		
		return $query->rows;
	}

	public function getPostFilters($post_id) {
		$post_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_post_filter WHERE post_id = '" . (int)$post_id . "'");

		foreach ($query->rows as $result) {
			$post_filter_data[] = $result['filter_id'];
		}

		return $post_filter_data;
	}

	public function getPostLayouts($post_id) {
		$post_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_post_to_layout WHERE post_id = '" . (int)$post_id . "'");

		foreach ($query->rows as $result) {
			$post_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $post_layout_data;
	}

	public function getPostImages($post_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_postmeta WHERE post_id = '" . (int)$post_id . "' AND meta_key = 'image' ORDER BY sort_order ASC");
		return $query->rows;
	}

	public function getPostStores($post_id) {
		$post_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_post_to_store WHERE post_id = '" . (int)$post_id . "'");

		foreach ($query->rows as $result) {
			$post_store_data[] = $result['store_id'];
		}

		return $post_store_data;
	}

	public function productByPostid($where=array(),$order='', $start = 0, $limit = 20) {
		
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$sql = "SELECT ";
		$sql .= " * ";
		$sql .= "FROM " . DB_PREFIX . "blog_related_product ";
		$inc = 1;
		if(is_array($where) && !empty($where)) {
			$sql .= "WHERE ";
			foreach ($where as $key => $value) {
				if($inc == 1) {
					$sql .= $key ." = "."'".$value."'";
				} else {
					$sql .= " AND " . $key ."="."'".$value."'";
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

		$query = $this->db->query($sql);
		if ($query->num_rows) {
			$product_id = array();
			foreach ($query->rows as $key => $value) {
				$product_id[] = $value['product_id'];
			}
			return $product_id;
		} else {
			return array();
		}
	}

	public function getTotalPost($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.ID) AS total FROM " . DB_PREFIX . "blog_post p LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.ID = pd.post_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_title'])) {
			$sql .= " AND pd.title LIKE '" . $this->db->escape($data['filter_title']) . "%'";
		}

		if (!empty($data['filter_author'])) {
			$sql .= " AND p.post_author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.post_status = '" . $data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
