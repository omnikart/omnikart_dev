<?php

function nitro_minify_css($styles) {

	if (defined('HTTP_CATALOG') || getNitroPersistence('Mini.CSSExtract')) {
	  return $styles;
	} else {
	  require_once(DIR_SYSTEM . 'nitro/core/minify_functions.php');
	  return optimizeCSS($styles);
	}

}