<?php
class ModelLocalisationPaymentTerm extends Model {
	public function addPaymentTerms($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "payment_term SET name = '" . $this->db->escape($data['name']) . "',sort_order = '" . (int)$data['sort_order'] . "'");
		$this->cache->delete('payment_terms');
	}
	public function editPaymentTerms($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "payment_term SET name = '" . $this->db->escape($data['name']) . "',sort_order = '" . (int)$data['sort_order'] . "'");
		$this->cache->delete('payment_terms');
	}
	public function getPaymentTerms($data = array()) {
		if (!$this->cache->get('payment_terms')){
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "payment_term WHERE 1");
			$this->cache->set('payment_terms',$query->rows);
			return $query->rows;
		 } 
		return $this->cache->get('payment_terms');
	}
}