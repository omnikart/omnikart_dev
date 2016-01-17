<div id="enquiry-products" class="modal" tabindex="-1"  role="dialog" aria-labelledby="enquiry-products">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Enquiry List</h4>
      </div>
      <div class="modal-body">
		<?php if ($enquiries) { ?>
		<table class="table">
			<thead>
				<tr>
					<td class="center" style="max-width:80px;">Sr.No.</td>
					<td style="max-width:100px;" data-tooltip="I’m the tooltip text.">Name
					</td>
					<td style="min-width:250px;">Description</td>
					<td class="center" style="max-width:100px;">Quantity</td>
					<td class="center" style="max-width:70px;">Unit</td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($enquiries as $key=>$enquiry) { ?>
					<tr>
						<td class="text-center"><?php echo $key; ?></td>
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
						<td class="text-center"><?php echo $enquiry['quantity']; ?></td>
						<td class="text-center"><?php echo $enquiry['unit_class']['title']; ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<div class="col-sm-12" id="collapsePayment">
			<div class="form-group required">
				<label class="col-sm-3 control-label">Payment Terms :</label>
				<div class="col-sm-3">
					<select class="form-control" name="payment_terms">
					<option value="0" selected>- Select Payment Terms -</option>
					<option value="1">COD</option>
					</select>
				</div>
				<label class="col-sm-3 control-label">Postcode :</label>
				<div class="col-sm-3">
					<input type="text" class="form-control" name="postcode" />
				</div>
			</div>
			<div class="form-group required">
				<label class="col-sm-3 control-label">C-Form available :</label>
				<div class="col-sm-9">
					<label class="radio-inline"><input type="radio" name="c_form" value="1">Yes</label>
					<label class="radio-inline"><input type="radio" name="c_form" value="0" checked="checked">No</label>
				</div>
			</div>
		</div>
		<?php } else { ?>
		<div class="">
		  	<h2>No Products</h2>
		  	<p> Please use quotation form to add to enquiry list. </p>
		</div>
		<?php } ?>
		<div class="clearfix"></div>
	  </div>
  	  <div class="modal-footer">
  	  <button type="button" class="btn btn-default" id="submit-enquiry">Submit Enquiry</button>
  	  </div>
   	</div>
  </div>
</div>