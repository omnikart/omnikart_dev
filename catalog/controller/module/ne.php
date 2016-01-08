<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------

class ControllerModuleNe extends Controller {

    private $_name = 'ne';
    private $template;

    public function index($setting) {
        static $module = 0;

        $this->load->model('module/' . $this->_name);

        if ($setting && $setting['status'] && ($setting['show_for'] || !$setting['show_for'] && !$this->customer->isLogged())) {
            $data['heading'] = $setting['heading'][$this->config->get('config_language_id')];
            $data['text'] = html_entity_decode($setting['text'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');

            $this->load->language('module/' . $this->_name);

            $data['config_language_id'] = $this->config->get('config_language_id');

            $data['text_subscribe'] = $this->language->get('text_subscribe');
            $data['text_close'] = $this->language->get('text_close');
            $data['text_timer'] = $this->language->get('text_timer');

            $data['entry_name'] = $this->language->get('entry_name');
            $data['entry_firstname'] = $this->language->get('entry_firstname');
            $data['entry_lastname'] = $this->language->get('entry_lastname');
            $data['entry_email'] = $this->language->get('entry_email');
            $data['entry_list'] = $this->language->get('entry_list');

            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                $data['subscribe'] = $this->url->link('module/ne/subscribe', 'box=' . $setting['subscribe_box_id'], 'SSL');
            } else {
                $data['subscribe'] = $this->url->link('module/ne/subscribe', 'box=' . $setting['subscribe_box_id']);
            }

            $data['subscribe'] = html_entity_decode($data['subscribe'], ENT_QUOTES, 'UTF-8');

            if ($this->config->get('ne_hide_marketing')) {
                $data['marketing_list'] = false;
            } else {
                $marketing_list = $this->config->get('ne_marketing_list');
                $data['marketing_list'] = isset($marketing_list[$this->config->get('config_store_id')]) ? $marketing_list[$this->config->get('config_store_id')] : array();
            }

            $data['module'] = $module++;

            $data['fields'] = $setting['fields'];
            $data['list_type'] = $setting['list_type'];
            $data['modal_timeout'] = $setting['modal_timeout'];
            $data['repeat_time'] = $setting['repeat_time'];

            if ($setting['type'] == '1') {
                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $this->_name . '/subscribe_box.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/' . $this->_name . '/subscribe_box.tpl';
                } else {
                    $this->template = 'default/template/' . $this->_name . '/subscribe_box.tpl';
                }
            } else {
                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/ne/bootstrap.css')) {
                    $this->document->addStyle(basename(DIR_APPLICATION) . '/view/theme/' . $this->config->get('config_template') . '/stylesheet/ne/bootstrap.css');
                } else {
                    $this->document->addStyle(basename(DIR_APPLICATION) . '/view/theme/default/stylesheet/ne/bootstrap.css');
                }

                $this->document->addScript(basename(DIR_APPLICATION) . '/view/javascript/ne/bootstrap.min.js');
                $this->document->addScript(basename(DIR_APPLICATION) . '/view/javascript/ne/jquery.cookie.js');

                if ($setting['modal_timeout']) {
                    $this->document->addScript(basename(DIR_APPLICATION) . '/view/javascript/ne/jquery.countdown.js');
                }

                if ($setting['type'] == '2') {
                    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $this->_name . '/subscribe_modal.tpl')) {
                        $this->template = $this->config->get('config_template') . '/template/' . $this->_name . '/subscribe_modal.tpl';
                    } else {
                        $this->template = 'default/template/' . $this->_name . '/subscribe_modal.tpl';
                    }
                } else {
                    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $this->_name . '/subscribe_button.tpl')) {
                        $this->template = $this->config->get('config_template') . '/template/' . $this->_name . '/subscribe_button.tpl';
                    } else {
                        $this->template = 'default/template/' . $this->_name . '/subscribe_button.tpl';
                    }
                }
            }

            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                $module_css = $this->url->link('module/ne/css', 'box=' . $setting['subscribe_box_id'], 'SSL');
            } else {
                $module_css = $this->url->link('module/ne/css', 'box=' . $setting['subscribe_box_id']);
            }

            $module_css = html_entity_decode($module_css, ENT_QUOTES, 'UTF-8');

            $this->document->addStyle($module_css);

            return $this->load->view($this->template, $data);
        }

        return false;
    }

    public function subscribe() {
        $subscribe_box_id = empty($this->request->get['box']) ? 0 : $this->request->get['box'];

        $this->load->model('module/' . $this->_name);

        $subscribe_box_info = $this->model_module_ne->getSubscribeBox($subscribe_box_id);

        if ($subscribe_box_info && $subscribe_box_info['status'] && ($subscribe_box_info['show_for'] || !$subscribe_box_info['show_for'] && !$this->customer->isLogged())) {
            $this->load->language('module/' . $this->_name);
            $out = array('message' => $this->language->get('text_subscribe_no_list'), 'type' => 'warning');

            if ($this->config->get('ne_hide_marketing')) {
                $marketing_list = array();
            } else {
                $marketing_list = $this->config->get('ne_marketing_list');
            }

            if ((isset($this->request->post['list']) && is_array($this->request->post['list']) && count($this->request->post['list'])) || empty($marketing_list)) {
                if ($subscribe_box_info['fields'] > 1 && empty($this->request->post['name']) || $subscribe_box_info['fields'] == 3 && empty($this->request->post['lastname'])) {
                    $out = array('message' => $this->language->get('text_subscribe_not_valid_data'), 'type' => 'warning');
                } else {
                    $this->request->post['email'] = empty($this->request->post['email']) ? '' : $this->request->post['email'];
                    $this->request->post['email'] = htmlspecialchars($this->request->post['email']);

                    if ($this->request->post['email'] && filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
                        $result = $this->model_module_ne->subscribe($this->request->post, $this->config->get($this->_name . '_key'), isset($this->request->post['list']) ? $this->request->post['list'] : array());
                        if ($result) {
                            if ($this->config->get('ne_subscribe_confirmation')) {
                                $out = array('message' => $this->language->get('text_subscribe_confirmation'), 'type' => 'success');
                            } else {
                                $out = array('message' => $this->language->get('text_subscribe_success'), 'type' => 'success');
                            }
                        } else {
                            $out = array('message' => $this->language->get('text_subscribe_exists'), 'type' => 'success');
                        }
                    } else {
                        $out = array('message' => $this->language->get('text_subscribe_not_valid_email'), 'type' => 'warning');
                    }
                }
            }

            $this->response->addHeader('Content-type: application/json');
            $this->response->setOutput($out ? json_encode($out) : '');
        }
    }

    public function css() {
        $out = '.ne-bootstrap {';
        $out .= '   position:absolute;';
        $out .= '   left:0;';
        $out .= '   right:0;';
        $out .= '   top:0;';
        $out .= '   z-index:99999;';
        $out .= '}' . PHP_EOL;

        $out .= '.ne-bootstrap .modal-backdrop {';
        $out .= '   z-index:0 !important;';
        $out .= '}' . PHP_EOL;

        $out .= '.ne_subscribe label {';
        $out .= '   display:block;';
        $out .= '}' . PHP_EOL;

        $out .= '.ne_close {';
        $out .= '   font-size:24px;';
        $out .= '   top:15px;';
        $out .= '   right:15px;';
        $out .= '   position:absolute;';
        $out .= '   text-decoration:none;';
        $out .= '}' . PHP_EOL;

        $out .= '.ne_close:hover {';
        $out .= '   text-decoration:none;';
        $out .= '}' . PHP_EOL;

        $out .= '.ne_timer {';
        $out .= '   float:left;';
        $out .= '}' . PHP_EOL;

        $out .= '.ne_timer > pre {';
        $out .= '   display:inline-block;';
        $out .= '   margin:0;';
        $out .= '}' . PHP_EOL;

        $out .= '.ne_subscribe_list {';
        $out .= '   margin:4px 0 0 0 !important;';
        $out .= '}' . PHP_EOL;

        $out .= '.ne_modal .modal-body .form-group:last-child {';
        $out .= '   margin-bottom:0;';
        $out .= '}' . PHP_EOL;

        $out .= '.ne_modal .modal-footer {';
        $out .= '   text-align:center;';
        $out .= '}' . PHP_EOL;

        $out .= '.ne_modal .modal-footer .ne_submit {';
        $out .= '   width:90%;';
        $out .= '}' . PHP_EOL;

        $out .= 'div[aria-hidden=true] {';
        $out .= '   display:none;';
        $out .= '}' . PHP_EOL;

        $out .= 'div[aria-hidden=false] {';
        $out .= '   display:block;';
        $out .= '}' . PHP_EOL;

        $subscribe_box_id = empty($this->request->get['box']) ? 0 : $this->request->get['box'];

        $this->load->model('module/' . $this->_name);

        $subscribe_box_info = $this->model_module_ne->getSubscribeBox($subscribe_box_id);

        if ($subscribe_box_info) {
            if (!empty($subscribe_box_info['modal_bg_color'])) {
                $out .= '.ne_modal .modal-content {';
                $out .= '   background-color:' . $subscribe_box_info['modal_bg_color'] . ';';
                $out .= '}' . PHP_EOL;
            }

            if (!empty($subscribe_box_info['modal_line_color'])) {
                $out .= '.ne_modal .modal-header {';
                $out .= '   border-bottom: 1px solid ' . $subscribe_box_info['modal_line_color'] . ';';
                $out .= '}' . PHP_EOL;

                $out .= '.ne_modal .modal-footer {';
                $out .= '   border-top: 1px solid ' . $subscribe_box_info['modal_line_color'] . ';';
                $out .= '}' . PHP_EOL;
            }

            if (!empty($subscribe_box_info['modal_heading_color'])) {
                $out .= '.ne_modal .modal-title, .ne_close, .ne_close:hover {';
                $out .= '   color:' . $subscribe_box_info['modal_heading_color'] . ';';
                $out .= '}' . PHP_EOL;
            }
        }

        $this->response->addHeader('Content-type: text/css');
        $this->response->setOutput($out);
    }

}
