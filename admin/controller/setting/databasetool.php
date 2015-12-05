<?php
class ControllerSettingDatabasetool extends Controller {
	public function updatetables(){
		if (!$this->user->hasPermission('modify', 'setting/databasetool')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		$sql = 	"CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customerpartner_product_to_address` (";
		$sql .= "`id` INT(11) NOT NULL,";
		$sql .= "`address_id` INT(11) NOT NULL DEFAULT '0',";
		$sql .= "PRIMARY KEY (`id`,`address_id`))";
		$sql .= "ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		
		$this->db->query($sql);
		
		$sql = 	"ALTER TABLE  `" . DB_PREFIX . "customerpartner_product_to_address`";
		$sql .= "DROP PRIMARY KEY,";
		$sql .= "ADD PRIMARY KEY(`id`,`address_id`),";
		$sql .= "CHANGE `id` `id` INT(11) NOT NULL;";
		
		
		$this->db->query($sql);
	}
}