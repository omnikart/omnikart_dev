<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
 		<fieldset>
 			<form method="post" action="<?php echo $savecart; ?>" id="form1">
 			<div class="row">
 				<div class="col-sm-4">
		 			<div class="form-group">
	 					<label class="control-label" for="cart-name">Enter a Short Name for Quick Order</label>
				  		<input type="text" name="name" value="" id="cart-name" class="form-control" />
	            	</div>
     			</div>
     			<div class="col-sm-4">
		 			<div class="form-group">
	 					<label class="control-label" for="pick-date">Pick a Date</label>
				  		<div class="input-group date">
		            		<input type="text" name="date" value="" data-toggle="tooltip" title="Click right button to pick date!" data-date-format="YYYY-MM-DD" id="pick-date" class="form-control" />
		            		<span class="input-group-btn">
			            		<button data-toggle="tooltip" title="Click here to pick date!" type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
	        		     		<button type="button" onclick="$('#form1').submit();" data-toggle="tooltip" title="Click here submit" data-loading-text="Loading..." class="btn btn-default">Schedule Cart</button>
		            		</span>
		            	</div>
	            	</div>
     			</div>
     			
			</div>	
     		</form>
     		
        <fieldset> 
        
        <?php if ($carts) { ?>
        <div class="table-responsive">
			<table class="table table-bordered table-hover">
				<?php foreach ($carts as $key => $cart) { ?>
					<tr id="tr-<?php echo $key; ?>">
						<td class="col-sm-3"><?php echo $cart['name']; ?> <input type="hidden" name="id" value="<?php echo $cart['id']; ?>" /></td>
						<td class="col-sm-3">
					  		<div class="input-group date">
								<input type="text" name="date" value="<?php echo $cart['date']; ?>" data-toggle="tooltip" title="Click right button to pick date!" data-date-format="YYYY-MM-DD" id="pick-date" class="form-control" />
			            		<span class="input-group-btn">
				            		<button data-toggle="tooltip" title="Click here to pick date!" type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
			            		</span>
		            		</div>
						</td>
						<td class="col-sm-5"><ul>
							<?php foreach ($cart['products'] as $product) { ?>
								<li><?php echo $product['name']; ?> - <?php echo $product['quantity']; ?></li>
							<?php } ?>
						</ul></td>
						<td class="col-sm-1">
						<a data-original-title="Edit" data-toggle="tooltip" title="Save Current Cart" class="btn btn-primary" onclick="updatecart('tr-<?php echo $key; ?>')"><i class="fa fa-save"></i></a>
						</td>
					</tr>
				<?php } ?>
			</table>
		</div>
		<?php } ?>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});

$('.time').datetimepicker({
	pickDate: false
});

function updatecart(id) {
	$.ajax({
	url: 'index.php?route=checkout/orderlater/updatecart',
	type: 'post',
	data: $('#'+id+ ' input[type=\'text\'], #'+id+ ' input[type=\'hidden\']'),
	dataType: 'json',
	beforeSend: function() {
		$('#button-cart').button('loading');
	},
	complete: function() {
		$('#button-cart').button('reset');
	},
	success: function(json) {
		$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
	}
	});

}

//--></script>
<?php echo $footer; ?>
