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
                <a href="<?php echo $refresh; ?>" data-toggle="tooltip" title="<?php echo $button_check; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></a>
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
                <h3 class="panel-title"><i class="fa fa-cloud-download"></i> <?php echo $text_check_updates; ?></h3>
            </div>
            <div class="panel-body">
                <div class="alert alert-info">
                    <i class="fa fa-exclamation-circle"></i> <?php echo $content; ?>
                </div>
            </div>
        </div>
        <p class="text-center small">Newsletter Enhancements OpenCart Module v3.7.2</p>
    </div>
</div>
<?php echo $footer; ?>