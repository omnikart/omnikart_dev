<div id="enquiry-products" class="modal" tabindex="-1" role="dialog" aria-labelledby="enquiry-products">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Enquiry List</h4>
			</div>
			<div class="modal-body">
		<?php if ($enquiries) { ?>
		<table id="table" class="table">
					<thead>
						<tr>
							<td class="center" style="max-width: 80px;">Sr.No.</td>
							<td style="max-width: 100px;"
								data-tooltip="I’m the tooltip text.">Name</td>
							<td style="min-width: 250px;">Description</td>
							<td class="center" style="max-width: 100px;">Quantity</td>
							<td class="center" style="max-width: 70px;">Unit</td>
						</tr>
					</thead>
					<tbody>
				<?php $count = 1; foreach ($enquiries as $key=>$enquiry) { ?>
					<tr>
							<td class="text-center"><?php echo $count; ?></td>
						<?php if (isset($enquiry['link'])) { ?>
							<td><a href="<?php echo $enquiry['link']; ?>"> <?php echo $enquiry['name']; ?> </a></td>
						<?php } else { ?>
							<td><?php echo $enquiry['name']; ?></td>
						<?php } ?>
						<td><?php echo $enquiry['description']; ?><br />
						<?php foreach ($enquiry['filenames'] as $file) { ?>
							<a href="<?php echo 'system/upload/queries/'.$file; ?>"
								target="_Blank"><?php echo substr($file,0,strrpos($file,'.',-1)); ?></a>
						<?php } ?>
						</td>
							<td class="text-center"><?php echo $enquiry['quantity']; ?></td>
							<td class="text-center"><?php echo $enquiry['unit_class']['title']; ?></td>
							<td><button id="delete" type="button"
									onclick="deleteRow('<?php echo $key; ?>')"
									class="btn btn-danger delete-enquiry">
									<i class="fa fa-trash-o"></i>
								</button></td>
						</tr>
				<?php $count++; } ?>
			</tbody>
				</table>
				<hr />
				<h4>Payment Terms and User Details</h4>
				<div class="col-sm-12" id="collapsePayment">
					<div class="form-group required">
						<label class="col-sm-2 control-label">Payment Terms :</label>
						<div class="col-sm-2">
							<select class="form-control" name="payment_terms">
								<option value="0" selected>- Select Payment Terms -</option>
					<?php foreach ($payment_terms as $term) { ?>
					<option value="<?php echo $term['payment_term_id']?>"><?php echo $term['name']; ?></option>
					<?php } ?>
					</select>
						</div>
						<label class="col-sm-2 control-label">C-Form available :</label>
						<div class="col-sm-2">
							<label class="radio-inline"><input type="radio" name="c_form"
								value="1">Yes</label> <label class="radio-inline"><input
								type="radio" name="c_form" value="0" checked="checked">No</label>
						</div>
					</div>
				</div>
		<?php } else { ?>
			<div class="">
					<h2>No Products</h2>
					<p>Please use quotation form to add to enquiry list.</p>
				</div>
		<?php } ?>
				<div class="clearfix">
				</div>
			</div>
			<div class="modal-footer">
			<div class="">
	      <?php if ($addresses) { ?>
			  <form>
			 	<h4 style="padding-right: 783px;">Address Book</h4>
			 <?php foreach ($addresses as $address) { ?>
			  <div class="clearfix enquiry-address pull-left">
			 		<input type="radio" name="address_id" id="address<?php echo $address['address_id']; ?>" value="<?php echo $address['address_id']; ?>" class="radio" />
					<label for="address<?php echo $address['address_id']; ?>">
						<strong><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?></strong><hr>
						<?php echo $address['address_1']; ?><br />
						 <?php echo $address['city']; ?> <?php echo $address['postcode']; ?><br/>
						<?php echo $address['zone']; ?> <?php echo $address['country']; ?>
					</label>
			  </div>
				<?php } ?>
			  </form>  
	      	<?php } else { ?>
	      		<p><?php echo "You have no addresses in your account."; ?></p>
	      	<?php } ?>
      		</div>
				<div class="buttons clearfix">
					<a href='<?php echo $add_address; ?>' target="_blank" class="btn btn-primary">ADD ADDRESS</a>
					<button type="button" class="btn btn-default" id="submit-enquiry">Submit Enquiry</button>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript"><!--
function deleteRow(key) {
	$.ajax({
		url : 'index.php?route=module/enquiry/deleteProduct',
		type: 'post',
		data: {'key':key},
	    dataType: "html",
	    success : function (data) {
		    $('.modal').modal('hide');
		     $('#enquiry_modal').modal('show');
		    $('#enquiry-products').remove();
			$('body').append(data);
			$('#enquiry-products').modal('show');
	    }
	}); 	
}
</script>

<script type="text/javascript"><!--
$('select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=account/account/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('input[name=\'postcode\']').parent().parent().addClass('required');
			} else {
				$('input[name=\'postcode\']').parent().parent().removeClass('required');
			}

			html = '<option value=""><?php echo "select" ; ?></option>';

			if (json['zone'] && json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
						html += ' selected="selected"';
			  		}

			  		html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}

			$('select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'country_id\']').trigger('change');
//--></script>

<style>
 #enquiry-products .enquiry-address label{width:100%;padding:10px;border:1px solid #ddd;}
#enquiry-products input.radio:empty {
					margin-left: -999px;
}
 input.radio:empty ~ label {
	position: relative;
	float: left;
	cursor: pointer;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}
input.radio:hover:not(:checked) ~ label:before {
	content:'\2714';
	text-indent: .9em;
	color: #333;
}
input.radio:hover:not(:checked) ~ label {
	color: #31708f;
}
input.radio:checked ~ label:before {
	content:'\2714';
	text-indent: .9em;
	color: #31708f;
}
input.radio:checked ~ label {
	color: #31708f;
	border:1px solid #31708f;
}    
</style>
