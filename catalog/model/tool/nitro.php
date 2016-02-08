<?php
class ModelToolNitro extends Model {
	public function __construct($register) {
		$this->loadCore ();
		parent::__construct ( $register );
	}
	public function getAllStores() {
		$this->load->model ( 'setting/store' );
		
		$stores_full = array_merge ( array (
				0 => array (
						'store_id' => '0',
						'name' => $this->config->get ( 'config_name' ),
						'url' => substr ( HTTP_SERVER, 0, strripos ( HTTP_SERVER, '/', - 2 ) + 1 ),
						'ssl' => substr ( HTTPS_SERVER, 0, strripos ( HTTPS_SERVER, '/', - 2 ) + 1 ) 
				) 
		), $this->model_setting_store->getStores () );
		
		$stores = array ();
		foreach ( $stores_full as $store_full ) {
			$stores [] = array (
					'url' => substr ( $store_full ['url'], stripos ( $store_full ['url'], '/' ) ),
					'store_id' => $store_full ['store_id'] 
			);
		}
		
		return $stores;
	}
	public function getCategoriesByStoreId($store_id = 0) {
		$cache_key = 'category.nitro.' . $store_id;
		
		$data = $this->cache->get ( $cache_key );
		
		if (! empty ( $data ))
			return $data;
		
		$result = $this->db->query ( "SELECT c.category_id FROM " . DB_PREFIX . "category_to_store c2s LEFT JOIN " . DB_PREFIX . "category c ON (c.category_id = c2s.category_id) WHERE c2s.store_id='" . $store_id . "' AND c.status=1" );
		
		foreach ( $result->rows as &$row ) {
			$parent_id = $row ['category_id'];
			$path = array ();
			
			while ( $parent_id > 0 ) {
				array_unshift ( $path, $parent_id );
				
				$result_2 = $this->db->query ( "SELECT parent_id FROM " . DB_PREFIX . "category WHERE category_id='" . $parent_id . "'" );
				
				if ($result_2->num_rows) {
					$parent_id = $result_2->row ['parent_id'];
				}
			}
			;
			
			$row ['path'] = implode ( '_', $path );
			unset ( $row ['category_id'] );
		}
		
		$this->cache->set ( $cache_key, $result->rows );
		
		return $result->rows;
	}
	public function getInformationsByStoreId($store_id = 0) {
		$result = $this->db->query ( "SELECT i.information_id FROM " . DB_PREFIX . "information_to_store i2s LEFT JOIN " . DB_PREFIX . "information i ON (i.information_id = i2s.information_id) WHERE i2s.store_id='" . $store_id . "' AND i.status=1" );
		
		return $result->rows;
	}
	public function from_admin_panel() {
		if (empty ( $this->session->data ['token'] ))
			return false;
		
		if (empty ( $this->session->data ['user_id'] ))
			return false;
		
		if (empty ( $this->request->get ['token'] ))
			return false;
		
		if ($this->request->get ['token'] != $this->session->data ['token'])
			return false;
		
		return true;
	}
	public function loadCore() {
		require_once (DIR_SYSTEM . 'nitro/core/core.php');
	}
	public function from_cron_url() {
		$this->loadCore ();
		
		if (empty ( $this->request->get ['cron_token'] ))
			return false;
		
		if (! getNitroPersistence ( 'CRON.Remote.Token' ))
			return false;
		
		if ($this->request->get ['cron_token'] != getNitroPersistence ( 'CRON.Remote.Token' ))
			return false;
		
		return true;
	}
	public function url($params) {
		if (! empty ( $params ['base'] )) {
			if (isset ( $this->request->server ['HTTPS'] ) && (($this->request->server ['HTTPS'] == 'on') || ($this->request->server ['HTTPS'] == '1'))) {
				$output = $this->config->get ( 'config_ssl' );
			} else {
				$output = $this->config->get ( 'config_url' );
			}
		} else {
			$output = $this->url->link ( $params ['route'], $params ['params'], 'SSL' );
		}
		
		return html_entity_decode ( $output );
	}
	public function clearDBCache() {
		$this->trunc_folder ( NITRO_DBCACHE_FOLDER, 'index.html' );
		return true;
	}
	public function clearProductCache($product_id) {
		$this->loadCore ();
		
		if (! getNitroPersistence ( 'PageCache.ClearCacheOnProductEdit' ))
			return;
		
		initNitroProductCacheDb ();
		
		$cachefile_query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "nitro_product_cache WHERE product_id='" . $product_id . "'" );
		
		foreach ( $cachefile_query->rows as $cachefile ) {
			$file = $cachefile ['cachefile'];
			
			if (file_exists ( $file ) && is_writable ( $file )) {
				unlink ( $file );
			}
			
			$filegz = $file . '.gz';
			
			if (file_exists ( $filegz ) && is_writable ( $filegz )) {
				unlink ( $filegz );
			}
		}
		
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "nitro_product_cache WHERE product_id='" . $product_id . "'" );
		
		$this->clearDBCache ();
	}
	private function trunc_folder($folder, $touch = false) {
		cleanFolder ( $folder, $touch );
	}
}