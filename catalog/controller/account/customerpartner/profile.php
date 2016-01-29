<?php
class ControllerAccountCustomerpartnerProfile extends Controller {
	private $error = array ();
	public function index() {
		if (! $this->customer->isLogged ()) {
			$this->session->data ['redirect'] = $this->url->link ( 'account/customerpartner/profile', '', 'SSL' );
			$this->response->redirect ( $this->url->link ( 'account/login', '', 'SSL' ) );
		}
		
		$this->load->model ( 'account/customerpartner' );
		
		// $customerRights = $this->model_account_customerpartner->getCustomerGroupRights($this->customer->getGroupId());
		$customerRights = $this->customer->getRights ();
		if ($customerRights && ! array_key_exists ( 'profile', $customerRights ['rights'] )) {
			$this->response->redirect ( $this->url->link ( 'account/account', '', 'SSL' ) );
		}
		
		$sellerId = $this->model_account_customerpartner->isSubUser ( $this->customer->getId () );
		if (! $customerRights ['isParent'] && ! $sellerId) {
			$data ['chkIsPartner'] = $this->model_account_customerpartner->chkIsPartner ();
		} else if ($sellerId) {
			$data ['chkIsPartner'] = true;
		} else {
			$data ['chkIsPartner'] = false;
		}
		
		if (! $data ['chkIsPartner'])
			$this->response->redirect ( $this->url->link ( 'account/account' ) );
		
		if ($sellerId) {
			$sellerId = $sellerId;
		} else {
			$sellerId = $this->customer->getId ();
		}
		
		$this->language->load ( 'account/customerpartner/profile' );
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->document->addScript ( 'admin/view/javascript/summernote/summernote.js' );
		$this->document->addStyle ( 'admin/view/javascript/summernote/summernote.css' );
		$this->document->addStyle ( 'catalog/view/theme/default/stylesheet/MP/sell.css' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST')) {
			$this->load->model ( 'account/customerpartner' );
			$this->model_account_customerpartner->updateProfile ( $this->request->post, $sellerId );
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			$this->response->redirect ( $this->url->link ( 'account/customerpartner/profile', '', 'SSL' ) );
		}
		
		$partner = $this->model_account_customerpartner->getProfile ( $sellerId );
		
		if ($partner) {
			
			$this->load->model ( 'tool/image' );
			
			if ($partner ['avatar'] && file_exists ( DIR_IMAGE . $partner ['avatar'] )) {
				$partner ['avatar'] = $this->model_tool_image->resize ( $partner ['avatar'], 100, 100 );
			} else if ($this->config->get ( 'marketplace_default_image_name' ) && file_exists ( DIR_IMAGE . $this->config->get ( 'marketplace_default_image_name' ) )) {
				$partner ['avatar'] = $this->model_tool_image->resize ( $this->config->get ( 'marketplace_default_image_name' ), 100, 100 );
			} else {
				$partner ['avatar'] = '';
			}
			
			if ($partner ['companybanner'] && file_exists ( DIR_IMAGE . $partner ['companybanner'] )) {
				$partner ['companybanner'] = $this->model_tool_image->resize ( $partner ['companybanner'], 100, 100 );
			} else if ($this->config->get ( 'marketplace_default_image_name' ) && file_exists ( DIR_IMAGE . $this->config->get ( 'marketplace_default_image_name' ) )) {
				$partner ['companybanner'] = $this->model_tool_image->resize ( $this->config->get ( 'marketplace_default_image_name' ), 100, 100 );
			} else {
				$partner ['companybanner'] = '';
			}
			
			if ($partner ['companylogo'] && file_exists ( DIR_IMAGE . $partner ['companylogo'] )) {
				$partner ['companylogo'] = $this->model_tool_image->resize ( $partner ['companylogo'], 100, 100 );
			} else if ($this->config->get ( 'marketplace_default_image_name' ) && file_exists ( DIR_IMAGE . $this->config->get ( 'marketplace_default_image_name' ) )) {
				$partner ['companylogo'] = $this->model_tool_image->resize ( $this->config->get ( 'marketplace_default_image_name' ), 100, 100 );
			} else {
				$partner ['companylogo'] = '';
			}
			
			if (! $partner ['countrylogo'] || ! file_exists ( DIR_IMAGE . '../' . $partner ['countrylogo'] )) {
				if ($this->config->get ( 'marketplace_default_image_name' ) && file_exists ( DIR_IMAGE . $this->config->get ( 'marketplace_default_image_name' ) )) {
					$partner ['countrylogo'] = $this->model_tool_image->resize ( $this->config->get ( 'marketplace_default_image_name' ), 20, 20 );
				}
			} else {
				$partner ['countrylogo'] = HTTP_SERVER . $partner ['countrylogo'];
			}
			
			$data ['storeurl'] = $this->url->link ( 'customerpartner/profile&id=' . $sellerId, '', 'SSL' );
		}
		
		if ($this->config->get ( 'wk_seller_group_status' )) {
			$this->load->model ( 'account/customer_group' );
			$isMember = $this->model_account_customer_group->getSellerMembershipGroup ( $this->customer->getId () );
			if ($isMember) {
				$allowedAccountMenu = $this->model_account_customer_group->getprofileOption ( $isMember ['gid'] );
				if ($allowedAccountMenu ['value']) {
					$accountMenu = explode ( ',', $allowedAccountMenu ['value'] );
					if ($accountMenu) {
						foreach ( $accountMenu as $key => $value ) {
							$values = explode ( ':', $value );
							$data ['allowed'] [$values [0]] = $values [1];
						}
					}
				}
			}
		} else if ($this->config->get ( 'marketplace_allowedprofilecolumn' )) {
			$data ['allowed'] = $this->config->get ( 'marketplace_allowedprofilecolumn' );
		}
		
		$data ['partner'] = $partner;
		
		$data ['countries'] = $this->model_account_customerpartner->getCountry ();
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home' ),
				'separator' => false 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_account' ),
				'href' => $this->url->link ( 'account/account' ),
				'separator' => $this->language->get ( 'text_separator' ) 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'heading_title' ),
				'href' => $this->url->link ( 'account/customerpartner/profile', '', 'SSL' ),
				'separator' => $this->language->get ( 'text_separator' ) 
		);
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		$data ['text_firstname'] = $this->language->get ( 'text_firstname' );
		$data ['text_lastname'] = $this->language->get ( 'text_lastname' );
		$data ['text_email'] = $this->language->get ( 'text_email' );
		
		// Tab
		$data ['tab_general'] = $this->language->get ( 'tab_general' );
		$data ['tab_profile_details'] = $this->language->get ( 'tab_profile_details' );
		$data ['tab_paymentmode'] = $this->language->get ( 'tab_paymentmode' );
		$data ['text_general'] = $this->language->get ( 'text_general' );
		$data ['text_profile_info'] = $this->language->get ( 'text_profile_info' );
		$data ['text_paymentmode'] = $this->language->get ( 'text_paymentmode' );
		
		// profile
		$data ['text_screen_name'] = $this->language->get ( 'text_screen_name' );
		$data ['text_gender'] = $this->language->get ( 'text_gender' );
		$data ['text_short_profile'] = $this->language->get ( 'text_short_profile' );
		$data ['text_avatar'] = $this->language->get ( 'text_avatar' );
		$data ['text_twitter_id'] = $this->language->get ( 'text_twitter_id' );
		$data ['text_facebook_id'] = $this->language->get ( 'text_facebook_id' );
		$data ['text_theme_background_color'] = $this->language->get ( 'text_theme_background_color' );
		$data ['text_company_banner'] = $this->language->get ( 'text_company_banner' );
		$data ['text_company_logo'] = $this->language->get ( 'text_company_logo' );
		$data ['text_company_locality'] = $this->language->get ( 'text_company_locality' );
		$data ['text_company_name'] = $this->language->get ( 'text_company_name' );
		$data ['text_company_description'] = $this->language->get ( 'text_company_description' );
		$data ['text_country_logo'] = $this->language->get ( 'text_country_logo' );
		$data ['text_otherpayment'] = $this->language->get ( 'text_otherpayment' );
		$data ['text_payment_mode'] = $this->language->get ( 'text_payment_mode' );
		$data ['text_profile'] = $this->language->get ( 'text_profile' );
		$data ['text_payment_detail'] = $this->language->get ( 'text_payment_detail' );
		$data ['text_account_information'] = $this->language->get ( 'text_account_information' );
		$data ['hover_avatar'] = $this->language->get ( 'hover_avatar' );
		$data ['hover_banner'] = $this->language->get ( 'hover_banner' );
		$data ['hover_company_logo'] = $this->language->get ( 'hover_company_logo' );
		$data ['text_sef_url'] = $this->language->get ( 'text_sef_url' );
		$data ['text_male'] = $this->language->get ( 'text_male' );
		$data ['text_female'] = $this->language->get ( 'text_female' );
		$data ['text_view_profile'] = $this->language->get ( 'text_view_profile' );
		
		$data ['warning_become_seller'] = $this->language->get ( 'warning_become_seller' );
		$data ['text_product_details'] = $this->language->get ( 'text_product_details' );
		
		$data ['button_continue'] = $this->language->get ( 'button_continue' );
		$data ['button_back'] = $this->language->get ( 'button_back' );
		
		$data ['customer_details'] = array (
				'firstname' => $partner ['firstname'],
				'lastname' => $partner ['lastname'],
				'email' => $partner ['email'] 
		);
		
		if (isset ( $this->error ['warning'] )) {
			$data ['error_warning'] = $this->error ['warning'];
		} else {
			$data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$data ['success'] = $this->session->data ['success'];
			unset ( $this->session->data ['success'] );
		} else {
			$data ['success'] = '';
		}
		
		$data ['action'] = $this->url->link ( 'account/customerpartner/profile', '', 'SSL' );
		$data ['back'] = $this->url->link ( 'account/account', '', 'SSL' );
		$data ['view_profile'] = $this->url->link ( 'customerpartner/profile&id=' . $sellerId, '', 'SSL' );
		
		$data ['isMember'] = true;
		if ($this->config->get ( 'wk_seller_group_status' )) {
			$data ['wk_seller_group_status'] = true;
			$this->load->model ( 'account/customer_group' );
			$isMember = $this->model_account_customer_group->getSellerMembershipGroup ( $this->customer->getId () );
			if ($isMember) {
				$allowedAccountMenu = $this->model_account_customer_group->getaccountMenu ( $isMember ['gid'] );
				if ($allowedAccountMenu ['value']) {
					$accountMenu = explode ( ',', $allowedAccountMenu ['value'] );
					if ($accountMenu && ! in_array ( 'profile:profile', $accountMenu )) {
						$data ['isMember'] = false;
					}
				}
			} else {
				$data ['isMember'] = false;
			}
		}
		
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['column_right'] = $this->load->controller ( 'common/column_right' );
		$data ['content_top'] = $this->load->controller ( 'common/content_top' );
		$data ['content_bottom'] = $this->load->controller ( 'common/content_bottom' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		$data ['header'] = $this->load->controller ( 'common/header' );
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/account/customerpartner/profile.tpl' )) {
			$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/account/customerpartner/profile.tpl', $data ) );
		} else {
			$this->response->setOutput ( $this->load->view ( 'default/template/account/customerpartner/profile.tpl', $data ) );
		}
	}
}
?>
