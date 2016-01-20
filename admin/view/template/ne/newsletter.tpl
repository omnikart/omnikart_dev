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
            <div class="pull-right">
                <a id="button-preview" href="#" data-toggle="tooltip" title="<?php echo $button_preview; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a>
                <a onclick="$('input[name=spam_check]').val('1');send('index.php?route=ne/newsletter/send&token=<?php echo $token; ?>');return false;" href="#" data-toggle="tooltip" title="<?php echo $button_check; ?>" class="btn btn-warning"><i class="fa fa-check-square-o"></i></a>
                <a onclick="$('#form').ajaxFormUnbind();$('#form').attr('action', '<?php echo $save; ?>');$('#form').submit();return false;" href="#" data-toggle="tooltip" title="<?php echo ($back ? $button_update : $button_save); ?>" class="btn btn-success"><i class="fa fa-floppy-o"></i></a>
                <a id="button-send" href="#" data-toggle="tooltip" title="<?php echo $button_send; ?>" class="btn btn-primary"><i class="fa fa-envelope-o"></i></a>
                <a href="<?php echo ($back ? $back : $reset); ?>" data-toggle="tooltip" title="<?php echo ($back ? $button_back : $button_reset); ?>" class="btn btn-default"><i class="fa fa-<?php echo ($back ? 'reply' : 'undo'); ?>"></i></a>
            </div>
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
                <h3 class="panel-title"><i class="fa fa-envelope"></i> <?php echo $text_create_and_send; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <input type="hidden" name="newsletter_id" value="" />
                    <input type="hidden" name="sent_count" value="" />
                    <input type="hidden" name="spam_check" value="" />
                    <input type="hidden" name="attachments_id" value="<?php echo $attachments_id; ?>" />
                    <input type="hidden" name="attachments_count" value="0" />
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-template"><?php echo $entry_template; ?></label>
                        <div class="col-sm-10">
                            <select name="template_id" id="input-template" class="form-control">
                                <?php foreach ($templates as $template) { ?>
                                    <?php if ($template['template_id'] == $template_id) { ?>
                                        <option value="<?php echo $template['template_id']; ?>" selected="selected"><?php echo $template['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $template['template_id']; ?>"><?php echo $template['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-language"><?php echo $entry_language; ?></label>
                        <div class="col-sm-10">
                            <select name="language_id" id="input-language" class="form-control">
                                <?php foreach ($languages as $language) { ?>
                                    <?php if ($language['language_id'] == $config_language_id) { ?>
                                        <option value="<?php echo $language['language_id']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                            <div class="checkbox">
                                <label>
                                    <?php if ($only_selected_language) { ?>
                                        <input type="checkbox" name="only_selected_language" value="1" checked="checked" />
                                    <?php } else { ?>
                                        <input type="checkbox" name="only_selected_language" value="1" />
                                    <?php } ?>
                                    <?php echo $text_only_selected_language; ?>
                                </label>
                            </div>
                            <p class="help-block"><?php echo $text_only_selected_language_help; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-currency"><?php echo $entry_currency; ?></label>
                        <div class="col-sm-10">
                            <select name="currency" id="input-currency" class="form-control">
                                <?php foreach ($currencies as $cur) { ?>
                                    <?php if ($cur['code'] == $currency) { ?>
                                        <option value="<?php echo $cur['code']; ?>" selected="selected"><?php echo $cur['title']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $cur['code']; ?>"><?php echo $cur['title']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-store"><?php echo $entry_store; ?></label>
                        <div class="col-sm-10">
                            <select name="store_id" id="input-store" class="form-control">
                                <?php array_unshift($stores, array('store_id' => 0, 'name' => $text_default)); ?>
                                <?php foreach ($stores as $store) { ?>
                                    <?php if ($store['store_id'] == $store_id) { ?>
                                        <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-circle"></i> <?php echo $text_clear_warning; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-to"><?php echo $entry_to; ?></label>
                        <div class="col-sm-10">
                            <select name="to" id="input-to" class="form-control">
                                <?php if ($to == 'newsletter') { ?>
                                    <option value="newsletter" selected="selected"><?php echo $text_newsletter; ?></option>
                                <?php } else { ?>
                                    <option value="newsletter"><?php echo $text_newsletter; ?></option>
                                <?php } ?>
                                <?php if ($to == 'customer_all') { ?>
                                    <option value="customer_all" selected="selected"><?php echo $text_customer_all; ?></option>
                                <?php } else { ?>
                                    <option value="customer_all"><?php echo $text_customer_all; ?></option>
                                <?php } ?>
                                <?php if ($to == 'customer_group') { ?>
                                    <option value="customer_group" selected="selected"><?php echo $text_customer_group; ?></option>
                                <?php } else { ?>
                                    <option value="customer_group"><?php echo $text_customer_group; ?></option>
                                <?php } ?>
                                <?php if ($to == 'customer') { ?>
                                    <option value="customer" selected="selected"><?php echo $text_customer; ?></option>
                                <?php } else { ?>
                                    <option value="customer"><?php echo $text_customer; ?></option>
                                <?php } ?>
                                <?php if ($to == 'affiliate_all') { ?>
                                    <option value="affiliate_all" selected="selected"><?php echo $text_affiliate_all; ?></option>
                                <?php } else { ?>
                                    <option value="affiliate_all"><?php echo $text_affiliate_all; ?></option>
                                <?php } ?>
                                <?php if ($to == 'affiliate') { ?>
                                    <option value="affiliate" selected="selected"><?php echo $text_affiliate; ?></option>
                                <?php } else { ?>
                                    <option value="affiliate"><?php echo $text_affiliate; ?></option>
                                <?php } ?>
                                <?php if ($to == 'product') { ?>
                                    <option value="product" selected="selected"><?php echo $text_product; ?></option>
                                <?php } else { ?>
                                    <option value="product"><?php echo $text_product; ?></option>
                                <?php } ?>
                                <?php if ($to == 'marketing') { ?>
                                    <option value="marketing" selected="selected"><?php echo $text_marketing; ?></option>
                                <?php } else { ?>
                                    <option value="marketing"><?php echo $text_marketing; ?></option>
                                <?php } ?>
                                <?php if ($to == 'marketing_all') { ?>
                                    <option value="marketing_all" selected="selected"><?php echo $text_marketing_all; ?></option>
                                <?php } else { ?>
                                    <option value="marketing_all"><?php echo $text_marketing_all; ?></option>
                                <?php } ?>
                                <?php if ($to == 'subscriber') { ?>
                                    <option value="subscriber" selected="selected"><?php echo $text_subscriber_all; ?></option>
                                <?php } else { ?>
                                    <option value="subscriber"><?php echo $text_subscriber_all; ?></option>
                                <?php } ?>
                                <?php if ($to == 'all') { ?>
                                    <option value="all" selected="selected"><?php echo $text_all; ?></option>
                                <?php } else { ?>
                                    <option value="all"><?php echo $text_all; ?></option>
                                <?php } ?>
                                <?php if ($to == 'rewards') { ?>
                                    <option value="rewards" selected="selected"><?php echo $text_rewards; ?></option>
                                <?php } else { ?>
                                    <option value="rewards"><?php echo $text_rewards; ?></option>
                                <?php } ?>
                                <?php if ($to == 'rewards_all') { ?>
                                    <option value="rewards_all" selected="selected"><?php echo $text_rewards_all; ?></option>
                                <?php } else { ?>
                                    <option value="rewards_all"><?php echo $text_rewards_all; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <?php if ($list_data) { ?>
                        <div class="form-group to to-marketing">
                            <label class="col-sm-2 control-label"><?php echo $entry_marketing; ?></label>
                            <div class="col-sm-10">
                                <?php foreach ($stores as $store) { ?>
                                    <div class="well well-sm list-display marketing-list-<?php echo $store['store_id']; ?>" style="height: 150px; overflow: auto;">
                                        <?php if (isset($list_data[$store['store_id']]) && $list_data[$store['store_id']]) foreach ($list_data[$store['store_id']] as $key => $list) { ?>
                                            <div class="checkbox">
                                                <label>
                                                    <?php if ($marketing_list && is_array($marketing_list) && in_array($store['store_id'] . '_' . $key, $marketing_list)) { ?>
                                                        <input type="checkbox" name="marketing_list[]" value="<?php echo $store['store_id']; ?>_<?php echo $key; ?>" checked="checked" />
                                                    <?php } else { ?>
                                                        <input type="checkbox" name="marketing_list[]" value="<?php echo $store['store_id']; ?>_<?php echo $key; ?>" />
                                                    <?php } ?>
                                                    <?php echo $list[$config_language_id]; ?>
                                                </label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group to to-customer-group">
                        <label class="col-sm-2 control-label" for="input-customer-group"><?php echo $entry_customer_group; ?></label>
                        <div class="col-sm-10">
                            <select name="customer_group_id" id="input-customer-group" class="form-control">
                                <?php foreach ($customer_groups as $customer_group) { ?>
                                    <?php if ($customer_group['customer_group_id'] == $customer_group_id && $to == 'customer_group') { ?>
                                        <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                            <div class="checkbox">
                                <label>
                                    <?php if ($customer_group_only_subscribers) { ?>
                                        <input type="checkbox" name="customer_group_only_subscribers" value="1" checked="checked" />
                                    <?php } else { ?>
                                        <input type="checkbox" name="customer_group_only_subscribers" value="1" />
                                    <?php } ?>
                                    <?php echo $text_only_subscribers; ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group to to-customer">
                        <label class="col-sm-2 control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="customers" value="" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" autocomplete="off">
                            <div id="customer" class="well well-sm" style="height: 150px; overflow: auto;">
                                <?php foreach ($customers as $customer) { ?>
                                    <div id="customer-<?php echo $customer['customer_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $customer['name']; ?>
                                        <input type="hidden" name="customer[]" value="<?php echo $customer['customer_id']; ?>" />
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group to to-affiliate">
                        <label class="col-sm-2 control-label" for="input-affiliate"><?php echo $entry_affiliate; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="affiliates" value="" placeholder="<?php echo $entry_affiliate; ?>" id="input-affiliate" class="form-control" autocomplete="off">
                            <div id="affiliate" class="well well-sm" style="height: 150px; overflow: auto;">
                                <?php foreach ($affiliates as $affiliate) { ?>
                                    <div id="affiliate-<?php echo $affiliate['affiliate_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $affiliate['name']; ?>
                                        <input type="hidden" name="affiliate[]" value="<?php echo $affiliate['affiliate_id']; ?>" />
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group to to-product">
                        <label class="col-sm-2 control-label" for="input-product"><?php echo $entry_product; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="products" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" autocomplete="off">
                            <div id="product" class="well well-sm" style="height: 150px; overflow: auto;">
                                <?php foreach ($products as $product) { ?>
                                    <div id="product-<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
                                        <input type="hidden" name="product[]" value="<?php echo $product['product_id']; ?>" />
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_defined; ?></label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <?php if ($defined) { ?>
                                    <input type="radio" name="defined" value="1" checked="checked" />
                                    <?php echo $entry_yes; ?>
                                <?php } else { ?>
                                    <input type="radio" name="defined" value="1" />
                                    <?php echo $entry_yes; ?>
                                <?php } ?>
                            </label>
                            <label class="radio-inline">
                                <?php if (!$defined) { ?>
                                    <input type="radio" name="defined" value="0" checked="checked" />
                                    <?php echo $entry_no; ?>
                                <?php } else { ?>
                                    <input type="radio" name="defined" value="0" />
                                    <?php echo $entry_no; ?>
                                <?php } ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group defined-product">
                        <label class="col-sm-2 control-label" for="input-section-name"><?php echo $entry_section_name; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="defined_product_text" value="" placeholder="<?php echo $entry_section_name; ?>" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group defined-product">
                        <label class="col-sm-2 control-label" for="input-defined-products"><?php echo $entry_product; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="defined_products" value="" placeholder="<?php echo $entry_product; ?>" id="input-defined-products" class="form-control" autocomplete="off">
                            <div id="defined-product" class="well well-sm" style="height: 150px; overflow: auto;">
                                <?php foreach ($defined_products as $product) { ?>
                                    <div id="defined-product-<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
                                        <input type="hidden" name="defined_product[]" value="<?php echo $product['product_id']; ?>" />
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php foreach ($defined_products_more as $index => $dpm) { ?>
                        <div class="form-group defined-product">
                            <label class="col-sm-2 control-label" for="input-section-name-<?php echo $index; ?>"><?php echo $entry_section_name; ?></label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" name="defined_product_more[<?php echo $index; ?>][text]" value="<?php echo $dpm['text']; ?>" id="input-section-name-<?php echo $index; ?>" placeholder="<?php echo $entry_section_name; ?>" class="form-control" />
                                    <span class="input-group-btn">
                                        <button onclick="removeSection(this);" class="btn btn-default" type="button">
                                            <i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group defined-product">
                            <label class="col-sm-2 control-label" for="input-defined-products-<?php echo $index; ?>"><?php echo $entry_product; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="defined_products_<?php echo $index; ?>" value="" placeholder="<?php echo $entry_product; ?>" id="input-defined-products-<?php echo $index; ?>" data-id="<?php echo $index; ?>" class="form-control" autocomplete="off">
                                <div id="defined-product-<?php echo $index; ?>" class="well well-sm" style="height: 150px; overflow: auto;">
                                    <?php foreach ($dpm['products'] as $product) { ?>
                                        <div id="defined-product-entry-<?php echo $index; ?>-<?php echo $product['product_id']; ?>"><i class="fa fa-minus"></i> <?php echo $product['name']; ?>
                                            <input type="hidden" name="defined_product_more[<?php echo $index; ?>][products][]" value="<?php echo $product['product_id']; ?>" />
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group defined-product">
                        <div class="col-sm-10 col-sm-offset-2">
                            <button id="button-add-defined-section" class="btn btn-primary" type="button">
                                <i class="fa fa-plus"></i> <?php echo $button_add_defined_section; ?>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_special; ?></label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <?php if ($special) { ?>
                                    <input type="radio" name="special" value="1" checked="checked" />
                                    <?php echo $entry_yes; ?>
                                <?php } else { ?>
                                    <input type="radio" name="special" value="1" />
                                    <?php echo $entry_yes; ?>
                                <?php } ?>
                            </label>
                            <label class="radio-inline">
                                <?php if (!$special) { ?>
                                    <input type="radio" name="special" value="0" checked="checked" />
                                    <?php echo $entry_no; ?>
                                <?php } else { ?>
                                    <input type="radio" name="special" value="0" />
                                    <?php echo $entry_no; ?>
                                <?php } ?>
                            </label>
                            <div class="checkbox checkbox-special">
                                <label>
                                    <?php if (!empty($skip_out_of_stock["special"])) { ?>
                                        <input type="checkbox" name="skip_out_of_stock[special]" value="1" checked="checked" />
                                    <?php } else { ?>
                                        <input type="checkbox" name="skip_out_of_stock[special]" value="1" />
                                    <?php } ?>
                                    <?php echo $text_skip_out_of_stock; ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_latest; ?></label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <?php if ($latest) { ?>
                                    <input type="radio" name="latest" value="1" checked="checked" />
                                    <?php echo $entry_yes; ?>
                                <?php } else { ?>
                                    <input type="radio" name="latest" value="1" />
                                    <?php echo $entry_yes; ?>
                                <?php } ?>
                            </label>
                            <label class="radio-inline">
                                <?php if (!$latest) { ?>
                                    <input type="radio" name="latest" value="0" checked="checked" />
                                    <?php echo $entry_no; ?>
                                <?php } else { ?>
                                    <input type="radio" name="latest" value="0" />
                                    <?php echo $entry_no; ?>
                                <?php } ?>
                            </label>
                            <div class="checkbox checkbox-latest">
                                <label>
                                    <?php if (!empty($skip_out_of_stock["latest"])) { ?>
                                        <input type="checkbox" name="skip_out_of_stock[latest]" value="1" checked="checked" />
                                    <?php } else { ?>
                                        <input type="checkbox" name="skip_out_of_stock[latest]" value="1" />
                                    <?php } ?>
                                    <?php echo $text_skip_out_of_stock; ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_popular; ?></label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <?php if ($popular) { ?>
                                    <input type="radio" name="popular" value="1" checked="checked" />
                                    <?php echo $entry_yes; ?>
                                <?php } else { ?>
                                    <input type="radio" name="popular" value="1" />
                                    <?php echo $entry_yes; ?>
                                <?php } ?>
                            </label>
                            <label class="radio-inline">
                                <?php if (!$popular) { ?>
                                    <input type="radio" name="popular" value="0" checked="checked" />
                                    <?php echo $entry_no; ?>
                                <?php } else { ?>
                                    <input type="radio" name="popular" value="0" />
                                    <?php echo $entry_no; ?>
                                <?php } ?>
                            </label>
                            <div class="checkbox checkbox-popular">
                                <label>
                                    <?php if (!empty($skip_out_of_stock["popular"])) { ?>
                                        <input type="checkbox" name="skip_out_of_stock[popular]" value="1" checked="checked" />
                                    <?php } else { ?>
                                        <input type="checkbox" name="skip_out_of_stock[popular]" value="1" />
                                    <?php } ?>
                                    <?php echo $text_skip_out_of_stock; ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_defined_categories; ?></label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <?php if ($defined_categories) { ?>
                                    <input type="radio" name="defined_categories" value="1" checked="checked" />
                                    <?php echo $entry_yes; ?>
                                <?php } else { ?>
                                    <input type="radio" name="defined_categories" value="1" />
                                    <?php echo $entry_yes; ?>
                                <?php } ?>
                            </label>
                            <label class="radio-inline">
                                <?php if (!$defined_categories) { ?>
                                    <input type="radio" name="defined_categories" value="0" checked="checked" />
                                    <?php echo $entry_no; ?>
                                <?php } else { ?>
                                    <input type="radio" name="defined_categories" value="0" />
                                    <?php echo $entry_no; ?>
                                <?php } ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group defined-categories">
                        <div class="col-sm-10 col-sm-offset-2">
                            <div class="well well-sm" style="height: 150px; overflow: auto;">
                                <?php foreach ($categories as $category) { ?>
                                    <div class="checkbox">
                                        <label>
                                            <?php if ($defined_category && is_array($defined_category) && in_array($category['category_id'], $defined_category)) { ?>
                                                <input type="checkbox" name="defined_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                                            <?php } else { ?>
                                                <input type="checkbox" name="defined_category[]" value="<?php echo $category['category_id']; ?>" />
                                            <?php } ?>
                                            <?php echo $category['name']; ?>
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="form-inline">
                                <div class="form-group" style="margin-left:0; margin-right:15px;">
                                    <label for="input-defined-categories-limit"><?php echo $entry_limit; ?></label>
                                    <input type="text" name="defined_category_limit" value="<?php echo $defined_category_limit; ?>" class="form-control" id="input-defined-categories-limit" size="2">
                                </div>
                                <div class="form-group" style="margin-left:0; margin-right:15px;">
                                    <label><?php echo $entry_sort_by; ?></label>
                                    <select name="defined_category_sort" class="form-control">
                                        <?php if ($defined_category_sort == 'date_added') { ?>
                                            <option value="date_added" selected="selected"><?php echo $text_date_added; ?></option>
                                        <?php } else { ?>
                                            <option value="date_added"><?php echo $text_date_added; ?></option>
                                        <?php } ?>
                                        <?php if ($defined_category_sort == 'sort_order') { ?>
                                            <option value="sort_order" selected="selected"><?php echo $text_sort_order; ?></option>
                                        <?php } else { ?>
                                            <option value="sort_order"><?php echo $text_sort_order; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-left:0; margin-right:15px;">
                                    <label><?php echo $entry_order; ?></label>
                                    <select name="defined_category_order" class="form-control">
                                        <?php if (!$defined_category_order) { ?>
                                            <option value="0" selected="selected"><?php echo $text_ascending; ?></option>
                                        <?php } else { ?>
                                            <option value="0"><?php echo $text_ascending; ?></option>
                                        <?php } ?>
                                        <?php if ($defined_category_order) { ?>
                                            <option value="1" selected="selected"><?php echo $text_descending; ?></option>
                                        <?php } else { ?>
                                            <option value="1"><?php echo $text_descending; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group attachments">
                        <label class="col-sm-2 control-label"><?php echo $entry_attachments; ?></label>
                        <div class="col-sm-10">
                            <button onclick="addFile(this);" class="btn btn-primary" type="button">
                                <i class="fa fa-plus"></i> <?php echo $button_add_file; ?>
                            </button>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-subject"><?php echo $entry_subject; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="subject" value="<?php echo $subject; ?>" id="input-subject" placeholder="<?php echo $entry_subject; ?>" class="form-control" />
                            <?php if ($error_subject) { ?>
                                <div class="text-danger"><?php echo $error_subject; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-2 control-label"><?php echo $entry_message; ?></label>
                        <div class="col-sm-10">
                            <textarea name="message" class="form-control"><?php echo $message; ?></textarea>
                            <?php if ($error_message) { ?>
                                <div class="text-danger"><?php echo $error_message; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" dor="input-text-message"><?php echo $entry_text_message; ?></label>
                        <div class="col-sm-10">
                            <textarea name="text_message" id="input-text-message" rows="10" class="form-control"><?php echo $text_message; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_text_message_products; ?></label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <?php if ($text_message_products) { ?>
                                    <input type="radio" name="text_message_products" value="1" checked="checked" />
                                    <?php echo $entry_yes; ?>
                                <?php } else { ?>
                                    <input type="radio" name="text_message_products" value="1" />
                                    <?php echo $entry_yes; ?>
                                <?php } ?>
                            </label>
                            <label class="radio-inline">
                                <?php if (!$text_message_products) { ?>
                                    <input type="radio" name="text_message_products" value="0" checked="checked" />
                                    <?php echo $entry_no; ?>
                                <?php } else { ?>
                                    <input type="radio" name="text_message_products" value="0" />
                                    <?php echo $entry_no; ?>
                                <?php } ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <div class="alert alert-info">
                                <?php echo $text_message_info; ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <p class="text-center small">Newsletter Enhancements OpenCart Module v3.7.2</p>
    </div>

    <div class="modal fade" id="preview-modal" tabindex="-1" role="dialog" aria-labelledby="preview-modal-label" aria-hidden="true">
        <div class="modal-dialog" style="min-width:800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="view/javascript/ne/jquery/jquery.form.js"></script>
    <script type="text/javascript"><!--
        CKEDITOR.replace('message', {
            height:'500'
        });
    //--></script>
    <script type="text/javascript"><!--
        var defined_section_row = <?php echo max(count($defined_products_more), 0); ?>;
        var attachment_row = 0;

        $(document).ready(function() {
            $('select[name=\'to\']').bind('change', function() {
                $('.to').hide();
                $('.to-' + $(this).val().replace('_', '-')).show();

                var to = $(this).val();

                if (to == 'marketing' || to == 'marketing_all' || to == 'subscriber' || to == 'all') {
                    if ($('.marketing-list-' + $('select[name=\'store_id\']').val() + ' input:checkbox').size() == 0) {
                        $('.to-marketing').hide();
                    } else {
                        $('.to-marketing').show();
                    }
                }

                getTemplate();
            }).trigger('change');

            $('#button-send').bind('click', function() {
                $(this).attr('disabled', true).find('i').removeClass('fa-envelope-o').addClass('fa-refresh fa-spin');

                $('#form').ajaxFormUnbind();

                $('#form').ajaxForm({
                    success: function() {
                        $('#button-send').attr('disabled', false).find('i').removeClass('fa-refresh fa-spin').addClass('fa-envelope-o');
                        send('index.php?route=ne/newsletter/send&token=<?php echo $token; ?>');
                    }
                });

                $('#form').submit();

                return false;
            });

            $('select').each(function(){
                if ($(this).children().length <= 1) {
                    $(this).parent().parent().hide();
                }
            });

            $('input[name=\'defined\']').bind('click', function() {
                if ($('input[name=\'defined\']:checked').val() == 1)
                {
                    $('.defined-product').show();
                }
                else
                {
                    $('.defined-product').hide();
                }
                getTemplate();
            });

            $('input[name=\'defined_categories\']').bind('click', function() {
                if ($('input[name=\'defined_categories\']:checked').val() == 1)
                {
                    $('.defined-categories').show();
                }
                else
                {
                    $('.defined-categories').hide();
                }
                getTemplate();
            });

            $('input[name="skip_out_of_stock[special]"], input[name="skip_out_of_stock[latest]"], input[name="skip_out_of_stock[popular]"]').bind('click', function() {
                getTemplate();
            });

            $('input[name=\'special\'], input[name=\'latest\'], input[name=\'popular\']').bind('click', function() {

                if ($('input[name=\'special\']:checked').val() == 1)
                {
                    $('.checkbox-special').show();
                }
                else
                {
                    $('.checkbox-special').hide();
                }

                if ($('input[name=\'latest\']:checked').val() == 1)
                {
                    $('.checkbox-latest').show();
                }
                else
                {
                    $('.checkbox-latest').hide();
                }

                if ($('input[name=\'popular\']:checked').val() == 1)
                {
                    $('.checkbox-popular').show();
                }
                else
                {
                    $('.checkbox-popular').hide();
                }

                getTemplate();
            });


            $('select[name=\'currency\']').bind('change', function() {
                getTemplate();
            });

            $('select[name=\'language_id\'], select[name=\'template_id\']').bind('change', function(){
                getDefinedText();
                getTemplate(true);
            });

            $('select[name=\'customer_group_id\']').bind('change', function(){
                getTemplate();
            });

            $('input[name="defined_category[]"]').bind('click', function(){
                getTemplate();
            });

            $('input[name=\'defined_category_limit\']').bind('change', function(){
                getTemplate();
            });

            $('select[name=\'defined_category_sort\']').bind('change', function(){
                getTemplate();
            });

            $('select[name=\'defined_category_order\']').bind('change', function(){
                getTemplate();
            });

            $('.list-display').hide();
            $('.marketing-list-' + $('select[name=\'store_id\']').val()).show();

            $('select[name=\'store_id\']').bind('change', function(){
                $('.list-display').hide();
                $('.list-display input:checkbox').removeAttr('checked');

                var to = $('select[name=\'to\']').val();

                if (to == 'marketing' || to == 'marketing_all' || to == 'subscriber' || to == 'all') {
                    if ($('.marketing-list-' + $(this).val() + ' input:checkbox').size() == 0) {
                        $('.to-marketing').hide();
                    } else {
                        $('.to-marketing').show();
                    }
                }

                $('.marketing-list-' + $(this).val()).show();

                getTemplate(true);
            });

            if ($('input[name=\'defined\']:checked').val() == 1)
            {
                $('.defined-product').show();
            }
            else
            {
                $('.defined-product').hide();
            }

            if ($('input[name=\'special\']:checked').val() == 1)
            {
                $('.checkbox-special').show();
            }
            else
            {
                $('.checkbox-special').hide();
            }

            if ($('input[name=\'latest\']:checked').val() == 1)
            {
                $('.checkbox-latest').show();
            }
            else
            {
                $('.checkbox-latest').hide();
            }

            if ($('input[name=\'popular\']:checked').val() == 1)
            {
                $('.checkbox-popular').show();
            }
            else
            {
                $('.checkbox-popular').hide();
            }

            if ($('input[name=\'defined_categories\']:checked').val() == 1)
            {
                $('.defined-categories').show();
            }
            else
            {
                $('.defined-categories').hide();
            }

            $('input[name=\'defined_products\']').autocomplete({
                'source': function(request, response) {
                    $.ajax({
                        url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                        dataType: 'json',
                        data: {
                            token: '<?php echo $token; ?>',
                            filter_name: encodeURIComponent(request)
                        },
                        type: 'post',
                        success: function(json) {
                            response($.map(json, function(item) {
                                return {
                                    label: item['name'],
                                    value: item['product_id']
                                }
                            }));
                        }
                    });
                },
                'select': function(item) {
                    $(this).val('');

                    $('#defined-product-' + item['value']).remove();
                    $('#defined-product').append('<div id="defined-product-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="defined_product[]" value="' + item['value'] + '" /></div>');

                    getTemplate();
                }
            });

            $('input[name^=\'defined_products_\']').autocomplete({
                'source': function(request, response) {
                    $.ajax({
                        url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                        dataType: 'json',
                        data: {
                            token: '<?php echo $token; ?>',
                            filter_name: encodeURIComponent(request)
                        },
                        type: 'post',
                        success: function(json) {
                            response($.map(json, function(item) {
                                return {
                                    label: item['name'],
                                    value: item['product_id']
                                }
                            }));
                        }
                    });
                },
                'select': function(item) {
                    $(this).val('');

                    var id = $(this).attr('data-id');

                    $('#defined-product-entry-' + id + '-' + item['value']).remove();
                    $('#defined-product-' + id).append('<div id="defined-product-entry-' + id + '-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="defined_product_more[' + id + '][products][]" value="' + item['value'] + '" /></div>');

                    getTemplate();
                }
            });

            $('#defined-product').delegate('.fa-minus-circle', 'click', function() {
                $(this).parent().remove();

                getTemplate();
            });

            $("[id^='defined-product-']").delegate('.fa-minus-circle', 'click', function() {
                $(this).parent().remove();

                getTemplate();
            });

            $('input[name=\'customers\']').autocomplete({
                'source': function(request, response) {
                    $.ajax({
                        url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                        dataType: 'json',
                        data: {
                            token: '<?php echo $token; ?>',
                            filter_name: encodeURIComponent(request)
                        },
                        type: 'post',
                        success: function(json) {
                            response($.map(json, function(item) {
                                return {
                                    label: item['name'],
                                    value: item['customer_id']
                                }
                            }));
                        }
                    });
                },
                'select': function(item) {
                    $(this).val('');
                    $('#customer-' + item['value']).remove();
                    $('#customer').append('<div id="customer-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="customer[]" value="' + item['value'] + '" /></div>');
                }
            });

            $('#customer').delegate('.fa-minus-circle', 'click', function() {
                $(this).parent().remove();
            });

            $('input[name=\'affiliates\']').autocomplete({
                'source': function(request, response) {
                    $.ajax({
                        url: 'index.php?route=marketing/affiliate/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                        dataType: 'json',
                        data: {
                            token: '<?php echo $token; ?>',
                            filter_name: encodeURIComponent(request)
                        },
                        type: 'post',
                        success: function(json) {
                            response($.map(json, function(item) {
                                return {
                                    label: item['name'],
                                    value: item['affiliate_id']
                                }
                            }));
                        }
                    });
                },
                'select': function(item) {
                    $(this).val('');
                    $('#affiliate-' + item['value']).remove();
                    $('#affiliate').append('<div id="affiliate-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="affiliate[]" value="' + item['value'] + '" /></div>');
                }
            });

            $('#affiliate').delegate('.fa-minus-circle', 'click', function() {
                $(this).parent().remove();
            });

            $('input[name=\'products\']').autocomplete({
                'source': function(request, response) {
                    $.ajax({
                        url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                        dataType: 'json',
                        data: {
                            token: '<?php echo $token; ?>',
                            filter_name: encodeURIComponent(request)
                        },
                        type: 'post',
                        success: function(json) {
                            response($.map(json, function(item) {
                                return {
                                    label: item['name'],
                                    value: item['product_id']
                                }
                            }));
                        }
                    });
                },
                'select': function(item) {
                    $(this).val('');
                    $('#product-' + item['value']).remove();
                    $('#product').append('<div id="product-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product[]" value="' + item['value'] + '" /></div>');
                }
            });

            $('#product').delegate('.fa-minus-circle', 'click', function() {
                $(this).parent().remove();
            });

            if (!$('textarea[name=message]').val()) {
                getTemplate();
            }

            $('.attachments').delegate('.fa-minus-circle', 'click', function() {
                $(this).parent().remove();
                attachment_row--;
                $('input[name=attachments_count]').val(attachment_row);
            });

            getDefinedText();

            $('#button-preview').bind('click', function() {
                $(this).attr('disabled', true).find('i').removeClass('fa-eye').addClass('fa-refresh fa-spin');

                var defined_products = [];

                if ($('input[name=defined]:checked').val() == 1) {
                    $('#defined-product input').each(function(id) {
                        var item = $('#defined-product input').get(id);
                        defined_products.push(item.value);
                    });
                }

                var defined_products_more = [];

                if ($('input[name=defined]:checked').val() == 1) {
                    for (var i = 0; i < defined_section_row; i++) {
                        defined_products_more[i] = {
                            'text' : $('input[name="defined_product_more[' + i + '][text]"]').val(),
                            'products' : []
                        };
                        $('#defined-product-' + i + ' input').each(function(id) {
                            var item = $('#defined-product-' + i + ' input').get(id);
                            defined_products_more[i]['products'].push(item.value);
                        });
                    }
                }

                var defined_categories = [];

                if ($('input[name=defined_categories]:checked').val() == 1) {
                    $('input[name="defined_category[]"]:checked').each(function(){
                        defined_categories.push($(this).val());
                    });
                }

                var skip_out_of_stock = [];

                if ($('input[name="skip_out_of_stock[special]"]').is(':checked')) {
                    skip_out_of_stock['special'] = 1;
                } else {
                    skip_out_of_stock['special'] = 0;
                }

                if ($('input[name="skip_out_of_stock[latest]"]').is(':checked')) {
                    skip_out_of_stock['latest'] = 1;
                } else {
                    skip_out_of_stock['latest'] = 0;
                }

                if ($('input[name="skip_out_of_stock[popular]"]').is(':checked')) {
                    skip_out_of_stock['popular'] = 1;
                } else {
                    skip_out_of_stock['popular'] = 0;
                }

                var message;

                for (var instance in CKEDITOR.instances) {
                    message = CKEDITOR.instances[instance].getData();
                }

                $.ajax({
                    type: 'post',
                    url: 'index.php?route=ne/newsletter/preview&token=<?php echo $token; ?>',
                    data: {
                        special: $('input[name=special]:checked').val(),
                        latest: $('input[name=latest]:checked').val(),
                        popular: $('input[name=popular]:checked').val(),
                        defined: defined_products,
                        defined_text: $('input[name=defined_product_text]').val(),
                        defined_more: defined_products_more,
                        defined_categories: defined_categories,
                        defined_category_limit: $('input[name=defined_category_limit]').val(),
                        defined_category_order: $('select[name=defined_category_order]').val(),
                        defined_category_sort: $('select[name=defined_category_sort]').val(),
                        skip_out_of_stock: $.extend({}, skip_out_of_stock),
                        template_id: $('select[name=template_id]').val(),
                        language_id: $('select[name=language_id]').val(),
                        currency: $('select[name=currency]').val(),
                        customer_group_id: ($('select[name=customer_group_id]').length ? $('select[name=customer_group_id]').val() : ''),
                        store_id: $('select[name=store_id]').val(),
                        message: message,
                        subject: $('input[name=subject]').val()
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#preview-modal .modal-title').text(data.subject);
                        $('#preview-modal .modal-body').html(data.body);
                        $('#preview-modal').modal('show');
                        $('#button-preview').attr('disabled', false).find('i').removeClass('fa-refresh fa-spin').addClass('fa-eye');
                    }
                });

                return false;
            });

            $('#button-add-defined-section').bind('click', function() {
                var html;
                html  = '<div class="form-group defined-product">';
                html += '   <label class="col-sm-2 control-label" for="input-section-name-' + defined_section_row + '"><?php echo $entry_section_name; ?></label>';
                html += '   <div class="col-sm-10">';
                html += '       <div class="input-group">';
                html += '           <input type="text" name="defined_product_more[' + defined_section_row + '][text]" value="" id="input-section-name-' + defined_section_row + '" placeholder="<?php echo $entry_section_name; ?>" class="form-control" />';
                html += '           <span class="input-group-btn">';
                html += '               <button onclick="removeSection(this);" class="btn btn-default" type="button">';
                html += '                   <i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?>';
                html += '               </button>';
                html += '           </span>';
                html += '       </div>';
                html += '   </div>';
                html += '</div>';
                html += '<div class="form-group defined-product">';
                html += '   <label class="col-sm-2 control-label" for="input-defined-products-' + defined_section_row + '"><?php echo $entry_product; ?></label>';
                html += '   <div class="col-sm-10">';
                html += '       <input type="text" name="defined_products_' + defined_section_row + '" value="" placeholder="<?php echo $entry_product; ?>" id="input-defined-products-' + defined_section_row + '" data-id="' + defined_section_row + '" class="form-control" autocomplete="off">';
                html += '       <div id="defined-product-' + defined_section_row + '" class="well well-sm" style="height: 150px; overflow: auto;"></div>';
                html += '   </div>';
                html += '</div>';

                $(this).parent().parent().before(html);

                $('input[name=\'defined_products_' + defined_section_row + '\']').autocomplete({
                    'source': function(request, response) {
                        $.ajax({
                            url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                            dataType: 'json',
                            data: {
                                token: '<?php echo $token; ?>',
                                filter_name: encodeURIComponent(request)
                            },
                            type: 'post',
                            success: function(json) {
                                response($.map(json, function(item) {
                                    return {
                                        label: item['name'],
                                        value: item['product_id']
                                    }
                                }));
                            }
                        });
                    },
                    'select': function(item) {
                        $(this).val('');

                        var id = $(this).attr('data-id');

                        $('#defined-product-entry-' + id + '-' + item['value']).remove();
                        $('#defined-product-' + id).append('<div id="defined-product-entry-' + id + '-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="defined_product_more[' + id + '][products][]" value="' + item['value'] + '" /></div>');

                        getTemplate();
                    }
                });

                defined_section_row++;
            });

        });


        function getDefinedText() {
            $.ajax({
                type: 'post',
                url: 'index.php?route=ne/newsletter/get_defined_text&token=<?php echo $token; ?>',
                data: {
                    template_id: $('select[name=template_id]').val(),
                    language_id: $('select[name=language_id]').val()
                },
                dataType: 'json',
                success: function(json) {
                    $('input[name=defined_product_text]').val(json.text);
                }
            });
        }

        function getTemplate(clear) {
            var defined_products = [];

            if ($('input[name=defined]:checked').val() == 1) {
                $('#defined-product input').each(function(id) {
                    var item = $('#defined-product input').get(id);
                    defined_products.push(item.value);
                });
            }

            var defined_products_more = [];

            if ($('input[name=defined]:checked').val() == 1) {
                for (var i = 0; i < defined_section_row; i++) {
                    defined_products_more[i] = {
                        'text' : $('input[name="defined_product_more[' + i + '][text]"]').val(),
                        'products' : []
                    };
                    $('#defined-product-' + i + ' input').each(function(id) {
                        var item = $('#defined-product-' + i + ' input').get(id);
                        defined_products_more[i]['products'].push(item.value);
                    });
                }
            }

            var defined_categories = [];

            if ($('input[name=defined_categories]:checked').val() == 1) {
                $('input[name="defined_category[]"]:checked').each(function(){
                    defined_categories.push($(this).val());
                });
            }

            var skip_out_of_stock = [];

            if ($('input[name="skip_out_of_stock[special]"]').is(':checked')) {
                skip_out_of_stock['special'] = 1;
            } else {
                skip_out_of_stock['special'] = 0;
            }

            if ($('input[name="skip_out_of_stock[latest]"]').is(':checked')) {
                skip_out_of_stock['latest'] = 1;
            } else {
                skip_out_of_stock['latest'] = 0;
            }

            if ($('input[name="skip_out_of_stock[popular]"]').is(':checked')) {
                skip_out_of_stock['popular'] = 1;
            } else {
                skip_out_of_stock['popular'] = 0;
            }

            var message;

            for (var instance in CKEDITOR.instances) {
                message = CKEDITOR.instances[instance].getData();
            }

            $.ajax({
                type: 'post',
                url: 'index.php?route=ne/newsletter/template&token=<?php echo $token; ?>',
                data: {
                    special: $('input[name=special]:checked').val(),
                    latest: $('input[name=latest]:checked').val(),
                    popular: $('input[name=popular]:checked').val(),
                    defined: defined_products,
                    defined_text: $('input[name=defined_product_text]').val(),
                    defined_more: defined_products_more,
                    defined_categories: defined_categories,
                    defined_category_limit: $('input[name=defined_category_limit]').val(),
                    defined_category_order: $('select[name=defined_category_order]').val(),
                    defined_category_sort: $('select[name=defined_category_sort]').val(),
                    skip_out_of_stock: $.extend({

                    }, skip_out_of_stock),
                    to: $('select[name=to]').val(),
                    template_id: $('select[name=template_id]').val(),
                    language_id: $('select[name=language_id]').val(),
                    currency: $('select[name=currency]').val(),
                    customer_group_id: ($('select[name=customer_group_id]').length ? $('select[name=customer_group_id]').val() : ''),
                    store_id: $('select[name=store_id]').val(),
                    attachments_id: $('input[name=attachments_id]').val(),
                    message: message,
                    subject: $('input[name=subject]').val(),
                    text_message: $('textarea[name=text_message]').val(),
                    clear: clear
                },
                dataType: 'json',
                success: function(json) {
                    $('input[name=subject]').val(json.subject);
                    $('textarea[name=text_message]').val(json.text_message);
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].setData(json.body);
                    }
                }
            });
        }

        function removeSection(el) {
            $(el).closest('.defined-product').next().remove();
            $(el).closest('.defined-product').remove();

            defined_section_row--;

            getTemplate();
        }

        function addFile(el) {
            var html;
            html  = '<div id="attachment_' + attachment_row + '" style="margin-bottom: 10px;">';
            html += '   <i class="fa fa-minus-circle pull-left"></i>';
            html += '   <input type="file" name="attachment_' + attachment_row + '" value="" style="display:inline-block;" />';
            html += '</div>';

            $(el).before(html);

            attachment_row++;

            $('input[name=attachments_count]').val(attachment_row);
        }

        function send(url) {
            var message;

            for (var instance in CKEDITOR.instances) {
                message = CKEDITOR.instances[instance].getData();
            }

            $('textarea[name=\'message\']').html(message);

            $.ajax({
                url: url,
                type: 'post',
                data: $("#form").serialize(),
                dataType: 'json',
                beforeSend: function() {
                    $('#button-send').attr('disabled', true).find('i').removeClass('fa-envelope-o').addClass('fa-refresh fa-spin');
                },
                complete: function() {
                    $('#button-send').attr('disabled', false).find('i').removeClass('fa-refresh fa-spin').addClass('fa-envelope-o');
                },
                success: function(json) {
                    $('#content > .container-fluid > .alert').remove();
                    $('.has-error .text-danger').remove();
                    $('.has-error').removeClass('has-error');

                    if (json['error']) {
                        if (json['error']['warning']) {
                            $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                        }
                        if (json['error']['subject']) {
                            $('input[name=\'subject\']').closest('.form-group').addClass('has-error');
                            $('input[name=\'subject\']').after('<div class="text-danger">' + json['error']['subject'] + '</div>');
                        }
                        if (json['error']['message']) {
                            $('textarea[name=\'message\']').closest('.form-group').addClass('has-error');
                            $('textarea[name=\'message\']').parent().append('<div class="text-danger">' + json['error']['message'] + '</div>');
                        }
                    }

                    if (json['next']) {
                        if (json['newsletter_id']) {
                            $('input[name=\'newsletter_id\']').val(json['newsletter_id']);
                        }
                        if (json['sent_count']) {
                            $('input[name=\'sent_count\']').val(json['sent_count']);
                        }
                        if (json['success']) {
                            $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
                            send(json['next']);
                        }
                    } else {
                        if (json['success']) {
                            $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                        }

                        $('input[name=\'sent_count\']').val('');
                        $('input[name=\'newsletter_id\']').val('');
                        $('input[name=\'spam_check\']').val('');
                    }
                }
            });

            $('input[name=spam_check]').val('0');
        }
    //--></script>
</div>
<?php echo $footer; ?>
