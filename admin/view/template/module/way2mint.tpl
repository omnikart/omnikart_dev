<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-account" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
      	<ul class="nav nav-tabs">
	        <li class="active"><a href="#templates" data-toggle="tab">Templates</a></li>
	        <li><a href="#general" data-toggle="tab">General</a></li>
       </ul>
       <div class="tab-content">
      		<div id="templates" class="tab-pane active">
		      	<div class="table-responsive">
		      		<table class="table table-bordered table-hover">
		      			<thead>
		      				<tr>
		      					<td class="text-center" style="width: 1px;"><input onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" type="checkbox"></td>
		      					<td>Id</td>
		      					<td>User id</td>
		      					<td>Template Name</td>
		      					<td>Content</td>
		      					<td>Langauge</td>
		      					<td>Status</td>
		      				</tr>
		      			</thead>
		      			<tbody>
							<?php foreach ($output['content'] as $template) { ?>
								<td class="text-center" style="width: 1px;"><input onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" type="checkbox"></td>
		      					<td><?php echo $template['id']; ?></td>
		      					<td><?php echo $template['user_id']; ?></td>
		      					<td><?php echo $template['template_name']; ?></td>
		      					<td><textarea rows="5" style="width:100%;"><?php echo $template['content']; ?></textarea></td>
		      					<td><?php echo $template['language']; ?></td>
		      					<td><?php echo ($template['status'])?'Enabled':'Disabled'; ?></td>
		      				<?php } ?>			
		      			</tbody>
					</table>
		        </div>
	        </div>
      		<div id="general" class="tab-pane active">
      			<div class="table-responsive">
		      		<table class="table table-bordered table-hover">
		      			<thead>
		      				<tr>
		      					<td class="col-sm-3"></td>
		      					<td class="col-sm-9"></td>
		      				</tr>
		      			</thead>
		      			<tbody>
		      				<tr>
		      					<td>Customer Order Sms</td>
		      					<td><div class="well well-sm">
			      					<?php foreach ($output['content'] as $template) { ?>
			      						<div class="checkbox">
			      							<label><input name="sms[customerorder]" value="<?php echo $template['template_name']; ?>" type="radio"><?php echo $template['template_name']; ?> (<?php echo $template['language']; ?>)</label>
			      						</div>
			      					<?php } ?>
		      					</div></td>
		      				<tr>
		      				<tr>
		      					<td>Supplier Order Sms</td>
		      					<td><div class="well well-sm">
			      					<?php foreach ($output['content'] as $template) { ?>
			      						<div class="checkbox">
			      							<label><input name="sms[sellertemplate]" value="<?php echo $template['template_name']; ?>" type="radio"><?php echo $template['template_name']; ?> (<?php echo $template['language']; ?>)</label>
			      						</div>
			      					<?php } ?>
		      					</div></td>
		      				<tr>
		      				<tr>
		      					<td>Admin Order Sms</td>
		      					<td><div class="well well-sm">
			      					<?php foreach ($output['content'] as $template) { ?>
			      						<div class="checkbox">
			      							<label><input name="sms[admintemplate]" value="<?php echo $template['template_name']; ?>" type="radio"><?php echo $template['template_name']; ?> (<?php echo $template['language']; ?>)</label>
			      						</div>
			      					<?php } ?>
		      					</div></td>
		      				<tr>
		      				
		      			</tbody>
					</table>
		        </div>
			</div>	        
       </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>