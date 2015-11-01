<?php
class ControllerCheckoutSms extends Controller {
	public function index(){
		$sms = new Sms();
		$sms->username = $this->config->get('way2mint_username');
		$sms->password = $this->config->get('way2mint_password');
		$sms->setfrom($this->config->get('way2mint_mp_from'));
		$sms->response_format = 'json';
		$sms->setto('9967296963,9987520964');
		$sms->template($this->config->get('way2mint_mp_tpl'));
		$param = explode(',',$this->config->get('way2mint_mp_param'));
		$params['templateParameters['.$param[0].']'] = "Ashwin";//$seller_info['firstname'];
		$params['templateParameters['.$param[1].']'] = "Narkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZopeNarkhede\nZope";//$data['text'];
		$sms->parameters($params);
		$sms->send();
	}
	
	
}