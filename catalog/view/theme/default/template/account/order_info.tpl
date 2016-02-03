<?php echo $header; ?><div id="columns">
	<div class="container">
		<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success">
			<i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger">
			<i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
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
    <div id="content" class="<?php echo $class; ?>" style="font-size:12px;"><?php echo $content_top; ?>
			<div class="col-sm-12 white">
      <h2 class="nmt"><?php echo $heading_title; ?></h2>
      <div class="row">
				<div class="col-sm-6">
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
							<td class="text-left"><?php if ($payment_method) { ?>
								<b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?><br />
								<?php } ?>
								<?php if ($shipping_method) { ?>
								<b><?php echo $text_shipping_method; ?></b> <?php echo $shipping_method; ?>
								<?php } ?></td>
						</tr>
					</tbody>
				</table>
				</div>
				<div class="col-sm-6">
				 <table class="table table-bordered table-hover">
					<thead>
						<tr>
							<td class="text-left" style="width: 50%;"><?php echo $text_payment_address; ?></td>
							<?php if ($shipping_address) { ?>
							<td class="text-left"><?php echo $text_shipping_address; ?></td>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="text-left"><?php echo $payment_address; ?></td>
							<?php if ($shipping_address) { ?>
							<td class="text-left"><?php echo $shipping_address; ?></td>
							<?php } ?>
						</tr>
					</tbody>
				</table>
				</div>
      </div>
			<?php foreach ($vendors as $vendor_id => $vendor) { ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#content" href="#panelcollapse-<?php echo $vendor_id; ?>" aria-expanded="true" aria-controls="panelcollapse-<?php echo $vendor_id; ?>"><?php echo $vendor['info']['companyname']; ?> ( Order: <?php echo $vendor['order_status']; ?> )</a>
						<?php if(in_array($vendor['order_status_id'],$pdforders_order_status_customer)) { ?>
						 <a href="<?php echo $vendor['pdf']; ?>" target="_blank" data-toggle="tooltip" title="Download Tax Invoice(PDF)" class="btn btn-info pull-right"><i class="fa fa-file-pdf-o"></i></a>
						<?php } else { ?>
						 <a data-toggle="tooltip" title="Order is not complete. PDF not available." class="btn btn-info pull-right"><i class="fa fa-file-pdf-o"></i></a>
						<?php } ?>
						<div class="clearfix"></div>
						</h4>
					</div>
					<div id="panelcollapse-<?php echo $vendor_id; ?>" class="panel-body panel-collapse collapse">					
							<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left"><?php echo $column_name; ?></td>
											<td class="text-left"><?php echo $column_model; ?></td>
											<td class="text-right"><?php echo $column_quantity; ?></td>
											<td class="text-right"><?php echo $column_price; ?></td>
											<td class="text-right"><?php echo $column_total; ?></td>
											<?php if ($vendor['products']) { ?>
											<td style="width: 20px;"></td>
											<?php } ?>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($vendor['products'] as $product) { ?>
										<tr>
											<td class="text-left"><?php echo $product['name']; ?>
												<?php foreach ($product['option'] as $option) { ?>
												<br />
												&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
												<?php } ?></td>
											<td class="text-left"><?php echo $product['model']; ?></td>
											<td class="text-right"><?php echo $product['quantity']; ?></td>
											<td class="text-right"><?php echo $product['price']; ?></td>
											<td class="text-right"><?php echo $product['total']; ?></td>
											<td class="text-right" style="white-space: nowrap;"><?php if ($product['reorder']) { ?>
												<a href="<?php echo $product['reorder']; ?>" data-toggle="tooltip" title="<?php echo $button_reorder; ?>" class="btn btn-primary"><i class="fa fa-shopping-cart"></i></a>
												<?php } ?>
												<a href="<?php echo $product['return']; ?>" data-toggle="tooltip" title="<?php echo $button_return; ?>" class="btn btn-danger"><i class="fa fa-reply"></i></a></td>
										</tr>
										<?php } ?>
									</tbody>
									<tfoot>
										<?php foreach ($vendor['totals'] as $total) { ?>
										<tr>
											<td colspan="3"></td>
											<td class="text-right"><b><?php echo $total['title']; ?></b></td>
											<td class="text-right"><?php echo $total['text']; ?></td>
											<?php if ($vendor['products']) { ?>
											<td></td>
											<?php } ?>
										</tr>
										<?php } ?>
									</tfoot>
								</table>
								<?php if ($vendor['histories']) { ?>
									<h3><?php echo $text_history; ?></h3>
									<table class="table table-bordered table-hover">
										<thead>
											<tr>
												<td class="text-left"><?php echo $column_date_added; ?></td>
												<td class="text-left"><?php echo $column_status; ?></td>
												<td class="text-left"><?php echo $column_comment; ?></td>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($vendor['histories'] as $history) { ?>
											<tr>
												<td class="text-left"><?php echo $history['date_added']; ?></td>
												<td class="text-left"><?php echo $history['status']; ?></td>
												<td class="text-left"><?php echo $history['comment']; ?></td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								<?php } ?>
							</div>
					</div>
				</div>  
			<?php } ?>
			<?php if (!$vendors) { ?>
				<div class="buttons clearfix">
					<div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
				</div>
      <?php } ?>
      <?php echo $content_bottom; ?>
      </div>
      </div>
    <?php echo $column_right; ?></div>
</div>
</div><?php echo $footer; ?>
