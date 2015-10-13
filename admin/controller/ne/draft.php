<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------

class ControllerNeDraft extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('sale/contact');
        $this->load->language('ne/draft');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('ne/draft');

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

        if (isset($this->request->get['filter_to'])) {
            $filter_to = $this->request->get['filter_to'];
        } else {
            $filter_to = null;
        }

        if (isset($this->request->get['filter_store'])) {
            $filter_store = $this->request->get['filter_store'];
        } else {
            $filter_store = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'draft_id';
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

        if (isset($this->request->get['filter_to'])) {
            $url .= '&filter_to=' . $this->request->get['filter_to'];
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

        $data['delete'] = $this->url->link('ne/draft/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data_param = array(
            'filter_date' => $filter_date,
            'filter_subject' => $filter_subject,
            'filter_to' => $filter_to,
            'filter_store' => $filter_store,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $total = $this->model_ne_draft->getTotal($data_param);

        $data['draft'] = array();

        $results = $this->model_ne_draft->getList($data_param);

        foreach ($results as $result) {
            $data['draft'][] = array_merge($result, array(
                'selected' => isset($this->request->post['selected']) && is_array($this->request->post['selected']) && in_array($result['draft_id'], $this->request->post['selected'])
            ));
        }
        unset($result);

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['column_subject'] = $this->language->get('column_subject');
        $data['column_date'] = $this->language->get('column_date');
        $data['column_to'] = $this->language->get('column_to');
        $data['column_actions'] = $this->language->get('column_actions');
        $data['column_store'] = $this->language->get('column_store');

        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_edit'] = $this->language->get('button_edit');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_marketing'] = $this->language->get('text_marketing');
        $data['text_marketing_all'] = $this->language->get('text_marketing_all');
        $data['text_subscriber_all'] = $this->language->get('text_subscriber_all');
        $data['text_all'] = $this->language->get('text_all');
        $data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_customer_all'] = $this->language->get('text_customer_all');
        $data['text_customer'] = $this->language->get('text_customer');
        $data['text_customer_group'] = $this->language->get('text_customer_group');
        $data['text_affiliate_all'] = $this->language->get('text_affiliate_all');
        $data['text_affiliate'] = $this->language->get('text_affiliate');
        $data['text_product'] = $this->language->get('text_product');
        $data['text_view'] = $this->language->get('text_view');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_draft_newsletters'] = $this->language->get('text_draft_newsletters');

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

        if (isset($this->request->get['filter_to'])) {
            $url .= '&filter_to=' . $this->request->get['filter_to'];
        }

        if ($order == 'ASC') {
            $url .= '&order=' . 'DESC';
        } else {
            $url .= '&order=' . 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_date'] = $this->url->link('ne/draft', 'token=' . $this->session->data['token'] . '&sort=datetime' . $url, 'SSL');
        $data['sort_subject'] = $this->url->link('ne/draft', 'token=' . $this->session->data['token'] . '&sort=subject' . $url, 'SSL');
        $data['sort_to'] = $this->url->link('ne/draft', 'token=' . $this->session->data['token'] . '&sort=to' . $url, 'SSL');
        $data['sort_store'] = $this->url->link('ne/draft', 'token=' . $this->session->data['token'] . '&sort=store_id' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_date'])) {
            $url .= '&filter_date=' . $this->request->get['filter_date'];
        }

        if (isset($this->request->get['filter_subject'])) {
            $url .= '&filter_subject=' . $this->request->get['filter_subject'];
        }

        if (isset($this->request->get['filter_to'])) {
            $url .= '&filter_to=' . $this->request->get['filter_to'];
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

        $data['detail'] = $this->url->link('ne/draft/detail', 'token=' . $this->session->data['token'] . $url . '&id=', 'SSL');

        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('ne/draft', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        $data['filter_date'] = $filter_date;
        $data['filter_subject'] = $filter_subject;
        $data['filter_to'] = $filter_to;
        $data['filter_store'] = $filter_store;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('ne/draft.tpl', $data));
    }

    public function detail()
    {
        if (isset($this->request->get['id'])) {

            $url = '';

            if (isset($this->request->get['filter_date'])) {
                $url .= '&filter_date=' . $this->request->get['filter_date'];
            }

            if (isset($this->request->get['filter_subject'])) {
                $url .= '&filter_subject=' . $this->request->get['filter_subject'];
            }

            if (isset($this->request->get['filter_to'])) {
                $url .= '&filter_to=' . $this->request->get['filter_to'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $this->session->data['ne_back'] = $url;

            $this->response->redirect($this->url->link('ne/newsletter', 'token=' . $this->session->data['token'] . '&id=' . (int)$this->request->get['id'], 'SSL'));
        } else {
            $this->response->redirect($this->url->link('ne/draft', 'token=' . $this->session->data['token'], 'SSL'));
        }
    }

    public function delete()
    {
        $this->load->language('ne/draft');
        $this->load->model('ne/draft');

        $url = '';

        if (isset($this->request->get['filter_date'])) {
            $url .= '&filter_date=' . $this->request->get['filter_date'];
        }

        if (isset($this->request->get['filter_subject'])) {
            $url .= '&filter_subject=' . $this->request->get['filter_subject'];
        }

        if (isset($this->request->get['filter_to'])) {
            $url .= '&filter_to=' . $this->request->get['filter_to'];
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
            foreach ($this->request->post['selected'] as $draft_id) {
                $this->model_ne_draft->delete($draft_id);
            }
            $this->session->data['success'] = $this->language->get('text_success');
        }

        $this->response->redirect($this->url->link('ne/draft', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    }

    private function validate()
    {
        if (!$this->user->hasPermission('modify', 'ne/draft')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}