<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-category_wall"
					data-toggle="tooltip" title="<?php echo $button_save; ?>"
					class="btn btn-primary">
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
				<form action="<?php echo $action; ?>" method="post"	enctype="multipart/form-data" id="form-category_wall" class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
						<div class="col-sm-10">
							<select name="category_wall_status" id="input-status" class="form-control">
				                <?php if ($category_wall_status) { ?>
				                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
												<option value="0"><?php echo $text_disabled; ?></option>
				                <?php } else { ?>
				                <option value="1"><?php echo $text_enabled; ?></option>
												<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				                <?php } ?>
              				</select>
						</div>
					</div>
					<?php $cat_row=0; foreach ($category_wall_categories as $key => $category_wall_category) { ?>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-status">Categories</label>
							<div class="col-sm-5">
								<input type="text" name="category_wall_categories[<?php echo $cat_row; ?>][category_id]" id="input-category" class="form-control" value="<?php echo $category_wall_category['category_id']; ?>" />
							</div>
							<div class="col-sm-5">
								<input type="text" name="category_wall_categories[<?php echo $cat_row; ?>][sub_categories]" id="input-category" class="form-control" value="<?php echo $category_wall_category['sub_categories']; ?>" />
							</div>
						</div>
					<?php $cat_row++; } ?>
				</form>
				<button class="btn btn-info" onclick="addcategory();">Add Category</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var cat_row = <?php echo $cat_row; ?>;

	function addcategory(){
		cat_row++;
		html='<div class="form-group">';
		html+='	<label class="col-sm-2 control-label" for="input-status">Categories</label>';
		html+='	<div class="col-sm-5">';
		html+='		<input type="text" name="category_wall_categories['+cat_row+'][category_id]" id="input-category" class="form-control" value="" />';
		html+='	</div>';
		html+='	<div class="col-sm-5">';
		html+='		<input type="text" name="category_wall_categories['+cat_row+'][sub_categories]" id="input-category" class="form-control" value="" />';
		html+='	</div>';
		html+='</div>';
		
		$('#form-category_wall').append(html);
	}

</script>
<?php echo $footer; ?>