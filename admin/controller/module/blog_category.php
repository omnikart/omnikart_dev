<?php
class ControllerModuleBlogCategory extends Controller {
	private $error = array ();
	public function index() {
		$this->load->language ( 'module/blog_category' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'extension/module' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validate ()) {
			if (! isset ( $this->request->get ['module_id'] )) {
				$this->model_extension_module->addModule ( 'blog_category', $this->request->post );
			} else {
				$this->model_extension_module->editModule ( $this->request->get ['module_id'], $this->request->post );
			}
			
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			
			$this->response->redirect ( $this->url->link ( 'extension/module', 'token=' . $this->session->data ['token'], 'SSL' ) );
		}
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		
		$data ['text_edit'] = $this->language->get ( 'text_edit' );
		$data ['text_enabled'] = $this->language->get ( 'text_enabled' );
		$data ['text_disabled'] = $this->language->get ( 'text_disabled' );
		
		$data ['entry_name'] = $this->language->get ( 'entry_name' );
		$data ['entry_title'] = $this->language->get ( 'entry_title' );
		$data ['entry_titleicon'] = $this->language->get ( 'entry_titleicon' );
		$data ['entry_listicon'] = $this->language->get ( 'entry_listicon' );
		$data ['entry_sublist_icon'] = $this->language->get ( 'entry_sublist_icon' );
		$data ['entry_status'] = $this->language->get ( 'entry_status' );
		$data ['entry_custom_style'] = $this->language->get ( 'entry_custom_style' );
		$data ['entry_custom_script'] = $this->language->get ( 'entry_custom_script' );
		
		$data ['button_save'] = $this->language->get ( 'button_save' );
		$data ['button_cancel'] = $this->language->get ( 'button_cancel' );
		
		if (isset ( $this->error ['warning'] )) {
			$data ['error_warning'] = $this->error ['warning'];
		} else {
			$data ['error_warning'] = '';
		}
		
		if (isset ( $this->error ['name'] )) {
			$data ['error_name'] = $this->error ['name'];
		} else {
			$data ['error_name'] = '';
		}
		
		if (isset ( $this->error ['title'] )) {
			$data ['error_title'] = $this->error ['title'];
		} else {
			$data ['error_title'] = '';
		}
		
		if (isset ( $this->error ['titleicon'] )) {
			$data ['error_titleicon'] = $this->error ['titleicon'];
		} else {
			$data ['error_titleicon'] = '';
		}
		
		// Breadcrumb
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
				'href' => $this->url->link ( 'module/blog_category', 'token=' . $this->session->data ['token'], 'SSL' ) 
		);
		
		if (! isset ( $this->request->get ['module_id'] )) {
			$data ['action'] = $this->url->link ( 'module/blog_category', 'token=' . $this->session->data ['token'], 'SSL' );
		} else {
			$data ['action'] = $this->url->link ( 'module/blog_category', 'token=' . $this->session->data ['token'] . '&module_id=' . $this->request->get ['module_id'], 'SSL' );
		}
		
		$data ['cancel'] = $this->url->link ( 'extension/module', 'token=' . $this->session->data ['token'], 'SSL' );
		
		if (isset ( $this->request->get ['module_id'] ) && ($this->request->server ['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule ( $this->request->get ['module_id'] );
		}
		
		if (isset ( $this->request->post ['name'] )) {
			$data ['name'] = $this->request->post ['name'];
		} elseif (! empty ( $module_info )) {
			$data ['name'] = $module_info ['name'];
		} else {
			$data ['name'] = '';
		}
		
		if (isset ( $this->request->post ['title'] )) {
			$data ['title'] = $this->request->post ['title'];
		} elseif (! empty ( $module_info )) {
			$data ['title'] = $module_info ['title'];
		} else {
			$data ['title'] = $this->language->get ( 'heading_title' );
		}
		
		if (isset ( $this->request->post ['titleicon'] )) {
			$data ['titleicon'] = $this->request->post ['titleicon'];
		} elseif (! empty ( $module_info )) {
			$data ['titleicon'] = $module_info ['titleicon'];
		} else {
			$data ['titleicon'] = $this->language->get ( 'titleicon' );
		}
		
		if (isset ( $this->request->post ['listicon'] )) {
			$data ['listicon'] = $this->request->post ['listicon'];
		} elseif (! empty ( $module_info )) {
			$data ['listicon'] = $module_info ['listicon'];
		} else {
			$data ['listicon'] = $this->language->get ( 'listicon' );
		}
		
		if (isset ( $this->request->post ['sublist_icon'] )) {
			$data ['sublist_icon'] = $this->request->post ['sublist_icon'];
		} elseif (! empty ( $module_info )) {
			$data ['sublist_icon'] = $module_info ['sublist_icon'];
		} else {
			$data ['sublist_icon'] = $this->language->get ( 'sublist_icon' );
		}
		
		if (isset ( $this->request->post ['status'] )) {
			$data ['status'] = $this->request->post ['status'];
		} elseif (! empty ( $module_info )) {
			$data ['status'] = $module_info ['status'];
		} else {
			$data ['status'] = '';
		}
		
		if (isset ( $this->request->post ['custom_style'] )) {
			$data ['custom_style'] = $this->request->post ['custom_style'];
		} elseif (! empty ( $module_info )) {
			$data ['custom_style'] = $module_info ['custom_style'];
		} else {
			$data ['custom_style'] = '';
		}
		
		if (isset ( $this->request->post ['custom_script'] )) {
			$data ['custom_script'] = $this->request->post ['custom_script'];
		} elseif (! empty ( $module_info )) {
			$data ['custom_script'] = $module_info ['custom_script'];
		} else {
			$data ['custom_script'] = '';
		}
		
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		
		$this->response->setOutput ( $this->load->view ( 'module/blog_category.tpl', $data ) );
	}
	protected function validate() {
		if (! $this->user->hasPermission ( 'modify', 'module/blog_category' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		
		if ((utf8_strlen ( $this->request->post ['name'] ) < 3) || (utf8_strlen ( $this->request->post ['name'] ) > 64)) {
			$this->error ['name'] = $this->language->get ( 'error_name' );
		}
		
		return ! $this->error;
	}
}