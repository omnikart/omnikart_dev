<?php
function nitro_get_file_type($path) {
	$ext = pathinfo ( $path, PATHINFO_EXTENSION );
	
	if (in_array ( strtolower ( $ext ), unserialize ( NITRO_EXTENSIONS_IMG ) ))
		return 'image';
	if (in_array ( strtolower ( $ext ), unserialize ( NITRO_EXTENSIONS_JS ) ))
		return 'js';
	if (in_array ( strtolower ( $ext ), unserialize ( NITRO_EXTENSIONS_CSS ) ))
		return 'css';
	
	return false;
}
function nitro_clean_path($path, $prefix = '/', $suffix = '/') {
	$parts = str_split ( $path );
	if (! empty ( $prefix ) && $parts [0] != $prefix)
		array_unshift ( $parts, $prefix );
	if (! empty ( $suffix ) && $parts [count ( $parts ) - 1] != $suffix)
		array_push ( $parts, $suffix );
	
	return implode ( $parts );
}
function nitro_resolve_cdn($path, $real_url) {
	if (! empty ( $real_url )) {
		$real_url = nitro_clean_path ( rtrim ( $real_url, '/' ), null, '/' );
	}
	
	if (getNitroPersistence ( 'CDNStandard.GenericURL' )) {
		$real_url = nitro_clean_path ( rtrim ( getNitroPersistence ( 'CDNStandard.GenericURL' ), '/' ), null, '/' );
	} else if (empty ( $real_url )) {
		$real_url = '';
	}
	
	if (stripos ( $path, 'http' ) === 0 || stripos ( $path, '//' ) === 0) {
		return $path;
	}
	
	$path = ltrim ( $path, '/' );
	
	if (areWeInIgnoredUrl () && getNitroPersistence ( 'PageCache.Enabled' )) {
		return $real_url . $path;
	}
	
	$type = nitro_get_file_type ( $path );
	$cdn_persistence = '';
	
	$cdn_http = '';
	$cdn_https = '';
	
	if (getNitroPersistence ( 'CDNStandard.Enabled' )) {
		$cdn_persistence = '3';
		switch ($type) {
			case 'image' :
				{
					if (getNitroPersistence ( 'CDNStandard.ServeImages' )) {
						$cdn_http = getNitroPersistence ( 'CDNStandard.ImagesHttpUrl' );
						$cdn_https = getNitroPersistence ( 'CDNStandard.ImagesHttpsUrl' );
					}
				}
				break;
			case 'js' :
				{
					if (getNitroPersistence ( 'CDNStandard.ServeJavaScript' )) {
						$cdn_http = getNitroPersistence ( 'CDNStandard.JavaScriptHttpUrl' );
						$cdn_https = getNitroPersistence ( 'CDNStandard.JavaScriptHttpsUrl' );
					}
				}
				break;
			case 'css' :
				{
					if (getNitroPersistence ( 'CDNStandard.ServeCSS' )) {
						$cdn_http = getNitroPersistence ( 'CDNStandard.CSSHttpUrl' );
						$cdn_https = getNitroPersistence ( 'CDNStandard.CSSHttpsUrl' );
					}
				}
				break;
		}
	} elseif (getNitroPersistence ( 'CDNAmazon.Enabled' )) {
		$cdn_persistence = '1';
		switch ($type) {
			case 'image' :
				{
					if (getNitroPersistence ( 'CDNAmazon.ServeImages' )) {
						$cdn_http = getNitroPersistence ( 'CDNAmazon.ImageHttpUrl' );
						$cdn_https = getNitroPersistence ( 'CDNAmazon.ImageHttpsUrl' );
					}
				}
				break;
			case 'js' :
				{
					if (getNitroPersistence ( 'CDNAmazon.ServeJavaScript' )) {
						$cdn_http = getNitroPersistence ( 'CDNAmazon.JavaScriptHttpUrl' );
						$cdn_https = getNitroPersistence ( 'CDNAmazon.JavaScriptHttpsUrl' );
					}
				}
				break;
			case 'css' :
				{
					if (getNitroPersistence ( 'CDNAmazon.ServeCSS' )) {
						$cdn_http = getNitroPersistence ( 'CDNAmazon.CSSHttpUrl' );
						$cdn_https = getNitroPersistence ( 'CDNAmazon.CSSHttpsUrl' );
					}
				}
				break;
		}
	} elseif (getNitroPersistence ( 'CDNRackspace.Enabled' )) {
		$cdn_persistence = '2';
		switch ($type) {
			case 'image' :
				{
					if (getNitroPersistence ( 'CDNRackspace.ServeImages' )) {
						$cdn_http = getNitroPersistence ( 'CDNRackspace.ImagesHttpUrl' );
						$cdn_https = getNitroPersistence ( 'CDNRackspace.ImagesHttpsUrl' );
					}
				}
				break;
			case 'js' :
				{
					if (getNitroPersistence ( 'CDNRackspace.ServeJavaScript' )) {
						$cdn_http = getNitroPersistence ( 'CDNRackspace.JavaScriptHttpUrl' );
						$cdn_https = getNitroPersistence ( 'CDNRackspace.JavaScriptHttpsUrl' );
					}
				}
				break;
			case 'css' :
				{
					if (getNitroPersistence ( 'CDNRackspace.ServeCSS' )) {
						$cdn_http = getNitroPersistence ( 'CDNRackspace.CSSHttpUrl' );
						$cdn_https = getNitroPersistence ( 'CDNRackspace.CSSHttpsUrl' );
					}
				}
				break;
		}
	}
	
	if (getSSLCachePrefix () == '-1' && ! empty ( $cdn_https )) { // We have SSL
		$cdn_url = $cdn_https;
	} else {
		$cdn_url = $cdn_http;
	}
	
	if (empty ( $cdn_persistence ) || empty ( $cdn_url )) {
		return $real_url . $path;
	}
	
	$cdn_url = nitro_clean_path ( rtrim ( $cdn_url, '/' ), null, '/' );
	
	global $db;
	$exists = $db->query ( "SELECT * FROM " . DB_PREFIX . "nitro_cdn_files WHERE file='" . $db->escape ( $path ) . "' AND uploaded=1 AND cdn=" . $cdn_persistence . " LIMIT 0,1" );
	
	if ($exists->num_rows) {
		return $cdn_url . $path;
	} else {
		return $real_url . $path;
	}
}
function nitroCDNResolve($data, $real_url = '') {
	if (is_string ( $data )) { // This is an image
		$data = nitro_resolve_cdn ( $data, $real_url );
	} else if (is_array ( $data )) {
		foreach ( $data as $i => $v ) {
			if (is_string ( $v )) { // This is a JavaScript file
				$data [$i] = nitro_resolve_cdn ( $v, $real_url );
			} else if (is_array ( $v ) && ! empty ( $v ['href'] )) { // This is a CSS file
				$data [$i] ['href'] = nitro_resolve_cdn ( $v ['href'], $real_url );
			}
		}
	}
	
	return $data;
}
function clearFTPPersistence() {
	$file = NITRO_FTP_PERSISTENCE;
	file_put_contents ( $file, '' );
	unset ( $_SESSION ['nitro_ftp_persistence'] );
}
function setFTPPersistence($value) {
	$file = NITRO_FTP_PERSISTENCE;
	$data = getFTPPersistence ();
	if (! in_array ( $value, $data )) {
		$data [] = $value;
		file_put_contents ( $file, serialize ( $data ) );
	}
}
function getFTPPersistence() {
	$file = NITRO_FTP_PERSISTENCE;
	
	$data = array ();
	if (file_exists ( $file )) {
		$data = unserialize ( file_get_contents ( $file ) );
	}
	return empty ( $data ) ? array () : $data;
}
function clearRackspacePersistence() {
	$file = NITRO_RACKSPACE_PERSISTENCE;
	file_put_contents ( $file, '' );
}
function setRackspacePersistence($value) {
	$file = NITRO_RACKSPACE_PERSISTENCE;
	$data = getRackspacePersistence ();
	if (! in_array ( $value, $data )) {
		$data [] = $value;
		file_put_contents ( $file, serialize ( $data ) );
	}
}
function getRackspacePersistence() {
	$file = NITRO_RACKSPACE_PERSISTENCE;
	
	$data = array ();
	if (file_exists ( $file )) {
		$data = unserialize ( file_get_contents ( $file ) );
	}
	return empty ( $data ) ? array () : $data;
}