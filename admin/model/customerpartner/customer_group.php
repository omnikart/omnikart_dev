<?php 

class ModelCustomerpartnerCustomergroup extends Model
{	

	public function getCustomerGroupList($filterData) {
		$sql = "SELECT cpc.id,cpc.rights,cpc.status,cpcn.name,cpcn.description FROM ".DB_PREFIX."customerpartner_customer_group cpc LEFT JOIN ".DB_PREFIX."customerpartner_customer_group_name cpcn ON (cpc.id=cpcn.customer_group_id) WHERE cpcn.language_id = '".$this->config->get('config_language_id')."' ";
		if(isset($filterData['groupName']) && $filterData['groupName']) {
			$sql .= " AND cpcn.name like '".$this->db->escape($filterData['groupName'])."%' ";
		}

		if(isset($filterData['groupIsParent'])) {
			$sql .= " AND cpc.isParent = ".$filterData['groupIsParent']." ";
		}

		if(isset($filterData['groupRights']) && $filterData['groupRights']) {
			$sql .= " AND cpc.rights like '%".$this->db->escape($filterData['groupRights'])."%' ";
		}

		if(isset($filterData['groupStatus']) && $filterData['groupStatus']) {
			$sql .= " AND cpc.status = '".$this->db->escape($filterData['groupStatus'])."' ";
		}

		if(isset($filterData['sort']) && $filterData['sort']) {
			$sql .= " ORDER BY ".$filterData['sort']." ".$filterData['order'];
		}

		$sql .= " LIMIT ".$filterData['start'].", ".$filterData['limit'];
		
		$groupList = $this->db->query($sql)->rows;
		
		if($groupList) {
			return $groupList;
		} else {
			return false;
		}
	}

	public function getCustomerGroupListTotal($filterData) {
		$sql = "SELECT cpc.id,cpc.rights,cpc.status,cpcn.name,cpcn.description FROM ".DB_PREFIX."customerpartner_customer_group cpc LEFT JOIN ".DB_PREFIX."customerpartner_customer_group_name cpcn ON (cpc.id=cpcn.customer_group_id) WHERE cpcn.language_id = '".$this->config->get('config_language_id')."' ";
		if(isset($filterData['groupName']) && $filterData['groupName']) {
			$sql .= " AND cpcn.name like '".$this->db->escape($filterData['groupName'])."%' ";
		}

		if(isset($filterData['groupIsParent'])) {
			$sql .= " AND cpc.isParent = ".$filterData['groupIsParent']." ";
		}

		if(isset($filterData['groupRights']) && $filterData['groupRights']) {
			$sql .= " AND cpc.rights like '%".$this->db->escape($filterData['groupRights'])."%' ";
		}

		if(isset($filterData['groupStatus']) && $filterData['groupStatus']) {
			$sql .= " AND cpc.status = '".$this->db->escape($filterData['groupStatus'])."' ";
		}

		$groupList = $this->db->query($sql)->rows;
		
		if($groupList) {
			return count($groupList);
		} else {
			return 0;
		}
	}

	public function getCustomerGroupDetails($id) {
		$groupDetail = $this->db->query("SELECT * FROM ".DB_PREFIX."customerpartner_customer_group cpc LEFT JOIN ".DB_PREFIX."customerpartner_customer_group_name cpcn ON (cpc.id=cpcn.customer_group_id) WHERE cpc.id='".$id."' ORDER BY cpcn.language_id ASC ")->rows;
		
		if($groupDetail) {
			return $groupDetail;
		} else {
			return false;
		}

	}

	public function addCustomerGroupDetails($data) {
		$rights = '';

		$this->load->model('sale/customer_group');
		$this->model_sale_customer_group->addCustomerGroup($data['ocCustomerGroup']);

		$ocCustomerGroup = $this->db->query("SELECT customer_group_id FROM ".DB_PREFIX."customer_group ORDER BY customer_group_id DESC")->row;

		if(!isset($data['customerGroupParent'])) {
			$parentGroup = $data['customerGroupParentGroupId'];
		} else {
			$parentGroup = 0;
		}

		if(isset($data['customerGroupRights'])) {
			foreach ($data['customerGroupRights'] as $key => $right) {
				$rights .= $right.":";
			}
		}

		$this->db->query("INSERT INTO ".DB_PREFIX."customerpartner_customer_group VALUES ('".$ocCustomerGroup['customer_group_id']."','".rtrim($rights)."','".$parentGroup."','".$data['customerGroupStatus']."') ");

		foreach ($data['customerGroupName'] as $key => $value) {
			$this->db->query("INSERT INTO ".DB_PREFIX."customerpartner_customer_group_name VALUES ('', '".$ocCustomerGroup['customer_group_id']."','".$value['language_id']."','".$this->db->escape($value['name'])."','".$this->db->escape($data['customerGroupDescription'][$key]['description'])."' ) ");

		}

		$ocCustomerGroup = $this->db->query("SELECT customer_group_id FROM ".DB_PREFIX."customer_group cg LEFT JOIN ".DB_PREFIX."customerpartner_customer_group cpc ON (cg.customer_group_id=cpc.id) WHERE cpc.status = 'enable' ")->rows;

		$testArray = array();
		if($ocCustomerGroup) {
			foreach ($ocCustomerGroup as $key => $value) {
				$testArray[$key] = $value['customer_group_id'];
			}
		}

		$this->load->model("setting/setting");
		$this->model_setting_setting->editSettingValue('config','config_customer_group_display',$testArray);

	}

	public function editCustomerGroupDetails($data) {
		$rights = '';

		$this->load->model('sale/customer_group');
		
		$this->model_sale_customer_group->editCustomerGroup($data['customer_group_id'], $data['ocCustomerGroup']);

		if(isset($data['customerGroupParentGroupId']) && $data['customerGroupParentGroupId']) {
			$parentGroup = $data['customerGroupParentGroupId'];
		} else {
			$parentGroup = 0;
		}

		if(isset($data['customerGroupRights'])) {
			foreach ($data['customerGroupRights'] as $key => $right) {
				$rights .= $right.":";
			}
		}

		$this->db->query("UPDATE ".DB_PREFIX."customerpartner_customer_group SET rights='".$rights."',status='".$data['customerGroupStatus']."',isParent='".$parentGroup."' WHERE id='".$data['customer_group_id']."' ");

		foreach ($data['customerGroupName'] as $key => $value) {
			$this->db->query("UPDATE ".DB_PREFIX."customerpartner_customer_group_name SET name = '".$this->db->escape($value['name'])."',description='".$this->db->escape($data['customerGroupDescription'][$key]['description'])."' WHERE customer_group_id = '".$data['customer_group_id']."' AND language_id = '".$value['language_id']."' ");

		}

		if(!$this->config->get('marketplace_customerGroupStatus')) {
			$users = $this->db->query("SELECT id FROM ".DB_PREFIX."customerpartner_customer_group WHERE isParent = '".$data['customer_group_id']."' ")->rows;

			$customer_group_ids = $data['customer_group_id']."','";
			if($users) {
				foreach ($users as $key => $user) {
					$customer_group_ids .= $user['id']."','";
				}
			}

			$customer_group_ids = rtrim($customer_group_ids,"','");

			if($data['customerGroupStatus'] == 'disable') {
				$this->db->query("UPDATE ".DB_PREFIX."customer SET status = '0' WHERE customer_group_id IN ('".$customer_group_ids."') ");
			} else if($data['customerGroupStatus'] == 'enable') {
				$this->db->query("UPDATE ".DB_PREFIX."customer SET status = '1' WHERE customer_group_id IN ('".$customer_group_ids."') ");
			}
		}

		$ocCustomerGroup = $this->db->query("SELECT customer_group_id FROM ".DB_PREFIX."customer_group cg LEFT JOIN ".DB_PREFIX."customerpartner_customer_group cpc ON (cg.customer_group_id=cpc.id) WHERE cpc.status = 'enable' ")->rows;

		$configArray = array();
		if($ocCustomerGroup) {
			foreach ($ocCustomerGroup as $key => $value) {
				$configArray[$key] = $value['customer_group_id'];
			}
		}

		$this->load->model("setting/setting");
		$this->model_setting_setting->editSettingValue('config','config_customer_group_display',$configArray);

	}

	public function deleteGroup($id) {
		$this->db->query("DELETE FROM ".DB_PREFIX."customerpartner_customer_group WHERE id='".$id."' ");
		$this->db->query("DELETE FROM ".DB_PREFIX."customerpartner_customer_group_name WHERE customer_group_id='".$id."' ");
	}
}

?>