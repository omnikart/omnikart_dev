<?php
class ModelCustomerpartnerMaster extends Model {
	

	public function getPartnerIdBasedonProduct($productid){
		return $this->db->query("SELECT c2p.customer_id as id FROM " . DB_PREFIX . "customerpartner_to_product c2p LEFT JOIN ".DB_PREFIX."product p ON(c2p.product_id = p.product_id) LEFT JOIN ".DB_PREFIX."product_to_store p2s ON (p.product_id = p2s.product_id) WHERE c2p.product_id = '".(int)$productid."' AND p.status = 1 AND p2s.store_id = '".$this->config->get('config_store_id')."' AND quantity > 0 ORDER BY c2p.sort_order, c2p.price ASC LIMIT 1  ")->row;
	}	

	public function getLatest(){
		$sql = "SELECT p.product_id, pd.name AS name, pd.description, pd.meta_title, pd.meta_description, pd.meta_keyword, pd.tag, p.model, cp2p.sku, p.upc, p.ean, p.jan, p.isbn, p.mpn, p.location, cp2p.quantity, IFNULL((SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = cp2p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "'), 'Not in Stock') AS stock_status,
		p.image, p.manufacturer_id, m.name AS manufacturer, IFNULL((SELECT cppd.price FROM " . DB_PREFIX . "cp_product_discount cppd WHERE (cppd.id = cp2p.id) AND (cppd.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AND (cppd.quantity = '1') AND ((cppd.date_start = '0000-00-00' OR cppd.date_start < NOW()) AND (cppd.date_end = '0000-00-00' OR cppd.date_end > NOW())) ORDER BY cppd.priority ASC, cppd.price ASC LIMIT 1),cp2p.price) AS price, p.price as original_price, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, p.points, p.tax_class_id, cp2p.date_available, IFNULL((cp2p.weight),p.weight) AS weight, IFNULL((cp2p.weight_class_id),p.weight_class_id) AS weight_class_id, IFNULL((cp2p.length),p.length) AS length, IFNULL((cp2p.width),p.width) AS width, IFNULL((cp2p.height),p.height) AS height, IFNULL((cp2p.length_class_id),p.length_class_id) AS length_class_id, p.subtract, (SELECT ROUND(AVG(rating)) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, IFNULL((SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id),0) AS reviews, IFNULL(cp2p.minimum, p.minimum) as minimum, p.sort_order, cp2p.status as status, cp2p.date_added, cp2p.date_modified, cp2p.viewed, cp2p.shipping, cp2p.stock_status_id, c2c.avatar,c.customer_id,CONCAT(c.firstname ,' ',c.lastname) AS seller_name,c2c.companyname,co.name AS country, FROM " . DB_PREFIX . "customerpartner_to_product cp2p LEFT JOIN ".DB_PREFIX ."product p ON (cp2p.product_id = p.product_id) LEFT JOIN ".DB_PREFIX ."product_description pd ON (pd.product_id = p.product_id) LEFT JOIN ".DB_PREFIX ."customerpartner_to_customer c2c ON (c2c.customer_id = cp2p.customer_id) LEFT JOIN ".DB_PREFIX ."customer c ON (c2c.customer_id = c.customer_id) LEFT JOIN ".DB_PREFIX ."country co ON (c2c.country = co.iso_code_2) LEFT JOIN ".DB_PREFIX."product_to_store p2s ON (p.product_id = p2s.product_id) WHERE c2c.is_partner = '1' AND p.status = '1' AND p.date_available <= NOW() AND pd.language_id = '".$this->config->get('config_language_id')."' AND p2s.store_id = '".$this->config->get('config_store_id')."' ORDER BY cp2p.id DESC limit 8 ";
		
		$query = $this->db->query($sql);
		return $query->rows;		
	}

	public function getPartnerCollectionCount($customerid){
		return count($this->db->query("SELECT DISTINCT p.product_id FROM " . DB_PREFIX . "customerpartner_to_product c2p LEFT JOIN ".DB_PREFIX ."product p ON (c2p.product_id = p.product_id) LEFT JOIN ".DB_PREFIX."product_to_store p2s ON (p.product_id = p2s.product_id) WHERE c2p.customer_id='".$customerid."' AND p.status='1' AND p.date_available <= NOW() AND p2s.store_id = '".$this->config->get('config_store_id')."' ORDER BY c2p.product_id ")->rows);
	}

	public function getOldPartner(){		
		return $this->db->query("SELECT *,co.name as country FROM " . DB_PREFIX . "customerpartner_to_customer c2c LEFT JOIN ".DB_PREFIX ."customer c ON (c2c.customer_id = c.customer_id) LEFT JOIN ".DB_PREFIX ."country co ON (c2c.country = co.iso_code_2) WHERE is_partner = 1 AND c.status = '1' ORDER BY c2c.customer_id ASC LIMIT 4")->rows;	
	}

	public function getProfile($customerid){
		$sql = "SELECT *,co.name as country FROM " . DB_PREFIX . "customerpartner_to_customer c2c LEFT JOIN ".DB_PREFIX ."customer c ON (c2c.customer_id = c.customer_id) LEFT JOIN ".DB_PREFIX ."address a ON (c.address_id = a.address_id) LEFT JOIN ".DB_PREFIX ."country co ON (c2c.country = co.iso_code_2) WHERE c2c.customer_id = '".(int)$customerid."' AND c2c.is_partner = '1'";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getFeedbackList($customerid){
		$sql = "SELECT c2f.* FROM " . DB_PREFIX . "customerpartner_to_feedback c2f LEFT JOIN ".DB_PREFIX ."customer c ON (c2f.customer_id = c.customer_id) LEFT JOIN ".DB_PREFIX ."customerpartner_to_customer cpc ON (cpc.customer_id = c.customer_id) where c2f.seller_id = '".(int)$customerid."'";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getTotalFeedback($customerid){
		$query = $this->db->query("SELECT id FROM " . DB_PREFIX . "customerpartner_to_feedback c2f where c2f.seller_id='".(int)$customerid."'");
		return count($query->rows);
	}

	public function getProductFeedbackList($customerid){
		$query = $this->db->query("SELECT r.*,CONCAT(pd.name,'<br/>',r.text) `text` FROM " . DB_PREFIX . "customerpartner_to_product c2p INNER JOIN ".DB_PREFIX ."review r ON (c2p.product_id = r.product_id) LEFT JOIN ".DB_PREFIX."product_description pd ON (pd.product_id = c2p.product_id) WHERE c2p.customer_id = '".(int)$customerid."' AND pd.language_id = '".$this->config->get('config_language_id')."'");
		return $query->rows;
	}

	public function getTotalProductFeedbackList($customerid){
		$query = $this->db->query("SELECT r.* FROM " . DB_PREFIX . "customerpartner_to_product c2p INNER JOIN ".DB_PREFIX ."review r ON (c2p.product_id = r.product_id) WHERE c2p.customer_id = '".(int)$customerid."'");			
		return count($query->rows);
	}

	
	public function saveFeedback($data,$seller_id){
	
		$result = $this->db->query("SELECT id FROM ".DB_PREFIX ."customerpartner_to_feedback WHERE customer_id = ".$this->customer->getId()." AND seller_id = '".(int)$seller_id."'")->row;

		if(!$result){
			$this->db->query("INSERT INTO ".DB_PREFIX ."customerpartner_to_feedback SET customer_id = '".$this->customer->getId()."',seller_id = '".(int)$seller_id."', feedprice = '".(int)$this->db->escape($data['price_rating'])."',feedvalue = '".$this->db->escape($data['value_rating'])."',feedquality = '".$this->db->escape($data['quality_rating'])."', nickname = '".$this->db->escape($data['name'])."',  review = '".$this->db->escape($data['text'])."', createdate = NOW() ");
		}else{
			$this->db->query("UPDATE ".DB_PREFIX ."customerpartner_to_feedback set feedprice='".$this->db->escape($data['price_rating'])."',feedvalue='".$this->db->escape($data['value_rating'])."',feedquality='".$this->db->escape($data['quality_rating'])."', nickname='".$this->db->escape($data['name'])."', review='".$this->db->escape($data['text'])."',createdate = NOW() WHERE id = '".$result['id']."'");			
		}

	}	
	
	public function getShopData($shop){
		$sql = $this->db->query("SELECT * FROM " . DB_PREFIX . "customerpartner_to_customer where companyname = '" .$this->db->escape($shop)."'")->row;		
		if($sql)
			return false;		
		return true;
	}
}
?>
