<?php

/**
 * contact.php
 *
 * Contact message management
 *
 * @author     Makai Lajos
 * @copyright  2015
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://opencart2oauth.opencart-api.com/schema_v2.0_oauth/
 */
require_once (DIR_SYSTEM . 'engine/restcontroller.php');
class ControllerRestContact extends RestController {
	private $error = array ();
	/*
	 * Contact message
	 */
	public function send() {
		$this->checkPlugin ();
		
		$json = array (
				'success' => false 
		);
		
		if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
			
			$requestjson = file_get_contents ( 'php://input' );
			
			$postData = json_decode ( $requestjson, true );
			
			$this->language->load ( 'information/contact' );
			
			if ($this->validate ( $postData )) {
				if (VERSION == '2.0.0.0' || VERSION == '2.0.1.0' || VERSION == '2.0.1.1') {
					$mail = new Mail ( $this->config->get ( 'config_mail' ) );
				} else {
					$mail = new Mail ();
					$mail->protocol = $this->config->get ( 'config_mail_protocol' );
					$mail->parameter = $this->config->get ( 'config_mail_parameter' );
					
					if (VERSION == '2.0.2.0') {
						$mail->smtp_hostname = $this->config->get ( 'config_mail_smtp_host' );
					} else {
						$mail->smtp_hostname = $this->config->get ( 'config_mail_smtp_hostname' );
					}
					
					$mail->smtp_username = $this->config->get ( 'config_mail_smtp_username' );
					$mail->smtp_password = html_entity_decode ( $this->config->get ( 'config_mail_smtp_password' ), ENT_QUOTES, 'UTF-8' );
					$mail->smtp_port = $this->config->get ( 'config_mail_smtp_port' );
					$mail->smtp_timeout = $this->config->get ( 'config_mail_smtp_timeout' );
				}
				
				$mail->setTo ( $this->config->get ( 'config_email' ) );
				$mail->setFrom ( $postData ['email'] );
				$mail->setSender ( html_entity_decode ( $postData ['name'], ENT_QUOTES, 'UTF-8' ) );
				$mail->setSubject ( html_entity_decode ( sprintf ( $this->language->get ( 'email_subject' ), $postData ['name'] ), ENT_QUOTES, 'UTF-8' ) );
				$mail->setText ( $postData ['enquiry'] );
				$mail->send ();
				
				$json = array (
						'success' => true,
						'error' => $this->language->get ( 'text_success' ) 
				);
			} else {
				$json = array (
						'success' => false,
						'error' => $this->error 
				);
			}
		}
		
		$this->sendResponse ( $json );
	}
	protected function validate($post) {
		if (! isset ( $post ['name'] ) || (utf8_strlen ( $post ['name'] ) < 3) || (utf8_strlen ( $post ['name'] ) > 32)) {
			$this->error ['name'] = $this->language->get ( 'error_name' );
		}
		
		if (! isset ( $post ['email'] ) || ! preg_match ( '/^[^\@]+@.*\.[a-z]{2,6}$/i', $post ['email'] )) {
			$this->error ['email'] = $this->language->get ( 'error_email' );
		}
		
		if (! isset ( $post ['enquiry'] ) || (utf8_strlen ( $post ['enquiry'] ) < 10) || (utf8_strlen ( $post ['enquiry'] ) > 3000)) {
			$this->error ['enquiry'] = $this->language->get ( 'error_enquiry' );
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
}