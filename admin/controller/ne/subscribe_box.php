<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------

class ControllerNeSubscribeBox extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('ne/subscribe_box');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('ne/subscribe_box');

        $data['edit'] = $this->url->link('ne/subscribe_box/update', 'token=' . $this->session->data['token'] . '&id=', 'SSL');
        $data['insert'] = $this->url->link('ne/subscribe_box/insert', 'token=' . $this->session->data['token'], 'SSL');
        $data['delete'] = $this->url->link('ne/subscribe_box/delete', 'token=' . $this->session->data['token'], 'SSL');
        $data['copy'] = $this->url->link('ne/subscribe_box/copy', 'token=' . $this->session->data['token'], 'SSL');

        $data['subscribe_boxes'] = $this->model_ne_subscribe_box->getList();

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_subscribe_boxes'] = $this->language->get('text_subscribe_boxes');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_last_change'] = $this->language->get('column_last_change');
        $data['column_actions'] = $this->language->get('column_actions');
        $data['column_status'] = $this->language->get('column_status');

        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_insert'] = $this->language->get('button_insert');
        $data['button_copy'] = $this->language->get('button_copy');
        $data['button_edit'] = $this->language->get('button_edit');

        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = $this->config->get('ne_warning');
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        if (isset($this->session->data['warning'])) {
            $data['error_warning'] = $this->session->data['warning'];
            unset($this->session->data['warning']);
        }

        $this->load->language('extension/module');

        $data['text_layout'] = sprintf($this->language->get('text_layout'), $this->url->link('design/layout', 'token=' . $this->session->data['token'], 'SSL'));

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('ne/subscribe_box_list.tpl', $data));
    }

    public function update() {
        $this->load->language('ne/subscribe_box');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('ne/subscribe_box');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_ne_subscribe_box->save(array_merge($this->request->post, array('id' => $this->request->get['id'])));
            $this->model_ne_subscribe_box->refreshModules($this->language->get('text_subscribe_box') . ' - ');
            $this->session->data['success'] = $this->language->get('text_success_save');

            $this->response->redirect($this->url->link('ne/subscribe_box', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->form();
    }

    public function insert() {
        $this->load->language('ne/subscribe_box');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('ne/subscribe_box');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_ne_subscribe_box->save($this->request->post);
            $this->model_ne_subscribe_box->refreshModules($this->language->get('text_subscribe_box') . ' - ');
            $this->session->data['success'] = $this->language->get('text_success_save');

            $this->response->redirect($this->url->link('ne/subscribe_box', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->form();
    }

    private function form() {
        if (isset($this->request->get['id'])) {
            $subscribe_box_id = $this->request->get['id'];
            $subscribe_box_info = $this->model_ne_subscribe_box->get($subscribe_box_id);
        } else {
            $subscribe_box_info = array();
        }

        $this->document->addScript('view/javascript/ne/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js');
        $this->document->addStyle('view/javascript/ne/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_show_for'] = $this->language->get('entry_show_for');
        $data['entry_fields'] = $this->language->get('entry_fields');
        $data['entry_heading'] = $this->language->get('entry_heading');
        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_type'] = $this->language->get('entry_type');
        $data['entry_modal_timeout'] = $this->language->get('entry_modal_timeout');
        $data['entry_modal_repeat_time'] = $this->language->get('entry_modal_repeat_time');
        $data['entry_modal_bg_color'] = $this->language->get('entry_modal_bg_color');
        $data['entry_modal_heading_color'] = $this->language->get('entry_modal_heading_color');
        $data['entry_modal_line_color'] = $this->language->get('entry_modal_line_color');
        $data['entry_list_type'] = $this->language->get('entry_list_type');

        $data['help_modal_timeout'] = $this->language->get('help_modal_timeout');
        $data['help_modal_repeat_time'] = $this->language->get('help_modal_repeat_time');

        $data['text_settings'] = $this->language->get('text_settings');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all'] = $this->language->get('text_all');
        $data['text_guests'] = $this->language->get('text_guests');
        $data['text_only_email'] = $this->language->get('text_only_email');
        $data['text_email_name'] = $this->language->get('text_email_name');
        $data['text_email_full'] = $this->language->get('text_email_full');
        $data['text_content_box'] = $this->language->get('text_content_box');
        $data['text_modal_popup'] = $this->language->get('text_modal_popup');
        $data['text_content_box_to_modal'] = $this->language->get('text_content_box_to_modal');
        $data['text_modal_popup_settings'] = $this->language->get('text_modal_popup_settings');
        $data['text_modal_popup_style'] = $this->language->get('text_modal_popup_style');
        $data['text_checkboxes'] = $this->language->get('text_checkboxes');
        $data['text_radio_buttons'] = $this->language->get('text_radio_buttons');
        $data['text_subscribe_box'] = $this->language->get('text_subscribe_box');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = $this->config->get('ne_warning');
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($subscribe_box_info)) {
            $data['name'] = $subscribe_box_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($subscribe_box_info)) {
            $data['status'] = $subscribe_box_info['status'];
        } else {
            $data['status'] = '';
        }

        if (isset($this->request->post['show_for'])) {
            $data['show_for'] = $this->request->post['show_for'];
        } elseif (!empty($subscribe_box_info)) {
            $data['show_for'] = $subscribe_box_info['show_for'];
        } else {
            $data['show_for'] = '';
        }

        if (isset($this->request->post['fields'])) {
            $data['fields'] = $this->request->post['fields'];
        } elseif (!empty($subscribe_box_info)) {
            $data['fields'] = $subscribe_box_info['fields'];
        } else {
            $data['fields'] = '';
        }

        if (isset($this->request->post['type'])) {
            $data['type'] = $this->request->post['type'];
        } elseif (!empty($subscribe_box_info)) {
            $data['type'] = $subscribe_box_info['type'];
        } else {
            $data['type'] = '';
        }

        if (isset($this->request->post['list_type'])) {
            $data['list_type'] = $this->request->post['list_type'];
        } elseif (!empty($subscribe_box_info)) {
            $data['list_type'] = $subscribe_box_info['list_type'];
        } else {
            $data['list_type'] = '';
        }

        if (isset($this->request->post['modal_timeout'])) {
            $data['modal_timeout'] = $this->request->post['modal_timeout'];
        } elseif (!empty($subscribe_box_info)) {
            $data['modal_timeout'] = $subscribe_box_info['modal_timeout'];
        } else {
            $data['modal_timeout'] = '0';
        }

        if (isset($this->request->post['repeat_time'])) {
            $data['repeat_time'] = $this->request->post['repeat_time'];
        } elseif (!empty($subscribe_box_info)) {
            $data['repeat_time'] = $subscribe_box_info['repeat_time'];
        } else {
            $data['repeat_time'] = '1';
        }

        if (isset($this->request->post['modal_bg_color'])) {
            $data['modal_bg_color'] = $this->request->post['modal_bg_color'];
        } elseif (!empty($subscribe_box_info)) {
            $data['modal_bg_color'] = $subscribe_box_info['modal_bg_color'];
        } else {
            $data['modal_bg_color'] = '#ffffff';
        }

        if (isset($this->request->post['modal_line_color'])) {
            $data['modal_line_color'] = $this->request->post['modal_line_color'];
        } elseif (!empty($subscribe_box_info)) {
            $data['modal_line_color'] = $subscribe_box_info['modal_line_color'];
        } else {
            $data['modal_line_color'] = '#e5e5e5';
        }

        if (isset($this->request->post['modal_heading_color'])) {
            $data['modal_heading_color'] = $this->request->post['modal_heading_color'];
        } elseif (!empty($subscribe_box_info)) {
            $data['modal_heading_color'] = $subscribe_box_info['modal_heading_color'];
        } else {
            $data['modal_heading_color'] = '#222222';
        }

        if (isset($this->request->post['heading'])) {
            $data['heading'] = $this->request->post['heading'];
        } elseif (!empty($subscribe_box_info)) {
            $data['heading'] = $subscribe_box_info['heading'];
        } else {
            $data['heading'] = array();
        }

        if (isset($this->request->post['text'])) {
            $data['text'] = $this->request->post['text'];
        } elseif (!empty($subscribe_box_info)) {
            $data['text'] = $subscribe_box_info['text'];
        } else {
            $data['text'] = array();
        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (!isset($subscribe_box_id)) {
            $data['action'] = $this->url->link('ne/subscribe_box/insert', 'token=' . $this->session->data['token'], 'SSL');
        } else {
            $data['action'] = $this->url->link('ne/subscribe_box/update', 'token=' . $this->session->data['token'] . '&id=' . $subscribe_box_id, 'SSL');
        }

        $data['cancel'] = $this->url->link('ne/subscribe_box', 'token=' . $this->session->data['token'], 'SSL');

        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = $this->config->get('ne_warning');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('ne/subscribe_box_form.tpl', $data));
    }

    public function delete() {
        $this->load->language('ne/subscribe_box');
        $this->load->model('ne/subscribe_box');

        if (isset($this->request->post['selected']) && $this->validate()) {
            foreach ($this->request->post['selected'] as $subscribe_box_id) {
                if (!$this->model_ne_subscribe_box->delete($subscribe_box_id)) {
                    $this->error['warning'] = $this->language->get('error_delete');
                }
            }
            if (isset($this->error['warning'])) {
                $this->session->data['warning'] = $this->error['warning'];
            } else {
                $this->session->data['success'] = $this->language->get('text_success');
            }
            $this->model_ne_subscribe_box->refreshModules($this->language->get('text_subscribe_box') . ' - ');
        }

        $this->response->redirect($this->url->link('ne/subscribe_box', 'token=' . $this->session->data['token'], 'SSL'));
    }

    public function copy() {
        if (isset($this->request->post['selected']) && $this->validate()) {
            $this->load->language('ne/subscribe_box');
            $this->load->model('ne/subscribe_box');

            foreach ($this->request->post['selected'] as $subscribe_box_id) {
                if (!$this->model_ne_subscribe_box->copy($subscribe_box_id)) {
                    $this->error['warning'] = $this->language->get('error_copy');
                }
            }
            if (isset($this->error['warning'])) {
                $this->session->data['warning'] = $this->error['warning'];
            } else {
                $this->session->data['success'] = $this->language->get('text_success_copy');
            }
            $this->model_ne_subscribe_box->refreshModules($this->language->get('text_subscribe_box') . ' - ');
        }

        $this->response->redirect($this->url->link('ne/subscribe_box', 'token=' . $this->session->data['token'], 'SSL'));
    }

    private function validateSave() {
        if (!$this->user->hasPermission('modify', 'ne/subscribe_box')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    private function validateForm() {
        if (!$this->user->hasPermission('modify', 'ne/subscribe_box')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (empty($this->request->post['name'])) {
            $this->error['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'ne/subscribe_box')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

}