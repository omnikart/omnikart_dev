<div id="cart">
  <button type="button" data-toggle="modal" data-target="#cart_modal" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary btn-block dropdown-toggle"><i class="fa fa-shopping-cart"></i> <span id="cart-total"><?php echo $text_items; ?></span></button>
</div> 
<div class="modal fade" id="cart_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
        	<div class="modal-body"><!-- edited latest -->
        	<div id="cart-content">
				<?php if ($products) { ?>

							<table class="table table-striped">
									<tr>
										<th class="text-center" style="width:90px;">Image</th>
										<th class="text-left">Product</th>
										<th class="text-right" style="width:100px;">Price</th>
										<th class="text-right" style="width:100px;">Quantity</th>
										<th class="text-right" style="width:100px;">Subtotal</th>
										<th class="text-right" style="width:150px;">Price Incl. Tax</th>
										<th class="text-center" style="width:50px;"></tr>
									</tr>
									<?php foreach ($products as $product) { ?>
									<tr>
										<td class="text-center"><?php if ($product['thumb']) { ?>
											<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
											<?php } ?></td>
										<td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
											<?php if ($product['option']) { ?>
											<?php foreach ($product['option'] as $option) { ?>
											<br />
											- <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
											<?php } ?>
											<?php } ?>
											<?php if ($product['recurring']) { ?>
											<br />
											- <small><?php echo $text_recurring; ?> <?php echo $product['recurring']; ?></small>
											<?php } ?></td>
										<td class="text-right" style="width:100px;"><?php echo $product['price']; ?></td>
										<td class="text-right" style="width:100px;">x <?php echo $product['quantity']; ?></td>
										<td class="text-right"  style="width:100px;"><?php echo $product['total']; ?></td>
										<td class="text-right"><?php echo $product['totaltax']; ?></td>
										<td class="text-center"><button type="button" onclick="cart.remove('<?php echo $product['key']; ?>');" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>
									</tr>
									<?php } ?>
									<?php foreach ($vouchers as $voucher) { ?>
									<tr>
										<td class="text-center"></td>
										<td class="text-left"><?php echo $voucher['description']; ?></td>
										<td class="text-right">x&nbsp;1</td>
										<td class="text-right"><?php echo $voucher['amount']; ?></td>
										<td class="text-center text-danger"><button type="button" onclick="voucher.remove('<?php echo $voucher['key']; ?>');" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>
									</tr>
									<?php } ?>
								</table>
								<div>
									<table class="pull-right">
										<?php foreach ($totals as $total) { ?>
										<tr>
											<td class="text-right"><strong><?php echo $total['title']; ?></strong></td>
											<td class="text-right"><?php echo $total['text']; ?></td>
										</tr>
										<?php } ?>
									</table>
									<div class="clearfix"></div>
								</div>

					<p class="text-right"><a href="<?php echo $cart; ?>"><strong><i class="fa fa-shopping-cart"></i> <?php echo $text_cart; ?></strong></a>&nbsp;&nbsp;&nbsp;<a href="<?php echo $checkout; ?>"><strong><i class="fa fa-share"></i> <?php echo $text_checkout; ?></strong></a></p>
				<?php } ?>
			</div>
			</div>
		</div>
	</div>
</div>
