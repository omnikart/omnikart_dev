<?php require_once DIR_TEMPLATE . 'module/mega_filter-fn.tpl'; ?>
<?php require DIR_TEMPLATE . 'module/' . $_name . '-header.tpl'; ?>

<br />

<ul class="nav nav-tabs attributes">
	<li class="active"><a data-toggle="tab" href="#tab-seo-settings"><i class="glyphicon glyphicon-wrench"></i> <?php echo $tab_settings; ?></a></li>
	<li><a data-toggle="tab" href="#tab-seo-aliases"><i class="glyphicon glyphicon-random"></i> <?php echo $tab_aliases; ?></a></li>
</ul>
<br />
<div class="tab-content">
	<div class="tab-pane active" id="tab-seo-settings">
		<table class="table table-tbody">
			<tr>
				<td width="200"><?php echo $text_enabled; ?>:</td>
				<td>
					<?php echo mf_render_btn_group( $text_yes, $text_no, $_name . '_seo[enabled]', ! empty( $seo['enabled'] ) ); ?>
				</td>
			</tr>
		</table>
	</div>
	<div class="tab-pane" id="tab-seo-aliases"></div>
</div>

<script type="text/javascript">
	$('#tab-seo-aliases').text('<?php echo $text_loading; ?>...').load('<?php echo $aliases_url; ?>'.replace(/&amp;/g,'&'));
</script>

<?php require DIR_TEMPLATE . 'module/' . $_name . '-footer.tpl'; ?>