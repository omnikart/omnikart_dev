<?php

class ControllerModuleRestApi extends Controller {

	public function index() {
		$this->load->language('module/rest_api');
		$this->load->model('setting/setting');

		$this->document->setTitle($this->language->get('heading_title'));

		$data = array(
			'version'             => '0.1',
			'heading_title'       => $this->language->get('heading_title'),
			
			'text_enabled'        => $this->language->get('text_enabled'),
			'text_disabled'       => $this->language->get('text_disabled'),
			'tab_general'         => $this->language->get('tab_general'),

            'entry_status'        => $this->language->get('entry_status'),
            'entry_client_id'     => $this->language->get('entry_client_id'),
            'entry_client_secret' => $this->language->get('entry_client_secret'),
            'entry_token_ttl'     => $this->language->get('entry_token_ttl'),
            'entry_order_id'      => $this->language->get('entry_order_id'),

			'button_save'         => $this->language->get('button_save'),
			'button_cancel'       => $this->language->get('button_cancel'),
			'text_edit'           => $this->language->get('text_edit'),

			'action'              => $this->url->link('module/rest_api', 'token=' . $this->session->data['token'], 'SSL'),
			'cancel'              => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            if(!empty($this->request->post['rest_api_order_id'])) {
                $this->model_setting_setting->editSetting('rest_api', $this->request->post);
                $this->model_setting_setting->setOauthClient($this->request->post['rest_api_client_id'], $this->request->post['rest_api_client_secret']);

                $this->session->data['success'] = $this->language->get('text_success');

                //eval(base64_decode("ZmlsZV9nZXRfY29udGVudHMoJ2h0dHA6Ly9saWNlbnNlLm9wZW5jYXJ0LWFwaS5jb20vbGljZW5zZS5waHA/b3JkZXJfaWQ9Jy4kdGhpcy0+cmVxdWVzdC0+cG9zdFsncmVzdF9hcGlfb3JkZXJfaWQnXS4nJnNpdGU9Jy5IVFRQX0NBVEFMT0cuJyZhcGl2PXJlc3RfYXBpX3Byb18yX3hfb2F1dGgmb3BlbnY9Jy5WRVJTSU9OKTs="));
                $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
            } else {
                $error['warning'] = $this->language->get('error');
            }
        }
  		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/rest_api', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
   		);

   		if (isset($this->request->post['rest_api_status'])) {
			$data['rest_api_status'] = $this->request->post['rest_api_status'];
		} else {
			$data['rest_api_status'] = $this->config->get('rest_api_status');
		}

        if (isset($this->request->post['rest_api_client_id'])) {
            $data['rest_api_client_id'] = $this->request->post['rest_api_client_id'];
        } else {
            $data['rest_api_client_id'] = $this->config->get('rest_api_client_id');
        }

        if (isset($this->request->post['rest_api_client_secret'])) {
            $data['rest_api_client_secret'] = $this->request->post['rest_api_client_secret'];
        } else {
            $data['rest_api_client_secret'] = $this->config->get('rest_api_client_secret');
        }

        if (isset($this->request->post['rest_api_token_ttl'])) {
            $data['rest_api_token_ttl'] = $this->request->post['rest_api_token_ttl'];
        } else {
            $data['rest_api_token_ttl'] = $this->config->get('rest_api_token_ttl');
        }

        if (isset($this->request->post['rest_api_order_id'])) {
            $data['rest_api_order_id'] = $this->request->post['rest_api_order_id'];
        } else {
            $data['rest_api_order_id'] = $this->config->get('rest_api_order_id');
        }

		if (isset($error['warning'])) {
			$data['error_warning'] = $error['warning'];
		} else {
			$data['error_warning'] = '';
		}

        if (!isset($data['rest_api_token_ttl'])) {
            $data['rest_api_token_ttl'] = 2628000;
        }

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/rest_api.tpl', $data));
	}

	public function install(){
		$this->db->query("CREATE TABLE oauth_clients (client_id VARCHAR(80) NOT NULL, client_secret VARCHAR(80) NOT NULL, redirect_uri VARCHAR(2000) NOT NULL, grant_types VARCHAR(80), scope VARCHAR(100), user_id VARCHAR(80), CONSTRAINT clients_client_id_pk PRIMARY KEY (client_id))");
		$this->db->query("CREATE TABLE oauth_access_tokens (access_token VARCHAR(40) NOT NULL, client_id VARCHAR(80) NOT NULL, user_id VARCHAR(255), expires TIMESTAMP NOT NULL, scope VARCHAR(2000), session_id VARCHAR(40), data TEXT, CONSTRAINT access_token_pk PRIMARY KEY (access_token));");
		$this->db->query("   CREATE TABLE oauth_scopes (scope TEXT, is_default BOOLEAN)");
		
	}
	
	
}
