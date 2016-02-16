<?php
class ControllerModuleCategorybanner extends Controller {
	private $error = array ();
	public function index() {
		$this->load->language ( 'module/categorybanner' );
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		$this->load->model ( 'extension/module' );
		$this->load->model ( 'module/categorybanner' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validate ()) {
			if (! isset ( $this->request->get ['module_id'] )) {
				$this->model_extension_module->addModule ( 'categorybanner', $this->request->post );
			} else {
				$this->model_extension_module->editModule ( $this->request->get ['module_id'], $this->request->post );
			}
			$this->model_module_categorybanner->updateCategories ( $this->request->post );
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			$this->response->redirect ( $this->url->link ( 'extension/module', 'token=' . $this->session->data ['token'], 'SSL' ) );
		}
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		$data ['text_edit'] = $this->language->get ( 'text_edit' );
		$data ['text_enabled'] = $this->language->get ( 'text_enabled' );
		$data ['text_disabled'] = $this->language->get ( 'text_disabled' );
		
		$data ['button_save'] = $this->language->get ( 'button_save' );
		$data ['button_cancel'] = $this->language->get ( 'button_cancel' );
		
		$data ['entry_name'] = $this->language->get ( 'entry_name' );
		$data ['entry_status'] = $this->language->get ( 'entry_status' );
		$data ['entry_category'] = $this->language->get ( 'entry_category' );
		
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
					'href' => $this->url->link ( 'module/categorybanner', 'token=' . $this->session->data ['token'], 'SSL' ) 
			);
		} else {
			$data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'heading_title' ),
					'href' => $this->url->link ( 'module/categorybanner', 'token=' . $this->session->data ['token'] . '&module_id=' . $this->request->get ['module_id'], 'SSL' ) 
			);
		}
		
		if (! isset ( $this->request->get ['module_id'] )) {
			$data ['action'] = $this->url->link ( 'module/categorybanner', 'token=' . $this->session->data ['token'], 'SSL' );
		} else {
			$data ['action'] = $this->url->link ( 'module/categorybanner', 'token=' . $this->session->data ['token'] . '&module_id=' . $this->request->get ['module_id'], 'SSL' );
		}
		
		$data ['cancel'] = $this->url->link ( 'extension/module', 'token=' . $this->session->data ['token'], 'SSL' );
		
		$data ['install'] = $this->url->link ( 'extension/categorybanner/install', 'token=' . $this->session->data ['token'], 'SSL' );
		
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
		
		if (isset ( $this->request->post ['status'] )) {
			$data ['status'] = $this->request->post ['status'];
		} elseif (! empty ( $module_info )) {
			$data ['status'] = $module_info ['status'];
		} else {
			$data ['status'] = '';
		}
		
		$data ['categories'] = array ();
		$this->load->model ( 'catalog/category' );
		
		$results = $this->model_catalog_category->getCategoriesByParent ( 0 );
		foreach ( $results as $result ) {
			$banner = $this->model_module_categorybanner->getCategory ( array (
					'category_id' => $result ['category_id'] 
			) );
			if (! $banner)
				$banner = array (
						'width' => '0',
						'height' => '0',
						'banner_id' => '0',
						'element_id' => $result ['category_id'] 
				);
			$data ['categories'] [] = array (
					'banner' => $banner,
					'name' => $result ['name'],
					'category_id' => $result ['category_id'],
					'route' => 'product/category' 
			);
		}
		
		$this->load->model ( 'design/banner' );
		$data ['banners'] = $this->model_design_banner->getBanners ();
		
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		
		$this->response->setOutput ( $this->load->view ( 'module/categorybanner.tpl', $data ) );
	}
	public function install() {
		$this->load->model ( 'module/categorybanner' );
		$this->model_module_categorybanner->install ();
		$this->response->redirect ( $this->url->link ( 'module/categorybanner', 'token=' . $this->session->data ['token'], 'SSL' ) );
	}
	private function validate() {
		if (! $this->user->hasPermission ( 'modify', 'module/categorybanner' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		
		if ((utf8_strlen ( $this->request->post ['name'] ) < 3) || (utf8_strlen ( $this->request->post ['name'] ) > 64)) {
			$this->error ['name'] = $this->language->get ( 'error_name' );
		}
		return ! $this->error;
	}
}