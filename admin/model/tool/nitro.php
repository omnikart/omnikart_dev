<?php
class ModelToolNitro extends Model {
	private $session_closed = false;
	
	public function __construct($register) {
		$this->loadCore();
		parent::__construct($register);
	}

	public function clearImageCache() {
		$this->trunc_folder(DIR_IMAGE . 'cache/', 'index.html');
		return true;
	}
	
	public function clearPageCache() {

		if ($this->user->hasPermission('modify', 'module/journal2')) {
            if (class_exists('Journal2Cache')) {
                Journal2Cache::deleteCache();
            }
        }
		
        initNitroProductCacheDb();
        $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "nitro_product_cache");

		$this->trunc_folder(NITRO_PAGECACHE_FOLDER, 'index.html');
		return true;
	}
	
	public function clearHeadersCache() {
		$this->trunc_folder(NITRO_HEADERS_FOLDER, 'index.html');
		return true;
	}
	
	public function clearDBCache() {
		$this->trunc_folder(NITRO_DBCACHE_FOLDER, 'index.html');
		return true;
	}
	
	public function clearJSCache() {
		$this->trunc_folder(dirname(DIR_APPLICATION) . DS . 'assets' . DS . 'js', 'index.html');
		return true;
	}
	
	public function clearTempJSCache() {
		$this->trunc_folder(NITRO_FOLDER . 'temp' . DS . 'js', 'index.html');
		return true;
	}
	
	public function clearCSSCache() {
		$this->trunc_folder(dirname(DIR_APPLICATION) . DS . 'assets' . DS . 'css', 'index.html');
		return true;
	}

	public function clearSystemCache() {
		$this->trunc_folder(DIR_CACHE);
		return true;
	}
	
	public function clearVqmodCache() {
		$this->trunc_folder(dirname(DIR_APPLICATION) . DS . 'vqmod' . DS . 'vqcache');

		$mods_cache = dirname(DIR_APPLICATION) . DS . 'mods.cache';
		if (file_exists($mods_cache)) {
			unlink($mods_cache);
		}

		return true;
	}
	
	public function loadCore() {
		require_once(DIR_SYSTEM . 'nitro/core/core.php');
	}
	
	public function loadCDN() {
		$this->loadCore();
		require_once(DIR_SYSTEM . 'nitro/core/cdn.php');
	}

	public function setPersistence($data) {
		$this->loadCore();
		return setNitroPersistence($data);
	}
	
	public function getPersistence($key = '') {
		$this->loadCore();
		return getNitroPersistence($key);
	}
	
	public function getSmushitPersistence() {
		$this->loadCore();
		return getNitroSmushitPersistence();
	}
	
	public function setSmushitPersistence($data) {
		$this->loadCore();
		return setNitroSmushitPersistence($data);
	}
	
	public function refreshGooglePageSpeedReport() {
		$this->loadCore();
		return refreshGooglePageSpeedReport();
	}
	
	public function getGoogleRawData() {
		$this->loadCore();
		refreshGooglePageSpeedReport();
		return getGooglePageSpeedReport();
	}
	
	public function getGooglePageSpeedReport($setting = null, $strategies = array()) {
		$this->loadCore();
		return getGooglePageSpeedReport($setting, $strategies);
	}
	
	public function setNitroPackModules($settings) {
		$ds = DIRECTORY_SEPARATOR;

		$dir = dirname(DIR_APPLICATION) . $ds . 'vqmod' . $ds . 'xml' . $ds;

		if (!empty($settings['NitroTemp']['ActiveModule']['pagecache'])) {
			if (file_exists($dir.'nitro_pagecache.xml_')) {
				rename($dir.'nitro_pagecache.xml_', $dir.'nitro_pagecache.xml');
			}
		} else {
			if (file_exists($dir.'nitro_pagecache.xml')) {
				rename($dir.'nitro_pagecache.xml', $dir.'nitro_pagecache.xml_');
			}
		}
		if (!empty($settings['NitroTemp']['ActiveModule']['cdn_generic'])) {
			if (file_exists($dir.'nitro_cdn_generic.xml_')) {
				rename($dir.'nitro_cdn_generic.xml_', $dir.'nitro_cdn_generic.xml');
			}
		} else {
			if (file_exists($dir.'nitro_cdn_generic.xml')) {
				rename($dir.'nitro_cdn_generic.xml', $dir.'nitro_cdn_generic.xml_');
			}
		}
		if (!empty($settings['NitroTemp']['ActiveModule']['db_cache'])) {
			if (file_exists($dir.'nitro_db_cache.xml_')) {
				rename($dir.'nitro_db_cache.xml_', $dir.'nitro_db_cache.xml');
			}
		} else {
			if (file_exists($dir.'nitro_db_cache.xml')) {
				rename($dir.'nitro_db_cache.xml', $dir.'nitro_db_cache.xml_');
			}
		}
		if (!empty($settings['NitroTemp']['ActiveModule']['image_cache'])) {
			if (file_exists($dir.'nitro_image_cache.xml_')) {
				rename($dir.'nitro_image_cache.xml_', $dir.'nitro_image_cache.xml');
			}
		} else {
			if (file_exists($dir.'nitro_image_cache.xml')) {
				rename($dir.'nitro_image_cache.xml', $dir.'nitro_image_cache.xml_');
			}
		}
		if (!empty($settings['NitroTemp']['ActiveModule']['jquery'])) {
			if (file_exists($dir.'nitro_jquery.xml_')) {
				rename($dir.'nitro_jquery.xml_', $dir.'nitro_jquery.xml');
			}
		} else {
			if (file_exists($dir.'nitro_jquery.xml')) {
				rename($dir.'nitro_jquery.xml', $dir.'nitro_jquery.xml_');
			}
		}
		if (!empty($settings['NitroTemp']['ActiveModule']['minifier'])) {
			if (file_exists($dir.'nitro_minifier.xml_')) {
				rename($dir.'nitro_minifier.xml_', $dir.'nitro_minifier.xml');
			}
		} else {
			if (file_exists($dir.'nitro_minifier.xml')) {
				rename($dir.'nitro_minifier.xml', $dir.'nitro_minifier.xml_');
			}
		}
		if (!empty($settings['NitroTemp']['ActiveModule']['product_count_fix'])) {
			if (file_exists($dir.'nitro_product_count_fix.xml_')) {
				rename($dir.'nitro_product_count_fix.xml_', $dir.'nitro_product_count_fix.xml');
			}
		} else {
			if (file_exists($dir.'nitro_product_count_fix.xml')) {
				rename($dir.'nitro_product_count_fix.xml', $dir.'nitro_product_count_fix.xml_');
			}
		}
		if (!empty($settings['NitroTemp']['ActiveModule']['system_cache'])) {
			if (file_exists($dir.'nitro_system_cache.xml_')) {
				rename($dir.'nitro_system_cache.xml_', $dir.'nitro_system_cache.xml');
			}
		} else {
			if (file_exists($dir.'nitro_system_cache.xml')) {
				rename($dir.'nitro_system_cache.xml', $dir.'nitro_system_cache.xml_');
			}
		}
		if (!empty($settings['NitroTemp']['ActiveModule']['pagecache_widget'])) {
			if (file_exists($dir.'nitro_pagecache_widget.xml_')) {
				rename($dir.'nitro_pagecache_widget.xml_', $dir.'nitro_pagecache_widget.xml');
			}
		} else {
			if (file_exists($dir.'nitro_pagecache_widget.xml')) {
				rename($dir.'nitro_pagecache_widget.xml', $dir.'nitro_pagecache_widget.xml_');
			}
		}
	}
	
	public function getActiveNitroModules() {
		$active_modules = array();
		$ds = DIRECTORY_SEPARATOR;
		$dir = dirname(DIR_APPLICATION).$ds.'vqmod'.$ds.'xml'.$ds;
		if (file_exists($dir.'nitro_pagecache.xml')) {
				$active_modules[] = 'pagecache';
		}
		if (file_exists($dir.'nitro_cdn_generic.xml')) {
				$active_modules[] = 'cdn_generic';
		}
		if (file_exists($dir.'nitro_db_cache.xml')) {
				$active_modules[] = 'db_cache';
		}
		if (file_exists($dir.'nitro_image_cache.xml')) {
				$active_modules[] = 'image_cache';
		}
		if (file_exists($dir.'nitro_jquery.xml')) {
				$active_modules[] = 'jquery';
		}
		if (file_exists($dir.'nitro_minifier.xml')) {
				$active_modules[] = 'minifier';
		}
		if (file_exists($dir.'nitro_product_count_fix.xml')) {
				$active_modules[] = 'product_count_fix';
		}
		if (file_exists($dir.'nitro_system_cache.xml')) {
				$active_modules[] = 'system_cache';
		}
		if (file_exists($dir.'nitro_pagecache_widget.xml')) {
				$active_modules[] = 'pagecache_widget';
		}
		return $active_modules;
	}
	
	public function applyNitroCacheHTRules() {
		$this->load->model('tool/nitro_htaccess');
		$this->model_tool_nitro_htaccess->applyHtaccessRules();
	}
	
	public function applyNitroCacheHTCompressionRules() {
		$this->load->model('tool/nitro_htaccess');
		$this->model_tool_nitro_htaccess->applyHtaccessCompressionRules();
	}
	
	private function sizeToString($size) {
		$count = 0;
		for ($i = $size; $i >= 1024; $i /= 1024) $count++;
		switch ($count) {
			case 0 : $suffix = ' B'; break;
			case 1 : $suffix = ' KB'; break;
			case 2 : $suffix = ' MB'; break;
			case 3 : $suffix = ' GB'; break;
			case ($count >= 4) : $suffix = ' TB'; break;
		}
		return round($i, 2) . $suffix;
	}
	
	public function get_valid_extensions($data) {
    $ext = array();

    if ($data['css']) {
      $ext = array_merge($ext, unserialize(NITRO_EXTENSIONS_CSS));
    }
    if ($data['js']) {
      $ext = array_merge($ext, unserialize(NITRO_EXTENSIONS_JS));
    }
    if ($data['images']) {
      $ext = array_merge($ext, unserialize(NITRO_EXTENSIONS_IMG));
    }

    return $ext;
  }

	public function cdn_init_db($cdn = false) {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "nitro_cdn_files` ( `id` int(10) unsigned NOT NULL AUTO_INCREMENT, `file` text NOT NULL, `realpath` text NOT NULL, `cdn` tinyint(1), `size` int(10) unsigned NOT NULL, `uploaded` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`), KEY `file` (`file`(20),`uploaded`,`cdn`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		if ($cdn) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "nitro_cdn_files WHERE cdn=" . $cdn);

			$ai = 0;

			$ai_query = $this->db->query("SELECT MAX(id) as max FROM " . DB_PREFIX . "nitro_cdn_files");

			if ($ai_query->num_rows) {
				$ai = $ai_query->row['max'] + 1;
			}

			$this->db->query("ALTER TABLE " . DB_PREFIX . "nitro_cdn_files AUTO_INCREMENT = " . $ai . ";");
		}
	}

	public function cdn_prepare_files($data) {
		$response = &$this->request->post['last'];

		$response['done'] = false;
		$response['response_type'] = '';

		if (empty($response['step'])) $response['step'] = 'list';

		$items = array();
        if (!class_exists('NitroFiles')) require_once(NITRO_LIB_FOLDER . 'NitroFiles.php');

		if ($response['step'] == 'list') {
			if (empty($response['continue_from'])) {
                $this->cdn_init_db($data['cdn']);
                $response['continue_from'] = '';
            }

            $admin_folder = basename(DIR_APPLICATION);

			$files = new NitroFiles(array(
				'ext' => $data['valid_extensions'],
				'root' => dirname(DIR_SYSTEM),
				'continue_from' => $response['continue_from'],
                'debug' => $response['continue_from'] != '',
				'batch' => NITRO_CDN_PREPARE_CHUNK,
				'rules' => array(
					array(
						'rule' => '/' . $admin_folder . '/',
						'match' => false
					),
					array(
						'rule' => '/blog\//i',
						'match' => false
					),
					array(
						'rule' => '/\/te?mp\//i',
						'match' => false
					)
				)
			));

			$items = $files->find();

			if (!empty($items)) {
				$response['message'] = 'Looking for files...';

				foreach ($items as $item) {
					$exists_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "nitro_cdn_files WHERE file='" . $this->db->escape($item['rel_path']) . "' AND uploaded=1 AND cdn=" . $data['cdn']);

					if ($exists_query->num_rows == 0) {
						$this->db->query("DELETE FROM " . DB_PREFIX . "nitro_cdn_files WHERE file='" . $this->db->escape($item['rel_path']) . "'");
						$this->db->query("INSERT INTO " . DB_PREFIX . "nitro_cdn_files SET file='" . $this->db->escape($item['rel_path']) . "', size='" . $item['size'] . "', realpath='" . $this->db->escape($item['full_path']) . "', uploaded=0, cdn=" . $data['cdn']);
					}

					$response['message'] = 'Found ' . $item['rel_path'];
					$response['continue_from'] = $files->getContinueFrom();
				}
			} else {
				$response['message'] = 'Starting upload...';
				$response['step'] = 'upload';
				$response['continue_from'] = '0';

				$total_query = $this->db->query("SELECT SUM(size) as total FROM " . DB_PREFIX . "nitro_cdn_files WHERE cdn=" . $data['cdn']);

				$response['all'] = $total_query->row['total'];
			}

		} elseif ($response['step'] == 'upload') {
			$items_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "nitro_cdn_files WHERE cdn=" . $data['cdn'] . " LIMIT " . (int)$response['continue_from'] . ', ' . NITRO_CDN_UPLOAD_CHUNK);

			$response['continue_from'] = (int)$response['continue_from'] + NITRO_CDN_UPLOAD_CHUNK;

			if ($items_query->num_rows) {
				$items = $items_query->rows;
			} else {
				$response['done'] = true;
			}

		}

		return $items;
	}

	private function is_class_method($type="public", $method, $class) { 
		$refl = new ReflectionMethod($class, $method); 

		switch($type) { 
		    case "static": 
		    	return $refl->isStatic(); 
		    	break; 
		    case "public": 
		    	return $refl->isPublic(); 
		    	break; 
		    case "private": 
		    	return $refl->isPrivate(); 
		    	break; 
		} 
	} 

	private function vqmod_resolve($file) {

		if (class_exists('VQMod')) {
		  if ($this->is_class_method('static', 'modCheck', 'VQMod')) {
		    $file = VQMod::modCheck($file);
		  } else {
		    $vqmod = new VQMod();
		    $file = $vqmod->modCheck($file);
		  }
		}

		return $file;
	}

	public function load_catalog_model($model) {
		$file  = dirname(DIR_APPLICATION) . '/catalog/model/' . $model . '.php';
		$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);

		if (file_exists($file)) { 
			$contents = trim(file_get_contents($this->vqmod_resolve($file)));
			$contents = str_ireplace('class ' . $class, 'class Catalog' . $class, $contents);
			$class = 'Catalog' . $class;

			$contents = preg_replace('~^\<\?php~', '', $contents);
			$contents = preg_replace('~\?\>$~', '', $contents);

			eval($contents);

			$this->registry->set('catalogmodel_' . str_replace('/', '_', $model), new $class($this->registry));
		} else {
			trigger_error('Error: Could not load model ' . $model . '!');
			exit();					
		}
	}

	public function cdn_precache_images(&$found_items) {
		if (empty($found_items)) return;

		$new_items = $found_items;
		$original_store_id = $this->config->get('config_store_id');

		$stores = array(0);
		$this->load->model('setting/store');
		$all_stores = $this->model_setting_store->getStores();
		foreach ($all_stores as $multistore_item) {
			$stores[] = $multistore_item['store_id'];
		}

		$this->load_catalog_model('tool/image');

		$image_settings = array(
			array(
				'w' => $this->config->get('config_image_category_width'),
				'h' => $this->config->get('config_image_category_height')
			),
			array(
				'w' => $this->config->get('config_image_thumb_width'),
				'h' => $this->config->get('config_image_thumb_height')
			),
			array(
				'w' => $this->config->get('config_image_popup_width'),
				'h' => $this->config->get('config_image_popup_height')
			),
			array(
				'w' => $this->config->get('config_image_product_width'),
				'h' => $this->config->get('config_image_product_height')
			),
			array(
				'w' => $this->config->get('config_image_additional_width'),
				'h' => $this->config->get('config_image_additional_height')
			),
			array(
				'w' => $this->config->get('config_image_related_width'),
				'h' => $this->config->get('config_image_related_height')
			),
			array(
				'w' => $this->config->get('config_image_compare_width'),
				'h' => $this->config->get('config_image_compare_height')
			),
			array(
				'w' => $this->config->get('config_image_wishlist_width'),
				'h' => $this->config->get('config_image_wishlist_height')
			),
			array(
				'w' => $this->config->get('config_image_cart_width'),
				'h' => $this->config->get('config_image_cart_height')
			)
		);

		

		if (!function_exists('unique_dimensions')) {
			function unique_dimensions($val) {
				if (empty($GLOBALS['unique_dimensions'])) {
					$GLOBALS['unique_dimensions'] = array();
				}

				if (!function_exists('val_exist')) {
					function val_exist($val, $array) {
						foreach($array as $current) {
							if ($current['w'] == $val['w'] && $current['h'] == $val['h']) {
								return true;
							}
						}

						return false;
					}
				}

				if (!val_exist($val, $GLOBALS['unique_dimensions'])) {
					$GLOBALS['unique_dimensions'][] = $val;
					return true;
				}

				return false;
			}
		}

		$this->load->model('setting/setting');
		
		if (!function_exists('apply_module_dimensions')) {
			function apply_module_dimensions(&$image_settings, $width_key, $height_key, $module, &$setting) {
				$all_modules = $setting->getSetting($module);
				if (!empty($all_modules[$module . "_module"]) && is_array($all_modules[$module . "_module"])) {
					foreach ($all_modules[$module . "_module"] as $my_module) {
						if (empty($my_module[$width_key]) || empty($my_module[$height_key])) continue;

						$image_settings[] = array(
							'w' => $my_module[$width_key],
							'h' => $my_module[$height_key]
						);
					}
				}
			}
		}

		//apply_module_dimensions($image_settings, 'width', 'height', 'banner', $this->model_setting_setting);
		apply_module_dimensions($image_settings, 'image_width', 'image_height', 'bestseller', $this->model_setting_setting);
		apply_module_dimensions($image_settings, 'width', 'height', 'carousel', $this->model_setting_setting);
		apply_module_dimensions($image_settings, 'image_width', 'image_height', 'featured', $this->model_setting_setting);
		apply_module_dimensions($image_settings, 'image_width', 'image_height', 'latest', $this->model_setting_setting);
		//apply_module_dimensions($image_settings, 'width', 'height', 'slideshow', $this->model_setting_setting);
		apply_module_dimensions($image_settings, 'image_width', 'image_height', 'special', $this->model_setting_setting);
		
		$image_settings = array_filter($image_settings, 'unique_dimensions');
		
		$image_base_realpath = realpath(DIR_IMAGE) . DS;

		foreach ($found_items as $item) {
			if (stripos($item['full_path'], $image_base_realpath) !== 0) continue;

			$rel_path = substr($item['full_path'], strlen($image_base_realpath));

			if (stripos($rel_path, 'cache') === 0) continue;

			$extension = strtolower(pathinfo($item['full_path'], PATHINFO_EXTENSION));

			if (!in_array($extension, array('jpg', 'jpeg', 'png', 'gif'))) continue;

			// We know that $rel_path contains a suitable path for resize.
			
			foreach ($stores as $store_id) {
				$this->config->set('config_store_id', $store_id);

				foreach ($image_settings as $image_setting) {

					try {
						$image_file = DIR_IMAGE . $rel_path;

						if ($item['size'] > NITRO_CDN_RESIZE_MIN_FILESIZE) {
							$image_info = getimagesize($image_file);
							if (in_array($image_info['mime'], array('image/gif', 'image/png', 'image/jpeg'))) {
								$url_path = $this->catalogmodel_tool_image->resize($rel_path, $image_setting['w'], $image_setting['h']);

								$new_image = $this->cdn_construct_image_path($url_path, $image_base_realpath);
								if (!empty($new_image)) {
									$new_items[] = $new_image;
								}
							}
						}
					} catch(Exception $e) {
						// do nothing.    
					}
				}
			}
		}

		$this->config->set('config_store_id', $original_store_id);

		function path_cmp($a, $b) {
		    if ($a['full_path'] == $b['full_path']) {
		        return 0;
		    }
		    return ($a['full_path'] < $b['full_path']) ? -1 : 1;
		}

		//uasort($new_items, 'path_cmp');

		if (NITRO_CDN_PREPARE_CHUNK > 0) {
			//$new_items = array_slice($new_items, 0, NITRO_CDN_PREPARE_CHUNK);
		}

		//$found_items = $new_items;
	}

	public function cdn_construct_image_path($url_path, $base) {

		$image_parts = array_filter(explode('/', DIR_IMAGE));
		$image_folder = array_pop($image_parts);

		$search = '/' . $image_folder . '/cache/';

		$search_len = strlen($search);

		$substr_pos = stripos($url_path, $search) + $search_len;

		$new_image = 'cache/' . substr($url_path, $substr_pos);

		$new_full_path = $base . $new_image;

		if (file_exists($new_full_path)) {
			return array(
				'rel_path' => $new_image,
				'full_path' => $new_full_path,
				'size' => filesize($new_full_path)
			);
		}

		return array();
	}

	public function cdn_after_upload($item) {
		$response = &$this->request->post['last'];

		$this->db->query("UPDATE " . DB_PREFIX . "nitro_cdn_files SET uploaded=1 WHERE id='" . $item['id'] . "'");

		if (empty($response['start'])) {
			$response['start'] = time();
		}

		if (empty($response['uploaded'])) {
			$response['uploaded'] = 0;
		}

		$response['uploaded'] += $item['size'];

		$response['percent'] = ceil(($response['uploaded'] * 100) / $response['all']);

		$interval = (time() - $response['start']);
		$interval = empty($interval) ? 1 : $interval;
		$speed = ceil($response['uploaded'] / $interval);

		$time_remaining = ceil(($response['all'] - $response['uploaded']) / $speed);
		$time_remaining = (str_pad(floor($time_remaining / 3600), 2, '0', STR_PAD_LEFT) . ':' . str_pad(floor($time_remaining % 3600 / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad(($time_remaining % 60), 2, '0', STR_PAD_LEFT));

		$response['message'] = 'Uploaded ' . $item['file'] . '<br />Speed: ' . $this->sizeToString($speed) . '/s<br />Time remaining: ' . $time_remaining . '';
	}

	public function generateUpToDateMimeArray($url){ //FUNCTION FROM Josh Sean @ http://www.php.net/manual/en/function.mime-content-type.php
		if (!empty($GLOBALS['nitro.mimes'])) return $GLOBALS['nitro.mimes'];

		$s=array('gz' => 'application/x-gzip');
		foreach(@explode("\n",@file_get_contents($url))as $x)
			if(isset($x[0])&&$x[0]!=='#'&&preg_match_all('#([^\s]+)#',$x,$out)&&isset($out[1])&&($c=count($out[1]))>1)
				for($i=1;$i<$c;$i++)
					$s[$out[1][$i]]=$out[1][0];

		if (!empty($s)) {
			$GLOBALS['nitro.mimes'] = $s;
		}

		return ($s)?$s:false;
	}
	
	public function getServerInfo($permission) {
		$text_no_permission = '<div class="info-error">You do not have permissions to view this.</div>';
		$result = array();
		
		/* PHP VERSION */
		if (!$permission) $result['php_version'] = $text_no_permission;
		else {
			$result['php_version'] = PHP_VERSION;
		}
		
		/* PHP User */
		$nitro_folder = defined('NITRO_FOLDER') ? NITRO_FOLDER : (DIR_SYSTEM.'nitro'.DIRECTORY_SEPARATOR);
		$php_user = 'Cannot be determined';
		if (is_writable($nitro_folder)) {
			touch($nitro_folder.'test_user');
			if (file_exists($nitro_folder.'test_user') && function_exists('posix_getpwuid')) {
				$user_info = @posix_getpwuid(fileowner($nitro_folder.'test_user'));
				if (!empty($user_info)) {
					$php_user = $user_info['name'];
				}
				unlink($nitro_folder.'test_user');
			}
		}
		$result['php_user'] = $php_user;
		
		/* WEB SERVER */
		if (!$permission) $result['web_server'] = $text_no_permission;
		else {
			if (ini_get('allow_url_fopen') == 1 || strtolower(ini_get('allow_url_fopen')) == 'off') {
				if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
					$url = HTTP_CATALOG;
				} else {
					$url = HTTPS_CATALOG;
				}
				
				$status_results = file_get_contents('http://tools.seobook.com/server-header-checker/?page=bulk&url=' . $url . '&useragent=1&typeProtocol=11');
				preg_match('~\<strong\>SERVER RESPONSE\<\/strong\>[^h]*(.*?)\<\/p\>~i', $status_results, $matches);
				$status = array_pop($matches);

				if (stripos($status, 'HTTP/1.1 2') !== FALSE) $type = 'success';
				else if (stripos($status, 'HTTP/1.1 3') !== FALSE) $type = 'warning';
				else $type = 'error';
				
				$status = '<span class="info-' . $type . '"><strong>' . $status . '</strong></span>';
				
			} else $status = '<span class="info-warning"><strong>Unknown (allow_url_fopen is Off)</strong></span>';
			$result['web_server'] = 'OS: ' . PHP_OS . ' | SAPI: ' . PHP_SAPI . ' | Status: ' . $status;
		}
		
		/* FTP FUNCTIONS */
		if (!$permission) $result['ftp_functions'] = $text_no_permission;
		else {
			$ftp = array();
			
			if (function_exists('ftp_ssl_connect')) {
				$ftp[] = 'ftp_ssl_connect()';
			}
			if (function_exists('ftp_connect')) {
				$ftp[] = 'ftp_connect()';
			}
			
			$result['ftp_functions'] = empty($ftp) ? 'No FTP functions available.' : implode(', ', $ftp);
		}
		
		/* OpenSSL */
		if (!$permission) $result['openssl'] = $text_no_permission;
		else {
			$result['openssl'] = function_exists('openssl_open') ? '<span class="info-success"><strong>YES</strong></span>' : '<span class="info-error"><strong>NO</strong></span>';
		}
		
		/* CURL */
		if (!$permission) $result['curl'] = $text_no_permission;
		else {
			if (function_exists('curl_init')) {
				$info = curl_version();
				$curl = '<span class="info-success"><strong>YES</strong></span> | Version: ' . $info['version'] . ' | Protocols: ' . implode(', ', $info['protocols']);
			} else {
				$curl = '<span class="info-error"><strong>NO</strong></span>';
			}
			
			$result['curl'] = $curl;
		}
		
		/* MemCache */
		if (!$permission) $result['memcache'] = $text_no_permission;
		else {
			$result['memcache'] = class_exists('Memcache') ? '<span class="info-success"><strong>YES</strong></span>' : '<span class="info-error"><strong>NO</strong></span>';
		}
		
		/* exec() */
		if (!$permission) $result['exec'] = $text_no_permission;
		else {
			$exec_enabled = $this->exec_enabled();
			
			$result['exec'] = $exec_enabled ? '<span class="info-success"><strong>YES</strong></span>' : '<span class="info-error"><strong>NO</strong></span>';
		}
		
		/* zlib */
		if (!$permission) $result['zlib'] = $text_no_permission;
		else {
			$result['zlib'] = function_exists('gzencode') ? '<span class="info-success"><strong>YES</strong></span>' : '<span class="info-error"><strong>NO</strong></span>';
		}
		
		/* safe mode */
		if (!$permission) $result['safe_mode'] = $text_no_permission;
		else {
			$safe_mode = (strtolower(ini_get('safe_mode')) != 'off' && ini_get('safe_mode') != 0);
			
			$result['safe_mode'] = $safe_mode ? '<span><strong>Enabled</strong></span>' : '<span><strong>Disabled</strong></span>';
		}
		
		if (function_exists('apache_get_modules')) {
			$modules = strtolower(implode('|', apache_get_modules()));
		} else {
			$shell_exec_enabled =
				 function_exists('shell_exec') &&
				 !in_array('shell_exec', array_map('trim',explode(', ', ini_get('disable_functions')))) &&
						  !(strtolower(ini_get('safe_mode')) != 'off' && ini_get('safe_mode') != 0);
						  
		    if ($shell_exec_enabled) {
				$modules = strtolower(shell_exec('apachectl -l'));
			} else {
				$modules = false;
			}
		}
		
		/* mod_deflate */
		if (!$permission) $result['mod_deflate'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_deflate') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_deflate'] = $mod_result;
		}
		
		/* mod_env */
		if (!$permission) $result['mod_env'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_env') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_env'] = $mod_result;
		}
		
		/* mod_expires */
		if (!$permission) $result['mod_expires'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_expires') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_expires'] = $mod_result;
		}
		
		/* mod_headers */
		if (!$permission) $result['mod_headers'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_headers') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_headers'] = $mod_result;
		}
		
		/* mod_mime */
		if (!$permission) $result['mod_mime'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_mime') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_mime'] = $mod_result;
		}
		
		/* mod_rewrite */
		if (!$permission) $result['mod_rewrite'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_rewrite') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_rewrite'] = $mod_result;
		}
		
		/* mod_setenvif */
		if (!$permission) $result['mod_setenvif'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_setenvif') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_setenvif'] = $mod_result;
		}
		
		/* path_system_nitro_cache */
		if (!$permission) $result['path_system_nitro_cache'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/cache') && is_writable(DIR_SYSTEM . 'nitro/cache')) $result['path_system_nitro_cache'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/cache')) $result['path_system_nitro_cache'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_cache'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_assets */
		if (!$permission) $result['path_assets'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . '../assets') && is_writable(DIR_SYSTEM . 'nitro/cache')) $result['path_assets'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . '../assets')) $result['path_assets'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_assets'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_system_nitro_data */
		if (!$permission) $result['path_system_nitro_data'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/data') && is_writable(DIR_SYSTEM . 'nitro/data')) $result['path_system_nitro_data'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/data')) $result['path_system_nitro_data'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_data'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_system_nitro_data_googlepagespeed-desktop */
		if (!$permission) $result['path_system_nitro_data_googlepagespeed-desktop'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/data/googlepagespeed-desktop.tpl') && is_writable(DIR_SYSTEM . 'nitro/data/googlepagespeed-desktop.tpl')) $result['path_system_nitro_data_googlepagespeed-desktop'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/data/googlepagespeed-desktop.tpl')) $result['path_system_nitro_data_googlepagespeed-desktop'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_data_googlepagespeed-desktop'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_system_nitro_data_googlepagespeed-mobile */
		if (!$permission) $result['path_system_nitro_data_googlepagespeed-mobile'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/data/googlepagespeed-mobile.tpl') && is_writable(DIR_SYSTEM . 'nitro/data/googlepagespeed-mobile.tpl')) $result['path_system_nitro_data_googlepagespeed-mobile'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/data/googlepagespeed-mobile.tpl')) $result['path_system_nitro_data_googlepagespeed-mobile'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_data_googlepagespeed-mobile'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_system_nitro_data_persistence */
		if (!$permission) $result['path_system_nitro_data_persistence'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/data/persistence.tpl') && is_writable(DIR_SYSTEM . 'nitro/data/persistence.tpl')) $result['path_system_nitro_data_persistence'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/data/persistence.tpl')) $result['path_system_nitro_data_persistence'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_data_persistence'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_system_nitro_data_amazon_persistence */
		if (!$permission) $result['path_system_nitro_data_amazon_persistence'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/data/amazon_persistence.tpl') && is_writable(DIR_SYSTEM . 'nitro/data/amazon_persistence.tpl')) $result['path_system_nitro_data_amazon_persistence'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/data/amazon_persistence.tpl')) $result['path_system_nitro_data_amazon_persistence'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_data_amazon_persistence'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_system_nitro_data_ftp_persistence */
		if (!$permission) $result['path_system_nitro_data_ftp_persistence'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/data/ftp_persistence.tpl') && is_writable(DIR_SYSTEM . 'nitro/data/ftp_persistence.tpl')) $result['path_system_nitro_data_ftp_persistence'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/data/ftp_persistence.tpl')) $result['path_system_nitro_data_ftp_persistence'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_data_ftp_persistence'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		return $result;

	}
	
	public function configureCron($config) {
		$this->loadCore();

		if ( !isExecEnabled()) {
			return '<strong>exec()</strong> is not available on your PHP server. Please contact your web hosting provider to enable it, or use a third-party CRON service with the URL below.';
		}

		$output = array();
		exec('which crontab', $output);

		if (empty($output)) {
			return '<strong>crontab</strong> command is not allowed for your Linux account. Please contact your web hosting provider to enable it, or use a third-party CRON service with the URL below.';
		}

		$output = array();
		exec('crontab -l', $output);

		if (!function_exists('cron_check')) {
            function cron_check($var) {
                return strpos($var, 'system/nitro/core/cron.php') === FALSE;
            }
        }

        $cron_settings = array_filter($output, 'cron_check');

        if (!empty($config['Local']['Status']) && $config['Local']['Status'] == 'yes' && !empty($config['Local']['Weekday'])) {
            $cron_settings[] = $this->cron_command($config);
        }

		$file = tempnam(sys_get_temp_dir(), 'nitro_') . '.txt';
		file_put_contents($file, implode(PHP_EOL, $cron_settings) . PHP_EOL);

		chmod($file, NITRO_FOLDER_PERMISSIONS);
		exec('crontab ' . $file);
		unlink($file);

		return null;
	}

    public function cron_command($config, $full_command = true) {
        if (empty($config)) return '';

        if (!function_exists('convert_days')) {
            function convert_days(&$item) {
                $item = $item%7;
            }
        }
        
        if (empty($config['Local']['Weekday'])) {
            $config['Local']['Weekday'] = array('*');
        } else {
            array_walk($config['Local']['Weekday'], 'convert_days');
        }

        $cron_command = array(
            'minute' => $config['Local']['Minute'],
            'hour' => $config['Local']['Hour'],
            'day' => '*',
            'month' => '*',
            'weekday' => implode(',', $config['Local']['Weekday']),
            'command' => 'php '. NITRO_CORE_FOLDER . 'cron.php >/dev/null 2>&1'
        );

        if ($full_command) {
            return implode(' ', $cron_command);
        } else {
            return $cron_command['command'];
        }
    }

	public function clearProductCache($product_id) {
		$this->loadCore();

		if (!getNitroPersistence('PageCache.ClearCacheOnProductEdit')) return;

		initNitroProductCacheDb();

		$cachefile_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "nitro_product_cache WHERE product_id='" . $product_id . "'");

		foreach ($cachefile_query->rows as $cachefile) {
			$file = $cachefile['cachefile'];

			if (file_exists($file) && is_writable($file)) {
				unlink($file);
			}

			$filegz = $file . '.gz';

			if (file_exists($filegz) && is_writable($filegz)) {
				unlink($filegz);
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "nitro_product_cache WHERE product_id='" . (int)$product_id . "'");

        $this->clearDBCache();
	}

	private function delete_folder($folder) {
		if (in_array($folder, array('.', '..'))) return;
		
		if (file_exists($folder)) {
			if (is_writeable($folder)) {
				if (is_dir($folder)) {
					$folder = rtrim($folder, DS);
					
					$files = scandir($folder);
					foreach ($files as $file) {
						if (in_array($file, array('.', '..'))) continue;
						$this->delete_folder($folder . DS . $file);
					}
					
					if (!rmdir($folder)) throw new Exception('Delete not successful. The path ' . $folder . ' could not get deleted.');
				} else {
					if (!unlink($folder)) throw new Exception('Delete not successful. The path ' . $folder . ' could not get deleted.');
				}
			} else throw new Exception('Delete not successful. The path ' . $folder . ' is not writable.');
		}
	}
	
	private function trunc_folder($folder, $touch = false) {
		$this->loadCore();
		cleanFolder($folder, $touch);
	}
	
	private function exec_enabled() {
		$this->loadCore();
		return isExecEnabled();
	}
	
	private function isSessionClosed() {
		return $this->session_closed;
	}
	
	private function closeSession() {
		if (session_id() && !$this->session_closed) session_write_close();
		$this->session_closed = true;
	}
	
	private function openSession() {
		if ($this->session_closed) {
			if (!session_id()) session_start();
		}
		$this->session_closed = false;
		return session_id();
	}
	
	private function smushCanContinue() {
		return true;
		$this->openSession();
		$stop_smushing = $_SESSION['stop_smushing'];
		$this->closeSession();
		return !$stop_smushing;
	}
}
?>