<?php
class ModelLocalisationPostcode extends Model {
	public function addCity($data) {
		$sql_initial_postcode = (isset ( $data ['initial_postcode'] )) ? ", initial_postcode = '" . $this->db->escape ( $data ['initial_postcode'] ) . "'" : "";
		$sql_final_postcode = (isset ( $data ['final_postcode'] )) ? ", final_postcode = '" . $this->db->escape ( $data ['final_postcode'] ) . "'" : "";
		$this->db->query ( "INSERT INTO " . DB_PREFIX . "city SET status = '" . ( int ) $data ['status'] . "', name = '" . $this->db->escape ( $data ['name'] ) . "', zone_id = '" . ( int ) $data ['zone_id'] . "', zone_code = '" . $this->db->escape ( $data ['zone_code'] ) . "', country_iso_code_2 = '" . $this->db->escape ( $data ['country_iso_code_2'] ) . "'" . $sql_initial_postcode . $sql_final_postcode );
		$this->cache->delete ( 'city' );
	}
	public function editCity($city_id, $data) {
		$sql_initial_postcode = (isset ( $data ['initial_postcode'] )) ? ", initial_postcode = '" . $this->db->escape ( $data ['initial_postcode'] ) . "'" : "";
		$sql_final_postcode = (isset ( $data ['final_postcode'] )) ? ", final_postcode = '" . $this->db->escape ( $data ['final_postcode'] ) . "'" : "";
		$this->db->query ( "UPDATE " . DB_PREFIX . "city SET status = '" . ( int ) $data ['status'] . "', name = '" . $this->db->escape ( $data ['name'] ) . "', zone_id = '" . ( int ) $data ['zone_id'] . "', zone_code = '" . $this->db->escape ( $data ['zone_code'] ) . "', country_iso_code_2 = '" . $this->db->escape ( $data ['country_iso_code_2'] ) . "' " . $sql_initial_postcode . $sql_final_postcode . " WHERE city_id = '" . ( int ) $city_id . "'" );
		
		$this->cache->delete ( 'city' );
	}
	public function deleteCity($city_id) {
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "city WHERE city_id = '" . ( int ) $city_id . "'" );
		
		$this->cache->delete ( 'city' );
	}
	public function getCity($city_id) {
		$query = $this->db->query ( "SELECT DISTINCT c.city_id, c.name, c.zone_id, c.initial_postcode, c.final_postcode, c.status,z.country_id FROM " . DB_PREFIX . "city c LEFT JOIN " . DB_PREFIX . "zone z ON (z.zone_id = c.zone_id) WHERE city_id = '" . ( int ) $city_id . "'" );
		
		return $query->row;
	}
	public function getPostcodes($data = array()) {
		$sql = "SELECT p.*,c.name as city,z.name as zone, ct.name as country FROM `" . DB_PREFIX . "postcode` p LEFT JOIN `" . DB_PREFIX . "city` c ON (c.city_id=p.city_id) LEFT JOIN `" . DB_PREFIX . "zone` z ON (z.zone_id=p.zone_id) LEFT JOIN `" . DB_PREFIX . "country` ct ON (ct.country_id=p.country_id) WHERE ";
		
		if (isset ( $data ['filter_status'] ) && ! is_null ( $data ['filter_status'] )) {
			$sql .= " p.status = '" . ( int ) $data ['filter_status'] . "' AND ";
		}
		
		if (isset ( $data ['filter_city'] ) && $data ['filter_city']) {
			$explode = array ();
			$sql .= " (";
			foreach ( explode ( ',', $data ['filter_city'], - 1 ) as $name ) {
				$implode [] = "c.name LIKE '%" . $this->db->escape ( $name ) . "%'";
			}
			if ($implode) {
				$sql .= " " . implode ( " OR ", $implode ) . "";
			}
			$sql .= ")";
		} elseif (isset ( $data ['filter_zone'] ) && $data ['filter_zone']) {
			$sql .= "z.zone_id = " . $data ['filter_zone'] . " ";
		} elseif (isset ( $data ['country'] ) && $data ['country']) {
			$sql .= "p.country_id = " . ( int ) $data ['country'] . ") ";
		} else {
			$sql .= "1";
		}
		
		if (isset ( $data ['sort'] )) {
			$sql .= " ORDER BY " . $data ['sort'];
		} else {
			$sql .= " ORDER BY p.postcode";
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
	}
	public function getPostcodesByCityId($city_id) {
		$postcode_data = $this->cache->get ( 'postcode.' . ( int ) $city_id );
		if (! $postcode_data) {
			$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "postcode WHERE (city_id='" . ( int ) $city_id . "' AND status = '1') ORDER BY postcode" );
			$postcode_data = $query->rows;
			$this->cache->set ( 'postcode.' . ( int ) $city_id, $postcode_data );
		}
		return $postcode_data;
	}
	public function getTotalPostcodes($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "postcode` p LEFT JOIN `" . DB_PREFIX . "city` c ON (c.city_id=p.city_id) LEFT JOIN `" . DB_PREFIX . "zone` z ON (z.zone_id=p.zone_id) LEFT JOIN `" . DB_PREFIX . "country` ct ON (ct.country_id=p.country_id) WHERE ";
		
		if (isset ( $data ['filter_status'] ) && ! is_null ( $data ['filter_status'] )) {
			$sql .= " p.status = '" . ( int ) $data ['filter_status'] . "' AND ";
		}
		
		if (isset ( $data ['filter_city'] ) && $data ['filter_city']) {
			$explode = array ();
			$sql .= " (";
			foreach ( explode ( ',', $data ['filter_city'], - 1 ) as $name ) {
				$implode [] = "c.name LIKE '%" . $this->db->escape ( $name ) . "%'";
			}
			if ($implode) {
				$sql .= " " . implode ( " OR ", $implode ) . "";
			}
			$sql .= ")";
		} elseif (isset ( $data ['filter_zone'] ) && $data ['filter_zone']) {
			$sql .= "z.zone_id = " . $data ['filter_zone'] . " ";
		} elseif (isset ( $data ['country'] ) && $data ['country']) {
			$sql .= "p.country_id = " . ( int ) $data ['country'] . ") ";
		} else {
			$sql .= "1";
		}
		
		$query = $this->db->query ( $sql );
		
		return $query->row ['total'];
	}
	public function getTotalCitiesByZoneId($zone_id) {
		$query = $this->db->query ( "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "city WHERE zone_id = '" . ( int ) $zone_id . "'" );
		
		return $query->row ['total'];
	}
	public function update($data = array()) {
		if ($data) {
			$selected = implode ( ',', $data ['selected'] );
			$this->db->query ( "UPDATE `" . DB_PREFIX . "postcode` SET `" . $this->db->escape ( $data ['action'] ) . "`='" . ( int ) $data ['action_value'] . "' WHERE postcode_id IN (" . $selected . ")" );
		}
	}
	public function install() {
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "postcode` (
			  	`postcode_id`  int(11) NOT NULL AUTO_INCREMENT ,
				`postcode`  varchar(64) NOT NULL ,
				`city_id`  int(11) NOT NULL ,
				`zone_id`  int(11) NOT NULL ,
				`country_id`  int(11) NOT NULL ,
				`payment`  tinyint(1) NOT NULL DEFAULT 0,
				`shipping`  tinyint(1) NOT NULL DEFAULT 0,
				`service`  tinyint(1) NOT NULL DEFAULT 0,
				`status`  tinyint(1) NOT NULL DEFAULT 1,
				PRIMARY KEY (`postcode_id`),
				INDEX `code` (`code`) USING BTREE,
				INDEX `city_id` (`city_id`) USING BTREE,
				INDEX `zone_id` (`zone_id`) USING BTREE,
				INDEX `country_id` (`country_id`) USING BTREE
				) DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 COMMENT='Pincode table module';" );
	}
	public function uninstall() {
		$this->db->query ( "DROP TABLE IF EXISTS `" . DB_PREFIX . "city`" );
	}
}