<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-blog_tag" data-toggle="tooltip"
					title="<?php echo $button_save; ?>" class="btn btn-primary">
					<i class="fa fa-save"></i>
				</button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip"
					title="<?php echo $button_cancel; ?>" class="btn btn-default"><i
					class="fa fa-reply"></i></a>
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
					<i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post"
					enctype="multipart/form-data" id="form-blog_tag"
					class="form-horizontal">

					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
						<div class="col-sm-10">
							<input type="text" name="name" value="<?php echo $name; ?>"
								placeholder="<?php echo $entry_name; ?>" id="input-name"
								class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-title"><?php echo $entry_title; ?></label>
						<div class="col-sm-10">
							<input type="text" name="title" value="<?php echo $title; ?>"
								placeholder="<?php echo $entry_title; ?>" id="input-title"
								class="form-control" />
              <?php if ($error_title) { ?>
              <div class="text-danger"><?php echo $error_title; ?></div>
              <?php } ?>
            </div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-titleicon"><?php echo $entry_titleicon; ?></label>
						<div class="col-sm-10">
							<input type="text" name="titleicon"
								value="<?php echo $titleicon; ?>"
								placeholder="<?php echo $entry_titleicon; ?>"
								id="input-titleicon" class="form-control" />
              <?php if ($error_titleicon) { ?>
              <div class="text-danger"><?php echo $error_titleicon; ?></div>
              <?php } ?>
            </div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-titleicon"><?php echo $entry_limit; ?></label>
						<div class="col-sm-10">
							<input type="text" name="limit" value="<?php echo $limit; ?>"
								placeholder="<?php echo $entry_limit; ?>" id="input-limit"
								class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_layout; ?></label>
						<div class="col-sm-10">
							<select name="tag_layout" id="input-tag-layout"
								class="form-control">
                <?php if ($tag_layout == 'flat') { ?>
                <option value="flat" selected="selected"><?php echo $text_layout_flat ?></option>
								<option value="rotation"><?php echo $text_layout_rotation; ?></option>
                <?php } else { ?>
                <option value="flat"><?php echo $text_layout_flat; ?></option>
								<option value="rotation" selected="selected"><?php echo $text_layout_rotation; ?></option>
                <?php } ?>
              </select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
						<div class="col-sm-10">
							<select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
								<option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
								<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-custom_style"><?php echo $entry_custom_style; ?></label>
						<div class="col-sm-10">
							<textarea name="custom_style" id="input-custom_style" cols="30"
								rows="10" class="form-control">
                <?php echo $custom_style; ?>
              </textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-custom_script"><?php echo $entry_custom_script; ?></label>
						<div class="col-sm-10">
							<textarea name="custom_script" id="input-custom_script" cols="30"
								rows="10" class="form-control"><?php echo $custom_script; ?></textarea>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>