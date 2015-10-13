<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------

class ControllerNeLanguage extends Controller {

    public function index() {
        if (!empty($this->request->get['uid']) && !empty($this->request->get['key']) && !empty($this->request->get['lang'])) {
            $uid = base64_decode(urldecode($this->request->get['uid']));
            $test = explode('|', $uid);

            if (count($test) == 2) {
                $data = array(
                    'uid' => $test[1],
                    'email' => $test[0],
                    'language_code' => $this->request->get['lang']
                );

                $key = md5($this->config->get('ne_key') . $data['email']);

                if ($key == $this->request->get['key']) {
                    $this->load->model('ne/language');
                    if ($this->model_ne_language->set($data)) {
                        $this->load->language('ne/language');
                        $this->session->data['success'] = $this->language->get('text_language_set_success');
                    }
                }
            }
        }

        $this->response->redirect($this->url->link('account/account'));
    }

}