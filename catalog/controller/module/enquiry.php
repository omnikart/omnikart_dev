<?php
class ControllerModuleEnquiry extends Controller {
	private $error = array();
	public function index($setting){
		$data = array();
		$data['modal_id'] = $setting['modal_id'];
		$data['logged'] = false;
		if ($this->customer->isLogged()){
			$data['firstname'] = $this->customer->getFirstName();
			$data['lastname'] = $this->customer->getLastName();
			$data['email'] = $this->customer->getEmail();
			$data['phone'] = $this->customer->getTelephone();
			$data['logged'] = true;
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/enquiry.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/enquiry.tpl', $data);
		} else {
			return $this->load->view('default/template/module/enquiry.tpl', $data);
		}
	}
	
	public function submit(){
		$json = array();
		
		$fields = array("firstname","lastname","phone");
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST'){		
			foreach ($fields as $field){
				if (isset($this->request->post[$field])){
					if (empty($this->request->post[$field])){
						$json[$field] = ucfirst($field)." field cannot be empty";
					}
				} else {
						$json['error'] = "Incomplete Date Received";
				}
			}
			if (isset($this->request->post['product'])){
				if (empty($this->request->post['product'])){
					$json['productall'] = "Please fill product information";
				} else {
					if (empty($this->request->post['product'][0]['name'])) {
						$json['product'] = "Please fill product name";
					}
					if (empty($this->request->post['product'][0]['quantity'])) {
						$json['quantity'] = "Please fill product quantity required";
					}
					if (empty($this->request->post['product'][0]['name'])) {
						$json['specification'] = "Please fill product specification";
					}
				}
			}
			
			if (!$json) {
				$userdata = array("firstname" => $this->request->post['firstname'],
						"lastname" => $this->request->post['lastname'],
						"phone" => $this->request->post['phone'],
						"email" => $this->request->post['email']);
				
				$file = isset($this->request->post['filename'])?$this->request->post['filename']:'';
				
				$data = array("user_info" => serialize($userdata),
						"query" => serialize($this->request->post['product']),
						"file" => $file
					);
				
				$this->load->model('module/enquiry');
				$this->model_module_enquiry->addenquiry($data);
				$json['success'] = "Successfully send your query to Omnikart. We'll get back to you soon. :)";
			}
			
		}
		
		echo json_encode($json);
	}

	public function upload() {
		$this->load->language('catalog/download');
	
		$json = array();
	
	
		if (!$json) {
			if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
				// Sanitize the filename
				$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));
	
				// Validate the filename length
				if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
					$json['error'] = $this->language->get('error_filename');
				}
				
				if ($this->request->files['file']['size'] > 10485760){
					$json['error'] = "File size should not be more than 10MB";
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
	
				if (!in_array($this->request->files['file']['type'], $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}
	
				// Check to see if any PHP files are trying to be uploaded
				$content = file_get_contents($this->request->files['file']['tmp_name']);
	
				if (preg_match('/\<\?php/i', $content)) {
					$json['error'] = $this->language->get('error_filetype');
				}
	
				// Return any upload error
				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
				}
			} else {
				$json['error'] = $this->language->get('error_upload');
			}
		}
	
		if (!$json) {
			$file = $filename . '.' . md5(mt_rand());
	
			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_UPLOAD.'queries/' . $file);
	
			$json['filename'] = $file;
			$json['mask'] = $filename;
			
			$json['link'] = HTTPS_SERVER.'system/upload/queries/' . $file;
	
			$json['success'] = $this->language->get('text_upload');
		}
	
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	
}