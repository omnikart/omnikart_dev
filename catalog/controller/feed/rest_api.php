<?php
/**
 * rest_api.php
 *
 * Custom rest services
 *
 * @author     Makai Lajos
 * @copyright  2015
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://opencart2oauth.opencart-api.com/schema_v2.0_oauth/
 */
require_once (DIR_SYSTEM . 'engine/restcontroller.php');
class ControllerFeedRestApi extends RestController {
	
	/* Get Oauth token */
	public function getToken() {
		if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
			$this->config->set ( 'config_error_display', 0 );
			
			$this->response->addHeader ( 'Content-Type: application/json' );
			
			/* check rest api is enabled */
			if (! $this->config->get ( 'rest_api_status' )) {
				$json ["error"] = 'API is disabled. Enable it!';
			}
			
			if (isset ( $json ["error"] )) {
				echo (json_encode ( $json ));
				exit ();
			}
			
			$requestjson = file_get_contents ( 'php://input' );
			$post = json_decode ( $requestjson, true );
			
			$oldToken = isset ( $post ['old_token'] ) ? $post ['old_token'] : null;
			$oldTokenData = null;
			$this->load->model ( 'account/customer' );
			
			if (! empty ( $oldToken )) {
				$oldTokenData = $this->model_account_customer->loadOldToken ( $oldToken );
			}
			
			$server = $this->getOauthServer ();
			$token = $server->handleTokenRequest ( OAuth2\Request::createFromGlobals () )->getParameters ();
			
			if (! empty ( $oldTokenData )) {
				$this->model_account_customer->loadSessionToNew ( $oldTokenData ['data'], $token ['access_token'] );
				$this->model_account_customer->deleteOldToken ( $oldToken );
			}
			
			// clear token table
			$this->clearTokensTable ( $token ['access_token'], session_id () );
			
			unset ( $token ['scope'] );
			$token ['expires_in'] = ( int ) $token ['expires_in'];
			
			$this->sendResponse ( $token );
		}
	}
	private function refreshToken($token) {
	}
	
	/* check database modification */
	public function getchecksum() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			
			$this->load->model ( 'catalog/product' );
			
			$checksum = $this->model_catalog_product->getChecksum ();
			
			$checksumArray = array ();
			
			for($i = 0; $i < count ( $checksum ); $i ++) {
				$checksumArray [] = array (
						'table' => $checksum [$i] ['Table'],
						'checksum' => $checksum [$i] ['Checksum'] 
				);
			}
			
			$json = array (
					'success' => true,
					'data' => $checksumArray 
			);
			
			$this->sendResponse ( $json );
		}
	}
	
	/*
	 * PRODUCT FUNCTIONS
	 */
	public function products() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			// get product details
			if (isset ( $this->request->get ['id'] ) && ctype_digit ( $this->request->get ['id'] )) {
				$this->getProduct ( $this->request->get ['id'] );
			} else {
				// get products list
				
				/* check category id parameter */
				if (isset ( $this->request->get ['category'] ) && ctype_digit ( $this->request->get ['category'] )) {
					$category_id = $this->request->get ['category'];
				} else {
					$category_id = 0;
				}
				
				$this->listProducts ( $category_id, $this->request );
			}
		}
	}
	
	/*
	 * Get products list
	 */
	public function listProducts($category_id, $request) {
		$json = array (
				'success' => false 
		);
		
		$this->load->model ( 'catalog/product' );
		
		$parameters = array (
				"limit" => 100,
				"start" => 1,
				'filter_category_id' => $category_id 
		);
		
		/* check limit parameter */
		if (isset ( $request->get ['limit'] ) && ctype_digit ( $request->get ['limit'] )) {
			$parameters ["limit"] = $request->get ['limit'];
		}
		
		/* check page parameter */
		if (isset ( $request->get ['page'] ) && ctype_digit ( $request->get ['page'] )) {
			$parameters ["start"] = $request->get ['page'];
		}
		
		/* check search parameter */
		if (isset ( $request->get ['search'] ) && ! empty ( $request->get ['search'] )) {
			$parameters ["filter_name"] = $request->get ['search'];
			$parameters ["filter_tag"] = $request->get ['search'];
		}
		
		/* check sort parameter */
		if (isset ( $request->get ['sort'] ) && ! empty ( $request->get ['sort'] )) {
			$parameters ["sort"] = $request->get ['sort'];
		}
		
		/* check order parameter */
		if (isset ( $request->get ['order'] ) && ! empty ( $request->get ['order'] )) {
			$parameters ["order"] = $request->get ['order'];
		}
		
		/* check filters parameter */
		if (isset ( $request->get ['filters'] ) && ! empty ( $request->get ['filters'] )) {
			$parameters ["filter_filter"] = $request->get ['filters'];
		}
		
		/* check simple list parameter */
		$simpleList = false;
		if (isset ( $request->get ['simple'] )) {
			if (! empty ( $request->get ['simple'] )) {
				$simpleList = true;
			}
		}
		
		$parameters ["start"] = ($parameters ["start"] - 1) * $parameters ["limit"];
		
		$products = $this->model_catalog_product->getProductsData ( $parameters, $this->customer );
		
		if (count ( $products ) == 0 || empty ( $products )) {
			$json ['success'] = false;
			$json ['error'] = "No product found";
		} else {
			$json ['success'] = true;
			foreach ( $products as $product ) {
				$json ['data'] [] = $this->getProductInfo ( $product, $simpleList );
			}
		}
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * Get product details
	 */
	public function getProduct($id) {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'catalog/product' );
		
		$products = $this->model_catalog_product->getProductsByIds ( array (
				$id 
		), $this->customer );
		if (! empty ( $products )) {
			$json ["data"] = $this->getProductInfo ( reset ( $products ) );
		} else {
			$json ['success'] = false;
		}
		
		$this->sendResponse ( $json );
	}
	private function getProductInfo($product, $simpleList = false) {
		$this->load->model ( 'tool/image' );
		$this->load->model ( 'catalog/category' );
		
		// product image
		if (isset ( $product ['image'] ) && file_exists ( DIR_IMAGE . $product ['image'] )) {
			$image = $this->model_tool_image->resize ( $product ['image'], 500, 500 );
		} else {
			$image = $this->model_tool_image->resize ( 'no_image.jpg', 500, 500 );
		}
		
		// additional images
		$images = array ();
		$reviews = array ();
		$special = "";
		$special_formated = "";
		$discounts = array ();
		$options = array ();
		$productCategories = array ();
		
		if (! $simpleList) {
			$additional_images = $this->model_catalog_product->getProductImages ( $product ['product_id'] );
			
			foreach ( $additional_images as $additional_image ) {
				if (isset ( $additional_image ['image'] ) && file_exists ( DIR_IMAGE . $additional_image ['image'] )) {
					$images [] = $this->model_tool_image->resize ( $additional_image ['image'], 500, 500 );
				} else {
					$images [] = $this->model_tool_image->resize ( 'no_image.jpg', 500, 500 );
				}
			}
			
			// special
			if (( float ) $product ['special']) {
				$special = $this->currency->restFormat ( $this->tax->calculate ( $product ['special'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
				$special_formated = $this->currency->format ( $this->tax->calculate ( $product ['special'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
			}
			
			// discounts
			$data_discounts = $this->model_catalog_product->getProductDiscounts ( $product ['product_id'] );
			
			foreach ( $data_discounts as $discount ) {
				$discounts [] = array (
						'quantity' => $discount ['quantity'],
						'price' => $this->currency->restFormat ( $this->tax->calculate ( $discount ['price'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) ),
						'price_formated' => $this->currency->format ( $this->tax->calculate ( $discount ['price'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) ) 
				);
			}
			
			// options
			foreach ( $this->model_catalog_product->getProductOptions ( $product ['product_id'] ) as $option ) {
				if ($option ['type'] == 'select' || $option ['type'] == 'radio' || $option ['type'] == 'checkbox' || $option ['type'] == 'image') {
					$option_value_data = array ();
					if (! empty ( $option ['product_option_value'] )) {
						foreach ( $option ['product_option_value'] as $option_value ) {
							if (! $option_value ['subtract'] || ($option_value ['quantity'] > 0)) {
								if ((($this->customer->isLogged () && $this->config->get ( 'config_customer_price' )) || ! $this->config->get ( 'config_customer_price' )) && ( float ) $option_value ['price']) {
									$price = $this->currency->restFormat ( $this->tax->calculate ( $option_value ['price'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
									$price_formated = $this->currency->format ( $this->tax->calculate ( $option_value ['price'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
								} else {
									$price = false;
									$price_formated = false;
								}
								
								if (isset ( $option_value ['image'] ) && file_exists ( DIR_IMAGE . $option_value ['image'] )) {
									$option_image = $this->model_tool_image->resize ( $option_value ['image'], 100, 100 );
								} else {
									$option_image = $this->model_tool_image->resize ( 'no_image.jpg', 100, 100 );
								}
								
								$option_value_data [] = array (
										'image' => $option_image,
										'price' => $price,
										'price_formated' => $price_formated,
										'price_prefix' => $option_value ['price_prefix'],
										'product_option_value_id' => $option_value ['product_option_value_id'],
										'option_value_id' => $option_value ['option_value_id'],
										'name' => $option_value ['name'],
										'quantity' => ! empty ( $option_value ['quantity'] ) ? $option_value ['quantity'] : 0 
								);
							}
						}
					}
					$options [] = array (
							'name' => $option ['name'],
							'type' => $option ['type'],
							'option_value' => $option_value_data,
							'required' => $option ['required'],
							'product_option_id' => $option ['product_option_id'],
							'option_id' => $option ['option_id'] 
					)
					;
				} elseif ($option ['type'] == 'text' || $option ['type'] == 'textarea' || $option ['type'] == 'file' || $option ['type'] == 'date' || $option ['type'] == 'datetime' || $option ['type'] == 'time') {
					$option_value = array ();
					if (! empty ( $option ['product_option_value'] )) {
						$option_value = $option ['product_option_value'];
					}
					$options [] = array (
							'name' => $option ['name'],
							'type' => $option ['type'],
							'option_value' => $option_value,
							'required' => $option ['required'],
							'product_option_id' => $option ['product_option_id'],
							'option_id' => $option ['option_id'] 
					);
				}
			}
			
			$product_category = $this->model_catalog_product->getCategories ( $product ['product_id'] );
			
			foreach ( $product_category as $prodcat ) {
				$category_info = $this->model_catalog_category->getCategory ( $prodcat ['category_id'] );
				if ($category_info) {
					$productCategories [] = array (
							'name' => $category_info ['name'],
							'id' => $category_info ['category_id'] 
					);
				}
			}
			
			/* reviews */
			$this->load->model ( 'catalog/review' );
			
			$reviews ["review_total"] = $this->model_catalog_review->getTotalReviewsByProductId ( $product ['product_id'] );
			
			$reviewList = $this->model_catalog_review->getReviewsByProductId ( $product ['product_id'], 0, 1000 );
			
			foreach ( $reviewList as $review ) {
				$reviews ['reviews'] [] = array (
						'author' => $review ['author'],
						'text' => nl2br ( $review ['text'] ),
						'rating' => ( int ) $review ['rating'],
						'date_added' => date ( $this->language->get ( 'date_format_short' ), strtotime ( $review ['date_added'] ) ) 
				);
			}
		}
		
		return array (
				'id' => $product ['product_id'],
				'seo_h1' => (! empty ( $product ['seo_h1'] ) ? $product ['seo_h1'] : ""),
				'name' => $product ['name'],
				'manufacturer' => $product ['manufacturer'],
				'sku' => (! empty ( $product ['sku'] ) ? $product ['sku'] : ""),
				'model' => $product ['model'],
				'image' => $image,
				'images' => $images,
				'price' => $this->currency->restFormat ( $this->tax->calculate ( $product ['price'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) ),
				'price_formated' => $this->currency->format ( $this->tax->calculate ( $product ['price'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) ),
				'rating' => ( int ) $product ['rating'],
				'description' => html_entity_decode ( $product ['description'], ENT_QUOTES, 'UTF-8' ),
				'attribute_groups' => empty ( $simpleList ) ? $this->model_catalog_product->getProductAttributes ( $product ['product_id'] ) : array (),
				'special' => $special,
				'special_formated' => $special_formated,
				'special_start_date' => (! empty ( $product ['special_start_date'] ) ? $product ['special_start_date'] : ""),
				'special_end_date' => (! empty ( $product ['special_end_date'] ) ? $product ['special_end_date'] : ""),
				'discounts' => $discounts,
				'options' => $options,
				'minimum' => $product ['minimum'] ? $product ['minimum'] : 1,
				'meta_title' => $product ['meta_title'],
				'meta_description' => $product ['meta_description'],
				'meta_keyword' => $product ['meta_keyword'],
				'tag' => $product ['tag'],
				'upc' => $product ['upc'],
				'ean' => $product ['ean'],
				'jan' => $product ['jan'],
				'isbn' => $product ['isbn'],
				'mpn' => $product ['mpn'],
				'location' => $product ['location'],
				'stock_status' => $product ['stock_status'],
				'manufacturer_id' => $product ['manufacturer_id'],
				'tax_class_id' => $product ['tax_class_id'],
				'date_available' => $product ['date_available'],
				'weight' => $product ['weight'],
				'weight_class_id' => $product ['weight_class_id'],
				'length' => $product ['length'],
				'width' => $product ['width'],
				'height' => $product ['height'],
				'length_class_id' => $product ['length_class_id'],
				'subtract' => $product ['subtract'],
				'sort_order' => $product ['sort_order'],
				'status' => $product ['status'],
				'date_added' => $product ['date_added'],
				'date_modified' => $product ['date_modified'],
				'viewed' => $product ['viewed'],
				'weight_class' => $product ['weight_class'],
				'length_class' => $product ['length_class'],
				'reward' => $product ['reward'],
				'points' => $product ['points'],
				'category' => $productCategories,
				'quantity' => ! empty ( $product ['quantity'] ) ? $product ['quantity'] : 0,
				'reviews' => $reviews 
		);
	}
	public function search() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
			$requestjson = file_get_contents ( 'php://input' );
			
			$requestjson = json_decode ( $requestjson, true );
			$this->searchService ( $this->request, $requestjson );
		}
	}
	
	/*
	 * Search products
	 */
	public function searchService($request, $requestjson) {
		$json = array (
				'success' => false 
		);
		
		$this->load->model ( 'catalog/product' );
		
		$parameters = array (
				"limit" => 100,
				"start" => 1 
		);
		
		/* check limit parameter */
		if (isset ( $request->get ['limit'] ) && ctype_digit ( $request->get ['limit'] )) {
			$parameters ["limit"] = $request->get ['limit'];
		}
		
		/* check page parameter */
		if (isset ( $request->get ['page'] ) && ctype_digit ( $request->get ['page'] )) {
			$parameters ["start"] = $request->get ['page'];
		}
		
		$parameters ["start"] = ($parameters ["start"] - 1) * $parameters ["limit"];
		
		$products = $this->model_catalog_product->search ( $parameters, $requestjson, $this->customer );
		
		if (count ( $products ) == 0 || empty ( $products )) {
			$json ['success'] = false;
			$json ['error'] = "No product found";
		} else {
			$json ['success'] = true;
			foreach ( $products as $product ) {
				$json ['data'] [] = $this->getProductInfo ( $product );
			}
		}
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * CATEGORY FUNCTIONS
	 */
	public function categories() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			// get category details
			if (isset ( $this->request->get ['id'] ) && ctype_digit ( $this->request->get ['id'] )) {
				$this->getCategory ( $this->request->get ['id'] );
			} else {
				// get category list
				
				/* check parent parameter */
				if (isset ( $this->request->get ['parent'] )) {
					$parent = $this->request->get ['parent'];
				} else {
					$parent = 0;
				}
				
				/* check level parameter */
				if (isset ( $this->request->get ['level'] )) {
					$level = $this->request->get ['level'];
				} else {
					$level = 1;
				}
				
				$this->listCategories ( $parent, $level );
			}
		}
	}
	
	/*
	 * PRODUCT SPECIFIC INFOS
	 */
	public function productclasses() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			$json = array (
					'success' => true 
			);
			
			$this->load->model ( 'catalog/product' );
			
			$json ['data'] ['stock_statuses'] = $this->model_catalog_product->getStockStatuses ();
			$json ['data'] ['length_classes'] = $this->model_catalog_product->getLengthClasses ();
			$json ['data'] ['weight_classes'] = $this->model_catalog_product->getWeightClasses ();
			$stores_result = $this->model_catalog_product->getStores ();
			
			$stores = array ();
			
			foreach ( $stores_result as $result ) {
				$stores [] = array (
						'store_id' => $result ['store_id'],
						'name' => $result ['name'] 
				);
			}
			
			$default_store [] = array (
					'store_id' => 0,
					'name' => $this->config->get ( 'config_name' ) 
			);
			
			$json ['data'] ['stores'] = array_merge ( $default_store, $stores );
			
			$json ['data'] ['recurrings'] = $this->model_catalog_product->getRecurrings ();
			
			$this->load->model ( 'localisation/currency' );
			
			$json ['data'] ['currencies'] = array ();
			
			$results = $this->model_localisation_currency->getCurrencies ();
			
			foreach ( $results as $result ) {
				if ($result ['status']) {
					$json ['data'] ['currencies'] [] = array (
							'title' => $result ['title'],
							'code' => $result ['code'],
							'symbol_left' => $result ['symbol_left'],
							'symbol_right' => $result ['symbol_right'] 
					);
				}
			}
			$this->sendResponse ( $json );
		} else {
			$this->sendResponse ( array (
					'success' => false 
			) );
		}
	}
	
	/*
	 * Get categories list
	 */
	public function listCategories($parent, $level) {
		$json ['success'] = true;
		
		$this->load->model ( 'catalog/category' );
		
		$data = $this->loadCatTree ( $parent, $level );
		
		if (count ( $data ) == 0) {
			$json ['success'] = false;
			$json ['error'] = "No category found";
		} else {
			$json ['data'] = $data;
		}
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * Get category details
	 */
	public function getCategory($id) {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'catalog/category' );
		$this->load->model ( 'tool/image' );
		
		if (ctype_digit ( $id )) {
			$category_id = $id;
		} else {
			$category_id = 0;
		}
		
		$category = $this->model_catalog_category->getCategory ( $category_id );
		
		if (isset ( $category ['category_id'] )) {
			
			$json ['success'] = true;
			
			if (isset ( $category ['image'] ) && file_exists ( DIR_IMAGE . $category ['image'] )) {
				$image = $this->model_tool_image->resize ( $category ['image'], 100, 100 );
			} else {
				$image = $this->model_tool_image->resize ( 'no_image.jpg', 100, 100 );
			}
			
			$json ['data'] = array (
					'id' => $category ['category_id'],
					'name' => $category ['name'],
					'description' => $category ['description'],
					'image' => $image,
					'filters' => $this->getCategoryFilters ( $category_id ) 
			);
		} else {
			$json ['success'] = false;
			$json ['error'] = "The specified category does not exist.";
		}
		
		$this->sendResponse ( $json );
	}
	public function loadCatTree($parent = 0, $level = 1) {
		$this->load->model ( 'catalog/category' );
		$this->load->model ( 'tool/image' );
		
		$result = array ();
		
		$categories = $this->model_catalog_category->getCategories ( $parent );
		
		if ($categories && $level > 0) {
			$level --;
			
			foreach ( $categories as $category ) {
				
				if (isset ( $category ['image'] ) && file_exists ( DIR_IMAGE . $category ['image'] )) {
					$image = $this->model_tool_image->resize ( $category ['image'], 100, 100 );
				} else {
					$image = $this->model_tool_image->resize ( 'no_image.jpg', 100, 100 );
				}
				
				$result [] = array (
						'category_id' => $category ['category_id'],
						'parent_id' => $category ['parent_id'],
						'name' => $category ['name'],
						'image' => $image,
						'filters' => $this->getCategoryFilters ( $category ['category_id'] ),
						'categories' => $this->loadCatTree ( $category ['category_id'], $level ) 
				);
			}
			return $result;
		}
	}
	
	/*
	 * MANUFACTURER FUNCTIONS
	 */
	public function manufacturers() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			// get manufacturer details
			if (isset ( $this->request->get ['id'] ) && ctype_digit ( $this->request->get ['id'] )) {
				$this->getManufacturer ( $this->request->get ['id'] );
			} else {
				// get manufacturers list
				$this->listManufacturers ();
			}
		}
	}
	
	/*
	 * Get manufacturers list
	 */
	public function listManufacturers() {
		$this->load->model ( 'catalog/manufacturer' );
		$this->load->model ( 'tool/image' );
		$json = array (
				'success' => true 
		);
		
		$data ['start'] = 0;
		$data ['limit'] = 1000;
		
		$results = $this->model_catalog_manufacturer->getManufacturers ( $data );
		
		$manufacturers = array ();
		
		foreach ( $results as $manufacturer ) {
			$manufacturers [] = $this->getManufacturerInfo ( $manufacturer );
		}
		
		if (empty ( $manufacturers )) {
			$json ['success'] = false;
			$json ['error'] = "No manufacturer found";
		} else {
			$json ['data'] = $manufacturers;
		}
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * Get manufacturer details
	 */
	public function getManufacturer($id) {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'catalog/manufacturer' );
		$this->load->model ( 'tool/image' );
		
		if (ctype_digit ( $id )) {
			$manufacturer = $this->model_catalog_manufacturer->getManufacturer ( $id );
			if ($manufacturer) {
				$json ['data'] = $this->getManufacturerInfo ( $manufacturer );
			} else {
				$json ['success'] = false;
				$json ['error'] = "The specified manufacturer does not exist.";
			}
		} else {
			$json ['success'] = false;
		}
		
		$this->sendResponse ( $json );
	}
	private function getManufacturerInfo($manufacturer) {
		if (isset ( $manufacturer ['image'] ) && file_exists ( DIR_IMAGE . $manufacturer ['image'] )) {
			$image = $this->model_tool_image->resize ( $manufacturer ['image'], 100, 100 );
		} else {
			$image = $this->model_tool_image->resize ( 'no_image.jpg', 100, 100 );
		}
		
		return array (
				'manufacturer_id' => $manufacturer ['manufacturer_id'],
				'name' => $manufacturer ['name'],
				'image' => $image,
				'sort_order' => $manufacturer ['sort_order'] 
		);
	}
	
	/*
	 * ORDER FUNCTIONS
	 */
	public function orders() {
		$this->checkPlugin ();
		
		$this->returnDeprecated ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			// get order details
			if (isset ( $this->request->get ['id'] ) && ctype_digit ( $this->request->get ['id'] )) {
				$this->getOrder ( $this->request->get ['id'] );
			} else {
				// get orders list
				$this->listOrders ();
			}
		}
	}
	
	/*
	 * List orders
	 */
	public function listOrders() {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'account/order' );
		
		/* check offset parameter */
		if (isset ( $this->request->get ['offset'] ) && $this->request->get ['offset'] != "" && ctype_digit ( $this->request->get ['offset'] )) {
			$offset = $this->request->get ['offset'];
		} else {
			$offset = 0;
		}
		
		/* check limit parameter */
		if (isset ( $this->request->get ['limit'] ) && $this->request->get ['limit'] != "" && ctype_digit ( $this->request->get ['limit'] )) {
			$limit = $this->request->get ['limit'];
		} else {
			$limit = 10000;
		}
		
		/* get all orders of user */
		$results = $this->model_account_order->getAllOrders ( $offset, $limit );
		
		$orders = array ();
		
		if (count ( $results )) {
			foreach ( $results as $result ) {
				
				$product_total = $this->model_account_order->getTotalOrderProductsByOrderId ( $result ['order_id'] );
				$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId ( $result ['order_id'] );
				
				$orders [] = array (
						'order_id' => $result ['order_id'],
						'name' => $result ['firstname'] . ' ' . $result ['lastname'],
						'status' => $result ['status'],
						'date_added' => $result ['date_added'],
						'products' => ($product_total + $voucher_total),
						'total' => $result ['total'],
						'currency_code' => $result ['currency_code'],
						'currency_value' => $result ['currency_value'] 
				);
			}
			
			if (count ( $orders ) == 0) {
				$json ['success'] = false;
				$json ['error'] = "No orders found";
			} else {
				$json ['data'] = $orders;
			}
		} else {
			$json ['error'] = "No orders found";
			$json ['success'] = false;
		}
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * List orders whith details
	 */
	public function listorderswithdetails() {
		$this->checkPlugin ();
		
		$this->returnDeprecated ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			
			$json = array (
					'success' => true 
			);
			
			$this->load->model ( 'account/order' );
			
			/* check limit parameter */
			if (isset ( $this->request->get ['limit'] ) && $this->request->get ['limit'] != "" && ctype_digit ( $this->request->get ['limit'] )) {
				$limit = $this->request->get ['limit'];
			} else {
				$limit = 100000;
			}
			
			if (isset ( $this->request->get ['filter_date_added_from'] )) {
				$date_added_from = date ( 'Y-m-d H:i:s', strtotime ( $this->request->get ['filter_date_added_from'] ) );
				if ($this->validateDate ( $date_added_from )) {
					$filter_date_added_from = $date_added_from;
				}
			} else {
				$filter_date_added_from = null;
			}
			
			if (isset ( $this->request->get ['filter_date_added_on'] )) {
				$date_added_on = date ( 'Y-m-d', strtotime ( $this->request->get ['filter_date_added_on'] ) );
				if ($this->validateDate ( $date_added_on, 'Y-m-d' )) {
					$filter_date_added_on = $date_added_on;
				}
			} else {
				$filter_date_added_on = null;
			}
			
			if (isset ( $this->request->get ['filter_date_added_to'] )) {
				$date_added_to = date ( 'Y-m-d H:i:s', strtotime ( $this->request->get ['filter_date_added_to'] ) );
				if ($this->validateDate ( $date_added_to )) {
					$filter_date_added_to = $date_added_to;
				}
			} else {
				$filter_date_added_to = null;
			}
			
			if (isset ( $this->request->get ['filter_date_modified_on'] )) {
				$date_modified_on = date ( 'Y-m-d', strtotime ( $this->request->get ['filter_date_modified_on'] ) );
				if ($this->validateDate ( $date_modified_on, 'Y-m-d' )) {
					$filter_date_modified_on = $date_modified_on;
				}
			} else {
				$filter_date_modified_on = null;
			}
			
			if (isset ( $this->request->get ['filter_date_modified_from'] )) {
				$date_modified_from = date ( 'Y-m-d H:i:s', strtotime ( $this->request->get ['filter_date_modified_from'] ) );
				if ($this->validateDate ( $date_modified_from )) {
					$filter_date_modified_from = $date_modified_from;
				}
			} else {
				$filter_date_modified_from = null;
			}
			
			if (isset ( $this->request->get ['filter_date_modified_to'] )) {
				$date_modified_to = date ( 'Y-m-d H:i:s', strtotime ( $this->request->get ['filter_date_modified_to'] ) );
				if ($this->validateDate ( $date_modified_to )) {
					$filter_date_modified_to = $date_modified_to;
				}
			} else {
				$filter_date_modified_to = null;
			}
			
			if (isset ( $this->request->get ['page'] )) {
				$page = $this->request->get ['page'];
			} else {
				$page = 1;
			}
			
			if (isset ( $this->request->get ['filter_order_status_id'] )) {
				$filter_order_status_id = $this->request->get ['filter_order_status_id'];
			} else {
				$filter_order_status_id = null;
			}
			
			$data = array (
					'filter_date_added_on' => $filter_date_added_on,
					'filter_date_added_from' => $filter_date_added_from,
					'filter_date_added_to' => $filter_date_added_to,
					'filter_date_modified_on' => $filter_date_modified_on,
					'filter_date_modified_from' => $filter_date_modified_from,
					'filter_date_modified_to' => $filter_date_modified_to,
					'filter_order_status_id' => $filter_order_status_id,
					'start' => ($page - 1) * $limit,
					'limit' => $limit 
			);
			
			$results = $this->model_account_order->getOrdersByFilter ( $data );
			/* get all orders */
			// $results = $this->model_account_order->getAllOrders($offset, $limit);
			
			$orders = array ();
			
			if (count ( $results )) {
				
				foreach ( $results as $result ) {
					
					$orderData = $this->getOrderDetailsToOrder ( $result );
					
					if (! empty ( $orderData )) {
						$orders [] = $orderData;
					}
				}
				
				if (count ( $orders ) == 0) {
					$json ['success'] = false;
					$json ['error'] = "No orders found";
				} else {
					$json ['data'] = $orders;
				}
			} else {
				$json ['error'] = "No orders found";
				$json ['success'] = false;
			}
		} else {
			$json ['success'] = false;
		}
		
		$this->sendResponse ( $json );
	}
	
	/* Get order details */
	public function getOrder($order_id) {
		$this->load->model ( 'checkout/order' );
		$this->load->model ( 'account/order' );
		
		$json = array (
				'success' => true 
		);
		
		if (ctype_digit ( $order_id )) {
			$order_info = $this->model_checkout_order->getOrder ( $order_id );
			
			if (! empty ( $order_info )) {
				$json ['success'] = true;
				$json ['data'] = $this->getOrderDetailsToOrder ( $order_info );
			} else {
				$json ['success'] = false;
				$json ['error'] = "The specified order does not exist.";
			}
		} else {
			$json ['success'] = false;
			$json ['error'] = "Invalid order id";
		}
		
		$this->sendResponse ( $json );
	}
	
	/* Get all orders of user */
	public function userorders() {
		$this->checkPlugin ();
		
		$this->returnDeprecated ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			
			$json = array (
					'success' => true 
			);
			
			$user = null;
			
			/* check user parameter */
			if (isset ( $this->request->get ['user'] ) && $this->request->get ['user'] != "" && ctype_digit ( $this->request->get ['user'] )) {
				$user = $this->request->get ['user'];
			} else {
				$json ['success'] = false;
			}
			
			if ($json ['success'] == true) {
				$orderData ['orders'] = array ();
				
				$this->load->model ( 'account/order' );
				
				/* get all orders of user */
				$results = $this->model_account_order->getOrdersByUser ( $user );
				
				$orders = array ();
				
				foreach ( $results as $result ) {
					
					$product_total = $this->model_account_order->getTotalOrderProductsByOrderId ( $result ['order_id'] );
					$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId ( $result ['order_id'] );
					
					$orders [] = array (
							'order_id' => $result ['order_id'],
							'name' => $result ['firstname'] . ' ' . $result ['lastname'],
							'status' => $result ['status'],
							'date_added' => $result ['date_added'],
							'products' => ($product_total + $voucher_total),
							'total' => $result ['total'],
							'currency_code' => $result ['currency_code'],
							'currency_value' => $result ['currency_value'] 
					);
				}
				
				if (count ( $orders ) == 0) {
					$json ['success'] = false;
					$json ['error'] = "No orders found";
				} else {
					$json ['data'] = $orders;
				}
			} else {
				$json ['success'] = false;
			}
		}
		
		$this->sendResponse ( $json );
	}
	private function getOrderDetailsToOrder($order_info) {
		$this->load->model ( 'catalog/product' );
		
		$orderData = array ();
		
		if (! empty ( $order_info )) {
			foreach ( $order_info as $key => $value ) {
				$orderData [$key] = $value;
			}
			
			$orderData ['products'] = array ();
			
			$products = $this->model_account_order->getOrderProducts ( $orderData ['order_id'] );
			
			foreach ( $products as $product ) {
				$option_data = array ();
				
				$options = $this->model_account_order->getOrderOptionsMod ( $orderData ['order_id'], $product ['order_product_id'] );
				
				foreach ( $options as $option ) {
					if ($option ['type'] != 'file') {
						$option_data [] = array (
								'name' => $option ['name'],
								'value' => $option ['value'],
								'type' => $option ['type'],
								'product_option_id' => isset ( $option ['product_option_id'] ) ? $option ['product_option_id'] : "",
								'product_option_value_id' => isset ( $option ['product_option_value_id'] ) ? $option ['product_option_value_id'] : "",
								'option_id' => isset ( $option ['option_id'] ) ? $option ['option_id'] : "",
								'option_value_id' => isset ( $option ['option_value_id'] ) ? $option ['option_value_id'] : "" 
						);
					} else {
						$option_data [] = array (
								'name' => $option ['name'],
								'value' => utf8_substr ( $option ['value'], 0, utf8_strrpos ( $option ['value'], '.' ) ),
								'type' => $option ['type'] 
						);
					}
				}
				
				$origProduct = $this->model_catalog_product->getProduct ( $product ['product_id'] );
				
				$orderData ['products'] [] = array (
						'order_product_id' => $product ['order_product_id'],
						'product_id' => $product ['product_id'],
						'name' => $product ['name'],
						'model' => $product ['model'],
						'sku' => (! empty ( $origProduct ['sku'] ) ? $origProduct ['sku'] : ""),
						'option' => $option_data,
						'quantity' => $product ['quantity'],
						'price' => $this->currency->format ( $product ['price'] + ($this->config->get ( 'config_tax' ) ? $product ['tax'] : 0), $order_info ['currency_code'], $order_info ['currency_value'] ),
						'total' => $this->currency->format ( $product ['total'] + ($this->config->get ( 'config_tax' ) ? ($product ['tax'] * $product ['quantity']) : 0), $order_info ['currency_code'], $order_info ['currency_value'] ) 
				);
			}
		}
		
		$orderData ['histories'] = array ();
		
		$histories = $this->model_account_order->getOrderHistoriesRest ( $orderData ['order_id'], 0, 1000 );
		
		foreach ( $histories as $result ) {
			$orderData ['histories'] [] = array (
					'notify' => $result ['notify'] ? $this->language->get ( 'text_yes' ) : $this->language->get ( 'text_no' ),
					'status' => $result ['status'],
					'comment' => nl2br ( $result ['comment'] ),
					'date_added' => date ( $this->language->get ( 'date_format_short' ), strtotime ( $result ['date_added'] ) ) 
			);
		}
		
		// Voucher
		$orderData ['vouchers'] = array ();
		
		$vouchers = $this->model_account_order->getOrderVouchers ( $orderData ['order_id'] );
		
		foreach ( $vouchers as $voucher ) {
			$orderData ['vouchers'] [] = array (
					'description' => $voucher ['description'],
					'amount' => $this->currency->format ( $voucher ['amount'], $order_info ['currency_code'], $order_info ['currency_value'] ) 
			);
		}
		
		// Totals
		$orderData ['totals'] = array ();
		
		$totals = $this->model_account_order->getOrderTotals ( $orderData ['order_id'] );
		
		foreach ( $totals as $total ) {
			$orderData ['totals'] [] = array (
					'title' => $total ['title'],
					'text' => $this->currency->format ( $total ['value'], $order_info ['currency_code'], $order_info ['currency_value'] ) 
			);
		}
		
		return $orderData;
	}
	
	/*
	 * CUSTOMER FUNCTIONS
	 */
	public function customers() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			// get customer details
			if (isset ( $this->request->get ['id'] ) && ctype_digit ( $this->request->get ['id'] )) {
				$this->getCustomer ( $this->request->get ['id'] );
			} else {
				// get customers list
				$this->listCustomers ();
			}
		}
	}
	
	/*
	 * Get customers list
	 */
	private function listCustomers() {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'account/customer' );
		
		$results = $this->model_account_customer->getCustomersMod ();
		
		$customers = array ();
		
		foreach ( $results as $customer ) {
			$customers [] = $this->getCustomerInfo ( $customer );
		}
		
		if (count ( $customers ) == 0) {
			$json ['success'] = false;
			$json ['error'] = "No customers found";
		} else {
			$json ['data'] = $customers;
		}
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * Get customer details
	 */
	private function getCustomer($id) {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'account/customer' );
		
		if (ctype_digit ( $id )) {
			$customer = $this->model_account_customer->getCustomer ( $id );
			if (! empty ( $customer ['customer_id'] )) {
				$json ['data'] = $this->getCustomerInfo ( $customer );
			} else {
				$json ['success'] = false;
				$json ['error'] = "The specified customer does not exist.";
			}
		} else {
			$json ['success'] = false;
		}
		
		$this->sendResponse ( $json );
	}
	private function getCustomerInfo($customer) {
		// Custom Fields
		$this->load->model ( 'account/custom_field' );
		
		$custom_fields = $this->model_account_custom_field->getCustomFields ( $this->config->get ( 'config_customer_group_id' ) );
		
		if (strpos ( VERSION, '2.1.' ) === false) {
			$account_custom_field = unserialize ( $customer ['custom_field'] );
		} else {
			$account_custom_field = json_decode ( $customer ['custom_field'], true );
		}
		
		return array (
				'store_id' => $customer ['store_id'],
				'customer_id' => $customer ['customer_id'],
				'firstname' => $customer ['firstname'],
				'lastname' => $customer ['lastname'],
				'telephone' => $customer ['telephone'],
				'fax' => $customer ['fax'],
				'email' => $customer ['email'],
				'account_custom_field' => $account_custom_field,
				'custom_fields' => $custom_fields 
		)
		;
	}
	/*
	 * REVIEW FUNCTIONS
	 */
	public function reviews() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
			// add review
			$requestjson = file_get_contents ( 'php://input' );
			
			$requestjson = json_decode ( $requestjson, true );
			
			if (isset ( $this->request->get ['id'] ) && ctype_digit ( $this->request->get ['id'] ) && ! empty ( $requestjson )) {
				$this->addReview ( $this->request->get ['id'], $requestjson );
			} else {
				$this->sendResponse ( array (
						'success' => false 
				) );
			}
		} else {
			$this->sendResponse ( array (
					'success' => false 
			) );
		}
	}
	
	/* add review */
	public function addReview($id, $post) {
		$json ['success'] = false;
		
		$this->load->language ( 'product/product' );
		
		if ((utf8_strlen ( $post ['name'] ) < 3) || (utf8_strlen ( $post ['name'] ) > 25)) {
			$json ['error'] [] = $this->language->get ( 'error_name' );
		}
		
		if ((utf8_strlen ( $post ['text'] ) < 25) || (utf8_strlen ( $post ['text'] ) > 1000)) {
			$json ['error'] [] = $this->language->get ( 'error_text' );
		}
		
		if (empty ( $post ['rating'] ) || $post ['rating'] < 0 || $post ['rating'] > 5) {
			$json ['error'] [] = $this->language->get ( 'error_rating' );
		}
		
		if (! isset ( $json ['error'] )) {
			$this->load->model ( 'catalog/review' );
			$this->model_catalog_review->addReview ( $id, $post );
			$json ['success'] = "true";
		}
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * LANGUAGE FUNCTIONS
	 */
	public function languages() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			// get language details
			if (isset ( $this->request->get ['id'] ) && ctype_digit ( $this->request->get ['id'] )) {
				$this->getLanguage ( $this->request->get ['id'] );
			} else {
				// get languages list
				$this->listLanguages ();
			}
		}
	}
	
	/*
	 * ORDER STATUSES FUNCTIONS
	 */
	public function order_statuses() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			// get order statuses list
			$this->listOrderStatuses ();
		}
	}
	
	/*
	 * Get order statuses list
	 */
	private function listOrderStatuses() {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'account/order' );
		
		$statuses = $this->model_account_order->getOrderStatuses ();
		
		if (count ( $statuses ) == 0) {
			$json ['success'] = false;
			$json ['error'] = "No order status found";
		} else {
			$json ['data'] = $statuses;
		}
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * Get languages list
	 */
	private function listLanguages() {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'localisation/language' );
		
		$languages = $this->model_localisation_language->getLanguages ();
		
		if (count ( $languages ) == 0) {
			$json ['success'] = false;
			$json ['error'] = "No language found";
		} else {
			$json ['data'] = $languages;
		}
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * Get language details
	 */
	private function getLanguage($id) {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'localisation/language' );
		
		if (ctype_digit ( $id )) {
			$result = $this->model_localisation_language->getLanguage ( $id );
		} else {
			$json ['success'] = false;
			$json ['error'] = "Not valid id";
		}
		
		if (! empty ( $result )) {
			$json ['data'] = array (
					'language_id' => $result ['language_id'],
					'name' => $result ['name'],
					'code' => $result ['code'],
					'locale' => $result ['locale'],
					'image' => $result ['image'],
					'directory' => $result ['directory'],
					'filename' => $result ['filename'],
					'sort_order' => $result ['sort_order'],
					'status' => $result ['status'] 
			);
		} else {
			$json ['success'] = false;
			$json ['error'] = "The specified language does not exist.";
		}
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * STORE FUNCTIONS
	 */
	public function stores() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			// get store details
			if (isset ( $this->request->get ['id'] ) && ctype_digit ( $this->request->get ['id'] )) {
				$this->getStore ( $this->request->get ['id'] );
			} else {
				// get stores list
				$this->listStores ();
			}
		}
	}
	
	/*
	 * Get stores list
	 */
	private function listStores() {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'catalog/product' );
		
		$results = $this->model_catalog_product->getStores ();
		
		$stores = array ();
		
		foreach ( $results as $result ) {
			$stores [] = array (
					'store_id' => $result ['store_id'],
					'name' => $result ['name'] 
			);
		}
		
		$default_store [] = array (
				'store_id' => 0,
				'name' => $this->config->get ( 'config_name' ) 
		);
		
		$data = array_merge ( $default_store, $stores );
		
		if (count ( $data ) == 0) {
			$json ['success'] = false;
			$json ['error'] = "No store found";
		} else {
			$json ['data'] = $data;
		}
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * Get store details
	 */
	private function getStore($id) {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'catalog/product' );
		$result = array ();
		
		if (ctype_digit ( $id )) {
			$result = $this->model_catalog_product->getStore ( $id );
		} else {
			$json ['success'] = false;
		}
		if (count ( $result )) {
			// Store
			$json ['data'] ['store_id'] = $id; // Store id
			
			foreach ( $result as $setting ) {
				switch ($setting ['key']) {
					case 'config_name' :
						$json ['data'] ['store_name'] = $setting ['value']; // Store title
						break;
					case 'config_owner' :
						$json ['data'] ['store_owner'] = $setting ['value']; // Store owner
						break;
					case 'config_geocode' :
						$json ['data'] ['store_geocode'] = $setting ['value']; // Store geocode
						break;
					case 'config_address' :
						$json ['data'] ['store_address'] = $setting ['value']; // Store address
						break;
					case 'config_email' :
						$json ['data'] ['store_email'] = $setting ['value']; // Store email
						break;
					case 'config_telephone' :
						$json ['data'] ['store_telephone'] = $setting ['value']; // Store telephone
						break;
					case 'config_fax' :
						$json ['data'] ['store_fax'] = $setting ['value']; // Store fax
						break;
					case 'config_open' :
						$json ['data'] ['store_open'] = $setting ['value']; // Store open
						break;
					case 'config_comment' :
						$json ['data'] ['store_comment'] = $setting ['value']; // Store comment
						break;
					case 'config_language' :
						$json ['data'] ['store_language'] = $setting ['value']; // Store language
						break;
					case 'config_url' :
						$json ['data'] ['store_url'] = $setting ['value']; // Store url
						break;
					case 'config_image' :
						$json ['data'] ['store_image'] = $setting ['value']; // Store image
						$this->load->model ( 'tool/image' );
						if (! empty ( $setting ['value'] ) && is_file ( DIR_IMAGE . $setting ['value'] )) {
							$json ['data'] ['thumb'] = $this->model_tool_image->resize ( $setting ['value'], 200, 200 );
						} else {
							$json ['data'] ['thumb'] = $this->model_tool_image->resize ( 'no_image.png', 200, 200 );
						}
						break;
				}
			}
		} else {
			$json ['success'] = false;
			$json ['error'] = "The specified store does not exist.";
		}
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * COUNTRY FUNCTIONS
	 */
	public function countries() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			// get country details
			if (isset ( $this->request->get ['id'] ) && ctype_digit ( $this->request->get ['id'] )) {
				$this->getCountry ( $this->request->get ['id'] );
			} else {
				$this->listCountries ();
			}
		}
	}
	
	/*
	 * Get countries
	 */
	private function listCountries() {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'localisation/country' );
		
		$results = $this->model_localisation_country->getCountries ();
		
		$data = array ();
		
		foreach ( $results as $country ) {
			$data [] = $this->getCountryInfo ( $country, false );
		}
		
		if (count ( $results ) == 0) {
			$json ['success'] = false;
			$json ['error'] = "No country found";
		} else {
			$json ['data'] = $data;
		}
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * Get country details
	 */
	public function getCountry($country_id) {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'localisation/country' );
		
		$country_info = $this->model_localisation_country->getCountry ( $country_id );
		
		if (! empty ( $country_info )) {
			$json ["data"] = $this->getCountryInfo ( $country_info );
		} else {
			$json ['success'] = false;
			$json ['error'] = "The specified country does not exist.";
		}
		
		$this->sendResponse ( $json );
	}
	private function getCountryInfo($country_info, $addZone = true) {
		$this->load->model ( 'localisation/zone' );
		$info = array (
				'country_id' => $country_info ['country_id'],
				'name' => $country_info ['name'],
				'iso_code_2' => $country_info ['iso_code_2'],
				'iso_code_3' => $country_info ['iso_code_3'],
				'address_format' => $country_info ['address_format'],
				'postcode_required' => $country_info ['postcode_required'],
				'status' => $country_info ['status'] 
		);
		if ($addZone) {
			$info ['zone'] = $this->model_localisation_zone->getZonesByCountryId ( $country_info ['country_id'] );
		}
		
		return $info;
	}
	
	/*
	 * SESSION FUNCTIONS
	 */
	public function session() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			// get session details
			$this->getSessionId ();
		}
	}
	
	/*
	 * Get current session id
	 */
	public function getSessionId() {
		$json = array (
				'success' => true 
		);
		
		$json ['data'] = array (
				'session' => session_id () 
		);
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * FEATURED PRODUCTS FUNCTIONS
	 */
	public function featured() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			// get featured products
			$limit = 0;
			
			if (isset ( $this->request->get ['limit'] ) && ctype_digit ( $this->request->get ['limit'] ) && $this->request->get ['limit'] > 0) {
				$limit = $this->request->get ['limit'];
			}
			
			$this->getFeaturedProducts ( $limit );
		}
	}
	
	/*
	 * Get featured products
	 */
	public function getFeaturedProducts($limit) {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'catalog/product' );
		
		$this->load->model ( 'tool/image' );
		
		$featureds = $this->model_catalog_product->getModulesByCode ( 'featured' );
		$data = array ();
		$index = 0;
		
		if (count ( $featureds )) {
			foreach ( $featureds as $featured ) {
				$data [$index] ['module_id'] = $featured ['module_id'];
				$data [$index] ['name'] = $featured ['name'];
				$data [$index] ['code'] = $featured ['code'];
				
				if (strpos ( VERSION, '2.1.' ) === false) {
					$settings = unserialize ( $featured ['setting'] );
				} else {
					$settings = json_decode ( $featured ['setting'], true );
				}
				
				$products = $settings ['product'];
				
				if ($limit) {
					$products = array_slice ( $products, 0, ( int ) $limit );
				}
				
				foreach ( $products as $product_id ) {
					$product_info = $this->model_catalog_product->getProduct ( $product_id );
					
					if ($product_info) {
						if ($product_info ['image']) {
							$image = $this->model_tool_image->resize ( $product_info ['image'], 500, 500 );
						} else {
							$image = false;
						}
						
						if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
							$price = $this->currency->restFormat ( $this->tax->calculate ( $product_info ['price'], $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
							$price_formated = $this->currency->format ( $this->tax->calculate ( $product_info ['price'], $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
						} else {
							$price = false;
							$price_formated = false;
						}
						
						if (( float ) $product_info ['special']) {
							$special = $this->currency->restFormat ( ($this->tax->calculate ( $product_info ['special'], $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) )) );
							$special_formated = $this->currency->format ( $this->tax->calculate ( $product_info ['special'], $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
						} else {
							$special = false;
							$special_formated = false;
						}
						
						if ($this->config->get ( 'config_review_status' )) {
							$rating = $product_info ['rating'];
						} else {
							$rating = false;
						}
						
						$data [$index] ['products'] [] = array (
								'product_id' => $product_info ['product_id'],
								'thumb' => $image,
								'name' => $product_info ['name'],
								'price' => $price,
								'price_formated' => $price_formated,
								'special' => $special,
								'special_formated' => $special_formated,
								'rating' => $rating 
						);
					}
				}
				$index ++;
			}
		}
		$json ['data'] = $data;
		$this->sendResponse ( $json );
	}
	
	/*
	 * GET UTC AND LOCAL TIME DIFFERENCE
	 * returns offset in seconds
	 */
	public function utc_offset() {
		$this->checkPlugin ();
		
		$json = array (
				'success' => false 
		);
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			$serverTimeZone = date_default_timezone_get ();
			$timezone = new DateTimeZone ( $serverTimeZone );
			$now = new DateTime ( "now", $timezone );
			$offset = $timezone->getOffset ( $now );
			
			$json ['data'] = array (
					'offset' => $offset 
			);
			$json ['success'] = true;
		}
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * ADD ORDER HISTORY
	 */
	public function orderhistory() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'PUT') {
			$requestjson = file_get_contents ( 'php://input' );
			
			$requestjson = json_decode ( $requestjson, true );
			
			if (isset ( $this->request->get ['id'] ) && ctype_digit ( $this->request->get ['id'] ) && ! empty ( $requestjson )) {
				$this->addOrderHistory ( $this->request->get ['id'], $requestjson );
			} else {
				$this->sendResponse ( array (
						'success' => false 
				) );
			}
		}
	}
	private function addOrderHistory($id, $data) {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'checkout/order' );
		
		$order_info = $this->model_checkout_order->getOrder ( $id );
		
		if ($order_info) {
			$this->model_checkout_order->addOrderHistory ( $id, $data ['order_status_id'], $data ['comment'], $data ['notify'] );
		} else {
			$json ["success"] = false;
			$json ["error"] = "Order not found";
		}
		
		$this->sendResponse ( $json );
	}
	
	// date format validator
	private function validateDate($date, $format = 'Y-m-d H:i:s') {
		$d = DateTime::createFromFormat ( $format, $date );
		return $d && $d->format ( $format ) == $date;
	}
	
	/*
	 * BESTSELLERS FUNCTIONS
	 */
	public function bestsellers() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			$this->getBestsellers ( $this->request );
		}
	}
	
	/* check database modification */
	private function getBestsellers($request) {
		$this->load->model ( 'catalog/product' );
		
		$this->load->model ( 'tool/image' );
		
		$data ['products'] = array ();
		
		/* check limit parameter */
		$limit = 10;
		if (isset ( $request->get ['limit'] ) && ctype_digit ( $request->get ['limit'] )) {
			$limit = $request->get ['limit'];
		}
		
		$results = $this->model_catalog_product->getBestSellerProducts ( $limit );
		
		if ($results) {
			foreach ( $results as $result ) {
				if ($result ['image']) {
					$image = $this->model_tool_image->resize ( $result ['image'], 500, 500 );
				} else {
					$image = $this->model_tool_image->resize ( 'placeholder.png', 500, 500 );
				}
				
				if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
					$price = $this->currency->format ( $this->tax->calculate ( $result ['price'], $result ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
				} else {
					$price = false;
				}
				
				if (( float ) $result ['special']) {
					$special = $this->currency->format ( $this->tax->calculate ( $result ['special'], $result ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
				} else {
					$special = false;
				}
				
				if ($this->config->get ( 'config_tax' )) {
					$tax = $this->currency->format ( ( float ) $result ['special'] ? $result ['special'] : $result ['price'] );
				} else {
					$tax = false;
				}
				
				if ($this->config->get ( 'config_review_status' )) {
					$rating = $result ['rating'];
				} else {
					$rating = false;
				}
				
				$data ['products'] [] = array (
						'product_id' => $result ['product_id'],
						'thumb' => $image,
						'name' => $result ['name'],
						'description' => utf8_substr ( strip_tags ( html_entity_decode ( $result ['description'], ENT_QUOTES, 'UTF-8' ) ), 0, $this->config->get ( 'config_product_description_length' ) ) . '..',
						'price' => $price,
						'special' => $special,
						'tax' => $tax,
						'rating' => $rating,
						'href' => $this->url->link ( 'product/product', 'product_id=' . $result ['product_id'] ) 
				);
			}
		}
		
		$json = array (
				'success' => true,
				'data' => $data 
		);
		
		$this->sendResponse ( $json );
	}
	
	/*
	 * CATEGORY FILTER FUNCTIONS
	 */
	public function filters() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			
			$categoryId = 0;
			
			if (isset ( $this->request->get ['id'] ) && ctype_digit ( $this->request->get ['id'] )) {
				$categoryId = $this->request->get ['id'];
			}
			$this->getFilters ( $categoryId );
		}
	}
	
	/* get category filters */
	private function getFilters($category_id) {
		$data = array ();
		
		$this->load->model ( 'catalog/category' );
		
		$category_info = $this->model_catalog_category->getCategory ( $category_id );
		
		if ($category_info) {
			$data = $this->getCategoryFilters ( $category_id );
			$json = array (
					'success' => true,
					'data' => $data 
			);
		} else {
			$json = array (
					'success' => false,
					'error' => "Category does not exist" 
			);
		}
		
		$this->sendResponse ( $json );
	}
	private function getCategoryFilters($category_id) {
		$this->load->language ( 'module/filter' );
		$this->load->model ( 'catalog/product' );
		
		$data ['filter_groups'] = array ();
		
		$filter_groups = $this->model_catalog_category->getCategoryFilters ( $category_id );
		
		if ($filter_groups) {
			foreach ( $filter_groups as $filter_group ) {
				$childen_data = array ();
				
				foreach ( $filter_group ['filter'] as $filter ) {
					$filter_data = array (
							'filter_category_id' => $category_id,
							'filter_filter' => $filter ['filter_id'] 
					);
					
					$childen_data [] = array (
							'filter_id' => $filter ['filter_id'],
							'name' => $filter ['name'] . ($this->config->get ( 'config_product_count' ) ? ' (' . $this->model_catalog_product->getTotalProducts ( $filter_data ) . ')' : '') 
					);
				}
				
				$data ['filter_groups'] [] = array (
						'filter_group_id' => $filter_group ['filter_group_id'],
						'name' => $filter_group ['name'],
						'filter' => $childen_data 
				);
			}
		}
		
		return $data;
	}
	
	/*
	 * SLIDESHOW FUNCTIONS
	 */
	public function slideshows() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			$this->getSlideshows ();
		}
	}
	
	/*
	 * Get slideshows
	 */
	public function getSlideshows() {
		$json = array (
				'success' => true 
		);
		
		$this->load->model ( 'catalog/product' );
		$this->load->model ( 'extension/module' );
		$this->load->model ( 'design/banner' );
		$this->load->model ( 'tool/image' );
		
		$slideshows = $this->model_catalog_product->getModulesByCode ( 'slideshow' );
		$data = array ();
		$index = 0;
		
		if (count ( $slideshows )) {
			foreach ( $slideshows as $slideshow ) {
				$module_info = $this->model_extension_module->getModule ( $slideshow ['module_id'] );
				$data [$index] ['module_id'] = $slideshow ['module_id'];
				$data [$index] ['name'] = $module_info ['name'];
				$data [$index] ['banner_id'] = $module_info ['banner_id'];
				$data [$index] ['width'] = $module_info ['width'];
				$data [$index] ['height'] = $module_info ['height'];
				$data [$index] ['status'] = $module_info ['status'];
				
				$data [$index] ['banners'] = array ();
				
				$results = $this->model_design_banner->getBanner ( $module_info ['banner_id'] );
				
				foreach ( $results as $result ) {
					if (is_file ( DIR_IMAGE . $result ['image'] )) {
						$data [$index] ['banners'] [] = array (
								'title' => $result ['title'],
								'link' => $result ['link'],
								'image' => $this->model_tool_image->resize ( $result ['image'], $module_info ['width'], $module_info ['height'] ) 
						);
					}
				}
				$index ++;
			}
		}
		$json ['data'] = $data;
		$this->sendResponse ( $json );
	}
	
	/*
	 * GET RELATED PRODUCT FUNCTIONS
	 */
	public function related() {
		$this->checkPlugin ();
		
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			
			if (isset ( $this->request->get ['id'] ) && ctype_digit ( $this->request->get ['id'] )) {
				$this->getRelated ( $this->request->get ['id'] );
			} else {
				$this->sendResponse ( array (
						'success' => false,
						'Product id is required.' 
				) );
			}
		} else {
			$this->sendResponse ( array (
					'success' => false,
					'error' => 'Invalid HTTP method' 
			) );
		}
	}
	
	/* get related products */
	private function getRelated($id) {
		$this->load->model ( 'tool/image' );
		$data = array ();
		
		$this->load->model ( 'catalog/product' );
		
		$results = $this->model_catalog_product->getProductRelated ( $id );
		$data ['products'] = array ();
		foreach ( $results as $result ) {
			if ($result ['image']) {
				$image = $this->model_tool_image->resize ( $result ['image'], $this->config->get ( 'config_image_related_width' ), $this->config->get ( 'config_image_related_height' ) );
			} else {
				$image = $this->model_tool_image->resize ( 'placeholder.png', $this->config->get ( 'config_image_related_width' ), $this->config->get ( 'config_image_related_height' ) );
			}
			
			if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
				$price = $this->currency->format ( $this->tax->calculate ( $result ['price'], $result ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
			} else {
				$price = false;
			}
			
			if (( float ) $result ['special']) {
				$special = $this->currency->format ( $this->tax->calculate ( $result ['special'], $result ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
			} else {
				$special = false;
			}
			
			/*
			 * if ($this->config->get('config_tax')) {
			 * $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
			 * } else {
			 * $tax = false;
			 * }
			 */
			
			if ($this->config->get ( 'config_review_status' )) {
				$rating = ( int ) $result ['rating'];
			} else {
				$rating = false;
			}
			
			$data ['products'] [] = array (
					'product_id' => $result ['product_id'],
					'thumb' => $image,
					'name' => $result ['name'],
					'description' => utf8_substr ( strip_tags ( html_entity_decode ( $result ['description'], ENT_QUOTES, 'UTF-8' ) ), 0, $this->config->get ( 'config_product_description_length' ) ) . '..',
					'price' => $price,
					'special' => $special,
					// 'tax' => $tax,
					'minimum' => $result ['minimum'] > 0 ? $result ['minimum'] : 1,
					'rating' => $rating 
			);
		}
		
		if (! empty ( $data ['products'] )) {
			$json = array (
					'success' => true,
					'data' => $data ['products'] 
			);
		} else {
			$json = array (
					'success' => false,
					'error' => "No related product found" 
			);
		}
		
		$this->sendResponse ( $json );
	}
}