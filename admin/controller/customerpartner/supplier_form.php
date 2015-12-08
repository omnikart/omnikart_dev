<?php  class ControllerCustomerpartnersupplierform extends Controller {

public function index() {

		$this->load->language('customerpartner/supplier_form');
    	
		$this->document->setTitle($this->language->get('heading_title')); 
		
		 $this->getList();
  	}	
  	
private function getList() {
	  
	 
	$url='';
      
	$data['header'] = $this->load->controller('common/header');
	$data['footer'] = $this->load->controller('common/footer');
	$data['column_left'] = $this->load->controller('common/column_left');
   	$this->response->setOutput($this->load->view('customerpartner/supplier_form.tpl',$data));
  	
    }
    
     
  }
  
  ?>