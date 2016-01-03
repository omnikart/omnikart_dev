/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.filebrowserBrowseUrl = 'index.php?route=common/filemanager&token=' + getURLVar('token');
	config.filebrowserUploadUrl = 'index.php?route=common/filemanager/upload&token=' + getURLVar('token');
	config.filebrowserImageBrowseUrl = 'index.php?route=common/filemanager&token=' + getURLVar('token');
};
