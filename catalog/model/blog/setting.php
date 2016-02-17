<?php
class ModelBlogSetting extends Model {
	private $affected_row = 0;
	public function settings($where = array(), $order = '', $start = 0, $limit = 1000) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 20;
		}
		$sql = "SELECT ";
		$sql .= " * ";
		$sql .= "FROM " . DB_PREFIX . "blog_setting";
		$inc = 1;
		if (is_array ( $where ) && ! empty ( $where )) {
			$sql .= "WHERE ";
			foreach ( $where as $key => $value ) {
				if ($inc == 1) {
					$sql .= $key . " = " . "'" . $value . "' ";
				} else {
					$sql .= " AND " . $key . "=" . "'" . $value . "'";
				}
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
			foreach ( $this->settings_general () as $key => $setting ) {
				$query->rows [] = $setting;
			}
			// print_r($this->settings_general()); die();
			return $query->rows;
		} else {
			return false;
		}
	}
	public function settings_general($where = array(), $order = '', $start = 0, $limit = 20) {
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
					$sql .= $key . " = " . "'" . $value . "' ";
				} else {
					$sql .= " AND " . $key . "=" . "'" . $value . "'";
				}
			}
			$sql .= " AND language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "'";
		} else {
			$sql .= "WHERE ";
			$sql .= " language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "'";
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
}