<?php
class ModelModuleEnquiry extends Model {
	public function addenquiry($data = array()){
		$this->db->query("INSERT INTO ".DB_PREFIX."enquiry SET query = '".$data['query']."', user_info = '".$data['user_info']."', file = '".$data['file']."', status = 1");
		
	}
}