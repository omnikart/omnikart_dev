<?php
/*
 * Author: minhdqa
 * Mail: minhdqa@gmail.com 
 */
?>
<?php echo $header;?>
<?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-combo" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
				<h1><?php echo $heading_title; ?></h1>
				<ul class="breadcrumb">
				  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
				  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				  <?php } ?>
				</ul>
		</div>
    </div>
	<div class="container-fluid">
      <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      <?php } ?>
	<?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $add_title; ?></h3>
        </div>
        <div class="panel-body">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-combo" >
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
					<td class="text-left required"><?php echo $entry_combo_name; ?></td>
					<td class="text-left required"><?php echo $entry_product_name; ?></td>
					<td class="text-left"><?php echo $entry_discount; ?></td>
					<td class="text-left"><?php echo $entry_display_position; ?></td>
					<td class="text-left"><?php echo $entry_priority; ?></td>
                </tr>
              </thead>
              <tbody> 
                <tr>
                  <td class="text-left" style="vertical-align: top;">
					<div class="form-group required <?php if ($error_combo_name) {echo 'has-error';}?>">
					<input type="text" name="combo_name" value="<?php echo $combo['combo_name']; ?>" class="form-control" id="combo-name"/>
					<?php if ($error_combo_name) { ?>
						<div class="text-danger"><?php echo $error_combo_name; ?></div>
					<?php } ?>
					</div>
				  </td>
				  <td class="text-left" style="vertical-align: top;">
					<div class="form-group required <?php if ($error_combo_products) {echo 'has-error';}?>">
					  <input type="text" name="combo-product" value="" id="input-product" class="form-control" />
					  <div id="combo-products" class="well well-sm" style="height: 100px; overflow: auto;">
						<?php foreach ($combo['combo_products'] as $combo_products) { ?>
						<div id="combo-products-<?php echo $combo_products['product_id']; ?>"><i style="float: left; line-height: 4; padding-right:2px;" class="fa fa-minus-circle"></i> <?php echo '<div class="combo-search"><div class="combo-search-image"><img class="img-thumbnail" src="'.$combo_products['product_image'].'"></div><div class="combo-search-item"><div class="combo-search-item-name"><strong>'.$combo_products['product_name'].'</strong></div><div class="combo-search-item-model">'.$combo_products['product_model'].'</div><div class="combo-search-item-price">'.$combo_products['product_price'].'</div></div></div>'; ?>
						  <input type="hidden" name="combo_products[]" value="<?php echo $combo_products['product_id']; ?>" />
						</div>
						<?php } ?>
					  </div>
					  <?php if ($error_combo_products) { ?>
						<div class="text-danger"><?php echo $error_combo_products; ?></div>
					  <?php } ?>
					</div>  
				  </td>
				  <td class="text-left" style="vertical-align: top;">
					<div>
						<label class="col-sm-3 control-label"><?php echo $text_discount_type; ?></label>
						<div class="col-sm-9">
							<select name="discount_type" class="form-control" style="width:200px;">
								<option value="fixed amount" 	<?php if($combo['discount_type'] == "fixed amount") echo "selected "; ?>><?php echo $text_fixed; ?></option>
								<option value="percentage" 	<?php if($combo['discount_type'] == "percentage") echo "selected "; ?>><?php echo $text_percent; ?></option>
							</select>
							<p></p>
						</div>
					</div>
					<div class="form-group required <?php if ($error_discount_number) {echo 'has-error';}?>">
						<label class="col-sm-3 control-label"><?php echo $text_discount_numb; ?></label>
						<div class="col-sm-9">
							<input type="text" name="discount_number" value="<?php echo $combo['discount_number']; ?>" id="input-discount" class="form-control" />
							<?php if ($error_discount_number) { ?>
								<div class="text-danger"><?php echo $error_discount_number; ?></div>
							<?php } ?>
						</div>
					</div>
				  </td>
				  <td class="text-left" style="vertical-align: top;">
					<div>
						<input type="checkbox" name="display_detail" value="1" <?php if ($combo['display_detail']) echo "checked=checked"; ?> /> <?php echo $text_detail_page ?>
					</div>
					<div>
					<label class="col-sm-3 control-label"><?php echo $entry_category.": "; ?></label>
					<select multiple name="combo_category[]" class="col-sm-9" size="8">
					<?php foreach ($combo['category_list'] as $category_id => $category_list) {;?>
						<option value="<?php echo $category_id;?>" <?php if (in_array($category_id,$combo['combo_category'])) echo "selected=selected"; ?>><?php echo $category_list; ?></option>
					<?php }?>
					</select>
					</div>
					
				  </td>
				  <td class="text-left">
					<div>
						<input type="text" name="priority" value="<?php echo $combo['priority']; ?>" id="input-priority" class="form-control" style="width:40px;"/>
					</div>
				  </td>
                </tr>
              </tbody>
            </table>
			<div>
				<input type="checkbox" name="override" value="1" <?php if ($combo['override']) echo "checked=checked"; ?> /> <?php echo $text_override ?>
			</div>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript" language="javascript"><!--
$(document).ready(function(){	
	$('input[name=\'combo-product\']').autocomplete({
		delay: 500,
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',			
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name']+' ('+item['model']+')',
							label2: item['name'],
							label1: '<div class="combo-search"><div class="combo-search-image"><img class="img-thumbnail" src="'+item['image']+'"></div><div class="combo-search-item"><div class="combo-search-item-name"><strong>'+item['name']+'</strong></div><div class="combo-search-item-model">'+item['model']+'</div><div class="combo-search-item-price">'+item['price']+'</div></div></div>',
							value: item['product_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			
			$('input[name=\'combo_product\']').val('');
			
			$('#combo-products' + item['value']).remove();
			
			var html = $('<div class="combo-products" id="combo-products-' + item['value'] + '"><i style="float: left; line-height: 4; padding-right:2px;" class="fa fa-minus-circle"></i> ' + item['label1'] + '<input type="hidden" name="combo_products[]" value="' + item['value'] + '" /></div>');
			
			$('#combo-products').append(html);	
		}	
	});
	$('#combo-products').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});
});
//--></script> 