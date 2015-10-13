<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------

class ControllerNeSubscribers extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('ne/subscribers');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('ne/subscribers');

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

        if (isset($this->request->get['filter_customer_group_id'])) {
            $filter_customer_group_id = $this->request->get['filter_customer_group_id'];
        } else {
            $filter_customer_group_id = null;
        }

        if (isset($this->request->get['filter_newsletter'])) {
            $filter_newsletter = $this->request->get['filter_newsletter'];
        } else {
            $filter_newsletter = null;
        }

        if (isset($this->request->get['filter_store'])) {
            $filter_store = $this->request->get['filter_store'];
        } else {
            $filter_store = null;
        }

        if (isset($this->request->get['filter_language'])) {
            $filter_language = $this->request->get['filter_language'];
        } else {
            $filter_language = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
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

        if (isset($this->request->get['filter_customer_group_id'])) {
            $url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
        }

        if (isset($this->request->get['filter_newsletter'])) {
            $url .= '&filter_newsletter=' . $this->request->get['filter_newsletter'];
        }

        if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store=' . $this->request->get['filter_store'];
        }

        if (isset($this->request->get['filter_language'])) {
            $url .= '&filter_language=' . $this->request->get['filter_language'];
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
            'href' => $this->url->link('ne/subscribers', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['subscribe'] = $this->url->link('ne/subscribers/subscribe', 'token=' . $this->session->data['token'] . $url . '&id=', 'SSL');
        $data['unsubscribe'] = $this->url->link('ne/subscribers/unsubscribe', 'token=' . $this->session->data['token'] . $url . '&id=', 'SSL');

        $this->load->model('sale/customer_group');

        $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

        $data_param = array(
            'filter_name' => $filter_name,
            'filter_email' => $filter_email,
            'filter_customer_group_id' => $filter_customer_group_id,
            'filter_newsletter' => $filter_newsletter,
            'filter_store' => $filter_store,
            'filter_language' => $filter_language,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $customers_total = $this->model_ne_subscribers->getTotal($data_param);

        $data['customers'] = array();

        $results = $this->model_ne_subscribers->getList($data_param);
        $data['customers'] = $results;

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_subscribers'] = $this->language->get('text_subscribers');

        $data['entry_yes'] = $this->language->get('entry_yes');
        $data['entry_no'] = $this->language->get('entry_no');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_email'] = $this->language->get('column_email');
        $data['column_customer_group'] = $this->language->get('column_customer_group');
        $data['column_newsletter'] = $this->language->get('column_newsletter');
        $data['column_actions'] = $this->language->get('column_actions');
        $data['column_store'] = $this->language->get('column_store');
        $data['column_language'] = $this->language->get('column_language');

        $data['button_subscribe'] = $this->language->get('button_subscribe');
        $data['button_unsubscribe'] = $this->language->get('button_unsubscribe');
        $data['button_filter'] = $this->language->get('button_filter');

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

        if (isset($this->request->get['filter_customer_group_id'])) {
            $url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
        }

        if (isset($this->request->get['filter_newsletter'])) {
            $url .= '&filter_newsletter=' . $this->request->get['filter_newsletter'];
        }

        if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store=' . $this->request->get['filter_store'];
        }

        if (isset($this->request->get['filter_language'])) {
            $url .= '&filter_language=' . $this->request->get['filter_language'];
        }

        if ($order == 'ASC') {
            $url .= '&order=' . 'DESC';
        } else {
            $url .= '&order=' . 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('ne/subscribers', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
        $data['sort_email'] = $this->url->link('ne/subscribers', 'token=' . $this->session->data['token'] . '&sort=c.email' . $url, 'SSL');
        $data['sort_customer_group'] = $this->url->link('ne/subscribers', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, 'SSL');
        $data['sort_newsletter'] = $this->url->link('ne/subscribers', 'token=' . $this->session->data['token'] . '&sort=c.newsletter' . $url, 'SSL');
        $data['sort_store'] = $this->url->link('ne/subscribers', 'token=' . $this->session->data['token'] . '&sort=store_id' . $url, 'SSL');
        $data['sort_language'] = $this->url->link('ne/subscribers', 'token=' . $this->session->data['token'] . '&sort=language_code' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . $this->request->get['filter_email'];
        }

        if (isset($this->request->get['filter_customer_group_id'])) {
            $url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
        }

        if (isset($this->request->get['filter_newsletter'])) {
            $url .= '&filter_newsletter=' . $this->request->get['filter_newsletter'];
        }

        if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store=' . $this->request->get['filter_store'];
        }

        if (isset($this->request->get['filter_language'])) {
            $url .= '&filter_language=' . $this->request->get['filter_language'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $customers_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('ne/subscribers', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($customers_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customers_total - $this->config->get('config_limit_admin'))) ? $customers_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customers_total, ceil($customers_total / $this->config->get('config_limit_admin')));

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $data['filter_name'] = $filter_name;
        $data['filter_email'] = $filter_email;
        $data['filter_customer_group_id'] = $filter_customer_group_id;
        $data['filter_newsletter'] = $filter_newsletter;
        $data['filter_store'] = $filter_store;
        $data['filter_language'] = $filter_language;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('ne/subscribers.tpl', $data));
    }

    public function subscribe()
    {

        $this->load->model('ne/subscribers');

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . $this->request->get['filter_email'];
        }

        if (isset($this->request->get['filter_newsletter'])) {
            $url .= '&filter_newsletter=' . $this->request->get['filter_newsletter'];
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

        $this->model_ne_subscribers->subscribe($this->request->get['id']);

        $this->response->redirect($this->url->link('ne/subscribers', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    }

    public function unsubscribe()
    {

        $this->load->model('ne/subscribers');

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . $this->request->get['filter_email'];
        }

        if (isset($this->request->get['filter_newsletter'])) {
            $url .= '&filter_newsletter=' . $this->request->get['filter_newsletter'];
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

        $this->model_ne_subscribers->unsubscribe($this->request->get['id']);

        $this->response->redirect($this->url->link('ne/subscribers', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    }
}