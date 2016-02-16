<div class="modal" id="<?php echo $modal_id; ?>" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">×</button>
				<h4>Product Enquiry Form</h4>
			</div>
			<div class="modal-body">
				<!-- edited latest -->
				<form id="product_add_form" class="form-horizontal">
					<div class="col-sm-12" id="enquiry-product-details">
						<div class="form-group required">
							<label class="col-sm-3 control-label">Product Name :</label>
							<div class="col-sm-9">
								<input type="text" id="product-name" class="form-control"
									name="name" /> <input type="hidden" class="form-control"
									name="product_id" /> <input type="hidden" class="form-control"
									name="category_id" />
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-3 control-label">Quantity :</label>
							<div class="col-sm-4">
								<div class="input-group">
									<input type="text" id="quantity" class="form-control"
										name="quantity" /> <span class="input-group-btn">
										<button type="button" data-loading-text="Loading..."
											class="btn btn-primary btn-increase">
											<i class="fa fa-plus"></i>
										</button>
										<button type="button" data-loading-text="Loading..."
											class="btn btn-primary btn-decrease">
											<i class="fa fa-minus"></i>
										</button>
									</span>
								</div>
							</div>
							<div class="col-sm-3">
	          			<?php if ($unit_classes) { ?>
	          			<select type="text" class="form-control" name="unit_class">
	          				<?php foreach ($unit_classes as $unit_class) { ?>
	          					<option
										value="<?php echo $unit_class['unit_class_id']; ?>"><?php echo $unit_class['title']; ?></option>
	          				<?php } ?>
	          			</select>
	          			<?php } ?>
	          		</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Details :</label>
							<div class="col-sm-9">
								<textarea type="text" id="enquiry-description"
									class="form-control" name="description" rows="4"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-9">
								<div id="uploaded-file"></div>
								<div class="row">
									<div class="col-sm-5">
										<a type="text" class="btn btn-default btn-block"
											id="button-upload">Upload</a>
									</div>
									<div class="col-sm-5 pull-right">
										<a type="text" class="btn btn-default btn-block"
											id="button-addproduct">Add Product</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" id="view-enquiry">
					Enquiries <span class="badge">0</span>
				</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
  $('body').on('click', '#submit-enquiry', function(){
	buttont = $(this);
	var postcode = $('#enquiry-products input[name=\'postcode\']').val();
	var payment = $('#enquiry-products select[name=\'payment_terms\']').val();
	if ((postcode!='') && (payment!='')){	
	    $.ajax({
	        url : 'index.php?route=module/enquiry/submit',
	        data: $('#enquiry-products select[name=\'payment_terms\'],#enquiry-products input[name=\'c_form\']:checked,#enquiry-products input[name=\'postcode\']'),
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
					$('#enquiry_modal button.btn-decrease').trigger('click');
					setTimeout(function(){ $('.modal').modal('hide'); }, 1000);
					$('#view-enquiry .badge').load('index.php?route=module/enquiry/addProduct');
				} else if(!json['logged']) { 
					$('#modal-login').remove();
					$.ajax({
						url: 'index.php?route=module/login',
						type: 'get',
						dataType: 'html',
						success: function(data) {
							$('.modal').modal('hide');
							var login_modal = addmodal('modal-login','');
							login_modal.find('.modal-title').html('Please Login to submit enquiry');
							login_modal.find('.modal-body').html(data);
							$('#enquiry_modal').modal('show');
							$('#enquiry-products').modal('show');
							login_modal.modal('show');
						}
					});
				}
			}
			
	      });
	} 
	if (postcode=='') {
		$('#enquiry-products input[name=\'postcode\']').addClass('alert-danger');
	}
	if (payment=='0') {
		$('#enquiry-products select[name=\'payment_terms\']').addClass('alert-danger');
	}
  });
  
$('#enquiry_modal').on('click','button.btn-increase',function(){
    var input = $('#enquiry_modal input[name=\'quantity\']');
    if ($.isNumeric(input.val())) input.val(parseInt(input.val()) + 1);
	else input.val(1);
});
$('#enquiry_modal').on('click','button.btn-decrease',function(){
    var input = $('#enquiry_modal input[name=\'quantity\']');
    if ($.isNumeric(input.val()) && (1!=input.val())) {
   		input.val(parseInt(input.val()) - 1);
    }
	else input.val(1);
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
						html =  '<div class="text-left uploaded-files"><i class="fa fa-minus-circle"></i> <a target="_Blank" href="'+json['link']+'">'+json['mask']+'</a><input type="hidden" name="filenames[]" value="'+json['filename']+'" />'
						html += '</div>'; 	
						$('#uploaded-file').append(html);
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
$('input[name=\'name\']').autocomplete({
	'delay': 500,
	'source': function(request, response) {
		$.ajax({
		    url: '<?php echo $autocomplete_products?>&search=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label:  item['name'],
						clabel:  item['cname'],
						value:  item['value'],
						id: item['product_id'],
						type:  item['type'],
				 	}
				}));
			}
		});
	},
	'select': function(item) {
		if (item['type']=='2') {
			var GP_modal = addmodal('modal-GP','');
			GP_modal.find('.modal-dialog').addClass('modal-lg');
			GP_modal.find('.modal-title').html('This product has following variations...!');
			GP_modal.find('.modal-body').load('index.php?route=product/product/getGpProducts&product_id='+item['id']);
			GP_modal.modal('show');
		} else if (item['type']=='1') {
			$('#enquiry-product-details input[name=\'name\']').val(item['label']);
			$('#enquiry-product-details input[name=\'product_id\']').val(item['id']);
			$.ajax({
				url : 'index.php?route=product/product/getdescription&product_id='+item['id'],
			    dataType: "text",
			    success : function (data) {
			    	$("#enquiry-description").val(data);
			    }
			});
			
		} else if (item['type']=='0') {
			$('input[name=\'name\']').val(item['clabel']);
			$('#enquiry-product-details input[name=\'category_id\']').val(item['label']);
			/* 			
 			$.ajax({
				url : 'index.php?route=product/product/getdescription&product_id='+item['value'],
			    dataType: "text",
			    success : function (data) {
			    	$("#enquiry-description").val(data);
			    }
			}); */
		}
	}
});
$('#enquiry_modal').on('click','#button-addproduct',function(){
	$.ajax({
		url : 'index.php?route=module/enquiry/addProduct',
		method: 'post',
		data : $('#enquiry-product-details input, #enquiry-product-details select, #enquiry-product-details textarea'),
	    dataType: "json",
	    success : function (json) {
	    	if (json['success']) {
		    	$('#view-enquiry .badge').html(json['number']);
	    		$('#enquiry-product-details input, #enquiry-product-details select, #enquiry-product-details textarea').val('');
		    } else if(json['name']) {
				$('#product_add_form input[name=\'name\']').addClass('alert-danger');
				
			}
	    }
	});
});
$('#enquiry_modal').on('click','.fa-minus-circle',function(){
	$(this).parent().remove();
});

$('#enquiry_modal').on('click','#view-enquiry',function(){
	$.ajax({
		url : 'index.php?route=module/enquiry/getEnquiry',
	    dataType: "html",
	    success : function (data) {
		    $('.modal').modal('hide');
		    $('#enquiry_modal').modal('show');
		    $('#enquiry-products').remove();
			$('body').append(data);
			$('#enquiry-products').modal('show');
	    }
	});
});
$('#enquiry_modal button.btn-decrease').trigger('click');

$('#button-addproduct').attr('disabled',true);
$("#product-name").keyup(function(){
	if($(this).val().length !=0)
    	$('#button-addproduct').attr('disabled', false);            
    else
        $('#button-addproduct').attr('disabled',true);
});

</script>
