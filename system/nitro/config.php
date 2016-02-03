<?php
define ( 'DS', DIRECTORY_SEPARATOR );
define ( 'NITRO_FOLDER', DIR_SYSTEM . 'nitro' . DS );
define ( 'NITRO_SITE_ROOT', dirname ( DIR_SYSTEM ) . DS );
define ( 'NITRO_CORE_FOLDER', DIR_SYSTEM . 'nitro' . DS . 'core' . DS );
define ( 'NITRO_DATA_FOLDER', DIR_SYSTEM . 'nitro' . DS . 'data' . DS );
define ( 'NITRO_LIB_FOLDER', DIR_SYSTEM . 'nitro' . DS . 'lib' . DS );
define ( 'NITRO_INCLUDE_FOLDER', DIR_SYSTEM . 'nitro' . DS . 'include' . DS );
define ( 'NITRO_DBCACHE_FOLDER', DIR_SYSTEM . 'nitro' . DS . 'cache' . DS . 'dbcache' . DS );
define ( 'NITRO_PAGECACHE_FOLDER', DIR_SYSTEM . 'nitro' . DS . 'cache' . DS . 'pagecache' . DS );
define ( 'NITRO_HEADERS_FOLDER', DIR_SYSTEM . 'nitro' . DS . 'cache' . DS . 'headers' . DS );

define ( 'NITRO_PERSISTENCE', DIR_SYSTEM . 'nitro' . DS . 'data' . DS . 'persistence.tpl' );
define ( 'NITRO_FTP_PERSISTENCE', DIR_SYSTEM . 'nitro' . DS . 'data' . DS . 'ftp_persistence.tpl' );
define ( 'NITRO_RACKSPACE_PERSISTENCE', DIR_SYSTEM . 'nitro' . DS . 'data' . DS . 'rackspace_persistence.tpl' );
define ( 'NITRO_SMUSHIT_PERSISTENCE', DIR_SYSTEM . 'nitro' . DS . 'data' . DS . 'smushit_persistence.tpl' );

define ( 'NITRO_EXTENSIONS_CSS', serialize ( array (
		'css' 
) ) );
define ( 'NITRO_EXTENSIONS_JS', serialize ( array (
		'js' 
) ) );
define ( 'NITRO_EXTENSIONS_IMG', serialize ( array (
		'png',
		'jpg',
		'jpeg',
		'gif',
		'tiff',
		'bmp' 
) ) );
define ( 'NITRO_DEBUG_MODE', 0 ); // 0 - Production; 1 - Debug mode;
define ( 'NITRO_NP_FILE', NITRO_DATA_FOLDER . 'np.txt' );
define ( 'NITRO_PAGECACHE_TIME', 86400 );
define ( 'NITRO_IGNORE_AJAX_REQUESTS', TRUE );
define ( 'NITRO_IGNORE_POST_REQUESTS', TRUE );
define ( 'NITRO_AUTO_GET_PAGESPEED', TRUE );
define ( 'NITRO_DEFAULT_EXCLUDES', TRUE );
define ( 'NITRO_DELETE_CHUNK', 1000 );
define ( 'NITRO_CDN_PREPARE_CHUNK', 50 );
define ( 'NITRO_CDN_UPLOAD_CHUNK', 10 );
define ( 'NITRO_CDN_RESIZE_MIN_FILESIZE', 1024 );

define ( 'NITRO_FOLDER_PERMISSIONS', 0755 );
define ( 'NITRO_DISABLE_FOR_ADMIN', false );
?>