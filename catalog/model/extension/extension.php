<?php
class ModelExtensionExtension extends Model {
	function getExtensions($type) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape ( $type ) . "'" );
		
		if ($type == 'shipping' || $type == 'payment') {
			if ($this->customer->isLogged()) {
				$customer_group_id = $this->customer->getGroupId();
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}

			$methods_query = $this->db->query("SELECT " . $type . "_methods FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int) $customer_group_id . "'");
			
			$available_methods =  unserialize($methods_query->row[$type . '_methods']);

			foreach ($query->rows as $key => $row) {
				if (!in_array($row['code'], $available_methods)) {
					unset($query->rows[$key]);
				}
			}
		}
		return $query->rows;
	}
}
