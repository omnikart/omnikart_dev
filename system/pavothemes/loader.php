<?php 

/******************************************************
 * @package Pavo Opencart Theme Framework for Opencart 1.5.x
 * @version 3.0
 * @author http://www.pavothemes.com
 * @copyright	Copyright (C) May 2014 PavoThemes.com <@emai:pavothemes@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/
if( !class_exists("Tools") ){
	class Tools{
		public static function getValue( $key, $value='' ){
			return isset($_REQUEST[$key])?$_REQUEST[$key]:$value;
		}
	}
}
 
 


define( "PAV_CSS_CACHE", DIR_CACHE."pavo-asset/" );
define( "PAV_SUB_PATH", 'system/cache/pavo-asset/' );
define( "PAV_FRAMEWORK_WIDGET_DIR", DIR_SYSTEM. 'pavothemes/widget/' );

require_once( DIR_SYSTEM.'pavothemes/form.php' );
require_once( DIR_SYSTEM.'pavothemes/widgetbase.php' );
require_once( DIR_SYSTEM . 'pavothemes/framework.php' );
require_once( DIR_SYSTEM . 'pavothemes/cache.php' );
require_once( DIR_SYSTEM . 'pavothemes/csscompressor.php' );

?>