<?php
class ModelExtensionBlogComment extends Model {
	public function getComment($where=array()) {

		$sql = "SELECT ";
		$sql .= " * ";
		$sql .= "FROM " . DB_PREFIX . "blog_comment c ";
		$sql .= "LEFT JOIN " . DB_PREFIX . "blog_post p ON (c.comment_post_ID = p.ID) ";
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
			$sql .= " AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		} else {
			$sql .= "WHERE ";
			$sql .= " pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		}

		$query = $this->db->query($sql);
		if ($query->num_rows) {
			return $query->row;
		} else {
			return array();
		}
	}

	public function getComments($data=array(), $where=array()) {

		$sql = "SELECT ";
		$sql .= " * ";
		$sql .= "FROM " . DB_PREFIX . "blog_comment c ";
		$sql .= "LEFT JOIN " . DB_PREFIX . "blog_post p ON (c.comment_post_ID = p.ID) ";
		$sql .= "LEFT JOIN " . DB_PREFIX . "blog_post_description pd ON (p.ID = pd.post_id) ";
		$sql .= "LEFT JOIN " . DB_PREFIX . "user u ON (c.comment_author_id = u.user_id) ";
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
			$sql .= " AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		} else {
			$sql .= "WHERE ";
			$sql .= " pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		}

		if (!empty($data['filter_author'])) {
			$sql .= " AND u.username LIKE '" . $this->db->escape($data['filter_author']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status']) && in_array($data['filter_status'], array('publish','unpublish'))) {
			$sql .= " AND c.comment_approve = '" . $data['filter_status'] . "'";
		} 

		$sort_data = array(
			'c.comment_author',
			'c.comment_approve',
			'c.comment_date'
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

	public function getTotalComments($data = array()) {

		$sql = "SELECT COUNT(DISTINCT c.comment_ID) AS total FROM " . DB_PREFIX . "blog_comment c WHERE 1=1 ";

		if (!empty($data['filter_author'])) {
			$sql .= " AND c.comment_author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND c.comment_approve = '" . $data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}