<?php
class ModelLocalisationUnitClass extends Model {
	public function getUnitClasses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "unit_class uc LEFT JOIN " . DB_PREFIX . "unit_class_description ucd ON (uc.unit_class_id = ucd.unit_class_id) WHERE ucd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sort_data = array(
				'title',
				'unit',
				'value'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY title";
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

			return $query->rows;
		} else {
			$unit_class_data = $this->cache->get('unit_class.' . (int)$this->config->get('config_language_id'));

			if (!$unit_class_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "unit_class uc LEFT JOIN " . DB_PREFIX . "unit_class_description ucd ON (uc.unit_class_id = ucd.unit_class_id) WHERE ucd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

				$unit_class_data = $query->rows;

				$this->cache->set('unit_class.' . (int)$this->config->get('config_language_id'), $unit_class_data);
			}

			return $unit_class_data;
		}
	}

	public function getUnitClass($unit_class_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "unit_class wc LEFT JOIN " . DB_PREFIX . "unit_class_description wcd ON (wc.unit_class_id = wcd.unit_class_id) WHERE wc.unit_class_id = '" . (int)$unit_class_id . "' AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
		return $query->row;
	}
	
}