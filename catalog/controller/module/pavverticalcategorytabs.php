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
			'featured_category'=> array(),
			'description'  => '',
			'width'        => '202',
			'height'       => '224',
			'cwidth'        => '200',
			'cheight'       => '200',
				
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
		foreach ($setting['featured_category'] as $category) {
			$result = $this->model_catalog_category->getCategory($category);
			$this->mdata['tabs'][] = array(
				'name'=>$result['name'],
				'href'=>$this->url->link('product/category','path='.$result['category_id'],'SSL'),
			);
		}
		
		$products = array();
		
		foreach($setting['featured_product'] as $product) {
			$result = $this->model_catalog_product->getProduct($product);
			$product_vendor = $this->model_catalog_product->getSupplierProduct($result['product_id'],$result['vendor_id']);
	
			if ($result['image']) $image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
			else $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
			
			if ((float)$result['special']) $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
			else $special = false;
			
			if ($this->config->get('config_tax')) $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
			else $tax = false;
			
			if ($this->config->get('config_review_status')) $rating = (int)$result['rating'];
			else $rating = false;
			
			$original_price  = 0;
			$discount = 0;
			$price = false;
			$minimum = 0;
			$enabled = false;
			$tax = false;
			
			if ($product_vendor){
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_vendor['price'], $result['tax_class_id'], $this->config->get('config_tax')));
					if ($product_vendor['price'] < $result['price']) {
						$original_price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
						$discount = (int)(($result['price'] - $product_vendor['price'])*100/$result['price']);
					}
				}
				$minimum = $product_vendor['minimum'] > 0 ? $product_vendor['minimum'] : 1;
				$enabled = true;
			}
			
			if ($is_gp = $this->model_catalog_product->getGroupedProductGrouped($result['product_id'])) {
				$gp_price_min = $is_gp['gp_price_min'];
				$gp_price_max = $is_gp['gp_price_max'];
				$prices = $this->model_catalog_product->getGroupedProductMinimum($result['product_id']);
				if ($prices['minimum'] && $prices['maximum']) {
					$price = $this->currency->format($this->tax->calculate($prices['minimum'], $result['tax_class_id'], $this->config->get('config_tax'))) . ' - ' . $this->currency->format($this->tax->calculate($prices['maximum'], $result['tax_class_id'], $this->config->get('config_tax')));
					if ($tax) $tax = $this->currency->format($prices['minimum']) . '/' . $this->currency->format($prices['maximum']);
				} else {
					$price = $this->language->get('text_gp_price_start') . $this->currency->format($this->tax->calculate($prices['minimum'], $result['tax_class_id'], $this->config->get('config_tax')));
					if ($tax) $tax = $this->currency->format($prices['minimum']);
				}
				$result['type'] = 2;
				$enabled = true;
			}
			
			if ($enabled) {
				$products[] = array(
						'product_id'  => $result['product_id'],
						'thumb'       => $image,
						'name'        => $result['name'],
						'price'       => $price,
						'original_price' => $original_price,
						'discount'    => $discount,
						'special'     => $special,
						'rating'      => $result['rating'],
						'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
			}
		}
		
		$this->mdata['products'] = $products;

		$this->mdata['module_description'] = isset($setting['description'][$this->config->get('config_language_id')])?$setting['description'][$this->config->get('config_language_id')]:"";
		$this->mdata['description_status'] = isset($setting['description_status'])?$setting['description_status']:"";
		
		$this->mdata['module_description'] = (html_entity_decode($this->mdata['module_description'], ENT_QUOTES, 'UTF-8'));
		$this->mdata['module'] = $module++;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/pavverticalcategorytabs.tpl')) {
			$template = $this->config->get('config_template') . '/template/module/pavverticalcategorytabs.tpl';
		} else {
			$template = 'default/template/module/pavverticalcategorytabs.tpl';
		}
		return $this->load->view($template, $this->mdata);
	}

	public function _d($var){
		echo "<pre>"; print_r($var); die;
	}
}
?>
