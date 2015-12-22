<?php
class ModelLocalisationCity extends Model {
	public function addCity($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "city SET status = '" . (int)$data['status'] . "', name = '" . $this->db->escape($data['name']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . $this->db->escape($data['country_id']) . "'");
		$this->cache->delete('city');
	}

	public function editCity($city_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "city SET status = '" . (int)$data['status'] . "', name = '" . $this->db->escape($data['name']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . $this->db->escape($data['country_id']) . "' WHERE city_id = '" . (int)$city_id . "'");

		$this->cache->delete('city');
	}

	public function deleteCity($city_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "city WHERE city_id = '" . (int)$city_id . "'");

		$this->cache->delete('city');	
	}

	public function getCity($city_id) {
		$query = $this->db->query("SELECT DISTINCT c.city_id, c.name, c.zone_id, c.initial_postcode, c.final_postcode, c.status,z.country_id FROM " . DB_PREFIX . "city c LEFT JOIN " . DB_PREFIX . "zone z ON (z.zone_id = c.zone_id) WHERE city_id = '" . (int)$city_id . "'");
		
		return $query->row;
	}

	public function getCities($data = array()) {
		$sql = "SELECT c.city_id, c.name, c.zone_id, z.name AS zone, 
						z.code, ct.name as country
					FROM " . DB_PREFIX . "city c 
					LEFT JOIN " . DB_PREFIX . "zone z ON (z.zone_id = c.zone_id)
					LEFT JOIN " . DB_PREFIX . "country ct ON (z.country_id = ct.country_id)
					WHERE c.status = '1'";
		
		$implode = array();
		if (isset($data['filter_name']) && $data['filter_name']) {
			$sql .= " AND (";
			foreach (explode(',',$data['filter_name']) as $name) {
				$implode[] = "c.name LIKE '%" . $this->db->escape($name) . "%'";
			}
			if ($implode) {
				$sql .= " " . implode(" AND ", $implode) . "";
			}
			$sql .= ")";
		}
		
		if (isset($data['filter_zone']) && $data['filter_zone']) {
			$sql .= " AND c.zone_id='".(int)$data['filter_zone']."' ";
		}
		
		
		$sort_data = array(
			'c.name',
			'z.name',
			'ct.name'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY c.name";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if(isset($data['sort']) && $data['sort'] != 'c.name'){
			$sql .= ", c.name ASC";
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

		return $query->rows;
	}

	public function getCitiesByZoneId($zone_id) {
		$city_data = $this->cache->get('city.' . (int)$zone_id);

		if (!$city_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "city WHERE zone_id = '" . (int)$zone_id . "' AND status = '1' ORDER BY name");

			$city_data = $query->rows;

			$this->cache->set('city.' . (int)$zone_id, $city_data);
		}

		return $city_data;
	}

	public function getTotalCities() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "city");

		return $query->row['total'];
	}

	public function getTotalCitiesByZoneId($zone_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "city WHERE zone_id = '" . (int)$zone_id . "'");

		return $query->row['total'];
	}

	public function install(){
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "city` (
			  `city_id`  int(11) NOT NULL AUTO_INCREMENT,
				`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
				`zone_id`  int(11) NOT NULL ,
				`zone_code`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
				`country_iso_code_2`  char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
				`status`  tinyint(1) NOT NULL DEFAULT 1 ,
				PRIMARY KEY (`city_id`),
				INDEX `WT11ESTADO` (`zone_id`) USING BTREE ,
				INDEX `WTCIDID` (`city_id`) USING BTREE 
				) DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci COMMENT='Cities table module';");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "city`");
	}
}
