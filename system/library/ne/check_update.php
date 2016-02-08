<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------

class ControllerNeCheckUpdate extends Controller
{
    private $error = array();
    private $current = '3.7.2';

    public function index()
    {
        $this->load->language('ne/check_update');

        $this->document->setTitle($this->language->get('heading_title'));

        $server_version = @file_get_contents("http://www.codersroom.com/updates/ne.version");
        if (!ctype_digit(trim(str_replace('.', '', $server_version)))) {
            $server_version = $this->current;
        }

        $data['refresh'] = $this->url->link('ne/check_update', 'token=' . $this->session->data['token'], 'SSL');
        $data['content'] = (($server_version == $this->current) ? sprintf($this->language->get('text_update_latest'), $server_version) : sprintf($this->language->get('text_update_available'), $server_version));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_check_updates'] = $this->language->get('text_check_updates');

        $data['button_check'] = $this->language->get('button_check');
        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = $this->config->get('ne_warning');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('ne/check_update.tpl', $data));
    }

}