<?php
function nitro_db_cache_error_handler($errno, $errstr, $errfile, $errline) {
	return true;
}
function getNitroDBCache($cache) {
	set_error_handler ( 'nitro_db_cache_error_handler' );
	
	$result = false;
	$file = NITRO_DBCACHE_FOLDER . $cache . '.nitro';
	$nitroPersistence = getNitroPersistence ();
	$nitroPersistence = $nitroPersistence ['Nitro'];
	$expire = ! empty ( $nitroPersistence ['DBCache'] ['ExpireTime'] ) ? ( int ) $nitroPersistence ['DBCache'] ['ExpireTime'] : 0;
	
	if (! empty ( $nitroPersistence ['DBCache'] ['CacheDepo'] )) {
		if ($nitroPersistence ['DBCache'] ['CacheDepo'] == 'hdd') {
			if ($expire > 0 && file_exists ( $file ) && is_readable ( $file ) && time () - $expire < filemtime ( $file )) {
				$result = unserialize ( file_get_contents ( $file ) );
			} else {
				clearstatcache ( true );
				if (file_exists ( $file ) && is_writeable ( $file ))
					unlink ( $file );
			}
		} else {
			$result = unserialize ( getRamCache ( $file, $nitroPersistence ['DBCache'] ['CacheDepo'] ) );
		}
	}
	
	restore_error_handler ();
	
	return $result;
}
function getMemcacheHost() {
	$server = getNitroPersistence ( 'DBCache.MemcacheHost' );
	
	return empty ( $server ) ? 'localhost' : $server;
}
function getMemcachePort() {
	$server = getNitroPersistence ( 'DBCache.MemcachePort' );
	
	return empty ( $server ) ? '11211' : $server;
}
function getRamCache($key, $depo) {
	$data = null;
	if ($depo == 'ram_eaccelerator') {
		$data = eaccelerator_get ( $key );
	}
	if ($depo == 'ram_xcache') {
		$data = xcache_get ( $key );
	}
	if ($depo == 'ram_memcache') {
		$memcache_obj = new Memcache ();
		if ($memcache_obj->connect ( getMemcacheHost (), getMemcachePort () )) {
			$data = $memcache_obj->get ( $key );
			$memcache_obj->close ();
		}
	}
	
	return $data;
}
function setRamCache($key, $data, $depo, $ttl = 86400) {
	if ($depo == 'ram_eaccelerator') {
		return eaccelerator_put ( $key, $data, $ttl );
	}
	if ($depo == 'ram_xcache') {
		return xcache_set ( $key, $data, $ttl );
	}
	if ($depo == 'ram_memcache') {
		$memcache_obj = new Memcache ();
		if ($memcache_obj->connect ( getMemcacheHost (), getMemcachePort () )) {
			$result = $memcache_obj->set ( $key, $data, 0, $ttl );
			$memcache_obj->close ();
		}
		return $result;
	}
	
	return false;
}
function setNitroDBCache($cache, $data, $expire = 3600) {
	$data = serialize ( $data );
	$file = NITRO_DBCACHE_FOLDER . $cache . '.nitro';
	
	$nitroPersistence = getNitroPersistence ();
	$nitroPersistence = $nitroPersistence ['Nitro'];
	
	if (! empty ( $nitroPersistence ['DBCache'] ['CacheDepo'] )) {
		if ($nitroPersistence ['DBCache'] ['CacheDepo'] == 'hdd') {
			
			if (! is_dir ( NITRO_DBCACHE_FOLDER )) {
				if (! mkdir ( NITRO_DBCACHE_FOLDER ))
					return false;
			}
			
			if (is_writeable ( NITRO_DBCACHE_FOLDER )) {
				if (file_put_contents ( $file, $data ))
					return true;
			}
		} else {
			setRamCache ( $file, $data, $nitroPersistence ['DBCache'] ['CacheDepo'], $nitroPersistence ['DBCache'] ['ExpireTime'] );
		}
	}
	
	return false;
}

?>