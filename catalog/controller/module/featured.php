<?php
class ControllerModuleFeatured extends Controller {
	public function index($setting) {
		$this->load->language('product/gp_grouped');
		static $module = 0;
		
		$this->load->language('module/featured');

		$data['heading_title'] = $setting['name'];

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');
		$this->document->addStyle('catalog/view/javascript/jquery/homepage/flexslider.css');
		$this->document->addScript('catalog/view/javascript/jquery/homepage/jquery.flexslider-min.js');
		$data['products'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}
	
		$data['limit'] = (int)$setting['limit'];

		if (!empty($setting['product'])) {
			$products = $setting['product'];
			

			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					$product_vendor = $this->model_catalog_product->getSupplierProduct($product_info['product_id'],$product_info['vendor_id']);
					
					if ($product_info['image']) $image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
					else $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					
					if ($this->config->get('config_review_status')) $rating = $product_info['rating'];
					else $rating = false;

					if ((float)$product_info['special']) $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
					else $special = false;
					
					$original_price  = 0;
					$discount = 0;
					$price = false;
					$minimum = 0;
					$enabled = false;
					$tax = false;
					
					if ($product_vendor) {
						if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
							$price = $this->currency->format($this->tax->calculate($product_vendor['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
							if ($product_vendor['price'] < $product_info['price']) {
								$original_price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
								$discount = (int)(($product_info['price'] - $product_vendor['price'])*100/$product_info['price']);
							}
						}
						if ($this->config->get('config_tax')) $tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
						$minimum = $product_vendor['minimum'] > 0 ? $product_vendor['minimum'] : 1;
						$enabled = true;
					}
					
					if ($is_gp = $this->model_catalog_product->getGroupedProductGrouped($product_info['product_id'])) {
						$gp_price_min = $is_gp['gp_price_min'];
						$gp_price_max = $is_gp['gp_price_max'];
						$prices = $this->model_catalog_product->getGroupedProductMinimum($product_info['product_id']);
						if ($prices['minimum'] && $prices['maximum']) {
							$price = $this->currency->format($this->tax->calculate($prices['minimum'], $product_info['tax_class_id'], $this->config->get('config_tax'))) . ' - ' . $this->currency->format($this->tax->calculate($prices['maximum'], $product_info['tax_class_id'], $this->config->get('config_tax')));
							if ($tax) $tax = $this->currency->format($prices['minimum']) . '/' . $this->currency->format($prices['maximum']);
						} else {
							$price = $this->language->get('text_gp_price_start') . $this->currency->format($this->tax->calculate($prices['minimum'], $product_info['tax_class_id'], $this->config->get('config_tax')));
							if ($tax) $tax = $this->currency->format($prices['minimum']);
						}
						$product_info['type'] = 2;
					}
					
					if ($enabled || '2'==$product_info['type']) {
						$data['products'][] = array(
								'product_id'  => $product_info['product_id'],
								'thumb'       => $image,
								'name'        => $product_info['name'],
								'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
								'price'       => $price,
								'original_price' => $original_price,
								'discount'    => $discount,
								'minimum'     => $minimum,
								'special'     => $special,
								'tax'         => $tax,
								'enabled' 	  => $enabled,
								'rating'      => $rating,
								'type'		  => $product_info['type'],
								'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
						);
					}
				}
			}
		}
		$data['module'] = $module++;
		
		if ($data['products']) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featured.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/featured.tpl', $data);
			} else {
				return $this->load->view('default/template/module/featured.tpl', $data);
			}
		}
	}
}
