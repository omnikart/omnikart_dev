<?php
class ControllerBlogHome extends Controller {
	public function index() {
		$this->load->language('blog/home');

		$this->load->helper('blog');
		$this->load->model('blog/setting');
		$settings = $this->model_blog_setting->settings();
		$blog_config = setting($settings);
		$data['config'] = $blog_config;

		// Heading Meta Tag
		$this->document->setTitle($blog_config['title']);
		$this->document->setDescription($blog_config['meta_description']);
		$this->document->setKeywords($blog_config['meta_keyword']);

		$data['heading_title'] = $blog_config['name'] ? $blog_config['name'] : '';

		$data['text_author'] = $this->language->get('text_author');
		$data['text_tag'] = $this->language->get('text_tag');
		$data['text_view'] = $this->language->get('text_view');
		$data['text_readmore'] = $this->language->get('text_readmore');
		$data['text_comment'] = $this->language->get('text_comment');
		$data['not_found'] = $this->language->get('not_found');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/js/blogScript.js')) {
			$this->document->addScript('catalog/view/theme/'.$this->config->get('config_template').'/js/blogScript.js');
		}

		$this->load->model('blog/post');
		$this->load->model('tool/image');
		
		// Breadcrumb
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
		);
		
		$data['breadcrumbs'][] = array(
				'text' => 'Blog',
				'href' => ''
		);
		
		$logo_size = $blog_config['logo_image_size'];
		$size = explode('x', $logo_size);
		$logo_width = isset($size[0]) ? $size[0] : 100;
		$logo_height = isset($size[1]) ? $size[1] : 100;

		$icon_size = $blog_config['icon_image_size'];
		$size = explode('x', $icon_size);
		$icon_width = isset($size[0]) ? $size[0] : 30;
		$icon_height = isset($size[1]) ? $size[1] : 30;

		if ($blog_config['blog_logo'] && is_file(DIR_IMAGE . $blog_config['blog_logo'])) {
			$data['blog_logo'] =  $this->model_tool_image->resize($blog_config['blog_logo'], $logo_width, $logo_height);
		} else {
			$data['blog_logo'] = $this->model_tool_image->resize('no_image.png', $logo_width, $logo_height);
		}

		if ($blog_config['blog_icon'] && is_file(DIR_IMAGE . $blog_config['blog_icon'])) {
			$data['blog_icon'] =  $this->model_tool_image->resize($blog_config['blog_icon'], $icon_width, $icon_height);
		} else {
			$data['blog_icon'] = $this->model_tool_image->resize('no_image.png', $icon_width, $icon_height);
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = isset($blog_config['post_limit_front']) ? $blog_config['post_limit_front'] : 5;

		$filter_data = array(
			'sort' => 'p.date_modified',
			'order' => 'DESC',
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);

		$total_post = $this->model_blog_post->getTotalPost();
		$posts = $this->model_blog_post->getPosts($filter_data);

		$thumb_size = explode('x', $blog_config['post_thumbnail_image_size']);
		$thumb_width  = isset($thumb_size[0]) ? $thumb_size[0] : 200;
		$thumb_height  = isset($thumb_size[1]) ? $thumb_size[1] : 250;

		$data['posts'] = array();

		if($posts) {
			
			$post_thumb = '';
			
			foreach ($posts as $key => $post) {
				if (!empty($post['post_thumb']) && is_file(DIR_IMAGE . $post['post_thumb'])) {
					$post_thumb = $this->model_tool_image->resize($post['post_thumb'], $thumb_width, $thumb_height);
				} else {
					$post_thumb = $this->model_tool_image->resize('no_image.png', $thumb_width, $thumb_height);
				}

				$post_images = array();

				if($blog_config['post_thumbnail_type'] == 'slide') {
					if(isset($post['image'])) {
						foreach ($post['image'] as $key => $image) {
			            	if (!empty($image['meta_value']) && is_file(DIR_IMAGE . $image['meta_value'])) {
					       		$post_images[] = $this->model_tool_image->resize($image['meta_value'], '30', '30');
					       	} else {
								$post_images[] = $this->model_tool_image->resize('no_image.png', '30', '30');
							}
				    	}
				    }
		        }
						$tags = array();
						foreach (explode(',',$post['tag']) as $tag) {
							$tags[] = array(
							'tag' 	=> $tag,
							'link'	=> $this->url->link('blog/tag','tag='.urldecode($tag),'SSL')
							);
						}
		        $data['posts'][] = array(
					'ID' => $post['ID'],
					'date_added' => $post['date_added'],
					'title' => $post['title'],
					'content' => words_limit(html_entity_decode($post['content']),$blog_config['word_limit_in_post'],'...'),
	        		'excerpt' => words_limit(html_entity_decode($post['excerpt']),$blog_config['word_limit_in_post'],'...'),
					'post_author' => $post['post_author'],
					'comment_count' => $post['comment_count'],
					'view' => $post['view'],
					'post_thumbnail' => $post_thumb,
					'images' => $post_images,
					'tag' => $post['tag'],
					'link'			=> $this->url->link('blog/single','pid='.$post['ID'],'SSL'),
					'comment_count' => $post['comment_count'],
					'tag' 			=> $tags
				);
			}
		}

		$pagination = new Pagination();
		$pagination->total = $total_post;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('blog/home','&page={page}');
		$data['pagination'] = $pagination->render();

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/blog/home.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/blog/home.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/blog/home.tpl', $data));
		}
	}
}
