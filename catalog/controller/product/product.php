<?php
class ControllerProductProduct extends Controller {
	private $error = array ();
	public function index() {
		$this->load->language ( 'product/product' );
		$this->load->language ( 'product/gp_grouped' );
		$this->load->model ( 'checkout/combo_products' );
		$this->load->language ( 'total/combo_products' );
		$this->load->model ( 'catalog/category' );
		$this->load->model ( 'catalog/product' );
		
		if (isset ( $this->request->get ['vendor_id'] )) {
			$vendor_id = $this->request->get ['vendor_id'];
		} else {
			$vendor_id = 0;
		}
		
		if (isset($this->request->get['gpid'])) {
			$gp_vendor_ids = $this->request->get['gpid'];
		} else {
			$gp_vendor_ids = array();
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}
	
		$getCategories = $this->model_catalog_product->getCategories($product_id);
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
			'text' => $this->language->get ( 'text_home' ),
			'href' => $this->url->link ( 'common/home' ) 
		);
		$url = '';
		if (isset ( $this->request->get ['sort'] ))
			$url .= '&sort=' . $this->request->get ['sort'];
		if (isset ( $this->request->get ['order'] ))
			$url .= '&order=' . $this->request->get ['order'];
		if (isset ( $this->request->get ['page'] ))
			$url .= '&page=' . $this->request->get ['page'];
		if (isset ( $this->request->get ['limit'] ))
			$url .= '&limit=' . $this->request->get ['limit'];
		
		$category_id = 0;
		
		if (isset ( $this->request->get ['path'] )) {
			$path = '';
			$parts = explode ( '_', ( string ) $this->request->get ['path'] );
			$category_id = ( int ) array_pop ( $parts );
		}
		
		if (! in_array ( $category_id, $getCategories )) {
			$getCategories = array_shift ( $getCategories );
			$categoryPath = $this->model_catalog_category->getCategoryPath ( $getCategories ['category_id'] );
			foreach ( $categoryPath as $path ) {
				$category_info = $this->model_catalog_category->getCategory ( $path ['path_id'] );
				if ($category_info) {
					$data ['breadcrumbs'] [] = array (
							'text' => $category_info ['name'],
							'href' => $this->url->link ( 'product/category', 'path=' . $category_info ['category_id'] . $url ) 
					);
				}
				unset ( $category_info );
			}
		} else {
			foreach ( $parts as $path_id ) {
				if (! $path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}
				
				$category_info = $this->model_catalog_category->getCategory ( $path_id );
				
				if ($category_info) {
					$data ['breadcrumbs'] [] = array (
							'text' => $category_info ['name'],
							'href' => $this->url->link ( 'product/category', 'path=' . $path ) 
					);
				}
			}
			// Set the last category breadcrumb
			$category_info = $this->model_catalog_category->getCategory ( $category_id );
			
			if ($category_info) {
				
				$data ['breadcrumbs'] [] = array (
						'text' => $category_info ['name'],
						'href' => $this->url->link ( 'product/category', 'path=' . $this->request->get ['path'] . $url ) 
				);
			}
		}
		
		$this->load->model ( 'catalog/manufacturer' );
		
		if (isset ( $this->request->get ['manufacturer_id'] )) {
			$data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'text_brand' ),
					'href' => $this->url->link ( 'product/manufacturer' ) 
			);
			
			$url = '';
			
			if (isset ( $this->request->get ['sort'] )) {
				$url .= '&sort=' . $this->request->get ['sort'];
			}
			
			if (isset ( $this->request->get ['order'] )) {
				$url .= '&order=' . $this->request->get ['order'];
			}
			
			if (isset ( $this->request->get ['page'] )) {
				$url .= '&page=' . $this->request->get ['page'];
			}
			
			if (isset ( $this->request->get ['limit'] )) {
				$url .= '&limit=' . $this->request->get ['limit'];
			}
			
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer ( $this->request->get ['manufacturer_id'] );
			
			if ($manufacturer_info) {
				$data ['breadcrumbs'] [] = array (
						'text' => $manufacturer_info ['name'],
						'href' => $this->url->link ( 'product/manufacturer/info', 'manufacturer_id=' . $this->request->get ['manufacturer_id'] . $url ) 
				);
			}
		}
		
		if (isset ( $this->request->get ['search'] ) || isset ( $this->request->get ['tag'] )) {
			$url = '';
			
			if (isset ( $this->request->get ['search'] )) {
				$url .= '&search=' . $this->request->get ['search'];
			}
			
			if (isset ( $this->request->get ['tag'] )) {
				$url .= '&tag=' . $this->request->get ['tag'];
			}
			
			if (isset ( $this->request->get ['description'] )) {
				$url .= '&description=' . $this->request->get ['description'];
			}
			
			if (isset ( $this->request->get ['category_id'] )) {
				$url .= '&category_id=' . $this->request->get ['category_id'];
			}
			
			if (isset ( $this->request->get ['sub_category'] )) {
				$url .= '&sub_category=' . $this->request->get ['sub_category'];
			}
			
			if (isset ( $this->request->get ['sort'] )) {
				$url .= '&sort=' . $this->request->get ['sort'];
			}
			
			if (isset ( $this->request->get ['order'] )) {
				$url .= '&order=' . $this->request->get ['order'];
			}
			
			if (isset ( $this->request->get ['page'] )) {
				$url .= '&page=' . $this->request->get ['page'];
			}
			
			if (isset ( $this->request->get ['limit'] )) {
				$url .= '&limit=' . $this->request->get ['limit'];
			}
			
			$data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'text_search' ),
					'href' => $this->url->link ( 'product/search', $url ) 
			);
		}
		
		$product_info = $this->model_catalog_product->getProduct($product_id,$vendor_id);
		
		if ($product_info && $product_info['gp_parent_id']) {
				$url = '';
				if (isset($this->request->get['path'])) $url .= '&path=' . $this->request->get['path'];
				if (isset($this->request->get['filter'])) $url .= '&filter=' . $this->request->get['filter'];
				if (isset($this->request->get['manufacturer_id'])) $url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
				if (isset($this->request->get['search'])) $url .= '&search=' . $this->request->get['search'];
				if (isset($this->request->get['tag'])) $url .= '&tag=' . $this->request->get['tag'];
				if (isset($this->request->get['description'])) $url .= '&description=' . $this->request->get['description'];
				if (isset($this->request->get['category_id'])) $url .= '&category_id=' . $this->request->get['category_id'];
				if (isset($this->request->get['sub_category'])) $url .= '&sub_category=' . $this->request->get['sub_category'];
				if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
				if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
				if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
				if (isset($this->request->get['limit'])) $url .= '&limit=' . $this->request->get['limit'];
				$this->response->redirect($this->url->link('product/product', $url . '&product_id=' . $product_info['gp_parent_id']));
		} elseif ($product_info) {
			$this->load->model('account/customerpartner');
			if (!$vendor_id) $vendor_id = $product_info['vendor_id'];
			$url = '';

			if (isset($this->request->get['path'])) $url .= '&path=' . $this->request->get['path'];
			if (isset($this->request->get['filter'])) $url .= '&filter=' . $this->request->get['filter'];
			if (isset($this->request->get['manufacturer_id'])) 	$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			if (isset($this->request->get['search'])) 		$url .= '&search=' . $this->request->get['search'];
			if (isset($this->request->get['tag'])) 			$url .= '&tag=' . $this->request->get['tag'];
			if (isset($this->request->get['description'])) 	$url .= '&description=' . $this->request->get['description'];
			if (isset($this->request->get['category_id'])) 	$url .= '&category_id=' . $this->request->get['category_id'];
			if (isset($this->request->get['sub_category'])) $url .= '&sub_category=' . $this->request->get['sub_category'];
			if (isset($this->request->get['sort'])) 		$url .= '&sort=' . $this->request->get['sort'];
			if (isset($this->request->get['order'])) 		$url .= '&order=' . $this->request->get['order'];
			if (isset($this->request->get['page'])) 		$url .= '&page=' . $this->request->get['page'];
			if (isset($this->request->get['limit'])) 		$url .= '&limit=' . $this->request->get['limit'];
			
			$data['breadcrumbs'][] = array(
				'text' => $product_info['name'],
				'href' => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id'])
			);

			$this->document->setTitle($product_info['meta_title']);
			$this->document->setDescription($product_info['meta_description']);
			$this->document->setKeywords($product_info['meta_keyword']);
			$this->document->setOgURL($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']));

			$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');
			$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

			$data['dbe'] = $this->checkdb();	
			$data['heading_title'] = $product_info['name'];
			$data['text_select'] = $this->language->get('text_select');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_reward'] = $this->language->get('text_reward');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_stock'] = $this->language->get('text_stock');
			$data['text_discount'] = $this->language->get('text_discount');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_option'] = $this->language->get('text_option');
			$data['text_write'] = $this->language->get('text_write');
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
			$data['text_note'] = $this->language->get('text_note');
			$data['text_tags'] = $this->language->get('text_tags');
			$data['text_related'] = $this->language->get('text_related');
			$data['text_loading'] = $this->language->get('text_loading');

			$data['entry_qty'] = $this->language->get('entry_qty');
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_review'] = $this->language->get('entry_review');
			$data['entry_rating'] = $this->language->get('entry_rating');
			$data['entry_good'] = $this->language->get('entry_good');
			$data['entry_bad'] = $this->language->get('entry_bad');

			$data['button_add_to_cart'] = $this->language->get('button_add_to_cart');
			$data['button_add_to_quote'] = $this->language->get('button_add_to_quote');
			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_upload'] = $this->language->get('button_upload');
			$data['button_continue'] = $this->language->get('button_continue');

			$data['button_add_to_quotation'] = $this->language->get('button_add_to_quotation');
			
			$this->load->model('catalog/review');

			$data['tab_description'] = $this->language->get('tab_description');
			$data['tab_attribute'] = $this->language->get('tab_attribute');
			$data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);

			$data['product_id'] = (int)$this->request->get['product_id'];
			$data['manufacturer'] = $product_info['manufacturer'];
			$data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$data['model'] = $product_info['model'];
			$data['reward'] = $product_info['reward'];
			$data['points'] = $product_info['points'];
			$data['curr_vendor'] = array();
			$data['vendors'] = array();


			$this->load->model('tool/image');
			
			$product_info ['image'] = ($product_info ['image'] ? $product_info ['image'] : 'no_image.png');
			if ($product_info ['image']) {
				$data ['popup'] = $this->model_tool_image->resize ( $product_info ['image'], $this->config->get ( 'config_image_popup_width' ), $this->config->get ( 'config_image_popup_height' ) );
			} else {
				$data ['popup'] = '';
			}
			
			if ($product_info ['image']) {
				$data ['thumb'] = $this->model_tool_image->resize ( $product_info ['image'], $this->config->get ( 'config_image_thumb_width' ), $this->config->get ( 'config_image_thumb_height' ) );
			} else {
				$data ['thumb'] = '';
			}
			
			$this->document->setOgImage($this->model_tool_image->resize($product_info['image'], 470, 394));

			$data ['images'] = array ();
			
			$results = $this->model_catalog_product->getProductImages ( $this->request->get ['product_id'] );
			
			foreach ( $results as $result ) {
				$data ['images'] [] = array (
						'popup' => $this->model_tool_image->resize ( $result ['image'], $this->config->get ( 'config_image_popup_width' ), $this->config->get ( 'config_image_popup_height' ) ),
						'thumb' => $this->model_tool_image->resize ( $result ['image'], $this->config->get ( 'config_image_additional_width' ), $this->config->get ( 'config_image_additional_height' ) ) 
				);
			}
			
			$data['enabled'] = false;

			$data['options'] = array();

			$data['review_status'] = $this->config->get('config_review_status');

			if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
				$data['review_guest'] = true;
			} else {
				$data ['review_guest'] = false;
			}
			
			if ($this->customer->isLogged ()) {
				$data ['customer_name'] = $this->customer->getFirstName () . '&nbsp;' . $this->customer->getLastName ();
			} else {
				$data['customer_name'] = '';
			}
			$data['price'] = 0;
			$data['stock'] = $this->language->get('text_notinstock');
			$data['minimum'] = '1';

			if ($is_gp_grouped = $this->model_catalog_product->getGroupedProductGrouped($this->request->get['product_id'])) {
				$data['template'] = $template = $this->config->get('config_template');
				$this->document->addStyle('catalog/view/theme/' . $template . '/stylesheet/gp_grouped_' . $is_gp_grouped['gp_template'] . '.css');
			}
			if (!$is_gp_grouped ) {
				$product_vendor = $this->model_account_customerpartner->getSupplierProduct($product_id,$vendor_id);
				$supplier_options = $this->model_account_customerpartner->getSupplierProductOptions($product_vendor['id']);
				foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
					$product_option_value_data = array();
				
					foreach ($option['product_option_value'] as $option_value) {
						if (!$option_value['subtract'] || (isset($supplier_options[$option_value['product_option_value_id']]) && ($supplier_options[$option_value['product_option_value_id']]['quantity'] > 0))) {
							if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$supplier_options[$option_value['product_option_value_id']]['price']) {
								$price = $this->currency->format($this->tax->calculate($supplier_options[$option_value['product_option_value_id']]['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false));
							} else {
								$price = false;
							}
							$product_option_value_data[] = array(
									'product_option_value_id' => $option_value['product_option_value_id'],
									'option_value_id'         => $option_value['option_value_id'],
									'name'                    => $option_value['name'],
									'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
									'price'                   => $price,
									'quantity'                => $supplier_options[$option_value['product_option_value_id']]['quantity'],
									'price_prefix'            => $option_value['price_prefix'],
									'enabled'            	  => true,
							);
						} else {
							$product_option_value_data[] = array(
									'product_option_value_id' => $option_value['product_option_value_id'],
									'option_value_id'         => $option_value['option_value_id'],
									'name'                    => $option_value['name'],
									'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
									'price'                   => 0,
									'quantity'                => 0,
									'price_prefix'            => $option_value['price_prefix'],
									'enabled'            	  => false
							);
						}
					}
				
					$data['options'][] = array(
							'product_option_id'    => $option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $option['option_id'],
							'name'                 => $option['name'],
							'type'                 => $option['type'],
							'value'                => $option['value'],
							'required'             => $option['required']
					);
				}
				if ($product_vendor){
					$data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_vendor['minimum']);
					if ($product_vendor['quantity'] <= 0) {
						$data['stock'] = $product_vendor['stock_status'];
					} elseif ($this->config->get('config_stock_display')) {
						$data['stock'] = $product_vendor['quantity'];
					} else {
						$data['stock'] = $this->language->get('text_instock');
					}
					
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$data['price'] = $this->currency->format($this->tax->calculate($product_vendor['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
						$data['original_price']  = 0; // Comparing MRP and Supplier Price //
						$data['discount'] = 0;
						
						if ($product_vendor['price'] < $product_info['price']) {
							$data['original_price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
							$data['discount'] = (int)(($product_info['price'] - $product_vendor['price'])*100/$product_info['price']);
						}
					} else {
						$data['price'] = false;
					}
					
					$discounts = $this->model_catalog_product->getSupplierProductDiscounts($product_vendor['id']);
					$data['discounts'] = array();
					foreach ($discounts as $discount) {
						$data['discounts'][] = array(
								'quantity' => $discount['quantity'],
								'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
						);
					}
					
					if ($product_vendor['minimum']) $data['minimum'] = $product_vendor['minimum'];
					else $data['minimum'] = 1;
					
					if ($product_vendor['price']!='0'){
						$data['enabled'] = true;
					}
					
					if ((float)$product_info['special']) $data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
					else $data['special'] = false;
					
					if ($this->config->get('config_tax')) $data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_vendor['price']);
					else $data['tax'] = false;
					
					$this->session->data['total'] = $data['price'];
					
					$data['curr_vendor'] = $this->model_account_customerpartner->getProfile($vendor_id);
					$data['vendors'] = $this->model_account_customerpartner->getProductVendors($product_id,$vendor_id);
						
					if (!empty($data['curr_vendor'])) {
						$data['vlink'] = $this->url->link('customerpartner/profile','id='.$data['curr_vendor']['customer_id'],'SSL');
					}
					
					foreach ($data['vendors'] as $key => $vendor) {
						$data['vendors'][$key]['link'] = $this->url->link('product/product', '&vendor_id='.$vendor['vendor_id'].'&product_id=' . $product_id ,'SSL');
						$data['vendors'][$key]['price'] = $this->currency->format($this->tax->calculate($vendor['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
					}
				}
				
				$data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
				$data['rating'] = (int)$product_info['rating'];
				$data['text_payment_recurring'] = $this->language->get('text_payment_recurring');
				$data['recurrings'] = $this->model_catalog_product->getProfiles($this->request->get['product_id']);
			}
			
			$data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);
				
			$data['products'] = array();
			$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
			foreach ($results as $result) {
				$product_vendor = $this->model_account_customerpartner->getSupplierProduct($result['product_id'],$result['vendor_id']);
				$enabled = false;
				if ($product_vendor) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
					}
					$enabled = true;
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_vendor['price'], $result['tax_class_id'], $this->config->get('config_tax')));
						$original_price  = 0;
						$discount = 0;
						if ($product_vendor['price'] < $result['price']) {
							$original_price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
							$discount = (int)(($result['price'] - $product_vendor['price'])*100/$result['price']);
						}
					} else {
						$price = false;
					}
	
					if ((float)$result['special']) {
						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$special = false;
					}
	
					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $product_vendor['price']);
					} else {
						$tax = false;
					}
	
					if ($this->config->get('config_review_status')) {
						$rating = (int)$result['rating'];
					} else {
						$rating = false;
					}
					if ($price && $is_gp = $this->model_catalog_product->getGroupedProductGrouped($result['product_id'])) {
						$gp_price_min = $is_gp['gp_price_min'];
						$gp_price_max = $is_gp['gp_price_max'];
	
						if ($gp_price_min[0] == '#') {
							$child_info = $this->model_catalog_product->getProduct(substr($gp_price_min,1));
							$gp_price_min = $child_info['special'] ? $child_info['special'] : $child_info['price'];
						}
						if ($gp_price_max[0] == '#') {
							$child_info = $this->model_catalog_product->getProduct(substr($gp_price_max,1));
							$gp_price_max = $child_info['special'] ? $child_info['special'] : $child_info['price'];
						}
	
						if ($gp_price_min && $gp_price_max) {
							$price = $this->language->get('text_gp_price_min') . $this->currency->format($this->tax->calculate($gp_price_min, $result['tax_class_id'], $this->config->get('config_tax'))) . $this->language->get('text_gp_price_max') . $this->currency->format($this->tax->calculate($gp_price_max, $result['tax_class_id'], $this->config->get('config_tax')));
	
							if ($tax) {
								$tax = $this->currency->format($gp_price_min) . '/' . $this->currency->format($gp_price_max);
							}
						} else {
							$price = $this->language->get('text_gp_price_start') . $this->currency->format($this->tax->calculate($gp_price_min, $result['tax_class_id'], $this->config->get('config_tax')));
	
							if ($tax) {
								$tax = $this->currency->format($gp_price_min);
							}
						}
						$result['type'] = '2';
					}
					$data['products'][] = array(
						'product_id'  => $result['product_id'],
						'thumb'       => $image,
						'name'        => $result['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
						'price'       => $price,
						'original_price' => $original_price,
						'discount' => $discount,
						'special'     => $special,
						'tax'         => $tax,
						'enabled'	  => $enabled,
						'type'		  => $result['type'],
						'minimum'     => $product_vendor['minimum'] > 0 ? $product_vendor['minimum'] : 1,
						'rating'      => $rating,
						'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
					);
				}
			}
			
			$data ['tags'] = array ();
			
			if ($product_info ['tag']) {
				$tags = explode ( ',', $product_info ['tag'] );
				
				foreach ( $tags as $tag ) {
					$data ['tags'] [] = array (
							'tag' => trim ( $tag ),
							'href' => $this->url->link ( 'product/search', 'tag=' . trim ( $tag ) ) 
					);
				}
			}

			$this->model_catalog_product->updateViewed($this->request->get['product_id']);

			if ($this->config->get('config_google_captcha_status')) {
				$this->document->addScript('https://www.google.com/recaptcha/api.js');

				$data['site_key'] = $this->config->get('config_google_captcha_public');
			} else {
				$data ['site_key'] = '';
			}

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			$data['ct_right'] = $this->load->controller('common/ct_right');
			
			// combo products //
			
			$getcombos = $this->model_checkout_combo_products->getCombosinclProduct ( $this->request->get ['product_id'] );
			$html = '';
			if ($getcombos) {
				$html .= '<div class="combo-section">';
			}
			foreach ( $getcombos as $combo ) {
				$getcombo = $this->model_checkout_combo_products->getCombo ( $combo ['combo_id'] );
				if ($getcombo ['display_detail']) {
					$html .= $this->returnCombo_HTML ( $getcombo ['combo_id'] );
				}
			}

			if ($getcombos) $html .= '</div>';
			$data['combo'] = $html;
			$data['combo_title'] = $this->language->get('text_combo_header');
			
			//
			if ($is_gp_grouped) {
				// Clear default data
				$data ['model'] = '';
				$data ['text_model'] = '';
				$data ['stock'] = '';
				$data ['text_stock'] = '';
				$data ['reward'] = '';
				$data ['text_reward'] = '';
				$data ['points'] = '';
				$data ['text_points'] = '';
				$data ['discounts'] = array ();
				$data ['price'] = false;
				
				// GP Configuration
				$gp_image_popup_w = $this->config->get ( 'gp_grouped_image_popup_width' );
				$gp_image_popup_h = $this->config->get ( 'gp_grouped_image_popup_height' );
				$gp_image_thumb_w = $this->config->get ( 'gp_grouped_image_thumb_width' );
				$gp_image_thumb_h = $this->config->get ( 'gp_grouped_image_thumb_height' );
				$gp_image_added_w = $this->config->get ( 'gp_grouped_image_added_width' );
				$gp_image_added_h = $this->config->get ( 'gp_grouped_image_added_height' );
				$gp_image_child_w = $this->config->get ( 'gp_grouped_image_child_width' );
				$gp_image_child_h = $this->config->get ( 'gp_grouped_image_child_height' );
				
				$gp_add_child_image = $this->config->get ( 'gp_grouped_add_child_image' );
				$gp_add_child_images = $this->config->get ( 'gp_grouped_add_child_images' );
				$gp_add_child_description = $this->config->get ( 'gp_grouped_add_child_description' );
				
				if ($product_info ['image']) {
					$data ['popup'] = $this->model_tool_image->resize ( $product_info ['image'], $gp_image_popup_w, $gp_image_popup_h );
					$data ['thumb'] = $this->model_tool_image->resize ( $product_info ['image'], $gp_image_thumb_w, $gp_image_thumb_h );
				}
				
				if ($gp_image_child_w && $gp_image_child_h) {
					$gp_child_image_col = true;
				} else {
					$gp_child_image_col = false;
				}
				
				$gp_child_option_col = false;
				
				$child_no_image = array (
						'swap' => $this->model_tool_image->resize ( $product_info ['image'], $gp_image_thumb_w, $gp_image_thumb_h ),
						'popup' => ($gp_child_image_col) ? $this->model_tool_image->resize ( $product_info ['image'], $gp_image_popup_w, $gp_image_popup_h ) : '',
						'thumb' => ($gp_child_image_col) ? $this->model_tool_image->resize ( $product_info ['image'], $gp_image_child_w, $gp_image_child_h ) : '' 
				);
				
				if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
					$tcg_customer_price = true;
				} else {
					$tcg_customer_price = false;
				}
				
				$tcg_tax = $this->config->get ( 'config_tax' );
				
				$this->language->load ( 'product/gp_grouped' );
				
				$data ['text_gp_no_stock'] = $this->language->get ( 'text_gp_no_stock' );
				$data ['text_gp_total'] = $this->language->get ( 'text_gp_total' );
				
				$data ['gp_child_info'] = array ();
				if ($this->config->get ( 'gp_grouped_child_info' )) {
					foreach ( $this->config->get ( 'gp_grouped_child_info' ) as $field ) {
						$data ['gp_child_info'] [$field] = $this->language->get ( 'text_gp_child_' . $field );
					}
				}

				$gp_child_attributes = $this->config->get('gp_grouped_child_attribute');
				$attributenames = array();
				$agnames = array();
				$data['childs'] = array();
				$product_grouped = $this->model_catalog_product->getGroupedProductGroupedChilds($product_id);
					foreach ($product_grouped as $child) {
						$child_info = $this->model_catalog_product->getProduct($child['child_id']);
						
						if ($child_info) {
							$enable = false;
							if (isset($gp_vendor_ids[$child['child_id']])) $gp_vendor_id =  $gp_vendor_ids[$child['child_id']];
							else $gp_vendor_id =  $child_info['vendor_id'];
							
							$child_supplier_info = $this->model_account_customerpartner->getSupplierProduct($child['child_id'],$gp_vendor_id);
							
							if ($child_info['image']) {
								$child_image = array(
									'swap' => $this->model_tool_image->resize($child_info['image'], $gp_image_thumb_w, $gp_image_thumb_h),
									'popup' => ($gp_child_image_col) ? $this->model_tool_image->resize($child_info['image'], $gp_image_popup_w, $gp_image_popup_h) : '',
								);
							}
							if ($gp_add_child_images) {
								$results = $this->model_catalog_product->getProductImages ( $child ['child_id'] );
								foreach ( $results as $result ) {
									$data ['images'] [] = array (
											'popup' => $this->model_tool_image->resize ( $result ['image'], $gp_image_popup_w, $gp_image_popup_h ),
											'thumb' => $this->model_tool_image->resize ( $result ['image'], $gp_image_added_w, $gp_image_added_h ),
											'name' => $child_info ['name'] 
									);
								}
								if ($gp_add_child_images) {
									$results = $this->model_catalog_product->getProductImages($child['child_id']);
									foreach ($results as $result) {
										$data['images'][] = array(
											'popup' => $this->model_tool_image->resize($result['image'], $gp_image_popup_w, $gp_image_popup_h),
											'thumb' => $this->model_tool_image->resize($result['image'], $gp_image_added_w, $gp_image_added_h),
											'name'  => $child_info['name']
										);
									}
								}
							} else {
								$child_image = $child_no_image;
							}

							if ($gp_add_child_description && $child_info['description']) { 
								$data['description'] .= '<ul class="gp-add-child-description"><li>' . $child_info['name'] . '</li><li>' . html_entity_decode($child_info['description'], ENT_QUOTES, 'UTF-8') . '</li></ul>';
							}

							if ((float)$child_info['special']) {
								$child_special = $this->currency->format($this->tax->calculate($child_info['special'], $child_info['tax_class_id'], $tcg_tax));
							} else {
								$child_special = false;
							}
							$child_price = false;
							$child_tax = false;
							$child_discounts = array();
							if ($this->config->get('config_stock_display')) {
								$child_info['stock'] = $child_supplier_info['quantity'];
							} else {
								$child_info['stock'] = $this->language->get('text_instock');
							}
							$enabled = false;
							$curr_vendor = array();
							$vendors = array();
							$vlink = '';
							$supplier_options = array();
							
							if ($child_supplier_info) {
								$enable = true;
								if ($child_supplier_info['quantity'] <= 0)
									$child_info['stock'] = $child_supplier_info['stock_status'];
								
								if ($tcg_customer_price) 
									$child_price = $this->currency->format($this->tax->calculate($child_supplier_info['price'], $child_info['tax_class_id'], $tcg_tax));

								if ($tcg_tax)
									$child_tax = $this->currency->format((float)$child_info['special'] ? $child_supplier_info['special'] : $child_info['price']);
	
								
								foreach ($this->model_catalog_product->getSupplierProductDiscounts($child_supplier_info['id']) as $discount) {
									$child_discounts[] = array(
											'quantity' => $discount['quantity'],
											'price'    => $this->currency->format($this->tax->calculate($discount['price'], $child_info['tax_class_id'], $tcg_tax))
									);
								}
								
								$curr_vendor = $this->model_account_customerpartner->getProfile($gp_vendor_id);
								$vendors = $this->model_account_customerpartner->getProductVendors($child_info['product_id'],$gp_vendor_id);
								if (!empty($curr_vendor)) {
									$vlink = $this->url->link('customerpartner/profile','id='.$curr_vendor['customer_id'],'SSL');
								}
								$enabled = true;
								$supplier_options = $this->model_account_customerpartner->getSupplierProductOptions($child_supplier_info['id']);
							}
							// Disable button cart
							if ($this->config->get('gp_grouped_child_nocart') && !$this->config->get('config_stock_checkout') && $child_info['quantity'] <= 0) {
								$child_child_nocart = true;
							} else {
								$child_child_nocart = false;
							}

							if ($gp_child_attributes) {
								$child_attributes = $this->model_catalog_product->getProductAttributes($child_info['product_id']);
							} else {
								$child_attributes = array();
							}

							$child_options = array();

							foreach ($this->model_catalog_product->getProductOptions($child['child_id']) as $option) {
								$gp_child_option_col = true;
								$product_option_value_data = array();

								foreach ($option['product_option_value'] as $option_value) {
									if (!$option_value['subtract'] || (isset($supplier_options[$option_value['product_option_value_id']]) && ($supplier_options[$option_value['product_option_value_id']]['quantity'] > 0))) {
										if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$supplier_options[$option_value['product_option_value_id']]['price']) {
											$price = $this->currency->format($this->tax->calculate($supplier_options[$option_value['product_option_value_id']]['price'], $child_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false));
										} else {
											$price = false;
										}
										$product_option_value_data[] = array(
											'product_option_value_id' => $option_value['product_option_value_id'],
											'option_value_id'         => $option_value['option_value_id'],
											'name'                    => $option_value['name'],
											'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
											'price'                   => $price,
											'quantity'                => $supplier_options[$option_value['product_option_value_id']]['quantity'],
											'price_prefix'            => $option_value['price_prefix'],
											'enabled'            	  => true,
										);
									} else {
										$product_option_value_data [] = array (
											'product_option_value_id' => $option_value['product_option_value_id'],
											'option_value_id'         => $option_value['option_value_id'],
											'name'                    => $option_value['name'],
											'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
											'price'                   => 0,
											'quantity'                => 0,
											'price_prefix'            => $option_value['price_prefix'],
											'enabled'            	  => false
										);
									}
								}
								
								$child_options[] = array(
										'product_option_id'    => $option['product_option_id'],
										'product_option_value' => $product_option_value_data,
										'option_id'            => $option['option_id'],
										'name'                 => $option['name'],
										'type'                 => $option['type'],
										'value'                => $option['value'],
										'required'             => $option['required']
								);
								
							}

							$qty_now = '';
							foreach ( $this->cart->getProducts () as $gp_cart ) {
								if ($child ['child_id'] == $gp_cart ['product_id']) {
									$qty_now = $gp_cart ['quantity'];
								}
							}
							
							foreach ($child_attributes as $key => $ag){
								$attributenames = array();
								if (!isset($agnames[$key]['a'])) $agnames[$key]['a'] = array();
								$agnames[$key]['name'] = $ag['name'];
								foreach ($ag['attribute'] as $key2 => $a) {$agnames[$key]['a'][$key2] = $a['name'];} 
							}
							
							$data['childs'][$child_info['product_id']] = array(
								'child_id'   => $child_info['product_id'],
								'info'       => $child_info,
								'image'      => $child_image,
								'name'       => str_replace($product_info['name'], '', $child_info['name']),
								'attributes' => $child_attributes,
								'price'      => $child_price,
								'special'    => $child_special,
								'tax'        => $child_tax,
								'nocart'     => $child_child_nocart,
								'options'    => $child_options,
								'discounts'  => $child_discounts,
								'qty_now'    => $qty_now,
								'enabled'	  => $enabled,
								'curr_vendor'=> $curr_vendor,
								'vlink'		 => $vlink,
								'vendors'	 => $vendors 	
							);
						}
					}
				// Column
				// $data['attributenames'] = $attributenames; edit for filtering
				$data ['agnames'] = $agnames;
				$data ['column_gp_image'] = $gp_child_image_col ? $this->language->get ( 'column_gp_image' ) : false;
				$data ['column_gp_name'] = $this->language->get ( 'column_gp_name' );
				$data ['column_gp_price'] = $tcg_customer_price ? $this->language->get ( 'column_gp_price' ) : false;
				$data ['column_gp_option'] = $gp_child_option_col ? $this->language->get ( 'column_gp_option' ) : false;
				$data ['column_gp_qty'] = $this->language->get ( 'column_gp_qty' );
				
				if (file_exists ( DIR_TEMPLATE . $template . '/template/product/gp_grouped_' . $is_gp_grouped ['gp_template'] . '.tpl' )) {
					$this->response->setOutput ( $this->load->view ( $template . '/template/product/gp_grouped_' . $is_gp_grouped ['gp_template'] . '.tpl', $data ) );
				} else {
					$this->response->setOutput ( $this->load->view ( 'default/template/product/gp_grouped_' . $is_gp_grouped ['gp_template'] . '.tpl', $data ) );
				}
			} elseif (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/product/product.tpl' )) {
				$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/product/product.tpl', $data ) );
			} else {
				$this->response->setOutput ( $this->load->view ( 'default/template/product/product.tpl', $data ) );
			}
		} else {
			$url = '';
			
			if (isset ( $this->request->get ['path'] )) {
				$url .= '&path=' . $this->request->get ['path'];
			}
			
			if (isset ( $this->request->get ['filter'] )) {
				$url .= '&filter=' . $this->request->get ['filter'];
			}
			
			if (isset ( $this->request->get ['manufacturer_id'] )) {
				$url .= '&manufacturer_id=' . $this->request->get ['manufacturer_id'];
			}
			
			if (isset ( $this->request->get ['search'] )) {
				$url .= '&search=' . $this->request->get ['search'];
			}
			
			if (isset ( $this->request->get ['tag'] )) {
				$url .= '&tag=' . $this->request->get ['tag'];
			}
			
			if (isset ( $this->request->get ['description'] )) {
				$url .= '&description=' . $this->request->get ['description'];
			}
			
			if (isset ( $this->request->get ['category_id'] )) {
				$url .= '&category_id=' . $this->request->get ['category_id'];
			}
			
			if (isset ( $this->request->get ['sub_category'] )) {
				$url .= '&sub_category=' . $this->request->get ['sub_category'];
			}
			
			if (isset ( $this->request->get ['sort'] )) {
				$url .= '&sort=' . $this->request->get ['sort'];
			}
			
			if (isset ( $this->request->get ['order'] )) {
				$url .= '&order=' . $this->request->get ['order'];
			}
			
			if (isset ( $this->request->get ['page'] )) {
				$url .= '&page=' . $this->request->get ['page'];
			}
			
			if (isset ( $this->request->get ['limit'] )) {
				$url .= '&limit=' . $this->request->get ['limit'];
			}
			
			$data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'text_error' ),
					'href' => $this->url->link ( 'product/product', $url . '&product_id=' . $product_id ) 
			);
			
			$this->document->setTitle ( $this->language->get ( 'text_error' ) );
			
			$data ['heading_title'] = $this->language->get ( 'text_error' );
			
			$data ['text_error'] = $this->language->get ( 'text_error' );
			
			$data ['button_continue'] = $this->language->get ( 'button_continue' );
			
			$data ['continue'] = $this->url->link ( 'common/home' );
			
			$this->response->addHeader ( $this->request->server ['SERVER_PROTOCOL'] . ' 404 Not Found' );
			
			$data ['column_left'] = $this->load->controller ( 'common/column_left' );
			$data ['column_right'] = $this->load->controller ( 'common/column_right' );
			$data ['content_top'] = $this->load->controller ( 'common/content_top' );
			$data ['content_bottom'] = $this->load->controller ( 'common/content_bottom' );
			$data ['footer'] = $this->load->controller ( 'common/footer' );
			
			if ($is_gp_grouped = $this->model_catalog_product->getGroupedProductGrouped ( $this->request->get ['product_id'] )) {
				$data ['template'] = $template = $this->config->get ( 'config_template' );
				$this->document->addStyle ( 'catalog/view/theme/' . $template . '/stylesheet/gp_grouped_' . $is_gp_grouped ['gp_template'] . '.css' );
			}
			
			$data ['header'] = $this->load->controller ( 'common/header' );
			$data ['ct_right'] = $this->load->controller ( 'common/ct_right' );
			
			if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/error/not_found.tpl' )) {
				$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/error/not_found.tpl', $data ) );
			} else {
				$this->response->setOutput ( $this->load->view ( 'default/template/error/not_found.tpl', $data ) );
			}
		}
	}
	public function review() {
		$this->load->language ( 'product/product' );
		
		$this->load->model ( 'catalog/review' );
		
		$data ['text_no_reviews'] = $this->language->get ( 'text_no_reviews' );
		
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		$data ['reviews'] = array ();
		
		$review_total = $this->model_catalog_review->getTotalReviewsByProductId ( $this->request->get ['product_id'] );
		
		$results = $this->model_catalog_review->getReviewsByProductId ( $this->request->get ['product_id'], ($page - 1) * 5, 5 );
		
		foreach ( $results as $result ) {
			$data ['reviews'] [] = array (
					'author' => $result ['author'],
					'text' => nl2br ( $result ['text'] ),
					'rating' => ( int ) $result ['rating'],
					'date_added' => date ( $this->language->get ( 'date_format_short' ), strtotime ( $result ['date_added'] ) ) 
			);
		}
		
		$pagination = new Pagination ();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$pagination->url = $this->url->link ( 'product/product/review', 'product_id=' . $this->request->get ['product_id'] . '&page={page}' );
		
		$data ['pagination'] = $pagination->render ();
		
		$data ['results'] = sprintf ( $this->language->get ( 'text_pagination' ), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil ( $review_total / 5 ) );
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/product/review.tpl' )) {
			$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/product/review.tpl', $data ) );
		} else {
			$this->response->setOutput ( $this->load->view ( 'default/template/product/review.tpl', $data ) );
		}
	}
	public function write() {
		$this->load->language ( 'product/product' );
		
		$json = array ();
		
		if ($this->request->server ['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen ( $this->request->post ['name'] ) < 3) || (utf8_strlen ( $this->request->post ['name'] ) > 25)) {
				$json ['error'] = $this->language->get ( 'error_name' );
			}
			
			if ((utf8_strlen ( $this->request->post ['text'] ) < 25) || (utf8_strlen ( $this->request->post ['text'] ) > 1000)) {
				$json ['error'] = $this->language->get ( 'error_text' );
			}
			
			if (empty ( $this->request->post ['rating'] ) || $this->request->post ['rating'] < 0 || $this->request->post ['rating'] > 5) {
				$json ['error'] = $this->language->get ( 'error_rating' );
			}
			
			if ($this->config->get ( 'config_google_captcha_status' ) && empty ( $json ['error'] )) {
				if (isset ( $this->request->post ['g-recaptcha-response'] )) {
					$recaptcha = file_get_contents ( 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode ( $this->config->get ( 'config_google_captcha_secret' ) ) . '&response=' . $this->request->post ['g-recaptcha-response'] . '&remoteip=' . $this->request->server ['REMOTE_ADDR'] );
					
					$recaptcha = json_decode ( $recaptcha, true );
					
					if (! $recaptcha ['success']) {
						$json ['error'] = $this->language->get ( 'error_captcha' );
					}
				} else {
					$json ['error'] = $this->language->get ( 'error_captcha' );
				}
			}
			
			if (! isset ( $json ['error'] )) {
				$this->load->model ( 'catalog/review' );
				
				$this->model_catalog_review->addReview ( $this->request->get ['product_id'], $this->request->post );
				
				$json ['success'] = $this->language->get ( 'text_success' );
			}
		}
		
		$this->response->addHeader ( 'Content-Type: application/json' );
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function getRecurringDescription() {
		$this->language->load ( 'product/product' );
		$this->load->model ( 'catalog/product' );
		
		if (isset ( $this->request->post ['product_id'] )) {
			$product_id = $this->request->post ['product_id'];
		} else {
			$product_id = 0;
		}
		
		if (isset ( $this->request->post ['recurring_id'] )) {
			$recurring_id = $this->request->post ['recurring_id'];
		} else {
			$recurring_id = 0;
		}
		
		if (isset ( $this->request->post ['quantity'] )) {
			$quantity = $this->request->post ['quantity'];
		} else {
			$quantity = 1;
		}
		
		$product_info = $this->model_catalog_product->getProduct ( $product_id, $vendor_id );
		$recurring_info = $this->model_catalog_product->getProfile ( $product_id, $recurring_id );
		
		$json = array ();
		
		if ($product_info && $recurring_info) {
			if (! $json) {
				$frequencies = array (
						'day' => $this->language->get ( 'text_day' ),
						'week' => $this->language->get ( 'text_week' ),
						'semi_month' => $this->language->get ( 'text_semi_month' ),
						'month' => $this->language->get ( 'text_month' ),
						'year' => $this->language->get ( 'text_year' ) 
				);
				
				if ($recurring_info ['trial_status'] == 1) {
					$price = $this->currency->format ( $this->tax->calculate ( $recurring_info ['trial_price'] * $quantity, $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
					$trial_text = sprintf ( $this->language->get ( 'text_trial_description' ), $price, $recurring_info ['trial_cycle'], $frequencies [$recurring_info ['trial_frequency']], $recurring_info ['trial_duration'] ) . ' ';
				} else {
					$trial_text = '';
				}
				
				$price = $this->currency->format ( $this->tax->calculate ( $recurring_info ['price'] * $quantity, $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
				
				if ($recurring_info ['duration']) {
					$text = $trial_text . sprintf ( $this->language->get ( 'text_payment_description' ), $price, $recurring_info ['cycle'], $frequencies [$recurring_info ['frequency']], $recurring_info ['duration'] );
				} else {
					$text = $trial_text . sprintf ( $this->language->get ( 'text_payment_cancel' ), $price, $recurring_info ['cycle'], $frequencies [$recurring_info ['frequency']], $recurring_info ['duration'] );
				}
				
				$json ['success'] = $text;
			}
		}
		
		$this->response->addHeader ( 'Content-Type: application/json' );
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function returnCombo_HTML($combo_id) {
		$this->load->language ( 'total/combo_products' );
		$this->load->model ( 'catalog/product' );
		$this->load->model ( 'checkout/combo_products' );
		
		$getcombo = $this->model_checkout_combo_products->getCombo ( $combo_id );
		
		$products_in_combo = explode ( ",", $getcombo ['product_id'] );
		
		$price_total = 0;
		$price_ori = 0;
		$price_all = 0;
		
		$wishlist_combo = array ();
		$wishlist_combo_unique = array ();
		$cart_combo = array ();
		
		foreach ( $products_in_combo as $product_id ) {
			$product_info = $this->model_catalog_product->getProduct ( $product_id );
			$this->load->model ( 'tool/image' );
			
			$href = $this->url->link ( 'product/product', 'product_id=' . $product_info ['product_id'] );
			
			if ($getcombo ['override'])
				$price = $this->currency->format ( $this->tax->calculate ( $product_info ['price'], $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
			elseif ($product_info ['special'])
				$price = $this->currency->format ( $this->tax->calculate ( $product_info ['special'], $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
			else
				$price = $this->currency->format ( $this->tax->calculate ( $product_info ['price'], $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
			
			if ($getcombo ['override'])
				$price_total += $this->tax->calculate ( $product_info ['price'], $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) );
			elseif ($product_info ['special'])
				$price_total += $this->tax->calculate ( $product_info ['special'], $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) );
			else
				$price_total += $this->tax->calculate ( $product_info ['price'], $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) );
			
			$price_ori += $product_info ['price'];
			
			if ($product_info ['image']) {
				$html = '<div class="combo-item col-sm-3">';
				$html .= '<div class="row">';
				$html .= '	<div class="col-sm-6">';
				$html .= '		<div class="combo-item-img">';
				$html .= '			<a href="' . $href . '"><img class="img-thumbnail" src="' . $this->model_tool_image->resize ( $product_info ['image'], 100, 100 ) . '"></a>';
				$html .= '		</div>';
				$html .= '	</div>';
				$html .= '	<div class="col-sm-6">';
				$html .= '		<div class="combo-item-name"><h4>' . $product_info ['name'] . '</h4></div>';
				$html .= '		<div class="combo-item-price">' . $price . '</div>';
				$html .= '	</div>';
				$html .= '</div>';
				$html .= '</div>';
				$product_array [] = $html;
			} else
				$product_array [] = '<div class="combo-item"><div class="combo-item-img"><a href="' . $href . '"><img class="img-thumbnail" src="' . $this->model_tool_image->resize ( 'no_image.png', 80, 80 ) . '"></a></div><div class="combo-item-name">' . $product_info ['name'] . '</div><div class="combo-item-price">' . $price . '</div></div>';
			
			$wishlist_combo [] = 'wishlist_combo.add(\'' . $product_id . '\')';
			$cart_combo [] = 'cart_combo.add(\'' . $product_id . '\')';
		}
		
		$wishlist_combo_unique = array_unique ( $wishlist_combo );
		
		if ($getcombo ['discount_type'] == 'fixed amount') {
			$price_discount = $price_total - $getcombo ['discount_number'];
			$discount = $this->currency->format ( $getcombo ['discount_number'] );
			$discount_save = $this->language->get ( 'text_save' ) . ' ' . $discount;
		} else {
			$price_discount = $price_total - ($price_ori / 100) * $getcombo ['discount_number'];
			$discount = $getcombo ['discount_number'] . '%';
			$discount_save = $this->language->get ( 'text_save' ) . ' ' . $discount;
		}
		$price_all = '<div class="combo-save">'.$this->language->get('text_price_all').': <span class="price_discount">'.$this->currency->format($price_discount).'</span></br>('.$discount_save.')</div>';
		$wishlist_button = '<div class="pull-left"><button data-original-title="'.$this->language->get('text_add_wishlist').'" type="button" data-toggle="tooltip" class="btn" title="" onclick="'.implode(";",$wishlist_combo_unique).'">'.$this->language->get('text_add_wishlist').'</button>';
		$cart_button = '<button data-original-title="'.$this->language->get('text_add_cart').'" type="button" data-toggle="tooltip" class="btn btn-primary" title="" onclick="'.implode(";",$cart_combo).'">'.$this->language->get('text_add_cart').'</button></div>';
	
		$html = '<div id="combo-'.$combo_id.'" class="combo-set">';
		$html .= '<div class="combo-contain">'.implode(' <div class="combo-plus"> + </div> ',$product_array);
		$html .= $price_all.'</div><div class="combo-action">'.$wishlist_button.$cart_button.'</div>';
		$html .= '</div><div class="clearfix"></div>';
		return $html;
	}
	private function checkdb() {
		if (! $this->customer->isLogged ()) {
			return false;
		}
		$cgs = $this->config->get ( 'cd_customer_group' );
		
		if (! (in_array ( $this->customer->getGroupId (), $cgs ))) {
			return false;
		}
		return true;
	}
	
	public function getGProw(){
		if (isset($this->request->get['child_id'])) {
			
			
		$this->load->model ( 'catalog/product' );
		$this->load->model('account/customerpartner');
		$child_id = $this->request->get['child_id'];
		
		
		$child_info = $this->model_catalog_product->getProduct($child_id);
			if ($child_info) {
				$this->load->language ( 'product/product' );
				$this->load->language ( 'product/gp_grouped' );
				$data['button_add_to_cart'] = $this->language->get('button_add_to_cart');
				$data['button_add_to_quote'] = $this->language->get('button_add_to_quote');
				$data['text_loading'] = $this->language->get('text_loading');
				$data['text_discount'] = $this->language->get('text_discount');
				
				$this->load->model('tool/image');
				$enable = false;
				if (isset($this->request->get['vendor_id'])) {
					$gp_vendor_id = $this->request->get['vendor_id'];
				} else {
					$gp_vendor_id = $child_info['vendor_id'];;
				}
							
				$child_supplier_info = $this->model_account_customerpartner->getSupplierProduct($child_id,$gp_vendor_id);
					
			
				if ((float)$child_info['special']) {
					$child_special = $this->currency->format($this->tax->calculate($child_info['special'], $child_info['tax_class_id'], $tcg_tax));
				} else {
					$child_special = false;
				}
				$child_price = false;
				$child_tax = false;
				$child_discounts = array();
				if ($this->config->get('config_stock_display')) {
					$child_info['stock'] = $child_supplier_info['quantity'];
				} else {
					$child_info['stock'] = $this->language->get('text_instock');
				}
				$enabled = false;
				$curr_vendor = array();
				$vendors = array();
				$vlink = '';
				$supplier_options = array();
				$gp_child_option_col = false;
				if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
					$tcg_customer_price = true;
					$data['column_gp_price'] = true;
				} else {
					$tcg_customer_price = false;
					$data['column_gp_price'] = true;
				}
				
				if ($child_supplier_info) {
					$enable = true;
					if ($child_supplier_info['quantity'] <= 0)	$child_info['stock'] = $child_supplier_info['stock_status'];
			
					$child_price = $this->currency->format($this->tax->calculate($child_supplier_info['price'], $child_info['tax_class_id'],true));
					$child_tax = $this->currency->format((float)$child_info['special'] ? $child_supplier_info['special'] : $child_info['price']);
			
			
					foreach ($this->model_catalog_product->getSupplierProductDiscounts($child_supplier_info['id']) as $discount) {
						$child_discounts[] = array(
								'quantity' => $discount['quantity'],
								'price'    => $this->currency->format($this->tax->calculate($discount['price'], $child_info['tax_class_id'], true))
						);
					}

					$curr_vendor = $this->model_account_customerpartner->getProfile($gp_vendor_id);
					$vendors = $this->model_account_customerpartner->getProductVendors($child_info['product_id'],$gp_vendor_id);
					if (!empty($curr_vendor)) {
						$vlink = $this->url->link('customerpartner/profile&id='.$curr_vendor['customer_id'],'','SSL');
					}
					$enabled = true;
					$supplier_options = $this->model_account_customerpartner->getSupplierProductOptions($child_supplier_info['id']);
				}
				// Disable button cart
				if ($this->config->get('gp_grouped_child_nocart') && !$this->config->get('config_stock_checkout') && $child_info['quantity'] <= 0) {
					$child_child_nocart = true;
				} else {
					$child_child_nocart = false;
				}
			
				$child_options = array();
				
				foreach ($this->model_catalog_product->getProductOptions($child_id) as $option) {
					$gp_child_option_col = true;
					$product_option_value_data = array();
					foreach ($option['product_option_value'] as $option_value) {
						if (!$option_value['subtract'] || (isset($supplier_options[$option_value['product_option_value_id']]) && ($supplier_options[$option_value['product_option_value_id']]['quantity'] > 0))) {
							if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$supplier_options[$option_value['product_option_value_id']]['price']) {
								$price = $this->currency->format($this->tax->calculate($supplier_options[$option_value['product_option_value_id']]['price'], $child_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false));
							} else {
								$price = false;
							}
							$product_option_value_data[] = array(
									'product_option_value_id' => $option_value['product_option_value_id'],
									'option_value_id'         => $option_value['option_value_id'],
									'name'                    => $option_value['name'],
									'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
									'price'                   => $price,
									'quantity'                => $supplier_options[$option_value['product_option_value_id']]['quantity'],
									'price_prefix'            => $option_value['price_prefix'],
									'enabled'            	  => true,
							);
						} else {
							$product_option_value_data [] = array (
									'product_option_value_id' => $option_value['product_option_value_id'],
									'option_value_id'         => $option_value['option_value_id'],
									'name'                    => $option_value['name'],
									'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
									'price'                   => 0,
									'quantity'                => 0,
									'price_prefix'            => $option_value['price_prefix'],
									'enabled'            	  => false
							);
						}
					}
			
					$child_options[] = array(
							'product_option_id'    => $option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $option['option_id'],
							'name'                 => $option['name'],
							'type'                 => $option['type'],
							'value'                => $option['value'],
							'required'             => $option['required']
					);
					
				}
				$data ['column_gp_option'] = $gp_child_option_col ? true : false;
				
				$qty_now = '';
				foreach ( $this->cart->getProducts () as $gp_cart ) {
					if ($child_id == $gp_cart ['product_id'] && $gp_vendor_id == $gp_cart ['vendor_id'] ) {
						$qty_now = $gp_cart ['quantity'];
					}
				}
				foreach ($vendors as $key => $vendor) {
					$vendors[$key]['price'] = $this->currency->format($this->tax->calculate($vendor['price'], $child_info['tax_class_id'], $this->config->get('config_tax')));
				}
				$data['child'] = array(
					'child_id'   => $child_info['product_id'],
					'price'      => $child_price,
					'special'    => $child_special,
					'tax'        => $child_tax,
					'nocart'     => $child_child_nocart,
					'options'    => ($child_options?$child_options:false),
					'discounts'  => ($child_discounts?$child_discounts:false),
					'qty_now'    => $qty_now,
					'enabled'	  => $enabled,
					'curr_vendor'=> $curr_vendor,
					'vlink'		 => $vlink,
					'vendors'	 => $vendors 
				);
			}			
			$this->response->setOutput(json_encode($data));
		}
	}
	
	public function getGpProducts() {
		$data = array ();
		$this->load->language ( 'product/product' );
		$this->load->language ( 'product/gp_grouped' );
		$this->load->model ( 'checkout/combo_products' );
		$this->load->language ( 'total/combo_products' );
		$this->load->model ( 'catalog/category' );
		$this->load->model ( 'catalog/product' );
		$this->load->model('tool/image');
		
		if (isset ( $this->request->get ['product_id'] )) {
			$product_id = ( int ) $this->request->get ['product_id'];
		} else {
			$product_id = 0;
		}
		$gp_child_attributes = $this->config->get ( 'gp_grouped_child_attribute' );
		$attributenames = array ();
		$agnames = array ();
		$data ['childs'] = array ();
		
		if ($is_gp_grouped = $this->model_catalog_product->getGroupedProductGrouped ( $this->request->get ['product_id'] )) {
			$data ['template'] = $template = $this->config->get ( 'config_template' );
			$this->document->addStyle ( 'catalog/view/theme/' . $template . '/stylesheet/gp_grouped_' . $is_gp_grouped ['gp_template'] . '.css' );
		}
		
		$product_grouped = $this->model_catalog_product->getGroupedProductGroupedChilds ( $product_id );
		if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
			$tcg_customer_price = true;
		} else {
			$tcg_customer_price = false;
		}
		$gp_child_option_col = false;
		
		$tcg_tax = $this->config->get ( 'config_tax' );
		
		$this->language->load ( 'product/gp_grouped' );
		foreach ( $product_grouped as $child ) {
			
		$this->language->load('product/gp_grouped');		
		foreach ($product_grouped as $child) {
			$child_info = $this->model_catalog_product->getProduct($child['child_id']);
		

			if ($child_info) {
				if ($tcg_customer_price) {
					$child_price = $this->currency->format ( $this->tax->calculate ( $child_info ['price'], $child_info ['tax_class_id'], $tcg_tax ) );
				} else {
					$child_price = false;
				}
				
				if (( float ) $child_info ['special']) {
					$child_special = $this->currency->format ( $this->tax->calculate ( $child_info ['special'], $child_info ['tax_class_id'], $tcg_tax ) );
				} else {
					$child_special = false;
				}
				
				if ($tcg_tax) {
					$child_tax = $this->currency->format ( ( float ) $child_info ['special'] ? $child_info ['special'] : $child_info ['price'] );
				} else {
					$child_tax = false;
				}
				
				$child_discounts = array ();
				
				foreach ( $this->model_catalog_product->getProductDiscounts ( $child_info ['product_id'] ) as $discount ) {
					$child_discounts [] = array (
							'quantity' => $discount ['quantity'],
							'price' => $this->currency->format ( $this->tax->calculate ( $discount ['price'], $child_info ['tax_class_id'], $tcg_tax ) ) 
					);
				}
				
				// Disable button cart
				if ($this->config->get ( 'gp_grouped_child_nocart' ) && ! $this->config->get ( 'config_stock_checkout' ) && $child_info ['quantity'] <= 0) {
					$child_child_nocart = true;
				} else {
					$child_child_nocart = false;
				}
				
				if ($gp_child_attributes) {
					$child_attributes = $this->model_catalog_product->getProductAttributes ( $child_info ['product_id'] );
				} else {
					$child_attributes = array ();
				}
				
				$child_options = array ();
				
				foreach ( $this->model_catalog_product->getProductOptions ( $child ['child_id'] ) as $option ) {
					$gp_child_option_col = true;
					
					$product_option_value_data = array ();
					
					foreach ( $option ['product_option_value'] as $option_value ) {
						if (! $option_value ['subtract'] || ($option_value ['quantity'] > 0)) {
							if ($tcg_customer_price && ( float ) $option_value ['price']) {
								$child_option_price = $this->currency->format ( $this->tax->calculate ( $option_value ['price'], $child_info ['tax_class_id'], $tcg_tax ? 'P' : false ) );
							} else {
								$child_option_price = false;
							}
							
							$product_option_value_data [] = array (
									'product_option_value_id' => $option_value ['product_option_value_id'],
									'option_value_id' => $option_value ['option_value_id'],
									'name' => $option_value ['name'],
									'image' => $this->model_tool_image->resize ( $option_value ['image'], 50, 50 ),
									'price' => $child_option_price,
									'price_prefix' => $option_value ['price_prefix'] 
							);
						}
					}
					
					$child_options [] = array (
							'product_option_id' => $option ['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id' => $option ['option_id'],
							'name' => $option ['name'],
							'type' => $option ['type'],
							'value' => $option ['value'],
							'required' => $option ['required'] 
					);
				}
				
				$qty_now = '';
				foreach ( $this->cart->getProducts () as $gp_cart ) {
					if ($child ['child_id'] == $gp_cart ['product_id']) {
						$qty_now = $gp_cart ['quantity'];
					}
				}
				
				foreach ( $child_attributes as $key => $ag ) {
					$attributenames = array ();
					if (! isset ( $agnames [$key] ['a'] ))
						$agnames [$key] ['a'] = array ();
					$agnames [$key] ['name'] = $ag ['name'];
					foreach ( $ag ['attribute'] as $key2 => $a ) {
						$agnames [$key] ['a'] [$key2] = $a ['name'];
					}
				}
				/*
				 * $curr_vendor = $this->model_account_customerpartner->getProfile($child_info['vendor_id']);
				 * $vendors = $this->model_account_customerpartner->getProductVendors($child_info['product_id'],$child_info['vendor_id']);
				 *
				 * if (!empty($curr_vendor)) {
				 * $vlink = $this->url->link('customerpartner/profile','id='.$curr_vendor['customer_id'],'SSL');
				 * }
				 */
				
				$data ['childs'] [$child_info ['product_id']] = array (
						'child_id' => $child_info ['product_id'],
						'info' => $child_info,
						'name' => $child_info ['name'],
						'attributes' => $child_attributes,
						'price' => $child_price,
						'special' => $child_special,
						'tax' => $child_tax,
						'nocart' => $child_child_nocart,
						'options' => $child_options,
						'discounts' => $child_discounts,
						'qty_now' => $qty_now 
				)
				// 'curr_vendor'=> $curr_vendor,
				// 'vlink' => $vlink,
				// 'vendors' => $vendors
				;
				/* } */
			}
		}
		// Column
		// $data['attributenames'] = $attributenames; edit for filtering
		$data ['agnames'] = $agnames;
		$data ['column_gp_name'] = $this->language->get ( 'column_gp_name' );
		$data ['column_gp_price'] = $tcg_customer_price ? $this->language->get ( 'column_gp_price' ) : false;
		$data ['column_gp_option'] = $gp_child_option_col ? $this->language->get ( 'column_gp_option' ) : false;
		$data ['column_gp_qty'] = $this->language->get ( 'column_gp_qty' );
		$template = $this->config->get ( 'config_template' );
		if (file_exists ( DIR_TEMPLATE . $template . '/template/product/gp_grouped_' . $is_gp_grouped ['gp_template'] . '.tpl' )) {
			$this->response->setOutput ( $this->load->view ( $template . '/template/product/gp_grouped_enquiry_' . $is_gp_grouped ['gp_template'] . '.tpl', $data ) );
		} else {
			$this->response->setOutput ( $this->load->view ( 'default/template/product/gp_grouped_enquiry_' . $is_gp_grouped ['gp_template'] . '.tpl', $data ) );
		}
	}
	}
	public function getdescription() {
		$product_id = $this->request->get ['product_id'];
		$this->load->model ( 'catalog/product' );
		$attributes = $this->model_catalog_product->getProductAttributes ( $product_id );
		$output = '';
		foreach ( $attributes as $ag ) {
			foreach ( $ag ['attribute'] as $a ) {
				$output .= $a ['name'] . '-' . $a ['text'] . "\n";
			}
		}
		$this->response->setOutput ( $output );
	}
}