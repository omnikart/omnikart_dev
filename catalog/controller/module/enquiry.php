<?php
class ControllerModuleEnquiry extends Controller {
	private $error = array ();
	public function index($setting) {
		$data = array ();
		$data ['modal_id'] = $setting ['modal_id'];
		$data ['logged'] = false;
		if ($this->customer->isLogged ()) {
			$data ['firstname'] = $this->customer->getFirstName ();
			$data ['lastname'] = $this->customer->getLastName ();
			$data ['email'] = $this->customer->getEmail ();
			$data ['phone'] = $this->customer->getTelephone ();
			$data ['logged'] = true;
		}
		
		$this->load->model ( 'localisation/unit_class' );
		
		$data ['unit_classes'] = $this->model_localisation_unit_class->getUnitClasses ();
		$data ['autocomplete_products'] = $this->url->link ( 'product/json/enquiry_product', '', 'SSL' );
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/module/enquiry_form.tpl' )) {
			return $this->load->view ( $this->config->get ( 'config_template' ) . '/template/module/enquiry_form.tpl', $data );
		} else {
			return $this->load->view ( 'default/template/module/enquiry_form.tpl', $data );
		}
	}
	public function submit() {
		$json = array ();
		
		if ($this->request->server ['REQUEST_METHOD'] == 'POST') {
			
			if (! $this->customer->isLogged ())
				$json ['logged'] = false;
			
			$data = $this->request->post;
			
			if (! isset ( $data ['payment_terms'] ))
				$json ['payment_terms'] = 'Please select payment terms...!';
			
			if (! isset ( $data ['c_form'] ))
				$json ['c_form'] = 'Please check whether you can provide C-Form...!';
			
			if (! $json && isset ( $this->session->data ['enquiry'] )) {
				$data ['enquiries'] = array ();
				$data ['payment_terms'] = $data ['payment_terms'];
				$data ['address_id'] = $data ['address_id'];
				$data ['c_form'] = $data ['c_form'];
				$enquiries = $this->session->data ['enquiry'];
				
				foreach ( $enquiries as $enquiry => $quantity ) {
					$enq = unserialize ( base64_decode ( $enquiry ) );
					$data ['enquiries'] [] = array (
							'name' => $enq ['name'],
							'product_id' => $enq ['product_id'],
							'category_id' => $enq ['category_id'],
							'quantity' => $quantity,
							'description' => $enq ['description'],
							'unit_class' => $enq ['unit_class'],
							'filenames' => $enq ['filenames'] 
					);
				}
				$this->load->model ( 'module/enquiry' );
				$this->model_module_enquiry->addenquiry ( $data );
				$this->model_module_enquiry->getEnquiry();
				unset ( $this->session->data ['enquiry'] );
				$json ['success'] = "Successfully send your query to Omnikart. We'll get back to you soon. :)";
			}
		}
		echo json_encode ( $json );
	}
	public function upload() {
		$this->load->language ( 'catalog/download' );
		
		$json = array ();
		
		if (! $json) {
			if (! empty ( $this->request->files ['file'] ['name'] ) && is_file ( $this->request->files ['file'] ['tmp_name'] )) {
				// Sanitize the filename
				$filename = basename ( html_entity_decode ( $this->request->files ['file'] ['name'], ENT_QUOTES, 'UTF-8' ) );
				
				// Validate the filename length
				if ((utf8_strlen ( $filename ) < 3) || (utf8_strlen ( $filename ) > 128)) {
					$json ['error'] = $this->language->get ( 'error_filename' );
				}
				
				if ($this->request->files ['file'] ['size'] > 10485760) {
					$json ['error'] = "File size should not be more than 10MB";
				}
				
				// Allowed file extension types
				$allowed = array ();
				
				$extension_allowed = preg_replace ( '~\r?\n~', "\n", $this->config->get ( 'config_file_ext_allowed' ) );
				
				$filetypes = explode ( "\n", $extension_allowed );
				
				foreach ( $filetypes as $filetype ) {
					$allowed [] = trim ( $filetype );
				}
				
				if (! in_array ( strtolower ( substr ( strrchr ( $filename, '.' ), 1 ) ), $allowed )) {
					$json ['error'] = $this->language->get ( 'error_filetype' );
				}
				
				// Allowed file mime types
				$allowed = array ();
				
				$mime_allowed = preg_replace ( '~\r?\n~', "\n", $this->config->get ( 'config_file_mime_allowed' ) );
				
				$filetypes = explode ( "\n", $mime_allowed );
				
				foreach ( $filetypes as $filetype ) {
					$allowed [] = trim ( $filetype );
				}
				
				if (! in_array ( $this->request->files ['file'] ['type'], $allowed )) {
					$json ['error'] = $this->language->get ( 'error_filetype' );
				}
				
				// Check to see if any PHP files are trying to be uploaded
				$content = file_get_contents ( $this->request->files ['file'] ['tmp_name'] );
				
				if (preg_match ( '/\<\?php/i', $content )) {
					$json ['error'] = $this->language->get ( 'error_filetype' );
				}
				
				// Return any upload error
				if ($this->request->files ['file'] ['error'] != UPLOAD_ERR_OK) {
					$json ['error'] = $this->language->get ( 'error_upload_' . $this->request->files ['file'] ['error'] );
				}
			} else {
				$json ['error'] = $this->language->get ( 'error_upload' );
			}
		}
		
		if (! $json) {
			$file = $filename . '.' . md5 ( mt_rand () );
			
			move_uploaded_file ( $this->request->files ['file'] ['tmp_name'], DIR_UPLOAD . 'queries/' . $file );
			
			$json ['filename'] = $file;
			$json ['mask'] = $filename;
			
			$json ['link'] = HTTPS_SERVER . 'system/upload/queries/' . $file;
			
			$json ['success'] = $this->language->get ( 'text_upload' );
		}
		
		$this->response->addHeader ( 'Content-Type: application/json' );
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function addProduct() {
		$this->load->language ( 'module/enquiry' );
		$json = array ();
		if ($this->request->server ['REQUEST_METHOD'] == 'POST') {
			if (isset ( $this->request->post )) {
				if (isset ( $this->request->post ['name'] )) {
					$data = $this->request->post;
					$enquiry = array ();
					
					if (isset ( $data ['name'] ) && $data ['name'])
						$enquiry ['name'] = $data ['name'];
					else
						$json ['name'] = 'Please enter the valid enquiry';
					
					if (! $json) {
						if (isset ( $data ['product_id'] ))
							$enquiry ['product_id'] = $data ['product_id'];
						else
							$enquiry ['product_id'] = 0;
						
						if (isset ( $data ['category_id'] ))
							$enquiry ['category_id'] = $data ['category_id'];
						else
							$enquiry ['category_id'] = 0;
						
						if (isset ( $data ['unit_class'] ))
							$enquiry ['unit_class'] = $data ['unit_class'];
						else
							$enquiry ['unit_class'] = 1;
						
						if (isset ( $data ['description'] ))
							$enquiry ['description'] = $data ['description'];
						else
							$enquiry ['description'] = '';
						
						if (isset ( $data ['filenames'] ))
							$enquiry ['filenames'] = $data ['filenames'];
						else
							$enquiry ['filenames'] = array ();
						
						$key = base64_encode ( serialize ( $enquiry ) );
						
						if (isset ( $data ['quantity'] ))
							$quantity = $data ['quantity'];
						else
							$quantity = 1;
						
						if (( int ) $quantity && (( int ) $quantity > 0)) {
							if (! isset ( $this->session->data ['enquiry'] [$key] )) {
								$this->session->data ['enquiry'] [$key] = ( int ) $quantity;
							} else {
								$this->session->data ['enquiry'] [$key] += ( int ) $quantity;
							}
						}
						$json ['success'] = 'Product added successfully';
						$json ['number'] = count ( $this->session->data ['enquiry'] );
					}
				} elseif (isset ( $this->request->post ['product_id'] )) {
					$data = $this->request->post;
					$enquiry ['product_id'] = $data ['product_id'];
					$enquiry ['category_id'] = 0;
					$enquiry ['unit_class'] = 1;
					$enquiry ['filenames'] = array ();
					
					$this->load->model ( 'catalog/product' );
					
					$attributes = $this->model_catalog_product->getProductAttributes ( $data ['product_id'] );
					$output = '';
					foreach ( $attributes as $ag ) {
						foreach ( $ag ['attribute'] as $a ) {
							$output .= $a ['name'] . '-' . $a ['text'] . "\n";
						}
					}
					$enquiry ['description'] = $output;
					$product = $this->model_catalog_product->getProduct ( $data ['product_id'] );
					$enquiry ['name'] = $product ['name'];
					
					$key = base64_encode ( serialize ( $enquiry ) );
					
					if (isset ( $data ['quantity'] ))
						$quantity = $data ['quantity'];
					else
						$quantity = 1;
					
					if (( int ) $quantity && (( int ) $quantity > 0)) {
						if (! isset ( $this->session->data ['enquiry'] [$key] )) {
							$this->session->data ['enquiry'] [$key] = ( int ) $quantity;
						} else {
							$this->session->data ['enquiry'] [$key] += ( int ) $quantity;
						}
					}
					$json ['success'] = sprintf ( $this->language->get ( 'text_success' ), $this->url->link ( 'product/product', 'product_id=' . $enquiry ['product_id'] ), $enquiry ['name'] );
					$json ['number'] = count ( $this->session->data ['enquiry'] );
				}
			}
			$this->response->setOutput ( json_encode ( $json ) );
		} elseif ($this->request->server ['REQUEST_METHOD'] == 'GET') {
			if (isset ( $this->session->data ['enquiry'] )) {
				$this->response->setOutput ( count ( $this->session->data ['enquiry'] ) );
			} else {
				$this->response->setOutput ( " 0 " );
			}
		}
	}
	public function test() {
		if (isset ( $this->request->get ['enquiry_id'] )) {
			$this->load->model ( 'module/enquiry' );
			$this->model_module_enquiry->renderEnquiry ( $this->request->get ['enquiry_id'] );
		}
	}
	public function deleteProduct() {
		if ($this->request->server ['REQUEST_METHOD'] == 'POST') {
			if (isset ( $this->request->post ['key'] )) {
				unset ( $this->session->data ['enquiry'] [$this->request->post ['key']] );
				$this->getEnquiry ();
			}
		}
	}
	
	public function getEnquiry() {
		
		$data = array ();
		$data ['enquiries'] = array ();
		if (isset ( $this->session->data ['enquiry'] ))
			$enquiries = $this->session->data ['enquiry'];
		else
			$enquiries = array ();
		
		$this->load->model ( 'localisation/unit_class' );
		$this->load->model ( 'module/enquiry' );
		$data ['payment_terms'] = $this->model_module_enquiry->getPaymentTerms ();
		foreach ( $enquiries as $enquiry => $quantity ) {
			$enq = unserialize ( base64_decode ( $enquiry ) );
			$data ['enquiries'] [$enquiry] = array (
					'name' => $enq ['name'],
					'product_id' => $enq ['product_id'],
					'category_id' => $enq ['category_id'],
					'quantity' => $quantity,
					'description' => $enq ['description'],
					'unit_class' => $this->model_localisation_unit_class->getUnitClass ( $enq ['unit_class'] ),
					'filenames' => $enq ['filenames'] 
			);
		}
		
		$data ['addresses'] = array ();
		$this->load->model ( 'account/address' );
		$data ['addresses'] = $this->model_account_address->getAddresses ();
		
		if (isset ( $this->request->post ['country_id'] )) {
			$data ['country_id'] = $this->request->post ['country_id'];
		} elseif (! empty ( $address_info )) {
			$data ['country_id'] = $address_info ['country_id'];
		} else {
			$data ['country_id'] = $this->config->get ( 'config_country_id' );
		}
		
		if (isset ( $this->request->post ['zone_id'] )) {
			$data ['zone_id'] = $this->request->post ['zone_id'];
		} elseif (! empty ( $address_info )) {
			$data ['zone_id'] = $address_info ['zone_id'];
		} else {
			$data ['zone_id'] = '';
		}
		
		$this->load->model ( 'localisation/country' );
		
		$data['add_address'] = $this->url->link ( 'account/address', '', 'SSL' );
		$data ['countries'] = $this->model_localisation_country->getCountries ();
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/module/enquiry_products.tpl' )) {
			$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/module/enquiry_products.tpl', $data ) );
		} else {
			
			$this->response->setOutput ( $this->load->view ( 'default/template/module/enquiry_products.tpl', $data ) );
		}
	}
	
	
	public function getEnquiryComment() {
		if (isset ( $this->request->get ['enquiry_id'] ))
			$enquiry_id = $this->request->get ['enquiry_id'];
		else
			$enquiry_id = 0;
		$this->load->model ( 'account/customerpartner' );
		$seller_id = $this->model_account_customerpartner->getuserseller ();
		$json = array ();
		$this->load->model ( 'module/enquiry' );
		$json = $this->model_module_enquiry->getEnquiryComments ( $enquiry_id, $seller_id );
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function addEnquiryComment() {
		$this->load->model ( 'account/customerpartner' );
		$seller_id = $this->model_account_customerpartner->getuserseller ();
		$json = array ();
		if ($this->request->server ['REQUEST_METHOD'] == 'POST') {
			if (isset ( $this->request->post )) {
				$this->load->model ( 'module/enquiry' );
				$data = $this->request->post;
				$this->model_module_enquiry->addEnquiryComments ( $data ['enquiry_id'], $seller_id, $data ['enquiry'] [$data ['enquiry_id']] );
				$json['name'] = $this->customer->getFirstName().' '.$this->customer->getLastName();
				$this->response->setOutput ( json_encode ( $json ) );
			}
		}
	}
	
	
	public function install(){
		$this->load->model('module/enquiry');
		$this->model_module_enquiry->install();
	}	
}