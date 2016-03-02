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
    <div class="row">
					<div class="col-sm-6">
						<h2><?php echo $category['name']; ?></h2>
						<div style="width: 110px; height: 100px; overflow: hidden"
							class="img-thumbnail" data-toggle="tooltip"
							title="Click to change the image">
							<img src="<?php echo $thumb; ?>" alt="" class="click-file"
								id="popover" data-toggle="popover" data-trigger="hover" />
						</div>
					</div>
				</div>
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
									class="hidden-xs hidden-sm hidden-md">Update Category</span>
							</button>
							<button type="button" id="delete" class="btn btn-default"
								data-toggle="tooltip" title="Delete">
								<i class="fa fa-trash-o"></i>&nbsp;Delete
							</button>
							<button type="button" id="quickaddcart" class="btn btn-default"
								data-toggle="tooltip" title="Quick Add Products">
								<i class="fa fa-shopping-cart"></i>&nbsp;Quick Cart
							</button>
						</div>
					</div>
				</div>
				<br />
				<div class="row" id="category-d" style="display: none;">
					<form id="category-form">
						<input type="hidden" name="avatarremove" value="0" /> <input
							type="hidden" name="category_image"
							value="<?php echo $category['image']; ?>" /> <input type="hidden"
							name="category_id"
							value="<?php echo $category['category_id']; ?>" />
					</form>
				</div>
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
								<?php if ($dbe) { ?>
									<td rowspan="2" class="gp-col-checkbox">
										<input type="checkbox" name="products[]" id="pcd-00" value="<?php echo $child['child_id']; ?>" onclick="$('input[name*=\'products\']').prop('checked', this.checked);" />
										<label for="pcd-00"></label>
									</td>
								<?php } ?>
								
								<td >Image</td>
								<td id="gp-toggle-info">Product Name</td>
								<td class="text-right">Price</td>
								<?php if ($column_gp_option) { ?>
								<td><?php echo $column_gp_option; ?></td>
								<?php } ?>
								<td><?php echo $column_gp_qty; ?></td>
								<td class="text-center"></td>
								<td class="text-center">Stock Status</td>
								<td class="text-center">Stock Status/Quantity Baught</td>
							</tr>
						</thead>
						<tbody class="products-list">
							<?php foreach ($products as $child) { $child_id = $child['product_id']; ?>
									<tr data-gp-child-row="<?php echo $child_id; ?>" class="product-thumb">
										<?php if ($dbe) { ?>
											<td class="gp-col-checkbox hover-content"><input class="removeproduct" type="checkbox"
														id="pcd-<?php echo $child['product_id']; ?>"
														name="products[<?php echo $child['product_id']; ?>][product_id]"
														value="<?php echo $child['product_id']; ?>" />
														<label for="pcd-<?php echo $child['product_id']; ?>"></label>
											</td>
										<?php } ?>
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
										<?php if ($column_gp_option) { ?>
										<td class="gp-col-option" id="options-<?php echo $child_id; ?>"><?php foreach ($child['options'] as $option) { ?>
											<?php if ($option['type'] == 'select') { ?>
											<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
												<label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
												<select	name="option[<?php echo $option['product_option_id']; ?>]"	id="input-option<?php echo $option['product_option_id']; ?>" class="form-control">
													<option value=""><?php echo $text_select; ?></option>
													<?php foreach ($option['product_option_value'] as $option_value) { ?>
													<option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
														<?php if ($option_value['price']) { ?>
														(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
														<?php } ?>
													</option>
													<?php } ?>
												</select>
											</div>
											<?php } ?>
											<?php if ($option['type'] == 'radio') { ?>
											<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
												<label class="control-label"><?php echo $option['name']; ?></label>
												<div id="input-option<?php echo $option['product_option_id']; ?>">
													<?php foreach ($option['product_option_value'] as $option_value) { ?>
													<div class="radio">
														<label> 
															<?php if ($option_value['enabled']) { ?>
															<input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
															<?php } else {?>
															<input type="radio" name="" value="" disabled/>
															<?php } ?>
															<?php echo $option_value['name']; ?>
															<?php if ($option_value['price']) { ?>
															(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
															<?php } ?>
														</label>
													</div>
													<?php } ?>
												</div>
											</div>
											<?php } ?>
											<?php if ($option['type'] == 'checkbox') { ?>
											<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
												<label class="control-label"><?php echo $option['name']; ?></label>
												<div id="input-option<?php echo $option['product_option_id']; ?>">
													<?php foreach ($option['product_option_value'] as $option_value) { ?>
													<div class="checkbox">
														<label>
															<?php if ($option_value['enabled']) { ?>
															<input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" />
															<?php } else { ?>
															<input type="checkbox" name="" value="" disabled/>
															<?php } ?>
															<?php echo $option_value['name']; ?>
															<?php if ($option_value['price']) { ?>
															(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
															<?php } ?>
														</label>
													</div>
													<?php } ?>
												</div>
											</div>
											<?php } ?>
											<?php if ($option['type'] == 'image') { ?>
											<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
												<label class="control-label"><?php echo $option['name']; ?></label>
												<div id="input-option<?php echo $option['product_option_id']; ?>" class="dropdown fs-dropdown">
													<div class="input-group">
														<div class="input-group-addon" id="fs-dropdown-selected<?php echo $option['product_option_id']; ?>"><?php echo $text_select; ?></div>
														<div class="input-group-btn">
															<button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
																<span class="caret"></span>
															</button>
															<ul class="dropdown-menu">
																<?php foreach ($option['product_option_value'] as $option_value) { ?>
																<li>
																	<label	onclick="fsDropdown($(this), 'fs-dropdown-selected<?php echo $option['product_option_id']; ?>');">
																		<input type="radio"	name="option[<?php echo $option['product_option_id']; ?>]"	value="<?php echo $option_value['product_option_value_id']; ?>"	style="display: none; width: 0;" /> 
																		<img src="<?php echo $option_value['image']; ?>"	alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" /> <?php echo $option_value['name']; ?>
																		<?php if ($option_value['price']) { ?>
																		(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
																		<?php } ?>
																	</label>
																</li>
																<?php } ?>
															</ul>
														</div>
													</div>
												</div>
											</div>
											<?php } ?>
											<?php if ($option['type'] == 'text') { ?>
											<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
												<label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
												<input type="text"	name="option[<?php echo $option['product_option_id']; ?>]"	value="<?php echo $option['value']; ?>"	placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>"	class="form-control" />
											</div>
											<?php } ?>
											<?php if ($option['type'] == 'textarea') { ?>
											<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
												<label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
												<textarea name="option[<?php echo $option['product_option_id']; ?>]" rows="5" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control"><?php echo $option['value']; ?></textarea>
											</div>
										<?php } ?>
										<?php if ($option['type'] == 'file') { ?>
										<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
											<label class="control-label"><?php echo $option['name']; ?></label>
											<button type="button" id="button-upload<?php echo $option['product_option_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default btn-block">
												<i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
												<input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" id="input-option<?php echo $option['product_option_id']; ?>" />
											</div>
											<?php } ?>
											<?php if ($option['type'] == 'date') { ?>
											<div
											class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
											<label class="control-label"
											for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
											<div class="input-group date">
												<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>"	data-format="YYYY-MM-DD" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
												<span class="input-group-btn">
													<button class="btn btn-default" type="button">
														<i class="fa fa-calendar"></i>
													</button>
												</span>
											</div>
										</div>
										<?php } ?>
										<?php if ($option['type'] == 'datetime') { ?>
										<div
										class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
										<label class="control-label"
										for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
										<div class="input-group datetime">
											<input type="text"	name="option[<?php echo $option['product_option_id']; ?>]"	value="<?php echo $option['value']; ?>"	data-format="YYYY-MM-DD HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" /> 
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<i class="fa fa-calendar"></i>
												</button>
											</span>
										</div>
									</div>
									<?php } ?>
									<?php if ($option['type'] == 'time') { ?>
									<div
									class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
									<label class="control-label"
									for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
									<div class="input-group time">
										<input type="text" name="option[<?php echo $option['product_option_id']; ?>]"	value="<?php echo $option['value']; ?>" data-format="HH:mm"	id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">
												<i class="fa fa-calendar"></i>
											</button>
										</span>
									</div>
								</div>
								<?php } ?>
								<?php } ?></td>
								<?php } ?>
								<td class="gp-col-qty">
									<?php if ($child['nocart']) { ?>
									<input name="" type="text" value="" class="form-control" disabled="disabled" title="<?php echo $text_gp_no_stock; ?>" />
									<?php } else { ?>
									<input name="products[<?php echo $child['product_id']; ?>][quantity]" id="quantity<?php echo $child_id; ?>" type="text" value="<?php echo $child['quantity']; ?>" class="form-control" />
									<?php } ?>
								</td>
								<td class="gp-col-btn">
									<input type="hidden" name="product_id" id="product<?php echo $child_id; ?>" value="<?php echo $child_id; ?>" />
									<input type="hidden" name="vendor_id" id="vendor<?php echo $child_id; ?>" value="<?php echo $child['curr_vendor']['customer_id']; ?>" />
									<div class="btn-toolbar" role="toolbar" aria-label="...">
										<div class="btn-group justified" role="group" aria-label="...">
											<button type="button"	data-loading-text="<?php echo $text_loading; ?>" <?php if ($child['enabled']) { ?> onclick="addGpGrouped('<?php echo $child_id; ?>');" <?php } else { echo "disabled=\"disabled\""; } ?> class="btn btn-primary"><?php echo $button_add_to_cart; ?></button>
										</div>
									</div>
								</td>
								<td class="gp-col-stock">
									<?php echo $child['stock']; ?>
								</td>
								<td  class="gp-col-contract">
									<?php $complete = ((float)$child['purchase']['quantity']*100/$child['contract_quantity']); ?>									
										<div class="progress">
											<div class="progress-bar progress-bar-success" style="width: <?php echo $complete;?>%">
												<?php echo $child['purchase']['quantity'];?>
											</div>
											<div class="progress-bar progress-bar-warning progress-bar-striped" style="width: <?php echo (100-$complete);?>%">
											</div>
										</div>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>				
				</div>
				
				
				
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
        url : 'index.php?route=account/cdp/updatecategory',
        data: $('#category-form input,.products-list input[name*=\'products\']'),
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
  $('#delete').on('click', function(){
	buttont = $(this);
	if (confirm("Are you sure you want to delete selected products")){
	    $.ajax({
	        url : 'index.php?route=account/cd/removeproducts',
	        data: $('.product-thumb .hover-content input[type=\'checkbox\']:checked'),
	        type: 'post',
			beforeSend: function() {
				$(buttont).button('loading');
			},
			complete: function() {
				$(buttont).button('reset');
			},
			success: function(json) {
				if (json['success']) {
					location.reload();
				}
			}
	      });
      } else {
		$('input.removeproduct').prop('checked',false);
      }
  });
  
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
.gp-table .gp-col-stock{width:100px;}
.gp-table .gp-col-contract{width:150px;}
</style>
</div><?php echo $footer; ?>
