<?php
class ControllerAccountCustomerpartnerExcelport extends Controller {
	private $error = array ();
	public function __construct($registry) {
		$registry->set ( 'db', new ExcelPortDB ( $registry->get ( 'db' ) ) );
		parent::__construct ( $registry );
	}
	public function index() {
		$data = array ();
		
		$this->language->load ( 'module/excelport' );
		
		$this->load->model ( 'module/excelport' );
		$this->load->model ( 'setting/store' );
		$this->load->model ( 'setting/setting' );
		$this->load->model ( 'localisation/language' );
		
		$this->model_module_excelport->openstock_integrate ();
		
		$this->response->addHeader ( 'Cache-Control: no-cache, no-store' );
		
		$this->model_module_excelport->ini_settings ();
		
		$this->document->addStyle ( 'view/stylesheet/excelport.css' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		if ($this->request->server ['REQUEST_METHOD'] == 'POST' && $this->validate ()) {
			
			$submitAction = empty ( $this->request->get ['submitAction'] ) ? null : $this->request->get ['submitAction'];
			
			if (empty ( $submitAction )) {
				$this->session->data ['excelport_success'] [] = $this->language->get ( 'text_success' );
			}
			
			try {
				switch ($submitAction) {
					case 'export' :
						{
							unset ( $this->session->data ['generated_files'] );
							unset ( $this->session->data ['generated_file'] );
							$this->session->data ['generated_files'] = array ();
							$this->model_module_excelport->deleteProgress ();
							$this->session->data ['ajaxgenerate'] = true;
							$this->model_module_excelport->cleanTemp ( IMODULE_ROOT . IMODULE_TEMP_FOLDER );
						}
						break;
					case 'import' :
						{
							$this->model_module_excelport->deleteProgress ();
							$this->session->data ['ajaximport'] = true;
							
							$uploadedFile = $this->model_module_excelport->getStandardFile ( $this->request->files ['ExcelPort'], 'Import', 'File' );
							
							$this->session->data ['uploaded_files'] = $this->model_module_excelport->prepareUploadedFile ( $uploadedFile );
							
							if (! empty ( $this->session->data ['uploaded_files'] ) && ! empty ( $this->request->post ['ExcelPort'] ['Import'] ['Delete'] )) {
								if ($this->request->post ['ExcelPort'] ['Import'] ['DataType'] == 'Products') {
									$this->load->model ( 'module/excelport_product' );
									$this->model_module_excelport_product->deleteProducts ();
								} else if ($this->request->post ['ExcelPort'] ['Import'] ['DataType'] == 'Categories') {
									$this->load->model ( 'module/excelport_category' );
									$this->model_module_excelport_category->deleteCategories ();
								} else if ($this->request->post ['ExcelPort'] ['Import'] ['DataType'] == 'Options') {
									$this->load->model ( 'module/excelport_option' );
									$this->model_module_excelport_option->deleteOptions ();
								} else if ($this->request->post ['ExcelPort'] ['Import'] ['DataType'] == 'Attributes') {
									$this->load->model ( 'module/excelport_attribute' );
									$this->model_module_excelport_attribute->deleteAttributes ();
									$this->model_module_excelport_attribute->deleteAttributeGroups ();
								} else if ($this->request->post ['ExcelPort'] ['Import'] ['DataType'] == 'Customers') {
									$this->load->model ( 'module/excelport_customer' );
									$this->model_module_excelport_customer->deleteCustomers ();
								} else if ($this->request->post ['ExcelPort'] ['Import'] ['DataType'] == 'CustomerGroups') {
									$this->load->model ( 'module/excelport_customer_group' );
									$this->model_module_excelport_customer_group->deleteCustomerGroups ();
								} else if ($this->request->post ['ExcelPort'] ['Import'] ['DataType'] == 'Orders') {
									$this->load->model ( 'module/excelport_order' );
									$this->model_module_excelport_order->deleteOrders ();
								}
							}
						}
						break;
				}
			} catch ( Exception $e ) {
				$this->session->data ['excelport_error'] [] = $e->getMessage ();
			}
			
			$selectedTab = (empty ( $this->request->post ['selectedTab'] )) ? 0 : $this->request->post ['selectedTab'];
			
			$this->response->redirect ( $this->url->link ( 'module/excelport', 'token=' . $this->session->data ['token'] . '&tab=' . $selectedTab, 'SSL' ) );
		}
		
		// Set language data
		$variables = array (
				'heading_title',
				'text_enabled',
				'text_disabled',
				'text_content_top',
				'text_content_bottom',
				'text_column_left',
				'text_column_right',
				'text_activate',
				'text_not_activated',
				'text_click_activate',
				'entry_code',
				'button_save',
				'button_cancel',
				'entry_layouts_active',
				'text_question_data',
				'text_datatype_option_products',
				'text_question_store',
				'text_question_language',
				'button_export',
				'text_note',
				'text_learn_to_increase',
				'button_import',
				'text_question_data_import',
				'text_question_store_import',
				'text_question_language_import',
				'text_question_file_import',
				'text_file_generating',
				'text_file_downloading',
				'text_import_done',
				'text_preparing_data',
				'text_export_entries_number',
				'text_import_limit',
				'text_confirm_delete_other',
				'text_question_delete_other',
				'text_question_type_export',
				'text_question_add_as_new',
				'text_datatype_option_categories',
				'text_datatype_option_attributes',
				'text_toggle_filter',
				'button_add_condition',
				'button_discard_condition',
				'text_conjunction',
				'text_the_value',
				'help_conjunction',
				'text_datatype_option_customers',
				'text_datatype_option_customer_groups',
				'text_datatype_option_options',
				'text_datatype_option_orders' 
		);
		
		foreach ( $variables as $variable )
			$data [$variable] = $this->language->get ( $variable );
		
		$data ['error_warning'] = '';
		$data ['success_message'] = '';
		
		if (! empty ( $this->session->data ['excelport_success'] )) {
			$data ['success_message'] = implode ( '<br />', $this->session->data ['excelport_success'] );
			unset ( $this->session->data ['excelport_success'] );
		}
		
		if (! empty ( $this->session->data ['excelport_error'] )) {
			$this->error = array_merge ( $this->error, $this->session->data ['excelport_error'] );
			unset ( $this->session->data ['excelport_error'] );
		}
		
		if (! empty ( $this->error )) {
			$data ['error_warning'] = implode ( '<br />', $this->error );
		}
		
		$data ['text_supported_in_oc1541'] = sprintf ( $this->language->get ( 'text_supported_in_oc1541' ), IMODULE_UPMOST_VERSION );
		$data ['default_store_name'] = $this->config->get ( 'config_name' ) . $this->language->get ( 'text_default' );
		
		$data ['progress_name'] = $this->model_module_excelport->get_progress_name ();
		
		$data ['stores'] = array_values ( $this->model_setting_store->getStores () );
		
		$data ['languages'] = array_values ( $this->model_localisation_language->getLanguages () );
		
		$data ['breadcrumbs'] = array (
				array (
						'text' => $this->language->get ( 'text_home' ),
						'href' => $this->url->link ( 'common/home', 'token=' . $this->session->data ['token'], 'SSL' ) 
				),
				array (
						'text' => $this->language->get ( 'text_module' ),
						'href' => $this->url->link ( 'extension/module', 'token=' . $this->session->data ['token'], 'SSL' ) 
				),
				array (
						'text' => $this->language->get ( 'heading_title' ),
						'href' => $this->url->link ( 'module/excelport', 'token=' . $this->session->data ['token'], 'SSL' ) 
				) 
		);
		
		$data ['action'] = $this->url->link ( 'module/excelport', 'token=' . $this->session->data ['token'], 'SSL' );
		$data ['cancel'] = $this->url->link ( 'extension/module', 'token=' . $this->session->data ['token'], 'SSL' );
		
		$data ['https_server'] = dirname ( HTTPS_SERVER );
		$data ['http_server'] = dirname ( HTTP_SERVER );
		
		$data ['ajaxgenerate'] = empty ( $this->session->data ['ajaxgenerate'] ) ? 'false' : $this->session->data ['ajaxgenerate'];
		unset ( $this->session->data ['ajaxgenerate'] );
		
		$data ['ajaximport'] = empty ( $this->session->data ['ajaximport'] ) ? 'false' : $this->session->data ['ajaximport'];
		unset ( $this->session->data ['ajaximport'] );
		
		if (isset ( $this->request->post ['ExcelPort'] )) {
			foreach ( $this->request->post ['ExcelPort'] as $key => $value ) {
				$data ['data'] ['ExcelPort'] [$key] = $this->request->post ['ExcelPort'] [$key];
			}
		} else {
			$configValue = $this->model_setting_setting->getSetting ( 'ExcelPort' );
			$data ['data'] = $configValue;
		}
		
		$data ['conditions'] = $this->model_module_excelport->conditions;
		$data ['operations'] = $this->model_module_excelport->operations;
		$data ['tabs'] = $this->model_module_excelport->getTabs ();
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		
		$this->response->setOutput ( $this->load->view ( 'module/excelport.tpl', $data ) );
	}
	public function ajaxgenerate() {
		header ( 'Cache-Control: no-cache, no-store' );
		$this->session->data ['start_time'] = time ();
		ini_set ( 'memory_limit', '1024M' );
		ini_set ( 'max_execution_time', 900 );
		ini_set ( 'display_errors', 1 );
		ini_set ( 'error_reporting', E_ALL );
		$this->load->model ( 'module/excelport' );
		$error = false;
		// $this->model_module_excelport->deleteProgress();
		
		set_error_handler ( create_function ( '$severity, $message, $file, $line', 'throw new Exception($message . " in file " . $file . " on line " . $line);' ) );
		
		try {
			$this->session->data ['success'] = array ();
			
			if (! is_dir ( IMODULE_ROOT . IMODULE_TEMP_FOLDER . '/' . $this->customer->getId () )) {
				mkdir ( IMODULE_ROOT . IMODULE_TEMP_FOLDER . '/' . $this->customer->getId (), 0755 );
			}
			
			if ($this->model_module_excelport->exportXLS ( 'Products', '1', '0', IMODULE_ROOT . IMODULE_TEMP_FOLDER . '/' . $this->customer->getId (), array (
					'ExportLimit' => 500,
					'ImportLimit' => 800 
			), false, false, array () )) {
				// $this->session->data['success'][] = 'Success'; // TODO - AJAX
			} else {
				// $this->session->data['error_warning'][] = 'I\'m a Failure :(';
			}
		} catch ( Exception $e ) {
			$error = $e->getMessage ();
		}
		
		$this->download ();
	}
	public function ajaximport() {
		header ( 'Cache-Control: no-cache, no-store' );
		$this->session->data ['start_time'] = time ();
		ini_set ( 'memory_limit', '1024M' );
		ini_set ( 'max_execution_time', 900 );
		ini_set ( 'display_errors', 1 );
		ini_set ( 'error_reporting', E_ALL );
		$this->load->model ( 'module/excelport' );
		$this->model_module_excelport->deleteProgress ();
		
		$error = false;
		
		if (! is_dir ( IMODULE_ROOT . IMODULE_TEMP_FOLDER . '/' . $this->customer->getId () )) {
			mkdir ( IMODULE_ROOT . IMODULE_TEMP_FOLDER . '/' . $this->customer->getId (), 0755 );
		}
		
		$uploadedFile = $this->model_module_excelport->getStandardFile ( $this->request->files ['file'] );
		$this->session->data ['uploaded_files'] = $this->model_module_excelport->prepareUploadedFile ( $uploadedFile );
		
		if (! empty ( $this->session->data ['uploaded_files'] )) {
			$file = $this->session->data ['uploaded_files'] [0];
			
			set_error_handler ( create_function ( '$severity, $message, $file, $line', 'throw new Exception($message . " in file " . $file . " on line " . $line);' ) );
			
			try {
				$this->session->data ['success'] = array ();
				
				$this->model_module_excelport->importXLS ( 'Products', '1', $file, array (
						'ExportLimit' => 500,
						'ImportLimit' => 800 
				), false );
			} catch ( Exception $e ) {
				$error = $e->getMessage ();
			}
			
			restore_error_handler ();
			$progress = 1;
		} else {
			$this->language->load ( 'module/excelport' );
			$progress = $this->model_module_excelport->getProgress ();
			$progress ['finishedImport'] = true;
			$this->model_module_excelport->setProgress ( $progress );
		}
		
		$this->model_module_excelport->cleanTemp ();
		
		echo json_encode ( $progress );
		exit ();
	}
	public function cleantemp() {
		$this->load->model ( 'module/excelport' );
		$this->model_module_excelport->cleanTemp ();
	}
	public function download() {
		header ( 'Cache-Control: no-cache, no-store' );
		$files = $this->session->data ['generated_files'];
		$this->load->model ( 'module/excelport' );
		
		if (! empty ( $files )) {
			$this->load->model ( 'localisation/language' );
			
			$name = 'excelport_' . str_replace ( '/', '_', substr ( DIR_APPLICATION, 7, strlen ( DIR_APPLICATION ) - 7 ) ) . '_' . date ( "Y-m-d_H-i-s" ) . ".zip";
			
			$file = $this->model_module_excelport->createZip ( $files, IMODULE_ROOT . IMODULE_TEMP_FOLDER . '/' . $this->customer->getId () . '/' . $name, true, IMODULE_ROOT . IMODULE_TEMP_FOLDER . '/' . $this->customer->getId () . '/' );
			
			if (file_exists ( $file ) && ! empty ( $file )) {
				$this->model_module_excelport->createDownload ( $file, false );
				echo "3";
			} else {
				$this->model_module_excelport->cleanTemp ();
			}
		} else {
			$this->model_module_excelport->cleanTemp ();
		}
	}
}
?>
