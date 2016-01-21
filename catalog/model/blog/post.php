<?php
class ModelBlogPost extends Model {
	public function getTotalPost($data = array(), $where = array()) {
		$sql = "SELECT COUNT(DISTINCT p.ID) AS total";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "blog_category_path cp LEFT JOIN " . DB_PREFIX . "blog_post_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "blog_post_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "blog_post_filter pf ON (p2c.post_id = pf.post_id) LEFT JOIN " . DB_PREFIX . "blog_post p ON (pf.post_id = p.ID)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "blog_post p ON (p2c.post_id = p.ID)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "blog_post p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.ID = pd.post_id) LEFT JOIN " . DB_PREFIX . "blog_post_to_store p2s ON (p.ID = p2s.post_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.post_status = 'publish' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_title']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_title'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_title'])));

				foreach ($words as $word) {
					$implode[] = "pd.title LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.content LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
				}
			}

			if (!empty($data['filter_title']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
			}

			$sql .= ")";
		}

		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function getPost($where=array()) {			

		$sql = "SELECT DISTINCT *, pd.title, p.post_thumb";
		$sql .= " FROM " . DB_PREFIX . "blog_post p ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.ID = pd.post_id)";
		$sql .= " LEFT JOIN " . DB_PREFIX . "blog_post_to_store p2s ON (p.ID = p2s.post_id)";
		$inc = 1;
		if(is_array($where) && !empty($where)) {
			$sql .= " WHERE ";
			foreach ($where as $key => $value) {
				if($inc == 1) {
					$sql .= $key . $value;
				} else {
					$sql .= " AND " . $key . $value;
				}
				$inc++;
			}

			$sql .= " AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.post_status = 'publish' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		} else {
			$sql .= "WHERE ";
			$sql .= " pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.post_status = 'publish' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		}

		$query = $this->db->query($sql);

		if ($query->num_rows) {
			$post = $query->row;
			$postmeta = $this->postmeta(array("post_id"=>"='".$post['ID']."'"));
			foreach ($postmeta as $key => $value) {
				$post[$value['meta_key']][] = $value;
			}
			$post['link'] = $this->url->link('blog/single', 'post_id='.(int)$post['ID'],'SSL');
			return $post;
		} else {
			return array();
		}
	}

	public function getPosts($data=array(), $where=array()) {

		$sql = "SELECT p.ID";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "blog_category_path cp LEFT JOIN " . DB_PREFIX . "blog_post_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "blog_post_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "blog_post_filter pf ON (p2c.post_id = pf.post_id) LEFT JOIN " . DB_PREFIX . "blog_post p ON (pf.post_id = p.ID)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "blog_post p ON (p2c.post_id = p.ID)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "blog_post p";
		}	

		$sql .= " LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.ID = pd.post_id)";
		$sql .= " LEFT JOIN " . DB_PREFIX . "blog_post_to_store p2s ON (p.ID = p2s.post_id)";

		$inc = 1;
		if(is_array($where) && count($where) > 0) {
			$sql .= " WHERE ";
			foreach ($where as $key => $value) {
				if($inc == 1) {
					$sql .= $key . $value;
				} else {
					$sql .= " AND " . $key . $value;
				}
				$inc++;
			}

			$sql .= " AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.post_status = 'publish' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		} else {
			$sql .= " WHERE ";
			$sql .= " pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.post_status = 'publish' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		}

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_title']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_title'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_title'])));

				foreach ($words as $word) {
					$implode[] = "pd.title LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_content'])) {
					$sql .= " OR pd.content LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
				}
			}

			if (!empty($data['filter_title']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
			}

			$sql .= ")";
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

		$post_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$post_data[] = $this->getPost(array("p.ID" => "='".$result['ID']."'"));
		}

		return $post_data;
	}

	public function getPostLayoutId($post_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_post_to_layout WHERE post_id = '" . (int)$post_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function related_product($where=array(),$order='', $start = 0, $limit = 20) {
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
		if ($query->num_rows) {		
			return $query->rows;
		} else {
			return array();
		}
	}

	public function increaseView($post_id) {
		// print_r($post_id); die();
		$query = $this->db->query("UPDATE " . DB_PREFIX . "blog_post SET view = view+1 WHERE ID='$post_id' LIMIT 1");
		return $this->db->countAffected();
	}

	public function post_by_category($where=array(),$order='', $start = 0, $limit = 20) {
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
			$sql .= " AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			$sql .= " AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		} else {
			$sql .= "WHERE ";
			$sql .= " cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			$sql .= " pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		}

		if($order) {
			$sql .= " ORDER BY " . $order;
		}
		if($limit) {
			$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
		}
		// return $sql;
		$query = $this->db->query($sql);
		if ($query->num_rows) {		
			return $query->rows;
		} else {
			return array();
		}
	}

	public function getCategoryLayoutId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category_to_layout WHERE category_id = '" . (int)$category_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function postmeta($where=array(),$order='', $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$sql = "SELECT ";
		$sql .= " * ";
		$sql .= "FROM " . DB_PREFIX . "blog_postmeta pm ";
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
		if ($query->num_rows) {
			return $query->rows;
		} else {
			return array();
		}
	}

	public function post_tag($limit=10) {
		$posts = $this->getPosts();
		$tags = '';
		foreach ($posts as $key => $post) { 
			$tags .= $post['tag'].',';
		}
		$row_tags = explode(',', $tags);
		$tags = array_unique($row_tags);
		foreach ($tags as $key => $value) {
			if(!$tags[$key]) {
				unset($tags[$key]);
			}
		}
		return $limit > count($tags) ? $tags : array_slice($tags, $limit);
	}
}
