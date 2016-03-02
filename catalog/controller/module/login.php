<?php
class ControllerModuleLogin extends Controller {
	private $error = array ();
	public function index() {
		$this->language->load ( 'module/login' );
		
		$data ['text_login'] = $this->language->get ( 'text_login' );
		$data ['text_email'] = $this->language->get ( 'text_email' );
		$data ['text_password'] = $this->language->get ( 'text_password' );
		$data ['text_forgotten'] = $this->language->get ( 'text_forgotten' );
		
		$data ['forgotten'] = $this->url->link ( 'account/forgotten', '', 'SSL' );
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/module/login.tpl' )) {
			$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/module/login.tpl', $data ) );
		} else {
			$this->response->setOutput ( $this->load->view ( 'default/template/module/login.tpl', $data ) );
		}
	}
	public function login() {
		$this->language->load ( 'module/login' );
		
		$json = array ();
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validate ()) {
			unset ( $this->session->data ['guest'] );
			$json ['success'] = $this->url->link ( 'account/account', '', 'SSL' );

			$customerRights = $this->customer->getRights ();
			if (! (isset ( $customerRights ['rights'] ) && in_array ( 'db', $customerRights ['rights'] ))) {				
						$json ['success'] = $this->url->link ( 'account/cd', '', 'SSL' );
			}
			
			
		} else {
			$json ['error'] = $this->error ['warning'];
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	private function validate() {
		if (! $this->customer->login ( $this->request->post ['email'], $this->request->post ['password'] )) {
			$this->error ['warning'] = $this->language->get ( 'error_login' );
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
}
