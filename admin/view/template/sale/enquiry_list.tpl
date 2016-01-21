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
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
		<?php if ($enquiries) { ?>
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			<?php foreach ($enquiries as $enquiry) { ?>
				<div class="panel panel-default">
					<div class="panel-heading"># <?php echo $enquiry['enquiry_id']; ?> &nbsp; <?php echo $enquiry['firstname'].' '.$enquiry['lastname']; ?>
						<a href=""  data-toggle="modal" data-target="#chatModal" style="margin-left:500px;">Chat with customer</a>
						<a href="javascript:void();" data-enquiryId="<?php echo $enquiry['enquiry_id']; ?>" class="pull-right" data-toggle="modal" data-target="#enquiryModal">Reply with Smart Quotation</a> 
					</div>
					<div id="enquiryModal" class="modal fade" role="dialog">
  							<div class="modal-dialog modal-lg">
 									<div class="modal-content">
  											<div class="modal-header">
        										<button type="button" class="close" data-dismiss="modal">&times;</button>
        									</div>
  											<div class="modal-body">
   											<table class="table table-bordered">
   											<tbody></tbody></table></div>
											<div class="modal-footer">
	    									</div>
	    							</div>
								</div>
							</div>
					<div id="chatModal" class="modal fade" role="dialog">
  							<div class="modal-dialog modal-lg">
 									<div class="modal-content">
      										<div class="modal-header">
    											<button type="button" class="close" data-dismiss="modal">&times;</button>
        									</div>
  											<div class="modal-body">
   											<table class="table table-bordered">
   											<tbody>
											<div class="form-group required">
											<label class="control-label col-sm-2" for="comment" >Comment</label>
											<div class="col-sm-8">
											<textarea name="comment" rows="5" class="form-control"></textarea>
											</div> 
											</div>
   											</tbody>
   											</table></div>
											<div class="modal-footer">
	    									</div>
									</div>
							</div>
					</div>
					<div class="panel-body">
						<p>	Email: <?php echo $enquiry['email']; ?><br />
						Telephone: <?php echo $enquiry['telephone']; ?><br />
						Postcode: <?php echo $enquiry['postcode']; ?><br />
						<?php foreach ($enquiry['terms'] as $term) { ?>
						<?php echo $term['type']; ?>:<?php echo $term['value']; ?><br />
						<?php } ?>
						</p>
					</div>
				<table class="table table-bordered">
					<thead>
						<tr>
							<td class="center" style="max-width:100px;">Sr No.</td>
							<td style="max-width:100px;">Name</td>
							<td style="width:400px;">Description</td>
							<td class="center" style="max-width:100px;">Quantity</td>
							<td class="center" style="max-width:50px;">Unit</td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($enquiry['enquiries'] as $key=>$enquiry_product) { ?>
							<tr>
							<td class="center"><?php echo $key; ?></td>
							<?php if (isset($enquiry_product['link'])) { ?>
							<td><a href="<?php echo $enquiry_product['link']; ?>" > <?php echo $enquiry_product['name']; ?> </a></td>
							<?php } else { ?>
							<td><?php echo $enquiry_product['name']; ?></td>
							<?php } ?>
							<td><?php echo $enquiry_product['description']; ?><br />
								<?php if (is_array($enquiry_product['filenames'])) { foreach ($enquiry_product['filenames'] as $file) { ?>
									<a href="<?php echo '../system/upload/queries/'.$file; ?>" target="_Blank"><?php echo substr($file,0,strrpos($file,'.',-1)); ?></a>
								<?php } } ?>
							</td>
							<td class="center"><?php echo $enquiry_product['quantity']; ?></td>
							<td class="center"><?php echo $enquiry_product['unit_title']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				</div>
			<?php } ?>
			</div>
		<?php } else { ?>
		<tr>
		<td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
		</tr>
		<?php } ?>
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
        <form class="form" id="form-quotation">
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
					  <div class="row">
					  	<div class="col-sm-6">
					  		<div class="form-group">
			  		  			<label class="control-label">Contact Person :</label>
			  		  			<input type="text" name="firstname" value="" id="input-name" class="form-control">
				  	  		</div>
                      	</div>
                        <div class="col-sm-6">
                        	<div class="form-group">
				  				<label class="control-label">Company Name :</label>
				  				<input type="text"  name="companyname" value="" id="input-company_name" class="form-control">
				  			</div>
                        </div>
					  </div>
                      <div class="form-group">
				       	  <label class="control-label" for="input-address">Address</label>
				          <select name="address" id="input-address" class="form-control">
			                    
				          </select>
				      </div>
					  <div class="row">
					    <div class="col-sm-6">
					        <div class="form-group required">
			                	<label class="control-label" for="input-address-1">Address 1:</label>
			                	<input type="text" name="address_1" value="" id="input-address-1" class="form-control" />
			                </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group required">
				                <label class="control-label" for="input-address-2">Address 2:</label>
				                <input type="text" name="address_2" value="" id="input-address-2" class="form-control" />
			                </div>
			            </div>
                      </div>
					  <div class="row">
					    <div class="col-sm-6">
			                <div class="form-group required">
			                	<label class="control-label" for="input-city">City:</label>
			                	<input type="text" name="city" value="" id="input-city" class="form-control" />
			           		</div>
				        </div>     
				        <div class="col-sm-6">
			                 <div class="form-group required">
		                	 	<label class="control-label" for="input-postcode">Postcode:</label>
		                		<input type="text" name="postcode" value="" id="input-postcode" class="form-control" />
		                	 </div>
		              	</div>
				      </div>	 
				      <div class="row">
					    <div class="col-sm-6">
			            	<div class="form-group required">
					             <label class="control-label" for="input-zone">Zone:</label>
					             <select name="zone_id" id="input-zone" class="form-control"></select>    
				            </div>
				        </div>
				        <div class="col-sm-6">
							<div class="form-group required">
		                		 <label class="control-label" for="input-country">Country</label>
		                		 <select name="country_id" id="input-country" class="form-control">
				                    <option value="">-- Select Country--</option>
				                    <?php foreach ($countries as $country) { ?>
					                    <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
				                    <?php } ?>
				                 </select>
		                	</div>			           
			            </div>
				      </div>
				      <div class="row">
					    <div class="col-sm-6">     
				            <div class="form-group">
					  		    <label class="control-label">Contact No :</label>
					  		    <input type="text"  value="" name="phone" id="input-phone" class="form-control">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
							  	<label class="control-label">Email :</label>
							    <input type="text"  value="" name="email" id="input-email" class="form-control">
						   </div>
			            </div>
			          </div>
				  </td>
			      <td colspan="6">
					 <div class="row">
					    <div class="col-sm-6"> 
							<div class="form-group">
						  		<label class="control-label">Date :</label>
						  		<div class="input-group date">
							  			<input type="text"  name="date" value="" id="input-date" class="form-control">
							  		    <span class="input-group-btn">
							               <button data-toggle="tooltip" title="Click here to pick date!" type="button" class="btn btn-default datebutton"><i class="fa fa-calendar"></i></button>
						            	</span>
					            </div>
						    </div>
				  	    </div>
				  	    <div class="col-sm-6">    
				  	        <div class="form-group">
				  				<label class="control-label">Quote Expiration Date :</label>
				  				<div class="input-group date">
					  				<input type="text"  name="quote_expiration_date" value="" id="input-date" class="form-control">
					  		    	<span class="input-group-btn">
					               		<button data-toggle="tooltip" title="Click here to pick date!" type="button" class="btn btn-default datebutton"><i class="fa fa-calendar"></i></button>
				            		</span>
			            		</div>
				    		</div>
				    	</div>
				     </div> 		
				  	 <div class="form-group">
				  		<label class="control-label">Omnikart Contact Name :</label>
				  		<input type="text"  name="omnikart_contact_name" value="" id="input-name" class="form-control">
				  	 </div>
				  	 <div class="form-group">
				  		<label class="control-label">Omnikart Contact No :</label>
				  		<input type="text"  name="omnikart_contact_no" value=""  class="form-control">
				  	 </div>
				  	 <div class="row">
					    <div class="col-sm-6"> 
					       <div class="form-group">
				  				<label class="control-label">Delivery Lead Time :</label>
				  				<input type="text" name="lead_time" value=""  class="form-control">
				  		   </div>
				  	    </div>
				  	    <div class="col-sm-6"> 
						   <div class="form-group">
				  			    <label class="control-label">Price :</label>
				  				<input type="text" name="price" value=""  class="form-control">
				  		   </div>
				  		</div>
				     </div>
				  	 <div class="row">
					    <div class="col-sm-6"> 
					
				  	<div class="form-group">
				  		<label class="control-label">Packing and Forwarding :</label>
				  		<input type="text" name="packing_forwarding" value=""  class="form-control">
				  	</div>
				  	  </div>  	
					  <div class="col-sm-6">
				  	     <div class="form-group">
				  		   <label class="control-label">Octroi :</label>
				  	  	   <input type="text" name="lead_time" value="" class="form-control">
				  	     </div>
				  	  </div>
					</div>
				  	<div class="row">
					    <div class="col-sm-6"> 
						  	<div class="form-group">
						  		<label class="control-label">Payment Method :</label>
						  		<input type="text" name="payment_method" value=""  class="form-control">
						  	</div>
						</div>  	
						<div class="col-sm-6">
						    <div class="form-group">
						  		<label class="control-label">Payment Terms :</label>
						  		<input type="text" name="payment_terms" value=""  class="form-control">
						  	</div>
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
				  <td colspan="1">Tax Rate</td>
				  <td colspan="2">Total(INR)</td>
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
        <a target="_blank" href="" id="pdf_link" type="button" class="btn btn-default" ><i class="fa fa-2x fa-paper-plane"></i></a>
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
var zone_id = 0;	
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
				$('#input-phone').val(json.user_info.phone);
				$('#input-email').val(json.user_info.email);
				
				var addresses = json.addresses;
				loadaddress(addresses);
				var products = json.query;
				loadproducts(products);
				
				$('#myModal select[name=\'address\']').val(json['address_id']);
				$('#myModal select[name=\'address\']').trigger('change');
				$('#pdf_link').attr('href',json.pdf_link);
				
				$('#myModal').modal({
					show: 'true'
				});
		}
	});
});
function loadaddress(address){
	html = '<option value="0"	>-- Select Address --</option>';
	
	$(address).each(function(index,value){
		html += '<option value="'+value.address_id+'">'+value.company+' '+value.address_1+' '+value.city+' '+value.zone+' '+value.postcode+'</option>';
	});
	$('#input-address').html(html);
}
function loadproducts(products){
	$(products).each(function(index,value){
		html = '<tr class="products">';
		html += '<td>'+(index*1+1)+'</td>';
		html += '<td colspan="3"><input type="text" name="products['+index+'][name]" value="'+value.name+'" class="form-control"></td>';
		html += '<td ><input type="text"  name="products['+index+'][minimum]" value="'+value.quantity+'" id="input-name" class="form-control"></td>';
		html += '<td colspan="2">'+unitclasses+'<input type="text" name="products['+index+'][unit]" value="'+value.unit+'" id="input-name" class="form-control"></td>';
		html += '<td colspan="2"><input type="text" name="products['+index+'][price]" value="'+value.price+'"  class="form-control"></td>'; 
		html += '<td colspan="1"><input type="text" name="products['+index+'][tax_rate]" value="'+value.tax_rate+'"  class="form-control"></td>'; 
		html += '<td colspan="2"><input type="text" name="products['+index+'][total]" value="'+value.total+'"  class="form-control"></td>';
		html += '</tr>';
		productrow=index;
	});
	$('#product-table > .products').remove();
	$('#product-table').append(html);
}

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
	html += '<td colspan="1"><input type="text" name="" value=""  class="form-control"></td>'; 
	html += '<td colspan="2"><input type="text" name="products['+productrow+'][total]" class="form-control"></td>';
	html += '</tr>';
    $('#product-table').append(html);
});


$('#myModal select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=sale/order/country&token=<?php echo $token; ?>&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#myModal select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			
			$('#myModal .fa-spin').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#myModal input[name=\'postcode\']').parent().parent().addClass('required');
			} else {
				$('#myModal input[name=\'postcode\']').parent().parent().removeClass('required');
			}
			
			html = '<option value="">-- Select Zone --</option>';
			
			if (json['zone'] && json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
	    			
					if (json['zone'][i]['zone_id'] == zone_id) {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected">-- No zone available --</option>';
			}
			
			
			$('#myModal select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
$('select[name=\'address\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=sale/customer/address&token=<?php echo $token; ?>&address_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'payment_address\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('#tab-payment .fa-spin').remove();
		},		
		success: function(json) {
			// Reset all fields
			$('#myModal select option').not('#myModal select[name=\'address\']').removeAttr('selected');
					
			/*$('#myModal input[name=\'firstname\']').val(json['firstname']);
			$('#myModal input[name=\'lastname\']').val(json['lastname']);
			$('#myModal input[name=\'company\']').val(json['company']);*/
			
			$('#myModal input[name=\'address_1\']').val(json['address_1']);
			$('#myModal input[name=\'address_2\']').val(json['address_2']);
			$('#myModal input[name=\'city\']').val(json['city']);
			$('#myModal input[name=\'postcode\']').val(json['postcode']);
			$('#myModal select[name=\'country_id\']').val(json['country_id']);
			
			zone_id = json['zone_id'];
			
			$('#myModal select[name=\'country_id\']').trigger('change');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});
</script>

<script type="text/javascript">
$('#enquiryModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var enquiry_id = button.attr('data-enquiryId');
  var modal = $('#enquiryModal');
  	$.ajax({
		url: 'index.php?route=sale/enquiry/getEnquiry&token=<?php echo $token; ?>&enquiry_id='+enquiry_id,
 		success: function(data) {
		     modal.find('.modal-body').html(data);
		}
	});
});
</script>	
<?php echo $footer; ?>
