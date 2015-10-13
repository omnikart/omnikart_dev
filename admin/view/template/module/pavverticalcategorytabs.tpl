<?php
	echo $header;
	echo $column_left;
	$module_row=0;
?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">

				<a class="btn btn-primary" onclick="$('#form').submit();"><?php echo $button_save; ?></a>
				<a class="btn btn-success" onclick="$('#action').val('save-stay');$('#form').submit();"><?php echo $objlang->get('save_stay'); ?></a>
				<a class="btn btn-danger" href="<?php echo $cancel; ?>"><?php echo $button_cancel; ?></a>

			</div>
			<h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div><!-- End div#page-header -->
  <div id="page-content" class="container-fluid">
    <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      <?php } ?>
  <?php if (isset($success) && !empty($success)) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $subheading; ?></h3>
      </div>
      <div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
					<input type="hidden" name="pavverticalcategorytabs_module[action]" id="action" value=""/>

			        <!-- Nav tabs -->
			        <div class="row">
			          <ul class="nav nav-tabs" role="tablist">
			            <li <?php if( $selectedid ==0 ) { ?>class="active" <?php } ?>> <a href="<?php echo $link; ?>"> <span class="fa fa-plus"></span> <?php echo $olang->get('button_module_add');?></a></li>
			            <?php $i=1; foreach( $moduletabs as $key => $module ){ ?>
			            <li role="presentation" <?php if( $module['module_id']==$selectedid ) {?>class="active"<?php } ?>>
			              <a href="<?php echo $link; ?>&module_id=<?php echo $module['module_id']?>" aria-controls="bannermodule-<?php echo $key; ?>"  >
			                <span class="fa fa-pencil"></span> <?php echo $module['name']?>
			               </a>
			              </li>
			            <?php $i++ ;} ?>

			          </ul>
			        </div>
			        <!-- Tab panes -->

						<!-- Modules -->
						<div class="row">
							<div class="col-sm-12">
							<div >
					            <?php $module_row = 1; ?>
					            <?php foreach ($modules as $module) { ?>
					             <?php if( $selectedid ){ ?>
					             <div class="pull-right">
					                <a href="<?php echo $action;?>&delete=1" class="remove btn btn-danger" ><span><i class="fa fa-remove"></i> Delete This</span></a>
					              </div>
					              <?php } ?>
					               <div id="tab-module<?php echo $module_row; ?>">
					                    <ul class="nav nav-tabs" id="language<?php echo $module_row; ?>">
					                      <?php foreach ($languages as $language) { ?>
					                      <li><a href="#tab-module<?php echo $module_row; ?>-language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
					                      <?php } ?>
					                    </ul>
						               	<div class="tab-content">
											<?php foreach ($languages as $language) { ?>
											<div class="tab-pane" id="tab-module<?php echo $module_row; ?>-language<?php echo $language['language_id']; ?>">
												<table class="table">
													<tr>
														<td class="col-sm-2"><?php echo $entry_description; ?></td>
														<td class="col-sm-10"><textarea class="pavo-editor" name="pavverticalcategorytabs_module[<?php echo $module_row; ?>][description][<?php echo $language['language_id']; ?>]" id="input-description-<?php echo $module_row; ?>-language-<?php echo $language['language_id']; ?>"><?php echo isset($module['description'][$language['language_id']]) ? $module['description'][$language['language_id']] : ''; ?></textarea></td>
													</tr>
												</table>
											</div>
											<?php } ?>
										</div>

									<table class="table">
										<tr>
								            <td class="col-sm-2"><?php echo $objlang->get('entry_name'); ?></td>
								            <td class="col-sm-10"><input class="form-control" name="pavverticalcategorytabs_module[<?php echo $module_row; ?>][name]" value="<?php echo isset($module['name'])?$module['name']:''; ?>"/></td>
								          </tr>
								          <tr>
								          <td class="col-sm-2"><?php echo $objlang->get('entry_status'); ?></td>
								          <td class="col-sm-10"><select name="pavverticalcategorytabs_module[<?php echo $module_row; ?>][status]" class="form-control" style="width:10%;">
								            <?php if ( isset($module['status']) && $module['status'] ) { ?>
								            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
								            <option value="0"><?php echo $text_disabled; ?></option>
								            <?php } else { ?>
								            <option value="1"><?php echo $text_enabled; ?></option>
								            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								            <?php } ?>
								          </select></td>
								        </tr>
										<tr>
											<td><?php echo $objlang->get('prefix_class'); ?></td>
											<td>
												<input class="form-control no-width" type="text" name="pavverticalcategorytabs_module[<?php echo $module_row; ?>][prefix_class]" value="<?php echo isset($module['prefix_class'])?$module['prefix_class']:''; ?>" />
											</td>
										</tr>
										<tr>
											<td><?php echo $entry_tabs; ?></td>
											<td>
												<select class="form-control no-width" name="pavverticalcategorytabs_module[<?php echo $module_row; ?>][category_id]">
													<?php foreach ($categories as $category) { ?>
													<?php $category_id = isset($module['category_id'])?$module['category_id']:0;?>
													<?php $selected = ($category['category_id'] == $category_id)?'selected=selected':''?>
													<option value="<?php echo $category['category_id']; ?>" <?php echo $selected;?>><?php echo $category['name']; ?></option>
													<?php } ?>
												</select>
											</td>
										</tr>
										<tr>
											<td><?php echo $objlang->get( 'entry_icon_image' );?></td>
											<td class="col-sm-8">
												<?php $thumb = isset($module['thumb'])?$module['thumb']:$no_image; ?>
												<a href="" id="thumb-image<?php echo $module_row; ?>"data-toggle="image" class="img-thumbnail">
													<img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $no_image; ?>" />
												</a>
												<input type="hidden" name="pavverticalcategorytabs_module[<?php echo $module_row; ?>][image]" value="<?php echo $module['image']; ?>" id="input-image<?php echo $module_row; ?>" />
											</td>
										</tr>
										<tr>
											<td><?php echo $objlang->get( 'entry_size_icon' );?></td>
											<td class="left">
												<input class="form-control no-width" type="text" name="pavverticalcategorytabs_module[<?php echo $module_row; ?>][iwidth]" value="<?php echo isset($module['iwidth'])?$module['iwidth']:'50'; ?>" size="3" /> x
												<input class="form-control no-width" type="text" name="pavverticalcategorytabs_module[<?php echo $module_row; ?>][iheight]" value="<?php echo isset($module['iheight'])?$module['iheight']:'50'; ?>" size="3"/>
											</td>
										</tr>
										<tr>
											<td><?php echo $banner_position; ?></td>
											<td class="left">
												<select class="form-control no-width"name="pavverticalcategorytabs_module[<?php echo $module_row; ?>][banner_position]">
													<?php if($module['banner_position']) { ?>
													<option value="1" selected="selected"><?php echo $text_left; ?></option>
													<option value="0"><?php echo $text_right; ?></option>
													<?php } else { ?>
													<option value="1" ><?php echo $text_left; ?></option>
													<option value="0" selected="selected"><?php echo $text_right; ?></option>
													<?php } ?>
												</select>
											</td>
										</tr>
										<tr>
											<td><?php echo $limit_tabs; ?></td>
											<td class="left">
												<input class="form-control no-width" type="text" name="pavverticalcategorytabs_module[<?php echo $module_row; ?>][limit_tabs]" value="<?php echo isset($module['limit_tabs'])?$module['limit_tabs']:4;?>" size="3"/>
											</td>
										</tr>
										<tr>
											<td><?php echo $entry_dimension; ?></td>
											<td class="left"><input class="form-control no-width"type="text" name="pavverticalcategorytabs_module[<?php echo $module_row; ?>][width]" value="<?php echo isset($module['width'])?$module['width']:'200'; ?>" size="3" /> x
											<input class="form-control no-width"type="text" name="pavverticalcategorytabs_module[<?php echo $module_row; ?>][height]" value="<?php echo isset($module['height'])?$module['height']:'200'; ?>" size="3"/>
											<?php if (isset($error_dimension[$module_row])) { ?>
											<span class="error"><?php echo $error_dimension[$module_row]; ?></span>
											<?php } ?></td>
										</tr>
										<tr>
											<td class="left"><?php echo $entry_carousel; ?></td>
											<td class="left"><input class="form-control no-width"type="text" name="pavverticalcategorytabs_module[<?php echo $module_row; ?>][itemsperpage]" value="<?php echo $module['itemsperpage']; ?>" size="3" /> x
											<input class="form-control no-width"type="text" name="pavverticalcategorytabs_module[<?php echo $module_row; ?>][cols]" value="<?php echo $module['cols']; ?>" size="3"/> x
											<input class="form-control no-width"type="text" name="pavverticalcategorytabs_module[<?php echo $module_row; ?>][limit]" value="<?php echo $module['limit']; ?>" size="3"/>
											<?php if (isset($error_carousel[$module_row])) { ?>
											<span class="error"><?php echo $error_carousel[$module_row]; ?></span>
											<?php } ?></td>
										</tr>
										<tr>
											<td><?php echo $tab_position; ?></td>
											<td class="left">
												<select class="form-control no-width"name="pavverticalcategorytabs_module[<?php echo $module_row; ?>][tab_position]">
													<?php if($module['tab_position']) { ?>
													<option value="1" selected="selected"><?php echo $text_left; ?></option>
													<option value="0"><?php echo $text_right; ?></option>
													<?php } else { ?>
													<option value="1" ><?php echo $text_left; ?></option>
													<option value="0" selected="selected"><?php echo $text_right; ?></option>
													<?php } ?>
												</select>
											</td>
										</tr>
									</table>

								</div>

								<?php $module_row++; ?>
								<?php } ?>
							</div>
						</div> <!-- End DIV CONTENT TAB -->
						</div>
					</div><!-- End div .panel-body -->
				</form>
			</div>

			<!-- end div content form -->

		</div>
	</div><!-- End div#page-content -->

</div><!-- End div#content -->
<script type="text/javascript"><!--
$('.pavo-editor').summernote({
    height: 300
  });
//--></script>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

 
//--></script>
 <script type="text/javascript"><!--
 <?php $module_row = 1; ?>
$('#module li:first-child a').tab('show');
<?php foreach ($modules as $module) { ?>
$('#language<?php echo $module_row; ?> li:first-child a').tab('show');
<?php $module_row++; ?>
<?php } ?>
//--></script>
<?php echo $footer; ?>
