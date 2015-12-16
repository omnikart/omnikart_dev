<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  	<div class="page-header">
	    <div class="container-fluid">
	      	<div class="pull-right">
		      	<button type="button" data-toggle="tooltip" title="<?php echo "delete"; ?>" class="btn btn-danger" onclick="confirm('<?php echo "confirm"; ?>') ? $('#form-attribute1').submit() : false;"><i class="fa fa-trash-o"></i></button>         
	  			<button type="button" data-toggle="modal" data-enquiryid="0" data-target="#supplier-modal" class="btn btn-primary"><i class="fa fa-plus"></i></button>
			</div>
	      <h1><?php echo "$vendor"; ?></h1>
	      <ul class="breadcrumb">
	       </ul>
	    </div>
	</div>
	<div class="container-fluid">
		<div class="panel panel-default">
			<div class="panel-heading"><h3 class="panel-title"><i class="fa fa-list"></i> <?php echo  "Vendor List"; ?></h3></div>
				<div class="panel-body">
				    <div class="well">
				        <div class="row">
				            <div class="col-sm-4">
				              	<div class="form-group">
					                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
				                	<input type="text" name="filter_name"   placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
				              	</div>
				              	<div class="form-group">
				                	<label class="control-label" for="input-mail"><?php echo $entry_mail; ?></label>
				                	<input type="text" name="filter_mail"   placeholder="<?php echo $entry_mail; ?>" id="input-mail" class="form-control" />
				              	</div>
				            </div>
				            <div class="col-sm-4">
				              	<div class="form-group">
				                	<label class="control-label" for="input-category"><?php echo $entry_category; ?></label>
				                	<input type="text" name="filter_category"   placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
				              	</div>
				              	<div class="form-group">
				                	<label class="control-label" for="input-brand"><?php echo $entry_brand; ?></label>
				                	<input type="text" name="filter_brand"   placeholder="<?php echo $entry_brand; ?>" id="input-brand" class="form-control" />
				              	</div>
				        	</div>
				        	<div class="col-sm-4">
				               	<div class="form-group">
				                	<label class="control-label" for="input-trade"><?php echo $entry_trade; ?></label>
				                	<input type="text" name="filter_trade"   placeholder="<?php echo $entry_trade; ?>" id="input-trade" class="form-control" />
				              	</div>
				              	<div class="form-group">
				              		<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
							  	</div>
							</div>
						</div>
					</div>
					<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-attribute1">
						<div class="table-responsive">
							<table  class="table table-bordered table-hover"   border="1px solid black";>
								<thead>
									<tr> 
										<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
										<td class="text-center">company name</td>
										<td class="text-center">phone number</td>
										<td class="text-center">email id</td>
										<td class="text-center">name of the contact person</td>
										<td class="text-center">alternate number</td>
										<td class="text-center">product category</td>
										<td class="text-center">Brands</td>
										<td class="text-center">Trade</td>
										<td class="text-center">Action</td>
										<td class="text-center">History</td>
									</tr>
								</thead>
								<tbody>
									<?php if ($enquiries) { ?>
										<?php foreach ($enquiries  as $enquiry) { ?>
										<tr>
											<td class="text-center"><input type="checkbox" name="selected[]" value="<?php echo $enquiry['id']; ?>"></td>
											<td class="text-center"><?php echo $enquiry['user_info']['company']; ?></td>
											<td class="text-center"><?php echo $enquiry['user_info']['number']; ?></td>
											<td class="text-center"><?php echo $enquiry['user_info']['email']; ?></td>
											<td class="text-center"><?php echo $enquiry['user_info']['name']; ?></td>
											<td class="text-center"><?php echo $enquiry['user_info']['number_2']; ?></td>
											<td class="text-center">
												<?php foreach ($enquiry['categories']  as $category) { ?>
													<?php echo $category; ?></td>
												<?php } ?>                  
											</td>
											<td class="text-center">
												<?php foreach ($enquiry['manufacturers']  as $manufacturer) { ?>
													<?php echo $manufacturer; ?>
												<?php } ?>
											</td>
											<td class="text-center">
												<?php echo $enquiry['trade']; ?> 
											</td>
											<td class=text-right>    
												<input type="hidden" name="enquiry_id" value="<?php echo $enquiry['id']; ?>" />
												<div class="btn-group" role="group">
													<button type="button" class="btn btn-primary" data-toggle="modal" data-enquiryid="<?php echo $enquiry['id']; ?>" data-target="#schedule-modal"><i class="fa fa-calendar-plus-o"></i></button>
										  			<button type="button" class="btn btn-primary" data-toggle="modal" data-enquiryid="<?php echo $enquiry['id']; ?>" data-target="#supplier-modal"><i class="fa fa-plus"></i></button>
												</div>
											</td>
											<td>
											<button type="button" class="btn btn-primary" data-toggle="modal" data-enquiryid="<?php echo $enquiry['id']; ?>" data-target="#demo"><i class="fa fa-history"></i></button>
                                          	</td>
										</tr>
									 	<?php } ?>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</form>
				</div>
			</div>
	</div>
	
	<div class="container">
      <div class="modal fade" id="demo" role="dialog" aria-labelledby="schedule-modal">
       <div class="modal-dialog">
         <div class="modal-content">
           <div class="modal-body">
          </div>
       </div>
     </div>
  </div>
 </div>
	
   <div class="modal fade" id="schedule-modal" role="dialog" aria-labelledby="schedule-modal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Supplier Form</h4>
				</div>
				<div class="modal-body bgColorWhite">
					<div class="panel-body">
						<form id="supplier_form1" class="form-horizontal" >
								<input name="enquiry_id" id="enquiry_id" class="form-control"  type="hidden" value="" /> 
								<div class="form-group required">
									<label class="control-label col-sm-4" for="comment" >What did he say?</label>
									<div class="col-sm-8">
										<textarea name="comment" rows="5" class="form-control" id="comment"></textarea>
									</div> 
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4" for="date">Calender</label>
									<div class="col-sm-8">
										<div class="input-group date">
										<input type="text" name="date" value="" placeholder="" data-date-format="YYYY-MM-DD" class="form-control" />
										<span class="input-group-btn">
											<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          				</span>
                          				</div>
                          			</div>
                          		</div>
              				<div class="form-group required">
                				<label class="control-label col-sm-4" for="input-status"><?php echo $entry_status; ?></label>
                				<div class="col-sm-8">
                				<select name="status" id="input-status" class="form-control">
                  						<option value="1" selected="selected">Enabled</option>
            			      			<option value="0">Disabled</option>
                				</select>
              					</div>
              				</div>
    	                       <div class="form-group">
									<label class="control-label col-sm-4" for="reg">Registration</label>
									<div class="col-sm-8">
									     <input name="registration" class="form-control" placeholder="" type="checkbox" id="reg" /> 
								   </div> 
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4" for="pri">Pricelist</label>
									<div class="col-sm-8">
									     <input name="pricelist" class="form-control" placeholder="" type="checkbox" id="pri" /> 
								    </div> 
								</div>
						</form>
						<div class="text-right">
							<button id="registration" class="btn btn-primary">Registration</button>
						</div>
					</div> 
				</div>
				<div  class="modal-footer ">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
<div class="modal fade" id="supplier-modal" role="dialog" aria-labelledby="supplier-modal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Supplier Form</h4>
				</div>
				<div class="modal-body bgColorWhite">
					
				</div>
				<div  class="modal-footer ">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>	 
</div>	
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.time').datetimepicker({
	pickDate: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});

$('#button-filter').on('click', function() {
	var url = "<?php echo $filterLink; ?>";

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_mail = $('input[name=\'filter_mail\']').val();

	if (filter_mail) {
		url += '&filter_mail=' + encodeURIComponent(filter_mail);
	}

	var filter_category = $('input[name=\'filter_category\']').val();

	if (filter_category) {
		url += '&filter_category=' + encodeURIComponent(filter_category);
	}

	var filter_brand = $('input[name=\'filter_brand\']').val();

	if (filter_brand) {
		url += '&filter_brand=' + encodeURIComponent(filter_brand);
	}
    
    var filter_trade = $('input[name=\'filter_trade\']').val();

	if (filter_trade) {
		url += '&filter_trade=' + encodeURIComponent(filter_trade);
	}
    
  location = url;
});

$('#schedule-modal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var recipient = button.data('enquiryid'); // Extract info from data-* attributes
  var modal = $(this);
  modal.find('.modal-title').text('Supplier Form');
  modal.find('.modal-body #enquiry_id').val(recipient);
 
});
$('#supplier-modal').on('show.bs.modal', function (event) {
  var modal = $(this);	
  var button = $(event.relatedTarget); // Button that triggered the modal
  var enquiryId = button.data('enquiryid'); // Extract info from data-* attributes
	$('#supplier-modal .modal-body').load('<?php echo $supplier_form2; ?>&enquiryId='+enquiryId);
});

$('#demo').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var recipient = button.data('enquiryid'); // Extract info from data-* attributes
  var modal = $(this);
  modal.find('.modal-body #enquiry_id').val(recipient);
 
});
$('#demo').on('show.bs.modal', function (event) {
  var modal = $(this);	
  var button = $(event.relatedTarget); // Button that triggered the modal
  var enquiryId = button.data('enquiryid'); // Extract info from data-* attributes
  alert(enquiryId);
  $.ajax({
      url : '<?php echo $supplier_schedule_link1; ?>&enquiryId='+enquiryId,
      type: 'post',
      dataType: 'json',
      beforeSend: function() {
      },
      complete: function() {
      },
      success: function(json) {
	    }
  });	 
});


//--></script>

<script  type="text/javascript">
 $('#registration').on('click', function(){
    buttont = $(this);
      $.ajax({
        url : '<?php echo $supplier_schedule_link; ?>',
        data: $('#supplier_form1').serialize(),
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
        },
        complete: function() {
        },
        success: function(json) {
	    }
        
      });
  });
  
 </script>

<?php echo $footer; ?>   