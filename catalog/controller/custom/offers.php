<?php
class ControllerCustomOffers extends Controller
{
    private $error = array();
    public function index()
    {
        $this->load->model('setting/setting');
        $this->load->language('custom/offers');

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('custom/offers', 'SSL')
        );
		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['description'] = '';
		$data['continue'] = $this->url->link('common/home');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
      
        //$this->response->setOutput($this->load->view('custom/hello.tpl', $data));
		$this->response->setOutput($this->load->view('default/template/custom/offers.tpl', $data));
    }
}
?>