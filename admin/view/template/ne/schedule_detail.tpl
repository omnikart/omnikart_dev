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
                <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-floppy-o"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-calendar"></i> <?php echo $text_newsletters_schedule; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
                    <input type="hidden" name="date_next" value="<?php echo $date_next; ?>" />
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_active; ?></label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <?php if ($active) { ?>
                                    <input type="radio" name="active" value="1" checked="checked" />
                                    <?php echo $entry_yes; ?>
                                <?php } else { ?>
                                    <input type="radio" name="active" value="1" />
                                    <?php echo $entry_yes; ?>
                                <?php } ?>
                            </label>
                            <label class="radio-inline">
                                <?php if (!$active) { ?>
                                    <input type="radio" name="active" value="0" checked="checked" />
                                    <?php echo $entry_no; ?>
                                <?php } else { ?>
                                    <input type="radio" name="active" value="0" />
                                    <?php echo $entry_no; ?>
                                <?php } ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" value="<?php echo $name; ?>" id="input-name" placeholder="<?php echo $entry_name; ?>" class="form-control" />
                            <?php if ($error_name) { ?>
                                <div class="text-danger"><?php echo $error_name; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_recurrent; ?></label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <?php if ($recurrent) { ?>
                                    <input type="radio" name="recurrent" value="1" checked="checked" />
                                    <?php echo $entry_yes; ?>
                                <?php } else { ?>
                                    <input type="radio" name="recurrent" value="1" />
                                    <?php echo $entry_yes; ?>
                                <?php } ?>
                            </label>
                            <label class="radio-inline">
                                <?php if (!$recurrent) { ?>
                                    <input type="radio" name="recurrent" value="0" checked="checked" />
                                    <?php echo $entry_no; ?>
                                <?php } else { ?>
                                    <input type="radio" name="recurrent" value="0" />
                                    <?php echo $entry_no; ?>
                                <?php } ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group once">
                        <label class="col-sm-2 control-label" for="input-date"><?php echo $entry_datetime; ?></label>
                        <div class="col-sm-10">
                            <div class="form-inline">
                                <div class="form-group" style="margin-left:0; margin-right:15px;">
                                    <div class="input-group date">
                                        <input type="text" name="date" value="<?php echo $date; ?>" data-date-format="YYYY-MM-DD" id="input-date" class="form-control" />
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-left:0; margin-right:15px;">
                                    <label><?php echo $text_at; ?></label>
                                    <select name="time" class="form-control">
                                        <option value="0" <?php echo $time == '0' ? ' selected="selected"' : "" ?>>00:00</option>
                                        <option value="1" <?php echo $time == '1' ? ' selected="selected"' : "" ?>>01:00</option>
                                        <option value="2" <?php echo $time == '2' ? ' selected="selected"' : "" ?>>02:00</option>
                                        <option value="3" <?php echo $time == '3' ? ' selected="selected"' : "" ?>>03:00</option>
                                        <option value="4" <?php echo $time == '4' ? ' selected="selected"' : "" ?>>04:00</option>
                                        <option value="5" <?php echo $time == '5' ? ' selected="selected"' : "" ?>>05:00</option>
                                        <option value="6" <?php echo $time == '6' ? ' selected="selected"' : "" ?>>06:00</option>
                                        <option value="7" <?php echo $time == '7' ? ' selected="selected"' : "" ?>>07:00</option>
                                        <option value="8" <?php echo $time == '8' ? ' selected="selected"' : "" ?>>08:00</option>
                                        <option value="9" <?php echo $time == '9' ? ' selected="selected"' : "" ?>>09:00</option>
                                        <option value="10" <?php echo $time == '10' ? ' selected="selected"' : "" ?>>10:00</option>
                                        <option value="11" <?php echo $time == '11' ? ' selected="selected"' : "" ?>>11:00</option>
                                        <option value="12" <?php echo $time == '12' ? ' selected="selected"' : "" ?>>12:00</option>
                                        <option value="13" <?php echo $time == '13' ? ' selected="selected"' : "" ?>>13:00</option>
                                        <option value="14" <?php echo $time == '14' ? ' selected="selected"' : "" ?>>14:00</option>
                                        <option value="15" <?php echo $time == '15' ? ' selected="selected"' : "" ?>>15:00</option>
                                        <option value="16" <?php echo $time == '16' ? ' selected="selected"' : "" ?>>16:00</option>
                                        <option value="17" <?php echo $time == '17' ? ' selected="selected"' : "" ?>>17:00</option>
                                        <option value="18" <?php echo $time == '18' ? ' selected="selected"' : "" ?>>18:00</option>
                                        <option value="19" <?php echo $time == '19' ? ' selected="selected"' : "" ?>>19:00</option>
                                        <option value="20" <?php echo $time == '20' ? ' selected="selected"' : "" ?>>20:00</option>
                                        <option value="21" <?php echo $time == '21' ? ' selected="selected"' : "" ?>>21:00</option>
                                        <option value="22" <?php echo $time == '22' ? ' selected="selected"' : "" ?>>22:00</option>
                                        <option value="23" <?php echo $time == '23' ? ' selected="selected"' : "" ?>>23:00</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-left:0; margin-right:15px;">
                                    <label style="font-style:italic;font-weight:normal;"><?php echo $text_current_server_time; ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group recurrent">
                        <label class="col-sm-2 control-label"><?php echo $entry_frequency; ?></label>
                        <div class="col-sm-10">
                            <select name="frequency" class="form-control">
                                <option value="1" <?php echo $frequency == '1' ? ' selected="selected"' : "" ?>>
                                    <?php echo $text_daily; ?>
                                </option>
                                <option value="7" <?php echo $frequency == '7' ? ' selected="selected"' : "" ?>>
                                    <?php echo $text_weekly; ?>
                                </option>
                                <option value="30" <?php echo $frequency == '30' ? ' selected="selected"' : "" ?>>
                                    <?php echo $text_monthly; ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group recurrent">
                        <label class="col-sm-2 control-label" for="input-date"><?php echo $entry_daytime; ?></label>
                        <div class="col-sm-10">
                            <div class="form-inline">
                                <div class="form-group" style="margin-left:0; margin-right:15px;">
                                    <select name="day" <?php echo ( $frequency == '1' ) ? 'disabled="disabled"' : '' ?> class="form-control">
                                        <option value="1" <?php echo $day == '1' ? ' selected="selected"' : "" ?>><?php echo $text_monday; ?></option>
                                        <option value="2" <?php echo $day == '2' ? ' selected="selected"' : "" ?>><?php echo $text_tuesday; ?></option>
                                        <option value="3" <?php echo $day == '3' ? ' selected="selected"' : "" ?>><?php echo $text_wednesday; ?></option>
                                        <option value="4" <?php echo $day == '4' ? ' selected="selected"' : "" ?>><?php echo $text_thursday; ?></option>
                                        <option value="5" <?php echo $day == '5' ? ' selected="selected"' : "" ?>><?php echo $text_friday; ?></option>
                                        <option value="6" <?php echo $day == '6' ? ' selected="selected"' : "" ?>><?php echo $text_saturday; ?></option>
                                        <option value="0" <?php echo $day == '0' ? ' selected="selected"' : "" ?>><?php echo $text_sunday; ?></option>
                                        <?php if ( $frequency == '1' ) { ?>
                                        <option value="" selected="selected"><?php echo $text_daily; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-left:0; margin-right:15px;">
                                    <label><?php echo $text_at; ?></label>
                                    <select name="rtime" class="form-control">
                                        <option value="0" <?php echo $time == '0' ? ' selected="selected"' : "" ?>>00:00</option>
                                        <option value="1" <?php echo $time == '1' ? ' selected="selected"' : "" ?>>01:00</option>
                                        <option value="2" <?php echo $time == '2' ? ' selected="selected"' : "" ?>>02:00</option>
                                        <option value="3" <?php echo $time == '3' ? ' selected="selected"' : "" ?>>03:00</option>
                                        <option value="4" <?php echo $time == '4' ? ' selected="selected"' : "" ?>>04:00</option>
                                        <option value="5" <?php echo $time == '5' ? ' selected="selected"' : "" ?>>05:00</option>
                                        <option value="6" <?php echo $time == '6' ? ' selected="selected"' : "" ?>>06:00</option>
                                        <option value="7" <?php echo $time == '7' ? ' selected="selected"' : "" ?>>07:00</option>
                                        <option value="8" <?php echo $time == '8' ? ' selected="selected"' : "" ?>>08:00</option>
                                        <option value="9" <?php echo $time == '9' ? ' selected="selected"' : "" ?>>09:00</option>
                                        <option value="10" <?php echo $time == '10' ? ' selected="selected"' : "" ?>>10:00</option>
                                        <option value="11" <?php echo $time == '11' ? ' selected="selected"' : "" ?>>11:00</option>
                                        <option value="12" <?php echo $time == '12' ? ' selected="selected"' : "" ?>>12:00</option>
                                        <option value="13" <?php echo $time == '13' ? ' selected="selected"' : "" ?>>13:00</option>
                                        <option value="14" <?php echo $time == '14' ? ' selected="selected"' : "" ?>>14:00</option>
                                        <option value="15" <?php echo $time == '15' ? ' selected="selected"' : "" ?>>15:00</option>
                                        <option value="16" <?php echo $time == '16' ? ' selected="selected"' : "" ?>>16:00</option>
                                        <option value="17" <?php echo $time == '17' ? ' selected="selected"' : "" ?>>17:00</option>
                                        <option value="18" <?php echo $time == '18' ? ' selected="selected"' : "" ?>>18:00</option>
                                        <option value="19" <?php echo $time == '19' ? ' selected="selected"' : "" ?>>19:00</option>
                                        <option value="20" <?php echo $time == '20' ? ' selected="selected"' : "" ?>>20:00</option>
                                        <option value="21" <?php echo $time == '21' ? ' selected="selected"' : "" ?>>21:00</option>
                                        <option value="22" <?php echo $time == '22' ? ' selected="selected"' : "" ?>>22:00</option>
                                        <option value="23" <?php echo $time == '23' ? ' selected="selected"' : "" ?>>23:00</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-left:0; margin-right:15px;">
                                    <label style="font-style:italic;font-weight:normal;"><?php echo $text_current_server_time; ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_template; ?></label>
                        <div class="col-sm-10">
                            <select name="template_id" class="form-control">
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
                        <label class="col-sm-2 control-label"><?php echo $entry_language; ?></label>
                        <div class="col-sm-10">
                            <select name="language_id" class="form-control">
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
                        <label class="col-sm-2 control-label"><?php echo $entry_currency; ?></label>
                        <div class="col-sm-10">
                            <select name="currency" class="form-control">
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
                        <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                        <div class="col-sm-10">
                            <select name="store_id" class="form-control">
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
                                                    <?php echo $list[$config_language_id]; ?></label>
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
                    <div class="form-group random-setting">
                        <label class="col-sm-2 control-label"><?php echo $entry_random; ?></label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <?php if ($random) { ?>
                                    <input type="radio" name="random" value="1" checked="checked" />
                                    <?php echo $entry_yes; ?>
                                <?php } else { ?>
                                    <input type="radio" name="random" value="1" />
                                    <?php echo $entry_yes; ?>
                                <?php } ?>
                            </label>
                            <label class="radio-inline">
                                <?php if (!$random) { ?>
                                    <input type="radio" name="random" value="0" checked="checked" />
                                    <?php echo $entry_no; ?>
                                <?php } else { ?>
                                    <input type="radio" name="random" value="0" />
                                    <?php echo $entry_no; ?>
                                <?php } ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group required random">
                        <label class="col-sm-2 control-label" for="input-random-count"><span data-toggle="tooltip" title="<?php echo $help_random_count; ?>"><?php echo $entry_random_count; ?></span></label>
                        <div class="col-sm-10 col-md-2">
                            <input type="text" name="random_count" value="<?php echo $random_count; ?>" class="form-control" id="input-random-count" size="2">
                        </div>
                    </div>
                    <div class="form-group defined-setting">
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
                        <label class="col-sm-2 control-label" for="input-text-message"><?php echo $entry_text_message; ?></label>
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

    <script type="text/javascript" src="view/javascript/ne/ckeditor/ckeditor.js"></script>
    <script type="text/javascript"><!--
        CKEDITOR.replace('message', {
            height:'500'
        });
    //--></script>
    <script type="text/javascript"><!--
        var defined_section_row = <?php echo max(count($defined_products_more), 0); ?>;

        $(document).ready(function() {
            $('select[name=\'to\']').bind('change', function () {
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
            }).trigger('change');

            $('select').each(function(){
                if ($(this).children().length <= 1) {
                    $(this).parent().parent().hide();
                }
            });

            $('select[name=\'frequency\']').change(function() {
                var len = $('select[name=\'day\'] option').size();
                if ($(this).val() == '30' || $(this).val() == '1') {
                    if (len == 8) {
                        $('select[name=\'day\']').removeAttr('disabled');
                        $('select[name=\'day\'] option:last').remove();
                    }
                    $('select[name=\'day\']').append($("<option></option>").attr("value", "").text('<?php echo $text_daily; ?>'));
                    $('select[name=\'day\'] option:last').attr('selected', 'selected');
                    $('select[name=\'day\']').attr('disabled', 'disabled');
                } else if (len == 8) {
                    $('select[name=\'day\']').removeAttr('disabled');
                    $('select[name=\'day\'] option:last').remove();
                }
            });

            $('input[name=\'recurrent\']').bind('click', function() {
                if ($('input[name=\'recurrent\']:checked').val() == 1) {
                    $('.once').hide();
                    $('.recurrent').show();
                    $('.defined-setting').hide();
                    $('.random-setting').show();
                } else {
                    $('.recurrent').hide();
                    $('.once').show();
                    $('.random-setting').hide();
                    $('.defined-setting').show();
                }
                checkDefined();
                checkRandom();
            });
            $('input[name=\'recurrent\']:checked').trigger('click');

            $('select[name=\'language_id\'], select[name=\'template_id\']').bind('change', function(){
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
                getTemplate();
            });

            if ($('input[name=\'recurrent\']:checked').val() == 1)
            {
                $('.defined-setting').hide();
                $('.random-setting').show();
            }
            else
            {
                $('.random-setting').hide();
                $('.defined-setting').show();
            }

            if ($('input[name=\'defined_categories\']:checked').val() == 1)
            {
                $('.defined-categories').show();
            }
            else
            {
                $('.defined-categories').hide();
            }

            $('input[name=\'random\']').bind('click', function() {
                checkRandom();
            });

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
            });

            $('input[name=\'defined\']').bind('click', function() {
                checkDefined();
            });

            $('input[name=\'defined_categories\']').bind('click', function() {
                checkDefinedCategories();
            });

            var subject = $('input[name=\'subject\']').val();
            var message;

            for (var instance in CKEDITOR.instances) {
                message = CKEDITOR.instances[instance].getData();
            }

            if (!subject.length && !message.length) {
                getTemplate();
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
                }
            });

            $('#defined-product').delegate('.fa-minus-circle', 'click', function() {
                $(this).parent().remove();
            });

            $("[id^='defined-product-']").delegate('.fa-minus-circle', 'click', function() {
                $(this).parent().remove();
            });

            checkDefinedCategories();

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
                        recurrent: $('input[name=recurrent]:checked').val(),
                        special: $('input[name=special]:checked').val(),
                        latest: $('input[name=latest]:checked').val(),
                        popular: $('input[name=popular]:checked').val(),
                        random: $('input[name=random]:checked').val(),
                        random_count: $('input[name=random_count]').val(),
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
                    }
                });

                defined_section_row++;
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
        });

        function checkRandom() {
            if ($('input[name=\'random\']:checked').val() == 1 && $('input[name=\'recurrent\']:checked').val() == 1)
            {
                $('.random').show();
            }
            else
            {
                $('.random').hide();
            }
        }

        function checkDefined() {
            if ($('input[name=\'defined\']:checked').val() == 1 && $('input[name=\'recurrent\']:checked').val() != 1)
            {
                $('.defined-product').show();
            }
            else
            {
                $('.defined-product').hide();
            }
        }

        function checkDefinedCategories() {
            if ($('input[name=\'defined_categories\']:checked').val() == 1)
            {
                $('.defined-categories').show();
            }
            else
            {
                $('.defined-categories').hide();
            }
        }

        function getTemplate() {
            $.ajax({
                type: 'post',
                url: 'index.php?route=ne/schedule/template&token=<?php echo $token; ?>',
                data: {
                    template_id: $('select[name=template_id]').val(),
                    language_id: $('select[name=language_id]').val(),
                    store_id: $('select[name=store_id]').val()
                },
                dataType: 'json',
                success: function(json) {
                    $('input[name=subject]').val(json.subject);
                    for (var instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].setData(json.body);
                    }
                }
            });
        }

        function removeSection(el) {
            $(el).closest('.defined-product').next().remove();
            $(el).closest('.defined-product').remove();

            defined_section_row--;
        }


    //--></script>
    <script type="text/javascript"><!--
        $('.date').datetimepicker({
            pickTime: false
        });
    //--></script>
</div>
<?php echo $footer; ?>