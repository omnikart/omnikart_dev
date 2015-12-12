<?php  class ControllerCustomerpartnersupplierform2 extends Controller {

public function index() {
        $this->load->language('customerpartner/supplier_form2');
    	$this->document->setTitle($this->language->get('heading_title')); 
	    $this->getList();
  	}	
  	
private function getList() {
	
  	
    }
   }
?>