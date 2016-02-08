<?php
// -----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
// -----------------------------------------------------
class ModelNeTrack extends Model {
	public function add($data) {
		$query = $this->db->query ( "SELECT history_id, stats_personal_id FROM `" . DB_PREFIX . "ne_stats_personal` WHERE stats_personal_id = '" . ( int ) $data ['uid'] . "' AND email = '" . $this->db->escape ( $data ['email'] ) . "'" );
		if ($query->row) {
			$history_id = $query->row ['history_id'];
			$stats_personal_id = $query->row ['stats_personal_id'];
			
			$this->db->query ( "UPDATE " . DB_PREFIX . "ne_stats SET `views` = `views` + 1 WHERE history_id = '" . ( int ) $history_id . "'" );
			$this->db->query ( "UPDATE " . DB_PREFIX . "ne_stats_personal SET `views` = `views` + 1 WHERE stats_personal_id = '" . ( int ) $stats_personal_id . "'" );
			
			$this->db->query ( "INSERT INTO " . DB_PREFIX . "ne_stats_personal_views SET `stats_personal_id` = '" . ( int ) $stats_personal_id . "'" );
		}
	}
	public function click($data) {
		$query = $this->db->query ( "SELECT stats_personal_id FROM `" . DB_PREFIX . "ne_stats_personal` WHERE stats_personal_id = '" . ( int ) $data ['uid'] . "' AND email = '" . $this->db->escape ( $data ['email'] ) . "'" );
		if ($query->row) {
			$stats_personal_id = $query->row ['stats_personal_id'];
			$this->db->query ( "INSERT INTO " . DB_PREFIX . "ne_clicks SET `stats_personal_id` = '" . ( int ) $stats_personal_id . "', `url` = '" . $this->db->escape ( urldecode ( $data ['link'] ) ) . "', `kind` = '" . $this->db->escape ( $data ['kind'] ) . "'" );
		}
	}
}

?>