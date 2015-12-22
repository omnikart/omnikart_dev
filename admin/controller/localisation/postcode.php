<?php
class ControllerLocalisationPostcode extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('localisation/postcode');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/postcode');

		$this->getList();
	}

	public function add() {
		$this->load->language('localisation/postcode');
	
		$this->document->setTitle($this->language->get('heading_title'));
	
		$this->load->model('localisation/postcode');
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$postcode = $this->request->post;
			$this->load->model('localisation/country');
			$country = $this->model_localisation_country->getCountry($postcode['country_id']);
			$postcode['country_iso_code_2'] = $country['iso_code_2'];
			$this->load->model('localisation/zone');
			$zone = $this->model_localisation_zone->getZone($postcode['zone_id']);
			$postcode['zone_code'] = $zone['code'];
			$this->model_localisation_postcode->addpostcode($postcode);
			$this->session->data['success'] = $this->language->get('text_success');
	
			$url = '';
	
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
	
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
	
			$this->response->redirect($this->url->link('localisation/postcode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	
		$this->getForm();
	}
	
	protected function getlist(){
		//$this->document->addScript('view/javascript/select/jquery.easyselect.min.js');
		//$this->document->addStyle('view/javascript/select/jquery.easyselect.min.css');
		
		
		$url = '';
		$fields = array('filter_code'=>'', 'filter_city'=>'', 'filter_zone'=>'', 'filter_status'=>Null, 'sort'=>'p.postcode','order'=>'ASC','page'=>1);
		$sort = array();
		foreach ($fields as $key => $field){
			if (isset($this->request->get[$key])) {
				$sort[$key] = $this->request->get[$key];
				$url .= '&'.$key.'='.$sort[$key];
			} else {
				$sort[$key] = $field;
			}
			$data[$key] = $sort[$key];
		}
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
		);
		
		$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('localisation/postcode', 'token=' . $this->session->data['token'] . $url, 'SSL'),
				'separator' => ' :: '
		);
		
		$data['add'] = $this->url->link('localisation/postcode/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('localisation/postcode/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['update'] = $this->url->link('localisation/postcode/update', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$data['postcodes'] = array();
		
		$sort['start'] 	= ($sort['page'] - 1) * $this->config->get('config_limit_admin');
		$sort['limit'] 	= $this->config->get('config_limit_admin');
		
		$postcode_total = $this->model_localisation_postcode->getTotalPostcodes($sort);
		$results = $this->model_localisation_postcode->getPostcodes($sort);
		
		foreach ($results as $result) {
			$action[] = array(
				'text' => $this->language->get('text_edit'),
			);
		
			$data['postcodes'][] = array(
					'postcode_id' 	=> $result['postcode_id'],
					'postcode'      => $result['postcode'],
					'city_id'		=> $result['city_id'],
					'city'		  	=> $result['city'],
					'zone_id' 	    => $result['zone_id'],
					'zone' 	      	=> $result['zone'],
					'country_id' 	=> $result['country_id'],
					'country' 	  	=> $result['country'],
					'payment' 		=> $result['payment'],
					'shipping' 		=> $result['shipping'],
					'service' 		=> $result['service'],
					'status' 		=> $result['status'],
					'edit'     		=> $this->url->link('localisation/postcode/update', 'token=' . $this->session->data['token'] . '&postcode_id=' . $result['postcode_id'] . $url, 'SSL')
			);
		}
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_city'] = $this->language->get('column_city');
		$data['column_zone'] = $this->language->get('column_zone');
		$data['column_country'] = $this->language->get('column_country');
		$data['column_payment'] = $this->language->get('column_payment');
		$data['column_shipping'] = $this->language->get('column_shipping');
		$data['column_service'] = $this->language->get('column_service');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');
		$data['text_list']	= $this->language->get('text_list');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_edit'] = $this->language->get('button_edit');
		
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
		if (isset($sort['filter_city']) && $sort['filter_city']) {
			$url .= '&filter_city='.$sort['filter_city'];
		}
		if (isset($sort['filter_zone']) && $sort['filter_zone']) {
			$url .= '&filter_zone='.$sort['filter_zone'];
		}
		if (isset($sort['filter_status']) && $sort['filter_status']) {
			$url .= '&filter_status='.$sort['filter_status'];
		}
		if (isset($sort['filter_code']) && $sort['filter_code']) {
			$url .= '&filter_code='.$sort['filter_code'];
		}
		$url .= '&order=' . (($sort['order']=='ASC')?'DESC':'ASC');
		
		$url1 = $url.'&page=' . $sort['page'];
		$data['sort_postcode'] = $this->url->link('localisation/postcode', 'token=' . $this->session->data['token'] . '&sort=p.postcode' . $url1, 'SSL');
		$data['sort_city'] = $this->url->link('localisation/postcode', 'token=' . $this->session->data['token'] . '&sort=c.name' . $url1 , 'SSL');
		$data['sort_zone'] = $this->url->link('localisation/postcode', 'token=' . $this->session->data['token'] . '&sort=z.name' . $url1 , 'SSL');
		$data['sort_country'] = $this->url->link('localisation/postcode', 'token=' . $this->session->data['token'] . '&sort=ct.name' . $url1 , 'SSL');

		$url1 = $url.'&sort='.$sort['sort'];
		
		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');
		$data['country_id'] = $this->config->get('config_country_id');
		$data['zone_id'] = $this->config->get('config_zone_id');
		$data['countries'] = $this->model_localisation_country->getCountries();
		if($data['country_id']){
			$data['zones'] = $this->model_localisation_zone->getZonesByCountryId($data['country_id']);
		}
		$pagination = new Pagination();
		$pagination->total = $postcode_total;
		$pagination->page = $sort['page'];
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('localisation/postcode', 'token=' . $this->session->data['token'] . $url1  . '&page={page}', 'SSL');
		
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($postcode_total) ? (($sort['page'] - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($sort['page'] - 1) * $this->config->get('config_limit_admin')) > ($postcode_total - $this->config->get('config_limit_admin'))) ? $postcode_total : ((($sort['page'] - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $postcode_total, ceil($postcode_total / $this->config->get('config_limit_admin')));
		
		$data['sort'] = $sort['sort'];
		$data['order'] = $sort['order'];
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['token'] = $this->session->data['token'];
		$this->response->setOutput($this->load->view('localisation/postcode_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['country_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_initial_postcode'] = $this->language->get('entry_initial_postcode');
		$data['entry_final_postcode'] = $this->language->get('entry_final_postcode');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_status'] = $this->language->get('entry_status');
	
		$data['help_address_format'] = $this->language->get('help_address_format');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
	
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
		if (isset($this->error['final_name'])) {
			$data['error_final_postcode'] = $this->error['final_name'];
		} else {
			$data['error_final_postcode'] = '';
		}
		if (isset($this->error['initial_postcode'])) {
			$data['error_initial_postcode'] = $this->error['initial_postcode'];
		} else {
			$data['error_initial_postcode'] = '';
		}
		
		$url = '';
		
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
			'href' => $this->url->link('localisation/postcode', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	
		if (!isset($this->request->get['postcode_id'])) {
			$data['action'] = $this->url->link('localisation/postcode/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('localisation/postcode/edit', 'token=' . $this->session->data['token'] . '&postcode_id=' . $this->request->get['postcode_id'] . $url, 'SSL');
		}
	
		$data['cancel'] = $this->url->link('localisation/postcode', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		if (isset($this->request->get['postcode_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$postcode_info = $this->model_localisation_postcode->getpostcode($this->request->get['postcode_id']);
		}
	
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($postcode_info)) {
			$data['name'] = $postcode_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['country_id'])) {
			$data['country_id'] = $this->request->post['country_id'];
		} elseif (!empty($postcode_info)) {
			$data['country_id'] = $postcode_info['country_id'];
		} else {
			$data['country_id'] = '';
		}
		
		if (isset($this->request->post['zone_id'])) {
			$data['zone_id'] = $this->request->post['zone_id'];
		} elseif (!empty($postcode_info)) {
			$data['zone_id'] = $postcode_info['zone_id'];
		} else {
			$data['zone_id'] = $this->config->get('config_zone_id');
		}
	
		if (isset($this->request->post['initial_postcode'])) {
			$data['initial_postcode'] = $this->request->post['initial_postcode'];
		} elseif (!empty($postcode_info)) {
			$data['initial_postcode'] = $postcode_info['initial_postcode'];
		} else {
			$data['initial_postcode'] = '';
		}
	
		if (isset($this->request->post['final_postcode'])) {
			$data['final_postcode'] = $this->request->post['final_postcode'];
		} elseif (!empty($postcode_info)) {
			$data['final_postcode'] = $postcode_info['final_postcode'];
		} else {
			$data['final_postcode'] = '';
		}
	
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($postcode_info)) {
			$data['status'] = $postcode_info['status'];
		} else {
			$data['status'] = '1';
		}
	
		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');
		$data['countries'] = $this->model_localisation_country->getCountries();
		if($data['country_id']){
			$data['zones'] = $this->model_localisation_zone->getZonesByCountryId($data['country_id']);
		}
		
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/postcode_form.tpl', $data));
		
	}
	public function autocompletezones(){
		$json = array();
		$this->load->model('localisation/zone');
		if (isset($this->request->get['country_id']) && is_int((int)$this->request->get['country_id'])){
			$zones = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);
			foreach($zones as $zone){
				$json[] = array('name'=>$zone['name'],'zone_id'=>$zone['zone_id']);
			}
		}
		$this->response->setOutput(json_encode($json));
	}

	public function update(){
		if (isset($this->request->post['selected'])) {
			$this->load->model('localisation/postcode');
			$data = $this->request->post;
			$this->model_localisation_postcode->update($data);			
		}
	}
	
	public function install(){
		$this->load->model('localisation/postcode');
		$this->model_localisation_postcode->install();
	}
	private function validateForm(){
		if (!$this->user->hasPermission('modify', 'localisation/postcode')) {
				$this->error['warning'] = $this->language->get('error_permission');
		}
	
		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 128)) {
				$this->error['name'] = $this->language->get('error_name');
		}
	
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}