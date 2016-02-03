<?php
/*
 * Author: minhdqa
 * Mail: minhdqa@gmail.com
 */
class ControllerModuleComboProducts extends Controller {
	private $error = array ();
	public function install() {
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "combo_products` (
          `combo_id` int(11) NOT NULL,
          `product_id` int(11) NOT NULL
        )" );
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "combo_setting` (
          `combo_id` int(11) NOT NULL AUTO_INCREMENT,
		  `combo_name` text NOT NULL,
          `product_id` text NOT NULL,
		  `discount_type` text NOT NULL,
		  `discount_number` int(11) NOT NULL,
          `display_detail` int(11) NOT NULL,
		  `priority` int(3) NOT NULL DEFAULT 0,
		  `status` int(3) NOT NULL DEFAULT 1,
		  `override` int(11) NOT NULL,
          PRIMARY KEY (`combo_id`)
        )" );
		$this->db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "combo_category` (
          `combo_id` int(11) NOT NULL,
          `category_id` int(11) NOT NULL
        )" );
		if (version_compare ( VERSION, '2.0.0.0', '=' )) {
			$code = 'group';
		} else {
			$code = 'code';
		}
		$this->db->query ( "INSERT INTO " . DB_PREFIX . "extension SET type = 'total', code = 'combo_products'" );
		$this->db->query ( "INSERT INTO " . DB_PREFIX . "setting SET `" . $code . "` = 'combo_products', `key` = 'combo_products_status', `value` = '1', `serialized` = '0'" );
		$this->db->query ( "INSERT INTO " . DB_PREFIX . "setting SET `" . $code . "` = 'combo_products', `key` = 'comboproducts_status', `value` = '1', `serialized` = '0'" );
		$this->db->query ( "INSERT INTO " . DB_PREFIX . "setting SET `" . $code . "` = 'combo_products', `key` = 'combo_products_sort_order', `value` = '8', `serialized` = '0'" );
	}
	public function uninstall() {
		if (version_compare ( VERSION, '2.0.0.0', '=' )) {
			$code = 'group';
		} else {
			$code = 'code';
		}
		$this->db->query ( "DROP TABLE `" . DB_PREFIX . "combo_products`" );
		$this->db->query ( "DROP TABLE `" . DB_PREFIX . "combo_setting`" );
		$this->db->query ( "DROP TABLE `" . DB_PREFIX . "combo_category`" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "extension WHERE code = 'combo_products'" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "setting WHERE `" . $code . "` = 'combo_products'" );
	}
	public function index() {
		$this->language->load ( 'module/comboproducts' );
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		$this->load->model ( 'module/comboproducts' );
		$this->getList ();
	}
	public function add() {
		$this->load->language ( 'module/comboproducts' );
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		$this->load->model ( 'module/comboproducts' );
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm ()) {
			$this->model_module_comboproducts->addCombo ( $this->request->post );
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			$this->response->redirect ( $this->url->link ( 'module/comboproducts', 'token=' . $this->session->data ['token'], 'SSL' ) );
		}
		
		$this->getForm ();
	}
	public function edit() {
		$this->load->language ( 'module/comboproducts' );
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		$this->load->model ( 'module/comboproducts' );
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm ()) {
			$this->model_module_comboproducts->editCombo ( $this->request->get ['combo_id'], $this->request->post );
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			$this->response->redirect ( $this->url->link ( 'module/comboproducts', 'token=' . $this->session->data ['token'], 'SSL' ) );
		}
		
		$this->getForm ();
	}
	public function switching() {
		$this->load->language ( 'module/comboproducts' );
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		$this->load->model ( 'module/comboproducts' );
		
		$combo_id = $this->request->get ['combo_id'];
		$this->model_module_comboproducts->updateStatus ( $combo_id );
		$this->session->data ['success'] = $this->language->get ( 'text_success' );
		$this->response->redirect ( $this->url->link ( 'module/comboproducts', 'token=' . $this->session->data ['token'], 'SSL' ) );
	}
	public function delete() {
		$this->load->language ( 'module/comboproducts' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'module/comboproducts' );
		
		if (isset ( $this->request->post ['selected'] ) && $this->validateDelete ()) {
			foreach ( $this->request->post ['selected'] as $combo_id ) {
				$this->model_module_comboproducts->deletecombo ( $combo_id );
			}
			
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			
			$this->response->redirect ( $this->url->link ( 'module/comboproducts', 'token=' . $this->session->data ['token'], 'SSL' ) );
		}
		
		$this->getList ();
	}
	protected function getList() {
		$this->load->model ( 'catalog/product' );
		$this->load->model ( 'catalog/category' );
		$this->load->model ( 'setting/setting' );
		
		$texts = array (
				'heading_title',
				'edit_title',
				'text_enabled',
				'text_disabled',
				'text_status',
				'text_confirm',
				'text_detail_page',
				'text_no_results',
				'text_next_version',
				'text_warning',
				'entry_combo_name',
				'entry_product_name',
				'entry_discount',
				'entry_display_position',
				'entry_category',
				'entry_priority',
				'button_insert',
				'button_delete',
				'button_edit',
				'button_switch',
				'button_combo_add',
				'button_combo_remove',
				'error_permission' 
		);
		foreach ( $texts as $text ) {
			$data [$text] = $this->language->get ( $text );
		}
		
		if (isset ( $this->error ['warning'] )) {
			$data ['error_warning'] = $this->error ['warning'];
		} else {
			$data ['error_warning'] = '';
		}
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home', 'token=' . $this->session->data ['token'], 'SSL' ),
				'separator' => false 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_module' ),
				'href' => $this->url->link ( 'extension/module', 'token=' . $this->session->data ['token'], 'SSL' ),
				'separator' => ' :: ' 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'heading_title' ),
				'href' => $this->url->link ( 'module/comboproducts', 'token=' . $this->session->data ['token'], 'SSL' ),
				'separator' => ' :: ' 
		);
		
		$url = '';
		$data ['insert'] = $this->url->link ( 'module/comboproducts/add', 'token=' . $this->session->data ['token'], 'SSL' );
		$data ['delete'] = $this->url->link ( 'module/comboproducts/delete', 'token=' . $this->session->data ['token'], 'SSL' );
		
		$data ['combos'] = array ();
		$results = $this->model_module_comboproducts->getCombos ();
		
		foreach ( $results as $result ) {
			
			$product_name = array ();
			$products_in_combo = $this->model_module_comboproducts->getProducts ( $result ['combo_id'] );
			
			foreach ( $products_in_combo as $product_id ) {
				$product_info = $this->model_catalog_product->getProductDescriptions ( $product_id );
				$product_name [] = $product_info [$this->config->get ( 'config_language_id' )] ['name'];
			}
			
			$category_name = array ();
			$category = $this->model_module_comboproducts->getCategory ( $result ['combo_id'] );
			
			foreach ( $category as $category_id ) {
				$category_info = $this->model_catalog_category->getCategoryDescriptions ( $category_id );
				$category_name [] = $category_info [$this->config->get ( 'config_language_id' )] ['name'];
			}
			
			if ($result ['status'] == 1) {
				$status = 'ban';
				$title = 'Enabled';
			} else {
				$status = 'check-circle';
				$title = 'Disabled';
			}
			
			$data ['combos'] [] = array (
					'combo_id' => $result ['combo_id'],
					'combo_name' => $result ['combo_name'],
					'products' => $product_name,
					'discount_type' => $result ['discount_type'],
					'discount_number' => $result ['discount_number'],
					'display_detail' => $result ['display_detail'],
					'category_list' => $category_name,
					'priority' => $result ['priority'],
					'warning' => sprintf ( $this->language->get ( 'text_warning' ), $this->url->link ( 'design/layout', 'token=' . $this->session->data ['token'], 'SSL' ) ),
					'edit' => $this->url->link ( 'module/comboproducts/edit', 'token=' . $this->session->data ['token'] . '&combo_id=' . $result ['combo_id'] . $url, 'SSL' ),
					'status' => $status,
					'title' => $title,
					'switch' => $this->url->link ( 'module/comboproducts/switching', 'token=' . $this->session->data ['token'] . '&combo_id=' . $result ['combo_id'] . $url, 'SSL' ) 
			);
		}
		
		if (isset ( $this->request->post ['selected'] )) {
			$data ['selected'] = ( array ) $this->request->post ['selected'];
		} else {
			$data ['selected'] = array ();
		}
		
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		
		$this->response->setOutput ( $this->load->view ( 'module/comboproducts_list.tpl', $data ) );
	}
	protected function getForm() {
		$this->load->model ( 'catalog/category' );
		$this->load->model ( 'catalog/product' );
		$this->load->model ( 'tool/image' );
		
		$texts = array (
				'heading_title',
				'add_title',
				'text_fixed',
				'text_percent',
				'text_discount_type',
				'text_discount_numb',
				'text_detail_page',
				'text_override',
				'text_success',
				'text_next_version',
				'button_save',
				'button_cancel',
				'entry_combo_name',
				'entry_product_name',
				'entry_discount',
				'entry_display_position',
				'entry_category',
				'entry_priority' 
		);
		foreach ( $texts as $text ) {
			$data [$text] = $this->language->get ( $text );
		}
		
		if (isset ( $this->error ['warning'] )) {
			$data ['error_warning'] = $this->error ['warning'];
		} else {
			$data ['error_warning'] = '';
		}
		
		if (isset ( $this->error ['combo_name'] )) {
			$data ['error_combo_name'] = $this->error ['combo_name'];
		} else {
			$data ['error_combo_name'] = '';
		}
		
		if (isset ( $this->error ['combo_products'] )) {
			$data ['error_combo_products'] = $this->error ['combo_products'];
		} else {
			$data ['error_combo_products'] = '';
		}
		
		if (isset ( $this->error ['discount_number'] )) {
			$data ['error_discount_number'] = $this->error ['discount_number'];
		} else {
			$data ['error_discount_number'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$data ['success'] = $this->session->data ['success'];
			unset ( $this->session->data ['success'] );
		} else {
			$data ['success'] = '';
		}
		
		if (isset ( $this->request->get ['combo_id'] )) {
			$get_combo = $this->model_module_comboproducts->getCombo ( $this->request->get ['combo_id'] );
			$get_products = $this->model_module_comboproducts->getProducts ( $this->request->get ['combo_id'] );
			$get_category = $this->model_module_comboproducts->getCategory ( $this->request->get ['combo_id'] );
		}
		
		$url = '';
		
		$data ['token'] = $this->session->data ['token'];
		
		if (! isset ( $this->request->get ['combo_id'] )) {
			$data ['action'] = $this->url->link ( 'module/comboproducts/add', 'token=' . $this->session->data ['token'] . $url, 'SSL' );
		} else {
			$data ['action'] = $this->url->link ( 'module/comboproducts/edit', 'token=' . $this->session->data ['token'] . '&combo_id=' . $this->request->get ['combo_id'] . $url, 'SSL' );
		}
		
		$data ['cancel'] = $this->url->link ( 'module/comboproducts', 'token=' . $this->session->data ['token'] . $url, 'SSL' );
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home', 'token=' . $this->session->data ['token'], 'SSL' ),
				'separator' => false 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_module' ),
				'href' => $this->url->link ( 'extension/module', 'token=' . $this->session->data ['token'], 'SSL' ),
				'separator' => ' :: ' 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'heading_title' ),
				'href' => $this->url->link ( 'module/comboproducts', 'token=' . $this->session->data ['token'], 'SSL' ),
				'separator' => ' :: ' 
		);
		
		if (isset ( $get_combo )) {
			$combo_name = $get_combo ['combo_name'];
			
			$combo_products = array ();
			foreach ( $get_products as $product_id ) {
				$product_info = $this->model_catalog_product->getProductDescriptions ( $product_id );
				$product_info2 = $this->model_catalog_product->getProduct ( $product_id );
				if ($product_info2 ['image'])
					$product_image = $this->model_tool_image->resize ( $product_info2 ['image'], 40, 40 );
				else
					$product_image = $this->model_tool_image->resize ( 'no_image.png', 40, 40 );
				$combo_products [] = array (
						'product_name' => $product_info [$this->config->get ( 'config_language_id' )] ['name'],
						'product_image' => $product_image,
						'product_price' => $product_info2 ['price'],
						'product_model' => $product_info2 ['model'],
						'product_id' => $product_id 
				);
			}
			
			$discount_type = $get_combo ['discount_type'];
			$discount_number = $get_combo ['discount_number'];
			$display_detail = $get_combo ['display_detail'];
			$override = $get_combo ['override'];
			$combo_category = $get_category;
			$priority = $get_combo ['priority'];
		} else {
			$combo_name = '';
			$combo_products = array ();
			$discount_type = 'fixed amount';
			$discount_number = '';
			$display_detail = '';
			$override = '';
			$combo_category = array ();
			$priority = '';
		}
		
		// category_list
		$filter_data = array (
				'sort' => 'name',
				'order' => 'ASC',
				'start' => '',
				'limit' => 9999 
		);
		
		$categories = $this->model_catalog_category->getCategories ( $filter_data );
		$category_list = array ();
		
		foreach ( $categories as $category ) {
			$category_list [$category ['category_id']] = $category ['name'];
		}
		
		$data ['combo'] = array (
				'combo_name' => $combo_name,
				'combo_products' => $combo_products,
				'discount_type' => $discount_type,
				'discount_number' => $discount_number,
				'display_detail' => $display_detail,
				'combo_category' => $combo_category,
				'category_list' => $category_list,
				'priority' => $priority,
				'override' => $override 
		);
		
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		
		$this->response->setOutput ( $this->load->view ( 'module/comboproducts_form.tpl', $data ) );
	}
	protected function validate() {
		if (! $this->user->hasPermission ( 'modify', 'module/comboproducts' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	protected function validateDelete() {
		if (! $this->user->hasPermission ( 'modify', 'module/comboproducts' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		
		return ! $this->error;
	}
	protected function validateForm() {
		if (! $this->user->hasPermission ( 'modify', 'module/comboproducts' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		
		if (utf8_strlen ( $this->request->post ['combo_name'] ) < 1) {
			$this->error ['combo_name'] = $this->language->get ( 'error_combo_name' );
		}
		
		if (! isset ( $this->request->post ['combo_products'] )) {
			$this->error ['combo_products'] = $this->language->get ( 'error_combo_products' );
		}
		
		if (! isset ( $this->request->post ['discount_number'] )) {
			$this->error ['discount_number'] = $this->language->get ( 'error_discount_number' );
		} elseif (! is_numeric ( $this->request->post ['discount_number'] )) {
			$this->error ['discount_number'] = $this->language->get ( 'error_discount_number' );
		}
		
		if ($this->error && ! isset ( $this->error ['warning'] )) {
			$this->error ['warning'] = $this->language->get ( 'error_warning' );
		}
		
		return ! $this->error;
	}
}
?>