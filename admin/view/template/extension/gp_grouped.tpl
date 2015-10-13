<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-gp" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-gp" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $entry_image_thumb_size; ?></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-sm-6<?php echo $error_image_thumb_width; ?>">
                  <input type="text" name="gp_grouped_image_thumb_width" value="<?php echo $gp_grouped_image_thumb_width; ?>" placeholder="<?php echo $entry_width; ?>" class="form-control" />
                </div>
                <div class="col-sm-6<?php echo $error_image_thumb_height; ?>">
                  <input type="text" name="gp_grouped_image_thumb_height" value="<?php echo $gp_grouped_image_thumb_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                </div>
              </div>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $entry_image_popup_size; ?></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-sm-6<?php echo $error_image_popup_width; ?>">
                  <input type="text" name="gp_grouped_image_popup_width" value="<?php echo $gp_grouped_image_popup_width; ?>" placeholder="<?php echo $entry_width; ?>" class="form-control" />
                </div>
                <div class="col-sm-6<?php echo $error_image_popup_height; ?>">
                  <input type="text" name="gp_grouped_image_popup_height" value="<?php echo $gp_grouped_image_popup_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                </div>
              </div>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $entry_image_added_size; ?></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-sm-6<?php echo $error_image_added_width; ?>">
                  <input type="text" name="gp_grouped_image_added_width" value="<?php echo $gp_grouped_image_added_width; ?>" placeholder="<?php echo $entry_width; ?>" class="form-control" />
                </div>
                <div class="col-sm-6<?php echo $error_image_added_height; ?>">
                  <input type="text" name="gp_grouped_image_added_height" value="<?php echo $gp_grouped_image_added_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for=""><span data-toggle="tooltip" title="<?php echo $help_image_child_size; ?>"><?php echo $entry_image_child_size; ?></span></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-sm-6">
                  <input type="text" name="gp_grouped_image_child_width" value="<?php echo $gp_grouped_image_child_width; ?>" placeholder="<?php echo $entry_width; ?>" class="form-control" />
                </div>
                <div class="col-sm-6">
                  <input type="text" name="gp_grouped_image_child_height" value="<?php echo $gp_grouped_image_child_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for=""><span data-toggle="tooltip" title="<?php echo $help_add_child_image; ?>"><?php echo $entry_add_child_image; ?></span></label>
            <div class="col-sm-10">
              <select name="gp_grouped_add_child_image" class="form-control">
                <?php if ($gp_grouped_add_child_image) { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for=""><span data-toggle="tooltip" title="<?php echo $help_add_child_images; ?>"><?php echo $entry_add_child_images; ?></span></label>
            <div class="col-sm-10">
              <select name="gp_grouped_add_child_images" class="form-control">
                <?php if ($gp_grouped_add_child_images) { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for=""><span data-toggle="tooltip" title="<?php echo $help_add_child_description; ?>"><?php echo $entry_add_child_description; ?></span></label>
            <div class="col-sm-10">
              <select name="gp_grouped_add_child_description" class="form-control">
                <?php if ($gp_grouped_add_child_description) { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for=""><?php echo $entry_child_info; ?></label>
            <div class="col-sm-10">
              <?php foreach ($get_child_infos as $value) { ?>
              <label class="checkbox-inline">
                <?php if (in_array($value, $gp_grouped_child_info)) { ?>
                <input type="checkbox" name="gp_grouped_child_info[]" value="<?php echo $value; ?>" checked="checked" /> <?php echo $value; ?>
                <?php } else { ?>
                <input type="checkbox" name="gp_grouped_child_info[]" value="<?php echo $value; ?>" /> <?php echo $value; ?>
                <?php } ?>
              </label>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for=""><?php echo $entry_child_attribute; ?></label>
            <div class="col-sm-10">
              <select name="gp_grouped_child_attribute" class="form-control">
                <?php if ($gp_grouped_child_attribute) { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for=""><span data-toggle="tooltip" title="<?php echo $help_child_nocart; ?>"><?php echo $entry_child_nocart; ?></span></label>
            <div class="col-sm-10">
              <select name="gp_grouped_child_nocart" class="form-control">
                <?php if ($gp_grouped_child_nocart) { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>