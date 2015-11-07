<?php
class ControllerCheckoutOrderlater extends Controller {
	private $error = array();

	public function index() {
		$this->checkuser();
		
		$this->load->model("account/customerpartner");
				
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_account_customerpartner->savecart($this->request->post);
			unset($this->session->data['cart']);
			$this->response->redirect($this->url->link('checkout/orderlater','', 'SSL'));
		}
		
		$this->getlist();
	}
	
	public function getlist(){
		
		$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home','','SSL')
		);
		
		$data['breadcrumbs'][] = array(
				'text' => 'Schedule Order',
				'href' => $this->url->link('checkout/orderlater', '', 'SSL')
		);
		
		$this->document->setTitle("Schedule Order");
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
		}
		
		$data['heading_title'] = "Scheduled Order";

		$oldcarts = $this->model_account_customerpartner->getsavedcarts();
		$this->load->model('catalog/product');
		$data['carts'] = array();
		
		$today=date_create(date("Y-m-d"));
		
		foreach ($oldcarts as $oldcart){
			$products = array();
			foreach (unserialize($oldcart['cart']) as $key => $quantity){
				$product = unserialize(base64_decode($key));
				$result = $this->model_catalog_product->getProduct($product['product_id']);
				$products[$key] = array('name'=>$result['name'],'quantity'=>$quantity,'product_id'=>$product['product_id']);
			}
			$cartdate = date_create($oldcart['date']);
			$diff=date_diff($today,$cartdate)->format("%R%a");
			$data['carts'][] = array('id' => $oldcart['id'],'date' => $oldcart['date'],'days'=>$diff, 'products' => $products, 'name'=> $oldcart['name']);
		}
		
		
		$data['savecart'] = $this->url->link('checkout/orderlater','','SSL');
		
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/orderlater.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/orderlater.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/checkout/orderlater.tpl', $data));
		}
	}
	public function updatecart(){
		$this->load->model("account/customerpartner");
		
		$this->checkuser();
		
		$data = $this->request->post;
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if (isset($data['id']) && $data['id']) {
				$this->model_account_customerpartner->updatecart($data);
				$json['success'] = "Card Updated Successfully";
			}
		}
		$this->response->setOutput(json_encode($json));
	}
	public function buycart(){
		$this->load->model("account/customerpartner");
		$this->checkuser();
		$data = $this->request->post;
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if (isset($data['id']) && $data['id']) {
				$result = $this->model_account_customerpartner->getsavedcart($data['id']);
				$cart = unserialize($result['cart']);
				$this->session->data['cart'] = $cart;
				$json['success'] = "Card Updated Successfully";
				$json['redirect'] = $this->url->link('checkout/checkout','', 'SSL');
				
			}
		}
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