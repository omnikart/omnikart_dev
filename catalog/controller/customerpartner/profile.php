<?php
class ControllerCustomerpartnerProfile extends Controller {

	private $error = array();
	private $data = array();

	public function index() {

		if(!isset($this->request->get['id']))
			$this->request->get['id'] = 0;
		
		$this->data['id'] = $this->request->get['id'];

		$this->load->model('tool/image');	

		$this->load->model('customerpartner/master');	

		$this->language->load('customerpartner/profile');
		
		$this->document->setTitle($this->language->get('heading_title'));
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_store'] = $this->language->get('text_store');
		$this->data['text_collection'] = $this->language->get('text_collection');
		$this->data['text_location'] = $this->language->get('text_location');
		$this->data['text_reviews'] = $this->language->get('text_reviews');
		$this->data['text_product_reviews'] = $this->language->get('text_product_reviews');
		$this->data['text_profile'] = $this->language->get('text_profile');
		$this->data['text_from']	=	$this->language->get('text_from');
		$this->data['text_seller']	=	$this->language->get('text_seller');
		$this->data['text_total_products']	=	$this->language->get('text_total_products');		

		$this->language->load('customerpartner/feedback');
	
		$this->data['text_write'] = $this->language->get('text_write');
		$this->data['text_note'] = $this->language->get('text_note');
		$this->data['entry_bad'] = $this->language->get('entry_bad');
		$this->data['entry_good'] = $this->language->get('entry_good');
		$this->data['entry_captcha'] = $this->language->get('entry_captcha');
		$this->data['text_loading'] = $this->language->get('text_loading');
		$this->data['text_nickname'] = $this->language->get('text_nickname');
		$this->data['text_review'] = $this->language->get('text_review');
		$this->data['text_no_feedbacks'] = $this->language->get('text_no_feedbacks');		
		$this->data['text_price'] = $this->language->get('text_price');
		$this->data['text_value'] = $this->language->get('text_value');
		$this->data['text_quality'] = $this->language->get('text_quality');		
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['text_login'] = $this->language->get('text_login');


		$this->language->load('module/marketplace');

		$this->data['text_ask_admin'] = $this->language->get('text_ask_admin');
		$this->data['text_ask_question'] = $this->language->get('text_ask_question');
		$this->data['text_close'] = $this->language->get('text_close');
		$this->data['text_subject'] = $this->language->get('text_subject');
		$this->data['text_ask'] = $this->language->get('text_ask');
		$this->data['text_send'] = $this->language->get('text_send');
		$this->data['text_error_mail'] = $this->language->get('text_error_mail');		
		$this->data['text_success_mail'] = $this->language->get('text_success_mail');		
		$this->data['text_ask_seller']	=	$this->language->get('text_ask_seller');

		$this->data['logged'] = $this->customer->isLogged();
		$this->data['send_mail'] = $this->url->link('account/customerpartner/sendmail','','SSL'); 
		$this->data['mail_for'] = '&contact_seller=true';
		
      	if(isset($this->request->get['collection'])) {
      		$this->data['showCollection'] = true;
      	} else {
      		$this->data['showCollection'] = false;
      	}

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),     	
        	'separator' => false
      	);	

		if($this->config->get('wk_seller_group_status')) {
			$this->load->model('account/customer_group');
			$isMember = $this->model_account_customer_group->getSellerMembershipGroup($this->customer->getId());
			if($isMember) {
				$allowedAccountMenu = $this->model_account_customer_group->getpublicSellerProfile($isMember['gid']);
				if($allowedAccountMenu['value']) {
					$accountMenu = explode(',',$allowedAccountMenu['value']);
					if($accountMenu) {
						foreach ($accountMenu as $key => $value) {
							$values = explode(':',$value);
							$this->data['public_seller_profile'][$values[0]] = $values[1];
						}
					}
				}
			}
		} else if($this->config->get('marketplace_allowed_public_seller_profile')) {
			$this->data['public_seller_profile'] = $this->config->get('marketplace_allowed_public_seller_profile');
		}

      	// if($this->config->get('marketplace_profile_profile')) {
      	// 	$data['profile_tab'] = $this->config->get('marketplace_profile_profile');
      	// }

      	// if($this->config->get('marketplace_profile_store')) {
      	// 	$this->data['store_tab'] = $this->config->get('marketplace_profile_store');
      	// }

      	// if($this->config->get('marketplace_profile_collection')) {
      	// 	$this->data['collection_tab'] = $this->config->get('marketplace_profile_collection');
      	// }

      	// if($this->config->get('marketplace_profile_review')) {
      	// 	$this->data['review_tab'] = $this->config->get('marketplace_profile_review');
      	// }

      	// if($this->config->get('marketplace_profile_product_review')) {
      	// 	$this->data['product_review_tab'] = $this->config->get('marketplace_profile_product_review');
      	// }

      	// if($this->config->get('marketplace_profile_location')) {
      	// 	$this->data['location_tab'] = $this->config->get('marketplace_profile_location');
      	// }

		$partner = $this->model_customerpartner_master->getProfile($this->request->get['id']);

		if(!$partner)
			$this->response->redirect($this->url->link('error/not_found'));

		if ($partner['companybanner'] && file_exists(DIR_IMAGE . $partner['companybanner'])) {
			// $partner['companybanner'] = $this->model_tool_image->resize($partner['companybanner'], 1130, 250,'h');
			$partner['companybanner'] = HTTP_SERVER.'image/'.$partner['companybanner'];
		} else {
			$partner['companybanner'] = HTTP_SERVER.'image/'.$this->config->get('marketplace_default_image_name');
		}

		if ($partner['companylogo'] && file_exists(DIR_IMAGE . $partner['companylogo'])) {
			$partner['companylogo'] = $this->model_tool_image->resize($partner['companylogo'], 120, 120);
			// $partner['avatar'] = HTTP_SERVER.'image/'.$partner['avatar'];			
		} else if($this->config->get('marketplace_default_image_name') && file_exists(DIR_IMAGE . $this->config->get('marketplace_default_image_name'))) {
					$partner['companylogo'] = $this->model_tool_image->resize($this->config->get('marketplace_default_image_name'), 120, 120);
			}

		if ($partner['avatar'] && file_exists(DIR_IMAGE . $partner['avatar'])) {
			$partner['avatar'] = $this->model_tool_image->resize($partner['avatar'], 200, 200);
			// $partner['avatar'] = HTTP_SERVER.'image/'.$partner['avatar'];			
		} else if($this->config->get('marketplace_default_image_name') && file_exists(DIR_IMAGE . $this->config->get('marketplace_default_image_name'))) {
					$partner['avatar'] = $this->model_tool_image->resize($this->config->get('marketplace_default_image_name'), 200, 200);
			}

		$this->data['partner'] = $partner;

		$this->data['feedback_total'] = $this->model_customerpartner_master->getTotalFeedback($this->request->get['id']);
		$this->data['seller_total_products'] = $this->model_customerpartner_master->getPartnerCollectionCount($this->request->get['id']);

		$this->data['loadLocation'] = $this->url->link('customerpartner/profile/loadLocation&location='.$partner['companylocality'],'','SSL');
		$this->data['feedback'] = $this->url->link('customerpartner/profile/feedback&id='.$this->request->get['id'],'','SSL');
		$this->data['writeFeedback'] = $this->url->link('customerpartner/profile/writeFeedback&id='.$this->request->get['id'],'','SSL');
		$this->data['product_feedback'] = $this->url->link('customerpartner/profile/productFeedback&id='.$this->request->get['id'],'','SSL');
		$this->data['collection'] = $this->url->link('customerpartner/profile/collection&id='.$this->request->get['id'],'','SSL');

		$this->data['product_feedback_total'] = $this->model_customerpartner_master->getTotalProductFeedbackList($this->request->get['id']);
		$this->data['collection_total'] = $this->model_customerpartner_master->getPartnerCollectionCount($this->request->get['id']);

		$this->session->data['redirect'] = $this->url->link('customerpartner/profile&id='.$this->request->get['id'],'','SSL');
		$this->data['login'] = $this->url->link('account/login','','SSL');
		$this->data['seller_id'] = $this->request->get['id'];
		$this->data['isLogged'] = $this->customer->isLogged();

		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/customerpartner/profile.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/customerpartner/profile.tpl', $this->data));
		} else {
			$this->response->setOutput($this->load->view('default/template/customerpartner/profile.tpl', $this->data));
		}

	}

	//for location tab
	public function loadLocation(){

		if($this->request->get['location']){
			$location = '<iframe id="seller-location" width="100%" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q='.$this->request->get['location'].'&amp;output=embed"></iframe>';

			$this->response->setOutput($location);
		}else{
			$this->response->setOutput('No location added by Seller.');
		}
	}

	//for feedback tab	
	public function feedback(){

		if(!isset($this->request->get['id']))
			$this->request->get['id'] = 0;

		$page = 1;

		$this->language->load('customerpartner/feedback');
	
		$this->data['text_write'] = $this->language->get('text_write');
		$this->data['text_note'] = $this->language->get('text_note');
		$this->data['entry_bad'] = $this->language->get('entry_bad');
		$this->data['entry_good'] = $this->language->get('entry_good');
		$this->data['entry_captcha'] = $this->language->get('entry_captcha');
		$this->data['text_loading'] = $this->language->get('text_loading');
		$this->data['text_nickname'] = $this->language->get('text_nickname');
		$this->data['text_review'] = $this->language->get('text_review');

		$this->data['text_no_feedbacks'] = $this->language->get('text_no_feedbacks');		
		$this->data['text_price'] = $this->language->get('text_price');
		$this->data['text_value'] = $this->language->get('text_value');
		$this->data['text_quality'] = $this->language->get('text_quality');		
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['text_login'] = $this->language->get('text_login');
		
		$this->data['action'] = $this->url->link('customerpartner/profile/feedback','&id='.$this->request->get['id'],'SSL');

		$this->load->model('customerpartner/master');

		$this->data['feedbacks'] = $this->model_customerpartner_master->getFeedbackList($this->request->get['id']);
			
		$feedback_total = $this->model_customerpartner_master->getTotalFeedback($this->request->get['id']);

		$this->data['results'] = sprintf($this->language->get('text_pagination'), ($feedback_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($feedback_total - 5)) ? $feedback_total : ((($page - 1) * 5) + 5), $feedback_total, ceil($feedback_total / 5));

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/customerpartner/feedback.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/customerpartner/feedback.tpl', $this->data));
		} else {
			$this->response->setOutput($this->load->view('default/template/customerpartner/feedback.tpl', $this->data));
		}

	}

	//for product feedback tab	
	public function productFeedback(){

		if(!isset($this->request->get['id']))
			$this->request->get['id'] = 0;

		$page = 1;

		$this->language->load('customerpartner/feedback');
	
		$this->data['text_write'] = $this->language->get('text_write');
		$this->data['text_note'] = $this->language->get('text_note');
		$this->data['entry_bad'] = $this->language->get('entry_bad');
		$this->data['entry_good'] = $this->language->get('entry_good');
		$this->data['entry_captcha'] = $this->language->get('entry_captcha');
		$this->data['text_loading'] = $this->language->get('text_loading');
		$this->data['text_nickname'] = $this->language->get('text_nickname');
		$this->data['text_review'] = $this->language->get('text_review');

		$this->data['text_no_reviews'] = $this->language->get('text_no_feedbacks');		
		$this->data['text_price'] = $this->language->get('text_price');
		$this->data['text_value'] = $this->language->get('text_value');
		$this->data['text_quality'] = $this->language->get('text_quality');		
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['text_login'] = $this->language->get('text_login');
		
		$this->load->model('customerpartner/master');

		//use same variable name to use product review tpl
		$this->data['reviews'] = $this->model_customerpartner_master->getProductFeedbackList($this->request->get['id']);
			
		$product_feedback_total = $this->model_customerpartner_master->getTotalProductFeedbackList($this->request->get['id']);

		$this->data['pagination'] = '';

		$this->data['results'] = sprintf($this->language->get('text_pagination'), ($product_feedback_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($product_feedback_total - 5)) ? $product_feedback_total : ((($page - 1) * 5) + 5), $product_feedback_total, ceil($product_feedback_total / 5));

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/review.tpl', $this->data));
		} else {
			$this->response->setOutput($this->load->view('default/template/product/review.tpl', $this->data));
		}

	}

	public function writeFeedback() {

		$this->load->language('customerpartner/feedback');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {

			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (empty($this->request->post['quality_rating']) || $this->request->post['quality_rating'] < 0 || $this->request->post['quality_rating'] > 5) {
				$json['error'] = $this->language->get('error_quality_rating');
			}

			if (empty($this->request->post['price_rating']) || $this->request->post['price_rating'] < 0 || $this->request->post['price_rating'] > 5) {
				$json['error'] = $this->language->get('error_price_rating');
			}

			if (empty($this->request->post['value_rating']) || $this->request->post['value_rating'] < 0 || $this->request->post['value_rating'] > 5) {
				$json['error'] = $this->language->get('error_value_rating');
			}

			if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
				$json['error'] = $this->language->get('error_captcha');
			}

			unset($this->session->data['captcha']);

			if (!isset($json['error'])) {			
				$this->load->model('customerpartner/master');
				$this->model_customerpartner_master->saveFeedback($this->request->post,$this->request->get['id']);
				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');

		$this->response->setOutput(json_encode($json));
	}

	//for collection tab		
	public function collection() {

		if(!isset($this->request->get['id']))
			$this->request->get['id'] = 0;
		
		$this->load->model('tool/image');

		$this->load->model('catalog/category');

		$this->load->model('account/customerpartner');

		$this->load->model('customerpartner/master');

		$this->language->load('customerpartner/collection');

		$this->language->load('product/category');

		$this->data['text_refine'] = $this->language->get('text_refine');
		$this->data['text_empty'] = $this->language->get('text_no_products');
		$this->data['text_quantity'] = $this->language->get('text_quantity');
		$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$this->data['text_model'] = $this->language->get('text_model');
		$this->data['text_price'] = $this->language->get('text_price');
		$this->data['text_tax'] = $this->language->get('text_tax');
		$this->data['text_points'] = $this->language->get('text_points');
		$this->data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		$this->data['text_sort'] = $this->language->get('text_sort');
		$this->data['text_limit'] = $this->language->get('text_limit');

		$this->data['button_cart'] = $this->language->get('button_cart');
		$this->data['button_wishlist'] = $this->language->get('button_wishlist');
		$this->data['button_compare'] = $this->language->get('button_compare');
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_list'] = $this->language->get('button_list');
		$this->data['button_grid'] = $this->language->get('button_grid');

		$partner = $this->model_customerpartner_master->getProfile($this->request->get['id']);

		if(!$partner)
			$this->response->redirect($this->url->link('error/not_found'));		

		$this->data['compare'] = $this->url->link('product/compare' , '' . 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$url = "&id=".$this->request->get['id'];	


		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else { 
			$page = 1;
		}	
							
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_product_limit');
		}
	

		$filter_data = array(
			'customer_id'		 => $this->request->get['id'],
			'filter_category_id' => 0,
			'sort'               => $sort,
			'order'              => $order,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit,
			'filter_store' 		 => $this->config->get('config_store_id'),
			'filter_status'		 => 1
		);

		$this->data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			$children_data = array();

			$children = $this->model_catalog_category->getCategories($category['category_id']);

			foreach ($children as $child) {

				$filter_data ['filter_category_id']  = $child['category_id'];

				$products_in_category = $this->model_account_customerpartner->getTotalProductsSeller($filter_data);

				if($products_in_category)
					$children_data[] = array(
						'category_id' => $child['category_id'],
						'name'        => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $products_in_category . ')' : ''),
						'href'        => $this->url->link('customerpartner/profile/collection', 'path=' . $category['category_id'] . '_' . $child['category_id'].$url)
					);
			}

			$filter_data ['filter_category_id']  = $category['category_id'];

			$products_in_category = $this->model_account_customerpartner->getTotalProductsSeller($filter_data);

			if($products_in_category)			
				$this->data['categories'][] = array(
					'category_id' => $category['category_id'],
					'name'        => $category['name'] . ($this->config->get('config_product_count') ? ' (' . $products_in_category . ')' : ''),
					'children'    => $children_data,
					'href'        => $this->url->link('customerpartner/profile/collection', 'path=' . $category['category_id'].$url)
				);
		}		


		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$this->data['category_id'] = $category_id = $parts[0];
		} else {
			$this->data['category_id'] = $category_id = 0;
		}

		if (isset($parts[1])) {
			$this->data['child_id'] = $category_id = $parts[1];
		} else {
			$this->data['child_id'] = 0;
		}
			
		$filter_data ['filter_category_id']  = $category_id;

		if (isset($this->request->get['path'])) {
			$url .= '&path=' . $this->request->get['path'];
		}

		$results = $this->model_account_customerpartner->getProductsSeller($filter_data);

		$product_total = $this->model_account_customerpartner->getTotalProductsSeller($filter_data);

		$this->data['products'] = array();

		foreach ($results as $result) {

			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
			}

			if ((float)$result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}

			if ($this->config->get('config_tax')) {
				$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
			} else {
				$tax = false;
			}

			if ($this->config->get('config_review_status')) {
				$rating = (int)$result['rating'];
			} else {
				$rating = false;
			}

			$this->data['products'][] = array(
				'product_id'  => $result['product_id'],
				'thumb'       => $image,
				'name'        => $result['name'],
				'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
				'price'       => $price,
				'special'     => $special,
				'tax'         => $tax,
				'rating'      => $result['rating'],
				'href'        => $this->url->link('product/product', '&product_id=' . $result['product_id'] )
			);
		}				
								
		$this->data['sorts'] = array();
		
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_default'),
			'value' => 'p.sort_order-ASC',
			'href'  => $this->url->link('customerpartner/profile/collection', '&sort=p.sort_order&order=ASC' . $url)
		);
		
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_name_asc'),
			'value' => 'pd.name-ASC',
			'href'  => $this->url->link('customerpartner/profile/collection','&sort=pd.name&order=ASC' . $url)
		);

		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_name_desc'),
			'value' => 'pd.name-DESC',
			'href'  => $this->url->link('customerpartner/profile/collection', '&sort=pd.name&order=DESC' . $url)
		);

		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_price_asc'),
			'value' => 'p.price-ASC',
			'href'  => $this->url->link('customerpartner/profile/collection','&sort=p.price&order=ASC' . $url)
		); 

		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_price_desc'),
			'value' => 'p.price-DESC',
			'href'  => $this->url->link('customerpartner/profile/collection', '&sort=p.price&order=DESC' . $url)
		); 
		
		if ($this->config->get('config_review_status')) {
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'rating-DESC',
				'href'  => $this->url->link('customerpartner/profile/collection', '&sort=rating&order=DESC' . $url)
			); 
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'rating-ASC',
				'href'  => $this->url->link('customerpartner/profile/collection', '&sort=rating&order=ASC' . $url)
			);
		}
		
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_model_asc'),
			'value' => 'p.model-ASC',
			'href'  => $this->url->link('customerpartner/profile/collection','&sort=p.model&order=ASC' . $url)
		);

		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_model_desc'),
			'value' => 'p.model-DESC',
			'href'  => $this->url->link('customerpartner/profile/collection','&sort=p.model&order=DESC' . $url)
		);

		$url = "id=".$this->request->get['id'];

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['path'])) {
			$url .= '&path=' . $this->request->get['path'];
		}

		$this->data['limits'] = array();

		$limits = array_unique(array($this->config->get('config_product_limit'), 25, 50, 75, 100));

		sort($limits);

		foreach($limits as $value) {
			$this->data['limits'][] = array(
				'text'  => $value,
				'value' => $value,
				'href'  => $this->url->link('customerpartner/profile/collection', $url . '&limit=' . $value)
			);
		}
					
		$url = "id=".$this->request->get['id'];

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}	

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		if (isset($this->request->get['path'])) {
			$url .= '&path=' . $this->request->get['path'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('customerpartner/profile/collection' , $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->document->addLink($this->url->link('customerpartner/profile/collection', $url . '&page=' . $pagination->page), 'canonical');

		if ($pagination->limit && ceil($pagination->total / $pagination->limit) > $pagination->page) {
			$this->document->addLink($this->url->link('customerpartner/profile/collection', $url . '&page=' . ($pagination->page + 1)), 'next');
		}

		if ($pagination->page > 1) {
			$this->document->addLink($this->url->link('customerpartner/profile/collection', $url . '&page=' . ($pagination->page - 1)), 'prev');
		}
		
		$this->data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->data['limit'] = $limit;		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/customerpartner/collection.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/customerpartner/collection.tpl', $this->data));
		} else {
			$this->response->setOutput($this->load->view('default/template/customerpartner/collection.tpl', $this->data));
		}

	}

}
?>
