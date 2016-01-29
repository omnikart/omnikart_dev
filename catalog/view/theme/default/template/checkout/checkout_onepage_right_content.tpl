
<!-- Shipping/Payment Method -->
<div class="row" id="billing-shipping-methods">

    <?php if ($shipping_required) { ?>
        <!-- Shipping Method -->
	<div class="col-sm-6 col-xs-12">

		<div id="shipping-method-panel" class="panel panel-<?php
					if ($css ['checkout_theme'] == 'standar') {
						echo 'warning';
					} else {
						echo $css ['checkout_theme'];
					}
					?>" style="<?php
					if (! empty ( $css ['shipping_method_panel_color'] )) {
						echo "border-color:{$css['shipping_method_panel_color']}!important;";
					}
					?>">
			<div class="checkout-overlay checkout-overlay-dark hidden"></div>
			<div class="panel-heading" style="<?php
					if (! empty ( $css ['shipping_method_panel_color'] )) {
						echo "background-color:{$css['shipping_method_panel_color']}!important;border-color:{$css['shipping_method_panel_color']}!important;";
					}
					?>">
				<h3 class="panel-title">
					<!--<span class="glyphicon glyphicon-send"></span>&nbsp;-->
					<i class="fa fa-paper-plane-o" style="font-size: 1.5em;"></i>&nbsp;
					<strong><?php echo $text_checkout_shipping_method; ?></strong> <span
						id="shipping-method-wait"
						class="glyphicon glyphicon-refresh glyphicon-spin-animate hidden"></span>
				</h3>
			</div>
			<div class="panel-body">
                    <?php if (!empty($tips['shipping_method_tip'][$config_language_id])) { ?>
                        <i class="fa fa-lightbulb-o"></i>&nbsp;<small
					class="text-warning"><i><?php echo $tips['shipping_method_tip'][$config_language_id]; ?></i></small>
                    <?php } ?>

                    <div id="shipping-method">

					<div class="checkout-content-onepage">
                            <?php echo $shippingMethod; ?>
                        </div>
				</div>
			</div>
		</div>
	</div>

	<!-- Payment Method -->
	<div class="col-sm-6 col-xs-12">

		<div  id="payment-method-panel" class="panel panel-<?php
					if ($css ['checkout_theme'] == 'standar') {
						echo 'warning';
					} else {
						echo $css ['checkout_theme'];
					}
					?>" style="<?php
					if (! empty ( $css ['payment_method_panel_color'] )) {
						echo "border-color:{$css['payment_method_panel_color']}!important;";
					}
					?>">
			<div class="checkout-overlay checkout-overlay-dark hidden"></div>
			<div class="panel-heading" style="<?php
					if (! empty ( $css ['payment_method_panel_color'] )) {
						echo "background-color:{$css['payment_method_panel_color']}!important;border-color:{$css['payment_method_panel_color']}!important;";
					}
					?>">
				<h3 class="panel-title">
					<!--<span class="glyphicon glyphicon-cog"></span>&nbsp;-->
					<i class="fa fa-credit-card" style="font-size: 1.5em;"></i>&nbsp; <strong><?php echo $text_checkout_payment_method; ?></strong>
					<span id="payment-method-wait"
						class="glyphicon glyphicon-refresh glyphicon-spin-animate hidden"></span>
				</h3>
			</div>
			<div class="panel-body">
                    <?php if (!empty($tips['payment_method_tip'][$config_language_id])) { ?>
                        <i class="fa fa-lightbulb-o"></i>&nbsp;<small
					class="text-warning"><i><?php echo $tips['payment_method_tip'][$config_language_id]; ?></i></small>
                    <?php } ?>

                    <div id="payment-method">

					<div class="checkout-content-onepage">
                            <?php echo $paymentMethod; ?>
                        </div>
				</div>
			</div>
		</div>
	</div>
    <?php } else { ?>
        <!-- Payment Method -->
	<div class="col-xs-12">

		<div id="payment-method-panel" class="panel panel-<?php
					if ($css ['checkout_theme'] == 'standar') {
						echo 'warning';
					} else {
						echo $css ['checkout_theme'];
					}
					?>" style="<?php
					if (! empty ( $css ['payment_method_panel_color'] )) {
						echo "border-color:{$css['payment_method_panel_color']}!important;";
					}
					?>">
			<div class="checkout-overlay checkout-overlay-dark hidden"></div>
			<div class="panel-heading" style="<?php
					if (! empty ( $css ['payment_method_panel_color'] )) {
						echo "background-color:{$css['payment_method_panel_color']}!important;border-color:{$css['payment_method_panel_color']}!important;";
					}
					?>">
				<h3 class="panel-title">
					<!--<span class="glyphicon glyphicon-cog"></span>&nbsp;-->
					<i class="fa fa-credit-card" style="font-size: 1.5em;"></i>&nbsp; <strong><?php echo $text_checkout_payment_method; ?></strong>
					<span id="payment-method-wait"
						class="glyphicon glyphicon-refresh glyphicon-spin-animate hidden"></span>
				</h3>
			</div>
			<div class="panel-body">
                    <?php if (!empty($tips['payment_method_tip'][$config_language_id])) { ?>
                        <i class="fa fa-lightbulb-o"></i>&nbsp;<small
					class="text-warning"><i><?php echo $tips['payment_method_tip'][$config_language_id]; ?></i></small>
                    <?php } ?>
                    <div id="payment-method">

					<div class="checkout-content-onepage">
                            <?php echo $paymentMethod; ?>
                        </div>
				</div>
			</div>
		</div>
	</div>
    <?php } ?>

</div>

<?php

if ($mmos_checkout ['enable_giftcart'] == 1 || $mmos_checkout ['enable_counpon'] == 1) :
	
	if (($mmos_checkout ['enable_giftcart'] - $mmos_checkout ['enable_counpon']) == 0) {
		$couponclass = 'col-sm-6';
	} else {
		
		$couponclass = 'col-sm-12';
	}
	
	?>


<!-- Voucher/Coupon -->

<?php if($show_reward == '1'){ ?>
<div class="row" id="reward">
	<div class="col-xs-12">
		<div
			class="panel panel-<?php
		if ($css ['checkout_theme'] == 'standar') {
			echo 'success';
		} else {
			echo $css ['checkout_theme'];
		}
		?>">
			<div class="panel-heading" role="tab" id="headingreward">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion"
						href="#collapsereward" aria-expanded="true"
						aria-controls="collapsereward">
         <?php echo $heading_title; ?>
         <span class="glyphicon glyphicon-chevron-down"
						aria-hidden="true"
						style="position: absolute; top: 10px; right: 10px;"></span>
					</a>
				</h4>
			</div>
			<div id="collapsereward" class="panel-collapse collapse"
				role="tabpanel" aria-labelledby="collapsereward">
				<div class="panel-body">
					<div class="row">
						<div class="form-group">
							<div class="col-xs-12">
								<div class="input-group input-group-sm">
									<input type="text" name="reward" class="form-control"
										placeholder="<?php echo $entry_reward; ?>"> <span
										class="input-group-btn">
										<button id="apply-reward" class="btn btn-<?php
		if ($css ['checkout_theme'] == 'standar') {
			echo 'warning';
		} else {
			echo $css ['checkout_theme'];
		}
		?>" type="button" style="<?php
		if (! empty ( $css ['voucher_coupon_btn_color'] )) {
			echo "background-color:{$css['voucher_coupon_btn_color']}!important;background-image:none;";
		}
		?>">
											<span class="hidden-sm hidden-xs"><?php echo $button_reward; ?></span>
											<span class="glyphicon glyphicon-ok hidden-lg hidden-md"></span>
										</button>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<div class="row" id="voucher-coupon">
	<div class="col-xs-12">
		<div id="voucher-coupon-panel"
			class="panel panel-<?php
	if ($css ['checkout_theme'] == 'standar') {
		echo 'success';
	} else {
		echo $css ['checkout_theme'];
	}
	?>">
			<div class="checkout-overlay checkout-overlay-dark hidden"></div>
			<div class="panel-heading" role="tab" id="headingvoucher-coupon">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion"
						href="#collapsevoucher-coupon" aria-expanded="true"
						aria-controls="collapsevoucher-coupon">
       <?php echo $entry_heading_voucher.' & '.$entry_heading_coupon;?> 
        <span class="glyphicon glyphicon-chevron-down"
						aria-hidden="true"
						style="position: absolute; top: 10px; right: 10px;"></span>
					</a>

				</h4>
			</div>
			<div id="collapsevoucher-coupon" class="panel-collapse collapse"
				role="tabpanel" aria-labelledby="collapsevoucher-coupon">



				<div class="panel-body">
					<div class="row">

						<div class="form-group">
                         <?php if($mmos_checkout['enable_giftcart'] == 1 ):  ?>
                    <!-- Voucher -->
							<div class="<?php echo  $couponclass; ?> col-xs-12">
								<div class="input-group input-group-sm">
									<!--                            <div class="input-group-btn">
                                                                <button type="button" class="btn btn-default" id="voucher-label"><strong><?php echo $entry_voucher; ?></strong></button>
                                                            </div> /btn-group -->

									<input type="text" name="voucher" class="form-control"
										placeholder="<?php echo $entry_voucher; ?>"> <span
										class="input-group-btn">
										<button id="apply-voucher" class="btn btn-<?php
		if ($css ['checkout_theme'] == 'standar') {
			echo 'warning';
		} else {
			echo $css ['checkout_theme'];
		}
		?>" type="button" style="<?php
		if (! empty ( $css ['voucher_coupon_btn_color'] )) {
			echo "background-color:{$css['voucher_coupon_btn_color']}!important;background-image:none;";
		}
		?>">
											<span class="hidden-sm hidden-xs"><?php echo $button_voucher; ?></span>
											<span class="glyphicon glyphicon-ok hidden-lg hidden-md"></span>
										</button>
									</span>
								</div>
							</div>
                        <?php endif; ?>
                        <?php if($mmos_checkout['enable_counpon'] == 1 ):  ?>
                        <div
								class="<?php echo  $couponclass; ?> col-xs-12">
								<div class="input-group  input-group-sm">
									<!--                            <div class="input-group-btn">
                                                                <button type="button" class="btn btn-default" id="coupon-label"><strong><?php echo $entry_coupon; ?></strong></button>
                                                            </div> /btn-group -->
									<input type="text" name="coupon" class="form-control"
										placeholder="<?php echo $entry_coupon; ?>"> <span
										class="input-group-btn">
										<button id="apply-coupon" class="btn btn-<?php
		if ($css ['checkout_theme'] == 'standar') {
			echo 'warning';
		} else {
			echo $css ['checkout_theme'];
		}
		?>" type="button"style="<?php
		if (! empty ( $css ['voucher_coupon_btn_color'] )) {
			echo "background-color:{$css['voucher_coupon_btn_color']}!important;background-image:none;";
		}
		?>">
											<span class="hidden-sm hidden-xs"><?php echo $button_coupon; ?></span>
											<span class="glyphicon glyphicon-ok hidden-lg hidden-md"></span>
										</button>

									</span>
								</div>
							</div>
                                            <?php endif; ?>
                    </div>
					</div>
				</div>
			</div>
		</div>

	</div>


</div>
<?php endif; ?>
<!-- Order summary -->
<div id="confirm-panel" class="panel panel-<?php
if ($css ['checkout_theme'] == 'standar') {
	echo 'default';
} else {
	echo $css ['checkout_theme'];
}
?>"  style="<?php
if (! empty ( $css ['cart_order_panel_color'] )) {
	echo "border-color:{$css['cart_order_panel_color']}!important;";
}
?>">

	<div class="checkout-overlay checkout-overlay-dark hidden"></div>

	<div class="panel-heading"  style="<?php
	if (! empty ( $css ['cart_order_panel_color'] )) {
		echo "background-color:{$css['cart_order_panel_color']}!important;border-color:{$css['cart_order_panel_color']}!important;";
	}
	?>">

		<h3 class="panel-title">
			<!--<span class="glyphicon glyphicon-list-alt"></span>&nbsp;-->
			<!--<i class="fa fa-list"  style="font-size: 1.5em;"></i>&nbsp;-->
			<div id="cart_order_title">
				<i class="fa fa-shopping-cart" style="font-size: 1.5em;"></i>&nbsp;<strong><?php echo $text_cart; ?></strong>
			</div>


			<span id="summary-wait"
				class="glyphicon glyphicon-refresh glyphicon-spin-animate hidden"></span>
		</h3>

	</div>
	<div class="panel-body">
		<div id="confirm" class="checkout-content-onepage">
            <?php echo $orderSummary; ?>
        </div>
		<div id="confirm-checkout" style="display: none;"></div>

	</div>
</div>

<script type="text/javascript"><!--
<?php
if ($css ['checkout_theme'] == 'standar') {
	$class = 'primary';
} else {
	$class = $css ['checkout_theme'];
}
?>
$('#btn-tab-cart').on('click', function() {
    $(this).removeClass('btn-default').addClass('active btn-<?php echo $class; ?>');
    $('#btn-tab-confirm').removeClass('active btn-<?php echo $class; ?>').addClass('btn-default');
    $('.checkout-overlay').addClass('hidden');
    $('#confirm-checkout').hide();
    $('#confirm').slideDown();
});
$('#btn-tab-confirm').on('click', function() {
    $(this).removeClass('btn-default').addClass('active btn-<?php echo $class; ?>');
    $('#btn-tab-cart').removeClass('active btn-<?php echo $class; ?>').addClass('btn-default');
    $('.checkout-overlay').removeClass('hidden');
    $('#checkout-overlay').addClass('hidden');
    $('#confirm-panel .checkout-overlay').addClass('hidden');
    $('#btn-confirm-order').trigger('click');
});
//--></script>
