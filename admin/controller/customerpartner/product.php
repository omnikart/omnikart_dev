<?php
class ControllerCustomerpartnerProduct extends Controller {
	private $error = array ();
	private $data = array ();
	public function index() {
		$this->load->language ( 'customerpartner/product' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'customerpartner/product' );
		
		$this->getList ();
	}
	public function delete() {
		$this->load->language ( 'customerpartner/product' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'customerpartner/product' );
		
		if (isset ( $this->request->post ['selected'] ) && $this->validateDelete ()) {
			
			foreach ( $this->request->post ['selected'] as $product_id ) {
				$this->model_customerpartner_product->deleteProduct ( $product_id );
			}
			
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			
			$url = '';
			
			if (isset ( $this->request->get ['filter_name'] )) {
				$url .= '&filter_name=' . urlencode ( html_entity_decode ( $this->request->get ['filter_name'], ENT_QUOTES, 'UTF-8' ) );
			}
			
			if (isset ( $this->request->get ['filter_model'] )) {
				$url .= '&filter_model=' . urlencode ( html_entity_decode ( $this->request->get ['filter_model'], ENT_QUOTES, 'UTF-8' ) );
			}
			
			if (isset ( $this->request->get ['filter_price'] )) {
				$url .= '&filter_price=' . $this->request->get ['filter_price'];
			}
			
			if (isset ( $this->request->get ['filter_quantity'] )) {
				$url .= '&filter_quantity=' . $this->request->get ['filter_quantity'];
			}
			
			if (isset ( $this->request->get ['filter_status'] )) {
				$url .= '&filter_status=' . $this->request->get ['filter_status'];
			}
			
			if (isset ( $this->request->get ['sort'] )) {
				$url .= '&sort=' . $this->request->get ['sort'];
			}
			
			if (isset ( $this->request->get ['order'] )) {
				$url .= '&order=' . $this->request->get ['order'];
			}
			
			if (isset ( $this->request->get ['page'] )) {
				$url .= '&page=' . $this->request->get ['page'];
			}
			
			$this->response->redirect ( $this->url->link ( 'customerpartner/product', 'token=' . $this->session->data ['token'] . $url, 'SSL' ) );
		}
		
		$this->getList ();
	}
	private function getList() {
		$this->load->model ( 'catalog/product' );
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$filter_name = $this->request->get ['filter_name'];
		} else {
			$filter_name = null;
		}
		
		if (isset ( $this->request->get ['filter_seller'] )) {
			$filter_seller = $this->request->get ['filter_seller'];
		} else {
			$filter_seller = null;
		}
		
		if (isset ( $this->request->get ['filter_model'] )) {
			$filter_model = $this->request->get ['filter_model'];
		} else {
			$filter_model = null;
		}
		
		if (isset ( $this->request->get ['filter_price'] )) {
			$filter_price = $this->request->get ['filter_price'];
		} else {
			$filter_price = null;
		}
		
		if (isset ( $this->request->get ['filter_quantity'] )) {
			$filter_quantity = $this->request->get ['filter_quantity'];
		} else {
			$filter_quantity = null;
		}
		
		if (isset ( $this->request->get ['filter_status'] )) {
			$filter_status = $this->request->get ['filter_status'];
		} else {
			$filter_status = null;
		}
		
		if (isset ( $this->request->get ['sort'] )) {
			$sort = $this->request->get ['sort'];
		} else {
			$sort = 'pd.name';
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$order = $this->request->get ['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$page = $this->request->get ['page'];
		} else {
			$page = 1;
		}
		
		$url = '';
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$url .= '&filter_name=' . urlencode ( html_entity_decode ( $this->request->get ['filter_name'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['filter_seller'] )) {
			$url .= '&filter_seller=' . urlencode ( html_entity_decode ( $this->request->get ['filter_seller'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['filter_model'] )) {
			$url .= '&filter_model=' . urlencode ( html_entity_decode ( $this->request->get ['filter_model'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['filter_price'] )) {
			$url .= '&filter_price=' . $this->request->get ['filter_price'];
		}
		
		if (isset ( $this->request->get ['filter_quantity'] )) {
			$url .= '&filter_quantity=' . $this->request->get ['filter_quantity'];
		}
		
		if (isset ( $this->request->get ['filter_status'] )) {
			$url .= '&filter_status=' . $this->request->get ['filter_status'];
		}
		
		if (isset ( $this->request->get ['sort'] )) {
			$url .= '&sort=' . $this->request->get ['sort'];
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$url .= '&order=' . $this->request->get ['order'];
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
		}
		
		$this->data ['breadcrumbs'] = array ();
		
		$this->data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home', 'token=' . $this->session->data ['token'], 'SSL' ),
				'separator' => false 
		);
		
		$this->data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'heading_title' ),
				'href' => $this->url->link ( 'customerpartner/product', 'token=' . $this->session->data ['token'] . $url, 'SSL' ),
				'separator' => ' :: ' 
		);
		
		$this->data ['insert'] = $this->url->link ( 'catalog/product/add', 'token=' . $this->session->data ['token'] . $url, 'SSL' );
		$this->data ['copy'] = $this->url->link ( 'customerpartner/product/copy', 'token=' . $this->session->data ['token'] . $url, 'SSL' );
		$this->data ['approve'] = $this->url->link ( 'customerpartner/product/approve&token=' . $this->session->data ['token'] . $url, 'SSL' );
		$this->data ['delete'] = $this->url->link ( 'customerpartner/product/delete', 'token=' . $this->session->data ['token'] . $url, 'SSL' );
		
		$this->data ['products'] = array ();
		
		$data = array (
				'filter_name' => $filter_name,
				'filter_seller' => $filter_seller,
				'filter_model' => $filter_model,
				'filter_price' => $filter_price,
				'filter_quantity' => $filter_quantity,
				'filter_status' => $filter_status,
				'sort' => $sort,
				'order' => $order,
				'start' => ($page - 1) * $this->config->get ( 'config_limit_admin' ),
				'limit' => $this->config->get ( 'config_limit_admin' ) 
		);
		
		$this->load->model ( 'tool/image' );
		
		$this->load->model ( 'customerpartner/partner' );
		
		$this->data ['partners'] = $this->model_customerpartner_partner->getCustomers ();
		
		$product_total = $this->model_customerpartner_product->getTotalProducts ( $data );
		
		$results = $this->model_customerpartner_product->getProducts ( $data );
		
		foreach ( $results as $result ) {
			
			$action = array ();
			
			$product_id = $result ['product_id'];
			
			$action [] = array (
					'text' => $this->language->get ( 'text_edit' ),
					'href' => $this->url->link ( 'catalog/product/edit', 'token=' . $this->session->data ['token'] . '&product_id=' . $product_id . $url, 'SSL' ) 
			);
			
			if ($result ['image'] && file_exists ( DIR_IMAGE . $result ['image'] )) {
				$image = $this->model_tool_image->resize ( $result ['image'], 40, 40 );
			} else {
				$image = $this->model_tool_image->resize ( 'no_image.png', 40, 40 );
			}
			
			$special = false;
			
			$customername = $result ['firstname'] . " " . $result ['lastname'];
			
			$product_specials = $this->model_catalog_product->getProductSpecials ( $product_id );
			
			foreach ( $product_specials as $product_special ) {
				if (($product_special ['date_start'] == '0000-00-00' || $product_special ['date_start'] < date ( 'Y-m-d' )) && ($product_special ['date_end'] == '0000-00-00' || $product_special ['date_end'] > date ( 'Y-m-d' ))) {
					$special = $product_special ['price'];
					
					break;
				}
			}
			
			$this->data ['products'] [] = array (
					'product_id' => $result ['product_id'],
					'customer_id' => $result ['customer_id'],
					'name' => $result ['name'],
					'model' => $result ['model'],
					'price' => $result ['price'],
					'special' => $special,
					'image' => $image,
					'quantity' => $result ['quantity'],
					'status' => $result ['status'],
					'selected' => isset ( $this->request->post ['selected'] ) && in_array ( $result ['product_id'], $this->request->post ['selected'] ),
					'action' => $action,
					'seller' => $customername 
			);
		}
		
		$this->data ['heading_title'] = $this->language->get ( 'heading_title' );
		$this->data ['text_enabled'] = $this->language->get ( 'text_enabled' );
		$this->data ['text_disabled'] = $this->language->get ( 'text_disabled' );
		$this->data ['text_no_results'] = $this->language->get ( 'text_no_results' );
		$this->data ['text_image_manager'] = $this->language->get ( 'text_image_manager' );
		
		$this->data ['text_approve'] = $this->language->get ( 'text_approve' );
		$this->data ['text_disapprove'] = $this->language->get ( 'text_disapprove' );
		$this->data ['text_confirm'] = $this->language->get ( 'text_confirm' );
		$this->data ['text_seller_info'] = $this->language->get ( 'text_seller_info' );
		$this->data ['text_confirm_approve'] = $this->language->get ( 'text_confirm_approve' );
		
		$this->data ['column_image'] = $this->language->get ( 'column_image' );
		$this->data ['column_name'] = $this->language->get ( 'column_name' );
		$this->data ['column_model'] = $this->language->get ( 'column_model' );
		$this->data ['column_price'] = $this->language->get ( 'column_price' );
		$this->data ['column_quantity'] = $this->language->get ( 'column_quantity' );
		$this->data ['column_status'] = $this->language->get ( 'column_status' );
		$this->data ['column_action'] = $this->language->get ( 'column_action' );
		$this->data ['column_partner_name'] = $this->language->get ( 'column_partner_name' );
		
		$this->data ['button_copy'] = $this->language->get ( 'button_copy' );
		$this->data ['button_insert'] = $this->language->get ( 'button_insert' );
		$this->data ['button_delete'] = $this->language->get ( 'button_delete' );
		$this->data ['button_filter'] = $this->language->get ( 'button_filter' );
		
		$this->data ['token'] = $this->session->data ['token'];
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		$url = '';
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$url .= '&filter_name=' . urlencode ( html_entity_decode ( $this->request->get ['filter_name'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['filter_seller'] )) {
			$url .= '&filter_seller=' . urlencode ( html_entity_decode ( $this->request->get ['filter_seller'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['filter_model'] )) {
			$url .= '&filter_model=' . urlencode ( html_entity_decode ( $this->request->get ['filter_model'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['filter_price'] )) {
			$url .= '&filter_price=' . $this->request->get ['filter_price'];
		}
		
		if (isset ( $this->request->get ['filter_quantity'] )) {
			$url .= '&filter_quantity=' . $this->request->get ['filter_quantity'];
		}
		
		if (isset ( $this->request->get ['filter_status'] )) {
			$url .= '&filter_status=' . $this->request->get ['filter_status'];
		}
		
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
		}
		
		$this->data ['sort_name'] = $this->url->link ( 'customerpartner/product', 'token=' . $this->session->data ['token'] . '&sort=pd.name' . $url, 'SSL' );
		$this->data ['sort_seller_name'] = $this->url->link ( 'customerpartner/product', 'token=' . $this->session->data ['token'] . '&sort=c.customer_id' . $url, 'SSL' );
		$this->data ['sort_model'] = $this->url->link ( 'customerpartner/product', 'token=' . $this->session->data ['token'] . '&sort=p.model' . $url, 'SSL' );
		$this->data ['sort_price'] = $this->url->link ( 'customerpartner/product', 'token=' . $this->session->data ['token'] . '&sort=p.price' . $url, 'SSL' );
		$this->data ['sort_quantity'] = $this->url->link ( 'customerpartner/product', 'token=' . $this->session->data ['token'] . '&sort=p.quantity' . $url, 'SSL' );
		$this->data ['sort_status'] = $this->url->link ( 'customerpartner/product', 'token=' . $this->session->data ['token'] . '&sort=p.product_id' . $url, 'SSL' );
		$this->data ['sort_order'] = $this->url->link ( 'customerpartner/product', 'token=' . $this->session->data ['token'] . '&sort=p.sort_order' . $url, 'SSL' );
		
		$url = '';
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$url .= '&filter_name=' . urlencode ( html_entity_decode ( $this->request->get ['filter_name'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['filter_seller'] )) {
			$url .= '&filter_seller=' . urlencode ( html_entity_decode ( $this->request->get ['filter_seller'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['filter_model'] )) {
			$url .= '&filter_model=' . urlencode ( html_entity_decode ( $this->request->get ['filter_model'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['filter_price'] )) {
			$url .= '&filter_price=' . $this->request->get ['filter_price'];
		}
		
		if (isset ( $this->request->get ['filter_quantity'] )) {
			$url .= '&filter_quantity=' . $this->request->get ['filter_quantity'];
		}
		
		if (isset ( $this->request->get ['filter_status'] )) {
			$url .= '&filter_status=' . $this->request->get ['filter_status'];
		}
		
		if (isset ( $this->request->get ['sort'] )) {
			$url .= '&sort=' . $this->request->get ['sort'];
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$url .= '&order=' . $this->request->get ['order'];
		}
		
		$pagination = new Pagination ();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get ( 'config_limit_admin' );
		$pagination->text = $this->language->get ( 'text_pagination' );
		$pagination->url = $this->url->link ( 'customerpartner/product', 'token=' . $this->session->data ['token'] . $url . '&page={page}', 'SSL' );
		
		$this->data ['pagination'] = $pagination->render ();
		$this->data ['results'] = sprintf ( $this->language->get ( 'text_pagination' ), ($product_total) ? (($page - 1) * $this->config->get ( 'config_limit_admin' )) + 1 : 0, ((($page - 1) * $this->config->get ( 'config_limit_admin' )) > ($product_total - $this->config->get ( 'config_limit_admin' ))) ? $product_total : ((($page - 1) * $this->config->get ( 'config_limit_admin' )) + $this->config->get ( 'config_limit_admin' )), $product_total, ceil ( $product_total / $this->config->get ( 'config_limit_admin' ) ) );
		
		$this->data ['filter_name'] = $filter_name;
		$this->data ['filter_seller'] = $filter_seller;
		$this->data ['filter_model'] = $filter_model;
		$this->data ['filter_price'] = $filter_price;
		$this->data ['filter_quantity'] = $filter_quantity;
		$this->data ['filter_status'] = $filter_status;
		
		$this->data ['sort'] = $sort;
		$this->data ['order'] = $order;
		
		$this->data ['header'] = $this->load->controller ( 'common/header' );
		$this->data ['footer'] = $this->load->controller ( 'common/footer' );
		$this->data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$this->response->setOutput ( $this->load->view ( 'customerpartner/product_list.tpl', $this->data ) );
	}
	public function approve() {
		$this->load->language ( 'customerpartner/product' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'customerpartner/product' );
		
		if ($this->validateDelete () and isset ( $this->request->get ['product_id'] )) {
			
			$this->model_customerpartner_product->addProduct ( $this->request->get );
			
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
		}
		
		$url = '';
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$url .= '&filter_name=' . urlencode ( html_entity_decode ( $this->request->get ['filter_name'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['filter_model'] )) {
			$url .= '&filter_model=' . urlencode ( html_entity_decode ( $this->request->get ['filter_model'], ENT_QUOTES, 'UTF-8' ) );
		}
		
		if (isset ( $this->request->get ['filter_price'] )) {
			$url .= '&filter_price=' . $this->request->get ['filter_price'];
		}
		
		if (isset ( $this->request->get ['filter_quantity'] )) {
			$url .= '&filter_quantity=' . $this->request->get ['filter_quantity'];
		}
		
		if (isset ( $this->request->get ['filter_status'] )) {
			$url .= '&filter_status=' . $this->request->get ['filter_status'];
		}
		
		if (isset ( $this->request->get ['sort'] )) {
			$url .= '&sort=' . $this->request->get ['sort'];
		}
		
		if (isset ( $this->request->get ['order'] )) {
			$url .= '&order=' . $this->request->get ['order'];
		}
		
		if (isset ( $this->request->get ['page'] )) {
			$url .= '&page=' . $this->request->get ['page'];
		}
		
		$this->response->redirect ( $this->url->link ( 'customerpartner/product', 'token=' . $this->session->data ['token'] . $url, 'SSL' ) );
	}
	private function validateDelete() {
		if (! $this->user->hasPermission ( 'modify', 'customerpartner/product' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function autocomplete() {
		$json = array ();
		
		if (isset ( $this->request->get ['filter_name'] ) || isset ( $this->request->get ['filter_model'] ) || isset ( $this->request->get ['filter_category_id'] )) {
			
			$this->load->model ( 'customerpartner/product' );
			
			if (isset ( $this->request->get ['filter_name'] )) {
				$filter_name = $this->request->get ['filter_name'];
			} else {
				$filter_name = '';
			}
			
			if (isset ( $this->request->get ['filter_model'] )) {
				$filter_model = $this->request->get ['filter_model'];
			} else {
				$filter_model = '';
			}
			
			if (isset ( $this->request->get ['filter_category_id'] )) {
				$filter_category_id = $this->request->get ['filter_category_id'];
			} else {
				$filter_category_id = '';
			}
			
			if (isset ( $this->request->get ['filter_for_seller'] )) {
				$filter_for_seller = $this->request->get ['filter_for_seller'];
			} else {
				$filter_for_seller = '';
			}
			
			if (isset ( $this->request->get ['filter_sub_category'] )) {
				$filter_sub_category = $this->request->get ['filter_sub_category'];
			} else {
				$filter_sub_category = '';
			}
			
			if (isset ( $this->request->get ['limit'] )) {
				$limit = $this->request->get ['limit'];
			} else {
				$limit = 20;
			}
			
			if (isset ( $this->request->get ['customer_id'] )) {
				$customer_id = $this->request->get ['customer_id'];
			} else {
				$customer_id = '';
			}
			
			$data = array (
					'filter_name' => $filter_name,
					'filter_model' => $filter_model,
					'filter_category_id' => $filter_category_id,
					'filter_sub_category' => $filter_sub_category,
					'filter_for_seller' => $filter_for_seller,
					'start' => 0,
					'limit' => $limit 
			);
			
			$results = $this->model_customerpartner_product->getProducts ( $data );
			
			foreach ( $results as $result ) {
				
				$customer_ids = explode ( ',', $result ['customer_id'] );
				
				$option_data = array ();
				
				if (! $filter_for_seller || (! isset ( $result ['customer_id'] ) || (! in_array ( $customer_id, $customer_ids ))))
					$json [] = array (
							'product_id' => $result ['product_id'],
							'name' => strip_tags ( html_entity_decode ( $result ['name'], ENT_QUOTES, 'UTF-8' ) ),
							'model' => $result ['model'],
							'option' => $option_data,
							'price' => $result ['price'] 
					);
			}
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
}
?>
