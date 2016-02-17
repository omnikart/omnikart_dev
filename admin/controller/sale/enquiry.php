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

	protected function getList() {

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
			'filter_customer'	   => $filter_customer,
			'filter_date_added'    => $filter_date_added,
			'start'                => ($page - 1) * 30,
			'limit'                => 30
		);

		$results = $this->model_module_enquiry->getEnquiries($filter_data);
		$total = $this->model_module_enquiry->getTotalEnquiries($filter_data);
		$data['enquiries'] = $results;

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

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['update'] = $this->url->link('sale/enquiry/updateQuery', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
		$data['action'] = $this->url->link('sale/enquiry/delete', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');

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

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/enquiry_list.tpl', $data));
	}

	public function install(){
		$this->load->model('module/enquiry');
		$this->model_module_enquiry->install();
	}

	public function quotation(){
		if (isset($this->request->get['enquiry_id']) && (int)$this->request->get['enquiry_id']) {
			$this->load->model('module/enquiry');
			$data = $this->model_module_enquiry->getEnquiry($this->request->get['enquiry_id']);
			$this->load->model('localisation/tax_class');
			
			$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
			$data['text_none'] = "None";
			if (isset($this->request->post['tax_class_id'])) {
				$data['tax_class_id'] = $this->request->post['tax_class_id'];
			} elseif (!empty($product_info)) {
				$data['tax_class_id'] = $product_info['tax_class_id'];
			} else {
				$data['tax_class_id'] = 0;
			}
			$data['payment_term']=array();
			$this->load->model('localisation/payment_term');
			$data['payment_term'] = $this->model_localisation_payment_term->getPaymentTerms();
			$this->response->setOutput($this->load->view('sale/quotation.tpl',$data));
		}
	}

	public function updateQuote(){
		$data= array();
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['enquiry'])) {
			$data = $this->request->post['enquiry'];
			$this->load->model('module/enquiry');
			foreach ($data['product'] as $key => $product) {
				$data['product'][$key]['total'] = $this->tax->calculate($product['unit_price'],$product['tax_class_id'],true)*$product['quantity'];
			}
			$this->model_module_enquiry->updateQuote($data);
		}
	}

	public function getEnquiry(){

		if (isset($this->request->get['enquiry_id']) && (int)$this->request->get['enquiry_id']) {
			if (isset($this->request->get['quote_revision_id']))
				$quote_revision_id = $this->request->get['quote_revision_id'];
			else
				$quote_revision_id = 0;
			
			if (isset($this->request->get['quote_id']))
			$quote_id = $this->request->get['quote_id'];
			else
			$quote_id = 0;
		
			$this->load->model('module/enquiry');
			$data = $this->model_module_enquiry->getEnquiry($this->request->get['enquiry_id'],$quote_id,$quote_revision_id);
			$this->load->model('localisation/tax_class');
			$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
			$data['text_none'] = "None";
			if (isset($this->request->post['tax_class_id'])) {
				$data['tax_class_id'] = $this->request->post['tax_class_id'];
			} elseif (!empty($product_info)) {
				$data['tax_class_id'] = $product_info['tax_class_id'];
			} else {
				$data['tax_class_id'] = 0;
			}

			if (isset($this->request->post['weight'])) {
				$data['weight'] = $this->request->post['weight'];
			} elseif (!empty($product_info)) {
				$data['weight'] = $product_info['weight'];
			} else {
				$data['weight'] = '';
			}

			$this->load->model('localisation/weight_class');

			$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

			if (isset($this->request->post['weight_class_id'])) {
				$data['weight_class_id'] = $this->request->post['weight_class_id'];
			} elseif (!empty($product_info)) {
				$data['weight_class_id'] = $product_info['weight_class_id'];
			} else {
				$data['weight_class_id'] = $this->config->get('config_weight_class_id');
			}

			if (isset($this->request->post['length'])) {
				$data['length'] = $this->request->post['length'];
			} elseif (!empty($product_info)) {
				$data['length'] = $product_info['length'];
			} else {
				$data['length'] = '';
			}

			if (isset($this->request->post['width'])) {
				$data['width'] = $this->request->post['width'];
			} elseif (!empty($product_info)) {
				$data['width'] = $product_info['width'];
			} else {
				$data['width'] = '';
			}

			if (isset($this->request->post['height'])) {
				$data['height'] = $this->request->post['height'];
			} elseif (!empty($product_info)) {
				$data['height'] = $product_info['height'];
			} else {
				$data['height'] = '';
			}

			$this->load->model('localisation/length_class');

			$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

			if (isset($this->request->post['length_class_id'])) {
				$data['length_class_id'] = $this->request->post['length_class_id'];
			} elseif (!empty($product_info)) {
				$data['length_class_id'] = $product_info['length_class_id'];
			} else {
				$data['length_class_id'] = $this->config->get('config_length_class_id');
			}
			$this->load->model('localisation/unit_class');
			$this->load->model('localisation/payment_term');
			$data['unit_classes'] = $this->model_localisation_unit_class->getUnitClasses();
			$data['payment_term']=array();
			$data['payment_term'] = $this->model_localisation_payment_term->getPaymentTerms();			
			$data['config_name']=$this->config->get('config_name');
			$data['config_owner']=$this->config->get('config_owner');
			$data['config_address']=$this->config->get('config_address');
			$data['config_email']=$this->config->get('config_email');
			$data['config_telephone']=$this->config->get('config_telephone');
			$data['enquiryupdates'] = $this->url->link('sale/enquiry/quotation&token=' . $this->session->data['token'] , '&enquiry_id='.(int)$this->request->get['enquiry_id'], 'SSL');
			$data['token'] = $this->session->data['token'];
			$url="";
			$data['get_revision_link'] = $this->url->link('sale/enquiry/getEnquiry&token=' . $this->session->data['token'] . $url,'', 'SSL');
			$this->response->setOutput($this->load->view('sale/enquiry_form.tpl',$data));
		}
	}
}
