<?php
class ControllerCheckoutServiceCheck extends Controller {
	private $error = array();
	public function index(){
		$json = array();
		if (isset($this->request->post['postcode'])){
			$this->load->model('checkout/servicecheck');
			$data = array('postcode'=>$this->request->post['postcode']);
			$result = $this->model_checkout_servicecheck->checkpostcode($data);
			if ($result){
				$this->load->model('extension/extension');
				$results = $this->model_extension_extension->getExtensions('payment');
				
				if ($result['service'] && $result['shipping']){
					$address = array(
							'country_id'=> $result['country_id'],
							'zone_id'=> $result['zone_id'],
							'postcode'=> $result['postcode']
					);
					$total = 1000;
					$this->load->model('payment/cod');
					$method = $this->model_payment_cod->getMethod($address, $total);
					if ($method) {
						$json['success'] = array('text'	=>	$method['title']);
					} else {
						$json['success'] = array('text'	=>	"COD is not available. Service is available");
					}
				} else {
					$json['success'] = array('text'	=>	"Servive is not available in your location");
				}
			} else {
				$json['error'] = array('text'=>'Please enter a valid postcode..!');
			}
		}
		$this->response->setOutput(json_encode($json));
	}
}