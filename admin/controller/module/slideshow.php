<?php
class ControllerModuleSlideshow extends Controller {
	private $error = array ();
	public function index() {
		$this->load->language ( 'module/slideshow' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'extension/module' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validate ()) {
			if (! isset ( $this->request->get ['module_id'] )) {
				$this->model_extension_module->addModule ( 'slideshow', $this->request->post );
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
		$data ['entry_banner'] = $this->language->get ( 'entry_banner' );
		$data ['entry_width'] = $this->language->get ( 'entry_width' );
		$data ['entry_height'] = $this->language->get ( 'entry_height' );
		$data ['entry_margin_left'] = $this->language->get ( 'entry_margin_left' );
		$data ['entry_status'] = $this->language->get ( 'entry_status' );
		
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
		
		if (isset ( $this->error ['width'] )) {
			$data ['error_width'] = $this->error ['width'];
		} else {
			$data ['error_width'] = '';
		}
		if (isset ( $this->error ['height'] )) {
			$data ['error_height'] = $this->error ['height'];
		} else {
			$data ['error_height'] = '';
		}
		
		if (isset ( $this->error ['margin_left'] )) {
			$data ['error_margin_left'] = $this->error ['margin_left'];
		} else {
			$data ['error_margin_left'] = '';
		}
		if (isset ( $this->error ['side_width'] )) {
			$data ['error_side_width'] = $this->error ['side_width'];
		} else {
			$data ['error_side_width'] = '';
		}
		if (isset ( $this->error ['side_height'] )) {
			$data ['error_side_height'] = $this->error ['side_height'];
		} else {
			$data ['error_side_height'] = '';
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
					'href' => $this->url->link ( 'module/slideshow', 'token=' . $this->session->data ['token'], 'SSL' ) 
			);
		} else {
			$data ['breadcrumbs'] [] = array (
					'text' => $this->language->get ( 'heading_title' ),
					'href' => $this->url->link ( 'module/slideshow', 'token=' . $this->session->data ['token'] . '&module_id=' . $this->request->get ['module_id'], 'SSL' ) 
			);
		}
		
		if (! isset ( $this->request->get ['module_id'] )) {
			$data ['action'] = $this->url->link ( 'module/slideshow', 'token=' . $this->session->data ['token'], 'SSL' );
		} else {
			$data ['action'] = $this->url->link ( 'module/slideshow', 'token=' . $this->session->data ['token'] . '&module_id=' . $this->request->get ['module_id'], 'SSL' );
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
		
		if (isset ( $this->request->post ['banner_id'] )) {
			$data ['banner_id'] = $this->request->post ['banner_id'];
		} elseif (! empty ( $module_info )) {
			$data ['banner_id'] = $module_info ['banner_id'];
		} else {
			$data ['banner_id'] = '';
		}
		
		$this->load->model ( 'design/banner' );
		
		$data ['banners'] = $this->model_design_banner->getBanners ();
		
		if (isset ( $this->request->post ['width'] )) {
			$data ['width'] = $this->request->post ['width'];
		} elseif (! empty ( $module_info )) {
			$data ['width'] = $module_info ['width'];
		} else {
			$data ['width'] = '';
		}
		
		if (isset ( $this->request->post ['height'] )) {
			$data ['height'] = $this->request->post ['height'];
		} elseif (! empty ( $module_info )) {
			$data ['height'] = $module_info ['height'];
		} else {
			$data ['height'] = '';
		}
		
		if (isset ( $this->request->post ['side_height'] )) {
			$data ['side_height'] = $this->request->post ['side_height'];
		} elseif (! empty ( $module_info )) {
			$data ['side_height'] = $module_info ['side_height'];
		} else {
			$data ['side_height'] = '';
		}
		if (isset ( $this->request->post ['side_width'] )) {
			$data ['side_width'] = $this->request->post ['side_width'];
		} elseif (! empty ( $module_info )) {
			$data ['side_width'] = $module_info ['side_width'];
		} else {
			$data ['side_width'] = '';
		}
		
		if (isset ( $this->request->post ['margin_left'] )) {
			$data ['margin_left'] = $this->request->post ['margin_left'];
		} elseif (! empty ( $module_info )) {
			$data ['margin_left'] = $module_info ['margin_left'];
		} else {
			$data ['margin_left'] = '';
		}
		
		if (isset ( $this->request->post ['status'] )) {
			$data ['status'] = $this->request->post ['status'];
		} elseif (! empty ( $module_info )) {
			$data ['status'] = $module_info ['status'];
		} else {
			$data ['status'] = '';
		}
		
		if (isset ( $this->request->post ['status_side'] )) {
			$data ['status_side'] = $this->request->post ['status_side'];
		} elseif (! empty ( $module_info )) {
			$data ['status_side'] = $module_info ['status_side'];
		} else {
			$data ['status_side'] = '';
		}
		
		if (isset ( $this->request->post ['side_banner_id'] )) {
			$data ['side_banner_id'] = $this->request->post ['side_banner_id'];
		} elseif (! empty ( $module_info )) {
			$data ['side_banner_id'] = $module_info ['side_banner_id'];
		} else {
			$data ['side_banner_id'] = '';
		}
		
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		
		$this->response->setOutput ( $this->load->view ( 'module/slideshow.tpl', $data ) );
	}
	protected function validate() {
		if (! $this->user->hasPermission ( 'modify', 'module/slideshow' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		
		if ((utf8_strlen ( $this->request->post ['name'] ) < 3) || (utf8_strlen ( $this->request->post ['name'] ) > 64)) {
			$this->error ['name'] = $this->language->get ( 'error_name' );
		}
		
		if (! $this->request->post ['width']) {
			$this->error ['width'] = $this->language->get ( 'error_width' );
		}
		
		if (! $this->request->post ['height']) {
			$this->error ['height'] = $this->language->get ( 'error_height' );
		}
		
		if (! $this->request->post ['margin_left'] && ($this->request->post ['margin_left'] != 0)) {
			$this->error ['margin_left'] = $this->language->get ( 'error_margin_left' );
		}
		
		return ! $this->error;
	}
}
