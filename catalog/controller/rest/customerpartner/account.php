<?php
/**
 * account.php
 *
 * Account management
 *
 * @author     Makai Lajos
 * @copyright  2015
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://opencart2oauth.opencart-api.com/schema_v2.0_oauth/
 */
require_once (DIR_SYSTEM . 'engine/restcontroller.php');
class ControllerRestCustomerpartnerAccount extends RestController {
	private $error = array ();
	private $data = array ();
	public function account() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			if ($_GET ['type'] == 'products') {
				$this->getProducts ();
			}
			// get account details
			
			$this->getAccount ();
		} else if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
			// modify account
			$requestjson = file_get_contents ( 'php://input' );
			
			$requestjson = json_decode ( $requestjson, true );
			
			if (! empty ( $requestjson )) {
				$this->saveAccount ( $requestjson );
			} else {
				$this->sendResponse ( array (
						'success' => false 
				) );
			}
		}
	}
	private function getProducts() {
		$json = array (
				'success' => true 
		);
		$this->load->model ( 'account/customerpartner' );
		$customerRights = $this->customer->getRights ();
		
		$this->data ['list'] = true;
		$this->data ['allowedAddEdit'] = true;
		
		if ($customerRights && ! array_key_exists ( 'productlist', $customerRights ['rights'] )) {
			$this->response->redirect ( $this->url->link ( 'account/account', '', 'SSL' ) );
			$data ['list'] = false;
		}
		
		if ($customerRights && ! array_key_exists ( 'addproduct', $customerRights ['rights'] )) {
			$this->data ['allowedAddEdit'] = false;
		}
		
		$sellerId = $this->model_account_customerpartner->isSubUser ( $this->customer->getId () );
		
		if (! $customerRights ['isParent'] && ! $sellerId) {
			$this->data ['chkIsPartner'] = $this->model_account_customerpartner->chkIsPartner ();
			$sellerId = $this->model_account_customerpartner->getuserseller ();
		} else if ($sellerId) {
			$this->data ['chkIsPartner'] = true;
		}
		
		if (isset ( $this->request->get ['filter_name'] )) {
			$filter_name = $this->request->get ['filter_name'];
		} else {
			$filter_name = null;
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
		$data = array (
				'filter_name' => $filter_name,
				'filter_model' => $filter_model,
				'filter_price' => $filter_price,
				'filter_quantity' => $filter_quantity,
				'filter_status' => $filter_status,
				'sort' => $sort,
				'order' => $order,
				'start' => ($page - 1) * $this->config->get ( 'config_product_limit' ),
				'limit' => $this->config->get ( 'config_product_limit' ),
				'customer_id' => $sellerId 
		);
		$product_total = $this->model_account_customerpartner->getTotalProductsSeller ( $data );
		
		$results = $this->model_account_customerpartner->getProductsSeller ( $data );
		
		foreach ( $results as $key => $result ) {
			
			if (! $results [$key] ['product_id'])
				$results [$key] ['product_id'] = $result ['product_id'] = $key;
			
			$action = array ();
			
			$action [] = array (
					'text' => $this->language->get ( 'text_edit' ),
					'href' => $this->url->link ( 'account/customerpartner/addproduct', '' . '&product_id=' . $result ['product_id'], 'SSL' ) 
			);
			
			if ($result ['image'] && file_exists ( DIR_IMAGE . $result ['image'] )) {
				$thumb = $this->model_tool_image->resize ( $result ['image'], 40, 40 );
			} else {
				$thumb = $this->model_tool_image->resize ( 'no_image.jpg', 40, 40 );
			}
			
			$product_sold_quantity = array ();
			$sold = $totalearn = 0;
			
			$product_sold_quantity = $this->model_account_customerpartner->getProductSoldQuantity ( $result ['product_id'], $sellerId );
			
			if ($product_sold_quantity) {
				$sold = $product_sold_quantity ['quantity'] ? $product_sold_quantity ['quantity'] : 0;
				$totalearn = $product_sold_quantity ['total'] ? $product_sold_quantity ['total'] : 0;
			}
			
			$results [$key] ['thumb'] = $thumb;
			$results [$key] ['sold'] = $sold;
			$results [$key] ['soldlink'] = $this->url->link ( 'account/customerpartner/soldlist&product_id=' . $result ['product_id'], '', 'SSL' );
			$results [$key] ['totalearn'] = $this->currency->format ( $totalearn );
			$results [$key] ['selected'] = isset ( $this->request->post ['selected'] ) && in_array ( $result ['product_id'], $this->request->post ['selected'] );
			$results [$key] ['totalearn'] = $this->currency->format ( $totalearn );
			$results [$key] ['action'] = $action;
		}
		
		$limit = $this->config->get ( 'config_product_limit' );
		
		$this->data ['results'] = sprintf ( $this->language->get ( 'text_pagination' ), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil ( $product_total / $limit ) );
		
		$this->data ['products'] = $results;
		
		$this->load->model ( 'localisation/stock_status' );
		$this->data ['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses ();
		
		$this->load->model ( 'localisation/weight_class' );
		$this->data ['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses ();
		
		$this->load->model ( 'localisation/length_class' );
		$this->data ['length_classes'] = $this->model_localisation_length_class->getLengthClasses ();
		
		$this->data ['mp_ap'] = $this->config->get ( 'marketplace_selleraddproduct' );
		
		$this->sendResponse ( $this->data );
	}
}
