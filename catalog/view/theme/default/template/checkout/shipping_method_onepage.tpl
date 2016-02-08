<style type="text/css">
#tbl_shipping_methods tr>td {
	border: none;
}
</style>
<?php if ($error_warning) { ?>
<div class=""><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($warning_non_address_shipping) { ?>
<div class=""><?php echo $warning_non_address_shipping; ?></div>
<?php } ?>
<?php if ($shipping_methods) { ?>
    <?php
	$is_matched_shipping_method = false;
	$alt_shipping_method = null;
	?>
<p class="text-info hidden-sm hidden-xs"><?php echo $text_shipping_method; ?></p>

<table class="table table-hover" id="tbl_shipping_methods">
        <?php foreach ($shipping_methods as $shipping_method) { ?>
        <!--            <tr>
                    <td colspan="3"><b><?php echo $shipping_method['title']; ?></b></td>
                </tr>-->
            <?php if (!$shipping_method['error']) { ?>

                <?php foreach ($shipping_method['quote'] as $quote) { ?>
                    <?php
				if (! $alt_shipping_method) {
					$alt_shipping_method = $quote;
				}
				?>
                    <tr class="">
		<td style="">
			<div class="radio">
				<label>
                                    <?php // if ($quote['code'] == $code || !$code) { ?>
                                    <?php if ($quote['code'] == $code) { ?>
                                        <?php
					// $code = $quote['code'];
					$is_matched_shipping_method = true;
					// $shipping = explode('.', $this->request->post['shipping_method']);
					// $shipping = explode('.', $code);
					// $this->session->data['shipping_method'] = $quote;
					?>
                                        <input type="radio"
					name="shipping_method" value="<?php echo $quote['code']; ?>"
					id="<?php echo $quote['code']; ?>" checked="checked" />
                                    <?php } else { ?>
                                        <input type="radio"
					name="shipping_method" value="<?php echo $quote['code']; ?>"
					id="<?php echo $quote['code']; ?>" />
                                    <?php } ?>
                                    <?php echo $quote['title']; ?>
                                </label>
			</div>

		</td>
		<!--<td><label for="<?php echo $quote['code']; ?>"><?php echo $quote['title']; ?></label></td>-->
		<td class="text-right"><label for="<?php echo $quote['code']; ?>"><?php echo $quote['text']; ?></label></td>
	</tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
		<td colspan="3"><div class="error"><?php echo $shipping_method['error']; ?></div></td>
	</tr>
            <?php } ?>
        <?php } ?>
    </table>
<?php if (!$is_matched_shipping_method) { ?>
        <?php // $this->session->data['shipping_method'] = $alt_shipping_method; ?>
<script type="text/javascript"><!--
//            $('#tbl_shipping_methods input[value=\'<?php echo $alt_shipping_method['code']; ?>\']').attr('checked', 'checked');
            //-->
        </script>
<?php } ?>

<?php } ?>



<div class="row" style="display: none !important;">
	<div class="panel-footer">
		<div class="text-right">
			<input type="button" value="<?php echo $button_continue; ?>" id="button-shipping-method" class="btn btn-<?php
			
if ($css ['checkout_theme'] == 'standar') {
				echo 'warning';
			} else {
				echo $css ['checkout_theme'];
			}
			?>" style="<?php

if (! empty ( $css ['common_btn_color'] )) {
	echo "background-color:{$css['common_btn_color']}!important; background-image:none;";
}
?>"/>
		</div>
	</div>
</div>
