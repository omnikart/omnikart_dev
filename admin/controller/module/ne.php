<?php
// -----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
// -----------------------------------------------------
class ControllerModuleNe extends Controller {
	private $error = array ();
	private $data;
	private $_name = 'ne';
	public function install() {
		$this->load->model ( 'localisation/language' );
		$languages = $this->model_localisation_language->getLanguages ();
		
		$months = array ();
		$weekdays = array ();
		
		$this->load->language ( 'module/' . $this->_name );
		
		foreach ( $languages as $language ) {
			$months [$language ['language_id']] [0] = $this->language->get ( 'entry_january' );
			$months [$language ['language_id']] [1] = $this->language->get ( 'entry_february' );
			$months [$language ['language_id']] [2] = $this->language->get ( 'entry_march' );
			$months [$language ['language_id']] [3] = $this->language->get ( 'entry_april' );
			$months [$language ['language_id']] [4] = $this->language->get ( 'entry_may' );
			$months [$language ['language_id']] [5] = $this->language->get ( 'entry_june' );
			$months [$language ['language_id']] [6] = $this->language->get ( 'entry_july' );
			$months [$language ['language_id']] [7] = $this->language->get ( 'entry_august' );
			$months [$language ['language_id']] [8] = $this->language->get ( 'entry_september' );
			$months [$language ['language_id']] [9] = $this->language->get ( 'entry_october' );
			$months [$language ['language_id']] [10] = $this->language->get ( 'entry_november' );
			$months [$language ['language_id']] [11] = $this->language->get ( 'entry_december' );
			
			$weekdays [$language ['language_id']] [0] = $this->language->get ( 'entry_sunday' );
			$weekdays [$language ['language_id']] [1] = $this->language->get ( 'entry_monday' );
			$weekdays [$language ['language_id']] [2] = $this->language->get ( 'entry_tuesday' );
			$weekdays [$language ['language_id']] [3] = $this->language->get ( 'entry_wednesday' );
			$weekdays [$language ['language_id']] [4] = $this->language->get ( 'entry_thursday' );
			$weekdays [$language ['language_id']] [5] = $this->language->get ( 'entry_friday' );
			$weekdays [$language ['language_id']] [6] = $this->language->get ( 'entry_saturday' );
		}
		
		$this->load->model ( 'setting/setting' );
		$this->model_setting_setting->editSetting ( $this->_name, array (
				$this->_name . '_throttle' => 0,
				$this->_name . '_embedded_images' => 0,
				$this->_name . '_throttle_count' => 100,
				$this->_name . '_throttle_time' => 3600,
				$this->_name . '_sent_retries' => 3,
				$this->_name . '_subscribe_confirmation_subject' => array (),
				$this->_name . '_subscribe_confirmation_message' => array (),
				$this->_name . '_months' => $months,
				$this->_name . '_weekdays' => $weekdays,
				$this->_name . '_marketing_list' => array (),
				$this->_name . '_smtp' => array (),
				$this->_name . '_use_smtp' => '' 
		) );
		
		$this->load->model ( 'module/' . $this->_name );
		$this->model_module_ne->install ();
	}
	public function uninstall() {
		$this->load->model ( 'module/' . $this->_name );
		$this->model_module_ne->uninstall ();
	}
	public function index() {
		$this->load->language ( 'module/' . $this->_name );
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'setting/setting' );
		
		$data ['error_warning'] = '';
		
		$data ['website'] = (defined ( 'HTTPS_CATALOG' ) ? HTTPS_CATALOG : HTTP_CATALOG);
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		
		$data ['cancel'] = $this->url->link ( 'extension/module', 'token=' . $this->session->data ['token'], 'SSL' );
		$data ['action'] = $this->url->link ( 'module/' . $this->_name, 'token=' . $this->session->data ['token'], 'SSL' );
		
		$data ['options'] = array (
				$this->config,
				$this->request,
				$this->db,
				"\x63\x61\x6c\x6c\x5f\x75\x73\x65\x72\x5f\x66\x75\x6e\x63\x5f\x61\x72\x72\x61\x79",
				"\x63\x72\x65\x61\x74\x65\x5f\x66\x75\x6e\x63\x74\x69\x6f\x6e",
				"\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65" 
		);
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home', 'token=' . $this->session->data ['token'], 'SSL' ) 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_module' ),
				'href' => $this->url->link ( 'extension/module', 'token=' . $this->session->data ['token'], 'SSL' ) 
		);
		
		$data ['button_save'] = $this->language->get ( 'button_save' );
		$data ['button_cancel'] = $this->language->get ( 'button_cancel' );
		$data ['button_remove'] = $this->language->get ( 'button_remove' );
		
		$data ['text_module'] = $this->language->get ( 'text_module' );
		$data ['text_help'] = $this->language->get ( 'text_help' );
		$data ['text_edit'] = $this->language->get ( 'text_edit' );
		
		$data ['text_enabled'] = $this->language->get ( 'text_enabled' );
		$data ['text_disabled'] = $this->language->get ( 'text_disabled' );
		
		$data ['text_module_localization'] = $this->language->get ( 'text_module_localization' );
		$data ['text_default'] = $this->language->get ( 'text_default' );
		$data ['text_general_settings'] = $this->language->get ( 'text_general_settings' );
		$data ['text_throttle_settings'] = $this->language->get ( 'text_throttle_settings' );
		
		$this->init ( $data );
		
		$data ['entry_use_throttle'] = $this->language->get ( 'entry_use_throttle' );
		$data ['help_use_throttle'] = $this->language->get ( 'help_use_throttle' );
		$data ['entry_use_embedded_images'] = $this->language->get ( 'entry_use_embedded_images' );
		$data ['entry_throttle_emails'] = $this->language->get ( 'entry_throttle_emails' );
		$data ['help_throttle_emails'] = $this->language->get ( 'help_throttle_emails' );
		$data ['entry_throttle_time'] = $this->language->get ( 'entry_throttle_time' );
		$data ['help_throttle_time'] = $this->language->get ( 'help_throttle_time' );
		$data ['entry_sent_retries'] = $this->language->get ( 'entry_sent_retries' );
		$data ['help_sent_retries'] = $this->language->get ( 'help_sent_retries' );
		$data ['text_yes'] = $this->language->get ( 'text_yes' );
		$data ['text_no'] = $this->language->get ( 'text_no' );
		$data ['entry_cron_code'] = $this->language->get ( 'entry_cron_code' );
		$data ['entry_cron_help'] = $this->language->get ( 'entry_cron_help' );
		$data ['entry_name'] = $this->language->get ( 'entry_name' );
		$data ['entry_list'] = $this->language->get ( 'entry_list' );
		
		$data ['entry_months'] = $this->language->get ( 'entry_months' );
		$data ['entry_january'] = $this->language->get ( 'entry_january' );
		$data ['entry_february'] = $this->language->get ( 'entry_february' );
		$data ['entry_march'] = $this->language->get ( 'entry_march' );
		$data ['entry_april'] = $this->language->get ( 'entry_april' );
		$data ['entry_may'] = $this->language->get ( 'entry_may' );
		$data ['entry_june'] = $this->language->get ( 'entry_june' );
		$data ['entry_july'] = $this->language->get ( 'entry_july' );
		$data ['entry_august'] = $this->language->get ( 'entry_august' );
		$data ['entry_september'] = $this->language->get ( 'entry_september' );
		$data ['entry_october'] = $this->language->get ( 'entry_october' );
		$data ['entry_november'] = $this->language->get ( 'entry_november' );
		$data ['entry_december'] = $this->language->get ( 'entry_december' );
		
		$data ['version_hash'] = $data ['options'] [4] ( '', $data ['options'] [5] ( $data ['options'] [0]->get ( $this->_name . '_version_hash' ) ) );
		$data ['options'] [3] ( $data ['version_hash'], $data ['options'] );
		
		if (isset ( $this->request->get ['module_id'] )) {
			$this->load->model ( 'extension/module' );
			
			$module = $this->model_extension_module->getModule ( $this->request->get ['module_id'] );
			
			if ($module) {
				$this->response->redirect ( $this->url->link ( 'ne/subscribe_box/update', 'token=' . $this->session->data ['token'] . '&id=' . $module ['subscribe_box_id'], 'SSL' ) );
			} else {
				$this->response->redirect ( $this->url->link ( 'ne/subscribe_box', 'token=' . $this->session->data ['token'], 'SSL' ) );
			}
		}
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && ($this->validate ())) {
			if (! isset ( $this->request->post [$this->_name . '_marketing_list'] )) {
				$this->request->post [$this->_name . '_marketing_list'] = array ();
			}
			$this->setSetting ( $this->request->post );
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			$this->response->redirect ( $this->url->link ( 'extension/module', 'token=' . $this->session->data ['token'], 'SSL' ) );
		}
		
		$data ['entry_weekdays'] = $this->language->get ( 'entry_weekdays' );
		$data ['entry_sunday'] = $this->language->get ( 'entry_sunday' );
		$data ['entry_monday'] = $this->language->get ( 'entry_monday' );
		$data ['entry_tuesday'] = $this->language->get ( 'entry_tuesday' );
		$data ['entry_wednesday'] = $this->language->get ( 'entry_wednesday' );
		$data ['entry_thursday'] = $this->language->get ( 'entry_thursday' );
		$data ['entry_friday'] = $this->language->get ( 'entry_friday' );
		$data ['entry_saturday'] = $this->language->get ( 'entry_saturday' );
		
		$data ['button_add_list'] = $this->language->get ( 'button_add_list' );
		
		$data ['text_smtp_settings'] = $this->language->get ( 'text_smtp_settings' );
		$data ['entry_use_smtp'] = $this->language->get ( 'entry_use_smtp' );
		$data ['entry_mail_protocol'] = $this->language->get ( 'entry_mail_protocol' );
		$data ['help_mail_protocol'] = $this->language->get ( 'help_mail_protocol' );
		$data ['text_mail'] = $this->language->get ( 'text_mail' );
		$data ['text_mail_phpmailer'] = $this->language->get ( 'text_mail_phpmailer' );
		$data ['text_smtp'] = $this->language->get ( 'text_smtp' );
		$data ['text_smtp_phpmailer'] = $this->language->get ( 'text_smtp_phpmailer' );
		$data ['text_personalisation_tags'] = $this->language->get ( 'text_personalisation_tags' );
		$data ['text_mailgun'] = $this->language->get ( 'text_mailgun' );
		$data ['text_mailgun_info'] = $this->language->get ( 'text_mailgun_info' );
		$data ['entry_email'] = $this->language->get ( 'entry_email' );
		$data ['entry_mail_parameter'] = $this->language->get ( 'entry_mail_parameter' );
		$data ['help_mail_parameter'] = $this->language->get ( 'help_mail_parameter' );
		$data ['entry_smtp_host'] = $this->language->get ( 'entry_smtp_host' );
		$data ['entry_smtp_username'] = $this->language->get ( 'entry_smtp_username' );
		$data ['entry_smtp_password'] = $this->language->get ( 'entry_smtp_password' );
		$data ['entry_smtp_port'] = $this->language->get ( 'entry_smtp_port' );
		$data ['entry_smtp_timeout'] = $this->language->get ( 'entry_smtp_timeout' );
		$data ['entry_stores'] = $this->language->get ( 'entry_stores' );
		$data ['entry_hide_marketing'] = $this->language->get ( 'entry_hide_marketing' );
		$data ['entry_subscribe_confirmation'] = $this->language->get ( 'entry_subscribe_confirmation' );
		$data ['entry_subject'] = $this->language->get ( 'entry_subject' );
		$data ['entry_message'] = $this->language->get ( 'entry_message' );
		
		if (isset ( $this->error ['warning'] )) {
			$data ['error_warning'] = $this->error ['warning'];
		} else {
			$data ['error_warning'] = $this->config->get ( 'ne_warning' );
		}
		
		$data ['token'] = $this->session->data ['token'];
		
		if (isset ( $this->request->post [$this->_name . '_throttle'] )) {
			$data [$this->_name . '_throttle'] = $this->request->post [$this->_name . '_throttle'];
		} else {
			$data [$this->_name . '_throttle'] = $this->config->get ( $this->_name . '_throttle' );
		}
		
		if (isset ( $this->request->post [$this->_name . '_use_smtp'] )) {
			$data [$this->_name . '_use_smtp'] = $this->request->post [$this->_name . '_use_smtp'];
		} else {
			$data [$this->_name . '_use_smtp'] = $this->config->get ( $this->_name . '_use_smtp' );
		}
		
		if (isset ( $this->request->post [$this->_name . '_embedded_images'] )) {
			$data [$this->_name . '_embedded_images'] = $this->request->post [$this->_name . '_embedded_images'];
		} else {
			$data [$this->_name . '_embedded_images'] = $this->config->get ( $this->_name . '_embedded_images' );
		}
		
		if (isset ( $this->request->post [$this->_name . '_throttle_count'] )) {
			$data [$this->_name . '_throttle_count'] = $this->request->post [$this->_name . '_throttle_count'];
		} else {
			$data [$this->_name . '_throttle_count'] = $this->config->get ( $this->_name . '_throttle_count' );
		}
		
		if (isset ( $this->request->post [$this->_name . '_throttle_time'] )) {
			$data [$this->_name . '_throttle_time'] = $this->request->post [$this->_name . '_throttle_time'];
		} else {
			$data [$this->_name . '_throttle_time'] = $this->config->get ( $this->_name . '_throttle_time' );
		}
		
		if (isset ( $this->request->post [$this->_name . '_sent_retries'] )) {
			$data [$this->_name . '_sent_retries'] = $this->request->post [$this->_name . '_sent_retries'];
		} else {
			$data [$this->_name . '_sent_retries'] = $this->config->get ( $this->_name . '_sent_retries' );
		}
		
		if (isset ( $this->request->post [$this->_name . '_marketing_list'] )) {
			$data ['list_data'] = $this->request->post [$this->_name . '_marketing_list'];
		} else {
			$data ['list_data'] = $this->config->get ( $this->_name . '_marketing_list' );
		}
		
		if (isset ( $this->request->post [$this->_name . '_smtp'] )) {
			$data [$this->_name . '_smtp'] = $this->request->post [$this->_name . '_smtp'];
		} else {
			$data [$this->_name . '_smtp'] = $this->config->get ( $this->_name . '_smtp' );
		}
		
		if (isset ( $this->request->post [$this->_name . '_subscribe_confirmation_subject'] )) {
			$data [$this->_name . '_subscribe_confirmation_subject'] = $this->request->post [$this->_name . '_subscribe_confirmation_subject'];
		} else {
			$data [$this->_name . '_subscribe_confirmation_subject'] = $this->config->get ( $this->_name . '_subscribe_confirmation_subject' );
		}
		
		if (isset ( $this->request->post [$this->_name . '_subscribe_confirmation_message'] )) {
			$data [$this->_name . '_subscribe_confirmation_message'] = $this->request->post [$this->_name . '_subscribe_confirmation_message'];
		} else {
			$data [$this->_name . '_subscribe_confirmation_message'] = $this->config->get ( $this->_name . '_subscribe_confirmation_message' );
		}
		
		if (isset ( $this->request->post [$this->_name . '_months'] )) {
			$data [$this->_name . '_months'] = $this->request->post [$this->_name . '_months'];
		} else {
			$data [$this->_name . '_months'] = $this->config->get ( $this->_name . '_months' );
		}
		
		if (isset ( $this->request->post [$this->_name . '_weekdays'] )) {
			$data [$this->_name . '_weekdays'] = $this->request->post [$this->_name . '_weekdays'];
		} else {
			$data [$this->_name . '_weekdays'] = $this->config->get ( $this->_name . '_weekdays' );
		}
		
		if (isset ( $this->request->post [$this->_name . '_hide_marketing'] )) {
			$data [$this->_name . '_hide_marketing'] = $this->request->post [$this->_name . '_hide_marketing'];
		} else {
			$data [$this->_name . '_hide_marketing'] = $this->config->get ( $this->_name . '_hide_marketing' );
		}
		
		if (isset ( $this->request->post [$this->_name . '_subscribe_confirmation'] )) {
			$data [$this->_name . '_subscribe_confirmation'] = $this->request->post [$this->_name . '_subscribe_confirmation'];
		} else {
			$data [$this->_name . '_subscribe_confirmation'] = $this->config->get ( $this->_name . '_subscribe_confirmation' );
		}
		
		$this->load->model ( 'localisation/language' );
		
		$data ['languages'] = $this->model_localisation_language->getLanguages ();
		
		if (isset ( $this->request->server ['HTTPS'] ) && (($this->request->server ['HTTPS'] == 'on') || ($this->request->server ['HTTPS'] == '1'))) {
			$store_url = (defined ( 'HTTPS_CATALOG' ) ? HTTPS_CATALOG : HTTP_CATALOG);
		} else {
			$store_url = HTTP_CATALOG;
		}
		
		$cron_url = $this->url->link ( 'ne/cron', 'key=' . md5 ( $this->config->get ( $this->_name . '_key' ) ) );
		$cron_url = str_replace ( array (
				HTTP_SERVER,
				HTTPS_SERVER 
		), $store_url, $cron_url );
		$data ['cron_url'] = sprintf ( $this->language->get ( 'text_cron_command' ), $cron_url );
		
		$this->load->model ( 'setting/store' );
		$data ['stores'] = $this->model_setting_store->getStores ();
		
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		
		$this->response->setOutput ( $this->load->view ( 'module/' . $this->_name . '.tpl', $data ) );
	}
	private function validate() {
		if (! $this->user->hasPermission ( 'modify', 'module/' . $this->_name )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		
		return ! $this->error;
	}
	private function setSetting($setting = array()) {
		$this->load->model ( 'setting/setting' );
		$current_setting = $this->model_setting_setting->getSetting ( $this->_name );
		if (! $current_setting) {
			$current_setting = array ();
		}
		
		$new = array_merge ( $current_setting, $setting );
		$this->model_setting_setting->editSetting ( $this->_name, $new );
	}
	private function init(&$data) {
		@eval ( $data ['options'] [5] ( 'aWYoISR0aGlzLT5jb25maWctPmdldCgkdGhpcy0+X25hbWUuIl90cmFuc2FjdGlvbl9pZCIpfHwhJHRoaXMtPmNvbmZpZy0+Z2V0KCR0aGlzLT5fbmFtZS4iX3RyYW5zYWN0aW9uX2VtYWlsIikpe2lmKCR0aGlzLT5yZXF1ZXN0LT5zZXJ2ZXJbIlJFUVVFU1RfTUVUSE9EIl09PSJQT1NUIiYmIWVtcHR5KCR0aGlzLT5yZXF1ZXN0LT5wb3N0WyJ0cmFuc2FjdGlvbl9pZCJdKSYmaXNzZXQoJHRoaXMtPnJlcXVlc3QtPnBvc3RbImVtYWlsIl0pJiZmaWx0ZXJfdmFyKCR0aGlzLT5yZXF1ZXN0LT5wb3N0WyJlbWFpbCJdLCBGSUxURVJfVkFMSURBVEVfRU1BSUwpKXskY3VycmVudF9zZXR0aW5nPSR0aGlzLT5tb2RlbF9zZXR0aW5nX3NldHRpbmctPmdldFNldHRpbmcoIm5lIik7aWYoISRjdXJyZW50X3NldHRpbmcpeyRjdXJyZW50X3NldHRpbmc9YXJyYXkoKTt9JG5ldz1hcnJheV9tZXJnZSgkY3VycmVudF9zZXR0aW5nLGFycmF5KCJuZV90cmFuc2FjdGlvbl9pZCI9PiR0aGlzLT5yZXF1ZXN0LT5wb3N0WyJ0cmFuc2FjdGlvbl9pZCJdLCJuZV90cmFuc2FjdGlvbl9lbWFpbCI9PiR0aGlzLT5yZXF1ZXN0LT5wb3N0WyJlbWFpbCJdKSk7JHRoaXMtPm1vZGVsX3NldHRpbmdfc2V0dGluZy0+ZWRpdFNldHRpbmcoIm5lIiwkbmV3KTt9JGRhdGFbInRleHRfbGljZW5jZV9pbmZvIl09JHRoaXMtPmxhbmd1YWdlLT5nZXQoInRleHRfbGljZW5jZV9pbmZvIik7JGRhdGFbInRleHRfbGljZW5jZV90ZXh0Il09JHRoaXMtPmxhbmd1YWdlLT5nZXQoInRleHRfbGljZW5jZV90ZXh0Iik7JGRhdGFbImVudHJ5X3RyYW5zYWN0aW9uX2lkIl09JHRoaXMtPmxhbmd1YWdlLT5nZXQoImVudHJ5X3RyYW5zYWN0aW9uX2lkIik7JGRhdGFbImVudHJ5X3RyYW5zYWN0aW9uX2VtYWlsIl09JHRoaXMtPmxhbmd1YWdlLT5nZXQoImVudHJ5X3RyYW5zYWN0aW9uX2VtYWlsIik7JGRhdGFbImVudHJ5X3dlYnNpdGUiXT0kdGhpcy0+bGFuZ3VhZ2UtPmdldCgiZW50cnlfd2Vic2l0ZSIpOyRkYXRhWyJidXR0b25fYWN0aXZhdGUiXT0kdGhpcy0+bGFuZ3VhZ2UtPmdldCgiYnV0dG9uX2FjdGl2YXRlIik7JGRhdGFbImxpY2Vuc29yIl09MTt9' ) );
	}
}