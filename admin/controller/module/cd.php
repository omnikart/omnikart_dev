<?php
class ControllerModuleCd extends Controller {
	private $error = array ();
	public function index() {
		$this->load->language ( 'module/cd' );
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		$this->load->model ( 'setting/setting' );
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validate ()) {
			$this->model_setting_setting->editSetting ( 'cd', $this->request->post );
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			$this->response->redirect ( $this->url->link ( 'extension/module', 'token=' . $this->session->data ['token'], 'SSL' ) );
		}
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		$data ['text_edit'] = $this->language->get ( 'text_edit' );
		$data ['text_enabled'] = $this->language->get ( 'text_enabled' );
		$data ['text_disabled'] = $this->language->get ( 'text_disabled' );
		$data ['entry_status'] = $this->language->get ( 'entry_status' );
		$data ['button_save'] = $this->language->get ( 'button_save' );
		$data ['button_cancel'] = $this->language->get ( 'button_cancel' );
		$data ['entry_customer_group'] = $this->language->get ( 'entry_customer_group' );
		
		if (isset ( $this->error ['warning'] )) {
			$data ['error_warning'] = $this->error ['warning'];
		} else {
			$data ['error_warning'] = '';
		}
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/dashboard', 'token=' . $this->session->data ['token'], 'SSL' ) 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_module' ),
				'href' => $this->url->link ( 'extension/module', 'token=' . $this->session->data ['token'], 'SSL' ) 
		);
		
		if (! isset ( $this->request->get ['module_id'] )) {
			$data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'heading_title' ),
					'href' => $this->url->link ( 'module/cd', 'token=' . $this->session->data ['token'], 'SSL' ) 
			);
		}
		
		if (isset ( $this->request->post ['cd_customer_group'] )) {
			$data ['cd_customer_group'] = $this->request->post ['cd_customer_group'];
		} else {
			$data ['cd_customer_group'] = array (
					$this->config->get ( 'cd_customer_group' ) 
			);
		}
		
		$this->load->model ( 'sale/customer_group' );
		
		$data ['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups ();
		
		$data ['action'] = $this->url->link ( 'module/cd', 'token=' . $this->session->data ['token'], 'SSL' );
		
		$data ['cancel'] = $this->url->link ( 'extension/module', 'token=' . $this->session->data ['token'], 'SSL' );
		
		if (isset ( $this->request->post ['cd_status'] )) {
			$data ['cd_status'] = $this->request->post ['cd_status'];
		} else {
			$data ['cd_status'] = $this->config->get ( 'cd_status' );
		}
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		$this->response->setOutput ( $this->load->view ( 'module/cd.tpl', $data ) );
	}
	protected function validate() {
		if (! $this->user->hasPermission ( 'modify', 'module/cd' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		return ! $this->error;
	}
}