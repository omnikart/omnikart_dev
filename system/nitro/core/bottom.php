<?php

function writeToCacheFile() {
	if (!isPreCacheRequest() && passesPageCacheValidation() == false) {
		return false;	
	}

	$cachefile = NITRO_PAGECACHE_FOLDER . generateNameOfCacheFile();
	
	if (!is_dir(NITRO_PAGECACHE_FOLDER)) {
		mkdir(NITRO_PAGECACHE_FOLDER);
	}
	
	$ob_content = ob_get_contents();
	
	if (
			getNitroPersistence('Mini.Enabled') && 
			(
				getNitroPersistence('Mini.CSSExtract') || 
				getNitroPersistence('Mini.JSExtract')
			)
		) {

		require_once NITRO_FOLDER . 'core' . DS . 'resources_fix_tool.php';

		function nitro_error_handler_bottom($errno, $errstr, $errfile, $errline) {
			return true;
		}

		set_error_handler('nitro_error_handler_bottom');

		try {
			$ob_content = extractHardcodedResources($ob_content);
		} catch (Exception $e) {}
		
		restore_error_handler();
	}
	
	$ob_content = minifyHtmlIfNecessary($ob_content);

	$ob_content = addImageWHAttributesIfNecessary($ob_content);

	$cached = fopen($cachefile, 'w');
	fwrite($cached, $ob_content);
	fclose($cached);

	if (getNitroPersistence('Compress.Enabled') && getNitroPersistence('Compress.HTML')) {  
        $ob_content = compressGzipIfNecessary($ob_content);

        $old_cachefile = $cachefile;
        $cachefile = $cachefile . '.gz';

        $cached = fopen($cachefile, 'w');
        fwrite($cached, $ob_content);
        fclose($cached);

        file_put_contents($old_cachefile, '');
    }

	$headers = getSpecialHeaders();
	
	if (!empty($headers)) {
		$headers_file = NITRO_HEADERS_FOLDER . generateNameOfCacheFile();
		$hf = fopen($headers_file, 'w');
		fwrite($hf, $headers);
		fclose($hf);
	}
}

function addImageWHAttributesIfNecessary($content) {
	if (getNitroPersistence('PageCache.AddWHImageAttributes')) {
		if (mobileCheck()) {
			return $content;
		}
		
		return preg_replace('/(?<=src\=)[\"\'][^\"\']*[-_]{1}(\d+)x(\d+)(-?_?[0-9]*)\.((jpe?g)|(png))[\"|\']/', '$0 width="$1" height="$2"', $content);
	}

	return $content;
}

function compressGzipIfNecessary($content) {
	$level = getNitroPersistence('Compress.HTMLLevel');

	if (getNitroPersistence('Compress.Enabled') && getNitroPersistence('Compress.HTML') && $level) {
		return gzencode($content, $level);
	}

	return $content;
}

function writeLoadTime($time) {
	if (!isPreCacheRequest() && passesPageCacheValidation() == false) {
		return false;	
	}

	unset($_SESSION['NitroRenderTime']);
	unset($_SESSION['NitroNameOfCacheFile']);

	file_put_contents(NITRO_PAGECACHE_FOLDER . 'meta.html', generateNameOfCacheFile() . ' : ' . $time . ' ; ', FILE_APPEND | LOCK_EX);
}

function close_nitro() {
	writeToCacheFile();

	ob_end_flush();
	
	writeLoadTime(microtime(true) - $GLOBALS['nitro.start.time']);
}

close_nitro();
?>