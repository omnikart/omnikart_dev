<?php
/******************************************************
 * @package Pav Category Tabs module for Opencart 1.5.x
 * @version 1.0
 * @author http://www.pavothemes.com
 * @copyright	Copyright (C) Feb 2012 PavoThemes.com <@emai:pavothemes@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/

class ControllerModulePavverticalcategorytabs extends Controller {

	private $mdata = array();

	public function index($setting) {
		static $module = 0;

		$this->mdata['objconfig'] = $this->config;

		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$this->load->language('module/pavverticalcategorytabs');
		$this->load->model('catalog/category');
		$this->load->language('product/gp_grouped');
		
		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/pavverticalcategorytabs.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/pavverticalcategorytabs.css');
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/bootstrap-tabs-x.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/pavverticalcategorytabs.css');
			$this->document->addStyle('catalog/view/theme/default/stylesheet/bootstrap-tabs-x.css');
		}
		$this->mdata['button_cart'] = $this->language->get('button_cart');
		$this->mdata['heading_title'] = $this->language->get('heading_title');

		$lang = $this->config->get('config_language_id');

		$default = array(

			'title'          => array(),
			'status_p'        => 0,
			'prefix_p'       => '',
			'limit_p'        => 10,
			'banner_position'        =>1,
			'itemsperpage_p' => 5,
			'cols_p'         => 1,

			'featured_product' => array(),

			'description'  => '',
			'width'        => '202',
			'height'       => '224',

			'iwidth'       => '279',
			'iheight'      => '414',

			'itemsperpage' => 8,
			'cols'         => 4,
			'limit'        => 16,

			'limit_tabs'   => 4,
			'status_nav'   => 1,
			'prefix_class' => '',
			'image'        => '',
			'category_id'  => 0,
			'tab_position'        =>1
		);

		if( isset($setting) && !empty($setting) ){
			$setting = array_merge($default, $setting);
		} else {
			return;
		}

		$this->mdata['button_compare'] = $this->language->get('button_compare');
		$this->mdata['button_wishlist'] = $this->language->get('button_wishlist');

		// Get Data Setting
		$this->mdata['title']        = isset($setting['title'][$lang])?$setting['title'][$lang]:'';
		$this->mdata['status_p']     = $setting['status_p'];
		$this->mdata['prefix_p']     = $setting['prefix_p'];

		$this->mdata['cols_p']         = (int)$setting['cols_p'];
		$this->mdata['itemsperpage_p'] = (int)$setting['itemsperpage_p'];
		$this->mdata['limit_p']        = (int)$setting['limit_p'];
		$this->mdata['banner_position']        = (int)$setting['banner_position'];
		$this->mdata['tab_position']        = "left";
		if((int)$setting['tab_position'] == 0){
			$this->mdata['tab_position']        = "right";
		}

		$featured_product           = $setting['featured_product'];

		$this->mdata['description']  = isset($setting['description'][$lang])?$setting['description'][$lang]:'';

		$this->mdata['cols']         = (int)$setting['cols'];
		$this->mdata['itemsperpage'] = (int)$setting['itemsperpage'];
		$this->mdata['limit']        = (int)$setting['limit'];
		$this->mdata['status_nav']   = (int)$setting['status_nav'];
		$this->mdata['prefix_class'] = $setting['prefix_class'];
		$this->mdata['image']        = $this->model_tool_image->resize( $setting['image'], $setting['iwidth'], $setting['iheight'] );
 		$category_id                = (int)$setting['category_id'];

		// Get Name Parent-Category
		$category = $this->model_catalog_category->getCategory($category_id);
		$this->mdata['category_name'] = isset($category['name'])?$category['name']:'';
		$this->mdata['category_link'] = $this->url->link('product/category', 'path='.$category_id);

		// Get Sub-Categories By Parent-ID
		$sub_categories = $this->model_catalog_category->getCategories($category_id);
		if(empty($sub_categories)) {
			$tabs = $this->_getCategory($category, $setting);
		} else {
			$tabs = $this->_getCategoryTabs($sub_categories, $setting);
		}
		$this->mdata['tabs'] = $tabs;



		$this->mdata['module_description'] = isset($setting['description'][$this->config->get('config_language_id')])?$setting['description'][$this->config->get('config_language_id')]:"";
		$this->mdata['module_description'] = (html_entity_decode($this->mdata['module_description'], ENT_QUOTES, 'UTF-8'));
		$this->mdata['module'] = $module++;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/pavverticalcategorytabs.tpl')) {
			$template = $this->config->get('config_template') . '/template/module/pavverticalcategorytabs.tpl';
		} else {
			$template = 'default/template/module/pavverticalcategorytabs.tpl';
		}
		return $this->load->view($template, $this->mdata);
	}

	// chua fix
	public function _getCategory($category, $setting){
		$results = array();
        if( !empty($category) ) {
            $results[$category['category_id']]['products'] = $this->_getProducts($category['category_id'], $setting);
            $results[$category['category_id']]['name'] = $category['name'];
        }
		return $results;
	}

	private function _getCategoryTabs( $category_id, $setting ){
		$result = array();
		foreach ($category_id as $key=>$category){
			$result[$category['category_id']]['products'] = $this->_getProducts($category['category_id'], $setting);
			$result[$category['category_id']]['name'] = $category['name'];
		}
		return array_slice($result, 0, 4);
	}

	public function _getCProducts($products, $setting){
		$result = array();
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			if ($product_info) {
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
                    // Image Attribute for product
					$product_images = $this->model_catalog_product->getProductImages($result['product_id']);
					if(isset($product_images) && !empty($product_images)) {
							$thumb2 = $this->model_tool_image->resize($product_images[0]['image'], $setting['width'], $setting['height']);
					}
				} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $product_info['rating'];
				} else {
					$rating = false;
				}

				$result[] = array(
					'product_id' => $product_info['product_id'],
					'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'thumb'   	 => $image,
					'name'    	 => $product_info['name'],
					'price'   	 => $price,
					'special' 	 => $special,
					'rating'     => $rating,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
					'href'    	 => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
					'thumb2'     => isset($thumb2)?$thumb2:'',
				);
			}
		}
		return $result;
	}

	private function _getProducts($category_id, $setting){
		$products = array();
		$data = array(
			'filter_category_id' => $category_id,
			'filter_sub_category' => true,
			'sort'  => 'p.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => $setting['itemsperpage'],
		);
		$results = $this->model_catalog_product->getProducts($data);
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
                // Image Attribute for product
                $product_images = $this->model_catalog_product->getProductImages($result['product_id']);
                if(isset($product_images) && !empty($product_images)) {
                    $thumb2 = $this->model_tool_image->resize($product_images[0]['image'], $setting['width'], $setting['height']);
                }
			} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
			}

			if ((float)$result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}

			if ($this->config->get('config_tax')) {
				$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
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
					$price = $this->currency->format($this->tax->calculate($gp_price_min, $result['tax_class_id'], $this->config->get('config_tax'))) . '-' . $this->currency->format($this->tax->calculate($gp_price_max, $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = $this->language->get('text_gp_price_start') . $this->currency->format($this->tax->calculate($gp_price_min, $result['tax_class_id'], $this->config->get('config_tax')));
				}
			}
			$products[] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
					'price'       => $price,
					'special'     => $special,
					//'tax'         => $tax,
					'rating'      => $result['rating'],
					'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                    'thumb2'     => isset($thumb2)?$thumb2:'',
			);
		}
		return $products;
	}

	public function _d($var){
		echo "<pre>"; print_r($var); die;
	}
}
?>
