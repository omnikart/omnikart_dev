<?php
class ExcelPortDB {
	public $db;
	public function __construct($db) {
		$this->db = $db;
	}
	public function query($query) {
		$temp = preg_replace ( '/[^a-zA-Z ]/', '', $query );
		
		$for_run = false;
		
		if (stripos ( $temp, 'SELECT' ) !== 0 && method_exists ( $this->db, 'run' )) {
			return $this->db->run ( $query );
		} else {
			return $this->db->query ( $query );
		}
	}
	public function escape($data) {
		return $this->db->escape ( $data );
	}
	public function getLastId() {
		return $this->db->getLastId ();
	}
}

if (! function_exists ( 'modification_vqmod' )) {
	function modification_vqmod($file) {
		if (class_exists ( 'VQMod' )) {
			return VQMod::modCheck ( modification ( $file ), $file );
		} else {
			return modification ( $file );
		}
	}
}