<?php
require_once NITRO_CORE_FOLDER . 'minify_functions.php';
function extractHardcodedResources($content) {
	if (! isNitroEnabled () || ! getNitroPersistence ( 'Mini.Enabled' )) {
		return $content;
	}
	
	$settings = getNitroPersistence ();
	$cssExclude = array ();
	$jsExclude = array ();
	
	$jsLineExclude = array ();
	
	$cssExtractCheckPassed = false;
	$jsExtractCheckPassed = false;
	
	require_once (DIR_SYSTEM . 'nitro' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'core.php');
	require_once (DIR_SYSTEM . 'nitro' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'cdn.php');
	
	if (getNitroPersistence ( 'Mini.CSSExtract' )) {
		$cssExtractCheckPassed = true;
		
		if (getNitroPersistence ( 'Mini.CSSExclude' )) {
			$cssExclude = trim ( getNitroPersistence ( 'Mini.CSSExclude' ), "\n\r " );
			$cssExclude = explode ( "\n", $cssExclude );
			foreach ( $cssExclude as $k => $stylename ) {
				$stylename = trim ( $stylename, "\n\r " );
				if (! empty ( $stylename )) {
					$cssExclude [$k] = $stylename;
				}
			}
		}
		
		$extractedCSSFiles = array ();
		
		$current_pos = 0;
		$html_end = strlen ( $content ) - 1;
		
		while ( $html_end !== false && $current_pos < $html_end ) {
			$next_css = strpos ( $content, 'stylesheet', $current_pos );
			
			$tag_start = $next_css;
			if ($next_css !== false) {
				// go left to check if we are in a link tag
				$i = $next_css;
				$isTagStartFound = false;
				while ( $i > 0 && ! $isTagStartFound ) {
					if ($content [$i - 1] == '<') {
						if (substr ( $content, $i, 4 ) != '?php') {
							$isTagStartFound = true;
						} else {
							$i --;
						}
					} else {
						$i --;
					}
				}
				
				$tag_start = $i - 1;
				$tag = '';
				while ( $i < $next_css && ! in_array ( $content [$i], array (
						' ',
						"\n",
						"\r" 
				) ) ) {
					$tag .= $content [$i];
					$i ++;
				}
				
				if (strtolower ( $tag ) == 'link') {
					// see if we are not in a comment block
					$c = $i;
					$commentStartFound = false;
					$commentEndFound = false;
					
					while ( $c > 0 && ! $commentStartFound ) {
						if ($content [$c] == '>') {
							if (substr ( $content, $c - 2, 3 ) == '-->') {
								$commentEndFound = true;
								break;
							}
						}
						if ($content [$c] == '<') {
							if (substr ( $content, $c, 4 ) == '<!--') {
								$commentStartFound = true;
							}
						}
						$c --;
					}
					
					$weAreInComment = ($commentStartFound && ! $commentEndFound);
					
					// find the href
					while ( $i < $html_end ) {
						if ($content [$i] == 'h' && (substr ( $content, $i, 5 ) == 'href=')) {
							$i += 6;
							break;
						}
						$i ++;
					}
					
					$css_src = '';
					while ( $i < $html_end && $content [$i] != '\'' && $content [$i] != '"' ) {
						$css_src .= $content [$i];
						$i ++;
					}
					if (strpos ( $css_src, '<?php' ) !== false || nitroIsIgnoredUrl ( $css_src, $cssExclude ) || $weAreInComment) { // skip this css if its location is dynamically generated
						$current_pos = $next_css + 1;
						continue;
					}
					
					$extractedCSSFiles [] = $css_src;
					// cut the css link
					$i = $tag_start;
					$tag_end = $tag_start;
					$isTagEndFound = false;
					while ( $i < $html_end && ! $isTagEndFound ) {
						if ($content [$i] == '>' && $content [$i - 1] != '?') { // if we are not in php closing tag
							$isTagEndFound = true;
						}
						$tag_end = $i;
						$i ++;
					}
					$content = substr ( $content, 0, $tag_start ) . substr ( $content, $tag_end + 1 );
					$html_end = strlen ( $content ) - 1;
				} else {
					$current_pos = $next_css + 1;
					continue;
				}
			} else {
				break;
			}
			$current_pos = $tag_start + 1;
		}
		
		// minify and combine the newly extracted css resources
		// and then put them in the header
		$minCSS = optimizeCSS ( generateCSSMinificatorStyles ( $extractedCSSFiles ) );
		$new_css_include = '';
		foreach ( $minCSS as $css_file ) {
			$new_css_include .= '<link rel="' . $css_file ['rel'] . '" type="text/css" href="' . $css_file ['href'] . '" media="' . $css_file ['media'] . '" />';
		}
		if (! empty ( $new_css_include )) {
			$base_start = strpos ( $content, '<base' );
			
			if ($base_start === false) {
				$base_start = strpos ( $content, '<head' );
			}
			
			$i = $base_start;
			$base_end = 0;
			while ( $i < $html_end && ! $base_end ) {
				if ($content [$i] == '>' && $content [$i - 1] != '?') {
					$base_end = $i;
					break;
				}
				$i ++;
			}
			
			$content = substr ( $content, 0, $base_end + 1 ) . $new_css_include . substr ( $content, $base_end + 1 );
		}
	}
	
	if (getNitroPersistence ( 'Mini.JSExtract' )) {
		$jsExtractCheckPassed = true;
		
		if (getNitroPersistence ( 'Mini.JSExclude' )) {
			$jsExclude = trim ( getNitroPersistence ( 'Mini.JSExclude' ), "\n\r " );
			$jsExclude = explode ( "\n", $jsExclude );
			foreach ( $jsExclude as $script ) {
				$script = trim ( $script, "\n\r " );
				if (! empty ( $script )) {
					$jsExclude [] = $script;
				}
			}
		}
		
		if (getNitroPersistence ( 'Mini.JSExcludeInline' )) {
			$jsLineExclude = trim ( getNitroPersistence ( 'Mini.JSExcludeInline' ), "\n\r " );
			$jsLineExclude = explode ( "\n", $jsLineExclude );
			foreach ( $jsLineExclude as $script ) {
				$script = trim ( $script, "\n\r " );
				if (! empty ( $script )) {
					$jsLineExclude [] = $script;
				}
			}
		}
		
		if (NITRO_DEFAULT_EXCLUDES) {
			$jsLineExclude [] = 'flexslider';
			$jsLineExclude [] = '#button-cart';
		}
		
		$extractedJSFiles = array ();
		
		$current_pos = 0;
		$html_end = strlen ( $content ) - 1;
		while ( $html_end !== false && $current_pos < $html_end ) {
			$next_js = strpos ( $content, '<script', ($current_pos) );
			$tag_start = $next_js;
			if ($next_js !== false) {
				// go left to check if we are in a script tag
				$i = $next_js;
				$tag_start = $i;
				$tag = 'script';
				while ( $i < $next_js && ! in_array ( $content [$i], array (
						' ',
						"\n",
						"\r" 
				) ) ) {
					$tag .= $content [$i];
					$i ++;
				}
				
				if (strtolower ( $tag ) == 'script') {
					// see if we are not in a comment block
					$c = $i;
					$commentStartFound = false;
					$commentEndFound = false;
					while ( $c > 0 && ! $commentStartFound ) {
						if ($content [$c] == '>') {
							if (substr ( $content, $c - 2, 3 ) == '-->') {
								$commentEndFound = true;
								break;
							}
						}
						if ($content [$c] == '<') {
							if (substr ( $content, $c, 4 ) == '<!--') {
								$commentStartFound = true;
							}
						}
						$c --;
					}
					
					$weAreInComment = ($commentStartFound && ! $commentEndFound);
					// find the src
					$src_start = $i;
					$isSrcStartFound = false;
					while ( $i < $html_end && ! $isSrcStartFound ) {
						if ($content [$i] == 's' && (substr ( $content, $i, 4 ) == 'src=')) {
							$isSrcStartFound = true;
							$src_start = $i;
							break;
						} else if ($content [$i] == '>' && $content [$i - 1] != '?') { // we have reached the closing char of the script tag
							break;
						}
						$i ++;
					}
					
					$i = $src_start + 5;
					$js_src = '';
					if ($isSrcStartFound) {
						while ( $i < $html_end && $content [$i] != '\'' && $content [$i] != '"' ) {
							$js_src .= $content [$i];
							$i ++;
						}
					}
					$js_src = trim ( $js_src );
					
					if (! $isSrcStartFound && ! $weAreInComment) { // inline javascript
						$type_start = false;
						$end_of_tag = false;
						$i = $tag_start;
						while ( $i < $html_end && ! $type_start && ! $end_of_tag ) {
							if ($content [$i] == 't') {
								if (substr ( $content, $i, 5 ) == 'type=') {
									$type_start = $i + 6;
									break;
								}
							} else if ($content [$i] == '>' && $content [$i - 1] != '?') {
								$end_of_tag = $i;
								break;
							}
							$i ++;
						}
						if ($type_start) {
							
							$i = $type_start;
							$script_type = '';
							while ( $i < $html_end && $content [$i] != '\'' && $content [$i] != '"' ) {
								$script_type .= $content [$i];
								$i ++;
							}
							if ($script_type == 'text/javascript') {
								while ( $i < $html_end && ! $end_of_tag ) {
									if ($content [$i] == '>' && $content [$i - 1] != '?') {
										$end_of_tag = $i;
										break;
									}
									$i ++;
								}
							}
						}
						
						if ($end_of_tag) {
							$script_end = strpos ( $content, '</script', $end_of_tag );
							$code = substr ( $content, $end_of_tag + 1, $script_end - ($end_of_tag + 1) );
							
							foreach ( $jsLineExclude as $excludeCode ) {
								if (stripos ( $code, $excludeCode ) !== false) { // skip this js
									$current_pos = $next_js + 1;
									continue 2;
								}
							}
							
							$new_js_file = createTempScript ( $code );
							$tag_end = $tag_start;
							
							$i = $tag_start;
							$isTagEndFound = false;
							$passedThroughClosingScriptTag = false;
							while ( $i < $html_end && ! $isTagEndFound ) {
								if ($content [$i] == '>' && $content [$i - 1] != '?') { // if we are not in php closing tag
									if ($passedThroughClosingScriptTag) {
										$isTagEndFound = true;
									}
								} else if ($content [$i] == '<') {
									if (substr ( $content, $i, 8 ) == '</script') {
										$passedThroughClosingScriptTag = true;
									}
								}
								$tag_end = $i;
								$i ++;
							}
							$content = substr ( $content, 0, $tag_start ) . substr ( $content, $tag_end + 1 );
							
							$extractedJSFiles [] = $new_js_file;
							$html_end = strlen ( $content ) - 1;
						}
					}
					
					if (strpos ( $js_src, '<?php' ) !== false || nitroIsIgnoredUrl ( $js_src, $jsExclude ) || $weAreInComment || ! $isSrcStartFound) { // skip this js if its location is dynamically generated, is excluded, is in comment or is inline
						$current_pos = $next_js + 1;
						continue;
					}
					
					$extractedJSFiles [] = $js_src;
					// cut the js link from html
					$i = $tag_start;
					$tag_end = $tag_start;
					$isTagEndFound = false;
					$passedThroughClosingScriptTag = false;
					while ( $i < $html_end && ! $isTagEndFound ) {
						if ($content [$i] == '>' && $content [$i - 1] != '?') { // if we are not in php closing tag
							if ($passedThroughClosingScriptTag) {
								$isTagEndFound = true;
							}
						} else if ($content [$i] == '<') {
							if (substr ( $content, $i, 8 ) == '</script') {
								$passedThroughClosingScriptTag = true;
							}
						}
						$tag_end = $i;
						$i ++;
					}
					$content = substr ( $content, 0, $tag_start ) . substr ( $content, $tag_end + 1 );
					$html_end = strlen ( $content ) - 1;
				} else {
					$current_pos = $next_js + 1;
					continue;
				}
			} else {
				break;
			}
			$current_pos = $tag_start;
		}
		// minify and combine the extracted js
		// and put it at the end
		
		$minJS = optimizeJS ( generateJSMinificatorScripts ( $extractedJSFiles ) );
		
		$new_js_include = '';
		$use_defer = getNitroPersistence ( 'Mini.JSDefer' );
		
		foreach ( $minJS as $js_file ) {
			$new_js_include .= '<script type="text/javascript" src="' . $js_file . '"' . ($use_defer ? ' defer' : '') . '></script>';
		}
		if (! empty ( $new_js_include )) {
			$position = getNitroPersistence ( 'Mini.JSPosition' );
			if ($position == 'bottom') {
				$move_pos = strpos ( $content, '</body' );
				if ($move_pos === false) {
					$move_pos = strpos ( $content, '</html' );
				}
			} else {
				$base_start = strpos ( $content, '<base' );
				
				if ($base_start === false) {
					$base_start = strpos ( $content, '<head' );
				}
				
				$i = $base_start;
				$base_end = 0;
				while ( $i < $html_end && ! $base_end ) {
					if ($content [$i] == '>' && $content [$i - 1] != '?') {
						$base_end = $i;
						break;
					}
					$i ++;
				}
				
				$move_pos = $base_end + 1;
			}
			
			if ($move_pos !== false) {
				$content = substr ( $content, 0, $move_pos ) . $new_js_include . substr ( $content, $move_pos );
			} else {
				$content .= $new_js_include;
			}
		}
	}
	
	return $content;
}
function createTempScript($code) {
	if (! file_exists ( NITRO_FOLDER . 'temp' ) || ! is_dir ( NITRO_FOLDER . 'temp' )) {
		mkdir ( NITRO_FOLDER . 'temp' );
	}
	
	if (! file_exists ( NITRO_FOLDER . 'temp' . DS . 'js' ) || ! is_dir ( NITRO_FOLDER . 'temp' . DS . 'js' )) {
		mkdir ( NITRO_FOLDER . 'temp' . DS . 'js' );
	}
	
	$scriptname = md5 ( $code ) . '.js';
	$script_path = NITRO_FOLDER . 'temp' . DS . 'js' . DS . $scriptname;
	$code = str_replace ( array (
			'<!--',
			'-->' 
	), '', $code );
	file_put_contents ( $script_path, $code );
	$script_path = str_replace ( array (
			'/',
			'\\' 
	), array (
			DS,
			DS 
	), $script_path );
	return str_replace ( dirname ( DIR_APPLICATION ) . DS, '', $script_path );
}
function nitroIsIgnoredUrl($url, $ignored_urls) {
	if (! empty ( $ignored_urls )) {
		foreach ( $ignored_urls as $ignoredUrl ) {
			if (! empty ( $ignoredUrl )) {
				if (strpos ( $url, $ignoredUrl ) !== false) {
					return true;
				}
			}
		}
	}
	
	return false;
}
function generateCSSMinificatorStyles($styles) {
	$formatted_styles = array ();
	foreach ( $styles as $style ) {
		$formatted_styles [md5 ( $style )] = array (
				'href' => $style,
				'rel' => 'stylesheet',
				'media' => 'screen' 
		);
	}
	return $formatted_styles;
}
function generateJSMinificatorScripts($scripts) {
	$formatted_scripts = array ();
	
	foreach ( $scripts as $script ) {
		$formatted_scripts [md5 ( $script )] = $script;
	}
	
	return $formatted_scripts;
}