<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" id="button-delete" form="form-enquiry" formaction="<?php echo $action; ?>" data-toggle="tooltip" title="Delete Seleted Queries" class="btn btn-danger"><i class="fa fa-times"></i></button>
        <button type="submit" id="button-update" form="form-enquiry" formaction="<?php echo $update; ?>" data-toggle="tooltip" title="Update Seleted Queries" class="btn btn-info"><i class="fa fa-save"></i></button>
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a></div>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
				<?php /*
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                <select name="filter_order_status" id="input-order-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_order_status == '0') { ?>
                  <option value="0" selected="selected"><?php echo $text_missing; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_missing; ?></option>
                  <?php } ?>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $filter_order_status) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
                <input type="text" name="filter_total" value="<?php echo $filter_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-modified"><?php echo $entry_date_modified; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" placeholder="<?php echo $entry_date_modified; ?>" data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
				*/
				?>
        <form method="post" enctype="multipart/form-data" id="form-enquiry">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-right col-sm-2">Name</td>
                  <td class="text-left col-sm-2">Contact Number</td>
                  <td class="text-left">Query Details</td>
                  <td style="width: 1px;" class="text-left"></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($enquiries) { ?>
                <?php foreach ($enquiries as $enquiry) { ?>
                <tr>
                  <td class="text-center"><input type="checkbox" name="selected[]" value="<?php echo $enquiry['id']; ?>"></td>
                  <td class="text-right"><?php echo $enquiry['user_info']['firstname'].' '.$enquiry['user_info']['lastname']; ?></td>
                  <td class="text-right">
                  	<?php echo $enquiry['user_info']['phone']; ?> <br />
                   	<?php echo $enquiry['user_info']['email']; ?> <br />
                   	<?php echo $enquiry['date']; ?>
                  </td>
                  <td><table class="table">
									<tr><td>Product name</td><td>Quantity</td><td>Specifications</td></tr>
                  <?php foreach ($enquiry['query'] as $query) { ?>
                  <tr>
										<td class="text-left"><?php echo $query['name']; ?></td>
										<td class="text-left"><?php echo $query['quantity']; ?></td>
										<td class="text-left"><?php echo $query['specification']; ?></td>
									</tr>
									<?php } ?>
									</table></td>
				<td class="text-left">
					<select name="query[<?php echo $enquiry['id']; ?>][status]">
						<option value="1" <?php echo (1==$enquiry['status'])?'selected':''; ?> >Pending</option>
						<option value="2" <?php echo (2==$enquiry['status'])?'selected':''; ?>>Query Confirmed</option>
						<option value="3" <?php echo (3==$enquiry['status'])?'selected':''; ?>>Product Suggested</option>
						<option value="4" <?php echo (4==$enquiry['status'])?'selected':''; ?>>Product Confirmed</option>
						<option value="5" <?php echo (5==$enquiry['status'])?'selected':''; ?>>Voided</option>						
					</select>
				</td>									
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php //echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
