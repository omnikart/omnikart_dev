<div class="modal fade" id="<?php echo $modal_id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	  	<div class="modal-header">
	  		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	  		<h4>Product Enquiry Form</h4>
	  	</div>
      <div class="modal-body"><!-- edited latest -->
      	<form id="enquiry_form">
	  	   <div class="row">
	  	   <div class="col-sm-12">
	  	   	<div class="table-responsive">
			  	<table class="table" id="productquery">
			  		<thead>
			  			<tr style="visible:none;">
			  				<th class="col-sm-6">Product Name</th>
			  				<th class="col-sm-5">Quantity</th>
			  				<th class="col-sm-1"></th>
			  			</tr>
			  		</thead>
			  		<tbody id="product-0">
			  			<tr>
					  		<td>
					          <div class="form-group required">
					              <input type="text" name="product[0][name]" value=trim("") placeholder="Product Name" id="product-name" class="form-control" />
					          </div>
					    	</td>
							<td>
					          <div class="form-group required">
					          	<div class="input-group">
					              <input type="text" name="product[0][quantity]" value="" placeholder="Quantity Required" id="quantity" class="form-control quantity" />
					              <span class="input-group-btn">
	            					<button type="button" id="button-ctrl" data-loading-text="Loading..." class="btn btn-primary btn-increase"><i class="fa fa-plus"></i></button>
	            					<button type="button" id="button-ctrl" data-loading-text="Loading..." class="btn btn-primary btn-decrease"><i class="fa fa-minus"></i></button>
	            				  </span>
	            				 </div>
					          </div>
						    </td>
						    <td><button type="button" id="button-upload" onclick="$('#product-0').remove();" class="btn btn-danger btn-decrease"><i class="fa fa-times"></i></button>
						    </td>
						</tr>
						<tr>
							<td colspan="3">
						        <div class="form-group required">
						            <textarea rows="5" name="product[0][specification]" placeholder="Please specify your requirement and product specifications required" id="specification" class="form-control"></textarea>
						        </div>
							</td>
			  			</tr>
			  		</tbody>
			   	</table>
			</div>
		   	  <div class="col-sm-12">
		   	  	<div class="row">
			   	  <div class="col-sm-6">
			   	  	<button type="button" id="addqueryproduct" class="btn btn-default">Add Product</button>
			   	  	<button type="button" id="button-upload" class="btn btn-default">Upload</button>
			   	  </div>
			   	  <div class="col-sm-6">
		   	  		<div id="uploaded-file"></div>
		            		
			        
			      </div>
			   	  </div>
			   	</div>
		   	  </div>
		   	  <div class="clearfix"></div>
		   	  <br />
		   	  <hr />
		   	  <div class="col-sm-12">
			  	<div class="row">
			   	  <div class="col-sm-6">
			          <div class="form-group required">
			            <label class="control-label" for="first-name">First Name</label>
			              <input type="text" name="firstname" value="<?php echo ($logged)?$firstname:''; ?>" placeholder="First Name" id="first-name" class="form-control" />
			          </div>
			      </div>
				  <div class="col-sm-6">
			          <div class="form-group required">
			            <label class="control-label" for="last-name">Last Name</label>
			            <input type="text" name="lastname" value="<?php echo ($logged)?$lastname:''; ?>" placeholder="Last Name" id="last-name" class="form-control" />
			          </div>
			      </div>		      
			   </div>
			  </div>
  		   	  <div class="col-sm-12">
			  	<div class="row">
			      <div class="col-sm-6">
			          <div class="form-group required">
			            <label class="control-label" for="phone">Contact Number</label>
			              <input type="text" name="phone" value="<?php echo ($logged)?$phone:''; ?>" placeholder="Phone Number" id="phone" class="form-control" />
			          </div>
		          </div>
			      <div class="col-sm-6">
			          <div class="form-group">
			            <label class="control-label" for="email">Email Id</label>
			              <input type="text" name="email" value="<?php echo ($logged)?$email:''; ?>" placeholder="Email Id" id="email" class="form-control" />
			          </div>
		          </div>
		        </div>
	          </div>
	       </div>	          
	       </div>          
        </form>
      <div class="modal-footer">
	   	<button type="button" class="btn btn-default" id="submit-enquiry">Submit Enquiry</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	var productrow = 0;
  $('#submit-enquiry').on('click', function(){
	buttont = $(this);
	
    $.ajax({
        url : 'index.php?route=module/enquiry/submit',
        data: $('#enquiry_form').serialize(),
        type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$(buttont).button('loading');
		},
		complete: function() {
			$(buttont).button('reset');
		},
		success: function(json) {
			
			if (json['success']){
				$('#enquiry_form input[type=text], #enquiry_form textarea').val("");
				setTimeout(function(){ $('#<?php echo $modal_id; ?>').modal('hide'); }, 1000);
			} else {
				if (json['firstname']) {
					$($('#<?php echo $modal_id; ?> #first-name').parent()).append('<div class="text-danger">'+json['firstname']+'</div>');
				}
				if (json['lastname']) {
					$($('#<?php echo $modal_id; ?> #last-name').parent()).append('<div class="text-danger">'+json['lastname']+'</div>');
				}
				if (json['phone']) {
					$($('#<?php echo $modal_id; ?> #phone').parent()).append('<div class="text-danger">'+json['phone']+'</div>');
				}
				if (json['product']) {
					$($('#<?php echo $modal_id; ?> #product-name').parent()).append('<div class="text-danger">'+json['product']+'</div>');
				}
				if (json['quantity']) {
					$($('#<?php echo $modal_id; ?> #quantity').parent().parent()).append('<div class="text-danger">'+json['quantity']+'</div>');
				}
			}
		}
		
      });
  });
  
  $('#enquiry_modal').on('click','#addqueryproduct',function(){
  	
  	productrow++;
  	
  	html = 	'<tbody  id="product-'+productrow+'"><tr>';
  	html += '	<td>';
	html +=	'		<div class="form-group required">';
	html +=	'			<input type="text" name="product['+productrow+'][name]" value="" placeholder="Product Name" id="product-name" class="form-control" />';
	html +=	'		</div>';
	html +=	'	</td>';
	html +=	'	<td>';
	html +=	'		<div class="form-group required"><div class="input-group">';
	html +=	'			<input type="text" name="product['+productrow+'][quantity]" value="" placeholder="Quantity Required" id="quantity" class="form-control quantity" />';
	html +=	'			<span class="input-group-btn">';
	html +=	'				<button type="button" id="button-upload" data-loading-text="Loading..." class="btn btn-primary btn-increase"><i class="fa fa-plus"></i></button>';
	html +=	'				<button type="button" id="button-upload" data-loading-text="Loading..." class="btn btn-primary btn-decrease"><i class="fa fa-minus"></i></button>';
	html +=	'			</span>';
	html +=	'		</div></div>';
	html +=	'	</td><td><button type="button" onclick="$(\'#product-'+productrow+'\').remove();" id="button-upload" class="btn btn-danger btn-decrease"><i class="fa fa-times"></i></button></td></tr>';
	html +=	'	<tr><td colspan="3">';
	html +=	'		<div class="form-group required">';
	html +=	'			<textarea rows="5" name="product['+productrow+'][specification]" placeholder="Please specify your requirement and product specifications required" id="specification" class="form-control"></textarea>';
	html +=	'		</div>';
	html +=	'	</td>';						    
	html +=	'</tr></tbody>';
	
  	$('#productquery').append(html);
  	
  });
$('#enquiry_modal').on('click','#productquery button.btn-increase',function(){
    var $input = $(this).closest('.input-group').find('.quantity');
    if ($.isNumeric($input.val())) $input.val(parseInt($input.val()) + 1);
	else $input.val(1);
});
$('#enquiry_modal').on('click','#productquery button.btn-decrease',function(){
    var $input = $(this).closest('.input-group').find('.quantity');
    if ($.isNumeric($input.val()) && (1!=$input.val())) {
   		$input.val(parseInt($input.val()) - 1);
    }
	else $input.val(1);
});

  $('#enquiry_modal').on('click','#button-upload',function(){
	$('#form-upload').remove();
	
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');
	
	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);		
			
			$.ajax({
				url: 'index.php?route=module/enquiry/upload',
				type: 'post',		
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,		
				beforeSend: function() {
					$('#button-upload').button('loading');
				},
				complete: function() {
					$('#button-upload').button('reset');
				},	
				success: function(json) {
					if (json['error']) {
						alert(json['error']);
					}
								
					if (json['success']) {
						html = '<a target="_Blank" href="'+json['link']+'">'+json['mask']+'</a><input type="hidden" name="filename" value="'+json['filename']+'" />'
						$('#uploaded-file').html(html);
						
						
						$('input[name=\'mask\']').attr('value', json['mask']);
					}
				},			
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});

</script>