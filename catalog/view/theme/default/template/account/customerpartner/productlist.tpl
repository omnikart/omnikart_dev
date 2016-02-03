<?php echo $header; ?><div id="columns">
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"> </i> <?php echo $success; ?></div>
  <?php } ?>
  
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>

  <div id="content" class="<?php echo $class; ?>">
    <?php echo $content_top; ?>    
    <h1>
      <?php echo $heading_title; ?>
	  	<div class="pull-right">
	        <?php if($list || (($allowedAddEdit) && $mp_ap)) { ?>
						<a href="<?php echo $insert; ?>"  data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add</a>
	        <?php } ?>
	       	<a href = "index.php?route=account/customerpartner/excelport/ajaxgenerate" class="btn btn-primary">
	       		<span data-toggle="tooltip" title="Excel Port Products Download product database in Excel format, Edit is and upload the updated workbook">
	        		<i class="fa fa-download"></i> Download Products</span>
	        </a>
	        
	        <button class="btn btn-primary" id="button-upload">
	       		<span data-toggle="tooltip" title="Excel Port Products Download product database in Excel format, Edit is and upload the updated workbook">
	        		<i class="fa fa-upload"></i> Upload Products</span>
	        </button>
	        
		<button data-toggle="tooltip" class="btn btn-primary" id="updateProducts"  title="Update changes for selected products. Page will not refresh after this."><i class="fa fa-save"></i>  Save Changes</button>
			<button data-toggle="tooltip" class="btn btn-info" id="disableProducts"  title="Disable Current Changes"><i class="fa fa-times"></i> Disable</button>
	        <a onclick="$('#form-product').submit();" data-toggle="tooltip" class="btn btn-danger"  title="<?php echo $button_delete; ?>"><i class="fa fa-trash-o"></i> Delete</a>
      	</div> 
    </h1>

    <fieldset>
      <legend><i class="fa fa-list"></i> <?php echo $heading_title; ?></legend>
      <?php if($isMember) { ?>
      <div class="well">
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label class="control-label" for="input-name"><?php echo $column_name; ?></label>
              <div class='input-group'>                
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $column_name; ?>" id="input-name" class="form-control" />                       
                <span class="input-group-addon"><span class="fa fa-angle-double-down"></span></span>
              </div>
            </div>            
            <div class="form-group">
              <label class="control-label" for="input-price"><?php echo $column_price; ?></label>
              <input type="text" name="filter_price" value="<?php echo $filter_price; ?>" placeholder="<?php echo $column_price; ?>" id="input-price" class="form-control" />
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              <label class="control-label" for="input-model"><?php echo $column_model; ?></label>
              <div class='input-group'>                
                <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $column_model; ?>" id="input-model" class="form-control" />                                    
                <span class="input-group-addon"><span class="fa fa-angle-double-down"></span></span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label" for="input-model"><?php echo $column_quantity; ?></label>
              <input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" placeholder="<?php echo $column_quantity; ?>" id="input-model" class="form-control" />
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              <label class="control-label" for="input-status"><?php echo $column_status; ?></label>                
              <select name="filter_status" class="form-control" id="input-status">
                <option value="*"></option>
                <?php if ($filter_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } ?>
                <?php if (!is_null($filter_status) && !$filter_status) { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
            <a onclick="filter();" class="btn btn-primary pull-right"><?php echo $button_filter; ?></a> 
          </div>
        </div>
      </div>
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="product-list">
            <thead>
              <tr>
                <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>

                <td class="text-left" style="min-width:170px"><?php if ($sort == 'pd.name') { ?>
                  <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo str_replace(' ', '', $column_name); ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_name; ?>"><?php echo str_replace(' ', '', $column_name); ?></a>
                  <?php } ?></td>
                <td class="text-left" style="min-width:100px"><?php if ($sort == 'p.model') { ?>
                  <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                  <?php } ?></td>
                <td class="text-left" style="min-width:110px" data-toggle="tooltip" data-original-title="Please mention price for one/minimum quantity of product. If you offer quantity based discount please edit the product using the button in an action and edit 'discounts' tab"><?php if ($sort == 'p.price') { ?>
                  <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                  <?php } ?></td>
                <td class="text-right" style="min-width:110px"><?php if ($sort == 'p.quantity') { ?>
                  <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                  <?php } ?></td>
				<td class="text-right" style="min-width:90px" data-toggle="tooltip" data-original-title="Minimum Order Quantity">(MOQ)</td>                  
                <td class="text-right" style="min-width:100px" data-toggle="tooltip" title="" data-original-title="Status shown when a product is out of stock">Availability</td> 
                <td class="text-left" style="min-width:110px"><?php if ($sort == 'p.status') { ?>
                  <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                  <?php } ?></td>

                <td class="text-right" style="min-width:90px"><?php echo $column_sold; ?></td>
                <td class="text-right" style="min-width:70px"><?php echo $column_earned; ?></td>
                <td width="1" class="text-right"><?php echo $column_action; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($products) { ?>
              <?php foreach ($products as $product) { ?>
              <tr class="top-row product-<?php echo $product['product_id']; ?>">
                <td style="text-align: center;"><?php if ($product['selected']) { ?>
                  <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                  <?php } else { ?>
                  <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                  <?php } ?></td>
                <td class="text-left">
                  <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['thumb']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" class="img-thumbnail" />
                  &nbsp;
                  <?php if($product['status']){ ?>
                    <a href="index.php?route=product/product&product_id=<?php echo $product['product_id']; ?>"> <?php echo $product['name']; ?></a>
                  <?php }else{ ?>
                    <?php echo $product['name']; ?>
                  <?php } ?>
                  <input type="hidden" name="products[<?php echo $product['product_id']; ?>][id]" value="<?php echo $product['id']; ?>" />
                  <input type="hidden" name="products[<?php echo $product['product_id']; ?>][product_id]" value="<?php echo $product['product_id']; ?>" />
                  
                </td>
                <td class="text-left"><?php echo $product['model']; ?></td>
                <td class="text-left">
                	<input type="text" class="form-control" name="products[<?php echo $product['product_id']; ?>][price]" value="<?php echo $product['price']; ?>"/>
                </td>
                <td class="text-right">
				  <input type="text" name="products[<?php echo $product['product_id']; ?>][quantity]" class="form-control product-quantity <?php if ($product['quantity'] <= 0) { echo "alert-danger"; } elseif ($product['quantity'] <= 5) { echo "alert-warning"; } else { echo "alert-success"; } ?>" value="<?php echo $product['quantity']; ?>"/>                  
              	</td>
				<td class="text-right">
				  <input type="text" name="products[<?php echo $product['product_id']; ?>][minimum]" class="form-control" value="<?php echo $product['minimum']; ?>"/>                  
              	</td>              	
				<td class="text-right" >
					<select name="products[<?php echo $product['product_id']; ?>][stock_status_id]" id="input-stock-status" class="form-control">
					<?php foreach ($stock_statuses as $stock_status) { ?>
						<?php if ($stock_status['stock_status_id'] == $product['stock_status_id']) { ?>
						<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
						<?php } ?>
					<?php } ?>
					</select>
              	</td>
                <td class="text-left">
                	<select class="form-control" name="products[<?php echo $product['product_id']; ?>][status]">
                		<option value="1" <?php echo $product['status'] ? 'selected' : '' ; ?>>Enabled</option>
                		<option value="0" <?php echo $product['status'] ? '' : 'selected' ; ?>>Disabled</option>
                	</select>
                </td>       
                <td class="text-right">
                  <a <?php if($product['sold']){ ?> href="<?php echo $product['soldlink']; ?>" <?php } ?> style="text-decoration:none;" />                                            
                    <?php if ($product['sold'] <= 0) { ?>
                    <span class="label label-danger"><?php echo $product['sold']; ?></span>
                    <?php } elseif ($product['sold'] <= 5) { ?>
                    <span class="label label-warning" data-toggle="tooltip" title="<?php echo $text_soldlist_info; ?>"><?php echo $product['sold']; ?></span>
                    <?php } else { ?>
                    <span class="label label-success" data-toggle="tooltip" title="<?php echo $text_soldlist_info; ?>"><?php echo $product['sold']; ?></span>
                    <?php } ?></td>
                  </a>
                </td>
                <td class="text-right">
                  <span class="text-success"><?php echo $product['totalearn']; ?></span>
                </td>

                <td class="text-right">
                  <?php if($product['action']){ ?>
                    <?php foreach ($product['action'] as $action) { ?>
                      <a <?php if(!$allowedAddEdit) echo "disabled"; ?> href="<?php echo $action['href']; ?>" class="btn btn-info"><span data-toggle="tooltip" title="<?php echo $action['text']; ?>"><i class="fa fa-pencil"></i></span></a>
                    <?php } ?>
                  <?php } ?>
                </td>
              </tr>
              <?php if (!empty($product['options'])) {?>

              <?php foreach ($product['options'] as $options) { ?>
              	<tr>
              		<td></td>
              		<td colspan="2"><?php echo $options['name']?></td>
	              	<td colspan="1">Price</td>
	              	<td colspan="1">Quantity</td>
	              	<td colspan="7"></td>
	            </tr>
              	<?php foreach ($options['product_option_value'] as $product_option_value) { ?>
	            	<tr>
              			<td></td>
	            		<td colspan="2">
	            			<?php echo $product_option_value['name']; ?>
	            		</td>
	            		<td colspan="1">
	            			<input type="text" name="products[<?php echo $product['product_id']; ?>][options][<?php echo $product_option_value['product_option_value_id']; ?>][price]" class="form-control"  value="<?php echo $product_option_value['price'];?>"/>
	            		</td>
	            		<td colspan="1">
	            			<input type="text" name="products[<?php echo $product['product_id']; ?>][options][<?php echo $product_option_value['product_option_value_id']; ?>][quantity]" data-option-quantity="<?php echo $product['product_id']; ?>"  class="form-control option-quantity" value="<?php echo $product_option_value['quantity'];?>"/>
	            		</td>
	            		<td colspan="1">
	            			<input type="text" name="products[<?php echo $product['product_id']; ?>][options][<?php echo $product_option_value['product_option_value_id']; ?>][sku]" class="form-control" value="<?php echo $product_option_value['sku']; ?>"/>
	            		</td>
	            		<td colspan="6"></td>
	            	</tr>  			
	            <?php } ?>
		            
              <?php } ?>
              	
              <?php } ?>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </form>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <?php } else { ?>
        <div class="text-danger">
          Warning: You are not authorised to view this page, Please contact to site administrator!
        </div>
      <?php } ?>
    </fieldset>

    <?php echo $content_bottom; ?>  
  </div> 
  <?php echo $column_right; ?>
  </div>
</div>  
<div id="progress-dialog" class="modal" data-backdrop="static" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content padding20">
      <div id="progressbar">
        <div class="progress">
          <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
          </div>
        </div>
      </div>
      <div id="progressinfo"></div>
      <button class="btn btn-default finishActionButton" style="display: none;">Abort</button>
    </div>
  </div>
</div>
<script type="text/javascript"><!--

$('#form-product').submit(function(){
    if ($(this).attr('action').indexOf('delete',1) != -1) {
        if (!confirm('<?php echo $text_confirm; ?>')) {
            return false;
        }
    }
});

function filter() {
  url = 'index.php?route=account/customerpartner/productlist';
  
  var filter_name = $('input[name=\'filter_name\']').val();
  
  if (filter_name) {
    url += '&filter_name=' + encodeURIComponent(filter_name);
  }
  
  var filter_model = $('input[name=\'filter_model\']').val();
  
  if (filter_model) {
    url += '&filter_model=' + encodeURIComponent(filter_model);
  }
  
  var filter_price = $('input[name=\'filter_price\']').val();
  
  if (filter_price) {
    url += '&filter_price=' + encodeURIComponent(filter_price);
  }
  
  var filter_quantity = $('input[name=\'filter_quantity\']').val();
  
  if (filter_quantity) {
    url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
  }
  
  var filter_status = $('select[name=\'filter_status\']').val();
  
  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status);
  } 

  location = url;
}
//--></script> 
<script type="text/javascript"><!--
$('.row input').keydown($('#updateProducts').on('click',function(){
	$.ajax({
		url: 'index.php?route=account/customerpartner/productlist/updateProduct',
		type: 'post',
		data: $('#form-product').serialize(),
		dataType: 'json',
		beforeSend: function() {
			$('#button-cart').button('loading');
		},
		complete: function() {
			$('#button-cart').button('reset');
		},
		success: function(json) {
		
		}	
	});
});function(e) {
  if (e.keyCode == 13) {
    filter();
  }
});
//--></script> 
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
  delay: 500,
  source: function(request, response) {
    $.ajax({
      url: 'index.php?route=customerpartner/autocomplete/product&filter_type=customerpartner_&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {   
        response($.map(json, function(item) {
          return {
            label: item.name,
            value: item.product_id
          }
        }));
      }
    });
  }, 
  select: function(item) {
    $('input[name=\'filter_name\']').val(item.label);            
    return false;
  },
  focus: function(item) {
    return false;
  }
});

$('input[name=\'filter_model\']').autocomplete({
  delay: 500,
  source: function(request, response) {
    $.ajax({
      url: 'index.php?route=customerpartner/autocomplete/product&filter_type=customerpartner_&filter_model=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {   
        response($.map(json, function(item) {
          return {
            label: item.model,
            value: item.product_id
          }
        }));
      }
    });
  }, 
  select: function(item) {
    $('input[name=\'filter_model\']').val(item.label);            
    return false;
  },
  focus: function(item) {
    return false;
  }
});

$('#updateProducts').on('click',function(){
	ths = $(this);
	$.ajax({
		url: 'index.php?route=account/customerpartner/productlist/updateProduct',
		type: 'post',
		data: $('#form-product').serialize(),
		dataType: 'json',
		beforeSend: function() {
			$(ths).button('loading');
		},
		complete: function() {
			$(ths).button('reset');
		},
		success: function(json) {
			$('input[type=\'checkbox\']').attr('checked', false);
		}	
	});
});
$('#disableProducts').on('click',function(){
	$.ajax({
		url: 'index.php?route=account/customerpartner/productlist/disableProduct',
		type: 'post',
		data: $('#form-product').serialize(),
		dataType: 'json',
		beforeSend: function() {
			$('#button-cart').button('loading');
		},
		complete: function() {
			$('#button-cart').button('reset');
		},
		success: function(json) {
			$('input[type=\'checkbox\']').attr('checked', false);
		}	
	});
});

<!--
$('#button-upload').on('click', function() {
	$('#form-upload').remove();
	
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');
	
	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}
	
	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);		
			
			$.ajax({
				url: 'index.php?route=account/customerpartner/excelport/ajaximport',
				type: 'post',		
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
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
						alert(json['success']);
						
						$('input[name=\'filename\']').attr('value', json['filename']);
						$('input[name=\'mask\']').attr('value', json['mask']);
					}
					location.reload(); 
				},			
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});

$('.option-quantity').on('change',function(){
	var ths = this.attr('data-option-quantity');
	$('input[data-option-quantity=\''+ths+'\']').each(function (){
		alert(this.val());
	});
});
//-->
//--></script> 
</div><?php echo $footer; ?>
