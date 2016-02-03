
<div>
	<form method="post" enctype="multipart/form-data"
		onsubmit="return false;">
		<div class="row">
			<blockquote style="border-left: 0">
				<div class="checkout-content-onepage">

					<fieldset>
						<legend class="text-info">
							<i class="fa fa-user" style="font-size: 1.5em;"></i>&nbsp;<?php echo $text_returning_customer; ?></legend>

						<div class="form-group form-group-sm required">
							<label class="control-label"> <?php echo $entry_email; ?></label>
							<input type="email" name="email" value=""
								class="form-control input-sm">
						</div>
						<div class="form-group form-group-sm required">
							<label class="control-label"> <?php echo $entry_password; ?></label>
							<input type="password" name="password" value=""
								class="form-control input-sm" />
						</div>
						<div class="form-group  form-group-sm text-right">
							<a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
						</div>
					</fieldset>

				</div>
			</blockquote>
		</div>


		<div class="row">
			<div class="panel-footer text-right">
				<input type="submit"  id="button-login" class="btn btn-<?php
				if ($css ['checkout_theme'] == 'standar') {
					echo 'primary';
				} else {
					echo $css ['checkout_theme'];
				}
				?>" value="<?php echo $button_login; ?>" style="<?php
																							if (! empty ( $css ['common_btn_color'] )) {
																								echo "background-color:{$css['common_btn_color']}!important; background-image:none;";
																							}
																							?>"/>
			</div>

		</div>
	</form>
</div>
<script type="text/javascript"><!--

    //--></script>