<?php
// ==============================================================================
// Formula-Based Shipping v201.1
//
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
//
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
// ==============================================================================
class ControllerShippingFormulaBased extends Controller {
	private $type = 'shipping';
	private $name = 'formula_based';
	public function index() {
		$data = array (
				'type' => $this->type,
				'name' => $this->name 
		);
		
		$this->backupSettings ( 'auto', $data );
		$data ['permission'] = $this->user->hasPermission ( 'modify', $this->type . '/' . $this->name );
		
		$token = $data ['token'] = (isset ( $this->session->data ['token'] )) ? $this->session->data ['token'] : '';
		$data = array_merge ( $data, $this->load->language ( $this->type . '/' . $this->name ) );
		$data ['exit'] = $this->url->link ( 'extension/' . $this->type, 'token=' . $token, 'SSL' );
		
		$data ['settings_buttons'] = true;
		$data ['tooltips_button'] = true;
		
		// ------------------------------------------------------------------------------
		// Data Arrays
		// ------------------------------------------------------------------------------
		$data ['rule_options'] = array (
				'adjustments' => array (
						'adjust',
						'cumulative',
						'max',
						'min',
						'round',
						'setting_override',
						'tax_class',
						'total_value' 
				),
				'cart_criteria' => array (
						'length',
						'width',
						'height',
						'quantity',
						'stock',
						'total',
						'volume',
						'weight' 
				),
				'datetime_criteria' => array (
						'day',
						'date',
						'time' 
				),
				'location_criteria' => array (
						'city',
						'distance',
						'geo_zone',
						'location_comparison',
						'postcode' 
				),
				'order_criteria' => array (
						'currency',
						'customer_group',
						'language',
						'store' 
				),
				'rule_sets' => array (
						'rule_set' 
				) 
		);
		
		$data ['setting_override_array'] = array (
				array (
						'group' => 'config',
						'key' => 'config_address',
						'value' => $this->config->get ( 'config_address' ) 
				) 
		);
		
		$data ['currency_array'] = array (
				$this->config->get ( 'config_currency' ) => '' 
		);
		$this->load->model ( 'localisation/currency' );
		foreach ( $this->model_localisation_currency->getCurrencies () as $currency ) {
			$data ['currency_array'] [$currency ['code']] = $currency ['code'];
		}
		
		$data ['customer_group_array'] = array (
				0 => $data ['text_guests'] 
		);
		$this->load->model ( 'sale/customer_group' );
		foreach ( $this->model_sale_customer_group->getCustomerGroups () as $customer_group ) {
			$data ['customer_group_array'] [$customer_group ['customer_group_id']] = $customer_group ['name'];
		}
		
		$data ['geo_zone_array'] = array (
				0 => $data ['text_everywhere_else'] 
		);
		$this->load->model ( 'localisation/geo_zone' );
		foreach ( $this->model_localisation_geo_zone->getGeoZones () as $geo_zone ) {
			$data ['geo_zone_array'] [$geo_zone ['geo_zone_id']] = $geo_zone ['name'];
		}
		
		$this->load->model ( 'localisation/language' );
		$data ['language_array'] = array (
				$this->config->get ( 'config_language' ) => '' 
		);
		$data ['language_flags'] = array ();
		foreach ( $this->model_localisation_language->getLanguages () as $language ) {
			$data ['language_array'] [$language ['code']] = $language ['name'];
			$data ['language_flags'] [$language ['code']] = $language ['image'];
		}
		
		$data ['store_array'] = array (
				0 => $this->config->get ( 'config_name' ) 
		);
		$store_query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "store ORDER BY name" );
		foreach ( $store_query->rows as $store ) {
			$data ['store_array'] [$store ['store_id']] = $store ['name'];
		}
		
		$data ['tax_class_array'] = array (
				0 => $data ['text_none'] 
		);
		$this->load->model ( 'localisation/tax_class' );
		foreach ( $this->model_localisation_tax_class->getTaxClasses () as $tax_class ) {
			$data ['tax_class_array'] [$tax_class ['tax_class_id']] = $tax_class ['title'];
		}
		
		$data ['total_value_array'] = array ();
		foreach ( array (
				'prediscounted',
				'nondiscounted',
				'taxed',
				'total' 
		) as $total_value ) {
			$data ['total_value_array'] [$total_value] = $data ['text_' . $total_value . '_subtotal'];
		}
		
		$data ['quantity_unit'] = $data ['text_items'];
		$data ['stock_unit'] = $data ['text_items'];
		$left_symbol = $this->currency->getSymbolLeft ();
		$data ['total_unit'] = ($left_symbol) ? $left_symbol : $this->currency->getSymbolRight ();
		$length = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "length_class_description WHERE length_class_id = " . ( int ) $this->config->get ( 'config_length_class_id' ) );
		$data ['length_unit'] = $length->row ['unit'];
		$data ['width_unit'] = $length->row ['unit'];
		$data ['height_unit'] = $length->row ['unit'];
		$data ['volume_unit'] = $length->row ['unit'] . '&sup3;';
		$weight = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "weight_class_description WHERE weight_class_id = " . ( int ) $this->config->get ( 'config_weight_class_id' ) );
		$data ['weight_unit'] = $weight->row ['unit'];
		
		$data ['rule_sets'] = array ();
		foreach ( $data ['saved'] as $key => $setting ) {
			if (preg_match ( '/rule_set_(\d+)_name/', $key )) {
				$data ['rule_sets'] [str_replace ( array (
						'rule_set_',
						'_name' 
				), '', $key )] = $setting;
			}
		}
		
		// ------------------------------------------------------------------------------
		// Extensions Settings
		// ------------------------------------------------------------------------------
		$data ['settings'] = array ();
		
		$data ['settings'] [] = array (
				'type' => 'tabs',
				'tabs' => array (
						'extension_settings',
						'charges',
						'charge_combinations',
						'rule_sets' 
				) 
		);
		$data ['settings'] [] = array (
				'key' => 'extension_settings',
				'type' => 'heading' 
		);
		$data ['settings'] [] = array (
				'key' => 'status',
				'type' => 'select',
				'options' => array (
						'0' => $data ['text_disabled'],
						'1' => $data ['text_enabled'] 
				) 
		);
		$data ['settings'] [] = array (
				'key' => 'sort_order',
				'type' => 'text',
				'default' => ($this->type == 'shipping' ? '1' : '3'),
				'attributes' => array (
						'style' => 'width: 30px !important',
						'maxlength' => '1' 
				) 
		);
		$data ['settings'] [] = array (
				'key' => 'tax_class_id',
				'type' => 'select',
				'options' => $data ['tax_class_array'] 
		);
		$data ['settings'] [] = array (
				'key' => 'heading',
				'type' => 'multilingual_text',
				'default' => $data ['heading_title'] 
		);
		$data ['settings'] [] = array (
				'key' => 'display',
				'type' => 'select',
				'options' => array (
						'expanded' => $data ['text_expanded'],
						'collapsed' => $data ['text_collapsed'] 
				) 
		);
		$data ['settings'] [] = array (
				'key' => 'testing_mode',
				'type' => 'select',
				'options' => array (
						'0' => $data ['text_disabled'],
						'1' => $data ['text_enabled'] 
				) 
		);
		
		// ------------------------------------------------------------------------------
		// Charges
		// ------------------------------------------------------------------------------
		$data ['settings'] [] = array (
				'key' => 'charges',
				'type' => 'tab' 
		);
		$data ['settings'] [] = array (
				'type' => 'html',
				'content' => '<div class="text-info text-center" style="padding-bottom: 5px">' . $data ['help_charges'] . '</div>' 
		);
		$data ['settings'] [] = array (
				'key' => 'charges',
				'type' => 'heading',
				'buttons' => 'expand_collapse' 
		);
		$data ['settings'] [] = array (
				'key' => 'charge',
				'type' => 'table_start',
				'columns' => array (
						'action',
						'group',
						'title',
						'charge',
						'rules' 
				) 
		);
		
		$table = 'charge';
		$sortby = 'group';
		foreach ( $this->getTableRowNumbers ( $data, $table, $sortby ) as $num => $rules ) {
			$prefix = $table . '_' . $num . '_';
			$data ['settings'] [] = array (
					'type' => 'row_start' 
			);
			$data ['settings'] [] = array (
					'key' => 'expand_collapse',
					'type' => 'button' 
			);
			$data ['settings'] [] = array (
					'key' => 'copy',
					'type' => 'button' 
			);
			$data ['settings'] [] = array (
					'key' => 'delete',
					'type' => 'button' 
			);
			$data ['settings'] [] = array (
					'type' => 'column' 
			);
			$data ['settings'] [] = array (
					'key' => $prefix . 'group',
					'type' => 'text',
					'attributes' => array (
							'style' => 'width: 30px !important',
							'maxlength' => '1' 
					) 
			);
			$data ['settings'] [] = array (
					'type' => 'column' 
			);
			$data ['settings'] [] = array (
					'key' => $prefix . 'title',
					'type' => 'multilingual_text',
					'admin_ref' => true 
			);
			$data ['settings'] [] = array (
					'type' => 'column' 
			);
			
			$charge_options = array ();
			$charge_options ['text_simple_charges'] = '';
			$charge_options ['flat'] = $data ['text_flat_charge'];
			$charge_options ['peritem'] = $data ['text_per_item_charge'];
			$charge_options ['text_bracket_charges'] = '';
			$charge_options ['distance'] = $data ['text_distance'];
			$charge_options ['postcode'] = $data ['text_postcode'];
			$charge_options ['quantity'] = $data ['text_quantity'];
			$charge_options ['total'] = $data ['text_total'];
			$charge_options ['volume'] = $data ['text_volume'];
			$charge_options ['weight'] = $data ['text_weight'];
			
			$data ['settings'] [] = array (
					'key' => $prefix . 'type',
					'type' => 'select',
					'options' => $charge_options 
			);
			$data ['settings'] [] = array (
					'key' => $prefix . 'charges',
					'type' => 'textarea' 
			);
			$data ['settings'] [] = array (
					'type' => 'column' 
			);
			$data ['settings'] [] = array (
					'key' => $prefix . 'rule',
					'type' => 'rule',
					'rules' => $rules 
			);
			$data ['settings'] [] = array (
					'type' => 'row_end' 
			);
		}
		
		$data ['settings'] [] = array (
				'type' => 'table_end',
				'buttons' => 'add_row',
				'text' => 'button_add_charge' 
		);
		
		// ------------------------------------------------------------------------------
		// Charge Combinations
		// ------------------------------------------------------------------------------
		$data ['settings'] [] = array (
				'key' => 'charge_combinations',
				'type' => 'tab' 
		);
		$data ['settings'] [] = array (
				'type' => 'html',
				'content' => '<div class="text-info text-center" style="padding-bottom: 5px">' . $data ['help_charge_combinations'] . '</div>' 
		);
		$data ['settings'] [] = array (
				'key' => 'charge_combinations',
				'type' => 'heading' 
		);
		$data ['settings'] [] = array (
				'key' => 'combination',
				'type' => 'table_start',
				'columns' => array (
						'action',
						'sort_order',
						'title_combination',
						'combination_formula' 
				) 
		);
		
		$table = 'combination';
		$sortby = 'sort_order';
		foreach ( $this->getTableRowNumbers ( $data, $table, $sortby ) as $num => $rules ) {
			$prefix = $table . '_' . $num . '_';
			$data ['settings'] [] = array (
					'type' => 'row_start' 
			);
			$data ['settings'] [] = array (
					'key' => 'delete',
					'type' => 'button' 
			);
			$data ['settings'] [] = array (
					'type' => 'column' 
			);
			$data ['settings'] [] = array (
					'key' => $prefix . 'sort_order',
					'type' => 'text',
					'attributes' => array (
							'style' => 'width: 30px !important',
							'maxlength' => '1' 
					) 
			);
			$data ['settings'] [] = array (
					'type' => 'column' 
			);
			$data ['settings'] [] = array (
					'key' => $prefix . 'title',
					'type' => 'select',
					'options' => array (
							'single' => $data ['text_single_title'],
							'combined' => $data ['text_combined_title_no_prices'],
							'combined_prices' => $data ['text_combined_title_with_prices'] 
					) 
			);
			$data ['settings'] [] = array (
					'type' => 'column' 
			);
			$data ['settings'] [] = array (
					'key' => $prefix . 'formula',
					'type' => 'text',
					'attributes' => array (
							'style' => 'font-family: monospace; font-size: 14px; width: 300px !important',
							'placeholder' => $data ['placeholder_formula'] 
					) 
			);
			$data ['settings'] [] = array (
					'type' => 'row_end' 
			);
		}
		
		$data ['settings'] [] = array (
				'type' => 'table_end',
				'buttons' => 'add_row',
				'text' => 'button_add_combination' 
		);
		
		// ------------------------------------------------------------------------------
		// Rule Sets
		// ------------------------------------------------------------------------------
		$data ['settings'] [] = array (
				'key' => 'rule_sets',
				'type' => 'tab' 
		);
		$data ['settings'] [] = array (
				'type' => 'html',
				'content' => '<div class="text-info text-center" style="padding-bottom: 5px">' . $data ['help_rule_sets'] . '</div>' 
		);
		$data ['settings'] [] = array (
				'key' => 'rule_sets',
				'type' => 'heading',
				'buttons' => 'expand_collapse' 
		);
		$data ['settings'] [] = array (
				'key' => 'rule_set',
				'type' => 'table_start',
				'columns' => array (
						'action',
						'sort_order',
						'name',
						'rules' 
				) 
		);
		
		$table = 'rule_set';
		$sortby = 'sort_order';
		foreach ( $this->getTableRowNumbers ( $data, $table, $sortby ) as $num => $rules ) {
			$prefix = $table . '_' . $num . '_';
			$data ['settings'] [] = array (
					'type' => 'row_start' 
			);
			$data ['settings'] [] = array (
					'key' => 'expand_collapse',
					'type' => 'button' 
			);
			$data ['settings'] [] = array (
					'key' => 'copy',
					'type' => 'button' 
			);
			$data ['settings'] [] = array (
					'key' => 'delete',
					'type' => 'button' 
			);
			$data ['settings'] [] = array (
					'type' => 'column' 
			);
			$data ['settings'] [] = array (
					'key' => $prefix . 'sort_order',
					'type' => 'text',
					'attributes' => array (
							'style' => 'width: 30px !important',
							'maxlength' => '1' 
					) 
			);
			$data ['settings'] [] = array (
					'type' => 'column' 
			);
			$data ['settings'] [] = array (
					'key' => $prefix . 'name',
					'type' => 'text' 
			);
			$data ['settings'] [] = array (
					'type' => 'column' 
			);
			$data ['settings'] [] = array (
					'key' => $prefix . 'rule',
					'type' => 'rule',
					'rules' => $rules 
			);
			$data ['settings'] [] = array (
					'type' => 'row_end' 
			);
		}
		
		$data ['settings'] [] = array (
				'type' => 'table_end',
				'buttons' => 'add_row',
				'text' => 'button_add_rule_set' 
		);
		
		// ------------------------------------------------------------------------------
		// end settings
		// ------------------------------------------------------------------------------
		
		$this->document->setTitle ( $data ['heading_title'] );
		
		if (version_compare ( VERSION, '2.0' ) < 0) {
			$this->data = $data;
			$this->template = $this->type . '/' . $this->name . '.tpl';
			$this->children = array (
					'common/header',
					'common/footer' 
			);
			$this->response->setOutput ( $this->render () );
		} else {
			$data ['header'] = $this->load->controller ( 'common/header' );
			$data ['column_left'] = $this->load->controller ( 'common/column_left' );
			$data ['footer'] = $this->load->controller ( 'common/footer' );
			$this->response->setOutput ( $this->load->view ( $this->type . '/' . $this->name . '.tpl', $data ) );
		}
	}
	private function getTableRowNumbers($data, $table, $sorting) {
		$groups = array ();
		$rules = array ();
		
		foreach ( $data ['saved'] as $key => $setting ) {
			if (preg_match ( '/' . $table . '_(\d+)_' . $sorting . '/', $key, $matches )) {
				$groups [$setting] [] = $matches [1];
			}
			if (preg_match ( '/' . $table . '_(\d+)_rule_(\d+)_type/', $key, $matches )) {
				$rules [$matches [1]] [] = $matches [2];
			}
		}
		
		if (empty ( $groups )) {
			$groups = array (
					'' => array (
							'1' 
					) 
			);
		}
		ksort ( $groups, SORT_STRING );
		
		$rows = array ();
		foreach ( $groups as $group ) {
			foreach ( $group as $num ) {
				$rows [$num] = (empty ( $rules [$num] )) ? array () : $rules [$num];
			}
		}
		
		return $rows;
	}
	
	// ==============================================================================
	// Setting functions
	// ==============================================================================
	private $encryption_key = '';
	public function backupSettings($backup_type = '', &$data = array()) {
		$data ['saved'] = array ();
		
		if (empty ( $backup_type ) && ! $this->user->hasPermission ( 'modify', $this->type . '/' . $this->name )) {
			return;
		}
		
		$manual_filepath = DIR_LOGS . $this->name . '_backup' . $this->encryption_key . '.txt';
		$auto_filepath = DIR_LOGS . $this->name . '_autobackup' . $this->encryption_key . '.txt';
		$filepath = ($backup_type == 'auto') ? $auto_filepath : $manual_filepath;
		if (file_exists ( $filepath ))
			unlink ( $filepath );
		
		file_put_contents ( $filepath, 'EXTENSION	SETTING	NUMBER	SUB-SETTING	SUB-NUMBER	SUB-SUB-SETTING	VALUE' . "\n", FILE_APPEND | LOCK_EX );
		
		$settings_query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "setting WHERE `" . (version_compare ( VERSION, '2.0.1' ) < 0 ? 'group' : 'code') . "` = '" . $this->db->escape ( $this->name ) . "' ORDER BY `key` ASC" );
		foreach ( $settings_query->rows as $setting ) {
			$data ['saved'] [str_replace ( $this->name . '_', '', $setting ['key'] )] = (is_string ( $setting ['value'] ) && strpos ( $setting ['value'], 'a:' ) === 0) ? unserialize ( $setting ['value'] ) : $setting ['value'];
			
			$parts = explode ( '|', preg_replace ( array (
					'/_(\d+)_/',
					'/_(\d+)/' 
			), array (
					'|$1|',
					'|$1' 
			), str_replace ( $this->name . '_', '', $setting ['key'] ) ) );
			$line = $this->name . "\t" . $parts [0] . "\t" . (isset ( $parts [1] ) ? $parts [1] : '') . "\t" . (isset ( $parts [2] ) ? $parts [2] : '') . "\t" . (isset ( $parts [3] ) ? $parts [3] : '') . "\t" . (isset ( $parts [4] ) ? $parts [4] : '') . "\t" . str_replace ( array (
					"\t",
					"\n" 
			), array (
					'    ',
					'\n' 
			), $setting ['value'] ) . "\n";
			
			file_put_contents ( $filepath, $line, FILE_APPEND | LOCK_EX );
		}
		
		if ($backup_type == 'auto') {
			$data ['autobackup_time'] = date ( 'Y-M-d @ g:i a' );
			$data ['backup_time'] = (file_exists ( $manual_filepath )) ? date ( 'Y-M-d @ g:i a', filemtime ( $manual_filepath ) ) : '';
			asort ( $data ['saved'] );
		} elseif (empty ( $backup_type )) {
			echo date ( 'Y-M-d @ g:i a' );
		}
	}
	public function viewBackup() {
		if (! $this->user->hasPermission ( 'access', $this->type . '/' . $this->name )) {
			echo 'You do not have permission to view this file.';
			return;
		}
		if (! file_exists ( DIR_LOGS . $this->name . '_backup.txt' )) {
			echo 'Backup file "' . DIR_LOGS . $this->name . '_backup.txt" does not exist';
			return;
		}
		
		$contents = trim ( file_get_contents ( DIR_LOGS . $this->name . '_backup' . $this->encryption_key . '.txt' ) );
		$lines = explode ( "\n", $contents );
		
		$html = '<table border="1" style="font-family: monospace" cellspacing="0" cellpadding="5">';
		foreach ( $lines as $line ) {
			$html .= '<tr><td>' . implode ( '</td><td>', explode ( "\t", $line ) ) . '</td></tr>';
		}
		echo $html;
	}
	public function downloadBackup() {
		if (! $this->user->hasPermission ( 'access', $this->type . '/' . $this->name ) || ! file_exists ( DIR_LOGS . $this->name . '_backup.txt' )) {
			return;
		}
		
		header ( 'Pragma: public' );
		header ( 'Expires: 0' );
		header ( 'Content-Description: File Transfer' );
		header ( 'Content-Type: application/octet-stream' );
		header ( 'Content-Disposition: attachment; filename=' . $this->name . '_backup.' . date ( 'Y-n-d' ) . '.txt' );
		header ( 'Content-Transfer-Encoding: binary' );
		
		echo file_get_contents ( DIR_LOGS . $this->name . '_backup' . $this->encryption_key . '.txt' );
	}
	public function restoreSettings() {
		$data = $this->load->language ( $this->type . '/' . $this->name );
		
		if (! $this->user->hasPermission ( 'modify', $this->type . '/' . $this->name )) {
			$this->session->data ['error'] = $data ['standard_error'];
			$this->response->redirect ( str_replace ( array (
					'&amp;',
					"\n",
					"\r" 
			), array (
					'&',
					'',
					'' 
			), $this->url->link ( $this->type . '/' . $this->name, 'token=' . $this->session->data ['token'], 'SSL' ) ) );
		}
		
		if ($this->request->post ['from'] == 'auto') {
			$filepath = DIR_LOGS . $this->name . '_autobackup' . $this->encryption_key . '.txt';
		} elseif ($this->request->post ['from'] == 'manual') {
			$filepath = DIR_LOGS . $this->name . '_backup' . $this->encryption_key . '.txt';
		} elseif ($this->request->post ['from'] == 'file') {
			$filepath = $this->request->files ['backup_file'] ['tmp_name'];
			if (empty ( $filepath )) {
				$this->response->redirect ( str_replace ( array (
						'&amp;',
						"\n",
						"\r" 
				), array (
						'&',
						'',
						'' 
				), $this->url->link ( $this->type . '/' . $this->name, 'token=' . $this->session->data ['token'], 'SSL' ) ) );
			}
		}
		
		$contents = trim ( file_get_contents ( $filepath ) );
		
		if (strpos ( $contents, 'EXTENSION' ) !== 0) {
			$this->session->data ['error'] = $data ['error_invalid_file_data'];
			$this->response->redirect ( str_replace ( array (
					'&amp;',
					"\n",
					"\r" 
			), array (
					'&',
					'',
					'' 
			), $this->url->link ( $this->type . '/' . $this->name, 'token=' . $this->session->data ['token'], 'SSL' ) ) );
		}
		
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "setting WHERE `" . (version_compare ( VERSION, '2.0.1' ) < 0 ? 'group' : 'code') . "` = '" . $this->db->escape ( $this->name ) . "'" );
		
		foreach ( explode ( "\n", file_get_contents ( $filepath ) ) as $row ) {
			if (empty ( $row ) || strpos ( $row, 'EXTENSION' ) === 0)
				continue;
			
			$col = explode ( "\t", $row );
			$value = str_replace ( '\n', "\n", array_pop ( $col ) );
			$key = implode ( '_', array_diff ( $col, array (
					'' 
			) ) );
			
			$this->db->query ( "INSERT INTO " . DB_PREFIX . "setting SET `store_id` = 0, `" . (version_compare ( VERSION, '2.0.1' ) < 0 ? 'group' : 'code') . "` = '" . $this->db->escape ( $this->name ) . "', `key` = '" . $this->db->escape ( $key ) . "', `value` = '" . $this->db->escape ( $value ) . "', `serialized` = 0" );
		}
		
		$this->session->data ['success'] = $data ['text_settings_restored'];
		$this->response->redirect ( str_replace ( array (
				'&amp;',
				"\n",
				"\r" 
		), array (
				'&',
				'',
				'' 
		), $this->url->link ( $this->type . '/' . $this->name, 'token=' . $this->session->data ['token'], 'SSL' ) ) );
	}
	public function saveSetting() {
		if (! $this->user->hasPermission ( 'modify', $this->type . '/' . $this->name )) {
			echo 'PermissionError';
			return;
		}
		
		foreach ( $this->request->post as $key => $value ) {
			$this->db->query ( "DELETE FROM " . DB_PREFIX . "setting WHERE `" . (version_compare ( VERSION, '2.0.1' ) < 0 ? 'group' : 'code') . "` = '" . $this->db->escape ( $this->name ) . "'AND `key` = '" . $this->db->escape ( $this->name . '_' . $key ) . "'" );
			$this->db->query ( "
				INSERT INTO " . DB_PREFIX . "setting SET
				`store_id` = 0,
				`" . (version_compare ( VERSION, '2.0.1' ) < 0 ? 'group' : 'code') . "` = '" . $this->db->escape ( $this->name ) . "',
				`key` = '" . $this->db->escape ( $this->name . '_' . $key ) . "',
				`value` = '" . $this->db->escape ( stripslashes ( $value ) ) . "'
				" . (version_compare ( VERSION, '1.5.1' ) >= 0 ? ", `serialized` = 0" : "") . "
			" );
		}
	}
	public function deleteSetting() {
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "setting WHERE `" . (version_compare ( VERSION, '2.0.1' ) < 0 ? 'group' : 'code') . "` = '" . $this->db->escape ( $this->name ) . "' AND `key` = '" . $this->db->escape ( $this->name . '_' . $this->request->get ['setting'] ) . "'" );
	}
	
	// ==============================================================================
	// Ajax functions
	// ==============================================================================
	public function loadDropdown() {
		$data = $this->load->language ( $this->type . '/' . $this->name );
		echo '<option value="">' . $data ['standard_select'] . '</option>';
		$options = array ();
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "setting WHERE `" . (version_compare ( VERSION, '2.0.1' ) < 0 ? 'group' : 'code') . "` = '" . $this->name . "' AND `key` LIKE '" . $this->name . "_" . $this->request->get ['type'] . "%'" );
		foreach ( $query->rows as $row ) {
			if (strpos ( $row ['key'], '_name' )) {
				$num = str_replace ( array (
						$this->name . '_' . $this->request->get ['type'] . '_',
						'_name' 
				), '', $row ['key'] );
				foreach ( $query->rows as $subrow ) {
					if (strpos ( $subrow ['key'], $num . '_sort_order' ) && $row ['value']) {
						$options ['<option value="' . $num . '">' . $row ['value'] . '</option>'] = $subrow ['value'];
						break;
					}
				}
			}
		}
		asort ( $options );
		foreach ( $options as $option => $sort_order ) {
			echo $option;
		}
	}
}
?>