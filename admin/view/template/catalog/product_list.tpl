<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<a href="<?php echo $add; ?>" data-toggle="tooltip"
					title="<?php echo $button_add; ?>" class="btn btn-primary"><i
					class="fa fa-plus"></i></a>
				<button type="button" data-toggle="tooltip"
					title="<?php echo $button_enable; ?>" class="btn btn-success"
					onclick="$('#form-product').attr('action', '<?php echo $enable; ?>').submit()">
					<i class="fa fa-check"></i>
				</button>
				<button type="button" data-toggle="tooltip"
					title="<?php echo $button_disable; ?>" class="btn btn-warning"
					onclick="$('#form-product').attr('action', '<?php echo $disable; ?>').submit()">
					<i class="fa fa-ban"></i>
				</button>
				<button type="button" data-toggle="tooltip"
					title="<?php echo $button_copy; ?>" class="btn btn-default"
					onclick="$('#form-product').attr('action', '<?php echo $copy; ?>').submit()">
					<i class="fa fa-copy"></i>
				</button>
				<button type="button" data-toggle="tooltip"
					title="<?php echo $button_delete; ?>" class="btn btn-danger"
					onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;">
					<i class="fa fa-trash-o"></i>
				</button>
			</div>
			<div class="pull-right">
				<a href="<?php echo $add_gp_grouped; ?>" data-toggle="tooltip"
					title="<?php echo $button_add; ?>" class="btn btn-primary"><i
					class="fa fa-plus"></i> Grouped</a>&nbsp;
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
    <div class="alert alert-danger">
			<i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success">
			<i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
    <?php } ?>
    <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
        <?php if (isset($filter_gpt) && $filter_gpt == 'gp_grouped') { ?>
        - <a
					href="index.php?route=catalog/product&token=<?php echo $token; ?>"
					style="text-decoration: underline; font-weight: bold;">Grouped</a>
        <?php } else { ?>
        - <a
					href="index.php?route=catalog/product&token=<?php echo $token; ?>&filter_gpt=gp_grouped">Grouped</a>
        <?php } ?>        
        <?php if (isset($filter_gpt)) { ?>
					<input type="hidden" name="filter_gpt"
					value="<?php echo $filter_gpt; ?>" />
				<?php } else { ?>
					<input type="hidden" name="filter_gpt" value="" />
				<?php } ?>
      </div>
			<div class="panel-body">
				<div class="well">
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
								<input type="text" name="filter_name"
									value="<?php echo $filter_name; ?>"
									placeholder="<?php echo $entry_name; ?>" id="input-name"
									class="form-control" />
							</div>
							<div class="form-group">
								<label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
								<input type="text" name="filter_model"
									value="<?php echo $filter_model; ?>"
									placeholder="<?php echo $entry_model; ?>" id="input-model"
									class="form-control" />
							</div>
							<div class="form-group">
								<label class="control-label" for="input-tax-class"><?php echo "Tax class"; ?></label>
								<select name="tax_class_id" id="input-tax-class"
									class="form-control">
									<option value="0"><?php echo $text_none; ?></option>
                    <?php foreach ($tax_classes as $tax_class) { ?>
                    <?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
                    <option
										value="<?php echo $tax_class['tax_class_id']; ?>"
										selected="selected"><?php echo $tax_class['title']; ?></option>
                    <?php } else { ?>
                    <option
										value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-price"><?php echo $entry_price; ?></label>
								<input type="text" name="filter_price"
									value="<?php echo $filter_price; ?>"
									placeholder="<?php echo $entry_price; ?>" id="input-price"
									class="form-control" />
							</div>
							<div class="form-group">
								<label class="control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
								<input type="text" name="filter_quantity"
									value="<?php echo $filter_quantity; ?>"
									placeholder="<?php echo $entry_quantity; ?>"
									id="input-quantity" class="form-control" />
							</div>
							<div class="form-group">
								<label class="control-label" for="input-supplier"><?php echo $entry_supplier; ?></label>
								<input type="text" name="filter_supplier"
									value="<?php echo $filter_supplier; ?>"
									placeholder="<?php echo $entry_supplier; ?>"
									id="input-supplier" class="form-control" />
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
								<select name="filter_status" id="input-status"
									class="form-control">
									<option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
							</div>
							<div class="form-group">
								<label class="control-label" for="input-brand"><?php echo "Brands"; ?></label>
								<input type="text" name="filter_brand"
									value="<?php echo $filter_brand; ?>"
									placeholder="<?php echo "Brand"; ?>" id="input-brand"
									class="form-control" />
							</div>
							<button type="button" id="button-filter"
								class="btn btn-primary pull-right">
								<i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
						</div>
					</div>
				</div>
				<!-- Action -->
				<div class="well">
					<div class="row">
						<div class="col-sm-4">
							<button type="button" class="btn btn-primary"
								value="get_attributes" data-toggle="modal"
								data-target="#get_attributes">Get Attributes</button>
						</div>
						<div class="col-sm-4">
							<select id="getTaxClass" name="tax_class_id" class="form-control">
								<option value="0"><?php echo $text_none; ?></option>
	                    <?php foreach ($tax_classes as $tax_class) { ?>
	                    <?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
	                    <option
									value="<?php echo $tax_class['tax_class_id']; ?>"
									selected="selected"><?php echo $tax_class['title']; ?></option>
	                    <?php } else { ?>
	                    <option
									value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
	                    <?php } ?>
	                    <?php } ?>
      				</select>
						</div>
						<div class="col-sm-4">
							<a class="btn btn-default" id="update">Action</a>
						</div>
					</div>
				</div>
				<form action="<?php echo $delete; ?>" method="post"
					enctype="multipart/form-data" id="form-product">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td style="width: 1px;" class="text-center"><input
										type="checkbox"
										onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
									<td class="text-center"><?php echo $column_image; ?></td>
									<td class="text-left"><?php if ($sort == 'pd.name') { ?>
                    <a href="<?php echo $sort_name; ?>"
										class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
									<td class="text-left"><?php if ($sort == 'p.model') { ?>
                    <a href="<?php echo $sort_model; ?>"
										class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                    <?php } ?></td>
									<td class="text-right"><?php if ($sort == 'p.price') { ?>
                    <a href="<?php echo $sort_price; ?>"
										class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                    <?php } ?></td>
									<td class="text-right"><?php if ($sort == 'p.quantity') { ?>
                    <a href="<?php echo $sort_quantity; ?>"
										class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                    <?php } ?></td>
									<td class="text-left"><?php if ($sort == 'p.status') { ?>
                    <a href="<?php echo $sort_status; ?>"
										class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
									<td class="text-right"><?php echo $column_action; ?></td>
								</tr>
							</thead>
							<tbody>
                <?php if ($products) { ?>
                <?php foreach ($products as $product) { ?>
                <tr>
									<td class="text-center"><?php if (in_array($product['product_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]"
										value="<?php echo $product['product_id']; ?>"
										checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]"
										value="<?php echo $product['product_id']; ?>" />
                    <?php } ?></td>
									<td class="text-center"><?php if ($product['image']) { ?>
                    <img src="<?php echo $product['image']; ?>"
										alt="<?php echo $product['name']; ?>" class="img-thumbnail" />
                    <?php } else { ?>
                    <span class="img-thumbnail list"><i
											class="fa fa-camera fa-2x"></i></span>
                    <?php } ?></td>
									<td class="text-left"><?php echo $product['name']; ?></td>
									<td class="text-left"><?php echo $product['model']; ?></td>
									<td class="text-right"><?php if ($product['special']) { ?>
                    <span style="text-decoration: line-through;"><input
											type="text" name="product_price"
											value="<?php echo $product['price']; ?>"
											placeholder="product_price" id="product_price"
											class="form-control" /> </span><br />
										<div class="text-danger">
											<input type="text" name="product_price"
												value="<?php echo $product['special']; ?>"
												placeholder="product_price" id="product_price"
												class="form-control" />
										</div>
                    <?php } else { ?>
                    <?php echo $product['price']; ?>
                    <?php } ?></td>
									<td class="text-right"><?php if ($product['quantity'] <= 0) { ?>
                    <span class="label label-warning"><?php echo $product['quantity']; ?></span>
                    <?php } elseif ($product['quantity'] <= 5) { ?>
                    <span class="label label-danger"><?php echo $product['quantity']; ?></span>
                    <?php } else { ?>
                    <span class="label label-success"><?php echo $product['quantity']; ?></span>
                    <?php } ?></td>
									<td class="text-left"><?php echo $product['status']; ?></td>
									<td class="text-right"><a
										href="<?php echo $product['edit']; ?>" data-toggle="tooltip"
										title="<?php echo $button_edit; ?>" class="btn btn-primary"><i
											class="fa fa-pencil"></i></a></td>
								</tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
									<td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
								</tr>
                <?php } ?>
              </tbody>
						</table>
					</div>
				</form>

				<!--get attributes-->
				<div class="modal fade" id="get_attributes" role="dialog">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header"></div>
							<div class="modal-body"></div>
							<div class="modal-footer">
								<div class="pull-right">
									<button id="productUpdates" onclick="productUpdates();"
										class="btn btn-primary">
										<i class="fa fa-save"></i>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
					<div class="col-sm-6 text-right"><?php echo $results; ?></div>
				</div>
			</div>
		</div>
	</div>

<script type="text/javascript">
$('#get_attributes').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var modal = $('#get_attributes');
  	$.ajax({
		url: 'index.php?route=catalog/product/getproductUpdates&token=<?php echo $token; ?>',
		type: 'post',
		data: $('input[name^=\'selected\']:checked'),
		success: function(data) {
		     modal.find('.modal-body').html(data);
		  } 
	});
});
</script>

	<script type="text/javascript">
$('#update').on('click',function () {
   	$.ajax({
		url: 'index.php?route=catalog/product/productUpdates&token=<?php echo $token; ?>',
		type: 'post',
		data: $('select#getTaxClass,input[name^=\'selected\']:checked'),
		dataType: 'json',
		success: function(data) {
			
		}
	});
});

$('#productUpdates').on('click',function(){
	 alert("successfully modified");
});
</script>

	<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	var url = 'index.php?route=catalog/product&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	var filter_gpt = $('input[name=\'filter_gpt\']').val();

	if (filter_name) {
		url += '&filter_gpt=' + encodeURIComponent(filter_gpt);
	}

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_model = $('input[name=\'filter_model\']').val();

	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}
     
    var tax_class_id = $('select[name=\'tax_class_id\']').val();

	if (tax_class_id != '0') {
		url += '&tax_class_id=' + encodeURIComponent(tax_class_id);
	}
    
	var filter_price = $('input[name=\'filter_price\']').val();

	if (filter_price) {
		url += '&filter_price=' + encodeURIComponent(filter_price);
	}

	var filter_quantity = $('input[name=\'filter_quantity\']').val();

	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}
    
    var filter_supplier = $('input[name=\'filter_supplier\']').val();

	if (filter_supplier) {
		url += '&filter_supplier=' + encodeURIComponent(filter_supplier);
	}
    
	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
    
    var filter_brand = $('input[name=\'filter_brand\']').val();

	if (filter_brand) {
		url += '&filter_brand=' + encodeURIComponent(filter_brand);
	}
        
	location = url;
});
//--></script>
	<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}
});

$('input[name=\'filter_model\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['model'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_model\']').val(item['label']);
	}
});

$('input[name=\'filter_brand\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['manufacturer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_brand\']').val(item['label']);
	}
});


$('input[name=\'filter_supplier\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
		    url: 'index.php?route=customerpartner/partner/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label:  item['name'],
						value:  item['id']
				 	}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_supplier\']').val(item['value']);
	}
});//--></script>
</div>
<?php echo $footer; ?>
