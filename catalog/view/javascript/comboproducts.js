var wishlist_combo = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?route=account/wishlist/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
			
				$("#combo-notification .modal-footer").hide();
				$("#combo-notification").modal('show');
				if (json['success']) {
					/*$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');*/
					$("#combo-notification .modal-body p").append('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['info']) {
					/*$('#content').parent().before('<div class="alert alert-info"><i class="fa fa-info-circle"></i> ' + json['info'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');*/
					$("#combo-notification .modal-body p").append('<div class="alert alert-info"><i class="fa fa-info-circle"></i> ' + json['info'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				$('#wishlist-total').html(json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}
		});
	}
}

var cart_combo = {
	'add': function(product_id, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/add',
			type: 'post',
			data: 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			success: function(json) {

				$('#cart > button').button('reset');
				
				$("#combo-notification .modal-footer").hide();
				$("#combo-notification").modal('show');

				if (json['redirect']) {
					/*$('#content').parent().before('<div class="alert alert-warning"><i class="fa fa-warning"></i> ' + json['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');*/
					$("#combo-notification .modal-body p").append('<div class="alert alert-warning"><i class="fa fa-warning"></i> ' + json['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['success']) {
					/*$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');*/
					$("#combo-notification .modal-body p").append('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					setTimeout(function () {
						$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
					}, 100);

					$('html, body').animate({ scrollTop: 0 }, 'slow');

					$('#cart_modal .modal-body > ul').load('index.php?route=common/cart/info ul li');
				}
			}
		});
	}
}
