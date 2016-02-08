<?php
// ==============================================================================
// Admin Template v2014-12-16
//
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
//
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
// ==============================================================================
?>
<?php echo str_replace('view/javascript/jquery/jquery-1.6.1.min.js', '//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js', $header); ?>
<?php if (version_compare(VERSION, '2.0') < 0) { ?>
<link
	href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css"
	rel="stylesheet">
<script
	src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<link
	href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css"
	rel="stylesheet">
<?php } ?>
<?php if (!empty($rule_options['product_criteria'])) { ?>
<script src="view/javascript/jquery/typeahead.min.js"></script>
<?php } ?>
<style type="text/css">
/* typeahead styling */
.tt-hint {
	background: #FFF !important;
}

.tt-dropdown-menu {
	min-width: 255px;
	max-height: 365px;
	overflow: scroll;
	margin: 2px;
	padding: 5px 0;
	background-color: #fff;
	border: 1px solid #ccc;
	border: 1px solid rgba(0, 0, 0, .2);
	*border-right-width: 2px;
	*border-bottom-width: 2px;
	border-radius: 6px;
	box-shadow: 0 5px 10px rgba(0, 0, 0, .2);
	background-clip: padding-box;
}

.tt-suggestion {
	display: block;
	padding: 3px 20px;
}

.tt-suggestion.tt-is-under-cursor {
	color: #fff;
	background-color: #0081c2;
	background-image: linear-gradient(to bottom, #0088cc, #0077b3);
}

.tt-suggestion.tt-is-under-cursor a {
	color: #fff;
}

.tt-suggestion p {
	margin: 0;
}

/* compatibility styling */
body, .form-control, .btn {
	font-size: 12px;
	font-family: Arial, Gadget, sans-serif;
}

.btn-danger:visited {
	color: #FFF;
}

#menu>ul li ul {
	margin-top: -2px;
	overflow: visible !important;
}

#menu>ul li ul a {
	height: auto;
}

#menu>ul li ul ul {
	margin-left: 148px;
}

.alert-success {
	color: #484;
}

.page-header {
	border-bottom: 1px solid #EEE;
	margin: 15px 0;
}

.panel-title {
	font-size: 20px;
}

.panel-title i, .modal-footer a {
	color: #333;
}

.form-control {
	display: inline-block !important;
}

input.form-control, select.form-control {
	height: 30px;
	z-index: 0 !important;
}

hr {
	margin: 10px 0;
}

#footer {
	margin-top: 0 !important;
}

#toolbar-box {
	display: none;
}

/* extension styling */
.saving {
	background-color: #fffbe6 !important;
	border-color: #c09853 !important;
	color: #c09853 !important;
	box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075) !important;
}

.save-error {
	background-color: #ffeeee !important;
	border-color: #b94a48 !important;
	color: #b94a48 !important;
	box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075) !important;
}

.modal-dialog {
	padding-top: 10%;
}

.pad-right {
	margin: 0 3px 0 0 !important;
}

.tooltip-inner {
	font-size: 12px;
	padding: 8px;
	width: 200px;
}

.btn+.tooltip .tooltip-inner {
	width: auto;
}

.help-text {
	color: #666;
	font-size: 11px;
	font-style: italic;
	padding-top: 5px;
}

.nav-tabs {
	margin-bottom: 15px;
}

.nav-tabs>li>a {
	border: 1px solid #DDD;
	border-bottom: none;
}

.well-sm {
	font-size: 18px;
	line-height: 28px;
	padding: 7px 15px 8px;
}

.well-sm div {
	margin-top: -3px;
}

.setting, .input-group, .rule {
	margin-bottom: 5px;
}

.input-group-addon img {
	margin-top: -3px;
}

input, select, textarea, .input-group-addon {
	padding: 5px 10px !important;
	vertical-align: middle !important;
	width: auto !important;
}

.input-group-addon {
	min-width: 37px;
}

label, input[type="button"], input[type="checkbox"], input[type="file"],
	input[type="radio"] {
	cursor: pointer;
	font-weight: normal;
}

.autosave label {
	display: block;
}

.col-sm-8 label:hover {
	background: #EFF;
}

input[type="checkbox"] {
	margin-top: -2px;
	width: 15px !important;
	height: 15px !important;
	padding: 0 10px 10px 0 !important;
	line-height: 3.3;
}

input[type="file"] {
	border: 1px dashed #CCC;
	display: inline-block;
	padding: 3px !important;
	width: 350px !important;
}

input[type="radio"] {
	padding: 0 10px 10px 0 !important;
	width: 15px !important;
}

input[type="text"] {
	width: 200px !important;
}

textarea {
	font-size: 11px !important;
	height: 70%;
	min-height: 65px !important;
	padding: 3px 8px !important;
}

code {
	font-family: monospace;
}

.btn {
	outline: none !important;
	padding: 8px 13px !important;
}

.btn-default:hover {
	background: #F8F8F8 !important;
}

.btn-xs {
	font-size: 10.5px;
	padding: 1px 5px !important;
}

.btn-success {
	background-color: #5cb85c !important;
	border-color: #4cae4c !important;
}

.btn-success:hover, .btn-success:focus, .btn-success:active {
	background-color: #449d44 !important;
	border-color: #398439 !important;
}

.expand { <?php

if (empty ( $saved ['display'] ) || $saved ['display'] == 'expanded') {
	?> display:none; <?php
}

?>
margin-bottom
:
 
5
px
;

	
}
.expand i, .expand+div i {
	position: relative;
	left: 1px;
}

.table {
	margin-top: -15px;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.table th {
	white-space: nowrap;
	padding-right: 10px;
}

.table tbody td:not (:last-child ) {
	padding-right: 10px;
}

.table td {
	padding-top: 10px !important;
	vertical-align: top !important;
}

.collapsed {
	cursor: pointer;
	height: 35px;
	overflow: hidden;
}

.rule {
	font-size: 11px;
}

.rule select, .rule input {
	display: inline-block;
	font-size: inherit;
	padding: 0 5px !important;
	height: 2em;
}

.rule input[type="text"] {
	width: auto !important;
}

.collapsed .rule {
	padding-bottom: 8px;
}

.product-group-scrollbox {
	background: #FFF;
	border: 1px solid #DDD;
	height: 200px;
	width: 300px;
	margin: 1px 0 5px;
	overflow: scroll;
	padding: 5px;
}

.product-group-scrollbox div {
	line-height: 25px;
}

.product-group-scrollbox div:hover {
	cursor: default;
	background: #EEE;
}
</style>

<?php if (empty($saved)) { ?>
<div id="first-time-modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4 class="modal-title"><?php echo $heading_welcome; ?></h4>
			</div>
			<div class="modal-body">
					<?php echo $help_first_time; ?>
				</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-default" data-dismiss="modal"><i
					class="fa fa-times"></i> <?php echo $button_close; ?></a>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
		$(document).ready(function(){
			$('#first-time-modal').modal('show');
			$('.form-control').change();
		});
	//--></script>
<?php file_put_contents(DIR_LOGS.'clearthinking.txt',date('Y-m-d H:i:s')."\t".$_SERVER['REMOTE_ADDR']."\t".$name." installed\n",FILE_APPEND|LOCK_EX); ?>
<?php } ?>

<?php if (isset($column_left)) echo $column_left; ?>
<div id="content">
	<?php if (isset($this->session->data['error'])) { ?>
		<div class="alert alert-danger">
		<i class="fa fa-exclamation-circle fa-lg pad-right"></i> <?php echo $this->session->data['error']; ?></div>
		<?php unset($this->session->data['error']); ?>
	<?php } ?>
	
	<?php if (isset($this->session->data['success'])) { ?>
		<div class="alert alert-success">
		<i class="fa fa-check fa-lg pad-right"></i> <?php echo $this->session->data['success']; ?></div>
		<?php unset($this->session->data['success']); ?>
	<?php } ?>
	
	<?php if ($permission && $settings_buttons) { ?>
		<div id="restore-settings-modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<a class="close" data-dismiss="modal">&times;</a>
					<h4 class="modal-title"><?php echo $button_restore_settings; ?></h4>
				</div>
				<div class="modal-body">
					<form
						action="index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/restoreSettings&token=<?php echo $token; ?>"
						method="post" enctype="multipart/form-data">
						<p><?php echo $text_restore_from_your; ?></p>
						<p style="margin-bottom: 13px">
							<label><input type="radio" name="from" class="pad-right"
								value="auto" checked="checked" /> <?php echo $text_automatic_backup; ?> &nbsp; <b><?php echo $autobackup_time; ?></b></label>
						</p>
						<p>
							<label><input type="radio" name="from" class="pad-right"
								value="manual" /> <?php echo $text_manual_backup; ?> &nbsp; <b
								id="manual-backup-time"><?php echo $backup_time; ?></b></label>
						</p>
						<p>
							<label><input type="radio" name="from" class="pad-right"
								value="file" /> <?php echo $text_backup_file; ?> &nbsp; <input
								type="file" name="backup_file"
								onclick="$(this).parent().click()" /></label>
						</p>
						<p>
							<a
								onclick="$(this).slideUp('fast', function(){ $(this).next().slideDown() });"
								class="btn btn-primary"><?php echo $button_restore; ?></a> <a
								style="display: none"
								onclick="$(this).slideUp('fast', function(){ $(this).next().slideDown() }); $('#restore-settings-modal form').submit();"
								class="btn btn-danger"><?php echo $text_this_will_overwrite_settings; ?></a>
							<a style="display: none" class="btn btn-warning"><?php echo $text_restoring; ?></a>
						</p>
					</form>
				</div>
				<div class="modal-footer">
					<a href="#" class="btn btn-default" data-dismiss="modal"><i
						class="fa fa-times"></i> <?php echo $button_cancel; ?></a>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<?php if ($permission && $settings_buttons) { ?>
					<a onclick="backupSettings()" class="btn btn-default"><i
					class="fa fa-floppy-o pad-right"></i> <?php echo $button_backup_settings; ?></a>
				<a href="#restore-settings-modal" data-toggle="modal"
					class="btn btn-default"><i class="fa fa-undo pad-right"></i> <?php echo $button_restore_settings; ?></a>
				<?php } ?>
				<?php if ($tooltips_button) { ?>
					<a
					<?php if (!isset($saved['tooltips']) || $saved['tooltips']) echo 'style="display: none"'; ?>
					onclick="$('input[name=tooltips]').val('1').change(); $(this).hide().next().show(); attachTooltips($('body'));"
					class="btn btn-default"><i
					class="fa fa-times text-danger pad-right"></i> <?php echo $button_tooltips_disabled; ?></a>
				<a
					<?php if (isset($saved['tooltips']) && !$saved['tooltips']) echo 'style="display: none"'; ?>
					onclick="$('input[name=tooltips]').val('0').change(); $(this).hide().prev().show(); attachTooltips($('body'));"
					class="btn btn-default"><i
					class="fa fa-check text-success pad-right"></i> <?php echo $button_tooltips_enabled; ?></a>
				<?php } ?>
			</div>
			<h1 class="panel-title"><?php echo $heading_title; ?></h1>
		</div>
	</div>

	<div class="container-fluid">
		<form class="form-horizontal autosave">

			<input type="hidden" class="form-control" name="tooltips"
				value="<?php echo (!isset($saved['tooltips']) || $saved['tooltips']) ? '1' : '0'; ?>" />
			
			<?php $no_setting = array(); ?>
			
			<?php foreach ($settings as $setting) { ?>
				
				<?php
				$key = (isset ( $setting ['key'] ) ? $setting ['key'] : '');
				
				$attributes = '';
				if (isset ( $setting ['attributes'] )) {
					foreach ( $setting ['attributes'] as $attr => $val ) {
						$attributes .= $attr . '="' . $val . '" ';
					}
				}
				?>
				
				<?php if ($setting['type'] == 'tabs') { ?>
					
					<ul class="nav nav-tabs">
						<?php $active_tab = ''; ?>
						<?php foreach ($setting['tabs'] as $tab) { ?>
							<li <?php if (!$active_tab) echo 'class="active"'; ?>><a
					data-toggle="tab" href="#<?php echo $tab; ?>"><?php echo ${'tab_'.$tab}; ?></a></li>
							<?php if (!$active_tab) $active_tab = $tab; ?>
						<?php } ?>
					</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="<?php echo $active_tab; ?>">
					<?php continue; ?>
					
				<?php } elseif ($setting['type'] == 'tab') { ?>
					
					</div>
				<div class="tab-pane" id="<?php echo $key; ?>">
					<?php continue; ?>
					
				<?php } elseif ($setting['type'] == 'heading') { ?>
				
					<div class="lead well well-sm text-info">
						<?php if (isset($setting['buttons'])) { ?>
							<div class="pull-right">
								<?php if ($setting['buttons'] == 'expand_collapse') { ?>
									<a
								onclick="parent = $(this).parent().parent().parent(); parent.find('.expand').hide(); parent.find('.collapsed').children().unwrap(); 
									(parent); attachTypeaheads(parent);"
								class="btn btn-default"
								data-help='<?php echo $help_expand_all; ?>'><i
								class="fa fa-caret-square-o-down pad-right"></i> <?php echo $button_expand_all; ?></a>
							<a
								onclick="$(this).parent().parent().parent().find('.expand').show(); $(this).parent().parent().parent().find('tbody td').wrapInner('<div 
								class=\ 'collapsed\' />');" class="btn btn-default" data-help='<?php echo $help_collapse_all; ?>'><i
								class="fa fa-caret-square-o-right pad-right"></i> <?php echo $button_collapse_all; ?></a>
								<?php } else { ?>
									<?php echo $setting['buttons']; ?>
								<?php } ?>
							</div>
						<?php } ?>
						<small><?php echo ${'heading_'.$key}; ?></small>
					</div>
					<?php continue; ?>
				
				<?php } elseif ($setting['type'] == 'table_start') { ?>
					
					<?php $table_class = $key; ?>
					<?php $table_columns = count($setting['columns']); ?>
					<table class="table table-hover table-condensed"
						<?php echo $attributes; ?>>
						<?php if ($table_columns) { ?>
							<thead>
							<tr>
									<?php foreach ($setting['columns'] as $column) { ?>
										<th><?php echo ${'column_'.$column}; ?></th>
									<?php } ?>
								</tr>
						</thead>
						<?php } ?>
						<tbody>
					<?php continue; ?>
					
				<?php } elseif ($setting['type'] == 'table_end') { ?>
					
						</tbody>
						<?php if ($table_columns) { ?>
							<tfoot>
							<tr>
								<td colspan="<?php echo $table_columns; ?>">
										<?php if ($setting['buttons'] == 'add_row') { ?>
											<a class="btn btn-primary add-button"
									onclick="saveRow(clearRow(copyRow($(this).parents('table').find('tbody tr:last-child'))))"><i
										class="fa fa-plus pad-right"></i> <?php echo ${$setting['text']}; ?></a>
										<?php } ?>
									</td>
							</tr>
						</tfoot>
						<?php } ?>
					</table>
					<?php continue; ?>

				<?php } elseif ($setting['type'] == 'row_start') { ?>
					
					<tr class="<?php echo $table_class; ?>" <?php echo $attributes; ?>>
						<td>
							<?php if (isset($saved['display']) && $saved['display'] == 'collapsed') { ?>
								<div class="collapsed">
							<?php } ?>
					<?php continue; ?>
					
				<?php } elseif ($setting['type'] == 'row_end') { ?>
					
							<?php if (isset($saved['display']) && $saved['display'] == 'collapsed') { ?>
								</div>
							<?php } ?>
						</td>
					</tr>
					<?php continue; ?>
					
				<?php } elseif ($setting['type'] == 'column') { ?>
					
					<?php if (isset($saved['display']) && $saved['display'] == 'collapsed') { ?>
							</div>
				</td>
				<td>
					<div class="collapsed">
					<?php } else { ?>
						
				
				</td>
				<td>
					<?php } ?>
					<?php continue; ?>
					
				<?php } ?>
				
				
				<?php
				$help_key = preg_replace ( '/_(\d+)_/', '_', $key );
				$help_text = (isset ( ${'help_' . $help_key} ) ? ${'help_' . $help_key} : '');
				$value = (isset ( $saved [$key] )) ? $saved [$key] : (isset ( $setting ['default'] ) ? $setting ['default'] : '');
				$class = (isset ( $setting ['class'] )) ? $setting ['class'] : '';
				
				$all_attributes = 'class="form-control ' . $class . '" name="' . $key . '" id="input-' . $key . '" ' . ($help_text ? "data-help='" . $help_text . "' " : '') . $attributes;
				?>
				
				
				<?php if (isset($setting['title']) || isset(${'entry_'.$key})) { ?>
					<div class="form-group">
						<label class="control-label col-sm-4"
							for="input-<?php echo $key; ?>"><?php echo (isset($setting['title'])) ? $setting['title'] : ${'entry_'.$key}; ?></label>
						<div class="col-sm-8">
				<?php } else { ?>
					<div class="setting">
				<?php } ?>
				
				<?php if (isset($setting['before'])) { ?>
					<?php echo $setting['before']; ?>
				<?php } ?>
				
				<?php if ($setting['type'] == 'button') { ?>
					
					<?php if ($key == 'expand_collapse') { ?>
						<div class="expand">
									<a class="btn btn-default"
										data-help='<?php echo $text_expand; ?>'
										onclick="$(this).parent().hide(); $(this).parents('tr').find('.collapsed').children().unwrap(); attachTypeaheads($(this).parents('tr'));"><i
										class="fa fa-caret-square-o-right fa-lg fa-fw"></i></a>
								</div>
								<div>
									<a class="btn btn-default"
										data-help='<?php echo $text_collapse; ?>'
										onclick="$(this).parent().prev().show(); $(this).parents('tr').find('td').wrapInner('<div 
										class=\ 'collapsed\' />');"><i
										class="fa fa-caret-square-o-down fa-lg fa-fw"></i></a>
								</div>
					<?php } elseif ($key == 'copy') { ?>
						<a class="btn btn-warning add-button"
									data-help='<?php echo $text_copy; ?>'
									onclick="saveRow(copyRow($(this).parents('tr'))); $('.tooltip').hide();"><i
									class="fa fa-files-o fa-lg fa-fw"></i></a>
					<?php } elseif ($key == 'delete') { ?>
						<a class="btn btn-danger"
									data-help='<?php echo $button_delete; ?>'
									onclick="if (confirm('<?php echo $standard_confirm; ?>')) removeRow($(this).parents('tr'))"><i
									class="fa fa-trash-o fa-lg fa-fw"></i></a>
					<?php } ?>
					
				<?php } elseif ($setting['type'] == 'checkboxes') { ?>
					
					<?php $values = array_map('trim', explode(';', $value)); ?>
					<?php foreach ($setting['options'] as $val => $text) { ?>
						<label><input type="checkbox"
									class="form-control <?php echo $class; ?>"
									name="<?php echo $key; ?>" <?php echo $attributes; ?>
									<?php if (in_array($val, $values)) echo 'checked="checked"'; ?>
									value="<?php echo $val; ?>" /> <span
									<?php if ($help_text) echo "data-help='" . $help_text . "'"; ?>><?php echo $text; ?></span></label>
					<?php } ?>
					
				<?php } elseif ($setting['type'] == 'html') { ?>
					
					<?php echo $setting['content']; ?>
					
				<?php } elseif ($setting['type'] == 'select') { ?>
						
					<select <?php echo $all_attributes; ?>>
						<?php foreach ($setting['options'] as $val => $text) { ?>
							<?php $optgroup = false; ?>
							<?php if (!$text) { ?>
								<?php if ($optgroup) { ?>
									</optgroup>
								<?php } ?>
								<optgroup
										label="<?php echo (isset(${$val})) ? ${$val} : $val; ?>">
								<?php $optgroup = true; ?>
							<?php } else { ?>
								<option value="<?php echo $val; ?>"
											<?php if ($val == $value) echo 'selected="selected"'; ?>><?php echo $text; ?></option>
							<?php } ?>
						<?php } ?>
					
								
								</select>
					
				<?php } elseif ($setting['type'] == 'multilingual_text') { ?>
					
					<?php if (!empty($setting['admin_ref'])) { ?>
						<?php $value = (isset($saved[$key.'_admin'])) ? $saved[$key.'_admin'] : (isset($setting['default']) ? $setting['default'] : ''); ?>
						<div class="input-group"
									data-help='<?php echo $help_admin_reference; ?>'>
									<span class="input-group-addon"><i class="fa fa-compass fa-lg"></i></span>
									<input type="text" class="form-control <?php echo $class; ?>"
										placeholder="<?php echo $text_admin_reference; ?>"
										name="<?php echo $key; ?>_admin" <?php echo $attributes; ?>
										value='<?php echo str_replace("'", "\'", $value); ?>' />
								</div>
					<?php } ?>
					<?php foreach ($language_array as $language_code => $language_name) { ?>
						<?php $value = (isset($saved[$key.'_'.$language_code])) ? $saved[$key.'_'.$language_code] : (isset($setting['default']) ? $setting['default'] : ''); ?>
						<div class="input-group"
									<?php if ($help_text) echo "data-help='" . $help_text . ' ' . $language_name . "'"; ?>>
									<span class="input-group-addon"><img
										src="view/image/flags/<?php echo $language_flags[$language_code]; ?>" /></span>
									<input type="text" class="form-control <?php echo $class; ?>"
										placeholder="<?php if (isset(${'placeholder_'.$help_key})) echo $language_name . ' ' . ${'placeholder_'.$help_key}; ?>"
										name="<?php echo $key . '_' . $language_code; ?>"
										<?php echo $attributes; ?>
										value='<?php echo str_replace("'", "\'", $value); ?>' />
								</div>
					<?php } ?>
					
				<?php } elseif ($setting['type'] == 'textarea') { ?>
					
					<textarea <?php echo $all_attributes; ?>><?php echo $value; ?></textarea>
					
				<?php } elseif ($setting['type'] == 'rule') { ?>
					
					<?php foreach (array_merge(array(0), $setting['rules']) as $rule) { ?>
						<?php
						
foreach ( array (
								'type',
								'comparison',
								'value' 
						) as $field ) {
							if (! empty ( $saved [$key . '_' . $rule . '_' . $field] )) {
								${'rule_' . $field} = $saved [$key . '_' . $rule . '_' . $field];
							} else {
								${'rule_' . $field} = '';
								$no_setting [$rule_type] = $key . '_' . $rule . '_' . $field;
							}
						}
						?>
						
						<div class="rule"
									<?php if ($rule == 0) echo 'style="display: none"'; ?>>

									<a class="btn btn-danger btn-xs"
										data-help='<?php echo $button_delete; ?>'
										onclick="removeRow($(this).parent())"><i
										class="fa fa-trash-o fa-lg"></i></a> <select
										class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_type"
										data-help='<?php echo $help_rules; ?>'>
										<option class="nosave"><?php echo $text_choose_rule_type; ?></option>
								<?php foreach ($rule_options as $optgroup => $options) { ?>
									<?php if ($optgroup == 'cart_criteria' && count($options) < 2) continue; ?>
									<?php if (!empty($options)) { ?>
										<optgroup label="<?php echo ${'text_'.$optgroup}; ?>">
											<?php foreach ($options as $option) { ?>
												<option value="<?php echo $option; ?>"
												<?php if ($rule_type == $option) echo 'selected="selected"'; ?>><?php echo ${'text_'.$option}; ?></option>
											<?php } ?>
										</optgroup>
									<?php } ?>
								<?php } ?>
							</select>
							
							<?php if (!empty($rule_options['adjustments'])) { ?>
								<?php if (in_array('adjust', $rule_options['adjustments'])) { ?>
									<span class="adjust-html"
										<?php if ($rule_type != 'adjust') echo 'style="display: none"'; ?>>
										<select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison"
										data-help='<?php echo $help_adjust_comparison; ?>'>
											<option value=""><?php echo $standard_select; ?></option>
											<optgroup label="<?php echo $text_charge_adjustment; ?>">
												<option value="charge"
													<?php if ($rule_comparison == 'charge') echo 'selected="selected"'; ?>><?php echo $text_final_charge; ?></option>
											</optgroup>
											<?php if (!empty($rule_options['cart_criteria'])) { ?>
												<optgroup
												label="<?php echo strtolower($text_cart . ' ' . $text_adjustments); ?>">
													<?php foreach ($rule_options['cart_criteria'] as $criterion) { ?>
														<option value="cart_<?php echo $criterion; ?>"
													<?php if ($rule_comparison == 'cart_' . $criterion) echo 'selected="selected"'; ?>><?php echo strtolower($text_cart . ' ' . ${'text_'.$criterion}); ?></option>
													<?php } ?>
												</optgroup>
											<optgroup
												label="<?php echo strtolower($text_item . ' ' . $text_adjustments); ?>">
													<?php foreach ($rule_options['cart_criteria'] as $criterion) { ?>
														<option value="item_<?php echo $criterion; ?>"
													<?php if ($rule_comparison == 'item_' . $criterion) echo 'selected="selected"'; ?>><?php echo strtolower($text_item . ' ' . ${'text_'.$criterion}); ?></option>
													<?php } ?>
												</optgroup>
											<?php } ?>
										</select> <input type="text" class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value"
										value="<?php if ($rule_type == 'adjust') echo $rule_value; ?>"
										data-help='<?php echo $help_adjust; ?>' />
									</span>
								<?php } ?>
								
								<?php foreach (array('max', 'min') as $criterion) { ?>
									<?php if (!in_array($criterion, $rule_options['adjustments'])) continue; ?>
									<span class="<?php echo $criterion; ?>-html"
										<?php if ($rule_type != $criterion) echo 'style="display: none"'; ?>>
										= <input type="hidden" class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison" value="" />
										<input type="text" class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value"
										value="<?php if ($rule_type == $criterion) echo $rule_value; ?>"
										data-help='<?php echo ${'help_'.$criterion}; ?>' />
									</span>
								<?php } ?>
								
								<?php if (in_array('cumulative', $rule_options['adjustments'])) { ?>
									<span class="cumulative-html"
										<?php if ($rule_type != 'cumulative') echo 'style="display: none"'; ?>
										data-help='<?php echo $help_cumulative; ?>'> <input
										type="hidden" class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison" value="" />
										<input type="hidden" class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value" value="" />
										<?php echo $text_enabled_successive_brackets; ?>
									</span>
								<?php } ?>
								
								<?php if (in_array('round', $rule_options['adjustments'])) { ?>
									<span class="round-html"
										<?php if ($rule_type != 'round') echo 'style="display: none"'; ?>>
										<select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison">
											<option value=""><?php echo $standard_select; ?></option>
											<option value="nearest"
												<?php if ($rule_comparison == 'nearest') echo 'selected="selected"'; ?>><?php echo $text_to_the_nearest; ?></option>
											<option value="up"
												<?php if ($rule_comparison == 'up') echo 'selected="selected"'; ?>><?php echo $text_up_to_the_nearest; ?></option>
											<option value="down"
												<?php if ($rule_comparison == 'down') echo 'selected="selected"'; ?>><?php echo $text_down_to_the_nearest; ?></option>
									</select> <input type="text" class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value"
										value="<?php if ($rule_type == 'round') echo $rule_value; ?>"
										data-help='<?php echo $help_round; ?>' />
									</span>
								<?php } ?>
								
								<?php if (!empty($setting_override_array)) { ?>
									<span class="setting_override-html"
										<?php if ($rule_type != 'setting_override') echo 'style="display: none"'; ?>>
										<select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison">
											<option value=""><?php echo $standard_select; ?></option>
											<?php $optgroup = $setting_override_array[0]['group']; ?>
											<optgroup
												label="<?php echo $setting_override_array[0]['group']; ?>">
												<?php foreach ($setting_override_array as $setting_override) { ?>
													<?php if ($setting_override['group'] != $optgroup) { ?>
														</optgroup>
											<optgroup label="<?php echo $setting_override['group']; ?>">
														<?php $optgroup = $setting_override['group']; ?>
													<?php } ?>
														<option value="<?php echo $setting_override['key']; ?>"
													title="<?php echo $setting_override['value']; ?>"
													<?php if ($rule_comparison == $setting_override['key']) echo 'selected="selected"'; ?>>
															<?php echo $setting_override['key']; ?>
														</option>
												<?php } ?>
											</optgroup>
									</select> <input type="text" class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value"
										value="<?php if ($rule_type == 'setting_override') echo $rule_value; ?>"
										data-help='<?php echo $help_setting_override; ?>' />
									</span>
								<?php } ?>
								
								<?php foreach (array('tax_class', 'total_value') as $criterion) { ?>
									<?php if (!in_array($criterion, $rule_options['adjustments'])) continue; ?>
									<span class="<?php echo $criterion; ?>-html"
										<?php if ($rule_type != $criterion) echo 'style="display: none"'; ?>>
										= <input type="hidden" class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison" value="" />
										<select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value"
										data-help='<?php echo ${'help_'.$criterion}; ?>'>
											<option value=""><?php echo $standard_select; ?></option>
											<?php foreach (${$criterion.'_array'} as $k => $v) { ?>
												<option value="<?php echo $k; ?>"
												<?php if ($rule_value == $k) echo 'selected="selected"'; ?>><?php echo $v; ?></option>
											<?php } ?>
										</select>
									</span>
								<?php } ?>
							<?php } /* adjustments */ ?>
							
							<?php if (!empty($rule_options['datetime_criteria'])) { ?>
								<?php foreach ($rule_options['datetime_criteria'] as $criterion) { ?>
									<span class="<?php echo $criterion; ?>-html"
										<?php if ($rule_type != $criterion) echo 'style="display: none"'; ?>>
										<?php if ($criterion == 'day') { ?>
											<select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison">
											<option value=""><?php echo $standard_select; ?></option>
											<option value="is"
												<?php if ($rule_comparison == 'is') echo 'selected="selected"'; ?>><?php echo $text_is; ?></option>
											<option value="not"
												<?php if ($rule_comparison == 'not') echo 'selected="selected"'; ?>><?php echo $text_is_not; ?></option>
									</select> <select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value"
										data-help='<?php echo $help_day; ?>'>
											<option value=""><?php echo $standard_select; ?></option>
												<?php foreach (array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday') as $day) { ?>
													<option value="<?php echo $day; ?>"
												<?php if ($rule_value == $day) echo 'selected="selected"'; ?>><?php echo ${'text_'.$day}; ?></option>
												<?php } ?>
											</select>
										<?php } else { ?>
											<select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison">
											<option value=""><?php echo $standard_select; ?></option>
											<option value="after"
												<?php if ($rule_comparison == 'after') echo 'selected="selected"'; ?>><?php echo $text_is_on_or_after; ?></option>
											<option value="before"
												<?php if ($rule_comparison == 'before') echo 'selected="selected"'; ?>><?php echo $text_is_on_or_before; ?></option>
									</select> <input type="text" class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value"
										placeholder="<?php echo ${'help_'.$criterion}; ?>"
										value="<?php if ($rule_type == $criterion) echo $rule_value; ?>"
										data-help='<?php echo $help_datetime_criteria; ?>' />
										<?php } ?>
									</span>
								<?php } ?>
							<?php } /* datetime_criteria */ ?>
							
							<?php if (!empty($rule_options['cart_criteria'])) { ?>
								<span
										class="<?php echo implode('-html ', $rule_options['cart_criteria']) . '-html'; ?>"
										<?php if (!in_array($rule_type, $rule_options['cart_criteria'])) echo 'style="display: none"'; ?>>
										<select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison"
										data-help='<?php echo $help_cart_criteria_comparisons; ?>'>
											<option value=""><?php echo $standard_select; ?></option>
											<optgroup
												label="<?php echo $text_eligible_item_comparisons; ?>">
												<option value="cart"
													<?php if ($rule_comparison == 'cart') echo 'selected="selected"'; ?>><?php echo $text_of_cart; ?></option>
												<option value="any"
													<?php if ($rule_comparison == 'any') echo 'selected="selected"'; ?>><?php echo $text_of_any_item; ?></option>
												<option value="every"
													<?php if ($rule_comparison == 'every') echo 'selected="selected"'; ?>><?php echo $text_of_every_item; ?></option>
											</optgroup>
											<optgroup
												label="<?php echo $text_entire_cart_comparisons; ?>">
												<option value="entire_cart"
													<?php if ($rule_comparison == 'entire_cart') echo 'selected="selected"'; ?>><?php echo $text_of_entire_cart; ?></option>
												<option value="entire_any"
													<?php if ($rule_comparison == 'entire_any') echo 'selected="selected"'; ?>><?php echo $text_of_any_item_in_entire_cart; ?></option>
												<option value="entire_every"
													<?php if ($rule_comparison == 'entire_every') echo 'selected="selected"'; ?>><?php echo $text_of_every_item_in_entire_cart; ?></option>
											</optgroup>
									</select> = <input type="text" class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value"
										value="<?php if (in_array($rule_type, $rule_options['cart_criteria'])) echo $rule_value; ?>"
										data-help='<?php echo $help_cart_criteria; ?>' />
									</span>
								<?php foreach ($rule_options['cart_criteria'] as $criterion) { ?>
									<span class="<?php echo $criterion; ?>-html"
										<?php if ($rule_type != $criterion) echo 'style="display: none"'; ?>><?php echo ${$criterion.'_unit'}; ?></span>
								<?php } ?>
							<?php } /* cart_criteria */ ?>
							
							<?php if (!empty($rule_options['location_criteria'])) { ?>
								<?php foreach (array('city', 'distance', 'postcode') as $criterion) { ?>
									<span class="<?php echo $criterion; ?>-html"
										<?php if ($rule_type != $criterion) echo 'style="display: none"'; ?>>
										<select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison">
											<option value=""><?php echo $standard_select; ?></option>
											<option value="is"
												<?php if ($rule_comparison == 'is') echo 'selected="selected"'; ?>><?php echo $text_is; ?></option>
											<option value="not"
												<?php if ($rule_comparison == 'not') echo 'selected="selected"'; ?>><?php echo $text_is_not; ?></option>
									</select> <input type="text" class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value"
										value="<?php if ($rule_type == $criterion) echo $rule_value; ?>"
										data-help='<?php echo ${'help_'.$criterion}; ?>' />
									</span>
								<?php } ?>
								
								<?php if (in_array('geo_zone', $rule_options['location_criteria'])) { ?>
									<span class="geo_zone-html"
										<?php if ($rule_type != 'geo_zone') echo 'style="display: none"'; ?>>
										<select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison">
											<option value=""><?php echo $standard_select; ?></option>
											<option value="is"
												<?php if ($rule_comparison == 'is') echo 'selected="selected"'; ?>><?php echo $text_is; ?></option>
											<option value="not"
												<?php if ($rule_comparison == 'not') echo 'selected="selected"'; ?>><?php echo $text_is_not; ?></option>
									</select> <select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value"
										data-help='<?php echo $help_geo_zone; ?>'>
											<option value=""><?php echo $standard_select; ?></option>
											<?php foreach ($geo_zone_array as $k => $v) { ?>
												<option value="<?php echo $k; ?>"
												<?php if ($rule_value == $k) echo 'selected="selected"'; ?>><?php echo $v; ?></option>
											<?php } ?>
										</select>
									</span>
								<?php } ?>
								
								<span class="location_comparison-html"
										<?php if ($rule_type != 'location_comparison') echo 'style="display: none"'; ?>>
										= <input type="hidden" class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison" value="" />
										<select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value"
										data-help='<?php echo $help_location_comparison; ?>'>
											<option value=""><?php echo $standard_select; ?></option>
											<option value="payment"
												<?php if ($rule_comparison == 'payment') echo 'selected="selected"'; ?>><?php echo $text_payment_address; ?></option>
											<option value="shipping"
												<?php if ($rule_comparison == 'shipping') echo 'selected="selected"'; ?>><?php echo $text_shipping_address; ?></option>
									</select>
									</span>
							<?php } /* location_criteria */ ?>
							
							<?php if (!empty($rule_options['order_criteria']['shipping_cost']) && $type != 'shipping') { ?>
								<?php $criterion = 'shipping_cost'; ?>
								<span class="<?php echo $criterion; ?>-html"
										<?php if ($rule_type != $criterion) echo 'style="display: none"'; ?>>
										= <input type="hidden" class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison" value="" />
										<input type="text" class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value"
										value="<?php if ($rule_type == $criterion) echo $rule_value; ?>"
										data-help='<?php echo ${'help_'.$criterion}; ?>' />
									</span>
							<?php } ?>
							
							<?php if (!empty($rule_options['order_criteria']['shipping_rate'])) { ?>
								<?php $criterion == 'shipping_rate'; ?>
								<span class="<?php echo $criterion; ?>-html"
										<?php if ($rule_type != $criterion) echo 'style="display: none"'; ?>>
										<select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison">
											<option value=""><?php echo $standard_select; ?></option>
											<option value="is"
												<?php if ($rule_comparison == 'is') echo 'selected="selected"'; ?>><?php echo $text_is; ?></option>
											<option value="not"
												<?php if ($rule_comparison == 'not') echo 'selected="selected"'; ?>><?php echo $text_is_not; ?></option>
									</select> <input type="text" class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value"
										value="<?php if ($rule_type == $criterion) echo $rule_value; ?>"
										data-help='<?php echo ${'help_'.$criterion}; ?>' />
									</span>
							<?php } ?>
							
							<?php if (!empty($rule_options['order_criteria'])) { ?>
								<?php foreach ($rule_options['order_criteria'] as $criterion) { ?>
									<?php if ($criterion == 'shipping_cost' || $criterion == 'shipping_rate') continue; ?>
									<span class="<?php echo $criterion; ?>-html"
										<?php if ($rule_type != $criterion) echo 'style="display: none"'; ?>>
										<?php if ($criterion == 'past_orders') { ?>
											<select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison"
										data-help='<?php echo $help_past_orders_dropdown; ?>'>
											<option value=""><?php echo $standard_select; ?></option>
											<option value="days"
												<?php if ($rule_comparison == 'days') echo 'selected="selected"'; ?>><?php echo $text_days; ?></option>
											<option value="quantity"
												<?php if ($rule_comparison == 'quantity') echo 'selected="selected"'; ?>><?php echo $text_quantity; ?></option>
											<option value="total"
												<?php if ($rule_comparison == 'total') echo 'selected="selected"'; ?>><?php echo $text_total; ?></option>
									</select> = <input type="text" class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value"
										value="<?php if ($rule_type == $criterion) echo $rule_value; ?>"
										data-help='<?php echo ${'help_'.$criterion}; ?>' />
										<?php } else { ?>
											<select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison">
											<option value=""><?php echo $standard_select; ?></option>
											<option value="is"
												<?php if ($rule_comparison == 'is') echo 'selected="selected"'; ?>><?php echo $text_is; ?></option>
											<option value="not"
												<?php if ($rule_comparison == 'not') echo 'selected="selected"'; ?>><?php echo $text_is_not; ?></option>
									</select> <select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value"
										data-help='<?php echo ${'help_'.$criterion}; ?>'>
											<option value=""><?php echo $standard_select; ?></option>
												<?php foreach (${$criterion.'_array'} as $k => $v) { ?>
													<option value="<?php echo $k; ?>"
												<?php if ($rule_value == $k) echo 'selected="selected"'; ?>><?php echo $v; ?></option>
												<?php } ?>
											</select>
										<?php } ?>
									</span>
								<?php } ?>
							<?php } /* order_criteria */ ?>
							
							<?php if (!empty($rule_options['product_criteria'])) { ?>
								<?php foreach ($rule_options['product_criteria'] as $criterion) { ?>
									<span class="<?php echo $criterion; ?>-html"
										<?php if ($rule_type != $criterion) echo 'style="display: none"'; ?>>
										<?php if ($criterion == 'option') { ?>
											<input type="text" class="form-control typeahead"
										data-type="option"
										name="<?php echo $key . '_' . $rule; ?>_comparison"
										value="<?php if ($rule_type == $criterion) echo $rule_comparison; ?>"
										data-help='<?php echo $help_option; ?>' /> = <input
										type="text" class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value"
										value="<?php if ($rule_type == $criterion) echo $rule_value; ?>"
										data-help='<?php echo $help_option_value; ?>' />
										<?php } elseif ($criterion == 'product_group') { ?>
											<?php echo $text_cart_has_items_from; ?>
											<select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison"
										data-help='<?php echo $help_product_group_comparison; ?>'>
											<option value=""><?php echo $standard_select; ?></option>
											<option value="any"
												<?php if ($rule_comparison == 'any') echo 'selected="selected"'; ?>><?php echo $text_any; ?></option>
											<option value="all"
												<?php if ($rule_comparison == 'all') echo 'selected="selected"'; ?>><?php echo $text_all; ?></option>
											<option value="not"
												<?php if ($rule_comparison == 'not') echo 'selected="selected"'; ?>><?php echo $text_not; ?></option>
											<option value="onlyany"
												<?php if ($rule_comparison == 'onlyany') echo 'selected="selected"'; ?>><?php echo $text_only_any; ?></option>
											<option value="onlyall"
												<?php if ($rule_comparison == 'onlyall') echo 'selected="selected"'; ?>><?php echo $text_only_all; ?></option>
											<option value="none"
												<?php if ($rule_comparison == 'none') echo 'selected="selected"'; ?>><?php echo $text_none_of_the; ?></option>
									</select>
											<?php echo $text_members_of; ?>
											<select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value"
										data-value="<?php if ($rule_type == 'product_group') echo $rule_value; ?>"
										data-help='<?php echo $help_product_group; ?>'>
											<option value=""><?php echo $standard_select; ?></option>
												<?php foreach ($product_groups as $k => $v) { ?>
													<option value="<?php echo $k; ?>"
												<?php if ($rule_value == $k) echo 'selected="selected"'; ?>><?php echo $v; ?></option>
												<?php } ?>
											</select>
										<?php } elseif ($criterion == 'other_product_data') { ?>
											<select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison"
										data-help='<?php echo $help_other_product_data_column; ?>'>
											<option value=""><?php echo $standard_select; ?></option>
												<?php foreach ($product_columns as $column) { ?>
													<option value="<?php echo $column; ?>"
												<?php if ($rule_comparison == $column) echo 'selected="selected"'; ?>><?php echo $column; ?></option>
												<?php } ?>
											</select> <input type="text" class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value"
										value="<?php if ($rule_type == $criterion) echo $rule_value; ?>"
										data-help='<?php echo ${'help_'.$criterion}; ?>' />
										<?php } else { ?>
											<select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison">
											<option value=""><?php echo $standard_select; ?></option>
											<option value="is"
												<?php if ($rule_comparison == 'is') echo 'selected="selected"'; ?>><?php echo $text_is; ?></option>
											<option value="not"
												<?php if ($rule_comparison == 'not') echo 'selected="selected"'; ?>><?php echo $text_is_not; ?></option>
									</select> <input type="text" class="form-control typeahead"
										data-type="<?php echo $criterion; ?>"
										name="<?php echo $key . '_' . $rule; ?>_value"
										value="<?php if ($rule_type == $criterion) echo $rule_value; ?>"
										data-help='<?php echo ${'help_'.$criterion}; ?>' />
										<?php } ?>
									</span>
								<?php } ?>
							<?php } /* product_criteria */ ?>
							
							<?php if (!empty($rule_options['rule_sets'])) { ?>
								<span class="rule_set-html"
										<?php if ($rule_type != 'rule_set') echo 'style="display: none"'; ?>>
										<input type="hidden" class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_comparison" value="" />
										<select class="form-control"
										name="<?php echo $key . '_' . $rule; ?>_value"
										data-value="<?php if ($rule_type == 'rule_set') echo $rule_value; ?>"
										data-help='<?php echo $help_rule_set; ?>'>
											<option value=""><?php echo $standard_select; ?></option>
										<?php foreach ($rule_sets as $k => $v) { ?>
											<option value="<?php echo $k; ?>"
												<?php if ($rule_value == $k) echo 'selected="selected"'; ?>><?php echo $v; ?></option>
										<?php } ?>
									</select>
									</span>
							<?php } /* rule_sets */ ?>
							
						</div>
					<?php } ?>
					
					<a class="btn btn-success btn-xs add-button"
									onclick="clearRow(copyRow($(this).prev())).children('select').change();"
									style="margin-top: 5px"
									data-help='<?php echo $help_add_rule; ?>'><i class="fa fa-plus"></i> <?php echo $button_add_rule; ?></a>
					
				<?php } elseif ($setting['type'] == 'typeahead') { ?>
					
							<?php echo $text_autocomplete_from; ?>
							<select class="nosave form-control"
									onchange="attachTypeaheads($(this).parents('td'))"
									style="display: inline-block"
									data-help='<?php echo $help_autocomplete_from; ?>'>
									<option value="all"><?php echo $text_all_database_tables; ?></option>
								<?php foreach ($typeahead_types as $typeahead_type) { ?>
									<option value="<?php echo $typeahead_type; ?>"><?php echo ($typeahead_type == 'category') ? 'Categories' : ucwords(str_replace('_', ' ', $typeahead_type)) . 's'; ?></option>
								<?php } ?>
							</select>
							</div>
							<div class="setting">
								<input type="text" class="nosave form-control typeahead"
									data-key="<?php echo $key; ?>"
									data-type="<?php echo $setting['type']; ?>"
									placeholder="<?php echo $placeholder_group_members; ?>"
									data-help='<?php echo $help_group_members; ?>' />
							</div>
					<?php if (isset($saved['display']) && $saved['display'] == 'collapsed') { ?>
							</div>
				
				</td>
				<td>
					<div class="collapsed">
					<?php } else { ?>
						
				
				</td>
				<td>
					<?php } ?>
						<div class="product-group-scrollbox">
							<?php $scrollbox_list = array(); ?>
							<?php foreach($saved as $saved_key => $saved_value) { ?>
								<?php if (strpos($saved_key, $key) === 0) $scrollbox_list[$saved_value] = $saved_key; ?>
							<?php } ?>
							<?php uksort($scrollbox_list, 'strcasecmp'); ?>
							<?php foreach ($scrollbox_list as $saved_value => $saved_key) { ?>
								<div>
							<a class="btn btn-danger btn-xs"
								data-help='<?php echo $button_delete; ?>'
								onclick="removeRow($(this).parent())"><i
								class="fa fa-trash-o fa-lg"></i></a>
									&nbsp;<?php echo $saved_value; ?><input type="hidden"
								class="form-control" name="<?php echo $saved_key; ?>"
								value="<?php echo $saved_value; ?>" /> <span
								style="display: none"><?php echo $saved_value; ?></span>
						</div>
							<?php } ?>
						</div>
						
				<?php } else { ?>
					
					<input type="<?php echo $setting['type']; ?>"
					<?php echo $all_attributes; ?>
					value='<?php echo str_replace("'", "\'", $value); ?>' />
				
				<?php } ?>
				
				<?php if (isset($setting['after'])) { ?>
					<?php echo $setting['after']; ?>
				<?php } ?>
				
				<?php if (isset($key) && (isset(${'entry_'.$key}) || isset($setting['title']))) { ?>
						
			
			</div>
			<!-- .form-control -->
	
	</div>
	<!-- .form-group -->
				<?php } else { ?>
					</div>
<!-- .setting -->
<?php } ?>
				
			<?php } /* end $settings foreach loop */ ?>
			
			<?php if (isset($active_tab)) { ?>
</div>
</div>
<?php } ?>

</form>
</div>

<?php echo $copyright; ?>

</div>

<script type="text/javascript"><!--
	// Settings Functions
	var retries = [];
	
	<?php if (!$permission) { ?>
		$(':input').attr('disabled', 'disabled');
	<?php } else { ?>
		$(document).on('change', 'form.autosave :input', function(){
			var element = $(this);
			if (element.hasClass('nosave') || element.find('option:selected').hasClass('nosave') || element.attr('name').indexOf('_0_') != -1) {
				return;
			}
			element.addClass('saving').removeClass('save-error');
			if (retries[element.attr('name')] == undefined) {
				retries[element.attr('name')] = 0;
			}
			var checkboxes = $('input[name="' + element.attr('name') + '"]:checked').map(function(){ return this.value; }).get().join(';');
			$.ajax({
				type: 'POST',
				url: 'index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/saveSetting&token=<?php echo $token; ?>',
				data: (element.attr('type') == 'checkbox' ? element.attr('name') + '=' + checkboxes : element),
				success: function(error) {
					$('.add-button').removeAttr('disabled');
					element.removeClass('saving').removeClass('save-error');
					if (error) {
						if (retries[element.attr('name')] < 5) {
							retries[element.attr('name')]++;
							element.change();
						} else if (error == 'PermissionError') {
							element.addClass('save-error');
							$('.alert').remove();
							$('.autosave').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle fa-lg pad-right"></i> <?php echo $standard_error; ?></div>').fadeIn();
						} else {
							alert('Please try saving setting "' + element.attr('name') + '" again. There was an error saving the setting:' + "\n\n" + error);
						}
					} else {
						retries[element.attr('name')] = 0;
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					element.removeClass('saving').addClass('save-error');
					alert('Please try saving setting "' + element.attr('name') + '" again. There was an error saving the setting:' + "\n\n" + jqXHR.status + ' (' + errorThrown + ')');
				}
			});
		});
	<?php } ?>
	
	<?php foreach ($no_setting as $rule_type => $setting_to_save) { ?>
		<?php if (empty($rule_type)) continue; ?>
		$('.<?php echo $rule_type; ?>-html *[name=<?php echo $setting_to_save; ?>]').change();
	<?php } ?>
	
	<?php if ($permission && $settings_buttons) { ?>
		function backupSettings() {
			if (!$('#manual-backup-time').text() || confirm('<?php echo $text_this_will_overwrite_your; ?>')) {
				$.ajax({
					url: 'index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/backupSettings&token=<?php echo $token; ?>',
					success: function(time) {
						if (!time) {
							$('.alert').remove();
							$('.autosave').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle fa-lg"></i> <?php echo $standard_error; ?></div>').fadeIn();
						} else {
							$('#manual-backup-time').html(time);
							$('#restore-button').removeClass('disabled');
							$('.alert').remove();
							$('.autosave').before('<div class="alert alert-success"><i class="fa fa-check fa-lg pad-right"></i> <?php echo $text_backup_saved_to; ?> ' + time + ' &nbsp; (<a target="_blank" href="index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/viewBackup&token=<?php echo $token; ?>"><?php echo $text_view_backup; ?></a>) &nbsp; (<a href="index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/downloadBackup&token=<?php echo $token; ?>"><?php echo $text_download_backup_file; ?></a>)</div>').fadeIn();
						}
					}
				});
			}
		}
	<?php } ?>
	
	// UI Functions
	$(document).on('click', '.add-button', function(){
		$(this).attr('disabled', 'disabled');
	});
	
	$(document).on('dblclick', '.collapsed', function(){
		$(this).parents('tr').find('.expand a').click();
	});
	
	// Tooltip Functions
	function attachTooltips(element) {
		$('.tooltip').hide();
		element.find('*[data-help]').each(function(){
			if ($('input[name="tooltips"]').val() == 1) {
				$(this).attr('title', $(this).attr('data-help')).tooltip({'animation': false, 'placement': ($(this).attr('data-help').length < 400) ? 'top' : 'right', 'html':true});
			} else {
				$(this).tooltip('destroy');
			}
		});
	}
	<?php if (!isset($saved['tooltips']) || $saved['tooltips']) { ?>
		attachTooltips($('body'));
	<?php } ?>
	
	// Typeahead Functions
	<?php if (!empty($saved['autocomplete_preloading'])) { ?>
		var localdata = {
			<?php foreach (array_merge(array('all'), $typeahead_types) as $typeahead_type) { ?>
				'<?php echo $typeahead_type; ?>': [<?php echo ${$typeahead_type.'_preload'}; ?>],
			<?php } ?>
		};
	<?php } ?>
	
	var currentTypeaheadValue = '';
	
	function attachTypeaheads(element) {
		<?php if (empty($typeahead_types)) { ?>
			return;
		<?php } ?>
		element.find('.typeahead').each(function(){
			var element = $(this);
			var type = (element.attr('data-type') == 'typeahead') ? element.parents('td').find('select').val() : element.attr('data-type');
			element.typeahead('destroy').typeahead({
				limit: 100,
				<?php if (!empty($saved['autocomplete_preloading'])) { ?>
					local: localdata[type],
				<?php } ?>
				remote: 'index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/typeahead&token=<?php echo $token; ?>&type=' + type + '&q=%QUERY'
			}).on('keydown', function(e) {
				if (e.which == 13 && $('.tt-is-under-cursor').length < 1) {
					currentTypeaheadValue = '';
					element.parent().find('.tt-suggestion:first-child').click();
				}
			}).on('keyup', function(e) {
				currentTypeaheadValue = element.val();
			}).on('typeahead:selected', function(obj, datum) {
				if (element.val().indexOf('[') != -1) {
					var scrollbox = element.parents('tr').find('.product-group-scrollbox');
					if (scrollbox.length) {
						if (!scrollbox.find('input[value="' + element.val() + '"]').length) {
							scrollbox.addClass('saving');
							var firstUnusedNumber = 1;
							while (scrollbox.html().indexOf('_member_' + firstUnusedNumber) != -1) {
								firstUnusedNumber++;
							}
							scrollbox.append('<div><a class="btn btn-danger btn-xs" data-help="<?php echo $button_delete; ?>" onclick="removeRow($(this).parent())"><i class="fa fa-trash-o fa-lg"></i></a> &nbsp;' + element.val() + '<input type="hidden" class="form-control" name="' + element.attr('data-key') + '_' + firstUnusedNumber + '" value="' + element.val() + '" /><span style="display: none">' + element.val() + '</span></div>').find('input').last().change();
							scrollbox.append(scrollbox.children().sort(function(a, b) { A = $('input', a).val().toLowerCase(); B = $('input', b).val().toLowerCase(); return (A < B) ? -1 : (A > B) ? 1 : 0; }));
							setTimeout(function(){ scrollbox.removeClass('saving'); }, 500);
						}
						element.typeahead('setQuery', currentTypeaheadValue).focus();
					} else {
						element.typeahead('setQuery', element.val().replace(/\[.*:/, '[')).change();
					}
				}
			});
		});
	}
	<?php if (empty($saved['display']) || $saved['display'] == 'expanded') { ?>
		attachTypeaheads($('form.autosave'));
	<?php } ?>
	
	// Row Functions
	function removeRow(row) {
		if (row.parent().find('tr').length != 1) {
			row.find(':input').each(function(){
				$.get('index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/deleteSetting&setting=' + $(this).attr('name') + '&token=<?php echo $token; ?>');
			});
			row.remove();
		} else {
			clearRow(row);
		}
	}
	
	function clearRow(row) {
		row.find(':input').each(function(){
			$.get('index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/deleteSetting&setting=' + $(this).attr('name') + '&token=<?php echo $token; ?>');
		});
		row.find('input[type=text], textarea').val('');
		row.find(':checked').removeAttr('checked');
		row.find(':selected').removeAttr('selected');
		row.find('.rule').not(':first-child').remove();
		row.find('.product-group-scrollbox div').remove();
		return row;
	}
	
	function copyRow(row) {
		row.find('input').each(function(){
			$(this).attr('value', $(this).val());
			if ($(this).is(':checked')) {
				$(this).attr('checked', 'checked');
			} else {
				$(this).removeAttr('checked');
			}
		});
		var clone = row.clone();
		row.find('option').each(function(i){
			if($(this).is(':selected')) {
				clone.find('option').eq(i).attr('selected', 'selected');
			} else {
				clone.find('option').eq(i).removeAttr('selected');
			}
		});
		var firstUnusedNumber = 1;
		while (row.parent().html().indexOf(row.attr('class') + '_' + firstUnusedNumber + '_') != -1) {
			firstUnusedNumber++;
		}
		clone.html(clone.html().replace(new RegExp(row.attr('class') + '_(\\d+)_', 'g'), row.attr('class') + '_' + firstUnusedNumber + '_'));
		clone.find('.typeahead').each(function(){
			$(this).parent().after($(this)).remove();
		});
		attachTooltips(clone);
		attachTypeaheads(clone);
		clone.insertAfter(row).show();
		return row.next();
	}
	
	function saveRow(row) {
		row.find('.form-control').each(function(){
			$(this).change();
		});
	}
	
	// Rule Functions
	function loadDropdown(element) {
		$.ajax({
			async: false,
			url: 'index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/loadDropdown&type=' + element.parent().attr('class').replace('-html', '') + '&token=<?php echo $token; ?>',
			success: function(data) {
				var selectedValue = (data.indexOf('value="' + element.attr('data-value') + '"') != -1) ? element.attr('data-value') : element.find('option:first-child').val();
				element.html(data);
				if (selectedValue) {
					element.attr('data-value', selectedValue).val(selectedValue);
				}
			}
		});
	}
	
	$(document).on('change', '.rule > select', function(){
		$(this).parent().find('.form-control').addClass('nosave');
		$(this).parent().children('span:not(.' + $(this).val() + '-html)').hide().find('.form-control[type="text"]').val('');
		$(this).removeClass('nosave').parent().find('.' + $(this).val() + '-html .form-control').removeClass('nosave');
		if ($(this).val() == 'rule_set' || $(this).val() == 'product_group') {
			loadDropdown($(this).parent().find('.' + $(this).val() + '-html select[data-value]'));
		}
		saveRow($(this).parent().find('.' + $(this).val() + '-html').show());
	});
	
	$(document).on('change', 'select[data-value]', function(){
		$(this).attr('data-value', $(this).val());
	});
//--></script>
<?php echo $footer; ?>