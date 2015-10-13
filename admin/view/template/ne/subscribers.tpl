<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?></h1>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <?php if ($success) { ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-users"></i> <?php echo $text_subscribers; ?></h3>
            </div>
            <div class="panel-body">
                <div class="well">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-name"><?php echo $column_name; ?></label>
                                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $column_name; ?>" id="input-name" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-email"><?php echo $column_email; ?></label>
                                <input type="text" name="filter_email" value="<?php echo $filter_email; ?>" placeholder="<?php echo $column_email; ?>" id="input-email" class="form-control" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-customer-group"><?php echo $column_customer_group; ?></label>
                                <select name="filter_customer_group_id" id="input-customer-group" class="form-control">
                                    <option value=""></option>
                                    <?php foreach ($customer_groups as $customer_group) { ?>
                                        <?php if ($customer_group['customer_group_id'] == $filter_customer_group_id) { ?>
                                            <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-newsletter"><?php echo $column_newsletter; ?></label>
                                <select name="filter_newsletter" id="input-newsletter" class="form-control">
                                    <option value=""></option>
                                    <?php if ($filter_newsletter == '1') { ?>
                                        <option value="1" selected="selected"><?php echo $entry_yes; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $entry_yes; ?></option>
                                    <?php } ?>
                                    <?php if ($filter_newsletter == '0') { ?>
                                        <option value="0" selected="selected"><?php echo $entry_no; ?></option>
                                    <?php } else { ?>
                                        <option value="0"><?php echo $entry_no; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-store"><?php echo $column_store; ?></label>
                                <select name="filter_store" id="input-store" class="form-control">
                                    <option value=""></option>
                                    <?php if ($filter_store == '0') { ?>
                                        <option value="0" selected="selected"><?php echo $text_default; ?></option>
                                    <?php } else { ?>
                                        <option value="0"><?php echo $text_default; ?></option>
                                    <?php } ?>
                                    <?php foreach ($stores as $store) { ?>
                                        <?php if ($filter_store == $store['store_id'] && $filter_store != '') { ?>
                                            <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-language"><?php echo $column_language; ?></label>
                                <select name="filter_language" id="input-language" class="form-control">
                                    <option value="*"></option>
                                    <?php if ($filter_language === '') { ?>
                                        <option value="" selected="selected"><?php echo $text_default; ?></option>
                                    <?php } else { ?>
                                        <option value=""><?php echo $text_default; ?></option>
                                    <?php } ?>
                                    <?php foreach ($languages as $language) { ?>
                                        <?php if ($filter_language == $language['code'] && $filter_language != '') { ?>
                                            <option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <td class="text-left">
                                    <?php if ($sort == 'name') { ?>
                                        <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                                    <?php } else { ?>
                                        <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                    <?php } ?>
                                </td>
                                <td class="text-left">
                                    <?php if ($sort == 'c.email') { ?>
                                        <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
                                    <?php } else { ?>
                                        <a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
                                    <?php } ?>
                                </td>
                                <td class="text-right">
                                    <?php if ($sort == 'customer_group') { ?>
                                        <a href="<?php echo $sort_customer_group; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer_group; ?></a>
                                    <?php } else { ?>
                                        <a href="<?php echo $sort_customer_group; ?>"><?php echo $column_customer_group; ?></a>
                                    <?php } ?>
                                </td>
                                <td class="text-right">
                                    <?php if ($sort == 'c.newsletter') { ?>
                                        <a href="<?php echo $sort_newsletter; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_newsletter; ?></a>
                                    <?php } else { ?>
                                        <a href="<?php echo $sort_newsletter; ?>"><?php echo $column_newsletter; ?></a>
                                    <?php } ?>
                                </td>
                                <td class="text-right">
                                    <?php if ($sort == 'store_id') { ?>
                                        <a href="<?php echo $sort_store; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_store; ?></a>
                                    <?php } else { ?>
                                        <a href="<?php echo $sort_store; ?>"><?php echo $column_store; ?></a>
                                    <?php } ?>
                                </td>
                                <td class="text-right">
                                    <?php if ($sort == 'language_code') { ?>
                                        <a href="<?php echo $sort_language; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_language; ?></a>
                                    <?php } else { ?>
                                        <a href="<?php echo $sort_language; ?>"><?php echo $column_language; ?></a>
                                    <?php } ?>
                                </td>
                                <td class="text-right"><?php echo $column_actions; ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($customers) { ?>
                                <?php foreach ($customers as $customer) { ?>
                                    <tr>
                                        <td class="text-left"><?php echo $customer['name']; ?></td>
                                        <td class="text-left"><?php echo $customer['email']; ?></td>
                                        <td class="text-right"><?php echo $customer['customer_group']; ?></td>
                                        <td class="text-right">
                                            <?php if ($customer['newsletter'] == '1') { ?>
                                                <?php echo $entry_yes; ?>
                                            <?php } else { ?>
                                                <?php echo $entry_no; ?>
                                            <?php } ?>
                                        </td>
                                        <td class="text-right">
                                            <?php if ($customer['store_id'] == '0') { ?>
                                                <?php echo $text_default; ?>
                                            <?php } else { ?>
                                                <?php foreach ($stores as $store) { ?>
                                                    <?php if ($customer['store_id'] == $store['store_id']) { ?>
                                                        <?php echo $store['name']; ?>
                                                        <?php break; ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                        <td class="text-right">
                                            <?php if (!$customer['language_code']) { ?>
                                                <?php echo $text_default; ?>
                                            <?php } else foreach ($languages as $language) { ?>
                                                <?php if ($customer['language_code'] == $language['code']) { ?>
                                                    <?php echo $language['name']; ?>
                                                    <?php break; ?>
                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                        <td class="text-right">
                                            <?php if ($customer['newsletter']) { ?>
                                                <a href="<?php echo $unsubscribe . $customer['customer_id']; ?>" data-toggle="tooltip" title="<?php echo $button_unsubscribe; ?>" class="btn btn-danger"><i class="fa fa-minus"></i></a>
                                            <?php } else { ?>
                                                <a href="<?php echo $subscribe . $customer['customer_id']; ?>" data-toggle="tooltip" title="<?php echo $button_subscribe; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                                            <?php } ?>
                                        </td>
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
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
            </div>
        </div>
        <p class="text-center small">Newsletter Enhancements OpenCart Module v3.7.2</p>
    </div>
    <script type="text/javascript"><!--
        $('#button-filter').on('click', function() {
            var url = 'index.php?route=ne/subscribers&token=<?php echo $token; ?>';

            var filter_name = $('input[name=\'filter_name\']').val();

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }

            var filter_email = $('input[name=\'filter_email\']').val();

            if (filter_email) {
                url += '&filter_email=' + encodeURIComponent(filter_email);
            }

            var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').val();

            if (filter_customer_group_id) {
                url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
            }

            var filter_newsletter = $('select[name=\'filter_newsletter\']').val();

            if (filter_newsletter) {
                url += '&filter_newsletter=' + encodeURIComponent(filter_newsletter);
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
</div>
<?php echo $footer; ?>