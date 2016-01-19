<?php
class ControllerExtensionBlog extends Controller {
	
	private $data = array();
	private $error = array();
	private $pid = '';
	private $cid = '';
	private $comment_id = '';

	private $post_limit 	= 20;
	private $cat_limit 		= 20;
	private $comment_limit 	= 20;

	/**
	 * Post Section Start
	 *=============================================
	 */
	public function index() {

		$this->language->load('extension/blog/post');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->getPostList();	

	}

	protected function getPostList() {

		$this->load->helper('blog');
		$this->load->model('extension/blog/setting');
		$settings = $this->model_extension_blog_setting->settings();
		$config = setting($settings);

		if (isset($this->request->get['filter_title'])) {
			$filter_title = $this->request->get['filter_title'];
		} else {
			$filter_title = null;
		}

		if (isset($this->request->get['filter_author'])) {
			$filter_author = $this->request->get['filter_author'];
		} else {
			$filter_author = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.ID';
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

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/blog', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		// Buttons URL
		$data['post_create_link'] = $this->url->link('extension/blog/post_create', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['post_edit_link'] = $this->url->link('extension/blog/post_edit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['post_delete_multiple'] = $this->url->link('extension/blog/post_delete_multiple', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['post_delete_link'] = $this->url->link('extension/blog/post_delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$limit = isset($config['post_limit_admin']) ? $config['post_limit_admin'] : $this->post_limit;	

		$filter_data = array(
			'filter_title'	  => $filter_title,
			'filter_author'	  => $filter_author,
			'filter_status'   => $filter_status,
			'sort' 			  => $sort,
			'order' 		  => $order,
			'start' 		  => ($page - 1) * $limit,
			'limit'			  => $limit
		);

		$this->load->model('tool/image');

		$this->load->model('extension/blog/post');

		$total_post = $this->model_extension_blog_post->getTotalPost($filter_data);
		$posts = $this->model_extension_blog_post->getPosts($filter_data);

		if(is_array($posts) && count($posts) > 0) {
			foreach ($posts as $post) {
				if (is_file(DIR_IMAGE . $post['post_thumb'])) {
					$image = $this->model_tool_image->resize($post['post_thumb'], 40, 40);
				} else {
					$image = $this->model_tool_image->resize('no_image.png', 40, 40);
				}

				$data['posts'][] = array(
					'ID' 		 	=> $post['ID'],
					'image'      	=> $image,
					'title'    		=> $post['title'],
					'date_added'    => $post['date_added'],
					'post_author'   => $post['post_author'],
					'post_status'   => $post['post_status'],
				);
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');

		// Top Menubar Label
		$data['post_menu'] 		= $this->language->get('post');
		$data['category_menu'] 	= $this->language->get('category');
		$data['comment_menu'] 	= $this->language->get('comment');
		$data['setting_menu'] 	= $this->language->get('setting');
		$data['help_menu'] 		= $this->language->get('help');

		// Button Label
		$data['create_btn'] = $this->language->get('create_btn');
		$data['delete_btn'] = $this->language->get('delete_btn');
		$data['create_btn_tooltip'] = $this->language->get('create_btn_tooltip');
		$data['delete_btn_tooltip'] = $this->language->get('delete_btn_tooltip');
		$data['button_filter'] = $this->language->get('button_filter');

		// Filter box text
		$data['filter_text_title'] = $this->language->get('filter_text_title');
		$data['filter_text_author'] = $this->language->get('filter_text_author');
		$data['filter_text_status'] = $this->language->get('filter_text_status');
		$data['filter_text_publish'] = $this->language->get('filter_text_publish');
		$data['filter_text_unpublish'] = $this->language->get('filter_text_unpublish');

		// Table Column Label
		$data['col_thumb'] = $this->language->get('col_thumb');
		$data['col_postTitle'] = $this->language->get('col_postTitle');
		$data['col_author'] = $this->language->get('col_author');
		$data['col_date'] = $this->language->get('col_date');
		$data['col_status'] = $this->language->get('col_status');
		$data['col_action'] = $this->language->get('col_action');

		// Text
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_not_found'] = $this->language->get('text_not_found');

		$data['token'] = $this->session->data['token']; 	

		// Alert Message
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
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

		$url = '';

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . $this->request->get['filter_author'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_title'] = $this->url->link('extension/blog', 'token=' . $this->session->data['token'] . '&sort=pd.title' . $url, 'SSL');
		$data['sort_date'] = $this->url->link('extension/blog', 'token=' . $this->session->data['token'] . '&sort=p.date_added' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		// Pagination
		$pagination = new Pagination();
		$pagination->total = $total_post;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('extension/blog', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_post) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total_post - $limit)) ? $total_post : ((($page - 1) * $limit) + $limit), $total_post, ceil($total_post / $limit));
		
		$data['filter_title'] = $filter_title;
		$data['filter_author'] = $filter_author;
		$data['filter_status'] = $filter_status;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/blog/post.tpl', $data));
	}

	public function post_create() {
		$this->language->load('extension/blog/post_form');

		$this->document->setTitle($this->language->get('heading_title_create'));
		$this->data['heading_title'] = $this->language->get('heading_title_create');

		// Button Text
		$this->data['save_btn'] = $this->language->get('save_btn_create');
		$this->data['save_btn_tooltip'] = $this->language->get('save_btn_tooltip_create');

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('base_title'),
			'href' => $this->url->link('extension/blog', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_create'),
			'href' => $this->url->link('extension/blog/post_create', 'token=' . $this->session->data['token'], 'SSL')
		);

		$url = '';

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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

		$this->load->model('extension/blog/postmodify');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$post_id = $this->model_extension_blog_postmodify->createPost($this->request->post);
			if($post_id) {
				$post_review_link = '<a target="_blink" href="' . HTTP_CATALOG . 'index.php?route=blog/single&pid=' . $post_id . '">View</a>';
				$this->session->data['success'] = $this->language->get('text_success_create') . '&nbsp;|&nbsp;'.$post_review_link;
			} else {
				$this->session->data['error_warning'] = $this->language->get('text_error_create');
			}	
		
			$this->response->redirect($this->url->link('extension/blog/post_edit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->data['action'] = $this->url->link('extension/blog/post_create', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['cancel'] = $this->url->link('extension/blog', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->getForm();
	}

	protected function check_post_id() {
		if(!isset($this->request->get['pid'])) {
			$this->response->redirect($this->url->link('extension/blog', 'token=' . $this->session->data['token'], 'SSL'));
		}

		return true;
	}

	public function post_edit() {

		$this->check_post_id();

		$this->pid = $this->request->get['pid'];

		$this->language->load('extension/blog/post_form');

		$this->document->setTitle($this->language->get('heading_title_edit'));
		$this->data['heading_title'] = $this->language->get('heading_title_edit');

		$this->data['save_btn'] = $this->language->get('save_btn_edit');
		$this->data['save_btn_tooltip'] = $this->language->get('save_btn_tooltip_edit');

		// Breadcrumb
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('base_title'),
			'href' => $this->url->link('extension/blog', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_edit'),
			'href' => $this->url->link('extension/blog/post_edit', 'token=' . $this->session->data['token'] . '&pid='. $this->pid, 'SSL')
		);

		$url = '';

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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

		$this->load->model('extension/blog/postmodify');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_extension_blog_postmodify->editPost($this->pid, $this->request->post);

			$post_review_link = '<a target="_blink" href="' . HTTP_CATALOG . 'index.php?route=blog/single&pid=' . $this->pid . '">View</a>';
			$this->session->data['success'] = $this->language->get('text_success_edit') . '&nbsp;|&nbsp;'.$post_review_link;
		
			$this->response->redirect($this->url->link('extension/blog', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->data['action'] = $this->url->link('extension/blog/post_edit', 'token=' . $this->session->data['token'] . '&pid=' . $this->pid. $url, 'SSL');
		$this->data['cancel'] = $this->url->link('extension/blog', 'token=' . $this->session->data['token'] . '&pid=' . $this->pid . $url, 'SSL');

		$this->getForm();
	}

	public function post_delete() {

		$this->check_post_id();

		$this->pid = $this->request->get['pid'];

		$this->language->load('extension/blog/post');
		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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

		$this->load->model('extension/blog/postmodify');
		if ($this->pid && $this->validateDelete()) {
			if($this->model_extension_blog_postmodify->deletePost($this->pid)) {
				$this->session->data['success'] = $this->language->get('text_success_delete');
			} else {
				$this->session->data['error_warning'] = $this->language->get('text_error_delete');
			}		

			$this->response->redirect($this->url->link('extension/blog', 'token=' . $this->session->data['token'] . '&pid=' . $this->pid . $url, 'SSL'));
		}	

		$this->getPostList();
	}

	public function post_delete_multiple() {

		$this->language->load('extension/blog/post');
		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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

		$this->load->model('extension/blog/postmodify');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			$success = $this->language->get('text_success_delete') . ' Post ID = ';
			$error = $this->language->get('text_error_delete') . ' Post ID = ';
			$inc = 1;
			foreach ($this->request->post['selected'] as $post_id) {
				// $this->model_catalog_post->deletepost($post_id);
				$this->pid = $post_id;
				if($this->model_extension_blog_postmodify->deletePost($this->pid)) {
					if($inc == 1) {
						$success .= $this->pid;
					} else {
						$success .= ', ' . $this->pid;
					}
				} else {
					if($inc == 1) {
						$error .= $this->pid . ' ';
					} else {
						$error .= ', ' . $this->pid;
					}
				}	
				$inc++;
			}
			$this->session->data['success'] = $success;
			$this->session->data['error_warning'] = $error;

			$this->response->redirect($this->url->link('extension/blog', 'token=' . $this->session->data['token'] . '&pid=' . $this->pid . $url, 'SSL'));
		}		

		$this->getPostList();
	}

	protected function getForm() {

		$this->load->model('extension/blog/post');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->error['date_available'])) {
			$this->data['error_date_available'] = $this->error['date_available'];
		} else {
			$this->data['error_date_available'] = '';
		}
		
		if (isset($this->error['keyword'])) {
			$this->data['error_keyword'] = $this->error['keyword'];
		} else {
			$this->data['error_keyword'] = '';
		}

		// Menubar Label
		$this->data['post_menu'] = $this->language->get('post');
		$this->data['category_menu'] = $this->language->get('category');
		$this->data['comment_menu'] = $this->language->get('comment');
		$this->data['setting_menu'] = $this->language->get('setting');
		$this->data['help_menu'] = $this->language->get('help');

		// Tab Label
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_relation'] = $this->language->get('tab_relation');
		$this->data['tab_image'] = $this->language->get('tab_image');
		$this->data['tab_design'] = $this->language->get('tab_design');


		// Button
		$this->data['cancel_btn'] = $this->language->get('cancel_btn');
		$this->data['cancel_btn_tooltip'] = $this->language->get('cancel_btn_tooltip');
		$this->data['image_add'] = $this->language->get('image_add');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['text_default'] = $this->language->get('text_default');

		// Form Label
		$this->data['entry_postTitle'] = $this->language->get('entry_postTitle');
		$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_category_default'] = $this->language->get('entry_category_default');
		$this->data['entry_filter'] = $this->language->get('entry_filter');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_date_available'] = $this->language->get('entry_date_available');
		$this->data['entry_author'] = $this->language->get('entry_author');
		$this->data['entry_content'] = $this->language->get('entry_content');
		$this->data['entry_excerpt'] = $this->language->get('entry_excerpt');
		$this->data['entry_meta_desc'] = $this->language->get('entry_meta_desc');
		$this->data['entry_meta_key'] = $this->language->get('entry_meta_key');
		$this->data['entry_comment'] = $this->language->get('entry_comment');
		$this->data['entry_thumb'] = $this->language->get('entry_thumb');
		$this->data['entry_date'] = $this->language->get('entry_date');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_tag'] = $this->language->get('entry_tag');
		$this->data['entry_related'] = $this->language->get('entry_related');

		// Table column text
		$this->data['col_image'] = $this->language->get('col_image');
		$this->data['col_order'] = $this->language->get('col_order');
		$this->data['col_action'] = $this->language->get('col_action');
		$this->data['col_store'] = $this->language->get('col_store');
		$this->data['col_layout'] = $this->language->get('col_layout');

		$this->data['help_tags'] = $this->language->get('help_tags');
		$this->data['help_keyword'] = $this->language->get('help_keyword');
		$this->data['help_autocomplete'] = $this->language->get('help_autocomplete');

		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		if ($this->pid && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$post_info = $this->model_extension_blog_post->getPost($this->pid);
		}

		$this->data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['post_description'])) {
			$this->data['post_description'] = $this->request->post['post_description'];
		} elseif ($this->pid) {
			$this->data['post_description'] = $this->model_extension_blog_post->getPostDescriptions($this->pid);
		} else {
			$this->data['post_description'] = array();
		}

		$this->load->model('extension/blog/category');

		if (isset($this->request->post['post_category'])) {
			$categories = $this->request->post['post_category'];
		} elseif ($this->pid) {
			$categories = $this->model_extension_blog_post->getPostCategories($this->pid);
		} else {
			$categories = array();
		}

		$this->data['post_categories'] = array();

		foreach ($categories as $category_id) {
			$category_info = $this->model_extension_blog_category->getCategory($category_id);
			if ($category_info) {
				$this->data['post_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
				);
			}
		}

		$this->load->model('catalog/filter');

		if (isset($this->request->post['post_filter'])) {
			$filters = $this->request->post['post_filter'];
		} elseif ($this->pid) {
			$filters = $this->model_extension_blog_post->getpostFilters($this->pid);
		} else {
			$filters = array();
		}

		$this->data['post_filters'] = array();

		foreach ($filters as $filter_id) {
			$filter_info = $this->model_catalog_filter->getFilter($filter_id);

			if ($filter_info) {
				$this->data['post_filters'][] = array(
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
				);
			}
		}

		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($post_info)) {
			$this->data['keyword'] = $post_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}

		if (isset($this->request->post['date_available'])) {
			$this->data['date_available'] = $this->request->post['date_available'];
		} elseif (!empty($post_info)) {
			$this->data['date_available'] = ($post_info['date_available'] != '0000-00-00') ? $post_info['date_available'] : '';
		} else {
			$this->data['date_available'] = date('Y-m-d');
		}

		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($post_info)) {
			$this->data['sort_order'] = $post_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($category_info)) {
			$this->data['status'] = $post_info['post_status'];
		} else {
			$this->data['status'] = '';
		}

		// Post Layout
		if (isset($this->request->post['post_layout'])) {
			$this->data['post_layout'] = $this->request->post['post_layout'];
		} elseif ($this->pid) {
			$this->data['post_layout'] = $this->model_extension_blog_post->getPostLayouts($this->pid);
		} else {
			$this->data['post_layout'] = array();
		}

		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();		

		// Post Store
		$this->load->model('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['post_store'])) {
			$this->data['post_store'] = $this->request->post['post_store'];
		} elseif (isset($this->request->get['product_id'])) {
			$this->data['post_store'] = $this->model_extension_blog_post->getPostStores($this->pid);
		} else {
			$this->data['post_store'] = array(0);
		}

		// Related product
		if (isset($this->request->post['related_product'])) {
			$products = $this->request->post['related_product'];
		} elseif (isset($this->pid) && $this->pid) {
			$products = $this->model_extension_blog_post->productByPostid(array('post_id'=>$this->pid));
		} else {
			$products = array();
		}

		$this->data['related_products'] = array();

		$this->load->model('catalog/product');
		if(!empty($products)) {
			for ($i =0; $i < count($products); $i++) {
				$related_product = $this->model_catalog_product->getProduct($products[$i]);
				if ($related_product) {
					$this->data['related_products'][] = array(
						'product_id' => $related_product['product_id'],
						'name'       => $related_product['name']
					);
				}
			}
		}

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($post_info)) {
			$this->data['image'] = $post_info['post_thumb'];
		} else {
			$this->data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($post_info) && is_file(DIR_IMAGE . $post_info['post_thumb'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($post_info['post_thumb'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['post_image'])) {
			$post_images = $this->request->post['post_image'];
		} elseif ($this->pid) {
			$post_images = $this->model_extension_blog_post->getPostImages($this->pid,'image');
		} else {
			$post_images = array();
		}

		$this->data['post_images'] = array();
		$this->load->model('tool/image');
		if(count($post_images) > 0) {
			foreach ($post_images as $post_image) {
				if (is_file(DIR_IMAGE . $post_image['meta_value'])) {
					$image = $post_image['meta_value'];
					$thumb = $post_image['meta_value'];
				} else {
					$image = '';
					$thumb = 'no_image.png';
				}

				$this->data['post_images'][] = array(
					'image'      => $image,
					'thumb'      => $this->model_tool_image->resize($thumb, 100, 100),
					'sort_order' => $post_image['sort_order']
				);
			}
		}
		
		$this->data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$this->response->setOutput($this->load->view('extension/blog/post_form.tpl', $this->data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/blog')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->data['form_error'] = array();

		foreach ($this->request->post['post_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 255)) {
				$this->data['form_error'][$language_id]['title'] = $this->language->get('error_title');
			}

			if (utf8_strlen(strip_tags(html_entity_decode($value['content']))) < 10) {
				$this->data['form_error'][$language_id]['content'] = $this->language->get('error_content');
			}
		}

		if ((!isset($this->request->post['post_category']) || empty($this->request->post['post_category']))) {
			$this->data['form_error']['category'] = $this->language->get('error_category');
		}

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['pid']) && $url_alias_info['query'] != 'post_id=' . $this->request->get['pid']) {
				$this->data['form_error']['keyword'] = $this->language->get('error_keyword');
			}

			if ($url_alias_info && !isset($this->request->get['pid'])) {
				$this->data['form_error']['keyword'] = $this->language->get('error_keyword');
			}
		}

		if ($this->data['form_error'] && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}


	/**
	 * Category Section
	 *=================================================
	 */
	public function category() {

		$this->language->load('extension/blog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->getCatList();
	}

	protected function getCatList() {
		$this->load->helper('blog');
		$this->load->model('extension/blog/setting');
		$settings = $this->model_extension_blog_setting->settings();
		$config = setting($settings);

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'c1.date_added';
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
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		// Breadcrumb
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/blog/category', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		// Buttons
		$data['category_create_link'] = $this->url->link('extension/blog/category_create', 'token=' . $this->session->data['token'], 'SSL');
		$data['category_edit_link'] = $this->url->link('extension/blog/category_edit', 'token=' . $this->session->data['token'], 'SSL');
		$data['category_delete_multiple'] = $this->url->link('extension/blog/category_delete_multiple', 'token=' . $this->session->data['token'], 'SSL');
		$data['category_delete_link'] = $this->url->link('extension/blog/category_delete', 'token=' . $this->session->data['token'], 'SSL');
		$data['token'] = $this->session->data['token'];

		$limit = isset($config['category_limit_admin']) ? $config['category_limit_admin'] : $this->category_limit;

		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_status'   => $filter_status,
			'sort' 			  => $sort,
			'order' 		  => $order,
			'start' 		  => ($page - 1) * $limit,
			'limit'			  => $limit
		);

		$this->load->model('tool/image');

		$this->load->model('extension/blog/category');

		$total_category = $this->model_extension_blog_category->getTotalCategories($filter_data);
		$categories = $this->model_extension_blog_category->getCategories($filter_data);

		if(is_array($categories) && count($categories) > 0) {
			foreach ($categories as $category) {
				
				if (is_file(DIR_IMAGE . $category['image'])) {
					$image = $this->model_tool_image->resize($category['image'], 40, 40);
				} else {
					$image = $this->model_tool_image->resize('no_image.png', 40, 40);
				}

				$data['categories'][] = array(
					'category_id' 		 	=> $category['category_id'],
					'image'      	=> $image,
					'category_name'    => $category['name'],
					'status'   => $category['status'],
					'sort_order'   => $category['sort_order'],
				);
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');

		// Menubar Label
		$data['post_menu'] = $this->language->get('post');
		$data['category_menu'] = $this->language->get('category');
		$data['comment_menu'] = $this->language->get('comment');
		$data['setting_menu'] = $this->language->get('setting');
		$data['help_menu'] = $this->language->get('help');

		// Button Label
		$data['create_btn'] = $this->language->get('create_btn');
		$data['delete_btn'] = $this->language->get('delete_btn');
		$data['create_btn_tooltip'] = $this->language->get('create_btn_tooltip');
		$data['delete_btn_tooltip'] = $this->language->get('delete_btn_tooltip');
		$data['button_filter'] = $this->language->get('button_filter');

		// Filter box text
		$data['filter_text_name'] = $this->language->get('filter_text_name');
		$data['filter_text_status'] = $this->language->get('filter_text_status');
		$data['filter_text_publish'] = $this->language->get('filter_text_publish');
		$data['filter_text_unpublish'] = $this->language->get('filter_text_unpublish');

		// Table Column Label
		$data['col_title'] = $this->language->get('col_title');
		$data['col_thumb'] = $this->language->get('col_thumb');
		$data['col_status'] = $this->language->get('col_status');
		$data['col_order'] = $this->language->get('col_order');
		$data['col_action'] = $this->language->get('col_action');

		// Text
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_not_found'] = $this->language->get('text_not_found');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
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

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('extension/blog/category', 'token=' . $this->session->data['token'] . '&sort=cd1.name' . $url, 'SSL');
		$data['sort_order'] = $this->url->link('extension/blog/category', 'token=' . $this->session->data['token'] . '&sort=c1.sort_order' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('extension/blog/category', 'token=' . $this->session->data['token'] . '&sort=c1.status' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		// Pagination
		$pagination = new Pagination();
		$pagination->total = $total_category;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('extension/blog/category', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_category) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total_category - $limit)) ? $total_category : ((($page - 1) * $limit) + $limit), $total_category, ceil($total_category / $limit));
		
		$data['filter_name'] = $filter_name;
		$data['filter_status'] = $filter_status;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/blog/category.tpl', $data));
	}

	public function category_create() {
		$this->language->load('extension/blog/category_form');
		$this->document->setTitle($this->language->get('heading_title_create'));
		$this->data['heading_title'] = $this->language->get('heading_title_create');

		$this->data['save_btn'] = $this->language->get('save_btn_create');
		$this->data['save_btn_tooltip'] = $this->language->get('save_btn_tooltip_create');

		$this->load->model('extension/blog/post');

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('base_title'),
			'href' => $this->url->link('extension/blog/category', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_create'),
			'href' => $this->url->link('extension/blog/category_create', 'token=' . $this->session->data['token'], 'SSL')
		);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
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


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatecatForm()) {
			$this->load->model('extension/blog/catmodify');
			$cid = $this->model_extension_blog_catmodify->createCategory($this->request->post);
			if($cid) {
				$this->session->data['success'] = $this->language->get('text_success_create');
			} else {
				$this->session->data['error_warning'] = $this->language->get('text_error_create');
			}	

			$this->response->redirect($this->url->link('extension/blog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->data['action'] = $this->url->link('extension/blog/category_create', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['cancel'] = $this->url->link('extension/blog/category', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['token'] = $this->session->data['token'];

		$this->getcategoryForm();
	}

	protected function check_category_id() {
		if(!isset($this->request->get['cid'])) {
			$this->response->redirect($this->url->link('extension/blog/category', 'token=' . $this->session->data['token'], 'SSL'));
		}

		return true;
	}

	public function category_edit() {

		$this->check_category_id();

		$this->cid = (int)$this->request->get['cid'];

		$this->language->load('extension/blog/category_form');
		$this->document->setTitle($this->language->get('heading_title_edit'));
		$this->data['heading_title'] = $this->language->get('heading_title_edit');

		$this->data['save_btn'] = $this->language->get('save_btn_edit');
		$this->data['save_btn_tooltip'] = $this->language->get('save_btn_tooltip_edit');

		// Breadcrumb
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('base_title'),
			'href' => $this->url->link('extension/blog/category', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_edit'),
			'href' => $this->url->link('extension/blog/category_edit', 'token=' . $this->session->data['token'] . '&cid=' . $this->cid, 'SSL')
		);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
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

		$this->load->model('extension/blog/catmodify');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatecatForm()) {

			$this->model_extension_blog_catmodify->editCategory($this->cid, $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success_edit');
				
			$this->response->redirect($this->url->link('extension/blog/category_edit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->data['action'] = $this->url->link('extension/blog/category_edit', 'token=' . $this->session->data['token'] . '&cid=' . $this->cid . $url, 'SSL');
		$this->data['cancel'] = $this->url->link('extension/blog/category', 'token=' . $this->session->data['token'] . '&cid=' . $this->cid . $url, 'SSL');
		$this->data['token'] = $this->session->data['token'];

		$this->getcategoryForm();
	}

	public function category_delete() {

		$this->check_category_id();

		$this->cid = $this->request->get['cid'];

		$this->language->load('extension/blog/category');
		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
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

		$this->load->model('extension/blog/catmodify');
		if ($this->cid && $this->validateDelete()) {
			if($this->model_extension_blog_catmodify->deleteCategory($this->cid)) {
				$this->session->data['success'] = $this->language->get('text_success_delete');
			} else {
				$this->session->data['error_warning'] = $this->language->get('text_error_delete');
			}

			$this->response->redirect($this->url->link('extension/blog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}			
		
		$this->getCatList();
	}

	public function category_delete_multiple() {

		$this->language->load('extension/blog/category');
		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
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

		$this->load->model('extension/blog/catmodify');
		if (isset($this->request->post['selected']) && $this->validateDelete()) {

			$success = $this->language->get('text_success_delete') . ' Category ID = ';
			$error = $this->language->get('text_error_delete') . ' Category ID = ';

			$inc = 1;
			foreach ($this->request->post['selected'] as $category_id) {
				$this->cid = $category_id;
				if($this->model_extension_blog_catmodify->deleteCategory($this->cid)) {
					if($inc == 1) {
						$success .= $this->cid;
					} else {
						$success .= ', ' . $this->cid;
					}
				} else {
					if($inc == 1) {
						$error .= $this->cid . ' ';
					} else {
						$error .= ', ' . $this->cid;
					}
				}	
				$inc++;
			}

			$this->session->data['success'] = $success;
			$this->session->data['error_warning'] = $error;

			$this->response->redirect($this->url->link('extension/blog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}			
		
		$this->getCatList();
	}

	public function category_autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('extension/blog/category');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'sort' => 'cd1.name',
				'order' => 'ASC',
				'start' => 0,
				'limit' => 20
			);

			$results = $this->model_extension_blog_category->getCategories($filter_data);

			if($results) {
				foreach ($results as $result) {
					$json[] = array(
						'category_id' 		=> $result['category_id'],
						'name'     => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
					);
				}
			}
			
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}
		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function getcategoryForm() {
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->error['date_available'])) {
			$this->data['error_date_available'] = $this->error['date_available'];
		} else {
			$this->data['error_date_available'] = '';
		}
		
		if (isset($this->error['keyword'])) {
			$this->data['error_keyword'] = $this->error['keyword'];
		} else {
			$this->data['error_keyword'] = '';
		}

		// Menubar Label
		$this->data['post_menu'] = $this->language->get('post');
		$this->data['category_menu'] = $this->language->get('category');
		$this->data['comment_menu'] = $this->language->get('comment');
		$this->data['setting_menu'] = $this->language->get('setting');
		$this->data['help_menu'] = $this->language->get('help');

		// Text
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_none'] = $this->language->get('text_none');

		// Tab Label
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_design'] = $this->language->get('tab_design');

		// Button
		$this->data['cancel_btn'] = $this->language->get('cancel_btn');
		$this->data['cancel_btn_tooltip'] = $this->language->get('cancel_btn_tooltip');

		// Form Label
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_slug'] = $this->language->get('entry_slug');
		$this->data['entry_meta_desc'] = $this->language->get('entry_meta_desc');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_parent'] = $this->language->get('entry_parent');
		$this->data['entry_filter'] = $this->language->get('entry_filter');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_thumb'] = $this->language->get('entry_thumb');
		$this->data['entry_top'] = $this->language->get('entry_top');
		$this->data['entry_column'] = $this->language->get('entry_column');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['col_store'] = $this->language->get('col_store');
		$this->data['col_layout'] = $this->language->get('col_layout');

		$this->data['help_autocomplete'] = $this->language->get('help_autocomplete');
		$this->data['help_keyword'] = $this->language->get('help_keyword');

		$this->load->model('extension/blog/category');
		if ($this->cid && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$category_info = $this->model_extension_blog_category->getCategory($this->cid);	
		} 

		$ths->data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['cat_description'])) {
			$this->data['cat_description'] = $this->request->post['cat_description'];
		} elseif (isset($this->request->get['cid'])) {
			$this->data['cat_description'] = $this->model_extension_blog_category->getCatDescriptions($this->cid);
		} else {
			$this->data['cat_description'] = array();
		}

		if (isset($this->request->post['path'])) {
			$this->data['path'] = $this->request->post['path'];
		} elseif (!empty($category_info)) {
			$this->data['path'] = $category_info['path'];
		} else {
			$this->data['path'] = '';
		}

		if (isset($this->request->post['parent_id'])) {
			$this->data['parent_id'] = $this->request->post['parent_id'];
		} elseif (!empty($category_info)) {
			$this->data['parent_id'] = $category_info['parent_id'];
		} else {
			$this->data['parent_id'] = 0;
		}

		if (isset($this->request->post['top'])) {
			$this->data['top'] = $this->request->post['top'];
		} elseif (!empty($category_info)) {
			$this->data['top'] = $category_info['top'];
		} else {
			$this->data['top'] = 0;
		}

		if (isset($this->request->post['column'])) {
			$this->data['column'] = $this->request->post['column'];
		} elseif (!empty($category_info)) {
			$this->data['column'] = $category_info['column'];
		} else {
			$this->data['column'] = 1;
		}

		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($category_info)) {
			$this->data['sort_order'] = $category_info['sort_order'];
		} else {
			$this->data['sort_order'] = 0;
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($category_info)) {
			$this->data['status'] = $category_info['status'];
		} else {
			$this->data['status'] = '';
		}

		// Category Layout
		if (isset($this->request->post['category_layout'])) {
			$this->data['category_layout'] = $this->request->post['category_layout'];
		} elseif (isset($this->cid)) {
			$this->data['category_layout'] = $this->model_extension_blog_category->getCategoryLayouts($this->cid);
		} else {
			$this->data['category_layout'] = array();
		}

		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('catalog/filter');

		if (isset($this->request->post['category_filter'])) {
			$filters = $this->request->post['category_filter'];
		} elseif ($this->cid) {
			$filters = $this->model_extension_blog_category->getCategoryFilters($this->cid);
		} else {
			$filters = array();
		}

		// Category filters
		$this->data['category_filters'] = array();

		foreach ($filters as $filter_id) {
			$filter_info = $this->model_catalog_filter->getFilter($filter_id);

			if ($filter_info) {
				$this->data['category_filters'][] = array(
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
				);
			}
		}

		// Category store
		$this->load->model('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['category_store'])) {
			$this->data['category_store'] = $this->request->post['category_store'];
		} elseif ($this->cid) {
			$this->data['category_store'] = $this->model_extension_blog_category->getCategoryStores($this->cid);
		} else {
			$this->data['category_store'] = array(0);
		}

		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($category_info)) {
			$this->data['keyword'] = $category_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		// print_r($this->data['keyword']); die();

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($category_info)) {
			$this->data['image'] = $category_info['image'];
		} else {
			$this->data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($category_info) && is_file(DIR_IMAGE . $category_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($category_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/blog/category_form.tpl', $this->data));
	}

	protected function validatecatForm() {
		if (!$this->user->hasPermission('modify', 'extension/blog')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->data['form_error'] = array();

		foreach ($this->request->post['cat_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 50)) {
				$this->data['form_error'][$language_id]['name'] = $this->language->get('error_name');
			}
		}

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);
		
			if ($url_alias_info && isset($this->request->get['cid']) && $url_alias_info['query'] != 'blog_category_id=' . $this->request->get['cid']) {
				$this->data['form_error']['keyword'] = $this->language->get('error_keyword');
			}

			if ($url_alias_info && !isset($this->request->get['cid'])) {
				$this->data['form_error']['keyword'] = $this->language->get('error_keyword');
			}
		}		

		if ($this->data['form_error'] && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	/**
	 * Comment
	 *=======================================================
	 */
	public function comment() {
		$this->language->load('extension/blog/comment');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->getCommentList();
	}

	protected function getCommentList() {
		$this->load->helper('blog');
		$this->load->model('extension/blog/setting');
		$settings = $this->model_extension_blog_setting->settings();
		$config = setting($settings);

		if (isset($this->request->get['filter_author'])) {
			$filter_author = $this->request->get['filter_author'];
		} else {
			$filter_author = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'comment_date';
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

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} 

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/blog/comment', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['comment_create_link'] = $this->url->link('extension/blog/comment_create', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['comment_edit_link'] = $this->url->link('extension/blog/comment_edit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['comment_delete_link'] = $this->url->link('extension/blog/comment_delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['comment_delete_multiple'] = $this->url->link('extension/blog/comment_delete_multiple', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$data['token'] = $this->session->data['token'];

		$limit = isset($config['comment_limit_admin']) ? $config['comment_limit_admin'] : $this->comment_limit;

		$filter_data = array(
			'filter_author'	  => $filter_author,
			'filter_status'   => $filter_status,
			'sort' 			  => $sort,
			'order' 		  => $order,
			'start' 		  => ($page - 1) * $limit,
			'limit'			  => $limit
		);

		$where = array();

		$this->load->model('tool/image');

		$this->load->model('extension/blog/comment');

		$total_comment = $this->model_extension_blog_comment->getTotalComments($filter_data);
		$data['comments'] = $this->model_extension_blog_comment->getComments($filter_data,$where);

		$data['heading_title'] = $this->language->get('heading_title');	

		// Menubar Label
		$data['post_menu'] = $this->language->get('post');
		$data['category_menu'] = $this->language->get('category');
		$data['comment_menu'] = $this->language->get('comment');
		$data['setting_menu'] = $this->language->get('setting');
		$data['help_menu'] = $this->language->get('help');

		// Button Label
		$data['create_btn'] = $this->language->get('create_btn');
		$data['delete_btn'] = $this->language->get('delete_btn');
		$data['create_btn_tooltip'] = $this->language->get('create_btn_tooltip');
		$data['delete_btn_tooltip'] = $this->language->get('delete_btn_tooltip');
		$data['button_filter'] = $this->language->get('button_filter');

		// Filter box text
		$data['filter_text_author'] = $this->language->get('filter_text_author');
		$data['filter_text_status'] = $this->language->get('filter_text_status');
		$data['filter_text_publish'] = $this->language->get('filter_text_publish');
		$data['filter_text_unpublish'] = $this->language->get('filter_text_unpublish');

		// Table Column Label
		$data['col_postTitle'] = $this->language->get('col_postTitle');
		$data['col_excerpt'] = $this->language->get('col_excerpt');
		$data['col_author'] = $this->language->get('col_author');
		$data['col_date'] = $this->language->get('col_date');
		$data['col_status'] = $this->language->get('col_status');	
		$data['col_action'] = $this->language->get('col_action');	

		// Text
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_not_found'] = $this->language->get('text_not_found');

		// Alert Message
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
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

		$url = '';

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_date'] = $this->url->link('extension/blog/comment', 'token=' . $this->session->data['token'] . '&sort=comment_date' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('extension/blog/comment', 'token=' . $this->session->data['token'] . '&sort=comment_approve' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		// Pagination
		$pagination = new Pagination();
		$pagination->total = $total_comment;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('extension/blog/comment', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_comment) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total_comment - $limit)) ? $total_comment : ((($page - 1) * $limit) + $limit), $total_comment, ceil($total_comment / $limit));

		$data['filter_author'] = $filter_author;
		$data['filter_status'] = $filter_status;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/blog/comment.tpl', $data));

	}

	protected function check_comment_id() {
		if(!isset($this->request->get['comment_id']) || empty($this->request->get['comment_id'])) {
			$this->response->redirect($this->url->link('extension/blog/comment', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}

	public function comment_edit() {

		$this->check_comment_id();

		$this->comment_id = (int)$this->request->get['comment_id'];

		$this->language->load('extension/blog/comment_form');
		$this->document->setTitle($this->language->get('heading_title_edit'));
		$this->data['heading_title'] = $this->language->get('heading_title_edit');

		$this->data['save_btn'] = $this->language->get('save_btn_edit');
		$this->data['save_btn_tooltip'] = $this->language->get('save_btn_tooltip_edit');

		// Breadcrumb
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('base_title'),
			'href' => $this->url->link('extension/blog/comment', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_edit'),
			'href' => $this->url->link('extension/blog/comment_edit', 'token=' . $this->session->data['token'] . '&comment_id=' . $this->comment_id, 'SSL')
		);

		$url = '';

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatecmtForm()) {
			$this->load->model('extension/blog/cmtmodify');
			if($this->model_extension_blog_cmtmodify->editComment($this->comment_id, $this->request->post)) {
				$this->session->data['success'] = $this->language->get('text_success_edit');
			} else {
				$this->session->data['error_warning'] = $this->language->get('text_error');
			}			
			$this->response->redirect($this->url->link('extension/blog/comment', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->data['action'] = $this->url->link('extension/blog/comment_edit', 'token=' . $this->session->data['token'] . '&comment_id=' . $this->comment_id . $url, 'SSL');
		$this->data['cancel'] = $this->url->link('extension/blog/comment', 'token=' . $this->session->data['token'] . '&comment_id=' . $this->comment_id . $url, 'SSL');
		$this->data['token'] = $this->session->data['token'];

		$this->load->model('extension/blog/comment');
		$this->data['comment'] = $this->model_extension_blog_comment->getComment(array("c.comment_ID"=>"='".$this->comment_id."'"));

		$this->getcmtForm();
	}

	public function comment_delete() {

		$this->check_comment_id();

		$this->comment_id = $this->request->get['comment_id'];

		$this->language->load('extension/blog/comment');
		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$this->load->model('extension/blog/cmtmodify');
		if ($this->comment_id && $this->validateDelete()) {
			if($this->model_extension_blog_cmtmodify->deleteComment($this->comment_id)) {
				$this->session->data['success'] = $this->language->get('text_success_delete');
			} else {
				$this->session->data['error_warning'] = $this->language->get('text_error');
			}		

			$this->response->redirect($this->url->link('extension/blog/comment', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}	

		$this->getCommentList();
	}

	public function comment_delete_multiple() {

		$this->language->load('extension/blog/comment');
		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$this->load->model('extension/blog/cmtmodify');
		if (isset($this->request->post['selected']) && $this->validateDelete()) {

			$success = $this->language->get('text_success_delete') . ' comment ID = ';
			$error = $this->language->get('text_error_delete') . ' comment ID = ';

			$inc = 1;
			foreach ($this->request->post['selected'] as $comment_id) {
				// $this->model_catalog_post->deletepost($comment_id);
				$this->comment_id = $comment_id;
				if($this->model_extension_blog_cmtmodify->deleteComment($this->comment_id)) {
					if($inc == 1) {
						$success .= $this->comment_id;
					} else {
						$success .= ', ' . $this->comment_id;
					}
				} else {
					if($inc == 1) {
						$error .= $this->comment_id . ' ';
					} else {
						$error .= ', ' . $this->comment_id;
					}
				}	
				$inc++;
			}

			$this->session->data['success'] = $success;
			$this->session->data['error_warning'] = $error;

			$this->response->redirect($this->url->link('extension/blog/comment', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}	

		$this->getCommentList();
	}

	protected function getcmtForm() {
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		// Menubar Label
		$this->data['post_menu'] = $this->language->get('post');
		$this->data['category_menu'] = $this->language->get('category');
		$this->data['comment_menu'] = $this->language->get('comment');
		$this->data['setting_menu'] = $this->language->get('setting');
		$this->data['help_menu'] = $this->language->get('help');

		// Button
		$this->data['cancel_btn'] = $this->language->get('cancel_btn');
		$this->data['cancel_btn_tooltip'] = $this->language->get('cancel_btn_tooltip');

		// Form Label
		$this->data['col_author'] = $this->language->get('col_author');
		$this->data['col_email'] = $this->language->get('col_email');
		$this->data['col_content'] = $this->language->get('col_content');
		$this->data['col_status'] = $this->language->get('col_status');

		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/blog/comment_form.tpl', $this->data));
	}

	protected function validatecmtForm() {
		if (!$this->user->hasPermission('modify', 'extension/blog')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->registry->set('form_validation', new Form_validation($this->registry));

		$this->form_validation->set_rules('comment_author', 'Author', 'trim|required');
		$this->form_validation->set_rules('author_email', 'Email', 'trim|valid_email');
		$this->form_validation->set_rules('comment_content', 'Content', 'trim|min_length[10]');
		$this->form_validation->set_rules('comment_approve', 'Approve', 'trim');
		if ($this->form_validation->run() == FALSE)
		{

			$data['form_error']['comment_author'] 	= $this->form_validation->error('comment_author', '<span class="error">', '</span>');
			$data['form_error']['author_email'] 	= $this->form_validation->error('author_email', '<span class="error">', '</span>');
			$data['form_error']['comment_content'] 	= $this->form_validation->error('comment_content', '<span class="error">', '</span>');
			$data['form_error']['comment_approve'] 	= $this->form_validation->error('comment_approve', '<span class="error">', '</span>');
			foreach ($data['form_error'] as $key => $value) {
				if (empty($data['form_error'][$key])) {
					unset($data['form_error'][$key]);
				}
			}

			$this->error['warning'] = $this->language->get('error_warning');
			$this->data['form_error'] = $data['form_error'];
		}		

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	/**
	 * Setting
	 *==========================================================================================
	 */
	public function setting() {

		$this->language->load('extension/blog/setting');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		// Menubar Label
		$this->data['post_menu'] = $this->language->get('post');
		$this->data['category_menu'] = $this->language->get('category');
		$this->data['comment_menu'] = $this->language->get('comment');
		$this->data['setting_menu'] = $this->language->get('setting');
		$this->data['help_menu'] = $this->language->get('help');

		// Button Label
		$this->data['save_btn'] = $this->language->get('save_btn');
		$this->data['save_btn_tooltip'] = $this->language->get('save_btn_tooltip');

		// Tab lebel
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_option'] = $this->language->get('tab_option');
		$this->data['tab_image'] = $this->language->get('tab_image');
		$this->data['tab_color'] = $this->language->get('tab_color');
		$this->data['tab_social'] = $this->language->get('tab_social');
		$this->data['tab_seo'] = $this->language->get('tab_seo');

		// Table Column Label
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_meta_desc'] = $this->language->get('entry_meta_desc');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		
		$this->data['col_settingName'] = $this->language->get('col_settingName');
		$this->data['col_settingContent'] = $this->language->get('col_settingContent');
		$this->data['col_settingSortOrder'] = $this->language->get('col_settingSortOrder');

		// Breadcrumb
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/blog/setting', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatesetForm()) {

			// print_r($this->request->post); die();
			$this->load->model('extension/blog/setting');

			if($this->model_extension_blog_setting->editSetting($this->request->post)) {
				$this->session->data['success'] = $this->language->get('text_success');
			} else {
				$this->session->data['error_warning'] = $this->language->get('text_error');
			}			

			$this->response->redirect($this->url->link('extension/blog/setting', 'token=' . $this->session->data['token'], 'SSL'));
		}

		// Buttons
		$this->data['action'] = $this->url->link('extension/blog/setting', 'token=' . $this->session->data['token'] . '&action=edit', 'SSL');
		$this->data['cancel'] = $this->url->link('extension/blog/setting', 'token=' . $this->session->data['token'] . '&action=cancel', 'SSL');
		
		$this->data['token'] = $this->session->data['token'];

		// Alert Message
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();


		$this->load->model('extension/blog/setting');
		$total_setting = $this->model_extension_blog_setting->total_settings();

		$general_setting = $this->model_extension_blog_setting->setting_general();
		// $general = array();
		foreach ($general_setting as $key => $setting) {
			$this->data['general_setting'][$setting['language_id']][$setting['setting_name']] = $setting['setting_value'];
		}

		$this->data['setting_option'] = $this->model_extension_blog_setting->settings(array("setting_keyword"=>"='option'"),"position");
		$this->data['setting_image'] = $this->model_extension_blog_setting->settings(array("setting_keyword"=>"='image'"),"position");
		$this->data['setting_color'] = $this->model_extension_blog_setting->settings(array("setting_keyword"=>"='color'"),"position");
		$this->data['setting_social'] = $this->model_extension_blog_setting->settings(array("setting_keyword"=>"='social'"),"position");

		foreach ($this->data['setting_image'] as $image) {
			if($image['setting_name'] == 'blog_logo') {
				$blog_logo = $image['setting_value'];
			}

			if($image['setting_name'] == 'blog_icon') {
				$blog_icon = $image['setting_value'];
			}

			if($image['setting_name'] == 'logo_image_size') {
				$logo_size = $image['setting_value'];
				$size = explode('x', $logo_size);
				$logo_width = isset($size[0]) ? $size[0] : 100;
				$logo_height = isset($size[1]) ? $size[1] : 100;
			}	

			if($image['setting_name'] == 'icon_image_size') {
				$icon_size = $image['setting_value'];
				$size = explode('x', $icon_size);
				$icon_width = isset($size[0]) ? $size[0] : 30;
				$icon_height = isset($size[1]) ? $size[1] : 30;
			}			
		}

		// print_r($blog_logo); die();
		if (isset($blog_logo)) {
			$this->data['image_logo'] = $blog_logo;
		} else {
			$this->data['image_logo'] = '';
		}

		if (isset($blog_icon)) {
			$this->data['image_icon'] = $blog_icon;
		} else {
			$this->data['image_icon'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['blog_logo']) && is_file(DIR_IMAGE . $this->request->post['blog_logo'])) {
			$this->data['blog_logo'] = $this->model_tool_image->resize($this->request->post['blog_logo'], $logo_width, $logo_height);
		} elseif (isset($blog_logo) && is_file(DIR_IMAGE . $blog_logo)) {
			$this->data['blog_logo'] = $this->model_tool_image->resize($blog_logo, $logo_width, $logo_height);
		} else {
			$this->data['blog_logo'] = $this->model_tool_image->resize('no_image.png', $logo_width, $logo_height);
		}	

		if (isset($this->request->post['blog_icon']) && is_file(DIR_IMAGE . $this->request->post['blog_icon'])) {
			$this->data['blog_icon'] = $this->model_tool_image->resize($this->request->post['blog_icon'], $icon_width, $icon_height);
		} elseif (isset($blog_icon) && is_file(DIR_IMAGE . $blog_icon)) {
			$this->data['blog_icon'] = $this->model_tool_image->resize($blog_icon, $icon_width, $icon_height);
		} else {
			$this->data['blog_icon'] = $this->model_tool_image->resize('no_image.png', $icon_width, $icon_height);
		}	
		
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/blog/setting.tpl', $this->data));
	}

	protected function validatesetForm() {
		if (!$this->user->hasPermission('modify', 'extension/blog')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	/**
	 * Help
	 *==========================================================================================
	 */
	public function help() {
		$this->language->load('extension/blog/help');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		// Menubar Label
		$this->data['post_menu'] = $this->language->get('post');
		$this->data['category_menu'] = $this->language->get('category');
		$this->data['comment_menu'] = $this->language->get('comment');
		$this->data['setting_menu'] = $this->language->get('setting');
		$this->data['help_menu'] = $this->language->get('help');

		// Lable Text
		$this->data['demosite_text'] = $this->language->get('demosite_text');
		$this->data['doc_text'] = $this->language->get('doc_text');
		$this->data['fb_text'] = $this->language->get('fb_text');
		$this->data['tw_text'] = $this->language->get('tw_text');
		$this->data['skype_text'] = $this->language->get('skype_text');
		$this->data['gmail_text'] = $this->language->get('gmail_text');
		$this->data['mobile_text'] = $this->language->get('mobile_text');

		$this->data['token'] = $this->session->data['token'];

		// Breadcrumb
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/blog/help', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/blog/help.tpl', $this->data));
	}
	
	public function install(){
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_category` (
				`category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`status` varchar(11) NOT NULL DEFAULT 'publish',
				`sort_order` int(3) NOT NULL,
				`parent_id` int(11) NOT NULL,
				`top` tinyint(1) NOT NULL,
				`column` int(3) NOT NULL,
				`image` varchar(255) NOT NULL,
				`date_added` datetime NOT NULL,
				`date_modified` datetime NOT NULL,
				PRIMARY KEY (`category_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=59 ;");


			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_category_description` (
				`category_id` int(11) unsigned NOT NULL,
				`language_id` int(11) unsigned NOT NULL,
				`name` varchar(255) CHARACTER SET utf8 NOT NULL,
				`slug` varchar(255) CHARACTER SET utf8 NOT NULL,
				`description` longtext CHARACTER SET utf8 NOT NULL,
				`meta_description` varchar(255) CHARACTER SET utf8 NOT NULL,
				`meta_keyword` varchar(255) CHARACTER SET utf8 NOT NULL,
				PRIMARY KEY (`category_id`,`language_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
			
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_category_filter` (
				`category_id` int(11) NOT NULL,
				`filter_id` int(11) NOT NULL,
				PRIMARY KEY (`category_id`,`filter_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
			
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_category_path` (
				`category_id` int(11) NOT NULL,
				`path_id` int(11) NOT NULL,
				`level` int(11) NOT NULL,
				PRIMARY KEY (`category_id`,`path_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
			
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_category_to_layout` (
				`category_id` int(11) NOT NULL,
				`store_id` int(11) NOT NULL,
				`layout_id` int(11) NOT NULL,
				PRIMARY KEY (`category_id`,`store_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
			
			
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_category_to_store` (
				`category_id` int(11) unsigned NOT NULL,
				`store_id` int(11) unsigned NOT NULL DEFAULT '0',
				PRIMARY KEY (`category_id`,`store_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
			
			
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_comment` (
				`comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				`comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT '0',
				`comment_author` varchar(255) NOT NULL,
				`comment_author_id` int(11) unsigned NOT NULL,
				`author_email` varchar(100) NOT NULL,
				`comment_date` datetime NOT NULL,
				`comment_content` text CHARACTER SET utf8 NOT NULL,
				`comment_approve` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT 'unpublish',
				PRIMARY KEY (`comment_ID`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;");
			
			
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_post` (
				`ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				`post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
				`sort_order` int(11) NOT NULL,
				`post_status` varchar(20) NOT NULL DEFAULT 'publish',
				`comment_status` varchar(20) NOT NULL DEFAULT 'open',
				`view` int(11) NOT NULL DEFAULT '0',
				`comment_count` int(11) NOT NULL DEFAULT '0',
				`post_thumb` varchar(255) DEFAULT NULL,
				`date_available` date NOT NULL,
				`date_added` datetime NOT NULL,
				`date_modified` datetime NOT NULL,
				PRIMARY KEY (`ID`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;");
			
			
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_postmeta` (
				`meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				`post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
				`meta_key` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
				`meta_value` longtext CHARACTER SET utf8,
				`sort_order` int(11) NOT NULL,
				PRIMARY KEY (`meta_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=525 ;");
			
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_post_description` (
				`post_id` int(11) unsigned NOT NULL,
				`language_id` int(11) unsigned NOT NULL,
				`title` varchar(255) NOT NULL,
				`content` longtext NOT NULL,
				`excerpt` text NOT NULL,
				`meta_description` varchar(255) NOT NULL,
				`meta_keyword` varchar(255) NOT NULL,
				`tag` text NOT NULL,
				PRIMARY KEY (`post_id`,`language_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
			
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_post_filter` (
				`post_id` int(11) NOT NULL,
				`filter_id` int(11) NOT NULL,
				PRIMARY KEY (`post_id`,`filter_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
						
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_post_to_category` (
				`post_id` int(11) NOT NULL,
				`category_id` int(11) NOT NULL,
				PRIMARY KEY (`post_id`,`category_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
						
			
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_post_to_layout` (
				`post_id` int(11) NOT NULL,
				`store_id` int(11) NOT NULL,
				`layout_id` int(11) NOT NULL,
				PRIMARY KEY (`post_id`,`store_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
			
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_post_to_store` (
				`post_id` int(11) unsigned NOT NULL,
				`store_id` int(11) unsigned NOT NULL DEFAULT '0',
				PRIMARY KEY (`post_id`,`store_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
						
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_related_product` (
				`post_id` int(11) NOT NULL,
				`product_id` int(11) NOT NULL,
				PRIMARY KEY (`post_id`,`product_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
						
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_setting` (
				`setting_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				`setting_keyword` varchar(255) NOT NULL,
				`setting_name` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
				`setting_value` longtext CHARACTER SET utf8 NOT NULL,
				`position` int(11) NOT NULL,
				PRIMARY KEY (`setting_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;");
			
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_setting_general` (
				`setting_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`language_id` int(11) unsigned NOT NULL,
				`setting_name` varchar(255) CHARACTER SET latin1 NOT NULL,
				`setting_value` longtext NOT NULL,
				PRIMARY KEY (`setting_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;");
	}
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/blog')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

}
