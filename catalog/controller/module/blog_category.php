<?php
class ControllerModuleBlogCategory extends Controller {
	public function index($settings) {
		$this->load->language ( 'module/blog_category' );
		
		foreach ( $settings as $key => $setting ) {
			$data [$key] = $setting;
		}
		
		$data ['title'] = $settings ['title'] ? $settings ['title'] : $this->language->get ( 'heading_title' );
		
		$data ['not_found'] = $this->language->get ( 'not_found' );
		
		if (isset ( $this->request->get ['blog_category_path'] )) {
			$parts = explode ( '_', ( string ) $this->request->get ['blog_category_path'] );
		} else {
			$parts = array ();
		}
		
		if (isset ( $parts [0] )) {
			$data ['category_id'] = $parts [0];
		} else {
			$data ['category_id'] = 0;
		}
		
		if (isset ( $parts [1] )) {
			$data ['child_id'] = $parts [1];
		} else {
			$data ['child_id'] = 0;
		}
		
		$this->load->model ( 'blog/post' );
		$this->load->model ( 'blog/category' );
		
		$data ['categories'] = array ();
		
		$categories = $this->model_blog_category->getCategories ( 0 );
		
		foreach ( $categories as $category ) {
			$children_data = array ();
			
			if ($category ['category_id'] == $data ['category_id']) {
				$children = $this->model_blog_category->getCategories ( $category ['category_id'] );
				foreach ( $children as $child ) {
					$filter_data = array (
							'filter_category_id' => $child ['category_id'],
							'filter_sub_category' => true 
					);
					$children_data [] = array (
							'category_id' => $child ['category_id'],
							'name' => $child ['name'] ? $child ['name'] . ' (' . $this->model_blog_post->getTotalPost ( $filter_data ) . ')' : '',
							'href' => $this->url->link ( 'blog/category', 'blog_category_path=' . $category ['category_id'] . '_' . $child ['category_id'] ) 
					);
				}
			}
			
			$filter_data = array (
					'filter_category_id' => $category ['category_id'],
					'filter_sub_category' => true 
			);
			
			$data ['categories'] [] = array (
					'category_id' => $category ['category_id'],
					'name' => $category ['name'] ? $category ['name'] . ' (' . $this->model_blog_post->getTotalPost ( $filter_data ) . ')' : '',
					'children' => $children_data,
					'href' => $this->url->link ( 'blog/category', 'blog_category_path=' . $category ['category_id'] ) 
			);
		}
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/module/blog_category.tpl' )) {
			return $this->load->view ( $this->config->get ( 'config_template' ) . '/template/module/blog_category.tpl', $data );
		} else {
			return $this->load->view ( 'default/template/module/blog_category.tpl', $data );
		}
	}
}
