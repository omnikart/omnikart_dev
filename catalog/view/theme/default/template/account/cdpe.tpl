<?php echo $header; ?><div id="columns">
	<div class="container">
		<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success">
			<i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
				<style type="text/css">
					.img-thumbnail {
						cursor: pointer;
					}
				</style>
    <?php if ($products) { ?>
				<div class="row">
					<div class="col-md-4">
						<div class="btn-group hidden-xs">
							<button type="button" id="list-view" class="btn btn-default"
								data-toggle="tooltip" title="<?php echo $button_list; ?>">
								<i class="fa fa-th-list"></i>
							</button>
							<button type="button" id="grid-view" class="btn btn-default"
								data-toggle="tooltip" title="<?php echo $button_grid; ?>">
								<i class="fa fa-th"></i>
							</button>
						</div>
					</div>
					<div class="col-md-6 pull-right">
						<div class="btn-group hidden-xs pull-right">
							<button class="updatecategory btn btn-default" type="button"
								value="<?php echo $category['category_id']; ?>">
								<i class="fa fa-check"></i> <span
									class="hidden-xs hidden-sm hidden-md">Update Products</span>
							</button>
						</div>
					</div>
				</div>
				<br />
				<h2>Products</h2>
				<div class="row">
					<?php foreach ($products as $product) { ?>
						<div class="product-layout product-list col-xs-12">
							<div class="product-thumb">
								<?php //require(DIR_TEMPLATE.'default/template/common/product/product_cdp.tpl'); ?>
							</div>
						</div>
					<?php } ?>
				</div>
				<div class="table-responsive">
					<table id="gp-table" class="gp-table table table-bordered">
						<thead>
							<tr>
								<td >Image</td>
								<td id="gp-toggle-info">Product Name</td>
								<td class="text-right">Price</td>
								<td class="text-center">Stock Status</td>
								<td class="text-center">Stock Status/Quantity Baught</td>
								<td class="text-center">Contract Quantity</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($products as $child) { $child_id = $child['product_id']; ?>
									<tr data-gp-child-row="<?php echo $child_id; ?>" class="product-thumb">
										<td class="gp-col-image">
											<a class="gp-child-image" href="<?php echo $child['image']['popup']; ?>" title="<?php echo $child['name']; ?>">
												<img src="<?php echo $child['thumb'] ?>" alt="<?php echo $child['name'] ?>" />
											</a>
										</td>
										<td class="gp-col-name">
											<div class="gp-child-name"><?php echo $child['name']; ?>
												<?php if ($child['enabled']) { ?> 
													<?php if ($child['curr_vendor'] && 0) { ?>
														<div style="display:inline-block;position:relative;">
															<a class="dropdown-toggle" href="#" id="suppliers<?php echo $child['child_id']; ?>" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span><i class="fa fa-users"></i></span>
															</a>
															<ul class="dropdown-menu" aria-labelledby="suppliers<?php echo $child['child_id']; ?>" style="width:300px;">
																<li>Supplied by</li>
																<li class="current"><a href="<?php echo $child['vlink']; ?>"><?php echo $child['curr_vendor']['companyname']; ?></a></li>
																<?php if ($child['vendors']) { ?>
																	<li role="separator" class="divider"></li>
																	<li>Also supplied by</li>
																	<?php foreach($child['vendors'] as $vendor) { ?>
																		<li class="others"><a onclick="reloadGp('<?php echo $child['child_id']; ?>','<?php echo $vendor['vendor_id']; ?>');"><?php echo $vendor['companyname']; ?> at <?php echo $vendor['price']; ?></a></li>
																	<?php } ?>
																<?php } ?>
															</ul>		
														</div>
													<?php } ?>							
												<?php } ?>											
											</div>
											<?php foreach ($gp_child_info as $field_name => $field_text) if($child['info'][$field_name]) { ?>
											<div class="gp-toggle-info gp-child-field"><?php echo $field_text; ?>: <?php echo $child['info'][$field_name]; ?></div>
											<?php } ?>
											<?php foreach ($child['attributes'] as $child_attributes) { ?>
											<div class="gp-toggle-info gp-child-attribute"><?php echo $child_attributes['name']; ?>:
												<?php foreach ($child_attributes['attribute'] as $child_attribute) { ?>
												<div><?php echo $child_attribute['name'] . ': ' . $child_attribute['text']; ?></div>
												<?php } ?>
											</div>
											<?php } ?>
										</td>
										<td class="gp-col-price">
											<?php if ($child['enabled']) { ?>
												<?php if (!$child['special']) { ?>
													<?php echo $child['price']; ?>&nbsp;
													<?php /* if ($child['discounts']) { ?>
														<div class="discounts">
															<a class="pull-right dropdown-toggle" href="#" id="discounts<?php echo $child['child_id']; ?>" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">								
																 <i class="fa fa-th-list"></i>										
															</a>
															<div class="dropdown-menu" aria-labelledby="discounts<?php echo $child['child_id']; ?>">
																<div class="col-sm-12">
																	<?php foreach ($child['discounts'] as $discount) { ?>
																	<div class="gp-toggle-info gp-child-discount"><?php echo $discount['quantity']; ?><?php echo $text_discount; ?><?php echo $discount['price']; ?></div>
																	<?php } ?>
																</div>
																<div class="clearfix"></div>
															</div>
														</div>
													<?php  } */ ?>									
												<?php } else { ?>
												<span class="gp-child-price-old"><?php echo $child['price']; ?></span> <?php echo $child['special']; ?>
												<?php } ?>
											<?php } else { echo "<span class=\"no-price\">Not Available.</span>"; } ?>
										</td>
								<td>
									<?php echo $child['stock']; ?>
								</td>
								<td>
									<?php echo $child['purchase']['quantity']; ?>
								</td>
								<td>
									<input type="hidden" name="products[<?php echo $child_id; ?>][product_id]" class="form-control" value="<?php echo $child['product_id']; ?>">
									<input type="text" name="products[<?php echo $child_id; ?>][contract_quantity]" class="form-control" value="<?php echo $child['contract_quantity']; ?>">
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>				
				</div>
				<?php echo $pagination; ?>	
				
				
    <?php } ?>
    <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
	</div>
	<script type="text/javascript">
  $('#quickaddcart').on('click', function(){
    buttont = $(this);
    $.ajax({
        url : 'index.php?route=account/cd/buycategory',
        data: $('.hover-content input[name*=\'products\']:checked, .product-thumb input[type=\'number\'], #category-form input[name=\'category_id\']'),
        type: 'post',
        dataType: 'json',
		beforeSend: function() {
			$(buttont).button('loading');
		},
		complete: function() {
			$(buttont).button('reset');
		},
		success: function(json) {
			setTimeout(function () {
				$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
			}, 100);
			$('#cart > ul').load('index.php?route=common/cart/info ul li');
		}
		
      });
  });
  $('.updatecategory').on('click', function(){
	buttont = $(this);
    $.ajax({
        url : 'index.php?route=account/cdpe/updateproducts',
        data: $('input[name*=\'products\']'),
        type: 'post',
		beforeSend: function() {
			$(buttont).button('loading');
		},
		complete: function() {
			$(buttont).button('reset');
		},
		success: function(json) {
		
		}
		
      });
  });  
  $('#category-view').click(function(){
  	$('#category-d').slideToggle("fast");
  });
</script>
	<script type="text/javascript">
  $('.img-thumbnail').on('click',function(){
	$('#form-upload').remove();
	
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');
	
	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);		
			var formdata = new FormData($('#form-upload')[0]);
			formdata.append('category_id','<?php echo $category['category_id']; ?>');
			$.ajax({
				url: 'index.php?route=account/cd/upload',
				type: 'post',		
				dataType: 'json',
				data: formdata,
				cache: false,
				contentType: false,
				processData: false,		
				beforeSend: function() {
					$('#button-upload').button('loading');
				},
				complete: function() {
					$('#button-upload').button('reset');
				},	
				success: function(json) {
					if (json['error']) {
						alert(json['error']);
					}
								
					if (json['success']) {
						$('input[name=\'category_image\']').val(json['mask']);
						$('.img-thumbnail > img').prop('src',json['filename']);
					}
				},			
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
 		function addGpGrouped(child_id) {
			if ($('#quantity' + child_id).val() < 1) {
				$('#quantity' + child_id).val('1');
			}

			$.ajax({
				url: 'index.php?route=checkout/cart/add',
				type: 'post',
				data: $('#product' + child_id + ', #quantity' + child_id + ', #options-' + child_id + ' input[type=\'text\'], #options-' + child_id + ' input[type=\'hidden\'], #options-' + child_id + ' input[type=\'radio\']:checked, #options-' + child_id + ' input[type=\'checkbox\']:checked, #options-' + child_id + ' select, #options-' + child_id + ' textarea'),
				dataType: 'json',
				beforeSend: function() {
					$('#button-cart').button('loading');
				},
				complete: function() {
					$('#button-cart').button('reset');
				},
				success: function(json) {
					$('.alert, .text-danger').remove();
					$('.form-group').removeClass('has-error');

					if (json['error']) {
						if (json['error']['option']) {
							for (i in json['error']['option']) {
								var element = $('#input-option' + i.replace('_', '-'));

								if (element.parent().hasClass('input-group')) {
									element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
								} else {
									element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
								}
							}
						}

						if (json['error']['recurring']) {
							$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
						}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			}
			
			if (json['success']) {
				$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				$('#cart-total').html(json['total']);
				
				$('html, body').animate({ scrollTop: 0 }, 'slow');
				
				$('#cart > ul').load('index.php?route=common/cart/info ul li');
			}
		}
	});
}
</script>
<style>
.gp-table .gp-col-btn{width: 60px;}
.gp-table .gp-col-image{width:70px;padding:0;}
</style>
</div><?php echo $footer; ?>
