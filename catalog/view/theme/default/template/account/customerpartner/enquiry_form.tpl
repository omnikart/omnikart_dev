<form id="quotation-form">
<div class="row">
	<div class="col-sm-12 form-inline">
		<div class="form-group pull-right">
	    	<label for="exampleInputName2">Quotation Versions</label>	
			<select name="enquiry[revisions]" class="form-control input-sm" >
				<?php foreach ($revisions as $revision) { ?>
			    	<option value="<?php echo $revision['quote_revision_id']; ?>" ><?php echo $revision['quote_revision_id']; ?> (<?php echo $revision['date_added']; ?>)</option>
				<?php } ?>
				<option value="0" ><?php echo "New"; ?></option>
			</select>
		</div>	
		<div class="form-group pull-right">
	    	<label for="exampleInputName2">New Quotation or Revision</label>
	    	<select name="enquiry[new]"  class="form-control input-sm" >
		    	<option value="1" ><?php echo "New"; ?></option>
		    	<option value="0"><?php echo "Revision"; ?></option>
			</select>
	  	</div>
	</div><div class="clearfix"></div>
</div>
<input type="hidden" name="enquiry[quote_id]"  value="<?php echo $quote_id; ?>"/>
<input type="hidden" name="enquiry[quote_revision_id]"  value="<?php echo $quote_revision_id; ?>"/>
<input type="hidden" name="enquiry[address_id]"  value="<?php echo $address_id; ?>"/>

<div class="panel panel-default">
<table class="table table-bordered">
	<tbody>
		<tr>
			<td style="width:100px">Name</td><td>: <?php echo $firstname.' '.$lastname; ?></td>
			<td>Store Name</td>
			<td>: <?php echo $config_name; ?></td>
		</tr>
		<tr>
			<td >Email</td><td>: <?php echo $email; ?></td>
			<td >Store Owner</td><td>: <?php echo $config_owner; ?></td>
		</tr>
		<tr>
			<td >Telephone</td><td>: <?php echo $telephone; ?></td>
			
			<td>Address</td><td><?php foreach($addresss as $address) { ?>
			<div class="clearfix quote-address pull-left">
			 		<input type="radio" name="address_id" id="address<?php echo $address['address_id']; ?>" value="<?php echo $address['address_id']; ?>" class="radio" />
					<label for="address<?php echo $address['address_id']; ?>">
						<strong><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?></strong><hr>
						<?php echo $address['address_1']; ?><br />
						 <?php echo $address['city']; ?> <?php echo $address['postcode']; ?><br/>
						<?php echo $address['zone']; ?> <?php echo $address['country']; ?>
					</label>
			  </div>
			<?php } ?>
			
			</td>
		</tr>
		<tr>
			<td >Address</td><td>:
			<?php echo $address_1;?><br />
			<?php echo $city;?><br />
			<?php echo $zone;?><br />
			<?php echo $country;?>
			</td>
			<td >E-Mail</td><td>: <?php echo $config_email; ?></td>	
		</tr>
		<tr>
			<td colspan="2"></td>
			<td>Telephone</td><td>: <?php echo $config_telephone; ?></td>
		</tr>
	</tbody>
</table>
</div>
<div class="panel panel-default">
  <div class="panel-body">
	<table  class="table table-bordered table-hover"   border="1px solid black";>
			<thead>
				<tr>
					<td class="center" style="width:50px;">Sr No.</td>
					<td style="width:200px;">Name</td>
					<td class="center" style="width:100px;">Quantity</td>
					<td class="center" style="width:100px;">Units</td>
					<td class="center" style="width:100px;">Unit Price</td>
					<td class="center" style="width:100px;">Tax class</td>
					<td class="center" style="">Description</td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($enquiries as $key=>$enquiry) { ?>
					<tr>
						<td class="center" rowspan="2"><?php echo $key; ?></td>
						<?php if (isset($enquiry['link'])) { ?>
							<td rowspan="2"><a href="<?php echo $enquiry['link']; ?>" > <?php echo $enquiry['name']; ?></a></td>
						<?php } else { ?>
							<td><?php echo $enquiry['name']; ?></td>
						<?php } ?>
						<td class="center">
							<input name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][quantity]" type="text" value="<?php echo $enquiry['quantity']; ?>" class="form-control input-sm"/>
						</td>
						<td class="center"><select name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][unit_class_id]" class="form-control input-sm">
						 	   <?php foreach ($unit_classes as $unit_class) { ?>
						       <option value="<?php echo $unit_class['unit_class_id']; ?>"><?php echo $unit_class['title']; ?></option>
						       <?php } ?>
						   </select>
						</td>
						<td class="center"><input name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][unit_price]" class="form-control input-sm" type="number" value="<?php echo $enquiry['price']; ?>" /></td>
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
              			<td class="center" colspan="2">
					<textarea rows="1" name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][description]" class="form-control input-sm"> <?php echo $enquiry['description']; ?> </textarea></td>
					</tr>
					<tr>
					<td colspan="3">
					<div class="input-group">
						<input type="text" name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][weight]" value="<?php echo $enquiry['weight'];?>" placeholder="<?php echo "weight"; ?>" class="form-control input-sm" />
						<span class="input-group-addon">.</span>
						<select name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][weight_class_id]" id="input-weight-class" value="<?php echo $enquiry['weight_class_id']; ?>" class="form-control input-sm">
		                    <?php foreach ($weight_classes as $weight_class) { ?>
		                    <?php if ($weight_class['weight_class_id'] == $enquiry['weight_class_id']) { ?>
		                    <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
		                    <?php } else { ?>
		                    <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
		                    <?php } ?>
		                    <?php } ?>
	                  	</select>
	                </div>
					</td>
					<td colspan="2">
						<div class="input-group">
							<input type="text" name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][length]" value="<?php echo $enquiry['length']; ?>" placeholder="<?php echo "length"; ?>" class="form-control input-sm" />
							<span class="input-group-addon">X</span>
							<input type="text" name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][height]" value="<?php echo $enquiry['height']; ?>" placeholder="<?php echo "height"; ?>" class="form-control input-sm" />
							<span class="input-group-addon">X</span>
							<input type="text" name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][width]" value="<?php echo $enquiry['width']; ?>" placeholder="<?php echo "width"; ?>" class="form-control input-sm" />
							<span class="input-group-addon">.</span>
							<select name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][length_class_id]" value="<?php echo $enquiry['length_class_id']; ?>" id="input-length-class" class="form-control input-sm">
			                    <?php foreach ($length_classes as $length_class) { ?>
			                    <?php if ($length_class['length_class_id'] == $enquiry['length_class_id']) { ?>
			                    <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
			                    <?php } else { ?>
			                    <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
			                    <?php } ?>
			                    <?php } ?>	
		                  	</select>
	                  </div>
	                </td>
					</tr>
				<?php } ?>
			</tbody>
			<tbody id="quote-term">  
				<?php $term_count = 0; foreach ($terms as $key => $term) { ?>
					<?php if ('payment'==$term['type']) { ?>
						<tr>
							<td colspan="3" class="right"><?php echo $term['type']; ?>
								<input type="hidden" name="enquiry[oldterm][<?php echo $key; ?>][term_type]" class="form-control input-sm" value="<?php echo $term['type']; ?>"/>
							</td>
							<td colspan="2">
							<select class="form-control input-sm" name="enquiry[oldterm][<?php echo $key; ?>][term_value]">
								<?php foreach($payment_term as $pterm) { ?>
								<option value="<?php echo $pterm['payment_term_id']; ?>" <?php echo ($pterm['payment_term_id']==$term['value']?'selected="selected"':''); ?>  ><?php echo $pterm['name']; ?></option>
								<?php } ?>
							</select>
							</td>
							<td colspan="1">
							<button type="button" class="btn btn-default" onclick="addtermrow();"> add payment term</button>
							</td>
				  		</tr>
				  	<?php } else { ?>
						<tr>
							<td colspan="3" class="right">
							<label style="width: 100px;" >comment</label>
							<input type="text" name="enquiry[oldterm][<?php echo $key; ?>][term_type]" class="form-control input-sm" value="<?php echo $term['type']; ?>"/>
							</td>
							<td colspan="2">
							<textarea name="enquiry[oldterm][<?php echo $key; ?>][term_value]" class="form-control input-sm"><?php echo $term['value']; ?></textarea>
							</td>
						</tr>  		
				<?php } ?>
				<?php $term_count++;}  ?>
			</tbody>
		</table>
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
  $('#trigger').on('click',function(){
    $('#save-quotation').trigger('click');
    return true;
  });
  
  var term_count = <?php echo $term_count++; ?>;
  function addtermrow(){
  	html = '<tr>';
	html += '<td colspan="3" class="right">';
	html += '<input type="text" name="enquiry[term]['+term_count+'][term_type]" placeholder="Enter Term Name" class="form-control input-sm" value=""/>';
	html += '</td>';
	html += '<td colspan="2">';
	html += '<textarea name="enquiry[term]['+term_count+'][term_value]" class="form-control input-sm" row="1" placeholder="Enter Term Value" value=""></textarea>';
	html += '</td>';
  	html += '</tr>';
  	$('#quote-term').append(html);
  	$('#quote-term').show(html);
  	term_count++;
  }
</script>
