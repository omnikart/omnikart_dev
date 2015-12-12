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
	$data['supplier_form2'] = $this->url->link('customerpartner/vendor_list/supplier_form2&token=' . $this->session->data['token'] . $url,'', 'SSL');
	$data['filterLink'] = $this->url->link('customerpartner/vendor_list&token=' . $this->session->data['token'] . $url,'', 'SSL');
	$data['token'] =  $this->session->data['token'];
	
	
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
	
  public function install(){
  	$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX ."supplier_fields` (
	  	`history_id` int(11) NOT NULL,
  		`field_code` int(11) NOT NULL,
		`field_type` varchar(64) NOT NULL,
  		`value_text` varchar(64) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=latin1");
  	
  	$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX ."supplier_history` (
	  	`history_id` int(11) NOT NULL,
  		`comment` text NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=latin1");
  	 
  	$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX ."supplier_requests` (
  	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `user_info` text NOT NULL,
	  `categories` text NOT NULL,
	  `brands` text NOT NULL,
	  `trade` text NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");	
  	
  	$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX ."supplier_schedule` (
	  `history_id` int(11) NOT NULL AUTO_INCREMENT,
	  `id` int(11) NOT NULL,
	  `date_scheduled` datetime NOT NULL,
	  `status` tinyint(11) NOT NULL,
	  `date_added` date NOT NULL,
	  PRIMARY KEY (`history_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
  }
 	 	
public function supplier_form2(){
		$url='';
		$this->load->model('customerpartner/master');
		
		if (isset($this->request->get['enquiryId'])) $enquiryId = $this->request->get['enquiryId'];
		else $enquiryId = 0;
		
		$fields = array(
		 'company_name',
		 'category',
		 'area',
		 'name',
		 'number',
		 'email',
		 'add',
		 'city'
		 );
		foreach ($fields as $field){
			$data[$field] = ''; // Initialize all values in supplier form 2
		}
		
		if ($enquiryId){
			$enquiry = $this->model_customerpartner_master->getSupplierQuery($enquiryId);
			if (isset($enquiry['user_info'])){
				$user_info = unserialize($enquiry['user_info']);
				$this->load->model('sale/customer');
				$customer = $this->model_sale_customer->getCustomerByEmail($user_info['email']);
				
				if ($customer){
					
					$data['name']       =$customer['firstname'].$customer['lastname'];
					$data['number']     = $customer['telephone'];
					$data['email']      = $customer['email'];
					 
			 	} else {
					$name = explode(' ',$user_info['name']);
					
					$data1 = array(
						'customer_group_id' => $this->config->get('config_customer_group_id'),
						'firstname' => $name[0], 
						'lastname' => (isset($name[1])?$name[1]:''),
						'email' => $user_info['email'],
						'telephone' => $user_info['number'],
						'fax' => $user_info['number_2'],
						'newsletter' => '1',
						'password' => '12345678',
						'status' => '1',
						'approved' => '1',
						'safe' => '1'
					);
					$customer_id = $this->model_sale_customer->addCustomer($data1);
				}
			 }
		} else {
		}
		$data['registration'] = $this->url->link('customerpartner/vendor_list/supplier_form2_save&token=' . $this->session->data['token'] . $url,'', 'SSL');
		$data['button_upload'] = "Upload"; //
		$data['button'] = $this->url->link('customerpartner/vendor_list/supplier_form2', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$this->response->setOutput($this->load->view('customerpartner/supplier_form2.tpl',$data));
		
	}
	
	public function supplier_form2_save(){
		
		if (isset($this->request->post['email'])) {
			$this->load->model('sale/customer');
			$customer = $this->model_sale_customer->getCustomerByEmail($this->request->post['email']);
			if (!$customer) {
				$fields = array(
						 'company_name',
						 'category',
						 'area',
						 'name',
						 'number',
						 'email',
						 'add',
						 'city'
				);
				foreach ($fields as $field){
					if (isset($this->request->post[$field])) {
						$data[$field] = $this->request->post[$field];
					} else {
						$data[$field] = '';
					}
				}
				$name = explode(' ',$data['name']);
				$data1 = array(
					'customer_group_id' => $this->config->get('config_customer_group_id'),
					'firstname' => $name[0], 
					'lastname' => (isset($name[1])?$name[1]:''),
					'email' => $user_info['email'],
					'telephone' => $user_info['number'],
					'fax' => $user_info['number_2'],
					'newsletter' => '1',
					'password' => '12345678',
					'status' => '1',
					'approved' => '1',
					'safe' => '1'
				);
				$customer_id = $this->model_sale_customer->addCustomer($data1);
			} else {
				$json = array();
				$url ="";
				$uploads = array(
								   'front',
								   'back'
				              );
				foreach ($uploads as $upload){
					
					
					
			 	 	if (!empty($this->request->files[$upload]['name']) && is_file($this->request->files[$upload]['tmp_name'])) {
						$filename = basename(html_entity_decode($this->request->files[$upload]['name'], ENT_QUOTES, 'UTF-8'));
						
						if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
							$json['error'] = $this->language->get('error_filename');
						}
						
						// Allowed file extension types
						$allowed = array();
			
						$extension_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_ext_allowed'));
			
						$filetypes = explode("\n", $extension_allowed);
			
						foreach ($filetypes as $filetype) {
							$allowed[] = trim($filetype);
						}
			
						if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
							$json['error'] = $this->language->get('error_filetype');
						}
							
								// Allowed file mime types
						$allowed = array();
		
						$mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));
		
						$filetypes = explode("\n", $mime_allowed);
		
						foreach ($filetypes as $filetype) {
							$allowed[] = trim($filetype);
						}
		
						if (!in_array($this->request->files[$upload]['type'], $allowed)) {
							$json['error'] = $this->language->get('error_filetype');
						}
			
						$content = file_get_contents($this->request->files[$upload]['tmp_name']);
						if (preg_match('/\<\?php/i', $content)) {
							$json['error'] = $this->language->get('error_filetype');
						}
						
						// Return any upload error
						if ($this->request->files[$upload]['error'] != UPLOAD_ERR_OK) {
							$json['error'] = $this->language->get('error_upload_' . $this->request->files[$upload]['error']);
						}
					} else {
						$json['error'] = $this->language->get('error_upload');
					}
						
					if (!$json) {
						$file = $filename;
		 				move_uploaded_file($this->request->files[$upload]['tmp_name'], DIR_UPLOAD.'supplier/' . $file);
		 				$json['filename'] = $file;
						$json['mask'] = $filename;
						$json = array();
					}
				}
			}
		}
		
		//$this->response->redirect($this->url->link('customerpartner/vendor_list/supplier_form2', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		
	}
 	
}
