<div class="box-heading">
	<h1>Local CRON</h1>
</div>
<div class="box-content">
	<table class="form settings">
		<tr>
			<td>Local CRON status<span class="help">Enable/Disable server-side
					CRON job. Note that this option will work only on servers with an
					enabled <strong>crontab</strong> Linux command.
			</span></td>
			<td><select class="form-control" name="Nitro[CRON][Local][Status]">
					<option value="no"
						<?php echo ( (!empty($data['Nitro']['CRON']['Local']['Status']) && $data['Nitro']['CRON']['Local']['Status'] == 'no')) ? 'selected=selected' : ''?>>Disabled</option>
					<option value="yes"
						<?php echo( (!empty($data['Nitro']['CRON']['Local']['Status']) && $data['Nitro']['CRON']['Local']['Status'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
			</select>
    <?php if (!empty($cron_error)) : ?>
        <div class="alert alert-danger"><?php echo $cron_error; ?></div>
    <?php endif; ?>
    </td>
		</tr>
		<tr>
			<td>Local CRON frequency<span class="help">Choose which days of the
					week and on what time you need the CRON job.</span></td>
			<td>
				<p class="cron_input_box">
					<span><input class="form-control" type="checkbox"
						name="Nitro[CRON][Local][Weekday][]" value="1"
						<?php if (!empty($data['Nitro']['CRON']['Local']['Weekday']) && in_array('1', $data['Nitro']['CRON']['Local']['Weekday'])) echo 'checked="checked"'; ?> />
						Monday</span> <span><input class="form-control" type="checkbox"
						name="Nitro[CRON][Local][Weekday][]" value="2"
						<?php if (!empty($data['Nitro']['CRON']['Local']['Weekday']) && in_array('2', $data['Nitro']['CRON']['Local']['Weekday'])) echo 'checked="checked"'; ?> />
						Tuesday</span> <span><input class="form-control" type="checkbox"
						name="Nitro[CRON][Local][Weekday][]" value="3"
						<?php if (!empty($data['Nitro']['CRON']['Local']['Weekday']) && in_array('3', $data['Nitro']['CRON']['Local']['Weekday'])) echo 'checked="checked"'; ?> />
						Wednesday</span> <span><input class="form-control" type="checkbox"
						name="Nitro[CRON][Local][Weekday][]" value="4"
						<?php if (!empty($data['Nitro']['CRON']['Local']['Weekday']) && in_array('4', $data['Nitro']['CRON']['Local']['Weekday'])) echo 'checked="checked"'; ?> />
						Thursday</span> <span><input class="form-control" type="checkbox"
						name="Nitro[CRON][Local][Weekday][]" value="5"
						<?php if (!empty($data['Nitro']['CRON']['Local']['Weekday']) && in_array('5', $data['Nitro']['CRON']['Local']['Weekday'])) echo 'checked="checked"'; ?> />
						Friday</span> <span><input class="form-control" type="checkbox"
						name="Nitro[CRON][Local][Weekday][]" value="6"
						<?php if (!empty($data['Nitro']['CRON']['Local']['Weekday']) && in_array('6', $data['Nitro']['CRON']['Local']['Weekday'])) echo 'checked="checked"'; ?> />
						Saturday</span> <span><input class="form-control" type="checkbox"
						name="Nitro[CRON][Local][Weekday][]" value="7"
						<?php if (!empty($data['Nitro']['CRON']['Local']['Weekday']) && in_array('7', $data['Nitro']['CRON']['Local']['Weekday'])) echo 'checked="checked"'; ?> />
						Sunday</span>
				</p>
				<p>
					<select name="Nitro[CRON][Local][Hour]"
						class="form-control input-mini inline-input">
                <?php for ($i = 0; $i < 24; $i++) : ?>
                <option value="<?php echo $i; ?>"
							<?php if (!empty($data['Nitro']['CRON']['Local']['Hour']) && $data['Nitro']['CRON']['Local']['Hour'] == $i) echo 'selected="selected"'; ?>><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
                <?php endfor; ?>
            </select> <select name="Nitro[CRON][Local][Minute]"
						class="form-control input-mini inline-input">
                <?php for ($i = 0; $i < 60; $i++) : ?>
                <option value="<?php echo $i; ?>"
							<?php if (!empty($data['Nitro']['CRON']['Local']['Minute']) && $data['Nitro']['CRON']['Local']['Minute'] == $i) echo 'selected="selected"'; ?>><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
                <?php endfor; ?>
            </select>
				</p>
			</td>
		</tr>
		<tr>
			<td>Local CRON behavior<span class="help">How would you like your
					Local CRON job to behave?</span></td>
			<td>
				<div>
					<input class="form-control" type="checkbox"
						name="Nitro[CRON][Local][Delete]"
						<?php if (!empty($data['Nitro']['CRON']['Local']['Delete'])) echo 'checked="checked"'; ?>
						value="1" /> Delete outdated NitroPack cache files. This applies
					for: PageCache, Minification, Database Cache
				</div>
				<div>
					<input class="form-control" type="checkbox"
						name="Nitro[CRON][Local][PreCache]"
						<?php if (!empty($data['Nitro']['CRON']['Local']['PreCache'])) echo 'checked="checked"'; ?>
						value="1" /> Pre-Cache sitemap pages: home page, categories (up to
					level 3), information pages, special offers.
				</div>
				<div>
					<input class="form-control" type="checkbox"
						name="Nitro[CRON][Local][SendEmail]"
						<?php if (!empty($data['Nitro']['CRON']['Local']['SendEmail'])) echo 'checked="checked"'; ?>
						value="1" /> Send an e-mail to <strong><?php echo $admin_email; ?></strong>
					after task completion
				</div>
			</td>
		</tr>
  <?php if (!empty($cron_command)) : ?>
      <tr>
			<td>Local CRON command<span class="help">You can insert this command
					into your <strong>crontab</strong>, if your server does not support
					<strong>exec()</strong>
			</span></td>
			<td><pre><?php echo $cron_command; ?></pre></td>
		</tr>
  <?php endif; ?>
</table>
</div>
<div class="box-heading">
	<h1>Remote CRON</h1>
</div>
<div class="box-content">
	<table class="form settings">
		<tr>
			<td>Remote CRON URL<span class="help">If the server-side CRON job
					does not work, you can use a third-party CRON service.</span></td>
			<td>Remote URL token:<span class="help">This token will be used to
					identify valid requests. If you refresh it, do not forget to click
					Save on the top right.</span><br /> <input
				class="form-control inline-input" type="text"
				name="Nitro[CRON][Remote][Token]"
				value="<?php echo !empty( $data['Nitro']['CRON']['Remote']['Token']) ? $data['Nitro']['CRON']['Remote']['Token'] : ''; ?>" />
				<span class="btn btn-default" id="cron_refresh_token"><i
					class="icon icon-refresh"></i></span><br /> <strong id="cron_url"
				data-url="<?php echo $cron_token_url; ?>"></strong> <script
					type="text/javascript">
            $(document).ready(function() {
                var cron_refresh_token = function() {
                    $('#cron_url_info').remove();
                    $('#cron_url').before('<p id="cron_url_info">Paste this URL in the third-party CRON service:</p>');
                    $('#cron_url').html($('#cron_url').attr('data-url').replace('{CRON_TOKEN}', $('input[name="Nitro[CRON][Remote][Token]"]').val()));
                }

                $('#cron_refresh_token').on('click', function(e) {
                    e.preventDefault();
                    $('input[name="Nitro[CRON][Remote][Token]"]').val(Math.random().toString().substr(2, 16));
                    cron_refresh_token();
                });

                if ($('input[name="Nitro[CRON][Remote][Token]"]').val() != '') {
                    cron_refresh_token();
                } else {
                  $('#cron_refresh_token').trigger('click');
                }

                $('input[name="Nitro[CRON][Remote][Token]"]').change(function() {
                  $('#cron_refresh_token').trigger('click');
                });
            });
        </script>
			</td>
		</tr>
		<tr>
			<td>Remote CRON behavior<span class="help">How would you like your
					Remote CRON job to behave?</span></td>
			<td>
				<div>
					<input class="form-control" type="checkbox"
						name="Nitro[CRON][Remote][Delete]"
						<?php if (!empty($data['Nitro']['CRON']['Remote']['Delete'])) echo 'checked="checked"'; ?>
						value="1" /> Delete outdated NitroPack cache files. This applies
					for: PageCache, Minification, Database Cache
				</div>
				<div>
					<input class="form-control" type="checkbox"
						name="Nitro[CRON][Remote][SendEmail]"
						<?php if (!empty($data['Nitro']['CRON']['Remote']['SendEmail'])) echo 'checked="checked"'; ?>
						value="1" /> Send an e-mail to <strong><?php echo $admin_email; ?></strong>
					after task completion
				</div>
			</td>
		</tr>
	</table>
</div>