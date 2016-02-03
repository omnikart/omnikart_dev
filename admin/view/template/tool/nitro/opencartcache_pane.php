<table class="form browserCache">
	<tr>
		<td>OpenCart Cache Status<span class="help">This is used to control
				the standard OpenCart cache. It is recommended to keep this Enabled.</span></td>
		<td><select name="Nitro[OpenCartCache][Enabled]"
			class="form-control NitroOpenCartCacheEnabled">
				<option value="yes"
					<?php echo( (!empty($data['Nitro']['OpenCartCache']['Enabled']) && $data['Nitro']['OpenCartCache']['Enabled'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
				<option value="no"
					<?php echo (empty($data['Nitro']['OpenCartCache']['Enabled']) || $data['Nitro']['OpenCartCache']['Enabled'] != 'yes') ? 'selected=selected' : ''?>>Disabled</option>
		</select></td>
	</tr>
	<tr>
		<td>Expire Time (seconds)<span class="help">If the cache files get
				older than this time, it will be re-cached automatically.</span></td>
		<td><input class="form-control" type="text"
			name="Nitro[OpenCartCache][ExpireTime]"
			value="<?php echo !empty($data['Nitro']['OpenCartCache']['ExpireTime']) ? $data['Nitro']['OpenCartCache']['ExpireTime'] : '3600'?>" />
		</td>
	</tr>
	<tr>
		<td>Cache Directory<span class="help">The native OpenCart cache
				directory, where it stores the files.</span></td>
		<td><span class="cacheDirSpan cacheDirLink"
			ca="<?php echo DIR_CACHE; ?>">********** (click to show)</span></td>
	</tr>
	<tr>
		<td>Delete cache<span class="help">Use this button to delete the
				OpenCart System Cache.</span></td>
		<td><a href="javascript:void(0)"
			onclick="document.location='index.php?route=tool/nitro/clearsystemcache&token=<?php echo $_GET['token']; ?>'"
			class="btn btn-default"><i class="icon-trash"></i> Clear OpenCart
				System Cache</a></td>
	</tr>
</table>
<script type="text/javascript">
$('.cacheDirSpan').click(function() {
	$(this).html($(this).attr('ca')).removeClass('cacheDirLink');
});
</script>