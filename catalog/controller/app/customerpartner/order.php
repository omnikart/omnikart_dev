<?php
class ControllerAppCustomerpartnerOrder extends Controller {
	public function index() {
		/* if (isset($this->session->data['app_id']) && ($this->session->data['app_id']=sha1($this->customer->getId()))) { */
		$post = json_decode ( file_get_contents ( 'php://input' ), true );
		$c_id = (isset ( $post ['customer_id'] ) ? $post ['customer_id'] : '0');
		if ($c_id) {
			$data ['id'] = $this->customer->getId ();
			$this->load->model ( 'account/customerpartner' );
			$orders = $this->model_account_customerpartner->getSellerOrders ();
			$data ['orders'] = $orders;
		} else {
			$data ['error'] = "Customer Id Not Received";
		}
		
		/*
		 * } else {
		 * $data['id'] = 0;
		 * }
		 */
		$this->response->addHeader ( 'Content-Type: application/json' );
		$this->response->setOutput ( json_encode ( $data ) );
	}
	public function orderinfo() {
		$post = json_decode ( file_get_contents ( 'php://input' ), true );
		$c_id = (isset ( $post ['customer_id'] ) ? $post ['customer_id'] : '0');
		$data ['id'] = '0';
		/* if (isset($this->session->data['app_id']) && ($this->session->data['app_id']=sha1($this->customer->getId()))) { */
		if ($c_id) {
			$data ['id'] = $this->customer->getId ();
			$this->load->model ( 'account/customerpartner' );
			$orders = $this->model_account_customerpartner->getOrder ( $post ['order_id'], $this->customer->getId () );
			$data ['orderinfo'] = $orders;
		}
		/*
		 * } else {
		 * $data['id'] = 0;
		 * }
		 */
		$this->response->addHeader ( 'Content-Type: application/json' );
		$this->response->setOutput ( json_encode ( $data ) );
	}
}