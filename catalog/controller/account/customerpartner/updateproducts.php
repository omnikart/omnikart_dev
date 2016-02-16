<?php
class ControllerAccountCustomerpartnerUpdateproducts extends Controller {
	private $error = array ();
	public function index() {
		if (! $this->customer->isLogged ()) {
			$this->session->data ['redirect'] = $this->url->link ( 'account/customerpartner/addproduct', '', 'SSL' );
			$this->response->redirect ( $this->url->link ( 'account/login', '', 'SSL' ) );
		}
		$this->load->model ( 'account/customerpartner' );
		$customerRights = $this->customer->getRights ();
		if ($customerRights && ! array_key_exists ( 'addproduct', $customerRights ['rights'] )) {
			$this->response->redirect ( $this->url->link ( 'account/account', '', 'SSL' ) );
		}
		$sellerId = $this->model_account_customerpartner->isSubUser ( $this->customer->getId () );
		if (! $customerRights ['isParent'] && ! $sellerId) {
			$data ['chkIsPartner'] = $this->model_account_customerpartner->chkIsPartner ();
		} else if ($sellerId) {
			$data ['chkIsPartner'] = true;
		} else {
			$data ['chkIsPartner'] = false;
		}
		if (! $data ['chkIsPartner'])
			$this->response->redirect ( $this->url->link ( 'account/account' ) );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') and $this->validate ()) {
			$this->model_account_customerpartner->editProducts ( $this->request->post );
		}
	}
	private function validate() {
		$error = true;
		return $error;
	}
}