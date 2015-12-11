<?php  class ControllerCustomerpartnersupplierform2 extends Controller {

public function index() {
        $this->load->language('customerpartner/supplier_form2');
    	$this->document->setTitle($this->language->get('heading_title')); 
	    $this->getList();
  	}	
  	
private function getList() {
	$url='';
	$data['button_upload'] =$this->language->get('button_upload');
	$data['header'] = $this->load->controller('common/header');
	$data['footer'] = $this->load->controller('common/footer');
	$data['column_left'] = $this->load->controller('common/column_left');
	$data['button'] = $this->url->link('customerpartner/supplier_form2', 'token=' . $this->session->data['token'] . $url, 'SSL');
	$this->response->setOutput($this->load->view('customerpartner/supplier_form2.tpl',$data));
  	
    }
   }
?>