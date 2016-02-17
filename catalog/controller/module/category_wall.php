<?php
class ControllerModuleCategoryWall extends Controller {
	public function index() {
		$this->load->language ( 'module/category_wall' );
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		
		if (isset ( $this->request->get ['path'] )) {
			$parts = explode ( '_', ( string ) $this->request->get ['path'] );
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
		
		$this->load->model ( 'catalog/category' );
		
		$this->load->model ( 'catalog/product' );
		
		$data ['categories'] = array ();
		
		$data ['categories'] = array ();
		
		$categories = $this->config->get('category_wall_categories');
		$this->load->model ( 'tool/image' );
	
		foreach ( $categories as $categoryl ) {
			$category = $this->model_catalog_category->getCategory($categoryl['category_id']);
			$sub_categories = explode(',',$categoryl['sub_categories'] );
			$children = array();
			foreach ( $sub_categories as $sub_category ) {
				$child = $this->model_catalog_category->getCategory($sub_category);
				$children[] = array (
					'name' => $child ['name'],
					'href' => $this->url->link ( 'product/category', 'path=' . $category ['category_id'].'_'.$child ['category_id'] ) ,
				);	
			}

			$data ['categories'] [] = array (
					'name' => $category ['name'],
					'image' => $this->model_tool_image->resize ( $category ['image'], 100, 100 ),
					'href' => $this->url->link ( 'product/category', 'path=' . $category ['category_id'] ) ,
					'children'=>$children
			);

		}
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/module/category_wall.tpl' )) {
			return $this->load->view ( $this->config->get ( 'config_template' ) . '/template/module/category_wall.tpl', $data );
		} else {
			return $this->load->view ( 'default/template/module/category_wall.tpl', $data );
		}
	}
}
