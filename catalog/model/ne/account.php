<?php
// -----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
// -----------------------------------------------------
class ModelNeAccount extends Model {
	public function subscribe($data) {
		$query = $this->db->query ( "SELECT history_id, stats_personal_id FROM `" . DB_PREFIX . "ne_stats_personal` WHERE stats_personal_id = '" . ( int ) $data ['uid'] . "' AND email = '" . $this->db->escape ( $data ['email'] ) . "'" );
		if (! $query->row) {
			return false;
		}
		
		$result = false;
		
		$email = $data ['email'];
		
		$query = $this->db->query ( "SELECT customer_id FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape ( $email ) . "' AND store_id = '" . ( int ) $this->config->get ( 'config_store_id' ) . "'" );
		$info = $query->row;
		
		if ($info) {
			$this->db->query ( "UPDATE " . DB_PREFIX . "customer SET `newsletter` = '" . ( int ) ! $this->config->get ( 'ne_subscribe_confirmation' ) . "' WHERE customer_id = '" . ( int ) $info ['customer_id'] . "'" );
			$result = true;
		}
		
		$query = $this->db->query ( "SELECT marketing_id FROM " . DB_PREFIX . "ne_marketing WHERE email = '" . $this->db->escape ( $email ) . "' AND store_id = '" . ( int ) $this->config->get ( 'config_store_id' ) . "'" );
		$info = $query->row;
		
		if ($info) {
			$this->db->query ( "UPDATE " . DB_PREFIX . "ne_marketing SET `subscribed` = '" . ( int ) ! $this->config->get ( 'ne_subscribe_confirmation' ) . "' WHERE marketing_id = '" . ( int ) $info ['marketing_id'] . "'" );
			$result = true;
		}
		
		if ($result && $this->config->get ( 'ne_subscribe_confirmation' )) {
			require_once (DIR_SYSTEM . 'library/mail_ne.php');
			
			$mail = new Mail_NE ();
			if ($this->config->get ( 'ne_use_smtp' )) {
				$mail_config = $this->config->get ( 'ne_smtp' );
				$mail->protocol = $mail_config [$data ['store_id']] ['protocol'];
				$mail->parameter = $mail_config [$data ['store_id']] ['parameter'];
				$mail->hostname = $mail_config [$data ['store_id']] ['host'];
				$mail->username = $mail_config [$data ['store_id']] ['username'];
				$mail->password = html_entity_decode ( $mail_config [$data ['store_id']] ['password'], ENT_QUOTES, 'UTF-8' );
				$mail->port = $mail_config [$data ['store_id']] ['port'];
				$mail->timeout = $mail_config [$data ['store_id']] ['timeout'];
				$mail->setFrom ( $mail_config [$data ['store_id']] ['email'] );
			} else {
				$mail->protocol = $this->config->get ( 'config_mail_protocol' );
				$mail->parameter = $this->config->get ( 'config_mail_parameter' );
				$mail->hostname = $this->config->get ( 'config_mail_smtp_hostname' );
				$mail->username = $this->config->get ( 'config_mail_smtp_username' );
				$mail->password = html_entity_decode ( $this->config->get ( 'config_mail_smtp_password' ), ENT_QUOTES, 'UTF-8' );
				$mail->port = $this->config->get ( 'config_mail_smtp_port' );
				$mail->timeout = $this->config->get ( 'config_mail_smtp_timeout' );
				$mail->setFrom ( $this->config->get ( 'config_email' ) );
			}
			$mail->setTo ( $email );
			$mail->setSender ( $this->config->get ( 'config_name' ) );
			
			$subject = $this->config->get ( 'ne_subscribe_confirmation_subject' );
			$subject = $subject [$this->config->get ( 'config_store_id' )] [$this->config->get ( 'config_language_id' )];
			
			$message = $this->config->get ( 'ne_subscribe_confirmation_message' );
			$message = $message [$this->config->get ( 'config_store_id' )] [$this->config->get ( 'config_language_id' )];
			
			if ($info ['firstname']) {
				$info ['firstname'] = mb_convert_case ( $info ['firstname'], MB_CASE_TITLE, 'UTF-8' );
			}
			
			if ($info ['lastname']) {
				$info ['lastname'] = mb_convert_case ( $info ['lastname'], MB_CASE_TITLE, 'UTF-8' );
			}
			
			$link = HTTPS_SERVER . 'index.php?route=ne/subscribe/confirm&code=' . md5 ( $this->config->get ( 'ne_key' ) . $email ) . '&email=' . $email;
			
			$subject = str_replace ( array (
					'{name}',
					'{lastname}',
					'{email}',
					'{link}' 
			), array (
					$info ['firstname'],
					$info ['lastname'],
					$email,
					$link 
			), $subject );
			$message = str_replace ( array (
					'{name}',
					'{lastname}',
					'{email}',
					'{link}' 
			), array (
					$info ['firstname'],
					$info ['lastname'],
					$email,
					$link 
			), $message );
			
			$message = html_entity_decode ( $message, ENT_QUOTES, 'UTF-8' );
			
			$mail->setSubject ( $subject );
			$mail->setHtml ( $message );
			
			$mail->send ();
		}
		
		return $result;
	}
	public function unsubscribe($data) {
		$query = $this->db->query ( "SELECT history_id, stats_personal_id FROM `" . DB_PREFIX . "ne_stats_personal` WHERE stats_personal_id = '" . ( int ) $data ['uid'] . "' AND email = '" . $this->db->escape ( $data ['email'] ) . "'" );
		if (! $query->row) {
			return false;
		}
		
		$result = false;
		
		$email = $data ['email'];
		
		$query = $this->db->query ( "SELECT customer_id FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape ( $email ) . "' AND store_id = '" . ( int ) $this->config->get ( 'config_store_id' ) . "'" );
		$info = $query->row;
		
		if ($info) {
			$this->db->query ( "UPDATE " . DB_PREFIX . "customer SET `newsletter` = '0' WHERE customer_id = '" . ( int ) $info ['customer_id'] . "'" );
			$result = true;
		}
		
		$query = $this->db->query ( "SELECT marketing_id FROM " . DB_PREFIX . "ne_marketing WHERE email = '" . $this->db->escape ( $email ) . "' AND store_id = '" . ( int ) $this->config->get ( 'config_store_id' ) . "'" );
		$info = $query->row;
		
		if ($info) {
			$this->db->query ( "UPDATE " . DB_PREFIX . "ne_marketing SET `subscribed` = '0' WHERE marketing_id = '" . ( int ) $info ['marketing_id'] . "'" );
			$result = true;
		}
		
		return $result;
	}
	public function confirm($email) {
		$this->db->query ( "UPDATE " . DB_PREFIX . "customer SET `newsletter` = '1' WHERE email = '" . $this->db->escape ( $email ) . "' AND store_id = '" . ( int ) $this->config->get ( 'config_store_id' ) . "'" );
		
		if ($this->db->countAffected ()) {
			return true;
		}
		
		$this->db->query ( "UPDATE " . DB_PREFIX . "ne_marketing SET `subscribed` = '1' WHERE email = '" . $this->db->escape ( $email ) . "' AND store_id = '" . ( int ) $this->config->get ( 'config_store_id' ) . "'" );
		
		if ($this->db->countAffected ()) {
			return true;
		}
		
		return false;
	}
}