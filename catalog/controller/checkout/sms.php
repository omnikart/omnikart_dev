<?php
class ControllerCheckoutSms extends Controller {
	public function index(){
		$sms = new Sms();
		$sms->username = $this->config->get('way2mint_username');
		$sms->password = $this->config->get('way2mint_password');
		$sms->setfrom($this->config->get('way2mint_mp_from'));
		$sms->response_format = 'json';
		$sms->setto('9967296963');
		$sms->template($this->config->get('way2mint_mp_tpl'));
		$param = explode(',',$this->config->get('way2mint_mp_param'));
		$params['templateParameters['.$param[0].']'] = "Synthoex";
		$params['templateParameters['.$param[1].']'] = "2 Pcs - Kleenguard G40 Latex Coated Gloves (M) 97271";
		$sms->parameters($params);
		$sms->send();
	}
	
	
}
