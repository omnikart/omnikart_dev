<?php
class ModelModuleEnquiry extends Model {
	public function addenquiry($data = array()){
		$this->db->query("INSERT INTO ".DB_PREFIX."enquiry SET query = '".$data['query']."', user_info = '".$data['user_info']."', file = '".$data['file']."', status = 1");
		
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			
		$mail->setTo('sales@omnikart.com');
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode("Product Enquiry", ENT_QUOTES, 'UTF-8'));
		$mail->setText($data['user_info']);
		$mail->send();
	}
}