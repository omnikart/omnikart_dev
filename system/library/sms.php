<?php
class Sms {
	protected $destination;
	protected $sender_id;
	protected $template_name;
	protected $templateParameters = array();

	public $username;
	public $password;
	public $url;
	public $response_format;
	
	public function __construct($config = array()) {
		foreach ($config as $key => $value) {
			$this->$key = $value;
		}
	}
	public function setto($to){
		$this->destination = $to;
	}
	public function setfrom($from){
		$this->sender_id = $from;
	}	
	public function template($template){
		$this->template_name = $template;
	}
	public function parameters($parameters){
		$this->templateParameters = $parameters;
		
	}
	
	
	public function send() {
		$url = "https://websms.way2mint.com/index.php/web_service/sendSMS?";
		
		$parameters = array('username'=> $this->username,
				'password'=>$this->password,
				'destination'=>$this->destination,
				'template_name'=>$this->template_name,
				'response_format'=>$this->response_format,
				'sender_id'=>$this->sender_id
		);
		
		$parameters = array_merge($parameters,$this->templateParameters);

		$url .= http_build_query($parameters);
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch,CURLOPT_HEADER, false);
		
		$output=curl_exec($ch);
		curl_close($ch);
	}
}