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

		// $data['subUser'] = true;
		// if(array_key_exists('create-user', $customerRights['rights'])) {
		// 	$data['subUser'] = false;
		// }

		$data['isParent'] = false;
		$data['isSubUser'] = false;
		if($customerRights && !$customerRights['isParent']) {
			$data['isParent'] = true;
		} else if($customerRights && $customerRights['isParent']) {
			$data['isSubUser'] = true;
		}
		$data['productsToReview'] = array();
		$data['reviewRequests'] = array();
		// Language
		$data['heading_title'] = $this->language->get('heading_title');
		$data['entry_product_name'] = $this->language->get('entry_product_name');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_subuser_name'] = $this->language->get('entry_subuser_name');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_action'] = $this->language->get('entry_action');
		$data['entry_toottip_getproductlist'] = $this->language->get('entry_toottip_getproductlist');
		$data['entry_norequestfound'] = $this->language->get('entry_norequestfound');
		$data['entry_nocart'] = $this->language->get('entry_nocart');
		$data['button_abort'] = $this->language->get('button_abort');
		$data['button_sendtoreview'] = $this->language->get('button_sendtoreview');
		$data['button_approve'] = $this->language->get('button_approve');
		$data['button_close'] = $this->language->get('button_close');
		$data['warning_emptycart'] = $this->language->get('warning_emptycart');
		$data['warning_notapproved'] = $this->language->get('warning_notapproved');
		$data['warning_rusure'] = $this->language->get('warning_rusure');

		$data['forReview'] = false;
		$data['abortEnable'] = false;
		$data['reviewEnable'] = false;
		$data['product_warning'] = '';
		if(isset($this->session->data['forReview']) && $this->session->data['forReview']) {
			$data['forReview'] = true;
		    $cart = $this->cart->getProducts();
		    $data['admin_id'] = $this->model_account_customerpartner->isSubUser($this->customer->getId());
		    $approveProduct = $this->model_account_customerpartner->getApprovedProducts($this->customer->getId());
		    $productNames = '';
		    foreach ($cart as $key => $product) {
		      	if($approveProduct && $approveProduct['customer_cart'] && array_key_exists($key, unserialize($approveProduct['customer_cart']))) {
			      	if(isset($approveProduct['approve_cart'])) {
			      		if(!$approveProduct['approve_cart']) {
			      			$approve_cart = array();
			      		} else {
			      			$approve_cart = unserialize($approveProduct['approve_cart']);
			      		}
			      		if(!array_key_exists($key,$approve_cart)) {
			      			$productNames .= $product['name'].", ";
			      		}
			      	}
		      		continue;
		      	}
		        $data['productsToReview'][] = array(
		          'key' => $key,
		          'product_id' => $product['product_id'],
		          'name' => $product['name'],
		          'model' => $product['model'],
		          'quantity' => $product['quantity'],
		        );
		        $data['abortEnable'] = true;
		        $data['reviewEnable'] = true;
		    }
		    if($productNames) {
		    	$data['product_warning'] = $this->language->get('warning_notapproved').rtrim($productNames,", ")." !";
		    }
		} else {
		    $reviewRequests = $this->model_account_customerpartner->getReviewRequest($this->customer->getId());
		    if($reviewRequests) {
				foreach ($reviewRequests as $key => $requestor) {
			    	$data['reviewRequests'][] = array(
			        	'customer_id' => $requestor['customer_id'],
			            'firstname' => $requestor['firstname'],
			            'lastname' => $requestor['lastname'],
			            'email' => $requestor['email'],
			        );
			    }
		    }
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

	    $data['review'] = $this->url->link('account/customerpartner/orderReview/sendForReview', '', 'SSL');
	    $data['approveAction'] = $this->url->link('account/customerpartner/orderReview/approveProduct', '', 'SSL');
      	
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

  public function sendForReview() {
    $this->load->language('customerpartner/orderReview');
    $this->load->model('account/customerpartner');
    if($this->request->server['REQUEST_METHOD'] == 'POST') {
    	if(isset($this->request->post['selected'])) {
		    $this->request->post['customer_id'] = $this->customer->getId();
		    $this->model_account_customerpartner->sendForReview($this->request->post);
		    unset($this->session->data['forReview']);
		    $this->response->redirect($this->url->link('account/customerpartner/orderReview','','SSL'));
		} else {
			$this->session->data['warning'] = $this->language->get('warning_sendforreview');
			$this->response->redirect($this->url->link('account/customerpartner/orderReview','','SSL'));
		}
    }
  }

  public function abortReview() {
    $this->load->language('customerpartner/orderReview');
    $this->load->model('account/customerpartner');
    if($this->request->server['REQUEST_METHOD'] == 'POST') {
      $this->cart->clear();
      $this->session->data['success'] = " Success: Action is aborted and now your cart is empty.";
    }
  }

  public function getProductsToReview() {
    $this->load->language('customerpartner/orderReview');
    $this->load->model('account/customerpartner');
    if($this->request->server['REQUEST_METHOD'] == 'POST') {
      $customer_id = $this->request->post['customer_id'];
      $result = $this->model_account_customerpartner->getProductsToApprove($customer_id);
      if($result) {
        $json['success'] = 'done';
        $json['data'] = $result;
      } else {
      	$json['warning'] = 'error';
      }
      $this->response->addHeader('Content-Type: application/json');
      $this->response->setOutput(json_encode($json));
    } 
  }

  public function approveProduct() {
    $this->load->language('customerpartner/orderReview');
    $this->load->model('account/customerpartner');
    if($this->request->server['REQUEST_METHOD'] == 'POST') {
    	if(isset($this->request->post['select'])) {
	      	$this->model_account_customerpartner->approveProductToBuy($this->request->post);
	      	$this->response->redirect($this->url->link('account/customerpartner/orderReview','','SSL'));
	    } else {
	    	$this->session->data['warning'] = $this->language->get('warning_reviewed');
	    	$this->response->redirect($this->url->link('account/customerpartner/orderReview','','SSL'));
	    }
    }
  }

}
?>
