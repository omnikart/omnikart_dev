<?php
class ControllerAccountCdp extends Controller {
	private $error = array();
	public function index() {
		$this->load->model("account/customerpartner");
		
		$this->checkuser();
		
		$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
		);
		
		$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/cd', '', 'SSL')
		);
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		if (isset($this->request->get['category_id'])){
			$filter['category_id'] = $this->request->get['category_id'];
		}
		
		
		$this->load->model('account/cd');
	
		$this->load->language('product/category');
	
		$this->load->model('catalog/category');
	
		$this->load->model('catalog/product');
	
		$this->load->model('tool/image');

		
		$data['text_refine'] = $this->language->get('text_refine');
		$data['text_empty'] = $this->language->get('text_empty');
		$data['text_quantity'] = $this->language->get('text_quantity');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_model'] = $this->language->get('text_model');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['text_points'] = $this->language->get('text_points');
		$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		$data['text_sort'] = $this->language->get('text_sort');
		$data['text_limit'] = $this->language->get('text_limit');
		
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_list'] = $this->language->get('button_list');
		$data['button_grid'] = $this->language->get('button_grid');		
		
		$data['dbe'] = true;
		$data['products'] = array();
		$data['category'] = $this->model_account_cd->getCategory($this->request->get['category_id']);
		$results = $this->model_account_cd->getProducts($filter);
		foreach ($results as $result) {
			$product_info = $this->model_catalog_product->getProduct($result['product_id']);
			if ($product_info){
				
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
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
				
				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
				} else {
					$tax = false;
				}
				
				if ($this->config->get('config_review_status')) {
					$rating = (int)$product_info['rating'];
				} else {
					$rating = false;
				}
				
				$data['products'][] = array(
						'product_id'  => $product_info['product_id'],
						'thumb'       => $image,
						'name'        => $product_info['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'tax'         => $tax,
						'remove'	  => $this->url->link('account/cdp/removeproduct','&product_id=' . $product_info['product_id']),
						'minimum'     => $product_info['minimum'] > 0 ? $product_info['minimum'] : 1,
						'rating'      => $product_info['rating'],
						'href'        => $this->url->link('product/product','&product_id=' . $product_info['product_id'])
				);
			}
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/cdp.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/cdp.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/cdp.tpl', $data));
		}
		
	}
	public function updatecategory(){
		$json = array();
		$this->load->model("account/customerpartner");
		$this->checkuser();
		$this->load->model('account/cd');
		if ($this->request->post && isset($this->request->post['category_id'])){
			if (!isset($this->request->post['payment_address'])) $json['payment_address'] = "Please select Payment Address"; 
			if (!isset($this->request->post['shipping_address'])) $json['shipping_address'] = "Please select Payment Address";
			if (!$json){
				$this->model_account_cd->updateCategory($this->request->post);
				$json['success'] = "Updates Successfully";
				
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function checkuser(){
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
			return false;
		}
		
		//$customerRights = $this->model_account_customerpartner->getCustomerGroupRights($this->customer->getGroupId());
		$customerRights = $this->customer->getRights();
		if (!(isset($customerRights['rights']) && in_array('db',$customerRights['rights']))) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');
			$this->response->redirect($this->url->link('account/account', '', 'SSL'));
			return false;
		}
		
		return true;
	}
	

}