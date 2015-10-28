<?php 

class ControllerAccountCustomerpartnerUserlist extends Controller
{
	private $error = array();

	public function index() {
		
		$this->load->language("account/customerpartner/userlist");
		$this->load->model("account/customerpartner");
		$this->getList();
	}

	public function getList() {
		//$customerRights = $this->model_account_customerpartner->getCustomerGroupRights($this->customer->getGroupId());
		$customerRights = $this->customer->getRights();

		if(!$this->customer->isLogged() || ($customerRights && !array_key_exists('create-user', $customerRights['rights'])) || !$customerRights ) {
			$this->response->redirect($this->url->link('account/account', '','SSL'));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_update'] = $this->language->get('button_update');
		$data['button_close'] = $this->language->get('button_close');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_disable'] = $this->language->get('button_disable');
		$data['button_enable'] = $this->language->get('button_enable');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_rights'] = $this->language->get('entry_rights');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_action'] = $this->language->get('entry_action');
		$data['text_no_record'] = $this->language->get('text_no_record');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_userlist'),
			'href' => $this->url->link('account/customerpartner/userlist', '', 'SSL')
		);

		$data['insert'] = $this->url->link('account/customerpartner/userregistration', '', 'SSL');
		$data['delete'] = $this->url->link('account/customerpartner/userlist/delete', '', 'SSL');

		$filter = array(
			'start' => 0,
			'limit' => 50,
			'groupIsParent' => $this->customer->getGroupId(),
		);

		$userList = $this->model_account_customerpartner->getUserList($this->customer->getId());
		$data['customer_groups'] = $this->model_account_customerpartner->getCustomerGroupList($filter);
		$allusers = $this->model_account_customerpartner->getAllUserList($this->customer->getId());
		$data['allusers'] = array();
		if ($allusers){
			$data['allusers'] = $allusers;
		}
		$data['userlist'] = array();
		if($userList) {
			foreach ($userList as $key => $user) {
				$customerRights = $this->model_account_customerpartner->getCustomerGroupRights($user['customer_group_id']);
				if($customerRights) {
					foreach ($customerRights as $key => $rights) {
						
					}
				}
				$data['userlist'][] = array(
					'customer_id' => $user['customer_id'],
					'customer_group_id' => $user['customer_group_id'],
					'manager_id' => ($user['manager_id'])?$user['manager_id']:'0',
					'p_limit' => ($user['p_limit'])?$user['p_limit']:'0',
					'firstname' => $user['firstname'],
					'lastname' => $user['lastname'],
					'email' => $user['email'],
					'customerRights' => $customerRights,
					'status' => $user['status'],
				);
			}
		}
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');

		$this->response->setOutput($this->load->view('default/template/account/customerpartner/userlist.tpl',$data));	
	}

	public function disableSubUser() {
		$this->load->model('account/customerpartner');
		$this->load->language('account/customerpartner/userlist');
		$json = array();
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			if($this->request->post['action'] == 'disable') {
				$result = $this->model_account_customerpartner->disableSubUser($this->request->post['customer_id']);
				if($result) {
					$json['success'] = $this->language->get("success_disabled");
				} else {
					$json['warning'] = $this->language->get("warning_disable");
				}
			} else if ($this->request->post['action'] == 'enable') {
				$result = $this->model_account_customerpartner->enableSubUser($this->request->post['customer_id']);
				if($result) {
					$json['success'] = $this->language->get("success_enabled");
				} else {
					$json['warning'] = $this->language->get("warning_enable");
				}
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function changeCustomerGroupId() {
		$this->load->model('account/customerpartner');
		$this->load->language('account/customerpartner/userlist');
		$json = array();
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$customer_id = $this->request->post['customer_id'];
			$customer_group_id = $this->request->post['customer_group_id'];
			$manager_id = $this->request->post['manager_id'];
			$p_limit = $this->request->post['p_limit'];
			$this->model_account_customerpartner->updateSubCustomerGroup($customer_id,$customer_group_id);
			$this->model_account_customerpartner->updatemaganer($customer_id,$manager_id,$p_limit);
			$this->session->data['success'] = $this->language->get('success_customergroup_update');
			$this->response->setOutput(json_encode($p_limit));
				
			//$this->response->redirect($this->url->link("account/customerpartner/userlist",'','SSL'));
		}
	}

	public function delete() {
		$this->load->model('account/customerpartner');
		$this->load->language('account/customerpartner/userlist');
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			if(isset($this->request->post['selected']) && $this->request->post['selected']) {
				foreach ($this->request->post['selected'] as $key => $value) {
					$this->model_account_customerpartner->deleteSubUser($value);
				}
				$this->session->data['success'] = $this->language->get('success_delete');
				$this->response->redirect($this->url->link('account/customerpartner/userlist','', 'SSL'));
			} else {
				$this->error['error_warning'] = $this->language->get('warning_delete');
				$this->getList();
			}
		}
	}
}
?>
