/*
 * Opencart Blog Extension v1.0 - blogScript.js
 * This file is part of Opencart Blog Extension, an Admin template build for sale sale at Evanto Marketplace.
 * All copyright to this file is hold by techbuz <techbuzz69@gmail.com>.
 * Last Updated:
 * April 09, 2015
 *
 */

$(document).ready(function() {
	/* Search */
	$('#blogsearch input[name=\'search\']').parent().find('button').on('click', function() {
		url = $('base').attr('href') + 'index.php?route=blog/search_processor';

		var value = $('header input[name=\'search\']').val();

		if (value) {
			url += '&search=' + encodeURIComponent(value);
		}

		location = url;
	});

	$('#blogsearch input[name=\'search\']').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('header input[name=\'search\']').parent().find('button').trigger('click');
		}
	});
});

$(function(){
	$('.alert-danger, .alert-warning').prepend('<span class="fa fa-exclamation-circle"></span>&nbsp;');
	$('.alert-success').prepend('<span class="fa fa-check-circle"></span>&nbsp;');
})(jQuery);