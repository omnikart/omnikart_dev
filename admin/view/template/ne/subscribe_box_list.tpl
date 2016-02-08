<?php
// -----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
// -----------------------------------------------------
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button
					onclick="$('#form').attr('action', '<?php echo $copy; ?>');$('#form').submit();return false;"
					data-toggle="tooltip" title="<?php echo $button_copy; ?>"
					class="btn btn-default">
					<i class="fa fa-files-o"></i>
				</button>
				<a href="<?php echo $insert; ?>" data-toggle="tooltip"
					title="<?php echo $button_insert; ?>" class="btn btn-primary"><i
					class="fa fa-plus"></i></a>
				<button type="submit" form="form" data-toggle="tooltip"
					title="<?php echo $button_delete; ?>" class="btn btn-danger">
					<i class="fa fa-trash-o"></i>
				</button>
			</div>
			<h1><?php echo $heading_title; ?></h1>
		</div>
	</div>
	<div class="container-fluid">
        <?php if ($error_warning) { ?>
            <div class="alert alert-danger">
			<i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
        <?php } ?>
        <?php if ($success) { ?>
            <div class="alert alert-success">
			<i class="fa fa-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
        <?php } ?>
        <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-check-square-o"></i> <?php echo $text_subscribe_boxes; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $delete; ?>" method="post"
					enctype="multipart/form-data" id="form" class="form-horizontal">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td style="width: 1px;" class="text-center"><input
										type="checkbox"
										onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
									<td class="text-left"><?php echo $column_name; ?></td>
									<td class="text-left"><?php echo $column_last_change; ?></td>
									<td class="text-left"><?php echo $column_status; ?></td>
									<td class="text-right"><?php echo $column_actions; ?></td>
								</tr>
							</thead>
							<tbody>
                                <?php if ($subscribe_boxes) { ?>
                                    <?php foreach ($subscribe_boxes as $subscribe_box) { ?>
                                        <tr>
									<td class="text-center">
                                                <?php if (in_array($subscribe_box['subscribe_box_id'], $selected)) { ?>
                                                    <input
										type="checkbox" name="selected[]"
										value="<?php echo $subscribe_box['subscribe_box_id']; ?>"
										checked="checked" />
                                                <?php } else { ?>
                                                    <input
										type="checkbox" name="selected[]"
										value="<?php echo $subscribe_box['subscribe_box_id']; ?>" />
                                                <?php } ?>
                                            </td>
									<td class="text-left"><?php echo $subscribe_box['name']; ?></td>
									<td class="text-left"><?php echo $subscribe_box['datetime']; ?></td>
									<td class="text-left">
                                                <?php if ($subscribe_box['status']) { ?>
                                                    <?php echo $text_enabled; ?>
                                                <?php } else { ?>
                                                    <?php echo $text_disabled; ?>
                                                <?php } ?>
                                            </td>
									<td class="text-right"><a
										href="<?php echo $edit . $subscribe_box['subscribe_box_id']; ?>"
										data-toggle="tooltip" title="<?php echo $button_edit; ?>"
										class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
								</tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
									<td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
								</tr>
                                <?php } ?>
                            </tbody>
						</table>
					</div>
				</form>
				<div class="alert alert-info">
					<i class="fa fa-info-circle"></i> <?php echo $text_layout; ?>
                    <button type="button" class="close"
						data-dismiss="alert">&times;</button>
				</div>
			</div>
		</div>
		<p class="text-center small">Newsletter Enhancements OpenCart Module
			v3.7.2</p>
	</div>
</div>
<?php echo $footer; ?>