<?php
class ControllerModuleBlogTabs extends Controller {
	public function index($settings) {
		$this->load->language ( 'module/blog_tabs' );
		
		$this->load->helper ( 'blog' );
		$this->load->model ( 'blog/post' );
		$this->load->model ( 'tool/image' );
		
		foreach ( $settings as $key => $setting ) {
			$data [$key] = $setting;
		}
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		$data ['title_recent'] = $settings ['title_recent'] ? $settings ['title_recent'] : $this->language->get ( 'text_recentpost' );
		$data ['title_popular'] = $settings ['title_popular'] ? $settings ['title_popular'] : $this->language->get ( 'text_popularpost' );
		
		$data ['limit_title'] = $settings ['limit_title'] ? $settings ['limit_title'] : 10;
		$data ['limit_content'] = $settings ['limit_content'] ? $settings ['limit_content'] : 50;
		
		$data ['not_found_recent'] = $this->language->get ( 'not_found_recent' );
		$data ['not_found_popular'] = $this->language->get ( 'not_found_popular' );
		
		$filter_data_recent = array (
				'sort' => 'p.date_modified',
				'order' => 'DESC',
				'start' => 0,
				'limit' => $settings ['limit_recent'] 
		);
		
		$filter_data_popular = array (
				'sort' => 'p.view',
				'order' => 'DESC',
				'start' => 0,
				'limit' => $settings ['limit_popular'] 
		);
		
		$data ['recent_posts'] = $this->model_blog_post->getPosts ( $filter_data_recent );
		$data ['popular_posts'] = $this->model_blog_post->getPosts ( $filter_data_popular );
		
		$thumb_size_recent = explode ( 'x', $settings ['thumbnail_size_recent'] );
		$thumb_width_recent = isset ( $thumb_size_recent [0] ) ? ( int ) $thumb_size_recent [0] : 200;
		$thumb_height_recent = isset ( $thumb_size_recent [1] ) ? ( int ) $thumb_size_recent [1] : 250;
		
		$thumb_size_popular = explode ( 'x', $settings ['thumbnail_size_popular'] );
		$thumb_width_popular = isset ( $thumb_size_popular [0] ) ? ( int ) $thumb_size_popular [0] : 200;
		$thumb_height_popular = isset ( $thumb_size_popular [1] ) ? ( int ) $thumb_size_popular [1] : 250;
		
		foreach ( $data ['recent_posts'] as $key => $post ) {
			if (! empty ( $post ['post_thumb'] ) && is_file ( DIR_IMAGE . $post ['post_thumb'] )) {
				$data ['recent_posts'] [$key] ['thumb'] = $this->model_tool_image->resize ( $post ['post_thumb'], 150, 150 );
			} else {
				$data ['recent_posts'] [$key] ['thumb'] = $this->model_tool_image->resize ( 'no_image.png', $thumb_width_recent, $thumb_height_recent );
			}
		}
		
		foreach ( $data ['popular_posts'] as $key => $post ) {
			if (! empty ( $post ['post_thumb'] ) && is_file ( DIR_IMAGE . $post ['post_thumb'] )) {
				$data ['popular_posts'] [$key] ['thumb'] = $this->model_tool_image->resize ( $post ['post_thumb'], 150, 150 );
			} else {
				$data ['popular_posts'] [$key] ['thumb'] = $this->model_tool_image->resize ( 'no_image.png', 150, 150 );
			}
		}
		
		if (isset ( $this->request->get ['path'] )) {
			$parts = explode ( '_', ( string ) $this->request->get ['path'] );
		} else {
			$parts = array ();
		}
		
		if (isset ( $parts [0] )) {
			$data ['blog_tabs_id'] = $parts [0];
		} else {
			$data ['blog_tabs_id'] = 0;
		}
		
		if (isset ( $parts [1] )) {
			$data ['child_id'] = $parts [1];
		} else {
			$data ['child_id'] = 0;
		}
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/module/blog_tabs.tpl' )) {
			return $this->load->view ( $this->config->get ( 'config_template' ) . '/template/module/blog_tabs.tpl', $data );
		} else {
			return $this->load->view ( 'default/template/module/blog_tabs.tpl', $data );
		}
	}
}
