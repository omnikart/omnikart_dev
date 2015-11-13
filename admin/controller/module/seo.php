<?php
class ControllerModuleSeo extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/seo');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('seo', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/seo', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/seo', 'token=' . $this->session->data['token'], 'SSL');
		$data['product_l'] = $this->url->link('module/seo/product', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['seo_status'])) {
			$data['seo_status'] = $this->request->post['seo_status'];
		} else {
			$data['seo_status'] = $this->config->get('seo_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/seo.tpl', $data));
	}
	
	public function product(){
		$this->load->language('module/seo');
		$this->document->setTitle($this->language->get('heading_title_p'));
		$url = '';
		$filters = array(
				'filter_name'=>NULL,
				'filter_model'=>NULL,
				'filter_price'=>NULL,
				'filter_quantity'=>NULL,
				'filter_status'=>NULL,
				'sort'=>'pd.name',
				'order'=>'ASC',
				'page'=>1);
		foreach ($filters as $filter => $default){
			if (isset($this->request->get[$filter])) {
				$filter_data[$filter] = $this->request->get[$filter];
				$url .= '&'.$filter.'=' . urlencode(html_entity_decode($filter_data[$filter], ENT_QUOTES, 'UTF-8'));
			} else {
				$filter_data[$filter] = $default;
			}
		}

		$data['heading_title'] = $this->language->get('heading_title_p');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_module'),
				'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/seo/product', 'token=' . $this->session->data['token'], 'SSL')
		);		
		$this->document->addScript("view/javascript/stringToSlug/speakingurl.min.js");
		$this->document->addScript("view/javascript/stringToSlug/jquery.stringToSlug.min.js");
		$datap['products'] = array();
		
		$filter_data['start'] = ($filter_data['page'] - 1) * $this->config->get('config_limit_admin');
		$filter_data['limit'] = $this->config->get('config_limit_admin');
		
		$this->load->model('module/seo');
		$this->load->model('tool/image');

		$product_total = $this->model_module_seo->getTotalProducts($filter_data);
		$results = $this->model_module_seo->getProducts($filter_data);		

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}
		
			$special = false;
		
			$datap['products'][] = array(
					'product_id' => $result['product_id'],
					'image'      => $image,
					'name'       => $result['name'],
					'model'      => $result['model'],
					'price'      => $result['price'],
					'keyword'    => ($result['keyword'])?$result['keyword']:'',
					'meta_title'    => $result['meta_title'],
					'meta_description'    => $result['meta_description'],
					'meta_keyword'    => $result['meta_keyword'],
					'special'    => $special,
					'status'     => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'edit'       => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);
		}
		$url = '';
		$filters = array(
				'filter_name'=>NULL,
				'filter_model'=>NULL,
				'filter_price'=>NULL,
				'filter_quantity'=>NULL,
				'filter_status'=>NULL,
				'sort'=>'pd.name',
				'order'=>'ASC');
		foreach ($filters as $filter => $default){
			if (isset($this->request->get[$filter])) {
				$url .= '&'.$filter.'=' . urlencode(html_entity_decode($filter_data[$filter], ENT_QUOTES, 'UTF-8'));
			}
		}
		
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $filter_data['page'];
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		
		$datap['pagination'] = $pagination->render();
		
		
		$data['content'] = $this->load->view('module/seo/seo_product.tpl', $datap);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('module/seo.tpl', $data));
	}
	
	public function category(){
		$this->load->language('module/seo');
		$this->document->setTitle($this->language->get('heading_title_c'));
		$url = '';
		$filters = array(
			'sort'  => 'name',
			'order' => 'ASC',
			'page' => 1
		);
		foreach ($filters as $filter => $default){
			if (isset($this->request->get[$filter])) {
				$filter_data[$filter] = $this->request->get[$filter];
				$url .= '&'.$filter.'=' . urlencode(html_entity_decode($filter_data[$filter], ENT_QUOTES, 'UTF-8'));
			} else {
				$filter_data[$filter] = $default;
			}
		}
	
		$data['heading_title'] = $this->language->get('heading_title_c');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_module'),
				'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/seo/category', 'token=' . $this->session->data['token'], 'SSL')
		);
		$this->document->addScript("view/javascript/stringToSlug/speakingurl.min.js");
		$this->document->addScript("view/javascript/stringToSlug/jquery.stringToSlug.min.js");
		$datap['products'] = array();
	
		$filter_data['start'] = ($filter_data['page'] - 1) * $this->config->get('config_limit_admin');
		$filter_data['limit'] = $this->config->get('config_limit_admin');
	
		$this->load->model('module/seo');
		$this->load->model('tool/image');
		$datap['categories'] = array();
		
		$category_total = $this->model_module_seo->getTotalCategories();
		$results = $this->model_module_seo->getCategories($filter_data);
		foreach ($results as $result) {
			$datap['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name_2'],
				'path'        => $result['name'],
				'keyword'    => ($result['keyword'])?$result['keyword']:'',
				'meta_title'    => $result['meta_title'],
				'meta_description'    => $result['meta_description'],
				'meta_keyword'    => $result['meta_keyword'],
				'sort_order'  => $result['sort_order'],
			);
		}
		$url = '';
		$filters = array(
			'sort'  => 'name',
			'order' => 'ASC',
		);
		foreach ($filters as $filter => $default){
			if (isset($this->request->get[$filter])) {
				$url .= '&'.$filter.'=' . urlencode(html_entity_decode($filter_data[$filter], ENT_QUOTES, 'UTF-8'));
			}
		}
		$pagination = new Pagination();
		$pagination->total = $category_total;
		$pagination->page = $filter_data['page'];
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$datap['pagination'] = $pagination->render();
	
		$data['content'] = $this->load->view('module/seo/seo_category.tpl', $datap);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('module/seo.tpl', $data));
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/seo')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	private function url_slug($str, $options = array()) {
		// Make sure string is in UTF-8 and strip invalid UTF-8 characters
		$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
	
		$defaults = array(
				'delimiter' => '-',
				'limit' => null,
				'lowercase' => true,
				'replacements' => array(),
				'transliterate' => false,
		);
	
		// Merge options
		$options = array_merge($defaults, $options);
	
		$char_map = array(
				// Latin
				'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
				'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
				'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
				'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
				'ß' => 'ss',
				'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
				'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
				'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
				'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
				'ÿ' => 'y',
				// Latin symbols
				'©' => '(c)',
				// Greek
				'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
				'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
				'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
				'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
				'Ϋ' => 'Y',
				'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
				'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
				'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
				'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
				'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
				// Turkish
				'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
				'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
				// Russian
				'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
				'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
				'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
				'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
				'Я' => 'Ya',
				'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
				'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
				'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
				'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
				'я' => 'ya',
				// Ukrainian
				'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
				'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
				// Czech
				'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
				'Ž' => 'Z',
				'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
				'ž' => 'z',
				// Polish
				'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
				'Ż' => 'Z',
				'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
				'ż' => 'z',
				// Latvian
				'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
				'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
				'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
				'š' => 's', 'ū' => 'u', 'ž' => 'z'
		);
	
		// Make custom replacements
		$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
	
		// Transliterate characters to ASCII
		if ($options['transliterate']) {
			$str = str_replace(array_keys($char_map), $char_map, $str);
		}
	
		// Replace non-alphanumeric characters with our delimiter
		$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
	
		// Remove duplicate delimiters
		$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
	
		// Truncate slug to max. characters
		$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
	
		// Remove delimiter from ends
		$str = trim($str, $options['delimiter']);
	
		return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
	}

	public function generateseo(){
		$this->load->model('module/seo');
		$results = $this->model_module_seo->generateseo();
	}
	public function generatemetat(){
		$this->load->model('module/seo');
		$results = $this->model_module_seo->generatemetat();
	}
	public function generatemetak(){
		$this->load->model('module/seo');
		$results = $this->model_module_seo->generatemetak();
	}
	
	public function generatecseo(){
		$this->load->model('module/seo');
		$results = $this->model_module_seo->generatecseo();
	}
	public function generatecmetat(){
		$this->load->model('module/seo');
		$results = $this->model_module_seo->generatecmetat();
	}
	public function generatecmetak(){
		$this->load->model('module/seo');
		$results = $this->model_module_seo->generatecmetak();
	}
	
}