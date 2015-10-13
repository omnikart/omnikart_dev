<?php

class ControllerCustomerpartnerCustomergroup extends Controller
{
	private $error = array();
	public function index() {
		$this->load->language('customerpartner/customer_group');
		$this->load->model('customerpartner/customer_group');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->getList();
	}

	public function getList() {
		
		$this->document->setTitle($this->language->get('heading_title'));

		// Languages
		$data['heading_title'] = $this->language->get('heading_title');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_group_rights'] = $this->language->get('entry_group_rights');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_action'] = $this->language->get('entry_action');
		$data['text_not_found'] = $this->language->get('text_not_found');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$url = '';

		if(isset($this->request->get['filter_group_name'])) {
			$data['filter_group_name'] = $filter_group_name = $this->request->get['filter_group_name'];
			$url = '&filter_group_name='.$this->request->get['filter_group_name'];
		} else {
			$filter_group_name = null;
		}

		if(isset($this->request->get['filter_group_rights'])) {
			$data['filter_group_rights'] = $filter_group_rights = $this->request->get['filter_group_rights'];
			$url = '&filter_group_rights='.$this->request->get['filter_group_rights'];
		} else {
			$filter_group_rights = null;
		}

		if(isset($this->request->get['filter_group_status'])) {
			$data['filter_group_status'] = $filter_group_status = $this->request->get['filter_group_status'];
			$url = '&filter_group_status='.$this->request->get['filter_group_status'];
		} else {
			$filter_group_status = null;
		}

		if(isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
			$url .= '&page=' . $this->request->get['page'];
		} else {
			$page = 1;
		}

		if(isset($this->request->get['sort'])) {
			$data['sort'] = $sort = $this->request->get['sort'];
		} else {
			$data['sort'] = $sort = 'cpcn.name';
		}

		if(isset($this->request->get['order'])) {
			if($this->request->get['order'] == 'asc') {
				$data['order'] = $order = 'desc';
			} else {
				$data['order'] = $order = 'asc';
			}
		} else {
			$data['order'] = $order = 'asc';
		}

		// Filter array

		$filterData = array(
			'groupName' => $filter_group_name,
			'groupRights' => $filter_group_rights,
			'groupStatus' => $filter_group_status,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin'),
			'sort' => $sort,
			'order' => $order,
		);
		// Breadcrumbs

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('customerpartner/customer_group', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		// Fetching Data
		$customer_group_total = $this->model_customerpartner_customer_group->getCustomerGroupListTotal($filterData);
		$groupList = $this->model_customerpartner_customer_group->getCustomerGroupList($filterData);
		
		$data['groupList'] = array();
		$rights = '';
		if($groupList) {
			foreach ($groupList as $key => $group) {
				$rights = str_replace(':',', ', str_replace('-',' ', rtrim($group['rights'],":")));
				if(isset($group['status']) && $group['status'] == 'enable') {
					$status = $this->language->get('text_enabled');
				} else {
					$status = $this->language->get('text_disabled');
				}
				$data['groupList'][] = array(
					'id' => $group['id'],
					'name' => $group['name'],
					'rights' => $rights,
					'status' => $status,
					'edit' => $this->url->link("customerpartner/customer_group/getForm&id=".$group['id'].'&token='.$this->session->data['token'], 'SSL'),
				);
			}
		}

		// Pagination
		$pagination = new Pagination();
		$pagination->total = $customer_group_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('customerpartner/customer_group', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_group_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_group_total - $this->config->get('config_limit_admin'))) ? $customer_group_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_group_total, ceil($customer_group_total / $this->config->get('config_limit_admin')));

		if(isset($this->error['error_warning'])) {
			$data['error_warning'] = $this->error['error_warning'];
			unset($this->error['error_warning']);
		} else {
			$data['error_warning'] = '';
		}
		
		if(isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		// action variables

		$data['filterUrl'] = $this->url->link("customerpartner/customer_group&token=".$this->session->data['token']);

		$data['add'] = $this->url->link("customerpartner/customer_group/getForm",'token='.$this->session->data['token'], 'SSL');

		$data['delete'] = $this->url->link("customerpartner/customer_group/deleteGroup",'token='.$this->session->data['token'], 'SSL');

		$data['groupNameUrl'] = $this->url->link("customerpartner/customer_group&sort=cpcn.name",$url.'token='.$this->session->data['token'].'&order='.$order, 'SSL');

		$data['groupRightsUrl'] = $this->url->link("customerpartner/customer_group&sort=cpc.rights",$url.'token='.$this->session->data['token'].'&order='.$order, 'SSL');

		$data['groupStatusUrl'] = $this->url->link("customerpartner/customer_group&sort=cpc.status",$url.'token='.$this->session->data['token'].'&order='.$order, 'SSL');

		// Load common controller
		
		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
		$data['column_left'] = $this->load->controller('common/column_left');


		$this->response->setOutput($this->load->view('customerpartner/customer_group_list.tpl',$data));

	}

	public function getForm() {

		$this->load->language('customerpartner/customer_group');
		$this->load->model('customerpartner/customer_group');
		
		// Defining rights array
		$data['rights'] = array(
			'make-order' => $this->language->get('text_rights_make_order'),
			'view-all-order' => $this->language->get('text_rights_view_all_orders'),
			// 'license-others' => $this->language->get('text_rights_licenses_others'),
			'create-user' => $this->language->get('text_rights_create_user'),
			'offer-discount' => $this->language->get('text_rights_offer_discount'),
			'addproduct' => $this->language->get('text_rights_addedit_product'),
			'productlist' => $this->language->get('text_rights_access_productlist'),
			'profile' => $this->language->get('text_rights_access_profile'),
			'db' => $this->language->get('text_rights_access_dashboard'),
			'dbe' => $this->language->get('text_rights_edit_dashboard'),
		);

		// Defining post array
		$configData = array (
			'customerGroupRights',
			'customerGroupName',
			'customerGroupDescription',
			'customerGroupStatus',
			'parentGroupId',
			'parentGroupName',
		);

		foreach ($configData as $key => $value) {
			if(isset($this->request->post[$value])) {
				$data[$value] = $this->request->post[$value];
			}
		}

		if(isset($this->request->get['id']) || isset($this->request->post['id']) ) {
			$this->document->setTitle($this->language->get('heading_title_edit'));
			$data['sub_heading_title'] = $this->language->get('heading_title_edit');

			$data['action'] = $this->url->link("customerpartner/customer_group/editCustomerGroup", 'token='.$this->session->data['token'], 'SSL');
			// Get data to edit
			$groupDetails = $this->model_customerpartner_customer_group->getCustomerGroupDetails($this->request->get['id']);
			
			$data['id'] = $this->request->get['id'];
			if($groupDetails) {
				foreach ($groupDetails as $key => $value) {
					$data['customerGroupRights'] = explode(':', rtrim($value['rights'],":"));
					$data['customerGroupStatus'] = $value['status'];
					$data['customerGroupName'][] = array(
						'name' => $value['name'],
						'language_id' => $value['language_id'],
					);
					$data['customerGroupDescription'][] = array(
						'description' => $value['description'],
						'language_id' => $value['language_id'],
					);
					if($value['isParent']) {
						$parentGroupDetails = $this->model_customerpartner_customer_group->getCustomerGroupDetails($value['isParent']);
						$data['parentGroupId'] = $value['isParent'];
						if($parentGroupDetails[$key]['language_id'] == $this->config->get('config_language_id')) {
							$data['parentGroupName'] = $parentGroupDetails[$key]['name'];
						}
						// redefining rights array as per parent group
						$parentGroupRights = explode(':', rtrim($parentGroupDetails[$key]['rights'],":"));
						foreach ($parentGroupRights as $key => $right) {
							$data['parentGroupRights'][$right] = $data['rights'][$right];
						}
						$data['rights'] = $data['parentGroupRights'];
					}
					
				}
			}
		} else {
			$this->document->setTitle($this->language->get('heading_title_add'));
			$data['sub_heading_title'] = $this->language->get('heading_title_add');
			$data['action'] = $this->url->link("customerpartner/customer_group/addCustomerGroup", 'token='.$this->session->data['token'], 'SSL');
		}

		// action urls
		$data['back'] = $this->url->link("customerpartner/customer_group",'token='.$this->session->data['token'], 'SSL');

		$this->load->model("localisation/language");
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['heading_title'] = $this->language->get('heading_title');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_parent_group'] = $this->language->get('entry_parent_group');
		$data['entry_group_rights'] = $this->language->get('entry_group_rights');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_selectall'] = $this->language->get('entry_selectall');
		$data['entry_deselectall'] = $this->language->get('entry_deselectall');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_back'] = $this->language->get('button_back');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['description'])) {
			$data['error_description'] = $this->error['description'];
		} else {
			$data['error_description'] = array();
		}

		// Urls

		$data['getGroupUrl'] = $this->url->link('customerpartner/customer_group/getParentGroup&token=' . $this->session->data['token']);

		// Breadcrumbs

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('customerpartner/customer_group/getForm', 'token=' . $this->session->data['token'] , 'SSL')
		);


		$data['header'] = $this->load->controller("common/header");
		$data['footer'] = $this->load->controller("common/footer");
		$data['column_left'] = $this->load->controller("common/column_left");

		$this->response->setOutput($this->load->view("customerpartner/customer_group_form.tpl",$data));

	}

	public function addCustomerGroup() {
		$this->load->language('customerpartner/customer_group');
		$this->load->model('customerpartner/customer_group');
		if($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateForm()) {
			$this->load->model("localisation/language");
			$languages = $this->model_localisation_language->getLanguages();
			$flag = 0;
			foreach ($languages as $key => $language) {
				$ocCustomerGroupArray['customer_group_description'][$language['language_id']] = array (
					'name' => $this->request->post['customerGroupName'][$flag]['name'],
					'description' => $this->request->post['customerGroupDescription'][$flag]['description'],
				);
				$flag++;
			}
			$ocCustomerGroupArray['approval'] = 0;
    		$ocCustomerGroupArray['sort_order'] = 0;
    		$this->request->post['ocCustomerGroup'] = $ocCustomerGroupArray;
    		
			$this->model_customerpartner_customer_group->addCustomerGroupDetails($this->request->post);

			$this->session->data['success'] = $this->language->get('success_add');
			$this->response->redirect($this->url->link('customerpartner/customer_group', 'token='.$this->session->data['token'], 'SSL'));

		}
		$this->getForm();
	}

	public function editCustomerGroup() {
		$this->load->language('customerpartner/customer_group');
		$this->load->model('customerpartner/customer_group');
		if($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateForm()) {
			$this->load->model("localisation/language");
			$languages = $this->model_localisation_language->getLanguages();
			$flag = 0;
			foreach ($languages as $key => $language) {
				$ocCustomerGroupArray['customer_group_description'][$language['language_id']] = array (
					'name' => $this->request->post['customerGroupName'][$flag]['name'],
					'description' => $this->request->post['customerGroupDescription'][$flag]['description'],
				);
				$flag++;
			}
			$ocCustomerGroupArray['approval'] = 0;
    		$ocCustomerGroupArray['sort_order'] = 0;
    		$this->request->post['ocCustomerGroup'] = $ocCustomerGroupArray;
			$this->model_customerpartner_customer_group->editCustomerGroupDetails($this->request->post);
			$this->session->data['success'] = $this->language->get('success_update');
			$this->response->redirect($this->url->link("customerpartner/customer_group",'token='.$this->session->data['token'], 'SSL'));
		}
		$this->request->get['id'] = $this->request->post['customer_group_id'];
		$this->getForm();
	}

	public function deleteGroup() {
		$this->load->language('customerpartner/customer_group');
		$this->load->model('customerpartner/customer_group');
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			if(isset($this->request->post['select']) && $this->request->post['select']) {
				foreach ($this->request->post['select'] as $key => $value) {
					$this->model_customerpartner_customer_group->deleteGroup($value);
				}
				$this->session->data['success'] = $this->language->get('success_delete');
				$this->response->redirect($this->url->link('customerpartner/customer_group','token='.$this->session->data['token'], 'SSL'));
			} else {
				$this->error['error_warning'] = $this->language->get('error_delete');
			}
		}
		$this->getList();
	}

	public function getParentGroup() {
		$this->load->model("customerpartner/customer_group");
		$this->load->language("customerpartner/customer_group");
		$rights = array(
			'make-order' => $this->language->get('text_rights_make_order'),
			'view-all-order' => $this->language->get('text_rights_view_all_orders'),
			// 'license-others' => $this->language->get('text_rights_licenses_others'),
			'create-user' => $this->language->get('text_rights_create_user'),
			'offer-discount' => $this->language->get('text_rights_offer_discount'),
			'addproduct' => $this->language->get('text_rights_addedit_product'),
			'productlist' => $this->language->get('text_rights_access_productlist'),
			'profile' => $this->language->get('text_rights_access_profile'),
			'db' => $this->language->get('text_rights_access_dashboard'),
			'dbe' => $this->language->get('text_rights_edit_dashboard'),
		);
		$json = array();
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$data = array (
				'groupIsParent' => 0,
				'groupName' => $this->request->post['filterGroupName'],
				'start' => 0,
				'limit' => 50,
			);
			$parentGroups = $this->model_customerpartner_customer_group->getCustomerGroupList($data);
			if($parentGroups) {
				foreach ($parentGroups as $key => $group) {
					$index = array();
					$rightsIndex = explode(':', rtrim($group['rights'],":"));
					foreach ($rightsIndex as $key => $value) {
						$index[] = array(
							'index' => $value,
							'text' => $rights[$value],
						);
					}
					$json[] = array (
						'name' => $group['name'],
						'id' => $group['id'],
						'rights' => $index,
					);
				}
			} else {
				return false;
			}
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}

	public function validateForm() {
		if (!$this->user->hasPermission('modify', 'customerpartner/customer_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['customerGroupName'] as $key => $post) {
			if((utf8_strlen($post['name']) < 1) || (utf8_strlen($post['name']) > 64)) {
				$this->error['name'][$post['language_id']] = $this->language->get('error_name');
			}
		}

		foreach ($this->request->post['customerGroupDescription'] as $key => $post) {
			if((utf8_strlen($post['description']) < 10) || (utf8_strlen($post['description']) > 400)) {
				$this->error['description'][$post['language_id']] = $this->language->get('error_description');
			}	
		}

		return !$this->error;
	}

}
?>
