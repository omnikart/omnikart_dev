<?php
class ModelLocalisationUnitClass extends Model {
	public function install() {
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "unit_class_description` (
				`unit_class_id` int(11) NOT NULL AUTO_INCREMENT,
				`language_id` int(11) NOT NULL,
				`title` varchar(32) NOT NULL,
	   			`unit` varchar(4) NOT NULL,
	  			 PRIMARY KEY (`unit_class_id`,`language_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
		
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "unit_class` (
	  		`unit_class_id` int(11) NOT NULL AUTO_INCREMENT,
	  		`value` decimal(15,8) NOT NULL DEFAULT '0.00000000',
	  		PRIMARY KEY (`unit_class_id`))
	  		ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1" );
	}
	public function addUnitClass($data) {
		$this->db->query ( "INSERT INTO " . DB_PREFIX . "unit_class SET value = '" . ( float ) $data ['value'] . "'" );
		
		$unit_class_id = $this->db->getLastId ();
		
		foreach ( $data ['unit_class_description'] as $language_id => $value ) {
			$this->db->query ( "INSERT INTO " . DB_PREFIX . "unit_class_description SET unit_class_id = '" . ( int ) $unit_class_id . "', language_id = '" . ( int ) $language_id . "', title = '" . $this->db->escape ( $value ['title'] ) . "', unit = '" . $this->db->escape ( $value ['unit'] ) . "'" );
		}
		
		$this->cache->delete ( 'unit_class' );
	}
	public function editUnitClass($unit_class_id, $data) {
		$this->db->query ( "UPDATE " . DB_PREFIX . "unit_class SET value = '" . ( float ) $data ['value'] . "' WHERE unit_class_id = '" . ( int ) $unit_class_id . "'" );
		
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "unit_class_description WHERE unit_class_id = '" . ( int ) $unit_class_id . "'" );
		
		foreach ( $data ['unit_class_description'] as $language_id => $value ) {
			$this->db->query ( "INSERT INTO " . DB_PREFIX . "unit_class_description SET unit_class_id = '" . ( int ) $unit_class_id . "', language_id = '" . ( int ) $language_id . "', title = '" . $this->db->escape ( $value ['title'] ) . "', unit = '" . $this->db->escape ( $value ['unit'] ) . "'" );
		}
		
		$this->cache->delete ( 'unit_class' );
	}
	public function deleteUnitClass($unit_class_id) {
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "unit_class WHERE unit_class_id = '" . ( int ) $unit_class_id . "'" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "unit_class_description WHERE unit_class_id = '" . ( int ) $unit_class_id . "'" );
		
		$this->cache->delete ( 'unit_class' );
	}
	public function getUnitClasses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "unit_class wc LEFT JOIN " . DB_PREFIX . "unit_class_description wcd ON (wc.unit_class_id = wcd.unit_class_id) WHERE wcd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "'";
			
			$sort_data = array (
					'title',
					'unit',
					'value' 
			);
			
			if (isset ( $data ['sort'] ) && in_array ( $data ['sort'], $sort_data )) {
				$sql .= " ORDER BY " . $data ['sort'];
			} else {
				$sql .= " ORDER BY title";
			}
			
			if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
				if ($data ['start'] < 0) {
					$data ['start'] = 0;
				}
				
				if ($data ['limit'] < 1) {
					$data ['limit'] = 20;
				}
				
				$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
			}
			
			$query = $this->db->query ( $sql );
			
			return $query->rows;
		} else {
			$unit_class_data = $this->cache->get ( 'unit_class.' . ( int ) $this->config->get ( 'config_language_id' ) );
			
			if (! $unit_class_data) {
				$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "unit_class wc LEFT JOIN " . DB_PREFIX . "unit_class_description wcd ON (wc.unit_class_id = wcd.unit_class_id) WHERE wcd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "'" );
				
				$unit_class_data = $query->rows;
				
				$this->cache->set ( 'unit_class.' . ( int ) $this->config->get ( 'config_language_id' ), $unit_class_data );
			}
			
			return $unit_class_data;
		}
	}
	public function getUnitClass($unit_class_id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "unit_class wc LEFT JOIN " . DB_PREFIX . "unit_class_description wcd ON (wc.unit_class_id = wcd.unit_class_id) WHERE wc.unit_class_id = '" . ( int ) $unit_class_id . "' AND wcd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "'" );
		
		return $query->row;
	}
	public function getUnitClassDescriptionByUnit($unit) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "unit_class_description WHERE unit = '" . $this->db->escape ( $unit ) . "' AND language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "'" );
		
		return $query->row;
	}
	public function getUnitClassDescriptions($unit_class_id) {
		$unit_class_data = array ();
		
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "unit_class_description WHERE unit_class_id = '" . ( int ) $unit_class_id . "'" );
		
		foreach ( $query->rows as $result ) {
			$unit_class_data [$result ['language_id']] = array (
					'title' => $result ['title'],
					'unit' => $result ['unit'] 
			);
		}
		
		return $unit_class_data;
	}
	public function getTotalUnitClasses() {
		$query = $this->db->query ( "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "unit_class" );
		
		return $query->row ['total'];
	}
}