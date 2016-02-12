<?php
class ControllerAccountCustomerpartnerEnquiry extends Controller {
	private $error = array ();
	private $data = array ();
	public function index() {
		if (! $this->customer->isLogged ()) {
			$this->session->data ['redirect'] = $this->url->link ( 'account/customerpartner/productlist', '', 'SSL' );
			$this->response->redirect ( $this->url->link ( 'account/login', '', 'SSL' ) );
		}
		
		$this->load->model ( 'account/customerpartner' );
		
		// $customerRights = $this->model_account_customerpartner->getCustomerGroupRights($this->customer->getGroupId());
		
		$customerRights = $this->customer->getRights ();
		
		$sellerId = $this->model_account_customerpartner->isSubUser ( $this->customer->getId () );
		
		if (! $customerRights ['isParent'] && ! $sellerId) {
			$this->data ['chkIsPartner'] = $this->model_account_customerpartner->chkIsPartner ();
			$sellerId = $this->model_account_customerpartner->getuserseller ();
		} else if ($sellerId) {
			$this->data ['chkIsPartner'] = true;
		}
		
		if (! $this->data ['chkIsPartner'])
			$this->response->redirect ( $this->url->link ( 'account/account' ) );
		
		$this->document->addStyle ( 'catalog/view/theme/default/stylesheet/MP/sell.css' );
		
		$this->language->load ( 'account/customerpartner/enquiry' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title_enquirylist' ) );
		
		$this->data ['breadcrumbs'] = array ();
		
		$this->data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home' ),
				'separator' => false 
		);
		
		$this->data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_account' ),
				'href' => $this->url->link ( 'account/account' ),
				'separator' => $this->language->get ( 'text_separator' ) 
		);
		
		$this->data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'heading_title_enquirylist' ),
				'href' => $this->url->link ( 'account/customerpartner/enquiry', '', 'SSL' ),
				'separator' => $this->language->get ( 'text_separator' ) 
		);
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$filter_name = $this->request->get ['filter_name'];
		} else {
			$filter_name = null;
		}
		
		if (isset ( $this->request->get ['filter_date'] )) {
			$filter_date = $this->request->get ['filter_date'];
		} else {
			$filter_date = null;
		}
		
		if (isset ( $this->request->get ['filter_status'] )) {
			$filter_status = $this->request->get ['filter_status'];
		} else {
			$filter_status = null;
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		$url = '';
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$url .= '&filter_name=' . urlencode ( html_entity_decode ( $this->request->get ['filter_name'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['filter_model'] )) {
			$url .= '&filter_date=' . urlencode ( html_entity_decode ( $this->request->get ['filter_date'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['filter_status'] )) {
			$url .= '&filter_status=' . $this->request->get ['filter_status'];
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
		}
		
		if ($this->config->get ( 'wkmpuseseo' ))
			$url = '';
		
		$data = array (
				'filter_name' => $filter_name,
				'filter_date' => $filter_date,
				'filter_status' => $filter_status,
				'start' => ($page - 1) * $this->config->get ( 'config_product_limit' ),
				'limit' => $this->config->get ( 'config_product_limit' ),
				'seller_id' => $sellerId 
		);
		
		$enquiries = $this->model_account_customerpartner->getEnquiries ( $data );
		$this->data ['enquiries'] = $enquiries;
		
		$enquiries_total = $this->model_account_customerpartner->getEnquiriesTotal ( $data );
		
		if ($sellerId) {
			$this->data ['customer_id'] = $sellerId;
		}
		
		$this->data ['isMember'] = true;
		$this->data ['isSentEnquiry'] = false;
		$this->data ['heading_title'] = $this->language->get ( 'heading_title_enquirylist' );
		$this->data ['text_enabled'] = $this->language->get ( 'text_enabled' );
		$this->data ['text_disabled'] = $this->language->get ( 'text_disabled' );
		$this->data ['text_no_results'] = $this->language->get ( 'text_no_results' );
		$this->data ['text_image_manager'] = $this->language->get ( 'text_image_manager' );
		$this->data ['text_confirm'] = $this->language->get ( 'text_confirm' );
		$this->data ['text_soldlist_info'] = $this->language->get ( 'text_soldlist_info' );
		$this->data ['column_image'] = $this->language->get ( 'column_image' );
		$this->data ['column_name'] = "Customer Name";
		$this->data ['column_enquiry'] = "Enquiry Brief";
		$this->data ['column_date'] = "Date Added";
		$this->data ['column_status'] = $this->language->get ( 'column_status' );
		$this->data ['column_action'] = $this->language->get ( 'column_action' );
		$this->data ['column_sold'] = "Sold";
		$this->data ['button_copy'] = $this->language->get ( 'button_copy' );
		$this->data ['button_insert'] = $this->language->get ( 'button_insert' );
		$this->data ['button_delete'] = $this->language->get ( 'button_delete' );
		$this->data ['button_filter'] = $this->language->get ( 'button_filter' );
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['warning'] )) {
			$this->data ['error_warning'] = $this->session->data ['warning'];
			unset ( $this->session->data ['warning'] );
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		$url = '';
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$url .= '&filter_name=' . urlencode ( html_entity_decode ( $this->request->get ['filter_name'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['filter_date'] )) {
			$url .= '&filter_date=' . urlencode ( html_entity_decode ( $this->request->get ['filter_date'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['filter_status'] )) {
			$url .= '&filter_status=' . $this->request->get ['filter_status'];
		}
		
		$pagination = new Pagination ();
		$pagination->total = $enquiries_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get ( 'config_product_limit' );
		$pagination->text = $this->language->get ( 'text_pagination' );
		$pagination->url = $this->url->link ( 'account/customerpartner/enquiry', '' . $url . '&page={page}', 'SSL' );
		
		$this->data ['pagination'] = $pagination->render ();
		
		$limit = $this->config->get ( 'config_product_limit' );
		$this->data ['results'] = sprintf ( $this->language->get ( 'text_pagination' ), ($enquiries_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($enquiries_total - $limit)) ? $enquiries_total : ((($page - 1) * $limit) + $limit), $enquiries_total, ceil ( $enquiries_total / $limit ) );
		
		$this->data ['filter_name'] = $filter_name;
		$this->data ['filter_date'] = $filter_date;
		$this->data ['filter_status'] = $filter_status;
		
		$this->data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$this->data ['column_right'] = $this->load->controller ( 'common/column_right' );
		$this->data ['content_top'] = $this->load->controller ( 'common/content_top' );
		$this->data ['content_bottom'] = $this->load->controller ( 'common/content_bottom' );
		$this->data ['footer'] = $this->load->controller ( 'common/footer' );
		$this->data ['header'] = $this->load->controller ( 'common/header' );
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/account/customerpartner/enquiry.tpl' )) {
			$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/account/customerpartner/enquiry.tpl', $this->data ) );
		} else {
			$this->response->setOutput ( $this->load->view ( 'default/template/account/customerpartner/enquiry.tpl', $this->data ) );
		}
	}
	public function getEnquiry() {
		if (isset ( $this->request->get ['enquiry_id'] ) && ( int ) $this->request->get ['enquiry_id']) {
			if (isset ( $this->request->get ['quote_revision_id'] ))
				$quote_revision_id = $this->request->get ['quote_revision_id'];
			else
				$quote_revision_id = 0;
			
			if (isset ( $this->request->get ['quote_id'] ))
				$quote_id = $this->request->get ['quote_id'];
			else
				$quote_id = 0;
			$this->load->model('module/enquiry');
			$this->load->model('account/customerpartner');
			$seller_id = $this->model_account_customerpartner->getuserseller();
			$data = $this->model_module_enquiry->getEnquiry($this->request->get['enquiry_id'],$seller_id ,$quote_id,$quote_revision_id);
			
			$this->load->model('localisation/tax_class');
			$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
			$data['text_none'] = "None";
			if (isset($this->request->post['tax_class_id'])) {
				$data['tax_class_id'] = $this->request->post['tax_class_id'];
			} elseif (!empty($product_info)) {
				$data['tax_class_id'] = $product_info['tax_class_id'];
			}
			
			if (isset ( $this->request->post ['weight'] )) {
				$data ['weight'] = $this->request->post ['weight'];
			} elseif (! empty ( $product_info )) {
				$data ['weight'] = $product_info ['weight'];
			} else {
				$data ['weight'] = '';
			}
			
			$this->load->model ( 'localisation/weight_class' );
			
			$data ['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses ();
			
			if (isset ( $this->request->post ['weight_class_id'] )) {
				$data ['weight_class_id'] = $this->request->post ['weight_class_id'];
			} elseif (! empty ( $product_info )) {
				$data ['weight_class_id'] = $product_info ['weight_class_id'];
			} else {
				$data ['weight_class_id'] = $this->config->get ( 'config_weight_class_id' );
			}
			
			if (isset ( $this->request->post ['length'] )) {
				$data ['length'] = $this->request->post ['length'];
			} elseif (! empty ( $product_info )) {
				$data ['length'] = $product_info ['length'];
			} else {
				$data ['length'] = '';
			}
			
			if (isset ( $this->request->post ['width'] )) {
				$data ['width'] = $this->request->post ['width'];
			} elseif (! empty ( $product_info )) {
				$data ['width'] = $product_info ['width'];
			} else {
				$data ['width'] = '';
			}
			
			if (isset ( $this->request->post ['height'] )) {
				$data ['height'] = $this->request->post ['height'];
			} elseif (! empty ( $product_info )) {
				$data ['height'] = $product_info ['height'];
			} else {
				$data ['height'] = '';
			}
			
			$this->load->model ( 'localisation/length_class' );
			
			$data ['length_classes'] = $this->model_localisation_length_class->getLengthClasses ();
			
			if (isset ( $this->request->post ['length_class_id'] )) {
				$data ['length_class_id'] = $this->request->post ['length_class_id'];
			} elseif (! empty ( $product_info )) {
				$data ['length_class_id'] = $product_info ['length_class_id'];
			} else {
				$data ['length_class_id'] = $this->config->get ( 'config_length_class_id' );
			}
			$this->load->model('localisation/unit_class');
			$this->load->model('localisation/payment_term');
			$data['unit_classes'] = $this->model_localisation_unit_class->getUnitClasses();
			$data['payment_term']=array();
			$data['payment_term'] = $this->model_localisation_payment_term->getPaymentTerms();
			$data['config_name']=$this->config->get('config_name');
			$data['config_owner']=$this->config->get('config_owner');
			$data['config_address']=$this->config->get('config_address');
			$data['config_email']=$this->config->get('config_email');
			$data['config_telephone']=$this->config->get('config_telephone');
			
			$this->load->model('account/address');
			$data['addresss'] = $this->model_account_address->getAddresses();
			
			$data['enquiryupdates'] = $this->url->link('account/customerpartner/enquiry/quotation', '&enquiry_id='.(int)$this->request->get['enquiry_id'], 'SSL');

			
			$this->response->setOutput ( $this->load->view ( 'default/template/account/customerpartner/enquiry_form.tpl', $data ) );
		}
	}
	public function quotation() {
		if (isset ( $this->request->get ['enquiry_id'] ) && ( int ) $this->request->get ['enquiry_id']) {
			$this->load->model ( 'module/enquiry' );
			$this->load->model ( 'account/customerpartner' );
			$seller_id = $this->model_account_customerpartner->getuserseller ();
			$data = $this->model_module_enquiry->getEnquiry ( $this->request->get ['enquiry_id'], $seller_id );
			$this->load->model ( 'localisation/tax_class' );
			
			$data ['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses ();
			$data ['text_none'] = "None";
			if (isset ( $this->request->post ['tax_class_id'] )) {
				$data ['tax_class_id'] = $this->request->post ['tax_class_id'];
			} elseif (! empty ( $product_info )) {
				$data ['tax_class_id'] = $product_info ['tax_class_id'];
			} else {
				$data ['tax_class_id'] = 0;
			}
			$data ['payment_term'] = array ();
			$this->load->model ( 'localisation/payment_term' );
			$data ['payment_term'] = $this->model_localisation_payment_term->getPaymentTerms ();
			
			$this->load->model('account/address');
			$data['addresss'] = $this->model_account_address->getAddresses();
			
			$this->response->setOutput ( $this->load->view ( 'default/template/account/customerpartner/quotation.tpl', $data ) );
		}
	}
	public function updateQuote() {
		$data = array ();
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && isset ( $this->request->post ['enquiry'] )) {
			$data = $this->request->post ['enquiry'];
			$this->load->model ( 'module/enquiry' );
			foreach ( $data ['product'] as $key => $product ) {
				$data ['product'] [$key] ['total'] = $this->tax->calculate ( $product ['unit_price'], $product ['tax_class_id'], true ) * $product ['quantity'];
			}
			$this->model_module_enquiry->updateQuote ( $data );
		}
	}
	private function validate() {
		$this->load->language ( 'account/customerpartner/addproduct' );
		
		if (! $this->customer->getId ()) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function getQuotationSuppliers() {
		if (isset ( $this->request->get ['enquiry_id'] ))
			$enquiry_id = $this->request->get ['enquiry_id'];
		else
			$enquiry_id = 0;
		
		$this->load->language( 'account/customerpartner/quotationcomments' );
		
		$this->load->model ( 'module/enquiry' );
		$this->data = $this->model_module_enquiry->getQuotationBySuppliers ( $enquiry_id );
		

		
		$this->data ['breadcrumbs'] = array ();
		
		$this->data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home' ),
				'separator' => false 
		);
		
		$this->data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_account' ),
				'href' => $this->url->link ( 'account/account' ),
				'separator' => $this->language->get ( 'text_separator' ) 
		);
		
		$this->data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_enquiry' ),
				'href' => $this->url->link ( 'account/customerpartner/sentenquiry', '', 'SSL' ),
				'separator' => $this->language->get ( 'text_separator' ) 
		);
		
		$this->data ['breadcrumbs'] [] = array (
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link ( 'account/customerpartner/enquiry/getQuotationSuppliers', 'enquiry_id='.$enquiry_id, 'SSL' ),
				'separator' => $this->language->get ( 'text_separator' ) 
		);
		
		$this->data ['isMember'] = true;
		$this->data ['isSentEnquiry'] = true;
		$this->data ['heading_title'] = $this->language->get ( 'heading_title_enquirylist' );
		$this->data ['column_name'] = "Customer Name";
		$this->data ['column_date'] = "Date Added";
		$this->data ['column_status'] = $this->language->get ( 'column_status' );
		$this->data ['button_filter'] = $this->language->get ( 'button_filter' );
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['warning'] )) {
			$this->data ['error_warning'] = $this->session->data ['warning'];
			unset ( $this->session->data ['warning'] );
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		$url = '';
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$url .= '&filter_name=' . urlencode ( html_entity_decode ( $this->request->get ['filter_name'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['filter_date'] )) {
			$url .= '&filter_date=' . urlencode ( html_entity_decode ( $this->request->get ['filter_date'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['filter_status'] )) {
			$url .= '&filter_status=' . $this->request->get ['filter_status'];
		}
		
		$pagination = new Pagination ();
		$pagination->limit = $this->config->get ( 'config_product_limit' );
		$pagination->text = $this->language->get ( 'text_pagination' );
		$pagination->url = $this->url->link ( 'account/customerpartner/productlist', '' . $url . '&page={page}', 'SSL' );
		
		$this->data ['pagination'] = $pagination->render ();
		
		$limit = $this->config->get ( 'config_product_limit' );
		
		$this->data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$this->data ['column_right'] = $this->load->controller ( 'common/column_right' );
		$this->data ['content_top'] = $this->load->controller ( 'common/content_top' );
		$this->data ['content_bottom'] = $this->load->controller ( 'common/content_bottom' );
		$this->data ['footer'] = $this->load->controller ( 'common/footer' );
		$this->data ['header'] = $this->load->controller ( 'common/header' );
		
		$this->response->setOutput ( $this->load->view ( 'default/template/account/customerpartner/viewquotationcomments.tpl', $this->data ) );
	}
	public function getSentEnquiryComment() {
		if (isset ( $this->request->get ['quote_id'] ))
			$quote_id = $this->request->get ['quote_id'];
		else
			$quote_id = 0;
		$json = array ();
		$this->load->model ( 'module/enquiry' );
		$json = $this->model_module_enquiry->getSentEnquiryComments ( $quote_id );
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function addSentEnquiryComment() {
		$this->load->model ( 'account/customerpartner' );
		$customer_id = $this->model_account_customerpartner->getuserseller ();
		$json = array ();
		if ($this->request->server ['REQUEST_METHOD'] == 'POST') {
			if (isset ( $this->request->post )) {
				$this->load->model ( 'module/enquiry' );
				$data = $this->request->post;
				$json = $this->model_module_enquiry->addSentEnquiryComments ( $data ['quote_id'], $customer_id, $data ['quote'] [$data ['quote_id']] );
				$this->response->setOutput ( json_encode ( $json ) );
			}
		}
	}
}
?>
