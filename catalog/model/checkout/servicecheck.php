<?php
class ModelCheckoutServicecheck extends Model {
	public function checkpostcode($data = array()) {
		if (isset ( $data ['postcode'] ) && $data ['postcode']) {
			$query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "postcode` WHERE postcode=" . ( int ) $data ['postcode'] );
			return $query->row;
		}
	}
}