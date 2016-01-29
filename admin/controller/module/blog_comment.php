<?php
class ControllerModuleBlogComment extends Controller {
	private $error = array ();
	public function index() {
		$this->load->language ( 'module/blog_comment' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'extension/module' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validate ()) {
			if (! isset ( $this->request->get ['module_id'] )) {
				$this->model_extension_module->addModule ( 'blog_comment', $this->request->post );
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
		$data ['text_select'] = $this->language->get ( 'text_select' );
		
		$data ['text_left'] = $this->language->get ( 'text_left' );
		$data ['text_right'] = $this->language->get ( 'text_right' );
		
		$data ['entry_name'] = $this->language->get ( 'entry_name' );
		$data ['entry_title'] = $this->language->get ( 'entry_title' );
		$data ['entry_titleicon'] = $this->language->get ( 'entry_titleicon' );
		$data ['entry_author_photo_size'] = $this->language->get ( 'entry_author_photo_size' );
		$data ['entry_author_photo_position'] = $this->language->get ( 'entry_author_photo_position' );
		$data ['entry_author_photo_display'] = $this->language->get ( 'entry_author_photo_display' );
		$data ['entry_limit'] = $this->language->get ( 'entry_limit' );
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
		
		// Set error message
		if (isset ( $this->error ['name'] )) {
			$data ['error_name'] = $this->error ['name'];
		} else {
			$data ['error_name'] = '';
		}
		
		if (isset ( $this->error ['title_list'] )) {
			$data ['error_title_list'] = $this->error ['title_list'];
		} else {
			$data ['error_title_list'] = '';
		}
		
		if (isset ( $this->error ['title_comment'] )) {
			$data ['error_title_comment'] = $this->error ['title_comment'];
		} else {
			$data ['error_title_comment'] = '';
		}
		
		if (isset ( $this->error ['titleicon_list'] )) {
			$data ['error_titleicon_list'] = $this->error ['titleicon_list'];
		} else {
			$data ['error_titleicon_list'] = '';
		}
		
		if (isset ( $this->error ['titleicon_comment'] )) {
			$data ['error_titleicon_comment'] = $this->error ['titleicon_comment'];
		} else {
			$data ['error_titleicon_comment'] = '';
		}
		
		if (isset ( $this->error ['limit'] )) {
			$data ['error_limit'] = $this->error ['limit'];
		} else {
			$data ['error_limit'] = '';
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
				'href' => $this->url->link ( 'module/blog_comment', 'token=' . $this->session->data ['token'], 'SSL' ) 
		);
		
		if (! isset ( $this->request->get ['module_id'] )) {
			$data ['action'] = $this->url->link ( 'module/blog_comment', 'token=' . $this->session->data ['token'], 'SSL' );
		} else {
			$data ['action'] = $this->url->link ( 'module/blog_comment', 'token=' . $this->session->data ['token'] . '&module_id=' . $this->request->get ['module_id'], 'SSL' );
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
		
		if (isset ( $this->request->post ['title_list'] )) {
			$data ['title_list'] = $this->request->post ['title_list'];
		} elseif (! empty ( $module_info )) {
			$data ['title_list'] = $module_info ['title_list'];
		} else {
			$data ['title_list'] = $this->language->get ( 'title_list' );
		}
		
		if (isset ( $this->request->post ['title_comment'] )) {
			$data ['title_comment'] = $this->request->post ['title_comment'];
		} elseif (! empty ( $module_info )) {
			$data ['title_comment'] = $module_info ['title_comment'];
		} else {
			$data ['title_comment'] = $this->language->get ( 'title_comment' );
		}
		
		if (isset ( $this->request->post ['titleicon_list'] )) {
			$data ['titleicon_list'] = $this->request->post ['titleicon_list'];
		} elseif (! empty ( $module_info )) {
			$data ['titleicon_list'] = $module_info ['titleicon_list'];
		} else {
			$data ['titleicon_list'] = $this->language->get ( 'titleicon_list' );
		}
		
		if (isset ( $this->request->post ['titleicon_comment'] )) {
			$data ['titleicon_comment'] = $this->request->post ['titleicon_comment'];
		} elseif (! empty ( $module_info )) {
			$data ['titleicon_comment'] = $module_info ['titleicon_comment'];
		} else {
			$data ['titleicon_comment'] = $this->language->get ( 'titleicon_comment' );
		}
		
		if (isset ( $this->request->post ['limit'] )) {
			$data ['limit'] = $this->request->post ['limit'];
		} elseif (! empty ( $module_info )) {
			$data ['limit'] = $module_info ['limit'];
		} else {
			$data ['limit'] = $this->language->get ( 'limit' );
		}
		
		if (isset ( $this->request->post ['status'] )) {
			$data ['status'] = $this->request->post ['status'];
		} elseif (! empty ( $module_info )) {
			$data ['status'] = $module_info ['status'];
		} else {
			$data ['status'] = '';
		}
		
		if (isset ( $this->request->post ['author_photo_size'] )) {
			$data ['author_photo_size'] = $this->request->post ['author_photo_size'];
		} elseif (! empty ( $module_info )) {
			$data ['author_photo_size'] = $module_info ['author_photo_size'];
		} else {
			$data ['author_photo_size'] = '80x80';
		}
		
		if (isset ( $this->request->post ['author_photo_position'] )) {
			$data ['author_photo_position'] = $this->request->post ['author_photo_position'];
		} elseif (! empty ( $module_info )) {
			$data ['author_photo_position'] = $module_info ['author_photo_position'];
		} else {
			$data ['author_photo_position'] = $this->language->get ( 'author_photo_position' );
		}
		
		if (isset ( $this->request->post ['author_photo_display'] )) {
			$data ['author_photo_display'] = $this->request->post ['author_photo_display'];
		} elseif (! empty ( $module_info )) {
			$data ['author_photo_display'] = $module_info ['author_photo_display'];
		} else {
			$data ['author_photo_display'] = $this->language->get ( 'author_photo_display' );
		}
		
		if (isset ( $this->request->post ['custom_style_comment'] )) {
			$data ['custom_style_comment'] = $this->request->post ['custom_style_comment'];
		} elseif (! empty ( $module_info )) {
			$data ['custom_style_comment'] = $module_info ['custom_style_comment'];
		} else {
			$data ['custom_style_comment'] = '';
		}
		
		if (isset ( $this->request->post ['custom_script_comment'] )) {
			$data ['custom_script_comment'] = $this->request->post ['custom_script_comment'];
		} elseif (! empty ( $module_info )) {
			$data ['custom_script_comment'] = $module_info ['custom_script_comment'];
		} else {
			$data ['custom_script_comment'] = '';
		}
		
		if (isset ( $this->request->post ['custom_style_commentbox'] )) {
			$data ['custom_style_commentbox'] = $this->request->post ['custom_style_commentbox'];
		} elseif (! empty ( $module_info )) {
			$data ['custom_style_commentbox'] = $module_info ['custom_style_commentbox'];
		} else {
			$data ['custom_style_commentbox'] = '';
		}
		
		if (isset ( $this->request->post ['custom_script_commentbox'] )) {
			$data ['custom_script_commentbox'] = $this->request->post ['custom_script_commentbox'];
		} elseif (! empty ( $module_info )) {
			$data ['custom_script_commentbox'] = $module_info ['custom_script_commentbox'];
		} else {
			$data ['custom_script_commentbox'] = '';
		}
		
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		
		$this->response->setOutput ( $this->load->view ( 'module/blog_comment.tpl', $data ) );
	}
	protected function validate() {
		if (! $this->user->hasPermission ( 'modify', 'module/blog_comment' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		
		if ((utf8_strlen ( $this->request->post ['name'] ) < 3) || (utf8_strlen ( $this->request->post ['name'] ) > 64)) {
			$this->error ['name'] = $this->language->get ( 'error_name' );
		}
		
		return ! $this->error;
	}
}