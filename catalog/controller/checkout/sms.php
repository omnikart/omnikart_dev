<?php
class ControllerCheckoutSms extends Controller {
	public function index(){
		$sms = new Sms();
		$sms->username = 'omnikart';
		$sms->password= 'Shraddha_9960';
		$sms->response_format = 'json';
		$sms->setto('9773581721');
		$sms->setfrom('OMNKRT');
		$sms->template('Order Products');
		$sms->parameters(array('templateParameters[R]'=>'Dumb User','templateParameters[S]'=>"Mandar Zope\nAbhishek Shinghane"));
		$sms->send();
		echo "hello";
		
	}
	
	
}