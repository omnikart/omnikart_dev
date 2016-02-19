<?php echo $header; ?><div id="columns">
	<div class="container">
		<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>

  <?php if ($error_warning) { ?>
    <div class="alert alert-danger">
			<i class="fa fa-exclamation-circle"></i><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
    <div class="alert alert-success">
			<i class="fa fa-check-circle"> </i> <?php echo $success; ?></div>
  <?php } ?>
  
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>

  <div id="content" class="<?php echo $class; ?>">
    <?php echo $content_top; ?>    
    <h1> Quotation and Comments </h1>
				<fieldset>
					<legend><i class="fa fa-list"></i>Quotation and Comments</legend>
      <?php if($isMember) { ?>
					<form action="<?php echo $delete; ?>" method="post"
						enctype="multipart/form-data" id="form-product">
		<?php if ($quotes) { ?>
			<div class="col-sm-3">
				<ul class="nav nav-pills nav-stacked" role="tablist">
				<?php foreach ($quotes as $quote) { ?>
						<li role="presentation" onclick="suppliertabclick(<?php echo $quote['quote']['quote_id']; ?>,<?php echo $quote['quote']['enquiry_id']; ?>);">
							<?php if(isset($quote['info']['firstname'])) { ?>
							<a><?php  echo $quote['info']['firstname'] . ' ' . $quote['info']['lastname']; ?></a>
							<?php } ?>
						</li>
				<?php } ?>
				</ul>
			</div>
			<div class="tab-content col-sm-9" id="commentsandquote">
			</div>
		<?php } else { ?>
		<tr>
			<td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
		</tr>
		<?php } ?>
      </form>
      <?php } else { ?>
        <div class="text-danger">Warning: You are not authorised to view
						this page, Please contact to site administrator!</div>
      <?php } ?>
    </fieldset>

    <?php echo $content_bottom; ?>  
  </div> 
  <?php echo $column_right; ?>
  </div>
	</div>
	<div id="progress-dialog" class="modal" data-backdrop="static"
		style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content padding20">
				<div id="progressbar">
					<div class="progress">
						<div class="progress-bar" role="progressbar" aria-valuenow="0"
							aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
				<div id="progressinfo"></div>
				<button class="btn btn-default finishActionButton"
					style="display: none;">Abort</button>
			</div>
		</div>
	</div>
	
<script type="text/javascript"><!--

function suppliertabclick(quote_id,enquiry_id) {
  
  var html_tab_content='';
  $.ajax({
	  url : 'index.php?route=account/customerpartner/enquiry/getSentEnquiryComment&quote_id='+quote_id+'&enquiry_id='+enquiry_id,
	    dataType: 'json',
	    success : function (json) {
	    	$("#commentsandquote").html("");

	    	html_tab_content += '<div id ="previousconversations" style="overflow-y: scroll; height:400px;">';
	    	
		    if (json.comments) {
	    	 	 html_tab_content +=       '<div class="container">'
	    	 	 html_tab_content +=     	 '<ul class="list-inline">'
	    	 	 html_tab_content +=    		'<li class="active"><a data-toggle="tab" href="#chat">Chat</a></li>'
	    	 	 html_tab_content +=    		' <li><a data-toggle="tab" href="#quotation">Quotation</a></li>'
	    	 	 html_tab_content +=      	 '</ul>'
	    	 	 html_tab_content +=       '<div class="tab-content">'
	    	 	 html_tab_content +=       '<div id="quotation" class="tab-pane fade">'
	    	 	 
		    	html_tab_content += 	 '<div class="panel panel-default">'
				html_tab_content +=	'<table class="table table-bordered">'
				html_tab_content +='<tbody>'
				html_tab_content +=	'<tr><td colspan="2">'
				html_tab_content +=	'<div class="clearfix quote-address pull-left">'
				html_tab_content +=				'<label for="address<?php echo $supplier_address['supplier_address_id']; ?>">'
				html_tab_content +='<?php echo $supplier_address['firstname']; ?> <?php echo $supplier_address['lastname']; ?></td>'
				html_tab_content +=			'<td colspan="2">'
				html_tab_content +=			'<?php echo $supplier_address['address_1']; ?>'
				html_tab_content +=	'<?php echo $supplier_address['city']; ?> <?php echo $supplier_address['postcode']; ?>'
				html_tab_content +=		'<?php echo $supplier_address['zone']; ?> <?php echo $supplier_address['country']; ?>'
				html_tab_content +=	'</label>'
				html_tab_content +=  '</div>'
				html_tab_content +=	'</td></tr>'
				html_tab_content +=	'<tr><td colspan="4" class="pull-left" >Quotation No: 12345</td></tr>'
				html_tab_content +=	'<tr id="customer"><td colspan="2"  class="pull-left">Customer Details</td>'
				html_tab_content +=	'<td colspan="2">Information</td></tr>'
				html_tab_content +=	'<tr>'
				html_tab_content +=	'<td style="width:100px">Name</td><td>: <?php echo $firstname.' '.$lastname; ?></td>'
				html_tab_content +=		'<td style="width:100px">Date</td><td>:11/10/1993</td>'
				html_tab_content +=		'</tr>'
				html_tab_content +=	'<tr>'
				html_tab_content +=		'<td style="width:100px">Email</td><td>:<?php echo $email; ?></td>'
				html_tab_content +=		'<td style="width:100px">Quote Expiration Date</td><td>:11/10/1993</td>'
				html_tab_content +=	'</tr>'
				html_tab_content +=		'<tr>'
				html_tab_content +=	'<td style="width:100px">Telephone</td><td>: <?php echo $telephone; ?></td>'
				html_tab_content +=	'<td style="width:100px">Delivery Lead Time</td><td>:1 week</td>'
				html_tab_content +=	'</tr>'
				html_tab_content +=	'<tr>'
				html_tab_content +=	'<td rowspan="2" >Address</td><td rowspan="2" >' 
				html_tab_content +=	'<?php echo $address_1;?><br />'
				html_tab_content +=	'<?php echo $city;?><br />'
				html_tab_content +=	'<?php echo $zone;?><br />'
				html_tab_content +=	'<?php echo $country;?>'
				html_tab_content +=	'</td>'
				html_tab_content +=	'<td style="width:100px">Contact Name </td><td>:shailesh</td>'
				html_tab_content +='</tr>'
				html_tab_content +='<tr><td style="width:100px">Contact No </td><td>:9004458077</td></tr>'
				html_tab_content +='<tr>'
				html_tab_content +=	'<td colspan="4"></td>'
				html_tab_content +='</tr>'
				html_tab_content +='</tbody>'
				html_tab_content +='</table>'
				html_tab_content +='</div>'
				html_tab_content +='<div class="panel panel-default">'
		  		html_tab_content +='<div class="panel-body">'
				html_tab_content +='<table  class="table table-bordered table-hover"  border="1px solid black";>'
				html_tab_content +='<thead>'
				html_tab_content +=	'<tr>'
				html_tab_content +=	'<td class="center" >Sr No.</td>'
				html_tab_content +=	'<td>Name</td>'
				html_tab_content +=		'<td class="center" >Quantity</td>'
				html_tab_content +=		'<td >Description</td>'
				html_tab_content +=		'<td class="center" >Unit Price</td>'
				html_tab_content +=		'<td class="center" >Tax class</td>'
				html_tab_content +=		'<td class="center" >Total(INR)</td>'
				html_tab_content +=		'</tr>'
				html_tab_content +=	'</thead>'
				html_tab_content +=	'<tbody>'
				html_tab_content +=	'<?php foreach ($enquiries as $key=>$enquiry) { ?>'
				html_tab_content +=		'<tr>'
				html_tab_content +=		'<td class="center"><?php echo $key + 1; ?></td>'
				html_tab_content +=		'<?php if (isset($enquiry['link'])) { ?>'
				html_tab_content +=			'<td><a href="<?php echo $enquiry['link']; ?>" > <?php echo $enquiry['name']; ?> </a></td>'
				html_tab_content +=		'<?php } else { ?>'
				html_tab_content +=			'<td><?php echo $enquiry['name']; ?></td>'
				html_tab_content +=			'<?php } ?>'
				html_tab_content +=		'<td class="center"><?php echo $enquiry['quantity']; ?></td>'
				html_tab_content +=			'<td>'
				html_tab_content +=		'<?php echo round($enquiry['weight'],4); ?> <?php echo $enquiry['weight_class']; ?><hr>'
				html_tab_content +=	'<?php echo round($enquiry['length'],4); ?> x <?php echo round($enquiry['width'],4); ?> x <?php echo round($enquiry['height'],4); ?> <?php echo $enquiry['length_class']; ?></td>
				html_tab_content +=		'<td class="center"><?php echo $enquiry['price']; ?> <?php echo $enquiry['unit']; ?> </td>'
				html_tab_content +=		'<td class="center"><?php echo $enquiry['tax_class']; ?></td>'
		        html_tab_content +=		'<td class="center"><?php echo $enquiry['total']; ?></td>'
				html_tab_content +=		'</tr>'
				html_tab_content +=		'<?php } ?>'
				html_tab_content +=	'</tbody>'
				html_tab_content +=		'<?php foreach ($terms as $term) { ?>'
				html_tab_content +=		'<tr>'
				html_tab_content +=			'<td colspan="4"></td>'
				html_tab_content +=		'<td colspan="1" class="right"><?php echo $term['type']; ?></td>'
				html_tab_content +=		'<td colspan="2">'
				html_tab_content +=		'<?php echo $term['value']; ?>'
				html_tab_content +=			'</td>'
				html_tab_content +=	'</tr>'
				html_tab_content +=	'<?php } ?>'
				html_tab_content +=	'</tbody>'
				html_tab_content +=	'</table>'
				html_tab_content += '</div>'
				html_tab_content +='</div>'
			    	 	 
	    	 	 
	    	 	 html_tab_content +=       '</div>'
	    		
	    	 $.each(json.comments, function(key,val) {
	    	     html_tab_content +=       '<div id="chat" class="tab-pane fade in active">' 
	    	     html_tab_content += 		'<div class="panel panel-default">';
		    	 html_tab_content += 			'<div class="panel-heading">';
	    		 html_tab_content +=     			'<h4 class="panel-title">'+val['authorname']+'</h4>';
	    		 html_tab_content +=			'</div>';
	    		 html_tab_content += 			'<div class="panel-body">'+val['comment']+'</div>';
		    	 html_tab_content += 		'</div>';
	         });
		    }
		    html_tab_content += 		'</div>';
		    
	    	html_tab_content += 	'<div class="panel-footer">';
	    	html_tab_content += 		'<div class="quote-comments">';
	    	html_tab_content += 			'<div class="col-sm-12"><input type="hidden" name="quote_id" value="'+quote_id+'">';
	    	html_tab_content += 				'<label class="control-label" for="input-comment">Comment</label>';
	    	html_tab_content += 				'<textarea name="quote['+quote_id+']" rows="5" id="input-comment" class="form-control"></textarea>';
	    	html_tab_content += 			'</div>';
	    	html_tab_content += 			'<button type="button" id="button-comment" data-loading-text="text loading" class="btn btn-primary">Submit Comment</button>';
	    	html_tab_content +=    		'</div>';
	    	html_tab_content +=    	'</div>';
	    	html_tab_content +=    	'</div>';
	    	
	    	$("#commentsandquote").html(html_tab_content);
	    	$('#previousconversations').animate({scrollTop:5000}, 'slow');
	    }
  });
}
  $('body').on('click','#button-comment', function() {
		$.ajax({
			url : 'index.php?route=account/customerpartner/enquiry/addSentEnquiryComment',
			type: 'post',
			dataType: 'json',
			data: $('.quote-comments textarea[name^=\'quote\'] , .quote-comments input[name=\'quote_id\']'),
			success : function (json) {
				suppliertabclick($('.quote-comments input[name=\'quote_id\']').val());
		    }
		});
	});
//--></script>	
	
<script type="text/javascript"><!--
$('#form-product').submit(function(){
    if ($(this).attr('action').indexOf('delete',1) != -1) {
        if (!confirm('<?php echo $text_confirm; ?>')) {
            return false;
        }
    }
});

function filter() {
  url = 'index.php?route=account/customerpartner/sentenquiry';
  
  var filter_name = $('input[name=\'filter_name\']').val();
  
  if (filter_name) {
    url += '&filter_name=' + encodeURIComponent(filter_name);
  }
  
  var filter_date = $('input[name=\'filter_date\']').val();
  
  if (filter_date) {
    url += '&filter_model=' + encodeURIComponent(filter_date);
  }
  
  var filter_status = $('select[name=\'filter_status\']').val();
  
  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status);
  } 

  location = url;
}
//--></script>
</div><?php echo $footer; ?>
