<?php
// -----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
// -----------------------------------------------------
class ModelNeCron extends Model {
	public function get_queue() {
		$query = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "ne_queue WHERE `locked` = '0' ORDER BY queue_id ASC LIMIT 0, " . ( int ) $this->config->get ( 'ne_throttle_count' ) );
		return $query->rows;
	}
	public function remove_queue($queue_id) {
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "ne_queue WHERE queue_id = '" . ( int ) $queue_id . "'" );
	}
	public function get_newsletter($history_id) {
		$query = $this->db->query ( "SELECT n.history_id as history_id, `subject`, `message`, `text_message`, `text_message_products`, `store_id`, `template_id`, `language_id`, `to`, `datetime`, `store_id`, `queue`, `recipients`, `views` FROM " . DB_PREFIX . "ne_stats as s LEFT JOIN " . DB_PREFIX . "ne_history as n ON (s.history_id = n.history_id) WHERE n.history_id = '" . ( int ) $history_id . "'" );
		return $query->row;
	}
	public function add_stat($history_id, $email) {
		$this->db->query ( "INSERT IGNORE INTO " . DB_PREFIX . "ne_stats_personal SET history_id = '" . ( int ) $history_id . "', email = '" . $this->db->escape ( $email ) . "', views = '0'" );
		return $this->db->getLastId ();
	}
	public function update_stat($history_id) {
		$this->db->query ( "UPDATE " . DB_PREFIX . "ne_stats SET queue = queue + 1 WHERE history_id = '" . ( int ) $history_id . "'" );
	}
	public function get_scheduled($date, $time, $day) {
		$query = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "ne_schedule WHERE (`active` = 1 AND `time` = '" . ( int ) $time . "') AND ((`recurrent` = '0' AND `date` = '" . $this->db->escape ( $date ) . "' AND `date_next` = '0000-00-00') OR (`recurrent` = '1' AND `frequency` = '1' AND (`date_next` = '0000-00-00' OR `date_next` = '" . $this->db->escape ( $date ) . "')) OR (`recurrent` = '1' AND (`frequency` = '7' OR `frequency` = '30') AND `day` = '" . ( int ) $day . "' AND (`date_next` = '0000-00-00' OR `date_next` = '" . $this->db->escape ( $date ) . "'))) ORDER BY schedule_id ASC" );
		return $query->rows;
	}
	public function schedule_next($schedule_id, $date) {
		$this->db->query ( "UPDATE " . DB_PREFIX . "ne_schedule SET date_next = '" . $this->db->escape ( $date ) . "' WHERE schedule_id = '" . ( int ) $schedule_id . "'" );
	}
	public function schedule_inactive($schedule_id) {
		$this->db->query ( "UPDATE " . DB_PREFIX . "ne_schedule SET `active` = '0' WHERE schedule_id = '" . ( int ) $schedule_id . "'" );
	}
	public function getCustomers($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) LEFT JOIN " . DB_PREFIX . "ne_language_map lm ON c.email = lm.c_email WHERE cgd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "'";
		
		$implode = array ();
		
		if (! empty ( $data ['filter_name'] )) {
			$implode [] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape ( $data ['filter_name'] ) . "%'";
		}
		
		if (! empty ( $data ['filter_email'] )) {
			$implode [] = "c.email LIKE '" . $this->db->escape ( $data ['filter_email'] ) . "%'";
		}
		
		if (isset ( $data ['filter_newsletter'] ) && ! is_null ( $data ['filter_newsletter'] )) {
			$implode [] = "c.newsletter = '" . ( int ) $data ['filter_newsletter'] . "'";
		}
		
		if (! empty ( $data ['filter_customer_group_id'] )) {
			$implode [] = "c.customer_group_id = '" . ( int ) $data ['filter_customer_group_id'] . "'";
		}
		
		if (! empty ( $data ['filter_ip'] )) {
			$implode [] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape ( $data ['filter_ip'] ) . "')";
		}
		
		if (isset ( $data ['filter_status'] ) && ! is_null ( $data ['filter_status'] )) {
			$implode [] = "c.status = '" . ( int ) $data ['filter_status'] . "'";
		}
		
		if (isset ( $data ['filter_approved'] ) && ! is_null ( $data ['filter_approved'] )) {
			$implode [] = "c.approved = '" . ( int ) $data ['filter_approved'] . "'";
		}
		
		if (! empty ( $data ['filter_date_added'] )) {
			$implode [] = "DATE(c.date_added) = DATE('" . $this->db->escape ( $data ['filter_date_added'] ) . "')";
		}
		
		$implode [] = "NOT EXISTS (SELECT 1 FROM " . DB_PREFIX . "ne_blacklist bl WHERE bl.email = c.email)";
		
		if ($implode) {
			$sql .= " AND " . implode ( " AND ", $implode );
		}
		
		$sort_data = array (
				'name',
				'c.email',
				'customer_group',
				'c.status',
				'c.approved',
				'c.ip',
				'c.date_added' 
		);
		
		if (isset ( $data ['sort'] ) && in_array ( $data ['sort'], $sort_data )) {
			$sql .= " ORDER BY " . $data ['sort'];
		} else {
			$sql .= " ORDER BY name";
		}
		
		if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 20;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
		}
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function getCustomer($customer_id) {
		$query = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "ne_language_map lm ON c.email = lm.c_email WHERE c.customer_id = '" . ( int ) $customer_id . "' AND NOT EXISTS (SELECT 1 FROM " . DB_PREFIX . "ne_blacklist bl WHERE bl.email = c.email)" );
		
		return $query->row;
	}
	public function getAffiliates($data = array()) {
		$sql = "SELECT *, CONCAT(a.firstname, ' ', a.lastname) AS name, (SELECT SUM(at.amount) FROM " . DB_PREFIX . "affiliate_transaction at WHERE at.affiliate_id = a.affiliate_id GROUP BY at.affiliate_id) AS balance FROM " . DB_PREFIX . "affiliate a LEFT JOIN " . DB_PREFIX . "ne_language_map lm ON a.email = lm.c_email";
		
		$implode = array ();
		
		if (! empty ( $data ['filter_name'] )) {
			$implode [] = "CONCAT(a.firstname, ' ', a.lastname) LIKE '" . $this->db->escape ( $data ['filter_name'] ) . "%'";
		}
		
		if (! empty ( $data ['filter_email'] )) {
			$implode [] = "LCASE(a.email) = '" . $this->db->escape ( utf8_strtolower ( $data ['filter_email'] ) ) . "'";
		}
		
		if (! empty ( $data ['filter_code'] )) {
			$implode [] = "a.code = '" . $this->db->escape ( $data ['filter_code'] ) . "'";
		}
		
		if (isset ( $data ['filter_status'] ) && ! is_null ( $data ['filter_status'] )) {
			$implode [] = "a.status = '" . ( int ) $data ['filter_status'] . "'";
		}
		
		if (isset ( $data ['filter_approved'] ) && ! is_null ( $data ['filter_approved'] )) {
			$implode [] = "a.approved = '" . ( int ) $data ['filter_approved'] . "'";
		}
		
		if (! empty ( $data ['filter_date_added'] )) {
			$implode [] = "DATE(a.date_added) = DATE('" . $this->db->escape ( $data ['filter_date_added'] ) . "')";
		}
		
		$implode [] = "NOT EXISTS (SELECT 1 FROM " . DB_PREFIX . "ne_blacklist bl WHERE bl.email = a.email)";
		
		if ($implode) {
			$sql .= " WHERE " . implode ( " AND ", $implode );
		}
		
		$sort_data = array (
				'name',
				'a.email',
				'a.code',
				'a.status',
				'a.approved',
				'a.date_added' 
		);
		
		if (isset ( $data ['sort'] ) && in_array ( $data ['sort'], $sort_data )) {
			$sql .= " ORDER BY " . $data ['sort'];
		} else {
			$sql .= " ORDER BY name";
		}
		
		if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 20;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
		}
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function getAffiliate($affiliate_id) {
		$query = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "affiliate a LEFT JOIN " . DB_PREFIX . "ne_language_map lm ON a.email = lm.c_email WHERE affiliate_id = '" . ( int ) $affiliate_id . "' AND NOT EXISTS (SELECT 1 FROM " . DB_PREFIX . "ne_blacklist bl WHERE bl.email = a.email)" );
		
		return $query->row;
	}
	public function getStore($store_id) {
		$query = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "store WHERE store_id = '" . ( int ) $store_id . "'" );
		
		return $query->row;
	}
	public function getEmailsByProductsOrdered($products) {
		$implode = array ();
		
		foreach ( $products as $product_id ) {
			$implode [] = "op.product_id = '" . $product_id . "'";
		}
		
		$query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) LEFT JOIN " . DB_PREFIX . "ne_language_map lm ON (o.email = lm.c_email) WHERE (" . implode ( " OR ", $implode ) . ") AND o.order_status_id <> '0' AND NOT EXISTS (SELECT 1 FROM " . DB_PREFIX . "ne_blacklist bl WHERE bl.email = o.email) GROUP BY o.email" );
		
		return $query->rows;
	}
	public function send($data) {
		require_once (DIR_SYSTEM . 'library/mail_ne.php');
		
		$message = '<html dir="ltr" lang="en">' . PHP_EOL;
		$message .= '<head>' . PHP_EOL;
		$message .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . PHP_EOL;
		$message .= '<title>' . $data ['subject'] . '</title>' . PHP_EOL;
		if (! empty ( $data ['custom_css'] )) {
			$message .= html_entity_decode ( $data ['custom_css'], ENT_COMPAT, 'UTF-8' ) . PHP_EOL;
		}
		$message .= '</head>' . PHP_EOL;
		$message .= '<body style="padding:0;margin:0;">' . $data ['message'] . '</body>' . PHP_EOL;
		$message .= '</html>' . PHP_EOL;
		
		$message = str_replace ( array (
				chr ( 3 ) 
		), '', $message );
		
		$emails_count = count ( $data ['emails'] );
		
		if (! isset ( $data ['history_id'] )) {
			$newsletter_id = $this->addHistory ( array (
					'to' => $data ['to'],
					'subject' => $data ['subject'],
					'message' => $data ['message'],
					'store_id' => $data ['store_id'],
					'template_id' => $data ['template_id'],
					'language_id' => $data ['language_id'],
					'queue' => ($this->config->get ( 'ne_throttle' ) && ($emails_count > $this->config->get ( 'ne_throttle_count' ))) ? 0 : $emails_count,
					'recipients' => $emails_count 
			) );
		} else {
			$newsletter_id = ( int ) $data ['history_id'];
		}
		
		if (isset ( $data ['store_id'] ) && $data ['store_id'] > 0) {
			$store = $this->getStore ( $data ['store_id'] );
			if ($store) {
				$url = rtrim ( $store ['url'], '/' ) . '/';
			} else {
				$url = HTTP_SERVER;
			}
		} else {
			$url = HTTP_SERVER;
		}
		
		libxml_use_internal_errors ( true );
		$dom = new DOMDocument ();
		$dom->loadHTML ( $message );
		foreach ( $dom->getElementsByTagName ( 'a' ) as $node ) {
			if ($node->hasAttribute ( 'href' )) {
				$link = $node->getAttribute ( 'href' );
				if ((strpos ( $link, 'http://' ) === 0) || (strpos ( $link, 'https://' ) === 0)) {
					$add_key = ((strpos ( $link, '{key}' ) !== false) || (strpos ( $link, '%7Bkey%7D' ) !== false));
					$node->setAttribute ( 'href', $url . 'index.php?route=ne/track/click&amp;link=' . urlencode ( base64_encode ( $link ) ) . '&amp;uid={uid}&amp;language=' . $data ['language_code'] . ($add_key ? '&amp;key={key}' : '') );
				}
			}
		}
		
		if ($this->config->get ( 'ne_embedded_images' )) {
			if (isset ( $this->request->server ['HTTPS'] ) && (($this->request->server ['HTTPS'] == 'on') || ($this->request->server ['HTTPS'] == '1'))) {
				$server = defined ( 'HTTPS_IMAGE' ) ? HTTPS_IMAGE : HTTPS_SERVER . 'image/';
			} else {
				$server = defined ( 'HTTP_IMAGE' ) ? HTTP_IMAGE : HTTP_SERVER . 'image/';
			}
			
			foreach ( $dom->getElementsByTagName ( 'img' ) as $node ) {
				if ($node->hasAttribute ( 'src' )) {
					$src = $node->getAttribute ( 'src' );
					$src = str_replace ( $server, DIR_IMAGE, $src );
					$node->setAttribute ( 'src', $this->base64_encode_image ( $src ) );
				}
			}
		}
		
		$message = $dom->saveHTML ();
		libxml_clear_errors ();
		
		$message .= '<div><img width="0" height="0" alt="" src="' . $url . 'index.php?route=ne/track/gif&amp;uid={uid}" /></div>';
		
		$store_info = $this->getStore ( $data ['store_id'] );
		if ($store_info) {
			$store_name = $store_info ['name'];
		} else {
			$store_name = $this->config->get ( 'config_name' );
		}
		
		$this->load->model ( 'setting/setting' );
		$store_info = $this->model_setting_setting->getSetting ( 'config', $data ['store_id'] );
		
		foreach ( $data ['emails'] as $email => $info ) {
			
			if ($this->config->get ( 'ne_throttle' )) {
				if ($emails_count > $this->config->get ( 'ne_throttle_count' )) {
					$this->db->query ( "INSERT INTO " . DB_PREFIX . "ne_queue SET email = '" . $this->db->escape ( $email ) . "', firstname = '" . $this->db->escape ( $info ['firstname'] ) . "', lastname = '" . $this->db->escape ( $info ['lastname'] ) . "', history_id = '" . ( int ) $newsletter_id . "'" );
					continue;
				}
			}
			
			if (isset ( $info ['queue_id'] )) {
				$this->db->query ( "UPDATE " . DB_PREFIX . "ne_queue SET `locked` = '1' WHERE queue_id = '" . ( int ) $info ['queue_id'] . "'" );
			}
			
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
				$mail->setFrom ( $store_info ['config_email'] );
			}
			$mail->setTo ( $email );
			
			$mail->setSender ( $store_name );
			
			if (isset ( $data ['attachments'] ) && $data ['attachments']) {
				foreach ( $data ['attachments'] as $attachment ) {
					$mail->addAttachment ( $attachment, basename ( $attachment ) );
				}
			}
			
			$subject_to_send = $data ['subject'];
			$message_to_send = str_replace ( array (
					'{key}',
					'%7Bkey%7D' 
			), md5 ( $this->config->get ( 'ne_key' ) . $email ), $message );
			
			if ($info) {
				$firstname = mb_convert_case ( $info ['firstname'], MB_CASE_TITLE, 'UTF-8' );
				$lastname = mb_convert_case ( $info ['lastname'], MB_CASE_TITLE, 'UTF-8' );
				
				$subject_to_send = str_replace ( array (
						'{name}',
						'{lastname}',
						'{email}' 
				), array (
						$firstname,
						$lastname,
						$email 
				), $subject_to_send );
				$message_to_send = str_replace ( array (
						'{name}',
						'{lastname}',
						'{email}' 
				), array (
						$firstname,
						$lastname,
						$email 
				), $message_to_send );
				
				if (isset ( $info ['reward'] )) {
					$subject_to_send = str_replace ( '{reward}', $info ['reward'], $subject_to_send );
					$message_to_send = str_replace ( '{reward}', $info ['reward'], $message_to_send );
				}
			}
			
			$this->db->query ( "INSERT IGNORE INTO " . DB_PREFIX . "ne_stats_personal SET history_id = '" . ( int ) $newsletter_id . "', email = '" . $this->db->escape ( $email ) . "', views = '0'" );
			$personal_id = $this->db->getLastId ();
			
			if (! $personal_id) {
				$personal_id = $this->db->query ( "SELECT stats_personal_id FROM " . DB_PREFIX . "ne_stats_personal WHERE history_id = '" . ( int ) $newsletter_id . "' AND email = '" . $this->db->escape ( $email ) . "'" )->row;
				if ($personal_id) {
					$personal_id = $personal_id ['stats_personal_id'];
				}
			}
			
			if ($personal_id) {
				$uid = urlencode ( base64_encode ( $email . '|' . $personal_id ) );
				$message_to_send = str_replace ( array (
						'{uid}',
						'%7Buid%7D' 
				), $uid, $message_to_send );
				$mail->addHeader ( 'X-NEMail', $uid );
			}
			
			$message_to_send = html_entity_decode ( $message_to_send, ENT_QUOTES, 'UTF-8' );
			$mail->setSubject ( $subject_to_send );
			
			if ($data ['text_message_products']) {
				$data ['text_message'] .= (PHP_EOL . PHP_EOL . $url . 'index.php?route=ne/show&uid=' . (isset ( $uid ) ? $uid : ''));
			}
			if ($data ['text_message']) {
				$mail->setText ( $data ['text_message'] );
			}
			
			$mail->setHtml ( $message_to_send );
			
			$send_ok = $mail->send ();
			
			if (isset ( $info ['queue_id'] )) {
				$this->db->query ( "UPDATE " . DB_PREFIX . "ne_queue SET retries = retries + 1 WHERE queue_id = '" . ( int ) $info ['queue_id'] . "'" );
			}
			
			if (($send_ok && isset ( $info ['queue_id'] )) || (! $send_ok && isset ( $info ['queue_id'] ) && isset ( $info ['retries'] ) && (($info ['retries'] + 1) > $this->config->get ( 'ne_sent_retries' )))) {
				$this->remove_queue ( $info ['queue_id'] );
				$this->update_stat ( $newsletter_id );
			}
			
			if ((! $send_ok && ! isset ( $info ['queue_id'] )) || (! $send_ok && isset ( $info ['queue_id'] ) && isset ( $info ['retries'] ) && (($info ['retries'] + 1) > $this->config->get ( 'ne_sent_retries' )))) {
				$this->db->query ( "UPDATE " . DB_PREFIX . "ne_stats_personal SET `success` = '0' WHERE stats_personal_id = '" . ( int ) $personal_id . "'" );
			} elseif (isset ( $info ['queue_id'] )) {
				$this->db->query ( "UPDATE " . DB_PREFIX . "ne_queue SET `locked` = '0' WHERE queue_id = '" . ( int ) $info ['queue_id'] . "'" );
			}
		}
	}
	private function addHistory($data) {
		$this->db->query ( "INSERT INTO " . DB_PREFIX . "ne_history SET `to` = '" . $this->db->escape ( $data ['to'] ) . "', public_id = '" . $this->db->escape ( md5 ( $data ['subject'] . time () ) ) . "', store_id = '" . ( int ) $data ['store_id'] . "', template_id = '" . ( int ) $data ['template_id'] . "', language_id = '" . ( int ) $data ['language_id'] . "', subject = '" . $this->db->escape ( $data ['subject'] ) . "', message = '" . $this->db->escape ( $data ['message'] ) . "'" );
		$newsletter_id = $this->db->getLastId ();
		$this->db->query ( "INSERT INTO " . DB_PREFIX . "ne_stats SET history_id = '" . ( int ) $newsletter_id . "', queue = '" . ( int ) $data ['queue'] . "', recipients = '" . ( int ) $data ['recipients'] . "', views = '0'" );
		return $newsletter_id;
	}
	private function clean() {
		$this->db->query ( "DELETE m.* FROM " . DB_PREFIX . "ne_marketing AS m INNER JOIN " . DB_PREFIX . "customer AS c ON (LCASE(m.email) = LCASE(c.email) AND m.store_id = c.store_id)" );
	}
	private function base64_encode_image($image) {
		if (file_exists ( $image )) {
			$filename = htmlentities ( $image );
		} else {
			return '';
		}
		
		$imgtype = array (
				'jpg' => 'jpeg',
				'jpeg' => 'jpeg',
				'gif' => 'gif',
				'png' => 'png' 
		);
		
		$filetype = pathinfo ( $filename, PATHINFO_EXTENSION );
		
		if (array_key_exists ( $filetype, $imgtype )) {
			$imgbinary = fread ( fopen ( $filename, "r" ), filesize ( $filename ) );
		} else {
			return '';
		}
		
		return 'data:image/' . $imgtype [$filetype] . ';base64,' . base64_encode ( $imgbinary );
	}
	public function getRecipientsWithRewardPoints() {
		$query = $this->db->query ( "SELECT c.customer_id, c.firstname, c.lastname, c.email, cr.points FROM `" . DB_PREFIX . "customer` AS c INNER JOIN (SELECT customer_id, SUM(points) AS points FROM " . DB_PREFIX . "customer_reward GROUP BY customer_id) AS cr ON cr.customer_id = c.customer_id AND cr.points > '0' LEFT JOIN " . DB_PREFIX . "ne_language_map lm ON c.email = lm.c_email WHERE NOT EXISTS (SELECT 1 FROM " . DB_PREFIX . "ne_blacklist bl WHERE bl.email = c.email)" );
		return $query->rows;
	}
	public function getSubscribedRecipientsWithRewardPoints() {
		$query = $this->db->query ( "SELECT c.customer_id, c.firstname, c.lastname, c.email, cr.points FROM `" . DB_PREFIX . "customer` AS c INNER JOIN (SELECT customer_id, SUM(points) AS points FROM " . DB_PREFIX . "customer_reward GROUP BY customer_id) AS cr ON cr.customer_id = c.customer_id AND cr.points > '0' LEFT JOIN " . DB_PREFIX . "ne_language_map lm ON c.email = lm.c_email WHERE c.newsletter = '1' AND NOT EXISTS (SELECT 1 FROM " . DB_PREFIX . "ne_blacklist bl WHERE bl.email = c.email)" );
		return $query->rows;
	}
}

?>