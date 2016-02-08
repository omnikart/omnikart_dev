<?php
class ControllerCheckoutCartOnepage extends Controller {
	
	// custom function
	function minicart_total() {
		$this->load->language ( 'checkout/cart' );
		$json = array ();
		// Totals
		$this->load->model ( 'extension/extension' );
		
		$total_data = array ();
		$total = 0;
		$taxes = $this->cart->getTaxes ();
		
		// Display prices
		if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
			$sort_order = array ();
			
			$results = $this->model_extension_extension->getExtensions ( 'total' );
			
			foreach ( $results as $key => $value ) {
				$sort_order [$key] = $this->config->get ( $value ['code'] . '_sort_order' );
			}
			
			array_multisort ( $sort_order, SORT_ASC, $results );
			
			foreach ( $results as $result ) {
				if ($this->config->get ( $result ['code'] . '_status' )) {
					$this->load->model ( 'total/' . $result ['code'] );
					
					$this->{'model_total_' . $result ['code']}->getTotal ( $total_data, $total, $taxes );
				}
			}
			
			$sort_order = array ();
			
			foreach ( $total_data as $key => $value ) {
				$sort_order [$key] = $value ['sort_order'];
			}
			
			array_multisort ( $sort_order, SORT_ASC, $total_data );
		}
		
		$json ['total'] = sprintf ( $this->language->get ( 'text_items' ), $this->cart->countProducts () + (isset ( $this->session->data ['vouchers'] ) ? count ( $this->session->data ['vouchers'] ) : 0), $this->currency->format ( $total ) );
		$this->response->addHeader ( 'Content-Type: application/json' );
		$this->response->setOutput ( json_encode ( $json ) );
	}
	
	// custom function
	public function edit_cart() {
		$this->load->language ( 'checkout/cart' );
		
		$shipping_required_before = $this->cart->hasShipping ();
		// Update
		$json = array ();
		if (! empty ( $this->request->post ['quantity'] )) {
			foreach ( $this->request->post ['quantity'] as $key => $value ) {
				$this->cart->update ( $key, $value );
			}
			
			unset ( $this->session->data ['reward'] );
			
			// $this->response->redirect($this->url->link('checkout/cart'));
			// Totals
			$this->load->model ( 'extension/extension' );
			
			$total_data = array ();
			$total = 0;
			$taxes = $this->cart->getTaxes ();
			
			// Display prices
			if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
				$sort_order = array ();
				
				$results = $this->model_extension_extension->getExtensions ( 'total' );
				
				foreach ( $results as $key => $value ) {
					$sort_order [$key] = $this->config->get ( $value ['code'] . '_sort_order' );
				}
				
				array_multisort ( $sort_order, SORT_ASC, $results );
				
				foreach ( $results as $result ) {
					if ($this->config->get ( $result ['code'] . '_status' )) {
						$this->load->model ( 'total/' . $result ['code'] );
						
						$this->{'model_total_' . $result ['code']}->getTotal ( $total_data, $total, $taxes );
					}
				}
				
				$sort_order = array ();
				
				foreach ( $total_data as $key => $value ) {
					$sort_order [$key] = $value ['sort_order'];
				}
				
				array_multisort ( $sort_order, SORT_ASC, $total_data );
			}
			
			$json ['total'] = sprintf ( $this->language->get ( 'text_items' ), $this->cart->countProducts () + (isset ( $this->session->data ['vouchers'] ) ? count ( $this->session->data ['vouchers'] ) : 0), $this->currency->format ( $total ) );
		}
		if ($this->cart->hasShipping () != $shipping_required_before) {
			$json ['redirect'] = $this->url->link ( 'checkout/checkout_onepage', '', 'SSL' );
		}
		if ((! $this->cart->hasProducts () && empty ( $this->session->data ['vouchers'] )) || (! $this->cart->hasStock () && ! $this->config->get ( 'config_stock_checkout' ))) {
			$json ['redirect'] = $this->url->link ( 'checkout/cart', '', 'SSL' );
		}
		
		$this->response->addHeader ( 'Content-Type: application/json' );
		$this->response->setOutput ( json_encode ( $json ) );
	}
	
	// custom function
	public function remove_cart() {
		$this->load->language ( 'checkout/cart' );
		$shipping_required_before = $this->cart->hasShipping ();
		
		// Remove
		$json = array ();
		if (isset ( $this->request->post ['key'] )) {
			$this->cart->remove ( $this->request->post ['key'] );
			
			unset ( $this->session->data ['vouchers'] [$this->request->post ['key']] );
			
			unset ( $this->session->data ['reward'] );
			
			// Totals
			$this->load->model ( 'extension/extension' );
			
			$total_data = array ();
			$total = 0;
			$taxes = $this->cart->getTaxes ();
			
			// Display prices
			if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
				$sort_order = array ();
				
				$results = $this->model_extension_extension->getExtensions ( 'total' );
				
				foreach ( $results as $key => $value ) {
					$sort_order [$key] = $this->config->get ( $value ['code'] . '_sort_order' );
				}
				
				array_multisort ( $sort_order, SORT_ASC, $results );
				
				foreach ( $results as $result ) {
					if ($this->config->get ( $result ['code'] . '_status' )) {
						$this->load->model ( 'total/' . $result ['code'] );
						
						$this->{'model_total_' . $result ['code']}->getTotal ( $total_data, $total, $taxes );
					}
				}
				
				$sort_order = array ();
				
				foreach ( $total_data as $key => $value ) {
					$sort_order [$key] = $value ['sort_order'];
				}
				
				array_multisort ( $sort_order, SORT_ASC, $total_data );
			}
			
			$json ['total'] = sprintf ( $this->language->get ( 'text_items' ), $this->cart->countProducts () + (isset ( $this->session->data ['vouchers'] ) ? count ( $this->session->data ['vouchers'] ) : 0), $this->currency->format ( $total ) );
		}
		if ($this->cart->hasShipping () != $shipping_required_before) {
			$json ['redirect'] = $this->url->link ( 'checkout/checkout_onepage', '', 'SSL' );
		}
		if ((! $this->cart->hasProducts () && empty ( $this->session->data ['vouchers'] )) || (! $this->cart->hasStock () && ! $this->config->get ( 'config_stock_checkout' ))) {
			$json ['redirect'] = $this->url->link ( 'checkout/cart', '', 'SSL' );
		}
		
		$this->response->addHeader ( 'Content-Type: application/json' );
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function voucher() {
		$this->load->language ( 'checkout/voucher' );
		
		$json = array ();
		
		$this->load->model ( 'checkout/voucher' );
		
		if (isset ( $this->request->post ['voucher'] )) {
			$voucher = $this->request->post ['voucher'];
		} else {
			$voucher = '';
		}
		
		$voucher_info = $this->model_checkout_voucher->getVoucher ( $voucher );
		
		if (empty ( $this->request->post ['voucher'] )) {
			$json ['error'] ['warning'] = $this->language->get ( 'error_empty' );
		} elseif ($voucher_info) {
			$this->session->data ['voucher'] = $this->request->post ['voucher'];
			
			// $this->session->data['success'] = $this->language->get('text_success');
			$json ['success'] = $this->language->get ( 'text_success' );
			// $json['redirect'] = $this->url->link('checkout/cart');
		} else {
			$json ['error'] ['warning'] = $this->language->get ( 'error_voucher' );
		}
		
		$this->response->addHeader ( 'Content-Type: application/json' );
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function coupon() {
		$this->load->language ( 'checkout/coupon' );
		
		$json = array ();
		
		$this->load->model ( 'checkout/coupon' );
		
		if (isset ( $this->request->post ['coupon'] )) {
			$coupon = $this->request->post ['coupon'];
		} else {
			$coupon = '';
		}
		
		$coupon_info = $this->model_checkout_coupon->getCoupon ( $coupon );
		
		if (empty ( $this->request->post ['coupon'] )) {
			$json ['error'] ['warning'] = $this->language->get ( 'error_empty' );
		} elseif ($coupon_info) {
			$this->session->data ['coupon'] = $this->request->post ['coupon'];
			
			// $this->session->data['success'] = $this->language->get('text_success');
			$json ['success'] = $this->language->get ( 'text_success' );
			// $json['redirect'] = $this->url->link('checkout/cart');
		} else {
			$json ['error'] ['warning'] = $this->language->get ( 'error_coupon' );
		}
		
		$this->response->addHeader ( 'Content-Type: application/json' );
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function reward() {
		$this->load->language ( 'checkout/reward' );
		
		$json = array ();
		
		$points = $this->customer->getRewardPoints ();
		
		$points_total = 0;
		
		foreach ( $this->cart->getProducts () as $product ) {
			if ($product ['points']) {
				$points_total += $product ['points'];
			}
		}
		
		if (empty ( $this->request->post ['reward'] )) {
			$json ['error'] = $this->language->get ( 'error_reward' );
		}
		
		if ($this->request->post ['reward'] > $points) {
			$json ['error'] = sprintf ( $this->language->get ( 'error_points' ), $this->request->post ['reward'] );
		}
		
		if ($this->request->post ['reward'] > $points_total) {
			$json ['error'] = sprintf ( $this->language->get ( 'error_maximum' ), $points_total );
		}
		
		if (! $json) {
			$this->session->data ['reward'] = abs ( $this->request->post ['reward'] );
			
			$json ['success'] = $this->language->get ( 'text_success' );
		}
		
		$this->response->addHeader ( 'Content-Type: application/json' );
		$this->response->setOutput ( json_encode ( $json ) );
	}
}