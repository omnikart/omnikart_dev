<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-payment-term"
					formaction="<?php echo $add; ?>" data-toggle="tooltip"
					title="<?php echo $button_add; ?>" class="btn btn-primary">
					<i class="fa fa-plus"></i>
				</button>
				<button type="submit" form="form-payment-term"
					formaction="<?php echo $delete; ?>" data-toggle="tooltip"
					title="<?php echo $button_delete; ?>" class="btn btn-danger"
					onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-unit-class').submit() : false;">
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
    <?php if ($success) { ?>
    <div class="alert alert-success">
			<i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
    <?php } ?>
    <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
			</div>
			<div class="panel-body">
				<form method="post" enctype="multipart/form-data"
					id="form-payment-term">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td style="width: 1px;" class="text-center"><input
										type="checkbox"
										onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
									<td><?php echo $column_title; ?></td>
									<td><?php echo $column_sort_order; ?></td>
								</tr>
							</thead>
							<tbody>
                <?php $term_row = 0; if ($payment_terms) { ?>
                <?php foreach ($payment_terms as $term) { ?>
                <tr>
									<td class="text-center"><?php if (in_array($term['payment_term_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]"
										value="<?php echo $term['payment_term_id']; ?>"
										checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]"
										value="<?php echo $term['payment_term_id']; ?>" />
                    <?php } ?></td>
									<td><input type="text"
										name="terms[<?php echo $term_row; ?>][name]"
										value="<?php echo $term['name']; ?>" /></td>
									<td><input type="text"
										name="terms[<?php echo $term_row; ?>][sort_order]"
										value="<?php echo $term['sort_order']; ?>"></td>
								</tr>
                <?php $term_row++;} ?>
                <?php } ?>
     			<?php for ($i=0;$i<10;$i++) { ?>           
                <tr>
									<td class="text-center"></td>
									<td><input type="text" class="form-control"
										name="termsnew[<?php echo $term_row; ?>][name]" value="" /></td>
									<td><input type="text" class="form-control"
										name="termsnew[<?php echo $term_row; ?>][sort_order]" value="" /></td>
								</tr>
                <?php $term_row++;} ?>
              </tbody>
							<tfoot>

							</tfoot>
						</table>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>