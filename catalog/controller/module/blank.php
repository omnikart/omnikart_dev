<?php
class ControllerModuleBlank extends Controller {
	public function index($setting) {
		static $module = 0;
		
		$data ['height'] = $setting ['height'];
		$data ['width'] = $setting ['width'];
		
		$data ['module'] = $module ++;
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/module/blank.tpl' )) {
			return $this->load->view ( $this->config->get ( 'config_template' ) . '/template/module/blank.tpl', $data );
		} else {
			return $this->load->view ( 'default/template/module/blank.tpl', $data );
		}
	}
}
