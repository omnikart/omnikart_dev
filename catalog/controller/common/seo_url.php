<?php
class ControllerCommonSeoUrl extends Controller {
	public function index() {
		// Add rewrite to url class
		if ($this->config->get ( 'config_seo_url' )) {
			$this->url->addRewrite ( $this );
		}
		
		// Decode URL
		if (isset ( $this->request->get ['_route_'] )) {
			$parts = explode ( '/', $this->request->get ['_route_'] );
			
			// remove any empty arrays from trailing
			if (utf8_strlen ( end ( $parts ) ) == 0) {
				array_pop ( $parts );
			}
			
			foreach ( $parts as $part ) {
				$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape ( $part ) . "'" );
				$mfilterConfig = $this->config->get ( 'mega_filter_seo' );
				
				if (! empty ( $mfilterConfig ['enabled'] ) && ! $query->num_rows) {
					$mfilter_query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "mfilter_url_alias` WHERE `alias` = '" . $this->db->escape ( $part ) . "'" );
					
					if ($mfilter_query->num_rows) {
						if (! isset ( $this->request->get ['mfp'] )) {
							$this->request->get ['mfp'] = $mfilter_query->row ['mfp'];
						}
						$this->request->get ['mfp_seo_alias'] = $part;
						
						continue;
					}
				}
				if ($query->num_rows) {
					$url = explode ( '=', $query->row ['query'] );
					
					if ($url [0] == 'product_id') {
						$this->request->get ['product_id'] = $url [1];
					}
					
					if ($url [0] == 'category_id') {
						if (! isset ( $this->request->get ['path'] )) {
							$this->request->get ['path'] = $url [1];
						} else {
							$this->request->get ['path'] .= '_' . $url [1];
						}
					}
					
					if ($url [0] == 'manufacturer_id') {
						$this->request->get ['manufacturer_id'] = $url [1];
					}
					
					if ($url [0] == 'information_id') {
						$this->request->get ['information_id'] = $url [1];
					}
					
					if ($url [0] == 'post_id') {
						$this->request->get ['post_id'] = $url [1];
					}
					
					if ($query->row ['query'] && $url [0] != 'information_id' && $url [0] != 'manufacturer_id' && $url [0] != 'category_id' && $url [0] != 'product_id' && $url [0] != 'post_id') {
						$this->request->get ['route'] = $query->row ['query'];
					}
				} elseif ($this->config->get ( 'marketplace_useseo' ) and $this->request->get ['_route_'] == 'Marketplace-Collection') {
					$this->request->get ['route'] = 'customerpartner/profile/collection';
				} elseif ($this->config->get ( 'marketplace_useseo' ) and is_array ( $this->config->get ( 'marketplace_SefUrlsvalue' ) ) and in_array ( $this->request->get ['_route_'], $this->config->get ( 'marketplace_SefUrlsvalue' ) )) {
					$sefKey = array_search ( $this->request->get ['_route_'], $this->config->get ( 'marketplace_SefUrlsvalue' ) );
					$wkSefUrlspath = $this->config->get ( 'marketplace_SefUrlspath' );
					if (isset ( $wkSefUrlspath [$sefKey] ))
						$this->request->get ['route'] = $wkSefUrlspath [$sefKey];
				} else {
					$this->request->get ['route'] = 'error/not_found';
					
					break;
				}
			}
			
			if (! isset ( $this->request->get ['route'] )) {
				if (isset ( $this->request->get ['product_id'] )) {
					$this->request->get ['route'] = 'product/product';
				} elseif (isset ( $this->request->get ['path'] )) {
					$this->request->get ['route'] = 'product/category';
				} elseif (isset ( $this->request->get ['manufacturer_id'] )) {
					$this->request->get ['route'] = 'product/manufacturer/info';
				} elseif (isset ( $this->request->get ['information_id'] )) {
					$this->request->get ['route'] = 'information/information';
				} elseif (isset ( $this->request->get ['post_id'] )) {
					$this->request->get ['route'] = 'blog/single';
				} elseif (isset ( $this->request->get ['blog_category_path'] )) {
					$this->request->get ['route'] = 'blog/category';
				}
			}
			
			if (isset ( $this->request->get ['route'] )) {
				return new Action ( $this->request->get ['route'] );
			}
		}
	}
	public function rewrite($link) {
		$url_info = parse_url ( str_replace ( '&amp;', '&', $link ) );
		
		$url = '';
		
		$data = array ();
		
		parse_str ( $url_info ['query'], $data );
		
		foreach ( $data as $key => $value ) {
			if (isset ( $data ['route'] )) {
				if (($data ['route'] == 'product/product' && $key == 'product_id') || (($data ['route'] == 'product/manufacturer/info' || $data ['route'] == 'product/product') && $key == 'manufacturer_id') || ($data ['route'] == 'information/information' && $key == 'information_id')) {
					$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape ( $key . '=' . ( int ) $value ) . "'" );
					
					if ($query->num_rows && $query->row ['keyword']) {
						$url .= '/' . $query->row ['keyword'];
						
						unset ( $data [$key] );
					}
				} elseif ($this->config->get ( 'marketplace_useseo' ) and $data ['route'] == 'customerpartner/profile/collection') {
					$url .= '/Marketplace-Collection';
					unset ( $data [$key] );
				} elseif ($this->config->get ( 'marketplace_useseo' ) and is_array ( $this->config->get ( 'marketplace_SefUrlspath' ) ) and in_array ( $data ['route'], $this->config->get ( 'marketplace_SefUrlspath' ) )) {
					$sefKey = array_search ( $data ['route'], $this->config->get ( 'marketplace_SefUrlspath' ) );
					$wkSefUrlsvalue = $this->config->get ( 'marketplace_SefUrlsvalue' );
					if (isset ( $wkSefUrlsvalue [$sefKey] )) {
						$url .= '/' . $wkSefUrlsvalue [$sefKey];
						unset ( $data [$key] );
					}
				} elseif ($key == 'path') {
					$categories = explode ( '_', $value );
					
					foreach ( $categories as $category ) {
						$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . ( int ) $category . "'" );
						
						if ($query->num_rows && $query->row ['keyword']) {
							$url .= '/' . $query->row ['keyword'];
						} else {
							$url = '';
							
							break;
						}
					}
					
					unset ( $data [$key] );
				} elseif ($data ['route'] == 'blog/single' && $key == 'post_id') {
					$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape ( $key . '=' . ( int ) $value ) . "'" );
					
					if ($query->num_rows && $query->row ['keyword']) {
						$url .= '/' . $query->row ['keyword'];
						
						unset ( $data [$key] );
					}
				}
			}
		}
		
		if ($url) {
			unset($data['route']);
			$query = '';
			if ($data) {
				
				$query = '?'.http_build_query($data);
				/*foreach ($data as $key => $value) {
					$query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((string)$value);
				}
				if ($query) {
					$query = '?' . str_replace('&', '&amp;', trim($query, '&'));
				}*/
			}
			return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
		} else {
			return $link;
		}
	}
}
