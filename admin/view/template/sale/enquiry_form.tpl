<div class="panel panel-default">
<table class="table table-bordered">
	<tbody>
		<tr>
			<td style="width:100px">Name</td><td>: <?php echo $firstname.' '.$lastname; ?></td>
		</tr>
		<tr>
			<td >Email</td><td>: <?php echo $email; ?></td>
		</tr>
		<tr>
			<td >Telephone</td><td>: <?php echo $telephone; ?></td>
		</tr>
		<tr>
			<td >Postcode</td><td>: <?php echo $postcode; ?></td>
		</tr>
		<tr>
			<td colspan="2"></td>
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
					<td style="min-width:250px;">Description</td>
					<td class="center" style="max-width:70px;">Quantity</td>
					<td class="center" style="max-width:50px;">Unit Price</td>
					<td class="center" style="max-width:50px;">Tax class</td>
					<td class="center" style="max-width:50px;">Total Price</td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($enquiries as $key=>$enquiry) { ?>
					<tr>
						<td class="center"><?php echo $key; ?></td>
						<?php if (isset($enquiry['link'])) { ?>
							<td><a href="<?php echo $enquiry['link']; ?>" > <?php echo $enquiry['name']; ?> </a></td>
						<?php } else { ?>
							<td><?php echo $enquiry['name']; ?></td>
						<?php } ?>
						<td><?php echo $enquiry['description']; ?><br />
						<?php foreach ($enquiry['filenames'] as $file) { ?>
							<a href="<?php echo 'system/upload/queries/'.$file; ?>" target="_Blank"><?php echo substr($file,0,strrpos($file,'.',-1)); ?></a>
						<?php } ?>
						</td>
						<td class="center"><?php echo $enquiry['quantity']; ?></td>
						<td class="center"><input type=text value="" /></td>
                   		<td class="center>
                   		 <label class="control-label" for="input-tax-class"></label>
                   		<select name="tax_class_id" id="input-tax-class" class="form-control">
                    		<option value="0"><?php echo $text_none; ?></option>
                    		<?php foreach ($tax_classes as $tax_class) { ?>
                    		<?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
                    		<option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                    		<?php } else { ?>
                    		<option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                    		<?php } ?>
                    		<?php } ?>
              			</select>
              			</td>
              			<td class="center"><input type="text" value="" /></td>
					</tr>
				<?php } ?>
			</tbody>
				<?php foreach ($terms as $term) { ?>
				<tr>
					<td colspan="3" class="right"><?php echo $term['type']; ?></td>
					<td colspan="3"><?php echo $term['value']; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		 <div class="btn-group btn-group-md pull-right">
		  <a href="#" target="_blank">Generate Quotation</a>
		    <button type="button" class="btn btn-primary">Save Draft</button>
 		</div>
  	</div>
</div>
