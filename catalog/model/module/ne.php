<?php
// -----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
// -----------------------------------------------------
class ModelModuleNe extends Model {
	public function subscribe($data, $salt, $list = array()) {
		if (! isset ( $data ['lastname'] )) {
			$data ['lastname'] = '';
		}
		
		if (! isset ( $data ['name'] )) {
			$data ['name'] = '';
		}
		
		$code = md5 ( $salt . $data ['email'] );
		
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "ne_marketing WHERE email = '" . $this->db->escape ( $data ['email'] ) . "' AND `subscribed` = '1' AND store_id = '" . ( int ) $this->config->get ( 'config_store_id' ) . "'" );
		
		if (! $query->num_rows) {
			
			$this->db->query ( "INSERT INTO " . DB_PREFIX . "ne_marketing SET email = '" . $this->db->escape ( $data ['email'] ) . "', firstname = '" . $this->db->escape ( $data ['name'] ) . "', lastname = '" . $this->db->escape ( $data ['lastname'] ) . "', code = '" . $this->db->escape ( $code ) . "', store_id = '" . ( int ) $this->config->get ( 'config_store_id' ) . "', subscribed = '" . ( int ) ! $this->config->get ( 'ne_subscribe_confirmation' ) . "' ON DUPLICATE KEY UPDATE subscribed = '" . ( int ) ! $this->config->get ( 'ne_subscribe_confirmation' ) . "', firstname = '" . $this->db->escape ( $data ['name'] ) . "', lastname = '" . $this->db->escape ( $data ['lastname'] ) . "'" );
			if ($this->db->countAffected () > 0) {
				if ($list) {
					$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "ne_marketing WHERE email = '" . $this->db->escape ( $data ['email'] ) . "' AND store_id = '" . ( int ) $this->config->get ( 'config_store_id' ) . "'" );
					$this->db->query ( "DELETE FROM " . DB_PREFIX . "ne_marketing_to_list WHERE marketing_id = '" . ( int ) $query->row ['marketing_id'] . "'" );
					foreach ( $list as $id ) {
						$this->db->query ( "INSERT INTO " . DB_PREFIX . "ne_marketing_to_list SET marketing_id = '" . ( int ) $query->row ['marketing_id'] . "', marketing_list_id = '" . ( int ) $id . "'" );
					}
				}
				
				if ($this->config->get ( 'ne_subscribe_confirmation' )) {
					require_once (DIR_SYSTEM . 'library/mail_ne.php');
					
					$mail = new Mail_NE ();
					if ($this->config->get ( 'ne_use_smtp' )) {
						$mail_config = $this->config->get ( 'ne_smtp' );
						$mail->protocol = $mail_config [$this->config->get ( 'config_store_id' )] ['protocol'];
						$mail->parameter = $mail_config [$this->config->get ( 'config_store_id' )] ['parameter'];
						$mail->hostname = $mail_config [$this->config->get ( 'config_store_id' )] ['host'];
						$mail->username = $mail_config [$this->config->get ( 'config_store_id' )] ['username'];
						$mail->password = html_entity_decode ( $mail_config [$this->config->get ( 'config_store_id' )] ['password'], ENT_QUOTES, 'UTF-8' );
						$mail->port = $mail_config [$this->config->get ( 'config_store_id' )] ['port'];
						$mail->timeout = $mail_config [$this->config->get ( 'config_store_id' )] ['timeout'];
						$mail->setFrom ( $mail_config [$this->config->get ( 'config_store_id' )] ['email'] );
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
					$mail->setTo ( $data ['email'] );
					$mail->setSender ( $this->config->get ( 'config_name' ) );
					
					$subject = $this->config->get ( 'ne_subscribe_confirmation_subject' );
					$subject = $subject [$this->config->get ( 'config_store_id' )] [$this->config->get ( 'config_language_id' )];
					
					$message = $this->config->get ( 'ne_subscribe_confirmation_message' );
					$message = $message [$this->config->get ( 'config_store_id' )] [$this->config->get ( 'config_language_id' )];
					
					if ($data ['name']) {
						$data ['name'] = mb_convert_case ( $data ['name'], MB_CASE_TITLE, 'UTF-8' );
					}
					
					if ($data ['lastname']) {
						$data ['lastname'] = mb_convert_case ( $data ['lastname'], MB_CASE_TITLE, 'UTF-8' );
					}
					
					$link = HTTPS_SERVER . 'index.php?route=ne/subscribe/confirm&code=' . $code . '&email=' . $data ['email'];
					
					$subject = str_replace ( array (
							'{name}',
							'{lastname}',
							'{email}',
							'{link}' 
					), array (
							$data ['name'],
							$data ['lastname'],
							$data ['email'],
							$link 
					), $subject );
					$message = str_replace ( array (
							'{name}',
							'{lastname}',
							'{email}',
							'{link}' 
					), array (
							$data ['name'],
							$data ['lastname'],
							$data ['email'],
							$link 
					), $message );
					
					$message = html_entity_decode ( $message, ENT_QUOTES, 'UTF-8' );
					
					$mail->setSubject ( $subject );
					$mail->setHtml ( $message );
					
					$mail->send ();
				}
				
				return true;
			}
		}
		
		if ($list) {
			$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "ne_marketing WHERE email = '" . $this->db->escape ( $data ['email'] ) . "' AND store_id = '" . ( int ) $this->config->get ( 'config_store_id' ) . "'" );
			$this->db->query ( "DELETE FROM " . DB_PREFIX . "ne_marketing_to_list WHERE marketing_id = '" . ( int ) $query->row ['marketing_id'] . "'" );
			foreach ( $list as $id ) {
				$this->db->query ( "INSERT INTO " . DB_PREFIX . "ne_marketing_to_list SET marketing_id = '" . ( int ) $query->row ['marketing_id'] . "', marketing_list_id = '" . ( int ) $id . "'" );
			}
		}
		
		return false;
	}
	public function getSubscribeBox($subscribe_box_id) {
		$subscribe_box_info = $this->cache->get ( 'ne_subscribe_box.' . ( int ) $subscribe_box_id );
		if (! $subscribe_box_info) {
			$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "ne_subscribe_box WHERE subscribe_box_id = '" . ( int ) $subscribe_box_id . "'" );
			if ($query->row) {
				$subscribe_box_info = array_merge ( $query->row, unserialize ( $query->row ['data'] ) );
				unset ( $subscribe_box_info ['data'] );
				$this->cache->set ( 'ne_subscribe_box.' . ( int ) $subscribe_box_id, $subscribe_box_info );
			} else {
				return false;
			}
		}
		return $subscribe_box_info;
	}
}

?>