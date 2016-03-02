<form id="quotation-form">
<div class="row">
	<div class="col-sm-12 form-inline">
		<div class="form-group">
			<label class="control-label" for="pick-date">Quote Effective Date</label>
			<div class="input-group date">
				<input type="text" name="enquiry[effective_date]" value="" data-toggle="tooltip"
					title="Click right button to pick date!"
					data-date-format="YYYY-MM-DD" id="pick-date"
					class="form-control " value="<?php echo $effective_date; ?>"/> <span class="input-group-btn">
					<button data-toggle="tooltip"
						title="Click here to pick date!" type="button"
						class="btn btn-default datebutton">
						<i class="fa fa-calendar"></i>
					</button>
				</span>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label" for="pick-date">Quote Expiration Date</label>
			<div class="input-group date">
				<input type="text" name="enquiry[expiration_date]" value="" data-toggle="tooltip"
					title="Click right button to pick date!"
					data-date-format="YYYY-MM-DD" id="pick-date"
					class="form-control" value="<?php echo $expiration_date; ?>"/> <span class="input-group-btn">
					<button data-toggle="tooltip"
						title="Click here to pick date!" type="button"
						class="btn btn-default datebutton">
						<i class="fa fa-calendar"></i>
					</button>
				</span>
			</div>
		</div>	
		<div class="form-group pull-right">
	    	<label for="exampleInputName2">Quotation Versions</label>	
			<select name="enquiry[revisions]" class="form-control input-sm" >
				<?php foreach ($revisions as $revision) { ?>
			    	<option value="<?php echo $revision['quote_revision_id']; ?>" ><?php echo $revision['quote_revision_id']; ?> (<?php echo $revision['date_added']; ?>)</option>
				<?php } ?>
			</select>
		</div>	
	</div><div class="clearfix"></div>
</div>
<input type="hidden" name="enquiry[quote_id]"  value="<?php echo $quote_id; ?>"/>
<input type="hidden" name="enquiry[address_id]"  value="<?php echo $address_id; ?>"/>

<div class="panel panel-default">
<table class="table table-bordered" style="table-layout: fixed;width:100%;">
	<tbody>
		<tr>
			<td style="width:50%;">
				<?php echo $profile['companyname']; ?>
				<?php foreach ($addresss as $address) { ?>
				  <div class="clearfix quote-address">
				 		<input type="radio" name="enquiry[supplier_address_id]" id="address<?php echo $address['address_id']; ?>" value="<?php echo $address['address_id']; ?>" class="radio" <?php echo (($address['address_id'] == $address_id)?'checked':''); ?> />
						<label for="address<?php echo $address['address_id']; ?>">
							<?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?><br />
							<?php echo $address['address_1']; ?> <?php echo $address['city']; ?><br />
							<?php echo $address['zone']; ?> <?php echo $address['country']; ?>
						</label>
				  </div>
				<?php } ?>
				<button type="button" class="btn btn-xs btn-info more-address">More</button>
			</td>
			<td style="width:50%;">
				Customer Details: <br />
				<?php echo $firstname.' '.$lastname; ?><br />
				<?php echo $data['address']['address_1'];?><br />
				<?php echo $data['address']['city'];?><br />
				<?php echo $data['address']['zone'];?><br />
				<?php echo $data['address']['country'];?><br />
				<?php echo $data['customer']['telephone'];?>
				<?php echo $data['customer']['email'];?>
			</td>
		</tr>
	</tbody>
</table>
</div>
<div class="panel panel-default">
  <div class="panel-body">  
  <div class="table-responsive" style="overflow-x: auto;width: 100%;">
  
	<table  class="table"  style="">
			<thead>
				<tr>
					<td style="width:200px;">Name</td>
					<td class="center" style="width:300px;">Description</td>
					<td class="center" style="width:150px;">Quantity</td>
					<td class="center" style="width:100px;">Unit Price</td>
					<td class="center" style="width:100px;">Discount</td>
					<td class="center" style="width:100px;">Tax class</td>
					<td class="center" style="width:100px;">Total</td>

				</tr>
			</thead>
			<tbody class="quote-products">
			<?php if ($enquiries) { ?>
				<?php foreach ($enquiries as $key=>$enquiry) { ?>
					<tr>
						<?php if (isset($enquiry['link'])) { ?>
							<td>
								<a href="<?php echo $enquiry['link']; ?>" > <?php echo $enquiry['name']; ?></a>
							</td>
						<?php } else { ?>
							<td><?php echo $enquiry['name']; ?></td>
						<?php } ?>
						<td><textarea rows="1" name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][description]" class="form-control input-sm"> <?php echo $enquiry['description']; ?> </textarea>
						</td>
						<td class="center">
							<div style="width:50%;float:left;">
							<input name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][quantity]" type="text" value="<?php echo $enquiry['quantity']; ?>" class="form-control input-sm"/>
							</div>
							<div style="width:50%;float:right;">
							<select name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][unit_class_id]" class="form-control input-sm">
						 	   <?php foreach ($unit_classes as $unit_class) { ?>
						       <option value="<?php echo $unit_class['unit_class_id']; ?>"><?php echo $unit_class['title']; ?></option>
						       <?php } ?>
						   </select>
						   </div>
						</td>
						<td class="center"><input name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][unit_price]" class="form-control input-sm" type="number" value="<?php echo $enquiry['price']; ?>" /></td>
              			<td class="center"><input name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][discount]" class="form-control input-sm" type="number" value="<?php echo $enquiry['discount']; ?>" /></td>						
						<td	class="center">
                   		<select name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][tax_class_id]" id="input-tax-class" class="form-control input-sm" >
                    		<option value="0"><?php echo $text_none; ?></option>
                    		<?php foreach ($tax_classes as $tax_class) { ?>
                    		<?php if ($tax_class['tax_class_id'] == $enquiry['tax_class_id']) { ?>
                    		<option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                    		<?php } else { ?>
                    		<option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                    		<?php } ?>
                    		<?php } ?>
              			</select>
              			</td>
              			<td class="center"><input name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][total]" class="form-control input-sm" type="number" value="<?php echo $enquiry['total']; ?>" disabled /></td>
					</tr>

				<?php } ?>
				<tr>
					<td colspan="4" class="nb"></td>
					<td colspan="2" class="btn-cell text-right">Shipping Charges: </td>
					<td><input type="text" name="enquiry[shipping_charge]" value="<?php echo isset($shipping_charge)?$shipping_charge:'0'; ?>" class="form-control input-sm" /></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<table class="table table-bordered" style="table-layout: fixed;width:auto;">
			<tbody id="quote-term">  
				<?php $term_count = 0; foreach ($terms as $key => $term) { ?>
					<?php if ('payment'==$term['type']) { ?>
						<tr>
							<td style="width:200px;" class="fixed-term right"><?php echo $term['type']; ?>
								<input type="hidden" name="enquiry[oldterm][<?php echo $key; ?>][term_type]" class="form-control input-sm" value="<?php echo $term['type']; ?>"/>
							</td>
							<td  style="width:300px;">
							<select class="form-control input-sm" name="enquiry[oldterm][<?php echo $key; ?>][term_value]">
								<?php foreach($payment_term as $pterm) { ?>
								<option value="<?php echo $pterm['payment_term_id']; ?>" <?php echo ($pterm['payment_term_id']==$term['value']?'selected="selected"':''); ?>  ><?php echo $pterm['name']; ?></option>
								<?php } ?>
							</select>
							</td>
				  		</tr>
				  	<?php } else { ?>
						<tr>
							<td  class="right">
							<input type="text" name="enquiry[oldterm][<?php echo $key; ?>][term_type]" class="form-control input-sm" value="<?php echo $term['type']; ?>"/>
							</td>
							<td >
							<textarea name="enquiry[oldterm][<?php echo $key; ?>][term_value]" class="form-control input-sm"><?php echo $term['value']; ?></textarea>
							</td>
						</tr>  		
				<?php } ?>
				<?php $term_count++;}  ?>
			</tbody>
			<tfoot>
				<tr>
					<td class="text-left">
						<button type="button" class="btn btn-default btn-xs btn-block" onclick="addtermrow();"> add payment term</button>
					</td>
				</tr>
			</tfoot>
		</table>
		
		
		</div>
		  <a href="<?php echo $enquiryupdates ;?>" data-enquiryId="<?php echo $enquiry['enquiry_id']; ?>" id="trigger" style="margin-left:660px;" target="_blank">Generate Quotation</a>
		  <a href="javascript:void();" id="save-quotation" class="pull-right">Save Draft</a>
  	</div>
</div>
</form>
<script type="text/javascript">

$('#enquiryModal').on('change','select[name=\'enquiry[revisions]\']',function () {
	var quote_revision_id = $('#enquiryModal select[name=\'enquiry[revisions]\']').val(); 
	var value
	$('#enquiryModal .modal-body').load('index.php?route=sale/enquiry/getEnquiry&enquiry_id='+enquiry_id+'&quote_revision_id='+quote_revision_id);
   	$.ajax({
		url: 'index.php?route=sale/enquiry/getEnquiry&enquiry_id='+enquiry_id+'&quote_revision_id='+quote_revision_id,
		success: function(data) {
		}
	});
});

</script>

<script type="text/javascript">
$('.date').datetimepicker({
	pickTime: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});

$('.time').datetimepicker({
	pickDate: false
});
  $('#trigger').on('click',function(){
    $('#save-quotation').trigger('click');
    return true;
  });
  
  var term_count = <?php echo $term_count++; ?>;
  function addtermrow(){
  	html = '<tr>';
	html += '<td class="right">';
	html += '<input type="text" name="enquiry[term]['+term_count+'][term_type]" placeholder="Enter Term Name" class="form-control input-sm" value=""/>';
	html += '</td>';
	html += '<td >';
	html += '<textarea name="enquiry[term]['+term_count+'][term_value]" class="form-control input-sm" row="1" placeholder="Enter Term Value" value=""></textarea>';
	html += '</td>';
  	html += '</tr>';
  	$('#quote-term').append(html);
  	$('#quote-term').show(html);
  	term_count++;
  }
$('.quote-address > input.radio:not(:checked) ~ label').parent().css('display','none');
</script>
