<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">

    <link rel="stylesheet" media="screen" type="text/css" href="view/javascript/colorpicker/css/colorpicker.css" />
    <script type="text/javascript" src="view/javascript/colorpicker/js/colorpicker.js"></script> 

    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary" onclick="$('#savequit').prop('disabled', true);">
                    <i class="fa fa-save"></i>
                </button>
                <button type="submit" form="form"  data-toggle="tooltip" class="btn btn-danger" onclick="$('#savequit').prop('disabled', false);">
                    <?php echo $text_save_quit;?>
                </button>

                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
            </div>
            <h1><img src="//mmosolution.com/image/mmosolution.com_34.png"> <?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
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
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i><?php echo $text_edit;?></h3>
            </div>
            <div class="panel-body">
                <div class="">
                    <div class="row"> 
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_store; ?></label>
                            <div class="col-sm-10">
                                <select class="form-control" onchange="window.location = 'index.php?route=module/mmos_checkout_onepage&token=<?php echo $token; ?>&store_id=' + $(this).val();">
                                    <?php foreach($stores as $store){ ?>
                                    <option value="<?php echo $store['store_id']; ?>" <?php echo ($store_id == $store['store_id'])? 'selected' : ''; ?>><?php echo $store['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="<?php echo $action; ?>" method="post"  enctype="multipart/form-data" id="form" class="form-horizontal">
                    <input type="hidden" name="savequit" id="savequit" value="savequit" disabled="disabled"/>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-general-setting" data-toggle="tab"><?php echo $tab_gerenal_setting;?></a></li>
                        <li><a href="#tab-layout-setting" data-toggle="tab"><?php echo $tab_layout_setting;?></a></li>
                        <li><a href="#tab-tips-setting" data-toggle="tab"><?php echo $tab_tips_setting;?></a></li>
                        <li><a href="#tab-langs-setting" data-toggle="tab"><?php echo $tab_langguage_setting;?></a></li>
                        <li><a href="#tab-support" data-toggle="tab"><?php echo $tab_support;?></a></li>
                        <li id="mmos-offer"></li>
                        <li class="pull-right"><a  class="link" href="http://www.opencart.com/index.php?route=extension/extension&filter_username=mmosolution" target="_blank" class="text-success"><img src="//mmosolution.com/image/opencart.gif"> More Extension...</a></li>
                        <li class="pull-right"><a  class="text-link"  href="http://mmosolution.com" target="_blank" class="text-success"><img src="//mmosolution.com/image/mmosolution_20x20.gif">More Extension...</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-general-setting">
                            <table class="table  table-hover table-bordered table-striped">
                                <thead  class="bg-danger">
                                        <td class="col-sm-3 text-right">
                                            <div >
                                                <label class="control-label"><?php echo $entry_status;?></label>
                                            </div>

                                        </td>
                                        <td >
                                            <select name="mmos_checkout[status]" class="form-control">
                                                <?php if (!empty($module['status'])) { ?>
                                                <option value="1" selected="selected"><?php echo $text_enabled;?></option>
                                                <option value="0"><?php echo $text_disabled;?></option>
                                                <?php } else { ?>
                                                <option value="1"><?php echo $text_enabled;?></option>
                                                <option value="0" selected="selected"><?php echo $text_disabled;?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                             </thead>
                                    <tbody>
                                    <tr>
                                        <td  class="text-right">
                                            <div>
                                                <label class="control-label"><?php echo $entry_checkout_group;?></label>
                                            </div>

                                            <span class="help-block">
                                                <?php echo htmlspecialchars($help_checkout_group); ?>
                                            </span>
                                        </td>
                                        <td >
                                            <select name="mmos_checkout[default_customer_group]" class="form-control">
                                                <option value="-1"><?php echo $text_none;?></option>
                                                <?php foreach ($customer_groups as $customer_group) { ?>
                                                <option value="<?php echo $customer_group['customer_group_id']; ?>" <?php if ($customer_group['customer_group_id'] == $module['default_customer_group']) echo 'selected="selected"'; ?>><?php echo $customer_group['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="help-block">
                                                <?php echo htmlspecialchars($help_select_group); ?>
                                            </span>
                                            <br>
                                            <?php foreach ($customer_groups as $customer_group) { ?>
                                                <input name = "mmos_checkout[customer_group_access][]" type="checkbox" value="<?php echo $customer_group['customer_group_id']; ?>" <?php if (isset($module['customer_group_access']) && (in_array($customer_group['customer_group_id'],$module['customer_group_access']))) echo 'checked'; ?>> <?php echo $customer_group['name']; ?>, &#09;
																					  <?php } ?>
                                            <br>
                                            <label><?php echo $entry_display_style;?></label>
                                            <div style="display: inline-block;">
                                                <input type="radio"  name="mmos_checkout[customer_group_style]" value='0' <?php if ($module['customer_group_style'] == 0) echo 'checked="checked"'; ?> ><?php echo $text_stack;?>. 
                                                       <input type="radio"  name="mmos_checkout[customer_group_style]" value='1' <?php if ($module['customer_group_style'] == 1) echo 'checked="checked"'; ?>><?php echo $text_select_box;?>.
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" >
                                            <div>
                                                <label class="control-label">
                                                    <?php echo $entry_default_checkout;?>
                                                </label>
                                            </div>
                                        </td>
                                        <td >
                                            <select name="mmos_checkout[default_role]" class="form-control">
                                                <option value="guest" <?php
                                                        if ($module['default_role'] == 'guest') {
                                                        echo 'selected="selected"';
                                                        }
                                                        ?>><?php echo $text_guest;?></option>
                                                <option value="register" <?php
                                                        if ($module['default_role'] == 'register') {
                                                        echo 'selected="selected"';
                                                        }
                                                        ?>><?php echo $text_register;?></option>
                                                <option value="returning-customer" <?php
                                                        if ($module['default_role'] == 'returning-customer') {
                                                        echo 'selected="selected"';
                                                        }
                                                        ?>><?php echo $text_returning;?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  class="text-right">
                                            <div>
                                                <label class="control-label"><?php echo $entry_quick_register;?></label>
                                            </div>

                                            <span class="help-block"><?php echo $help_quick_register;?></span>
                                        </td>
                                        <td >
                                            <?php if (!empty($module['quick_register'])) { ?>
                                            <input type="radio" name="mmos_checkout[quick_register]" value="1" checked="checked"> <?php echo $text_yes;?>
                                            <input type="radio" name="mmos_checkout[quick_register]" value="0"> <?php echo $text_no;?>
                                            <?php } else { ?>
                                            <input type="radio" name="mmos_checkout[quick_register]" value="1"> <?php echo $text_yes;?>
                                            <input type="radio" name="mmos_checkout[quick_register]" value="0" checked="checked"> <?php echo $text_no;?>
                                            <?php } ?>

                                            <p><input type="checkbox" value="1" name="mmos_checkout[register_telephone_require]" <?php
                                                      if (!empty($module['register_telephone_require'])) {
                                                      echo 'checked="checked"';
                                                      }
                                                      ?>/><?php echo $text_register_fone;?></p>
                                            <p><input type="checkbox" value="1" name="mmos_checkout[register_telephone_tax_display]" <?php
                                                      if (!empty($module['register_telephone_tax_display'])) {
                                                      echo 'checked="checked"';
                                                      }
                                                      ?>/><?php echo $text_register_fone_tax;?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" >
                                            <div>
                                                <label class="control-label"><?php echo $entry_quick_guest_checkout;?></label>
                                            </div>

                                            <span class="help-block"><?php echo $help_quick_register;?></span>
                                        </td>
                                        <td >
                                            <?php if (!empty($module['quick_guest_checkout'])) { ?>
                                            <input type="radio" name="mmos_checkout[quick_guest_checkout]" value="1" checked="checked"> <?php echo $text_yes;?>
                                            <input type="radio" name="mmos_checkout[quick_guest_checkout]" value="0"> <?php echo $text_no;?>
                                            <?php } else { ?>
                                            <input type="radio" name="mmos_checkout[quick_guest_checkout]" value="1"> <?php echo $text_yes;?>
                                            <input type="radio" name="mmos_checkout[quick_guest_checkout]" value="0" checked="checked"> <?php echo $text_no;?>
                                            <?php } ?>
                                            <span class="help-block">
                                                <?php echo $help_quick_guest_checkout;?><br>
                                            </span>


                                            <p>
                                                <input type="checkbox" value="1" name="mmos_checkout[guest_telephone_require]" <?php
                                                       if (!empty($module['guest_telephone_require'])) {
                                                       echo 'checked="checked"';
                                                       }
                                                       ?>/>
                                                       <?php echo $text_register_fone;?>
                                            </p>
                                            <p>
                                                <input type="checkbox" value="1" name="mmos_checkout[guest_telephone_tax_display]" <?php
                                                       if (!empty($module['guest_telephone_tax_display'])) {
                                                       echo 'checked="checked"';
                                                       }
                                                       ?>/>
                                                       <?php echo $text_register_fone_tax;?>
                                            </p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td  class="text-right">
                                            <div>
                                                <label class="control-label"><?php echo $entry_button_refresh;?></label>
                                            </div>

                                            <span class="help-block"><?php echo $help_button_refresh;?></span>
                                        </td>
                                        <td >
                                            <?php if (!empty($module['show_refresh_button'])) { ?>
                                            <input type="radio" name="mmos_checkout[show_refresh_button]" value="1" checked="checked"> <?php echo $text_yes;?>
                                            <input type="radio" name="mmos_checkout[show_refresh_button]" value="0"> <?php echo $text_no;?>
                                            <?php } else { ?>
                                            <input type="radio" name="mmos_checkout[show_refresh_button]" value="1"> <?php echo $text_yes;?>
                                            <input type="radio" name="mmos_checkout[show_refresh_button]" value="0" checked="checked"> <?php echo $text_no;?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  class="left">
                                            <?php echo $entry_enable_qtip;?>
                                        </td>
                                        <td  class="left">

                                            <?php if (!empty($module['enable_qtip']) && $module['enable_qtip'] ==1 ) { ?>
                                            <input type="radio" name="mmos_checkout[enable_qtip]" value="1" checked="checked"> <?php echo $text_yes; ?>
                                            <input type="radio" name="mmos_checkout[enable_qtip]" value="0"> <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="mmos_checkout[enable_qtip]" value="1"> <?php echo $text_yes; ?>
                                            <input type="radio" name="mmos_checkout[enable_qtip]" value="0" checked="checked"> <?php echo $text_no; ?>
                                            <?php } ?>



                                        </td>
                                    </tr>
                                    <tr>
                                        <td  class="left">
                                            <?php echo $text_show_product_model;?>
                                        </td>
                                        <td  class="left">

                                            <?php if (!empty($module['enable_pmodel']) && $module['enable_pmodel'] ==1 ) { ?>
                                            <input type="radio" name="mmos_checkout[enable_pmodel]" value="1" checked="checked"> <?php echo $text_yes; ?>
                                            <input type="radio" name="mmos_checkout[enable_pmodel]" value="0"> <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="mmos_checkout[enable_pmodel]" value="1"> <?php echo $text_yes; ?>
                                            <input type="radio" name="mmos_checkout[enable_pmodel]" value="0" checked="checked"> <?php echo $text_no; ?>
                                            <?php } ?>



                                        </td>
                                    </tr>

                                    <tr>
                                        <td  class="left">
                                            <?php echo $text_show_counpon;?>
                                        </td>
                                        <td  class="left">

                                            <?php if (!empty($module['enable_counpon']) && $module['enable_counpon'] ==1 ) { ?>
                                            <input type="radio" name="mmos_checkout[enable_counpon]" value="1" checked="checked"> <?php echo $text_yes; ?>
                                            <input type="radio" name="mmos_checkout[enable_counpon]" value="0"> <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="mmos_checkout[enable_counpon]" value="1"> <?php echo $text_yes; ?>
                                            <input type="radio" name="mmos_checkout[enable_counpon]" value="0" checked="checked"> <?php echo $text_no; ?>
                                            <?php } ?>



                                        </td>
                                    </tr>

                                    <tr>
                                        <td  class="left">
                                            <?php echo $text_show_giftcart;?>
                                        </td>
                                        <td  class="left">

                                            <?php if (!empty($module['enable_giftcart']) && $module['enable_giftcart'] ==1 ) { ?>
                                            <input type="radio" name="mmos_checkout[enable_giftcart]" value="1" checked="checked"> <?php echo $text_yes; ?>
                                            <input type="radio" name="mmos_checkout[enable_giftcart]" value="0"> <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="mmos_checkout[enable_giftcart]" value="1"> <?php echo $text_yes; ?>
                                            <input type="radio" name="mmos_checkout[enable_giftcart]" value="0" checked="checked"> <?php echo $text_no; ?>
                                            <?php } ?>



                                        </td>
                                    </tr>

                                    <tr>
                                        <td  class="left">
                                            <?php echo $entry_calculate_non_address_shipping;?>
                                            <span class="help"><?php echo $help_calculate_non_address_shipping;?></span>
                                        </td>
                                        <td class="left">
                                            <select name="mmos_checkout[calculate_non_address_shipping]">
                                                <?php if (!empty($module['calculate_non_address_shipping'])) { ?>
                                                <option value="1" selected="selected"><?php echo $text_enabled;?></option>
                                                <option value="0"><?php echo $text_disabled;?></option>
                                                <?php } else { ?>
                                                <option value="1"><?php echo $text_enabled;?></option>
                                                <option value="0" selected="selected"><?php echo $text_disabled;?></option>
                                                <?php } ?>
                                            </select><br/>
                                            <span class="help">
                                                <?php echo $text_choose_for_calculate;?>
                                            </span><br/>
                                             <input type="checkbox" value="1" name="mmos_checkout[calculate_shipping_postcode]" <?php
                                                if (!empty($module['calculate_shipping_postcode'])) {
                                                    echo 'checked="checked"';
                                                }
                                                ?>/><?php echo $text_postcode;?><br/>
                                             <input type="checkbox" value="1" name="mmos_checkout[calculate_shipping_country_id]" <?php
                                                if (!empty($module['calculate_shipping_country_id'])) {
                                                    echo 'checked="checked"';
                                                }
                                                ?>/><?php echo $text_country_id;?><br/>
                                             <input type="checkbox" value="1" name="mmos_checkout[calculate_shipping_zone_id]" <?php
                                                if (!empty($module['calculate_shipping_zone_id'])) {
                                                    echo 'checked="checked"';
                                                }
                                                ?>/><?php echo $text_zone_id;?><br/>
                                             <input type="checkbox" value="1" name="mmos_checkout[calculate_shipping_city]" <?php
                                                if (!empty($module['calculate_shipping_city'])) {
                                                    echo 'checked="checked"';
                                                }
                                                ?>/><?php echo $text_city;?><br/>
                                            <span class="help">
                                                <?php echo $entry_warning_when_non_address_shipping;?><br>
                                            </span><br/>
                                            <?php foreach ($languages as $language) { ?>
                                            
                                             <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon1"><img class="left" src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                                                 <input class="form-control" style="width:97%" name="mmos_checkout[warning_non_address_shipping][<?php echo $language['language_id'];?>]" value="<?php echo isset($module['warning_non_address_shipping'][$language['language_id']]) ? $module['warning_non_address_shipping'][$language['language_id']]:'You must apply an address for calculating fee!'?>"/>
                                            </div>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  class="left">
                                            <?php echo $entry_calculate_non_address_payment;?>
                                            <span class="help-block"><?php echo $help_calculate_non_address_payment;?></span>
                                        </td>
                                        <td  class="left">
                                            <select name="mmos_checkout[calculate_non_address_payment]">
                                                <?php if (!empty($module['calculate_non_address_payment'])) { ?>
                                                <option value="1" selected="selected"><?php echo $text_enabled;?></option>
                                                <option value="0"><?php echo $text_disabled;?></option>
                                                <?php } else { ?>
                                                <option value="1"><?php echo $text_enabled;?></option>
                                                <option value="0" selected="selected"><?php echo $text_disabled;?></option>
                                                <?php } ?>
                                            </select><br/>
                                            <span class="help">
                                                <?php echo $text_choose_for_calculate;?>
                                            </span><br/>
                                             <input type="checkbox" value="1" name="mmos_checkout[calculate_payment_postcode]" <?php
                                                if (!empty($module['calculate_payment_postcode'])) {
                                                    echo 'checked="checked"';
                                                }
                                                ?>/><?php echo $text_postcode;?><br/>
                                             <input type="checkbox" value="1" name="mmos_checkout[calculate_payment_country_id]" <?php
                                                if (!empty($module['calculate_payment_country_id'])) {
                                                    echo 'checked="checked"';
                                                }
                                                ?>/><?php echo $text_country_id;?><br/>
                                             <input type="checkbox" value="1" name="mmos_checkout[calculate_payment_zone_id]" <?php
                                                if (!empty($module['calculate_payment_zone_id'])) {
                                                    echo 'checked="checked"';
                                                }
                                                ?>/><?php echo $text_zone_id;?><br/>
                                             <input type="checkbox" value="1" name="mmos_checkout[calculate_payment_city]" <?php
                                                if (!empty($module['calculate_payment_city'])) {
                                                    echo 'checked="checked"';
                                                }
                                                ?>/><?php echo $text_city;?><br/>
                                            <span class="help">
                                                <?php echo $entry_warning_when_non_address_payment;?><br>
                                            </span><br/>
                                            <?php foreach ($languages as $language) { ?>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon1"><img class="left" src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                                                <input class="form-control" style="width:97%" name="mmos_checkout[warning_non_address_payment][<?php echo $language['language_id'];?>]" value="<?php echo isset($module['warning_non_address_payment'][$language['language_id']]) ? $module['warning_non_address_payment'][$language['language_id']]:'You must apply an address for showing payment method !'?>" />
                                            </div>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  class="text-right">
                                            <div>
                                                <label class="control-label"><?php echo $entry_debug_mode;?></label>
                                            </div>

                                            <span class="help-block">
                                                <?php echo $help_debug_mode;?>
                                            </span>
                                        </td>
                                        <td >
                                            <?php if (!empty($module['debug'])) { ?>
                                            <input type="radio" name="mmos_checkout[debug]" value="1" checked="checked"> <?php echo $text_yes;?>
                                            <input type="radio" name="mmos_checkout[debug]" value="0"> <?php echo $text_no;?>
                                            <?php } else { ?>
                                            <input type="radio" name="mmos_checkout[debug]" value="1"> <?php echo $text_yes;?>
                                            <input type="radio" name="mmos_checkout[debug]" value="0" checked="checked"> <?php echo $text_no;?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="tab-layout-setting" >
                            <ul id="layout-setting" class="nav nav-tabs">
                                <li class="active">
                                    <a href="#checkout_theme" data-toggle="tab"><?php echo $text_checkout_theme;?></a>
                                </li>
                                <li>
                                    <a href="#custom_styles" data-toggle="tab"><?php echo $text_custom_style;?></a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="checkout_theme" class="tab-pane active">
                                    <input type="radio" value="standar" name="css[checkout_theme]" id="theme_standar"/><label for="theme_standar"><?php echo $text_standar;?></label>&emsp;
                                    <input type="radio" value="default" name="css[checkout_theme]" id="theme_default"/><label for="theme_default"><?php echo $text_default;?></label>&emsp;
                                    <input type="radio" value="primary" name="css[checkout_theme]" id="theme_primary"/><label for="theme_primary"><?php echo $text_primary;?></label>&emsp;
                                    <input type="radio" value="success" name="css[checkout_theme]" id="theme_success"/><label for="theme_success"><?php echo $text_success;?></label>&emsp;
                                    <input type="radio" value="info" name="css[checkout_theme]" id="theme_info"/><label for="theme_info"><?php echo $text_information;?></label>&emsp;
                                    <input type="radio" value="warning" name="css[checkout_theme]" id="theme_warning"/><label for="theme_warning"><?php echo $text_warning;?></label>&emsp;
                                    <input type="radio" value="danger" name="css[checkout_theme]" id="theme_danger"/><label for="theme_danger"><?php echo $text_danger;?></label>

                                    <div class="checkout_theme" id="theme_standar_setting" style="display: none;">
                                        <img src="view/image/checkout_themes/standar.png"/>
                                    </div>
                                    <div class="checkout_theme" id="theme_default_setting" style="display: none;">
                                        <img src="view/image/checkout_themes/default.png"/>
                                    </div>
                                    <div class="checkout_theme" id="theme_primary_setting" style="display: none;">
                                        <img src="view/image/checkout_themes/primary.png"/>
                                    </div>
                                    <div class="checkout_theme" id="theme_success_setting" style="display: none;">
                                        <img src="view/image/checkout_themes/success.png"/>
                                    </div>
                                    <div class="checkout_theme" id="theme_info_setting" style="display: none;">
                                        <img src="view/image/checkout_themes/info.png"/>
                                    </div>
                                    <div class="checkout_theme" id="theme_warning_setting" style="display: none;">
                                        <img src="view/image/checkout_themes/warning.png"/>
                                    </div>
                                    <div class="checkout_theme" id="theme_danger_setting" style="display: none;">
                                        <img src="view/image/checkout_themes/danger.png"/>
                                    </div>

                                </div>
                                <div id="custom_styles" style="margin-bottom: 200px;" class="tab-pane">
                                    <style>
                                        #custom_styles td:first-child{
                                            width: 1%;
                                        }
                                        #custom_styles td:nth-child(2){
                                            width: 1%;
                                            white-space: nowrap;
                                        }
                                    </style>

                                    <div>
                                        <table class="table table-hover table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <td colspan="3"  class="text-left"><?php echo $text_loading_bar_icon;?></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td  class="text-left" style="width:1%;"><input type="radio" name="css[loading_bar_icon]" id="loading_1" value="loading_1" <?php if (!empty($css['loading_bar_icon']) && $css['loading_bar_icon'] == 'loading_1') echo 'checked="checked"'; ?>/></td>
                                                    <td><label for="loading_1"><?php echo $text_loading_1;?></label> </td>
                                                    <td >
                                                        <img src="view/image/loading_bar_icon/loading_1.gif"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td  class="text-left" style="width:1%;"><input type="radio" name="css[loading_bar_icon]" id="loading_2" value="loading_2" <?php if (!empty($css['loading_bar_icon']) && $css['loading_bar_icon'] == 'loading_2') echo 'checked="checked"'; ?>/></td>
                                                    <td><label for="loading_2"><?php echo $text_loading_2;?></label></td>
                                                    <td>
                                                        <img src="view/image/loading_bar_icon/loading_2.gif"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td  class="text-left" style="width:1%;"><input type="radio" name="css[loading_bar_icon]" id="loading_3" value="loading_3" <?php if (!empty($css['loading_bar_icon']) && $css['loading_bar_icon'] == 'loading_3') echo 'checked="checked"'; ?>/></td>
                                                    <td><label for="loading_3"><?php echo $text_loading_3;?></label></td>
                                                    <td>
                                                        <img src="view/image/loading_bar_icon/loading_3.gif"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td  class="text-left" style="width:1%;"><input type="radio" name="css[loading_bar_icon]" id="loading_4" value="loading_4" <?php if (!empty($css['loading_bar_icon']) && $css['loading_bar_icon'] == 'loading_4') echo 'checked="checked"'; ?>/></td>
                                                    <td><label for="loading_4"><?php echo $text_loading_4;?></label></td>
                                                    <td>
                                                        <img src="view/image/loading_bar_icon/loading_4.gif"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td  class="text-left" style="width:1%;"><input type="radio" name="css[loading_bar_icon]" id="loading_5" value="loading_5" <?php if (!empty($css['loading_bar_icon']) && $css['loading_bar_icon'] == 'loading_5') echo 'checked="checked"'; ?>/></td>
                                                    <td><label for="loading_5"><?php echo $text_loading_5;?></label></td>
                                                    <td>
                                                        <img src="view/image/loading_bar_icon/loading_5.gif"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td  class="text-left" style="width:1%;"><input type="radio" name="css[loading_bar_icon]" id="loading_6" value="loading_6" <?php if (!empty($css['loading_bar_icon']) && $css['loading_bar_icon'] == 'loading_6') echo 'checked="checked"'; ?>/></td>
                                                    <td><label for="loading_6"><?php echo $text_loading_6;?></label></td>
                                                    <td>
                                                        <img src="view/image/loading_bar_icon/loading_6.gif"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td  class="text-left" style="width:1%;"><input type="radio" name="css[loading_bar_icon]" id="loading_7" value="loading_7" <?php if (!empty($css['loading_bar_icon']) && $css['loading_bar_icon'] == 'loading_7') echo 'checked="checked"'; ?>/></td>
                                                    <td><label for="loading_7"><?php echo $text_loading_7;?></label></td>
                                                    <td>
                                                        <img src="view/image/loading_bar_icon/loading_7.gif"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td  class="text-left" style="width:1%;"><input type="radio" name="css[loading_bar_icon]" id="loading_8" value="loading_8" <?php if (!empty($css['loading_bar_icon']) && $css['loading_bar_icon'] == 'loading_8') echo 'checked="checked"'; ?>/></td>
                                                    <td><label for="loading_8"><?php echo $text_loading_8;?></label></td>
                                                    <td>
                                                        <img src="view/image/loading_bar_icon/loading_8.gif"/>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <table class="table table-hover table-striped table-bordered" id="tbl-button-styles">
                                            <thead>
                                                <tr>
                                                    <td colspan="4"  class="text-left"><?php echo $text_button_color_style;?></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <?php if (!empty($css['common_btn_color'])) { ?>
                                                    <td  class="text-left"><input type="checkbox" id="common-button-color" name="css[common_btn_color]" value="<?php echo $css['common_btn_color']; ?>" checked="checked"/></td>
                                                    <td><label for="common-button-color"><?php echo $text_common_button;?></label></td>
                                                    <td>
                                                        <input type="text" class="color-picker" style="background-color:<?php echo $css['common_btn_color']; ?>"/><br><strong><?php echo $css['common_btn_color']; ?></strong>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-warning" type="button"><?php echo $text_common_button_ok;?></button>
                                                        <button type="button" class="btn btn-primary"><?php echo $text_common_button_continue;?></button>
                                                        <button type="button" class="btn btn-primary"><?php echo $text_common_button_login;?></button>
                                                    </td>
                                                    <?php } else { ?>
                                                    <td  class="text-left"><input type="checkbox" id="common-button-color" name="css[common_btn_color]" value="#ffffff"/></td>
                                                    <td><label for="common-button-color"><?php echo $text_common_button;?></label></td>
                                                    <td>
                                                        <input type="text" class="color-picker"/><br><strong></strong>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-warning" type="button"><?php echo $text_common_button_ok;?></button>
                                                        <button type="button" class="btn btn-primary"><?php echo $text_common_button_continue;?></button>
                                                        <button type="button" class="btn btn-primary"><?php echo $text_common_button_login;?></button>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                                <tr>
                                                    <?php if (!empty($css['voucher_coupon_btn_color'])) { ?>
                                                    <td  class="text-left"><input type="checkbox" id="voucher-coupon-button-color" name="css[voucher_coupon_btn_color]" value="<?php echo $css['voucher_coupon_btn_color']; ?>" checked="checked"/></td>
                                                    <td><label for="voucher-coupon-button-color"><?php echo $text_voucher_coupon_button;?></label></td>
                                                    <td>
                                                        <input type="text" class="color-picker" style="background-color: <?php echo $css['voucher_coupon_btn_color']; ?>"/><br><strong><?php echo $css['voucher_coupon_btn_color']; ?></strong>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-warning" type="button"><?php echo $text_voucher_apply_button;?></button>
                                                        <button type="button" class="btn btn-warning"><?php echo $text_coupon_apply_button;?></button>
                                                    </td>
                                                    <?php } else { ?>
                                                    <td  class="text-left"><input type="checkbox" id="voucher-coupon-button-color" name="css[voucher_coupon_btn_color]" value="#ffffff"/></td>
                                                    <td><label for="voucher-coupon-button-color"><?php echo $text_voucher_coupon_button;?></label></td>
                                                    <td>
                                                        <input type="text" class="color-picker"/><br><strong></strong>
                                                    </td>
                                                    <td>

                                                        <button class="btn btn-warning" type="button"><?php echo $text_voucher_apply_button;?></button>
                                                        <button type="button" class="btn btn-warning"><?php echo $text_coupon_apply_button;?></button>
                                                    </td>
                                                    <?php } ?>
                                                </tr>


                                                <tr>
                                                    <?php if (!empty($css['refresh_btn_color'])) { ?>
                                                    <td  class="text-left"><input type="checkbox" id="refresh-button-color"  name="css[refresh_btn_color]" value="<?php echo $css['refresh_btn_color']; ?>" checked="checked"/></td>
                                                    <td><label for="refresh-button-color"><?php echo $text_refresh_button;?></label></td>
                                                    <td>
                                                        <input type="text" class="color-picker" style="background-color: <?php echo $css['refresh_btn_color']; ?>"/><br><strong><?php echo $css['refresh_btn_color']; ?></strong>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-default"><i class="fa fa-refresh"></i></button>
                                                    </td>
                                                    <?php } else { ?>
                                                    <td  class="text-left"><input type="checkbox" id="refresh-button-color"  name="css[refresh_btn_color]" value="#ffffff"/></td>
                                                    <td><label for="refresh-button-color"><?php echo $text_refresh_button;?></label></td>
                                                    <td>
                                                        <input type="text" class="color-picker"/><br><strong></strong>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-default"><i class="fa fa-refresh"></i></button>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                                <tr>
                                                    <?php if (!empty($css['make_order_btn_color'])) { ?>
                                                    <td  class="text-left"><input type="checkbox" id="make-order-button-color"  name="css[make_order_btn_color]" value="<?php echo $css['make_order_btn_color']; ?>" checked="checked"/></td>
                                                    <td><label for="make-order-button-color"><?php echo $text_make_order_button;?></label></td>
                                                    <td>
                                                        <input type="text" class="color-picker" style="background-color: <?php echo $css['make_order_btn_color']; ?>"/><br><strong><?php echo $css['make_order_btn_color']; ?></strong>
                                                    </td>
                                                    <td><button type="button" class="btn btn-primary"><?php echo $text_make_order;?></button></td>
                                                    <?php } else { ?>
                                                    <td  class="text-left"><input type="checkbox" id="make-order-button-color"  name="css[make_order_btn_color]" value="#ffffff"/></td>
                                                    <td><label for="make-order-button-color"><?php echo $text_make_order_button;?></label></td>
                                                    <td>
                                                        <input type="text" class="color-picker"/><br><strong></strong>
                                                    </td>
                                                    <td><button type="button" class="btn btn-primary"><?php echo $text_make_order;?></button></td>
                                                    <?php } ?>
                                                </tr>
                                                <tr>
                                                    <?php if (!empty($css['edit_order_btn_color'])) { ?>
                                                    <td  class="text-left"><input type="checkbox" id="edit-order-button-color"  name="css[edit_order_btn_color]" value="<?php echo $css['edit_order_btn_color']; ?>" checked="checked"/></td>
                                                    <td><label for="edit-order-button-color"><?php echo $text_edit_order_button;?></label></td>
                                                    <td>
                                                        <input type="text" class="color-picker" style="background-color: <?php echo $css['edit_order_btn_color']; ?>"/><br><strong><?php echo $css['edit_order_btn_color']; ?></strong>
                                                    </td>
                                                    <td><button type="button" class="btn btn-default"><i class="fa fa-pencil"></i> <?php echo $text_edit_order;?></button></td>
                                                    <?php } else { ?>
                                                    <td  class="text-left"><input type="checkbox" id="edit-order-button-color"  name="css[edit_order_btn_color]" value="#ffffff"/></td>
                                                    <td><label for="edit-order-button-color"><?php echo $text_edit_order_button;?></label></td>
                                                    <td>
                                                        <input type="text" class="color-picker"/><br><strong></strong>
                                                    </td>
                                                    <td>
                                                        <!--<img src="view/image/checkout_themes/buttons/refresh.png"/>-->
                                                        <button type="button" class="btn btn-default"><i class="fa fa-pencil"></i> <?php echo $text_edit_order;?></button>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <table class="table table-hover table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <td colspan="4"  class="text-left"><?php echo $text_panel_color_style;?></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <?php if (!empty($css['checkout_panel_color'])) { ?>
                                                    <td  class="text-left"><input type="checkbox" name="css[checkout_panel_color]" id="checkout_panel_color" value="<?php echo $css['checkout_panel_color']; ?>" checked="checked"/></td>
                                                    <td><label for="checkout_panel_color"><?php echo $text_checkout_panel;?></label></td>
                                                    <td><input type="text" class="color-picker" style="background-color: <?php echo $css['checkout_panel_color']; ?>"/><br><strong><?php echo $css['checkout_panel_color']; ?></strong></td>
                                                    <td>
                                                        <img src="view/image/checkout_themes/panels/checkout.png"/>
                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading">
                                                                <h1 class="title">
                                                                    <i class="fa fa-cogs" style="font-size: 1.5em;"></i>
                                                                    <strong><?php echo $text_checkout;?></strong>
                                                                </h1>
                                                            </div>
                                                            <div class="panel-body"></div>
                                                        </div>
                                                    </td>
                                                    <?php } else { ?>
                                                    <td  class="text-left"><input type="checkbox" name="css[checkout_panel_color]" id="checkout_panel_color" value="#ffffff"/></td>
                                                    <td><label for="checkout_panel_color"><?php echo $text_checkout_panel;?></label></td>
                                                    <td><input type="text" class="color-picker"/><br><strong></strong></td>
                                                    <td>

                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading">
                                                                <h1 class="title">
                                                                    <i class="fa fa-cogs" style="font-size: 1.5em;"></i>
                                                                    <strong><?php echo $text_checkout;?></strong>
                                                                </h1>
                                                            </div>
                                                            <div class="panel-body"></div>
                                                        </div>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                                <tr>
                                                    <?php if (!empty($css['billing_panel_color'])) { ?>
                                                    <td  class="text-left"><input type="checkbox" name="css[billing_panel_color]" id="billing_panel_color" value="<?php echo $css['billing_panel_color']; ?>" checked="checked"/></td>
                                                    <td><label for="billing_panel_color"><?php echo $text_billing_details_panel;?></label></td>
                                                    <td><input type="text" class="color-picker" style="background-color: <?php echo $css['billing_panel_color']; ?>"/><br><strong><?php echo $css['billing_panel_color']; ?></strong></td>
                                                    <td>
                                                        <div class="panel panel-warning" style="">

                                                            <div class="panel-heading" style="">
                                                                <h3 class="panel-title">
                                                                    <i class="fa fa-credit-card" style="font-size: 1.5em;"></i><i class="fa fa-home" style="font-size: 1.5em;"></i>&nbsp;
                                                                    <strong><?php echo $text_billing_details;?></strong>
                                                                </h3>
                                                            </div>
                                                            <div class="panel-body"></div>
                                                        </div>
                                                    </td>
                                                    <?php } else { ?>
                                                    <td  class="text-left"><input type="checkbox" name="css[billing_panel_color]" id="billing_panel_color" value="#ffffff"/></td>
                                                    <td><label for="billing_panel_color"><?php echo $text_billing_details_panel;?></label></td>
                                                    <td><input type="text" class="color-picker"/><br><strong></strong></td>
                                                    <td>
                                                        <div class="panel panel-warning" style="">
                                                            <div class="panel-heading" style="">
                                                                <h3 class="panel-title">
                                                                    <i class="fa fa-credit-card" style="font-size: 1.5em;"></i><i class="fa fa-home" style="font-size: 1.5em;"></i>&nbsp;
                                                                    <strong><?php echo $text_billing_details;?></strong>
                                                                </h3>
                                                            </div>
                                                            <div class="panel-body"></div>
                                                        </div>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                                <tr>
                                                    <?php if (!empty($css['shipping_panel_color'])) { ?>
                                                    <td  class="text-left"><input type="checkbox" name="css[shipping_panel_color]" id="shipping_panel_color" value="<?php echo $css['shipping_panel_color']; ?>" checked="checked"/></td>
                                                    <td><label for="shipping_panel_color"><?php echo $text_delivery_details_panel;?></label></td>
                                                    <td><input type="text" class="color-picker" style="background-color: <?php echo $css['shipping_panel_color']; ?>"/><br><strong><?php echo $css['shipping_panel_color']; ?></strong></td>
                                                    <td>
                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading" style="">
                                                                <h3 class="panel-title">
                                                                    <i class="fa fa-paper-plane-o" style="font-size: 1.5em;"></i><i class="fa fa-home" style="font-size: 1.5em;"></i>&nbsp;
                                                                    &nbsp;<strong><?php echo $text_delivery_details;?></strong>
                                                                </h3>
                                                            </div>
                                                            <div class="panel-body"></div>
                                                        </div>
                                                    </td>
                                                    <?php } else { ?>
                                                    <td  class="text-left"><input type="checkbox" name="css[shipping_panel_color]" id="shipping_panel_color" value="#ffffff"/></td>
                                                    <td><label for="shipping_panel_color"><?php echo $text_delivery_details_panel;?></label></td>
                                                    <td><input type="text" class="color-picker"/><br><strong></strong></td>
                                                    <td>
                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading" style="">
                                                                <h3 class="panel-title">
                                                                    <i class="fa fa-paper-plane-o" style="font-size: 1.5em;"></i><i class="fa fa-home" style="font-size: 1.5em;"></i>&nbsp;
                                                                    &nbsp;<strong><?php echo $text_delivery_details;?></strong>
                                                                </h3>
                                                            </div>
                                                            <div class="panel-body"></div>
                                                        </div>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                                <tr>
                                                    <?php if (!empty($css['shipping_method_panel_color'])) { ?>
                                                    <td  class="text-left"><input type="checkbox" name="css[shipping_method_panel_color]" id="shipping_method_panel_color" value="<?php echo $css['shipping_method_panel_color']; ?>" checked="checked"/></td>
                                                    <td><label for="shipping_method_panel_color"><?php echo $text_delivery_method_panel;?></label></td>
                                                    <td><input type="text" class="color-picker" style="background-color: <?php echo $css['shipping_method_panel_color']; ?>"/><br><strong><?php echo $css['shipping_method_panel_color']; ?></strong></td>
                                                    <td>
                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading" >
                                                                <h3 class="panel-title">
                                                                    <i class="fa fa-paper-plane-o" style="font-size: 1.5em;"></i>&nbsp;
                                                                    <strong><?php echo $text_delivery_method;?></strong>
                                                                </h3>
                                                            </div>
                                                            <div class="panel-body"></div>
                                                        </div>
                                                    </td>
                                                    <?php } else { ?>
                                                    <td  class="text-left"><input type="checkbox" name="css[shipping_method_panel_color]" id="shipping_method_panel_color" value="#ffffff"/></td>
                                                    <td><label for="shipping_method_panel_color"><?php echo $text_delivery_method_panel;?></label></td>
                                                    <td><input type="text" class="color-picker"/><br><strong></strong></td>
                                                    <td>
                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading" >
                                                                <h3 class="panel-title">
                                                                    <i class="fa fa-paper-plane-o" style="font-size: 1.5em;"></i>&nbsp;
                                                                    <strong><?php echo $text_delivery_method;?></strong>
                                                                </h3>
                                                            </div>
                                                            <div class="panel-body"></div>
                                                        </div>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                                <tr>
                                                    <?php if (!empty($css['payment_method_panel_color'])) { ?>
                                                    <td  class="text-left"><input type="checkbox" name="css[payment_method_panel_color]" id="payment_method_panel_color" value="<?php echo $css['payment_method_panel_color']; ?>" checked="checked"/></td>
                                                    <td><label for="payment_method_panel_color"><?php echo $text_payment_method_panel;?></label></td>
                                                    <td><input type="text" class="color-picker" style="background-color: <?php echo $css['payment_method_panel_color']; ?>"/><br><strong><?php echo $css['payment_method_panel_color']; ?></strong></td>
                                                    <td>
                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading" style="">
                                                                <h3 class="panel-title">
                                                                    <i class="fa fa-credit-card" style="font-size: 1.5em;"></i>&nbsp;
                                                                    <strong><?php echo $text_payment_method;?></strong>
                                                                </h3>
                                                            </div>
                                                            <div class="panel-body"></div>
                                                        </div>
                                                    </td>
                                                    <?php } else { ?>
                                                    <td  class="text-left"><input type="checkbox" name="css[payment_method_panel_color]" id="payment_method_panel_color" value="#ffffff"/></td>
                                                    <td><label for="payment_method_panel_color"><?php echo $text_payment_method_panel;?></label></td>
                                                    <td><input type="text" class="color-picker"/><br><strong></strong></td>
                                                    <td>
                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading" style="">
                                                                <h3 class="panel-title">
                                                                    <i class="fa fa-credit-card" style="font-size: 1.5em;"></i>&nbsp;
                                                                    <strong><?php echo $text_payment_method;?></strong>
                                                                </h3>
                                                            </div>
                                                            <div class="panel-body"></div>
                                                        </div>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                                <tr>
                                                    <?php if (!empty($css['cart_order_panel_color'])) { ?>
                                                    <td  class="text-left"><input type="checkbox" name="css[cart_order_panel_color]" id="cart_order_panel_color" value="<?php echo $css['cart_order_panel_color']; ?>" checked="checked"/></td>
                                                    <td><label for="cart_order_panel_color"><?php echo $text_cart_order_panel;?></label></td>
                                                    <td><input type="text" class="color-picker" style="background-color: <?php echo $css['cart_order_panel_color']; ?>"/><br><strong><?php echo $css['cart_order_panel_color']; ?></strong></td>
                                                    <td>
                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading" style="">
                                                                <h3 class="panel-title">
                                                                    <div >
                                                                        <i class="fa fa-shopping-cart" style="font-size: 1.5em;"></i>&nbsp;<strong><?php echo $text_shopping_cart;?></strong>  
                                                                    </div>
                                                                </h3>
                                                            </div>
                                                            <div class="panel-body"></div>
                                                        </div>
                                                    </td>
                                                    <?php } else { ?>
                                                    <td  class="text-left"><input type="checkbox" name="css[cart_order_panel_color]" id="cart_order_panel_color" value="#ffffff"/></td>
                                                    <td><label for="cart_order_panel_color"><?php echo $text_cart_order_panel;?></label></td>
                                                    <td><input type="text" class="color-picker"/><br><strong></strong></td>
                                                    <td>
                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading" style="">
                                                                <h3 class="panel-title">
                                                                    <div >
                                                                        <i class="fa fa-shopping-cart" style="font-size: 1.5em;"></i>&nbsp;<strong><?php echo $text_shopping_cart;?></strong>  
                                                                    </div>
                                                                </h3>
                                                            </div>
                                                            <div class="panel-body"></div>
                                                        </div>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <script>
                                            $('.color-picker').each(function () {
                                                var that = this;
                                                $(this).ColorPicker({
                                                    color: $(that).closest('tr').find('input[type=checkbox]').val(),
                                                    onShow: function (colpkr) {
                                                        $(colpkr).fadeIn(500);
                                                        return false;
                                                    },
                                                    onHide: function (colpkr) {
                                                        $(colpkr).fadeOut(500);
                                                        return false;
                                                    },
                                                    onChange: function (hsb, hex, rgb) {
                                                        $(that).css('backgroundColor', '#' + hex);
                                                        $(that).siblings('strong').html('#' + hex);
                                                        $(that).closest('tr').find('input[type=checkbox]').val('#' + hex);
                                                    }
                                                });
                                            });
                                            //                                      
                                        </script>



                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="tab-tips-setting" >
                            <ul id="tips-languages" class="nav nav-tabs">
                                <?php $i = 0; ?>
                                <?php foreach ($languages as $language) { ?>
                                <li class="<?php if ($i == 0) echo 'active' ?> " >
                                    <a href="#tips-language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
                                </li>
                                <?php $i++; ?>
                                <?php } ?>
                            </ul>
                            <div class="tab-content">
                                <?php $i = 0; ?>
                                <?php foreach ($languages as $language) { ?>

                                <div id="tips-language<?php echo $language['language_id']; ?>" class="tab-pane <?php if ($i == 0) echo 'active' ?>">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td class="col-sm-2 text-right">
                                                    <div class="">
                                                        <label class="control-label"><?php echo $text_checkout_tips;?></label>
                                                    </div>

                                                    <span class="help-block"><?php echo $text_help_tips;?></span>
                                                </td>
                                                <td><textarea class="form-control" id='mmos_checkout_tip<?php echo $language['language_id']; ?>' name="tips[checkout_tip][<?php echo $language['language_id']; ?>]"  class="mmos_checkout_tip" rows="5" style="width: 100%;"><?php if (!empty($tips['checkout_tip'][$language['language_id']])) echo $tips['checkout_tip'][$language['language_id']]; ?></textarea></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">
                                                    <div>
                                                        <label class="control-label"><?php echo $text_payment_address_tips;?></label>
                                                    </div>

                                                    <span class="help-block"><?php echo $text_help_tips;?></span>
                                                </td>
                                                <td><textarea class="form-control" rows="5" name="tips[payment_address_tip][<?php echo $language['language_id']; ?>]" style="width: 100%;"><?php if (!empty($tips['payment_address_tip'][$language['language_id']])) echo $tips['payment_address_tip'][$language['language_id']]; ?></textarea></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">
                                                    <div>
                                                        <label class="control-label"><?php echo $text_shipping_address_tips;?></label>
                                                    </div>

                                                    <span class="help-block"><?php echo $text_help_tips;?></span>
                                                </td>
                                                <td><textarea class="form-control" rows="5" name="tips[shipping_address_tip][<?php echo $language['language_id']; ?>]" style="width: 100%;"><?php if (!empty($tips['shipping_address_tip'][$language['language_id']])) echo $tips['shipping_address_tip'][$language['language_id']]; ?></textarea></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">
                                                    <div>
                                                        <label class="control-label"><?php echo $text_shipping_method_tips;?></label>
                                                    </div>

                                                    <span class="help-block"><?php echo $text_help_tips;?></span>
                                                </td>
                                                <td><textarea class="form-control" rows="5" name="tips[shipping_method_tip][<?php echo $language['language_id']; ?>]" style="width: 100%;"><?php if (!empty($tips['shipping_method_tip'][$language['language_id']])) echo $tips['shipping_method_tip'][$language['language_id']]; ?></textarea></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">
                                                    <div>
                                                        <label class="control-label"><?php echo $text_payment_method_tips;?></label>
                                                    </div>

                                                    <span class="help-block"><?php echo $text_help_tips;?></span>
                                                </td>
                                                <td><textarea class="form-control" rows="5" name="tips[payment_method_tip][<?php echo $language['language_id']; ?>]" style="width: 100%;"><?php if (!empty($tips['payment_method_tip'][$language['language_id']])) echo $tips['payment_method_tip'][$language['language_id']]; ?></textarea></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <?php $i++; ?>
                                <?php } ?>
                            </div>


                        </div>

                        <div class="tab-pane" id="tab-langs-setting" >
                            <ul id="langs-languages" class="nav nav-tabs">
                                <?php $i = 0; ?>
                                <?php foreach ($languages as $language) { ?>
                                <li class="<?php if ($i == 0) echo 'active' ?> " >
                                    <a href="#langs-language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
                                </li>
                                <?php $i++; ?>
                                <?php } ?>
                            </ul>
                            <div class="tab-content">
                                <?php $i = 0; ?>
                                <?php foreach ($languages as $language) { ?>

                                <div id="langs-language<?php echo $language['language_id']; ?>" class="tab-pane <?php if ($i == 0) echo 'active' ?>">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td class="col-sm-2 text-right">
                                                    <div class="">
                                                        <label class="control-label"><?php echo $text_text_make_order_button;?></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="langs[make_order_button][<?php echo $language['language_id']; ?>]"  value="<?php if (!empty($langs['make_order_button'][$language['language_id']])) echo $langs['make_order_button'][$language['language_id']]; ?>"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-sm-2 text-right">
                                                    <div class="">
                                                        <label class="control-label"><?php echo $text_text_edit_order_button;?></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="langs[edit_order_button][<?php echo $language['language_id']; ?>]"  value="<?php if (!empty($langs['edit_order_button'][$language['language_id']])) echo $langs['edit_order_button'][$language['language_id']]; ?>"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-sm-2 text-right">
                                                    <div class="">
                                                        <label class="control-label"><?php echo $text_text_no_shipping_button;?></label>
                                                    </div>
                                                    <div class="help-block">
                                                        <?php echo $text_help_tips;?>.
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="langs[no_shipping_required][<?php echo $language['language_id']; ?>]"  value="<?php if (!empty($langs['no_shipping_required'][$language['language_id']])) echo $langs['no_shipping_required'][$language['language_id']]; ?>"/>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <?php $i++; ?>
                                <?php } ?>
                            </div>


                        </div>
                        <div class="tab-pane" id="tab-support" >
                            <div class="panel">
                                <div class=" clearfix">
                                    <div class="panel-body">
                                        <h4> About <?php echo $heading_title; ?></h4>
                                        <h5>Installed Version: V.<?php echo $MMOS_version; ?> </h5>
                                        <h5>Latest version: <span id="mmos_latest_version"><a href="http://mmosolution.com/index.php?route=product/search&search=<?php echo trim(strip_tags($heading_title)); ?>" target="_blank">Unknown -- Check</a></span></h5>
                                        <hr>
                                        <h4>About Author</h4>
                                        <div id="contact-infor">
                                            <i class="fa fa-envelope-o"></i> <a href="mailto:support@mmosolution.com?Subject=<?php echo trim(strip_tags($heading_title)).' OC '.VERSION; ?>" target="_top">support@mmosolution.com</a></br>
                                            <i class="fa fa-globe"></i> <a href="http://mmosolution.com" target="_blank">http://mmosolution.com</a> </br>
                                            <i class="fa fa-ticket"></i> <a href="http://mmosolution.com/support/" target="_blank">Open Ticket</a> </br>
                                            <br>
                                            <h4>Our on Social</h4>
                                            <a href="http://www.facebook.com/mmosolution" target="_blank"><i class="fa fa-2x fa-facebook-square"></i></a>
                                            <a class="text-success" href="http://plus.google.com/+Mmosolution" target="_blank"><i class="fa  fa-2x fa-google-plus-square"></i></a>
                                            <a class="text-warning" href="http://mmosolution.com/mmosolution_rss.rss" target="_blank"><i class="fa fa-2x fa-rss-square"></i></a>
                                            <a href="http://twitter.com/mmosolution" target="_blank"><i class="fa fa-2x fa-twitter-square"></i></a>
                                            <a class="text-danger" href="http://www.youtube.com/mmosolution" target="_blank"><i class="fa fa-2x fa-youtube-square"></i></a>
                                        </div>
                                        <div id="relate-products">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </form>

            </div>
        </div>
    </div>

</div>



<script type="text/javascript" src="//mmosolution.com/support.js"></script>
<script type="text/javascript">
    var productcode = '<?php echo $MMOS_code_id ;?>';
            <?php foreach ($languages as $language) { ?>
            //        $('#mmos_checkout_tip<?php echo $language['language_id']; ?>').summernote({height: 200});
            <?php } ?></script>
<script type="text/javascript">
            <?php if (empty($css['checkout_theme']) || $css['checkout_theme'] == 'standar') {?>
            $('#theme_standar').prop('checked', true);
            <?php } else { ?>
            $('#theme_<?php echo $css['checkout_theme']; ?>').prop('checked', true);
            <?php } ?>
            $('#checkout_theme input[name=\'css[checkout_theme]\']').bind('change', function () {
        $('#checkout_theme div.checkout_theme').hide();
        $('#' + this.id + '_setting').show();
    });
    $('#checkout_theme input[name=\'css[checkout_theme]\']:checked').trigger('change');
</script>


<?php echo $footer; ?>
