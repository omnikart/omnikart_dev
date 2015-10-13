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
        <?php if ($success) { ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $entry_heading; ?></h3>
            </div>
            <div class="panel-body">
                <div class="row" style="margin-bottom:20px;">
                    <div class="col-md-2 text-center">
                        <label class="control-label"><?php echo $entry_total_recipients; ?></label>
                        <p class="form-control-static">
                            <span class="label label-default"><?php echo $recipients_total; ?></span>
                        </p>
                    </div>
                    <div class="col-md-2 text-center">
                        <label class="control-label"><?php echo $entry_total_views; ?></label>
                        <p class="form-control-static">
                            <span class="label label-default"><?php echo $views_total; ?></span>
                        </p>
                    </div>
                    <div class="col-md-2 text-center">
                        <label class="control-label text-success"><?php echo $entry_sent; ?></label>
                        <p class="form-control-static text-success">
                            <span class="label label-success"><?php echo $detail['queue'] - $failed_total; ?> <?php echo $text_of; ?> <?php echo $detail['recipients']; ?></span><br/><?php echo ($detail['recipients'] ? floor(($detail['queue'] - $failed_total) / $detail['recipients'] * 100) : 0); ?>%
                        </p>
                    </div>
                    <div class="col-md-2 text-center">
                        <label class="control-label text-success"><?php echo $entry_read; ?></label>
                        <p class="form-control-static text-success">
                            <span class="label label-success"><?php echo $detail['read']; ?> <?php echo $text_of; ?> <?php echo $detail['queue']; ?></span><br/><?php echo ($detail['queue'] ? floor($detail['read'] / $detail['queue'] * 100) : 0); ?>%
                        </p>
                    </div>
                    <div class="col-md-2 text-center">
                        <label class="control-label text-danger"><?php echo $entry_total_failed; ?></label>
                        <p class="form-control-static">
                            <span class="label label-danger"><?php echo $failed_total; ?></span>
                        </p>
                    </div>
                    <div class="col-md-2 text-center">
                        <label class="control-label text-danger"><?php echo $entry_unsubscribe_clicks; ?></label>
                        <p class="form-control-static">
                            <span class="label label-danger"><?php echo $detail['unsubscribe_clicks']; ?></span>
                        </p>
                    </div>
                </div>

                <?php if ($detail['tracks']) { ?>
                    <fieldset>
                        <legend><?php echo $entry_track; ?></legend>
                        <div class="table-responsive">
                            <table class="table table-condensed">
                                <thead>
                                    <tr>
                                        <th class="text-left"><?php echo $entry_url; ?></th>
                                        <th class="text-right"><?php echo $entry_clicks; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($detail['tracks'] as $entry) { ?>
                                        <tr>
                                            <td class="text-left"><a href="<?php echo $entry['url']; ?>" target="_blank"><?php echo $entry['url']; ?></a></td>
                                            <td class="text-right"><span class="label label-default"><?php echo $entry['clicks']; ?></span></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                <?php } ?>
                <fieldset>
                    <legend><?php echo $entry_chart; ?></legend>
                    <div id="chart-views" style="width:100%;height:260px;margin-bottom:20px;"></div>
                </fieldset>
                <?php if ($attachements) { ?>
                    <fieldset>
                        <legend><?php echo $entry_attachements; ?></legend>
                        <?php foreach ($attachements as $key => $attachement) { ?>
                            <?php echo ($key + 1) . '. '; ?><a href="<?php echo $store_url . $attachement['path']; ?>" target="_blank" class="btn btn-link"><i class="fa fa-paperclip"></i> <?php echo $attachement['filename']; ?></a><br/>
                        <?php } ?>
                    </fieldset>
                <?php } ?>
                <fieldset>
                    <legend><?php echo $entry_recipients; ?></legend>
                    <div class="well">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="input-name"><?php echo $column_name; ?></label>
                                    <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $column_name; ?>" id="input-name" class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="input-email"><?php echo $column_email; ?></label>
                                    <input type="text" name="filter_email" value="<?php echo $filter_email; ?>" placeholder="<?php echo $column_email; ?>" id="input-email" class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="input-success"><?php echo $column_success; ?></label>
                                    <select name="filter_success" id="input-success" class="form-control">
                                        <option value=""></option>
                                        <?php if ($filter_success == '1') { ?>
                                            <option value="1" selected="selected"><?php echo $entry_yes; ?></option>
                                        <?php } else { ?>
                                            <option value="1"><?php echo $entry_yes; ?></option>
                                        <?php } ?>
                                        <?php if ($filter_success == '0') { ?>
                                            <option value="0" selected="selected"><?php echo $entry_no; ?></option>
                                        <?php } else { ?>
                                            <option value="0"><?php echo $entry_no; ?></option>
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
                                    <?php if ($sort == 's.views') { ?>
                                        <a href="<?php echo $sort_views; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_views; ?></a>
                                    <?php } else { ?>
                                        <a href="<?php echo $sort_views; ?>"><?php echo $column_views; ?></a>
                                    <?php } ?>
                                </td>
                                <td class="text-right">
                                    <?php if ($sort == 'clicks') { ?>
                                        <a href="<?php echo $sort_clicks; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_clicks; ?></a>
                                    <?php } else { ?>
                                        <a href="<?php echo $sort_clicks; ?>"><?php echo $column_clicks; ?></a>
                                    <?php } ?>
                                </td>
                                <td class="text-right">
                                    <?php if ($sort == 'success') { ?>
                                        <a href="<?php echo $sort_success; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_success; ?></a>
                                    <?php } else { ?>
                                        <a href="<?php echo $sort_success; ?>"><?php echo $column_success; ?></a>
                                    <?php } ?>
                                </td>
                                <td class="text-right"><?php echo $column_actions; ?></td>
                            </thead>
                            <tbody>
                                <?php if ($recipients) { ?>
                                    <?php foreach ($recipients as $recipient) { ?>
                                        <tr>
                                            <td class="text-left"><?php echo isset($recipient['name']) ? $recipient['name'] : ''; ?></td>
                                            <td class="text-left"><?php echo $recipient['email']; ?></td>
                                            <td class="text-right"><?php echo $recipient['views']; ?></td>
                                            <td class="text-right"><?php echo $recipient['clicks']; ?></td>
                                            <td class="text-right">
                                                <?php if ($recipient['success'] == '1') { ?>
                                                    <?php echo $entry_yes; ?>
                                                <?php } else { ?>
                                                    <?php echo $entry_no; ?>
                                                <?php } ?>
                                            </td>
                                            <td align="right">
                                                <a href="<?php echo $detail_link . $recipient['stats_personal_id']; ?>" data-toggle="tooltip" title="<?php echo $button_details; ?>" class="btn btn-primary show-detail"><i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                    </div>
                </fieldset>
            </div>
        </div>
        <p class="text-center small">Newsletter Enhancements OpenCart Module v3.7.2</p>
    </div>

    <div class="modal fade" id="detail-modal" tabindex="-1" role="dialog" aria-labelledby="detail-modal-label" aria-hidden="true">
        <div class="modal-dialog" style="min-width:600px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.js"></script>
    <script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.resize.min.js"></script>

    <script type="text/javascript"><!--
        $('#button-filter').on('click', function() {
            var url = 'index.php?route=ne/stats/detail&token=<?php echo $token; ?>&id=<?php echo $detail["stats_id"]; ?>';

            var filter_name = $('input[name=\'filter_name\']').val();

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }

            var filter_email = $('input[name=\'filter_email\']').val();

            if (filter_email) {
                url += '&filter_email=' + encodeURIComponent(filter_email);
            }

            var filter_success = $('select[name=\'filter_success\']').val();

            if (filter_success) {
                url += '&filter_success=' + encodeURIComponent(filter_success);
            }

            location = url;
        });

        $(function (){
            $.ajax({
                type: 'get',
                url: 'index.php?route=ne/stats/chart&token=<?php echo $token; ?>&id=<?php echo $detail["stats_id"]; ?>',
                dataType: 'json',
                success: function(json) {

                    var option = {
                        shadowSize: 0,
                        colors: ['#1065D2'],
                        bars: {
                            show: true,
                            fill: true,
                            lineWidth: 1
                        },
                        grid: {
                            backgroundColor: '#FFFFFF',
                            hoverable: true
                        },
                        points: {
                            show: false
                        },
                        xaxis: {
                            show: true,
                            ticks: json['xaxis']
                        },
                        yaxis: {
                            minTickSize: 1,
                            tickDecimals: 0
                        }
                    };

                    $.plot('#chart-views', [json['views']], option);

                    $('#chart-views').bind('plothover', function(event, pos, item) {
                        $('.tooltip').remove();

                        if (item) {
                            console.log(pos);
                            console.log(item);

                            $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1] + ' <?php echo $text_views; ?><br/>' + json['days'][item.dataIndex] + '</div></div>').prependTo('body');

                            $('#tooltip').css({
                                position: 'absolute',
                                left: item.pageX - ($('#tooltip').outerWidth() / 2),
                                top: item.pageY - $('#tooltip').outerHeight(),
                                pointer: 'cusror'
                            }).fadeIn('slow');

                            $('#chart-views').css('cursor', 'pointer');
                        } else {
                            $('#chart-views').css('cursor', 'auto');
                        }
                    });
                },
                error: function(xhr, ajaxOptions, thrownError) {
                   alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });

            $('.show-detail').bind('click', function() {
                var url = this.href;

                $('#detail-modal .modal-title').text('<?php echo $entry_track; ?>');
                $('#detail-modal .modal-body').html('<p class="text-center"><i class="fa fa-refresh fa-spin"></i></p>');
                $('#detail-modal').modal('show');

                $.ajax({
                    type: 'get',
                    url: url,
                    dataType: 'html',
                    success: function(data) {
                        $('#detail-modal .modal-body').html(data);
                    }
                });

                return false;
            });
        });
    //--></script>
</div>
<?php echo $footer; ?>