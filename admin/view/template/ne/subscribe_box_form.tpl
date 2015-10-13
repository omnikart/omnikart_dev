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
                <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
                <h3 class="panel-title"><i class="fa fa-check-square-o"></i> <?php echo $text_subscribe_box; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
                    <fieldset>
                        <legend><?php echo $text_settings; ?></legend>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                                <?php if ($error_name) { ?>
                                    <div class="text-danger"><?php echo $error_name; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
                            <div class="col-sm-10">
                                <label class="radio-inline">
                                    <?php if ($status) { ?>
                                        <input type="radio" name="status" value="1" checked="checked" />
                                        <?php echo $text_enabled; ?>
                                    <?php } else { ?>
                                        <input type="radio" name="status" value="1" />
                                        <?php echo $text_enabled; ?>
                                    <?php } ?>
                                </label>
                                <label class="radio-inline">
                                    <?php if (!$status) { ?>
                                        <input type="radio" name="status" value="0" checked="checked" />
                                        <?php echo $text_disabled; ?>
                                    <?php } else { ?>
                                        <input type="radio" name="status" value="0" />
                                        <?php echo $text_disabled; ?>
                                    <?php } ?>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_show_for; ?></label>
                            <div class="col-sm-10">
                                <select name="show_for" class="form-control">
                                    <?php if (!$show_for) { ?>
                                        <option value="1"><?php echo $text_all; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_guests; ?></option>
                                    <?php } else { ?>
                                        <option value="1" selected="selected"><?php echo $text_all; ?></option>
                                        <option value="0"><?php echo $text_guests; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_fields; ?></label>
                            <div class="col-sm-10">
                                <select name="fields" class="form-control">
                                    <?php if ($fields == '1') { ?>
                                        <option value="1" selected="selected"><?php echo $text_only_email; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $text_only_email; ?></option>
                                    <?php } ?>
                                    <?php if ($fields == '2') { ?>
                                        <option value="2" selected="selected"><?php echo $text_email_name; ?></option>
                                    <?php } else { ?>
                                        <option value="2"><?php echo $text_email_name; ?></option>
                                    <?php } ?>
                                    <?php if ($fields == '3') { ?>
                                        <option value="3" selected="selected"><?php echo $text_email_full; ?></option>
                                    <?php } else { ?>
                                        <option value="3"><?php echo $text_email_full; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_type; ?></label>
                            <div class="col-sm-10">
                                <select name="type" class="form-control">
                                    <?php if ($type == '1') { ?>
                                        <option value="1" selected="selected"><?php echo $text_content_box; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $text_content_box; ?></option>
                                    <?php } ?>
                                    <?php if ($type == '2') { ?>
                                        <option value="2" selected="selected"><?php echo $text_modal_popup; ?></option>
                                    <?php } else { ?>
                                        <option value="2"><?php echo $text_modal_popup; ?></option>
                                    <?php } ?>
                                    <?php if ($type == '3') { ?>
                                        <option value="3" selected="selected"><?php echo $text_content_box_to_modal; ?></option>
                                    <?php } else { ?>
                                        <option value="3"><?php echo $text_content_box_to_modal; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_list_type; ?></label>
                            <div class="col-sm-10">
                                <select name="list_type" class="form-control">
                                    <?php if ($list_type == '1') { ?>
                                        <option value="0"><?php echo $text_checkboxes; ?></option>
                                        <option value="1" selected="selected"><?php echo $text_radio_buttons; ?></option>
                                    <?php } else { ?>
                                        <option value="0" selected="selected"><?php echo $text_checkboxes; ?></option>
                                        <option value="1"><?php echo $text_radio_buttons; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="subscribe-modal">
                        <legend><?php echo $text_modal_popup_settings; ?></legend>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-modal-timeout"><span data-toggle="tooltip" title="<?php echo $help_modal_timeout; ?>"><?php echo $entry_modal_timeout; ?></span></label>
                            <div class="col-sm-10 col-md-2">
                                <input type="text" name="modal_timeout" value="<?php echo $modal_timeout; ?>" id="input-modal-timeout" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-repeat-time"><span data-toggle="tooltip" title="<?php echo $help_modal_repeat_time; ?>"><?php echo $entry_modal_repeat_time; ?></span></label>
                            <div class="col-sm-10 col-md-2">
                                <input type="text" name="repeat_time" value="<?php echo $repeat_time; ?>" id="input-repeat-time" class="form-control" />
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="subscribe-modal">
                        <legend><?php echo $text_modal_popup_style; ?></legend>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-modal-bg-color"><?php echo $entry_modal_bg_color; ?></label>
                            <div class="col-sm-10 col-md-4">
                                <div class="input-group color">
                                    <input type="text" name="modal_bg_color" value="<?php echo $modal_bg_color; ?>" id="input-modal-bg-color" class="form-control" />
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-modal-line-color"><?php echo $entry_modal_line_color; ?></label>
                            <div class="col-sm-10 col-md-4">
                                <div class="input-group color">
                                    <input type="text" name="modal_line_color" value="<?php echo $modal_line_color; ?>" id="input-modal-line-color" class="form-control" />
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-modal-heading-color"><?php echo $entry_modal_heading_color; ?></label>
                            <div class="col-sm-10 col-md-4">
                                <div class="input-group color">
                                    <input type="text" name="modal_heading_color" value="<?php echo $modal_heading_color; ?>" id="input-modal-heading-color" class="form-control" />
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <ul class="nav nav-tabs" id="languages">
                        <?php foreach ($languages as $language) { ?>
                            <li><a href="#language-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                        <?php } ?>
                    </ul>
                    <div class="tab-content">
                        <?php foreach ($languages as $language) { ?>
                            <div class="tab-pane" id="language-<?php echo $language['language_id']; ?>">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-heading-<?php echo $language['language_id']; ?>"><?php echo $entry_heading; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="heading[<?php echo $language['language_id']; ?>]" value="<?php echo isset($heading[$language['language_id']]) ? $heading[$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_heading; ?>" id="input-heading-<?php echo $language['language_id']; ?>" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-text-<?php echo $language['language_id']; ?>"><?php echo $entry_text; ?></label>
                                    <div class="col-sm-10">
                                        <textarea name="text[<?php echo $language['language_id']; ?>]" id="input-text-<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($text[$language['language_id']]) ? $text[$language['language_id']] : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>
        <p class="text-center small">Newsletter Enhancements OpenCart Module v3.7.2</p>
    </div>
    <script type="text/javascript" src="view/javascript/ne/ckeditor/ckeditor.js"></script>
    <script type="text/javascript"><!--
        <?php foreach ($languages as $language) { ?>
            CKEDITOR.replace('input-text-<?php echo $language['language_id']; ?>', {
                height:'500'
            });
        <?php } ?>
    //--></script>
    <script type="text/javascript"><!--
        $(function(){
            $('.color').colorpicker({
                'format': 'hex'
            });

            $('select[name=\'type\']').bind('change', function(){
                if ($(this).val() == '1') {
                    $('.subscribe-modal').hide();
                } else {
                    $('.subscribe-modal').show();
                }
            }).trigger('change');

            $('#languages a:first').tab('show');
        });
    //--></script>
</div>
<?php echo $footer; ?>