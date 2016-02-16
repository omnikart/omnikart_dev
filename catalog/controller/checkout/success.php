<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {
		$this->load->language ( 'checkout/success' );
		
		if (isset ( $this->session->data ['order_id'] )) {
			/* balastic ads */
			if (isset ( $this->session->data ['utm_source'] ) == 'BallisticAds') {
				$this->load->model ( 'checkout/order' );
				$order_info = $this->model_checkout_order->getOrder ( $this->session->data ['order_id'] );
				$url = ' http://ballisticads.com/co.php?coid=Omnikart&comment=';
				$json = array (
						'transaction-id' => $this->session->data ['order_id'] 
				);
				foreach ( $this->cart->getProducts () as $product ) {
					$json ['product-name-' . $product ['product_id']] = $product ['name'];
					$json ['price-' . $product ['product_id']] = $product ['total'];
				}
				$url .= urlencode ( json_encode ( $json ) );
				$ch = curl_init ();
				curl_setopt ( $ch, CURLOPT_URL, $url );
				curl_setopt ( $ch, CURLOPT_HEADER, 1 );
				curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
				$data = curl_exec ();
				curl_close ( $ch );
			}
			
			$this->cart->clear ();
			
			/*
			 * To close previous review
			 */
			if ($this->config->get ( 'marketplace_status' )) {
				$this->load->model ( 'account/customerpartner' );
				$this->model_account_customerpartner->closePreviousReview ( $this->customer->getId (), $this->session->data ['order_id'] );
			}
			/*
			 * end here
			 */
			
			// Add to activity log
			$this->load->model ( 'account/activity' );
			
			if ($this->customer->isLogged ()) {
				$activity_data = array (
						'customer_id' => $this->customer->getId (),
						'name' => $this->customer->getFirstName () . ' ' . $this->customer->getLastName (),
						'order_id' => $this->session->data ['order_id'] 
				);
				
				$this->model_account_activity->addActivity ( 'order_account', $activity_data );
			} else {
				$activity_data = array (
						'name' => $this->session->data ['guest'] ['firstname'] . ' ' . $this->session->data ['guest'] ['lastname'],
						'order_id' => $this->session->data ['order_id'] 
				);
				
				$this->model_account_activity->addActivity ( 'order_guest', $activity_data );
			}
			
			unset ( $this->session->data ['shipping_method'] );
			unset ( $this->session->data ['shipping_methods'] );
			unset ( $this->session->data ['payment_method'] );
			unset ( $this->session->data ['payment_methods'] );
			unset ( $this->session->data ['guest'] );
			unset ( $this->session->data ['comment'] );
			unset ( $this->session->data ['order_id'] );
			unset ( $this->session->data ['coupon'] );
			unset ( $this->session->data ['reward'] );
			unset ( $this->session->data ['voucher'] );
			unset ( $this->session->data ['vouchers'] );
			unset ( $this->session->data ['totals'] );
		}
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home' ) 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_basket' ),
				'href' => $this->url->link ( 'checkout/cart' ) 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_checkout' ),
				'href' => $this->url->link ( 'checkout/checkout', '', 'SSL' ) 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_success' ),
				'href' => $this->url->link ( 'checkout/success' ) 
		);
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		
		if ($this->customer->isLogged ()) {
			$data ['text_message'] = sprintf ( $this->language->get ( 'text_customer' ), $this->url->link ( 'account/account', '', 'SSL' ), $this->url->link ( 'account/order', '', 'SSL' ), $this->url->link ( 'account/download', '', 'SSL' ), $this->url->link ( 'information/contact' ) );
		} else {
			$data ['text_message'] = sprintf ( $this->language->get ( 'text_guest' ), $this->url->link ( 'information/contact' ) );
		}
		
		$data ['google_conversion'] = '<!-- Google Code for omnikart Conversion Page --><script type="text/javascript">
			/* <![CDATA[ */
			var google_conversion_id = 975481156;
			var google_conversion_language = "en";
			var google_conversion_format = "3";
			var google_conversion_color = "ffffff";
			var google_conversion_label = "XHE8CMqLiVwQxNKS0QM";
			var google_remarketing_only = false;
			/* ]]> */
			</script>
			<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
			</script>
			<noscript>
			<div style="display:inline;">
			<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/975481156/?label=XHE8CMqLiVwQxNKS0QM&amp;guid=ON&amp;script=0"/>
			</div>
			</noscript>';
		
		$data ['button_continue'] = $this->language->get ( 'button_continue' );
		
		$data ['continue'] = $this->url->link ( 'common/home' );
		
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['column_right'] = $this->load->controller ( 'common/column_right' );
		$data ['content_top'] = $this->load->controller ( 'common/content_top' );
		$data ['content_bottom'] = $this->load->controller ( 'common/content_bottom' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		$data ['header'] = $this->load->controller ( 'common/header' );
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/common/success.tpl' )) {
			$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/common/success.tpl', $data ) );
		} else {
			$this->response->setOutput ( $this->load->view ( 'default/template/common/success.tpl', $data ) );
		}
	}
}