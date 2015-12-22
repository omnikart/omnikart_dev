<?php  class ControllerCustomerpartnersupplierform2 extends Controller {

public function index() {
        $this->load->language('customerpartner/supplier_form2');
    	$this->document->setTitle($this->language->get('heading_title')); 
	    $this->getList();
  	}	
  	
private function getList() {
	$data['address_1'] = $this->language->get('address_1');
	$data['address_2'] = $this->language->get('address_2');
	$data['postcode'] = $this->language->get('postcode');
 
  	
    }
   }
?>