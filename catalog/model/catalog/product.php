<?php
class ModelCatalogProduct extends Model {
	public function getChecksum() {
		$query = $this->db->query("CHECKSUM TABLE " . DB_PREFIX . "product, "
				. DB_PREFIX . "category,"
				. DB_PREFIX . "product_to_category,"
				. DB_PREFIX . "product_description"
				
				);
		return $query->rows;
	}
	
	public function getTotalProductsByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		return $query->row['total'];
	}
			
	public function getProductsData($data = array(), $customer) {
		if ($customer->isLogged()) {
			$customer_group_id = $customer->getGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
	
		$sql = "SELECT p.product_id";
	
	
		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}
			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}
	
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
	
		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}
	
			if (!empty($data['filter_filter'])) {
				$implode = array();
	
				$filters = explode(',', $data['filter_filter']);
	
				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}
	
				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}
	
		if (!empty($data['filter_name']) ) {
			$sql .= " AND (";
	
			if (!empty($data['filter_name'])) {
				$implode = array();
	
				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));
	
				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}
	
				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
	
				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}
	
			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}
	
			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}
	
			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}
	
			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}
	
			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}
	
			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}
	
			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}
	
			$sql .= ")";
		}
	
		$sql .= " GROUP BY p.product_id";
	
		$sort_data = array(
				'name'=>'pd.name',
				'model'=>'p.model',
				'quantity'=>'p.quantity',
				'price'=>'p.price',
				'rating'=>'rating',
				'sort_order'=>'p.sort_order',
				'date_added'=>'p.date_added'
		);
	
		$sortSql = "";
		if (isset($data['sort']) && in_array($data['sort'], array_keys($sort_data))) {
			if ($data['sort'] == 'name' || $data['sort'] == 'model') {
				$sortSql .= " ORDER BY LCASE(" . $sort_data[$data['sort']] . ")";
			} elseif ($data['sort'] == 'price') {
				$sortSql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sortSql .= " ORDER BY " . $sort_data[$data['sort']];
			}
		} else {
			$sortSql .= " ORDER BY p.sort_order";
		}
	
		if (isset($data['order']) && (strtolower($data['order']) == strtolower('DESC'))) {
			$sortSql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sortSql .= " ASC, LCASE(pd.name) ASC";
		}
	
		$sql.= $sortSql;
		 
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['limit'] < 1) {
				$limit = 20;
			}else {
				$limit = (int)$data['limit'];
			}
	
			$offset = 0;
			if ($data['start'] < 0) {
				$offset = 0;
			}else{
				$offset = (int)$data['start'];
			}
	
			$sql .= " LIMIT " . $offset . "," . $limit;
		}
	
		$product_data = array();
	
		$query = $this->db->query($sql);
	
		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $result['product_id'];
		}
	
		return $this->getProductsByIds(array_keys($product_data), $customer, $sortSql);
	}
	
	public function getProductsByIds($product_ids, $customer, $sortSql = "ORDER BY p.product_id ASC") {
	
		if(count($product_ids) == 0){
			return false;
		}
	
		if ($customer->isLogged()) {
			$customer_group_id = $customer->getGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
	
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount,
(SELECT product_special_id FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special_id, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$customer_group_id . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id IN (" . implode(',', $product_ids) . ") AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'".$sortSql);
	
		$product_data = array();
		if ($query->num_rows) {
			foreach ($query->rows as $result) {
				$specialIds[$result['product_id']] = $result['special_id'];
				$product_data[$result['product_id']] = array(
						'product_id'       => $result['product_id'],
						'name'             => $result['name'],
						'description'      => $result['description'],
						'meta_description' => $result['meta_description'],
						'meta_keyword'     => $result['meta_keyword'],
						'meta_title'     => $result['meta_title'],
						'tag'              => $result['tag'],
						'model'            => $result['model'],
						'sku'              => $result['sku'],
						'upc'              => $result['upc'],
						'ean'              => $result['ean'],
						'jan'              => $result['jan'],
						'isbn'             => $result['isbn'],
						'mpn'              => $result['mpn'],
						'location'         => $result['location'],
						'quantity'         => $result['quantity'],
						'stock_status'     => $result['stock_status'],
						'image'            => $result['image'],
						'manufacturer_id'  => $result['manufacturer_id'],
						'manufacturer'     => $result['manufacturer'],
						'price'            => ($result['discount'] ? $result['discount'] : $result['price']),
						'special'          => isset($result['special']) ? $result['special'] : '',
						'reward'           => $result['reward'],
						'points'           => $result['points'],
						'tax_class_id'     => $result['tax_class_id'],
						'date_available'   => $result['date_available'],
						'weight'           => $result['weight'],
						'weight_class_id'  => $result['weight_class_id'],
						'length'           => $result['length'],
						'width'            => $result['width'],
						'height'           => $result['height'],
						'length_class_id'  => $result['length_class_id'],
						'subtract'         => $result['subtract'],
						'rating'           => round($result['rating']),
						'reviews'          => $result['reviews'] ? $result['reviews'] : 0,
						'minimum'          => $result['minimum'],
						'sort_order'       => $result['sort_order'],
						'status'           => $result['status'],
						'date_added'       => $result['date_added'],
						'date_modified'    => $result['date_modified'],
						'viewed'           => $result['viewed'],
						'weight_class'     => $result['weight_class'],
						'length_class'     => $result['length_class']
				);
			}
			$specialIds = array_filter($specialIds, function($item){
				return !empty($item);
			});
				if(count($specialIds) > 0){
					$specialsQuery = $this->db->query("SELECT date_start, date_end, price, product_id FROM " . DB_PREFIX . "product_special ps WHERE ps.product_special_id IN (" . implode(',', $specialIds) . ")");
	
					foreach ($specialsQuery->rows as $special) {
	
						if(isset($product_data[$special['product_id']])){
							$product_data[$special['product_id']]['special'] = $special['price'];
							$product_data[$special['product_id']]['special_start_date'] = $special['date_start'];
							$product_data[$special['product_id']]['special_end_date'] = $special['date_end'];
						}
					}
				}
				return $product_data;
		} else {
			return false;
		}
	}
	public function getOrderStatusByName($status) {
	
		$query = $this->db->query("SELECT order_status_id FROM " . DB_PREFIX . "order_status WHERE LCASE(name) = '" . $this->db->escape(utf8_strtolower($status)) . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
		return $query->rows;
	}
	
	public function getWeightClasses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "weight_class wc LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON (wc.weight_class_id = wcd.weight_class_id) WHERE wcd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
	
			$sort_data = array(
					'title',
					'unit',
					'value'
			);
	
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY title";
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
		} else {
			$weight_class_data = $this->cache->get('weight_class.' . (int)$this->config->get('config_language_id'));
	
			if (!$weight_class_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class wc LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON (wc.weight_class_id = wcd.weight_class_id) WHERE wcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
				$weight_class_data = $query->rows;
	
				$this->cache->set('weight_class.' . (int)$this->config->get('config_language_id'), $weight_class_data);
			}
	
			return $weight_class_data;
		}
	}
	
	public function getStockStatuses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";
	
			$sql .= " ORDER BY name";
	
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
		} else {
			$stock_status_data = $this->cache->get('stock_status.' . (int)$this->config->get('config_language_id'));
	
			if (!$stock_status_data) {
				$query = $this->db->query("SELECT stock_status_id, name FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");
	
				$stock_status_data = $query->rows;
	
				$this->cache->set('stock_status.' . (int)$this->config->get('config_language_id'), $stock_status_data);
			}
	
			return $stock_status_data;
		}
	}
	
	public function getLengthClasses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "length_class lc LEFT JOIN " . DB_PREFIX . "length_class_description lcd ON (lc.length_class_id = lcd.length_class_id) WHERE lcd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
	
			$sort_data = array(
					'title',
					'unit',
					'value'
			);
	
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY title";
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
		} else {
			$length_class_data = $this->cache->get('length_class.' . (int)$this->config->get('config_language_id'));
	
			if (!$length_class_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class lc LEFT JOIN " . DB_PREFIX . "length_class_description lcd ON (lc.length_class_id = lcd.length_class_id) WHERE lcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
				$length_class_data = $query->rows;
	
				$this->cache->set('length_class.' . (int)$this->config->get('config_language_id'), $length_class_data);
			}
	
			return $length_class_data;
		}
	}
	
	public function getStores($data = array()) {
		$store_data = $this->cache->get('store');
	
		if (!$store_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY url");
	
			$store_data = $query->rows;
	
			$this->cache->set('store', $store_data);
		}
	
		return $store_data;
	}
	
	public function getRecurrings($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "recurring` r LEFT JOIN " . DB_PREFIX . "recurring_description rd ON (r.recurring_id = rd.recurring_id) WHERE rd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
	
		if (!empty($data['filter_name'])) {
			$sql .= " AND rd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}
	
		$sort_data = array(
				'rd.name',
				'r.sort_order'
		);
	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY rd.name";
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
	
	
	public function checkProductExists($product_id) {
		$query = $this->db->query("SELECT COUNT(DISTINCT product_id) AS total FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
	
		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
	
	public static $fields = array(
			"category" => "p2c.category_id",
			"quantity" => "p.quantity",
			"stock_status" => "p.stock_status_id",
			"manufacturer" => "p.manufacturer_id",
			"model" => "p.model",
			"upc" => "p.upc",
			"name" => "pd.name",
			"date_start" => "ps.date_start",
			"date_end" => "ps.date_end",
			"product_id" => "p.product_id",
			"price" => "p.price",
			"status" => "p.status",
			"date_available" => "p.date_available",
			"store_id" => "p2s.store_id",
			"filter_text" => "filter_text",
	);
	
	
	public static $operands = array(
			"=" => "=",
			"!=" => "!=",
			">" => ">",
			">=" => ">=",
			"<" => "<",
			"<=" => "<=",
			"!<" => "!<",
			"!>" => "!>",
			"<>" => "<>",
			"in" => "in",
			"not_in" => "not in",
			"like" => "like",
	);
	
	public static $logicalOperands = array(
			"and"=>"AND",
			"or"=>"OR"
	);
	
	public static $sort_data = array(
			'name'=>'pd.name',
			'model'=>'p.model',
			'quantity'=>'p.quantity',
			'price'=>'p.price',
			'rating'=>'rating',
			'sort_order'=>'p.sort_order',
			'date_added'=>'p.date_added'
	);
	
	private function searchHelper($request){
		$sql = "";
	
		foreach($request['filters'] as $filter){
			if(array_key_exists($filter['field'], static::$fields)) {
	
				$operand = array_key_exists($filter['operand'], static::$operands) ? strtolower(static::$operands[$filter['operand']]) : "=";
				$logicalOperand = isset($filter['logical_operand']) && array_key_exists(strtolower($filter['logical_operand']), static::$logicalOperands) ? $filter['logical_operand'] : "AND";
	
				if (static::$fields[$filter['field']] == 'filter_text' ) {
					$sql .= $logicalOperand."  (";
	
					if (!empty( $filter['value'])) {
						$implode = array();
	
						$words = explode(' ', trim(preg_replace('/\s\s+/', ' ',  $filter['value'])));
	
						foreach ($words as $word) {
							$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
						}
	
						if ($implode) {
							$sql .= " " . implode(" AND ", $implode) . "";
						}
	
						$sql .= " OR pd.description LIKE '%" . $this->db->escape( $filter['value']) . "%'";
					}
	
					if (!empty( $filter['value'])) {
						$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower( $filter['value'])) . "'";
					}
	
					if (!empty( $filter['value'])) {
						$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower( $filter['value'])) . "'";
					}
	
					if (!empty( $filter['value'])) {
						$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower( $filter['value'])) . "'";
					}
	
					if (!empty( $filter['value'])) {
						$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower( $filter['value'])) . "'";
					}
	
					if (!empty( $filter['value'])) {
						$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower( $filter['value'])) . "'";
					}
	
					if (!empty( $filter['value'])) {
						$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower( $filter['value'])) . "'";
					}
	
					if (!empty( $filter['value'])) {
						$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower( $filter['value'])) . "'";
					}
	
					$sql .= ")";
				} else {
					if($operand == "in" || $operand == "not in"){
						$sql.=" ".$logicalOperand." ".static::$fields[$filter['field']]
						." ".$operand." (".$this->db->escape(implode(",",$filter['value'])).")";
					} elseif($operand == "like" ){
						$sql.=" ".$logicalOperand." ".static::$fields[$filter['field']];
						$sql .= " LIKE '%" . $this->db->escape( strtolower($filter['value'])) . "%'";
					} else{
						$sql.=" ".$logicalOperand." ".static::$fields[$filter['field']]
						." ".$operand." ".$this->db->escape($filter['value']);
					}
				}
			}
		}
	
		return $sql;
	
	}
	
	public function search($data = array(), $request, $customer) {
		if ($customer->isLogged()) {
			$customer_group_id = $customer->getGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
	
		$sql = "SELECT p.product_id
                                FROM " . DB_PREFIX . "product p
                              LEFT JOIN " . DB_PREFIX . "product_to_category p2c
                                        ON (p.product_id = p2c.product_id)
                              LEFT JOIN " . DB_PREFIX . "product_description pd
                                        ON (p.product_id = pd.product_id)
                              LEFT JOIN " . DB_PREFIX . "product_to_store p2s
                                        ON (p.product_id = p2s.product_id)
                              WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
                              AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
                              ";
	
		$sql.= $this->searchHelper($request);
	
		$sql .= " GROUP BY p.product_id";
	
		$sortSql = "";
	
		if (isset($request['sort']) && in_array($request['sort'], array_keys(static::$sort_data))) {
			if ($request['sort'] == 'name' || $request['sort'] == 'model') {
				$sortSql .= " ORDER BY LCASE(" . static::$sort_data[$request['sort']] . ")";
			} elseif ($request['sort'] == 'price') {
				$sortSql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sortSql .= " ORDER BY " . static::$sort_data[$request['sort']];
			}
		} else {
			$sortSql .= " ORDER BY p.sort_order";
		}
	
		if (isset($request['order']) && (strtolower($request['order']) == strtolower('DESC'))) {
			$sortSql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sortSql .= " ASC, LCASE(pd.name) ASC";
		}
	
		$sql.= $sortSql;
	
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['limit'] < 1) {
				$limit = 20;
			}else {
				$limit = (int)$data['limit'];
			}
	
			if ($data['start'] < 0) {
				$offset = 0;
			}else{
				$offset = (int)$data['start'];
			}
	
			$sql .= " LIMIT " . $offset . "," . $limit;
		}
	
		if(isset($request['debug']) && isset($request['debug']) == true){
			var_dump($sql);
		}
	
		$product_data = array();
	
		$query = $this->db->query($sql);
		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $result['product_id'];
		}
	
		return $this->getProductsByIds(array_keys($product_data), $customer, $sortSql);
	}
	
	public function getStore($store_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "'");
		return $query->rows;
	}
	
	public function getModulesByCode($code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `code` = '" . $this->db->escape($code) . "' ORDER BY `name`");
	
		return $query->rows;
	}	
	
	public function getGroupedProductGrouped($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gp_grouped WHERE product_id = '" . (int)$product_id . "'");

		return $query->row;
	}

	public function getGroupedProductGroupedChilds($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gp_grouped_child WHERE product_id = '" . (int)$product_id . "' ORDER BY child_sort_order");

		return $query->rows;
	}	
	
	public function updateViewed($product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
	}

	public function getProduct($product_id,$vendor_id=0) {

		require_once DIR_SYSTEM . 'nitro/core/core.php';
		require_once DIR_SYSTEM . 'nitro/core/top.php';

		if (getNitroPersistence('PageCache.ClearCacheOnProductEdit')) {
				setNitroProductCache($product_id, NITRO_PAGECACHE_FOLDER . generateNameOfCacheFile());
		}
		
		if ($vendor_id) { // checking if vendor exist for the product if $vendor_id !=0//
			$query3 = $this->db->query("SELECT c2p.id, c2p.customer_id AS vendor_id FROM " . DB_PREFIX . "customerpartner_to_product c2p WHERE (c2p.product_id = '" . (int)$product_id . "' AND customer_id = '".(int)$vendor_id."' AND c2p.status = '1') ORDER BY c2p.price ASC, c2p.sort_order ASC LIMIT 1"); 

			if ($query3->num_rows) $vendor_id = $query3->row['vendor_id']; // if vendor_id is set for product in customerpartner_product table
			else { 
				$query2 = $this->db->query("SELECT customer_id AS vendor_id FROM " . DB_PREFIX . "customerpartner_to_product c2p WHERE (c2p.product_id = '" . (int)$product_id . "' AND c2p.status = '1') ORDER BY c2p.price ASC, c2p.sort_order ASC LIMIT 1"); // Defalut vendor id for product
				if ($query2->num_rows) $vendor_id = $query2->row['vendor_id']; 
				else $vendor_id = 0;  // if $vendor_id != 0 but the customerpartner_product does not have $vendor_id set for the $product_id the set $vendor_id = 0 for further operations
			}
		} else { // if $vendor_id = 0 then get the default vendor for product
			$query2 = $this->db->query("SELECT customer_id AS vendor_id FROM " . DB_PREFIX . "customerpartner_to_product c2p WHERE (c2p.product_id = '" . (int)$product_id . "' AND c2p.status = '1') ORDER BY c2p.price ASC, c2p.sort_order ASC LIMIT 1"); // Defalut vendor id for product
			if ($query2->num_rows) $vendor_id = $query2->row['vendor_id'];
		}

		if ($vendor_id) { // if $vendor_id != 0 after above filter
			$query = $this->db->query("SELECT DISTINCT cp2p.id AS id, p.product_id, pd.name AS name, pd.description, pd.meta_title, pd.meta_description, pd.meta_keyword, pd.tag, p.model, cp2p.sku AS sku, p.upc, p.ean, p.jan, p.isbn, p.mpn, p.location, p.gp_parent_id, p.type, cp2p.quantity AS quantity, IFNULL((SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = cp2p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "'), 'Not in Stock') AS stock_status,	p.image, p.manufacturer_id, m.name AS manufacturer, IFNULL((SELECT cppd.price FROM " . DB_PREFIX . "cp_product_discount cppd WHERE (cppd.id = cp2p.id) AND (cppd.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AND (cppd.quantity = '1') AND ((cppd.date_start = '0000-00-00' OR cppd.date_start < NOW()) AND (cppd.date_end = '0000-00-00' OR cppd.date_end > NOW())) ORDER BY cppd.priority ASC, cppd.price ASC LIMIT 1),cp2p.price) AS cprice, p.price, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, p.points, p.tax_class_id, cp2p.date_available, IF((cp2p.weight=0),p.weight,cp2p.weight) AS weight, IF((cp2p.weight_class_id=0),p.weight_class_id,cp2p.weight_class_id) AS weight_class_id, IF((cp2p.length=0),p.length,cp2p.length) AS length, IF((cp2p.width=0), p.width, cp2p.width) AS width, IF((cp2p.height=0),p.height,cp2p.height) AS height, IF((cp2p.length_class_id=0),p.length_class_id,cp2p.length_class_id) AS length_class_id, p.subtract, (SELECT ROUND(AVG(rating)) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, IFNULL((SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id),0) AS reviews, IF((cp2p.minimum=0), p.minimum, cp2p.minimum) AS minimum, p.sort_order, cp2p.status AS status, cp2p.date_added AS date_added, cp2p.date_modified AS date_modified, cp2p.viewed AS viewed, cp2p.shipping AS shipping, cp2p.stock_status_id AS stock_status_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN ".DB_PREFIX."customerpartner_to_product cp2p ON (p.product_id = cp2p.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND cp2p.customer_id = " . (int)$vendor_id . " AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		} else { // if $vendor_id = 0 after above filter
			$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, IFNULL((SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1),p.price) AS price, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		}

		if ($query->num_rows) {
			return array(
				'product_id'       => $query->row['product_id'],
				'gp_parent_id' 		 => $query->row['gp_parent_id'],				
				'vendor_id'        => $vendor_id,
				'id'							 => isset($query->row['id'])?$query->row['id']:'0',
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => ($query->row['quantity']?$query->row['quantity']:'0'),
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'original_price'	 => $query->row['price'],
				'price'            => ((isset($query->row['cprice']) && $query->row['cprice'])?$query->row['cprice']:($query->row['price'] ? $query->row['price'] : '0')),
				'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'type'             => $query->row['type'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed'],
			);
		} else {
			return false;
		}
	}

	public function getProducts($data = array()) {
		$sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}
		
		$sql .= " LEFT JOIN " . DB_PREFIX . "customerpartner_to_product c2p ON (p.product_id = c2p.product_id AND c2p.customer_id = (SELECT c2p4.customer_id FROM " . DB_PREFIX . "customerpartner_to_product c2p4 WHERE (c2p4.product_id=p.product_id AND c2p4.status = '1') ORDER BY c2p4.price ASC, c2p4.sort_order ASC LIMIT 1)) ";
		
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.type <> '0' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
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

		if( in_array( __FUNCTION__, array( 'getProducts', 'getTotalProducts', 'getProductSpecials', 'getTotalProductSpecials' ) ) ) {					
			if( ! empty( $this->request->get['mfp'] ) || ( NULL != ( $mfSettings = $this->config->get('mega_filter_settings') ) && ! empty( $mfSettings['in_stock_default_selected'] ) ) ) {
				if( empty( $data['mfp_disabled'] ) ) {
					$this->load->model( 'module/mega_filter' );
					$sql = MegaFilterCore::newInstance( $this, $sql )->getSQL( __FUNCTION__);
				}
			}
		}

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id'],0);
		}

		return $product_data;
	}

	public function getProductAddress($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customerpartner_product_to_address WHERE id = '" . (int)$id . "'");
		if ($query->num_rows > 0) return $query->rows;
		else return false;
	}

	public function getProductSpecials($data = array()) {
		$sql = "SELECT DISTINCT ps.product_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
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

		if( in_array( __FUNCTION__, array( 'getProducts', 'getTotalProducts', 'getProductSpecials', 'getTotalProductSpecials' ) ) ) {					
			if( ! empty( $this->request->get['mfp'] ) || ( NULL != ( $mfSettings = $this->config->get('mega_filter_settings') ) && ! empty( $mfSettings['in_stock_default_selected'] ) ) ) {
				if( empty( $data['mfp_disabled'] ) ) {
					$this->load->model( 'module/mega_filter' );
					$sql = MegaFilterCore::newInstance( $this, $sql )->getSQL( __FUNCTION__);
				}
			}
		}
		
		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getLatestProducts($limit) {
		$product_data = $this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getPopularProducts($limit) {
		$product_data = array();

		$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed DESC, p.date_added DESC LIMIT " . (int)$limit);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getBestSellerProducts($limit) {
		$product_data = $this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$product_data = array();

			$query = $this->db->query("SELECT op.product_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_group_data = array();

		$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
			$product_attribute_data = array();

			$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");

			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_data[$product_attribute['attribute_id']] = array(
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']
				);
			}

			$product_attribute_group_data[$product_attribute_group['attribute_group_id']] = array(
				'name'               => $product_attribute_group['name'],
				'attribute'          => $product_attribute_data
			);
		}
		
		return $product_attribute_group_data;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'name'                    => $product_option_value['name'],
					'image'                   => $product_option_value['image'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;
	}
	
	public function getSupplierProductDiscounts($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cp_product_discount WHERE id = '" . (int)$id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductRelated($product_id) {
		$product_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		foreach ($query->rows as $result) {
			$product_data[$result['related_id']] = $this->getProduct($result['related_id']);
		}

		return $product_data;
	}

	public function getProductLayoutId($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getCategories($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}

	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.type <> '0' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		if( in_array( __FUNCTION__, array( 'getProducts', 'getTotalProducts', 'getProductSpecials', 'getTotalProductSpecials' ) ) ) {					
			if( ! empty( $this->request->get['mfp'] ) || ( NULL != ( $mfSettings = $this->config->get('mega_filter_settings') ) && ! empty( $mfSettings['in_stock_default_selected'] ) ) ) {
				if( empty( $data['mfp_disabled'] ) ) {
					$this->load->model( 'module/mega_filter' );
					$sql = MegaFilterCore::newInstance( $this, $sql )->getSQL( __FUNCTION__);
				}
			}
		}
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProfiles($product_id) {
		return $this->db->query("SELECT `pd`.* FROM `" . DB_PREFIX . "product_recurring` `pp` JOIN `" . DB_PREFIX . "recurring_description` `pd` ON `pd`.`language_id` = " . (int)$this->config->get('config_language_id') . " AND `pd`.`recurring_id` = `pp`.`recurring_id` JOIN `" . DB_PREFIX . "recurring` `p` ON `p`.`recurring_id` = `pd`.`recurring_id` WHERE `product_id` = " . (int)$product_id . " AND `status` = 1 AND `customer_group_id` = " . (int)$this->config->get('config_customer_group_id') . " ORDER BY `sort_order` ASC")->rows;
	}

	public function getProfile($product_id, $recurring_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "recurring` `p` JOIN `" . DB_PREFIX . "product_recurring` `pp` ON `pp`.`recurring_id` = `p`.`recurring_id` AND `pp`.`product_id` = " . (int)$product_id . " WHERE `pp`.`recurring_id` = " . (int)$recurring_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int)$this->config->get('config_customer_group_id'))->row;
	}

	public function getTotalProductSpecials() {
		$query = $this->db->query("SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");

		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
}
