<?php
class ControllerModuleBlogActiveInactive extends Controller {
	private $error = array ();
	public function index() {
		$this->load->language ( 'module/blog_active_inactive' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		// $this->load->model('setting/setting');
		
		if (isset ( $this->request->get ['extension'] ) && ($this->request->get ['extension'] == 'blog')) {
			
			$this->load->model ( 'user/user_group' );
			
			$action = isset ( $this->request->get ['action'] ) ? $this->request->get ['action'] : '';
			
			switch ($action) {
				case 'add' :
					$this->model_user_user_group->addPermission ( $this->user->getGroupId (), 'access', 'extension/' . $this->request->get ['extension'] );
					$this->model_user_user_group->addPermission ( $this->user->getGroupId (), 'modify', 'extension/' . $this->request->get ['extension'] );
					$this->session->data ['success'] = $this->language->get ( 'text_success_activate' );
					break;
				case 'remove' :
					$this->model_user_user_group->removePermission ( $this->user->getGroupId (), 'access', 'extension/' . $this->request->get ['extension'] );
					$this->model_user_user_group->removePermission ( $this->user->getGroupId (), 'modify', 'extension/' . $this->request->get ['extension'] );
					$this->session->data ['success'] = $this->language->get ( 'text_success_inactivate' );
					break;
				
				default :
					$this->session->data ['error'] = 'Action unspecified';
					break;
			}
			
			$this->response->redirect ( $this->url->link ( 'extension/module', 'token=' . $this->session->data ['token'], 'SSL' ) );
		}
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		
		$data ['text_edit'] = $this->language->get ( 'text_edit' );
		$data ['text_enabled'] = $this->language->get ( 'text_enabled' );
		$data ['text_disabled'] = $this->language->get ( 'text_disabled' );
		
		$data ['btn_activate'] = $this->language->get ( 'btn_activate' );
		$data ['btn_inactivate'] = $this->language->get ( 'btn_inactivate' );
		
		$data ['entry_status'] = $this->language->get ( 'entry_status' );
		
		$data ['button_cancel'] = $this->language->get ( 'button_cancel' );
		
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
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'heading_title' ),
				'href' => $this->url->link ( 'module/blog_active_inactive', 'token=' . $this->session->data ['token'], 'SSL' ) 
		);
		
		$data ['active'] = $this->url->link ( 'module/blog_active_inactive', 'token=' . $this->session->data ['token'] . '&extension=blog&action=add', 'SSL' );
		$data ['inactive'] = $this->url->link ( 'module/blog_active_inactive', 'token=' . $this->session->data ['token'] . '&extension=blog&action=remove', 'SSL' );
		
		$data ['cancel'] = $this->url->link ( 'extension/module', 'token=' . $this->session->data ['token'], 'SSL' );
		
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		
		$this->response->setOutput ( $this->load->view ( 'module/blog_active_inactive.tpl', $data ) );
	}
}