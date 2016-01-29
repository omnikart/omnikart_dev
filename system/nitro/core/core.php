<?php
require_once (DIR_SYSTEM . 'nitro/config.php');
require_once NITRO_CORE_FOLDER . 'nitro_db.php';

if (! function_exists ( 'np' )) {
	function np($var, $exit = false, $file = false) {
		if ($file) {
			file_put_contents ( NITRO_NP_FILE, var_export ( $var, true ) . PHP_EOL . PHP_EOL, $file );
		} else {
			echo '<pre>';
			var_dump ( $var );
			echo '</pre>';
		}
		
		if ($exit)
			exit ();
	}
}
function explodeTrim($delimiter, $string) {
	return ! empty ( $string ) ? array_filter ( array_map ( 'trim', explode ( $delimiter, $string ) ) ) : array ();
}
function getSpecialHeaders() {
	$important_headers = array ( // if the key is present and the value is not, then the headers will be saved
			'content-type' => 'html' 
	);
	$headers = headers_list ();
	if (! empty ( $headers )) {
		foreach ( $headers as $header ) {
			foreach ( $important_headers as $h => $v ) {
				if (strpos ( strtolower ( $header ), $h ) !== false && strpos ( strtolower ( $header ), $v ) === false) {
					return implode ( "\n", $headers );
				}
			}
		}
	}
	return '';
}
function getIgnoredUrls() {
	$ignoredUrls = explodeTrim ( "\n", getNitroPersistence ( 'DisabledURLs' ) );
	
	$predefinedIgnoredUrls = array (
			'/admin/',
			'isearch' 
	);
	// See if we are in admin
	$dir = basename ( DIR_APPLICATION );
	
	if (! in_array ( $dir, array (
			'admin',
			'catalog' 
	) )) {
		$predefinedIgnoredUrls [] = '/' . $dir . '/';
	}
	
	$ignoredUrls = array_merge ( $predefinedIgnoredUrls, $ignoredUrls );
	
	return $ignoredUrls;
}
function nitroEnableSession() {
	if (! session_id ()) {
		session_start ();
	}
}
function isAdminLogged() {
	nitroEnableSession ();
	
	return ! empty ( $_SESSION ['user_id'] );
}
function getFullURL() {
	$host = (! empty ( $_SERVER ['HTTP_HOST'] )) ? $_SERVER ['HTTP_HOST'] : '';
	$request_uri = (! empty ( $_SERVER ['REQUEST_URI'] )) ? $_SERVER ['REQUEST_URI'] : '';
	return $host . $request_uri;
}
function areWeInIgnoredUrl() {
	if (basename ( DIR_APPLICATION ) != 'catalog')
		return true;
	
	$url = getFullURL ();
	
	$ignoredUrls = getIgnoredUrls ();
	
	foreach ( $ignoredUrls as $ignoredUrl ) {
		if ($ignoredUrl [0] != '!') {
			if (preg_match ( '~' . str_replace ( array (
					'~',
					'#asterisk#' 
			), array (
					'\~',
					'.*' 
			), preg_quote ( str_replace ( '*', '#asterisk#', $ignoredUrl ) ) ) . '~', $url )) {
				return true;
			}
		} else {
			if (! preg_match ( '~' . str_replace ( array (
					'~',
					'#asterisk#' 
			), array (
					'\~',
					'.*' 
			), preg_quote ( str_replace ( '*', '#asterisk#', substr ( $ignoredUrl, 1 ) ) ) ) . '~', $url )) {
				return true;
			}
		}
	}
	
	return false;
}
function initNitroProductCacheDb() {
	$db = NitroDb::getInstance ();
	
	$db->query ( "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "nitro_product_cache` ( `product_id` int(11) NOT NULL, `cachefile` text NOT NULL, KEY `product_id` (`product_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8" );
}
function setNitroProductCache($product_id, $cachefile) {
	initNitroProductCacheDb ();
	
	$db = NitroDb::getInstance ();
	
	$db->query ( "INSERT INTO `" . DB_PREFIX . "nitro_product_cache` SET product_id='" . ( int ) $product_id . "', cachefile = '" . $cachefile . "'" );
}
function getOpenCartSetting($key, $store_id = 0) {
	$db = NitroDb::getInstance ();
	
	nitroEnableSession ();
	
	$store_id = $store_id == 0 && ! empty ( $GLOBALS ['nitro.store_id'] ) ? ( int ) $GLOBALS ['nitro.store_id'] : $store_id;
	
	$query = $db->query ( "SELECT value FROM " . DB_PREFIX . "setting WHERE `key`='" . $key . "' AND store_id='" . $store_id . "' LIMIT 1" );
	if ($query->num_rows) {
		$result = $query->row;
		if (! empty ( $result ['value'] ))
			return $result ['value'];
	}
	return null;
}
function inMaintenanceMode() {
	return getOpenCartSetting ( 'config_maintenance' ) == '1';
}
function isNitroEnabled() {
	return getNitroPersistence ( 'Enabled' ) && ! areWeInIgnoredUrl () && ! inMaintenanceMode ();
}
function mobileCheck() {
	$categorizr = DIR_SYSTEM . 'library/categorizr.php';
	$device = DIR_SYSTEM . 'library/device.php';
	
	if (file_exists ( $categorizr )) {
		require_once ($categorizr);
		
		if (isMobile () || isTablet ()) {
			return true;
		}
	} elseif (file_exists ( $device )) {
		
		if (! function_exists ( 'deviceIsMobile' )) {
			function deviceIsMobile() {
				$mobile = false;
				
				if (isset ( $_SERVER ['HTTP_USER_AGENT'] )) {
					
					$mobile_agents = array (
							'iPod',
							'iPhone',
							'webOS',
							'BlackBerry',
							'windows phone',
							'symbian',
							'vodafone',
							'opera mini',
							'windows ce',
							'smartphone',
							'palm',
							'midp' 
					);
					
					foreach ( $mobile_agents as $mobile_agent ) {
						if (stripos ( $_SERVER ['HTTP_USER_AGENT'], $mobile_agent )) {
							$mobile = true;
						}
					}
					if (stripos ( $_SERVER ['HTTP_USER_AGENT'], "Android" ) && stripos ( $_SERVER ['HTTP_USER_AGENT'], "mobile" )) {
						$mobile = true;
					}
				}
				return $mobile;
			}
		}
		
		if (! function_exists ( 'deviceIsTablet' )) {
			function deviceIsTablet() {
				$tablet = false;
				
				if (isset ( $_SERVER ['HTTP_USER_AGENT'] )) {
					
					$tablet_agents = array (
							'iPad',
							'RIM Tablet',
							'hp-tablet',
							'Kindle Fire',
							'Android' 
					);
					
					foreach ( $tablet_agents as $tablet_agent ) {
						if (stripos ( $_SERVER ['HTTP_USER_AGENT'], $tablet_agent )) {
							$tablet = true;
						}
					}
					
					if (stripos ( $_SERVER ['HTTP_USER_AGENT'], "Android" ) && stripos ( $_SERVER ['HTTP_USER_AGENT'], "mobile" )) {
						$tablet = false;
					}
				}
				return $tablet;
			}
		}
		
		if (deviceIsMobile () || deviceIsTablet ()) {
			return true;
		}
	} else {
		return isset ( $_COOKIE ['is_mobile'] ) && ( int ) $_COOKIE ['is_mobile'] == 1;
	}
	
	return false;
}
function refreshNitroPersistenceGlobal() {
	$data = file_get_contents ( NITRO_PERSISTENCE );
	$data = base64_decode ( $data );
	$returnData = json_decode ( $data, true );
	$GLOBALS ['nitro.persistence'] = $returnData;
	return $returnData;
}
function getNitroPersistence($key = '') {
	if (file_exists ( NITRO_PERSISTENCE ) && empty ( $GLOBALS ['nitro.persistence'] )) {
		$returnData = refreshNitroPersistenceGlobal ();
	} else {
		if (! empty ( $GLOBALS ['nitro.persistence'] )) {
			$returnData = $GLOBALS ['nitro.persistence'];
		} else {
			$returnData = false;
		}
	}
	
	if (! empty ( $key )) {
		$subkeys = explode ( '.', $key );
		array_unshift ( $subkeys, 'Nitro' );
		
		while ( ! empty ( $subkeys ) ) {
			$subkey = array_shift ( $subkeys );
			
			if (! empty ( $returnData [$subkey] )) {
				$returnData = $returnData [$subkey];
				
				if (is_string ( $returnData )) {
					$returnData = trim ( $returnData );
				}
			} else {
				$returnData = false;
				break;
			}
		}
		
		switch ($returnData) {
			case 'yes' :
				$returnData = true;
				break;
			case 'no' :
				$returnData = false;
				break;
		}
		
		$result = $returnData;
	} else {
		
		$result = ! empty ( $returnData ) ? $returnData : false;
	}
	
	return $result;
}
function nitroCheckFolder($folder) {
	if (! is_dir ( $folder )) {
		mkdir ( $folder, NITRO_FOLDER_PERMISSIONS );
	}
}
function setNitroPersistence($data) {
	nitroCheckFolder ( NITRO_FOLDER . 'data' );
	
	file_put_contents ( NITRO_PERSISTENCE, base64_encode ( json_encode ( $data ) ) );
	
	refreshNitroPersistenceGlobal ();
	
	return true;
}
function getNitroSmushitPersistence() {
	$file = NITRO_SMUSHIT_PERSISTENCE;
	
	$data = array (
			'smushed_images_count' => 0,
			'already_smushed_images_count' => 0,
			'total_images' => false,
			'kb_saved' => 0,
			'last_smush_timestamp' => 0 
	);
	
	if (file_exists ( $file )) {
		$data = json_decode ( file_get_contents ( $file ), true );
	} else {
		file_put_contents ( $file, json_encode ( $data ) );
	}
	
	return $data;
}
function setNitroSmushitPersistence($data) {
	if (is_array ( $data )) {
		$file = NITRO_SMUSHIT_PERSISTENCE;
		$old_data = getNitroSmushitPersistence ();
		$new_data = array_merge ( $old_data, $data );
		file_put_contents ( $file, json_encode ( $new_data ) );
		return true;
	}
	
	return false;
}
function setGooglePageSpeedReport($data, $strategy) {
	nitroCheckFolder ( NITRO_FOLDER . 'data' );
	
	file_put_contents ( NITRO_FOLDER . 'data' . DS . 'googlepagespeed-' . $strategy . '.tpl', base64_encode ( $data ) );
	
	return true;
}
function refreshGooglePageSpeedReport($strategies = array('mobile', 'desktop')) {
	foreach ( $strategies as $strategy ) {
		if (file_exists ( NITRO_FOLDER . 'data/googlepagespeed-' . $strategy . '.tpl' )) {
			if (! unlink ( NITRO_FOLDER . 'data/googlepagespeed-' . $strategy . '.tpl' )) {
				return 'There was a permission issue - please make sure the file system/nitro/data/googlepagespeed-' . $strategy . '.tpl has at least 644 permissions!';
			}
		}
	}
	
	return 'Google Page Speed Report was refreshed!';
}
function getGooglePageSpeedReport($setting = null, $strategies = array('mobile', 'desktop')) {
	foreach ( $strategies as $strategy ) {
		if (! file_exists ( NITRO_FOLDER . 'data/googlepagespeed-' . $strategy . '.tpl' )) {
			// Fetch the report and save it
			$catalogURL = (defined ( 'HTTP_SERVER' ) && HTTP_SERVER != '') ? dirname ( HTTP_SERVER ) . '/' : false;
			if (! $catalogURL)
				return false;
			
			$persistence = getNitroPersistence ();
			$persistence = $persistence ['Nitro'];
			
			$key = ! empty ( $persistence ['GooglePageSpeedApiKey'] ) ? $persistence ['GooglePageSpeedApiKey'] : 'AIzaSyCxptR6CbHYrHkFfsO_XN3nkf6FjoQp2Mg';
			
			$url = "https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url=" . $catalogURL . "&key=" . $key . "&strategy=" . $strategy;
			
			$output = fetchRemoteContent ( $url );
			
			if (! empty ( $output )) {
				setGooglePageSpeedReport ( $output, $strategy );
			}
		}
	}
	
	$returnData = false;
	
	foreach ( $strategies as $strategy ) {
		
		if (file_exists ( NITRO_FOLDER . 'data/googlepagespeed-' . $strategy . '.tpl' )) {
			if (! is_array ( $returnData )) {
				$returnData = array ();
			}
			
			$returnData [$strategy] = base64_decode ( file_get_contents ( NITRO_FOLDER . 'data' . DS . 'googlepagespeed-' . $strategy . '.tpl' ) );
		}
	}
	
	return $returnData;
}
function fetchRemoteContent($url) {
	if (strpos ( $url, '//' ) === 0) {
		if (isset ( $_SERVER ['HTTPS'] ) && (($_SERVER ['HTTPS'] == 'on') || ($_SERVER ['HTTPS'] == '1'))) {
			$url = 'https:' . $url;
		} else {
			$url = 'http:' . $url;
		}
	}
	
	if (ini_get ( 'allow_url_fopen' )) {
		if (! function_exists ( 'nitro_error_handler' )) {
			function nitro_error_handler($errno, $errstr, $errfile, $errline) {
				return true;
			}
		}
		set_error_handler ( 'nitro_error_handler' );
		try {
			$content = file_get_contents ( $url );
		} catch ( Exception $e ) {
		}
		restore_error_handler ();
		
		if (! $content) {
			return '';
		}
		return $content;
	} else {
		$ch = curl_init ( $url );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_ENCODING, "" );
		$content = curl_exec ( $ch );
		curl_close ( $ch );
		return $content;
	}
	return false;
}
function getPageCacheTime() {
	$pagecache_time = getNitroPersistence ( 'PageCache.ExpireTime' );
	
	return ! empty ( $pagecache_time ) && is_numeric ( $pagecache_time ) ? ( int ) $pagecache_time : NITRO_PAGECACHE_TIME;
}
function minifyHTML($html) {
	require_once NITRO_FOLDER . 'lib' . DS . 'minifier' . DS . 'HTMLMin.php';
	
	$htmlMinifier = new Nitro_Minify_HTML ( $html, array (
			'jsCleanComments' => false,
			'keepHTMLComments' => getNitroPersistence ( 'Mini.HTMLComments' ) 
	) );
	
	$html = $htmlMinifier->process ();
	
	return $html;
}
function getSSLCachePrefix() {
	return isset ( $_SERVER ['HTTPS'] ) && (($_SERVER ['HTTPS'] == 'on') || ($_SERVER ['HTTPS'] == '1')) ? '1-' : '0-';
}
function isExecEnabled() {
	$command = function_exists ( 'exec' ) && ! in_array ( 'exec', array_map ( 'trim', explode ( ', ', ini_get ( 'disable_functions' ) ) ) ) && ! (strtolower ( ini_get ( 'safe_mode' ) ) != 'off' && ini_get ( 'safe_mode' ) != 0);
	
	if ($command) {
		$result = array ();
		exec ( 'whoami', $result );
		return ! empty ( $result );
	}
	
	return false;
}
function isCli() {
	return ! empty ( $_SERVER ['argc'] );
}
function sendNitroMail($to, $subject, $message) {
	require_once realpath ( DIR_SYSTEM . 'library/mail.php' );
	
	$mail = new Mail ();
	$mail->protocol = getOpenCartSetting ( 'config_mail_protocol' );
	$mail->parameter = getOpenCartSetting ( 'config_mail_parameter' );
	$mail->hostname = getOpenCartSetting ( 'config_smtp_host' );
	$mail->username = getOpenCartSetting ( 'config_smtp_username' );
	$mail->password = getOpenCartSetting ( 'config_smtp_password' );
	$mail->port = getOpenCartSetting ( 'config_smtp_port' );
	$mail->timeout = getOpenCartSetting ( 'config_smtp_timeout' );
	$mail->setTo ( $to );
	$mail->setFrom ( getOpenCartSetting ( 'config_email' ) );
	$mail->setSender ( getOpenCartSetting ( 'config_name' ) );
	$mail->setSubject ( html_entity_decode ( $subject, ENT_QUOTES, 'UTF-8' ) );
	$mail->setText ( html_entity_decode ( $message, ENT_QUOTES, 'UTF-8' ) );
	$mail->send ();
}
function cleanNitroCacheFolders($touch = false, $time = false) {
	cleanFolder ( NITRO_PAGECACHE_FOLDER, $touch, $time );
	cleanFolder ( NITRO_DBCACHE_FOLDER, $touch, $time );
	cleanFolder ( NITRO_FOLDER . 'temp' . DS, $touch, $time );
	cleanFolder ( dirname ( DIR_APPLICATION ) . DS . 'assets' . DS . 'css' . DS, $touch, $time );
	cleanFolder ( dirname ( DIR_APPLICATION ) . DS . 'assets' . DS . 'js' . DS, $touch, $time );
	
	if (class_exists ( 'Journal2Cache' )) {
		Journal2Cache::deleteCache ();
	}
	
	$db = NitroDb::getInstance ();
	$db->query ( "TRUNCATE TABLE " . DB_PREFIX . "nitro_product_cache" );
}
function folderEmpty($dir, $time) {
	require_once NITRO_LIB_FOLDER . 'NitroFiles.php';
	
	$config = array (
			'root' => realpath ( $dir ) . DS,
			'start' => '',
			'batch' => 0 
	);
	
	if (! empty ( $time )) {
		$config ['rules'] [] = array (
				'delete_time' => $time 
		);
	}
	
	$files = new NitroFiles ( $config );
	
	return $files->isEmpty ();
}
function cleanNitroFiles($dir, $time) {
	require_once NITRO_LIB_FOLDER . 'NitroFiles.php';
	
	$config = array (
			'root' => realpath ( $dir ) . DS,
			'batch' => 0 
	);
	
	if (! empty ( $time )) {
		$config ['rules'] [] = array (
				'delete_time' => $time 
		);
	}
	
	$files = new NitroFiles ( $config );
	
	$files->delete ();
}
function cleanFolder($dir, $touch = false, $time = false) {
	if (! is_dir ( $dir ))
		return;
	
	if (isExecEnabled ()) {
		exec ( 'find ' . $dir . ' -type f -delete', $output );
	}
	
	cleanNitroFiles ( $dir, $time );
	
	if (is_string ( $touch )) {
		touch ( realpath ( $dir ) . DS . $touch );
	}
}
function loadNitroLib($lib) {
	$target = NITRO_LIB_FOLDER . preg_replace ( '/\.php$/', '', $lib ) . '.php';
	if (file_exists ( $target )) {
		require_once $target;
	}
}
function getQuickCacheRefreshFilename() {
	return NITRO_PAGECACHE_FOLDER . 'clearcache';
}