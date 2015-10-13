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
                <button type="submit" form="mainform" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
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
                <h3 class="panel-title"><i class="fa fa-ban"></i> <?php echo $text_blacklisted_emails; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
                    <fieldset>
                        <legend><?php echo $text_add_info; ?></legend>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <textarea name="emails" placeholder="" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 text-right">
                                <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <fieldset>
                    <legend><?php echo $text_blacklisted_emails; ?></legend>
                    <div class="well">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label" for="input-email"><?php echo $column_email; ?></label>
                                    <input type="text" name="filter_email" value="<?php echo $filter_email; ?>" placeholder="<?php echo $column_email; ?>" id="input-email" class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label" for="input-date"><?php echo $column_date; ?></label>
                                    <div class="input-group date">
                                        <input type="text" name="filter_date" value="<?php echo $filter_date; ?>" placeholder="<?php echo $column_date; ?>" data-date-format="YYYY-MM-DD" id="input-date" class="form-control" />
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                            </div>
                        </div>
                    </div>
                    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="mainform" class="form-horizontal">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                        <td class="text-left">
                                            <?php if ($sort == 'email') { ?>
                                                <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
                                            <?php } else { ?>
                                                <a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
                                            <?php } ?>
                                        </td>
                                        <td class="text-left">
                                            <?php if ($sort == 'date') { ?>
                                                <a href="<?php echo $sort_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date; ?></a>
                                            <?php } else { ?>
                                                <a href="<?php echo $sort_date; ?>"><?php echo $column_date; ?></a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($blacklisted) { ?>
                                        <?php foreach ($blacklisted as $entry) { ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?php if (in_array($entry['blacklist_id'], $selected)) { ?>
                                                        <input type="checkbox" name="selected[]" value="<?php echo $entry['blacklist_id']; ?>" checked="checked" />
                                                    <?php } else { ?>
                                                        <input type="checkbox" name="selected[]" value="<?php echo $entry['blacklist_id']; ?>" />
                                                    <?php } ?>
                                                </td>
                                                <td class="text-left"><?php echo $entry['email']; ?></td>
                                                <td class="text-left"><?php echo $entry['datetime']; ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
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
                </fieldset>
            </div>
        </div>
        <p class="text-center small">Newsletter Enhancements OpenCart Module v3.7.2</p>
    </div>
    <script type="text/javascript"><!--
        $('#button-filter').on('click', function() {
            var url = 'index.php?route=ne/blacklist&token=<?php echo $token; ?>';

            var filter_email = $('input[name=\'filter_email\']').val();

            if (filter_email) {
                url += '&filter_email=' + encodeURIComponent(filter_email);
            }

            var filter_date = $('input[name=\'filter_date\']').val();

            if (filter_date) {
                url += '&filter_date=' + encodeURIComponent(filter_date);
            }

            location = url;
        });
    //--></script>
    <script type="text/javascript"><!--
        $('.date').datetimepicker({
            pickTime: false
        });
    //--></script>
</div>
<?php echo $footer; ?>