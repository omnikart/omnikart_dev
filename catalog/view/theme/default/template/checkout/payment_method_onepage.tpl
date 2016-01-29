<style type="text/css">
#tbl_pament_methods tr>td {
	border: none;
}
</style>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($warning_non_address_payment) { ?>
<div class="warning"><?php echo $warning_non_address_payment; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>
    <?php
	$is_matched_payment_method = false;
	$alt_payment_method = null;
	?>
<p class="text-info hidden-sm hidden-xs"><?php echo $text_payment_method; ?></p>
<table class="table table-hover" id="tbl_pament_methods">
        <?php foreach ($payment_methods as $payment_method) { ?>
            <?php
		if (! $alt_payment_method) {
			$alt_payment_method = $payment_method;
		}
		?>
            <tr class="">
		<td style="width: 100%;">
			<div class="radio">
				<label>
                            <?php // if ($payment_method['code'] == $code || !$code) { ?>
                            <?php if ($payment_method['code'] == $code) { ?>
                                <?php
			$is_matched_payment_method = true;
			// $code = $payment_method['code'];
			// $this->session->data['payment_method'] = $payment_method;
			?>
                                <input type="radio"
					name="payment_method"
					value="<?php echo $payment_method['code']; ?>"
					id="<?php echo $payment_method['code']; ?>" checked="checked" />
                            <?php } else { ?>
                                <input type="radio"
					name="payment_method"
					value="<?php echo $payment_method['code']; ?>"
					id="<?php echo $payment_method['code']; ?>" />
                            <?php } ?>
                            <?php echo $payment_method['title']; ?>
                        </label>
			</div>

		</td>
		<!--<td><label for="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['title']; ?></label></td>-->
	</tr>
        <?php } ?>
    </table>


<?php if (!$is_matched_payment_method) { ?>
        <?php // $this->session->data['payment_method'] = $alt_payment_method; ?>
<script type="text/javascript"><!--
//            $('#tbl_pament_methods input[value=<?php echo $alt_payment_method['code']; ?>]').attr('checked', 'checked');
            //-->
        </script>
<?php } ?>

<?php } ?>



<div class="row" style="display: none;">
	<div class="panel-footer">
		<div class="text-right">
			<input type="button" value="<?php echo $button_continue; ?>" id="button-payment-method" class="btn btn-<?php if($css['checkout_theme']=='standar') {echo 'warning';}else{echo $css['checkout_theme']; } ?>"  style="<?php if(!empty($css['common_btn_color'])){echo "background-color:{$css['common_btn_color']}!important; background-image:none;";} ?>"/>
		</div>
	</div>
</div>



<script type="text/javascript"><!--
//    $('.colorbox').colorbox({
//        width: 640,
//        height: 480
//    });
//--></script>
