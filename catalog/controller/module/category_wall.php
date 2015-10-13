<?php
class ControllerModuleCategoryWall extends Controller {
	public function index() {
		$this->load->language('module/category_wall');

		$data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$data['category_id'] = $parts[0];
		} else {
			$data['category_id'] = 0;
		}

		if (isset($parts[1])) {
			$data['child_id'] = $parts[1];
		} else {
			$data['child_id'] = 0;
		}

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);
        
		$this->load->model('tool/image');
		
		foreach ($categories as $category) {
			if ($category['top']) {

			$childs =  $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$category['category_id'] . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name) LIMIT 4");

				$child_data = array();
				foreach ($childs->rows as $child){
				$filter_data = array('filter_category_id' => $child['category_id'], 'filter_sub_category' => true);

					$child_data[] = array(
						'name'     => $child['name'],
						'href'     => $this->url->link('product/category', 'path=' . $category['category_id'].'_'.$child['category_id'])
					);
				}
				$child_data[] = array(
					'name'     => 'Click to see more',
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);

				$filter_data = array('filter_category_id' => $category['category_id'], 'filter_sub_category' => true);

				$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
				if ($product_total!=0)	{
				$data['categories'][] = array(
					'name'     => $category['name'],
					'image'    => $this->model_tool_image->resize($category['image'],110,150),
					'child'    => $child_data,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);}
			}
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category_wall.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/category_wall.tpl', $data);
		} else {
			return $this->load->view('default/template/module/category_wall.tpl', $data);
		}
	}
}
