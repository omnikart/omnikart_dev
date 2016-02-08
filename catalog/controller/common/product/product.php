<?php
$product_vendor = $this->model_account_customerpartner->getSupplierProduct($result['product_id'],$result['vendor_id']);

if ($result['image']) {
	$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
} else {
	$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
}
$original_price  = 0;
$discount = 0;
$price = false;
$minimum = 1;
if ($product_vendor){
	if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
		$price = $this->currency->format($this->tax->calculate($product_vendor['price'], $result['tax_class_id'], $this->config->get('config_tax')));
		if ($product_vendor['price'] < $result['price']) {
			$original_price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			$discount = (int)(($result['price'] - $product_vendor['price'])*100/$result['price']);
		}
	}
	$minimum = $product_vendor['minimum'] > 0 ? $product_vendor['minimum'] : 1;
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

if ($is_gp = $this->model_catalog_product->getGroupedProductGrouped($result['product_id'])) {
	$gp_price_min = $is_gp['gp_price_min'];
	$gp_price_max = $is_gp['gp_price_max'];
	$prices = $this->model_catalog_product->getGroupedProductMinimum($result['product_id']);
		
	/*
	 if ($gp_price_min[0] == '#') {
	 $child_info = $this->model_catalog_product->getProduct(substr($gp_price_min,1));
	 $child_supplier_info = $this->model_account_customerpartner->getSupplierProduct(substr($gp_price_min,1),$child_info['vendor_id']);
	 $gp_price_min = $child_info['special'] ? $child_info['special'] : $child_supplier_info['price'];
	 }
	 if ($gp_price_max[0] == '#') {
	 $child_info = $this->model_catalog_product->getProduct(substr($gp_price_max,1));
	 $child_supplier_info = $this->model_account_customerpartner->getSupplierProduct(substr($gp_price_min,1),$child_info['vendor_id']);
	 $gp_price_max = $child_info['special'] ? $child_info['special'] : $child_supplier_info['price'];
	 }
	 if ($gp_price_min && $gp_price_max) {
	 */
		
	if ($prices['minimum'] && $prices['maximum']) {
		$price = $this->language->get('text_gp_price_min') . $this->currency->format($this->tax->calculate($prices['minimum'], $result['tax_class_id'], $this->config->get('config_tax'))) . $this->language->get('text_gp_price_max') . $this->currency->format($this->tax->calculate($prices['maximum'], $result['tax_class_id'], $this->config->get('config_tax')));

		if ($tax) {
			$tax = $this->currency->format($prices['minimum']) . '/' . $this->currency->format($prices['maximum']);
		}
	} else {
		$price = $this->language->get('text_gp_price_start') . $this->currency->format($this->tax->calculate($prices['minimum'], $result['tax_class_id'], $this->config->get('config_tax')));

		if ($tax) {
			$tax = $this->currency->format($prices['minimum']);
		}
	}
	$result['type'] = 2;
}

$enabled = true;
if ($result['type']!='2' && $result['price']=='0') $enabled = false;
	
$data['products'][] = array(
		'product_id'  => $result['product_id'],
		'thumb'       => $image,
		'name'        => $result['name'],
		'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
		'price'       => $price,
		'original_price' => $original_price,
		'discount'    => $discount,
		'enabled' 	  => $enabled,
		'special'     => $special,
		'tax'         => $tax,
		'minimum'     => $minimum,
		'rating'      => $result['rating'],
		'type'		  => $result['type'],
		'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url)
);