<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------
?>
<div class="clearfix" style="margin-bottom:20px;"></div>
<div class="panel panel-default">
    <?php if ($heading) { ?>
        <div class="panel-heading"><?php echo $heading; ?></div>
    <?php } ?>
    <div class="panel-body">
        <div id="ne_subscribe<?php echo $module; ?>" class="ne_subscribe" style="text-align:center;">
            <a href="#ne_modal<?php echo $module; ?>" data-toggle="modal" class="btn btn-primary ne_submit" style="width:80%;"><?php echo $text_subscribe; ?></a>
        </div>
    </div>
</div>
<div class="ne-bootstrap ne_modal<?php echo $module; ?>" tabindex="-1">
    <div class="fade ne_modal" id="ne_modal<?php echo $module; ?>" tabindex="-1" role="dialog" aria-labelledby="ne_modal<?php echo $module; ?>Label" aria-hidden="true" style="display:none;">
        <div class="modal-dialog" style="max-width:400px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo $heading ? $heading : '&nbsp;'; ?></h4>
                    <a href="#" class="ne_close" data-dismiss="modal" aria-hidden="true"><?php echo $text_close; ?></a>
                </div>
                <div class="modal-body">
                    <?php echo $text; ?>
                    <?php if ($fields > 1) { ?>
                        <div class="form-group">
                            <label for="ne_name"><?php echo $fields == 2 ? $entry_name : $entry_firstname; ?></label>
                            <input type="text" class="form-control" id="ne_name" name="ne_name" />
                        </div>
                    <?php } ?>
                    <?php if ($fields == 3) { ?>
                        <div class="form-group">
                            <label for="ne_lastname"><?php echo $entry_lastname; ?></label>
                            <input type="text" class="form-control" id="ne_lastname" name="ne_lastname" />
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="ne_email"><?php echo $entry_email; ?></label>
                        <input type="email" class="form-control" id="ne_email" name="ne_email">
                    </div>
                    <?php if ($marketing_list) { ?>
                        <label><?php echo $entry_list; ?></label>
                        <div class="form-group">
                            <?php foreach ($marketing_list as $key => $list) { ?>
                                <div class="<?php echo $list_type ? 'radio' : 'checkbox'; ?>">
                                    <input class="ne_subscribe_list" id="ne_list<?php echo $key; ?>" name="ne_list[]" type="<?php echo $list_type ? 'radio' : 'checkbox'; ?>" value="<?php echo $key; ?>"><label for="ne_list<?php echo $key; ?>">&nbsp;<?php echo $list[$language_id]; ?></label>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-primary ne_submit"><?php echo $text_subscribe; ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
jQuery(function ($) {
    $('body').append($('.ne_modal<?php echo $module; ?>'));

    $('#ne_modal<?php echo $module; ?>').modal({
        backdrop: "static",
        keyboard: false,
        show: false
    });

    $('#ne_modal<?php echo $module; ?> a.ne_submit').click(function(e){
        e.preventDefault();

        var list = $('#ne_modal<?php echo $module; ?> .ne_subscribe_list:checked').map(function(i,n) {
            return $(n).val();
        }).get();

        $.post("<?php echo $subscribe; ?>", {
            email: $('#ne_subscribe<?php echo $module; ?> input[name="ne_email"]').val(),
            <?php if ($fields > 1) { ?>name: $('#ne_subscribe<?php echo $module; ?> input[name="ne_name"]').val(), <?php } ?>
            <?php if ($fields == 3) { ?>lastname: $('#ne_subscribe<?php echo $module; ?> input[name="ne_lastname"]').val(), <?php } ?>
            'list[]': list
        }, function(data) {
            if (data) {
                if (data.type == 'success') {
                    $.cookie('ne_subscribed', true, {expires: 365, path: '/'});
                    $('#ne_modal<?php echo $module; ?> input[type="text"]').val('');
                    $('#ne_modal<?php echo $module; ?> input[type="email"]').val('');
                    $('#ne_modal<?php echo $module; ?> .ne_subscribe_list').removeAttr('checked');
                }

                $("#ne_modal<?php echo $module; ?> .alert").remove();

                if (data.type == 'success') {
                    $('#ne_modal<?php echo $module; ?> .modal-body').prepend('<div class="alert alert-success"><i class="fa fa-info-circle"></i> ' + data.message + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                } else {
                    $('#ne_modal<?php echo $module; ?> .modal-body').prepend('<div class="alert alert-danger"><i class="fa fa-info-circle"></i> ' + data.message + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }
                $("#ne_modal<?php echo $module; ?> .alert").delay(3000).slideUp(400, function(){
                    if(data.type == 'success') {
                        $('#ne_modal<?php echo $module; ?>').modal('hide');
                    }
                    $(this).remove();
                });
            } else {
                $('#ne_modal<?php echo $module; ?> input[type="email"]:first').focus();
                $('#ne_modal<?php echo $module; ?> input[type="text"]:first').focus();
            }
        }, "json");

        return false;
    });
});
//--></script>