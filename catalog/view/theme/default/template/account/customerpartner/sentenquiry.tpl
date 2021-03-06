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
    <h1>
      <?php echo $heading_title; ?>
	  	<div class="pull-right">
						<button data-toggle="tooltip" class="btn btn-primary"
							id="updateProducts"
							title="Update changes for selected products. Page will not refresh after this.">
							<i class="fa fa-save"></i> Update
						</button>
						<button data-toggle="tooltip" class="btn btn-info"
							id="disableProducts" title="Disable Current Changes">
							<i class="fa fa-times"></i> Disable
						</button>
						<a onclick="$('#form-product').submit();" data-toggle="tooltip"
							class="btn btn-danger" title="<?php echo $button_delete; ?>"><i
							class="fa fa-trash-o"></i> Delete</a>
					</div>
				</h1>

				<fieldset>
					<legend>
						<i class="fa fa-list"></i> <?php echo $heading_title; ?></legend>
      <?php if($isMember) { ?>
      <div class="well">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label" for="input-name"><?php echo $column_name; ?></label>
									<div class='input-group'>
										<input type="text" name="filter_name"
											value="<?php echo $filter_name; ?>"
											placeholder="<?php echo $column_name; ?>" id="input-name"
											class="form-control" /> <span class="input-group-addon"><span
											class="fa fa-angle-double-down"></span></span>
									</div>
								</div>
							</div>

							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label" for="input-price"><?php echo $column_date; ?></label>
									<input type="text" name="filter_date"
										value="<?php echo $filter_date; ?>"
										placeholder="<?php echo $column_date; ?>" id="input-date"
										class="form-control" />
								</div>
							</div>

							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label" for="input-status"><?php echo $column_status; ?></label>
									<select name="filter_status" class="form-control"
										id="input-status">
										<option value="*"></option>
                <?php if ($filter_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } ?>
                <?php if (!is_null($filter_status) && !$filter_status) { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
								</div>
								<a onclick="filter();" class="btn btn-primary pull-right"><?php echo $button_filter; ?></a>
							</div>
						</div>
					</div>
					<form action="<?php echo $delete; ?>" method="post"
						enctype="multipart/form-data" id="form-product">
		<?php if ($enquiries) { ?>
			<div class="panel-group" id="accordion" role="tablist"
							aria-multiselectable="true">
			<?php foreach ($enquiries as $enquiry) { ?>
				<div class="panel panel-default">
								<div class="panel-heading"># <?php echo $enquiry['enquiry_id']; ?> &nbsp; <?php echo $enquiry['firstname'].' '.$enquiry['lastname']; ?>
						<a href="<?php echo $enquiry['link']; ?>" class="pull-right">View Quotations & comments</a>
								</div>
								<div class="panel-body">
									<p>	Email: <?php echo $enquiry['email']; ?><br />
						Telephone: <?php echo $enquiry['telephone']; ?><br />
						<?php foreach ($enquiry['terms'] as $term) { ?>
						<?php echo $term['type']; ?>:<?php echo $term['value']; ?><br />
						<?php } ?>
						
						<?php foreach($enquiry['address'] as $address) { ?>
						<?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?><br />
						<?php echo $address['address_1']; ?> <?php echo $address['city']; ?><br />
						<?php }?>
						<?php echo $enquiry['zone']; ?>
						<?php echo $enquiry['count']; ?>
						</p>
								</div>
								<table class="table table-bordered">
									<thead>
										<tr>
											<td class="center" style="max-width: 100px;">Sr No.</td>
											<td style="max-width: 100px;">Name</td>
											<td style="width: 400px;">Description</td>
											<td class="center" style="max-width: 100px;">Quantity</td>
											<td class="center" style="max-width: 50px;">Unit</td>
										</tr>
									</thead>
									<tbody>
						<?php foreach ($enquiry['enquiries'] as $key=>$enquiry_product) { ?>
							<tr>
											<td class="center"><?php echo $key + 1; ?></td>
							<?php if (isset($enquiry_product['link'])) { ?>
							<td><a href="<?php echo $enquiry_product['link']; ?>"> <?php echo $enquiry_product['name']; ?> </a></td>
							<?php } else { ?>
							<td><?php echo $enquiry_product['name']; ?></td>
							<?php } ?>
							<td><?php echo $enquiry_product['description']; ?><br />
								<?php if (is_array($enquiry_product['filenames'])) { foreach ($enquiry_product['filenames'] as $file) { ?>
									<a href="<?php echo 'system/upload/queries/'.$file; ?>"
												target="_Blank"><?php echo substr($file,0,strrpos($file,'.',-1)); ?></a>
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
						<div class="col-sm-6 text-right"><?php echo $results; ?></div>
					</div>
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
/*function viewquotationcomments(enquiry_id){
	$.ajax({
	url : 'index.php?route=account/customerpartner/enquiry/getQuotationSuppliers&enquiry_id='+enquiry_id, 	
	dataType:'json',
	success : function (json) {

		var html_suppliers = '<div class="col-sm-3">';
		html_suppliers += '<ul class="nav nav-pills nav-stacked" role="tablist">';

		if (json.quotes) {
	    	 $.each(json.quotes, function(key,val) {
		    	 html_suppliers += '<li role="presentation" onclick="suppliertabclick(\''+val['quote']['quote_id']+'\');"><a>'+val['info']['firstname']+' '+val['info']['lastname']+'</a></li>';
	    	 });
		}
		
		html_suppliers += '  </ul>';
		html_suppliers += '</div>';
		html_suppliers += '<div class="col-sm-9">';
		html_suppliers += '<div class="tab-content" id="commentsandquote">';
		html_suppliers += '</div>';
		html_suppliers += '</div><div class="clearfix"/>';
		
		var quotations_comments_modal = addmodal('view-quotations','');
		quotations_comments_modal.find('.modal-title').html('<h3 class="">Quotation & Comments</hr>');
		quotations_comments_modal.find('.modal-body').html(html_suppliers);
		quotations_comments_modal.modal('show');
    	
	}
	});
}
function suppliertabclick(quote_id) {
  
  var html_tab_content='';
  $.ajax({
	  url : 'index.php?route=account/customerpartner/enquiry/getSentEnquiryComment&quote_id='+quote_id,
	    dataType: 'json',
	    success : function (json) {
	    	$("#commentsandquote").html("");

	    	/* html_tab_content += '<ul class="nav nav-pills nav-stacked" role="tablist">';
	    	html_tab_content += '<li role="presentation" onclick="suppliertabclick(\''+val['quote']['quote_id']+'\');"><a>'+val['info']['firstname']+' '+val['info']['lastname']+'</a></li>';
	    	html_tab_content += '<div id ="previousconversations" style="overflow-y: scroll; height:400px;">';
	    	
		    if (json.comments) {
	    	 $.each(json.comments, function(key,val) {
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
	    	
	    	$("#commentsandquote").html(html_tab_content);
	    	$('#previousconversations').animate({scrollTop:5000}, 'slow');
	    }
  });
}
  $('body').on('click','#button-comment', function() {
		$.ajax({
			type: 'post',
			dataType: 'json',
			data: $('.quote-comments textarea[name^=\'quote\'] , .quote-comments input[name=\'quote_id\']'),
			success : function (json) {
				suppliertabclick($('.quote-comments input[name=\'quote_id\']').val());
		    }
		});
	});*/
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
