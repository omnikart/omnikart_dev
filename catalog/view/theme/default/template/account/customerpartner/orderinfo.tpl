<?php echo $header; ?><div id="columns">
	<div class="container">
		<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
    <div class="alert alert-success">
			<i class="fa fa-exclamation-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>

  <?php if($chkIsPartner){ ?>
    <div id="content" class="<?php echo $class; ?>" style="font-size:12px;">
    <?php echo $content_top; ?>    
    <h1>
      <?php echo $heading_title; ?>
      <div class="pull-right">
        <a href="<?php echo $continue; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
        <a href="<?php echo $order_invoice; ?>" data-toggle="tooltip" class="btn btn-primary" target="_blank" title="<?php echo $button_invoice; ?>"><i class="fa fa-save"></i></a>
      </div>
    </h1>

    <fieldset>
      <legend><i class="fa fa-list"></i> <?php echo $heading_title; ?></legend>
      <?php if(!$errorPage && $isMember) { ?> 
        <table class="table table-bordered table-hover">
						<thead>
							<tr>
								<td class="text-left" colspan="2"><?php echo $text_order_detail; ?></td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-left" style="width: 50%;"><?php if ($invoice_no) { ?>
                <b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_no; ?><br />
                <?php } ?>
                <b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?><br />
									<b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?></td>
								<td class="text-left" style="width: 50%;"><?php if ($payment_method) { ?>
                <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?><br />
                <?php } ?>
                <?php if ($shipping_method) { ?>
                <b><?php echo $text_shipping_method; ?></b> <?php echo $shipping_method; ?>
                <?php } ?></td>
							</tr>
						</tbody>
					</table>
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<td class="text-left"><?php echo $text_payment_address; ?></td>
              <?php if ($shipping_address) { ?>
              <td class="text-left"><?php echo $text_shipping_address; ?></td>
              <?php } ?>
            </tr>
						</thead>
						<tbody>
							<tr>
								<td class="left"><?php echo $payment_address; ?></td>
              <?php if ($shipping_address) { ?>
              <td class="text-left"><?php echo $shipping_address; ?></td>
              <?php } ?>
            </tr>
						</tbody>
					</table>

					<form class="form-horizontal" action="<?php echo $action; ?>"
						method="post">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td class="text-left"><?php echo $column_name; ?></td>
									<td class="text-left"><?php echo $column_model; ?></td>
									<td class="text-right"><?php echo $column_quantity; ?></td>
									<td class="text-right"><?php echo $column_transaction_status; ?></td>
									<td class="text-right"><?php echo $column_price; ?></td>
									<td class="text-right"><?php echo $column_total; ?></td>
									<td class="text-left"><?php echo $column_tracking_no; ?></td>
								</tr>
							</thead>
							<tbody>
              <?php foreach ($products as $product) { ?>
              <tr>
									<td class="text-left"><?php echo $product['name']; ?>
                  <?php  foreach ($product['option'] as $option) { ?>
                  <br /> &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                  <?php }  ?>
                </td>
									<td class="text-left"><?php echo $product['model']; ?></td>
									<td class="text-right"><?php echo $product['quantity']; ?></td>
									<td class="text-right"><?php echo $product['paid_status']; ?></td>
									<td class="text-right"><?php echo $product['price']; ?></td>
									<td class="text-right"><?php echo $product['total']; ?></td>
									<td class="text-left">
                  <?php if($product['tracking']){ ?>
                    <?php echo $product['tracking']; ?>
                  <?php }else{ ?>
                    <input type="text" class="form-control" name="tracking[<?php echo $product['order_product_id'];?>]" placeholder="<?php echo $column_tracking_no; ?>" />
                  <?php $i = true; } ?>
                </td>
								</tr>
              <?php } ?>
              <?php foreach ($vouchers as $voucher) { ?>
              <tr>
									<td class="text-left"><?php echo $voucher['description']; ?></td>
									<td class="text-left"></td>
									<td class="text-right">1</td>
									<td class="text-right"><?php echo $voucher['amount']; ?></td>
									<td class="text-right"><?php echo $voucher['amount']; ?></td>
								</tr>
              <?php }  ?>
            </tbody>
							<tfoot>
              <?php foreach ($totals as $total) { ?>
              <tr>
									<td class="text-right" colspan="5"><b><?php echo $column_total; ?></b></td>
									<td class="text-right"><?php echo $total['total']; ?></td>
									<td class="text-right"><?php if(isset($i)){ ?><input
										type="submit" style="width: 100%" class="btn btn-info" /><?php } ?></td>
								</tr>
              <?php } ?>
            </tfoot>
						</table>
					</form>

        <?php if ($comment) { ?>
        <table class="table table-bordered">
						<thead>
							<tr>
								<td class="text-left"><?php echo $text_comment; ?></td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-left"><?php echo $comment; ?></td>
							</tr>
						</tbody>
					</table>
        <?php } ?>
        
        <div class="alert alert-info">
						<i class="fa fa-exclamation-circle"></i> <?php echo $history_info; ?></div>

					<form class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-order"><?php echo $entry_order_status; ?></label>
							<div class="col-sm-10">
								<select name="order_status_id" class="form-control">
                <?php if($wksellerorderstatus){ ?>
                  <?php foreach ($order_statuses as $order_statuses) { ?>
                    <?php if ($order_statuses['order_status_id'] == $order_status_id) { ?>
                      <option
										value="<?php echo $order_statuses['order_status_id']; ?>"
										selected="selected"><?php echo $order_statuses['name']; ?></option>
                    <?php } else { ?>
                      <option
										value="<?php echo $order_statuses['order_status_id']; ?>"><?php echo $order_statuses['name']; ?></option>
                    <?php } ?>
                  <?php } ?>
                <?php }else{ ?>
                  <?php foreach ($order_statuses as $order_statuses) { ?>
                    <?php if ($order_statuses['order_status_id'] == $order_status_id) { ?>
                    <option
										value="<?php echo $order_statuses['order_status_id']; ?>"
										selected="selected"><?php echo $order_statuses['name']; ?></option>
                    <?php } ?>
                  <?php } ?>
                <?php } ?>
              </select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-notify"><?php echo $entry_notify; ?></label>
							<div class="col-sm-10">
								<input type="checkbox" name="notify" id="input-notify" value="1" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-notifyAdmin"><?php echo $entry_notifyadmin; ?></label>
							<div class="col-sm-10">
								<input type="checkbox" name="notify" id="input-notifyAdmin"
									value="1" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-comment"><?php echo $entry_comment; ?></label>
							<div class="col-sm-10">
								<textarea name="comment" cols="40" rows="8" class="form-control"
									id="input-comment"></textarea>
							</div>
						</div>

						<a id="button-history" class="btn btn-default pull-right"><?php echo $button_add_history; ?></a>

						</from>

						<div class="clear"></div>

        <?php if ($histories) { ?>
        <h2><?php echo $text_history; ?></h2>
						<table class="table table-bordered table-hover" id="history">
							<thead>
								<tr>
									<td class="text-left"><?php echo $column_date_added; ?></td>
									<td class="text-left"><?php echo $column_status; ?></td>
									<td class="text-left"><?php echo $column_comment; ?></td>
								</tr>
							</thead>
							<tbody>
            <?php foreach ($histories as $history) { ?>
            <tr>
									<td class="text-left"><?php echo $history['date_added']; ?></td>
									<td class="text-left"><?php echo $history['status']; ?></td>
									<td class="text-left"><?php echo $history['comment']; ?></td>
								</tr>
            <?php } ?>
          </tbody>
						</table>
        <?php } ?>
        <?php }else{ ?>
        <div>
          <?php echo $error_page_order; ?>
        </div>
        <?php } ?>

    
				
				</fieldset>
      <?php echo $content_bottom; ?> 
    </div>
    <?php
		
} else {
			echo "<h2 class='text-danger'> For Become Seller inform Admin </h2>";
		}
		?>
    <?php echo $column_right; ?>  
  </div>
	</div>

	<script>
$('#button-history').on('click', function() {
  $.ajax({
    url: 'index.php?route=account/customerpartner/orderinfo/history&order_id=<?php echo $order_id; ?>&customerpartner_order_id=<?php echo $customerpartner_order_id; ?>',
    type: 'post',
    dataType: 'json',
    data: 'order_status_id=' + encodeURIComponent($('select[name=\'order_status_id\']').val()) + '&notify=' + encodeURIComponent($('input[name=\'notify\']').prop('checked') ? 1 : 0) + '&notifyadmin=' + encodeURIComponent($('input[name=\'notifyAdmin\']').prop('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),
    beforeSend: function() {
      $('.alert-success, .alert-warning').remove();
      $('#button-history').attr('disabled', true);
      $('#history').before('<div class="alert alert-warning"><i class="fa fa-refresh fa-spin"></i> <?php echo $text_wait; ?></div>');
      
    },
    complete: function() {
      $('#button-history').attr('disabled', false);
      $('.alert-warning').remove();
    },
    success: function(json) {
      if(json['success']){
        $('#history').before('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + json['success'] + '</div>');

        var d = new Date();
        var strDate = d.getDate() + "/" + (d.getMonth()+1) + "/" + d.getFullYear();

        $('#history').append('<tr><td class="text-left">'+strDate+'</td><td class="text-left">'+$('select[name=\'order_status_id\'] option:selected').text()+'</td><td class="text-left">'+$('textarea[name=\'comment\']').val()+'</td></tr>');  
        $('textarea[name=\'comment\']').val('');        
      }else{
        alert('Please try after some time,some error Occur !! ');
      }
    }
  });
});
</script>
</div><?php echo $footer; ?>