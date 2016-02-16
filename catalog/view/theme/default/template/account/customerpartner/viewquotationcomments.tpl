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
						<li role="presentation" onclick="suppliertabclick(<?php echo $quote['quote']['quote_id']; ?>);">
							<a><?php  echo $quote['info']['firstname'] . ' ' . $quote['info']['lastname']; ?></a>
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

function suppliertabclick(quote_id) {
  
  var html_tab_content='';
  $.ajax({
	  url : 'index.php?route=account/customerpartner/enquiry/getSentEnquiryComment&quote_id='+quote_id,
	    dataType: 'json',
	    success : function (json) {
	    	$("#commentsandquote").html("");

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
