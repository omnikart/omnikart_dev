<?php
class ControllerModuleBestSeller extends Controller {
	public function index($setting) {
		$this->load->language ( 'product/gp_grouped' );
		$this->load->language ( 'module/bestseller' );
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		
		$data ['text_tax'] = $this->language->get ( 'text_tax' );
		
		$data ['button_cart'] = $this->language->get ( 'button_cart' );
		$data ['button_wishlist'] = $this->language->get ( 'button_wishlist' );
		$data ['button_compare'] = $this->language->get ( 'button_compare' );
		
		$this->load->model ( 'catalog/product' );
		
		$this->load->model ( 'tool/image' );
		
		$data ['products'] = array ();
		
		$results = $this->model_catalog_product->getBestSellerProducts ( $setting ['limit'] );
		
		if ($results) {
			foreach ( $results as $result ) {
				if ($result ['image']) {
					$image = $this->model_tool_image->resize ( $result ['image'], $setting ['width'], $setting ['height'] );
				} else {
					$image = $this->model_tool_image->resize ( 'placeholder.png', $setting ['width'], $setting ['height'] );
				}
				
				if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
					$price = $this->currency->format ( $this->tax->calculate ( $result ['price'], $result ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
				} else {
					$price = false;
				}
				
				if (( float ) $result ['special']) {
					$special = $this->currency->format ( $this->tax->calculate ( $result ['special'], $result ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
				} else {
					$special = false;
				}
				
				if ($this->config->get ( 'config_tax' )) {
					$tax = $this->currency->format ( ( float ) $result ['special'] ? $result ['special'] : $result ['price'] );
				} else {
					$tax = false;
				}
				
				if ($this->config->get ( 'config_review_status' )) {
					$rating = $result ['rating'];
				} else {
					$rating = false;
				}
				if ($price && $is_gp = $this->model_catalog_product->getGroupedProductGrouped ( $result ['product_id'] )) {
					$gp_price_min = $is_gp ['gp_price_min'];
					$gp_price_max = $is_gp ['gp_price_max'];
					
					if ($gp_price_min [0] == '#') {
						$child_info = $this->model_catalog_product->getProduct ( substr ( $gp_price_min, 1 ) );
						$gp_price_min = $child_info ['special'] ? $child_info ['special'] : $child_info ['price'];
					}
					if ($gp_price_max [0] == '#') {
						$child_info = $this->model_catalog_product->getProduct ( substr ( $gp_price_max, 1 ) );
						$gp_price_max = $child_info ['special'] ? $child_info ['special'] : $child_info ['price'];
					}
					
					if ($gp_price_min && $gp_price_max) {
						$price = $this->language->get ( 'text_gp_price_min' ) . $this->currency->format ( $this->tax->calculate ( $gp_price_min, $result ['tax_class_id'], $this->config->get ( 'config_tax' ) ) ) . $this->language->get ( 'text_gp_price_max' ) . $this->currency->format ( $this->tax->calculate ( $gp_price_max, $result ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
						
						if ($tax) {
							$tax = $this->currency->format ( $gp_price_min ) . '/' . $this->currency->format ( $gp_price_max );
						}
					} else {
						$price = $this->language->get ( 'text_gp_price_start' ) . $this->currency->format ( $this->tax->calculate ( $gp_price_min, $result ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
						
						if ($tax) {
							$tax = $this->currency->format ( $gp_price_min );
						}
					}
				}
				$data ['products'] [] = array (
						'product_id' => $result ['product_id'],
						'thumb' => $image,
						'name' => $result ['name'],
						'description' => utf8_substr ( strip_tags ( html_entity_decode ( $result ['description'], ENT_QUOTES, 'UTF-8' ) ), 0, $this->config->get ( 'config_product_description_length' ) ) . '..',
						'price' => $price,
						'special' => $special,
						'tax' => $tax,
						'rating' => $rating,
						'href' => $this->url->link ( 'product/product', 'product_id=' . $result ['product_id'] ) 
				);
			}
			
			if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/module/bestseller.tpl' )) {
				return $this->load->view ( $this->config->get ( 'config_template' ) . '/template/module/bestseller.tpl', $data );
			} else {
				return $this->load->view ( 'default/template/module/bestseller.tpl', $data );
			}
		}
	}
}
