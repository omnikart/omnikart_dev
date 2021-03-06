<?php
class Cart {
	private $config;
	private $db;
	private $data = array ();
	public function __construct($registry) {
		$this->config = $registry->get ( 'config' );
		$this->customer = $registry->get ( 'customer' );
		$this->session = $registry->get ( 'session' );
		$this->db = $registry->get ( 'db' );
		$this->tax = $registry->get ( 'tax' );
		$this->weight = $registry->get ( 'weight' );
		
		if (! isset ( $this->session->data ['cart'] ) || ! is_array ( $this->session->data ['cart'] )) {
			$this->session->data ['cart'] = array ();
		}
	}
	public function getProducts() {
		if (! $this->data) {
			
			$discount_quantity = array ();
			
			foreach ( $this->session->data ['cart'] as $key => $quantity ) {
				$product = unserialize ( base64_decode ( $key ) );
				
				$product_id = $product ['product_id'];
				
				$stock = true;
				
				// Supplier
				if (! empty ( $product ['vendor_id'] )) { // Check if Vendor is set for the product if not make $vendor_id = 0
					$vendor_id = $product ['vendor_id'];
				} else {
					$vendor_id = 0;
				}
				
				// Options
				if (! empty ( $product ['option'] )) {
					$options = $product ['option'];
				} else {
					$options = array ();
				}
				
				// Profile
				if (! empty ( $product ['recurring_id'] )) {
					$recurring_id = $product ['recurring_id'];
				} else {
					$recurring_id = 0;
				}
				
				$product_query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . ( int ) $product_id . "' AND pd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "' AND p.date_available <= NOW() AND p.status = '1'" );
				
				if ($product_query->num_rows) { // Check if Product Exist
					$option_price = 0;
					$option_points = 0;
					$option_weight = 0;
					$option_data = array();

					$price = $product_query->row['price']; // Getting Product Default Price set by omnikart. ( MRP )
						
					if ($vendor_id) { // if $vendor_id != 0  get the vendor product details
						$vendor = $this->db->query("SELECT * FROM " . DB_PREFIX . "customerpartner_to_product c2p WHERE (c2p.product_id=".(int)$product_id." AND c2p.customer_id = '".(int)$vendor_id."' AND status='1')");
					} else { // if $vendor_id = 0  get the default vendor product details
						$vendor = $this->db->query("SELECT * FROM " . DB_PREFIX . "customerpartner_to_product c2p WHERE (c2p.product_id=".(int)$product_id." AND status='1') ORDER BY c2p.sort_order ASC LIMIT 1");
					}
					
					if($vendor->row && $vendor->row['price']){ // Check if any vendor exist for the product if yes the update the produc price
						$price = $vendor->row['price'];
						$id = $vendor->row['id'];
						$vendor_id = $vendor->row['customer_id'];
					} else {$vendor_id = 0;$id=0;} // if vendor_id is not zero and no vendor exist for the product then set $vendor_id = 0;
								
					
					$query = $this->db->query("SELECT * FROM `".DB_PREFIX."customerpartner_to_product_option` WHERE id='" . (int)$vendor->row['id'] . "'");
					$option_values = array();
					if ($query->num_rows){
						foreach ($query->rows as $option_value) {
							$option_values[$option_value['product_option_value_id']] = array(
								'product_option_value_id'=>$option_value['product_option_value_id'],
								'product_id'=>$option_value['product_id'],
								'id'=>$option_value['id'],
								'sku'=>$option_value['sku'],
								'price'=>$option_value['price'],
								'quantity'=>$option_value['quantity']
							);
						}
					}
					
					foreach ($options as $product_option_id => $value) { // Product Options Query //
						$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

						if ($option_query->num_rows) {
							if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
								$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, cppo.quantity AS quantity, pov.subtract, cppo.price AS price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov INNER JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) INNER JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) INNER JOIN `" . DB_PREFIX . "customerpartner_to_product_option` cppo ON (cppo.product_option_value_id=pov.product_option_value_id) WHERE pov.product_option_value_id = '" . (int)$value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cppo.id='" . (int)$id . "'");
								
								if ($option_value_query->num_rows ) {
									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
					

									}
									
									if ($option_value_query->row ['points_prefix'] == '+') {
										$option_points += $option_value_query->row ['points'];
									} elseif ($option_value_query->row ['points_prefix'] == '-') {
										$option_points -= $option_value_query->row ['points'];
									}
									
									if ($option_value_query->row ['weight_prefix'] == '+') {
										$option_weight += $option_value_query->row ['weight'];
									} elseif ($option_value_query->row ['weight_prefix'] == '-') {
										$option_weight -= $option_value_query->row ['weight'];
									}
									
									if ($option_value_query->row ['subtract'] && (! $option_value_query->row ['quantity'] || ($option_value_query->row ['quantity'] < $quantity))) {
										$stock = false;
									}
							
									$option_data[] = array(
										'product_option_id'       => $product_option_id,
										'product_option_value_id' => $value,
										'option_id'               => $option_query->row['option_id'],
										'option_value_id'         => $option_value_query->row['option_value_id'],
										'name'                    => $option_query->row['name'],
										'value'                   => $option_value_query->row['name'],
										'type'                    => $option_query->row['type'],
										'quantity'                => $option_value_query->row['quantity'],
										'subtract'                => $option_value_query->row['subtract'],
										'price'                   => $option_value_query->row['price'],
										'price_prefix'            => $option_value_query->row['price_prefix'],
										'points'                  => $option_value_query->row['points'],
										'points_prefix'           => $option_value_query->row['points_prefix'],
										'weight'                  => $option_value_query->row['weight'],
										'weight_prefix'           => $option_value_query->row['weight_prefix']
									);
								}
							} elseif ($option_query->row ['type'] == 'checkbox' && is_array ( $value )) {
								foreach ( $value as $product_option_value_id ) {
									$option_value_query = $this->db->query ( "SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . ( int ) $product_option_value_id . "' AND pov.product_option_id = '" . ( int ) $product_option_id . "' AND ovd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "'" );
									
									if ($option_value_query->num_rows) {
										if ($option_value_query->row ['price_prefix'] == '+') {
											$option_price += $option_value_query->row ['price'];
										} elseif ($option_value_query->row ['price_prefix'] == '-') {
											$option_price -= $option_value_query->row ['price'];
										}
										
										if ($option_value_query->row ['points_prefix'] == '+') {
											$option_points += $option_value_query->row ['points'];
										} elseif ($option_value_query->row ['points_prefix'] == '-') {
											$option_points -= $option_value_query->row ['points'];
										}
										
										if ($option_value_query->row ['weight_prefix'] == '+') {
											$option_weight += $option_value_query->row ['weight'];
										} elseif ($option_value_query->row ['weight_prefix'] == '-') {
											$option_weight -= $option_value_query->row ['weight'];
										}
										
										if ($option_value_query->row ['subtract'] && (! $option_value_query->row ['quantity'] || ($option_value_query->row ['quantity'] < $quantity))) {
											$stock = false;
										}
										
										$option_data [] = array (
												'product_option_id' => $product_option_id,
												'product_option_value_id' => $product_option_value_id,
												'option_id' => $option_query->row ['option_id'],
												'option_value_id' => $option_value_query->row ['option_value_id'],
												'name' => $option_query->row ['name'],
												'value' => $option_value_query->row ['name'],
												'type' => $option_query->row ['type'],
												'quantity' => $option_value_query->row ['quantity'],
												'subtract' => $option_value_query->row ['subtract'],
												'price' => $option_value_query->row ['price'],
												'price_prefix' => $option_value_query->row ['price_prefix'],
												'points' => $option_value_query->row ['points'],
												'points_prefix' => $option_value_query->row ['points_prefix'],
												'weight' => $option_value_query->row ['weight'],
												'weight_prefix' => $option_value_query->row ['weight_prefix'] 
										);
									}
								}
							} elseif ($option_query->row ['type'] == 'text' || $option_query->row ['type'] == 'textarea' || $option_query->row ['type'] == 'file' || $option_query->row ['type'] == 'date' || $option_query->row ['type'] == 'datetime' || $option_query->row ['type'] == 'time') {
								$option_data [] = array (
										'product_option_id' => $product_option_id,
										'product_option_value_id' => '',
										'option_id' => $option_query->row ['option_id'],
										'option_value_id' => '',
										'name' => $option_query->row ['name'],
										'value' => $value,
										'type' => $option_query->row ['type'],
										'quantity' => '',
										'subtract' => '',
										'price' => '',
										'price_prefix' => '',
										'points' => '',
										'points_prefix' => '',
										'weight' => '',
										'weight_prefix' => '' 
								);
							}
						}
					}

					// Product Discounts
					
					$discount_quantity [$product_id . '-' . $vendor_id] = 0;
					foreach ( $this->session->data ['cart'] as $key_2 => $quantity_2 ) {
						$product_2 = ( array ) unserialize ( base64_decode ( $key_2 ) );
						if ($vendor_id && isset ( $product_2 ['vendor_id'] )) { // if $vendor_id != 0 then update the quantity for the product
							if (($product_2 ['product_id'] . '-' . $product_2 ['vendor_id']) == ($product_id . '-' . $vendor_id)) {
								$discount_quantity [$product_id . '-' . $vendor_id] += $quantity_2;
							}
						} else {
							if ($product_2 ['product_id'] == $product_id) { // if $vendor_id = 0 then update the quantity for the product
								$discount_quantity [$product_id . '-' . $vendor_id] += $quantity_2;
							}
						}
					}
					if (isset ( $vendor ) && ($vendor->num_rows > 0) && isset ( $product_2 ['vendor_id'] )) { // Vendor discont
						$product_discount_query = $this->db->query ( "SELECT price FROM " . DB_PREFIX . "cp_product_discount WHERE id = '" . ( int ) $vendor->row ['id'] . "' AND customer_group_id = '" . ( int ) $this->config->get ( 'config_customer_group_id' ) . "' AND quantity <= '" . ( int ) $discount_quantity [$product_id . '-' . $vendor_id] . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1" );
					} else { // Product based discount till vendors offers something
						$product_discount_query = $this->db->query ( "SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . ( int ) $product_id . "' AND customer_group_id = '" . ( int ) $this->config->get ( 'config_customer_group_id' ) . "' AND quantity <= '" . ( int ) $discount_quantity [$product_id . '-' . $vendor_id] . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1" );
					}
					if ($product_discount_query->num_rows) {
						$price = $product_discount_query->row ['price'];
					}
					
					// Product Specials
					$product_special_query = $this->db->query ( "SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . ( int ) $product_id . "' AND customer_group_id = '" . ( int ) $this->config->get ( 'config_customer_group_id' ) . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1" );
					
					if ($product_special_query->num_rows) {
						$price = $product_special_query->row ['price'];
					}
					
					// Reward Points
					$product_reward_query = $this->db->query ( "SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . ( int ) $product_id . "' AND customer_group_id = '" . ( int ) $this->config->get ( 'config_customer_group_id' ) . "'" );
					
					if ($product_reward_query->num_rows) {
						$reward = $product_reward_query->row ['points'];
					} else {
						$reward = 0;
					}
					
					// Downloads
					$download_data = array ();
					
					$download_query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . ( int ) $product_id . "' AND dd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "'" );
					
					foreach ( $download_query->rows as $download ) {
						$download_data [] = array (
								'download_id' => $download ['download_id'],
								'name' => $download ['name'],
								'filename' => $download ['filename'],
								'mask' => $download ['mask'] 
						);
					}
					
					// Stock
					if ($vendor->row) {
						if (!$vendor->row['quantity'] || ($vendor->row['quantity'] < $quantity)) {
							$stock = false;
						}
					} else {
						if (! $product_query->row ['quantity'] || ($product_query->row ['quantity'] < $quantity)) {
							$stock = false;
						}
					}
					
					$recurring_query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "recurring` `p` JOIN `" . DB_PREFIX . "product_recurring` `pp` ON `pp`.`recurring_id` = `p`.`recurring_id` AND `pp`.`product_id` = " . ( int ) $product_query->row ['product_id'] . " JOIN `" . DB_PREFIX . "recurring_description` `pd` ON `pd`.`recurring_id` = `p`.`recurring_id` AND `pd`.`language_id` = " . ( int ) $this->config->get ( 'config_language_id' ) . " WHERE `pp`.`recurring_id` = " . ( int ) $recurring_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . ( int ) $this->config->get ( 'config_customer_group_id' ) );
					
					if ($recurring_query->num_rows) {
						$recurring = array (
								'recurring_id' => $recurring_id,
								'name' => $recurring_query->row ['name'],
								'frequency' => $recurring_query->row ['frequency'],
								'price' => $recurring_query->row ['price'],
								'cycle' => $recurring_query->row ['cycle'],
								'duration' => $recurring_query->row ['duration'],
								'trial' => $recurring_query->row ['trial_status'],
								'trial_frequency' => $recurring_query->row ['trial_frequency'],
								'trial_price' => $recurring_query->row ['trial_price'],
								'trial_cycle' => $recurring_query->row ['trial_cycle'],
								'trial_duration' => $recurring_query->row ['trial_duration'] 
						);
					} else {
						$recurring = false;
					}
					
					if (isset ( $vendor ) && ($vendor->num_rows > 0) && isset ( $product_2 ['vendor_id'] )) {
						$shipping = $vendor->row ['shipping'];
						$weight = ($vendor->row ['weight'] + $option_weight) * $quantity;
						$weight_class_id = $vendor->row ['weight_class_id'];
						$length = $vendor->row ['length'];
						$width = $vendor->row ['width'];
						$height = $vendor->row ['height'];
						$length_class_id = $vendor->row ['length_class_id'];
						$minimum = $vendor->row ['minimum'];
					} else {
						$shipping = $product_query->row ['shipping'];
						$weight = ($product_query->row ['weight'] + $option_weight) * $quantity;
						$weight_class_id = $product_query->row ['weight_class_id'];
						$length = $product_query->row ['length'];
						$width = $product_query->row ['width'];
						$height = $product_query->row ['height'];
						$length_class_id = $product_query->row ['length_class_id'];
						$minimum = $product_query->row ['minimum'];
					}

					$this->data[$key] = array(
						'key'             => $key,
						'id'              => $id,
						'product_id'      => $product_query->row['product_id'],
						'name'            => $product_query->row['name'],
						'model'           => $product_query->row['model'],
						'image'           => $product_query->row['image'],
						'option'          => $option_data,
						'download'        => $download_data,
						'quantity'        => $quantity,
						'subtract'        => $product_query->row['subtract'],
						'stock'           => $stock,
						'price'           => ($price + $option_price),
						'total'           => ($price + $option_price) * $quantity,
						'reward'          => $reward * $quantity,
						'points'          => ($product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $quantity : 0),
						'tax_class_id'    => $product_query->row['tax_class_id'],
						'recurring'       => $recurring,
						'shipping'        => $shipping,
						'weight'          => $weight,
						'weight_class_id' => $weight_class_id,
						'length'          => $length,         
						'width'           => $width,         
						'height'          => $height,        
						'length_class_id' => $length_class_id,
						'minimum'         => $minimum,
						'vendor_id'				=> $vendor_id
					);
				} else {
					$this->remove ( $key );
				}
			}
		}
		
		return $this->data;
	}
	public function getRecurringProducts() {
		$recurring_products = array ();
		
		foreach ( $this->getProducts () as $key => $value ) {
			if ($value ['recurring']) {
				$recurring_products [$key] = $value;
			}
		}
		
		return $recurring_products;
	}
	public function add($product_id, $qty = 1, $option = array(), $recurring_id = 0, $vendor_id = 0) {
		$this->data = array ();
		
		$product ['product_id'] = ( int ) $product_id;
		
		if ($option) {
			$product ['option'] = $option;
		}
		
		if ($recurring_id) {
			$product ['recurring_id'] = ( int ) $recurring_id;
		}
		
		if ($vendor_id) {
			$product ['vendor_id'] = ( int ) $vendor_id;
		}
		
		$key = base64_encode ( serialize ( $product ) );
		
		if (( int ) $qty && (( int ) $qty > 0)) {
			if (! isset ( $this->session->data ['cart'] [$key] )) {
				$this->session->data ['cart'] [$key] = ( int ) $qty;
			} else {
				$this->session->data ['cart'] [$key] += ( int ) $qty;
			}
		}
	}
	public function update($key, $qty) {
		$this->data = array ();
		
		if (( int ) $qty && (( int ) $qty > 0) && isset ( $this->session->data ['cart'] [$key] )) {
			$this->session->data ['cart'] [$key] = ( int ) $qty;
		} else {
			$this->remove ( $key );
		}
	}
	public function remove($key) {
		$this->data = array ();
		
		unset ( $this->session->data ['cart'] [$key] );
	}
	public function clear() {
		$this->data = array ();
		
		$this->session->data ['cart'] = array ();
	}

	public function getWeight($vendor_id = 0) {
		$weight = 0;
		if ($vendor_id) {
			foreach ($this->getProducts() as $product) {
				if ($product['shipping'] && ($product['vendor_id']==$vendor_id)) {
						$weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
				}
			}	
		} else {
			foreach ($this->getProducts() as $product) {
				if ($product['shipping']) {
					$weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
				}
			}
		}
		return $weight;
	}

	public function getSubTotal($vendor_id = 0) {
		$total = 0;
		if ($vendor_id) {
			foreach ($this->getProducts() as $product) {
				if ($vendor_id && ($product['vendor_id']==$vendor_id)) {
					$total += $product['total'];
				} 
			}
		} else {
			foreach ($this->getProducts() as $product) {
				$total += $product['total'];
			}
		}
		return $total;
	}

	public function getTaxes($vendor_id = 0) {
		$tax_data = array();

		if ($vendor_id) {
			foreach ($this->getProducts() as $product) {
				if ($product['vendor_id']==$vendor_id) {
					if ($product['tax_class_id']) {
						$tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);
						foreach ($tax_rates as $tax_rate) {
							if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
								$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
							} else {
								$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
							}
						}
					}
				}
			}
		} else {
			foreach ($this->getProducts() as $product) {
				if ($product['tax_class_id']) {
					$tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);
					foreach ($tax_rates as $tax_rate) {
						if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
							$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
						} else {
							$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
						}
					}
				}
			}
		}
		return $tax_data;
	}

	public function getTotal($vendor_id = 0) {
		$total = 0;
		if ($vendor_id) {
			foreach ($this->getProducts() as $product) {
				if ($product['vendor_id']==$vendor_id) {
					$total += $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
				}
			}
		} else {
			foreach ($this->getProducts() as $product) {
				$total += $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
			}
		}
		return $total;
	}

	public function countProducts($vendor_id = 0) {
		$product_total = 0;
		if ($vendor_id) {
			foreach ($this->getProducts() as $product) {
				if ($product['vendor_id']==$vendor_id) {
					$product_total += $product['quantity'];
				}
			}
		}	 else {
			foreach ($this->getProducts() as $product) {
				$product_total += $product['quantity'];
			}
		}

		return $product_total;
	}
	public function hasProducts() {
		return count ( $this->session->data ['cart'] );
	}
	public function hasRecurringProducts() {
		return count ( $this->getRecurringProducts () );
	}

	public function hasStock($vendor_id = 0) {
		$stock = true;
		if ($vendor_id) {
			foreach ($this->getProducts() as $product) {
				if ($product['vendor_id']==$vendor_id) {
					if (!$product['stock']) {
						$stock = false;
					}
				}
			}
		} else {
			foreach ($this->getProducts() as $product) {
				if (!$product['stock']) {
					$stock = false;
				}

			}
		}
		return $stock;
	}

	public function hasShipping($vendor_id = 0) {
		$shipping = false;
		if ($vendor_id) {
			foreach ($this->getProducts() as $product) {
				if ($product['vendor_id']==$vendor_id) {
					if ($product['shipping']) {
						$shipping = true;
						break;
					}
				}
			}
		} else {
			foreach ($this->getProducts() as $product) {
				if ($product['shipping']) {
					$shipping = true;

					break;
				}
			}
		}
		return $shipping;
	}

	public function hasDownload($vendor_id = 0) {
		$download = false;
		if ($vendor_id) {
			foreach ($this->getProducts() as $product) {
				if ($product['vendor_id']==$vendor_id) {
					if ($product['download']) {
						$download = true;
						break;
					}
				}
			}
		} else {
			foreach ($this->getProducts() as $product) {
				if ($product['download']) {
					$download = true;

					break;
				}
			}
		}
		return $download;
	}
	public function getVendors($vendor_id = 0) {
		$vendors = array();
		foreach ($this->getProducts() as $key => $product) {
				if (!isset($vendors[$product['vendor_id']])) {
						$vendors[$product['vendor_id']]['products'] = array();
				}
				$vendors[$product['vendor_id']]['products'][$key] = $product;
		}
		if ($vendor_id) {
			return $vendors[$vendor_id]['products'];
		} else {
			return $vendors;
		}
	}
	
}
