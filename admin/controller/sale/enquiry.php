<?php
class ControllerSaleEnquiry extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/order');

		$this->document->setTitle("Customer Enquiries");

		$this->load->model('module/enquiry');

		$this->getList();
	}

	public function add() {
	
	}

	public function edit() {
	
	}

	public function delete() {
		if ($this->request->post) {
			$this->load->model('module/enquiry');
			$this->model_module_enquiry->delete($this->request->post);
			$this->response->redirect($this->url->link('sale/enquiry', 'token=' . $this->session->data['token'],'SSL'));
		}
	}
	public function updateQuery() {
		$json = array();
		if ($this->request->post) {
			$this->load->model('module/enquiry');
			$this->model_module_enquiry->updateQuery($this->request->post);
			$this->response->redirect($this->url->link('sale/enquiry', 'token=' . $this->session->data['token'],'SSL'));
		}
	}

	public function updateEnquiry(){
		if (isset($this->request->post['enquiry_id'])) {
			$post = $this->request->post;
			$this->load->model('module/enquiry');
			$this->load->model('sale/customer');
			$result = $this->model_module_enquiry->getEnquiry($post['enquiry_id']);
			if (!$this->model_sale_customer->getCustomerByEmail($post['user_info']['email'])){
				//$customer_id = $this->model_sale_customer->();
			} else {
				$customer = $this->model_sale_customer->getCustomerByEmail($post['user_info']['email']);
				$customer_id = $customer['customer_id'];
			}
			
			$post['customer_id'] = $customer_id;
			$post['user_id'] = $this->user->getId();
				
			$this->model_module_enquiry->updateEnquiry($post);	
		    echo $customer_id;
		}
	}

	public function getEnquiry() {
		$json = array();

		if(isset($this->request->get['enquiry_id'])) {
			$enquiry_id=$this->request->get['enquiry_id'];
		} else 
			$enquiry_id=null;
		
		$type = 'json';
		
		if(isset($this->request->get['type']))
		{
			$type=$this->request->get['type'];
		}
		
						
		if($enquiry_id){
			$customer_id = 0;
			$json['addresses'] = array();
			$json['address_id'] = '0';
			$this->load->model('module/enquiry');
			$this->load->model('sale/customer');
			
			$result = $this->model_module_enquiry->getEnquiry($enquiry_id);
			$initial_query = $result['initial_query'];
			
			$json['query'] = array();
			
			if ($result['revision_products']){
				$revision_products = $result['revision_products']; 
				$revision_data = $result['revision_data'];
				
				
				$customer_id = $result['revision_query']['customer_id'];
				$json['query'] = $result['revision_products'];
				foreach ($result['revision_products'] as $product){	
					$json['query'][] = array(
						'name'=>$product['name'],
						'quantity'=>$product['quantity'],
						'unit'=>$product['unit'],
						'price'=>$product['price'],
						'total'=>$product['total'],
					);
				}
				
				$json['address_id'] = $revision_data['address_id'];
				
			} else { // Enquiry not saved to quotation
				$user_info = unserialize($initial_query['user_info']);// extract user info from serialized data
				if (isset($user_info['email'])) {
					if ($this->model_sale_customer->getCustomerByEmail($user_info['email'])){
						$customer = $this->model_sale_customer->getCustomerByEmail($user_info['email']);
						$customer_id = $customer['customer_id'];//initialized 
					}
				}
				

				foreach (unserialize($result1['query']) as $product){
					$json['query'][] = array(
						'name'=>$product['name'],
						'quantity'=>$product['quantity'],
						'unit'=>'',
						'price'=>'',
						'total'=>'',
					);
				}
			}
			
			if ($customer_id){
				$addresses = $this->model_sale_customer->getAddresses($customer_id);
				foreach ($addresses as $address){
					$json['addresses'][] = $address;
				}
			}
								
			
			$json['pdf_link'] = $this->url->link("sale/enquiry/getEnquiry&token=".$this->session->data['token']."&enquiry_id=".$this->request->get['enquiry_id']."&type=pdf",'','SSL');
			$json['id'] = $initial_query['id'];
			$json['user_info']=unserialize($initial_query['user_info']);
			
		}
		
		if ('json' == $type){
			$this->response->setOutput(json_encode($json));
		} else {
			$this->response->setOutput($this->load->view('sale/enquiry_form.tpl', $json));
		}
		
		
	}
	protected function getList() {
		
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_order_status'])) {
			$filter_order_status = $this->request->get['filter_order_status'];
		} else {
			$filter_order_status = null;
		}

		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} else {
			$filter_date_modified = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/enquiry', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['orders'] = array();

		$filter_data = array(
			'filter_order_id'      => $filter_order_id,
			'filter_customer'	   => $filter_customer,
			'filter_order_status'  => $filter_order_status,
			'filter_total'         => $filter_total,
			'filter_date_added'    => $filter_date_added,
			'filter_date_modified' => $filter_date_modified,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * 30,
			'limit'                => 30
		);

		$results = $this->model_module_enquiry->getEnquiries($filter_data);
		$total = $this->model_module_enquiry->getTotalEnquiries($filter_data);
		$data['enquiries'] = array();
		
		foreach ($results as $result) {
				$link = DIR_UPLOAD.'queries/'.$result['file'];
				$data['enquiries'][] = array(
						"id" => $result['id'],
						"user_info"=>unserialize($result['user_info']),
						"query"=>unserialize($result['query']),
						"date" => $result['date'],
						"status" => $result['status']
				);
		}
		
		$data['company_name']=$this->config->get('config_name');
		
		$data['enquiry_link'] = $this->url->link('sale/enquiry/getEnquiry&token=' . $this->session->data['token'],'', 'SSL');
		$data['update_link'] = $this->url->link('sale/enquiry/updateEnquiry&token=' . $this->session->data['token'],'', 'SSL');
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_missing'] = $this->language->get('text_missing');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_modified'] = $this->language->get('column_date_modified');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_return_id'] = $this->language->get('entry_return_id');
		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');

		$data['button_invoice_print'] = $this->language->get('button_invoice_print');
		$data['button_shipping_print'] = $this->language->get('button_shipping_print');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_view'] = $this->language->get('button_view');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['update'] = $this->url->link('sale/enquiry/updateQuery', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
		$data['action'] = $this->url->link('sale/enquiry/delete', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
		$data['sort_order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
		$data['sort_customer'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$data['sort_total'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
		$data['sort_date_modified'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$this->load->model('localisation/country');
		$data['countries'] = $this->model_localisation_country->getCountries();
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = 30;
		$pagination->url = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$this->load->model('localisation/unit_class');
		$data['unit_classes'] = $this->model_localisation_unit_class->getUnitClasses();
		$data['pagination'] = $pagination->render();

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/enquiry_list.tpl', $data));
	}
	public function install(){
		$this->load->model('module/enquiry');
		$this->model_module_enquiry->install();
	}

}
