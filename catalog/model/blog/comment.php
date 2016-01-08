<?php
class ModelBlogComment extends Model {
	public function total_comment($where=array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_comment";

		if(is_array($where) && !empty($where)) {
			$sql .= " WHERE ";
			$inc = 1;
			foreach ($where as $key => $value) {
				if($inc == 1) {
					$sql .= $key . $value;
				} else {
					$sql .= " AND " . $key . $value;
				}
				$inc++;
			}
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function comments($where=array(),$order='',$start = 0,$limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}
		$sql = "SELECT ";
		$sql .= " * ";
		$sql .= "FROM " . DB_PREFIX . "blog_comment bc LEFT JOIN " . DB_PREFIX . "blog_post p ON (bc.comment_post_ID=p.ID)";	
		
		if(is_array($where) && !empty($where)) {
			$sql .= " WHERE ";
			$inc = 1;
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

		$query = $this->db->query($sql);
		if ($query->num_rows) {
			return $query->rows;
		} else {
			return array();
		}
	}

	public function comment($where=array(),$order='',$start = 0,$limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}
		$sql = "SELECT ";
		$sql .= " * ";
		$sql .= "FROM " . DB_PREFIX . "blog_comment bc RIGHT JOIN " . DB_PREFIX . "blog_post p ON bc.comment_post_ID=p.ID RIGHT JOIN " . DB_PREFIX . "blog_post_description pd ON p.ID=pd.post_id";	
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
			$sql .= " AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		} else {
			$sql .= " WHERE ";
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
			return $query->row;
		} else {
			return false;
		}
	}
}