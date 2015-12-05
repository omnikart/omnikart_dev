<?php
class ControllerAppLogin extends Controller {

	public function index() {
		// Delete old login so not to cause any issues if there is an error
		$json = array();
		
		unset($this->session->data['app_id']);
		
		$data = json_decode(file_get_contents('php://input'), true);
		$keys = array(
			'username',
			'password'
		);

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		$username = ($this->request->post['username']?$this->request->post['username']:(isset($data['username'])?$data['username']:''));
		$password = ($this->request->post['password']?$this->request->post['password']:(isset($data['password'])?$data['password']:''));
		
		if ($this->customer->login($username, $password)) {
			
			$this->session->data['app_id'] = sha1($this->customer->getId());

			$json['cookie'] = $this->session->getId();

			$json['success'] = "1";
		} else {
			$json['success'] = "0";
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function logout() {
		// Delete old login so not to cause any issues if there is an error
		$json = array();
	
		unset($this->session->data['app_id']);
		$this->customer->logout();
		$json['success'] = "1";
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
}