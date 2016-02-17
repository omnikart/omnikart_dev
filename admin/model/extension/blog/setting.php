<?php
class ModelExtensionBlogSetting extends Model {
	private $affected_row = 0;
	public function total_settings() {
		$query = $this->db->query ( "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_setting" );
		return $query->row ['total'];
	}
	public function setting_general($where = array(), $order = '', $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 20;
		}
		$sql = "SELECT ";
		$sql .= " * ";
		$sql .= "FROM " . DB_PREFIX . "blog_setting_general ";
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
			return false;
		}
	}
	public function settings($where = array(), $order = '', $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 20;
		}
		$sql = "SELECT ";
		$sql .= " * ";
		$sql .= "FROM " . DB_PREFIX . "blog_setting ";
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
			return false;
		}
	}
	public function setting($where = array(), $order = '', $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 20;
		}
		$sql = "SELECT ";
		$sql .= " * ";
		$sql .= "FROM " . DB_PREFIX . "blog_setting ";
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
			return $query->row;
		} else {
			return false;
		}
	}
	public function editSetting($data) {
		$this->event->trigger ( 'pre.admin.setting.edit', $data );
		// print_r($data); die();
		
		if (is_array ( $data ['general_setting'] ) && ! empty ( $data )) {
			foreach ( $data ['general_setting'] as $key => $value ) {
				// print_r($value); die();
				foreach ( $value as $k => $v ) {
					// print_r($k); die();
					$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "blog_setting_general WHERE language_id = '" . ( int ) $key . "' AND setting_name = '" . $k . "'" );
					// print_r($check->num_rows); die();
					if (! $query->num_rows > 0) {
						$this->db->query ( "INSERT INTO " . DB_PREFIX . "blog_setting_general SET language_id = '" . ( int ) $key . "', setting_name = '" . $k . "', setting_value = '" . $this->db->escape ( $v ) . "'" );
					} else {
						$this->db->query ( "UPDATE " . DB_PREFIX . "blog_setting_general SET setting_value = '" . $this->db->escape ( $v ) . "' WHERE language_id = '" . ( int ) $key . "' AND setting_name = '" . $k . "' LIMIT 1" );
						if ($this->db->countAffected ()) {
							$this->affected_row ++;
						} else {
							$this->affected_row = $this->affected_row;
						}
					}
				}
			}
		}
		
		// print_r($this->affected_row); die();
		
		if (is_array ( $data ["setting"] ) && ! empty ( $data )) {
			// print_r($data['setting']); die();
			foreach ( $data ["setting"] as $key => $value ) {
				// print_r($value['name']); die();
				// $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_setting WHERE setting_name = '" . $k . "'");
				// if(!$query->num_rows > 0) {
				// $this->db->query("INSERT INTO " . DB_PREFIX . "blog_setting SET language_id = '" . (int)$key . "', setting_name = '" . $k . "', setting_value = '" . $this->db->escape($v) . "'");
				// } else {
				$this->db->query ( "UPDATE " . DB_PREFIX . "blog_setting SET setting_value = '" . $this->db->escape ( $value ['name'] ) . "', position = '" . $value ['position'] . "' WHERE setting_id = '" . ( int ) $key . "' LIMIT 1" );
				if ($this->db->countAffected ()) {
					$this->affected_row ++;
				} else {
					$this->affected_row = $this->affected_row;
				}
				// }
			}
		}
		return $this->affected_row;
	}
}