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

		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['login'] = 
				$this->url->link('module/login', '', 'SSL')
			;
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$data['shopping_cart'] = $this->url->link('checkout/cart');
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
						$data['t_db'] = "Customer DashBoard";

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
						$data['b_db'] = $this->url->link('account/cd','','SSL');
						
						$rights = $this->model_account_customerpartner->getCustomerGroupRights($this->customer->getGroupId());
						$data['rights'] = $rights['rights'];
						
						
		$data['contact'] = $this->url->link('information/contact');
		$data['careers'] = $this->url->link('information/careers');
		
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
				// Level 2
				$children_data = array();

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
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

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/header.tpl', $data);
		} else {
			return $this->load->view('default/template/common/header.tpl', $data);
		}
	}
}
