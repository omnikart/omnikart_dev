<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<a href="<?php echo $add; ?>" data-toggle="tooltip"
					title="<?php echo $button_add; ?>" class="btn btn-primary"><i
					class="fa fa-plus"></i></a>
				<button type="button" data-toggle="tooltip"
					title="<?php echo $button_delete; ?>" class="btn btn-danger"
					onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-postcode').submit() : false;">
					<i class="fa fa-trash-o"></i>
				</button>
			</div>
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) {	 ?>
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
			</div>
			<div class="panel-body">
				<div class="well">
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-code">Code (XXXXXX)</label>
								<input type="text" name="filter_code"
									value="<?php echo $filter_code; ?>" placeholder="Code"
									id="input-code" class="form-control" />
							</div>
							<div class="form-group">
								<label class="control-label" for="input-city">Input City</label>
								<input type="text" name="filter_city"
									value="<?php echo $filter_city; ?>" placeholder="City"
									id="input-city" class="form-control" />
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-zone">Zone</label> <select
									name="filter_zone" id="input-zone" class="form-control">
								</select>
							</div>
							<div class="form-group required">
								<label class="control-label" for="input-country">Country</label>
								<select name="filter_country" id="input-country"
									class="form-control">
	                <?php foreach ($countries as $country) { ?>
	                <?php if ($country['country_id'] == $country_id) { ?>
	                <option value="<?php echo $country['country_id']; ?>"
										selected="selected"><?php echo $country['name']; ?></option>
	                <?php } else { ?>
	                <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
	                <?php } ?>
	                <?php } ?>
              </select>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-status">Status</label> <select
									name="filter_status" id="input-status" class="form-control">
									<option value="*"></option>
									<option value="1"
										<?php echo (($filter_status)?'selected="selected"':'');  ?>>Enabled</option>
									<option value="0"
										<?php echo ((!$filter_status && !is_null($filter_status))?'selected="selected"':'');  ?>>Disabled</option>
								</select>
							</div>
							<button type="button" id="button-filter"
								class="btn btn-primary pull-right">
								<i class="fa fa-search"></i> Filter
							</button>
						</div>
					</div>
				</div>
				<form action="<?php echo $delete; ?>" method="post"
					enctype="multipart/form-data" id="form-postcode">
					<div class="well">
						<div class="row">
							<div class="col-sm-4">
								<select name="action" class="form-control">
									<option value="payment">Payment</option>
									<option value="shipping">Shipping</option>
									<option value="service">Service</option>
								</select>
							</div>
							<div class="col-sm-4">
								<select name="action_value" class="form-control">
									<option value="0">Disable</option>
									<option value="1">Enable</option>
								</select>
							</div>
							<div class="col-sm-4">
								<button type="submit" class="btn btn-default pull-right"
									form="form-postcode" formaction="<?php echo $update; ?>"
									id="update" formmethod="post">Action</button>
							</div>
						</div>
					</div>


					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td style="width: 1px;" class="text-center"><input
										type="checkbox"
										onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
									<td class="text-left"><a href="<?php echo $sort_postcode; ?>"
										class="<?php echo (($sort == 'p.postcode')?strtolower($order):''); ?>"><?php echo $column_name; ?></a>
									</td>
									<td class="text-left"><a href="<?php echo $sort_city; ?>"
										class="<?php echo (($sort == 'c.name')?strtolower($order):''); ?>"><?php echo $column_city; ?></a>
									</td>
									<td class="text-left"><a href="<?php echo $sort_zone; ?>"
										class="<?php echo (($sort == 'z.name')?strtolower($order):''); ?>"><?php echo $column_zone; ?></a>
									</td>
									<td class="text-left"><a href="<?php echo $sort_country; ?>"
										class="<?php echo (($sort == 'ct.name')?strtolower($order):''); ?>"><?php echo $column_country; ?></a>
									</td>
									<td class="text-right"><?php echo $column_payment; ?></td>
									<td class="text-right"><?php echo $column_shipping; ?></td>
									<td class="text-right"><?php echo $column_service; ?></td>
									<td class="text-right"><?php echo $column_status; ?></td>
								</tr>
							</thead>
							<tbody>
		            <?php
														if ($postcodes) {
															?>
			            <?php foreach ($postcodes as $postcode) { ?>
		                <tr>
									<td style="text-align: center;"><input type="checkbox"
										name="selected[]"
										value="<?php echo $postcode['postcode_id']; ?>"
										<?php echo (in_array($postcode['postcode_id'], $selected)?'checked="checked"':''); ?> />
									</td>
									<td class="left"><?php echo $postcode['postcode']; ?></td>
									<td class="left"><?php echo $postcode['city']; ?></td>
									<td class="left"><?php echo $postcode['zone']; ?></td>
									<td class="left"><?php echo $postcode['country']; ?></td>
									<td class="left"><?php echo $postcode['payment']; ?></td>
									<td class="left"><?php echo $postcode['shipping']; ?></td>
									<td class="left"><?php echo $postcode['service']; ?></td>
									<td class="left"><?php echo $postcode['status']; ?></td>
								</tr>
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
			</div>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	var url = 'index.php?route=localisation/postcode&token=<?php echo $token; ?>';

	var filter_city = $('input[name=\'filter_city\']').val();

	if (filter_city) {
		url += '&filter_city=' + encodeURIComponent(filter_city);
	}

	var filter_code = $('input[name=\'filter_code\']').val();

	if (filter_code) {
		url += '&filter_code=' + encodeURIComponent(filter_code);
	}

	var filter_zone = $('select[name=\'filter_zone\']').val();

	if (filter_zone) {
		url += '&filter_zone=' + encodeURIComponent(filter_zone);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	location = url;
});
//--></script>
<script type="text/javascript">
zone_id = <?php echo $zone_id; ?>;
function split( val ) {
	return val.split( /,\s*/ );
	}
function extractLast( term ) {
	return split( term ).pop();
}

$('select[name=\'filter_country\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=localisation/city/autocompletezones&token=<?php echo $token; ?>&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'filter_country\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			
			$('.fa-spin').remove();
		},			
		success: function(json) {
			html = '<option value="">-- Select Zone --</option>';
			
			if (json && json != '') {
				for (i = 0; i < json.length; i++) {
        			html += '<option value="' + json[i]['zone_id'] + '"';
	    			
					if (json[i]['zone_id'] == zone_id) {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json[i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected">-- No zone available --</option>';
			}
			
			
			$('select[name=\'filter_zone\']').html(html);
			/*$('select[name=\'filter_zone\']').data('easySelect').refresh();*/
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
$('select[name=\'filter_country\']').trigger('change');
/*
$(document).ready(function() {
	$('select[name=\'filter_zone\']').easySelect();
});
*/
var filter_zone = ''; 
$('input[name=\'filter_city\']').autocomplete({
	'source': function(request, response) {
		if ($('select[name=\'filter_zone\']').val()) {
			var filter_zone = $('select[name=\'filter_zone\']').val();
		}
		$.ajax({
			url: 'index.php?route=localisation/city/autocompletecity&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(extractLast(request))+'&filter_zone='+filter_zone,
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['city_name'],
						value: item['city_id']
					}
				}));
			}
		});
	},
	'search': function() {
		// custom minLength
		var term = extractLast( this.value );
		if ( term.length < 2 ) {
		return false;
		}
	},
	'focus': function() {
		// prevent value inserted on focus
		return false;
	},
	'select': function(item) {
		var terms = split( this.value );
		// remove the current input
		terms.pop();
		// add the selected item
		terms.push( item.label );
		// add placeholder to get the comma-and-space at the end
		terms.push( "" );
		this.value = terms.join( "," );
		return false;
	}
});
</script>



<?php echo $footer; ?>