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
				<a href="<?php echo $insert; ?>" data-toggle="tooltip"
					title="<?php echo $button_insert; ?>" class="btn btn-primary"><i
					class="fa fa-plus"></i></a>
				<button type="submit" form="form" data-toggle="tooltip"
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
					<i class="fa fa-calendar"></i> <?php echo $text_newsletters_schedule; ?></h3>
			</div>
			<div class="panel-body">
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
								<label class="control-label" for="input-to"><?php echo $column_to; ?></label>
								<select name="filter_to" id="input-to" class="form-control">
                                    <?php if ($filter_to == '') { ?>
                                        <option value=""
										selected="selected"></option>
                                    <?php } else { ?>
                                        <option value=""></option>
                                    <?php } ?>
                                    <?php if ($filter_to == 'newsletter') { ?>
                                        <option value="newsletter"
										selected="selected"><?php echo $text_newsletter; ?></option>
                                    <?php } else { ?>
                                        <option value="newsletter"><?php echo $text_newsletter; ?></option>
                                    <?php } ?>
                                    <?php if ($filter_to == 'customer_all') { ?>
                                        <option value="customer_all"
										selected="selected"><?php echo $text_customer_all; ?></option>
                                    <?php } else { ?>
                                        <option value="customer_all"><?php echo $text_customer_all; ?></option>
                                    <?php } ?>
                                    <?php if ($filter_to == 'customer_group') { ?>
                                        <option value="customer_group"
										selected="selected"><?php echo $text_customer_group; ?></option>
                                    <?php } else { ?>
                                        <option value="customer_group"><?php echo $text_customer_group; ?></option>
                                    <?php } ?>
                                    <?php if ($filter_to == 'customer') { ?>
                                        <option value="customer"
										selected="selected"><?php echo $text_customer; ?></option>
                                    <?php } else { ?>
                                        <option value="customer"><?php echo $text_customer; ?></option>
                                    <?php } ?>
                                    <?php if ($filter_to == 'affiliate_all') { ?>
                                        <option value="affiliate_all"
										selected="selected"><?php echo $text_affiliate_all; ?></option>
                                    <?php } else { ?>
                                        <option value="affiliate_all"><?php echo $text_affiliate_all; ?></option>
                                    <?php } ?>
                                    <?php if ($filter_to == 'affiliate') { ?>
                                        <option value="affiliate"
										selected="selected"><?php echo $text_affiliate; ?></option>
                                    <?php } else { ?>
                                        <option value="affiliate"><?php echo $text_affiliate; ?></option>
                                    <?php } ?>
                                    <?php if ($filter_to == 'product') { ?>
                                        <option value="product"
										selected="selected"><?php echo $text_product; ?></option>
                                    <?php } else { ?>
                                        <option value="product"><?php echo $text_product; ?></option>
                                    <?php } ?>
                                    <?php if ($filter_to == 'marketing') { ?>
                                        <option value="marketing"
										selected="selected"><?php echo $text_marketing; ?></option>
                                    <?php } else { ?>
                                        <option value="marketing"><?php echo $text_marketing; ?></option>
                                    <?php } ?>
                                    <?php if ($filter_to == 'marketing_all') { ?>
                                        <option value="marketing_all"
										selected="selected"><?php echo $text_marketing_all; ?></option>
                                    <?php } else { ?>
                                        <option value="marketing_all"><?php echo $text_marketing_all; ?></option>
                                    <?php } ?>
                                    <?php if ($filter_to == 'subscriber') { ?>
                                        <option value="subscriber"
										selected="selected"><?php echo $text_subscriber_all; ?></option>
                                    <?php } else { ?>
                                        <option value="subscriber"><?php echo $text_subscriber_all; ?></option>
                                    <?php } ?>
                                    <?php if ($filter_to == 'all') { ?>
                                        <option value="all"
										selected="selected"><?php echo $text_all; ?></option>
                                    <?php } else { ?>
                                        <option value="all"><?php echo $text_all; ?></option>
                                    <?php } ?>
                                </select>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-active"><?php echo $column_active; ?></label>
								<select name="filter_active" id="input-active"
									class="form-control">
									<option value=""></option>
                                    <?php if ($filter_active == '1') { ?>
                                        <option value="1"
										selected="selected"><?php echo $entry_yes; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $entry_yes; ?></option>
                                    <?php } ?>
                                    <?php if ($filter_active == '0') { ?>
                                        <option value="0"
										selected="selected"><?php echo $entry_no; ?></option>
                                    <?php } else { ?>
                                        <option value="0"><?php echo $entry_no; ?></option>
                                    <?php } ?>
                                </select>
							</div>
							<div class="form-group">
								<label class="control-label" for="input-recurrent"><?php echo $column_recurrent; ?></label>
								<select name="filter_recurrent" id="input-recurrent"
									class="form-control">
									<option value=""></option>
                                    <?php if ($filter_recurrent == '1') { ?>
                                        <option value="1"
										selected="selected"><?php echo $entry_yes; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $entry_yes; ?></option>
                                    <?php } ?>
                                    <?php if ($filter_recurrent == '0') { ?>
                                        <option value="0"
										selected="selected"><?php echo $entry_no; ?></option>
                                    <?php } else { ?>
                                        <option value="0"><?php echo $entry_no; ?></option>
                                    <?php } ?>
                                </select>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-store"><?php echo $column_store; ?></label>
								<select name="filter_store" id="input-store"
									class="form-control">
									<option value=""></option>
                                    <?php if ($filter_store == '0') { ?>
                                        <option value="0"
										selected="selected"><?php echo $text_default; ?></option>
                                    <?php } else { ?>
                                        <option value="0"><?php echo $text_default; ?></option>
                                    <?php } ?>
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
							<button type="button" id="button-filter"
								class="btn btn-primary pull-right">
								<i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
						</div>
					</div>
				</div>
				<form action="<?php echo $delete; ?>" method="post"
					enctype="multipart/form-data" id="form" class="form-horizontal">
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
                                        <?php if ($sort == 'to') { ?>
                                            <a
										href="<?php echo $sort_to; ?>"
										class="<?php echo strtolower($order); ?>"><?php echo $column_to; ?></a>
                                        <?php } else { ?>
                                            <a
										href="<?php echo $sort_to; ?>"><?php echo $column_to; ?></a>
                                        <?php } ?>
                                    </td>
									<td class="text-right">
                                        <?php if ($sort == 'active') { ?>
                                            <a
										href="<?php echo $sort_active; ?>"
										class="<?php echo strtolower($order); ?>"><?php echo $column_active; ?></a>
                                        <?php } else { ?>
                                            <a
										href="<?php echo $sort_active; ?>"><?php echo $column_active; ?></a>
                                        <?php } ?>
                                    </td>
									<td class="text-right">
                                        <?php if ($sort == 'recurrent') { ?>
                                            <a
										href="<?php echo $sort_recurrent; ?>"
										class="<?php echo strtolower($order); ?>"><?php echo $column_recurrent; ?></a>
                                        <?php } else { ?>
                                            <a
										href="<?php echo $sort_recurrent; ?>"><?php echo $column_recurrent; ?></a>
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
									<td class="text-right"><?php echo $column_actions; ?></td>
								</tr>
							</thead>
							<tbody>
                                <?php if ($schedule) { ?>
                                    <?php foreach ($schedule as $entry) { ?>
                                        <tr>
									<td class="text-center">
                                                <?php if (in_array($entry['schedule_id'], $selected)) { ?>
                                                    <input
										type="checkbox" name="selected[]"
										value="<?php echo $entry['schedule_id']; ?>" checked="checked" />
                                                <?php } else { ?>
                                                    <input
										type="checkbox" name="selected[]"
										value="<?php echo $entry['schedule_id']; ?>" />
                                                <?php } ?>
                                            </td>
									<td class="text-left"><?php echo $entry['name']; ?></td>
									<td class="text-left">
                                                <?php
																																		
if ($entry ['to'] == 'newsletter') {
																																			echo $text_newsletter;
																																		} elseif ($entry ['to'] == 'customer_all') {
																																			echo $text_customer_all;
																																		} elseif ($entry ['to'] == 'customer_group') {
																																			echo $text_customer_group;
																																		} elseif ($entry ['to'] == 'customer') {
																																			echo $text_customer;
																																		} elseif ($entry ['to'] == 'affiliate_all') {
																																			echo $text_affiliate_all;
																																		} elseif ($entry ['to'] == 'affiliate') {
																																			echo $text_affiliate;
																																		} elseif ($entry ['to'] == 'product') {
																																			echo $text_product;
																																		} elseif ($entry ['to'] == 'marketing') {
																																			echo $text_marketing;
																																		} elseif ($entry ['to'] == 'marketing_all') {
																																			echo $text_marketing_all;
																																		} elseif ($entry ['to'] == 'subscriber') {
																																			echo $text_subscriber_all;
																																		} elseif ($entry ['to'] == 'all') {
																																			echo $text_all;
																																		}
																																		?>
                                            </td>
									<td class="text-right">
                                                <?php if ($entry['active'] == '1') { ?>
                                                    <?php echo $entry_yes; ?>
                                                <?php } else { ?>
                                                    <?php echo $entry_no; ?>
                                                <?php } ?>
                                            </td>
									<td class="text-right">
                                                <?php if ($entry['recurrent'] == '1') { ?>
                                                    <?php echo $entry_yes; ?>
                                                <?php } else { ?>
                                                    <?php echo $entry_no; ?>
                                                <?php } ?>
                                            </td>
									<td class="text-right">
                                                <?php if ($entry['store_id'] == '0') { ?>
                                                    <?php echo $text_default; ?>
                                                <?php } else { ?>
                                                    <?php foreach ($stores as $store) { ?>
                                                        <?php if ($entry['store_id'] == $store['store_id']) { ?>
                                                            <?php echo $store['name']; ?>
                                                            <?php break; ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            </td>
									<td class="text-right"><a
										href="<?php echo $update . $entry['schedule_id']; ?>"
										data-toggle="tooltip" title="<?php echo $button_edit; ?>"
										class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
								</tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
									<td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
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
			</div>
		</div>
		<p class="text-center small">Newsletter Enhancements OpenCart Module
			v3.7.2</p>
	</div>
	<script type="text/javascript"><!--
        $('#button-filter').on('click', function() {
            var url = 'index.php?route=ne/schedule&token=<?php echo $token; ?>';

            var filter_name = $('input[name=\'filter_name\']').val();

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }

            var filter_active = $('input[name=\'filter_active\']').val();

            if (filter_active) {
                url += '&filter_active=' + encodeURIComponent(filter_active);
            }

            var filter_recurrent = $('input[name=\'filter_recurrent\']').val();

            if (filter_recurrent) {
                url += '&filter_recurrent=' + encodeURIComponent(filter_recurrent);
            }

            var filter_to = $('select[name=\'filter_to\']').val();

            if (filter_to) {
                url += '&filter_to=' + encodeURIComponent(filter_to);
            }

            var filter_store = $('select[name=\'filter_store\']').val();

            if (filter_store) {
                url += '&filter_store=' + encodeURIComponent(filter_store);
            }

            location = url;
        });
    //--></script>
</div>
<?php echo $footer; ?>