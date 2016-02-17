<?php
class ControllerModuleBlogTabs extends Controller {
	private $error = array ();
	public function index() {
		$this->load->language ( 'module/blog_tabs' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'extension/module' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validate ()) {
			if (! isset ( $this->request->get ['module_id'] )) {
				$this->model_extension_module->addModule ( 'blog_tabs', $this->request->post );
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
		$data ['text_static'] = $this->language->get ( 'text_static' );
		$data ['text_slider'] = $this->language->get ( 'text_slider' );
		
		$data ['entry_name'] = $this->language->get ( 'entry_name' );
		$data ['entry_title'] = $this->language->get ( 'entry_title' );
		$data ['entry_listicon'] = $this->language->get ( 'entry_listicon' );
		$data ['entry_limit'] = $this->language->get ( 'entry_limit' );
		$data ['entry_status'] = $this->language->get ( 'entry_status' );
		$data ['entry_word_limit_title'] = $this->language->get ( 'entry_word_limit_title' );
		$data ['entry_word_limit_content'] = $this->language->get ( 'entry_word_limit_content' );
		$data ['entry_thumbnail_show'] = $this->language->get ( 'entry_thumbnail_show' );
		$data ['entry_thumbnail_size'] = $this->language->get ( 'entry_thumbnail_size' );
		$data ['entry_thumbnail_type'] = $this->language->get ( 'entry_thumbnail_type' );
		
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
		
		if (isset ( $this->error ['title_recent'] )) {
			$data ['error_title_recent'] = $this->error ['title_recent'];
		} else {
			$data ['error_title_recent'] = '';
		}
		
		if (isset ( $this->error ['title_popular'] )) {
			$data ['error_title_popular'] = $this->error ['title_popular'];
		} else {
			$data ['error_title_popular'] = '';
		}
		
		if (isset ( $this->error ['listicon_recent'] )) {
			$data ['error_listicon_recent'] = $this->error ['listicon_recent'];
		} else {
			$data ['error_listicon_recent'] = '';
		}
		
		if (isset ( $this->error ['listicon_popular'] )) {
			$data ['error_listicon_popular'] = $this->error ['listicon_popular'];
		} else {
			$data ['error_listicon_popular'] = '';
		}
		
		if (isset ( $this->error ['limit_recent'] )) {
			$data ['error_limit_recent'] = $this->error ['limit_recent'];
		} else {
			$data ['error_limit_recent'] = '';
		}
		
		if (isset ( $this->error ['limit_popular'] )) {
			$data ['error_limit_popular'] = $this->error ['limit_popular'];
		} else {
			$data ['error_limit_popular'] = '';
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
				'href' => $this->url->link ( 'module/blog_tabs', 'token=' . $this->session->data ['token'], 'SSL' ) 
		);
		
		if (! isset ( $this->request->get ['module_id'] )) {
			$data ['action'] = $this->url->link ( 'module/blog_tabs', 'token=' . $this->session->data ['token'], 'SSL' );
		} else {
			$data ['action'] = $this->url->link ( 'module/blog_tabs', 'token=' . $this->session->data ['token'] . '&module_id=' . $this->request->get ['module_id'], 'SSL' );
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
		
		if (isset ( $this->request->post ['title_recent'] )) {
			$data ['title_recent'] = $this->request->post ['title_recent'];
		} elseif (! empty ( $module_info )) {
			$data ['title_recent'] = $module_info ['title_recent'];
		} else {
			$data ['title_recent'] = $this->language->get ( 'title_recent' );
		}
		
		if (isset ( $this->request->post ['title_popular'] )) {
			$data ['title_popular'] = $this->request->post ['title_popular'];
		} elseif (! empty ( $module_info )) {
			$data ['title_popular'] = $module_info ['title_popular'];
		} else {
			$data ['title_popular'] = $this->language->get ( 'title_popular' );
		}
		
		if (isset ( $this->request->post ['listicon_recent'] )) {
			$data ['listicon_recent'] = $this->request->post ['listicon_recent'];
		} elseif (! empty ( $module_info )) {
			$data ['listicon_recent'] = $module_info ['listicon_recent'];
		} else {
			$data ['listicon_recent'] = $this->language->get ( 'listicon_recent' );
		}
		
		if (isset ( $this->request->post ['listicon_popular'] )) {
			$data ['listicon_popular'] = $this->request->post ['listicon_popular'];
		} elseif (! empty ( $module_info )) {
			$data ['listicon_popular'] = $module_info ['listicon_popular'];
		} else {
			$data ['listicon_popular'] = $this->language->get ( 'listicon_popular' );
		}
		
		if (isset ( $this->request->post ['limit_recent'] )) {
			$data ['limit_recent'] = $this->request->post ['limit_recent'];
		} elseif (! empty ( $module_info )) {
			$data ['limit_recent'] = $module_info ['limit_recent'];
		} else {
			$data ['limit_recent'] = '5';
		}
		
		if (isset ( $this->request->post ['limit_popular'] )) {
			$data ['limit_popular'] = $this->request->post ['limit_popular'];
		} elseif (! empty ( $module_info )) {
			$data ['limit_popular'] = $module_info ['limit_popular'];
		} else {
			$data ['limit_popular'] = '5';
		}
		
		if (isset ( $this->request->post ['thumbnail_show_recent'] )) {
			$data ['thumbnail_show_recent'] = $this->request->post ['thumbnail_show_recent'];
		} elseif (! empty ( $module_info )) {
			$data ['thumbnail_show_recent'] = $module_info ['thumbnail_show_recent'];
		} else {
			$data ['thumbnail_show_recent'] = $this->language->get ( 'thumbnail_show_recent' );
		}
		
		if (isset ( $this->request->post ['thumbnail_show_popular'] )) {
			$data ['thumbnail_show_popular'] = $this->request->post ['thumbnail_show_popular'];
		} elseif (! empty ( $module_info )) {
			$data ['thumbnail_show_popular'] = $module_info ['thumbnail_show_popular'];
		} else {
			$data ['thumbnail_show_popular'] = $this->language->get ( 'thumbnail_show_popular' );
		}
		
		if (isset ( $this->request->post ['thumbnail_size_recent'] )) {
			$data ['thumbnail_size_recent'] = $this->request->post ['thumbnail_size_recent'];
		} elseif (! empty ( $module_info )) {
			$data ['thumbnail_size_recent'] = $module_info ['thumbnail_size_recent'];
		} else {
			$data ['thumbnail_size_recent'] = '80x90';
		}
		
		if (isset ( $this->request->post ['thumbnail_size_popular'] )) {
			$data ['thumbnail_size_popular'] = $this->request->post ['thumbnail_size_popular'];
		} elseif (! empty ( $module_info )) {
			$data ['thumbnail_size_popular'] = $module_info ['thumbnail_size_popular'];
		} else {
			$data ['thumbnail_size_popular'] = '80x90';
		}
		
		if (isset ( $this->request->post ['thumbnail_type_recent'] )) {
			$data ['thumbnail_type_recent'] = $this->request->post ['thumbnail_type_recent'];
		} elseif (! empty ( $module_info )) {
			$data ['thumbnail_type_recent'] = $module_info ['thumbnail_type_recent'];
		} else {
			$data ['thumbnail_type_recent'] = $this->language->get ( 'thumbnail_type_recent' );
		}
		
		if (isset ( $this->request->post ['thumbnail_type_popular'] )) {
			$data ['thumbnail_type_popular'] = $this->request->post ['thumbnail_type_popular'];
		} elseif (! empty ( $module_info )) {
			$data ['thumbnail_type_popular'] = $module_info ['thumbnail_type_popular'];
		} else {
			$data ['thumbnail_type_popular'] = $this->language->get ( 'thumbnail_type_popular' );
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
		
		if (isset ( $this->request->post ['status'] )) {
			$data ['status'] = $this->request->post ['status'];
		} elseif (! empty ( $module_info )) {
			$data ['status'] = $module_info ['status'];
		} else {
			$data ['status'] = '';
		}
		
		if (isset ( $this->request->post ['limit_title'] )) {
			$data ['limit_title'] = $this->request->post ['limit_title'];
		} elseif (! empty ( $module_info )) {
			$data ['limit_title'] = $module_info ['limit_title'];
		} else {
			$data ['limit_title'] = 10;
		}
		
		if (isset ( $this->request->post ['limit_content'] )) {
			$data ['limit_content'] = $this->request->post ['limit_content'];
		} elseif (! empty ( $module_info )) {
			$data ['limit_content'] = $module_info ['limit_content'];
		} else {
			$data ['limit_content'] = 35;
		}
		
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		
		$this->response->setOutput ( $this->load->view ( 'module/blog_tabs.tpl', $data ) );
	}
	protected function validate() {
		if (! $this->user->hasPermission ( 'modify', 'module/blog_tabs' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		
		if ((utf8_strlen ( $this->request->post ['name'] ) < 3) || (utf8_strlen ( $this->request->post ['name'] ) > 64)) {
			$this->error ['name'] = $this->language->get ( 'error_name' );
		}
		
		return ! $this->error;
	}
}