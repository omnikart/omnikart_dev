<?php
class ControllerAccountCdpe extends Controller {
	private $error = array ();
	public function index() {
		$this->load->model ( "account/customerpartner" );
		
		$this->checkuser ();
		 $sellerId = $this->model_account_customerpartner->isSubUser($this->customer->getId());
		 if($sellerId) {
					$sellerId = $sellerId;
		 } else {
					$sellerId = $this->customer->getId();
		 }	
		if (isset ( $this->session->data ['success'] )) {
			$data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$data ['success'] = '';
		}
		
		if (isset ( $this->request->get ['category_id'] )) {
			$filter ['category_id'] = $this->request->get ['category_id'];
		}
	
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		$this->load->model ( 'account/cd' );
		
		$this->load->language ( 'product/category' );
		$this->load->language ( 'product/gp_grouped' );

		$this->load->model ( 'catalog/category' );
		
		$this->load->model ( 'catalog/product' );
		
		$this->load->model ( 'tool/image' );
		
		$data ['text_refine'] = $this->language->get ( 'text_refine' );
		$data ['text_empty'] = $this->language->get ( 'text_empty' );
		$data ['text_quantity'] = $this->language->get ( 'text_quantity' );
		$data ['text_manufacturer'] = $this->language->get ( 'text_manufacturer' );
		$data ['text_model'] = $this->language->get ( 'text_model' );
		$data ['text_price'] = $this->language->get ( 'text_price' );
		$data ['text_tax'] = $this->language->get ( 'text_tax' );
		$data ['text_points'] = $this->language->get ( 'text_points' );
		$data ['text_compare'] = sprintf ( $this->language->get ( 'text_compare' ), (isset ( $this->session->data ['compare'] ) ? count ( $this->session->data ['compare'] ) : 0) );
		$data ['text_sort'] = $this->language->get ( 'text_sort' );
		$data ['text_limit'] = $this->language->get ( 'text_limit' );
		$data['button_add_to_cart'] = $this->language->get('button_add_to_cart');
		$data['button_add_to_quote'] = $this->language->get('button_add_to_quote');
		$data ['button_cart'] = $this->language->get ( 'button_cart' );
		$data ['button_wishlist'] = $this->language->get ( 'button_wishlist' );
		$data ['button_compare'] = $this->language->get ( 'button_compare' );
		$data ['button_continue'] = $this->language->get ( 'button_continue' );
		$data ['button_list'] = $this->language->get ( 'button_list' );
		$data ['button_grid'] = $this->language->get ( 'button_grid' );
		$data['text_related'] = $this->language->get('text_related');
		$data['text_loading'] = $this->language->get('text_loading');
		$data ['dbe'] = true;
		$data ['products'] = array ();

		$gp_child_option_col = false;
		
		$start = ($page-1)*50;
		$limit = 50;
		
		$totalproduct = $this->model_account_cd->gettotalProducts ();
		$results = $this->model_account_cd->getAllProducts ($start,$limit);
		foreach ( $results as $result ) {
			$product_info = $this->model_catalog_product->getProduct ( $result ['product_id'] );
			if ($this->config->get ( 'gp_grouped_child_nocart' ) && ! $this->config->get ( 'config_stock_checkout' ) && $child_info ['quantity'] <= 0) {
				$child_child_nocart = true;
			} else {
				$child_child_nocart = false;
			}
			
			if ($product_info) {
				
				$product_vendor = $this->model_account_customerpartner->getSupplierProduct($product_info['product_id'],$product_info['vendor_id']);

				if ($product_info ['image']) {
					$image = $this->model_tool_image->resize ( $product_info ['image'], 70,70 );
				} else {
					$image = $this->model_tool_image->resize ( 'placeholder.png', 70,70);
				}
				if (( float ) $product_info ['special']) {
					$special = $this->currency->format ( $this->tax->calculate ( $product_info ['special'], $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
				} else {
					$special = false;
				}		
				$enabled = false;
				$minimum = 0;
				$child_discounts = array();
		
				if ($product_vendor) {				
					if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
						$price = $this->currency->format ( $this->tax->calculate ( $product_vendor ['price'], $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
					} else {
						$price = false;
					}

					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_vendor['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
						$original_price  = 0;
						$discount = 0;
						if ($product_vendor['price'] < $product_info['price']) {
							$original_price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
							$discount = (int)(($product_info['price'] - $product_vendor['price'])*100/$product_info['price']);
						}
					} else {
						$price = false;
					}

					foreach ($this->model_catalog_product->getSupplierProductDiscounts($product_vendor['id']) as $discount) {
						$child_discounts[] = array(
								'quantity' => $discount['quantity'],
								'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], true))
						);
					}

					if ($this->config->get ( 'config_tax' )) {
						$tax = $this->currency->format ( ( float ) $product_info ['special'] ? $product_info ['special'] : $product_vendor ['price'] );
					} else {
						$tax = false;
					}
					
					if ($this->config->get ( 'config_review_status' )) {
						$rating = ( int ) $product_info ['rating'];
					} else {
						$rating = false;
					}
					$minimum = $product_vendor['minimum'];
					$curr_vendor = $this->model_account_customerpartner->getProfile($product_info['vendor_id']);
					$vendors = $this->model_account_customerpartner->getProductVendors($product_info['product_id'],$product_info['vendor_id']);
					if (!empty($curr_vendor)) {
						$vlink = $this->url->link('customerpartner/profile','id='.$curr_vendor['customer_id'],'SSL');
					}
					$enabled = true;
					$supplier_options = $this->model_account_customerpartner->getSupplierProductOptions($product_vendor['id']);
				}
				
				$qty_now = '';
				foreach ( $this->cart->getProducts () as $gp_cart ) {
					if ($product_info ['product_id'] == $gp_cart ['product_id']) {
						$qty_now = $gp_cart ['quantity'];
					}
				}
				
				$child_attributes = $this->model_catalog_product->getProductAttributes($product_info['product_id']);

				foreach ($child_attributes as $key => $ag){
					$attributenames = array();
					if (!isset($agnames[$key]['a'])) $agnames[$key]['a'] = array();
					$agnames[$key]['name'] = $ag['name'];
					foreach ($ag['attribute'] as $key2 => $a) {$agnames[$key]['a'][$key2] = $a['name'];} 
				}	
				$data ['products'] [] = array (
						'product_id' => $product_info ['product_id'],
						'info'       => $product_info,
						'thumb' => $image,
						'name' => $product_info ['name'],
						'description' => utf8_substr ( strip_tags ( html_entity_decode ( $product_info ['description'], ENT_QUOTES, 'UTF-8' ) ), 0, $this->config->get ( 'config_product_description_length' ) ) . '..',
						'price' => $price,
						'original_price' => $original_price,
						'discount' => $discount,
						'special' => $special,
						'attributes' => $child_attributes,
						'quantity' => $result ['quantity'],
						'tax' => $tax,
						'nocart' => $child_child_nocart,
						'enabled'	  => $enabled,
						'remove' => $this->url->link ( 'account/cdp/removeproduct', '&product_id=' . $product_info ['product_id'] ),
						'minimum' => $minimum,
						'discounts'  => $child_discounts,					
						'rating' => $product_info ['rating'],
						'href' => $this->url->link ( 'product/product', '&product_id=' . $product_info ['product_id'] ),
						'type'		  => $product_info['type'],
						'curr_vendor'=> $curr_vendor,
						'contract_quantity'=>$result['contract_quantity'],
						'vlink'		 => $vlink,
						'qty_now' => $qty_now,
						'stock' => $product_vendor['stock_status'],
						'vendors'	 => $vendors,
						'purchase' => $this->model_account_cd->getproductsale($sellerId,$product_info ['product_id'])
				);
			}
		}
		
		$data ['column_gp_name'] = $this->language->get ( 'column_gp_name' );
		$data ['column_gp_option'] = $gp_child_option_col ? $this->language->get ( 'column_gp_option' ) : false;
		$data ['column_gp_qty'] = $this->language->get ( 'column_gp_qty' );
		$data ['gp_child_info'] = array ();
		if ($this->config->get ( 'gp_grouped_child_info' )) {
			foreach ( $this->config->get ( 'gp_grouped_child_info' ) as $field ) {
				$data ['gp_child_info'] [$field] = $this->language->get ( 'text_gp_child_' . $field );
			}
		}

			$gp_child_attributes = $this->config->get('gp_grouped_child_attribute');
								
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home' ) 
		);
		
		
		$this->document->setTitle ("All Products");
		$this->document->addStyle ( 'catalog/view/theme/default/stylesheet/gp_grouped_default.css' );
		
		$pagination = new Pagination ();
		$pagination->total = $totalproduct;
		$pagination->page = $page;
		$pagination->limit = 50;
		$pagination->url = $this->url->link ( 'account/cdpe', 'page={page}', 'SSL' );
		
		$data ['pagination'] = $pagination->render ();
			
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['column_right'] = $this->load->controller ( 'common/column_right' );
		$data ['content_top'] = $this->load->controller ( 'common/content_top' );
		$data ['content_bottom'] = $this->load->controller ( 'common/content_bottom' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		$data ['header'] = $this->load->controller ( 'common/header' );


		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/account/cdpe.tpl' )) {
			$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/account/cdpe.tpl', $data ) );
		} else {
			$this->response->setOutput ( $this->load->view ( 'default/template/account/cdpe.tpl', $data ) );
		}
	}
	public function updateproducts() {
		$json = array ();
		$this->load->model ( "account/customerpartner" );
		$this->checkuser ();
		$this->load->model ( 'account/cd' );
		if ($this->request->post && isset ( $this->request->post ['products'] )) {
			foreach ( $this->request->post ['products'] as $product ) {
				if (! isset ( $product ['product_id'] ) && ! isset ( $product ['contract_quantity'] )) {
					$json ['error'] = 'Error Receiving Data';
					continue;
				}
			}
			
			if (! $json) {
				$this->model_account_cd->updateproducts ( $this->request->post );
				$json ['success'] = "Updates Successfully";
			}
		}
		$this->response->addHeader ( 'Content-Type: application/json' );
		$this->response->setOutput ( json_encode ( $json ) );
	}
	private function checkuser() {
		if (! $this->customer->isLogged ()) {
			$this->session->data ['redirect'] = $this->url->link ( 'account/account', '', 'SSL' );
			$this->response->redirect ( $this->url->link ( 'account/login', '', 'SSL' ) );
			return false;
		}
		
		// $customerRights = $this->model_account_customerpartner->getCustomerGroupRights($this->customer->getGroupId());
		$customerRights = $this->customer->getRights ();
		if (! (isset ( $customerRights ['rights'] ) && in_array ( 'db', $customerRights ['rights'] ))) {
			$this->session->data ['redirect'] = $this->url->link ( 'account/account', '', 'SSL' );
			$this->response->redirect ( $this->url->link ( 'account/account', '', 'SSL' ) );
			return false;
		}
		
		return true;
	}
}
