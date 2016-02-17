<?php
class ControllerModuleCd extends Controller {
	public function index() {
		$data = array ();
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/module/dashboard.tpl' )) {
			return $this->load->view ( $this->config->get ( 'config_template' ) . '/template/module/dashboard.tpl', $data );
		} else {
			return $this->load->view ( 'default/template/module/dashboard.tpl', $data );
		}
	}
}
