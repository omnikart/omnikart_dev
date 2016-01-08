
        <div id="ne_subscribe<?php echo $module; ?>" class="form-horizontal ne_subscribe">
            <div class="input-group">
                <input type="text" name="ne_email" value="" id="input-subscribe-email" class="form-control" />
								<span class="input-group-btn">
										<a href="#" class="btn btn-primary ne_submit"><?php echo $text_subscribe; ?></a>
                </span>
							</div>
            <?php if ($marketing_list) { ?>
                <div class="form-group ne_list_data">
                    <label class="col-sm-2 control-label"><?php echo $entry_list; ?></label>
                    <div class="col-sm-10">
                        <?php foreach ($marketing_list as $key => $list) { ?>
                            <div class="<?php echo $list_type ? 'radio' : 'checkbox'; ?>">
                                <input class="ne_subscribe_list" id="ne_list<?php echo $key; ?>" name="ne_list[]" type="<?php echo $list_type ? 'radio' : 'checkbox'; ?>" value="<?php echo $key; ?>" /><label for="ne_list<?php echo $key; ?>">&nbsp;<?php echo $list[$language_id]; ?></label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
<script type="text/javascript"><!--
$('#ne_subscribe<?php echo $module; ?> a.ne_submit').click(function(e){
    e.preventDefault();

    var list = $('#ne_subscribe<?php echo $module; ?> .ne_subscribe_list:checked').map(function(i,n) {
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
                $('#ne_subscribe<?php echo $module; ?> input[type="text"]').val('');
                $('#ne_modal<?php echo $module; ?> input[type="email"]').val('');
                $('#ne_subscribe<?php echo $module; ?> .ne_subscribe_list').removeAttr('checked');
            }

            $("#ne_subscribe<?php echo $module; ?> .alert").remove();

            if (data.type == 'success') {
                $('#ne_subscribe<?php echo $module; ?>').prepend('<div class="alert alert-success"><i class="fa fa-info-circle"></i> ' + data.message + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            } else {
                $('#ne_subscribe<?php echo $module; ?>').prepend('<div class="alert alert-danger"><i class="fa fa-info-circle"></i> ' + data.message + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            }
            $("#ne_subscribe<?php echo $module; ?> .alert").delay(3000).slideUp(400, function(){
                $(this).remove();
            });
        } else {
            $('#ne_subscribe<?php echo $module; ?> input[type="text"]:first').focus();
        }
    }, "json");

    return false;
});
//--></script>
