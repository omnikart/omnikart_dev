<?php  

/**
 * forgotten.php
 *
 * Forgotten password
 *
 * @author     Makai Lajos
 * @copyright  2015
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://opencart2oauth.opencart-api.com/schema_v2.0_oauth/
 */
require_once(DIR_SYSTEM . 'engine/restcontroller.php');

class ControllerRestForgotten extends RestController {
	
	/*
	* forgotten password
	*/
	public function forgotten() {

		$this->checkPlugin();
		
		$json = array('success' => true);
		
		if ( $_SERVER['REQUEST_METHOD'] === 'POST'){
			
			$requestjson = file_get_contents('php://input');
            $post = json_decode($requestjson, true);

			if ($this->customer->isLogged()) {
				$json['error']		= "User is already logged";
				$json['success']	= false;			
			} else {
                $this->load->model('account/customer');
                $this->load->language('account/forgotten');
                $this->load->language('mail/forgotten');

                $error = $this->validate($post);
                if(empty($error)){
                    $password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);

                    $this->model_account_customer->editPassword($post['email'], $password);

                    $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

                    $message  = sprintf($this->language->get('text_greeting'), $this->config->get('config_name')) . "\n\n";
                    $message .= $this->language->get('text_password') . "\n\n";
                    $message .= $password;

                    if(VERSION == '2.0.0.0' || VERSION == '2.0.1.0' || VERSION == '2.0.1.1') {
                        $mail = new Mail($this->config->get('config_mail'));
                    } else {
                        $mail = new Mail();
                        $mail->protocol = $this->config->get('config_mail_protocol');
                        $mail->parameter = $this->config->get('config_mail_parameter');

                        if(VERSION == '2.0.2.0'){
                            $mail->smtp_hostname = $this->config->get('config_mail_smtp_host');
                        } else {
                            $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
                        }

                        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
                        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
                        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
                        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

                    }

                    $mail->setTo($post['email']);
                    $mail->setFrom($this->config->get('config_email'));
                    $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
                    $mail->setSubject($subject);
                    $mail->setText($message);
                    $mail->send();

                    // Add to activity log
                    $customer_info = $this->model_account_customer->getCustomerByEmail($post['email']);

                    if ($customer_info) {
                        $this->load->model('account/activity');

                        $activity_data = array(
                            'customer_id' => $customer_info['customer_id'],
                            'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
                        );

                        $this->model_account_activity->addActivity('forgotten', $activity_data);
                    }
                } else {
                    $json["error"]		= $error;
                    $json["success"]	= false;
                }
            }
		} else {
				$json["error"]		= "Only POST request method allowed";
				$json["success"]	= false;
		}

		$this->sendResponse($json);
	
	}

    protected function validate($post) {
        $error = array();
        if (!isset($post['email'])) {
            $error[] = $this->language->get('error_email');
        } elseif (!$this->model_account_customer->getTotalCustomersByEmail($post['email'])) {
            $error[] = $this->language->get('error_email');
        }
        return $error;
    }
}
