<?php
// -----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
// -----------------------------------------------------
class ControllerNeMarketing extends Controller {
	private $error = array ();
	public function index() {
		$this->load->language ( 'ne/marketing' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'ne/marketing' );
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$filter_name = $this->request->get ['filter_name'];
		} else {
			$filter_name = null;
		}
		
		if (isset ( $this->request->get ['filter_email'] )) {
			$filter_email = $this->request->get ['filter_email'];
		} else {
			$filter_email = null;
		}
		
		if (isset ( $this->request->get ['filter_subscribed'] )) {
			$filter_subscribed = $this->request->get ['filter_subscribed'];
		} else {
			$filter_subscribed = null;
		}
		
		if (isset ( $this->request->get ['filter_list'] )) {
			$filter_list = $this->request->get ['filter_list'];
		} else {
			$filter_list = null;
		}
		
		if (isset ( $this->request->get ['filter_store'] )) {
			$filter_store = $this->request->get ['filter_store'];
		} else {
			$filter_store = null;
		}
		
		if (isset ( $this->request->get ['filter_language'] )) {
			$filter_language = $this->request->get ['filter_language'];
		} else {
			$filter_language = null;
		}
		
		if (isset ( $this->request->get ['sort'] )) {
			$sort = $this->request->get ['sort'];
		} else {
			$sort = 'name';
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$order = $this->request->get ['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		$url = '';
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$url .= '&filter_name=' . $this->request->get ['filter_name'];
		}
		
		if (isset ( $this->request->get ['filter_email'] )) {
			$url .= '&filter_email=' . $this->request->get ['filter_email'];
		}
		
		if (isset ( $this->request->get ['filter_subscribed'] )) {
			$url .= '&filter_subscribed=' . $this->request->get ['filter_subscribed'];
		}
		
		if (isset ( $this->request->get ['filter_list'] )) {
			$url .= '&filter_list=' . $this->request->get ['filter_list'];
		}
		
		if (isset ( $this->request->get ['filter_store'] )) {
			$url .= '&filter_store=' . $this->request->get ['filter_store'];
		}
		
		if (isset ( $this->request->get ['filter_language'] )) {
			$url .= '&filter_language=' . $this->request->get ['filter_language'];
		}
		
		if (isset ( $this->request->get ['sort'] )) {
			$url .= '&sort=' . $this->request->get ['sort'];
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$url .= '&order=' . $this->request->get ['order'];
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
		}
		
		$data ['list_data'] = $this->config->get ( 'ne_marketing_list' );
		
		$data ['subscribe'] = $this->url->link ( 'ne/marketing/subscribe', 'token=' . $this->session->data ['token'] . $url . '&id=', 'SSL' );
		$data ['unsubscribe'] = $this->url->link ( 'ne/marketing/unsubscribe', 'token=' . $this->session->data ['token'] . $url . '&id=', 'SSL' );
		
		$data_param = array (
				'filter_name' => $filter_name,
				'filter_email' => $filter_email,
				'filter_subscribed' => $filter_subscribed,
				'filter_list' => explode ( ',', $filter_list ),
				'filter_store' => $filter_store,
				'filter_language' => $filter_language,
				'sort' => $sort,
				'order' => $order,
				'start' => ($page - 1) * $this->config->get ( 'config_limit_admin' ),
				'limit' => $this->config->get ( 'config_limit_admin' ) 
		);
		
		$marketings_total = $this->model_ne_marketing->getTotal ( $data_param );
		
		$data ['marketings'] = array ();
		
		$results = $this->model_ne_marketing->getList ( $data_param );
		
		foreach ( $results as $result ) {
			$data ['marketings'] [] = array_merge ( $result, array (
					'selected' => isset ( $this->request->post ['selected'] ) && is_array ( $this->request->post ['selected'] ) && in_array ( $result ['marketing_id'], $this->request->post ['selected'] ) 
			) );
		}
		unset ( $result );
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		
		$data ['entry_yes'] = $this->language->get ( 'entry_yes' );
		$data ['entry_no'] = $this->language->get ( 'entry_no' );
		$data ['entry_store'] = $this->language->get ( 'entry_store' );
		$data ['entry_subscribers'] = $this->language->get ( 'entry_subscribers' );
		$data ['help_subscribers'] = $this->language->get ( 'help_subscribers' );
		
		$data ['text_no_results'] = $this->language->get ( 'text_no_results' );
		$data ['text_add_info'] = $this->language->get ( 'text_add_info' );
		$data ['text_default'] = $this->language->get ( 'text_default' );
		$data ['text_marketing_subscribers'] = $this->language->get ( 'text_marketing_subscribers' );
		$data ['text_marketing_subscribers_list'] = $this->language->get ( 'text_marketing_subscribers_list' );
		
		$data ['column_name'] = $this->language->get ( 'column_name' );
		$data ['column_email'] = $this->language->get ( 'column_email' );
		$data ['column_subscribed'] = $this->language->get ( 'column_subscribed' );
		$data ['column_list'] = $this->language->get ( 'column_list' );
		$data ['column_actions'] = $this->language->get ( 'column_actions' );
		$data ['column_store'] = $this->language->get ( 'column_store' );
		$data ['column_language'] = $this->language->get ( 'column_language' );
		
		$data ['button_csv'] = $this->language->get ( 'button_csv' );
		$data ['button_subscribe'] = $this->language->get ( 'button_subscribe' );
		$data ['button_unsubscribe'] = $this->language->get ( 'button_unsubscribe' );
		$data ['button_insert'] = $this->language->get ( 'button_insert' );
		$data ['button_filter'] = $this->language->get ( 'button_filter' );
		$data ['button_delete'] = $this->language->get ( 'button_delete' );
		
		$data ['config_language_id'] = $this->config->get ( 'config_language_id' );
		
		$data ['token'] = $this->session->data ['token'];
		
		$data ['action'] = $this->url->link ( 'ne/marketing/add', 'token=' . $this->session->data ['token'], 'SSL' );
		$data ['delete'] = $this->url->link ( 'ne/marketing/delete', 'token=' . $this->session->data ['token'] . $url, 'SSL' );
		$data ['csv'] = $this->url->link ( 'ne/marketing/csv', 'token=' . $this->session->data ['token'] . $url, 'SSL' );
		
		if (isset ( $this->error ['warning'] )) {
			$data ['error_warning'] = $this->error ['warning'];
		} else {
			$data ['error_warning'] = $this->config->get ( 'ne_warning' );
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$data ['success'] = '';
		}
		
		$url = '';
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$url .= '&filter_name=' . $this->request->get ['filter_name'];
		}
		
		if (isset ( $this->request->get ['filter_email'] )) {
			$url .= '&filter_email=' . $this->request->get ['filter_email'];
		}
		
		if (isset ( $this->request->get ['filter_subscribed'] )) {
			$url .= '&filter_subscribed=' . $this->request->get ['filter_subscribed'];
		}
		
		if (isset ( $this->request->get ['filter_list'] )) {
			$url .= '&filter_list=' . $this->request->get ['filter_list'];
		}
		
		if (isset ( $this->request->get ['filter_store'] )) {
			$url .= '&filter_store=' . $this->request->get ['filter_store'];
		}
		
		if (isset ( $this->request->get ['filter_language'] )) {
			$url .= '&filter_language=' . $this->request->get ['filter_language'];
		}
		
		if ($order == 'ASC') {
			$url .= '&order=' . 'DESC';
		} else {
			$url .= '&order=' . 'ASC';
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
		}
		
		$data ['sort_name'] = $this->url->link ( 'ne/marketing', 'token=' . $this->session->data ['token'] . '&sort=name' . $url, 'SSL' );
		$data ['sort_email'] = $this->url->link ( 'ne/marketing', 'token=' . $this->session->data ['token'] . '&sort=email' . $url, 'SSL' );
		$data ['sort_subscribed'] = $this->url->link ( 'ne/marketing', 'token=' . $this->session->data ['token'] . '&sort=subscribed' . $url, 'SSL' );
		$data ['sort_store'] = $this->url->link ( 'ne/marketing', 'token=' . $this->session->data ['token'] . '&sort=store_id' . $url, 'SSL' );
		$data ['sort_language'] = $this->url->link ( 'ne/marketing', 'token=' . $this->session->data ['token'] . '&sort=language_code' . $url, 'SSL' );
		
		$url = '';
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$url .= '&filter_name=' . $this->request->get ['filter_name'];
		}
		
		if (isset ( $this->request->get ['filter_email'] )) {
			$url .= '&filter_email=' . $this->request->get ['filter_email'];
		}
		
		if (isset ( $this->request->get ['filter_subscribed'] )) {
			$url .= '&filter_subscribed=' . $this->request->get ['filter_subscribed'];
		}
		
		if (isset ( $this->request->get ['filter_list'] )) {
			$url .= '&filter_list=' . $this->request->get ['filter_list'];
		}
		
		if (isset ( $this->request->get ['filter_store'] )) {
			$url .= '&filter_store=' . $this->request->get ['filter_store'];
		}
		
		if (isset ( $this->request->get ['filter_language'] )) {
			$url .= '&filter_language=' . $this->request->get ['filter_language'];
		}
		
		if (isset ( $this->request->get ['sort'] )) {
			$url .= '&sort=' . $this->request->get ['sort'];
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$url .= '&order=' . $this->request->get ['order'];
		}
		
		if (isset ( $this->request->post ['selected'] )) {
			$data ['selected'] = ( array ) $this->request->post ['selected'];
		} else {
			$data ['selected'] = array ();
		}
		
		$pagination = new Pagination ();
		$pagination->total = $marketings_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get ( 'config_limit_admin' );
		$pagination->url = $this->url->link ( 'ne/marketing', 'token=' . $this->session->data ['token'] . $url . '&page={page}', 'SSL' );
		
		$data ['pagination'] = $pagination->render ();
		
		$data ['results'] = sprintf ( $this->language->get ( 'text_pagination' ), ($marketings_total) ? (($page - 1) * $this->config->get ( 'config_limit_admin' )) + 1 : 0, ((($page - 1) * $this->config->get ( 'config_limit_admin' )) > ($marketings_total - $this->config->get ( 'config_limit_admin' ))) ? $marketings_total : ((($page - 1) * $this->config->get ( 'config_limit_admin' )) + $this->config->get ( 'config_limit_admin' )), $marketings_total, ceil ( $marketings_total / $this->config->get ( 'config_limit_admin' ) ) );
		
		$this->load->model ( 'setting/store' );
		
		$data ['stores'] = $this->model_setting_store->getStores ();
		
		$this->load->model ( 'localisation/language' );
		
		$data ['languages'] = $this->model_localisation_language->getLanguages ();
		
		$data ['filter_name'] = $filter_name;
		$data ['filter_email'] = $filter_email;
		$data ['filter_subscribed'] = $filter_subscribed;
		$data ['filter_list'] = explode ( ',', $filter_list );
		$data ['filter_store'] = $filter_store;
		$data ['filter_language'] = $filter_language;
		
		$data ['sort'] = $sort;
		$data ['order'] = $order;
		
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		
		$this->response->setOutput ( $this->load->view ( 'ne/marketing.tpl', $data ) );
	}
	public function unsubscribe() {
		$this->load->model ( 'ne/marketing' );
		
		$url = '';
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$url .= '&filter_name=' . $this->request->get ['filter_name'];
		}
		
		if (isset ( $this->request->get ['filter_email'] )) {
			$url .= '&filter_email=' . $this->request->get ['filter_email'];
		}
		
		if (isset ( $this->request->get ['filter_subscribed'] )) {
			$url .= '&filter_subscribed=' . $this->request->get ['filter_subscribed'];
		}
		
		if (isset ( $this->request->get ['filter_list'] )) {
			$url .= '&filter_list=' . $this->request->get ['filter_list'];
		}
		
		if (isset ( $this->request->get ['filter_store'] )) {
			$url .= '&filter_store=' . $this->request->get ['filter_store'];
		}
		
		if (isset ( $this->request->get ['filter_language'] )) {
			$url .= '&filter_language=' . $this->request->get ['filter_language'];
		}
		
		if (isset ( $this->request->get ['sort'] )) {
			$url .= '&sort=' . $this->request->get ['sort'];
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$url .= '&order=' . $this->request->get ['order'];
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
		}
		
		if (isset ( $this->request->get ['id'] ))
			$this->model_ne_marketing->unsubscribe ( $this->request->get ['id'] );
		
		$this->response->redirect ( $this->url->link ( 'ne/marketing', 'token=' . $this->session->data ['token'] . $url, 'SSL' ) );
	}
	public function subscribe() {
		$this->load->model ( 'ne/marketing' );
		
		$url = '';
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$url .= '&filter_name=' . $this->request->get ['filter_name'];
		}
		
		if (isset ( $this->request->get ['filter_email'] )) {
			$url .= '&filter_email=' . $this->request->get ['filter_email'];
		}
		
		if (isset ( $this->request->get ['filter_subscribed'] )) {
			$url .= '&filter_subscribed=' . $this->request->get ['filter_subscribed'];
		}
		
		if (isset ( $this->request->get ['filter_list'] )) {
			$url .= '&filter_list=' . $this->request->get ['filter_list'];
		}
		
		if (isset ( $this->request->get ['filter_store'] )) {
			$url .= '&filter_store=' . $this->request->get ['filter_store'];
		}
		
		if (isset ( $this->request->get ['filter_language'] )) {
			$url .= '&filter_language=' . $this->request->get ['filter_language'];
		}
		
		if (isset ( $this->request->get ['sort'] )) {
			$url .= '&sort=' . $this->request->get ['sort'];
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$url .= '&order=' . $this->request->get ['order'];
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
		}
		
		if (isset ( $this->request->get ['id'] ))
			$this->model_ne_marketing->subscribe ( $this->request->get ['id'] );
		
		$this->response->redirect ( $this->url->link ( 'ne/marketing', 'token=' . $this->session->data ['token'] . $url, 'SSL' ) );
	}
	public function add() {
		$this->load->language ( 'ne/marketing' );
		$this->load->model ( 'ne/marketing' );
		
		$url = '';
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$url .= '&filter_name=' . $this->request->get ['filter_name'];
		}
		
		if (isset ( $this->request->get ['filter_email'] )) {
			$url .= '&filter_email=' . $this->request->get ['filter_email'];
		}
		
		if (isset ( $this->request->get ['filter_subscribed'] )) {
			$url .= '&filter_subscribed=' . $this->request->get ['filter_subscribed'];
		}
		
		if (isset ( $this->request->get ['filter_list'] )) {
			$url .= '&filter_list=' . $this->request->get ['filter_list'];
		}
		
		if (isset ( $this->request->get ['filter_store'] )) {
			$url .= '&filter_store=' . $this->request->get ['filter_store'];
		}
		
		if (isset ( $this->request->get ['filter_language'] )) {
			$url .= '&filter_language=' . $this->request->get ['filter_language'];
		}
		
		if (isset ( $this->request->get ['sort'] )) {
			$url .= '&sort=' . $this->request->get ['sort'];
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$url .= '&order=' . $this->request->get ['order'];
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
		}
		
		if (isset ( $this->request->post ['emails'] ) && isset ( $this->request->post ['store_id'] )) {
			$count = $this->model_ne_marketing->add ( $this->request->post, $this->config->get ( 'ne_key' ) );
			$this->session->data ['success'] = sprintf ( $this->language->get ( 'text_success' ), $count );
		}
		
		$this->response->redirect ( $this->url->link ( 'ne/marketing', 'token=' . $this->session->data ['token'] . $url, 'SSL' ) );
	}
	public function delete() {
		$this->load->language ( 'ne/marketing' );
		$this->load->model ( 'ne/marketing' );
		
		$url = '';
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$url .= '&filter_name=' . $this->request->get ['filter_name'];
		}
		
		if (isset ( $this->request->get ['filter_email'] )) {
			$url .= '&filter_email=' . $this->request->get ['filter_email'];
		}
		
		if (isset ( $this->request->get ['filter_subscribed'] )) {
			$url .= '&filter_subscribed=' . $this->request->get ['filter_subscribed'];
		}
		
		if (isset ( $this->request->get ['filter_list'] )) {
			$url .= '&filter_list=' . $this->request->get ['filter_list'];
		}
		
		if (isset ( $this->request->get ['filter_store'] )) {
			$url .= '&filter_store=' . $this->request->get ['filter_store'];
		}
		
		if (isset ( $this->request->get ['filter_language'] )) {
			$url .= '&filter_language=' . $this->request->get ['filter_language'];
		}
		
		if (isset ( $this->request->get ['sort'] )) {
			$url .= '&sort=' . $this->request->get ['sort'];
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$url .= '&order=' . $this->request->get ['order'];
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
		}
		
		if (isset ( $this->request->post ['selected'] ) && $this->validate ()) {
			foreach ( $this->request->post ['selected'] as $marketing_id ) {
				$this->model_ne_marketing->delete ( $marketing_id );
			}
			
			$this->session->data ['success'] = $this->language->get ( 'text_success_delete' );
		}
		
		$this->response->redirect ( $this->url->link ( 'ne/marketing', 'token=' . $this->session->data ['token'] . $url, 'SSL' ) );
	}
	private function validate() {
		if (! $this->user->hasPermission ( 'modify', 'ne/marketing' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission_delete' );
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function csv() {
		$this->load->language ( 'ne/marketing' );
		
		$this->load->model ( 'ne/marketing' );
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$filter_name = $this->request->get ['filter_name'];
		} else {
			$filter_name = null;
		}
		
		if (isset ( $this->request->get ['filter_email'] )) {
			$filter_email = $this->request->get ['filter_email'];
		} else {
			$filter_email = null;
		}
		
		if (isset ( $this->request->get ['filter_subscribed'] )) {
			$filter_subscribed = $this->request->get ['filter_subscribed'];
		} else {
			$filter_subscribed = null;
		}
		
		if (isset ( $this->request->get ['filter_list'] )) {
			$filter_list = $this->request->get ['filter_list'];
		} else {
			$filter_list = null;
		}
		
		if (isset ( $this->request->get ['filter_store'] )) {
			$filter_store = $this->request->get ['filter_store'];
		} else {
			$filter_store = null;
		}
		
		if (isset ( $this->request->get ['filter_language'] )) {
			$filter_language = $this->request->get ['filter_language'];
		} else {
			$filter_language = null;
		}
		
		if (isset ( $this->request->get ['sort'] )) {
			$sort = $this->request->get ['sort'];
		} else {
			$sort = 'name';
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$order = $this->request->get ['order'];
		} else {
			$order = 'ASC';
		}
		
		$list_data = $this->config->get ( 'ne_marketing_list' );
		
		$this->load->model ( 'setting/store' );
		
		$stores = $this->model_setting_store->getStores ();
		
		$this->load->model ( 'localisation/language' );
		
		$languages = $this->model_localisation_language->getLanguages ();
		
		$data_param = array (
				'filter_name' => $filter_name,
				'filter_email' => $filter_email,
				'filter_subscribed' => $filter_subscribed,
				'filter_list' => explode ( ',', $filter_list ),
				'filter_store' => $filter_store,
				'filter_language' => $filter_language,
				'sort' => $sort,
				'order' => $order 
		);
		
		$results = $this->model_ne_marketing->getList ( $data_param );
		
		$out = array ();
		
		foreach ( $results as $result ) {
			$row = array (
					'name' => trim ( $result ['name'] ),
					'email' => $result ['email'],
					'subscribed' => ($result ['subscribed'] == 1 ? $this->language->get ( 'entry_yes' ) : $this->language->get ( 'entry_no' )) 
			);
			
			if ($list_data) {
				$list_out = array ();
				foreach ( $result ['list'] as $list_key ) {
					if (isset ( $list_data [$result ['store_id']] [$list_key] )) {
						$list_out [] = $list_data [$result ['store_id']] [$list_key] [$this->config->get ( 'config_language_id' )];
					}
				}
				if ($list_out) {
					$row ['lists'] = implode ( ', ', $list_out );
				} else {
					$row ['lists'] = '';
				}
			}
			
			$row ['store'] = $this->language->get ( 'text_default' );
			foreach ( $stores as $store ) {
				if ($result ['store_id'] == $store ['store_id']) {
					$row ['store'] = $store ['name'];
					break;
				}
			}
			
			$row ['language'] = $this->language->get ( 'text_default' );
			foreach ( $languages as $language ) {
				if ($result ['language_code'] == $language ['code']) {
					$row ['language'] = $language ['name'];
					break;
				}
			}
			
			$out [] = $row;
		}
		unset ( $result );
		
		$filename = 'marketing_' . date ( 'Y-m-d_H-i-s' ) . '.csv';
		
		header ( 'Content-Type: text/csv' );
		header ( 'Content-Disposition: attachment;filename=' . $filename );
		
		$fp = fopen ( 'php://output', 'w' );
		
		if (count ( $out )) {
			fputcsv ( $fp, array_keys ( $out [0] ) );
			
			foreach ( $out as $row ) {
				fputcsv ( $fp, $row );
			}
		}
	}
}