<?php
class ControllerModuleProductbycategory extends Controller {
	public function index($setting) {
		static $module = 0;

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->language('product/gp_grouped');
		$this->load->model('tool/image'); 

		$this->document->addStyle('catalog/view/javascript/jquery/homepage/flexslider.css');
		$this->document->addScript('catalog/view/javascript/jquery/homepage/jquery.flexslider-min.js');

		$data['categories'] = array();

		$results = $this->model_catalog_category->getCategory($setting['category_id']);

		if ($results) {
			
			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');

			$data['categories'][] = array(
				'name' => $results['name'],
				'href' => $this->url->link('product/category', 'path=' . $results['category_id'].'&utm_source='.$setting['name'].'&utm_medium=header&utm_campaign='.$results['name'])
			);

			$data['subcategories'] = array();

			$resultsb = $this->model_catalog_category->getCategories($results['category_id']);

			foreach (array_slice($resultsb,0,5) as $result) {
				$data['subcategories'][] = array(
					'name'  => $result['name'] ,
					'href'  => $this->url->link('product/category', 'path=' . $result['category_id'].'&utm_source='.$setting['name'].'&utm_medium=subheader&utm_campaign='.$result['name'])
				);
			}
			if (isset($this->request->get['filter'])) {
				$filter = $this->request->get['filter'];
			} else {
				$filter = '';
			}

			$data['style'] = $setting['style'];
			 
			$limit = $setting['limit'];
		 

			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
			} else {
				$order = 'ASC';
			}

			$category_id = $setting['category_id'];

			$data['products'] = array();
			
			$filter_data = array(
				'filter_category_id' => $category_id,
				'order'              => $order,
				 'filter_sub_category' => true, 
				'limit'              => $limit,
				'start'              => 0,
			);

			//$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

			$products = $setting['products'];

			foreach ($products as $product) {
				$product_info = $this->model_catalog_product->getProduct($product);
				if ($product_info) {
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
					}

					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
						$original_price  = 0;
						$discount = 0;				
						if ($product_info['price'] < $product_info['original_price']) {
							$original_price = $this->currency->format($this->tax->calculate($product_info['original_price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
							$discount = (int)(($product_info['original_price'] - $product_info['price'])*100/$product_info['original_price']);
						}				
					} else {
						$price = false;
					}

					if ((float)$product_info['special']) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}
					if ($price && $is_gp = $this->model_catalog_product->getGroupedProductGrouped($product_info['product_id'])) {
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
							$price = $this->language->get('text_gp_price_min') . $this->currency->format($this->tax->calculate($gp_price_min, $product_info['tax_class_id'], $this->config->get('config_tax'))) . $this->language->get('text_gp_price_max') . $this->currency->format($this->tax->calculate($gp_price_max, $product_info['tax_class_id'], $this->config->get('config_tax')));
						} else {
							$price = $this->language->get('text_gp_price_start') . $this->currency->format($this->tax->calculate($gp_price_min, $product_info['tax_class_id'], $this->config->get('config_tax')));
						}
					}
					$data['products'][] = array(
						'product_id'  => $product_info['product_id'],
						'thumb'       => $image,
						'name'        => $product_info['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
						'price'       => $price,
						'original_price' => $original_price,
						'discount'    => $discount,
						'minimum'     => $product_info['minimum'] > 0 ? $product_info['minimum'] : 1,
						'special'     => $special,
						'tax'         => $tax,
						'rating'      => $rating,
						'type'		  => $product_info['type'],
						'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'].'&utm_source='.$setting['name'].'&utm_medium=product&utm_campaign='.$product_info['name'])
					);
				}
			}
		}
		$data['module'] = $module++;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/productbycategory.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/productbycategory.tpl', $data);
		} else {
			return $this->load->view('default/template/module/productbycategory.tpl', $data);
		}
	}
}
