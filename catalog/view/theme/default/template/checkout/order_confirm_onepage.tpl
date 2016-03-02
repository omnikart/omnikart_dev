<!--<h1>confirm order onepage</h1>-->
<?php if (!isset($redirect)) { ?>
<style type="text/css">
#confirm-checkout tr td {
	vertical-align: middle;
}

#confirm-checkout table {
	background-color: #fff;
}

@media ( min-width : 768px) {
	#confirm-checkout .dl-horizontal dt {
		width: auto;
	}
}
</style>
<div class="row">
	<div class="col-sm-12">
     <div class="table-responsive">
				<table class="table table-striped table-bordered" style="border-bottom: 3px solid #dff0d8;">
            <thead>
                <tr class="">
                    <th colspan="2" class="name"><?php echo $column_name; ?></th>

                    
                     <?php if($mmos_checkout['enable_pmodel'] == 1) { ?>
                   
                    <th class="model" style="width:100px;"><?php echo $column_model; ?></th>  
                     <?php }  ?>
                   
                    <th class="quantity text-center" style="width:100px;"><?php echo $column_quantity; ?></th>
                    <th class="price" style="width:100px;"><?php echo $column_price; ?></th>
                    <th class="total" style="width:100px;"><?php echo $column_total; ?></th>
                    <th class="total" style="width:100px;">Total Tax</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product) { ?>  
                    <?php if ($product['recurring']): ?>
                        <tr>
					<td colspan="6" style="border: none;"><image
							src="catalog/view/theme/default/image/reorder.png" alt=""
							title="" style="float:left;" /><span
						style="float: left; line-height: 18px; margin-left: 10px;"> <strong><?php echo $text_recurring_item ?></strong>
                                    <?php echo $product['profile_description']?>
                            </td>
				</tr>
                    <?php endif; ?>
                    <tr>
                        <td class="name" style="width:80px;">
                               <img class="product-image" src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />   
                             <input  class="product-name" type="hidden" value="<?php echo $product['name']; ?>"/>
                             <input  class="product-image-popup" type="hidden" value="<?php echo $product['image_popup']; ?>"/>
                        </td>
                        <td class="name">
                            <a href="<?php echo $product['href']; ?>">
                              
                                <span style="padding-left: 1em;"><?php echo $product['name']; ?></span>
                               
                            </a>

                            <?php foreach ($product['option'] as $option) { ?>
                                <br /> &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                            <?php } ?>
                            <?php if ($product['recurring']): ?>
                                <br /> &nbsp;<small><?php echo $text_payment_profile ?>: <?php echo $product['profile_name'] ?></small>
                            <?php endif; ?>
                        </td>
                        
                        <?php if($mmos_checkout['enable_pmodel'] == 1) { ?>
                        <td class="model"><?php echo $product['model']; ?></td>
                        
                        <?php } ?>
                        <td class="quantity text-center">
							<div class="input-group input-group-sm">
								<span class="input-group-btn">
									<button type="button" class="btn btn-<?php echo ($css['checkout_theme'] == 'standar') ?  'warning' : $css['checkout_theme'];  ?> btn-decrease"><i class="fa fa-minus"></i></button>														
								</span>
								<input type="hidden" class="original-quantity form-control" value="<?php echo $product['quantity']; ?>"/>
								<input type="text" class="product-quantity form-control text-center " name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="3" onkeypress="validate_quantity_keypress(event)" />														
								<span class="input-group-btn">
									<button type="button" class="btn btn-<?php echo ($css['checkout_theme'] == 'standar') ?  'warning' : $css['checkout_theme'];  ?> btn-increase"><i class="fa fa-plus"></i></span></button>
								</span>
							</div>
						</td>
					<td class="price"><?php echo $product['price']; ?></td>
					<td class="total"><?php echo $product['total']; ?></td>
					<td class="total"><?php echo $product['tax']; ?></td>
					<td class="text-right">

						<button type="button" alt="<?php echo $button_remove; ?>"
							title="<?php echo $button_remove; ?>"
							class="btn-remove btn btn-danger btn-xs">
							<span class="glyphicon glyphicon-remove"></span> <input
								type="hidden" value="<?php echo $product['key']; ?>" />
						</button>


					</td>
				</tr>
                <?php } ?>
                </tbody>
							</table>
							<table>
                <?php foreach ($vouchers as $voucher) { ?>
                    <tr>
					<td class="name" colspan="3"><?php echo $voucher['description']; ?></td>

					<td class="quantity"><input type="text" value="1"
						class="form-control text-center" readonly="readonly" /></td>
					<td class="price"><?php echo $voucher['amount']; ?></td>
					<td class="total"><?php echo $voucher['amount']; ?></td>
					<td>

						<button type="button" alt="<?php echo $button_remove; ?>"
							title="<?php echo $button_remove; ?>"
							class="btn-remove btn btn-danger btn-xs">
							<span class="glyphicon glyphicon-remove"></span> <input
								type="hidden" value="<?php echo $voucher['key']; ?>" />
						</button>

					</td>
				</tr>
					<?php } ?>
				 </table>
					<table class="pull-right">
						<?php $i = 0; ?>
						<?php foreach ($totals as $total) { ?>
								<tr class="" style="border: none;">
										<td colspan="<?php echo ($mmos_checkout['enable_pmodel'] == 1) ? '5' : '4'; ?>" class="price text-right "><b><?php echo $total['title']; ?>:</b></td>
										<td class="total" colspan="2"><?php echo $total['text']; ?></td>
								</tr>
								<?php $i++; ?>
						<?php } ?>
				 </table>
 
    </div>

<?php if (!empty($comment)) { ?>
<div>

	<span class="glyphicon glyphicon-comment"></span> <?php echo $text_comment; ?>
		<div class="form-group">
		<p class="text-info"><?php echo $comment; ?></p>
	
	</div>

</div>
<hr>
<?php } ?>
    
    
    
    <?php if (!$shipping_required && !empty($no_shipping_required)) { ?>
<div class="alert alert-warning" role="alert">
	<span class="glyphicon glyphicon-exclamation-sign"></span> <span><?php echo $no_shipping_required; ?></span>
</div>
<?php } ?>
    
    
   
      <?php if ($shipping_required) { ?>
<hr>
<dl class="dl-horizontal">
	<dt>
		<span class="glyphicon glyphicon-send"></span></span> <?php echo $text_checkout_shipping_address; ?>:</dt>
	<dd><?php echo $shipping_address; ?></dd>
</dl>
<hr>
<dl class="dl-horizontal">
	<dt>
		<span class="glyphicon glyphicon-send"></span> <?php echo $text_checkout_shipping_method; ?>:</dt>
	<dd><?php echo $shipping_method ?></dd>
</dl>
<hr>

<?php } ?>


<dl class="dl-horizontal">
	<dt>
		<span class="glyphicon glyphicon-credit-card"></span> <?php echo $text_checkout_payment_address; ?>:</dt>
	<dd><?php echo $payment_address; ?></dd>
</dl>
<hr>
<dl class="dl-horizontal">
	<dt>
		<span class="glyphicon glyphicon-credit-card"></span> <?php echo $text_checkout_payment_method; ?>:</dt>
	<dd><?php echo $payment_method; ?></dd>
</dl>

<div class="clearfix">
	<div class="payment">
        <?php echo $payment; ?>
    </div>
</div>

<div class="clearfix">
	<button id="btn-edit-order" class="btn btn-<?php
	if ($css ['checkout_theme'] == 'standar') {
		echo 'primary';
	} else {
		echo $css ['checkout_theme'];
	}
	?>" style="<?php
	if (! empty ( $css ['edit_order_btn_color'] )) {
		echo "background-color:{$css['edit_order_btn_color']}!important; background-image:none;";
	}
	?>">
		<i class="fa fa-pencil"></i>&nbsp;<span><?php echo $edit_order_button; ?></span>
	</button>
</div>
	</div>

<script type="text/javascript"><!--
        $('#cart_order_title').html('<i class="fa fa-list" style="font-size: 1.5em;"></i>&nbsp;<strong><?php echo $text_order_detail; ?></strong>');
        $('#confirm-checkout').show();
        $('#billing-shipping-details').hide();
        $('#billing-shipping-methods').hide();
        $('#voucher-coupon').hide();
        $('body').animate({
            scrollTop: $("#checkout-container").parent().offset().top
        }, 500);


        $('#methods-summary-panel').removeClass('col-sm-9').addClass('col-sm-12');
        $('#btn-edit-order').click(function () {
            $('#cart_order_title').html('<i class="fa fa-shopping-cart" style="font-size: 1.5em;"></i>&nbsp;<strong><?php echo $text_cart; ?></strong>');
            $('#confirm-checkout').hide();
            $('#confirm').show();
            $('#methods-summary-panel').removeClass('col-sm-12').addClass('col-sm-9');
            $.when($('#billing-shipping-details').slideDown().promise(), $('#billing-shipping-methods').slideDown().promise(), $('#voucher-coupon').slideDown().promise()).done(function () {

                $('body').animate({
                    scrollTop: $("#checkout-container").parent().offset().top
                }, 500);
            });

        });

        $('#confirm-checkout img.product-image').each(function () {
            var product_name = $(this).siblings('.product-name').val();
            var urlproduct = $(this).siblings('.product-image-popup').val();
            $(this).qtip({
                content: {
                    text: '<div>'
                            + '<div><h3 class="text-center">' + product_name + '</h3></div>'
                            + '<div style="text-align: center!important;"><img src="' + urlproduct + '" />' + '</div>'

                            + '</div>'
                },
                position: {
                    my: 'right center',
                    target: 'mouse',
                    adjust: {
                        screen: true
                    },
                    viewport: $(window) // Keep it on-screen at all times if possible
                },
                show: {
                    ready: true,
                },
                hide: {
                    when: 'mouseout', fixed: true
                },
                style: 'qtip-wiki'
            });
        });
        //--></script>
<?php } else { ?>
<script type="text/javascript"><!--

        var redirect = '<?php echo $redirect; ?>';
                <?php if ($mmos_checkout['debug']) { ?>
                var messages = [];
                <?php foreach ($error as $err) { ?>
                messages.push('<?php echo $err; ?>');
                <?php } ?>
                var message = messages.join("\r\n");
        console.log(messages);
        console.log("Redirect: " + redirect);
        if (confirm(message + "\r\nRedirect: " + redirect)) {
        location = redirect;
        }
        <?php } else { ?>
                location = redirect;
                <?php } ?>
                //--></script> 
    <?php } ?>

