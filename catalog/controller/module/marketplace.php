<?php  
class ControllerModuleMarketplace extends Controller { 

	private $data = array();

	public function index() {

		$this->load->model('account/customerpartner');

		$this->load->model('customerpartner/master');

		$this->language->load('module/marketplace');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_my_profile'] = $this->language->get('text_my_profile');
		$this->data['text_addproduct'] = $this->language->get('text_addproduct');
		$this->data['text_wkshipping'] = $this->language->get('text_wkshipping');
		$this->data['text_productlist'] = $this->language->get('text_productlist');
		$this->data['text_dashboard'] = $this->language->get('text_dashboard');
		$this->data['text_orderhistory'] = $this->language->get('text_orderhistory');
		$this->data['text_becomePartner'] = $this->language->get('text_becomePartner');
		$this->data['text_download'] = $this->language->get('text_download');
		$this->data['text_transaction'] = $this->language->get('text_transaction');		

		$this->data['text_ask_admin'] = $this->language->get('text_ask_admin');
		$this->data['text_ask_question'] = $this->language->get('text_ask_question');
		$this->data['text_close'] = $this->language->get('text_close');
		$this->data['text_subject'] = $this->language->get('text_subject');
		$this->data['text_ask'] = $this->language->get('text_ask');
		$this->data['text_send'] = $this->language->get('text_send');
		$this->data['text_error_mail'] = $this->language->get('text_error_mail');		
		$this->data['text_success_mail'] = $this->language->get('text_success_mail');		
		$this->data['text_ask_seller']	=	$this->language->get('text_ask_seller');
		
		//for mp		
		$this->data['text_from']	=	$this->language->get('text_from');
		$this->data['text_seller']	=	$this->language->get('text_seller');
		$this->data['text_total_products']	=	$this->language->get('text_total_products');
		$this->data['text_tax']	=	$this->language->get('text_tax');
		$this->data['text_latest_product']	=	$this->language->get('text_latest_product');

		$this->data['button_cart']	=	$this->language->get('button_cart');
		$this->data['button_wishlist']	=	$this->language->get('button_wishlist');
		$this->data['button_compare']	=	$this->language->get('button_compare');
		
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['contact_mail'] = true;

		$this->data['send_mail'] = $this->url->link('account/customerpartner/sendmail','','SSL'); 

		//$customerRights = $this->model_account_customerpartner->getCustomerGroupRights($this->customer->getGroupId());
		$customerRights = $this->customer->getRights();
		if(isset($this->request->get['route']) AND (substr($this->request->get['route'],0,8)=='account/')) {

			if($this->config->get('marketplace_account_menu_sequence')) {
				$this->data['marketplace_account_menu_sequence'] = $this->config->get('marketplace_account_menu_sequence');
			}
			
			$this->data['isMember'] = false;
			if($this->config->get('wk_seller_group_status')) {
	      		$this->data['wk_seller_group_status'] = true;
	      		$this->load->model('account/customer_group');
				$isMember = $this->model_account_customer_group->getSellerMembershipGroup($this->customer->getId());
				if($isMember) {
					$allowedAccountMenu = $this->model_account_customer_group->getaccountMenu($isMember['gid']);
					if($allowedAccountMenu['value']) {
						$accountMenu = explode(',',$allowedAccountMenu['value']);
						foreach ($accountMenu as $key => $menu) {
							$aMenu = explode(':', $menu);	
							$this->data['marketplace_allowed_account_menu'][$aMenu[0]] = $aMenu[1];
						}
					}
					$this->data['isMember'] = true;
				} else {
					$this->data['isMember'] = false;
				}
	      	}

	      	if($this->config->get('marketplace_allowed_account_menu') && !$this->config->get('wk_seller_group_status')) {
				$this->data['marketplace_allowed_account_menu'] = $this->config->get('marketplace_allowed_account_menu');
			}

			$this->data['mail_for'] = '&contact_admin=true';
			$this->data['want_partner'] = $this->url->link('account/customerpartner/become_partner','','SSL');

			$this->data['account_menu_href'] = array(
				'profile' => $this->url->link('account/customerpartner/profile', '', 'SSL'),
				'dashboard' => $this->url->link('account/customerpartner/dashboard', '', 'SSL'),
				'orderhistory' => $this->url->link('account/customerpartner/orderlist', '', 'SSL'),
				'transaction' => $this->url->link('account/customerpartner/transaction', '', 'SSL'),
				'productlist' => $this->url->link('account/customerpartner/productlist', '', 'SSL'),
				'addproduct' => $this->url->link('account/customerpartner/addproduct', '', 'SSL'),
				'downloads' => $this->url->link('account/customerpartner/download', '', 'SSL'),
				'manageshipping' => $this->url->link('account/customerpartner/add_shipping_mod', '', 'SSL'),
				'asktoadmin' => $this->url->link('account/customerpartner/addproduct', '', 'SSL'),
			);

			$subUser = $this->model_account_customerpartner->isSubUser($this->customer->getId());

			if($subUser) {
				$this->data['chkIsPartner'] = $this->model_account_customerpartner->chkIsPartner($subUser);
			} else {
				$this->data['chkIsPartner'] = $this->model_account_customerpartner->chkIsPartner($this->customer->getId());
			}

			if( (!$customerRights['isParent'] && !$this->data['chkIsPartner']) || ($subUser && !$this->data['chkIsPartner'])  )  {
				$this->data['chkIsPartner'] = true;
				$this->data['account_menu_href']['become_partner'] = array();
				$this->data['marketplace_account_menu_sequence'] = array();
				$this->data['marketplace_allowed_account_menu'] = array();
				if(!$customerRights['isParent']) {
					$this->data['account_menu_href']['become_partner'] = $this->url->link('account/customerpartner/become_partner','','SSL');
					$this->data['marketplace_account_menu_sequence']['become_partner'] = $this->language->get('text_becomePartner');
					$this->data['marketplace_allowed_account_menu']['become_partner'] = 'become_partner';
				}
			} else if($subUser) {
				unset($this->data['marketplace_allowed_account_menu']['dashboard']);
				unset($this->data['marketplace_allowed_account_menu']['transaction']);
				unset($this->data['marketplace_allowed_account_menu']['manageshipping']);
				unset($this->data['marketplace_allowed_account_menu']['asktoadmin']);
				unset($this->data['marketplace_allowed_account_menu']['downloads']);
				if($customerRights && !array_key_exists('addproduct', $customerRights['rights']) ) {
					unset($this->data['marketplace_allowed_account_menu']['addproduct']);
				}
				if($customerRights && !array_key_exists('productlist', $customerRights['rights']) ) {
					unset($this->data['marketplace_allowed_account_menu']['productlist']);
				}
				if($customerRights && !array_key_exists('profile', $customerRights['rights']) ) {
					unset($this->data['marketplace_allowed_account_menu']['profile']);
				}
				if($customerRights && !array_key_exists('view-all-order', $customerRights['rights']) ) {
					unset($this->data['marketplace_allowed_account_menu']['orderhistory']);
				}
				$this->data['chkIsPartner'] = true;
			}

			if($customerRights && array_key_exists('create-user', $customerRights['rights'])) {
				// user registration entry
				$this->data['account_menu_href']['createuser'] = $this->url->link('account/customerpartner/userregistration', '', 'SSL');
				$this->data['marketplace_account_menu_sequence']['createuser'] = $this->language->get('text_createuser');;
				$this->data['marketplace_allowed_account_menu']['createuser'] = 'createuser';

				// user list entry
				$this->data['account_menu_href']['userlist'] = $this->url->link('account/customerpartner/userlist', '', 'SSL');
				$this->data['marketplace_account_menu_sequence']['userlist'] = $this->language->get('text_userlist');;
				$this->data['marketplace_allowed_account_menu']['userlist'] = 'userlist';

				$this->data['account_menu_href']['orderreview'] = $this->url->link('account/customerpartner/orderReview','','SSL');
				$this->data['marketplace_account_menu_sequence']['orderreview'] = $this->language->get('text_reviewproduct');
				$this->data['marketplace_allowed_account_menu']['orderreview'] = 'orderreview';
			}

		}elseif(isset($this->request->get['route']) AND $this->request->get['route']=='product/product' AND isset($this->request->get['product_id'])){

			$this->data['mail_for'] = '&contact_seller=true';
			$this->data['text_ask_question'] = $this->language->get('text_ask_seller');

			if(!$this->data['logged'])
				$this->data['text_ask_seller'] = $this->language->get('text_ask_seller_log');
			if (isset($this->request->get['vendor_id']))
				$id['id'] = $this->request->get['vendor_id'];
			else 				
				$id = $this->model_customerpartner_master->getPartnerIdBasedonProduct($this->request->get['product_id']);
			
			$this->data['contact_mail'] = $this->config->get('marketplace_customercontactseller');

			if($this->config->get('marketplace_product_show_seller_product')) {
				$this->data['show_seller_product'] = $this->config->get('marketplace_product_show_seller_product');
			} else {
				$this->data['show_seller_product'] = false;
			}

			if(isset($id['id']) AND $id['id']){

				$partner = $this->model_customerpartner_master->getProfile($id['id']);
				if($partner){

					if($this->config->get('marketplace_product_name_display')) {
						if($this->config->get('marketplace_product_name_display') == 'sn') {
							$this->data['displayName'] = $partner['firstname']." ".$partner['lastname'];
						} else if($this->config->get('marketplace_product_name_display') == 'cn') {
							$this->data['displayName'] = $partner['companyname'];
						} else {
							$this->data['displayName'] = $partner['companyname']." (".$partner['firstname']." ".$partner['lastname'].")";
						}
					}

					if($this->config->get('marketplace_product_image_display')) {
						$partner['companylogo'] = $partner[$this->config->get('marketplace_product_image_display')];
					}

					if ($partner['companylogo'] && file_exists(DIR_IMAGE . $partner['companylogo'])) {
						$partner['thumb'] = $this->model_tool_image->resize($partner['companylogo'], 120, 120);
						// $partner['avatar'] = HTTP_SERVER.'image/'.$partner['avatar'];			
					} else {
						$partner['thumb'] = $this->model_tool_image->resize('no_image.png', 200, 200);
					}

					$this->data['seller_id'] = $id['id'];

					$partner['sellerHref'] = $this->url->link('customerpartner/profile&id='.$id['id'],'','SSL');
					$this->data['collectionHref'] = $this->url->link('customerpartner/profile&id='.$id['id'],'&collection','SSL');
					$partner['name'] = $partner['firstname'].' '.$partner['lastname'];
					$partner['total_products'] = $this->model_customerpartner_master->getPartnerCollectionCount($id['id']);

					$this->data['partner'] = $partner;

					$filter_array = array( 'start' => 0,
										   'limit' => 4,
										   'customer_id' => $id['id'],
										   'filter_status' => 1,
										   'filter_store' => $this->config->get('config_store_id')

										   );

					$latest = $this->model_account_customerpartner->getProductsSeller($filter_array);

					$this->data['latest'] = array();

					if($latest)
						foreach($latest as $key => $result){

							if($result['product_id']==$this->request->get['product_id'])
								continue;						

							if ($result['image']) {
								$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
							} else {
								$image = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
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

							$this->data['latest'][] = array(
								'product_id'  => $result['product_id'],
								'thumb'       => $image,
								'name'        => $result['name'],
								'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
								'price'       => $price,
								'special'     => $special,
								'tax'         => $tax,
								'rating'      => $result['rating'],
								'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
							);

						}
				}

			}else
				return;

		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/marketplace.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/marketplace.tpl', $this->data);
		} else {
			return $this->load->view('default/template/module/marketplace.tpl', $this->data);
		}

	}
    
 
}
?>
