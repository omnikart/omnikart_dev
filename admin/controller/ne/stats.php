<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------

class ControllerNeStats extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('ne/stats');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('ne/stats');

        $this->model_ne_stats->cleanup();

        if (isset($this->request->get['filter_date'])) {
            $filter_date = $this->request->get['filter_date'];
        } else {
            $filter_date = null;
        }

        if (isset($this->request->get['filter_subject'])) {
            $filter_subject = $this->request->get['filter_subject'];
        } else {
            $filter_subject = null;
        }

        if (isset($this->request->get['filter_store'])) {
            $filter_store = $this->request->get['filter_store'];
        } else {
            $filter_store = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'stats_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_date'])) {
            $url .= '&filter_date=' . $this->request->get['filter_date'];
        }

        if (isset($this->request->get['filter_subject'])) {
            $url .= '&filter_subject=' . $this->request->get['filter_subject'];
        }

        if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store=' . $this->request->get['filter_store'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('ne/stats', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['detail'] = $this->url->link('ne/stats/detail', 'token=' . $this->session->data['token'] . '&id=', 'SSL');
        $data['delete'] = $this->url->link('ne/stats/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data_param = array(
            'filter_date' => $filter_date,
            'filter_subject' => $filter_subject,
            'filter_store' => $filter_store,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $total = $this->model_ne_stats->getTotal($data_param);

        $data['stats'] = array();

        $results = $this->model_ne_stats->getList($data_param);

        foreach ($results as $result) {
            $data['stats'][] = array_merge($result, array(
                'queue' => ($result['queue'] == $result['recipients']) ? $result['recipients'] : sprintf($this->language->get('entry_text_queued'), $result['queue'], $result['recipients'])
            ));
        }
        unset($result);

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_stats'] = $this->language->get('text_stats');

        $data['column_date'] = $this->language->get('column_date');
        $data['column_subject'] = $this->language->get('column_subject');
        $data['column_recipients'] = $this->language->get('column_recipients');
        $data['column_views'] = $this->language->get('column_views');
        $data['column_actions'] = $this->language->get('column_actions');
        $data['column_store'] = $this->language->get('column_store');

        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_details'] = $this->language->get('button_details');

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

        $url = '';

        if (isset($this->request->get['filter_date'])) {
            $url .= '&filter_date=' . $this->request->get['filter_date'];
        }

        if (isset($this->request->get['filter_subject'])) {
            $url .= '&filter_subject=' . $this->request->get['filter_subject'];
        }

        if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store=' . $this->request->get['filter_store'];
        }

        if ($order == 'ASC') {
            $url .= '&order=' . 'DESC';
        } else {
            $url .= '&order=' . 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_date'] = $this->url->link('ne/stats', 'token=' . $this->session->data['token'] . '&sort=datetime' . $url, 'SSL');
        $data['sort_subject'] = $this->url->link('ne/stats', 'token=' . $this->session->data['token'] . '&sort=subject' . $url, 'SSL');
        $data['sort_recipients'] = $this->url->link('ne/stats', 'token=' . $this->session->data['token'] . '&sort=recipients' . $url, 'SSL');
        $data['sort_views'] = $this->url->link('ne/stats', 'token=' . $this->session->data['token'] . '&sort=views' . $url, 'SSL');
        $data['sort_store'] = $this->url->link('ne/stats', 'token=' . $this->session->data['token'] . '&sort=store_id' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_date'])) {
            $url .= '&filter_date=' . $this->request->get['filter_date'];
        }

        if (isset($this->request->get['filter_subject'])) {
            $url .= '&filter_subject=' . $this->request->get['filter_subject'];
        }

        if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store=' . $this->request->get['filter_store'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $data['view_url'] = 'index.php?route=ne/show&id=';

        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('ne/stats', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $data['store_url'] = (defined('HTTPS_CATALOG') ? HTTPS_CATALOG : HTTP_CATALOG);
        } else {
            $data['store_url'] = HTTP_CATALOG;
        }

        $data['filter_date'] = $filter_date;
        $data['filter_subject'] = $filter_subject;
        $data['filter_store'] = $filter_store;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('ne/stats.tpl', $data));
    }

    public function detail()
    {
        if (isset($this->request->get['id'])) {
            $stats_id = $this->request->get['id'];
        } else {
            $this->response->redirect($this->url->link('ne/stats', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->load->language('ne/stats');

        $this->load->model('ne/stats');

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_email'])) {
            $filter_email = $this->request->get['filter_email'];
        } else {
            $filter_email = null;
        }

        if (isset($this->request->get['filter_success'])) {
            $filter_success = $this->request->get['filter_success'];
        } else {
            $filter_success = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . $this->request->get['filter_email'];
        }

        if (isset($this->request->get['filter_success'])) {
            $url .= '&filter_success=' . $this->request->get['filter_success'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['detail_link'] = $this->url->link('ne/stats/detail_recipient', 'token=' . $this->session->data['token'] . $url . '&id=', 'SSL');
        $data['cancel'] = $this->url->link('ne/stats', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $detail = $this->model_ne_stats->detail($stats_id);
        $data['detail'] = $detail;

        $this->document->setTitle($this->language->get('heading_title'));
        $data['heading_title'] = $this->language->get('heading_title');

        $data['entry_recipients'] = $this->language->get('entry_recipients');
        $data['entry_total_recipients'] = $this->language->get('entry_total_recipients');
        $data['entry_total_failed'] = $this->language->get('entry_total_failed');
        $data['entry_total_views'] = $this->language->get('entry_total_views');
        $data['entry_sent'] = $this->language->get('entry_sent');
        $data['entry_read'] = $this->language->get('entry_read');
        $data['entry_unsubscribe_clicks'] = $this->language->get('entry_unsubscribe_clicks');
        $data['entry_attachements'] = $this->language->get('entry_attachements');
        $data['entry_chart'] = $this->language->get('entry_chart');
        $data['entry_url'] = $this->language->get('entry_url');
        $data['entry_clicks'] = $this->language->get('entry_clicks');
        $data['entry_heading'] = sprintf($this->language->get('entry_heading'), $detail['subject']);
        $data['entry_track'] = $this->language->get('entry_track');
        $data['entry_yes'] = $this->language->get('entry_yes');
        $data['entry_no'] = $this->language->get('entry_no');

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_sent'] = $this->language->get('text_sent');
        $data['text_time'] = $this->language->get('text_time');
        $data['text_views'] = $this->language->get('text_views');
        $data['text_of'] = $this->language->get('text_of');

        $data['button_details'] = $this->language->get('button_details');
        $data['button_hide_details'] = $this->language->get('button_hide_details');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['column_actions'] = $this->language->get('column_actions');
        $data['column_email'] = $this->language->get('column_email');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_views'] = $this->language->get('column_views');
        $data['column_clicks'] = $this->language->get('column_clicks');
        $data['column_success'] = $this->language->get('column_success');

        $data['attachements'] = $this->model_ne_stats->getAttachements($detail['history_id']);

        $data['recipients'] = array();

        $data_param = array(
            'history_id' => $detail['history_id']
        );

        if ($detail['to'] == 'marketing' || $detail['to'] == 'marketing_all') {
            $recipients_total = $this->model_ne_stats->getRecipientsMarketingTotal($data_param);
        } elseif ($detail['to'] == 'subscriber' || $detail['to'] == 'all') {
            $recipients_total = $this->model_ne_stats->getRecipientsMarketingTotal($data_param) + $this->model_ne_stats->getRecipientsTotal($data_param);
        } else {
            $recipients_total = $this->model_ne_stats->getRecipientsTotal($data_param);
        }

        $data['recipients_total'] = $recipients_total;
        $data['failed_total'] = $this->model_ne_stats->getFailedTotal($detail['history_id']);

        $data_param = array(
            'history_id' => $detail['history_id'],
            'filter_name' => $filter_name,
            'filter_email' => $filter_email,
            'filter_success' => $filter_success,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        if ($detail['to'] == 'marketing' || $detail['to'] == 'marketing_all') {
            $recipients_total = $this->model_ne_stats->getRecipientsMarketingTotal($data_param);
            $results = $this->model_ne_stats->getRecipientsMarketingList($data_param);
        } elseif ($detail['to'] == 'subscriber' || $detail['to'] == 'all') {
            $recipients_total = $this->model_ne_stats->getRecipientsMarketingTotal($data_param) + $this->model_ne_stats->getRecipientsTotal($data_param);
            $results = $this->model_ne_stats->getRecipientsAllList($data_param);
        } else {
            $recipients_total = $this->model_ne_stats->getRecipientsTotal($data_param);
            $results = $this->model_ne_stats->getRecipientsList($data_param);
        }

        $data['recipients'] = $results;
        $data['views_total'] = $detail['views'];

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

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . $this->request->get['filter_email'];
        }

        if (isset($this->request->get['filter_success'])) {
            $url .= '&filter_success=' . $this->request->get['filter_success'];
        }

        if ($order == 'ASC') {
            $url .= '&order=' . 'DESC';
        } else {
            $url .= '&order=' . 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('ne/stats/detail', 'token=' . $this->session->data['token'] . '&id=' . $stats_id . '&sort=name' . $url, 'SSL');
        $data['sort_email'] = $this->url->link('ne/stats/detail', 'token=' . $this->session->data['token'] . '&id=' . $stats_id . '&sort=c.email' . $url, 'SSL');
        $data['sort_views'] = $this->url->link('ne/stats/detail', 'token=' . $this->session->data['token'] . '&id=' . $stats_id . '&sort=s.views' . $url, 'SSL');
        $data['sort_clicks'] = $this->url->link('ne/stats/detail', 'token=' . $this->session->data['token'] . '&id=' . $stats_id . '&sort=clicks' . $url, 'SSL');
        $data['sort_success'] = $this->url->link('ne/stats/detail', 'token=' . $this->session->data['token'] . '&id=' . $stats_id . '&sort=success' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . $this->request->get['filter_email'];
        }

        if (isset($this->request->get['filter_success'])) {
            $url .= '&filter_success=' . $this->request->get['filter_success'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $pagination = new Pagination();
        $pagination->total = $recipients_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('ne/stats/detail', 'token=' . $this->session->data['token'] . '&id=' . $stats_id . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($recipients_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($recipients_total - $this->config->get('config_limit_admin'))) ? $recipients_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $recipients_total, ceil($recipients_total / $this->config->get('config_limit_admin')));

        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $data['store_url'] = (defined('HTTPS_CATALOG') ? HTTPS_CATALOG : HTTP_CATALOG);
        } else {
            $data['store_url'] = HTTP_CATALOG;
        }

        $data['filter_name'] = $filter_name;
        $data['filter_email'] = $filter_email;
        $data['filter_success'] = $filter_success;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('ne/stats_detail.tpl', $data));
    }

    public function detail_recipient()
    {
        if (empty($this->request->server['HTTP_X_REQUESTED_WITH']) || strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
            $this->response->redirect($this->url->link('ne/stats', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        if (isset($this->request->get['id'])) {
            $stats_recipient_id = $this->request->get['id'];
        } else {
            $this->response->redirect($this->url->link('ne/stats', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->load->language('ne/stats');
        $data['entry_url'] = $this->language->get('entry_url');
        $data['entry_clicks'] = $this->language->get('entry_clicks');
        $data['entry_track'] = $this->language->get('entry_track');

        $data['text_no_data'] = $this->language->get('text_no_data');

        $this->load->model('ne/stats');

        $data['tracks'] = $this->model_ne_stats->getClicks($stats_recipient_id);

        $this->response->setOutput($this->load->view('ne/stats_detail_recipient.tpl', $data));
    }

    public function delete()
    {
        $this->load->language('ne/stats');
        $this->load->model('ne/stats');

        $url = '';

        if (isset($this->request->get['filter_date'])) {
            $url .= '&filter_date=' . $this->request->get['filter_date'];
        }

        if (isset($this->request->get['filter_subject'])) {
            $url .= '&filter_subject=' . $this->request->get['filter_subject'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->post['selected']) && $this->validate()) {
            foreach ($this->request->post['selected'] as $stats_id) {
                $this->model_ne_stats->delete($stats_id);
            }
            $this->session->data['success'] = $this->language->get('text_success');
        }

        $this->response->redirect($this->url->link('ne/stats', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    }

    public function chart()
    {
        if (isset($this->request->get['id'])) {
            $stats_id = $this->request->get['id'];
        } else {
            $stats_id = 0;
        }

        $json = array();

        if ($stats_id) {
            $this->load->language('ne/stats');

            $this->load->model('ne/stats');

            $json['views'] = array();
            $json['xaxis'] = array();
            $json['days'] = array();

            $json['views']['label'] = $this->language->get('text_views');
            $json['views']['data'] = array();

            $detail = $this->model_ne_stats->timeline($stats_id);

            $index = 1;
            foreach ($detail['timeline'] as $key => $value) {
                $json['views']['data'][] = array($index, $value);
                $json['xaxis'][] = array($index, '');
                $json['days'][] = $key;

                $index++;
            }

            if ($json['xaxis']) {
                $prev_day = strtotime($key . ' -1 day');

                array_unshift($json['days'], date('Y-m-d', $prev_day));
                array_unshift($json['xaxis'], array(0, ''));
                array_unshift($json['views']['data'], array(0, 0));

                $count = count($json['xaxis']);

                if ($count < 14) {
                    for ($i = 1; $i <= (14 - $count); $i++) {
                        $next_day = strtotime($key . ' +' . $i . ' day');
                        array_push($json['days'], date('Y-m-d', $next_day));
                        array_push($json['xaxis'], array($index, ''));
                        array_push($json['views']['data'], array($index, 0));
                        $index++;
                    }
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function validate()
    {
        if (!$this->user->hasPermission('modify', 'ne/stats')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}