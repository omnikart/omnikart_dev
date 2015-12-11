<?php  class ControllerCustomerpartnervendorlist extends Controller {

 public function index() {
        $this->load->language('customerpartner/vendor_list');
    	$this->document->setTitle("Vendor List"); 
		$this->load->model('customerpartner/master');
		$this->getList();
  	}	
  	
 private function getList() {
	
	if (isset($this->request->get['filter_name'])) {
		$filter_name = $this->request->get['filter_name'];
	} else {
		$filter_name = null;
	}
	
	if (isset($this->request->get['filter_mail'])) {
		$filter_mail = $this->request->get['filter_mail'];
	} else {
		$filter_mail = null;
	}
	
	if (isset($this->request->get['filter_category'])) {
		$filter_category = $this->request->get['filter_category'];
	} else {
		$filter_category = null;
	}
	
	if (isset($this->request->get['filter_brand'])) {
		$filter_brand = $this->request->get['filter_brand'];
	} else {
		$filter_brand = null;
	}
	if (isset($this->request->get['filter_trade'])) {
		$filter_trade = $this->request->get['filter_trade'];
	} else {
		$filter_trade = null;
	}
	
	$url = '';
	
	if (isset($this->request->get['filter_name'])) {
		$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
	}
	
	if (isset($this->request->get['filter_mail'])) {
		$url .= '&filter_mail=' . urlencode(html_entity_decode($this->request->get['filter_mail'], ENT_QUOTES, 'UTF-8'));
	}
	if (isset($this->request->get['filter_category'])) {
		$url .= '&filter_category=' . urlencode(html_entity_decode($this->request->get['filter_category'], ENT_QUOTES, 'UTF-8'));
	}
	if (isset($this->request->get['filter_brand'])) {
		$url .= '&filter_brand=' . urlencode(html_entity_decode($this->request->get['filter_brand'], ENT_QUOTES, 'UTF-8'));
	}
	if (isset($this->request->get['filter_trade'])) {
		$url .= '&filter_trade=' . urlencode(html_entity_decode($this->request->get['filter_trade'], ENT_QUOTES, 'UTF-8'));
	}
	
	$filter_data = array(
				'filter_name'	  => $filter_name,
				'filter_mail'	  => $filter_mail,
				'filter_category'	  => $filter_category,
				'filter_brand' =>  $filter_brand,
				'filter_trade'   =>  $filter_trade,
	 );
	
		$data['sort_name'] = $this->url->link('customerpartner/vendor_list', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$data['sort_mail'] = $this->url->link('customerpartner/vendor_list', 'token=' . $this->session->data['token'] . '&sort=pd.mail' . $url, 'SSL');
		$data['sort_category'] = $this->url->link('customerpartner/vendor_list', 'token=' . $this->session->data['token'] . '&sort=pd.category' . $url, 'SSL');
		$data['sort_brand'] = $this->url->link('customerpartner/vendor_list', 'token=' . $this->session->data['token'] . '&sort=pd.brand' . $url, 'SSL');
		$data['sort_trade'] = $this->url->link('customerpartner/vendor_list', 'token=' . $this->session->data['token'] . '&sort=pd.trade' . $url, 'SSL');
		$data['token'] = $this->session->data['token'];
		
		$results = $this->model_customerpartner_master->getSupplierQueries($data = array());
		$this->load->model('catalog/category');
		$this->load->model('catalog/manufacturer');
		$data['enquiries'] = array();

	   foreach ($results as $result){
	  	$categories = array();
	  	foreach (explode(',',$result['categories']) as $category_id){
	  		$cat=$this->model_catalog_category->getCategory($category_id);
	  		$categories[] = $cat['name'];
	  	}
	  	
	  	$manufacturers = array();
	  	foreach (explode(',',$result['brands']) as $manufacturer_id){
	  		$man=$this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
	  		$manufacturers[] = $man['name'];
	  	}
	  	
	  	 	$van= array(
	  		1 => 'OEM/Manufacturer', 
	  		2 => 'Authorized Dealer/Distributer',
	  		3 =>'Dealer/Stockist',
	  		4 =>'Importer',
	  		5 =>'Reseller'
	  		);
	  		
	  	 	 $data['enquiries'][] = array(
	 			'user_info' => unserialize($result['user_info']),
	 			'manufacturers' => $manufacturers,
	 		 	'categories' =>  $categories,
	 			'trade' =>$van[$result['trade']],
	  	 	  	 'id'=>$result['id']
	 	);
	 }
	$url='';
	$data['vendor'] = $this->language->get('vendor');
	$data['header'] = $this->load->controller('common/header');
	$data['footer'] = $this->load->controller('common/footer');
	$data['column_left'] = $this->load->controller('common/column_left');
	$data['entry_name'] = $this->language->get('entry_name');
	$data['entry_mail'] = $this->language->get('entry_mail');
	$data['entry_category'] = $this->language->get('entry_category');
	$data['entry_brand'] = $this->language->get('entry_brand');
	$data['entry_trade'] = $this->language->get('entry_trade');
	$data['button_filter'] = $this->language->get('button_filter');
	$data['add'] = $this->language->get('add');
	
	
	$data['filter_name'] =  $filter_name;
	$data['filter_mail'] =  $filter_mail;
	$data['filter_category'] =  $filter_category;
	$data['filter_brand'] =  $filter_brand;
	$data['filter_trade'] =  $filter_trade;
	
	$data['delete'] = $this->url->link('customerpartner/vendor_list/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
	$data['button'] = $this->url->link('customerpartner/supplier_form', 'token=' . $this->session->data['token'] . $url, 'SSL');
	$data['button1'] = $this->url->link('customerpartner/supplier_form2', 'token=' . $this->session->data['token'] . $url, 'SSL');
	$data['filterLink'] = $this->url->link('customerpartner/vendor_list&token=' . $this->session->data['token'] . $url,'', 'SSL');
	$this->response->setOutput($this->load->view('customerpartner/vendor_list.tpl',$data));
	$data['token'] =  $this->session->data['token'];
  	
    }
    
  public function delete() {
    	$this->load->language('customerpartner/vendor_list');
     	$this->load->model('customerpartner/master');
    
    	if (isset($this->request->post['selected']))   {
    		foreach ($this->request->post['selected'] as $id) {
    			$this->model_customerpartner_master->deleteSupplier($id);
    		}
    
    		$this->session->data['success'] = $this->language->get('success');
    
    		$url = '';
     
           $this->response->redirect($this->url->link('customerpartner/vendor_list', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}
     	$this->getList();
    }
    
  public function getForm() {
    	
    	$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_mail'])) {
			$url .= '&filter_mail=' . urlencode(html_entity_decode($this->request->get['filter_mail'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . urlencode(html_entity_decode($this->request->get['filter_category'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_brand'])) {
			$url .= '&filter_brand=' . urlencode(html_entity_decode($this->request->get['filter_brand'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_trade'])) {
			$url .= '&filter_trade=' . urlencode(html_entity_decode($this->request->get['filter_trade'], ENT_QUOTES, 'UTF-8'));
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
		$data['token'] = $this->session->data['token'];
    }
     	
  public function autocomplete() {
			$json = array();
			
 		if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_mail'])) {
				$filter_mail = $this->request->get['filter_mail'];
			} else {
				$filter_mail = '';
			}
			if (isset($this->request->get['filter_category'])) {
				$filter_category = $this->request->get['filter_category'];
			} else {
				$filter_category = '';
			}
			
			if (isset($this->request->get['filter_brand'])) {
				$filter_brand = $this->request->get['filter_brand'];
			} else {
				$filter_brand = '';
			}
			
			if (isset($this->request->get['filter_trade'])) {
				$filter_trade = $this->request->get['filter_trade'];
			} else {
				$filter_trade = '';
			}
			
 			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}
 	 	}		
    }
   ?>