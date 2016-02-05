<form id="quotation-form">
<div  class="pull-right">
	<select name="enquiry[new]"  class="form-control" >
    	<option value="1" ><?php echo "New"; ?></option>
    	<option value="0"><?php echo "Revision"; ?></option>
	</select>
	<select name="enquiry[revisions]" id="getrevision" class="form-control" >
		<?php foreach ($revisions as $revision) { ?>
	    	<option  id="getrevdata" value="<?php echo $revision['quote_revision_id']; ?>" ><?php echo $revision['quote_revision_id']; ?> (<?php echo $revision['date_added']; ?>)</option>
		<?php } ?>
		<option value="0" ><?php echo "New"; ?></option>
	</select>
</div>
<input type="hidden" name="enquiry[quote_id]"  value="<?php echo $quote_id; ?>"/>
<input type="hidden" name="enquiry[quote_revision_id]"  value="<?php echo $quote_revision_id; ?>"/>
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
			<td >Address</td><td>: <?php echo $config_address; ?></td>
		</tr>
		<tr>
			<td >Postcode</td><td>: <?php echo $postcode; ?></td>
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
					<td class="center" style="max-width:50px;">Sr No.</td>
					<td style="max-width:100px;" data-tooltip="I’m the tooltip text.">Name</td>
					<td class="center" style="max-width:70px;">Quantity</td>
					<td class="center" style="max-width:50px;">Units</td>
					<td class="center" style="max-width:50px;">Unit Price</td>
					<td class="center" style="max-width:50px;">Tax class</td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($enquiries as $key=>$enquiry) { ?>
					<tr>
						<td class="center" rowspan="2"><?php echo $key; ?></td>
						<?php if (isset($enquiry['link'])) { ?>
							<td><a href="<?php echo $enquiry['link']; ?>" > <?php echo $enquiry['name']; ?></a></td>
						<?php } else { ?>
							<td><?php echo $enquiry['name']; ?></td>
						<?php } ?>
						<td class="center">
						<input name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][quantity]" type="text" value="<?php echo $enquiry['quantity']; ?>" class="form-control"/></td>
						<td class="center"><select name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][unit_class_id]" class="form-control">
						 	   <?php foreach ($unit_classes as $unit_class) { ?>
						       <option value="<?php echo $unit_class['unit_class_id']; ?>"><?php echo $unit_class['title']; ?></option>
						       <?php } ?>
						   </select>
						</td>
						<td class="center"><input name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][unit_price]" class="form-control" type="number" value="<?php echo $enquiry['price']; ?>" /></td>
						<td
							class="center>
                   		<label class="control-label" for="input-tax-class"></label>
                   		<select name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][tax_class_id]" id="input-tax-class" class="form-control" >
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
					</tr>
					<tr>
					<td class="center" colspan="2">
					<textarea name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][description]" class="form-control"> <?php echo $enquiry['description']; ?> </textarea></td>
					<td>
					<input type="text" name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][length]" value="<?php echo $enquiry['length']; ?>" placeholder="<?php echo "length"; ?>" class="form-control" />
					<input type="text" name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][height]" value="<?php echo $enquiry['height']; ?>" placeholder="<?php echo "height"; ?>" class="form-control" /></td>
					<td>
					<input type="text" name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][width]" value="<?php echo $enquiry['width']; ?>" placeholder="<?php echo "width"; ?>" class="form-control" />
					<select name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][length_class_id]" value="<?php echo $enquiry['length_class_id']; ?>" id="input-length-class" class="form-control">
	                    <?php foreach ($length_classes as $length_class) { ?>
	                    <?php if ($length_class['length_class_id'] == $enquiry['length_class_id']) { ?>
	                    <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
	                    <?php } else { ?>
	                    <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
	                    <?php } ?>
	                    <?php } ?>	
                  	</select>
					</td>
					<td>
					<input type="text" name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][weight]" value="<?php echo $enquiry['weight'];?>" placeholder="<?php echo "weight"; ?>" class="form-control" />
					<select name="enquiry[product][<?php echo $enquiry['quote_product_id']; ?>][weight_class_id]" id="input-weight-class" value="<?php echo $enquiry['weight_class_id']; ?>" class="form-control">
	                    <?php foreach ($weight_classes as $weight_class) { ?>
	                    <?php if ($weight_class['weight_class_id'] == $enquiry['weight_class_id']) { ?>
	                    <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
	                    <?php } else { ?>
	                    <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
	                    <?php } ?>
	                    <?php } ?>
                  	</select>
					</td>
					</tr>
				<?php } ?>
			</tbody>
			<tbody id="quote-term">  
				<?php $term_count = 0; foreach ($terms as $key => $term) { ?>
					<?php if ('payment'==$term['type']) { ?>
						<tr>
							<td colspan="3" class="right"><?php echo $term['type']; ?>
								<input type="hidden" name="enquiry[oldterm][<?php echo $key; ?>][term_type]" class="form-control" value="<?php echo $term['type']; ?>"/>
							</td>
							<td colspan="2">
							<select class="form-control" name="enquiry[oldterm][<?php echo $key; ?>][term_value]">
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
							<input type="text" name="enquiry[oldterm][<?php echo $key; ?>][term_type]" class="form-control" value="<?php echo $term['type']; ?>"/>
							</td>
							<td colspan="2">
							<textarea name="enquiry[oldterm][<?php echo $key; ?>][term_value]" class="form-control"><?php echo $term['value']; ?></textarea>
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

$('#getrevdata').on('click',function () {
	var enquiry_id = button.attr('data-enquiryId');
	var value
   	$.ajax({
		url: 'index.php?route=sale/enquiry/getEnquiry&token=<?php echo $token; ?>&enquiry_id='+enquiry_id+'&quote_revision_id=<?php echo $quote_revision_id; ?>',
		type: 'post',
		data: $('select#getrevision,input[name^=\'selected\']:value'),
		dataType: 'json',
		success: function(data) {
		}
	});
});

</script>

<script type="text/javascript">
  $('#trigger').on('click',function(){
    $('#save-quotation').trigger('click');
  })
  var term_count = <?php echo $term_count++; ?>;
  function addtermrow(){
  	html = '<tr>';
	html += '<td colspan="3" class="right">';
	html += '<input type="text" name="enquiry[term]['+term_count+'][term_type]" placeholder="Enter Term Name" class="form-control" value=""/>';
	html += '</td>';
	html += '<td colspan="2">';
	html += '<textarea name="enquiry[term]['+term_count+'][term_value]" class="form-control" row="1" placeholder="Enter Term Value" value=""></textarea>';
	html += '</td>';
  	html += '</tr>';
  	$('#quote-term').append(html);
  	$('#quote-term').show(html);
  	term_count++;
  }
</script>
<style>
input[type="radio"] {
  margin-top: -1px;
  vertical-align: middle;}
</style>