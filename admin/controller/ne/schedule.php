<?php
// -----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
// -----------------------------------------------------
class ControllerNeSchedule extends Controller {
	private $error = array ();
	public function index() {
		$this->load->language ( 'sale/contact' );
		$this->load->language ( 'ne/schedule' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'ne/schedule' );
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$filter_name = $this->request->get ['filter_name'];
		} else {
			$filter_name = null;
		}
		
		if (isset ( $this->request->get ['filter_active'] )) {
			$filter_active = $this->request->get ['filter_active'];
		} else {
			$filter_active = null;
		}
		
		if (isset ( $this->request->get ['filter_recurrent'] )) {
			$filter_recurrent = $this->request->get ['filter_recurrent'];
		} else {
			$filter_recurrent = null;
		}
		
		if (isset ( $this->request->get ['filter_to'] )) {
			$filter_to = $this->request->get ['filter_to'];
		} else {
			$filter_to = null;
		}
		
		if (isset ( $this->request->get ['filter_store'] )) {
			$filter_store = $this->request->get ['filter_store'];
		} else {
			$filter_store = null;
		}
		
		if (isset ( $this->request->get ['sort'] )) {
			$sort = $this->request->get ['sort'];
		} else {
			$sort = 'schedule_id';
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$order = $this->request->get ['order'];
		} else {
			$order = 'DESC';
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
		
		if (isset ( $this->request->get ['filter_active'] )) {
			$url .= '&filter_active=' . $this->request->get ['filter_active'];
		}
		
		if (isset ( $this->request->get ['filter_recurrent'] )) {
			$url .= '&filter_recurrent=' . $this->request->get ['filter_recurrent'];
		}
		
		if (isset ( $this->request->get ['filter_to'] )) {
			$url .= '&filter_to=' . $this->request->get ['filter_to'];
		}
		
		if (isset ( $this->request->get ['filter_store'] )) {
			$url .= '&filter_store=' . $this->request->get ['filter_store'];
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
		
		$data ['insert'] = $this->url->link ( 'ne/schedule/insert', 'token=' . $this->session->data ['token'], 'SSL' );
		$data ['delete'] = $this->url->link ( 'ne/schedule/delete', 'token=' . $this->session->data ['token'] . $url, 'SSL' );
		
		$data_param = array (
				'filter_name' => $filter_name,
				'filter_active' => $filter_active,
				'filter_recurrent' => $filter_recurrent,
				'filter_to' => $filter_to,
				'filter_store' => $filter_store,
				'sort' => $sort,
				'order' => $order,
				'start' => ($page - 1) * $this->config->get ( 'config_limit_admin' ),
				'limit' => $this->config->get ( 'config_limit_admin' ) 
		);
		
		$total = $this->model_ne_schedule->getTotal ( $data_param );
		
		$data ['schedule'] = array ();
		
		$results = $this->model_ne_schedule->getList ( $data_param );
		
		foreach ( $results as $result ) {
			$data ['schedule'] [] = array_merge ( $result, array (
					'selected' => isset ( $this->request->post ['selected'] ) && is_array ( $this->request->post ['selected'] ) && in_array ( $result ['schedule_id'], $this->request->post ['selected'] ) 
			) );
		}
		unset ( $result );
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		
		$data ['entry_yes'] = $this->language->get ( 'entry_yes' );
		$data ['entry_no'] = $this->language->get ( 'entry_no' );
		
		$data ['text_no_results'] = $this->language->get ( 'text_no_results' );
		
		$data ['column_name'] = $this->language->get ( 'column_name' );
		$data ['column_active'] = $this->language->get ( 'column_active' );
		$data ['column_recurrent'] = $this->language->get ( 'column_recurrent' );
		$data ['column_to'] = $this->language->get ( 'column_to' );
		$data ['column_actions'] = $this->language->get ( 'column_actions' );
		$data ['column_store'] = $this->language->get ( 'column_store' );
		
		$data ['button_delete'] = $this->language->get ( 'button_delete' );
		$data ['button_filter'] = $this->language->get ( 'button_filter' );
		$data ['button_insert'] = $this->language->get ( 'button_insert' );
		$data ['button_edit'] = $this->language->get ( 'button_edit' );
		
		$data ['text_marketing'] = $this->language->get ( 'text_marketing' );
		$data ['text_marketing_all'] = $this->language->get ( 'text_marketing_all' );
		$data ['text_subscriber_all'] = $this->language->get ( 'text_subscriber_all' );
		$data ['text_all'] = $this->language->get ( 'text_all' );
		$data ['text_newsletter'] = $this->language->get ( 'text_newsletter' );
		$data ['text_customer_all'] = $this->language->get ( 'text_customer_all' );
		$data ['text_customer'] = $this->language->get ( 'text_customer' );
		$data ['text_customer_group'] = $this->language->get ( 'text_customer_group' );
		$data ['text_affiliate_all'] = $this->language->get ( 'text_affiliate_all' );
		$data ['text_affiliate'] = $this->language->get ( 'text_affiliate' );
		$data ['text_product'] = $this->language->get ( 'text_product' );
		$data ['text_default'] = $this->language->get ( 'text_default' );
		$data ['text_newsletters_schedule'] = $this->language->get ( 'text_newsletters_schedule' );
		
		$data ['token'] = $this->session->data ['token'];
		
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
		
		if (isset ( $this->request->get ['filter_active'] )) {
			$url .= '&filter_active=' . $this->request->get ['filter_active'];
		}
		
		if (isset ( $this->request->get ['filter_recurrent'] )) {
			$url .= '&filter_recurrent=' . $this->request->get ['filter_recurrent'];
		}
		
		if (isset ( $this->request->get ['filter_to'] )) {
			$url .= '&filter_to=' . $this->request->get ['filter_to'];
		}
		
		if (isset ( $this->request->get ['filter_store'] )) {
			$url .= '&filter_store=' . $this->request->get ['filter_store'];
		}
		
		if ($order == 'ASC') {
			$url .= '&order=' . 'DESC';
		} else {
			$url .= '&order=' . 'ASC';
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
		}
		
		$data ['sort_name'] = $this->url->link ( 'ne/schedule', 'token=' . $this->session->data ['token'] . '&sort=name' . $url, 'SSL' );
		$data ['sort_active'] = $this->url->link ( 'ne/schedule', 'token=' . $this->session->data ['token'] . '&sort=active' . $url, 'SSL' );
		$data ['sort_recurrent'] = $this->url->link ( 'ne/schedule', 'token=' . $this->session->data ['token'] . '&sort=recurrent' . $url, 'SSL' );
		$data ['sort_to'] = $this->url->link ( 'ne/schedule', 'token=' . $this->session->data ['token'] . '&sort=to' . $url, 'SSL' );
		$data ['sort_store'] = $this->url->link ( 'ne/schedule', 'token=' . $this->session->data ['token'] . '&sort=store_id' . $url, 'SSL' );
		
		$url = '';
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$url .= '&filter_name=' . $this->request->get ['filter_name'];
		}
		
		if (isset ( $this->request->get ['filter_active'] )) {
			$url .= '&filter_active=' . $this->request->get ['filter_active'];
		}
		
		if (isset ( $this->request->get ['filter_recurrent'] )) {
			$url .= '&filter_recurrent=' . $this->request->get ['filter_recurrent'];
		}
		
		if (isset ( $this->request->get ['filter_to'] )) {
			$url .= '&filter_to=' . $this->request->get ['filter_to'];
		}
		
		if (isset ( $this->request->get ['filter_store'] )) {
			$url .= '&filter_store=' . $this->request->get ['filter_store'];
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
		
		$data ['update'] = $this->url->link ( 'ne/schedule/update', 'token=' . $this->session->data ['token'] . $url . '&id=', 'SSL' );
		
		$pagination = new Pagination ();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get ( 'config_limit_admin' );
		$pagination->url = $this->url->link ( 'ne/schedule', 'token=' . $this->session->data ['token'] . $url . '&page={page}', 'SSL' );
		
		$data ['pagination'] = $pagination->render ();
		
		$data ['results'] = sprintf ( $this->language->get ( 'text_pagination' ), ($total) ? (($page - 1) * $this->config->get ( 'config_limit_admin' )) + 1 : 0, ((($page - 1) * $this->config->get ( 'config_limit_admin' )) > ($total - $this->config->get ( 'config_limit_admin' ))) ? $total : ((($page - 1) * $this->config->get ( 'config_limit_admin' )) + $this->config->get ( 'config_limit_admin' )), $total, ceil ( $total / $this->config->get ( 'config_limit_admin' ) ) );
		
		$this->load->model ( 'setting/store' );
		
		$data ['stores'] = $this->model_setting_store->getStores ();
		
		$data ['filter_name'] = $filter_name;
		$data ['filter_active'] = $filter_active;
		$data ['filter_recurrent'] = $filter_recurrent;
		$data ['filter_to'] = $filter_to;
		$data ['filter_store'] = $filter_store;
		
		$data ['sort'] = $sort;
		$data ['order'] = $order;
		
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		
		$this->response->setOutput ( $this->load->view ( 'ne/schedule.tpl', $data ) );
	}
	public function update() {
		$this->load->language ( 'sale/contact' );
		$this->load->language ( 'ne/newsletter' );
		$this->load->language ( 'ne/schedule' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'ne/schedule' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validate () && $this->validateForm ()) {
			if (isset ( $this->request->post ['recurrent'] ) && $this->request->post ['recurrent'] == '1') {
				if (strtotime ( $this->request->post ['date_next'] . ' ' . (strlen ( $this->request->post ['time'] ) == 1 ? '0' . $this->request->post ['time'] : $this->request->post ['time']) . ':00:00' ) < strtotime ( date ( 'Y-m-d' ) . ' ' . (strlen ( $this->request->post ['time'] ) == 1 ? '0' . $this->request->post ['time'] : $this->request->post ['time']) . ':00:00' )) {
					$this->request->post ['date_next'] = '0';
				}
			}
			$this->model_ne_schedule->save ( array_merge ( $this->request->post, array (
					'id' => $this->request->get ['id'] 
			) ) );
			$this->session->data ['success'] = $this->language->get ( 'text_success_save' );
			
			$this->response->redirect ( $this->url->link ( 'ne/schedule', 'token=' . $this->session->data ['token'], 'SSL' ) );
		}
		
		$this->form ();
	}
	public function insert() {
		$this->load->language ( 'sale/contact' );
		$this->load->language ( 'ne/newsletter' );
		$this->load->language ( 'ne/schedule' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'ne/schedule' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validate () && $this->validateForm ()) {
			$this->model_ne_schedule->save ( $this->request->post );
			$this->session->data ['success'] = $this->language->get ( 'text_success_save' );
			
			$this->response->redirect ( $this->url->link ( 'ne/schedule', 'token=' . $this->session->data ['token'], 'SSL' ) );
		}
		
		$this->form ();
	}
	private function form() {
		if (isset ( $this->request->get ['id'] )) {
			$schedule_id = $this->request->get ['id'];
			$schedule_info = $this->model_ne_schedule->get ( $schedule_id );
		} else {
			$schedule_info = array ();
		}
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		
		$data ['entry_template'] = $this->language->get ( 'entry_template' );
		$data ['entry_yes'] = $this->language->get ( 'entry_yes' );
		$data ['entry_no'] = $this->language->get ( 'entry_no' );
		$data ['entry_recurrent'] = $this->language->get ( 'entry_recurrent' );
		$data ['entry_random'] = $this->language->get ( 'entry_random' );
		$data ['entry_defined'] = $this->language->get ( 'entry_defined' );
		$data ['entry_latest'] = $this->language->get ( 'entry_latest' );
		$data ['entry_popular'] = $this->language->get ( 'entry_popular' );
		$data ['entry_special'] = $this->language->get ( 'entry_special' );
		$data ['entry_product'] = $this->language->get ( 'entry_product' );
		$data ['entry_store'] = $this->language->get ( 'entry_store' );
		$data ['entry_language'] = $this->language->get ( 'entry_language' );
		$data ['entry_currency'] = $this->language->get ( 'entry_currency' );
		$data ['entry_to'] = $this->language->get ( 'entry_to' );
		$data ['entry_customer_group'] = $this->language->get ( 'entry_customer_group' );
		$data ['entry_customer'] = $this->language->get ( 'entry_customer' );
		$data ['entry_affiliate'] = $this->language->get ( 'entry_affiliate' );
		$data ['entry_product'] = $this->language->get ( 'entry_product' );
		$data ['entry_subject'] = $this->language->get ( 'entry_subject' );
		$data ['entry_message'] = $this->language->get ( 'entry_message' );
		$data ['entry_text_message'] = $this->language->get ( 'entry_text_message' );
		$data ['entry_text_message_products'] = $this->language->get ( 'entry_text_message_products' );
		$data ['entry_name'] = $this->language->get ( 'entry_name' );
		$data ['entry_datetime'] = $this->language->get ( 'entry_datetime' );
		$data ['entry_frequency'] = $this->language->get ( 'entry_frequency' );
		$data ['entry_daytime'] = $this->language->get ( 'entry_daytime' );
		$data ['entry_active'] = $this->language->get ( 'entry_active' );
		$data ['entry_random_count'] = $this->language->get ( 'entry_random_count' );
		$data ['entry_marketing'] = $this->language->get ( 'entry_marketing' );
		$data ['entry_defined_categories'] = $this->language->get ( 'entry_defined_categories' );
		$data ['entry_section_name'] = $this->language->get ( 'entry_section_name' );
		
		$data ['help_random_count'] = $this->language->get ( 'help_random_count' );
		
		$data ['button_save'] = $this->language->get ( 'button_save' );
		$data ['button_cancel'] = $this->language->get ( 'button_cancel' );
		$data ['button_preview'] = $this->language->get ( 'button_preview' );
		$data ['button_remove'] = $this->language->get ( 'button_remove' );
		$data ['button_add_defined_section'] = $this->language->get ( 'button_add_defined_section' );
		
		$data ['text_marketing'] = $this->language->get ( 'text_marketing' );
		$data ['text_marketing_all'] = $this->language->get ( 'text_marketing_all' );
		$data ['text_subscriber_all'] = $this->language->get ( 'text_subscriber_all' );
		$data ['text_all'] = $this->language->get ( 'text_all' );
		$data ['text_clear_warning'] = $this->language->get ( 'text_clear_warning' );
		$data ['text_message_info'] = $this->language->get ( 'text_message_info' );
		$data ['text_default'] = $this->language->get ( 'text_default' );
		$data ['text_newsletter'] = $this->language->get ( 'text_newsletter' );
		$data ['text_customer_all'] = $this->language->get ( 'text_customer_all' );
		$data ['text_customer'] = $this->language->get ( 'text_customer' );
		$data ['text_customer_group'] = $this->language->get ( 'text_customer_group' );
		$data ['text_affiliate_all'] = $this->language->get ( 'text_affiliate_all' );
		$data ['text_affiliate'] = $this->language->get ( 'text_affiliate' );
		$data ['text_product'] = $this->language->get ( 'text_product' );
		$data ['text_newsletters_schedule'] = $this->language->get ( 'text_newsletters_schedule' );
		$data ['text_current_server_time'] = sprintf ( $this->language->get ( 'text_current_server_time' ), date ( 'Y-m-d H:i' ) );
		
		$data ['text_at'] = $this->language->get ( 'text_at' );
		$data ['text_daily'] = $this->language->get ( 'text_daily' );
		$data ['text_weekly'] = $this->language->get ( 'text_weekly' );
		$data ['text_monthly'] = $this->language->get ( 'text_monthly' );
		$data ['text_monday'] = $this->language->get ( 'text_monday' );
		$data ['text_tuesday'] = $this->language->get ( 'text_tuesday' );
		$data ['text_wednesday'] = $this->language->get ( 'text_wednesday' );
		$data ['text_thursday'] = $this->language->get ( 'text_thursday' );
		$data ['text_friday'] = $this->language->get ( 'text_friday' );
		$data ['text_saturday'] = $this->language->get ( 'text_saturday' );
		$data ['text_sunday'] = $this->language->get ( 'text_sunday' );
		$data ['text_loading'] = $this->language->get ( 'text_loading' );
		$data ['text_only_subscribers'] = $this->language->get ( 'text_only_subscribers' );
		$data ['text_only_selected_language'] = $this->language->get ( 'text_only_selected_language' );
		$data ['text_rewards'] = $this->language->get ( 'text_rewards' );
		$data ['text_rewards_all'] = $this->language->get ( 'text_rewards_all' );
		
		$data ['entry_limit'] = $this->language->get ( 'entry_limit' );
		$data ['entry_sort_by'] = $this->language->get ( 'entry_sort_by' );
		$data ['entry_order'] = $this->language->get ( 'entry_order' );
		
		$data ['text_date_added'] = $this->language->get ( 'text_date_added' );
		$data ['text_sort_order'] = $this->language->get ( 'text_sort_order' );
		$data ['text_ascending'] = $this->language->get ( 'text_ascending' );
		$data ['text_descending'] = $this->language->get ( 'text_descending' );
		
		$data ['text_skip_out_of_stock'] = $this->language->get ( 'text_skip_out_of_stock' );
		
		if (isset ( $this->request->post ['active'] )) {
			$data ['active'] = $this->request->post ['active'];
		} elseif (! empty ( $schedule_info )) {
			$data ['active'] = $schedule_info ['active'];
		} else {
			$data ['active'] = '';
		}
		
		if (isset ( $this->request->post ['name'] )) {
			$data ['name'] = $this->request->post ['name'];
		} elseif (! empty ( $schedule_info )) {
			$data ['name'] = $schedule_info ['name'];
		} else {
			$data ['name'] = '';
		}
		
		if (isset ( $this->request->post ['to'] )) {
			$data ['to'] = $this->request->post ['to'];
		} elseif (! empty ( $schedule_info )) {
			$data ['to'] = $schedule_info ['to'];
		} else {
			$data ['to'] = '';
		}
		
		if (isset ( $this->request->post ['date'] )) {
			$data ['date'] = $this->request->post ['date'];
		} elseif (! empty ( $schedule_info )) {
			$data ['date'] = $schedule_info ['date'];
		} else {
			$data ['date'] = date ( 'Y-m-d' );
		}
		
		if (isset ( $this->request->post ['date_next'] )) {
			$data ['date_next'] = $this->request->post ['date_next'];
		} elseif (! empty ( $schedule_info )) {
			$data ['date_next'] = $schedule_info ['date_next'];
		} else {
			$data ['date_next'] = '0';
		}
		
		if (isset ( $this->request->post ['time'] )) {
			$data ['time'] = $this->request->post ['time'];
		} elseif (! empty ( $schedule_info )) {
			$data ['time'] = $schedule_info ['time'];
		} else {
			$data ['time'] = '0';
		}
		
		if (isset ( $this->request->post ['rtime'] )) {
			$data ['rtime'] = $this->request->post ['rtime'];
		} elseif (! empty ( $schedule_info )) {
			$data ['rtime'] = $schedule_info ['time'];
		} else {
			$data ['rtime'] = '0';
		}
		
		if (isset ( $this->request->post ['day'] )) {
			$data ['day'] = $this->request->post ['day'];
		} elseif (! empty ( $schedule_info )) {
			$data ['day'] = $schedule_info ['day'];
		} else {
			$data ['day'] = '1';
		}
		
		if (isset ( $this->request->post ['frequency'] )) {
			$data ['frequency'] = $this->request->post ['frequency'];
		} elseif (! empty ( $schedule_info )) {
			$data ['frequency'] = $schedule_info ['frequency'];
		} else {
			$data ['frequency'] = '1';
		}
		
		if (isset ( $this->request->post ['template_id'] )) {
			$data ['template_id'] = $this->request->post ['template_id'];
		} elseif (! empty ( $schedule_info )) {
			$data ['template_id'] = $schedule_info ['template_id'];
		} else {
			$data ['template_id'] = '';
		}
		
		if (isset ( $this->request->post ['language_id'] )) {
			$data ['language_id'] = $this->request->post ['language_id'];
		} elseif (! empty ( $schedule_info )) {
			$data ['language_id'] = $schedule_info ['language_id'];
		} else {
			$data ['language_id'] = '';
		}
		
		if (isset ( $this->request->post ['store_id'] )) {
			$data ['store_id'] = $this->request->post ['store_id'];
		} elseif (! empty ( $schedule_info )) {
			$data ['store_id'] = $schedule_info ['store_id'];
		} else {
			$data ['store_id'] = '';
		}
		
		if (isset ( $this->request->post ['random'] )) {
			$data ['random'] = $this->request->post ['random'];
		} elseif (! empty ( $schedule_info )) {
			$data ['random'] = $schedule_info ['random'];
		} else {
			$data ['random'] = false;
		}
		
		if (isset ( $this->request->post ['random_count'] )) {
			$data ['random_count'] = $this->request->post ['random_count'];
		} elseif (! empty ( $schedule_info )) {
			$data ['random_count'] = $schedule_info ['random_count'];
		} else {
			$data ['random_count'] = false;
		}
		
		if (isset ( $this->request->post ['special'] )) {
			$data ['special'] = $this->request->post ['special'];
		} elseif (! empty ( $schedule_info )) {
			$data ['special'] = $schedule_info ['special'];
		} else {
			$data ['special'] = false;
		}
		
		if (isset ( $this->request->post ['latest'] )) {
			$data ['latest'] = $this->request->post ['latest'];
		} elseif (! empty ( $schedule_info )) {
			$data ['latest'] = $schedule_info ['latest'];
		} else {
			$data ['latest'] = false;
		}
		
		if (isset ( $this->request->post ['popular'] )) {
			$data ['popular'] = $this->request->post ['popular'];
		} elseif (! empty ( $schedule_info )) {
			$data ['popular'] = $schedule_info ['popular'];
		} else {
			$data ['popular'] = false;
		}
		
		if (isset ( $this->request->post ['recurrent'] )) {
			$data ['recurrent'] = $this->request->post ['recurrent'];
		} elseif (! empty ( $schedule_info )) {
			$data ['recurrent'] = $schedule_info ['recurrent'];
		} else {
			$data ['recurrent'] = false;
		}
		
		$this->load->model ( 'catalog/category' );
		
		$data ['categories'] = $this->model_catalog_category->getCategories ( 0 );
		
		if (isset ( $this->request->post ['defined_categories'] )) {
			$data ['defined_categories'] = $this->request->post ['defined_categories'];
		} elseif (! empty ( $schedule_info )) {
			$data ['defined_categories'] = $schedule_info ['categories'];
		} else {
			$data ['defined_categories'] = false;
		}
		
		if (isset ( $this->request->post ['defined_category'] )) {
			$data ['defined_category'] = $this->request->post ['defined_category'];
		} elseif (! empty ( $schedule_info )) {
			$data ['defined_category'] = unserialize ( $schedule_info ['defined_categories'] );
		} else {
			$data ['defined_category'] = array ();
		}
		
		if (isset ( $this->request->post ['skip_out_of_stock'] )) {
			$data ['skip_out_of_stock'] = $this->request->post ['skip_out_of_stock'];
		} elseif (! empty ( $schedule_info )) {
			$data ['skip_out_of_stock'] = unserialize ( $schedule_info ['skip_out_of_stock'] );
		} else {
			$data ['skip_out_of_stock'] = array ();
		}
		
		if (isset ( $this->request->post ['defined_category_limit'] )) {
			$data ['defined_category_limit'] = $this->request->post ['defined_category_limit'];
		} elseif (! empty ( $schedule_info )) {
			$data ['defined_category_limit'] = $schedule_info ['defined_category_limit'];
		} else {
			$data ['defined_category_limit'] = '8';
		}
		
		if (isset ( $this->request->post ['defined_category_sort'] )) {
			$data ['defined_category_sort'] = $this->request->post ['defined_category_sort'];
		} elseif (! empty ( $schedule_info )) {
			$data ['defined_category_sort'] = $schedule_info ['defined_category_sort'];
		} else {
			$data ['defined_category_sort'] = 'date_added';
		}
		
		if (isset ( $this->request->post ['defined_category_order'] )) {
			$data ['defined_category_order'] = $this->request->post ['defined_category_order'];
		} elseif (! empty ( $schedule_info )) {
			$data ['defined_category_order'] = $schedule_info ['defined_category_order'];
		} else {
			$data ['defined_category_order'] = '1';
		}
		
		if (isset ( $this->request->post ['subject'] )) {
			$data ['subject'] = $this->request->post ['subject'];
		} elseif (! empty ( $schedule_info )) {
			$data ['subject'] = $schedule_info ['subject'];
		} else {
			$data ['subject'] = '';
		}
		
		if (isset ( $this->request->post ['message'] )) {
			$data ['message'] = $this->request->post ['message'];
		} elseif (! empty ( $schedule_info )) {
			$data ['message'] = $schedule_info ['message'];
		} else {
			$data ['message'] = '';
		}
		
		if (isset ( $this->request->post ['text_message'] )) {
			$data ['text_message'] = $this->request->post ['text_message'];
		} elseif (! empty ( $schedule_info )) {
			$data ['text_message'] = $schedule_info ['text_message'];
		} else {
			$data ['text_message'] = '';
		}
		
		if (isset ( $this->request->post ['text_message_products'] )) {
			$data ['text_message_products'] = $this->request->post ['text_message_products'];
		} elseif (! empty ( $schedule_info )) {
			$data ['text_message_products'] = $schedule_info ['text_message_products'];
		} else {
			$data ['text_message_products'] = '';
		}
		
		if (isset ( $this->request->post ['customer_group_id'] )) {
			$data ['customer_group_id'] = $this->request->post ['customer_group_id'];
		} elseif (! empty ( $schedule_info )) {
			$data ['customer_group_id'] = $schedule_info ['customer_group_id'];
		} else {
			$data ['customer_group_id'] = '';
		}
		
		if (isset ( $this->request->post ['customer_group_only_subscribers'] )) {
			$data ['customer_group_only_subscribers'] = $this->request->post ['customer_group_only_subscribers'];
		} elseif (! empty ( $schedule_info )) {
			$data ['customer_group_only_subscribers'] = $schedule_info ['customer_group_only_subscribers'];
		} else {
			$data ['customer_group_only_subscribers'] = '';
		}
		
		if (isset ( $this->request->post ['only_selected_language'] )) {
			$data ['only_selected_language'] = $this->request->post ['only_selected_language'];
		} elseif (! empty ( $schedule_info )) {
			$data ['only_selected_language'] = $schedule_info ['only_selected_language'];
		} else {
			$data ['only_selected_language'] = '';
		}
		
		if (isset ( $this->request->post ['marketing_list'] )) {
			$data ['marketing_list'] = $this->request->post ['marketing_list'];
		} elseif (! empty ( $schedule_info )) {
			$data ['marketing_list'] = unserialize ( $schedule_info ['marketing_list'] );
		} else {
			$data ['marketing_list'] = array ();
		}
		
		$this->load->model ( 'catalog/product' );
		
		$data ['defined_products'] = array ();
		
		if (isset ( $this->request->post ['defined_product'] )) {
			$defined_products = $this->request->post ['defined_product'];
		} elseif (! empty ( $schedule_info )) {
			$defined_products = unserialize ( $schedule_info ['defined_products'] );
		} else {
			$defined_products = array ();
		}
		
		if ($defined_products) {
			foreach ( $defined_products as $product_id ) {
				$product_info = $this->model_catalog_product->getProduct ( $product_id );
				
				if ($product_info) {
					$data ['defined_products'] [] = array (
							'product_id' => $product_info ['product_id'],
							'name' => $product_info ['name'] 
					);
				}
			}
			unset ( $product_info );
			unset ( $product_id );
		}
		
		$data ['defined_products_more'] = array ();
		
		if (isset ( $this->request->post ['defined_product_more'] )) {
			$defined_products_more = $this->request->post ['defined_product_more'];
		} elseif (! empty ( $schedule_info )) {
			$defined_products_more = unserialize ( $schedule_info ['defined_products_more'] );
		} else {
			$defined_products_more = array ();
		}
		
		if ($defined_products_more) {
			foreach ( $defined_products_more as $dpm ) {
				if (! isset ( $dpm ['products'] )) {
					$dpm ['products'] = array ();
				}
				if (! isset ( $dpm ['text'] )) {
					$dpm ['text'] = '';
				}
				$dp_more = array (
						'text' => $dpm ['text'],
						'products' => array () 
				);
				foreach ( $dpm ['products'] as $product_id ) {
					$product_info = $this->model_catalog_product->getProduct ( $product_id );
					
					if ($product_info) {
						$dp_more ['products'] [] = array (
								'product_id' => $product_info ['product_id'],
								'name' => $product_info ['name'] 
						);
					}
				}
				$data ['defined_products_more'] [] = $dp_more;
			}
			unset ( $defined_products_more );
			unset ( $dp_more );
			unset ( $product_info );
			unset ( $product_id );
		}
		
		if (isset ( $this->request->post ['defined'] )) {
			$data ['defined'] = $this->request->post ['defined'];
		} elseif (! empty ( $schedule_info )) {
			$data ['defined'] = $schedule_info ['defined'];
		} else {
			$data ['defined'] = '';
		}
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home', 'token=' . $this->session->data ['token'], 'SSL' ) 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'heading_title' ),
				'href' => $this->url->link ( 'ne/schedule', 'token=' . $this->session->data ['token'], 'SSL' ) 
		);
		
		if (! isset ( $schedule_id )) {
			$data ['action'] = $this->url->link ( 'ne/schedule/insert', 'token=' . $this->session->data ['token'], 'SSL' );
		} else {
			$data ['action'] = $this->url->link ( 'ne/schedule/update', 'token=' . $this->session->data ['token'] . '&id=' . $schedule_id, 'SSL' );
		}
		
		$data ['cancel'] = $this->url->link ( 'ne/schedule', 'token=' . $this->session->data ['token'], 'SSL' );
		
		$data ['config_language_id'] = $this->config->get ( 'config_language_id' );
		
		$data ['token'] = $this->session->data ['token'];
		
		$this->load->model ( 'localisation/language' );
		
		$data ['languages'] = $this->model_localisation_language->getLanguages ();
		
		if (isset ( $this->request->post ['currency'] )) {
			$data ['currency'] = $this->request->post ['currency'];
		} elseif (! empty ( $schedule_info )) {
			$data ['currency'] = $schedule_info ['currency'];
		} else {
			$data ['currency'] = '';
		}
		
		$this->load->model ( 'localisation/currency' );
		
		$data ['currencies'] = $this->model_localisation_currency->getCurrencies ();
		
		$this->load->model ( 'setting/store' );
		
		$data ['stores'] = $this->model_setting_store->getStores ();
		
		$this->load->model ( 'sale/customer_group' );
		
		$data ['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups ( 0 );
		
		$this->load->model ( 'sale/customer' );
		
		$data ['customers'] = array ();
		$customers = array ();
		
		if (isset ( $this->request->post ['customer'] )) {
			$customers = $this->request->post ['customer'];
		} elseif (! empty ( $schedule_info )) {
			$customers = unserialize ( $schedule_info ['customer'] );
		}
		
		if ($customers)
			foreach ( $customers as $customer_id ) {
				$customer_info = $this->model_sale_customer->getCustomer ( $customer_id );
				
				if ($customer_info) {
					$data ['customers'] [] = array (
							'customer_id' => $customer_info ['customer_id'],
							'name' => $customer_info ['firstname'] . ' ' . $customer_info ['lastname'] 
					);
				}
			}
		
		$this->load->model ( 'marketing/affiliate' );
		
		$data ['affiliates'] = array ();
		$affiliates = array ();
		
		if (isset ( $this->request->post ['affiliate'] )) {
			$affiliates = $this->request->post ['affiliate'];
		} elseif (! empty ( $schedule_info )) {
			$affiliates = unserialize ( $schedule_info ['affiliate'] );
		}
		
		if ($affiliates)
			foreach ( $affiliates as $affiliate_id ) {
				$affiliate_info = $this->model_marketing_affiliate->getAffiliate ( $affiliate_id );
				
				if ($affiliate_info) {
					$data ['affiliates'] [] = array (
							'affiliate_id' => $affiliate_info ['affiliate_id'],
							'name' => $affiliate_info ['firstname'] . ' ' . $affiliate_info ['lastname'] 
					);
				}
			}
		
		$this->load->model ( 'catalog/product' );
		
		$data ['products'] = array ();
		$products = array ();
		
		if (isset ( $this->request->post ['product'] )) {
			$products = $this->request->post ['product'];
		} elseif (! empty ( $schedule_info )) {
			$products = unserialize ( $schedule_info ['product'] );
		}
		
		if ($products)
			foreach ( $products as $product_id ) {
				$product_info = $this->model_catalog_product->getProduct ( $product_id );
				
				if ($product_info) {
					$data ['products'] [] = array (
							'product_id' => $product_info ['product_id'],
							'name' => $product_info ['name'] 
					);
				}
			}
		
		$data ['list_data'] = $this->config->get ( 'ne_marketing_list' );
		
		$this->load->model ( 'ne/template' );
		
		$data ['templates'] = $this->model_ne_template->getList ();
		
		if (isset ( $this->error ['warning'] )) {
			$data ['error_warning'] = $this->error ['warning'];
		} else {
			$data ['error_warning'] = $this->config->get ( 'ne_warning' );
		}
		
		if (isset ( $this->error ['subject'] )) {
			$data ['error_subject'] = $this->error ['subject'];
		} else {
			$data ['error_subject'] = '';
		}
		
		if (isset ( $this->error ['message'] )) {
			$data ['error_message'] = $this->error ['message'];
		} else {
			$data ['error_message'] = '';
		}
		
		if (isset ( $this->error ['name'] )) {
			$data ['error_name'] = $this->error ['name'];
		} else {
			$data ['error_name'] = '';
		}
		
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		
		$this->response->setOutput ( $this->load->view ( 'ne/schedule_detail.tpl', $data ) );
	}
	public function delete() {
		$this->load->language ( 'ne/schedule' );
		$this->load->model ( 'ne/schedule' );
		
		$url = '';
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$url .= '&filter_name=' . $this->request->get ['filter_name'];
		}
		
		if (isset ( $this->request->get ['filter_recurrent'] )) {
			$url .= '&filter_recurrent=' . $this->request->get ['filter_recurrent'];
		}
		
		if (isset ( $this->request->get ['filter_to'] )) {
			$url .= '&filter_to=' . $this->request->get ['filter_to'];
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
			foreach ( $this->request->post ['selected'] as $schedule_id ) {
				$this->model_ne_schedule->delete ( $schedule_id );
			}
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
		}
		
		$this->response->redirect ( $this->url->link ( 'ne/schedule', 'token=' . $this->session->data ['token'] . $url, 'SSL' ) );
	}
	public function template() {
		if ($this->request->server ['REQUEST_METHOD'] == 'POST') {
			$post = http_build_query ( $this->request->post, '', '&' );
			
			if (isset ( $this->request->server ['HTTPS'] ) && (($this->request->server ['HTTPS'] == 'on') || ($this->request->server ['HTTPS'] == '1'))) {
				$store_url = (defined ( 'HTTPS_CATALOG' ) ? HTTPS_CATALOG : HTTP_CATALOG);
			} else {
				$store_url = HTTP_CATALOG;
			}
			
			if (isset ( $this->request->post ['store_id'] )) {
				$this->load->model ( 'setting/store' );
				$store = $this->model_setting_store->getStore ( $this->request->post ['store_id'] );
				if ($store) {
					$url = rtrim ( $store ['url'], '/' ) . '/index.php?route=ne/template/json_raw';
				} else {
					$url = $store_url . 'index.php?route=ne/template/json_raw';
				}
			} else {
				$url = $store_url . 'index.php?route=ne/template/json_raw';
			}
			
			$result = $this->do_request ( array (
					'url' => $url,
					'header' => array (
							'Content-type: application/x-www-form-urlencoded',
							"Content-Length: " . strlen ( $post ),
							"X-Requested-With: XMLHttpRequest" 
					),
					'method' => 'POST',
					'content' => $post 
			) );
			
			$response = $result ['response'];
			
			$this->response->addHeader ( 'Content-type: application/json' );
			$this->response->setOutput ( $response );
		} else {
			$this->response->redirect ( $this->url->link ( 'ne/schedule', 'token=' . $this->session->data ['token'], 'SSL' ) );
		}
	}
	private function validate() {
		if (! $this->user->hasPermission ( 'modify', 'ne/schedule' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	private function validateForm() {
		if (! $this->request->post ['subject']) {
			$this->error ['subject'] = $this->language->get ( 'error_subject' );
		}
		
		if (! $this->request->post ['message']) {
			$this->error ['message'] = $this->language->get ( 'error_message' );
		}
		
		if (! $this->request->post ['name']) {
			$this->error ['name'] = $this->language->get ( 'error_name' );
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	private function do_request($options) {
		$options = $options + array (
				'method' => 'GET',
				'content' => false,
				'header' => false,
				'async' => false 
		);
		
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $options ['url'] );
		curl_setopt ( $ch, CURLOPT_HEADER, 1 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 60 );
		curl_setopt ( $ch, CURLOPT_USERAGENT, 'Newsletter Enhancements for Opencart' );
		
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		
		if ($options ['header']) {
			curl_setopt ( $ch, CURLOPT_HTTPHEADER, $options ['header'] );
		}
		
		if ($options ['async']) {
			curl_setopt ( $ch, CURLOPT_TIMEOUT, 1 );
		} else {
			curl_setopt ( $ch, CURLOPT_TIMEOUT, 0 );
		}
		
		switch ($options ['method']) {
			case 'HEAD' :
				curl_setopt ( $ch, CURLOPT_NOBODY, 1 );
				break;
			case 'POST' :
				curl_setopt ( $ch, CURLOPT_POST, 1 );
				curl_setopt ( $ch, CURLOPT_POSTFIELDS, $options ['content'] );
				break;
			case 'PUT' :
				curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'PUT' );
				curl_setopt ( $ch, CURLOPT_POSTFIELDS, $options ['content'] );
				break;
			default :
				curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, $options ['method'] );
				if ($options ['content'])
					curl_setopt ( $ch, CURLOPT_POSTFIELDS, $options ['content'] );
				break;
		}
		
		$result = curl_exec ( $ch );
		$info = curl_getinfo ( $ch );
		$status = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
		
		curl_close ( $ch );
		
		return array (
				'header' => substr ( $result, 0, $info ['header_size'] ),
				'response' => substr ( $result, $info ['header_size'] ),
				'status' => $status,
				'info' => $info 
		);
	}
}