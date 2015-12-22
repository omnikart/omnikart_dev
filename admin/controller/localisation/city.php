<?php
class ControllerLocalisationCity extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('localisation/city');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/city');

		$this->getList();
	}

	public function add() {
		$this->load->language('localisation/city');
	
		$this->document->setTitle($this->language->get('heading_title'));
	
		$this->load->model('localisation/city');
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$city = $this->request->post;
			$this->load->model('localisation/country');
			$country = $this->model_localisation_country->getCountry($city['country_id']);
			$city['country_iso_code_2'] = $country['iso_code_2'];
			$this->load->model('localisation/zone');
			$zone = $this->model_localisation_zone->getZone($city['zone_id']);
			$city['zone_code'] = $zone['code'];
			$this->model_localisation_city->addCity($city);
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
	
			$this->response->redirect($this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	
		$this->getForm();
	}
	
	public function update() {
		$this->load->language('localisation/city');
	
		$this->document->setTitle($this->language->get('heading_title'));
	
		$this->load->model('localisation/city');
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$city = $this->request->post;
			$this->load->model('localisation/country');
			$country = $this->model_localisation_country->getCountry($city['country_id']);
			$city['country_iso_code_2'] = $country['iso_code_2'];
			$this->load->model('localisation/zone');
			$zone = $this->model_localisation_zone->getZone($city['zone_id']);
			$city['zone_code'] = $zone['code'];
				
			$this->model_localisation_city->editCity($this->request->get['city_id'], $city);
	
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
	
			$this->redirect($this->url->link('localisation/cities_table', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	
		$this->getForm();
	}
	
	public function delete(){
		$this->load->language('localisation/city');
	
		$this->document->setTitle("City");
	
		$this->load->model('localisation/city');
	
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $city_id) {
				$this->model_localisation_city->deleteCity($city_id);
			}
	
			$this->session->data['success'] = $this->language->get('text_success');
	
			$url = '';
			$fields = array('filter_name'=>'', 'sort'=>'c.name','order'=>'ASC','page'=>1);
			$sort = array();
			foreach ($fields as $key => $field){
				if (isset($this->request->get[$key])) {
					$sort[$key] = $this->request->get[$key];
					$url .= '&'.$key.'='.$sort[$key];
				} else {
					$sort[$key] = $field;
					$url .= '&'.$key.'='.$sort[$key];
				}
			}
	
			$this->response->redirect($this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	
		$this->getList();
	}
	
	protected function getlist(){
		$url = '';
		$fields = array('filter_name'=>'', 'sort'=>'c.name','order'=>'ASC','page'=>1);
		$sort = array();
		foreach ($fields as $key => $field){
			if (isset($this->request->get[$key])) {
				$sort[$key] = $this->request->get[$key];
				$url .= '&'.$key.'='.$sort[$key];
			} else {
				$sort[$key] = $field;
			}
		}
		
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
		);
		
		$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL'),
				'separator' => ' :: '
		);
		
		$data['add'] = $this->url->link('localisation/city/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('localisation/city/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$data['cities'] = array();
		
		$sort['start'] 	= ((int)$sort['page'] - 1) * $this->config->get('config_limit_admin');
		$sort['limit'] 	= $this->config->get('config_limit_admin');
		
		$city_total = $this->model_localisation_city->getTotalCities();
		$results = $this->model_localisation_city->getCities($sort);
		
		foreach ($results as $result) {
			$action[] = array(
					'text' => $this->language->get('text_edit'),
					
			);
		
			$data['cities'][] = array(
					'city_id' => $result['city_id'],
					'zone' 	=> $result['zone'],
					'country' 	=> $result['country'],
					'name'       => $result['name'],
					'initial_postcode' => $result['initial_postcode'],
					'final_postcode' => $result['final_postcode'],
					'edit'     =>  $this->url->link('localisation/city/update', 'token=' . $this->session->data['token'] . '&city_id=' . $result['city_id'] . $url, 'SSL')
			);
		}
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['column_name'] = $this->language->get('column_name');
		$data['column_zone'] = $this->language->get('column_zone');
		$data['column_country'] = $this->language->get('column_country');
		$data['column_initial_postcode'] = $this->language->get('column_initial_postcode');
		$data['column_final_postcode'] = $this->language->get('column_final_postcode');
		$data['column_action'] = $this->language->get('column_action');
		$data['text_list']	= $this->language->get('text_list');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_delete'] = $this->language->get('button_delete');
		
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
		$url .= '&order=' . $sort['order'];
		$url .= '&page=' . $sort['page'];
	
		$data['sort_name'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . '&sort=c.name' . $url, 'SSL');
		$data['sort_zone'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . '&sort=z.name' . $url, 'SSL');
		$data['sort_country'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . '&sort=ct.name' . $url, 'SSL');
		
		$url = '';
		$url .= '&sort=' . $sort['sort'];
		$url .= '&order=' . $sort['order'];
		
		$pagination = new Pagination();
		$pagination->total = $city_total;
		$pagination->page = $sort['page'];
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($city_total) ? (($sort['page'] - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($sort['page'] - 1) * $this->config->get('config_limit_admin')) > ($city_total - $this->config->get('config_limit_admin'))) ? $city_total : ((($sort['page'] - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $city_total, ceil($city_total / $this->config->get('config_limit_admin')));
		
		$data['sort'] = $sort['sort'];
		$data['order'] = $sort['order'];
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('localisation/city_list.tpl', $data));
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
			'href' => $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	
		if (!isset($this->request->get['city_id'])) {
			$data['action'] = $this->url->link('localisation/city/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('localisation/city/edit', 'token=' . $this->session->data['token'] . '&city_id=' . $this->request->get['city_id'] . $url, 'SSL');
		}
	
		$data['cancel'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		if (isset($this->request->get['city_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$city_info = $this->model_localisation_city->getcity($this->request->get['city_id']);
		}
	
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($city_info)) {
			$data['name'] = $city_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['country_id'])) {
			$data['country_id'] = $this->request->post['country_id'];
		} elseif (!empty($city_info)) {
			$data['country_id'] = $city_info['country_id'];
		} else {
			$data['country_id'] = '';
		}
		
		if (isset($this->request->post['zone_id'])) {
			$data['zone_id'] = $this->request->post['zone_id'];
		} elseif (!empty($city_info)) {
			$data['zone_id'] = $city_info['zone_id'];
		} else {
			$data['zone_id'] = $this->config->get('config_zone_id');
		}
	
		if (isset($this->request->post['initial_postcode'])) {
			$data['initial_postcode'] = $this->request->post['initial_postcode'];
		} elseif (!empty($city_info)) {
			$data['initial_postcode'] = $city_info['initial_postcode'];
		} else {
			$data['initial_postcode'] = '';
		}
	
		if (isset($this->request->post['final_postcode'])) {
			$data['final_postcode'] = $this->request->post['final_postcode'];
		} elseif (!empty($city_info)) {
			$data['final_postcode'] = $city_info['final_postcode'];
		} else {
			$data['final_postcode'] = '';
		}
	
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($city_info)) {
			$data['status'] = $city_info['status'];
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

		$this->response->setOutput($this->load->view('localisation/city_form.tpl', $data));
		
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
	
	public function autocompletecity(){
		$json = array();
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('localisation/city');
			$fields = array('filter_name'=>'', 'filter_zone'=>'', 'sort'=>'c.name','order'=>'ASC');
			foreach ($fields as $key => $field){
				if (isset($this->request->get[$key]) && $this->request->get[$key]) {
					$sort[$key] = $this->request->get[$key];
				} else {
					$sort[$key] = $field;
				}
			}
			$sort['limit'] = 10;
			$sort['start'] = 0;
			$results = $this->model_localisation_city->getCities($sort);
			foreach ($results as $result){
				$json[] = array('city_name'=>$result['name'],'city_id'=>$result['city_id']);
			}
		}
		$this->response->setOutput(json_encode($json));
	}
	
	
	public function install(){
		$this->load->model('localisation/city');
		$this->model_localisation_city->install();
	}
	private function validateForm(){
		if (!$this->user->hasPermission('modify', 'localisation/city')) {
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
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/city')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
	
		$this->load->model('setting/store');
		$this->load->model('sale/customer');
		$this->load->model('marketing/affiliate');
		$this->load->model('localisation/geo_zone');
	
		foreach ($this->request->post['selected'] as $zone_id) {
			/*
			if ($this->config->get('config_zone_id') == $zone_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}
			*/
			/*$store_total = $this->model_setting_store->getTotalStoresByZoneId($city_id);
	
			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}
			*/
			
			/*$address_total = $this->model_sale_customer->getTotalAddressesByZoneId($zone_id);
	
			if ($address_total) {
				$this->error['warning'] = sprintf($this->language->get('error_address'), $address_total);
			}
			*/
			/*
			$affiliate_total = $this->model_marketing_affiliate->getTotalAffiliatesByZoneId($zone_id);
	
			if ($affiliate_total) {
				$this->error['warning'] = sprintf($this->language->get('error_affiliate'), $affiliate_total);
			}
			*/
			/*$zone_to_geo_zone_total = $this->model_localisation_geo_zone->getTotalZoneToGeoZoneByZoneId($zone_id);
	
			if ($zone_to_geo_zone_total) {
				$this->error['warning'] = sprintf($this->language->get('error_zone_to_geo_zone'), $zone_to_geo_zone_total);
			}
			*/
		}	
	
		return !$this->error;
	}
}