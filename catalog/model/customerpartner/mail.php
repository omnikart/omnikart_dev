<?php
class ModelCustomerpartnerMail extends Model {
	private $data;
	private $customer_applied_for_partnership_to_seller = 'customer_applied_for_partnership_to_seller';
	private $customer_applied_for_partnership_to_admin = 'customer_applied_for_partnership_to_admin';
	private $seller_added_product_to_seller = 'seller_added_product_to_seller';
	private $seller_added_product_to_admin = 'seller_added_product_to_admin';
	private $seller_contact_admin = 'seller_contact_admin';
	private $customer_contact_seller_to_seller = 'customer_contact_seller_to_seller';
	private $customer_contact_seller_to_admin = 'customer_contact_seller_to_admin';
	private $order_mail_to_seller = 'order_mail_to_seller';
	public function getMailData($id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "customerpartner_mail WHERE id='" . ( int ) $id . "'" );
		return $query->row;
	}
	public function getCustomer($id) {
		return $this->db->query ( "SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . ( int ) $id . "'" )->row;
	}
	public function getSeller($id) {
		return $this->db->query ( "SELECT * FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customerpartner_to_customer c2c ON (c.customer_id = c2c.customer_id) WHERE c.customer_id = '" . ( int ) $id . "'" )->row;
	}
	public function mail($data, $values) {
		$value_index = array ();
		
		$mail_id = $data ['mail_id'];
		$mail_from = $data ['mail_from'];
		$mail_to = $data ['mail_to'];
		
		if (isset ( $data ['seller_id'] ) and $data ['seller_id']) {
			$seller_info = $this->getSeller ( $data ['seller_id'] );
		}
		
		if (isset ( $data ['customer_id'] ) and $data ['customer_id']) {
			$customer_info = $this->getCustomer ( $data ['customer_id'] );
		}
		
		$value_index = $values;
		
		// switch($mail_type) {
		
		// //customer applied for sellership to customer
		// case $this->customer_applied_for_partnership_to_seller :
		// $mail_id = $this->config->get('marketplace_mail_partner_request');
		
		// $seller_info = $this->getSeller($this->customer->getId());
		// $mail_from = $this->config->get('marketplace_adminmail');
		// $mail_to = $this->customer->getEmail();
		
		// $value_index = array(
		// 'seller_message' => $data['message'],
		// 'commission' => $data['commission'],
		// );
		// break;
		
		// //customer applied for sellership to admin
		// case $this->customer_applied_for_partnership_to_admin :
		// $mail_id = $this->config->get('marketplace_mail_partner_admin');
		
		// $seller_info = $this->getSeller($this->customer->getId());
		// $mail_from = $this->customer->getEmail();
		// $mail_to = $this->config->get('marketplace_adminmail');
		
		// $value_index = array(
		// 'seller_message' => $data['message'],
		// 'commission' => $data['commission'],
		// );
		// break;
		
		// //seller added product to seller
		// case $this->seller_added_product_to_seller :
		// $mail_id = $this->config->get('marketplace_mail_product_request');
		
		// $seller_info = $this->getSeller($this->customer->getId());
		// $mail_from = $this->config->get('marketplace_adminmail');
		// $mail_to = $this->customer->getEmail();
		
		// $value_index = array(
		// 'product_name' => $data['name'],
		// 'commission' => $seller_info['commission'],
		// );
		// break;
		
		// //seller added product to admin
		// case $this->seller_added_product_to_admin :
		// $mail_id = $this->config->get('marketplace_mail_product_admin');
		
		// $seller_info = $this->getSeller($this->customer->getId());
		// $mail_from = $this->customer->getEmail();
		// $mail_to = $this->config->get('marketplace_adminmail');
		
		// $value_index = array(
		// 'product_name' => $data['name'],
		// 'commission' => $seller_info['commission'],
		// );
		// break;
		
		// //seller contact admin
		// case $this->seller_contact_admin :
		// $mail_id = $this->config->get('marketplace_mail_seller_to_admin');
		
		// $seller_info = $this->getSeller($this->customer->getId());
		// $mail_from = $this->customer->getEmail();
		// $mail_to = $this->config->get('marketplace_adminmail');
		
		// $value_index = array(
		// 'seller_message' => $data['message'],
		// 'commission' => $seller_info['commission'],
		// );
		// break;
		
		// //customer contact seller to seller
		// case $this->customer_contact_seller_to_seller :
		// $mail_id = $this->config->get('marketplace_mail_cutomer_to_seller');
		
		// $customer_info = $this->getCustomer($this->customer->getId());
		// $seller_info = $this->getSeller($data['seller']);
		
		// $mail_from = $this->customer->getEmail();
		
		// $mail_to = $seller_info['email'];
		
		// $value_index = array(
		// 'customer_message' => $data['message'],
		// 'customer_name' => $customer_info['firstname'].' '.$customer_info['lastname'],
		// 'commission' => $seller_info['commission'],
		// );
		// break;
		
		// //customer contact seller to admin
		// case $this->customer_contact_seller_to_admin :
		// $mail_id = $this->config->get('marketplace_mail_cutomer_to_seller');
		
		// $customer_info = $this->getCustomer($this->customer->getId());
		// $seller_info = $this->getSeller($data['seller']);
		
		// $mail_from = $this->customer->getEmail();
		// $mail_to = $this->config->get('marketplace_adminmail');
		
		// $value_index = array(
		// 'customer_message' => $data['message'],
		// 'customer_name' => $customer_info['firstname'].' '.$customer_info['lastname'],
		// 'commission' => $seller_info['commission'],
		// );
		// break;
		
		// //customer contact seller to admin
		// case $this->order_mail_to_seller :
		// $mail_id = $this->config->get('marketplace_mail_order');
		
		// $seller_info = $this->getSeller($data['seller']);
		// $mail_from = $this->config->get('marketplace_adminmail');
		// $mail_to = $seller_info['email'];
		
		// $value_index = array(
		// 'order' => $data['html'],
		// 'commission' => $seller_info['commission'],
		// );
		// break;
		
		// default :
		// return;
		// }
		
		$mail_details = $this->getMailData ( $mail_id );
		
		if ($mail_details) {
			
			$this->data ['store_name'] = $this->config->get ( 'config_name' );
			$this->data ['store_url'] = HTTP_SERVER;
			$this->data ['logo'] = HTTP_SERVER . 'image/' . $this->config->get ( 'config_logo' );
			$find = array (
					'{order}',
					'{seller_message}',
					'{customer_message}',
					'{commission}',
					'{product_name}',
					'{customer_name}',
					'{seller_name}',
					'{config_logo}',
					'{config_icon}',
					'{config_currency}',
					'{config_image}',
					'{config_name}',
					'{config_owner}',
					'{config_address}',
					'{config_geocode}',
					'{config_email}',
					'{config_telephone}',
					'{membership_type}',
					'{membership_expiry}',
					'{membership_plan}',
					'{membership_commission}',
					'{membership_date}' 
			);
			
			$mailValues = str_replace ( '<br />', ',', nl2br ( $this->config->get ( 'marketplace_mail_keywords' ) ) );
			$mailValues = explode ( ',', $mailValues );
			$find = array ();
			$replace = array ();
			foreach ( $mailValues as $key => $value ) {
				$find [trim ( $value )] = '{' . trim ( $value ) . '}';
				$replace [trim ( $value )] = '';
			}
			
			$replace ['seller_name'] = $seller_info ['firstname'] . ' ' . $seller_info ['lastname'];
			$replace ['config_logo'] = '<a href="' . HTTP_SERVER . '" title="' . $this->data ['store_name'] . '"><img src="' . HTTP_SERVER . 'image/' . $this->config->get ( 'config_logo' ) . '" alt="' . $this->data ['store_name'] . '" /></a>';
			$replace ['config_icon'] = '<img src="' . HTTP_SERVER . 'image/' . $this->config->get ( 'config_icon' ) . '">';
			$replace ['config_currency'] = $this->config->get ( 'config_currency' );
			$replace ['config_image'] = '<img src="' . HTTP_SERVER . 'image/' . $this->config->get ( 'config_image' ) . '">';
			$replace ['config_name'] = $this->config->get ( 'config_name' );
			$replace ['config_owner'] = $this->config->get ( 'config_owner' );
			$replace ['config_address'] = $this->config->get ( 'config_address' );
			$replace ['config_geocode'] = $this->config->get ( 'config_geocode' );
			$replace ['config_email'] = $this->config->get ( 'config_email' );
			$replace ['config_telephone'] = $this->config->get ( 'config_telephone' );
			
			$replace = array_merge ( $replace, $value_index );
			ksort ( $find );
			ksort ( $replace );
			$mail_details ['message'] = trim ( str_replace ( $find, $replace, $mail_details ['message'] ) );
			
			$this->data ['subject'] = $mail_details ['subject'];
			$this->data ['message'] = $mail_details ['message'];
			
			$html = $this->load->view ( 'default/template/customerpartner/mail.tpl', $this->data );
			if (preg_match ( '/^[^\@]+@.*\.[a-z]{2,6}$/i', $mail_to ) and preg_match ( '/^[^\@]+@.*\.[a-z]{2,6}$/i', $mail_from )) {
				
				$mail = new Mail ();
				$mail->setTo ( $mail_to );
				$mail->setFrom ( $mail_from );
				$mail->setSender ( $this->data ['store_name'] );
				$mail->setSubject ( $mail_details ['subject'] );
				$mail->setHtml ( $html );
				$mail->setText ( strip_tags ( $html ) );
				$status = $mail->send ();
				if ($status) {
					return true;
				} else {
					return false;
				}
			}
		}
	}
	public function sms($data, $values) {
		$sms = new Sms ();
		$sms->username = $this->config->get ( 'way2mint_username' );
		$sms->password = $this->config->get ( 'way2mint_password' );
		$sms->setfrom ( $this->config->get ( 'way2mint_mp_from' ) );
		$sms->response_format = 'json';
		$sms->setto ( trim ( $data ['sms_to'] ) );
		$sms->template ( $this->config->get ( 'way2mint_mp_tpl' ) );
		$param = explode ( ',', $this->config->get ( 'way2mint_mp_param' ) );
		$params ['templateParameters[' . $param [0] . ']'] = "Seller";
		$params ['templateParameters[' . $param [1] . ']'] = $data ['text'];
		$sms->parameters ( $params );
		$sms->send ();
	}
}
?>