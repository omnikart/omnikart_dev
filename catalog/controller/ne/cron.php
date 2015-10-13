<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------

define('NE_TIME', DIR_LOGS . 'ne_time');

class ControllerNeCron extends Controller {

    private $template;

    public function index() {
        if (!isset($this->request->get['key']) || $this->request->get['key'] != md5($this->config->get('ne_key'))) {
            $this->response->redirect($this->url->link('common/home'));
        }

        @set_time_limit(0);

        $this->load->model('ne/cron');
        $this->load->model('ne/template');

        $this->do_scheduled();

        if (!file_exists(NE_TIME) || (filemtime(NE_TIME) + (int)$this->config->get('ne_throttle_time') - 100) < time()) {
            $this->send_queued();
        }

        $this->response->setOutput(date('Y-m-d H:i:s') . ' - ok');
    }

    private function do_scheduled() {

        $date = date('Y-m-d');
        $time = date('G');
        $day = date('w');

        $weekdays = array("sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday");
        $frequency = array("7" => "week", "30" => "month");

        $schedules = $this->model_ne_cron->get_scheduled($date, $time, $day);

        foreach ($schedules as $schedule) {

            $this->set_store_config($schedule['store_id']);

            $template_data = $this->template($schedule);

            $emails = array();

            $this->load->model('localisation/language');
            $language = $this->model_localisation_language->getLanguage($schedule['language_id']);

            switch ($schedule['to']) {
                case 'marketing':
                    $marketing_list = $schedule['marketing_list'] ? unserialize($schedule['marketing_list']) : array();
                    $marketing_data = array(
                        'filter_subscribed' => 1,
                        'filter_list' => $marketing_list,
                        'filter_store' => $schedule['store_id']
                    );

                    $this->load->model('ne/marketing');
                    $results = $this->model_ne_marketing->getList($marketing_data, true);

                    foreach ($results as $result) {
                        if (!empty($schedule['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                            continue;
                        }
                        $emails[$result['email']] = array(
                            'firstname' => $result['firstname'],
                            'lastname' => $result['lastname']
                        );
                    }
                    break;
                case 'marketing_all':
                    $marketing_list = $schedule['marketing_list'] ? unserialize($schedule['marketing_list']) : array();
                    $marketing_data = array(
                        'filter_list' => $marketing_list,
                        'filter_store' => $schedule['store_id']
                    );

                    $this->load->model('ne/marketing');
                    $results = $this->model_ne_marketing->getList($marketing_data, true);

                    foreach ($results as $result) {
                        if (!empty($schedule['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                            continue;
                        }
                        $emails[$result['email']] = array(
                            'firstname' => $result['firstname'],
                            'lastname' => $result['lastname']
                        );
                    }
                    break;
                case 'subscriber':
                    $marketing_list = $schedule['marketing_list'] ? unserialize($schedule['marketing_list']) : array();
                    $marketing_data = array(
                        'filter_subscribed' => 1,
                        'filter_list' => $marketing_list,
                        'filter_store' => $schedule['store_id']
                    );

                    $this->load->model('ne/marketing');
                    $results = $this->model_ne_marketing->getList($marketing_data, true);

                    foreach ($results as $result) {
                        if (!empty($schedule['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                            continue;
                        }
                        $emails[$result['email']] = array(
                            'firstname' => $result['firstname'],
                            'lastname' => $result['lastname']
                        );
                    }

                    $customer_data = array(
                        'filter_newsletter' => 1
                    );

                    $results = $this->model_ne_cron->getCustomers($customer_data);

                    foreach ($results as $result) {
                        if (!empty($schedule['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                            continue;
                        }
                        if ($result['store_id'] == $schedule['store_id']) {
                            $emails[$result['email']] = array(
                                'firstname' => $result['firstname'],
                                'lastname' => $result['lastname']
                            );
                        }
                    }
                    break;
                case 'all':
                    $marketing_list = $schedule['marketing_list'] ? unserialize($schedule['marketing_list']) : array();
                    $marketing_data = array(
                        'filter_list' => $marketing_list,
                        'filter_store' => $schedule['store_id']
                    );

                    $this->load->model('ne/marketing');
                    $results = $this->model_ne_marketing->getList($marketing_data, true);

                    foreach ($results as $result) {
                        if (!empty($schedule['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                            continue;
                        }
                        $emails[$result['email']] = array(
                            'firstname' => $result['firstname'],
                            'lastname' => $result['lastname']
                        );
                    }

                    $results = $this->model_ne_cron->getCustomers();

                    foreach ($results as $result) {
                        if (!empty($schedule['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                            continue;
                        }
                        if ($result['store_id'] == $schedule['store_id']) {
                            $emails[$result['email']] = array(
                                'firstname' => $result['firstname'],
                                'lastname' => $result['lastname']
                            );
                        }
                    }
                    break;
                case 'newsletter':
                    $customer_data = array(
                        'filter_newsletter' => 1
                    );

                    $results = $this->model_ne_cron->getCustomers($customer_data);

                    foreach ($results as $result) {
                        if (!empty($schedule['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                            continue;
                        }
                        if ($result['store_id'] == $schedule['store_id']) {
                            $emails[$result['email']] = array(
                                'firstname' => $result['firstname'],
                                'lastname' => $result['lastname']
                            );
                        }
                    }
                    break;
                case 'customer_all':
                    $results = $this->model_ne_cron->getCustomers();

                    foreach ($results as $result) {
                        if (!empty($schedule['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                            continue;
                        }
                        if ($result['store_id'] == $schedule['store_id']) {
                            $emails[$result['email']] = array(
                                'firstname' => $result['firstname'],
                                'lastname' => $result['lastname']
                            );
                        }
                    }
                    break;
                case 'customer_group':
                    $customer_data = array(
                        'filter_customer_group_id' => $schedule['customer_group_id'],
                    );

                    if ($schedule['customer_group_only_subscribers']) {
                        $customer_data['filter_newsletter'] = 1;
                    }

                    $results = $this->model_ne_cron->getCustomers($customer_data);

                    foreach ($results as $result) {
                        if (!empty($schedule['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                            continue;
                        }
                        if ($result['store_id'] == $schedule['store_id']) {
                            $emails[$result['email']] = array(
                                'firstname' => $result['firstname'],
                                'lastname' => $result['lastname']
                            );
                        }
                    }
                    break;
                case 'customer':
                    if (isset($schedule['customer']) && $schedule['customer']) {
                        $schedule['customer'] = unserialize($schedule['customer']);
                        foreach ($schedule['customer'] as $customer_id) {
                            $customer_info = $this->model_ne_cron->getCustomer($customer_id);

                            if ($customer_info) {
                                if (!empty($schedule['only_selected_language']) && (($language['code'] != $customer_info['language_code'] && $customer_info['language_code']) || (!$customer_info['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
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
                    $results = $this->model_ne_cron->getAffiliates();

                    foreach ($results as $result) {
                        if (!empty($schedule['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                            continue;
                        }
                        $emails[$result['email']] = array(
                            'firstname' => $result['firstname'],
                            'lastname' => $result['lastname']
                        );
                    }
                    break;
                case 'affiliate':
                    if (isset($schedule['affiliate']) && $schedule['affiliate']) {
                        $schedule['affiliate'] = unserialize($schedule['affiliate']);
                        foreach ($schedule['affiliate'] as $affiliate_id) {
                            $affiliate_info = $this->model_ne_cron->getAffiliate($affiliate_id);

                            if ($affiliate_info) {
                                if (!empty($schedule['only_selected_language']) && (($language['code'] != $affiliate_info['language_code'] && $affiliate_info['language_code']) || (!$affiliate_info['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
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
                    if (isset($schedule['product']) && $schedule['product']) {
                        $schedule['product'] = unserialize($schedule['product']);
                        $results = $this->model_ne_cron->getEmailsByProductsOrdered($schedule['product']);

                        foreach ($results as $result) {
                            if (!empty($schedule['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
                                continue;
                            }
                            if ($result['store_id'] == $schedule['store_id']) {
                                $emails[$result['email']] = array(
                                    'firstname' => $result['firstname'],
                                    'lastname' => $result['lastname']
                                );
                            }
                        }
                    }
                    break;
                case 'rewards_all':
                    $results = $this->model_ne_cron->getRecipientsWithRewardPoints();

                    foreach ($results as $result) {
                        if (!empty($schedule['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
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
                    $results = $this->model_ne_cron->getSubscribedRecipientsWithRewardPoints();

                    foreach ($results as $result) {
                        if (!empty($schedule['only_selected_language']) && (($language['code'] != $result['language_code'] && $result['language_code']) || (!$result['language_code'] && $language['language_id'] != $this->config->get('config_language_id')))) {
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

            if ($emails) {
                $template_info = $this->model_ne_template->getTemplate($schedule);

                $data_param = array(
                    'emails' => $emails,
                    'to' => $schedule['to'],
                    'subject' => $template_data['subject'],
                    'message' => $template_data['body'],
                    'text_message' => $schedule['text_message'],
                    'text_message_products' => $schedule['text_message_products'],
                    'store_id' => $schedule['store_id'],
                    'template_id' => $schedule['template_id'],
                    'language_id' => $schedule['language_id'],
                    'attachments_count' => 0,
                    'attachments_upload' => array(),
                    'attachments' => false,
                    'language_code' => $language['code'],
                    'custom_css' => empty($template_info['custom_css']) ? '' : $template_info['custom_css']
                );

                $this->model_ne_cron->send($data_param);
            }

            if ($schedule['recurrent'] == '1') {
                switch ($schedule['frequency']) {
                    case '1':
                        $this->model_ne_cron->schedule_next($schedule['schedule_id'], date('Y-m-d', strtotime("next day")));
                        break;
                    case '7':
                    case '30':
                        $next_date = date('Y-m-d', strtotime("first " . $weekdays[$schedule['day']] . " of next " . $frequency[$schedule['frequency']]));
                        $this->model_ne_cron->schedule_next($schedule['schedule_id'], $next_date);
                        break;
                    default:
                        $this->model_ne_cron->schedule_inactive($schedule['schedule_id']);
                        break;
                }
            } else {
                $this->model_ne_cron->schedule_inactive($schedule['schedule_id']);
            }
        }
    }

    private function send_queued() {

        $emails_in_queue = $this->model_ne_cron->get_queue();

        $emails_to_send = array();

        foreach ($emails_in_queue as $email_queue) {

            if (!isset($emails_to_send[$email_queue['history_id']])) {
                $emails_to_send[$email_queue['history_id']] = array();
            }

            $emails_to_send[$email_queue['history_id']][$email_queue['email']] = array('firstname' => $email_queue['firstname'], 'lastname' => $email_queue['lastname'], 'queue_id' => $email_queue['queue_id'], 'retries' => $email_queue['retries']);
        }

        foreach ($emails_to_send as $history_id => $emails) {

            $message_info = $this->model_ne_cron->get_newsletter($history_id);

            if ($message_info) {

                $this->load->model('localisation/language');
                $language = $this->model_localisation_language->getLanguage($message_info['language_id']);

                $this->set_store_config($message_info['store_id']);

                $template_info = $this->model_ne_template->getTemplate($message_info);

                $data_param = array(
                    'history_id' => $history_id,
                    'emails' => $emails,
                    'to' => $message_info['to'],
                    'subject' => $message_info['subject'],
                    'message' => html_entity_decode($message_info['message'], ENT_QUOTES, 'UTF-8'),
                    'text_message' => $message_info['text_message'],
                    'text_message_products' => $message_info['text_message_products'],
                    'store_id' => $message_info['store_id'],
                    'template_id' => $message_info['template_id'],
                    'language_id' => $message_info['language_id'],
                    'attachments_count' => 0,
                    'attachments_upload' => array(),
                    'attachments' => (array)glob(dirname(DIR_APPLICATION) . '/attachments/' . $message_info['history_id'] . '/*.*'),
                    'language_code' => $language['code'],
                    'custom_css' => empty($template_info['custom_css']) ? '' : $template_info['custom_css']
                );

                $this->model_ne_cron->send($data_param);
            }
        }

        touch(NE_TIME);
    }

    private function template($schedule) {

        $this->load->model('ne/template');

        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $server = defined('HTTPS_IMAGE') ? HTTPS_IMAGE : HTTPS_SERVER . 'image/';
        } else {
            $server = defined('HTTP_IMAGE') ? HTTP_IMAGE : HTTP_SERVER . 'image/';
        }

        $data_param = array(
            'template_id' => $schedule['template_id'],
            'language_id' => (int)(isset($schedule['language_id']) ? $schedule['language_id'] : $this->config->get('config_language_id'))
        );
        $template = $this->model_ne_template->getTemplate($data_param);
        unset($data_param);

        $currency = $schedule['currency'] ? $schedule['currency'] : '';

        if ($template) {

            if (!$template['uri'] || (!file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/') && !file_exists(DIR_TEMPLATE . 'default/template/ne/templates/' . $template['uri'] . '/'))) {
                $template['uri'] = 'default';
            }

            $subject = html_entity_decode($schedule['subject'], ENT_QUOTES, 'UTF-8');
            $body = html_entity_decode($schedule['message'], ENT_QUOTES, 'UTF-8');
            $subject = html_entity_decode($subject, ENT_QUOTES, 'UTF-8');
            $body = html_entity_decode($body, ENT_QUOTES, 'UTF-8');

            if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
                $body = str_replace('{logo}', '<!--//logo//--><a href="' . $this->url->link('common/home') . '" target="_blank"><img src="' . $server . $this->config->get('config_logo') . '" alt=""/></a><!--//end logo//-->', $body);
                $body = $this->replaceTags('<!--//logo//-->', '<!--//end logo//-->', '<a href="' . $this->url->link('common/home') . '" target="_blank"><img src="' . $server . $this->config->get('config_logo') . '" alt=""/></a>', $body);
            } else {
                $body = str_replace('{logo}', '<!--//logo//--><!--//end logo//-->', $body);
                $body = $this->replaceTags('<!--//logo//-->', '<!--//end logo//-->', '', $body);
            }

            if (strpos($body, '{date}') !== false) {
                $localisation_months = $this->config->get('ne_months');
                $localisation_weekdays = $this->config->get('ne_weekdays');
                $body = str_replace('{date}', ($localisation_weekdays[$template['language_id']][(int)date('w')] . ', ' . date('j') . ' ' . $localisation_months[$template['language_id']][(int)date('m') - 1] . ' ' . date('Y')), $body);
            }

            $this->load->model('localisation/language');
            $languages = $this->model_localisation_language->getLanguages();
            $languages_chooser = '';
            if (count($languages) > 1) {
                foreach ($languages as $language) {
                    $languages_chooser .= '<a href="' . $this->url->link('ne/language', 'lang=' . $language['code'] . '&uid={uid}&key={key}') . '" title="' . $language['name'] . '"><img src="' . $server . 'flags/' . $language['image'] . '" alt="' . $language['code'] . '" /></a>&nbsp;';
                }
            }

            $body = str_replace(
                array(
                    '{store_url}',
                    '{show_url}',
                    '{unsubscribe_url}',
                    '{subscribe_url}',
                    '{language}'
                ),
                array(
                    $this->url->link('common/home'),
                    $this->url->link('ne/show', 'uid={uid}'),
                    $this->url->link('ne/unsubscribe', 'uid={uid}&key={key}'),
                    $this->url->link('ne/subscribe', 'uid={uid}&key={key}'),
                    rtrim($languages_chooser, '&nbsp;')
                ),
                $body
            );

            $body = str_replace(
                array(
                    '{defined}',
                    '{special}',
                    '{latest}',
                    '{popular}',
                    '{categories}'
                ),
                array(
                    '<!--//defined//--><!--//end defined//-->',
                    '<!--//special//--><!--//end special//-->',
                    '<!--//latest//--><!--//end latest//-->',
                    '<!--//popular//--><!--//end popular//-->',
                    '<!--//defined_categories//--><!--//end defined_categories//-->'
                ),
                $body
            );

            $data['columns_count'] = $template['product_cols'];

            $data['heading_color'] = $template['heading_color'];
            $data['heading_style'] = $template['heading_style'];

            $data['name_color'] = $template['product_name_color'];
            $data['name_style'] = $template['product_name_style'];

            $data['model_color'] = $template['product_model_color'];
            $data['model_style'] = $template['product_model_style'];

            $data['show_price'] = $template['product_show_prices'];
            $data['show_saving'] = $template['product_show_savings'];

            $data['price_color'] = $template['product_price_color'];
            $data['price_style'] = $template['product_price_style'];

            $data['special_color'] = $template['product_price_color_special'];
            $data['special_style'] = $template['product_price_style_special'];

            $data['old_price_color'] = $template['product_price_color_when_special'];
            $data['old_price_style'] = $template['product_price_style_when_special'];

            $data['description_color'] = $template['product_description_color'];
            $data['description_style'] = $template['product_description_style'];

            $data['saving_color'] = $template['product_savings_color'];
            $data['saving_style'] = $template['product_savings_style'];

            $data['image_width'] = $template['product_image_width'];
            $data['image_height'] = $template['product_image_height'];

            $data['heading'] = '';
            $data['products'] = array();

            $data_setting = array(
                'language_id' => (int)(isset($schedule['language_id']) ? $schedule['language_id'] : $this->config->get('config_language_id')),
                'customer_group_id' => (int)((isset($schedule['customer_group_id']) && $schedule['customer_group_id']) ? $schedule['customer_group_id'] : $this->config->get('config_customer_group_id')),
                'store_id' => (int)(isset($schedule['store_id']) ? $schedule['store_id'] : $this->config->get('config_store_id'))
            );

            $data['products'] = array();

            if (isset($schedule['defined']) && $schedule['defined'] && (!isset($schedule['recurrent']) || (isset($schedule['recurrent']) && !$schedule['recurrent'])))
            {
                $data['heading'] = $template['defined'];

                $this->load->model('tool/image');

                $defined_products = unserialize($schedule['defined_products']);

                if (!$defined_products) {
                    $defined_products = array();
                }

                foreach ($defined_products as $product_id) {
                    $data_param = array_merge(array('product_id' => (int)$product_id), $data_setting);
                    $result = $this->model_ne_template->getProduct($data_param);

                    if (!$result) {
                        continue;
                    }

                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $template['product_image_width'], $template['product_image_height']);
                    } else {
                        $image = $this->model_tool_image->resize('no_image.jpg', $template['product_image_width'], $template['product_image_height']);
                    }

                    $image = str_replace(' ', '%20', $image);

                    if ($template['product_show_prices']) {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $price = false;
                    }

                    if ($template['product_show_prices'] && (float)$result['special']) {
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $special = false;
                    }

                    if ($template['product_show_savings'] && (float)$result['special']) {
                        $saving = round((($result['price'] - $result['special'])/$result['price'])*100, 0);
                        if ($saving > 0) {
                            $saving = sprintf($template['savings'], $saving);
                        } else {
                            $saving = false;
                        }
                    } else {
                        $saving = false;
                    }

                    $data['products'][] = array(
                        'product_id'  => $result['product_id'],
                        'thumb'   	  => $image,
                        'name'    	  => $result['name'],
                        'price'    	  => str_replace('$', ':usd', $price),
                        'special'     => str_replace('$', ':usd', $special),
                        'saving'      => $saving,
                        'model'       => (isset($result['model']) ? $result['model'] : ''),
                        'description' => ((int)$template['product_description_length'] ? mb_substr(trim(preg_replace("/\s\s+/u", ' ', strip_tags(html_entity_decode(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8')))), 0, (int)$template['product_description_length'], 'UTF-8') . '..' : ''),
                        'href'    	  => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                    );
                }
                unset($result);

                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/defined.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/defined.tpl';
                } else {
                    $this->template = 'default/template/ne/templates/' . $template['uri'] . '/defined.tpl';
                }

                $defined = $this->load->view($this->template, $data);

                $defined_products_more = unserialize($schedule['defined_products_more']);

                if (!$defined_products_more) {
                    $defined_products_more = array();
                }

                foreach ($defined_products_more as $defined_more) {
                    if (!isset($defined_more['products']) || !$defined_more['products']) {
                        continue;
                    }

                    $data['heading'] = isset($defined_more['text']) ? $defined_more['text'] : '';

                    $data['products'] = array();

                    foreach ($defined_more['products'] as $product_id) {
                        $data_param = array_merge(array('product_id' => (int)$product_id), $data_setting);
                        $result = $this->model_ne_template->getProduct($data_param);

                        if (!$result) {
                            continue;
                        }

                        if ($result['image']) {
                            $image = $this->model_tool_image->resize($result['image'], $template['product_image_width'], $template['product_image_height']);
                        } else {
                            $image = $this->model_tool_image->resize('no_image.jpg', $template['product_image_width'], $template['product_image_height']);
                        }

                        $image = str_replace(' ', '%20', $image);

                        if ($template['product_show_prices']) {
                            $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                        } else {
                            $price = false;
                        }

                        if ($template['product_show_prices'] && (float)$result['special']) {
                            $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                        } else {
                            $special = false;
                        }

                        if ($template['product_show_savings'] && (float)$result['special']) {
                            $saving = round((($result['price'] - $result['special'])/$result['price'])*100, 0);
                            if ($saving > 0) {
                                $saving = sprintf($template['savings'], $saving);
                            } else {
                                $saving = false;
                            }
                        } else {
                            $saving = false;
                        }

                        $data['products'][] = array(
                            'product_id'  => $result['product_id'],
                            'thumb'   	  => $image,
                            'name'    	  => $result['name'],
                            'price'    	  => str_replace('$', ':usd', $price),
                            'special'     => str_replace('$', ':usd', $special),
                            'saving'      => $saving,
                            'model'       => (isset($result['model']) ? $result['model'] : ''),
                            'description' => ((int)$template['product_description_length'] ? mb_substr(trim(preg_replace("/\s\s+/u", ' ', strip_tags(html_entity_decode(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8')))), 0, (int)$template['product_description_length'], 'UTF-8') . '..' : ''),
                            'href'    	  => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                        );
                    }
                    unset($result);

                    $defined .= $this->load->view($this->template, $data);
                }

                $body = $this->replaceTags('<!--//defined//-->', '<!--//end defined//-->', $defined, $body);
            } else {
                $body = $this->replaceTags('<!--//defined//-->', '<!--//end defined//-->', '', $body);
            }

            $data['products'] = array();

            if (isset($schedule['defined_categories']) && $schedule['defined_categories'])
            {
                $this->load->model('tool/image');

                $defined_categories = '';

                $schedule['defined_categories'] = unserialize($schedule['defined_categories']);

                foreach ($schedule['defined_categories'] as $category_id) {

                    $data['heading'] = sprintf($template['categories'], $this->model_ne_template->getPath($category_id, $data_setting['language_id']));

                    $data['products'] = array();
                    $products = $this->model_ne_template->getProductsByCategoryId($category_id);

                    foreach ($products as $product) {
                        $data_param = array_merge(array('product_id' => (int)$product['product_id']), $data_setting);
                        $result = $this->model_ne_template->getProduct($data_param);

                        if (!$result) {
                            continue;
                        }

                        if ($result['image']) {
                            $image = $this->model_tool_image->resize($result['image'], $template['product_image_width'], $template['product_image_height']);
                        } else {
                            $image = $this->model_tool_image->resize('no_image.jpg', $template['product_image_width'], $template['product_image_height']);
                        }

                        $image = str_replace(' ', '%20', $image);

                        if ($template['product_show_prices']) {
                            $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                        } else {
                            $price = false;
                        }

                        if ($template['product_show_prices'] && (float)$result['special']) {
                            $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                        } else {
                            $special = false;
                        }

                        if ($template['product_show_savings'] && (float)$result['special']) {
                            $saving = round((($result['price'] - $result['special'])/$result['price'])*100, 0);
                            if ($saving > 0) {
                                $saving = sprintf($template['savings'], $saving);
                            } else {
                                $saving = false;
                            }
                        } else {
                            $saving = false;
                        }

                        $data['products'][] = array(
                            'product_id'  => $result['product_id'],
                            'thumb'   	  => $image,
                            'name'    	  => $result['name'],
                            'price'    	  => str_replace('$', ':usd', $price),
                            'special'     => str_replace('$', ':usd', $special),
                            'saving'      => $saving,
                            'model'       => (isset($result['model']) ? $result['model'] : ''),
                            'description' => ((int)$template['product_description_length'] ? mb_substr(trim(preg_replace("/\s\s+/u", ' ', strip_tags(html_entity_decode(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8')))), 0, (int)$template['product_description_length'], 'UTF-8') . '..' : ''),
                            'href'    	  => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                        );
                    }
                    unset($result);

                    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/categories.tpl')) {
                        $this->template = $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/categories.tpl';
                    } else {
                        $this->template = 'default/template/ne/templates/' . $template['uri'] . '/categories.tpl';
                    }

                    $defined_categories .= $this->load->view($this->template, $data);
                }

                $body = $this->replaceTags('<!--//defined_categories//-->', '<!--//end defined_categories//-->', $defined_categories, $body);
            } else {
                $body = $this->replaceTags('<!--//defined_categories//-->', '<!--//end defined_categories//-->', '', $body);
            }

            $data['products'] = array();

            if (isset($schedule['special']) && $schedule['special'])
            {
                $data['heading'] = $template['special'];

                $this->load->model('tool/image');

                $data_param = array_merge(array(
                    'sort'  => 'p.sort_order',
                    'order' => 'ASC',
                    'start' => 0,
                    'limit' => (int)$template['specials_count']
                ), $data_setting);

                $product_total = $this->model_ne_template->getTotalProductSpecials($data_param);

                if (!$data_param['limit'])
                {
                    $data_param['limit'] = $product_total;
                }

                $results = $this->model_ne_template->getProductSpecials($data_param);

                foreach ($results as $result) {
                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $template['product_image_width'], $template['product_image_height']);
                    } else {
                        $image = $this->model_tool_image->resize('no_image.jpg', $template['product_image_width'], $template['product_image_height']);
                    }

                    $image = str_replace(' ', '%20', $image);

                    if ($template['product_show_prices']) {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $price = false;
                    }

                    if ($template['product_show_prices'] && (float)$result['special']) {
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $special = false;
                    }

                    if ($template['product_show_savings'] && (float)$result['special']) {
                        $saving = round((($result['price'] - $result['special'])/$result['price'])*100, 0);
                        if ($saving > 0) {
                            $saving = sprintf($template['savings'], $saving);
                        } else {
                            $saving = false;
                        }
                    } else {
                        $saving = false;
                    }

                    $data['products'][] = array(
                        'product_id'  => $result['product_id'],
                        'thumb'   	  => $image,
                        'name'    	  => $result['name'],
                        'price'    	  => str_replace('$', ':usd', $price),
                        'special'     => str_replace('$', ':usd', $special),
                        'saving'      => $saving,
                        'model'       => (isset($result['model']) ? $result['model'] : ''),
                        'description' => ((int)$template['product_description_length'] ? mb_substr(trim(preg_replace("/\s\s+/u", ' ', strip_tags(html_entity_decode(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8')))), 0, (int)$template['product_description_length'], 'UTF-8') . '..' : ''),
                        'href'    	  => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                    );
                }
                unset($result);

                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/special.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/special.tpl';
                } else {
                    $this->template = 'default/template/ne/templates/' . $template['uri'] . '/special.tpl';
                }

                $special = $this->load->view($this->template, $data);
                $body = $this->replaceTags('<!--//special//-->', '<!--//end special//-->', $special, $body);
            } else {
                $body = $this->replaceTags('<!--//special//-->', '<!--//end special//-->', '', $body);
            }

            $data['products'] = array();

            if (isset($schedule['latest']) && $schedule['latest'])
            {
                $data['heading'] = $template['latest'];

                $this->load->model('tool/image');

                $data_param = array_merge(array(
                    'sort'  => 'p.date_added',
                    'order' => 'DESC',
                    'start' => 0,
                    'limit' => (int)$template['latest_count']
                ), $data_setting);

                $results = $this->model_ne_template->getProducts($data_param);

                foreach ($results as $result) {
                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $template['product_image_width'], $template['product_image_height']);
                    } else {
                        $image = $this->model_tool_image->resize('no_image.jpg', $template['product_image_width'], $template['product_image_height']);
                    }

                    $image = str_replace(' ', '%20', $image);

                    if ($template['product_show_prices']) {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $price = false;
                    }

                    if ($template['product_show_prices'] && (float)$result['special']) {
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $special = false;
                    }

                    if ($template['product_show_savings'] && (float)$result['special']) {
                        $saving = round((($result['price'] - $result['special'])/$result['price'])*100, 0);
                        if ($saving > 0) {
                            $saving = sprintf($template['savings'], $saving);
                        } else {
                            $saving = false;
                        }
                    } else {
                        $saving = false;
                    }

                    $data['products'][] = array(
                        'product_id'  => $result['product_id'],
                        'thumb'   	  => $image,
                        'name'    	  => $result['name'],
                        'price'    	  => str_replace('$', ':usd', $price),
                        'special'     => str_replace('$', ':usd', $special),
                        'saving'      => $saving,
                        'model'       => (isset($result['model']) ? $result['model'] : ''),
                        'description' => ((int)$template['product_description_length'] ? mb_substr(trim(preg_replace("/\s\s+/u", ' ', strip_tags(html_entity_decode(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8')))), 0, (int)$template['product_description_length'], 'UTF-8') . '..' : ''),
                        'href'    	  => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                    );
                }
                unset($result);

                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/latest.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/latest.tpl';
                } else {
                    $this->template = 'default/template/ne/templates/' . $template['uri'] . '/latest.tpl';
                }

                $latest = $this->load->view($this->template, $data);
                $body = $this->replaceTags('<!--//latest//-->', '<!--//end latest//-->', $latest, $body);
            } else {
                $body = $this->replaceTags('<!--//latest//-->', '<!--//end latest//-->', '', $body);
            }

            $data['products'] = array();

            if (isset($schedule['popular']) && $schedule['popular'])
            {
                $data['heading'] = $template['popular'];

                $this->load->model('tool/image');

                $data_param = array_merge(array(
                    'limit' => (int)$template['popular_count']
                ), $data_setting);

                $results = $this->model_ne_template->getBestSellerProducts($data_param);

                foreach ($results as $result) {
                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $template['product_image_width'], $template['product_image_height']);
                    } else {
                        $image = $this->model_tool_image->resize('no_image.jpg', $template['product_image_width'], $template['product_image_height']);
                    }

                    $image = str_replace(' ', '%20', $image);

                    if ($template['product_show_prices']) {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $price = false;
                    }

                    if ($template['product_show_prices'] && (float)$result['special']) {
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $special = false;
                    }

                    if ($template['product_show_savings'] && (float)$result['special']) {
                        $saving = round((($result['price'] - $result['special'])/$result['price'])*100, 0);
                        if ($saving > 0) {
                            $saving = sprintf($template['savings'], $saving);
                        } else {
                            $saving = false;
                        }
                    } else {
                        $saving = false;
                    }

                    $data['products'][] = array(
                        'product_id'  => $result['product_id'],
                        'thumb'   	  => $image,
                        'name'    	  => $result['name'],
                        'price'    	  => str_replace('$', ':usd', $price),
                        'special'     => str_replace('$', ':usd', $special),
                        'saving'      => $saving,
                        'model'       => (isset($result['model']) ? $result['model'] : ''),
                        'description' => ((int)$template['product_description_length'] ? mb_substr(trim(preg_replace("/\s\s+/u", ' ', strip_tags(html_entity_decode(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8')))), 0, (int)$template['product_description_length'], 'UTF-8') . '..' : ''),
                        'href'    	  => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                    );
                }
                unset($result);

                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/popular.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/popular.tpl';
                } else {
                    $this->template = 'default/template/ne/templates/' . $template['uri'] . '/popular.tpl';
                }

                $popular = $this->load->view($this->template, $data);
                $body = $this->replaceTags('<!--//popular//-->', '<!--//end popular//-->', $popular, $body);
            } else {
                $body = $this->replaceTags('<!--//popular//-->', '<!--//end popular//-->', '', $body);
            }

            $data['products'] = array();

            if (isset($schedule['recurrent']) && $schedule['recurrent'] && isset($schedule['random']) && $schedule['random'])
            {
                $data['heading'] = $template['defined'];

                $this->load->model('tool/image');

                $data_param = array_merge(array(
                    'sort'  => 'RAND()',
                    'order' => 'DESC',
                    'start' => 0,
                    'limit' => (int)$schedule['random_count']
                ), $data_setting);

                $results = $this->model_ne_template->getProducts($data_param);

                foreach ($results as $result) {
                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $template['product_image_width'], $template['product_image_height']);
                    } else {
                        $image = $this->model_tool_image->resize('no_image.jpg', $template['product_image_width'], $template['product_image_height']);
                    }

                    $image = str_replace(' ', '%20', $image);

                    if ($template['product_show_prices']) {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $price = false;
                    }

                    if ($template['product_show_prices'] && (float)$result['special']) {
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $special = false;
                    }

                    if ($template['product_show_savings'] && (float)$result['special']) {
                        $saving = round((($result['price'] - $result['special'])/$result['price'])*100, 0);
                        if ($saving > 0) {
                            $saving = sprintf($template['savings'], $saving);
                        } else {
                            $saving = false;
                        }
                    } else {
                        $saving = false;
                    }

                    $data['products'][] = array(
                        'product_id'  => $result['product_id'],
                        'thumb'   	  => $image,
                        'name'    	  => $result['name'],
                        'price'    	  => str_replace('$', ':usd', $price),
                        'special'     => str_replace('$', ':usd', $special),
                        'saving'      => $saving,
                        'model'       => (isset($result['model']) ? $result['model'] : ''),
                        'description' => ((int)$template['product_description_length'] ? mb_substr(trim(preg_replace("/\s\s+/u", ' ', strip_tags(html_entity_decode(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8')))), 0, (int)$template['product_description_length'], 'UTF-8') . '..' : ''),
                        'href'    	  => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                    );
                }
                unset($result);

                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/defined.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/defined.tpl';
                } else {
                    $this->template = 'default/template/ne/templates/' . $template['uri'] . '/defined.tpl';
                }

                $latest = $this->load->view($this->template, $data);
                $body = $this->replaceTags('<!--//defined//-->', '<!--//end defined//-->', $latest, $body);
            }

            $body = str_replace(':usd', '$', $body);
        } else {
            $body = '';
            $subject = '';
        }

        return array('subject' => $subject, 'body' => $body);
    }

    private function replaceTags($start, $end, $text, $source) {
        $result = preg_replace('#('.preg_quote($start).')(.*)('.preg_quote($end).')#si', '$1'.$text.'$3', $source);

        if (preg_last_error() !== PREG_NO_ERROR) {
            $data = explode($start, $source);
            $out = array();
            foreach ($data as $entry) {
                $inner = explode($end, $entry);
                if (count($inner) > 1) {
                    $inner[0] = $text;
                    $out[] = implode($end, $inner);
                } else {
                    $out[] = $entry;
                }
            }
            $result = implode($start, $out);
        }

        return $result;
    }

    private function set_store_config($store_id) {
        $this->config->set('config_store_id', (int)$store_id);

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' OR store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY store_id ASC");

        foreach ($query->rows as $setting) {
            if (empty($setting['serialized'])) {
                $this->config->set($setting['key'], $setting['value']);
            } else {
                $this->config->set($setting['key'], unserialize($setting['value']));
            }
        }

        $this->url = new Url($this->config->get('config_url'), $this->config->get('config_secure') ? $this->config->get('config_ssl') : $this->config->get('config_url'));
    }

}