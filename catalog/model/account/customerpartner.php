<?php
const MPIMAGEFOLDER = 'catalog/';

class ModelAccountCustomerpartner extends Model {


	public function updateSubCustomerGroup($customer_id, $customer_group_id) {
		$this->db->query("UPDATE ".DB_PREFIX."customer SET customer_group_id = '".$customer_group_id."' WHERE customer_id = '".$customer_id."' ");
	}

	public function closePreviousReview($customer_id,$order_id) {
		$result = $this->db->query("SELECT order_review_id FROM ".DB_PREFIX."customerpartner_order_review WHERE customer_id = '".$customer_id."' AND status = 'open' ")->row;
		if(isset($result['order_review_id'])) {
			$this->db->query("UPDATE ".DB_PREFIX."customerpartner_order_review SET status = 'close', order_id = ".(int)$order_id." WHERE customer_id = '".$customer_id."' ");
			return true;
		} else {
			return false;
		}
	}

	public function getApprovedProducts($customer_id) {
		$result = $this->db->query("SELECT * FROM ".DB_PREFIX."customerpartner_order_review WHERE customer_id = '".$customer_id."' AND status = 'open' ")->row;
		if($result) {
			return $result;
		} else {
			return false;
		}
	}

	public function getProductsToApprove($customer_id) {
		$reviewResult = $this->db->query("SELECT * FROM ".DB_PREFIX."customerpartner_order_review WHERE customer_id = '".$customer_id."' AND status = 'open' ");
		if($reviewResult) {
			$customerCart = unserialize($reviewResult->row['customer_cart']);
			$approveCart = unserialize($reviewResult->row['approve_cart']);
			$disapproveCart = unserialize($reviewResult->row['disapprove_cart']);
			$returnArray = array();
			$currentCart = $this->session->data['cart'];
			$productsDetails = array();
			if($customerCart) {
				foreach ($customerCart as $key => $value) {
					$product = unserialize(base64_decode($key));
					$result = $this->db->query("SELECT * FROM ".DB_PREFIX."product p LEFT JOIN ".DB_PREFIX."product_description pd ON (pd.product_id=p.product_id) WHERE pd.language_id = '".$this->config->get('config_language_id')."' AND p.product_id='".$product['product_id']."' ")->row;
					if($approveCart && array_key_exists($key, $approveCart)) {
						$approved = true;
					} else {
						$approved = false;
					}
					if($disapproveCart && array_key_exists($key, $disapproveCart)) {
						$disapproved = true;
					} else {
						$disapproved = false;
					}
					$productsDetails[] = array(
						'key' => $key,
						'order_review_id' => $reviewResult->row['order_review_id'],
						'product_id' => $product['product_id'],
						'name' => $result['name'],
						'model' => $result['model'],
						'quantity' => $value,
						'approved' => $approved,
						'disapproved' => $disapproved,
					);
				}
				return $productsDetails;
			}
		} else {
			return false;
		}
	}

	public function approveProductToBuy($data) {
		$alreadyApproved = $this->db->query("SELECT approve_cart,disapprove_cart FROM ".DB_PREFIX."customerpartner_order_review WHERE customer_id='".$data['customer_id']."' AND order_review_id = '".$data['order_review_id']."' ")->row;
		$approved = array();
		$dcarts = unserialize($alreadyApproved['disapprove_cart']);
		foreach ($dcarts as $product => $dcart) {
			if (isset($data['select'][$product])) unset($dcarts[$product]);
		}
		if(isset($alreadyApproved['approve_cart']) && $alreadyApproved['approve_cart']) {
			$approved = unserialize($alreadyApproved['approve_cart']);
			if (!is_array($approved)) $approved = array();
		}
		$resultData = array_merge($approved,$data['select']);
		$this->db->query("UPDATE ".DB_PREFIX."customerpartner_order_review SET approve_cart = '".serialize($resultData)."', disapprove_cart = '".serialize($dcarts)."' WHERE order_review_id = '".$data['order_review_id']."' ");
	}
	
	public function disapproveProductToBuy($data) {
		$alreadyDisapproved = $this->db->query("SELECT approve_cart,disapprove_cart FROM ".DB_PREFIX."customerpartner_order_review WHERE customer_id='".$data['customer_id']."' AND order_review_id = '".$data['order_review_id']."' ")->row;
		$disapproved = array();
		$acarts = unserialize($alreadyDisapproved['approve_cart']);
		foreach ($acarts as $product => $acart) {
			if (isset($data['select'][$product])) unset($acarts[$product]);
		}
		if (empty($acarts)) {
			$acarts = array();
		}
		if (isset($alreadyDisapproved['disapprove_cart']) && $alreadyDisapproved['disapprove_cart']) {
			$disapproved = unserialize($alreadyDisapproved['disapprove_cart']);
			if (!is_array($disapproved)) $disapproved = array();
		}
		$resultData = array_merge($disapproved,$data['select']);
		$this->db->query("UPDATE ".DB_PREFIX."customerpartner_order_review SET disapprove_cart = '".serialize($resultData)."', approve_cart = '".serialize($acarts)."' WHERE order_review_id = '".$data['order_review_id']."' ");
	}
	
	public function getReviewRequest($admin_id) {
		$requests = $this->db->query("SELECT * FROM ".DB_PREFIX."customerpartner_employee_mapping cpem LEFT JOIN ".DB_PREFIX."customerpartner_order_review cpor ON (cpem.employee_id=cpor.customer_id) LEFT JOIN ".DB_PREFIX."customer c ON (c.customer_id=cpor.customer_id) WHERE cpor.admin_id = '".$admin_id."' AND cpor.status='open' ")->rows;
		
		if($requests) {
			return $requests;
		} else {
			return false;
		}
	}

	public function sendForReview($data) {
		if(isset($data['customer_id'])) {
			$customer_id = $data['customer_id'];
		} else {
			$customer_id = $this->customer->getId();
		}
		
		$data['admin_id'] = $this->getusermanager();
		
		$alreadyApproved = $this->db->query("SELECT customer_cart FROM ".DB_PREFIX."customerpartner_order_review WHERE customer_id='".$customer_id."' AND status = 'open' ")->row;
		
		$approved = array();
		if(isset($alreadyApproved['customer_cart']) && $alreadyApproved['customer_cart']) {
			$approved = unserialize($alreadyApproved['customer_cart']);
		}
		$data['selected'] = array_merge($approved,$data['selected']);
		if($alreadyApproved) {
			$this->db->query("UPDATE ".DB_PREFIX."customerpartner_order_review SET customer_cart = '".serialize($data['selected'])."' WHERE customer_id = '".$customer_id."' ");
		} else {
			$this->db->query("INSERT INTO ".DB_PREFIX."customerpartner_order_review VALUES ('',0,'".$customer_id."','".$data['admin_id']."','".serialize($data['selected'])."', '', '','open' ) ");
		}
		

		
		$text = '';
		
		$order_review_id = $this->db->query("SELECT order_review_id FROM ".DB_PREFIX."customerpartner_order_review WHERE customer_id = '".$customer_id."'")->row['order_review_id'];
		
		$this->load->model('account/customer');
		$requestor = $this->model_account_customer->getCustomer($customer_id);
		echo $this->getusermanager();
		$admin = $this->model_account_customer->getCustomer($this->getusermanager());
		
		$subject = "Product Approval Request - ".$order_review_id;
		
		$text = "Product Name\tModel\tQuantity\tApproved\tDisapproved\n";

		foreach ($data['selected'] as $key => $value) {
			$product = unserialize(base64_decode($key));
			$result = $this->db->query("SELECT * FROM ".DB_PREFIX."product p LEFT JOIN ".DB_PREFIX."product_description pd ON (pd.product_id=p.product_id) WHERE pd.language_id = '".$this->config->get('config_language_id')."' AND p.product_id='".$product['product_id']."' ")->row;
			$text .= $result['name']."\t".$result['model']."\t".$value."\tNo\tNo\n";
		}		
		
		$message = "Dear ".$requestor['firstname']." ".$requestor['lastname'].",\n";
		$message .= $text;
		$message .= "Please wait for Mr/Ms ".$admin['firstname']." ".$admin['lastname']." to approve it";
		
		$mail = $this->mail_init();
		$mail->setTo($requestor['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText($message);
		$mail->send();
		
		$message = "Dear ".$admin['firstname']." ".$admin['lastname'].",\n";
		$message .= $text;
		$message .= "Please approve the above request from Mr/Ms ".$admin['firstname']." ".$admin['lastname'].".";
		
		$mail->setTo($admin['email']);
		$mail->setFrom($requestor['email']);
		$mail->setSender(html_entity_decode($requestor['firstname']." ".$requestor['lastname'], ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText($message);
		$mail->send();
	}

	public function deleteSubUser($customer_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customerpartner_employee_mapping WHERE employee_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");		
	}	

	public function disableSubUser($customer_id) {
		$checkStatus = $this->db->query("SELECT status FROM ".DB_PREFIX."customerpartner_employee_mapping WHERE employee_id = '".$customer_id."' ")->row;
		if($checkStatus && $checkStatus['status'] == '1') {
			$this->db->query("UPDATE ".DB_PREFIX."customerpartner_employee_mapping SET status = '0' WHERE employee_id = '".$customer_id."' ");
			$this->db->query("UPDATE ".DB_PREFIX."customer SET status = '0' WHERE customer_id = '".$customer_id."' ");
			return true;
		} else {
			return false;
		}
	}

	public function enableSubUser($customer_id) {
		$checkStatus = $this->db->query("SELECT status FROM ".DB_PREFIX."customerpartner_employee_mapping WHERE employee_id = '".$customer_id."' ")->row;
		if($checkStatus && $checkStatus['status'] == '0') {
			$this->db->query("UPDATE ".DB_PREFIX."customerpartner_employee_mapping SET status = '1' WHERE employee_id = '".$customer_id."' ");
			$this->db->query("UPDATE ".DB_PREFIX."customer SET status = '1' WHERE customer_id = '".$customer_id."' ");
			return true;
		} else {
			return false;
		}
	}

	public function isSubUser($customer_id) {
		$user = $this->db->query("SELECT seller_id FROM ".DB_PREFIX."customerpartner_employee_mapping WHERE employee_id = '".$customer_id."' AND status = '1' ")->row;
		if($user) {
			return $user['seller_id'];
		} else {
			return false;
		}
	}
	/*check*/
	public function getUserList($customer_id) {
		$userList = $this->db->query("SELECT c.*,cpem.*,c2m.manager_id,c2m.p_limit FROM ".DB_PREFIX."customer c LEFT JOIN ".DB_PREFIX."customerpartner_employee_mapping cpem ON (c.customer_id=cpem.employee_id) LEFT JOIN ".DB_PREFIX."customer_to_manager c2m ON (c.customer_id=c2m.employee_id) WHERE cpem.seller_id='".$customer_id."' ")->rows;
		if($userList) {
			return $userList;
		} else {
			return false;
		}
	}
	/*check*/
	public function getAllUserList($customer_id) {
		$seller_id = $this->getuserseller();
		$userList = $this->db->query("SELECT c.*,c2m.p_limit,c2m.manager_id FROM ".DB_PREFIX."customer c LEFT JOIN ".DB_PREFIX."customerpartner_employee_mapping cpem ON (c.customer_id=cpem.employee_id) LEFT JOIN ".DB_PREFIX."customer_to_manager c2m ON (c.customer_id=c2m.employee_id) WHERE cpem.seller_id='".$seller_id."' ")->rows;
		if($userList) {
			return $userList;
		} else {
			return false;
		}
	}/*check*/	
	
	public function addSellerCustomerMapping($customer_id) {
		$seller_id = $this->getuserseller();
		$this->db->query("INSERT INTO ".DB_PREFIX."customerpartner_employee_mapping VALUES ('','".$seller_id."','".$customer_id."','1')	 ");
		return $this->db->getLastId();
	}

	public function getCustomerGroupRights($customer_group_id) {
		$customerRights = $this->db->query("SELECT cpc.rights,cpc.isParent,cpcn.name,cpcn.description FROM ".DB_PREFIX."customerpartner_customer_group cpc LEFT JOIN ".DB_PREFIX."customerpartner_customer_group_name cpcn ON (cpc.id=cpcn.customer_group_id) WHERE cpc.id = '".$customer_group_id."' AND cpcn.language_id = '".$this->config->get('config_language_id')."' ")->row;
		if ($customerRights) {
			$customerRightsExploded = explode(':', rtrim($customerRights['rights'],":"));
			foreach ($customerRightsExploded as $key => $value) {
				$rightsArray[$value] = str_replace('-', ' ', $value);
			}
			$returnArray['rights'] = $rightsArray;
			$returnArray['isParent'] = $customerRights['isParent'];
			$returnArray['name'] = $customerRights['name'];
			$returnArray['description'] = $customerRights['description'];
			return $returnArray;
		} else {
			return false;
		}
	}

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
	
	public function getSellerDetails() {
		$query_detail = $this->db->query("SELECT * FROM ".DB_PREFIX."seller_group sg LEFT JOIN ".DB_PREFIX."seller_group_name sgn ON (sg.groupid=sgn.id) ")->rows;
		return $query_detail;
	}

	public function getRamainingQuantity($seller_id){
		$products = $this->getTotalProductsSeller();
		$inStock = $this->db->query("SELECT gcquantity FROM ".DB_PREFIX."seller_group_customer WHERE customer_id = '".$seller_id."' ")->row;
		if(isset($inStock['gcquantity']) && $inStock['gcquantity'] > $products) {
			return ($inStock['gcquantity'] - $products);
		}
	}
	public function chkSellerPoductAccess($product_id,$seller_id){
		if(!$seller_id) {
			$seller_id = $this->customer->getId();
		}
		$sql = $this->db->query("SELECT c2p.customer_id FROM ".DB_PREFIX ."customerpartner_to_product c2p LEFT JOIN ".DB_PREFIX ."product p ON (c2p.product_id = p.product_id) WHERE p.product_id = '".(int)$product_id."' AND c2p.customer_id = '".(int)$seller_id."'");

		if($sql->row){
				return true;
		}else{
			return false;
		}
	}

	public function getProductKeyword($product_id){	
		$result = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'")->row;

		if($result)
			return $result['keyword'];
	}

	// Productlist
	public function getProductSoldQuantity($product_id,$seller_id){
		$sql = $this->db->query("SELECT SUM(c2o.quantity) quantity, SUM(c2o.price) total FROM ".DB_PREFIX ."customerpartner_to_order c2o LEFT JOIN ".DB_PREFIX ."customerpartner_to_product c2p ON (c2o.product_id = c2p.product_id AND c2o.customer_id = c2p.customer_id ) WHERE c2o.customer_id = '".$seller_id."' and c2p.product_id = '".(int)$product_id."'");
		return($sql->row);
	}
	
	public function getProduct($product_id,$vendor_id = 0) {

		if ($vendor_id) $vendor_id = $vendor_id;
		else $vendor_id = $this->getuserseller();


		$query = $this->db->query("SELECT DISTINCT p.product_id, pd.name AS name, pd.description, pd.meta_title, pd.meta_description, pd.meta_keyword, pd.tag, p.model, cp2p.sku, p.upc, p.ean, p.jan, p.isbn, p.mpn, p.location, cp2p.quantity, IFNULL((SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = cp2p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "'), 'Not in Stock') AS stock_status,
		p.image, p.manufacturer_id, m.name AS manufacturer, IFNULL((SELECT cppd.price FROM " . DB_PREFIX . "cp_product_discount cppd WHERE (cppd.id = cp2p.id) AND (cppd.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AND (cppd.quantity = '1') AND ((cppd.date_start = '0000-00-00' OR cppd.date_start < NOW()) AND (cppd.date_end = '0000-00-00' OR cppd.date_end > NOW())) ORDER BY cppd.priority ASC, cppd.price ASC LIMIT 1),cp2p.price) AS cprice, p.price, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, p.points, p.tax_class_id, cp2p.date_available, IFNULL((cp2p.weight),p.weight) AS weight, IFNULL((cp2p.weight_class_id),p.weight_class_id) AS weight_class_id, IFNULL((cp2p.length),p.length) AS length, IFNULL((cp2p.width),p.width) AS width, IFNULL((cp2p.height),p.height) AS height, IFNULL((cp2p.length_class_id),p.length_class_id) AS length_class_id, p.subtract, (SELECT ROUND(AVG(rating)) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, IFNULL((SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id),0) AS reviews, IFNULL(cp2p.minimum, p.minimum) AS minimum, p.sort_order, cp2p.status AS status, cp2p.date_added, cp2p.date_modified, cp2p.viewed, cp2p.shipping, cp2p.stock_status_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN ".DB_PREFIX."customerpartner_to_product cp2p ON (p.product_id = cp2p.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND cp2p.customer_id = " . (int)$vendor_id . " AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		if ($query->num_rows) {
			return array(
				'product_id'       => $query->row['product_id'],//
				'name'             => $query->row['name'],//
				'description'      => $query->row['description'],//
				'meta_title'       => $query->row['meta_title'],//
				'meta_description' => $query->row['meta_description'],//
				'meta_keyword'     => $query->row['meta_keyword'],//
				'tag'              => $query->row['tag'],//
				'model'            => $query->row['model'],//
				'sku'              => $query->row['sku'],//
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],//
				'location'         => $query->row['location'],//
				'quantity'         => $query->row['quantity'],//
				'stock_status'     => $query->row['stock_status'],//
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],//
				'price'            => $query->row['cprice'],//
				'original_price'   => $query->row['price'],//
				'special'          => $query->row['special'], //
				'reward'           => $query->row['reward'], //
				'points'           => $query->row['points'], //
				'tax_class_id'     => $query->row['tax_class_id'], //
				'date_available'   => $query->row['date_available'], //
				'weight'           => $query->row['weight'], //
				'weight_class_id'  => $query->row['weight_class_id'], //
				'length'           => $query->row['length'], //
				'width'            => $query->row['width'], //
				'height'           => $query->row['height'], //
				'length_class_id'  => $query->row['length_class_id'], //
				'subtract'         => $query->row['subtract'], //
				'rating'           => round($query->row['rating']), //
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0, //
				'minimum'          => $query->row['minimum'], //
				'sort_order'       => $query->row['sort_order'],//
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed'],

				//for mp
				'shipping'         => $query->row['shipping'],
				'stock_status_id'  => $query->row['stock_status_id'],
			);
		} else {
			return false;
		}
	}

	public function getProductsSeller($data = array()) {

		$sql = "SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "customerpartner_to_product c2p ON (c2p.product_id = p.product_id) LEFT JOIN ".DB_PREFIX."product_to_store p2s ON (p.product_id = p2s.product_id)";

		if (isset($data['filter_category_id']) AND $data['filter_category_id']) {
			$sql .= " LEFT JOIN " . DB_PREFIX ."product_to_category p2c ON (p.product_id = p2c.product_id)";
		}

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 

		if (isset($data['filter_category_id']) AND $data['filter_category_id']) {		
			$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
		}

		if (isset($data['filter_name']) AND !empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_model']) AND !empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) AND !empty($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
		}

		if (isset($data['filter_store']) && !is_null($data['filter_store'])) {
			$sql .= " AND p2s.store_id = '" . (int)$data['filter_store'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if(!isset($data['customer_id']) || !$data['customer_id'])
			$sql .= " AND c2p.customer_id = ". $this->getuserseller() ;
		else		
			$sql .= " AND c2p.customer_id = ". (int)$data['customer_id'] ;

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pd.name";	
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

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id'],$data['customer_id']);
		}
		return $product_data;
	}

	public function getProductsAddSeller($data = array()) {
		
		$sql = "SELECT p.product_id,pd.name,p.model,p.price,p.image";
	
		if (!empty($data['filter_category_id'])) {
			$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}
	
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ".DB_PREFIX."product_to_store p2s ON (p.product_id = p2s.product_id)";
		
		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (isset($data['filter_category_id']) AND $data['filter_category_id']) {
			$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
		}
		if (isset($data['filter_name']) AND !empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}
		if (isset($data['filter_model']) AND !empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}
		if (isset($data['filter_store']) && !is_null($data['filter_store'])) {
			$sql .= " AND p2s.store_id = '" . (int)$data['filter_store'] . "'";
		}
		if(!isset($data['customer_id']) || !$data['customer_id'])
			$sql .= " AND (p.product_id NOT IN (SELECT c2p.product_id FROM " . DB_PREFIX . "customerpartner_to_product c2p WHERE c2p.customer_id = '". $this->getuserseller()."'))" ;
		else
			$sql .= " AND (p.product_id NOT IN (SELECT c2p.product_id FROM " . DB_PREFIX . "customerpartner_to_product c2p WHERE c2p.customer_id = '". (int)$data['customer_id']."'))" ;
		
		$sql .= " GROUP BY p.product_id";		
		$sql .= " ORDER BY pd.name";
		$sql .= " ASC";
		$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		$query = $this->db->query($sql);
		
		$product_data = array();
		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = array(
						'product_id' => $result['product_id'],
						'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
						'model'      => $result['model'],
						'price'      => $result['price'],
						'image'      => $result['image'],
						'category'	 => $this->getProductCategories($result['product_id'])
				);
		}
		
		return $product_data;
	}
	
	public function getTotalProductsSeller($data = array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "customerpartner_to_product c2p ON (c2p.product_id = p.product_id) LEFT JOIN ".DB_PREFIX."product_to_store p2s ON (p.product_id = p2s.product_id)";

		if (isset($data['filter_category_id']) AND $data['filter_category_id']) {
			$sql .= " LEFT JOIN " . DB_PREFIX ."product_to_category p2c ON (p.product_id = p2c.product_id)";
		}

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 

		if (isset($data['filter_category_id']) AND $data['filter_category_id']) {		
			$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
		}

		if (isset($data['filter_name']) AND !empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_model']) AND !empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_store']) && !is_null($data['filter_store'])) {
			$sql .= " AND p2s.store_id = '" . (int)$data['filter_store'] . "'";
		}

		if (isset($data['filter_price']) AND !empty($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if(!isset($data['customer_id']) || !$data['customer_id'])
			$sql .= " AND c2p.customer_id = ". $this->getuserseller() ;
		else		
			$sql .= " AND c2p.customer_id = ". (int)$data['customer_id'] ;

		$query = $this->db->query($sql);

		return count($query->rows);
	}

	public function getTotalAddProductsSeller($data = array()) {
	
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ".DB_PREFIX."product_to_store p2s ON (p.product_id = p2s.product_id)";
		
		if (isset($data['filter_category_id']) AND $data['filter_category_id']) {
			$sql .= " LEFT JOIN " . DB_PREFIX ."product_to_category p2c ON (p.product_id = p2c.product_id)";
		}
		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		if (isset($data['filter_category_id']) AND $data['filter_category_id']) {
			$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
		}
		if (isset($data['filter_name']) AND !empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}
		if (isset($data['filter_model']) AND !empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}
		if (isset($data['filter_store']) && !is_null($data['filter_store'])) {
			$sql .= " AND p2s.store_id = '" . (int)$data['filter_store'] . "'";
		}
		if(!isset($data['customer_id']) || !$data['customer_id'])
			$sql .= " AND (p.product_id NOT IN (SELECT c2p.product_id FROM " . DB_PREFIX . "customerpartner_to_product c2p WHERE c2p.customer_id = '". $this->getuserseller()."'))" ;
		else
			$sql .= " AND (p.product_id NOT IN (SELECT c2p.product_id FROM " . DB_PREFIX . "customerpartner_to_product c2p WHERE c2p.customer_id = '". (int)$data['customer_id']."'))" ;
		
		$sql .= " GROUP BY p.product_id";		
		$sql .= " ORDER BY pd.name";
		$sql .= " ASC";
		$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
	
		$query = $this->db->query($sql);

		return $query->row?$query->row['total']:0;
	}
	
	
	
	public function deleteProduct($product_id) {

		if($this->chkSellerPoductAccess($product_id)){

			$this->db->query("DELETE FROM " . DB_PREFIX . "customerpartner_to_product WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "cp_product_discount WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "cp_product_special WHERE product_id = '" . (int)$product_id . "'");

/*
			//if seller can delete product from store
			if($this->config->get('marketplace_sellerdeleteproduct')){   
       	            
				$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
         	}
*/
		}
		
	}

	public function addProduct($data,$sellerId) {

		if($sellerId) {
			$sellerId = $sellerId;
		} else {
			$sellerId = $this->getuserseller();
		}

		$data['status'] = $this->config->get('marketplace_productapprov');		

		$renamedImage = '';
		$renamedOImage = array();
		$files = $this->request->files;

		if (isset($files['image']['name']) AND $files['image']['name']) {
			$renamedImage = rand(100000,999999) . basename(preg_replace('~[^\w\./\\\\]+~', '', $files['image']["name"]));
			move_uploaded_file($files["image"]["tmp_name"], DIR_IMAGE . MPIMAGEFOLDER .$renamedImage);
		}

		if (isset($files['product_image']['name']) AND $files['product_image']['name']) {
			foreach ($files['product_image']['name'] as $index => $product_image) {				
				$renamedImg = rand(100000,999999) . basename(preg_replace('~[^\w\./\\\\]+~', '', $product_image['image']));
				move_uploaded_file($files['product_image']["tmp_name"][$index]['image'], DIR_IMAGE . MPIMAGEFOLDER .$renamedImg);
				$renamedOImage[] = $renamedImg;
			}
		}
		
		/* product table */
		$sql = "INSERT INTO `" . DB_PREFIX . "product` SET ";
		$sql = $this->productQuery($sql,$data);
		$sql .= " date_added = NOW(), edit = '1' ";
		$this->db->query($sql);
		$product_id = $this->db->getLastId();
		
		/* customerpartner product table */
		$sql = "INSERT INTO `" . DB_PREFIX . "customerpartner_to_product` SET product_id = '".(int)$product_id."', customer_id = '".(int)$sellerId."', ";
		$sql = $this->cpproductQuery($sql,$data);
		$sql .= " date_added = NOW(), date_modified = NOW()";
		$this->db->query($sql);
		$cp_product_id = $this->db->getLastId();
		
		/* Product Image table */
		if($renamedImage){
			$this->db->query("UPDATE `" . DB_PREFIX . "product` SET image = '" . MPIMAGEFOLDER .$this->db->escape(html_entity_decode($renamedImage, ENT_QUOTES, 'UTF-8')) . "' WHERE product_id = '" . (int)$product_id . "'");
		}

		/* Product Additional Images table */
		if ($renamedOImage) {
			foreach ($renamedOImage as $index => $product_image) {				
				$this->db->query("INSERT INTO " . DB_PREFIX ."product_image SET product_id = '" . (int)$product_id . "', image = '" . MPIMAGEFOLDER . $this->db->escape(html_entity_decode($product_image, ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$data['product_image'][$index]['sort_order'] . "'");
			}
		}
		
		/* Product description, attributes, and options table */
		$this->productAddUpdate($product_id,$data,$sellerId,$cp_product_id);

		/* Supplier Discount */
		$this->db->query("DELETE FROM " . DB_PREFIX . "cp_product_discount WHERE id = '" . (int)$cp_product_id . "'");
		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "cp_product_discount SET id = '" . (int)$cp_product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}	
		
		$data = array(
			'name' => isset($data['product_description'][$this->config->get('config_language_id')]['name']) ? $data['product_description'][$this->config->get('config_language_id')]['name'] : '',
			'seller_id' => $sellerId,
        	'customer_id' => false,
        	'mail_id' => $this->config->get('marketplace_mail_product_request'),
        	'mail_from' => $this->config->get('marketplace_adminmail'),
        	'mail_to' => $this->customer->getEmail(),
        );

		$commission = $this->getSellerCommission($sellerId);

        $values = array(
        	'product_name' => $data['name'],
			'commission' => $commission."%",
        );

		//send maila after product added
        $this->load->model('customerpartner/mail');

		//add product mail to seller
        $this->model_customerpartner_mail->mail($data,$values);

        //add product mail end to admin
		$data['mail_id'] = $this->config->get('marketplace_mail_product_admin');
        $data['mail_from'] = $this->customer->getEmail();
        $data['mail_to'] = $this->config->get('marketplace_adminmail');

		if($this->config->get('marketplace_productaddemail')) 
        	$this->model_customerpartner_mail->mail($data,$values);
		
		return $product_id;
	}

	public function getSellerCommission($seller_id){
		$result = $this->db->query("SELECT commission FROM ".DB_PREFIX."customerpartner_to_customer WHERE customer_id = '".$seller_id."' AND is_partner = 1 ")->row;
		if(isset($result['commission'])) {
			return $result['commission'];
		} else {
			return false;
		}
	}

	public function editProduct($data,$sellerId) {

		if($sellerId) {
			$sellerId = $sellerId;
		} else {
			$sellerId = $this->getuserseller();
		}
		$add = false;
		
		if(!isset($this->request->get['product_id']))
			return;
		
		// $data['status'] = $this->config->get('marketplace_productapprov');		

		$product_id = $this->request->get['product_id'];
		
		if (array_key_exists('addproduct', $customerRights['rights']) && ($this->getProductEditAccess($product_id, $sellerId))){
			$add = true; 
		}
		
		if ($add) {
			$files = $this->request->files;

			$renamedImage = '';

			if (isset($files['image']['name']) AND $files['image']['name']) {
				$renamedImage = rand(100000,999999) . basename(preg_replace('~[^\w\./\\\\]+~', '', $files['image']["name"]));
				//upload product base image
				move_uploaded_file($files["image"]["tmp_name"], DIR_IMAGE . MPIMAGEFOLDER .$renamedImage);
				if(isset($data['image']) AND $data['image'] AND file_exists(DIR_IMAGE.$data['image']))
						unlink(DIR_IMAGE.$data['image']);
			}

			//to remove previous image from folder	
			if (isset($files['product_image']['name']) AND $files['product_image']['name']) {
				foreach ($files['product_image']['name'] as $index => $product_image) {				
					if($product_image['image']){
						$newImage = rand(100000,999999) . basename(preg_replace('~[^\w\./\\\\]+~', '', $product_image['image']));
						//upload product images
						move_uploaded_file($files['product_image']["tmp_name"][$index]['image'], DIR_IMAGE . MPIMAGEFOLDER .$newImage);

						if(isset($data['product_image'][$index]['image']) AND $data['product_image'][$index]['image'] AND file_exists(DIR_IMAGE.$data['product_image'][$index]['image']))
							unlink(DIR_IMAGE.$data['product_image'][$index]['image']);
						$data['product_image'][$index]['image'] = MPIMAGEFOLDER.$newImage;

					}
				}

			/* Product table */
			$sql = "UPDATE `" . DB_PREFIX . "product` SET ";
			$sql = $this->productQuery($sql,$data,true);
			$sql .= " date_modified = NOW() WHERE product_id = '".(int)$product_id."'";
			$this->db->query($sql);
			
			}

			/* Product Image table */
			if($renamedImage)
				$this->db->query("UPDATE `" . DB_PREFIX . "product` SET image = '" . MPIMAGEFOLDER .$this->db->escape(html_entity_decode($renamedImage, ENT_QUOTES, 'UTF-8')) . "' WHERE product_id = '" . (int)$product_id . "'");

			/* Product Additional Images table */
			$removeImages = $this->db->query("SELECT image FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'")->rows;
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
			if (isset($data['product_image']) AND $data['product_image']) {
				foreach ($data['product_image'] as $product_image) {
					if($product_image['image'])
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '".$product_image['sort_order']."'");
				}
			}else{
				//remove all images
				if($removeImages){
					foreach ($removeImages as $value) {
						if(file_exists(DIR_IMAGE.$value['image']))
							unlink(DIR_IMAGE.$value['image']);
					}
				}
			}

			/* Product description, attributes, and options table */		
			$this->productAddUpdate($product_id,$data);
		}
		
		/* customerpartner product table */
		$sql = "UPDATE `" . DB_PREFIX . "customerpartner_to_product` SET ";
		$sql = $this->cpproductQuery($sql,$data);
		$sql .= " date_modified = NOW() WHERE product_id = '".(int)$product_id."', customer_id = '".(int)$sellerId."' AND edit = '1'";
		$this->db->query($sql);
		$cp_product_id = $this->getProductCPId($product_id,$sellerId);
		
		/* Supplier Discount */
		$this->db->query("DELETE FROM " . DB_PREFIX . "cp_product_discount WHERE id = '" . (int)$cp_product_id . "'");
		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "cp_product_discount SET id = '" . (int)$cp_product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}			
	}

	public function updateProducts($data,$sellerId = 0){
		if (!$sellerId) $sellerId = $this->getuserseller();
		foreach($data['products'] as $product) {
			if (in_array($product['id'],$data['selected'])){
				$this->db->query("UPDATE `".DB_PREFIX."customerpartner_to_product` SET status = '".(int)$product['status']."',price = '".(float)$product['price']."',quantity = '".(int)$product['quantity']."',minimum = '".(int)$product['minimum']."' WHERE product_id = '".(int)$product['id']."' AND customer_id = '".(int)$sellerId."'");
			}			
		}
	}
	public function addProducts($data,$sellerId = 0){
		if (!$sellerId) $sellerId = $this->getuserseller();
		foreach($data['products'] as $product) {
			if (in_array($product['id'],$data['selected'])){
				$this->db->query("INSERT INTO ".DB_PREFIX."customerpartner_to_product SET customer_id = '".$sellerId."', product_id = '".$product['id']."', price = '".(float)$product['price']."', quantity = '".(int)$product['quantity']."', status = '0', sort_order = '1'");
			}
		}
	}
	public function disableProducts($data,$sellerId = 0){
		if (!$sellerId) $sellerId = $this->getuserseller();
		foreach($data['products'] as $product) {
			if (in_array($product['id'],$data['selected'])){
				$this->db->query("UPDATE ".DB_PREFIX."customerpartner_to_product SET status = '0' WHERE product_id = '".(int)$product['id']."' AND customer_id = '".(int)$sellerId."'");
			}
		}
	}
	public function productQuery($sql,$data,$value){

		$implode = array();
		$mp_allowproductcolumn = $this->config->get('marketplace_allowedproductcolumn');
		
		$fields = array('model','upc','ean','jan','isbn','mpn','location','subtract','manufacturer_id','points','sort_order','tax_class_id','sku','price','quantity','minimum','stock_status_id','date_available','shipping','weight','weight_class_id','length','width','height','length_class_id','status');

		foreach ($fields as $field){
			if (isset($data[$field])) {
				$implode[] = $field." = '" . $this->db->escape($data[$field]) . "'";
			}
		}
	
		if ($implode) {
			$sql .=  implode(" , ", $implode)." , " ;
		}

		return $sql;
	}
	
	public function cpproductQuery($sql,$data){

		$implode = array();

		$mp_allowproductcolumn = $this->config->get('marketplace_allowedproductcolumn');

		$fields = array('sku','date_available');
		$fields_float = array('price','weight','length','width','height');
		$fields_int = array('quantity','minimum','weight_class_id','length_class_id','stock_status_id','status','shipping');
		
		foreach ($fields as $field){
			if (isset($data[$field])) {
				$implode[] = $field." = '" . $this->db->escape($data[$field]) . "'";
			}
		}
		foreach ($fields_float as $field){
			if (isset($data[$field])) {
				$implode[] = $field." = '" . (float)$data[$field] . "'";
			}
		}		
		foreach ($fields_int as $field){
			if (isset($data[$field])) {
				$implode[] = $field." = '" . (int)$data[$field] . "'";
			}
		}		
		if ($implode) {
			$sql .=  implode(" , ", $implode)." , " ;
		}
		return $sql;
	}	
	/*needtocheck*/
	public function productAddUpdate($product_id,$data,$seller_id=0,$cp_product_id){
		
		if (!$seller_id) $seller_id = $this->getuserseller();

		/* Product Description Table*/
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		if(isset($data['product_description'])){
			foreach ($data['product_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_description` SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "'");
			}
		}
		/* Product Store Table*/
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = 0 ");

		/* Product Attribute Table*/
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
		if (isset($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {				
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}

		/* Product Option Table*/
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

					$product_option_id = $this->db->getLastId();

					if (isset($product_option['product_option_value']) && count($product_option['product_option_value']) > 0 ) {
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						} 
					}else{
						$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_option_id = '".$product_option_id."'");
					}
				} else { 
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['option_value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}

		/* Product Discounts Table*/
		/*
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}
		*/
		
		/* Product Special Table*/
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}
		
		/* Product Download Table*/
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		/* Product Category Table*/
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		/* Product Filter Table*/
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		/* Product Related Table*/
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}
		
		/* Product Reward Table*/
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$product_reward['points'] . "'");
			}
		}
		
		/* Product SEO Table*/
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
		if (isset($data['keyword']) AND $data['keyword']) {
			$data['keyword'] = $this->url_slug(array_values($data['product_description'])[0]['name'].'-'.$product_id).'.html';
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}		
	}


	public function getProductDescriptions($product_id) {
		$product_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'tag'              => $result['tag']
			);
		}
		
		return $product_description_data;
	}

	public function getProductCategories($product_id) {
		$query = $this->db->query("SELECT pc.category_id, (SELECT cp.path_id FROM " . DB_PREFIX . "category_path cp WHERE (cp.category_id = pc.category_id) AND cp.level = 0) AS parent_id FROM " . DB_PREFIX . "product_to_category pc WHERE pc.product_id = '" . (int)$product_id . "'");
		return $query->rows;
	}

	public function getProductFilters($product_id) {
		$product_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX ."product_filter WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_filter_data[] = $result['filter_id'];
		}

		return $product_filter_data;
	}

	public function getProductAttributes($product_id) {

		$product_attribute_data = array();

		$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text, ad.language_id FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' ORDER BY a.sort_order, ad.name");

		$product_attribute_description_data = array();

		foreach ($product_attribute_query->rows as $product_attribute) {

			$product_attribute_description_data[$product_attribute['language_id']] = array(
															'name' => $product_attribute['name'],
															'text' => $product_attribute['text']
															);		

			$product_attribute_data[] = array(
				'attribute_id'                  => $product_attribute['attribute_id'],
				'name'                  		=> $product_attribute['name'],
				'product_attribute_description' => $product_attribute_description_data
			);
		}

		return $product_attribute_data;
	}

	public function getProductOptions($product_id,$tabletype = '') {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . $tabletype."product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();	

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . $tabletype."product_option_value WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "'");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'points'                  => $product_option_value['points'],
					'points_prefix'           => $product_option_value['points_prefix'],						
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']					
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],			
				'product_option_value' => $product_option_value_data,
				'option_value'         => $product_option['value'],
				'required'             => $product_option['required']				
			);
		}

		return $product_option_data;
	}

	public function getProductPQ($product_id,$vendor_id) {
		$query = $this->db->query("SELECT *,(SELECT price FROM " . DB_PREFIX . "cp_product_discount cppd2 WHERE cppd2.id = c2p.id AND cppd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND cppd2.quantity = '1' AND ((cppd2.date_start = '0000-00-00' OR cppd2.date_start < NOW()) AND (cppd2.date_end = '0000-00-00' OR cppd2.date_end > NOW())) ORDER BY cppd2.priority ASC, cppd2.price ASC LIMIT 1) AS discount FROM " . DB_PREFIX ."customerpartner_to_product c2p WHERE c2p.product_id = '" . (int)$product_id . "' AND c2p.customer_id = '".$vendor_id."' AND c2p.status = '1'");
		return $query->row;
	}

	public function getProductDiscounts($product_id) {
		$id = $this->getProductCPId($product_id,$this->getuserseller());
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX ."cp_product_discount WHERE id = '" . (int)$id . "' ORDER BY quantity, priority, price");
		return $query->rows;
	}

	public function getProductSpecials($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");

		return $query->rows;
	}

	public function getProductRewards($product_id) {
		$product_reward_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}

		return $product_reward_data;
	}

	public function getProductDownloads($product_id) {
		$product_download_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_download_data[] = $result['download_id'];
		}

		return $product_download_data;
	}

	public function getProductRelated($product_id,$tabletype = '') {
		$product_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . $tabletype."product_related WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		}

		return $product_related_data;
	}	

	public function getProductRelatedInfo($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getProductEditAccess($product_id, $sellerId){
		if($sellerId) $sellerId = $sellerId;
		else $sellerId = $this->getuserseller();
		
		$edit = $this->db->query("SELECT edit FROM `" . DB_PREFIX . "customerpartner_to_product` WHERE product_id = '" . (int)$product_id . "' AND customer_id = '" .(int)$sellerId. "' AND edit = '1'");		
		if ($edit->num_rows > 0) return true;
		else return false;
	}
	
	// Proifle and seller info
	public function IsApplyForSellership() {
		$query = $this->db->query("SELECT customer_id FROM ".DB_PREFIX ."customerpartner_to_customer WHERE customer_id = '".(int)$this->customer->getId()."'")->row;

		if($query){
			return true;
		}else{
			return false;
		}
		
	}

	public function chkIsPartner($seller_id = 0){
		if($seller_id) {
			$seller_id = $seller_id;
		} else {
			$seller_id = $this->customer->getId();
		}
		$sql = $this->db->query("SELECT * FROM ".DB_PREFIX ."customerpartner_to_customer WHERE customer_id = '" . (int)$seller_id . "'");

		if(count($sql->row) && $sql->row['is_partner']==1){
			return true;
		}else{
			return false;
		}
	}

	// Become partner
	public function becomePartner($shop, $message = ''){

        $commission = $this->config->get('marketplace_commission') ? $this->config->get('marketplace_commission') : 0;

        if($this->config->get('marketplace_partnerapprov')){	
        	$this->db->query("INSERT INTO ".DB_PREFIX."customerpartner_to_customer set customer_id = '" .(int)$this->customer->getId()."', is_partner='1', commission = '".(float)$commission."', companyname = '".$this->db->escape($shop)."' ");
        }else{        	
        	$this->db->query("INSERT INTO ".DB_PREFIX."customerpartner_to_customer set customer_id = '" .(int)$this->customer->getId()."', is_partner='0', companyname = '".$this->db->escape($shop)."'");
        }

        $data = array(
        	'message' => $message,
        	'shop' => $shop,
        	'commission' => $commission,
        	'seller_id' => $this->customer->getId(),
        	'customer_id' => false,
        	'mail_id' => $this->config->get('marketplace_mail_partner_request'),
        	'mail_from' => $this->config->get('marketplace_adminmail'),
        	'mail_to' => $this->customer->getEmail(),
        );

        $values = array(
        	'seller_message' => $data['message'],
			'commission' => $data['commission']."%",
        );
        //send mail to Admin / Customer after request for Partnership
        $this->load->model('customerpartner/mail');
        
        // customer applied for sellership to customer
        $this->model_customerpartner_mail->mail($data,$values);
        
        //customer applied for sellership to admin
        $data['mail_id'] = $this->config->get('marketplace_mail_partner_admin');
        $data['mail_from'] = $this->customer->getEmail();
        $data['mail_to'] = $this->config->get('marketplace_adminmail');

        $this->model_customerpartner_mail->mail($data,$values);
					
	}	

	public function updateProfile($data,$seller_id = false) {
		if($seller_id) {
			$seller_id = $seller_id;
		} else {
			$seller_id = $this->customer->getId();
		}
		$impolde = array();

		// foreach ($data as $key => $value) {
		// 	$impolde[] = strtolower($key).'= "'.$this->db->escape($value).'"';
		// }

		if(isset($data['screenName']))
			$impolde[] = 'screenname = "'.$this->db->escape($data['screenName']).'"';

		if(isset($data['gender']))
			$impolde[] = 'gender = "'.$this->db->escape($data['gender']).'"';

		if(isset($data['shortProfile']))
			$impolde[] = 'shortprofile = "'.$this->db->escape($data['shortProfile']).'"';

		if(isset($data['twitterId']))
			$impolde[] = 'twitterid = "'.$this->db->escape($data['twitterId']).'"';

		if(isset($data['facebookId']))
			$impolde[] = 'facebookid = "'.$this->db->escape($data['facebookId']).'"';

		if(isset($data['backgroundcolor']))
			$impolde[] = 'backgroundcolor = "'.$this->db->escape($data['backgroundcolor']).'"';

		if(isset($data['companyLocality']))
			$impolde[] = 'companylocality = "'.$this->db->escape($data['companyLocality']).'"';

		if(isset($data['companyName']))
			$impolde[] = 'companyname = "'.$this->db->escape($data['companyName']).'"';

		if(isset($data['companyDescription']))
			$impolde[] = 'companydescription = "'.$this->db->escape($data['companyDescription']).'"';

		if(isset($data['otherpayment']))
			$impolde[] = 'otherpayment = "'.$this->db->escape($data['otherpayment']).'"';

		if(isset($data['country']))
			$impolde[] = 'country = "'.$this->db->escape($data['country']).'"';

		if(isset($data['countryLogo']))
			$impolde[] = 'countrylogo = "'.$this->db->escape($data['countryLogo']).'"';

		if(isset($data['paypalid']))
			$impolde[] = 'paypalid = "'.$this->db->escape($data['paypalid']).'"';

		if($impolde){

			$sql = "UPDATE ".DB_PREFIX ."customerpartner_to_customer SET ";
			$sql .= implode(", ",$impolde);
			$sql .= " WHERE customer_id = '".$seller_id."'";

			$this->db->query($sql);
		}		

		$files = $this->request->files;
		
		if(isset($files['companyBanner']['name']) AND $files['companyBanner']['name']){
			$files['companyBanner']['name'] = rand(100000,999999) . $files['companyBanner']["name"];
			$this->db->query("UPDATE ".DB_PREFIX ."customerpartner_to_customer set companybanner='catalog/".$this->db->escape($files['companyBanner']['name'])."' where customer_id='".$seller_id."'");
			move_uploaded_file($files['companyBanner']["tmp_name"], DIR_IMAGE . "catalog/" . $files['companyBanner']["name"]);
		}

		if(isset($files['companyLogo']['name']) AND $files['companyLogo']['name']){
			$files['companyLogo']['name'] = rand(100000,999999) . $files['companyLogo']["name"];
			$this->db->query("UPDATE ".DB_PREFIX ."customerpartner_to_customer set companylogo='catalog/".$this->db->escape($files['companyLogo']['name'])."' where customer_id='".$seller_id."'");
			move_uploaded_file($files['companyLogo']["tmp_name"], DIR_IMAGE . "catalog/" . $files['companyLogo']["name"]);
		}
	
		if(isset($files['avatar']['name']) AND $files['avatar']['name']){
			$files['avatar']['name'] = rand(100000,999999) . $files['avatar']["name"];
			$this->db->query("UPDATE ".DB_PREFIX ."customerpartner_to_customer set avatar='catalog/".$this->db->escape($files['avatar']['name'])."' where customer_id='".$seller_id."'");
			move_uploaded_file($files['avatar']["tmp_name"], DIR_IMAGE . "catalog/" . $files['avatar']["name"]);
		}

		if( isset($data['avatarremove']) AND $data['avatarremove'] ) {
			$this->db->query("UPDATE ".DB_PREFIX ."customerpartner_to_customer set avatar='' where customer_id='".$seller_id."'");
		}
		if( isset($data['companylogoremove']) AND $data['companylogoremove'] ){
			$this->db->query("UPDATE ".DB_PREFIX ."customerpartner_to_customer set companylogo='' where customer_id='".$seller_id."'");
		}
		if( isset($data['companybannerremove']) AND $data['companybannerremove'] ) {
			$this->db->query("UPDATE ".DB_PREFIX ."customerpartner_to_customer set companybanner='' where customer_id='".$seller_id."'");
		}
		
	}
	
	public function getProductVendors($product_id,$vendor_id) {
		
		$query = $this->db->query("SELECT DISTINCT c2p.customer_id AS vendor_id, c2p.price AS price, CONCAT(c.firstname,' ',c.lastname) AS name, c2c.companyname  FROM " . DB_PREFIX . "customerpartner_to_product c2p LEFT JOIN ".DB_PREFIX ."customerpartner_to_customer c2c ON (c2c.customer_id = c2p.customer_id) LEFT JOIN ".DB_PREFIX."customer c ON (c.customer_id = c2p.customer_id) WHERE (c2p.product_id = '" . (int)$product_id . "' AND c2p.status = '1' AND c2p.customer_id <> '".$vendor_id."') ORDER BY c2p.price ASC, c2p.sort_order ASC");

		return $query->rows;
	
	}
	
	public function getProfile($sellerId = false){
		if($sellerId) {
			$sellerId = $sellerId;
		} else {
			$sellerId = $this->customer->getId();
		}
		return $this->db->query("SELECT * FROM ".DB_PREFIX ."customerpartner_to_customer c2c LEFT JOIN ".DB_PREFIX."customer c ON (c2c.customer_id = c.customer_id) where c2c.customer_id = '".$sellerId."'")->row;
	}

	public function getsellerEmail($seller_id){
		$result = $this->db->query("SELECT email FROM ".DB_PREFIX ."customerpartner_to_customer c2c LEFT JOIN ".DB_PREFIX."customer c ON (c2c.customer_id = c.customer_id) where c2c.customer_id = '".$seller_id."'")->row;
		if(isset($result['email'])) {
			return $result['email'];
		} else {
			return false;
		}
	}

	public function getCountry(){
		return $this->db->query("SELECT * FROM ".DB_PREFIX ."country")->rows;
	}		





	// Order
	public function getOrderHistories($order_id) {
		$query = $this->db->query("SELECT date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added");
	
		return $query->rows;
	}	

	public function addOrderHistory($order_id, $data) {

		//$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$data['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$data['order_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");

		$order_info = $this->getOrder($order_id);

		$sellerEmail = $this->customer->getEmail();

		$this->language->load('account/customerpartner/orderinfo');

      	if ($data['notify']) {

			$subject = sprintf($this->language->get('m_text_subject'), $order_info['store_name'], $order_id);

			$message  = $this->language->get('m_text_order') . ' ' . $order_id . "\n";
			$message .= $this->language->get('m_text_date_added') . ' ' . date($this->language->get('m_date_format_short'), strtotime($order_info['date_added'])) . "\n\n";
			
			$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$data['order_status_id'] . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
				
			if ($order_status_query->num_rows) {
				$message .= $this->language->get('m_text_order_status') . "\n";
				$message .= $order_status_query->row['name'] . "\n\n";
			}
			
			if ($order_info['customer_id']) {
				$message .= $this->language->get('m_text_link') . "\n";
				$message .= html_entity_decode($order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id, ENT_QUOTES, 'UTF-8') . "\n\n";
			}
			
			if ($data['comment']) {
				$message .= $this->language->get('m_text_comment') . "\n\n";
				$message .= strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) . "\n\n";
			}

			$message .= $this->language->get('m_text_footer');

			$mail = new Mail($this->config->get('config_mail'));			
			$mail->setTo($order_info['email']);
			$mail->setFrom($sellerEmail);
			$mail->setSender($order_info['store_name']);
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}

		if ($data['notifyadmin']) {
			
			$subject = sprintf($this->language->get('m_text_subject'), $order_info['store_name'], $order_id);

			$message  = $this->language->get('m_text_order') . ' ' . $order_id . "\n";
			$message .= $this->language->get('m_text_date_added') . ' ' . date($this->language->get('m_date_format_short'), strtotime($order_info['date_added'])) . "\n\n";
			
			$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$data['order_status_id'] . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
				
			if ($order_status_query->num_rows) {
				$message .= $this->language->get('m_text_order_status_admin') . "\n";
				$message .= $order_status_query->row['name'] . "\n\n";
			}
			
			if ($data['comment']) {
				$message .= $this->language->get('m_text_comment') . "\n\n";
				$message .= strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) . "\n\n";
			}

			$message .= $this->language->get('m_text_footer');

			$mail = new Mail($this->config->get('config_mail'));
			$mail->setTo($this->config->get('marketplace_adminmail'));
			$mail->setFrom($sellerEmail);
			$mail->setSender($order_info['store_name']);
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}

		return true;
	}

	// Dashboard + Orderlist + OrderInfo

	public function getSellerOrdersByProduct($product_id,$page,$sellerId){
		if($sellerId) {
			$sellerId = $sellerId;
		} else {
			$sellerId = $this->customer->getId();
		}
		$limit = 12;
		$start = ($page-1)*$limit;

		$sql = $this->db->query("SELECT o.order_id ,o.date_added, CONCAT(o.firstname ,' ',o.lastname) name ,os.name orderstatus, c2o.price, c2o.quantity, c2o.paid_status  FROM " . DB_PREFIX ."order_status os LEFT JOIN ".DB_PREFIX ."order o ON (os.order_status_id = o.order_status_id) LEFT JOIN ".DB_PREFIX ."customerpartner_to_order c2o ON (o.order_id = c2o.order_id) WHERE c2o.customer_id = '".$sellerId."'  AND os.language_id = '".$this->config->get('config_language_id')."' AND c2o.product_id = '".(int)$product_id."' ORDER BY o.order_id DESC LIMIT $start,$limit ");
		return($sql->rows);
	}

	public function getSellerOrdersTotalByProduct($product_id,$sellerId){
		if($sellerId) {
			$sellerId = $sellerId;
		} else {
			$sellerId = $this->customer->getId();
		}
		$sql = $this->db->query("SELECT o.order_id ,o.date_added, CONCAT(o.firstname ,' ',o.lastname) name ,os.name orderstatus  FROM " . DB_PREFIX ."order_status os LEFT JOIN ".DB_PREFIX ."order o ON (os.order_status_id = o.order_status_id) LEFT JOIN ".DB_PREFIX ."customerpartner_to_order c2o ON (o.order_id = c2o.order_id) WHERE c2o.customer_id = '".$sellerId."'  AND os.language_id = '".$this->config->get('config_language_id')."' AND c2o.product_id = '".(int)$product_id."' ORDER BY o.order_id ");

		return(count($sql->rows));
	}

	public function getSellerOrders($data = array(),$seller_id){
		if($seller_id) {
			$seller_id = $seller_id;
		} else {
			$seller_id = $this->customer->getId();
		}

		$sql = "SELECT DISTINCT o.order_id ,o.date_added,o.currency_code,o.currency_value, CONCAT(o.firstname ,' ',o.lastname) name ,os.name orderstatus  FROM " . DB_PREFIX ."order_status os LEFT JOIN ".DB_PREFIX ."order o ON (os.order_status_id = o.order_status_id) LEFT JOIN ".DB_PREFIX ."customerpartner_to_order c2o ON (o.order_id = c2o.order_id) WHERE c2o.customer_id = '".$seller_id."'  AND os.language_id = '".$this->config->get('config_language_id')."'";
		
		if (isset($data['filter_order']) && !is_null($data['filter_order'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order'] . "'";
		}

		if (!empty($data['filter_name'])) {
			$sql .= " AND ((o.firstname LIKE '%" . $this->db->escape($data['filter_name']) . "%') OR (o.lastname LIKE '%" . $this->db->escape($data['filter_name']) . "%') OR CONCAT(o.firstname,' ',o.lastname) like '%" . $this->db->escape($data['filter_name']) . "%') ";
		}
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND os.name LIKE '%" . $data['filter_status'] . "%'";
		}

		if (!empty($data['filter_date'])) {
			$sql .= " AND o.date_added LIKE '%" . $this->db->escape($data['filter_date']) . "%'";
		}

		$sort_data = array(
			'o.order_id',
			'o.firstname',
			'os.name',
			'o.date_added'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY o.order_id";	
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
	}

	public function getSellerOrdersTotal($data = array(),$seller_id){
		if($seller_id) {
			$seller_id = $seller_id;
		} else {
			$seller_id = $this->customer->getId();
		}

		$sql = "SELECT COUNT(DISTINCT o.order_id) AS total ,o.date_added, CONCAT(o.firstname ,' ',o.lastname) name ,os.name orderstatus  FROM " . DB_PREFIX ."order_status os LEFT JOIN ".DB_PREFIX ."order o ON (os.order_status_id = o.order_status_id) LEFT JOIN ".DB_PREFIX ."customerpartner_to_order c2o ON (o.order_id = c2o.order_id) WHERE c2o.customer_id = '".$seller_id."'  AND os.language_id = '".$this->config->get('config_language_id')."' ";

		if (isset($data['filter_order']) && !is_null($data['filter_order'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order'] . "'";
		}

		if (!empty($data['filter_name'])) {
			$sql .= " AND ((o.firstname LIKE '%" . $this->db->escape($data['filter_name']) . "%') OR (o.lastname LIKE '%" . $this->db->escape($data['filter_name']) . "%') OR CONCAT(o.firstname,' ',o.lastname) like '%" . $this->db->escape($data['filter_name']) . "%') ";
		}
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND os.name LIKE '%" . $data['filter_status'] . "%'";
		}

		if (!empty($data['filter_date'])) {
			$sql .= " AND o.date_added LIKE '%" . $this->db->escape($data['filter_date']) . "%'";
		}

		$sort_data = array(
			'o.order_id',			
			'o.firstname',
			'os.name',
			'o.date_added'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY o.order_id";	
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

		return $query->row['total'];
		
	}

	public function getSellerOrderProducts($order_id,$seller_id){			
		if($seller_id) {
			$seller_id = $seller_id;
		} else {
			$seller_id = $this->customer->getId();
		}
		$sql = $this->db->query("SELECT op.*,c2o.price c2oprice, c2o.paid_status FROM " . DB_PREFIX ."customerpartner_to_order c2o LEFT JOIN " . DB_PREFIX . "order_product op ON (c2o.order_product_id = op.order_product_id AND c2o.order_id = op.order_id) WHERE c2o.order_id = '".$order_id."'  AND c2o.customer_id = '".$seller_id."' ORDER BY op.product_id ");

		return($sql->rows);
	}

	public function getOrder($order_id,$seller_id) {
		if($seller_id) {
			$seller_id = $seller_id;
		} else {
			$seller_id = $this->customer->getId();
		}
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "customerpartner_to_order c2o ON (o.order_id = c2o.order_id) WHERE o.order_id = '" . (int)$order_id . "' AND o.order_status_id > '0' AND c2o.customer_id = '".$seller_id."'");
		
		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");
			
			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';				
			}
			
			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");
			
			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}
			
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");
			
			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';				
			}
			
			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");
			
			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}
			
			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],

				'store_url'               => $order_query->row['store_url'],				
				'customer_id'             => $order_query->row['customer_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'email'                   => $order_query->row['email'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],				
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],	
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_method'          => $order_query->row['payment_method'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],				
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],	
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method'         => $order_query->row['shipping_method'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'order_status_id'         => $order_query->row['order_status_id'],
				'language_id'             => $order_query->row['language_id'],
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'date_modified'           => $order_query->row['date_modified'],
				'date_added'              => $order_query->row['date_added'],
				'ip'                      => $order_query->row['ip']
			);
		} else {
			return false;	
		}
	}

	// return seller products amount from customerpartner_to_order table
	public function getOrderTotals($order_id,$seller_id) {
		if($seller_id) {
			$seller_id = $seller_id;
		} else {
			$seller_id = $this->customer->getId();
		}
		$query = $this->db->query("SELECT SUM(price) total FROM " . DB_PREFIX . "customerpartner_to_order WHERE order_id = '" . (int)$order_id . "' AND customer_id = '".$seller_id."'");

		return $query->rows;
	}

	public function getOdrTracking($odr,$prod,$cust){		

		$sql = "SELECT tracking FROM " . DB_PREFIX ."customerpartner_sold_tracking where customer_id='".(int)$cust."' AND product_id='".(int)$prod."' AND order_id='".(int)$odr."'";
		
		return($this->db->query($sql)->row);
	}


	public function addOdrTracking($order_id,$tracking){
		//have to add product_order_id condition here too
		
		$comment = '';

		foreach($tracking as $product_id => $tracking_no){

			if($tracking_no){
				$sql = $this->db->query("SELECT c2t.* FROM " . DB_PREFIX ."customerpartner_sold_tracking c2t WHERE c2t.customer_id='".(int)$this->customer->getId()."' AND c2t.product_id='".(int)$product_id."' AND c2t.order_id='".(int)$order_id."'")->row;

				if(!$sql){
					$this->db->query("INSERT INTO " . DB_PREFIX ."customerpartner_sold_tracking SET customer_id='".(int)$this->customer->getId()."' ,tracking='".$this->db->escape($tracking_no)."' ,product_id='".(int)$product_id."' ,order_id='".(int)$order_id."'");

					$sql = $this->db->query("SELECT name FROM " . DB_PREFIX ."order_product WHERE product_id='".(int)$product_id."' AND order_id='".(int)$order_id."'")->row;

					if($sql)
						$comment .= 'Product - '. $sql['name'].'<br/>'.'Seller Tracking No - '. $tracking_no.'<br/>';
			    }
			}
		}

		if($comment){
			$sql = $this->db->query("SELECT o.order_status_id FROM `" . DB_PREFIX ."order` o WHERE o.order_id = '".(int)$order_id."'")->row;

			if($sql)
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . $sql['order_status_id'] . "', notify = '" .  0 . "', comment = '".$this->db->escape($comment)."', date_added = NOW()");
			
			
			$sql = $this->db->query("SELECT c2p.product_id FROM " . DB_PREFIX ."order_product o LEFT JOIN " . DB_PREFIX ."customerpartner_to_product c2p ON (o.product_id = c2p.product_id) LEFT JOIN " . DB_PREFIX ."customerpartner_sold_tracking cst ON (c2p.product_id = cst.product_id) where o.order_id='".(int)$order_id."' AND c2p.product_id NOT IN (SELECT product_id FROM " . DB_PREFIX . "customerpartner_sold_tracking cst WHERE cst.order_id = '".(int)$order_id."')")->rows;
			

			if(!$sql){
				// $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . $this->config->get('config_complete_status_id') . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
			}
		}
	}



	//for downloads
	
	public function addDownload($data) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "download SET filename = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($data['mask']) . "', date_added = NOW()");

		$download_id = $this->db->getLastId();

		foreach ($data['download_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET download_id = '" . (int)$download_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		//for seller table
		$this->db->query("INSERT INTO " . DB_PREFIX . "customerpartner_download SET download_id = '" . (int)$download_id . "', seller_id = '" . $this->customer->getId() . "'");
	}

	public function editDownload($download_id, $data) {

		$download_info = $this->getDownload($download_id);
		
		if ($download_info) {
			if (!empty($data['update'])) {
				$this->db->query("UPDATE " . DB_PREFIX . "order_download SET `filename` = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($data['mask']) . "' WHERE `filename` = '" . $this->db->escape($download_info['filename']) . "'");
			}				

			$this->db->query("UPDATE " . DB_PREFIX . "download SET filename = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($data['mask']) . "' WHERE download_id = '" . (int)$download_id . "'");

			$this->db->query("DELETE FROM " . DB_PREFIX . "download_description WHERE download_id = '" . (int)$download_id . "'");

			foreach ($data['download_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET download_id = '" . (int)$download_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
			}
		}
	}

	public function deleteDownload($download_id) {

		$download_info = $this->getDownload($download_id);

		if($download_info){

			if(file_exists(DIR_DOWNLOAD.$download_info['filename']))
				unlink(DIR_DOWNLOAD.$download_info['filename']);
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "download WHERE download_id = '" . (int)$download_id . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "download_description WHERE download_id = '" . (int)$download_id . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "customerpartner_download WHERE download_id = '" . (int)$download_id . "'");
		}
	}	

	public function getDownload($download_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customerpartner_download cd LEFT JOIN " . DB_PREFIX . "download d ON (cd.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE d.download_id = '" . (int)$download_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd.seller_id = '".$this->customer->getId()."'");

		return $query->row;
	}	

	public function getDownloadProduct($download_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "download d LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE d.download_id = '" . (int)$download_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getDownloadDescriptions($download_id) {
		$download_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download_description WHERE download_id = '" . (int)$download_id . "'");

		foreach ($query->rows as $result) {
			$download_description_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $download_description_data;
	}

	public function getTotalDownloads() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customerpartner_download cd LEFT JOIN " . DB_PREFIX . "download d ON (cd.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd.seller_id = '".$this->customer->getId()."'");

		return count($query->rows);
	}	

	public function getTotalProductsByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_download WHERE download_id = '" . (int)$download_id . "'");

		return $query->row['total'];
	}


	



	// Autocomplete

	// Manufacturer 
	public function getManufacturers($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "manufacturer";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'name',
			'sort_order'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
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
	}

	public function getManufacturer($manufacturer_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "') AS keyword FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row;
	}

	//category
	public function getCategories($data) {
		$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' &gt; ') AS name, c.parent_id, c.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c ON (cp.path_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (c.category_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY cp.category_id ORDER BY name";

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

	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR ' &gt; ') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "') AS keyword FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	} 

	//filters
	public function getFilters($data) {
		$sql = "SELECT *, (SELECT name FROM " . DB_PREFIX . "filter_group_description fgd WHERE f.filter_group_id = fgd.filter_group_id AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS `group` FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND fd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " ORDER BY f.sort_order ASC";

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

	public function getFilter($filter_id) {
		$query = $this->db->query("SELECT *, (SELECT name FROM " . DB_PREFIX . "filter_group_description fgd WHERE f.filter_group_id = fgd.filter_group_id AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS `group` FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_id = '" . (int)$filter_id . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	//downloads
	public function getDownloads($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "customerpartner_download cd LEFT JOIN " . DB_PREFIX . "download d ON (cd.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd.seller_id = '".$this->customer->getId()."'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND dd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'dd.name',
			'd.remaining'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY dd.name";	
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
	}

	//attributes
	public function getAttributes($data = array()) {
		$sql = "SELECT *, (SELECT agd.name FROM " . DB_PREFIX . "attribute_group_description agd WHERE agd.attribute_group_id = a.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS attribute_group FROM " . DB_PREFIX . "attribute a LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_attribute_group_id'])) {
			$sql .= " AND a.attribute_group_id = '" . $this->db->escape($data['filter_attribute_group_id']) . "'";
		}

		$sort_data = array(
			'ad.name',
			'attribute_group',
			'a.sort_order'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY attribute_group, ad.name";	
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
	}

	public function getAttribute($attribute_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute a LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE a.attribute_id = '" . (int)$attribute_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	//options
	public function getOptions($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "option` o LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE od.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$sql .= " AND od.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'od.name',
			'o.type',
			'o.sort_order'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY od.name";	
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
	}

	public function getOption($option_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option` o LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE o.option_id = '" . (int)$option_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getOptionValue($option_value_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value ov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_value_id = '" . (int)$option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getOptionValues($option_id) {
		$option_value_data = array();

		$option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value ov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_id = '" . (int)$option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order ASC");

		foreach ($option_value_query->rows as $option_value) {
			$option_value_data[] = array(
				'option_value_id' => $option_value['option_value_id'],
				'name'            => $option_value['name'],
				'image'           => $option_value['image'],
				'sort_order'      => $option_value['sort_order']
			);
		}

		return $option_value_data;
	}

	//customergroups
	public function getCustomerGroups($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		$sort_data = array(
			'cgd.name',
			'cg.sort_order'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY cgd.name";	
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
	}
	public function updatemaganer($customer_id,$manager_id,$p_limit){
		$seller_id = $this->getuserseller(); 
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."customer_to_manager WHERE employee_id='".$customer_id."'");
		if ($query->num_rows>0) $this->db->query("UPDATE ".DB_PREFIX."customer_to_manager SET manager_id = '".$manager_id."',p_limit = '".$p_limit."' WHERE employee_id = '".$customer_id."' ");
		else $this->db->query("INSERT INTO ".DB_PREFIX."customer_to_manager (employee_id,manager_id,p_limit,customer_id) VALUES ('".$customer_id."','".$manager_id."','".$p_limit."','".$seller_id."')");
	}
	public function getuserseller(){
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."customerpartner_employee_mapping WHERE employee_id = ".(int)$this->customer->getId());
		if ($query->num_rows>0){
			return $query->row['seller_id'];
		} elseif ($this->chkIsPartner()) {
			return $this->customer->getId();
		} else return false;
	}
	public function getusermanager(){
		$query = $this->db->query("SELECT manager_id FROM ".DB_PREFIX."customer_to_manager WHERE employee_id = ".(int)$this->customer->getId());
		if ($query->num_rows>0 && $query->row['manager_id']){
			return $query->row['manager_id'];
		} else {
			return $this->getuserseller();
		}
	}
	
	public function getUser(){
		$customer_id = $this->customer->getId();
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."customer_to_manager WHERE employee_id='".$customer_id."'");
		return $query->row; 
	}

		
	public function getProductCPId($product_id,$sellerId){
		return $this->db->query("SELECT id FROM `" . DB_PREFIX . "customerpartner_to_product` WHERE product_id = '".(int)$product_id."' AND customer_id = '".(int)$sellerId."'")->row['id'];
	}
	
	public function savecart($data){
		$customer_id = $this->customer->getId();
		var_dump($data);
		if (isset($data['new_name']) && $data['new_name']){
			$query = $this->db->query("INSERT INTO ".DB_PREFIX."saved_cart VALUES ('','".$customer_id."','".$data['new_name']."','".serialize($this->session->data['cart'])."','". $data['date'] ."','1')");
		} elseif (isset($data['id'])) {
			$query = $this->db->query("SELECT * FROM ".DB_PREFIX."saved_cart WHERE customer_id = '".$customer_id."' AND id = '".$data['id']."'");
			$cart = $query->row['cart'];
			$products = array();
			$cart = unserialize($query->row['cart']);
			foreach ($this->session->data['cart'] as $key => $quantity){
				if (isset($cart[$key])){
					$cart[$key] += $quantity;
				} else {
					$cart[$key] = $quantity;
				}
			}
			var_dump($cart);
			$query = $this->db->query("UPDATE ".DB_PREFIX."saved_cart SET cart = '".serialize($cart)."' WHERE id = '".$data['id']."'");
		}
		return true;
	}
	public function getsavedcarts(){
		$customer_id = $this->customer->getId();
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."saved_cart WHERE customer_id = '".$customer_id."'");
		return $query->rows;
	}
	public function getsavedcart($id){
		$customer_id = $this->customer->getId();
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."saved_cart WHERE customer_id = '".$customer_id."' AND id = '".$id."'");
		return $query->row;
	}
	public function updatecart($data){
		$customer_id = $this->customer->getId();
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."saved_cart WHERE customer_id = '".$customer_id."' AND id = '".$data['id']."'");
		$cart = $query->row['cart'];
		$products = array();
		foreach (unserialize($cart) as $key => $quantity){
			if ($data['product'][$key]){
				$products[$key] = $data['product'][$key];
			} else unset($products[$key]);
		}
		$sql = array();
		if (isset($data['date'])) $sql[] = " date = '".$data['date']."'";
		if (isset($data['name'])) $sql[] = " name = '".$data['name']."'";
		if (isset($data['product'])) $sql[] = " cart = '".serialize($products)."'";
		$query = $this->db->query("UPDATE ".DB_PREFIX."saved_cart SET ". implode(',',$sql) . " WHERE id = '". $data['id'] ."' AND customer_id = '".$customer_id."'");
		
	}
	public function deletecart($id) {
		$customer_id = $this->customer->getId();
		$this->db->query("DELETE FROM ".DB_PREFIX."saved_cart WHERE customer_id = '".$customer_id."' AND id = '".$id."'");
	}
	private function mail_init(){
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		return $mail;
	}
	private function url_slug($str, $options = array()) {
		// Make sure string is in UTF-8 and strip invalid UTF-8 characters
		$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
	
		$defaults = array(
				'delimiter' => '-',
				'limit' => null,
				'lowercase' => true,
				'replacements' => array(),
				'transliterate' => false,
		);
	
		// Merge options
		$options = array_merge($defaults, $options);
	
		$char_map = array(
				// Latin
				'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
				'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
				'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
				'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
				'ß' => 'ss',
				'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
				'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
				'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
				'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
				'ÿ' => 'y',
				// Latin symbols
				'©' => '(c)',
				// Greek
				'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
				'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
				'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
				'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
				'Ϋ' => 'Y',
				'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
				'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
				'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
				'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
				'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
				// Turkish
				'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
				'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
				// Russian
				'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
				'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
				'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
				'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
				'Я' => 'Ya',
				'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
				'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
				'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
				'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
				'я' => 'ya',
				// Ukrainian
				'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
				'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
				// Czech
				'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
				'Ž' => 'Z',
				'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
				'ž' => 'z',
				// Polish
				'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
				'Ż' => 'Z',
				'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
				'ż' => 'z',
				// Latvian
				'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
				'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
				'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
				'š' => 's', 'ū' => 'u', 'ž' => 'z'
		);
	
		// Make custom replacements
		$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
	
		// Transliterate characters to ASCII
		if ($options['transliterate']) {
			$str = str_replace(array_keys($char_map), $char_map, $str);
		}
	
		// Replace non-alphanumeric characters with our delimiter
		$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
	
		// Remove duplicate delimiters
		$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
	
		// Truncate slug to max. characters
		$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
	
		// Remove delimiter from ends
		$str = trim($str, $options['delimiter']);
	
		return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
	}
	
	
}
?>
