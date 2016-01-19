<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		$data['title'] = $this->document->getTitle();

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}
		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();

		require_once(DIR_SYSTEM . 'nitro/core/core.php');
        require_once(DIR_SYSTEM . 'nitro/core/cdn.php');

        $data['styles'] = nitroCDNResolve($data['styles']);
		$data['scripts'] = $this->document->getScripts();
        $data['scripts'] = nitroCDNResolve($data['scripts']);
            
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		if ($this->config->get('config_google_analytics_status')) {
			$data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		} else {
			$data['google_analytics'] = '';
		}

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
		} else {
			$data['icon'] = '';
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

		$data['text_home'] = $this->language->get('text_home');
		$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		$data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));

		$data['text_account'] = $this->language->get('text_account');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_all'] = $this->language->get('text_all');
		$data['text_gift'] = $this->language->get('text_gift');
		$data['text_contact'] = $this->language->get('text_contact');
		
		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['login'] = $this->url->link('module/login', '', 'SSL');//$this->url->link('account/login', '', 'SSL');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$data['shopping_cart'] = $this->url->link('checkout/cart', '', 'SSL');
		$data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

		$data['menusell'] = $this->url->link('customerpartner/sell', '', 'SSL');
		$data['menu_buy'] = $this->url->link('account/cd', '', 'SSL');
		$this->language->load('module/marketplace');
		$data['text_sell_header'] = $this->language->get('text_sell_header');
		$data['text_my_profile'] = $this->language->get('text_my_profile');
		$data['text_addproduct'] = $this->language->get('text_addproduct');
		$data['text_wkshipping'] = $this->language->get('text_wkshipping');
		$data['text_productlist'] = $this->language->get('text_productlist');
		$data['text_dashboard'] = $this->language->get('text_dashboard');
		$data['text_orderhistory'] = $this->language->get('text_orderhistory');
		$data['text_becomePartner'] = $this->language->get('text_becomePartner');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_enquiry'] = "Enquiries";
		
		$data['t_db'] = "Dashboard";
		$data['t_so'] = "Schedule Order";

		$this->load->model('account/customerpartner');
		$data['chkIsPartner'] = $this->model_account_customerpartner->chkIsPartner();
		$data['mp_addproduct'] = $this->url->link('account/customerpartner/addproduct', '', 'SSL');
		$data['mp_productlist'] = $this->url->link('account/customerpartner/productlist', '', 'SSL');
		$data['mp_dashboard'] = $this->url->link('account/customerpartner/dashboard', '', 'SSL');
		$data['mp_add_shipping_mod'] = $this->url->link('account/customerpartner/add_shipping_mod','', 'SSL');
		$data['mp_orderhistory'] = $this->url->link('account/customerpartner/orderlist','', 'SSL');
		$data['mp_download'] = $this->url->link('account/customerpartner/download','', 'SSL');
		$data['mp_profile'] = $this->url->link('account/customerpartner/profile','','SSL');      
		$data['mp_want_partner'] = $this->url->link('account/customerpartner/become_partner','','SSL'); 
		$data['mp_transaction'] = $this->url->link('account/customerpartner/transaction','','SSL'); 
		$data['mp_enquiry'] = $this->url->link('account/customerpartner/enquiry','','SSL');
		
		$data['b_db'] = $this->url->link('account/cd','','SSL');
		$data['b_so'] = $this->url->link('checkout/orderlater','','SSL');

		$rights = $this->customer->getRights();
		$data['rights'] = $rights['rights'];
						
						
		$data['contact'] = $this->url->link('information/contact', '', 'SSL');
		$data['careers'] = $this->url->link('information/careers', '', 'SSL');
		$data['gift'] = $this->url->link('account/voucher', '', 'SSL');
		
		$data['telephone'] = $this->config->get('config_telephone');
		$data['email'] = $this->config->get('config_email');

		$status = true;

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", str_replace(array("\r\n", "\r"), "\n", trim($this->config->get('config_robots'))));
			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;

					break;
				}
			}
		}
		// Menu
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$data['categories'] = array();
		$categories = $this->model_catalog_category->getCategories(0);
		foreach ($categories as $category) {
			if ($category['top']) {
				$result = $this->model_catalog_category->getCategories($category['category_id']);
				$children_data = array();
				foreach ($result as $cat){
					$filter_data = array(
							'filter_category_id'  => $cat['category_id'],
							'filter_sub_category' => true,
							'mfp_disabled' => true
					);
					$total = $this->model_catalog_product->getTotalProducts($filter_data);
					if ($total) {
						$children_data[] = array(
						'name'  => $cat['name'],
						'href'  => $this->url->link('product/category', 'path=' . $cat['category_id'],'SSL')
						);
					}
				}
				$data['categories'][] = array(
					'name'     => $category['name'],
					'column'   => ($category['column'] ? $category['column'] : 1),
					'children' => array_slice($children_data,0,12),
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'],'SSL')
				);
			}
		}

		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');
		$data['pavmegamenu'] =$this->load->controller('module/pavmegamenu');

		// For page specific css
		if (isset($this->request->get['route'])) {
			if (isset($this->request->get['product_id'])) {
				$class = '-' . $this->request->get['product_id'];
			} elseif (isset($this->request->get['path'])) {
				$class = '-' . $this->request->get['path'];
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$class = '-' . $this->request->get['manufacturer_id'];
			} else {
				$class = '';
			}

			$data['class'] = str_replace('/', '-', $this->request->get['route']) . $class;
		} else {
			$data['class'] = 'common-home';
		}

		$this->load->model('design/layout');

		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = 'common/home';
		}		
		
		$layout_id = 0;
		
		if ($route == 'product/category' && isset($this->request->get['path'])) {
			$this->load->model('catalog/category');
		
			$path = explode('_', (string)$this->request->get['path']);
		
			$layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));
		}
		
		if ($route == 'product/product' && isset($this->request->get['product_id'])) {
			$this->load->model('catalog/product');
		
			$layout_id = $this->model_catalog_product->getProductLayoutId($this->request->get['product_id']);
		}
		
		if ($route == 'information/information' && isset($this->request->get['information_id'])) {
			$this->load->model('catalog/information');
		
			$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
		}
		
		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}
		
		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}		
		
		$this->session->data['layout_id'] = $layout_id;
		
		$this->load->model('extension/module');
		
		$data['modules'] = array();
		
		$modules = $this->model_design_layout->getLayoutModules($layout_id, 'header');
		
		foreach ($modules as $module) {
			$part = explode('.', $module['code']);
		
			if (isset($part[0]) && $this->config->get($part[0] . '_status')) {
				$data['modules'][$part[0]] = $this->load->controller('module/' . $part[0]);
			}
		
			if (isset($part[1])) {
				$setting_info = $this->model_extension_module->getModule($part[1]);
		
				if ($setting_info && $setting_info['status']) {
					$data['modules'][$part[0].$part[1]] = $this->load->controller('module/' . $part[0], $setting_info);
				}
			}
		}		
		
		if (($route=='product/product') || ($route=='product/category') || ($route=='product/search')){
			$data['facebook'] = true;
		} elseif (($route=='checkout/cart') || ($route=='checkout/checkout_onepage')){
			$data['facebook_cart'] = true;
		} elseif (($route=='checkout/success')){
			$data['facebook_success'] = true;
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/header.tpl', $data);
		} else {
			return $this->load->view('default/template/common/header.tpl', $data);
		}
	}
}
