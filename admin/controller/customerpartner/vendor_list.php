<?php  class ControllerCustomerpartnervendorlist extends Controller {

public function index() {

		$this->load->language('customerpartner/vendor_list');
    	
		$this->document->setTitle($this->language->get('heading_title')); 
		
		$this->load->model('customerpartner/master');
		
		$this->getList();
  	}	
  	
private function getList() {
	 $results = $this->model_customerpartner_master->getSupplierQueries($data = array());
  	 $data['enquiries'] = array();
	foreach ($results as $result){
		 $data['enquiries'][] = array(
			'user_info' => unserialize($result['user_info']),
			'categories' => explode(',',$result['categories']),
			'manufacturers' => explode(',',$result['brands']),
			'trade' => $result['trade'],
			'id'=>$result['id']
	 );
	}

	$url='';

	$data['vendor'] = $this->language->get('vendor');
	$data['header'] = $this->load->controller('common/header');
	$data['footer'] = $this->load->controller('common/footer');
	$data['column_left'] = $this->load->controller('common/column_left');
	
	$data['delete'] = $this->url->link('customerpartner/vendor_list/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
	//$data['button'] = $this->url->link('customerpartner/supplier_form', 'token=' . $this->session->data['token'] . $url, 'SSL');
	//$data['button1'] = $this->url->link('customerpartner/supplier_form2', 'token=' . $this->session->data['token'] . $url, 'SSL');
	$this->response->setOutput($this->load->view('customerpartner/vendor_list.tpl',$data));
  	
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
  }
  
  ?>