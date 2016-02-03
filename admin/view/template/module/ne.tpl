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
				<button type="submit" form="form" data-toggle="tooltip"
					title="<?php echo $button_save; ?>" class="btn btn-primary">
					<i class="fa fa-save"></i>
				</button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip"
					title="<?php echo $button_cancel; ?>" class="btn btn-default"><i
					class="fa fa-reply"></i></a>
			</div>
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
		</div>
	</div>
	<div class="container-fluid">
        <?php if ($error_warning) { ?>
            <div class="alert alert-danger">
			<i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
        <?php } ?>
        <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post"
					enctype="multipart/form-data" id="form" class="form-horizontal">
					<input type="hidden" name="ne_embedded_images" value="0" />
                    <?php if (isset($licensor)) { ?>
                        <fieldset>
						<legend><?php echo $text_licence_info; ?></legend>
						<input type="hidden" name="website"
							value="<?php echo $website; ?>" />
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-email"><?php echo $entry_transaction_email; ?></label>
							<div class="col-sm-10">
								<input type="text" name="email" value=""
									placeholder="<?php echo $entry_transaction_email; ?>"
									id="input-email" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-transaction-id"><?php echo $entry_transaction_id; ?></label>
							<div class="col-sm-10">
								<input type="text" name="transaction_id" value=""
									placeholder="<?php echo $entry_transaction_id; ?>"
									id="input-transaction-id" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $entry_website; ?></label>
							<div class="col-sm-10">
								<p class="form-control-static"><?php echo $website; ?></p>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-primary"><?php echo $button_activate; ?></button>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-10 col-sm-offset-2">
                                    <?php echo $text_licence_text; ?>
                                </div>
						</div>
					</fieldset>
                    <?php } else { ?>
                        <fieldset>
						<legend><?php echo $text_throttle_settings; ?></legend>
						<div class="form-group">
							<label class="col-sm-2 control-label"><span data-toggle="tooltip"
								title="<?php echo $help_use_throttle; ?>"><?php echo $entry_use_throttle; ?></span></label>
							<div class="col-sm-10">
								<label class="radio-inline">
                                        <?php if ($ne_throttle) { ?>
                                            <input type="radio"
									name="ne_throttle" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                            <input type="radio"
									name="ne_throttle" value="1" />
                                            <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label> <label class="radio-inline">
                                        <?php if (!$ne_throttle) { ?>
                                            <input type="radio"
									name="ne_throttle" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                        <?php } else { ?>
                                            <input type="radio"
									name="ne_throttle" value="0" />
                                            <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
							</div>
						</div>
						<div class="form-group throttle">
							<label class="col-sm-2 control-label" for="input-throttle-count"><span
								data-toggle="tooltip"
								title="<?php echo $help_throttle_emails; ?>"><?php echo $entry_throttle_emails; ?></span></label>
							<div class="col-sm-10">
								<input type="text" name="ne_throttle_count"
									value="<?php echo $ne_throttle_count; ?>"
									placeholder="<?php echo $entry_throttle_emails; ?>"
									id="input-throttle-count" class="form-control" />
							</div>
						</div>
						<div class="form-group throttle">
							<label class="col-sm-2 control-label" for="input-throttle-time"><span
								data-toggle="tooltip" title="<?php echo $help_throttle_time; ?>"><?php echo $entry_throttle_time; ?></span></label>
							<div class="col-sm-10">
								<select name="ne_throttle_time" id="input-throttle-time"
									class="form-control">
									<option value="60"
										<?php echo $ne_throttle_time == '60' ? ' selected="selected"' : "" ?>>1</option>
									<option value="300"
										<?php echo $ne_throttle_time == '300' ? ' selected="selected"' : "" ?>>5</option>
									<option value="600"
										<?php echo $ne_throttle_time == '600' ? ' selected="selected"' : "" ?>>10</option>
									<option value="900"
										<?php echo $ne_throttle_time == '900' ? ' selected="selected"' : "" ?>>15</option>
									<option value="1800"
										<?php echo $ne_throttle_time == '1800' ? ' selected="selected"' : "" ?>>30</option>
									<option value="3600"
										<?php echo $ne_throttle_time == '3600' ? ' selected="selected"' : "" ?>>60</option>
									<option value="7200"
										<?php echo $ne_throttle_time == '7200' ? ' selected="selected"' : "" ?>>120</option>
									<option value="10800"
										<?php echo $ne_throttle_time == '10800' ? ' selected="selected"' : "" ?>>180</option>
									<option value="14400"
										<?php echo $ne_throttle_time == '14400' ? ' selected="selected"' : "" ?>>240</option>
								</select>
							</div>
						</div>
					</fieldset>
					<fieldset>
						<legend><?php echo $text_smtp_settings; ?></legend>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $entry_use_smtp; ?></label>
							<div class="col-sm-10">
								<label class="radio-inline">
                                        <?php if ($ne_use_smtp) { ?>
                                            <input type="radio"
									name="ne_use_smtp" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                            <input type="radio"
									name="ne_use_smtp" value="1" />
                                            <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label> <label class="radio-inline">
                                        <?php if (!$ne_use_smtp) { ?>
                                            <input type="radio"
									name="ne_use_smtp" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                        <?php } else { ?>
                                            <input type="radio"
									name="ne_use_smtp" value="0" />
                                            <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
							</div>
						</div>
						<div class="form-group smtp">
							<div class="col-sm-12">
								<ul class="nav nav-tabs" id="smtp-stores">
                                        <?php array_unshift($stores, array('store_id' => 0, 'name' => $text_default)); ?>
                                        <?php foreach ($stores as $store) { ?>
                                            <li><a
										href="#smtp-stores<?php echo $store['store_id']; ?>"
										data-toggle="tab"><?php echo $store['name']; ?></a></li>
                                        <?php } ?>
                                    </ul>
								<div class="tab-content">
                                        <?php foreach ($stores as $store) { ?>
                                            <div class="tab-pane"
										id="smtp-stores<?php echo $store['store_id']; ?>">
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-smtp-protocol"><span data-toggle="tooltip"
												title="<?php echo $help_mail_protocol; ?>"><?php echo $entry_mail_protocol; ?></span></label>
											<div class="col-sm-10">
												<select
													name="ne_smtp[<?php echo $store['store_id']; ?>][protocol]"
													id="input-smtp-protocol" class="form-control">
                                                            <?php if (isset($ne_smtp[$store['store_id']]['protocol']) && $ne_smtp[$store['store_id']]['protocol'] == 'mail') { ?>
                                                                <option
														value="mail" selected="selected"><?php echo $text_mail; ?></option>
                                                            <?php } else { ?>
                                                                <option
														value="mail"><?php echo $text_mail; ?></option>
                                                            <?php } ?>
                                                            <?php if (isset($ne_smtp[$store['store_id']]['protocol']) && $ne_smtp[$store['store_id']]['protocol'] == 'smtp') { ?>
                                                                <option
														value="smtp" selected="selected"><?php echo $text_smtp; ?></option>
                                                            <?php } else { ?>
                                                                <option
														value="smtp"><?php echo $text_smtp; ?></option>
                                                            <?php } ?>
                                                            <?php if (isset($ne_smtp[$store['store_id']]['protocol']) && $ne_smtp[$store['store_id']]['protocol'] == 'mail_phpmailer') { ?>
                                                                <option
														value="mail_phpmailer" selected="selected"><?php echo $text_mail_phpmailer; ?></option>
                                                            <?php } else { ?>
                                                                <option
														value="mail_phpmailer"><?php echo $text_mail_phpmailer; ?></option>
                                                            <?php } ?>
                                                            <?php if (isset($ne_smtp[$store['store_id']]['protocol']) && $ne_smtp[$store['store_id']]['protocol'] == 'smtp_phpmailer') { ?>
                                                                <option
														value="smtp_phpmailer" selected="selected"><?php echo $text_smtp_phpmailer; ?></option>
                                                            <?php } else { ?>
                                                                <option
														value="smtp_phpmailer"><?php echo $text_smtp_phpmailer; ?></option>
                                                            <?php } ?>
                                                            <?php if (isset($ne_smtp[$store['store_id']]['protocol']) && $ne_smtp[$store['store_id']]['protocol'] == 'mailgun') { ?>
                                                                <option
														value="mailgun" selected="selected"><?php echo $text_mailgun; ?></option>
                                                            <?php } else { ?>
                                                                <option
														value="mailgun"><?php echo $text_mailgun; ?></option>
                                                            <?php } ?>
                                                        </select>
											</div>
										</div>
										<div
											class="form-group mailgun-<?php echo $store['store_id']; ?>">
											<div class="col-sm-10 col-sm-offset-2">
												<p class="form-control-static"><?php echo $text_mailgun_info; ?></p>
											</div>
										</div>
										<div
											class="form-group mail-<?php echo $store['store_id']; ?> mailgun-<?php echo $store['store_id']; ?>">
											<label class="col-sm-2 control-label"
												for="input-smtp-email-<?php echo $store['store_id']; ?>"><?php echo $entry_email; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_smtp[<?php echo $store['store_id']; ?>][email]"
													value="<?php echo isset($ne_smtp[$store['store_id']]['email']) ? $ne_smtp[$store['store_id']]['email'] : ''; ?>"
													placeholder="<?php echo $entry_email; ?>"
													id="input-smtp-email-<?php echo $store['store_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group mail-<?php echo $store['store_id']; ?>">
											<label class="col-sm-2 control-label"
												for="input-smtp-parameter-<?php echo $store['store_id']; ?>"><span
												data-toggle="tooltip"
												title="<?php echo $help_mail_parameter; ?>"><?php echo $entry_mail_parameter; ?></span></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_smtp[<?php echo $store['store_id']; ?>][parameter]"
													value="<?php echo isset($ne_smtp[$store['store_id']]['parameter']) ? $ne_smtp[$store['store_id']]['parameter'] : ''; ?>"
													placeholder="<?php echo $entry_mail_parameter; ?>"
													id="input-smtp-parameter-<?php echo $store['store_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group mail-<?php echo $store['store_id']; ?>">
											<label class="col-sm-2 control-label"
												for="input-smtp-host-<?php echo $store['store_id']; ?>"><?php echo $entry_smtp_host; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_smtp[<?php echo $store['store_id']; ?>][host]"
													value="<?php echo isset($ne_smtp[$store['store_id']]['host']) ? $ne_smtp[$store['store_id']]['host'] : ''; ?>"
													placeholder="<?php echo $entry_smtp_host; ?>"
													id="input-smtp-host-<?php echo $store['store_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div
											class="form-group mail-<?php echo $store['store_id']; ?> mailgun-<?php echo $store['store_id']; ?>">
											<label class="col-sm-2 control-label"
												for="input-smtp-username-<?php echo $store['store_id']; ?>"><?php echo $entry_smtp_username; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_smtp[<?php echo $store['store_id']; ?>][username]"
													value="<?php echo isset($ne_smtp[$store['store_id']]['username']) ? $ne_smtp[$store['store_id']]['username'] : ''; ?>"
													placeholder="<?php echo $entry_smtp_username; ?>"
													id="input-smtp-username-<?php echo $store['store_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div
											class="form-group mail-<?php echo $store['store_id']; ?> mailgun-<?php echo $store['store_id']; ?>">
											<label class="col-sm-2 control-label"
												for="input-smtp-password-<?php echo $store['store_id']; ?>"><?php echo $entry_smtp_password; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_smtp[<?php echo $store['store_id']; ?>][password]"
													value="<?php echo isset($ne_smtp[$store['store_id']]['password']) ? $ne_smtp[$store['store_id']]['password'] : ''; ?>"
													placeholder="<?php echo $entry_smtp_password; ?>"
													id="input-smtp-password-<?php echo $store['store_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group mail-<?php echo $store['store_id']; ?>">
											<label class="col-sm-2 control-label"
												for="input-smtp-port-<?php echo $store['store_id']; ?>"><?php echo $entry_smtp_port; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_smtp[<?php echo $store['store_id']; ?>][port]"
													value="<?php echo isset($ne_smtp[$store['store_id']]['port']) ? $ne_smtp[$store['store_id']]['port'] : ''; ?>"
													placeholder="<?php echo $entry_smtp_port; ?>"
													id="input-smtp-port-<?php echo $store['store_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group mail-<?php echo $store['store_id']; ?>">
											<label class="col-sm-2 control-label"
												for="input-smtp-timeout-<?php echo $store['store_id']; ?>"><?php echo $entry_smtp_timeout; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_smtp[<?php echo $store['store_id']; ?>][timeout]"
													value="<?php echo isset($ne_smtp[$store['store_id']]['timeout']) ? $ne_smtp[$store['store_id']]['timeout'] : ''; ?>"
													placeholder="<?php echo $entry_smtp_timeout; ?>"
													id="input-smtp-timeout-<?php echo $store['store_id']; ?>"
													class="form-control" />
											</div>
										</div>
									</div>
                                        <?php } ?>
                                    </div>
							</div>
						</div>
					</fieldset>
					<fieldset>
						<legend><?php echo $text_general_settings; ?></legend>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-sent-retries"><span
								data-toggle="tooltip" title="<?php echo $help_sent_retries; ?>"><?php echo $entry_sent_retries; ?></span></label>
							<div class="col-sm-10">
								<input type="text" name="ne_sent_retries"
									value="<?php echo $ne_sent_retries; ?>"
									placeholder="<?php echo $entry_sent_retries; ?>"
									id="input-sent-retries" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $entry_cron_code; ?></label>
							<div class="col-sm-10">
								<pre><?php echo $cron_url; ?></pre>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $entry_cron_help; ?></label>
							<div class="col-sm-10">
								<pre><?php echo $text_help; ?></pre>
							</div>
						</div>
					</fieldset>
					<fieldset>
						<legend><?php echo $entry_list; ?></legend>
						<div class="form-group">
							<div class="col-sm-12">
								<ul class="nav nav-tabs" id="list-stores">
                                        <?php foreach ($stores as $store) { ?>
                                            <li><a
										href="#list-stores<?php echo $store['store_id']; ?>"
										data-toggle="tab"><?php echo $store['name']; ?></a></li>
                                        <?php } ?>
                                    </ul>
								<div class="tab-content">
                                        <?php $list_row = array(); ?>
                                        <?php foreach ($stores as $store) { ?>
                                            <div class="tab-pane"
										id="list-stores<?php echo $store['store_id']; ?>">
										<table class="table table-bordered table-hover"
											id="list<?php echo $store['store_id']; ?>">
											<thead>
												<tr>
													<td class="text-left"><?php echo $entry_name; ?></td>
													<td style="width: 1px;"></td>
												</tr>
											</thead>
                                                    <?php $list_row[$store['store_id']] = 1; ?>
                                                    <?php if (isset($list_data[$store['store_id']]) && $list_data[$store['store_id']]) foreach ($list_data[$store['store_id']] as $list) { ?>
                                                        <tbody
												id="list-row-<?php echo $store['store_id']; ?>-<?php echo $list_row[$store['store_id']]; ?>">
												<tr>
													<td class="text-left"><br />
                                                                    <?php foreach ($languages as $language) { ?>
                                                                        <div
															class="input-group">
															<span class="input-group-addon"><img
																src="view/image/flags/<?php echo $language['image']; ?>"
																title="<?php echo $language['name']; ?>"
																alt="<?php echo $language['name']; ?>" /></span> <input
																type="text"
																name="ne_marketing_list[<?php echo $store['store_id']; ?>][<?php echo $list_row[$store['store_id']]; ?>][<?php echo $language['language_id']; ?>]"
																value="<?php echo isset($list[$language['language_id']]) ? $list[$language['language_id']] : ''; ?>"
																class="form-control" />
														</div> <br />
                                                                    <?php } ?>
                                                                </td>
													<td class="text-left">
														<button type="button"
															onclick="$('#list-row-<?php echo $store['store_id']; ?>-<?php echo $list_row[$store['store_id']]; ?>').remove();"
															data-toggle="tooltip"
															title="<?php echo $button_remove; ?>"
															class="btn btn-danger">
															<i class="fa fa-minus-circle"></i>
														</button>
													</td>
												</tr>
											</tbody>
                                                        <?php $list_row[$store['store_id']] = $list_row[$store['store_id']] + 1; ?>
                                                    <?php } ?>
                                                    <tfoot>
												<tr>
													<td></td>
													<td class="text-left">
														<button type="button"
															onclick="addList(<?php echo $store['store_id']; ?>);"
															data-toggle="tooltip"
															title="<?php echo $button_add_list; ?>"
															class="btn btn-primary">
															<i class="fa fa-plus-circle"></i>
														</button>
													</td>
												</tr>
											</tfoot>
										</table>
									</div>
                                        <?php } ?>
                                    </div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $entry_hide_marketing; ?></label>
							<div class="col-sm-10">
								<label class="radio-inline">
                                        <?php if ($ne_hide_marketing) { ?>
                                            <input type="radio"
									name="ne_hide_marketing" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                            <input type="radio"
									name="ne_hide_marketing" value="1" />
                                            <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label> <label class="radio-inline">
                                        <?php if (!$ne_hide_marketing) { ?>
                                            <input type="radio"
									name="ne_hide_marketing" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                        <?php } else { ?>
                                            <input type="radio"
									name="ne_hide_marketing" value="0" />
                                            <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
							</div>
						</div>
					</fieldset>
					<fieldset>
						<legend><?php echo $entry_subscribe_confirmation; ?></legend>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $entry_subscribe_confirmation; ?></label>
							<div class="col-sm-10">
								<label class="radio-inline">
                                        <?php if ($ne_subscribe_confirmation) { ?>
                                            <input type="radio"
									name="ne_subscribe_confirmation" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                            <input type="radio"
									name="ne_subscribe_confirmation" value="1" />
                                            <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label> <label class="radio-inline">
                                        <?php if (!$ne_subscribe_confirmation) { ?>
                                            <input type="radio"
									name="ne_subscribe_confirmation" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                        <?php } else { ?>
                                            <input type="radio"
									name="ne_subscribe_confirmation" value="0" />
                                            <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
							</div>
						</div>
						<div class="form-group subscribe-confirmation">
							<div class="col-sm-12">
								<ul class="nav nav-tabs" id="subscribe-confirmation-stores">
                                        <?php foreach ($stores as $store) { ?>
                                            <li><a
										href="#subscribe-confirmation-stores<?php echo $store['store_id']; ?>"
										data-toggle="tab"><?php echo $store['name']; ?></a></li>
                                        <?php } ?>
                                    </ul>
								<div class="tab-content">
                                        <?php foreach ($stores as $store) { ?>
                                            <div class="tab-pane"
										id="subscribe-confirmation-stores<?php echo $store['store_id']; ?>">
										<ul class="nav nav-tabs"
											id="languages-confirmation-stores<?php echo $store['store_id']; ?>">
                                                    <?php foreach ($languages as $language) { ?>
                                                        <li><a
												href="#languages-confirmation-stores<?php echo $store['store_id']; ?>-<?php echo $language['language_id']; ?>"
												data-toggle="tab"><img
													src="view/image/flags/<?php echo $language['image']; ?>"
													title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                                                    <?php } ?>
                                                </ul>
										<div class="tab-content">
                                                    <?php foreach ($languages as $language) { ?>
                                                        <div
												class="tab-pane"
												id="languages-confirmation-stores<?php echo $store['store_id']; ?>-<?php echo $language['language_id']; ?>">
												<div class="form-group">
													<label class="col-sm-2 control-label"
														for="input-subject-<?php echo $store['store_id']; ?>-<?php echo $language['language_id']; ?>"><?php echo $entry_subject; ?></label>
													<div class="col-sm-10">
														<input type="text"
															name="ne_subscribe_confirmation_subject[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]"
															value="<?php echo isset($ne_subscribe_confirmation_subject[$store['store_id']][$language['language_id']]) ? $ne_subscribe_confirmation_subject[$store['store_id']][$language['language_id']] : ''; ?>"
															placeholder="<?php echo $entry_subject; ?>"
															id="input-subject-<?php echo $store['store_id']; ?>-<?php echo $language['language_id']; ?>"
															class="form-control" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label"
														for="input-message-<?php echo $store['store_id']; ?>-<?php echo $language['language_id']; ?>"><?php echo $entry_message; ?></label>
													<div class="col-sm-10">
														<textarea
															name="ne_subscribe_confirmation_message[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]"
															placeholder="<?php echo $entry_message; ?>"
															id="input-message-<?php echo $store['store_id']; ?>-<?php echo $language['language_id']; ?>"
															class="form-control"><?php echo isset($ne_subscribe_confirmation_message[$store['store_id']][$language['language_id']]) ? $ne_subscribe_confirmation_message[$store['store_id']][$language['language_id']] : ''; ?></textarea>
													</div>
												</div>
												<div class="form-group">
													<div class="col-sm-10 col-sm-offset-2">
                                                                    <?php echo $text_personalisation_tags; ?>
                                                                </div>
												</div>
											</div>
                                                    <?php } ?>
                                                </div>
									</div>
                                        <?php } ?>
                                    </div>
							</div>
						</div>
					</fieldset>
					<fieldset>
						<legend><?php echo $text_module_localization; ?> <?php echo $entry_months; ?></legend>
						<div class="form-group">
							<div class="col-sm-12">
								<ul class="nav nav-tabs" id="languages-months">
                                        <?php foreach ($languages as $language) { ?>
                                            <li><a
										href="#language-months<?php echo $language['language_id']; ?>"
										data-toggle="tab"><img
											src="view/image/flags/<?php echo $language['image']; ?>"
											title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                                        <?php } ?>
                                    </ul>
								<div class="tab-content">
                                        <?php foreach ($languages as $language) { ?>
                                            <div class="tab-pane"
										id="language-months<?php echo $language['language_id']; ?>">
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-january-<?php echo $language['language_id']; ?>"><?php echo $entry_january; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_months[<?php echo $language['language_id']; ?>][0]"
													value="<?php echo isset($ne_months[$language['language_id']][0]) ? $ne_months[$language['language_id']][0] : $entry_january; ?>"
													placeholder="<?php echo $entry_january; ?>"
													id="input-january-<?php echo $language['language_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-february-<?php echo $language['language_id']; ?>"><?php echo $entry_february; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_months[<?php echo $language['language_id']; ?>][1]"
													value="<?php echo isset($ne_months[$language['language_id']][1]) ? $ne_months[$language['language_id']][1] : $entry_february; ?>"
													placeholder="<?php echo $entry_february; ?>"
													id="input-february-<?php echo $language['language_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-march-<?php echo $language['language_id']; ?>"><?php echo $entry_march; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_months[<?php echo $language['language_id']; ?>][2]"
													value="<?php echo isset($ne_months[$language['language_id']][2]) ? $ne_months[$language['language_id']][2] : $entry_march; ?>"
													placeholder="<?php echo $entry_march; ?>"
													id="input-march-<?php echo $language['language_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-april-<?php echo $language['language_id']; ?>"><?php echo $entry_april; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_months[<?php echo $language['language_id']; ?>][3]"
													value="<?php echo isset($ne_months[$language['language_id']][3]) ? $ne_months[$language['language_id']][3] : $entry_april; ?>"
													placeholder="<?php echo $entry_april; ?>"
													id="input-april-<?php echo $language['language_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-may-<?php echo $language['language_id']; ?>"><?php echo $entry_may; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_months[<?php echo $language['language_id']; ?>][4]"
													value="<?php echo isset($ne_months[$language['language_id']][4]) ? $ne_months[$language['language_id']][4] : $entry_may; ?>"
													placeholder="<?php echo $entry_may; ?>"
													id="input-may-<?php echo $language['language_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-june-<?php echo $language['language_id']; ?>"><?php echo $entry_june; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_months[<?php echo $language['language_id']; ?>][5]"
													value="<?php echo isset($ne_months[$language['language_id']][5]) ? $ne_months[$language['language_id']][5] : $entry_june; ?>"
													placeholder="<?php echo $entry_june; ?>"
													id="input-june-<?php echo $language['language_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-july-<?php echo $language['language_id']; ?>"><?php echo $entry_july; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_months[<?php echo $language['language_id']; ?>][6]"
													value="<?php echo isset($ne_months[$language['language_id']][6]) ? $ne_months[$language['language_id']][6] : $entry_july; ?>"
													placeholder="<?php echo $entry_july; ?>"
													id="input-july-<?php echo $language['language_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-august-<?php echo $language['language_id']; ?>"><?php echo $entry_august; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_months[<?php echo $language['language_id']; ?>][7]"
													value="<?php echo isset($ne_months[$language['language_id']][7]) ? $ne_months[$language['language_id']][7] : $entry_august; ?>"
													placeholder="<?php echo $entry_august; ?>"
													id="input-august-<?php echo $language['language_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-september-<?php echo $language['language_id']; ?>"><?php echo $entry_september; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_months[<?php echo $language['language_id']; ?>][8]"
													value="<?php echo isset($ne_months[$language['language_id']][8]) ? $ne_months[$language['language_id']][8] : $entry_september; ?>"
													placeholder="<?php echo $entry_september; ?>"
													id="input-september-<?php echo $language['language_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-october-<?php echo $language['language_id']; ?>"><?php echo $entry_october; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_months[<?php echo $language['language_id']; ?>][9]"
													value="<?php echo isset($ne_months[$language['language_id']][9]) ? $ne_months[$language['language_id']][9] : $entry_october; ?>"
													placeholder="<?php echo $entry_october; ?>"
													id="input-october-<?php echo $language['language_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-november-<?php echo $language['language_id']; ?>"><?php echo $entry_november; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_months[<?php echo $language['language_id']; ?>][10]"
													value="<?php echo isset($ne_months[$language['language_id']][10]) ? $ne_months[$language['language_id']][10] : $entry_november; ?>"
													placeholder="<?php echo $entry_november; ?>"
													id="input-november-<?php echo $language['language_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-december-<?php echo $language['language_id']; ?>"><?php echo $entry_december; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_months[<?php echo $language['language_id']; ?>][11]"
													value="<?php echo isset($ne_months[$language['language_id']][11]) ? $ne_months[$language['language_id']][11] : $entry_december; ?>"
													placeholder="<?php echo $entry_december; ?>"
													id="input-december-<?php echo $language['language_id']; ?>"
													class="form-control" />
											</div>
										</div>
									</div>
                                        <?php } ?>
                                    </div>
							</div>
						</div>
					</fieldset>
					<fieldset>
						<legend><?php echo $text_module_localization; ?> <?php echo $entry_weekdays; ?></legend>
						<div class="form-group">
							<div class="col-sm-12">
								<ul class="nav nav-tabs" id="languages-weekdays">
                                        <?php foreach ($languages as $language) { ?>
                                            <li><a
										href="#language-weekdays<?php echo $language['language_id']; ?>"
										data-toggle="tab"><img
											src="view/image/flags/<?php echo $language['image']; ?>"
											title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                                        <?php } ?>
                                    </ul>
								<div class="tab-content">
                                        <?php foreach ($languages as $language) { ?>
                                            <div class="tab-pane"
										id="language-weekdays<?php echo $language['language_id']; ?>">
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-sunday-<?php echo $language['language_id']; ?>"><?php echo $entry_sunday; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_weekdays[<?php echo $language['language_id']; ?>][0]"
													value="<?php echo isset($ne_weekdays[$language['language_id']][0]) ? $ne_weekdays[$language['language_id']][0] : $entry_sunday; ?>"
													placeholder="<?php echo $entry_sunday; ?>"
													id="input-sunday-<?php echo $language['language_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-monday-<?php echo $language['language_id']; ?>"><?php echo $entry_monday; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_weekdays[<?php echo $language['language_id']; ?>][1]"
													value="<?php echo isset($ne_weekdays[$language['language_id']][1]) ? $ne_weekdays[$language['language_id']][1] : $entry_monday; ?>"
													placeholder="<?php echo $entry_monday; ?>"
													id="input-monday-<?php echo $language['language_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-tuesday-<?php echo $language['language_id']; ?>"><?php echo $entry_tuesday; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_weekdays[<?php echo $language['language_id']; ?>][2]"
													value="<?php echo isset($ne_weekdays[$language['language_id']][2]) ? $ne_weekdays[$language['language_id']][2] : $entry_tuesday; ?>"
													placeholder="<?php echo $entry_tuesday; ?>"
													id="input-tuesday-<?php echo $language['language_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-wednesday-<?php echo $language['language_id']; ?>"><?php echo $entry_wednesday; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_weekdays[<?php echo $language['language_id']; ?>][3]"
													value="<?php echo isset($ne_weekdays[$language['language_id']][3]) ? $ne_weekdays[$language['language_id']][3] : $entry_wednesday; ?>"
													placeholder="<?php echo $entry_wednesday; ?>"
													id="input-wednesday-<?php echo $language['language_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-thursday-<?php echo $language['language_id']; ?>"><?php echo $entry_thursday; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_weekdays[<?php echo $language['language_id']; ?>][4]"
													value="<?php echo isset($ne_weekdays[$language['language_id']][4]) ? $ne_weekdays[$language['language_id']][4] : $entry_thursday; ?>"
													placeholder="<?php echo $entry_thursday; ?>"
													id="input-thursday-<?php echo $language['language_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-friday-<?php echo $language['language_id']; ?>"><?php echo $entry_friday; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_weekdays[<?php echo $language['language_id']; ?>][5]"
													value="<?php echo isset($ne_weekdays[$language['language_id']][5]) ? $ne_weekdays[$language['language_id']][5] : $entry_friday; ?>"
													placeholder="<?php echo $entry_friday; ?>"
													id="input-friday-<?php echo $language['language_id']; ?>"
													class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"
												for="input-saturday-<?php echo $language['language_id']; ?>"><?php echo $entry_saturday; ?></label>
											<div class="col-sm-10">
												<input type="text"
													name="ne_weekdays[<?php echo $language['language_id']; ?>][6]"
													value="<?php echo isset($ne_weekdays[$language['language_id']][6]) ? $ne_weekdays[$language['language_id']][6] : $entry_saturday; ?>"
													placeholder="<?php echo $entry_saturday; ?>"
													id="input-saturday-<?php echo $language['language_id']; ?>"
													class="form-control" />
											</div>
										</div>
									</div>
                                        <?php } ?>
                                    </div>
							</div>
						</div>
					</fieldset>
                    <?php } ?>
                </form>
			</div>
		</div>
		<p class="text-center small">Newsletter Enhancements OpenCart Module
			v3.7.2</p>
	</div>
    <?php if (!isset($licensor)) { ?>
        <script type="text/javascript"><!--
            <?php foreach ($stores as $store) { ?>
                $("select[name='ne_smtp[<?php echo $store['store_id']; ?>][protocol]']").bind('change', function() {
                    if ($(this).val() == 'mailgun') {
                        $(".mail-<?php echo $store['store_id']; ?>").hide();
                        $(".mailgun-<?php echo $store['store_id']; ?>").show();
                    } else {
                        $(".mailgun-<?php echo $store['store_id']; ?>").hide();
                        $(".mail-<?php echo $store['store_id']; ?>").show();
                    }
                });
                $("select[name='ne_smtp[<?php echo $store['store_id']; ?>][protocol]']").trigger('change');

                <?php foreach ($languages as $language) { ?>
                    $('#input-message-<?php echo $store['store_id']; ?>-<?php echo $language['language_id']; ?>').summernote({
                        height: 300
                    });
                <?php } ?>
            <?php } ?>
        //--></script>
	<script type="text/javascript"><!--
            var list_row = [<?php echo implode(',', $list_row); ?>];

            function addList(store_id) {
                var html = '<tbody id="list-row-' + store_id + '-' + list_row[store_id] + '">';
                html += '<tr>';
                html += '<td class="text-left"><br/>';
                <?php foreach ($languages as $language) { ?>
                    html += '<div class="input-group">';
                    html += '<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" alt="<?php echo $language['name']; ?>" /></span>';
                    html += '<input type="text" name="ne_marketing_list[' + store_id + '][' + list_row[store_id] + '][<?php echo $language['language_id']; ?>]" value="" class="form-control" />';
                    html += '</div><br/>';
                <?php } ?>
                html += '</td>';
                html += '<td class="text-left"><button type="button" onclick="$(\'#list-row-' + store_id + '-' + list_row[store_id] + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
                html += '</tr>';
                html += '</tbody>';

                $('#list' + store_id + ' tfoot').before(html);
                list_row[store_id] = list_row[store_id] + 1;
            }

            $('input[name=\'ne_throttle\']').bind('click', function() {
                checkThrottle();
            });

            $('input[name=\'ne_use_smtp\']').bind('click', function() {
                checkSmtp();
            });

            $('input[name=\'ne_subscribe_confirmation\']').bind('click', function() {
                checkSubscribeConfirmation();
            });

            checkThrottle();
            checkSmtp();
            checkSubscribeConfirmation();

            function checkThrottle() {
                if ($('input[name=\'ne_throttle\']:checked').val() == 1) {
                    $('.throttle').show();
                } else {
                    $('.throttle').hide();
                }
            }

            function checkSmtp() {
                if ($('input[name=\'ne_use_smtp\']:checked').val() == 1) {
                    $('.smtp').show();
                } else {
                    $('.smtp').hide();
                }
            }

            function checkSubscribeConfirmation() {
                if ($('input[name=\'ne_subscribe_confirmation\']:checked').val() == 1) {
                    $('.subscribe-confirmation').show();
                } else {
                    $('.subscribe-confirmation').hide();
                }
            }
        //--></script>
	<script type="text/javascript"><!--
            $('#smtp-stores a:first').tab('show');
            $('#list-stores a:first').tab('show');
            $('#subscribe-confirmation-stores a:first').tab('show');
            <?php foreach ($stores as $store) { ?>
                $('#languages-confirmation-stores<?php echo $store["store_id"]; ?> a:first').tab('show');
            <?php } ?>
            $('#languages-months a:first').tab('show');
            $('#languages-weekdays a:first').tab('show');
        //--></script>
    <?php } ?>
</div>
<?php echo $footer; ?>