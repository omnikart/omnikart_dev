<?php
/**
 * logout.php
 *
 * Logout management
 *
 * @author     Makai Lajos
 * @copyright  2015
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://opencart2oauth.opencart-api.com/schema_v2.0_oauth/
 */
require_once (DIR_SYSTEM . 'engine/restcontroller.php');
class ControllerRestLogout extends RestController {
	
	/*
	 * Logout user
	 */
	public function logout() {
		$this->checkPlugin ();
		
		$json = array (
				'success' => true 
		);
		
		if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
			
			if ($this->customer->isLogged ()) {
				$this->event->trigger ( 'pre.customer.logout' );
				
				$this->customer->logout ();
				$this->cart->clear ();
				
				unset ( $this->session->data ['wishlist'] );
				unset ( $this->session->data ['shipping_address'] );
				unset ( $this->session->data ['shipping_method'] );
				unset ( $this->session->data ['shipping_methods'] );
				unset ( $this->session->data ['payment_address'] );
				unset ( $this->session->data ['payment_method'] );
				unset ( $this->session->data ['payment_methods'] );
				unset ( $this->session->data ['comment'] );
				unset ( $this->session->data ['order_id'] );
				unset ( $this->session->data ['coupon'] );
				unset ( $this->session->data ['reward'] );
				unset ( $this->session->data ['voucher'] );
				unset ( $this->session->data ['vouchers'] );
				
				$this->event->trigger ( 'post.customer.logout' );
			}
		} else {
			$json ["error"] = "Only POST request method allowed";
			$json ["success"] = false;
		}
		$this->sendResponse ( $json );
	}
}