<?php
class ControllerProductManufacturer extends Controller {
	public function index() {
		$this->load->language ( 'product/manufacturer' );
		
		$this->load->model ( 'catalog/manufacturer' );
		
		$this->load->model ( 'tool/image' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		
		$data ['text_index'] = $this->language->get ( 'text_index' );
		$data ['text_empty'] = $this->language->get ( 'text_empty' );
		
		$data ['button_continue'] = $this->language->get ( 'button_continue' );
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home' ) 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_brand' ),
				'href' => $this->url->link ( 'product/manufacturer' ) 
		);
		
		$data ['categories'] = array ();
		
		$results = $this->model_catalog_manufacturer->getManufacturers ();
		
		foreach ( $results as $result ) {
			if (is_numeric ( utf8_substr ( $result ['name'], 0, 1 ) )) {
				$key = '0 - 9';
			} else {
				$key = utf8_substr ( utf8_strtoupper ( $result ['name'] ), 0, 1 );
			}
			
			if (! isset ( $data ['categories'] [$key] )) {
				$data ['categories'] [$key] ['name'] = $key;
			}
			
			$data ['categories'] [$key] ['manufacturer'] [] = array (
					'name' => $result ['name'],
					'href' => $this->url->link ( 'product/manufacturer/info', 'manufacturer_id=' . $result ['manufacturer_id'] ) 
			);
		}
		
		$data ['continue'] = $this->url->link ( 'common/home' );
		
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['column_right'] = $this->load->controller ( 'common/column_right' );
		$data ['content_top'] = $this->load->controller ( 'common/content_top' );
		$data ['content_bottom'] = $this->load->controller ( 'common/content_bottom' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		$data ['header'] = $this->load->controller ( 'common/header' );
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/product/manufacturer_list.tpl' )) {
			
			if (isset ( $this->request->get ['mfilterAjax'] )) {
				$settings = $this->config->get ( 'mega_filter_settings' );
				$baseTypes = array (
						'stock_status',
						'manufacturers',
						'rating',
						'attributes',
						'price',
						'options',
						'filters' 
				);
				
				if (isset ( $this->request->get ['mfilterBTypes'] )) {
					$baseTypes = explode ( ',', $this->request->get ['mfilterBTypes'] );
				}
				
				if (! empty ( $settings ['calculate_number_of_products'] ) || in_array ( 'categories:tree', $baseTypes )) {
					if (empty ( $settings ['calculate_number_of_products'] )) {
						$baseTypes = array (
								'categories:tree' 
						);
					}
					
					$this->load->model ( 'module/mega_filter' );
					
					$idx = 0;
					
					if (isset ( $this->request->get ['mfilterIdx'] ))
						$idx = ( int ) $this->request->get ['mfilterIdx'];
					
					$data ['mfilter_json'] = json_encode ( MegaFilterCore::newInstance ( $this, NULL )->getJsonData ( $baseTypes, $idx ) );
				}
				
				$data ['header'] = $data ['column_left'] = $data ['column_right'] = $data ['content_top'] = $data ['content_bottom'] = $data ['footer'] = '';
			}
			
			if (! empty ( $data ['breadcrumbs'] ) && ! empty ( $this->request->get ['mfp'] )) {
				foreach ( $data ['breadcrumbs'] as $mfK => $mfBreadcrumb ) {
					$mfReplace = preg_replace ( '/path\[[^\]]+\],?/', '', $this->request->get ['mfp'] );
					$mfFind = (mb_strpos ( $mfBreadcrumb ['href'], '?mfp=', 0, 'utf-8' ) !== false ? '?mfp=' : '&mfp=');
					
					$data ['breadcrumbs'] [$mfK] ['href'] = str_replace ( array (
							$mfFind . $this->request->get ['mfp'],
							'&amp;mfp=' . $this->request->get ['mfp'],
							$mfFind . urlencode ( $this->request->get ['mfp'] ),
							'&amp;mfp=' . urlencode ( $this->request->get ['mfp'] ) 
					), $mfReplace ? $mfFind . $mfReplace : '', $mfBreadcrumb ['href'] );
				}
			}
			
			$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/product/manufacturer_list.tpl', $data ) );
		} else {
			
			if (isset ( $this->request->get ['mfilterAjax'] )) {
				$settings = $this->config->get ( 'mega_filter_settings' );
				$baseTypes = array (
						'stock_status',
						'manufacturers',
						'rating',
						'attributes',
						'price',
						'options',
						'filters' 
				);
				
				if (isset ( $this->request->get ['mfilterBTypes'] )) {
					$baseTypes = explode ( ',', $this->request->get ['mfilterBTypes'] );
				}
				
				if (! empty ( $settings ['calculate_number_of_products'] ) || in_array ( 'categories:tree', $baseTypes )) {
					if (empty ( $settings ['calculate_number_of_products'] )) {
						$baseTypes = array (
								'categories:tree' 
						);
					}
					
					$this->load->model ( 'module/mega_filter' );
					
					$idx = 0;
					
					if (isset ( $this->request->get ['mfilterIdx'] ))
						$idx = ( int ) $this->request->get ['mfilterIdx'];
					
					$data ['mfilter_json'] = json_encode ( MegaFilterCore::newInstance ( $this, NULL )->getJsonData ( $baseTypes, $idx ) );
				}
				
				$data ['header'] = $data ['column_left'] = $data ['column_right'] = $data ['content_top'] = $data ['content_bottom'] = $data ['footer'] = '';
			}
			
			if (! empty ( $data ['breadcrumbs'] ) && ! empty ( $this->request->get ['mfp'] )) {
				foreach ( $data ['breadcrumbs'] as $mfK => $mfBreadcrumb ) {
					$mfReplace = preg_replace ( '/path\[[^\]]+\],?/', '', $this->request->get ['mfp'] );
					$mfFind = (mb_strpos ( $mfBreadcrumb ['href'], '?mfp=', 0, 'utf-8' ) !== false ? '?mfp=' : '&mfp=');
					
					$data ['breadcrumbs'] [$mfK] ['href'] = str_replace ( array (
							$mfFind . $this->request->get ['mfp'],
							'&amp;mfp=' . $this->request->get ['mfp'],
							$mfFind . urlencode ( $this->request->get ['mfp'] ),
							'&amp;mfp=' . urlencode ( $this->request->get ['mfp'] ) 
					), $mfReplace ? $mfFind . $mfReplace : '', $mfBreadcrumb ['href'] );
				}
			}
			
			$this->response->setOutput ( $this->load->view ( 'default/template/product/manufacturer_list.tpl', $data ) );
		}
	}
	public function info() {
		$this->load->language ( 'product/gp_grouped' );
		$this->load->language ( 'product/manufacturer' );
		
		$this->load->model ( 'catalog/manufacturer' );
		$this->load->model('account/customerpartner');
		
		$this->load->model ( 'catalog/product' );
		
		$this->load->model ( 'tool/image' );
		
		if (isset ( $this->request->get ['manufacturer_id'] )) {
			$manufacturer_id = ( int ) $this->request->get ['manufacturer_id'];
		} else {
			$manufacturer_id = 0;
		}
		
		if (isset ( $this->request->get ['sort'] )) {
			$sort = $this->request->get ['sort'];
		} else {
			$sort = 'p.sort_order';
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$order = $this->request->get ['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		if (isset ( $this->request->get ['limit'] )) {
			$limit = $this->request->get ['limit'];
		} else {
			$limit = $this->config->get ( 'config_product_limit' );
		}
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home' ) 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_brand' ),
				'href' => $this->url->link ( 'product/manufacturer' ) 
		);
		
		$data ['dbe'] = $this->checkdb ();
		
		$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer ( $manufacturer_id );
		
		if ($manufacturer_info) {
			$this->document->setTitle ( $manufacturer_info ['name'] );
			$this->document->addLink ( $this->url->link ( 'product/manufacturer/info', 'manufacturer_id=' . $this->request->get ['manufacturer_id'] ), 'canonical' );
			
			$url = '';
			
			if (! empty ( $this->request->get ['mfp'] )) {
				$url .= '&mfp=' . $this->request->get ['mfp'];
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
					'text' => $manufacturer_info ['name'],
					'href' => $this->url->link ( 'product/manufacturer/info', 'manufacturer_id=' . $this->request->get ['manufacturer_id'] . $url ) 
			);
			
			$data ['heading_title'] = $manufacturer_info ['name'];
			
			$data ['text_empty'] = $this->language->get ( 'text_empty' );
			$data ['text_quantity'] = $this->language->get ( 'text_quantity' );
			$data ['text_manufacturer'] = $this->language->get ( 'text_manufacturer' );
			$data ['text_model'] = $this->language->get ( 'text_model' );
			$data ['text_price'] = $this->language->get ( 'text_price' );
			$data ['text_tax'] = $this->language->get ( 'text_tax' );
			$data ['text_points'] = $this->language->get ( 'text_points' );
			$data ['text_compare'] = sprintf ( $this->language->get ( 'text_compare' ), (isset ( $this->session->data ['compare'] ) ? count ( $this->session->data ['compare'] ) : 0) );
			$data ['text_sort'] = $this->language->get ( 'text_sort' );
			$data ['text_limit'] = $this->language->get ( 'text_limit' );
			
			$data ['button_cart'] = $this->language->get ( 'button_cart' );
			$data ['button_wishlist'] = $this->language->get ( 'button_wishlist' );
			$data ['button_compare'] = $this->language->get ( 'button_compare' );
			$data ['button_continue'] = $this->language->get ( 'button_continue' );
			$data ['button_list'] = $this->language->get ( 'button_list' );
			$data ['button_grid'] = $this->language->get ( 'button_grid' );
			
			$data ['compare'] = $this->url->link ( 'product/compare' );
			
			$data ['products'] = array ();
			
			$filter_data = array (
					'filter_manufacturer_id' => $manufacturer_id,
					'sort' => $sort,
					'order' => $order,
					'start' => ($page - 1) * $limit,
					'limit' => $limit 
			);
			
			$product_total = $this->model_catalog_product->getTotalProducts ( $filter_data );
			
			$results = $this->model_catalog_product->getProducts ( $filter_data );
			
			foreach ( $results as $result ) {
				$product_vendor = $this->model_account_customerpartner->getSupplierProduct($result['product_id'],$result['vendor_id']);
				
				if ($result['image']) $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				else $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				
				if ((float)$result['special']) $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				else $special = false;
		
				if ($this->config->get('config_review_status')) $rating = (int)$result['rating'];
				else $rating = false;
				
				$original_price  = 0;
				$discount = 0;
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) $price = 'Price not Available';
				else $price = false; 
				$minimum = 1;
				$tax = false;
				$enabled = false;
				
				if ($product_vendor){
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_vendor['price'], $result['tax_class_id'], $this->config->get('config_tax')));
						if ($product_vendor['price'] < $result['price']) {
							$original_price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
							$discount = (int)(($result['price'] - $product_vendor['price'])*100/$result['price']);
						}
					}
					if ($this->config->get('config_tax')) $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $product_vendor['price']);
					$minimum = $product_vendor['minimum'] > 0 ? $product_vendor['minimum'] : 1;
					$enabled = true;
				}

				if ($is_gp = $this->model_catalog_product->getGroupedProductGrouped($result['product_id'])) {
					$gp_price_min = $is_gp['gp_price_min'];
					$gp_price_max = $is_gp['gp_price_max'];
					$prices = $this->model_catalog_product->getGroupedProductMinimum($result['product_id']);
					if ($prices['minimum'] && $prices['maximum']) {
						$price = $this->currency->format($this->tax->calculate($prices['minimum'], $result['tax_class_id'], $this->config->get('config_tax'))) . ' - ' . $this->currency->format($this->tax->calculate($prices['maximum'], $result['tax_class_id'], $this->config->get('config_tax')));
						if ($tax) {
							$tax = $this->currency->format($prices['minimum']) . '/' . $this->currency->format($prices['maximum']);
						}
					} else {
						$price = $this->language->get('text_gp_price_start') . $this->currency->format($this->tax->calculate($prices['minimum'], $result['tax_class_id'], $this->config->get('config_tax')));
						if ($tax) {
							$tax = $this->currency->format($prices['minimum']);
						}
					}
					$result ['type'] = 2;
				}
				
				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'price'       => $price,
					'original_price' => $original_price,
					'discount'    => $discount,				
					'enabled' 	  => $enabled,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $minimum,
					'rating'      => $result['rating'],
					'type'		  => $result['type'],
					'href'        => $this->url->link('product/product', '&product_id=' . $result['product_id'] . $url)
				);
			}
			
			$url = '';
			
			if (! empty ( $this->request->get ['mfp'] )) {
				$url .= '&mfp=' . $this->request->get ['mfp'];
			}
			
			if (isset ( $this->request->get ['limit'] )) {
				$url .= '&limit=' . $this->request->get ['limit'];
			}
			
			$data ['sorts'] = array ();
			
			$data ['sorts'] [] = array (
					'text' => $this->language->get ( 'text_default' ),
					'value' => 'p.sort_order-ASC',
					'href' => $this->url->link ( 'product/manufacturer/info', 'manufacturer_id=' . $this->request->get ['manufacturer_id'] . '&sort=p.sort_order&order=ASC' . $url ) 
			);
			
			$data ['sorts'] [] = array (
					'text' => $this->language->get ( 'text_name_asc' ),
					'value' => 'pd.name-ASC',
					'href' => $this->url->link ( 'product/manufacturer/info', 'manufacturer_id=' . $this->request->get ['manufacturer_id'] . '&sort=pd.name&order=ASC' . $url ) 
			);
			
			$data ['sorts'] [] = array (
					'text' => $this->language->get ( 'text_name_desc' ),
					'value' => 'pd.name-DESC',
					'href' => $this->url->link ( 'product/manufacturer/info', 'manufacturer_id=' . $this->request->get ['manufacturer_id'] . '&sort=pd.name&order=DESC' . $url ) 
			);
			
			$data ['sorts'] [] = array (
					'text' => $this->language->get ( 'text_price_asc' ),
					'value' => 'p.price-ASC',
					'href' => $this->url->link ( 'product/manufacturer/info', 'manufacturer_id=' . $this->request->get ['manufacturer_id'] . '&sort=p.price&order=ASC' . $url ) 
			);
			
			$data ['sorts'] [] = array (
					'text' => $this->language->get ( 'text_price_desc' ),
					'value' => 'p.price-DESC',
					'href' => $this->url->link ( 'product/manufacturer/info', 'manufacturer_id=' . $this->request->get ['manufacturer_id'] . '&sort=p.price&order=DESC' . $url ) 
			);
			
			if ($this->config->get ( 'config_review_status' )) {
				$data ['sorts'] [] = array (
						'text' => $this->language->get ( 'text_rating_desc' ),
						'value' => 'rating-DESC',
						'href' => $this->url->link ( 'product/manufacturer/info', 'manufacturer_id=' . $this->request->get ['manufacturer_id'] . '&sort=rating&order=DESC' . $url ) 
				);
				
				$data ['sorts'] [] = array (
						'text' => $this->language->get ( 'text_rating_asc' ),
						'value' => 'rating-ASC',
						'href' => $this->url->link ( 'product/manufacturer/info', 'manufacturer_id=' . $this->request->get ['manufacturer_id'] . '&sort=rating&order=ASC' . $url ) 
				);
			}
			
			$data ['sorts'] [] = array (
					'text' => $this->language->get ( 'text_model_asc' ),
					'value' => 'p.model-ASC',
					'href' => $this->url->link ( 'product/manufacturer/info', 'manufacturer_id=' . $this->request->get ['manufacturer_id'] . '&sort=p.model&order=ASC' . $url ) 
			);
			
			$data ['sorts'] [] = array (
					'text' => $this->language->get ( 'text_model_desc' ),
					'value' => 'p.model-DESC',
					'href' => $this->url->link ( 'product/manufacturer/info', 'manufacturer_id=' . $this->request->get ['manufacturer_id'] . '&sort=p.model&order=DESC' . $url ) 
			);
			
			$url = '';
			
			if (! empty ( $this->request->get ['mfp'] )) {
				$url .= '&mfp=' . $this->request->get ['mfp'];
			}
			
			if (isset ( $this->request->get ['sort'] )) {
				$url .= '&sort=' . $this->request->get ['sort'];
			}
			
			if (isset ( $this->request->get ['order'] )) {
				$url .= '&order=' . $this->request->get ['order'];
			}
			
			$data ['limits'] = array ();
			
			$limits = array_unique ( array (
					$this->config->get ( 'config_product_limit' ),
					25,
					50,
					75,
					100 
			) );
			
			sort ( $limits );
			
			foreach ( $limits as $value ) {
				$data ['limits'] [] = array (
						'text' => $value,
						'value' => $value,
						'href' => $this->url->link ( 'product/manufacturer/info', 'manufacturer_id=' . $this->request->get ['manufacturer_id'] . $url . '&limit=' . $value ) 
				);
			}
			
			$url = '';
			
			if (! empty ( $this->request->get ['mfp'] )) {
				$url .= '&mfp=' . $this->request->get ['mfp'];
			}
			
			if (isset ( $this->request->get ['sort'] )) {
				$url .= '&sort=' . $this->request->get ['sort'];
			}
			
			if (isset ( $this->request->get ['order'] )) {
				$url .= '&order=' . $this->request->get ['order'];
			}
			
			if (isset ( $this->request->get ['limit'] )) {
				$url .= '&limit=' . $this->request->get ['limit'];
			}
			
			$pagination = new Pagination ();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link ( 'product/manufacturer/info', 'manufacturer_id=' . $this->request->get ['manufacturer_id'] . $url . '&page={page}' );
			
			$data ['pagination'] = $pagination->render ();
			
			$data ['results'] = sprintf ( $this->language->get ( 'text_pagination' ), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil ( $product_total / $limit ) );
			
			$data ['sort'] = $sort;
			$data ['order'] = $order;
			$data ['limit'] = $limit;
			
			$data ['continue'] = $this->url->link ( 'common/home' );
			
			$data ['column_left'] = $this->load->controller ( 'common/column_left' );
			$data ['column_right'] = $this->load->controller ( 'common/column_right' );
			$data ['content_top'] = $this->load->controller ( 'common/content_top' );
			$data ['content_bottom'] = $this->load->controller ( 'common/content_bottom' );
			$data ['footer'] = $this->load->controller ( 'common/footer' );
			$data ['header'] = $this->load->controller ( 'common/header' );
			
			if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/product/manufacturer_info.tpl' )) {
				
				if (isset ( $this->request->get ['mfilterAjax'] )) {
					$settings = $this->config->get ( 'mega_filter_settings' );
					$baseTypes = array (
							'stock_status',
							'manufacturers',
							'rating',
							'attributes',
							'price',
							'options',
							'filters' 
					);
					
					if (isset ( $this->request->get ['mfilterBTypes'] )) {
						$baseTypes = explode ( ',', $this->request->get ['mfilterBTypes'] );
					}
					
					if (! empty ( $settings ['calculate_number_of_products'] ) || in_array ( 'categories:tree', $baseTypes )) {
						if (empty ( $settings ['calculate_number_of_products'] )) {
							$baseTypes = array (
									'categories:tree' 
							);
						}
						
						$this->load->model ( 'module/mega_filter' );
						
						$idx = 0;
						
						if (isset ( $this->request->get ['mfilterIdx'] ))
							$idx = ( int ) $this->request->get ['mfilterIdx'];
						
						$data ['mfilter_json'] = json_encode ( MegaFilterCore::newInstance ( $this, NULL )->getJsonData ( $baseTypes, $idx ) );
					}
					
					$data ['header'] = $data ['column_left'] = $data ['column_right'] = $data ['content_top'] = $data ['content_bottom'] = $data ['footer'] = '';
				}
				
				if (! empty ( $data ['breadcrumbs'] ) && ! empty ( $this->request->get ['mfp'] )) {
					foreach ( $data ['breadcrumbs'] as $mfK => $mfBreadcrumb ) {
						$mfReplace = preg_replace ( '/path\[[^\]]+\],?/', '', $this->request->get ['mfp'] );
						$mfFind = (mb_strpos ( $mfBreadcrumb ['href'], '?mfp=', 0, 'utf-8' ) !== false ? '?mfp=' : '&mfp=');
						
						$data ['breadcrumbs'] [$mfK] ['href'] = str_replace ( array (
								$mfFind . $this->request->get ['mfp'],
								'&amp;mfp=' . $this->request->get ['mfp'],
								$mfFind . urlencode ( $this->request->get ['mfp'] ),
								'&amp;mfp=' . urlencode ( $this->request->get ['mfp'] ) 
						), $mfReplace ? $mfFind . $mfReplace : '', $mfBreadcrumb ['href'] );
					}
				}
				
				$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/product/manufacturer_info.tpl', $data ) );
			} else {
				
				if (isset ( $this->request->get ['mfilterAjax'] )) {
					$settings = $this->config->get ( 'mega_filter_settings' );
					$baseTypes = array (
							'stock_status',
							'manufacturers',
							'rating',
							'attributes',
							'price',
							'options',
							'filters' 
					);
					
					if (isset ( $this->request->get ['mfilterBTypes'] )) {
						$baseTypes = explode ( ',', $this->request->get ['mfilterBTypes'] );
					}
					
					if (! empty ( $settings ['calculate_number_of_products'] ) || in_array ( 'categories:tree', $baseTypes )) {
						if (empty ( $settings ['calculate_number_of_products'] )) {
							$baseTypes = array (
									'categories:tree' 
							);
						}
						
						$this->load->model ( 'module/mega_filter' );
						
						$idx = 0;
						
						if (isset ( $this->request->get ['mfilterIdx'] ))
							$idx = ( int ) $this->request->get ['mfilterIdx'];
						
						$data ['mfilter_json'] = json_encode ( MegaFilterCore::newInstance ( $this, NULL )->getJsonData ( $baseTypes, $idx ) );
					}
					
					$data ['header'] = $data ['column_left'] = $data ['column_right'] = $data ['content_top'] = $data ['content_bottom'] = $data ['footer'] = '';
				}
				
				if (! empty ( $data ['breadcrumbs'] ) && ! empty ( $this->request->get ['mfp'] )) {
					foreach ( $data ['breadcrumbs'] as $mfK => $mfBreadcrumb ) {
						$mfReplace = preg_replace ( '/path\[[^\]]+\],?/', '', $this->request->get ['mfp'] );
						$mfFind = (mb_strpos ( $mfBreadcrumb ['href'], '?mfp=', 0, 'utf-8' ) !== false ? '?mfp=' : '&mfp=');
						
						$data ['breadcrumbs'] [$mfK] ['href'] = str_replace ( array (
								$mfFind . $this->request->get ['mfp'],
								'&amp;mfp=' . $this->request->get ['mfp'],
								$mfFind . urlencode ( $this->request->get ['mfp'] ),
								'&amp;mfp=' . urlencode ( $this->request->get ['mfp'] ) 
						), $mfReplace ? $mfFind . $mfReplace : '', $mfBreadcrumb ['href'] );
					}
				}
				
				$this->response->setOutput ( $this->load->view ( 'default/template/product/manufacturer_info.tpl', $data ) );
			}
		} else {
			$url = '';
			
			if (! empty ( $this->request->get ['mfp'] )) {
				$url .= '&mfp=' . $this->request->get ['mfp'];
			}
			
			if (isset ( $this->request->get ['manufacturer_id'] )) {
				$url .= '&manufacturer_id=' . $this->request->get ['manufacturer_id'];
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
					'href' => $this->url->link ( 'product/manufacturer/info', $url ) 
			);
			
			$this->document->setTitle ( $this->language->get ( 'text_error' ) );
			
			$data ['heading_title'] = $this->language->get ( 'text_error' );
			
			$data ['text_error'] = $this->language->get ( 'text_error' );
			
			$data ['button_continue'] = $this->language->get ( 'button_continue' );
			
			$data ['continue'] = $this->url->link ( 'common/home' );
			
			$this->response->addHeader ( $this->request->server ['SERVER_PROTOCOL'] . ' 404 Not Found' );
			
			$data ['header'] = $this->load->controller ( 'common/header' );
			$data ['footer'] = $this->load->controller ( 'common/footer' );
			$data ['column_left'] = $this->load->controller ( 'common/column_left' );
			$data ['column_right'] = $this->load->controller ( 'common/column_right' );
			$data ['content_top'] = $this->load->controller ( 'common/content_top' );
			$data ['content_bottom'] = $this->load->controller ( 'common/content_bottom' );
			
			if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/error/not_found.tpl' )) {
				
				if (isset ( $this->request->get ['mfilterAjax'] )) {
					$settings = $this->config->get ( 'mega_filter_settings' );
					$baseTypes = array (
							'stock_status',
							'manufacturers',
							'rating',
							'attributes',
							'price',
							'options',
							'filters' 
					);
					
					if (isset ( $this->request->get ['mfilterBTypes'] )) {
						$baseTypes = explode ( ',', $this->request->get ['mfilterBTypes'] );
					}
					
					if (! empty ( $settings ['calculate_number_of_products'] ) || in_array ( 'categories:tree', $baseTypes )) {
						if (empty ( $settings ['calculate_number_of_products'] )) {
							$baseTypes = array (
									'categories:tree' 
							);
						}
						
						$this->load->model ( 'module/mega_filter' );
						
						$idx = 0;
						
						if (isset ( $this->request->get ['mfilterIdx'] ))
							$idx = ( int ) $this->request->get ['mfilterIdx'];
						
						$data ['mfilter_json'] = json_encode ( MegaFilterCore::newInstance ( $this, NULL )->getJsonData ( $baseTypes, $idx ) );
					}
					
					$data ['header'] = $data ['column_left'] = $data ['column_right'] = $data ['content_top'] = $data ['content_bottom'] = $data ['footer'] = '';
				}
				
				if (! empty ( $data ['breadcrumbs'] ) && ! empty ( $this->request->get ['mfp'] )) {
					foreach ( $data ['breadcrumbs'] as $mfK => $mfBreadcrumb ) {
						$mfReplace = preg_replace ( '/path\[[^\]]+\],?/', '', $this->request->get ['mfp'] );
						$mfFind = (mb_strpos ( $mfBreadcrumb ['href'], '?mfp=', 0, 'utf-8' ) !== false ? '?mfp=' : '&mfp=');
						
						$data ['breadcrumbs'] [$mfK] ['href'] = str_replace ( array (
								$mfFind . $this->request->get ['mfp'],
								'&amp;mfp=' . $this->request->get ['mfp'],
								$mfFind . urlencode ( $this->request->get ['mfp'] ),
								'&amp;mfp=' . urlencode ( $this->request->get ['mfp'] ) 
						), $mfReplace ? $mfFind . $mfReplace : '', $mfBreadcrumb ['href'] );
					}
				}
				
				$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/error/not_found.tpl', $data ) );
			} else {
				
				if (isset ( $this->request->get ['mfilterAjax'] )) {
					$settings = $this->config->get ( 'mega_filter_settings' );
					$baseTypes = array (
							'stock_status',
							'manufacturers',
							'rating',
							'attributes',
							'price',
							'options',
							'filters' 
					);
					
					if (isset ( $this->request->get ['mfilterBTypes'] )) {
						$baseTypes = explode ( ',', $this->request->get ['mfilterBTypes'] );
					}
					
					if (! empty ( $settings ['calculate_number_of_products'] ) || in_array ( 'categories:tree', $baseTypes )) {
						if (empty ( $settings ['calculate_number_of_products'] )) {
							$baseTypes = array (
									'categories:tree' 
							);
						}
						
						$this->load->model ( 'module/mega_filter' );
						
						$idx = 0;
						
						if (isset ( $this->request->get ['mfilterIdx'] ))
							$idx = ( int ) $this->request->get ['mfilterIdx'];
						
						$data ['mfilter_json'] = json_encode ( MegaFilterCore::newInstance ( $this, NULL )->getJsonData ( $baseTypes, $idx ) );
					}
					
					$data ['header'] = $data ['column_left'] = $data ['column_right'] = $data ['content_top'] = $data ['content_bottom'] = $data ['footer'] = '';
				}
				
				if (! empty ( $data ['breadcrumbs'] ) && ! empty ( $this->request->get ['mfp'] )) {
					foreach ( $data ['breadcrumbs'] as $mfK => $mfBreadcrumb ) {
						$mfReplace = preg_replace ( '/path\[[^\]]+\],?/', '', $this->request->get ['mfp'] );
						$mfFind = (mb_strpos ( $mfBreadcrumb ['href'], '?mfp=', 0, 'utf-8' ) !== false ? '?mfp=' : '&mfp=');
						
						$data ['breadcrumbs'] [$mfK] ['href'] = str_replace ( array (
								$mfFind . $this->request->get ['mfp'],
								'&amp;mfp=' . $this->request->get ['mfp'],
								$mfFind . urlencode ( $this->request->get ['mfp'] ),
								'&amp;mfp=' . urlencode ( $this->request->get ['mfp'] ) 
						), $mfReplace ? $mfFind . $mfReplace : '', $mfBreadcrumb ['href'] );
					}
				}
				
				$this->response->setOutput ( $this->load->view ( 'default/template/error/not_found.tpl', $data ) );
			}
		}
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
}
