<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<a href="<?php echo $add; ?>" class="btn btn-primary"
					title="<?php echo $button_add; ?>"> <i class="fa fa-plus"></i>
				</a> <a onclick="$('#deleteForm').submit();" class="btn btn-danger"
					title="<?php echo $button_delete; ?>"> <i class="fa fa-trash-o"></i>
				</a>
			</div>
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
		        <?php foreach ($breadcrumbs as $key => $breadcrumb ) { ?>
		        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		        <?php } ?>
		    </ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if(isset($error_warning) && $error_warning) { ?>
		    <div class="alert alert-danger">
			<i class="fa fa-exclamation-circle"></i>
		      <?php echo $error_warning; ?>
		    </div>
		<?php } ?>
		<?php if(isset($success) && $success) { ?>
		    <div class="alert alert-success">
			<i class="fa fa-check-circle"></i>
		      <?php echo $success; ?>
		    </div>
		<?php } ?>
	    <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-pencil"></i>
		        	<?php echo $heading_title; ?>
		        </h3>
			</div>
			<div class="panel-body">
				<div class="well">
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label></label> <input type="text" name="filter_group_name"
									class="form-control"
									value="<?php if(isset($filter_group_name)) echo $filter_group_name; ?>" />
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label></label> <input type="text" name="filter_group_rights"
									class="form-control"
									value="<?php if(isset($filter_group_rights)) echo $filter_group_rights; ?>" />
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label></label> <select class="form-control"
									name="filter_group_status">
									<option value=""></option>
									<option value="enable"
										<?php if(isset($filter_group_status) && $filter_group_status == 'enable') echo "selected"; ?>><?php echo $text_enabled; ?></option>
									<option value="disable"
										<?php if(isset($filter_group_status) && $filter_group_status == 'disable') echo "selected"; ?>><?php echo $text_disabled; ?></option>
								</select>
							</div>
							<button class="btn btn-primary pull-right" type="button"
								onclick="filter()">
								<i class="fa fa-filter"></i>
			       				<?php echo $button_filter; ?>
			       			</button>
						</div>
					</div>
				</div>
				<form action="<?php echo $delete; ?>" method="post" id="deleteForm"
					class="form-horizontal">
					<div class="responsive-table">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td><input type="checkbox" size="1"
										onclick="$('input[name*=\'select[]\']').prop('checked',this.checked);" /></td>
									<td><a href="<?php echo $groupNameUrl; ?>"
										class="<?php if(isset($sort) && $sort == 'cpcn.name') echo $order; ?>"><?php echo $entry_name; ?></a></td>
									<td><a href="<?php echo $groupRightsUrl; ?>"
										class="<?php if(isset($sort) && $sort == 'cpc.rights') echo $order; ?>"><?php echo $entry_group_rights; ?></a></td>
									<td><a href="<?php echo $groupStatusUrl; ?>"
										class="<?php if(isset($sort) && $sort == 'cpc.status') echo $order; ?>"><?php echo $entry_status; ?></a></td>
									<td class="text-center"><?php echo $entry_action; ?></td>
								</tr>
							</thead>
							<tbody>
			       				<?php if($groupList) { ?>
			       					<?php foreach ($groupList as $key => $group) { ?>
			       						<tr>
									<td><input type="checkbox" name="select[]"
										value="<?php echo $group['id']; ?>" size="1" /></td>
									<td><?php echo $group['name']; ?></td>
									<td><?php echo $group['rights']; ?></td>
									<td><?php echo $group['status']; ?></td>
									<td class="text-center"><a href="<?php echo $group['edit']; ?>"
										class="btn btn-primary"> <i class="fa fa-edit"></i>
									</a></td>
								</tr>
			       					<?php } ?>
			       				<?php } else { ?>
			       						<tr>
									<td colspan="5" class="text-center"><?php echo $text_not_found; ?></td>
								</tr>
			       				<?php } ?>
			       			</tbody>
						</table>
					</div>
				</form>
				<div class="row">
					<div class="pull-left"><?php echo $pagination; ?></div>
					<div class="pull-right"><?php echo $results; ?></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function filter(){
		url = '<?php echo $filterUrl; ?>';
		filter_group_name = $('input[name="filter_group_name"]').val();
		if(filter_group_name) {
			url += '&filter_group_name='+filter_group_name;
		}
		filter_group_rights = $('input[name="filter_group_rights"]').val();
		if(filter_group_rights) {
			url += '&filter_group_rights='+filter_group_rights;
		}
		filter_group_status = $('select[name="filter_group_status"]').val();
		if(filter_group_status) {
			url += '&filter_group_status='+filter_group_status;
		}
		// console.log(url);
		location = url;
	}
</script>

<?php echo $footer; ?>