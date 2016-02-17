<?php
class ControllerBlogSearchprocessor extends Controller {
	public function index() {
		$this->load->language ( 'blog/search_processor' );
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/stylesheet/blog_search.css' )) {
			$this->document->addStyle ( 'catalog/view/theme/' . $this->config->get ( 'config_template' ) . '/stylesheet/blog_search.css' );
		}
		
		$this->load->helper ( 'blog' );
		$this->load->model ( 'blog/setting' );
		$settings = $this->model_blog_setting->settings ();
		$blog_config = setting ( $settings );
		$data ['config'] = $blog_config;
		
		$this->load->model ( 'blog/category' );
		
		$this->load->model ( 'blog/post' );
		
		$this->load->model ( 'tool/image' );
		
		if (isset ( $this->request->get ['search'] )) {
			$search = $this->request->get ['search'];
		} else {
			$search = '';
		}
		
		if (isset ( $this->request->get ['tag'] )) {
			$tag = $this->request->get ['tag'];
		} elseif (isset ( $this->request->get ['search'] )) {
			$tag = $this->request->get ['search'];
		} else {
			$tag = '';
		}
		
		if (isset ( $this->request->get ['content'] )) {
			$content = $this->request->get ['content'];
		} else {
			$content = '';
		}
		
		if (isset ( $this->request->get ['category_id'] )) {
			$category_id = $this->request->get ['category_id'];
		} else {
			$category_id = 0;
		}
		
		if (isset ( $this->request->get ['sub_category'] )) {
			$sub_category = $this->request->get ['sub_category'];
		} else {
			$sub_category = '';
		}
		
		if (isset ( $this->request->get ['sort'] )) {
			$sort = $this->request->get ['sort'];
		} else {
			$sort = 'p.sort_order';
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$order = $this->request->get ['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		if (isset ( $this->request->get ['limit'] )) {
			$limit = $this->request->get ['limit'];
		} else {
			$limit = $this->config->get ( 'config_product_limit' );
		}
		
		if (isset ( $this->request->get ['search'] )) {
			$this->document->setTitle ( $this->language->get ( 'heading_title' ) . ' - ' . $this->request->get ['search'] );
		} elseif (isset ( $this->request->get ['tag'] )) {
			$this->document->setTitle ( $this->language->get ( 'heading_title' ) . ' - ' . $this->language->get ( 'heading_tag' ) . $this->request->get ['tag'] );
		} else {
			$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		}
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'blog/home' ) 
		);
		
		$url = '';
		
		if (isset ( $this->request->get ['search'] )) {
			$url .= '&search=' . urlencode ( html_entity_decode ( $this->request->get ['search'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['tag'] )) {
			$url .= '&tag=' . urlencode ( html_entity_decode ( $this->request->get ['tag'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['content'] )) {
			$url .= '&content=' . $this->request->get ['content'];
		}
		
		if (isset ( $this->request->get ['category_id'] )) {
			$url .= '&category_id=' . $this->request->get ['category_id'];
		}
		
		if (isset ( $this->request->get ['sub_category'] )) {
			$url .= '&sub_category=' . $this->request->get ['sub_category'];
		}
		
		if (isset ( $this->request->get ['sort'] )) {
			$url .= '&sort=' . $this->request->get ['sort'];
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$url .= '&order=' . $this->request->get ['order'];
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
		}
		
		if (isset ( $this->request->get ['limit'] )) {
			$url .= '&limit=' . $this->request->get ['limit'];
		}
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'heading_title' ),
				'href' => $this->url->link ( 'blog/search_processor', $url ) 
		);
		
		if (isset ( $this->request->get ['search'] )) {
			$data ['heading_title'] = $this->language->get ( 'heading_title' ) . ' - ' . $this->request->get ['search'];
		} else {
			$data ['heading_title'] = $this->language->get ( 'heading_title' );
		}
		
		$data ['text_empty'] = $this->language->get ( 'text_empty' );
		$data ['text_search'] = $this->language->get ( 'text_search' );
		$data ['text_keyword'] = $this->language->get ( 'text_keyword' );
		$data ['text_category'] = $this->language->get ( 'text_category' );
		$data ['text_sub_category'] = $this->language->get ( 'text_sub_category' );
		$data ['text_sort'] = $this->language->get ( 'text_sort' );
		$data ['text_limit'] = $this->language->get ( 'text_limit' );
		
		$data ['entry_search'] = $this->language->get ( 'entry_search' );
		$data ['entry_content'] = $this->language->get ( 'entry_content' );
		
		$data ['button_search'] = $this->language->get ( 'button_search' );
		$data ['button_cart'] = $this->language->get ( 'button_cart' );
		$data ['button_wishlist'] = $this->language->get ( 'button_wishlist' );
		$data ['button_compare'] = $this->language->get ( 'button_compare' );
		$data ['button_list'] = $this->language->get ( 'button_list' );
		$data ['button_grid'] = $this->language->get ( 'button_grid' );
		
		$this->load->model ( 'blog/category' );
		
		// 3 Level Category Search
		$data ['categories'] = array ();
		
		$categories_1 = $this->model_blog_category->getCategories ( 0 );
		
		foreach ( $categories_1 as $category_1 ) {
			$level_2_data = array ();
			
			$categories_2 = $this->model_blog_category->getCategories ( $category_1 ['category_id'] );
			
			foreach ( $categories_2 as $category_2 ) {
				$level_3_data = array ();
				
				$categories_3 = $this->model_blog_category->getCategories ( $category_2 ['category_id'] );
				
				foreach ( $categories_3 as $category_3 ) {
					$level_3_data [] = array (
							'category_id' => $category_3 ['category_id'],
							'name' => $category_3 ['name'] 
					);
				}
				
				$level_2_data [] = array (
						'category_id' => $category_2 ['category_id'],
						'name' => $category_2 ['name'],
						'children' => $level_3_data 
				);
			}
			
			$data ['categories'] [] = array (
					'category_id' => $category_1 ['category_id'],
					'name' => $category_1 ['name'],
					'children' => $level_2_data 
			);
		}
		
		$data ['posts'] = array ();
		
		if (isset ( $this->request->get ['search'] ) || isset ( $this->request->get ['tag'] )) {
			$filter_data = array (
					'filter_name' => $search,
					'filter_tag' => $tag,
					'filter_content' => $content,
					'filter_category_id' => $category_id,
					'filter_sub_category' => $sub_category,
					'sort' => $sort,
					'order' => $order,
					'start' => ($page - 1) * $limit,
					'limit' => $limit 
			);
			
			$post_total = $this->model_blog_post->getTotalPost ( $filter_data );
			
			$results = $this->model_blog_post->getPosts ( $filter_data );
			
			$thumb_size = explode ( 'x', $blog_config ['post_thumbnail_image_size'] );
			$thumb_width = isset ( $thumb_size [0] ) ? $thumb_size [0] : 200;
			$thumb_height = isset ( $thumb_size [1] ) ? $thumb_size [1] : 250;
			
			foreach ( $results as $result ) {
				if ($result ['post_thumb']) {
					$image = $this->model_tool_image->resize ( $result ['post_thumb'], $thumb_width, $thumb_height );
				} else {
					$image = $this->model_tool_image->resize ( 'placeholder.png', $thumb_width, $thumb_height );
				}
				
				$data ['posts'] [] = array (
						'post_id' => $result ['ID'],
						'thumb' => $image,
						'title' => $result ['title'],
						'content' => words_limit ( html_entity_decode ( $result ['content'] ), $blog_config ['word_limit_in_post'], '...' ),
						'href' => $this->url->link ( 'blog/single', 'post_id=' . $result ['ID'] . $url ) 
				);
			}
			
			$url = '';
			
			if (isset ( $this->request->get ['search'] )) {
				$url .= '&search=' . urlencode ( html_entity_decode ( $this->request->get ['search'], ENT_QUOTES, 'UTF-8' ) );
			}
			
			if (isset ( $this->request->get ['tag'] )) {
				$url .= '&tag=' . urlencode ( html_entity_decode ( $this->request->get ['tag'], ENT_QUOTES, 'UTF-8' ) );
			}
			
			if (isset ( $this->request->get ['content'] )) {
				$url .= '&content=' . $this->request->get ['content'];
			}
			
			if (isset ( $this->request->get ['category_id'] )) {
				$url .= '&category_id=' . $this->request->get ['category_id'];
			}
			
			if (isset ( $this->request->get ['sub_category'] )) {
				$url .= '&sub_category=' . $this->request->get ['sub_category'];
			}
			
			if (isset ( $this->request->get ['limit'] )) {
				$url .= '&limit=' . $this->request->get ['limit'];
			}
			
			$data ['sorts'] = array ();
			
			$data ['sorts'] [] = array (
					'text' => $this->language->get ( 'text_default' ),
					'value' => 'p.sort_order-ASC',
					'href' => $this->url->link ( 'blog/search_processor', 'sort=p.sort_order&order=ASC' . $url ) 
			);
			
			$data ['sorts'] [] = array (
					'text' => $this->language->get ( 'text_title_asc' ),
					'value' => 'pd.title-ASC',
					'href' => $this->url->link ( 'blog/search_processor', 'sort=pd.title&order=ASC' . $url ) 
			);
			
			$data ['sorts'] [] = array (
					'text' => $this->language->get ( 'text_title_desc' ),
					'value' => 'pd.title-DESC',
					'href' => $this->url->link ( 'blog/search_processor', 'sort=pd.title&order=DESC' . $url ) 
			);
			
			$url = '';
			
			if (isset ( $this->request->get ['search'] )) {
				$url .= '&search=' . urlencode ( html_entity_decode ( $this->request->get ['search'], ENT_QUOTES, 'UTF-8' ) );
			}
			
			if (isset ( $this->request->get ['tag'] )) {
				$url .= '&tag=' . urlencode ( html_entity_decode ( $this->request->get ['tag'], ENT_QUOTES, 'UTF-8' ) );
			}
			
			if (isset ( $this->request->get ['content'] )) {
				$url .= '&content=' . $this->request->get ['content'];
			}
			
			if (isset ( $this->request->get ['category_id'] )) {
				$url .= '&category_id=' . $this->request->get ['category_id'];
			}
			
			if (isset ( $this->request->get ['sub_category'] )) {
				$url .= '&sub_category=' . $this->request->get ['sub_category'];
			}
			
			if (isset ( $this->request->get ['sort'] )) {
				$url .= '&sort=' . $this->request->get ['sort'];
			}
			
			if (isset ( $this->request->get ['order'] )) {
				$url .= '&order=' . $this->request->get ['order'];
			}
			
			$data ['limits'] = array ();
			
			$limits = array_unique ( array (
					$this->config->get ( 'config_product_limit' ),
					25,
					50,
					75,
					100 
			) );
			
			sort ( $limits );
			
			foreach ( $limits as $value ) {
				$data ['limits'] [] = array (
						'text' => $value,
						'value' => $value,
						'href' => $this->url->link ( 'blog/search_processor', $url . '&limit=' . $value ) 
				);
			}
			
			$url = '';
			
			if (isset ( $this->request->get ['search'] )) {
				$url .= '&search=' . urlencode ( html_entity_decode ( $this->request->get ['search'], ENT_QUOTES, 'UTF-8' ) );
			}
			
			if (isset ( $this->request->get ['tag'] )) {
				$url .= '&tag=' . urlencode ( html_entity_decode ( $this->request->get ['tag'], ENT_QUOTES, 'UTF-8' ) );
			}
			
			if (isset ( $this->request->get ['content'] )) {
				$url .= '&content=' . $this->request->get ['content'];
			}
			
			if (isset ( $this->request->get ['category_id'] )) {
				$url .= '&category_id=' . $this->request->get ['category_id'];
			}
			
			if (isset ( $this->request->get ['sub_category'] )) {
				$url .= '&sub_category=' . $this->request->get ['sub_category'];
			}
			
			if (isset ( $this->request->get ['sort'] )) {
				$url .= '&sort=' . $this->request->get ['sort'];
			}
			
			if (isset ( $this->request->get ['order'] )) {
				$url .= '&order=' . $this->request->get ['order'];
			}
			
			if (isset ( $this->request->get ['limit'] )) {
				$url .= '&limit=' . $this->request->get ['limit'];
			}
			
			$pagination = new Pagination ();
			$pagination->total = $post_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link ( 'blog/search_processor', $url . '&page={page}' );
			
			$data ['pagination'] = $pagination->render ();
			
			$data ['results'] = sprintf ( $this->language->get ( 'text_pagination' ), ($post_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($post_total - $limit)) ? $post_total : ((($page - 1) * $limit) + $limit), $post_total, ceil ( $post_total / $limit ) );
		}
		
		$data ['search'] = $search;
		$data ['content'] = $content;
		$data ['category_id'] = $category_id;
		$data ['sub_category'] = $sub_category;
		
		$data ['sort'] = $sort;
		$data ['order'] = $order;
		$data ['limit'] = $limit;
		
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['column_right'] = $this->load->controller ( 'common/column_right' );
		$data ['content_top'] = $this->load->controller ( 'common/content_top' );
		$data ['content_bottom'] = $this->load->controller ( 'common/content_bottom' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		$data ['header'] = $this->load->controller ( 'common/header' );
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/blog/search_result.tpl' )) {
			$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/blog/search_result.tpl', $data ) );
		} else {
			$this->response->setOutput ( $this->load->view ( 'default/template/blog/search_result.tpl', $data ) );
		}
	}
}
