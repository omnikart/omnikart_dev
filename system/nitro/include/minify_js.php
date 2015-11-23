<?php

function nitro_minify_js($scripts) {

	if (defined('HTTP_CATALOG') || getNitroPersistence('Mini.JSExtract')) {
	  return $scripts;
	} else {
	  require_once(DIR_SYSTEM . 'nitro/core/minify_functions.php');
	  return optimizeJS($scripts);
	}

}