<?php
class NitroDb {
	public static $singleton;
	private $link = false;
	public static function getInstance() {
		if (empty ( self::$singleton ))
			self::$singleton = new NitroDb ();
		return self::$singleton->getLink ();
	}
	public function __construct() {
		$this->link = new NitroDBClass ( DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE );
	}
	public function getLink() {
		return $this->link;
	}
}
class NitroDBClass {
	private $db;
	public function __construct($driver, $hostname, $username, $password, $database) {
		$class = 'DB\\' . $driver;
		
		if (class_exists ( $class )) {
			$this->db = new $class ( $hostname, $username, $password, $database );
		} else {
			exit ( 'Error: Could not load database driver ' . $driver . '!' );
		}
	}
	public function query($sql) {
		return $this->db->query ( $sql );
	}
	public function escape($value) {
		return $this->db->escape ( $value );
	}
	public function countAffected() {
		return $this->db->countAffected ();
	}
	public function getLastId() {
		return $this->db->getLastId ();
	}
}