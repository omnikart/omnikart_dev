<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language ( 'common/footer' );
		$data ['text_information'] = $this->language->get ( 'text_information' );
		$data ['text_service'] = $this->language->get ( 'text_service' );
		$data ['text_entry'] = $this->language->get ( 'text_entry' );
		$data ['text_extra'] = $this->language->get ( 'text_extra' );
		$data ['text_blog'] = "Blog";
		$data ['text_contact'] = $this->language->get ( 'text_contact' );
		$data ['text_return'] = $this->language->get ( 'text_return' );
		$data ['text_sitemap'] = $this->language->get ( 'text_sitemap' );
		$data ['text_manufacturer'] = $this->language->get ( 'text_manufacturer' );
		$data ['text_voucher'] = $this->language->get ( 'text_voucher' );
		$data ['text_careers'] = $this->language->get ( 'text_careers' );
		$data ['text_affiliate'] = $this->language->get ( 'text_affiliate' );
		$data ['text_special'] = $this->language->get ( 'text_special' );
		$data ['text_account'] = $this->language->get ( 'text_account' );
		$data ['text_order'] = $this->language->get ( 'text_order' );
		$data ['text_wishlist'] = $this->language->get ( 'text_wishlist' );
		$data ['text_newsletter'] = $this->language->get ( 'text_newsletter' );
		
		$this->load->model ( 'catalog/information' );
		
		$data ['informations'] = array ();
		
		foreach ( $this->model_catalog_information->getInformations () as $result ) {
			if ($result ['bottom']) {
				$data ['informations'] [] = array (
						'title' => $result ['title'],
						'href' => $this->url->link ( 'information/information', 'information_id=' . $result ['information_id'] ) 
				);
			}
		}
		
		$data ['contact'] = $this->url->link ( 'information/contact' );
		$data ['return'] = $this->url->link ( 'account/return/add', '', 'SSL' );
		$data ['sitemap'] = $this->url->link ( 'information/sitemap' );
		$data ['manufacturer'] = $this->url->link ( 'product/manufacturer' );
		$data ['voucher'] = $this->url->link ( 'account/voucher', '', 'SSL' );
		$data ['affiliate'] = $this->url->link ( 'affiliate/account', '', 'SSL' );
		$data ['special'] = $this->url->link ( 'product/special' );
		$data ['account'] = $this->url->link ( 'account/account', '', 'SSL' );
		$data ['order'] = $this->url->link ( 'account/order', '', 'SSL' );
		$data ['wishlist'] = $this->url->link ( 'account/wishlist', '', 'SSL' );
		$data ['newsletter'] = $this->url->link ( 'account/newsletter', '', 'SSL' );
		$data ['powered'] = sprintf ( $this->language->get ( 'text_powered' ), $this->config->get ( 'config_name' ), date ( 'Y', time () ) );
		$data ['careers'] = $this->url->link ( 'information/careers', '', 'SSL' );
		$data ['search_action'] = $this->url->link ( 'product/json', '', 'SSL' );
		$data ['blog'] = $this->url->link ( 'blog/home', '', 'SSL' );
		
		$data ['product_id'] = '';
		$data ['route'] = '';
		$data ['total'] = isset ( $this->session->data ['total'] ) ? $this->session->data ['total'] : '';
		if (isset ( $this->request->get ['product_id'] )) {
			$data ['product_id'] = $this->request->get ['product_id'];
		}
		if (isset ( $this->request->get ['route'] )) {
			$data ['route'] = $this->request->get ['route'];
		}
		// Whos Online
		if ($this->config->get ( 'config_customer_online' )) {
			$this->load->model ( 'tool/online' );
			
			if (isset ( $this->request->server ['REMOTE_ADDR'] )) {
				$ip = $this->request->server ['REMOTE_ADDR'];
			} else {
				$ip = '';
			}
			
			if (isset ( $this->request->server ['HTTP_HOST'] ) && isset ( $this->request->server ['REQUEST_URI'] )) {
				$url = 'http://' . $this->request->server ['HTTP_HOST'] . $this->request->server ['REQUEST_URI'];
			} else {
				$url = '';
			}
			
			if (isset ( $this->request->server ['HTTP_REFERER'] )) {
				$referer = $this->request->server ['HTTP_REFERER'];
			} else {
				$referer = '';
			}
			
			$this->model_tool_online->whosonline ( $ip, $this->customer->getId (), $url, $referer );
		}
		$this->load->model ( 'extension/module' );
		$data ['modules'] = array ();
		$modules = $this->model_extension_module->getModulesByCode ( 'ne' );
		
		foreach ( $modules as $module ) {
			$data ['modules'] [] = $this->load->controller ( 'module/' . $module ['code'], unserialize ( $module ['setting'] ) );
		}
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/common/footer.tpl' )) {
			return $this->load->view ( $this->config->get ( 'config_template' ) . '/template/common/footer.tpl', $data );
		} else {
			return $this->load->view ( 'default/template/common/footer.tpl', $data );
		}
	}
}
