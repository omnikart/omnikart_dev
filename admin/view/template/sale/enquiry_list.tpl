<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" id="button-delete" form="form-enquiry" formaction="<?php echo $action; ?>" data-toggle="tooltip" title="Delete Seleted Queries" class="btn btn-danger"><i class="fa fa-times"></i></button>
        <button type="submit" id="button-update" form="form-enquiry" formaction="<?php echo $update; ?>" data-toggle="tooltip" title="Update Seleted Queries" class="btn btn-info"><i class="fa fa-save"></i></button>
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a></div>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
				<?php /*
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                <select name="filter_order_status" id="input-order-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_order_status == '0') { ?>
                  <option value="0" selected="selected"><?php echo $text_missing; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_missing; ?></option>
                  <?php } ?>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $filter_order_status) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
                <input type="text" name="filter_total" value="<?php echo $filter_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-modified"><?php echo $entry_date_modified; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" placeholder="<?php echo $entry_date_modified; ?>" data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
				*/
				?>
        <form method="post" enctype="multipart/form-data" id="form-enquiry">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-right col-sm-2">Name</td>
                  <td class="text-left col-sm-2">Contact Number</td>
                  <td class="text-left">Query Details</td>
                  <td style="width: 1px;" class="text-left"></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($enquiries) { ?>
                <?php foreach ($enquiries as $enquiry) { ?>
                <tr>
                  <td class="text-center"><input type="checkbox" name="selected[]" value="<?php echo $enquiry['id']; ?>"></td>
                  <td class="text-right"><?php echo $enquiry['user_info']['firstname'].' '.$enquiry['user_info']['lastname']; ?></td>
                  <td class="text-right">
                  	<?php echo $enquiry['user_info']['phone']; ?> <br />
                   	<?php echo $enquiry['user_info']['email']; ?> <br />
                   	<?php echo $enquiry['date']; ?>
                  </td>
                  <td>
                   <table class="table">
									<tr><td>Product name</td><td>Quantity</td><td>Specifications</td></tr>
                   <?php foreach ($enquiry['query'] as $query) { ?>
                   <tr>
					 <td class="text-left"><?php echo $query['name']; ?></td>
					 <td class="text-left"><?php echo $query['quantity']; ?></td>
					 <td class="text-left"><?php echo $query['specification']; ?></td>
				   </tr>
			       <?php } ?>
			       </table>
			      </td>
				<td class="text-left">
				  <div class="row">
					<select name="query[<?php echo $enquiry['id']; ?>][status]">
						<option value="1" <?php echo (1==$enquiry['status'])?'selected':''; ?> >Pending</option>
						<option value="2" <?php echo (2==$enquiry['status'])?'selected':''; ?>>Query Confirmed</option>
						<option value="3" <?php echo (3==$enquiry['status'])?'selected':''; ?>>Product Suggested</option>
						<option value="4" <?php echo (4==$enquiry['status'])?'selected':''; ?>>Product Confirmed</option>
						<option value="5" <?php echo (5==$enquiry['status'])?'selected':''; ?>>Voided</option>						
					</select>
				 </div>	
				 <div class="row" style="margin-right: 8px; margin-top: 5px;" >
				  <button type="button" id="" class="btn btn-primary pull-right get-quote" ><i class="fa fa-search">Update</i> </button>
				  <input type="hidden" name="enquiry_id" value="<?php echo $enquiry['id']; ?>" >
				 </div>
				</td>									
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
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php //echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width: 1200px;">

    <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" align="center">Omnikart Quotation</h4>
      </div>
      <div class="modal-body">
        <h1 align="Center">Omnikart Quotation</h1>
        <form class="form-horizontal" id="form-quotation">
        <input type="hidden" name="enquiry_id" value="" />
        <input type="hidden" name="revision_id" value="1" />
        <input type="hidden" name="comment" value="1" />
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr style="height: 10px;">
                  <td align="center" colspan="6">Omnikart Engineering Pvt. Ltd.</td>
                  <td class="text-left" colspan="6">Query Details</td>
                </tr>
              </thead>
              <tbody>
                <tr>  
                  <td class="text-left" colspan="2">Quotation No:</td>
                  <td colspan="10"></td>
                </tr>
                <tr style="color: #0000FF;">  
                  <td colspan="6"><h1>Customer Details</h1></td>
                  <td colspan="6"><h1>Information</h1</td>
                </tr>  
				<tr>
				  <td colspan="6">
				  	<div class="form-group">
				  		<label class="col-sm-4 control-label">Contact Person :</label>
				  		<div class="col-sm-8">
				  			<input type="text" name="user_info[firstname]" value="" id="input-name" class="form-control">
				  		</div>
				  	</div>
				  	<div class="form-group">
				  		<label class="col-sm-4 control-label">Company Name :</label>
				  		<div class="col-sm-8">
				  			<input type="text"  name="user_info[company_name]" value="" id="input-company_name" class="form-control">
				  		</div>
				  	</div>
				  	<div class="form-group">
				  		<label class="col-sm-4 control-label">Company Address :</label>
				  		<div class="col-sm-8">
				  			<textarea type="text"  name="user_info[company_address]" id="input-company_address" class="form-control"></textarea>
				  		</div>
				  	</div>
				  	<div class="form-group">
				  		<label class="col-sm-4 control-label">City</label>
				  		<div class="col-sm-8">
				  			<input type="text"  value="" name="user_info[city]" class="form-control">
				  		</div>
				  	</div>
				  	<div class="form-group">
				  		<label class="col-sm-4 control-label">state</label>
				  		<div class="col-sm-8">
				  			<input type="text"  value="" name="user_info[state]" class="form-control">
				  		</div>
				  		
				  	</div>				  					  	
                    <div class="form-group">
				  		<label class="col-sm-4 control-label">Contact No :</label>
				  		<div class="col-sm-8">
				  			<input type="text"  value="" name="user_info[contact_no]" class="form-control">
				  		</div>
				  	</div>
					<div class="form-group">
				  		<label class="col-sm-4 control-label">Email :</label>
				  		<div class="col-sm-8">
				  			<input type="text"  value="" name="user_info[email]" id="input-email" class="form-control">
				  		</div>
				  	</div>
				  </td>
				  <td colspan="6">
				   
				  	<div class="form-group">
				  		<label class="col-sm-4 control-label">Date :</label>
				  		<div class="col-sm-8">
				  			<div class="input-group date">
					  			<input type="text"  name="date" value="" id="input-date" class="form-control">
					  		    <span class="input-group-btn">
					               <button data-toggle="tooltip" title="Click here to pick date!" type="button" class="btn btn-default datebutton"><i class="fa fa-calendar"></i></button>
				            	</span>
			            	</div>
		            	</div>
	
				  	</div>
				  	<div class="form-group">
				  		<label class="col-sm-4 control-label">Quote Expiration Date :</label>
				  		<div class="col-sm-8">
				  			<div class="input-group date">
					  			<input type="text"  name="quote_expiration_date" value="" id="input-date" class="form-control">
					  		    <span class="input-group-btn">
					               <button data-toggle="tooltip" title="Click here to pick date!" type="button" class="btn btn-default datebutton"><i class="fa fa-calendar"></i></button>
				            	</span>
			            	</div>
				  		</div>
				  	</div>
				  	<div class="form-group">
				  		<label class="col-sm-4 control-label">Omnikart Contact Name :</label>
				  		<div class="col-sm-8">
				  			<input type="text"  name="omnikart_contact_name" value="" id="input-name" class="form-control">
				  		</div>
				  	</div>
				  	<div class="form-group">
				  		<label class="col-sm-4 control-label">Omnikart Contact No :</label>
				  		<div class="col-sm-8">
				  			<input type="text"  name="omnikart_contact_no" value="" id="input-name" class="form-control">
				  		</div>
				  	</div>
				  	<div class="form-group">
				  		<label class="col-sm-4 control-label">Delivery Lead Time :</label>
				  		<div class="col-sm-8">
				  			<input type="text" name="lead_time" value="" id="input-name" class="form-control">
				  		</div>
				  	</div>
				   </td>
				   
				</tr>
			</tbody>
			<tbody id="product-table">	
			    <tr>
			      <td colspan="1">Sr.No</td>
				  <td colspan="3">Description</td>
				  <td colspan="1">MOQ</td>
				  <td colspan="2">UOM Name</td>
				  <td colspan="2">Unit Price	</td>
				  <td colspan="3">Total(INR)</td>
				</tr>
			 </tbody>
			 <tbody style="visibility: hidden;">	
			    <tr>
			      <td class="col-sm-1"></td>
				  <td class="col-sm-1"></td>
				  <td class="col-sm-1"></td>
				  <td class="col-sm-1"></td>
				  <td class="col-sm-1"></td>
				  <td class="col-sm-1"></td>
				  <td class="col-sm-1"></td>
				  <td class="col-sm-1"></td>
				  <td class="col-sm-1"></td>
				  <td class="col-sm-1"></td>
				  <td class="col-sm-1"></td>
				  <td class="col-sm-1"></td>
				</tr>
			 </tbody>
			 <tfoot>
		       <tr ><td colspan="12" border="0"> <button id="add_button"  title="add-button" type="button" class="btn btn-default"><i class="fa fa-plus"></i></button></td></tr>
			 </tfoot>
			</table>
          </div>
        </form>
       </div>
       <div class="container">
         <div class="col-sm-4">
			<b>Commercial Terms and Conditions</b>				 </br>	
			  1	 Tax & Duties : 5% (Inclusive in Unit Price)     </br>				
		      2	 Price : As quoted								 </br>
			  3	 Packing and Forwarding : Nil					 </br>		
			  4	 Octroi : Nil				 					 </br>
			  5  Payment Method : Cash/Cheque					 </br>			
			  6  Payment Terms : 100% Advance					 </br>		
			  7  Special Terms if Any : The above prices are subject to change if the quantity and material vary at the time of placing order.				
										</br>
	      </br></br>
	     </div>
	   
	   
		   <div class="col-sm-4">				 					
				<b>Omnikart Bank Details				</b>  </br>
				Bank Name & Branch : HDFC Bank, Powai		  </br>
				Acoount Name : Omnikart Engineering Pvt Ltd	  </br>
				Account Number : 50200008446091			      </br>
				IFSC Code : HDFC0000239					      </br>
		   </div>										
		   <div class="col-sm-4">	 								
				<b>Vat/Tin Details </b>			</br>
				CIN	U29253MH2014PTC257831		</br>
				PAN N0	AACCO0433A				</br>
				VAT TIN	27641098315 V			</br>
				CST TIN	27641098315 C			</br>
		   </div>
	   </div>
      <div align="center">
        <button type="button" class="btn btn-default" ><i class="fa fa-2x fa-paper-plane"></i></button>
        <button id="button-form-update" data-toggle="tooltip" title="Update Seleted Queries" class="btn btn-info"><i class="fa fa-2x fa-save"></i></button>
      </div>
    </div>

  </div>
</div>
<script>
var unitclasses = '';
<?php if ($unit_classes) { ?>
	unitclasses += '<select name="unit_class_id" id="input-unit-class" >';
    <?php foreach ($unit_classes as $unit_class) { ?>
    unitclasses += '<option value="<?php echo $unit_class['unit_class_id']; ?>"><?php echo $unit_class['title']; ?></option>';
    <?php } ?>
	unitclasses += '</select>';
<?php } ?>

var productrow = 0;
$('#button-form-update').on('click',function(){
	$.ajax({
		url: '<?php echo $update_link; ?>',
		data: $('#form-quotation').serialize(),
		type: 'POST',
		dataType: 'json',
		beforeSend: function() {
		},
		success: function(json) {
		}
	});


});
$('.get-quote').on('click', function(){
	var ths = $(this);
	var enquiry_id = ths.parent().children('input[name=\'enquiry_id\']');
	$.ajax({
		url: "<?php echo $enquiry_link; ?>&enquiry_id="+enquiry_id.val(),
		dataType: 'json',
		beforeSend: function() {
			$('#recurring-description').html('');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
				$('#form-quotation input[name=\'enquiry_id\']').val(enquiry_id.val());
				$('#input-name').val(json.user_info.firstname+' '+json.user_info.lastname);
				$('#input-company_name').val("Omnikart Pvt. Limited");
				$('#input-company_address').val(json.user_info.firstname+' '+json.user_info.lastname);
				$('#input-no').val(json.user_info.phone);
				$('#input-email').val(json.user_info.email);
				
				
				$(json.query).each(function(index,value){
					html = '<tr class="products">';
					html += '<td>'+(index*1+1)+'</td>';
					html += '<td colspan="3"><input type="text" name="products['+index+'][name]" value="'+value.name+'" class="form-control"></td>';
					html += '<td ><input type="text"  name="products['+index+'][minimum]" value="'+value.quantity+'" id="input-name" class="form-control"></td>';
					html += '<td colspan="2">'+unitclasses+'<input type="text" name="products['+index+'][unit]" value="'+value.unit+'" id="input-name" class="form-control"></td>';
					html += '<td colspan="2"><input type="text" name="products['+index+'][price]" value="'+value.price+'" id="input-name" class="form-control"></td>'; 
					html += '<td colspan="3"><input type="text" name="products['+index+'][total]" value="'+value.total+'" id="input-name" class="form-control"></td>';
					html += '</tr>';
					productrow=index;
				});
				$('#product-table > .products').remove();
				$('#product-table').append(html);
				$('#myModal').modal({
					show: 'true'
				});
		}
	});
});

$('.date').datetimepicker({
	pickTime: false
});
$('#add_button').on('click', function(){
	productrow+=1;
	html = '<tr class="products">';
	html += '<td>'+(productrow*1+1)+'</td>';
	html += '<td colspan="3"><input type="text" name="products['+productrow+'][name]" class="form-control"></td>';
	html += '<td ><input type="text" name="products['+productrow+'][minimum]" class="form-control"></td>';
	html += '<td colspan="2">'+unitclasses+'<input type="text" name="products['+productrow+'][unit]" class="form-control"></td>';
	html += '<td colspan="2"><input type="text" name="products['+productrow+'][price]" class="form-control"></td>'; 
	html += '<td colspan="3"><input type="text" name="products['+productrow+'][total]" class="form-control"></td>';
	html += '</tr>';
    $('#product-table').append(html);
});
</script>
<?php echo $footer; ?>
