<?php
/*
 * Author: minhdqa
 * Mail: minhdqa@gmail.com
 */
?>
<?php echo $header;?>
<?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<a href="<?php echo $insert; ?>" data-toggle="tooltip"
					title="<?php echo $button_insert; ?>" class="btn btn-primary"><i
					class="fa fa-plus"></i></a>
				<button type="button" data-toggle="tooltip"
					title="<?php echo $button_delete; ?>" class="btn btn-danger"
					onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-combos').submit() : false;">
					<i class="fa fa-trash-o"></i>
				</button>
			</div>
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
				  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				  <?php } ?>
				</ul>
		</div>
	</div>
	<div class="container-fluid">
      <?php if ($error_warning) { ?>
      <div class="alert alert-danger">
			<i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
      <?php } ?>
      <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-pencil"></i> <?php echo $edit_title; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $delete; ?>" method="post"
					enctype="multipart/form-data" id="form-combos">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td style="width: 1px;" class="text-center"><input
										type="checkbox"
										onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
									<td class="text-left"><?php echo $entry_combo_name; ?></td>
									<td class="text-left"><?php echo $entry_product_name; ?></td>
									<td class="text-left"><?php echo $entry_discount; ?></td>
									<td class="text-left"><?php echo $entry_display_position; ?></td>
									<td class="text-left"><?php echo $entry_priority; ?></td>
									<td></td>
								</tr>
							</thead>
							<tbody>
                <?php if ($combos) { ?>
                <?php foreach ($combos as $combo) { ?>
                <tr>
									<td class="text-center"><?php if (in_array($combo['combo_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]"
										value="<?php echo $combo['combo_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]"
										value="<?php echo $combo['combo_id']; ?>" />
                    <?php } ?></td>
									<td class="text-left"><?php echo $combo['combo_name']; ?></td>
									<td class="text-left">
					<?php foreach ($combo['products'] as $product) {?>
						<ul>
											<li><?php echo $product; ?></li>
										</ul>
					<?php }?>
				  </td>
									<td class="text-left">
					<?php if ($combo['discount_type'] == 'fixed amount') { ?>
						<?php echo $combo['discount_type'].": ".$combo['discount_number']." ".$_SESSION['currency']; ?>
					<?php } else {?>
						<?php echo $combo['discount_type'].": ".$combo['discount_number']."% of 'Price'"; ?>
					<?php }?>
				  </td>
									<td class="text-left"><input type="checkbox"
										name="<?php echo $text_detail_page ?>"
										<?php if ($combo['display_detail']) echo 'checked="checked"'; ?>
										disabled="disabled" /> <?php echo $text_detail_page?>
					<?php if ($combo['category_list']) { ?>
					</br> <label class="control-label"><?php echo $entry_category.": "; ?></label>
										<div class="form-control well well-sm" name="category"
											rows="2" disabled="disabled" style="height: auto">
					<?php foreach ($combo['category_list'] as $category_list) {;?>
						<?php echo $category_list; ?>
						</br>
					<?php }?>
					</div>
					<?php } ?>
				  </td>
									<td class="text-center">
					<?php echo $combo['priority']; ?>
				  </td>
									<td class="text-center"><a href="<?php echo $combo['edit']; ?>"
										data-toggle="tooltip" title="<?php echo $button_edit; ?>"
										class="btn btn-primary"><i class="fa fa-pencil"></i></a> <a
										href="<?php echo $combo['switch']; ?>" data-toggle="tooltip"
										title="<?php echo $combo['title']; ?>"
										<?php if ($combo['title'] == 'Enabled') echo 'class="btn btn-success"'; else echo 'class="btn btn-danger"'?>><i
											class="fa fa-<?php echo $combo['status'];?>"></i></a></td>
								</tr>
                <?php } ?>
				<tr>
									<td class="text-left" colspan="7">
										<ul>
											<li><?php echo $combo['warning']; ?></li>
										</ul>
									</td>
								</tr>
                <?php } else { ?>
                <tr>
									<td class="text-center" colspan="7"><strong><?php echo $text_no_results; ?></strong></td>
								</tr>
                <?php } ?>
              </tbody>
						</table>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>