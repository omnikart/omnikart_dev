<?php
class ModelAccountOrder extends Model {
	public function getAllOrders($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
	
		if ($limit < 1) {
			$limit = 1;
		}
	
		$query = $this->db->query("SELECT o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.order_status_id > '0' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int)$start . "," . (int)$limit);
	
		return $query->rows;
	}
		
	public function getOrdersByUser($customer_id) {
	
		$query = $this->db->query("SELECT o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int)$customer_id . "' AND o.order_status_id > '0' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.order_id DESC");
			
		return $query->rows;
	}
	
	public function getOrdersByFilter($data = array()) {
		$sql = "SELECT o.*, CONCAT(o.firstname, ' ', o.lastname) AS customer,
				            payment_country.iso_code_2 as pc_iso_code_2,
				            payment_country.iso_code_3 as pc_iso_code_3,
                            shipping_country.iso_code_2 as sc_iso_code_2,
				            shipping_country.iso_code_3 as sc_iso_code_3,
				            payment_zone.code as payment_zone_code,
				            shipping_zone.code as shipping_zone_code
	
				        FROM `" . DB_PREFIX . "order` o
				        LEFT JOIN `" . DB_PREFIX . "country` payment_country ON ( payment_country.country_id = o.payment_country_id)
				        LEFT JOIN `" . DB_PREFIX . "country` shipping_country ON ( shipping_country.country_id = o.shipping_country_id)
				        LEFT JOIN `" . DB_PREFIX . "zone` payment_zone ON ( payment_zone.zone_id = o.payment_zone_id)
				        LEFT JOIN `" . DB_PREFIX . "zone` shipping_zone ON ( shipping_zone.zone_id = o.shipping_zone_id)
				                    ";
	
		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id IN ( ". $this->db->escape(rtrim($data['filter_order_status_id'],",")) . ")";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
	
		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}
	
		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}
	
	
		if (!empty($data['filter_date_added_to']) && !empty($data['filter_date_added_from'])) {
				
			$sql .= " AND o.date_added BETWEEN STR_TO_DATE('" . $this->db->escape($data['filter_date_added_from']) . "','%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('" . $this->db->escape($data['filter_date_added_to']) . "','%Y-%m-%d %H:%i:%s')";
	
		} elseif (!empty($data['filter_date_added_from'])) {
				
			$sql .= " AND o.date_added >= STR_TO_DATE('" . $this->db->escape($data['filter_date_added_from']) . "','%Y-%m-%d %H:%i:%s')";
	
		} elseif (!empty($data['filter_date_added_on'])) {
				
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added_on']) . "')";
		}
	
		if (!empty($data['filter_date_modified_to']) && !empty($data['filter_date_modified_from'])) {
				
			$sql .= " AND o.date_modified BETWEEN STR_TO_DATE('" . $this->db->escape($data['filter_date_modified_from']) . "','%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('" . $this->db->escape($data['filter_date_modified_to']) . "','%Y-%m-%d %H:%i:%s')";
	
		} elseif (!empty($data['filter_date_modified_from'])) {
				
			$sql .= " AND o.date_modified >= STR_TO_DATE('" . $this->db->escape($data['filter_date_modified_from']) . "','%Y-%m-%d %H:%i:%s')";
	
		} elseif (!empty($data['filter_date_modified_on'])) {
				
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified_on']) . "')";
		}
	
	
		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}
	
		$sort_data = array(
				'o.order_id',
				'customer',
				'o.date_added',
				'o.date_modified',
				'o.total'
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
	
		$orders_query = $this->db->query($sql);
		$orders = array();
		$index = 0;
	
		$this->load->model('localisation/language');
	
		foreach ($orders_query->rows as $order) {
	
			$payment_iso_code_2 = '';
			$payment_iso_code_3 = '';
	
			if (isset($order["pc_iso_code_2"])) {
				$payment_iso_code_2 = $order["pc_iso_code_2"];
			}
	
			if (isset($order["pc_iso_code_3"])) {
				$payment_iso_code_3 = $order["pc_iso_code_3"];
			}
	
			$shipping_iso_code_2 = '';
			$shipping_iso_code_3 = '';
	
			if (isset($order["sc_iso_code_2"])) {
				$shipping_iso_code_2 = $order["sc_iso_code_2"];
			}
	
			if (isset($order["sc_iso_code_3"])) {
				$shipping_iso_code_3 = $order["sc_iso_code_3"];
			}
	
			if (isset($order["payment_zone_code"])) {
				$payment_zone_code = $order["payment_zone_code"];
			} else {
				$payment_zone_code = '';
			}
	
			if (isset($order["shipping_zone_code"])) {
				$shipping_zone_code = $order["shipping_zone_code"];
			} else {
				$shipping_zone_code = '';
			}
	
	
			$language_info = $this->model_localisation_language->getLanguage($order['language_id']);
	
			if ($language_info) {
				$language_code = $language_info['code'];
				$language_filename = $language_info['filename'];
				$language_directory = $language_info['directory'];
			} else {
				$language_code = '';
				$language_filename = '';
				$language_directory = '';
			}
	
			$orders[$index] =  array(
					'order_id'                => $order['order_id'],
					'invoice_no'              => $order['invoice_no'],
					'invoice_prefix'          => $order['invoice_prefix'],
					'store_id'                => $order['store_id'],
					'store_name'              => $order['store_name'],
					'store_url'               => $order['store_url'],
					'customer_id'             => $order['customer_id'],
					'firstname'               => $order['firstname'],
					'lastname'                => $order['lastname'],
					'telephone'               => $order['telephone'],
					'fax'                     => $order['fax'],
					'email'                   => $order['email'],
					'payment_firstname'       => $order['payment_firstname'],
					'payment_lastname'        => $order['payment_lastname'],
					'payment_company'         => $order['payment_company'],
					//'payment_company_id'      => $order['payment_company_id'],
					//'payment_tax_id'          => $order['payment_tax_id'],
					'payment_address_1'       => $order['payment_address_1'],
					'payment_address_2'       => $order['payment_address_2'],
					'payment_postcode'        => $order['payment_postcode'],
					'payment_city'            => $order['payment_city'],
					'payment_zone_id'         => $order['payment_zone_id'],
					'payment_zone'            => $order['payment_zone'],
					'payment_zone_code'       => $payment_zone_code,
					'payment_country_id'      => $order['payment_country_id'],
					'payment_country'         => $order['payment_country'],
					'payment_iso_code_2'      => $payment_iso_code_2,
					'payment_iso_code_3'      => $payment_iso_code_3,
					'payment_address_format'  => $order['payment_address_format'],
					'payment_method'          => $order['payment_method'],
					'payment_code'            => $order['payment_code'],
					'shipping_firstname'      => $order['shipping_firstname'],
					'shipping_lastname'       => $order['shipping_lastname'],
					'shipping_company'        => $order['shipping_company'],
					'shipping_address_1'      => $order['shipping_address_1'],
					'shipping_address_2'      => $order['shipping_address_2'],
					'shipping_postcode'       => $order['shipping_postcode'],
					'shipping_city'           => $order['shipping_city'],
					'shipping_zone_id'        => $order['shipping_zone_id'],
					'shipping_zone'           => $order['shipping_zone'],
					'shipping_zone_code'      => $shipping_zone_code,
					'shipping_country_id'     => $order['shipping_country_id'],
					'shipping_country'        => $order['shipping_country'],
					'shipping_iso_code_2'     => $shipping_iso_code_2,
					'shipping_iso_code_3'     => $shipping_iso_code_3,
					'shipping_address_format' => $order['shipping_address_format'],
					'shipping_method'         => $order['shipping_method'],
					'shipping_code'           => $order['shipping_code'],
					'comment'                 => $order['comment'],
					'total'                   => $order['total'],
					'order_status_id'         => $order['order_status_id'],
					'language_id'             => $order['language_id'],
					'language_code'           => $language_code,
					'language_filename'       => $language_filename,
					'language_directory'      => $language_directory,
					'currency_id'             => $order['currency_id'],
					'currency_code'           => $order['currency_code'],
					'currency_value'          => $order['currency_value'],
					'ip'                      => $order['ip'],
					'forwarded_ip'            => $order['forwarded_ip'],
					'user_agent'              => $order['user_agent'],
					'accept_language'         => $order['accept_language'],
					'date_modified'           => $order['date_modified'],
					'date_added'              => $order['date_added']
			);
			$index++;
		}
	
		return $orders;
	}
	
	public function getOrderStatuses() {
	
		$query = $this->db->query("SELECT order_status_id, name FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");
	
		return $query->rows;
	}
	
	public function getOrderOptionsMod($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option
             LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (" . DB_PREFIX . "order_option.product_option_value_id = pov.product_option_value_id)
            WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");
	
		return $query->rows;
	}
	
	public function getOrderHistoriesRest($order_id) {
		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC");
	
		return $query->rows;
	}
	
	public function getOrder($order_id) {
		
        /*
        To get company orders not individuals for sub users
        */
        if($this->config->get('marketplace_status')) {
           $this->load->model('account/customerpartner');
           $sellerId = $this->model_account_customerpartner->isSubUser($this->customer->getId());
           if($sellerId) {
                $sellerId = $sellerId;
           } else {
                $sellerId = $this->customer->getId();
           }
           $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "' AND customer_id = '" . (int)$sellerId . "' AND order_status_id > '0'");
        } else {
            $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND order_status_id > '0'");
        }
        /*
        end here
        */
    

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

	public function getOrders($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 1;
		}

		
        /*
        To get company orders not individuals for sub users
        */
        if($this->config->get('marketplace_status')) {
           $this->load->model('account/customerpartner');
           $sellerId = $this->model_account_customerpartner->isSubUser($this->customer->getId());
           if($sellerId) {
                $sellerId = $sellerId;
           } else {
                $sellerId = $this->customer->getId();
           }
           $query = $this->db->query("SELECT o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int)$sellerId . "' AND o.order_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int)$start . "," . (int)$limit);
        } else {
            $query = $this->db->query("SELECT o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int)$start . "," . (int)$limit);
        }
        /*
        end here
        */
    

		return $query->rows;
	}

	public function getOrderProduct($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->row;
	}


          public function getProductimage($product_id) {
    $query = $this->db->query("SELECT image FROM `" . DB_PREFIX . "product` WHERE product_id = '".$product_id."'");
    return $query->row['image'];
  }
  public function getOrderstatusid($order_id) {
    $query = $this->db->query("SELECT order_status_id FROM " . DB_PREFIX . "order WHERE order_id = '" . (int)$order_id . "'");

    return $query->row['order_status_id'];
  }
  
	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}

	public function getOrderVouchers($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function getOrderHistories($order_id) {
		$query = $this->db->query("SELECT date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added");

		return $query->rows;
	}

	public function getTotalOrders() {
		
        /*
        To get company orders not individuals for sub users
        */
        if($this->config->get('marketplace_status')) {
           $this->load->model('account/customerpartner');
           $sellerId = $this->model_account_customerpartner->isSubUser($this->customer->getId());
           if($sellerId) {
                $sellerId = $sellerId;
           } else {
                $sellerId = $this->customer->getId();
           }
           $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o WHERE customer_id = '" . (int)$sellerId . "' AND o.order_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "'");
        } else {
            $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o WHERE customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "'");
        }
        /*
        end here
        */
    

		return $query->row['total'];
	}

	public function getTotalOrderProductsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}

	public function getTotalOrderVouchersByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}
}