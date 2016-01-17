<?php
class ControllerPaymentCod extends Controller {
	public function index() {
		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['text_loading'] = $this->language->get('text_loading');

		$data['continue'] = $this->url->link('checkout/success');
		
		$data['phone'] = isset($this->session->data['shippingaddress']['phone'])?$this->session->data['shippingaddress']['phone']:'';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cod.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/cod.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/cod.tpl', $data);
		}
	}

	public function otp() {
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				$otp = $this->request->post['otp'];
				if ($otp == $this->session->data['otp']) {
						$this->session->data['otp_verify'] = true;
						$json['success'] = true;
						echo json_encode($json); 
				} else {
						$this->session->data['otp_verify'] = false;
						$json['success'] = false;
						echo json_encode($json); 
				}
		} else {
			if ($this->session->data['payment_method']['code'] == 'cod') {
				if (isset($this->session->data['otp'])) {
					$otp = $this->session->data['otp']; 
				} else {
					$otp = mt_rand(100000, 999999);
					$this->session->data['otp'] = $otp;
				}
				
				if ((isset($this->request->get['otp_number']) && !empty($this->request->get['otp_number'])) || (isset($this->session->data['shippingaddress']['phone']) && !empty($this->session->data['shippingaddress']['phone']))) {
					$sms = new Sms();
					$sms->username = $this->config->get('way2mint_username');
					$sms->password = $this->config->get('way2mint_password');
					$sms->setfrom($this->config->get('way2mint_otp_from'));
					$sms->response_format = 'json';
					$sms->setto(trim($this->request->get['otp_number']));
					$sms->template($this->config->get('way2mint_otp_tpl'));
					$param = explode(',',$this->config->get('way2mint_otp_param'));
					$params['templateParameters['.$param[0].']'] = $otp;
					$sms->parameters($params);
					$sms->send();
					$json['success'] = true;
					echo json_encode($json); 
				}
			}
		}
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'cod') {
			$this->load->model('checkout/order');

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('cod_order_status_id'));
		}
	}
}
