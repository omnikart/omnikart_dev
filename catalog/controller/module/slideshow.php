<?php
class ControllerModuleSlideshow extends Controller {
	public function index($setting) {
		static $module = 0;

		$this->load->model('design/banner');
		$this->load->model('tool/image');

		//$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.css');
		//$this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');
		//
		
		$this->document->addStyle('catalog/view/javascript/jquery/homepage/flexslider.css');
		$this->document->addScript('catalog/view/javascript/jquery/homepage/jquery.flexslider-min.js');
		
		$data['banners'] = array();
		$data['side_banners'] = array();
		
		$results = $this->model_design_banner->getBanner($setting['banner_id']);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$data['banners'][] = array(
					'title' => $result['title'],
					'link'  => $result['link'],
					'image' => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height'])
				);
			}
		}
		
		$data['status_side'] = $setting['status_side']; 
		$results = array();
		if ($setting['status_side']){
			
			$results = $this->model_design_banner->getBanner($setting['side_banner_id']);
			
			foreach ($results as $result) {
				if (is_file(DIR_IMAGE . $result['image'])) {
					$data['side_banners'][] = array(
							'title' => $result['title'],
							'link'  => $result['link'],
							'image' => $this->model_tool_image->resize($result['image'], $setting['side_width'], $setting['side_height'])
					);
				}
			}
		}
		
		$data['module'] = $module++;
		$data['margin_left'] = $setting['margin_left'];
		

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/slideshow.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/slideshow.tpl', $data);
		} else {
			return $this->load->view('default/template/module/slideshow.tpl', $data);
		}
	}
}
