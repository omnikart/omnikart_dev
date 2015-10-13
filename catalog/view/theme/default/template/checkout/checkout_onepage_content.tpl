<?php if(!isset($redirect)){ ?>
<div class="row">
    <!-- Addresses/checkout-options -->
    <div class="col-sm-4" id="billing-shipping-details" >
        <?php echo $left_content; ?>
    </div>

    <!-- Shipping/Payment Method -- Voucher/coupon -->
    <div class="col-sm-8" id="methods-summary-panel">
        <?php echo $right_content; ?>
    </div><!-- END Shipping/Payment Method-Voucher/coupon -->
</div>
<?php }else{ ?>
<script type="text/javascript"><!--
    location='<?php echo $redirect; ?>';
    //--></script>
<?php } ?>