<?php
class ControllerBlogSearch extends Controller {
	public function index() {
		$this->load->language('blog/search');

		$data['text_search'] = $this->language->get('text_search');

		if (isset($this->request->get['search'])) {
			$data['search'] = $this->request->get['search'];
		} else {
			$data['search'] = '';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/blog/search.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/blog/search.tpl', $data);
		} else {
			return $this->load->view('default/template/blog/search.tpl', $data);
		}
	}
}