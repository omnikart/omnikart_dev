<?php
// -----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
// -----------------------------------------------------
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<a href="<?php echo $csv; ?>" data-toggle="tooltip"
					title="<?php echo $button_csv; ?>" class="btn btn-default"><i
					class="fa fa-file-text-o"></i></a>
				<button type="submit" form="mainform" data-toggle="tooltip"
					title="<?php echo $button_delete; ?>" class="btn btn-danger">
					<i class="fa fa-trash-o"></i>
				</button>
			</div>
			<h1><?php echo $heading_title; ?></h1>
		</div>
	</div>
	<div class="container-fluid">
        <?php if ($error_warning) { ?>
            <div class="alert alert-danger">
			<i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
        <?php } ?>
        <?php if ($success) { ?>
            <div class="alert alert-success">
			<i class="fa fa-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
        <?php } ?>
        <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-users"></i> <?php echo $text_marketing_subscribers; ?></h3>
			</div>
			<div class="panel-body">
                <?php array_unshift($stores, array('store_id' => 0, 'name' => $text_default)); ?>
                <form action="<?php echo $action; ?>" method="post"
					enctype="multipart/form-data" id="form" class="form-horizontal">
					<fieldset>
						<legend><?php echo $text_add_info; ?></legend>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-store"><?php echo $entry_store; ?></label>
							<div class="col-sm-10">
								<select name="store_id" id="input-store" class="form-control">
                                    <?php foreach ($stores as $store) { ?>
                                        <option
										value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                                    <?php } ?>
                                </select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-emails"><span
								data-toggle="tooltip" title="<?php echo $help_subscribers; ?>"><?php echo $entry_subscribers; ?></span></label>
							<div class="col-sm-10">
								<textarea name="emails" id="input-emails" placeholder=""
									rows="5" class="form-control"></textarea>
							</div>
						</div>
                        <?php foreach ($stores as $store) { ?>
                            <?php if (isset($list_data[$store['store_id']]) && $list_data[$store['store_id']]) { ?>
                                <div class="form-group list-display"
							id="list-<?php echo $store['store_id']; ?>">
							<div class="col-sm-offset-2 col-sm-10">
                                        <?php foreach ($list_data[$store['store_id']] as $key => $list) { ?>
                                            <div class="checkbox">
									<label> <input name="list[<?php echo $store['store_id']; ?>][]"
										type="checkbox" value="<?php echo $key; ?>" />
                                                    <?php echo $list[$config_language_id]; ?>
                                                </label>
								</div>
                                        <?php } ?>
                                    </div>
						</div>
                            <?php } ?>
                        <?php } ?>
                        <button type="submit" form="form"
							data-toggle="tooltip" title="<?php echo $button_insert; ?>"
							class="btn btn-primary pull-right">
							<i class="fa fa-plus"></i>
						</button>
					</fieldset>
				</form>
				<fieldset>
					<legend><?php echo $text_marketing_subscribers_list; ?></legend>
					<div class="well">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label" for="input-name"><?php echo $column_name; ?></label>
									<input type="text" name="filter_name"
										value="<?php echo $filter_name; ?>"
										placeholder="<?php echo $column_name; ?>" id="input-name"
										class="form-control" />
								</div>
								<div class="form-group">
									<label class="control-label" for="input-email"><?php echo $column_email; ?></label>
									<input type="text" name="filter_email"
										value="<?php echo $filter_email; ?>"
										placeholder="<?php echo $column_email; ?>" id="input-email"
										class="form-control" />
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label" for="input-subscribed"><?php echo $column_subscribed; ?></label>
									<select name="filter_subscribed" id="input-subscribed"
										class="form-control">
										<option value=""></option>
                                        <?php if ($filter_subscribed == '1') { ?>
                                            <option value="1"
											selected="selected"><?php echo $entry_yes; ?></option>
                                        <?php } else { ?>
                                            <option value="1"><?php echo $entry_yes; ?></option>
                                        <?php } ?>
                                        <?php if ($filter_subscribed == '0') { ?>
                                            <option value="0"
											selected="selected"><?php echo $entry_no; ?></option>
                                        <?php } else { ?>
                                            <option value="0"><?php echo $entry_no; ?></option>
                                        <?php } ?>
                                    </select>
								</div>
								<div class="form-group">
									<label class="control-label" for="input-store"><?php echo $column_store; ?></label>
									<select name="filter_store" id="input-store"
										class="form-control">
										<option value=""></option>
                                        <?php foreach ($stores as $store) { ?>
                                            <?php if ($filter_store == $store['store_id'] && $filter_store != '') { ?>
                                                <option
											value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                                            <?php } else { ?>
                                                <option
											value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label" for="input-language"><?php echo $column_language; ?></label>
									<select name="filter_language" id="input-language"
										class="form-control">
										<option value="*"></option>
                                        <?php if ($filter_language === '') { ?>
                                            <option value=""
											selected="selected"><?php echo $text_default; ?></option>
                                        <?php } else { ?>
                                            <option value=""><?php echo $text_default; ?></option>
                                        <?php } ?>
                                        <?php foreach ($languages as $language) { ?>
                                            <?php if ($filter_language == $language['code'] && $filter_language != '') { ?>
                                                <option
											value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                                            <?php } else { ?>
                                                <option
											value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
								</div>
                                <?php if ($list_data) { ?>
                                    <?php foreach ($stores as $store) { ?>
                                        <div
									class="form-group list-store-display"
									id="list-store-<?php echo $store['store_id']; ?>">
									<label class="control-label"><?php echo $column_list; ?></label>
									<div class="well well-sm"
										style="max-height: 150px; overflow: auto; background: #ffffff;">
										<h4><?php echo $store['name']; ?></h4>
                                                <?php if (isset($list_data[$store['store_id']])) foreach ($list_data[$store['store_id']] as $key => $list) { ?>
                                                    <div
											class="checkbox">
											<label>
                                                            <?php if ($filter_list && is_array($filter_list) && in_array($store['store_id'] . '_' . $key, $filter_list)) { ?>
                                                                <input
												type="checkbox" name="filter_list[]"
												value="<?php echo $store['store_id']; ?>_<?php echo $key; ?>"
												checked="checked" />
                                                            <?php } else { ?>
                                                                <input
												type="checkbox" name="filter_list[]"
												value="<?php echo $store['store_id']; ?>_<?php echo $key; ?>" />
                                                            <?php } ?>
                                                            <?php echo $list[$config_language_id]; ?>
                                                        </label>
										</div>
                                                <?php } ?>
                                            </div>
								</div>
                                    <?php } ?>
                                <?php } ?>
                                <button type="button" id="button-filter"
									class="btn btn-primary pull-right">
									<i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
							</div>
						</div>
					</div>
					<form action="<?php echo $delete; ?>" method="post"
						enctype="multipart/form-data" id="mainform"
						class="form-horizontal">
						<div class="table-responsive">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<td style="width: 1px;" class="text-center"><input
											type="checkbox"
											onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
										<td class="text-left">
                                            <?php if ($sort == 'name') { ?>
                                                <a
											href="<?php echo $sort_name; ?>"
											class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                                            <?php } else { ?>
                                                <a
											href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                            <?php } ?>
                                        </td>
										<td class="text-left">
                                            <?php if ($sort == 'email') { ?>
                                                <a
											href="<?php echo $sort_email; ?>"
											class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
                                            <?php } else { ?>
                                                <a
											href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
                                            <?php } ?>
                                        </td>
                                        <?php if ($list_data) { ?>
                                            <td class="text-left"><?php echo $column_list; ?></td>
                                        <?php } ?>
                                        <td class="text-right">
                                            <?php if ($sort == 'subscribed') { ?>
                                                <a
											href="<?php echo $sort_subscribed; ?>"
											class="<?php echo strtolower($order); ?>"><?php echo $column_subscribed; ?></a>
                                            <?php } else { ?>
                                                <a
											href="<?php echo $sort_subscribed; ?>"><?php echo $column_subscribed; ?></a>
                                            <?php } ?>
                                        </td>
										<td class="text-right">
                                            <?php if ($sort == 'store_id') { ?>
                                                <a
											href="<?php echo $sort_store; ?>"
											class="<?php echo strtolower($order); ?>"><?php echo $column_store; ?></a>
                                            <?php } else { ?>
                                                <a
											href="<?php echo $sort_store; ?>"><?php echo $column_store; ?></a>
                                            <?php } ?>
                                        </td>
										<td class="text-right">
                                            <?php if ($sort == 'language_code') { ?>
                                                <a
											href="<?php echo $sort_language; ?>"
											class="<?php echo strtolower($order); ?>"><?php echo $column_language; ?></a>
                                            <?php } else { ?>
                                                <a
											href="<?php echo $sort_language; ?>"><?php echo $column_language; ?></a>
                                            <?php } ?>
                                        </td>
										<td class="text-right"><?php echo $column_actions; ?></td>
									</tr>
								</thead>
								<tbody>
                                    <?php if ($marketings) { ?>
                                        <?php foreach ($marketings as $marketing) { ?>
                                            <tr>
										<td class="text-center">
                                                    <?php if (in_array($marketing['marketing_id'], $selected)) { ?>
                                                        <input
											type="checkbox" name="selected[]"
											value="<?php echo $marketing['marketing_id']; ?>"
											checked="checked" />
                                                    <?php } else { ?>
                                                        <input
											type="checkbox" name="selected[]"
											value="<?php echo $marketing['marketing_id']; ?>" />
                                                    <?php } ?>
                                                </td>
										<td class="text-left"><?php echo $marketing['name']; ?></td>
										<td class="text-left"><?php echo $marketing['email']; ?></td>
                                                <?php if ($list_data) { ?>
                                                    <td
											class="text-left">
                                                        <?php
																																							
$list_out = array ();
																																							foreach ( $marketing ['list'] as $list_key ) {
																																								if (isset ( $list_data [$marketing ['store_id']] [$list_key] )) {
																																									$list_out [] = $list_data [$marketing ['store_id']] [$list_key] [$config_language_id];
																																								}
																																							}
																																							if ($list_out)
																																								echo implode ( ', ', $list_out );
																																							?>
                                                    </td>
                                                <?php } ?>
                                                <td class="text-right">
                                                    <?php if ($marketing['subscribed'] == '1') { ?>
                                                        <?php echo $entry_yes; ?>
                                                    <?php } else { ?>
                                                        <?php echo $entry_no; ?>
                                                    <?php } ?>
                                                </td>
										<td class="text-right">
                                                    <?php foreach ($stores as $store) { ?>
                                                        <?php if ($marketing['store_id'] == $store['store_id']) { ?>
                                                            <?php echo $store['name']; ?>
                                                            <?php break; ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </td>
										<td class="text-right">
                                                    <?php if (!$marketing['language_code']) { ?>
                                                        <?php echo $text_default; ?>
                                                    <?php } else foreach ($languages as $language) { ?>
                                                        <?php if ($marketing['language_code'] == $language['code']) { ?>
                                                            <?php echo $language['name']; ?>
                                                            <?php break; ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </td>
										<td class="text-right">
                                                    <?php if ($marketing['subscribed']) { ?>
                                                        <a
											href="<?php echo $unsubscribe . $marketing['marketing_id']; ?>"
											data-toggle="tooltip"
											title="<?php echo $button_unsubscribe; ?>"
											class="btn btn-danger"><i class="fa fa-minus"></i></a>
                                                    <?php } else { ?>
                                                        <a
											href="<?php echo $subscribe . $marketing['marketing_id']; ?>"
											data-toggle="tooltip"
											title="<?php echo $button_subscribe; ?>"
											class="btn btn-primary"><i class="fa fa-plus"></i></a>
                                                    <?php } ?>
                                                </td>
									</tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
										<td class="text-center"
											colspan="<?php echo ($list_data ? 8 : 7); ?>"><?php echo $text_no_results; ?></td>
									</tr>
                                    <?php } ?>
                                </tbody>
							</table>
						</div>
					</form>
					<div class="row">
						<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
						<div class="col-sm-6 text-right"><?php echo $results; ?></div>
					</div>
				</fieldset>
			</div>
		</div>
		<p class="text-center small">Newsletter Enhancements OpenCart Module
			v3.7.2</p>
	</div>
	<script type="text/javascript"><!--
        $('#button-filter').on('click', function() {
            var url = 'index.php?route=ne/marketing&token=<?php echo $token; ?>';

            var filter_name = $('input[name=\'filter_name\']').val();

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }

            var filter_email = $('input[name=\'filter_email\']').val();

            if (filter_email) {
                url += '&filter_email=' + encodeURIComponent(filter_email);
            }

            var filter_subscribed = $('select[name=\'filter_subscribed\']').val();

            if (filter_subscribed) {
                url += '&filter_subscribed=' + encodeURIComponent(filter_subscribed);
            }

            var filter_list = [];

            $.each($('input[name=\'filter_list[]\']:checked'), function() {
                filter_list.push($(this).val());
            });

            if (filter_list.length) {
                url += '&filter_list=' + encodeURIComponent(filter_list.join());
            }

            var filter_store = $('select[name=\'filter_store\']').val();

            if (filter_store) {
                url += '&filter_store=' + encodeURIComponent(filter_store);
            }

            var filter_language = $('select[name=\'filter_language\']').val();

            if (filter_language != "*") {
                url += '&filter_language=' + encodeURIComponent(filter_language);
            }

            location = url;
        });
    //--></script>
	<script type="text/javascript"><!--
        $('select[name=\'store_id\']').change(function(){
            $('.list-display').hide();
            $('#list-' + $(this).val()).show();
        }).trigger('change');

        $('select[name=\'filter_store\']').change(function(){
            $('.list-store-display').hide();
            if ($(this).val() != '') {
                $('#list-store' + $(this).attr('value')).show();
            } else {
                $('.list-store-display').show();
            }
        }).trigger('change');
    //--></script>
</div>
<?php echo $footer; ?>