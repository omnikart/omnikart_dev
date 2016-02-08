<?php
class ControllerExtensionGpGrouped extends Controller {
	private $error = array ();
	public function index() {
		$this->load->language ( 'extension/gp_grouped' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'setting/setting' );
		// $this->model_setting_setting->deleteSetting('gp_grouped');
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validate ()) {
			$this->model_setting_setting->editSetting ( 'gp_grouped', $this->request->post );
			
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			
			$this->response->redirect ( $this->url->link ( 'extension/gp_grouped', 'token=' . $this->session->data ['token'], 'SSL' ) );
		}
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		
		$data ['text_edit'] = $this->language->get ( 'text_edit' );
		$data ['text_enabled'] = $this->language->get ( 'text_enabled' );
		$data ['text_disabled'] = $this->language->get ( 'text_disabled' );
		
		$data ['entry_width'] = $this->language->get ( 'entry_width' );
		$data ['entry_height'] = $this->language->get ( 'entry_height' );
		$data ['entry_image_thumb_size'] = $this->language->get ( 'entry_image_thumb_size' );
		$data ['entry_image_popup_size'] = $this->language->get ( 'entry_image_popup_size' );
		$data ['entry_image_added_size'] = $this->language->get ( 'entry_image_added_size' );
		$data ['entry_image_child_size'] = $this->language->get ( 'entry_image_child_size' );
		$data ['entry_add_child_image'] = $this->language->get ( 'entry_add_child_image' );
		$data ['entry_add_child_images'] = $this->language->get ( 'entry_add_child_images' );
		$data ['entry_add_child_description'] = $this->language->get ( 'entry_add_child_description' );
		$data ['entry_child_info'] = $this->language->get ( 'entry_child_info' );
		$data ['entry_child_attribute'] = $this->language->get ( 'entry_child_attribute' );
		$data ['entry_child_nocart'] = $this->language->get ( 'entry_child_nocart' );
		
		$data ['help_image_child_size'] = $this->language->get ( 'help_image_child_size' );
		$data ['help_add_child_image'] = $this->language->get ( 'help_add_child_image' );
		$data ['help_add_child_images'] = $this->language->get ( 'help_add_child_images' );
		$data ['help_add_child_description'] = $this->language->get ( 'help_add_child_description' );
		$data ['help_child_nocart'] = $this->language->get ( 'help_child_nocart' );
		
		$data ['button_save'] = $this->language->get ( 'button_save' );
		$data ['button_cancel'] = $this->language->get ( 'button_cancel' );
		
		if (isset ( $this->session->data ['success'] )) {
			$data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$data ['success'] = '';
		}
		
		if (isset ( $this->error ['warning'] )) {
			$data ['error_warning'] = $this->error ['warning'];
		} else {
			$data ['error_warning'] = '';
		}
		
		if (isset ( $this->error ['image_popup_width'] )) {
			$data ['error_image_popup_width'] = $this->error ['image_popup_width'];
		} else {
			$data ['error_image_popup_width'] = '';
		}
		
		if (isset ( $this->error ['image_popup_height'] )) {
			$data ['error_image_popup_height'] = $this->error ['image_popup_height'];
		} else {
			$data ['error_image_popup_height'] = '';
		}
		
		if (isset ( $this->error ['image_thumb_width'] )) {
			$data ['error_image_thumb_width'] = $this->error ['image_thumb_width'];
		} else {
			$data ['error_image_thumb_width'] = '';
		}
		
		if (isset ( $this->error ['image_thumb_height'] )) {
			$data ['error_image_thumb_height'] = $this->error ['image_thumb_height'];
		} else {
			$data ['error_image_thumb_height'] = '';
		}
		
		if (isset ( $this->error ['image_added_width'] )) {
			$data ['error_image_added_width'] = $this->error ['image_added_width'];
		} else {
			$data ['error_image_added_width'] = '';
		}
		
		if (isset ( $this->error ['image_added_height'] )) {
			$data ['error_image_added_height'] = $this->error ['image_added_height'];
		} else {
			$data ['error_image_added_height'] = '';
		}
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/dashboard', 'token=' . $this->session->data ['token'], 'SSL' ) 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'heading_title' ),
				'href' => $this->url->link ( 'extension/gp_grouped', 'token=' . $this->session->data ['token'], 'SSL' ) 
		);
		
		$data ['action'] = $this->url->link ( 'extension/gp_grouped', 'token=' . $this->session->data ['token'], 'SSL' );
		$data ['cancel'] = $this->url->link ( 'catalog/product', 'token=' . $this->session->data ['token'], 'SSL' );
		
		// Main product image Popup
		if (isset ( $this->request->post ['gp_grouped_image_popup_width'] )) {
			$data ['gp_grouped_image_popup_width'] = $this->request->post ['gp_grouped_image_popup_width'];
		} else {
			$data ['gp_grouped_image_popup_width'] = $this->config->get ( 'gp_grouped_image_popup_width' );
		}
		
		if (isset ( $this->request->post ['gp_grouped_image_popup_height'] )) {
			$data ['gp_grouped_image_popup_height'] = $this->request->post ['gp_grouped_image_popup_height'];
		} else {
			$data ['gp_grouped_image_popup_height'] = $this->config->get ( 'gp_grouped_image_popup_height' );
		}
		
		// Main product image Thumb
		if (isset ( $this->request->post ['gp_grouped_image_thumb_width'] )) {
			$data ['gp_grouped_image_thumb_width'] = $this->request->post ['gp_grouped_image_thumb_width'];
		} else {
			$data ['gp_grouped_image_thumb_width'] = $this->config->get ( 'gp_grouped_image_thumb_width' );
		}
		
		if (isset ( $this->request->post ['gp_grouped_image_thumb_height'] )) {
			$data ['gp_grouped_image_thumb_height'] = $this->request->post ['gp_grouped_image_thumb_height'];
		} else {
			$data ['gp_grouped_image_thumb_height'] = $this->config->get ( 'gp_grouped_image_thumb_height' );
		}
		
		// Main product image Additional
		if (isset ( $this->request->post ['gp_grouped_image_added_width'] )) {
			$data ['gp_grouped_image_added_width'] = $this->request->post ['gp_grouped_image_added_width'];
		} else {
			$data ['gp_grouped_image_added_width'] = $this->config->get ( 'gp_grouped_image_added_width' );
		}
		
		if (isset ( $this->request->post ['gp_grouped_image_added_height'] )) {
			$data ['gp_grouped_image_added_height'] = $this->request->post ['gp_grouped_image_added_height'];
		} else {
			$data ['gp_grouped_image_added_height'] = $this->config->get ( 'gp_grouped_image_added_height' );
		}
		
		// Child product image
		if (isset ( $this->request->post ['gp_grouped_image_child_width'] )) {
			$data ['gp_grouped_image_child_width'] = $this->request->post ['gp_grouped_image_child_width'];
		} else {
			$data ['gp_grouped_image_child_width'] = $this->config->get ( 'gp_grouped_image_child_width' );
		}
		
		if (isset ( $this->request->post ['gp_grouped_image_child_height'] )) {
			$data ['gp_grouped_image_child_height'] = $this->request->post ['gp_grouped_image_child_height'];
		} else {
			$data ['gp_grouped_image_child_height'] = $this->config->get ( 'gp_grouped_image_child_height' );
		}
		
		// Append Child to Main
		if (isset ( $this->request->post ['gp_grouped_add_child_image'] )) {
			$data ['gp_grouped_add_child_image'] = $this->request->post ['gp_grouped_add_child_image'];
		} else {
			$data ['gp_grouped_add_child_image'] = $this->config->get ( 'gp_grouped_add_child_image' );
		}
		
		if (isset ( $this->request->post ['gp_grouped_add_child_images'] )) {
			$data ['gp_grouped_add_child_images'] = $this->request->post ['gp_grouped_add_child_images'];
		} else {
			$data ['gp_grouped_add_child_images'] = $this->config->get ( 'gp_grouped_add_child_images' );
		}
		
		if (isset ( $this->request->post ['gp_grouped_add_child_description'] )) {
			$data ['gp_grouped_add_child_description'] = $this->request->post ['gp_grouped_add_child_description'];
		} else {
			$data ['gp_grouped_add_child_description'] = $this->config->get ( 'gp_grouped_add_child_description' );
		}
		
		// Display Child Info
		$data ['get_child_infos'] = array (
				'model',
				'sku',
				'upc',
				'ean',
				'jan',
				'isbn',
				'mpn',
				'quantity',
				'stock',
				'manufacturer',
				'points',
				'date_available',
				'weight',
				'length',
				'width',
				'height',
				'date_added',
				'date_modified' 
		);
		// $field_query=$this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."product`");foreach($field_query->rows as $field){$data['get_child_infos'][]=$field['Field'];}
		
		if (isset ( $this->request->post ['gp_grouped_child_info'] )) {
			$data ['gp_grouped_child_info'] = $this->request->post ['gp_grouped_child_info'];
		} elseif ($this->config->get ( 'gp_grouped_child_info' )) {
			$data ['gp_grouped_child_info'] = $this->config->get ( 'gp_grouped_child_info' );
		} else {
			$data ['gp_grouped_child_info'] = array ();
		}
		
		// Display Child Attribute
		if (isset ( $this->request->post ['gp_grouped_child_attribute'] )) {
			$data ['gp_grouped_child_attribute'] = $this->request->post ['gp_grouped_child_attribute'];
		} else {
			$data ['gp_grouped_child_attribute'] = $this->config->get ( 'gp_grouped_child_attribute' );
		}
		
		// Child to Cart
		if (isset ( $this->request->post ['gp_grouped_child_nocart'] )) {
			$data ['gp_grouped_child_nocart'] = $this->request->post ['gp_grouped_child_nocart'];
		} else {
			$data ['gp_grouped_child_nocart'] = $this->config->get ( 'gp_grouped_child_nocart' );
		}
		
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		
		$this->response->setOutput ( $this->load->view ( 'extension/gp_grouped.tpl', $data ) );
	}
	protected function validate() {
		if (! $this->user->hasPermission ( 'modify', 'extension/gp_grouped' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		
		if (! $this->request->post ['gp_grouped_image_popup_width']) {
			$this->error ['image_popup_width'] = ' has-error';
		}
		
		if (! $this->request->post ['gp_grouped_image_popup_height']) {
			$this->error ['image_popup_height'] = ' has-error';
		}
		
		if (! $this->request->post ['gp_grouped_image_thumb_width']) {
			$this->error ['image_thumb_width'] = ' has-error';
		}
		
		if (! $this->request->post ['gp_grouped_image_thumb_height']) {
			$this->error ['image_thumb_height'] = ' has-error';
		}
		
		if (! $this->request->post ['gp_grouped_image_added_width']) {
			$this->error ['image_added_width'] = ' has-error';
		}
		
		if (! $this->request->post ['gp_grouped_image_added_height']) {
			$this->error ['image_added_height'] = ' has-error';
		}
		
		if ($this->error && ! isset ( $this->error ['warning'] )) {
			$this->error ['warning'] = $this->language->get ( 'error_form' );
		}
		
		return ! $this->error;
	}
}