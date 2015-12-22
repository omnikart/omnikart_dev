<?php
class ControllerBlogCategory extends Controller {
	public function index() {

		$this->load->language('blog/category');

		$this->load->helper('blog');
		$this->load->model('blog/setting');
		$settings = $this->model_blog_setting->settings();
		$blog_config = setting($settings);
		$data['config'] = $blog_config;

		$this->load->model('blog/post');
		$this->load->model('blog/category');

		$this->load->model('tool/image');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/js/blogScript.js')) {
			$this->document->addScript('catalog/view/theme/'.$this->config->get('config_template').'/js/blogScript.js');
		}

		$add_default_style = false;
		 
		// Social Sharing
		$this->document->addScript('//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-54da4ef349e7f893');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
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

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = isset($blog_config['post_limit_front']) ? $blog_config['post_limit_front'] : 5;
		}

		// Breadcrumb
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('blog/home')
		);

		if (isset($this->request->get['blog_category_path'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$path = '';

			$parts = explode('_', (string)$this->request->get['blog_category_path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_blog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('blog/category', 'blog_category_path=' . $path . $url)
					);
				}
			}
		} else {
			$category_id = 0;
		}

		$category_info = $this->model_blog_category->getCategory($category_id);

		if ($category_info) {
			$doc_title = '';
			if($category_info['name']) {
				$doc_title = $category_info['name'] . ' | ';
			}

			if($blog_config['name']) {
				$doc_title =  $doc_title . $blog_config['name'];
			}


			$doc_metaDescription = $category_info['meta_description'] ? $category_info['meta_description'] : $blog_config['meta_description'];
			$doc_metaKeyword = $category_info['meta_keyword'] ? $category_info['meta_keyword'] : $blog_config['meta_keyword'];

			$this->document->setTitle($doc_title);
			$this->document->setDescription($doc_metaDescription);
			$this->document->setKeywords($doc_metaKeyword);

			$data['heading_title'] = $blog_config['name'] ? $blog_config['name'] : '';

			$data['text_empty'] = $this->language->get('text_empty');

			// Label
			$data['text_author'] = $this->language->get('text_author');
			$data['text_tag'] = $this->language->get('text_tag');
			$data['text_view'] = $this->language->get('text_view');
			$data['text_readmore'] = $this->language->get('text_readmore');
			$data['text_comment'] = $this->language->get('text_comment');
			$data['not_found'] = $this->language->get('not_found');

			// Set the last category breadcrumb
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('blog/category', 'blog_category_path=' . $this->request->get['blog_category_path'])
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

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['categories'] = array();

			$results = $this->model_blog_category->getCategories($category_id);

			foreach ($results as $result) {
				$filter_data = array(
					'filter_category_id'  => $result['category_id'],
					'filter_sub_category' => true
				);

				$data['categories'][] = array(
					'name'  => $result['name'] ? $result['name'] . ' (' . $this->model_blog_post->getTotalPost($filter_data) . ')' : '',
					'href'  => $this->url->link('blog/category', 'blog_category_path=' . $this->request->get['blog_category_path'] . '_' . $result['category_id'] . $url)
				);
			}

			$data['products'] = array();

			$filter_data = array(
				'filter_category_id' => $category_id,
				'filter_filter'      => $filter,
				'sort' => $sort,	
				'order' => $order,
				'start' => ($page - 1) * $limit,
				'limit' => $limit
			);

			$total_post = $this->model_blog_post->getTotalPost($filter_data);
			$results = $this->model_blog_post->getposts($filter_data);

			$thumb_size = explode('x', $blog_config['post_thumbnail_image_size']);
			$thumb_width  = isset($thumb_size[0]) ? $thumb_size[0] : 200;
			$thumb_height  = isset($thumb_size[1]) ? $thumb_size[1] : 250;

			foreach ($results as $post) {
				if (!empty($post['post_thumb']) && is_file(DIR_IMAGE . $post['post_thumb'])) {
					$post_thumb = $this->model_tool_image->resize($post['post_thumb'], $thumb_width, $thumb_height);
				} else {
					$post_thumb = $this->model_tool_image->resize('no_image.png', $thumb_width, $thumb_height);
				}

				$images = array();
				if($blog_config['post_thumbnail_type'] == 'slide') {
					if(isset($post['images']) && count($post['images']) > 0) {
			            foreach ($post['images'] as $image) {
					        switch ($image['meta_key']) {
					            case 'image':
					              $images[] = $this->model_tool_image->resize($image['meta_value'], $thumb_width, $thumb_height);
					              break;
					            default:
					              //...
					              break;
				        	} 
				    	} // end foreach()
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
		        	'ID' 			=> $post['ID'],
		        	'date_added' 	=> $post['date_added'],
		        	'title' 		=> $post['title'],
		        	'post_author' 	=> $post['post_author'],
		        	'comment_count' => $post['comment_count'],
		        	'view' 			=> $post['view'],
		        	'post_thumbnail'=> $post_thumb,
		        	'image' 		=> $images,
		        	'link'			=> $this->url->link('blog/single','pid='.$post['ID'],'SSL'),
		        	'content' 		=> words_limit(html_entity_decode($post['content']),$blog_config['word_limit_in_post'],'...'),
		        	'tag' 			=> $tags
		        );	

		        $data['category_info'] = $category_info;

			} // End foreach loop

			$path = isset($this->request->get['blog_category_path']) ? $this->request->get['blog_category_path'] : '';

			$pagination = new Pagination();
			$pagination->total = $total_post;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('blog/category','&path='.$path.'&page={page}');
			$data['pagination'] = $pagination->render();

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/blog/category.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/blog/category.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/blog/category.tpl', $data));
			}
		} else {

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/404.css')) {
				$this->document->addStyle('catalog/view/theme/'.$this->config->get('config_template').'/stylesheet/404.css');
			} 

			$url = '';

			if (isset($this->request->get['blog_category_path'])) {
				$url .= '&blog_category_path=' . $this->request->get['blog_category_path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
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

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('not_found'),
				'href' => $this->url->link('product/category', $url)
			);

			$this->document->setTitle($this->language->get('not_found'));

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_error'] = $this->language->get('not_found');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('blog/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found_blog.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found_blog.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}
}
