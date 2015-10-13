<?php 

class ControllerAccountCustomerpartnerOrderreview extends Controller
{
	public function index() {
		$this->load->language('customerpartner/orderReview');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/customerpartner');

		//$customerRights = $this->model_account_customerpartner->getCustomerGroupRights($this->customer->getGroupId());
		$customerRights = $this->customer->getRights();
		if(!$this->customer->isLogged() ||  !$customerRights ) {
			$this->response->redirect($this->url->link('account/account', '','SSL'));
		}

		// Language
		$data['heading_title'] = $this->language->get('heading_title');
		$data['forReview'] = false;
		if($this->session->data['forReview']) {
			$data['forReview'] = true;
		}
		
		// Breadcrumbs
		$data['breadcrumbs'] = array();

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),     	
        	'separator' => false
      	); 

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account'),     	
        	'separator' => $this->language->get('text_separator')
      	); 

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_orderreview'),
			'href'      => $this->url->link('account/customerpartner/orderlist', '', 'SSL'),       	
        	'separator' => $this->language->get('text_separator')
      	);

      	if(isset($this->session->data['warning'])) {
      		$data['warning'] = $this->session->data['warning'];
      		unset($this->session->data['warning']);
      	} else {
      		$data['warning'] = '';
      	}

      	if(isset($this->session->data['success'])) {
      		$data['success'] = $this->session->data['success'];
      		unset($this->session->data['success']);
      	} else {
      		$data['success'] = '';
      	}

      	$data['header'] = $this->load->Controller('common/header');
      	$data['footer'] = $this->load->Controller('common/footer');
      	$data['column_left'] = $this->load->Controller('common/column_left');
      	$data['column_right'] = $this->load->Controller('common/column_right');
      	$data['content_top'] = $this->load->Controller('common/content_top');
      	$data['content_bottom'] = $this->load->Controller('common/content_bottom');

      	$this->response->setOutput($this->load->view('default/template/customerpartner/orderReview.tpl', $data));
	}


}
?>
