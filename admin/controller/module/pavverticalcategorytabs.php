<?php
class ControllerModulePavverticalcategorytabs extends Controller {

	private $mdata = array();
	private $error = array();

	public function index() {

		$this->mdata['objlang'] = $this->language;
		$this->mdata['objurl'] = $this->url;

		$this->load->language('module/pavverticalcategorytabs');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('extension/extension');
		$this->load->model('extension/module');
		$this->load->model('tool/image');


		if( isset($this->request->get['module_id']) && isset($this->request->get['delete']) ){
			$this->model_extension_module->deleteModule( $this->request->get['module_id'] );
			$this->response->redirect($this->url->link('module/pavverticalcategorytabs', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {


			$action = isset($this->request->post["action"]) ? $this->request->post["action"] : "";
			unset($this->request->post['pavverticalcategorytabs_module']['action']);

		 	$data = array();
			foreach ($this->request->post['pavverticalcategorytabs_module'] as $key => $value) {
		 	 	$data = $value;
		 	 	break;
		 	}


			if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('pavverticalcategorytabs', $data );
			} else {
				$this->model_extension_module->editModule( $this->request->get['module_id'], $data );
			}

			$this->session->data['success'] = $this->language->get('text_success');
			if( isset($this->request->get['module_id']) ) {
				$this->response->redirect($this->url->link('module/pavverticalcategorytabs', 'module_id='.$this->request->get['module_id'].'&token=' . $this->session->data['token'], 'SSL'));
			}else{
				$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		// Get Entry Language
		$this->_entryLanguage();

		// Get message
		$this->_message();

		// Get Breadcrumbs
		$this->_breadcrumbs();


		// Get Category SelectBox
		$this->_getCategories();

		if (isset($this->error['warning'])) {
			$this->mdata['error_warning'] = $this->error['warning'];
		} else {
			$this->mdata['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->mdata['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->mdata['success'] = '';
		}

		if (isset($this->error['dimension'])) {
			$this->mdata['error_dimension'] = $this->error['dimension'];
		} else {
			$this->mdata['error_dimension'] = array();
		}
		$this->mdata['entry_tabs'] = $this->language->get('entry_tabs');

		$this->_breadcrumbs();

		$this->mdata['action'] = $this->url->link('module/pavverticalcategorytabs', 'token=' . $this->session->data['token'], 'SSL');

		$this->mdata['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->mdata['modules'] = array();


		// Get Setting Status
		if (isset($this->request->post['pavverticalcategorytabs_status'])) {
			$this->mdata['pavverticalcategorytabs_status'] = $this->request->post['pavverticalcategorytabs_status'];
		} else {
			$this->mdata['pavverticalcategorytabs_status'] = $this->config->get('pavverticalcategorytabs_status');
		}

		$default = array(
			'image' => '',
			'description' => array(),
			'block_width' => 6,
			'column_class' => '',
			'category_id' => '',
			'banner_position' => 1,
			'limit_tabs' => 8,
			'width'		=> 202,
			'height'	=> 168,
			'itemsperpage'=> 3,
			'cols'	=> 3,
			'limit' => 9,
			'tab_position'	=> 1,
			'name'	=> ''
		);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
			$module_info = array_merge( $default, $module_info );
			$this->mdata['selectedid'] = $this->request->get['module_id'];


			$this->mdata['subheading'] = $this->language->get('text_edit_module') . $module_info['name'];
			$this->mdata['action'] = $this->url->link('module/pavverticalcategorytabs', 'module_id='.$this->request->get['module_id'].'&token=' . $this->session->data['token'], 'SSL');
		}else {
			$module_info = $default;
			$this->mdata['selectedid'] = 0;
			$this->mdata['subheading'] = $this->language->get('text_create_new_module');
			$this->mdata['action'] = $this->url->link('module/pavverticalcategorytabs', 'token=' . $this->session->data['token'], 'SSL');
		}

		$modules = array( 0=> $module_info );

		$moduletabs = $this->model_extension_module->getModulesByCode( 'pavverticalcategorytabs' );
	 	$this->mdata['link'] = $this->url->link('module/pavverticalcategorytabs', 'token=' . $this->session->data['token'] . '', 'SSL');
 		//$this->mdata['modules'] = $modules;


 		$this->mdata['moduletabs'] = $moduletabs;


 		$this->mdata['olang']  = $this->language;



		$this->mdata['toolimg'] = $this->model_tool_image;
		$this->mdata['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		foreach ($modules as &$module) {
			$module['thumb'] = $this->model_tool_image->resize($module['image'], 120, 120);
			$module['products'] = $this->_getProductSetting($module);
		}

		$this->mdata['modules'] = $modules;

		// Set template
		$template = 'module/pavverticalcategorytabs.tpl';

		// Render
		$this->mdata['header'] = $this->load->controller('common/header');
		$this->mdata['column_left'] = $this->load->controller('common/column_left');
		$this->mdata['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($template, $this->mdata));
	}

	public function _d($var){
		echo "<pre>"; print_r($var); die;
	}

	public function _getCategories(){
		$this->load->model('catalog/category');
		if( VERSION == '1.5.4' ){
			$categories = $this->model_catalog_category->getCategories(0);
		}else {
			$categories = $this->model_catalog_category->getCategories( array('limit' => 999999999, 'start'=>0 ) );
		}
		$this->mdata['categories'] = $categories;
	}

	public function _getProductSetting($module){
		$this->load->model('catalog/product');
		$setting = array();
		// Get List Product
		if (isset($module['featured_product'])) {
			$products = $module['featured_product'];
		} else {
			$products = array();
		}
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			if ($product_info) {
				$setting[] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				);
			}
		}
		return $setting;
	}

	public function _message(){
 		if (isset($this->error['warning'])) {
			$this->mdata['error_warning'] = $this->error['warning'];
		} else {
			$this->mdata['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->mdata['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->mdata['success'] = '';
		}

		if (isset($this->error['dimension'])) {
			$this->mdata['error_dimension'] = $this->error['dimension'];
		} else {
			$this->mdata['error_dimension'] = array();
		}
	}

	public function _entryLanguage(){

		$this->mdata['heading_title'] = $this->language->get('heading_title');
		$this->mdata['text_enabled'] = $this->language->get('text_enabled');
		$this->mdata['text_disabled'] = $this->language->get('text_disabled');
		$this->mdata['text_content_top'] = $this->language->get('text_content_top');
		$this->mdata['text_content_bottom'] = $this->language->get('text_content_bottom');
		$this->mdata['text_column_left'] = $this->language->get('text_column_left');
		$this->mdata['text_column_right'] = $this->language->get('text_column_right');
		$this->mdata['text_left'] = $this->language->get('text_left');
		$this->mdata['text_right'] = $this->language->get('text_right');
		$this->mdata['banner_position'] = $this->language->get('banner_position');
		$this->mdata['tab_position'] = $this->language->get('tab_position');
		$this->mdata['entry_description'] = $this->language->get('entry_description');
		$this->mdata['entry_tabs'] = $this->language->get('entry_tabs');
		$this->mdata['limit_tabs'] = $this->language->get('label_limit_tabs');

		$this->mdata['entry_banner'] = $this->language->get('entry_banner');
		$this->mdata['entry_dimension'] = $this->language->get('entry_dimension');
		$this->mdata['entry_carousel'] = $this->language->get('entry_carousel');

		$this->mdata['entry_product'] = $this->language->get('entry_product');
		$this->mdata['entry_help_product'] = $this->language->get('entry_help_product');
		$this->mdata['entry_title_product'] = $this->language->get('entry_title_product');
		$this->mdata['entry_help_title_product'] = $this->language->get('entry_help_title_product');

		$this->mdata['entry_prefix_product'] = $this->language->get('entry_prefix_product');
		$this->mdata['entry_help_prefix_product'] = $this->language->get('entry_help_prefix_product');

		$this->mdata['entry_status_column'] = $this->language->get('entry_status_column');
		$this->mdata['entry_help_status_column'] = $this->language->get('entry_help_status_column');

		$this->mdata['entry_layout'] = $this->language->get('entry_layout');
		$this->mdata['entry_position'] = $this->language->get('entry_position');
		$this->mdata['entry_status'] = $this->language->get('entry_status');
		$this->mdata['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->mdata['entry_limit'] = $this->language->get('entry_limit');

		$this->mdata['button_save'] = $this->language->get('button_save');
		$this->mdata['button_cancel'] = $this->language->get('button_cancel');
		$this->mdata['button_add_module'] = $this->language->get('button_add_module');
		$this->mdata['button_remove'] = $this->language->get('button_remove');
		$this->mdata['status_nav'] = $this->language->get('label_status_nav');
		$this->mdata['tab_module'] = $this->language->get('tab_module');

		$this->mdata['no_image'] = '';
		$this->mdata['text_browse'] = $this->language->get('text_browse');
		$this->mdata['text_image_manager'] = $this->language->get('text_image_manager');
 		$this->mdata['text_browse'] = $this->language->get('text_browse');
		$this->mdata['text_clear'] = $this->language->get('text_clear');
		$this->mdata['no_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$this->load->model('localisation/language');
		$this->mdata['tab_module'] = $this->language->get('tab_module');
		$this->mdata['languages'] = $this->model_localisation_language->getLanguages();

		$this->mdata['action'] = $this->url->link('module/pavverticalcategorytabs', 'token=' . $this->session->data['token'], 'SSL');
		$this->mdata['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->mdata['token'] = $this->session->data['token'];
	}

	public function _breadcrumbs(){
		$this->mdata['breadcrumbs'] = array();

   		$this->mdata['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->mdata['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->mdata['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/pavverticalcategorytabs', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/pavverticalcategorytabs')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (isset($this->request->post['pavverticalcategorytabs_module'])) {
			foreach ($this->request->post['pavverticalcategorytabs_module'] as $key => $value) {

				if( (isset($value['width'])) && (isset($value['height'])) ){
					if (!$value['width'] || !$value['height']) {
						$this->error['dimension'][$key] = $this->language->get('error_dimension');
					}
				}

				if(isset($value['limit'])){
					if (!$value['limit'] || !$value['cols'] || !$value['itemsperpage'] ) {
						$this->error['dimension'][$key] = $this->language->get('error_carousel');
					}
				}
			}
		}
		return !$this->error;
	}
}
?>
