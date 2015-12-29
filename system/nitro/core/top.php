<?php 

function getIgnoredRoutes() {
	$ignoredRoutes = explodeTrim("\n", getNitroPersistence('PageCache.IgnoredRoutes'));
	
	$predefinedIgnoredRoutes = array(
		'checkout/cart', 
		'checkout/checkout',
		'checkout/success',
		'account/register',
		'account/login',
		'account/edit',
		'account/account',
		'account/password',
		'account/address',
		'account/address/update',
		'account/address/delete',
		'account/wishlist',
		'account/order',
		'account/download',
		'account/return',
		'account/return/insert',
		'account/reward',
		'account/voucher',
		'account/transaction',
		'account/newsletter',
		'account/logout',
		'affiliate/login',
		'affiliate/register',
		'affiliate/account',
		'affiliate/edit',
		'affiliate/password',
		'affiliate/payment',
		'affiliate/tracking',
		'affiliate/transaction',
		'affiliate/logout',
		'information/contact',
		'product/compare',
		'error/not_found'
	);
	
	$ignoredRoutes = array_merge($predefinedIgnoredRoutes, $ignoredRoutes);

	return $ignoredRoutes;
}

function isCustomerLogged() {
    nitroEnableSession();

	return !empty($_SESSION['customer_id']);
}

function isItemsInCart() {
    nitroEnableSession();

	$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'Hidden IP';
    $folder = dirname(dirname(dirname(dirname(__FILE__)))).'/vendors/persistentcart/'.$ip.'.txt';
     
    if (file_exists($folder)) {
        return true;
    }
    
    return (!empty($_SESSION['cart']) || !empty($_SESSION['persistent_cart']));
}

function isWishlistAdded() {
    nitroEnableSession();

	return !empty($_SESSION['wishlist']);
}

function isAJAXRequest() { 
	return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function isPOSTRequest() { 
	return !empty($_POST);
}

function generateNameOfCacheFile() {
	if (!empty($GLOBALS['nitro.pagecache.file'])) {
		return $GLOBALS['nitro.pagecache.file'];
	}
	
	nitroEnableSession();

    if (empty($_SESSION['language']) && empty($_SESSION['currency'])) {
        $db = NitroDb::getInstance();
        $db->query("SET NAMES 'utf8'");
        $db->query("SET CHARACTER SET utf8");
        $db->query("SET CHARACTER_SET_CONNECTION=utf8");
        $db->query("SET SQL_MODE = ''");
        // In, when the site is opened for first time
        
        // Store
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`ssl`, 'www.', '') = '" . $db->escape('https://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
        } else {
            $store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`url`, 'www.', '') = '" . $db->escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
        }

        $store_id = 0;

        if ($store_query->num_rows) {
            $result = $store_query->row;
            $store_id = (int)$result['store_id'];
        }
        
        $GLOBALS['nitro.store_id'] = $store_id;
        
        $resource = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE (`key`='config_language' OR `key`='config_currency') AND `store_id` = '" . $store_id . "'");
                
        if ($resource->num_rows) {
            $data = array();
            $config_language = 0;
            $config_currency = 0;
            
            foreach ($resource->rows as $result) {
                if (!empty($result['key']) && $result['key'] == 'config_language') {
                    $config_language = strtolower($result['value']);
                }
                if (!empty($result['key']) && $result['key'] == 'config_currency') {
                    $config_currency = strtolower($result['value']);
                }
            }

            if (isset($_SESSION)) {
                $_SESSION['language'] = $config_language;
                $_SESSION['currency'] = $config_currency;
            }
        }
    }
	
	$filename = getFullURL();

	$filename = str_replace(array('/','?',':',';','=','&amp;','&','.','--','%','~','-amp-'),'-',$filename);

	$default_language = !empty($_COOKIE['language']) ? $_COOKIE['language'] : '0';
	$default_currency = !empty($_COOKIE['currency']) ? $_COOKIE['currency'] : '0';

	$language = strtolower((!empty($_SESSION['language']) && is_string($_SESSION['language'])) ? $_SESSION['language'] : $default_language); 
	$currency = strtolower((!empty($_SESSION['currency']) && is_string($_SESSION['currency'])) ? $_SESSION['currency'] : $default_currency); 
	
	if (NITRO_DEBUG_MODE) {
		$cached_filename = $filename.'-'.$language.'-'.$currency.'.html';
	} else {
		$cached_filename = md5($filename.'-'.$language.'-'.$currency).'.html';
	}
	
	if (mobileCheck()) {
		$cached_filename = 'mobile-' . $cached_filename;
	}
	
	$GLOBALS['nitro.pagecache.file'] = getSSLCachePrefix() . $cached_filename;

	return $GLOBALS['nitro.pagecache.file'];
}

function pageRefresh() {
	echo '<script type="text/javascript">document.location = document.location;</script>'; exit;	
}

function getLoadTime($filename = NULL) {
	$metafile = NITRO_PAGECACHE_FOLDER . 'meta.html';
	$cachefile = !empty($filename) ? $filename : generateNameOfCacheFile();

	if (file_exists($metafile)) {
		$entries = file_get_contents($metafile);

		$entries = explode(' ; ',$entries);
		
		foreach ($entries as $raw_entry) {
			$entry = explode(' : ',$raw_entry);
			if ($entry[0] == $cachefile) {
				return $entry[1];	
			}
		}
		
	} else {
		return 1;	
	}
}

function isPreCacheRequest() {
	if (!function_exists('getallheaders')) { 
    function getallheaders() { 
      $headers = ''; 
      foreach ($_SERVER as $name => $value) { 
        if (substr($name, 0, 5) == 'HTTP_') { 
          $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
        } 
      } 

      return $headers; 
    } 
	}

	$headers = getallheaders();

	return !empty($headers['Nitro-Precache']);
}

function isYMM() {
    nitroEnableSession();
    
    return !empty($_SESSION['ymm']);
}

function passesPageCacheValidation() {
	if (NITRO_IGNORE_AJAX_REQUESTS && isAJAXRequest()) {
		return false;	
	}

	if (NITRO_IGNORE_POST_REQUESTS && isPOSTRequest()) {
		return false;	
	}
	
	if (isItemsInCart() || isCustomerLogged() || isWishlistAdded() || (isAdminLogged() && NITRO_DISABLE_FOR_ADMIN) || isYMM()) {
		return false;	
	}
	
	$ignoredRoutes = getIgnoredRoutes();

	global $registry;

	if (!empty($registry)) {
		$current_route = !empty($registry->get('request')->get['route']) ? $registry->get('request')->get['route'] : NULL;
	}

	if (
		(!empty($_GET['route']) && in_array($_GET['route'], $ignoredRoutes)) || 
		(!empty($current_route) && in_array($current_route, $ignoredRoutes))
	) {
		return false;
	}

	if(areWeInIgnoredUrl()) {
		return false;
	}

	return true;
}

function decideToShowFrontWidget() {
	$store_front_widget = (getNitroPersistence('PageCache.Enabled') && getNitroPersistence('PageCache.StoreFrontWidget'));

	switch ($store_front_widget) {
		case 'showOnlyWhenAdminIsLogged' : return isAdminLogged(); break;
		case 'showAlways': return true; break;
	}

	return false;
}

function serveCacheIfNecessary() {
	nitroEnableSession();
	
	if (passesPageCacheValidation() == false) {
		return false;	
	}
	
	$nitrocache_time = getPageCacheTime();

	$cachefile = NITRO_PAGECACHE_FOLDER . generateNameOfCacheFile();

	if (file_exists($cachefile) && (time() - $nitrocache_time) < filemtime($cachefile)) {
        $cache_filemtime = filemtime($cachefile);

        $quick_refresh_file = getQuickCacheRefreshFilename();
        if (file_exists($quick_refresh_file)) {
            if (filemtime($quick_refresh_file) > $cache_filemtime) {
                return false;
            }
        }

		$before = microtime(true);
		usleep(1);
		header('Content-type: text/html; charset=utf-8');
		
		serveBrowserCacheHeadersIfNecessary($cache_filemtime);
		serveSpecialHeadersIfNecessary($cache_filemtime);
		
		if (loadGzipHeadersIfNecessary()) {
			$cachefile = $cachefile . '.gz';	
		}

		readfile($cachefile);

		$after = microtime(true);

        nitroEnableSession();

		$_SESSION['NitroRenderTime'] = $after - $before;
		$_SESSION['NitroNameOfCacheFile'] = generateNameOfCacheFile();

		exit;
	}
}

function serveBrowserCacheHeadersIfNecessary($filemtime) {
	if (headers_sent()) {
		return;
	}
	
    nitroEnableSession();
    
	if (!empty($_SESSION['NitroSwitchLanguage'])) {
		unset($_SESSION['NitroSwitchLanguage']);
		return;
	}

	header('Nitro-Cache: Enabled');
	
	$userAgent = !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'FOOBAR';
	$isIE = (stripos($userAgent, 'MSIE ') !== false);

	$code304 = false;
	
	if (getNitroPersistence('BrowserCache.Headers.Pages.CacheControl') && !$isIE) {
		header('Cache-Control:public, max-age=31536000');
	}

	if (getNitroPersistence('BrowserCache.Headers.Pages.Expires')) {
		header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + getPageCacheTime()));
		$code304 = true;
	}

	if (getNitroPersistence('BrowserCache.Headers.Pages.LastModified')) {
		header('Last-Modified: '.gmdate('D, d M Y H:i:s \G\M\T', $filemtime));
		$code304 = true;
	}
	
	if ($code304 && !empty($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $filemtime) {
		header('HTTP/1.1 304 Not Modified');

		exit;
	}
}


function serveSpecialHeadersIfNecessary($filemtime) {
	$headers_file = NITRO_HEADERS_FOLDER . generateNameOfCacheFile();

	if (file_exists($headers_file) && filemtime($headers_file) >= $filemtime) {
		$headers = explode("\n", file_get_contents($headers_file));
		foreach ($headers as $header) {
			header($header, true);
		}
	}
}


function minifyHtmlIfNecessary($html) {
	if (getNitroPersistence('Mini.Enabled') && getNitroPersistence('Mini.HTML')) {	
		return minifyHTML($html);
	}

	return $html;
}

function loadGzipHeadersIfNecessary() {
	if (getNitroPersistence('Compress.Enabled') && getNitroPersistence('Compress.HTML')) {
		$headers = array();

		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)) {
			$encoding = 'gzip';
		} 
	
		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false)) {
			$encoding = 'x-gzip';
		}
	
		if (!isset($encoding)) {
			return false;
		}

		if (headers_sent()) {
			return false;
		}
	
		if (connection_status()) { 
			return false;
		}
		
		header('Content-Encoding: ' . $encoding);

		return true;
	}

	return false;
}

function applyCloudFlareFix() {
	if (getNitroPersistence('CDNCloudFlare.Enabled')) {
		if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
		  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		}
	}
}

function open_nitro() {
	if (session_id()) {
		if (isset($_SESSION['nitro_ftp_persistence'])) unset($_SESSION['nitro_ftp_persistence']);
		if (isset($_SESSION['nitro_persistence'])) unset($_SESSION['nitro_persistence']);
	}

	if (isset($_POST['cacheFileToClear']) && count($_POST) == 1) {
		if (file_exists(NITRO_PAGECACHE_FOLDER . $_POST['cacheFileToClear'])) {
			unlink(NITRO_PAGECACHE_FOLDER . $_POST['cacheFileToClear']);
		}

		pageRefresh();
	}

	if (isNitroEnabled()) {
		applyCloudFlareFix();
		serveCacheIfNecessary();
	}

	$GLOBALS['nitro.start.time'] = microtime(true);

	ob_start(); // Start the output buffer
}

open_nitro();
?>