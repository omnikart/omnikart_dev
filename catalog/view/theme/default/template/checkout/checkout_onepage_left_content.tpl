<!-- CHECKOUT OPTIONS & BILLING ADDRESS -->
<div class="panel panel-<?php
if ($css ['checkout_theme'] == 'standar') {
	echo 'warning';
} else {
	echo $css ['checkout_theme'];
}
?>" style="<?php
if (! empty ( $css ['billing_panel_color'] )) {
	echo "border-color:{$css['billing_panel_color']}!important;";
}
?>">
	<div class="checkout-overlay checkout-overlay-dark hidden"></div>
	<div class="panel-heading" style="<?php
	if (! empty ( $css ['billing_panel_color'] )) {
		echo "background-color:{$css['billing_panel_color']}!important;border-color:{$css['billing_panel_color']}!important;";
	}
	?>">
		<h3 class="panel-title">
			<!--<span class="glyphicon glyphicon-user"></span><span class="glyphicon glyphicon-home"></span>&nbsp;-->
			<i class="fa fa-home" style="font-size: 1.5em;"></i>&nbsp;
            <?php if (!$logged) { ?>
                <strong id="checkout-option-title"> <?php echo $account_view_title; ?></strong>
            <?php } else { ?>
                <strong><?php echo $text_checkout_payment_address; ?></strong>
            <?php } ?>
            <span id="billing-wait"
				class="glyphicon glyphicon-refresh glyphicon-spin-animate hidden"></span>
		</h3>
	</div>

	<div class="panel-body">

        <?php if (!$logged) { ?>
            <!-- CHECKOUT OPTIONS -->
		<div id="checkout-options">
			<div class="list-group">
				<li href="#" class="list-group-item "><span
					class="text-<?php
									if ($css ['checkout_theme'] == 'standar') {
										echo 'warning';
									} else {
										echo $css ['checkout_theme'];
									}
									?>"
					style="font-size: 1.5em;"><i class="fa fa-certificate"
						style="font-size: 1em;"></i>&nbsp;<?php echo $text_checkout; ?>
                        </span></li>
                    <?php if ($guest_checkout) { ?>
                        <a href="javascript:void(0);"
					class="list-group-item  <?php
										if ($account_view == 'guest') {
											echo 'active';
										}
										?>">
					<div class="radio">
						<label>
                                    <?php if ($account_view == 'guest') { ?>
                                        <input type="radio"
							name="account" value="guest" id="guest" checked="checked" />
                                    <?php } else { ?>
                                        <input type="radio"
							name="account" value="guest" id="guest" />
        <?php } ?>
                                    <strong><?php echo $text_guest; ?>&nbsp;<i
								class="fa fa-pencil"></i></strong>
						</label>
					</div>
				</a>
                    <?php } ?>
                    <a href="javascript:void(0);"
					class="list-group-item <?php
									if ($account_view == 'register') {
										echo 'active';
									}
									?>">
					<div class="radio">
						<label>
                                <?php if ($account_view == 'register') { ?>
                                    <input type="radio" name="account"
							value="register" id="register" checked="checked" />
    <?php } else { ?>
                                    <input type="radio" name="account"
							value="register" id="register" />
    <?php } ?>
                                <strong><?php echo $text_register; ?>&nbsp;<i
								class="fa fa-pencil-square-o"></i> </strong>
						</label>
					</div>
				</a> <a href="javascript:void(0);"
					class="list-group-item <?php
									if ($account_view == 'returning-customer') {
										echo 'active';
									}
									?>">
					<div class="radio">
						<label>
    <?php if ($account_view == 'returning-customer') { ?>
                                    <input type="radio" name="account"
							value="returning-customer" id="returning-customer"
							checked="checked">
    <?php } else { ?>
                                    <input type="radio" name="account"
							value="returning-customer" id="returning-customer">
    <?php } ?>
                                <strong><?php echo $text_i_am_returning_customer; ?>&nbsp;<i
								class="fa fa-user"></i> </strong>
						</label>
					</div>
				</a>
			</div>
		</div>

		<!-- LOGIN FORM -->

		<div id="login-form" style="<?php
									if ($account_view != 'returning-customer') {
										echo 'display:none;';
									}
									?>">
            <?php echo $login; ?>
            </div>
        <?php } ?>



        <!-- BILLING TIPS -->
<?php if (!empty($tips['payment_address_tip'][$config_language_id])) { ?>
            <i class="fa fa-lightbulb-o"></i>&nbsp;<small
			class="text-warning"><i><?php echo $tips['payment_address_tip'][$config_language_id]; ?></i></small>
                <?php } ?>

        <!-- BILLING ADDRESS -->
                <?php if (!$logged) { ?>
            <div id="payment-address">
			<div class="checkout-content-onepage">
                             <?php if ($guest_checkout) { ?>
                        <div id="guest-form-detail" style="overflow: visible!important; <?php
																		if ($account_view != 'guest') {
																			echo 'display:none;';
																		}
																		?>">
                        <?php echo $guest; ?>
                        </div>
                             <?php } ?>
                    <div id="register-form-detail" style="overflow: visible!important;<?php
																	if ($account_view != 'register') {
																		echo 'display:none;';
																	}
																	?>">
                    <?php echo $register; ?>
                    </div>
			</div>
		</div>
        <?php } else { ?>
            <div id="payment-address">
			<div class="checkout-content-onepage">
    <?php echo $paymentAddress; ?>
                </div>
		</div>
<?php } ?>
    </div>
</div>

<!-- Shipping Address-->
<?php if ($shipping_required && ($logged || (!$logged && $guest_checkout))) { ?>
<div id="shipping-address-panel" class="panel panel-<?php
	if ($css ['checkout_theme'] == 'standar') {
		echo 'warning';
	} else {
		echo $css ['checkout_theme'];
	}
	?>" style="<?php
	if (! empty ( $css ['shipping_panel_color'] )) {
		echo "border-color:{$css['shipping_panel_color']}!important;";
	}
	if (! empty ( $shipping_address_same ) || (! $logged && $account_view != 'guest')) {
		echo 'display:none;';
	}
	?>">
	<div class="checkout-overlay checkout-overlay-dark hidden"></div>
	<div class="panel-heading" style="<?php
	if (! empty ( $css ['shipping_panel_color'] )) {
		echo "background-color:{$css['shipping_panel_color']}!important;border-color:{$css['shipping_panel_color']}!important;";
	}
	?>">
		<h3 class="panel-title">
			<!--<span class="glyphicon glyphicon-send"></span><span class="glyphicon glyphicon-home"></span>-->
			<i class="fa fa-paper-plane-o" style="font-size: 1.5em;"></i>&nbsp;
			&nbsp;<strong><?php echo $text_checkout_shipping_address; ?></strong>
			<span id="shipping-wait"
				class="glyphicon glyphicon-refresh glyphicon-spin-animate hidden"></span>
		</h3>
	</div>
	<div class="panel-body">
                    <?php if (!empty($tips['shipping_address_tip'][$config_language_id])) { ?>
                <i class="fa fa-lightbulb-o"></i>&nbsp;<small
			class="text-warning"><i><?php echo $tips['shipping_address_tip'][$config_language_id]; ?></i></small>
                    <?php } ?>
            <div id="shipping-address">
			<div class="checkout-content-onepage">
    <?php if (!$logged && $guest_checkout) { ?>
        <?php echo $guestShipping; ?>
    <?php } else if ($logged) { ?>
        <?php echo $shippingAddress; ?>
    <?php } ?>
                </div>
		</div>
	</div>

</div>
<?php } else { ?>
    <?php // echo $text_no_shipping_required;     ?>
<?php } ?>
<?php if (isset($account_view) && $account_view == 'returning-customer') { ?>
<script type="text/javascript"><!--
        $(function() {
            $('#login-form').show();
            $('#shipping-address-panel').hide();
            $('#register-form-detail').hide();
            $('#guest-form-detail').hide();

            //overlay
            $('#shipping-method-panel div.checkout-overlay').removeClass('hidden');
            $('#payment-method-panel div.checkout-overlay').removeClass('hidden');
            $('#voucher-coupon-panel div.checkout-overlay').removeClass('hidden');
            $('#confirm-footer-panel div.checkout-overlay').removeClass('hidden');
            $('#confirm-panel div.checkout-overlay').addClass('hidden');
            $('#confirm-footer-panel .checkout-overlay').removeClass('hidden');
        });
        //--></script>
<?php } ?>