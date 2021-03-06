<?php
class ControllerProductSearch extends Controller {
	public function index() {
		$this->load->language ( 'product/gp_grouped' );
		$this->load->language ( 'product/search' );
		$this->load->model ( 'catalog/category' );
		$this->load->model ( 'catalog/product' );
		$this->load->model ( 'tool/image' );
		$this->load->model('account/customerpartner');
		
		if (isset ( $this->request->get ['search'] )) {
			$search = $this->request->get ['search'];
		} else {
			$search = '';
		}
		
		if (isset ( $this->request->get ['tag'] )) {
			$tag = $this->request->get ['tag'];
		} elseif (isset ( $this->request->get ['search'] )) {
			$tag = $this->request->get ['search'];
		} else {
			$tag = '';
		}
		
		if (isset ( $this->request->get ['description'] )) {
			$description = $this->request->get ['description'];
		} else {
			$description = '';
		}
		
		if (isset ( $this->request->get ['category_id'] )) {
			$category_id = $this->request->get ['category_id'];
		} else {
			$category_id = 0;
		}
		
		if (isset ( $this->request->get ['sub_category'] )) {
			$sub_category = $this->request->get ['sub_category'];
		} else {
			$sub_category = '';
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
		
		if (isset ( $this->request->get ['search'] )) {
			$this->document->setTitle ( $this->language->get ( 'heading_title' ) . ' - ' . $this->request->get ['search'] );
		} elseif (isset ( $this->request->get ['tag'] )) {
			$this->document->setTitle ( $this->language->get ( 'heading_title' ) . ' - ' . $this->language->get ( 'heading_tag' ) . $this->request->get ['tag'] );
		} else {
			$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		}
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home' ) 
		);
		
		$url = '';
		
		if (! empty ( $this->request->get ['mfp'] )) {
			$url .= '&mfp=' . $this->request->get ['mfp'];
		}
		
		if (isset ( $this->request->get ['search'] )) {
			$url .= '&search=' . urlencode ( html_entity_decode ( $this->request->get ['search'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['tag'] )) {
			$url .= '&tag=' . urlencode ( html_entity_decode ( $this->request->get ['tag'], ENT_QUOTES, 'UTF-8' ) );
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
				'text' => $this->language->get ( 'heading_title' ),
				'href' => $this->url->link ( 'product/search', $url ) 
		);
		
		if (isset ( $this->request->get ['search'] )) {
			$data ['heading_title'] = $this->language->get ( 'heading_title' ) . ' - ' . $this->request->get ['search'];
		} else {
			$data ['heading_title'] = $this->language->get ( 'heading_title' );
		}
		
		$data ['text_empty'] = $this->language->get ( 'text_empty' );
		$data ['text_search'] = $this->language->get ( 'text_search' );
		$data ['text_keyword'] = $this->language->get ( 'text_keyword' );
		$data ['text_category'] = $this->language->get ( 'text_category' );
		$data ['text_sub_category'] = $this->language->get ( 'text_sub_category' );
		$data ['text_quantity'] = $this->language->get ( 'text_quantity' );
		$data ['text_manufacturer'] = $this->language->get ( 'text_manufacturer' );
		$data ['text_model'] = $this->language->get ( 'text_model' );
		$data ['text_price'] = $this->language->get ( 'text_price' );
		$data ['text_tax'] = $this->language->get ( 'text_tax' );
		$data ['text_points'] = $this->language->get ( 'text_points' );
		$data ['text_compare'] = sprintf ( $this->language->get ( 'text_compare' ), (isset ( $this->session->data ['compare'] ) ? count ( $this->session->data ['compare'] ) : 0) );
		$data ['text_sort'] = $this->language->get ( 'text_sort' );
		$data ['text_limit'] = $this->language->get ( 'text_limit' );
		
		$data ['entry_search'] = $this->language->get ( 'entry_search' );
		$data ['entry_description'] = $this->language->get ( 'entry_description' );
		
		$data ['button_search'] = $this->language->get ( 'button_search' );
		$data ['button_cart'] = $this->language->get ( 'button_cart' );
		$data ['button_wishlist'] = $this->language->get ( 'button_wishlist' );
		$data ['button_compare'] = $this->language->get ( 'button_compare' );
		$data ['button_list'] = $this->language->get ( 'button_list' );
		$data ['button_grid'] = $this->language->get ( 'button_grid' );
		
		$data ['compare'] = $this->url->link ( 'product/compare' );
		
		$this->load->model ( 'catalog/category' );
		$data ['dbe'] = $this->checkdb ();
		// 3 Level Category Search
		$data ['categories'] = array ();
		
		$categories_1 = $this->model_catalog_category->getCategories ( 0 );
		
		foreach ( $categories_1 as $category_1 ) {
			$level_2_data = array ();
			
			$categories_2 = $this->model_catalog_category->getCategories ( $category_1 ['category_id'] );
			
			foreach ( $categories_2 as $category_2 ) {
				$level_3_data = array ();
				
				$categories_3 = $this->model_catalog_category->getCategories ( $category_2 ['category_id'] );
				
				foreach ( $categories_3 as $category_3 ) {
					$level_3_data [] = array (
							'category_id' => $category_3 ['category_id'],
							'name' => $category_3 ['name'] 
					);
				}
				
				$level_2_data [] = array (
						'category_id' => $category_2 ['category_id'],
						'name' => $category_2 ['name'],
						'children' => $level_3_data 
				);
			}
			
			$data ['categories'] [] = array (
					'category_id' => $category_1 ['category_id'],
					'name' => $category_1 ['name'],
					'children' => $level_2_data 
			);
		}
		
		$data ['products'] = array ();
		
		if (isset ( $this->request->get ['search'] ) || isset ( $this->request->get ['tag'] )) {
			$filter_data = array (
					'filter_name' => $search,
					'filter_tag' => $tag,
					'filter_description' => $description,
					'filter_category_id' => $category_id,
					'filter_sub_category' => $sub_category,
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
			
			if (isset ( $this->request->get ['search'] )) {
				$url .= '&search=' . urlencode ( html_entity_decode ( $this->request->get ['search'], ENT_QUOTES, 'UTF-8' ) );
			}
			
			if (isset ( $this->request->get ['tag'] )) {
				$url .= '&tag=' . urlencode ( html_entity_decode ( $this->request->get ['tag'], ENT_QUOTES, 'UTF-8' ) );
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
			
			if (isset ( $this->request->get ['limit'] )) {
				$url .= '&limit=' . $this->request->get ['limit'];
			}
			
			$data ['sorts'] = array ();
			
			$data ['sorts'] [] = array (
					'text' => $this->language->get ( 'text_default' ),
					'value' => 'p.sort_order-ASC',
					'href' => $this->url->link ( 'product/search', 'sort=p.sort_order&order=ASC' . $url ) 
			);
			
			$data ['sorts'] [] = array (
					'text' => $this->language->get ( 'text_name_asc' ),
					'value' => 'pd.name-ASC',
					'href' => $this->url->link ( 'product/search', 'sort=pd.name&order=ASC' . $url ) 
			);
			
			$data ['sorts'] [] = array (
					'text' => $this->language->get ( 'text_name_desc' ),
					'value' => 'pd.name-DESC',
					'href' => $this->url->link ( 'product/search', 'sort=pd.name&order=DESC' . $url ) 
			);
			
			$data ['sorts'] [] = array (
					'text' => $this->language->get ( 'text_price_asc' ),
					'value' => 'p.price-ASC',
					'href' => $this->url->link ( 'product/search', 'sort=p.price&order=ASC' . $url ) 
			);
			
			$data ['sorts'] [] = array (
					'text' => $this->language->get ( 'text_price_desc' ),
					'value' => 'p.price-DESC',
					'href' => $this->url->link ( 'product/search', 'sort=p.price&order=DESC' . $url ) 
			);
			
			if ($this->config->get ( 'config_review_status' )) {
				$data ['sorts'] [] = array (
						'text' => $this->language->get ( 'text_rating_desc' ),
						'value' => 'rating-DESC',
						'href' => $this->url->link ( 'product/search', 'sort=rating&order=DESC' . $url ) 
				);
				
				$data ['sorts'] [] = array (
						'text' => $this->language->get ( 'text_rating_asc' ),
						'value' => 'rating-ASC',
						'href' => $this->url->link ( 'product/search', 'sort=rating&order=ASC' . $url ) 
				);
			}
			
			$data ['sorts'] [] = array (
					'text' => $this->language->get ( 'text_model_asc' ),
					'value' => 'p.model-ASC',
					'href' => $this->url->link ( 'product/search', 'sort=p.model&order=ASC' . $url ) 
			);
			
			$data ['sorts'] [] = array (
					'text' => $this->language->get ( 'text_model_desc' ),
					'value' => 'p.model-DESC',
					'href' => $this->url->link ( 'product/search', 'sort=p.model&order=DESC' . $url ) 
			);
			
			$url = '';
			
			if (! empty ( $this->request->get ['mfp'] )) {
				$url .= '&mfp=' . $this->request->get ['mfp'];
			}
			
			if (isset ( $this->request->get ['search'] )) {
				$url .= '&search=' . urlencode ( html_entity_decode ( $this->request->get ['search'], ENT_QUOTES, 'UTF-8' ) );
			}
			
			if (isset ( $this->request->get ['tag'] )) {
				$url .= '&tag=' . urlencode ( html_entity_decode ( $this->request->get ['tag'], ENT_QUOTES, 'UTF-8' ) );
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
						'href' => $this->url->link ( 'product/search', $url . '&limit=' . $value ) 
				);
			}
			
			$url = '';
			
			if (! empty ( $this->request->get ['mfp'] )) {
				$url .= '&mfp=' . $this->request->get ['mfp'];
			}
			
			if (isset ( $this->request->get ['search'] )) {
				$url .= '&search=' . urlencode ( html_entity_decode ( $this->request->get ['search'], ENT_QUOTES, 'UTF-8' ) );
			}
			
			if (isset ( $this->request->get ['tag'] )) {
				$url .= '&tag=' . urlencode ( html_entity_decode ( $this->request->get ['tag'], ENT_QUOTES, 'UTF-8' ) );
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
			
			if (isset ( $this->request->get ['limit'] )) {
				$url .= '&limit=' . $this->request->get ['limit'];
			}
			
			$pagination = new Pagination ();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link ( 'product/search', $url . '&page={page}' );
			
			$data ['pagination'] = $pagination->render ();
			
			$data ['results'] = sprintf ( $this->language->get ( 'text_pagination' ), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil ( $product_total / $limit ) );
		}
		
		$data ['search'] = $search;
		$data ['description'] = $description;
		$data ['category_id'] = $category_id;
		$data ['sub_category'] = $sub_category;
		
		$data ['sort'] = $sort;
		$data ['order'] = $order;
		$data ['limit'] = $limit;
		
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['column_right'] = $this->load->controller ( 'common/column_right' );
		$data ['content_top'] = $this->load->controller ( 'common/content_top' );
		$data ['content_bottom'] = $this->load->controller ( 'common/content_bottom' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		$data ['header'] = $this->load->controller ( 'common/header' );
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/product/search.tpl' )) {
			
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
			
			$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/product/search.tpl', $data ) );
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
			
			$this->response->setOutput ( $this->load->view ( 'default/template/product/search.tpl', $data ) );
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
