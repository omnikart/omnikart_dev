<?php
function optimizeCSS($styles) {
	if (!isNitroEnabled() || !getNitroPersistence('Mini.Enabled')) {
		return $styles;
	}
	
	global $registry;

	$oc_root = dirname(DIR_APPLICATION);
	
	//load NitroCache
	require_once(DIR_SYSTEM . 'nitro/core/cdn.php');
	
	$excludes = explodeTrim("\n", getNitroPersistence('Mini.CSSExclude'));
	
	$css_groups = array();
	
	foreach($styles as $hash=>$style) {
		if (empty($css_groups[$style['rel'].'/nitro_divider/'.$style['media']])) $css_groups[$style['rel'].'/nitro_divider/'.$style['media']] = array();
		$css_groups[$style['rel'].'/nitro_divider/'.$style['media']][$hash] = $style['href'];
	}
	
	if (getNitroPersistence('Mini.CSS')) {
		foreach ($css_groups as &$files) {
			$files = minify('css', $files, $excludes);
		}
	}
	
	if (getNitroPersistence('Mini.CSSCombine')) {
		foreach ($css_groups as &$files) {
			$files = combine('css', $files, $excludes);
		}
	}
	
	$styles = array();

	foreach ($css_groups as $key=>$files) {
		foreach ($files as $file) {
			list($rel, $media) = explode('/nitro_divider/', $key);
			$styles[md5($file)] = array(
				'rel' => $rel,
				'media' => $media,
				'href' => $file
			);
		}
	}

	return nitroCDNResolve($styles);
}

function optimizeJS($scripts) {
	if (!isNitroEnabled() || !getNitroPersistence('Mini.Enabled')) {
		return $scripts;
	}
	
	global $registry;
	$oc_root = dirname(DIR_APPLICATION);
	$cache = NULL;
	$cachefile = NULL;
	$filename = NULL;
	
	//load NitroCache
	require_once(DIR_SYSTEM . 'nitro/core/cdn.php');
	
	$nitroSettings = getNitroPersistence();

	$excludes = explodeTrim("\n", getNitroPersistence('Mini.JSExclude'));
	
	if (getNitroPersistence('Mini.JS')) {
		$scripts = minify('js', $scripts, $excludes);
	}
	
	if (getNitroPersistence('Mini.JSCombine')) {
		$scripts = combine('js', $scripts, $excludes);
	}
	
	return nitroCDNResolve($scripts);
}

function is_url(&$string) {
    $standard_match = preg_match('@^(https?:)?//.*$@', $string);

    if (!$standard_match) {
        $trimmed = trim($string, '/');

        //if (stripos($trimmed, 'index.php?') === 0 || stripos($trimmed, 'stylesheet/cssminify1.php?') !== FALSE) {
        if (preg_match('~\.php\?~', $trimmed) !== FALSE) {
            $string = HTTP_SERVER . $trimmed;
            $standard_match = true;
        }
    }

    return $standard_match;
}

function encode_filename($filename) {
	if (NITRO_DEBUG_MODE) {
		return preg_replace('/\.\w+$/', '', str_replace(DS, '-', str_replace(NITRO_SITE_ROOT, '', $filename)));
	} else {
		return md5($filename);
	}
}

function clean_file_paths(&$files, &$excludes) {
	global $registry;
	$oc_root = dirname(DIR_APPLICATION);
	
	foreach($files as $hash=>$file) {
		$files[$hash] = html_entity_decode($file);
		$url_info = parse_url($file);
		
		if (!empty($url_info['path'])) {
			if (strpos($url_info['path'], $oc_root) === 0) {//This is an absolute path to the file
				$f = str_replace($oc_root, '', $url_info['path']);
			} else {
				$f = $url_info['path'];
			}
			$f = trim($f, '/');
			
			if (file_exists($oc_root.DS.$f)) {
				if (preg_match('/.*?\.(css|js)$/', $f)) {
					$files[$hash] = $f;
				} else {
					if (!is_url($file)) {
						$excludes[] = basename($file);
					}
				}
			} else if (!is_url($file)) {
				$excludes[] = basename($file);
			}
		} else if (!is_url($file)) {
			$excludes[] = basename($file);
		}
	}
}

function minify($type, $files, $excludes) {
	if (!in_array($type, array('css', 'js'))) return $files;
	
	//extract local fylesystem path
	clean_file_paths($files, $excludes);
	
	global $registry;
	$oc_root = dirname(DIR_APPLICATION);
	$cache = NULL;
	$cachefile = NULL;
	$filename = NULL;
	
	if (!defined('DS')) {
		define('DS', DIRECTORY_SEPARATOR);
	}
	
	if (!file_exists($oc_root.DS.'assets')) {
		mkdir($oc_root.DS.'assets');
	}
	
	if (!file_exists($oc_root.DS.'assets'.DS.$type)) {
		mkdir($oc_root.DS.'assets'.DS.$type);
	}

	$cachefile = $oc_root.DS.'assets'.DS.$type.DS.getSSLCachePrefix().$type.'.cache';
	
	if (!file_exists($cachefile)) {
		touch($cachefile);
		file_put_contents($cachefile, json_encode(array()));
	}
	
	$cache = json_decode(file_get_contents($cachefile), true);
	
	switch ($type) {
		case 'js':
			include_once NITRO_LIB_FOLDER.'minifier'.DS.'JSShrink.php';
			break;
		case 'css':
			if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
				$webshopUrl = $registry->get('config')->get('config_ssl');
			} else {
				$webshopUrl = $registry->get('config')->get('config_url');
			}
			$webshopUrl = rtrim(preg_replace('~^https?\:~i', '', $webshopUrl), '/');
			
			include_once NITRO_LIB_FOLDER.'minifier'.DS.'CSSMin.php';
			break;
	}
	
	foreach ($files as $hash=>$file) {
		$recache = false;
		
		if (!is_excluded_file($file, $excludes)) {
			$filename = $oc_root.DS.trim(str_replace('/', DS, $file), DS);
			$basefilename = basename($file, '.'.$type);
			$target = '/assets/'.$type.'/'.getSSLCachePrefix().'nitro-mini-' . encode_filename($filename) . '.'.$type;
			$targetAbsolutePath = $oc_root.DS.trim(str_replace('/', DS, $target), DS);
			if (!empty($cache[$filename])) {
				if (!is_url($file) && $cache[$filename] != filemtime($filename)) {
					$recache = true;
				}
			} else {
				$recache = true;
			}
			
			if ($recache || !file_exists($targetAbsolutePath)) {
				touch($targetAbsolutePath);
				$content = is_url($file) ? fetchRemoteContent($file) : file_get_contents($filename);
				
				switch($type) {
					case 'js':
						$content = Minifier::minify($content, array('flaggedComments' => false));
						break;
					case 'css':
						$urlToCurrentDir = is_url($file) ? dirname($file) : $webshopUrl.dirname('/'.trim($file, '/'));
						$content = preg_replace('/(url\()(?![\'\"]?(?:(?:https?\:\/\/)|(?:data\:)|(?:\/)))([\'\"]?)\/?(?!\/)/', '$1$2'.$urlToCurrentDir.'/', $content);

						$content = Nitro_Minify_CSS_Compressor::process($content);
						break;
				}
				
				file_put_contents($targetAbsolutePath, $content);
				
				$cache[$filename] = is_url($file) ? time() : filemtime($filename);
			}
			
			$files[$hash] = trim($target, '/');
		}
	}
	
	file_put_contents($cachefile, json_encode($cache));
	
	return $files;
}

function is_excluded_file($path, $excludes) {
	$excludes[] = 'fonts.googleapis.com';
	foreach ($excludes as $e) {
		if (strpos($path, $e) !== false) return true;
	}
	return false;
}

function combine($type, $files, $excludes) {
	if (!in_array($type, array('css', 'js'))) return $files;
	
	//extract local fylesystem path
	clean_file_paths($files, $excludes);
	
	global $registry;
	$oc_root = dirname(DIR_APPLICATION);
	$cache = NULL;
	$cachefile = NULL;
	$filename = NULL;
	
	if (!defined('DS')) {
		define('DS', DIRECTORY_SEPARATOR);
	}
	
	$cachefile = $oc_root.DS.'assets'.DS.$type.DS.getSSLCachePrefix().$type.'-combined.cache';
	
	if (!file_exists($cachefile)) {
		touch($cachefile);
		file_put_contents($cachefile, json_encode(array()));
	}
	
	$cache = json_decode(file_get_contents($cachefile), true);
	
	$comboHash = '';
	$excludedFiles = array();
	$includedFiles = 0;
	
	foreach ($files as $hash=>$file) {
		if (!is_excluded_file($file, $excludes)) {
			$comboHash .= $hash;
			$includedFiles++;
		} else {
			$excludedFiles[$hash] = $file;
		}
	}
	
	$comboHash = md5($comboHash);
	$target = '/assets/'.$type.'/'.getSSLCachePrefix().'nitro-combined-' . $comboHash . '.'.$type;
	$targetAbsolutePath = $oc_root.DS.trim(str_replace('/', DS, $target), DS);
	
	$recache = false;
	
	foreach ($files as $hash=>$file) {
		if (!is_excluded_file($file, $excludes)) {
			$filename = $oc_root.DS.trim(str_replace('/', DS, $file), DS);
			
			if (!empty($cache[$comboHash][$filename])) {
				if (!is_url($file) && $cache[$comboHash][$filename] != filemtime($filename)) {
					$recache = true;
					break;
				}
			} else {
				$recache = true;
				break;
			}
		} else {
			continue;
		}
	}
	
	$combinedContent = '';
	
	if ($recache || !file_exists($targetAbsolutePath)) {
		if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
			$webshopUrl = $registry->get('config')->get('config_ssl');
		} else {
			$webshopUrl = $registry->get('config')->get('config_url');
		}
		$webshopUrl = rtrim(preg_replace('~^https?\:~i', '', $webshopUrl), '/');
		
		$counter = 0;
		foreach ($files as $hash=>$file) {
			if (!is_excluded_file($file, $excludes)) {
				$filename = $oc_root.DS.trim(str_replace('/', DS, $file), DS);
				
				$content = is_url($file) ? fetchRemoteContent($file) : file_get_contents($filename);
				$urlToCurrentDir = is_url($file) ? dirname($file) : $webshopUrl.str_replace($oc_root, '', dirname('/'.trim($filename, '/')));
				
				if (!empty($content)) {
					if ($type == 'js' && substr($content, -1) == ')') {
						$content .= ';';
					}
					
					if ($type == 'css') {
						$content = preg_replace('/(url\()(?![\'\"]?(?:(?:https?\:\/\/)|(?:data\:)|(?:\/)))([\'\"]?)\/?(?!\/)/', '$1$2'.$urlToCurrentDir.'/', $content);
					}
					
					$combinedContent .= (($counter > 0) ? PHP_EOL : '') . $content;
					unset($content);
					$counter++;
				}
				
				$cache[$comboHash][$filename] = is_url($file) ? 0 : filemtime($filename);
			}
		}
		
		if ($type == 'css') {
			/* pull imports to the top and include their content if possible */
			$imports = array();
			preg_match_all('/\@import[^\;]*\;/', $combinedContent, $imports);
			if (!empty($imports)) {
				$imports = array_reverse($imports[0]);
				foreach ($imports as $import) {
					$importUrl = preg_replace('/[^\'"\(]*(?:[\'"\(\s]?)*(.*?)(?:[\'"\)]).*/', '$1', $import);
					if (getNitroPersistence('Mini.CSSFetchImport')) {
						$tmpImportContent = fetchRemoteContent($importUrl);
					}
					
					if (!empty($tmpImportContent)) {
						$combinedContent = $tmpImportContent.str_replace($import, '', $combinedContent);
					} else {
						$combinedContent = $import.str_replace($import, '', $combinedContent);
					}
				}
			}
		}
		
		file_put_contents($targetAbsolutePath, $combinedContent);
	}
	
	file_put_contents($cachefile, json_encode($cache));
	
	if ($includedFiles > 0) {
		return array_merge($excludedFiles, array(md5($target) => trim($target, '/')));
	} else {
		return $excludedFiles;
	}
}