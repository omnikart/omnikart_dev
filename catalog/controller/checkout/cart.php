<?php
class ControllerCheckoutCart extends Controller {
	public function index() {
		$this->load->language ( 'checkout/cart' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'href' => $this->url->link ( 'common/home' ),
				'text' => $this->language->get ( 'text_home' ) 
		);
		
		$data ['breadcrumbs'] [] = array (
				'href' => $this->url->link ( 'checkout/cart' ),
				'text' => $this->language->get ( 'heading_title' ) 
		);
		
		if ($this->cart->hasProducts () || ! empty ( $this->session->data ['vouchers'] )) {
			$data ['heading_title'] = $this->language->get ( 'heading_title' );
			
			$data ['text_recurring_item'] = $this->language->get ( 'text_recurring_item' );
			$data ['text_next'] = $this->language->get ( 'text_next' );
			$data ['text_next_choice'] = $this->language->get ( 'text_next_choice' );
			
			$data ['column_image'] = $this->language->get ( 'column_image' );
			$data ['column_name'] = $this->language->get ( 'column_name' );
			$data ['column_model'] = $this->language->get ( 'column_model' );
			$data ['column_quantity'] = $this->language->get ( 'column_quantity' );
			$data ['column_price'] = $this->language->get ( 'column_price' );
			$data ['column_total'] = $this->language->get ( 'column_total' );
			
			$data ['button_update'] = $this->language->get ( 'button_update' );
			$data ['button_remove'] = $this->language->get ( 'button_remove' );
			$data ['button_shopping'] = $this->language->get ( 'button_shopping' );
			$data ['button_checkout'] = $this->language->get ( 'button_checkout' );
			
			if (! $this->cart->hasStock () && (! $this->config->get ( 'config_stock_checkout' ) || $this->config->get ( 'config_stock_warning' ))) {
				$data ['error_warning'] = $this->language->get ( 'error_stock' );
			} elseif (isset ( $this->session->data ['error'] )) {
				$data ['error_warning'] = $this->session->data ['error'];
				
				unset ( $this->session->data ['error'] );
			} else {
				$data ['error_warning'] = '';
			}
			
			if ($this->config->get ( 'config_customer_price' ) && ! $this->customer->isLogged ()) {
				$data ['attention'] = sprintf ( $this->language->get ( 'text_login' ), $this->url->link ( 'account/login' ), $this->url->link ( 'account/register' ) );
			} else {
				$data ['attention'] = '';
			}
			
			if (isset ( $this->session->data ['success'] )) {
				$data ['success'] = $this->session->data ['success'];
				
				unset ( $this->session->data ['success'] );
			} else {
				$data ['success'] = '';
			}
			
			$data ['action'] = $this->url->link ( 'checkout/cart/edit', '', true );
			
			if ($this->config->get ( 'config_cart_weight' )) {
				$data ['weight'] = $this->weight->format ( $this->cart->getWeight (), $this->config->get ( 'config_weight_class_id' ), $this->language->get ( 'decimal_point' ), $this->language->get ( 'thousand_point' ) );
			} else {
				$data ['weight'] = '';
			}
			
			$this->load->model ( 'tool/image' );
			$this->load->model ( 'tool/upload' );
			
			$data ['products'] = array ();
			
			$products = $this->cart->getProducts ();
			
			foreach ( $products as $product ) {
				$product_total = 0;
				
				foreach ( $products as $product_2 ) {
					if ($product_2 ['product_id'] == $product ['product_id']) {
						$product_total += $product_2 ['quantity'];
					}
				}
				
				if ($product ['minimum'] > $product_total) {
					$data ['error_warning'] = sprintf ( $this->language->get ( 'error_minimum' ), $product ['name'], $product ['minimum'] );
				}
				
				if ($product ['image']) {
					$image = $this->model_tool_image->resize ( $product ['image'], $this->config->get ( 'config_image_cart_width' ), $this->config->get ( 'config_image_cart_height' ) );
				} else {
					$image = '';
				}
				
				$option_data = array ();
				
				foreach ( $product ['option'] as $option ) {
					if ($option ['type'] != 'file') {
						$value = $option ['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode ( $option ['value'] );
						
						if ($upload_info) {
							$value = $upload_info ['name'];
						} else {
							$value = '';
						}
					}
					
					$option_data [] = array (
							'name' => $option ['name'],
							'value' => (utf8_strlen ( $value ) > 20 ? utf8_substr ( $value, 0, 20 ) . '..' : $value) 
					);
				}
				
				// Display prices
				if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
					$price = $this->currency->format ( $this->tax->calculate ( $product ['price'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
				} else {
					$price = false;
				}
				
				// Display prices
				if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
					$total = $this->currency->format ( $this->tax->calculate ( $product ['price'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) * $product ['quantity'] );
				} else {
					$total = false;
				}
				
				$recurring = '';
				
				if ($product ['recurring']) {
					$frequencies = array (
							'day' => $this->language->get ( 'text_day' ),
							'week' => $this->language->get ( 'text_week' ),
							'semi_month' => $this->language->get ( 'text_semi_month' ),
							'month' => $this->language->get ( 'text_month' ),
							'year' => $this->language->get ( 'text_year' ) 
					);
					
					if ($product ['recurring'] ['trial']) {
						$recurring = sprintf ( $this->language->get ( 'text_trial_description' ), $this->currency->format ( $this->tax->calculate ( $product ['recurring'] ['trial_price'] * $product ['quantity'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) ), $product ['recurring'] ['trial_cycle'], $frequencies [$product ['recurring'] ['trial_frequency']], $product ['recurring'] ['trial_duration'] ) . ' ';
					}
					
					if ($product ['recurring'] ['duration']) {
						$recurring .= sprintf ( $this->language->get ( 'text_payment_description' ), $this->currency->format ( $this->tax->calculate ( $product ['recurring'] ['price'] * $product ['quantity'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) ), $product ['recurring'] ['cycle'], $frequencies [$product ['recurring'] ['frequency']], $product ['recurring'] ['duration'] );
					} else {
						$recurring .= sprintf ( $this->language->get ( 'text_payment_cancel' ), $this->currency->format ( $this->tax->calculate ( $product ['recurring'] ['price'] * $product ['quantity'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) ), $product ['recurring'] ['cycle'], $frequencies [$product ['recurring'] ['frequency']], $product ['recurring'] ['duration'] );
					}
				}
				
				$data ['products'] [] = array (
						'key' => $product ['key'],
						'thumb' => $image,
						'name' => $product ['name'],
						'model' => $product ['model'],
						'option' => $option_data,
						'recurring' => $recurring,
						'quantity' => $product ['quantity'],
						'vendor_id' => $product ['vendor_id'],
						'stock' => $product ['stock'] ? true : ! (! $this->config->get ( 'config_stock_checkout' ) || $this->config->get ( 'config_stock_warning' )),
						'reward' => ($product ['reward'] ? sprintf ( $this->language->get ( 'text_points' ), $product ['reward'] ) : ''),
						'price' => $price,
						'total' => $total,
						'href' => $this->url->link ( 'product/product', 'product_id=' . $product ['product_id'] ) 
				);
			}
			
			// Gift Voucher
			$data ['vouchers'] = array ();
			
			if (! empty ( $this->session->data ['vouchers'] )) {
				foreach ( $this->session->data ['vouchers'] as $key => $voucher ) {
					$data ['vouchers'] [] = array (
							'key' => $key,
							'description' => $voucher ['description'],
							'amount' => $this->currency->format ( $voucher ['amount'] ),
							'remove' => $this->url->link ( 'checkout/cart', 'remove=' . $key ) 
					);
				}
			}
			
			// Totals
			$this->load->model ( 'extension/extension' );
			
			$total_data = array ();
			$total = 0;
			$taxes = $this->cart->getTaxes ();
			
			// Display prices
			if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
				$sort_order = array ();
				
				$results = $this->model_extension_extension->getExtensions ( 'total' );
				
				foreach ( $results as $key => $value ) {
					$sort_order [$key] = $this->config->get ( $value ['code'] . '_sort_order' );
				}
				
				array_multisort ( $sort_order, SORT_ASC, $results );
				
				foreach ( $results as $result ) {
					if ($this->config->get ( $result ['code'] . '_status' )) {
						$this->load->model ( 'total/' . $result ['code'] );
						
						$this->{'model_total_' . $result ['code']}->getTotal ( $total_data, $total, $taxes );
					}
				}
				
				$sort_order = array ();
				
				foreach ( $total_data as $key => $value ) {
					$sort_order [$key] = $value ['sort_order'];
				}
				
				array_multisort ( $sort_order, SORT_ASC, $total_data );
			}
			
			$data ['totals'] = array ();
			
			foreach ( $total_data as $total ) {
				$data ['totals'] [] = array (
						'title' => $total ['title'],
						'text' => $this->currency->format ( $total ['value'] ) 
				);
			}
			
			$data ['continue'] = $this->url->link ( 'common/home' );
			
			$data ['checkout'] = $this->url->link ( 'checkout/checkout', '', 'SSL' );
			
			$this->load->model ( 'extension/extension' );
			
			$data ['checkout_buttons'] = array ();
			
			$data ['coupon'] = $this->load->controller ( 'checkout/coupon' );
			$data ['voucher'] = $this->load->controller ( 'checkout/voucher' );
			$data ['reward'] = $this->load->controller ( 'checkout/reward' );
			$data ['shipping'] = $this->load->controller ( 'checkout/shipping' );
			$data ['column_left'] = $this->load->controller ( 'common/column_left' );
			$data ['column_right'] = $this->load->controller ( 'common/column_right' );
			$data ['content_top'] = $this->load->controller ( 'common/content_top' );
			$data ['content_bottom'] = $this->load->controller ( 'common/content_bottom' );
			$data ['footer'] = $this->load->controller ( 'common/footer' );
			$data ['header'] = $this->load->controller ( 'common/header' );
			
			if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/checkout/cart.tpl' )) {
				$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/checkout/cart.tpl', $data ) );
			} else {
				$this->response->setOutput ( $this->load->view ( 'default/template/checkout/cart.tpl', $data ) );
			}
		} else {
			$data ['heading_title'] = $this->language->get ( 'heading_title' );
			
			$data ['text_error'] = $this->language->get ( 'text_empty' );
			
			$data ['button_continue'] = $this->language->get ( 'button_continue' );
			
			$data ['continue'] = $this->url->link ( 'common/home' );
			
			unset ( $this->session->data ['success'] );
			
			$data ['column_left'] = $this->load->controller ( 'common/column_left' );
			$data ['column_right'] = $this->load->controller ( 'common/column_right' );
			$data ['content_top'] = $this->load->controller ( 'common/content_top' );
			$data ['content_bottom'] = $this->load->controller ( 'common/content_bottom' );
			$data ['footer'] = $this->load->controller ( 'common/footer' );
			$data ['header'] = $this->load->controller ( 'common/header' );
			
			if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/error/not_found.tpl' )) {
				$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/error/not_found.tpl', $data ) );
			} else {
				$this->response->setOutput ( $this->load->view ( 'default/template/error/not_found.tpl', $data ) );
			}
		}
	}
	public function add() {
		$this->load->language ( 'checkout/cart' );
		$this->load->language ( 'total/combo_products' );
		
		$json = array ();
		
		$cart_type = 'cart';
		
		if (isset ( $this->request->get ['cart'] )) {
			$cart_type = $this->request->get ['cart'];
		}
		
		if (isset ( $this->request->post ['product_id'] )) {
			$product_id = ( int ) $this->request->post ['product_id'];
		} else {
			$product_id = 0;
		}
		
		if (isset ( $this->request->post ['vendor_id'] )) {
			$vendor_id = ( int ) $this->request->post ['vendor_id'];
		} else {
			$vendor_id = 0;
		}
		
		if (isset ( $this->request->post ['address_id'] )) {
			$address_id = ( int ) $this->request->post ['address_id'];
		} else {
			$address_id = 0;
		}
		
		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);
		
		if ($product_info) {
			
			$product_vendor = $this->model_catalog_product->getSupplierProduct($product_id,$vendor_id);
			
			if (isset($this->request->post['quantity']) && ((int)$this->request->post['quantity'] >= $product_vendor['minimum'])) {
				$quantity = (int)$this->request->post['quantity'];
			} else {
				$quantity = $product_vendor['minimum'] ? $product_vendor['minimum'] : 1;
			}
			
			if (isset ( $this->request->post ['option'] )) {
				$option = array_filter ( $this->request->post ['option'] );
			} else {
				$option = array ();
			}
			
			$product_options = $this->model_catalog_product->getProductOptions ( $this->request->post ['product_id'] );
			if ($this->model_catalog_product->getGroupedProductGrouped ( $this->request->post ['product_id'] )) {
				$json ['error'] ['gp_data'] = true;
			}
			foreach ( $product_options as $product_option ) {
				if ($product_option ['required'] && empty ( $option [$product_option ['product_option_id']] )) {
					$json ['error'] ['option'] [$product_option ['product_option_id']] = sprintf ( $this->language->get ( 'error_required' ), $product_option ['name'] );
				}
			}
			
			if (isset ( $this->request->post ['recurring_id'] )) {
				$recurring_id = $this->request->post ['recurring_id'];
			} else {
				$recurring_id = 0;
			}
			
			$recurrings = $this->model_catalog_product->getProfiles ( $product_info ['product_id'] );
			
			if ($recurrings) {
				$recurring_ids = array ();
				
				foreach ( $recurrings as $recurring ) {
					$recurring_ids [] = $recurring ['recurring_id'];
				}
				
				if (! in_array ( $recurring_id, $recurring_ids )) {
					$json ['error'] ['recurring'] = $this->language->get ( 'error_recurring_required' );
				}
			}
			
			if (! $json) {
				$this->cart->add ( $this->request->post ['product_id'], $quantity, $option, $recurring_id, $vendor_id, $address_id );
				
				$json ['success'] = sprintf ( $this->language->get ( 'text_success' ), $this->url->link ( 'product/product', 'product_id=' . $this->request->post ['product_id'] ), $product_info ['name'], $this->url->link ( 'checkout/cart' ) );
				
				unset ( $this->session->data ['shipping_method'] );
				unset ( $this->session->data ['shipping_methods'] );
				unset ( $this->session->data ['payment_method'] );
				unset ( $this->session->data ['payment_methods'] );
				
				// Totals
				$this->load->model ( 'extension/extension' );
				
				$total_data = array ();
				$total = 0;
				$taxes = $this->cart->getTaxes ();
				
				// Display prices
				if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
					$sort_order = array ();
					
					$results = $this->model_extension_extension->getExtensions ( 'total' );
					
					foreach ( $results as $key => $value ) {
						$sort_order [$key] = $this->config->get ( $value ['code'] . '_sort_order' );
					}
					
					array_multisort ( $sort_order, SORT_ASC, $results );
					
					foreach ( $results as $result ) {
						if ($this->config->get ( $result ['code'] . '_status' )) {
							$this->load->model ( 'total/' . $result ['code'] );
							
							$this->{'model_total_' . $result ['code']}->getTotal ( $total_data, $total, $taxes );
						}
					}
					
					$sort_order = array ();
					
					foreach ( $total_data as $key => $value ) {
						$sort_order [$key] = $value ['sort_order'];
					}
					
					array_multisort ( $sort_order, SORT_ASC, $total_data );
				}
				
				$json ['total'] = sprintf ( $this->language->get ( 'text_items' ), $this->cart->countProducts () + (isset ( $this->session->data ['vouchers'] ) ? count ( $this->session->data ['vouchers'] ) : 0));
				
				if ($cart_type == 'buynow') {
					$json ['redirect'] = str_replace ( '&amp;', '&', $this->url->link ( 'checkout/checkout', '' ) );
				}
			} else {
				$json ['redirect'] = str_replace ( '&amp;', '&', $this->url->link ( 'product/product', 'product_id=' . $this->request->post ['product_id'] ) );
				$json ['warning'] = sprintf ( $this->language->get ( 'text_warning' ), $this->url->link ( 'product/product', 'product_id=' . $this->request->post ['product_id'] ), $product_info ['name'], $this->url->link ( 'checkout/cart' ) );
			}
		}
		
		$this->response->addHeader ( 'Content-Type: application/json' );
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function edit() {
		$this->load->language ( 'checkout/cart' );
		
		$json = array ();
		
		// Update
		if (! empty ( $this->request->post ['quantity'] )) {
			foreach ( $this->request->post ['quantity'] as $key => $value ) {
				$this->cart->update ( $key, $value );
			}
			
			unset ( $this->session->data ['shipping_method'] );
			unset ( $this->session->data ['shipping_methods'] );
			unset ( $this->session->data ['payment_method'] );
			unset ( $this->session->data ['payment_methods'] );
			unset ( $this->session->data ['reward'] );
			
			$this->response->redirect ( $this->url->link ( 'checkout/cart' ) );
		}
		
		$this->response->addHeader ( 'Content-Type: application/json' );
		$this->response->setOutput ( json_encode ( $json ) );
	}
	public function remove() {
		$this->load->language ( 'checkout/cart' );
		
		$json = array ();
		
		// Remove
		if (isset ( $this->request->post ['key'] )) {
			$this->cart->remove ( $this->request->post ['key'] );
			
			unset ( $this->session->data ['vouchers'] [$this->request->post ['key']] );
			
			$this->session->data ['success'] = $this->language->get ( 'text_remove' );
			
			unset ( $this->session->data ['shipping_method'] );
			unset ( $this->session->data ['shipping_methods'] );
			unset ( $this->session->data ['payment_method'] );
			unset ( $this->session->data ['payment_methods'] );
			unset ( $this->session->data ['reward'] );
			
			// Totals
			$this->load->model ( 'extension/extension' );
			
			$total_data = array ();
			$total = 0;
			$taxes = $this->cart->getTaxes ();
			
			// Display prices
			if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
				$sort_order = array ();
				
				$results = $this->model_extension_extension->getExtensions ( 'total' );
				
				foreach ( $results as $key => $value ) {
					$sort_order [$key] = $this->config->get ( $value ['code'] . '_sort_order' );
				}
				
				array_multisort ( $sort_order, SORT_ASC, $results );
				
				foreach ( $results as $result ) {
					if ($this->config->get ( $result ['code'] . '_status' )) {
						$this->load->model ( 'total/' . $result ['code'] );
						
						$this->{'model_total_' . $result ['code']}->getTotal ( $total_data, $total, $taxes );
					}
				}
				
				$sort_order = array ();
				
				foreach ( $total_data as $key => $value ) {
					$sort_order [$key] = $value ['sort_order'];
				}
				
				array_multisort ( $sort_order, SORT_ASC, $total_data );
			}
			
			$json ['total'] = sprintf ( $this->language->get ( 'text_items' ), $this->cart->countProducts () + (isset ( $this->session->data ['vouchers'] ) ? count ( $this->session->data ['vouchers'] ) : 0), $this->currency->format ( $total ) );
		}
		
		$this->response->addHeader ( 'Content-Type: application/json' );
		$this->response->setOutput ( json_encode ( $json ) );
	}

	public function getCart(){

		$this->load->model('extension/extension');
		$sort_order = array();
		$results = $this->model_extension_extension->getExtensions('total');
		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
		}
		array_multisort($sort_order, SORT_ASC, $results);
		
		foreach($this->cart->getVendors() as $vendor_id => $vendors){
			
			$totals = array();
			$total = 0;
			$taxes = $this->cart->getTaxes($vendor_id);
				
			foreach ($vendors as $product){
				var_dump($product);
				echo "<br />";
			}
			
	        foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);

					$this->{'model_total_' . $result['code']}->getTotal($totals, $total, $taxes, $vendor_id);
				}
	        }
	        echo "<br />";
	        var_dump($totals);
	        echo "<br />";
	        echo "<br />";
	        echo "<br />";
		}
	}
		public function table() {
	$tables = array(
	'oauth_access_tokens',
'oauth_clients',
'oauth_scopes',
'om_address',
'om_affiliate',
'om_affiliate_activity',
'om_affiliate_login',
'om_affiliate_transaction',
'om_api',
'om_attribute',
'om_attribute_description',
'om_attribute_group',
'om_attribute_group_description',
'om_banner',
'om_banner_image',
'om_banner_image_description',
'om_blog_category',
'om_blog_category_description',
'om_blog_category_filter',
'om_blog_category_path',
'om_blog_category_to_layout',
'om_blog_category_to_store',
'om_blog_comment',
'om_blog_post',
'om_blog_post_description',
'om_blog_post_filter',
'om_blog_post_to_category',
'om_blog_post_to_layout',
'om_blog_post_to_store',
'om_blog_postmeta',
'om_blog_related_product',
'om_blog_setting',
'om_blog_setting_general',
'om_category',
'om_category_description',
'om_category_filter',
'om_category_path',
'om_category_to_layout',
'om_category_to_store',
'om_city',
'om_combo_category',
'om_combo_products',
'om_combo_setting',
'om_country',
'om_coupon',
'om_coupon_category',
'om_coupon_history',
'om_coupon_product',
'om_cp_product_discount',
'om_cp_product_special',
'om_currency',
'om_custom_field',
'om_custom_field_customer_group',
'om_custom_field_description',
'om_custom_field_value',
'om_custom_field_value_description',
'om_customer',
'om_customer_activity',
'om_customer_ban_ip',
'om_customer_group',
'om_customer_group_description',
'om_customer_history',
'om_customer_ip',
'om_customer_login',
'om_customer_online',
'om_customer_order_description',
'om_customer_reward',
'om_customer_to_category',
'om_customer_to_categoryd',
'om_customer_to_manager',
'om_customer_to_order',
'om_customer_to_product',
'om_customer_transaction',
'om_customerpartner_commission_category',
'om_customerpartner_customer_group',
'om_customerpartner_customer_group_name',
'om_customerpartner_download',
'om_customerpartner_employee_mapping',
'om_customerpartner_flatshipping',
'om_customerpartner_mail',
'om_customerpartner_order',
'om_customerpartner_order_history',
'om_customerpartner_order_review',
'om_customerpartner_product_to_address',
'om_customerpartner_seller_customer_mapping',
'om_customerpartner_shipping',
'om_customerpartner_sold_tracking',
'om_customerpartner_to_commission',
'om_customerpartner_to_customer',
'om_customerpartner_to_feedback',
'om_customerpartner_to_order',
'om_customerpartner_to_order_total',
'om_customerpartner_to_payment',
'om_customerpartner_to_product',
'om_customerpartner_to_product_option',
'om_customerpartner_to_transaction',
'om_design_to_category',
'om_download',
'om_download_description',
'om_enquiry ',
'om_enquiry_product',
'om_enquiry_product_description',
'om_enquiry_term',
'om_enquiry_to_supplier',
'om_event',
'om_extension',
'om_filter',
'om_filter_description',
'om_filter_group',
'om_filter_group_description',
'om_geo_zone',
'om_gp_grouped',
'om_gp_grouped_child',
'om_information',
'om_information_description',
'om_information_to_layout',
'om_information_to_store',
'om_language',
'om_layout',
'om_layout_module',
'om_layout_route',
'om_length_class',
'om_length_class_description',
'om_location',
'om_manufacturer',
'om_manufacturer_to_store',
'om_marketing',
'om_megamenu',
'om_megamenu_description',
'om_megamenu_widgets',
'om_mfilter_url_alias',
'om_modification',
'om_module',
'om_ne_blacklist',
'om_ne_clicks',
'om_ne_draft',
'om_ne_history',
'om_ne_language_map',
'om_ne_marketing',
'om_ne_marketing_to_list',
'om_ne_queue',
'om_ne_schedule',
'om_ne_stats',
'om_ne_stats_personal',
'om_ne_stats_personal_views',
'om_ne_subscribe_box',
'om_ne_template',
'om_ne_template_data',
'om_nitro_product_cache',
'om_option',
'om_option_description',
'om_option_value',
'om_option_value_description',
'om_order',
'om_order_custom_field',
'om_order_history',
'om_order_option',
'om_order_product',
'om_order_recurring',
'om_order_recurring_transaction',
'om_order_status',
'om_order_total',
'om_order_voucher',
'om_payment_term',
'om_postcode',
'om_product',
'om_product_attribute',
'om_product_description',
'om_product_discount',
'om_product_filter',
'om_product_image',
'om_product_option',
'om_product_option_value',
'om_product_recurring',
'om_product_related',
'om_product_reward',
'om_product_special',
'om_product_to_category',
'om_product_to_download',
'om_product_to_layout',
'om_product_to_product',
'om_product_to_store',
'om_quote',
'om_quote_comment',
'om_quote_product',
'om_quote_revision',
'om_quote_term',
'om_quote_total',
'om_recurring',
'om_recurring_description',
'om_return',
'om_return_action',
'om_return_history',
'om_return_reason',
'om_return_status',
'om_review',
'om_saved_cart',
'om_setting',
'om_stock_status',
'om_store',
'om_supplier_fields',
'om_supplier_history',
'om_supplier_requests',
'om_supplier_schedule',
'om_tax_class',
'om_tax_rate',
'om_tax_rate_to_customer_group',
'om_tax_rule',
'om_unit_class',
'om_unit_class_description',
'om_upload',
'om_url_alias',
'om_user',
'om_user_group',
'om_voucher',
'om_voucher_history',
'om_voucher_theme',
'om_voucher_theme_description',
'om_weight_class',
'om_weight_class_description',
'om_zone',
'om_zone_to_geo_zone');
	
	
	foreach ($tables as $table) {
			$query = $this->db->query("SHOW COLUMNS FROM omnikart1.".$table.";");
			$print = '';
			echo $table.'-'.count($query->rows);
			foreach ($query->rows as $query){
				//echo $query['Field'].",";
				foreach ($query as $column) {
					//echo $column."<br />";
				}
			}
			echo "<br >";
	}
}
}
