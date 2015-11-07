<?php
class ControllerAccountCd extends Controller {
	private $error = array();
	public function index() {
		$this->load->model("account/customerpartner");
		$this->load->language('account/cd');
		
		$this->checkuser();
		
		$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
		);
		
		$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_cd'),
				'href' => $this->url->link('account/cd', '', 'SSL')
		);
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$this->load->model('account/cd');
	
		$this->load->language('product/category');
	
		$this->load->model('catalog/category');
	
		$this->load->model('catalog/product');
	
		$this->load->model('tool/image');

		$this->document->setTitle($this->language->get('heading_title'));
		
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
		
		$data['dbe'] = false;
		$results = $this->model_account_cd->getCategories();
		foreach ($results as $result) {
			
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
			}
			
			$data['categories'][] = array(
					'name'  => $result['name'],
					'category_id'  => $result['category_id'],
					'image' => $image,
					'href'  => $this->url->link('account/cdp', 'category_id='.$result['category_id'])
			);
		}
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/cd.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/cd.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/cd.tpl', $data));
		}
		
	}
	
	public function addProductCd() {
		$this->load->model("account/customerpartner");
		
		$error = array();
		$this->checkuser();
		$products = array();
		
		$this->load->model('account/cd');
		$data = $this->request->post;
		if(isset($data['products'])){
			if (!isset($data['category_id']) || $data['category_id']=="N") $error['error_text'] = "Please select new category";
			elseif (isset($data['category_id']) && $data['category_id'] =='0' && empty($data['category-name'])) $error['error_text'] = "Please enter a category name";
		
			if (empty($error)){
				$result = $this->model_account_cd->addProducts($data);
				$error['success'] = count($data['products'])." Products added successfully.";
			}
		} else $error['error_text'] = "Please close this window and select atleast one product.";
		echo json_encode($error);
	}
	public function removeproducts(){
		$json = array();
		$this->load->model("account/customerpartner");
		$this->checkuser();
		$products = array();
	
		$this->load->model('account/cd');
		$data = $this->request->post;
		if(isset($data['products'])){
			$this->model_account_cd->removeproducts($data);
			$json['success'] = "Products Deleted Successfully";
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function getCategories() {
		$this->load->model("account/customerpartner");
		$this->checkuser();
		$this->load->model('account/cd');
		$cats = $this->model_account_cd->getCategories();
		$json = '<select class="form-control col-sm-12" name="category_id"><option value="N">-- Please Select --</option><option value="0">New Category</option>';
		if ($cats) {
			foreach ($cats as $cat){
				$json .= '<option value='.$cat['category_id'].'>'.$cat['name'].'</option>';
			}
		}
		$json .= '</select>';
		
		$this->response->setOutput(json_encode($json));
	}
	public function buycategory() {
		$this->load->model("account/customerpartner");
		$json = array();
		$this->checkuser();
		$this->load->model("account/cd");
		
		if (isset($this->request->post['category_id'])){
			$filter['category_id'] = $this->request->post['category_id'];
		}
		if (isset($this->request->post['product'])){
			$products = $this->request->post['product'];
		} else {
			$products = array();
		}
		if (isset($this->request->post['productq'])){
			$productq = $this->request->post['productq'];
		} else {
			$productq = array();
		}
		
		$data['category'] = $this->model_account_cd->getCategory($this->request->post['category_id']);
		if (!$products) {
			$this->load->model('account/cd');
			$results = $this->model_account_cd->getProducts($filter);
			foreach ($results as $result) {
				$this->add($result['product_id'],$result['quantity'],array());
					
			}
		} else {
			foreach ($products as $product){
				$this->add($product['product_id'],$productq[$product['product_id']]['quantity'],array());
			}
		}
		unset($this->session->data['shipping_method']);
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['payment_method']);
		unset($this->session->data['payment_methods']);
		
		// Totals
		$this->load->model('extension/extension');
	
		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();		

		// Display prices
		if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
			$sort_order = array();
		
			$results = $this->model_extension_extension->getExtensions('total');
		
			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}
		
			array_multisort($sort_order, SORT_ASC, $results);
		
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);
		
					$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
				}
			}
		
			$sort_order = array();
		
			foreach ($total_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
		
			array_multisort($sort_order, SORT_ASC, $total_data);
		}

		$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
	}
	
	private function add($product_id,$quantity,$option) {
		$this->load->language('checkout/cart');
	
		$json = array();
	
		$this->load->model('catalog/product');
	
		$product_info = $this->model_catalog_product->getProduct($product_id);
	
		if ($product_info) {

			$product_options = $this->model_catalog_product->getProductOptions($product_id);
	
			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				}
			}
	
			if (isset($this->request->post['recurring_id'])) {
				$recurring_id = $this->request->post['recurring_id'];
			} else {
				$recurring_id = 0;
			}
	
			$recurrings = $this->model_catalog_product->getProfiles($product_info['product_id']);
	
			if ($recurrings) {
				$recurring_ids = array();
	
				foreach ($recurrings as $recurring) {
					$recurring_ids[] = $recurring['recurring_id'];
				}
	
				if (!in_array($recurring_id, $recurring_ids)) {
					$json['error']['recurring'] = $this->language->get('error_recurring_required');
				}
			}
	
			if (!$json) {
				$this->cart->add($product_id, $quantity, $option, $recurring_id,$vendor_id);
				
				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $product_id), $product_info['name'], $this->url->link('checkout/cart'));
	
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
	
				// Totals
				$this->load->model('extension/extension');
	
				$total_data = array();
				$total = 0;
				$taxes = $this->cart->getTaxes();
	
				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$sort_order = array();
	
					$results = $this->model_extension_extension->getExtensions('total');
	
					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
					}
	
					array_multisort($sort_order, SORT_ASC, $results);
	
					foreach ($results as $result) {
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('total/' . $result['code']);
	
							$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
						}
					}
	
					$sort_order = array();
	
					foreach ($total_data as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}
	
					array_multisort($sort_order, SORT_ASC, $total_data);
				}
	
				$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
			} else {
				$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $product_id));
			}
		}
		return true;
		//$this->response->addHeader('Content-Type: application/json');
		//$this->response->setOutput(json_encode($json));
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