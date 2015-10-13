<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------

class ControllerNeNewsletter extends Controller {
    private $error = array();

    public function index() {

        if (isset($this->request->get['id']) && $this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->load->model('ne/draft');
            $this->request->post = $this->model_ne_draft->detail($this->request->get['id']);

            if (!$this->request->post) {
                $this->response->redirect($this->url->link('ne/newsletter', 'token=' . $this->session->data['token'], 'SSL'));
            }
        } elseif ($this->request->files && !empty($this->request->post['attachments_count'])) {
            for ($i = 0; $i < count($this->request->files); $i++) {
                if (is_uploaded_file($this->request->files['attachment_'.$i]['tmp_name'])) {
                    $filename = $this->request->files['attachment_'.$i]['name'];
                    $path = dirname(DIR_DOWNLOAD) . DIRECTORY_SEPARATOR . 'attachments' .
                        DIRECTORY_SEPARATOR . $this->request->post['attachments_id'];
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    if (is_dir($path)) {
                        move_uploaded_file($this->request->files['attachment_'.$i]['tmp_name'], $path .
                            DIRECTORY_SEPARATOR . $filename);
                    }
                    if (file_exists($path . DIRECTORY_SEPARATOR . $filename)) {
                        $attachments[] = array(
                            'filename' => $filename,
                            'path'     => $path . DIRECTORY_SEPARATOR . $filename
                        );
                    }
                }
            }
        }

        $this->load->language('sale/contact');
        $this->load->language('ne/newsletter');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['entry_template'] = $this->language->get('entry_template');
        $data['entry_yes'] = $this->language->get('entry_yes');
        $data['entry_no'] = $this->language->get('entry_no');
        $data['entry_defined'] = $this->language->get('entry_defined');
        $data['entry_latest'] = $this->language->get('entry_latest');
        $data['entry_popular'] = $this->language->get('entry_popular');
        $data['entry_special'] = $this->language->get('entry_special');
        $data['entry_product'] = $this->language->get('entry_product');
        $data['entry_attachments'] = $this->language->get('entry_attachments');
        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_language'] = $this->language->get('entry_language');
        $data['entry_to'] = $this->language->get('entry_to');
        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_customer'] = $this->language->get('entry_customer');
        $data['entry_affiliate'] = $this->language->get('entry_affiliate');
        $data['entry_product'] = $this->language->get('entry_product');
        $data['entry_subject'] = $this->language->get('entry_subject');
        $data['entry_message'] = $this->language->get('entry_message');
        $data['entry_text_message'] = $this->language->get('entry_text_message');
        $data['entry_text_message_products'] = $this->language->get('entry_text_message_products');
        $data['entry_marketing'] = $this->language->get('entry_marketing');
        $data['entry_defined_categories'] = $this->language->get('entry_defined_categories');
        $data['entry_section_name'] = $this->language->get('entry_section_name');
        $data['entry_currency'] = $this->language->get('entry_currency');

        $data['button_add_file'] = $this->language->get('button_add_file');
        $data['button_add_defined_section'] = $this->language->get('button_add_defined_section');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_send'] = $this->language->get('button_send');
        $data['button_reset'] = $this->language->get('button_reset');
        $data['button_back'] = $this->language->get('button_back');
        $data['button_update'] = $this->language->get('button_update');
        $data['button_preview'] = $this->language->get('button_preview');
        $data['button_check'] = $this->language->get('button_check');

        $data['text_create_and_send'] = $this->language->get('text_create_and_send');
        $data['text_marketing'] = $this->language->get('text_marketing');
        $data['text_marketing_all'] = $this->language->get('text_marketing_all');
        $data['text_subscriber_all'] = $this->language->get('text_subscriber_all');
        $data['text_all'] = $this->language->get('text_all');
        $data['text_clear_warning'] = $this->language->get('text_clear_warning');
        $data['text_message_info'] = $this->language->get('text_message_info');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_customer_all'] = $this->language->get('text_customer_all');
        $data['text_customer'] = $this->language->get('text_customer');
        $data['text_customer_group'] = $this->language->get('text_customer_group');
        $data['text_affiliate_all'] = $this->language->get('text_affiliate_all');
        $data['text_affiliate'] = $this->language->get('text_affiliate');
        $data['text_product'] = $this->language->get('text_product');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_only_subscribers'] = $this->language->get('text_only_subscribers');
        $data['text_only_selected_language'] = $this->language->get('text_only_selected_language');
        $data['text_only_selected_language_help'] = $this->language->get('text_only_selected_language_help');
        $data['text_rewards'] = $this->language->get('text_rewards');
        $data['text_rewards_all'] = $this->language->get('text_rewards_all');

        $data['entry_limit'] = $this->language->get('entry_limit');
        $data['entry_sort_by'] = $this->language->get('entry_sort_by');
        $data['entry_order'] = $this->language->get('entry_order');

        $data['text_date_added'] = $this->language->get('text_date_added');
        $data['text_sort_order'] = $this->language->get('text_sort_order');
        $data['text_ascending'] = $this->language->get('text_ascending');
        $data['text_descending'] = $this->language->get('text_descending');

        $data['text_skip_out_of_stock'] = $this->language->get('text_skip_out_of_stock');

        if (isset($this->request->post['marketing_list']) && is_array($this->request->post['marketing_list'])) {
            $data['marketing_list'] = $this->request->post['marketing_list'];
        } else {
            $data['marketing_list'] = array();
        }

        $this->load->model('catalog/product');

        $data['defined_products'] = array();

        if (isset($this->request->post['defined_product']) && is_array($this->request->post['defined_product'])) {
            foreach ($this->request->post['defined_product'] as $product_id) {
                $product_info = $this->model_catalog_product->getProduct($product_id);

                if ($product_info) {
                    $data['defined_products'][] = array(
                        'product_id' => $product_info['product_id'],
                        'name'       => $product_info['name']
                    );
                }
            }
            unset($product_info);
            unset($product_id);
        }

        $data['defined_products_more'] = array();

        if (isset($this->request->post['defined_product_more']) && is_array($this->request->post['defined_product_more'])) {
            foreach ($this->request->post['defined_product_more'] as $dpm) {
                if (!isset($dpm['products'])) {
                    $dpm['products'] = array();
                }
                if (!isset($dpm['text'])) {
                    $dpm['text'] = '';
                }
                $defined_products_more = array('text' => $dpm['text'], 'products' => array());
                foreach ($dpm['products'] as $product_id) {
                    $product_info = $this->model_catalog_product->getProduct($product_id);

                    if ($product_info) {
                        $defined_products_more['products'][] = array(
                            'product_id' => $product_info['product_id'],
                            'name'       => $product_info['name']
                        );
                    }
                }
                $data['defined_products_more'][] = $defined_products_more;
            }
            unset($defined_products_more);
            unset($product_info);
            unset($product_id);
        }

        $this->load->model('catalog/category');

        $data['categories'] = $this->model_catalog_category->getCategories(0);

        if (isset($this->request->get['id']) || isset($this->request->post['id'])) {
            $data['id'] = (isset($this->request->get['id']) ?$this->request->get['id'] : $this->request->post['id']);
        } else {
            $data['id'] = false;
        }

        if (!empty($this->request->post['attachments_id'])) {
            $data['attachments_id'] = $this->request->post['attachments_id'];
        } else {
            $data['attachments_id'] = time();
        }

        if (isset($this->request->post['defined'])) {
            $data['defined'] = $this->request->post['defined'];
        } else {
            $data['defined'] = false;
        }

        if (isset($this->request->post['defined_categories'])) {
            $data['defined_categories'] = $this->request->post['defined_categories'];
        } else {
            $data['defined_categories'] = false;
        }

        if (isset($this->request->post['defined_category'])) {
            $data['defined_category'] = $this->request->post['defined_category'];
        } else {
            $data['defined_category'] = array();
        }

        if (isset($this->request->post['skip_out_of_stock'])) {
            $data['skip_out_of_stock'] = $this->request->post['skip_out_of_stock'];
        } else {
            $data['skip_out_of_stock'] = array();
        }

        if (isset($this->request->post['defined_category_limit'])) {
            $data['defined_category_limit'] = $this->request->post['defined_category_limit'];
        } else {
            $data['defined_category_limit'] = '8';
        }

        if (isset($this->request->post['defined_category_sort'])) {
            $data['defined_category_sort'] = $this->request->post['defined_category_sort'];
        } else {
            $data['defined_category_sort'] = 'date_added';
        }

        if (isset($this->request->post['defined_category_order'])) {
            $data['defined_category_order'] = $this->request->post['defined_category_order'];
        } else {
            $data['defined_category_order'] = '1';
        }

        if (isset($this->request->post['special'])) {
            $data['special'] = $this->request->post['special'];
        } else {
            $data['special'] = false;
        }

        if (isset($this->request->post['latest'])) {
            $data['latest'] = $this->request->post['latest'];
        } else {
            $data['latest'] = false;
        }

        if (isset($this->request->post['popular'])) {
            $data['popular'] = $this->request->post['popular'];
        } else {
            $data['popular'] = false;
        }

        if (isset($this->request->post['attachments'])) {
            $data['attachments'] = $this->request->post['attachments'];
        } else {
            $data['attachments'] = false;
        }

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/customer_group');

        $this->load->model('ne/newsletter');

        $data['config_language_id'] = $this->config->get('config_language_id');

        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = $this->config->get('ne_warning');
        }

        if (isset($this->error['subject'])) {
            $data['error_subject'] = $this->error['subject'];
        } else {
            $data['error_subject'] = '';
        }

        if (isset($this->error['message'])) {
            $data['error_message'] = $this->error['message'];
        } else {
            $data['error_message'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['action'] = $this->url->link('ne/newsletter', 'token=' . $this->session->data['token'], 'SSL');
        if (isset($this->session->data['ne_back'])) {
            $data['back'] = $this->url->link('ne/draft', 'token=' . $this->session->data['token'] . $this->session->data['ne_back'], 'SSL');
            unset($this->session->data['ne_back']);
            $data['reset'] = false;
        } else {
            $data['reset'] = $this->url->link('ne/newsletter', 'token=' . $this->session->data['token'], 'SSL');
            $data['back'] = false;
        }
        $data['save'] = $this->url->link('ne/newsletter/save', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['template_id'])) {
            $data['template_id'] = $this->request->post['template_id'];
        } else {
            $data['template_id'] = '';
        }

        $this->load->model('ne/template');
        $data['templates'] = $this->model_ne_template->getList();

        if (isset($this->request->post['store_id'])) {
            $data['store_id'] = $this->request->post['store_id'];
        } else {
            $data['store_id'] = '';
        }

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        if (isset($this->request->post['language_id'])) {
            $data['language_id'] = $this->request->post['language_id'];
        } else {
            $data['language_id'] = '';
        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['currency'])) {
            $data['currency'] = $this->request->post['currency'];
        } else {
            $data['currency'] = '';
        }

        $this->load->model('localisation/currency');

        $data['currencies'] = $this->model_localisation_currency->getCurrencies();

        if (isset($this->request->post['to'])) {
            $data['to'] = $this->request->post['to'];
        } else {
            $data['to'] = '';
        }

        if (isset($this->request->post['customer_group_id'])) {
            $data['customer_group_id'] = $this->request->post['customer_group_id'];
        } else {
            $data['customer_group_id'] = '';
        }

        if (isset($this->request->post['customer_group_only_subscribers'])) {
            $data['customer_group_only_subscribers'] = $this->request->post['customer_group_only_subscribers'];
        } else {
            $data['customer_group_only_subscribers'] = '';
        }

        if (isset($this->request->post['only_selected_language'])) {
            $data['only_selected_language'] = $this->request->post['only_selected_language'];
        } else {
            $data['only_selected_language'] = 1;
        }

        $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups(0);

        $data['customers'] = array();

        if (isset($this->request->post['customer']) && is_array($this->request->post['customer'])) {
            foreach ($this->request->post['customer'] as $customer_id) {
                $customer_info = $this->model_ne_newsletter->getCustomer($customer_id);

                if ($customer_info) {
                    $data['customers'][] = array(
                        'customer_id' => $customer_info['customer_id'],
                        'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
                    );
                }
            }
        }

        $data['affiliates'] = array();

        if (isset($this->request->post['affiliate']) && is_array($this->request->post['affiliate'])) {
            foreach ($this->request->post['affiliate'] as $affiliate_id) {
                $affiliate_info = $this->model_ne_newsletter->getAffiliate($affiliate_id);

                if ($affiliate_info) {
                    $data['affiliates'][] = array(
                        'affiliate_id' => $affiliate_info['affiliate_id'],
                        'name'         => $affiliate_info['firstname'] . ' ' . $affiliate_info['lastname']
                    );
                }
            }
        }

        $this->load->model('catalog/product');

        $data['products'] = array();

        if (isset($this->request->post['product']) && is_array($this->request->post['product'])) {
            foreach ($this->request->post['product'] as $product_id) {
                $product_info = $this->model_catalog_product->getProduct($product_id);

                if ($product_info) {
                    $data['products'][] = array(
                        'product_id' => $product_info['product_id'],
                        'name'       => $product_info['name']
                    );
                }
            }
        }

        $data['list_data'] = $this->config->get('ne_marketing_list');

        if (isset($this->request->post['subject'])) {
            $data['subject'] = $this->request->post['subject'];
        } else {
            $data['subject'] = '';
        }

        if (isset($this->request->post['message'])) {
            $data['message'] = $this->request->post['message'];
        } else {
            $data['message'] = '';
        }

        if (isset($this->request->post['text_message'])) {
            $data['text_message'] = $this->request->post['text_message'];
        } else {
            $data['text_message'] = '';
        }

        if (isset($this->request->post['text_message_products'])) {
            $data['text_message_products'] = $this->request->post['text_message_products'];
        } else {
            $data['text_message_products'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('ne/newsletter.tpl', $data));
    }

    public function save() {
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->load->model('ne/draft');
            $this->load->language('ne/newsletter');
            $this->model_ne_draft->save($this->request->post);
            $this->session->data['success'] = $this->language->get('text_success_save');
        }
        $this->response->redirect($this->url->link('ne/draft', 'token=' . $this->session->data['token'], 'SSL'));
    }

    public function template() {
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $post = http_build_query($this->request->post, '', '&');

            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                $store_url = (defined('HTTPS_CATALOG') ? HTTPS_CATALOG : HTTP_CATALOG);
            } else {
                $store_url = HTTP_CATALOG;
            }

            if (isset($this->request->post['store_id'])) {
                $this->load->model('setting/store');
                $store = $this->model_setting_store->getStore($this->request->post['store_id']);
                if ($store) {
                    $url = rtrim($store['url'], '/') . '/index.php?route=ne/template/json';
                } else {
                    $url = $store_url . 'index.php?route=ne/template/json';
                }
            } else {
                $url = $store_url . 'index.php?route=ne/template/json';
            }

            $result = $this->do_request(array(
                'url' => $url,
                'header' => array('Content-type: application/x-www-form-urlencoded', "Content-Length: ". strlen($post), "X-Requested-With: XMLHttpRequest"),
                'method' => 'POST',
                'content' => $post
            ));

            $response = $result['response'];

            $this->response->addHeader('Content-type: application/json');
            $this->response->setOutput($response);
        } else {
            $this->response->redirect($this->url->link('ne/newsletter', 'token=' . $this->session->data['token'], 'SSL'));
        }
    }

    public function preview() {
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $post = http_build_query($this->request->post, '', '&');

            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                $store_url = (defined('HTTPS_CATALOG') ? HTTPS_CATALOG : HTTP_CATALOG);
            } else {
                $store_url = HTTP_CATALOG;
            }

            if (isset($this->request->post['store_id'])) {
                $this->load->model('setting/store');
                $store = $this->model_setting_store->getStore($this->request->post['store_id']);
                if ($store) {
                    $url = rtrim($store['url'], '/') . '/index.php?route=ne/template/json';
                } else {
                    $url = $store_url . 'index.php?route=ne/template/json';
                }
            } else {
                $url = $store_url . 'index.php?route=ne/template/json';
            }

            $result = $this->do_request(array(
                'url' => $url,
                'header' => array('Content-type: application/x-www-form-urlencoded', "Content-Length: ". strlen($post), "X-Requested-With: XMLHttpRequest"),
                'method' => 'POST',
                'content' => $post
            ));

            $response = $result['response'];
            
            if ($response && $response = json_decode($response)) {
                $response->body = html_entity_decode($response->body, ENT_QUOTES, 'UTF-8');

                $response->subject = str_replace(array('{name}', '{lastname}', '{email}', '{reward}'), array('John', 'Doe', 'john.doe@mail.com', '999'), $response->subject);
                $response->body = str_replace(array('{name}', '{lastname}', '{email}', '{reward}'), array('John', 'Doe', 'john.doe@mail.com', '999'), $response->body);
                $response->body = str_replace(array('{uid}', '%7Buid%7D'), 0, $response->body);
                $response->body = str_replace(array('{key}', '%7Bkey%7D'), 0, $response->body);

                $this->response->addHeader('Content-type: application/json');
                $this->response->setOutput(json_encode($response));
            }
        } else {
            $this->response->redirect($this->url->link('ne/newsletter', 'token=' . $this->session->data['token'], 'SSL'));
        }
    }

    public function get_defined_text() {
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['template_id']) && isset($this->request->post['language_id'])) {
            $this->load->model('ne/template');
            $template_info = $this->model_ne_template->get((int)$this->request->post['template_id']);
            $this->response->addHeader('Content-type: application/json');
            $this->response->setOutput(json_encode(array('text' => isset($template_info['defined_text'][(int)$this->request->post['language_id']]) ? $template_info['defined_text'][(int)$this->request->post['language_id']] : '')));
        } else {
            $this->response->redirect($this->url->link('ne/newsletter', 'token=' . $this->session->data['token'], 'SSL'));
        }
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'ne/newsletter')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['subject']) {
            $this->error['subject'] = $this->language->get('error_subject');
        }

        if (!$this->request->post['message']) {
            $this->error['message'] = $this->language->get('error_message');
        }

        return !$this->error;
    }

    private function do_request($options) {
        $options = $options + array(
            'method' => 'GET',
            'content' => false,
            'header' => false,
            'async' => false,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $options['url']);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Newsletter Enhancements for Opencart');

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        if ($options['header']) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $options['header']);
        }

        if ($options['async']) {
            curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        } else {
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        }

        switch ($options['method']) {
            case 'HEAD':
                curl_setopt($ch, CURLOPT_NOBODY, 1);
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $options['content']);
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $options['content']);
                break;
            default:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $options['method']);
                if ($options['content'])
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $options['content']);
                break;
        }

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return array(
            'header' => substr($result, 0, $info['header_size']),
            'response' => substr($result, $info['header_size']),
            'status' => $status,
            'info' => $info
        );
    }

    public function send() {
        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

            $this->load->language('sale/contact');
            $this->load->language('ne/newsletter');

            $this->load->model('ne/newsletter');

            if (!$this->user->hasPermission('modify', 'ne/newsletter')) {
                $json['error']['warning'] = $this->language->get('error_permission');
            }

            if (!$this->request->post['subject']) {
                $json['error']['subject'] = $this->language->get('error_subject');
            }

            if (!$this->request->post['message']) {
                $json['error']['message'] = $this->language->get('error_message');
            }

            if (!$json) {
                @set_time_limit(0);

                $emails = array();
                $is_end = true;

                $this->load->model('localisation/language');
                $language = $this->model_localisation_language->getLanguage((isset($this->request->post['language_id']) ? $this->request->post['language_id'] : $this->config->get('config_language_id')));

                if (empty($this->request->post['sent_count'])) {
                    $sent_count = 0;
                } else {
                    $sent_count = (int)$this->request->post['sent_count'];
                }

                if (isset($this->request->post['spam_check']) && $this->request->post['spam_check']) {
                    $newsletter_id = 0;
                    $isnotspam = file_get_contents('http://isnotspam.com');
                    if ($isnotspam) {
                        $isnotspam_doc = new DOMDocument;
                        if ($isnotspam_doc->loadHTML($isnotspam)) {
                            $xpath = new DOMXpath($isnotspam_doc);
                            foreach($xpath->query('//input[@name="email"]') as $input) {
                                $emails[$input->getAttribute('value')] = array(
                                    'firstname' => 'John',
                                    'lastname' => 'Doe'
                                );
                                break;
                            }
                        }
                    }
                } else {
                    if (empty($this->request->post['newsletter_id'])) {
                        $this->load->model('ne/stats');
                        $this->model_ne_stats->cleanup();

                        $newsletter_id = $this->model_ne_newsletter->addHistory(array(
                            'to' => $this->request->post['to'],
                            'subject' => $this->request->post['subject'],
                            'message' => $this->request->post['message'],
                            'text_message' => $this->request->post['text_message'],
                            'text_message_products' => $this->request->post['text_message_products'],
                            'store_id' => $this->request->post['store_id'],
                            'template_id' => $this->request->post['template_id'],
                            'language_id' => $language['language_id']
                        ));
                    } else {
                        $newsletter_id = $this->request->post['newsletter_id'];
                    }

                    if (isset($this->request->get['page'])) {
                        $page = $this->request->get['page'];
                    } else {
                        $page = 1;
                    }

                    if ($this->config->get('ne_throttle')) {
                        $iteration_amount = 6000;
                    } else {
                        $iteration_amount = 10;
                    }

                    switch ($this->request->post['to']) {
                        case 'marketing':
                            $marketing_data = array(
                                'filter_subscribed' => 1,
                                'filter_list'       => isset($this->request->post['marketing_list']) ? $this->request->post['marketing_list'] : array(),
                                'filter_store'      => $this->request->post['store_id'],
                                'start'             => ($page - 1) * $iteration_amount,
                                'limit'             => $iteration_amount
                            );

                            $this->load->model('ne/marketing');
                            $results = $this->model_ne_marketing->getList($marketing_data, true);

                            if ($results) {
                                $is_end = false;
                            }

                            foreach ($results as $result) {
                                if (isset($this->request->post['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                                    continue;
                                }
                                $emails[$result['email']] = array(
                                    'firstname' => $result['firstname'],
                                    'lastname' => $result['lastname']
                                );
                            }
                            break;
                        case 'marketing_all':
                            $marketing_data = array(
                                'filter_list'       => isset($this->request->post['marketing_list']) ? $this->request->post['marketing_list'] : array(),
                                'filter_store'      => $this->request->post['store_id'],
                                'start'             => ($page - 1) * $iteration_amount,
                                'limit'             => $iteration_amount
                            );

                            $this->load->model('ne/marketing');
                            $results = $this->model_ne_marketing->getList($marketing_data, true);

                            if ($results) {
                                $is_end = false;
                            }

                            foreach ($results as $result) {
                                if (isset($this->request->post['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                                    continue;
                                }
                                $emails[$result['email']] = array(
                                    'firstname' => $result['firstname'],
                                    'lastname' => $result['lastname']
                                );
                            }
                            break;
                        case 'subscriber':
                            $marketing_data = array(
                                'filter_subscribed' => 1,
                                'filter_list'       => isset($this->request->post['marketing_list']) ? $this->request->post['marketing_list'] : array(),
                                'filter_store'      => $this->request->post['store_id'],
                                'start'             => ($page - 1) * $iteration_amount / 2,
                                'limit'             => $iteration_amount / 2
                            );

                            $this->load->model('ne/marketing');
                            $results = $this->model_ne_marketing->getList($marketing_data, true);

                            if ($results) {
                                $is_end = false;
                            }

                            foreach ($results as $result) {
                                if (isset($this->request->post['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                                    continue;
                                }
                                $emails[$result['email']] = array(
                                    'firstname' => $result['firstname'],
                                    'lastname' => $result['lastname']
                                );
                            }

                            $customer_data = array(
                                'filter_newsletter' => 1,
                                'start'             => ($page - 1) * $iteration_amount / 2,
                                'limit'             => $iteration_amount / 2
                            );

                            $results = $this->model_ne_newsletter->getCustomers($customer_data);

                            if ($results) {
                                $is_end = false;
                            }

                            foreach ($results as $result) {
                                if (isset($this->request->post['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                                    continue;
                                }
                                if ($result['store_id'] == $this->request->post['store_id']) {
                                    $emails[$result['email']] = array(
                                        'firstname' => $result['firstname'],
                                        'lastname' => $result['lastname']
                                    );
                                }
                            }
                            break;
                        case 'all':
                            $marketing_data = array(
                                'filter_list'       => isset($this->request->post['marketing_list']) ? $this->request->post['marketing_list'] : array(),
                                'filter_store'      => $this->request->post['store_id'],
                                'start'             => ($page - 1) * $iteration_amount / 2,
                                'limit'             => $iteration_amount / 2
                            );

                            $this->load->model('ne/marketing');
                            $results = $this->model_ne_marketing->getList($marketing_data, true);

                            if ($results) {
                                $is_end = false;
                            }

                            foreach ($results as $result) {
                                if (isset($this->request->post['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                                    continue;
                                }
                                $emails[$result['email']] = array(
                                    'firstname' => $result['firstname'],
                                    'lastname' => $result['lastname']
                                );
                            }

                            $results = $this->model_ne_newsletter->getCustomers(array(
                                'start'             => ($page - 1) * $iteration_amount / 2,
                                'limit'             => $iteration_amount / 2
                            ));

                            if ($results) {
                                $is_end = false;
                            }

                            foreach ($results as $result) {
                                if (isset($this->request->post['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                                    continue;
                                }
                                if ($result['store_id'] == $this->request->post['store_id']) {
                                    $emails[$result['email']] = array(
                                        'firstname' => $result['firstname'],
                                        'lastname' => $result['lastname']
                                    );
                                }
                            }
                            break;
                        case 'newsletter':
                            $customer_data = array(
                                'filter_newsletter' => 1,
                                'start'             => ($page - 1) * $iteration_amount,
                                'limit'             => $iteration_amount
                            );

                            $results = $this->model_ne_newsletter->getCustomers($customer_data);

                            if ($results) {
                                $is_end = false;
                            }

                            foreach ($results as $result) {
                                if (isset($this->request->post['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                                    continue;
                                }
                                if ($result['store_id'] == $this->request->post['store_id']) {
                                    $emails[$result['email']] = array(
                                        'firstname' => $result['firstname'],
                                        'lastname' => $result['lastname']
                                    );
                                }
                            }
                            break;
                        case 'customer_all':
                            $results = $this->model_ne_newsletter->getCustomers(array(
                                'start'             => ($page - 1) * $iteration_amount,
                                'limit'             => $iteration_amount
                            ));

                            if ($results) {
                                $is_end = false;
                            }

                            foreach ($results as $result) {
                                if (isset($this->request->post['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                                    continue;
                                }
                                if ($result['store_id'] == $this->request->post['store_id']) {
                                    $emails[$result['email']] = array(
                                        'firstname' => $result['firstname'],
                                        'lastname' => $result['lastname']
                                    );
                                }
                            }
                            break;
                        case 'customer_group':
                            $customer_data = array(
                                'filter_customer_group_id' => $this->request->post['customer_group_id'],
                                'start'             => ($page - 1) * $iteration_amount,
                                'limit'             => $iteration_amount
                            );

                            if (isset($this->request->post['customer_group_only_subscribers'])) {
                                $customer_data['filter_newsletter'] = 1;
                            }

                            $results = $this->model_ne_newsletter->getCustomers($customer_data);

                            if ($results) {
                                $is_end = false;
                            }

                            foreach ($results as $result) {
                                if (isset($this->request->post['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                                    continue;
                                }
                                if ($result['store_id'] == $this->request->post['store_id']) {
                                    $emails[$result['email']] = array(
                                        'firstname' => $result['firstname'],
                                        'lastname' => $result['lastname']
                                    );
                                }
                            }
                            break;
                        case 'customer':
                            if (isset($this->request->post['customer']) && !empty($this->request->post['customer'])) {
                                foreach ($this->request->post['customer'] as $customer_id) {
                                    $customer_info = $this->model_ne_newsletter->getCustomer($customer_id);

                                    if ($customer_info) {
                                        if (isset($this->request->post['only_selected_language']) && (($language['code'] != $customer_info['language_code'] && $customer_info['language_code']) || (!$customer_info['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                                            continue;
                                        }
                                        $emails[$customer_info['email']] = array(
                                            'firstname' => $customer_info['firstname'],
                                            'lastname' => $customer_info['lastname']
                                        );
                                    }
                                }
                            }
                            break;
                        case 'affiliate_all':
                            $results = $this->model_ne_newsletter->getAffiliates(array(
                                'start'             => ($page - 1) * $iteration_amount,
                                'limit'             => $iteration_amount
                            ));

                            if ($results) {
                                $is_end = false;
                            }

                            foreach ($results as $result) {
                                if (isset($this->request->post['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                                    continue;
                                }
                                $emails[$result['email']] = array(
                                    'firstname' => $result['firstname'],
                                    'lastname' => $result['lastname']
                                );
                            }
                            break;
                        case 'affiliate':
                            if (isset($this->request->post['affiliate']) && !empty($this->request->post['affiliate'])) {
                                foreach ($this->request->post['affiliate'] as $affiliate_id) {
                                    $affiliate_info = $this->model_ne_newsletter->getAffiliate($affiliate_id);

                                    if ($affiliate_info) {
                                        if (isset($this->request->post['only_selected_language']) && (($language['code'] != $affiliate_info['language_code'] && $affiliate_info['language_code']) || (!$affiliate_info['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                                            continue;
                                        }
                                        $emails[$affiliate_info['email']] = array(
                                            'firstname' => $affiliate_info['firstname'],
                                            'lastname' => $affiliate_info['lastname']
                                        );
                                    }
                                }
                            }
                            break;
                        case 'product':
                            if (isset($this->request->post['product']) && $this->request->post['product']) {
                                $results = $this->model_ne_newsletter->getEmailsByProductsOrdered($this->request->post['product']);

                                foreach ($results as $result) {
                                    if (isset($this->request->post['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                                        continue;
                                    }
                                    if ($result['store_id'] == $this->request->post['store_id']) {
                                        $emails[$result['email']] = array(
                                            'firstname' => $result['firstname'],
                                            'lastname' => $result['lastname']
                                        );
                                    }
                                }
                            }
                            break;
                        case 'rewards_all':
                            $results = $this->model_ne_newsletter->getRecipientsWithRewardPoints(array(
                                'start'             => ($page - 1) * $iteration_amount,
                                'limit'             => $iteration_amount
                            ));

                            if ($results) {
                                $is_end = false;
                            }

                            foreach ($results as $result) {
                                if (isset($this->request->post['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                                    continue;
                                }
                                $emails[$result['email']] = array(
                                    'firstname' => $result['firstname'],
                                    'lastname' => $result['lastname'],
                                    'reward' => $result['points'],
                                );
                            }
                            break;
                        case 'rewards':
                            $results = $this->model_ne_newsletter->getSubscribedRecipientsWithRewardPoints(array(
                                'start'             => ($page - 1) * $iteration_amount,
                                'limit'             => $iteration_amount
                            ));

                            if ($results) {
                                $is_end = false;
                            }

                            foreach ($results as $result) {
                                if (isset($this->request->post['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                                    continue;
                                }
                                $emails[$result['email']] = array(
                                    'firstname' => $result['firstname'],
                                    'lastname' => $result['lastname'],
                                    'reward' => $result['points'],
                                );
                            }
                            break;
                    }
                }

                $sent_count += ($emails ? count($emails) : 0);

                $json['sent_count'] = $sent_count;
                if ($is_end) {
                    if ($this->config->get('ne_throttle')) {
                        $json['success'] = $this->language->get('text_throttle_success');
                    } else {
                        $json['success'] = $this->language->get('text_success');
                    }

                    if (count($emails) == 1 && !empty($this->request->post['spam_check'])) {
                        $this->load->model('setting/setting');
                        $emails_keys = array_keys($emails);
                        $json['success'] = sprintf($this->language->get('text_success_check'), urlencode(reset($emails_keys)));
                    } else {
                        $this->model_ne_newsletter->addHistoryQueue($newsletter_id, array(
                            'queue' => ($this->config->get('ne_throttle') ? 0 : $sent_count),
                            'recipients' => $sent_count
                        ));
                    }

                    $json['next'] = '';
                    $json['newsletter_id'] = 0;
                } else {
                    $json['newsletter_id'] = $newsletter_id;
                    if ($this->config->get('ne_throttle')) {
                        $json['success'] = sprintf($this->language->get('text_throttle_sent'), $sent_count);
                    } else {
                        $json['success'] = sprintf($this->language->get('text_sent'), $sent_count);
                    }
                    $json['next'] = str_replace('&amp;', '&', $this->url->link('ne/newsletter/send', 'token=' . $this->session->data['token'] . '&page=' . ($page + 1), 'SSL'));
                }

                if ($emails) {
                    $this->load->model('ne/template');
                    $template_info = $this->model_ne_template->get((int)$this->request->post['template_id']);

                    $data_param = array(
                        'emails' => $emails,
                        'subject' => $this->request->post['subject'],
                        'message' => $this->request->post['message'],
                        'text_message' => $this->request->post['text_message'],
                        'text_message_products' => $this->request->post['text_message_products'],
                        'store_id' => $this->request->post['store_id'],
                        'attachments_id' => $this->request->post['attachments_id'],
                        'language_code' => $language['code'],
                        'newsletter_id' => $newsletter_id,
                        'custom_css' => empty($template_info['custom_css']) ? '' : $template_info['custom_css']
                    );

                    $this->model_ne_newsletter->send($data_param);
                }
            }
        }

        $this->response->setOutput(json_encode($json));
    }
}
