<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      	<a onclick="$('#customerForm').submit();" class="btn btn-primary" title="<?php echo $button_save; ?>">
      		<i class="fa fa-save"></i>
      	</a>
      	<a href="<?php echo $back; ?>" class="btn btn-default" title="<?php echo $button_back; ?>" >
      		<i class="fa fa-reply"></i>
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
	<?php if(isset($error_warning)) { ?>
	    <div class="alert alert-danger">
	      <i class="fa fa-exclamation-circle"></i>
	      <?php echo $error_warning; ?>
	    </div>
	<?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $sub_heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" enctype="multipart/form-data" id="customerForm" method="POST" class="form-horizontal">
          <input type="hidden" name="customer_group_id" value="<?php if(isset($id)) echo $id; ?>">
        	<div class="form-group required">
        		<label class="col-sm-2 control-label">
        			<span>
        				<?php echo $entry_name; ?>
        			</span>
        		</label>
        		<div class="col-sm-10">
        			<?php $index = 0; foreach ($languages as $key => $language) { ?>
        				<div class="input-group" style="margin-bottom:2px">
        					<span class="input-group-addon">
        						<img title="<?php echo $language['name'] ?>" src="view/image/flags/<?php echo $language['image'] ?>">
        					</span>
        					<input type="text" name="customerGroupName[<?php echo $index; ?>][name]; ?>]" value="<?php if(isset($customerGroupName)) echo $customerGroupName[$index]['name']; ?>" class="form-control"/>
        					<input type="hidden" name="customerGroupName[<?php echo $index; ?>][language_id]" value="<?php echo $language['language_id']; ?>" class="form-control" />
        				</div>
        				<?php if (isset($error_name[$language['language_id']])) { ?>
			              <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
			            <?php } ?>
        			<?php $index++; } ?>
        		</div>
        	</div>
        	<div class="form-group required">
        		<label class="col-sm-2 control-label">
        			<span>
        				<?php echo $entry_description; ?>
        			</span>
        		</label>
        		<div class="col-sm-10">
        			<?php $index = 0; foreach ($languages as $key => $language) { ?>
        				<div class="input-group" style="margin-bottom:2px">
        					<span class="input-group-addon">
        						<img title="<?php echo $language['name'] ?>" src="view/image/flags/<?php echo $language['image'] ?>">
        					</span>
        					<textarea rows="5" type="text" name="customerGroupDescription[<?php echo $index; ?>][description]; ?>]" value="" class="form-control" ><?php if(isset($customerGroupDescription)) echo $customerGroupDescription[$index]['description']; ?></textarea>
        					<input type="hidden" name="customerGroupDescription[<?php echo $index; ?>][language_id]" value="<?php echo $language['language_id']; ?>" class="form-control" />
        				</div>
        				<?php if (isset($error_description[$language['language_id']])) { ?>
			              <div class="text-danger"><?php echo $error_description[$language['language_id']]; ?></div>
			            <?php } ?>
        			<?php $index++; } ?>
        		</div>
        	</div>
          <div class="form-group">
            <label class="col-sm-2 control-label">
              <span >
                <?php echo "Parent"; ?>
              </span>
            </label>
            <div class="col-sm-10">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="customerGroupParent" value="1" <?php if(!isset($parentGroupId)) echo "checked='checked'"; ?> />
                  Parent Group
                </label>
              </div>
            </div>
          </div>
          <div class="form-group <?php if(!isset($parentGroupId)) echo "hide"; ?>">
            <label class="col-sm-2 control-label">
              <span >
                <?php echo "Parent Group"; ?>
              </span>
            </label>
            <div class="col-sm-10">
              <input type="text" name="customerGroupParentGroup" class="form-control" value="<?php if(isset($parentGroupName)) echo $parentGroupName; ?>" />
              <input type="hidden" name="customerGroupParentGroupId" value="<?php if(isset($parentGroupId)) echo $parentGroupId; ?>" />
            </div>
          </div>
        	<div class="form-group">
        		<label class="col-sm-2 control-label">
        			<span>
        				<?php echo $entry_group_rights; ?>
        			</span>
        		</label>
        		<div class="col-sm-10">
        			<div class="well well-sm" style="height:150px;overflow:auto">
	        			<?php foreach ($rights as $key => $right) { ?>
	        				<div class="checkbox">
		        				<label>
		        					<input type="checkbox" name="customerGroupRights[]" value="<?php echo $key; ?>" <?php if(isset($customerGroupRights) && in_array($key, $customerGroupRights)) echo "checked"; ?> />
		        					<?php echo $right; ?>
		        				</label>
		        			</div>
	        			<?php $index++; } ?>
	        		</div>
	        		<a class="selectAll"><?php echo $entry_selectall;?></a> &nbsp;&nbsp; <a class="deselectAll"><?php echo $entry_deselectall;?></a>
        		</div>
        	</div>
          <div class="form-group">
            <label class="col-sm-2 control-label">
              <?php echo $entry_status; ?>
            </label>
            <div class="col-sm-10">
              <select class="form-control" name="customerGroupStatus" >
                <option value="enable" <?php if(isset($customerGroupStatus) && $customerGroupStatus == 'enable') echo "selected"; ?> ><?php echo $text_enabled; ?></option>
                <option value="disable" <?php if(isset($customerGroupStatus) && $customerGroupStatus == 'disable') echo "selected"; ?> ><?php echo $text_disabled; ?></option>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

rightsHtml = $('.well').html();

$('input[name="customerGroupParentGroup"]').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: '<?php echo $getGroupUrl; ?>',
      data: '&filterGroupName='+  encodeURIComponent(request),
      type: 'post',
      success: function(json){
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['id'],
            rights: item['rights'],
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name="customerGroupParentGroup"]').val();
    $('input[name="customerGroupParentGroupId"]').val();
    $('input[name="customerGroupParentGroup"]').val(item.label);
    $('input[name="customerGroupParentGroupId"]').val(item.value);
    html = '';
    $.each(item.rights,function(key,value){
      html += '<div class="checkbox">';
      html += '<label><input type="checkbox" name="customerGroupRights[]" value="'+value['index']+'" checked="checked" /> '+value['text'];
      html += '</label></div>';
    });
    $('.well').html(html);
  }
});

$('input[name="customerGroupParent"]').on('click',function(){
  if($(this).is(':checked')) {
    $('input[name="customerGroupParentGroup"]').parent().parent().addClass('hide')
    $('input[name="customerGroupParentGroup"]').val('');
    $('input[name="customerGroupParentGroupId"]').val('');
    $('.well').html(rightsHtml);
  } else {
    $('input[name="customerGroupParentGroup"]').parent().parent().removeClass('hide')
  }
});

$('.selectAll').on('click',function(){
  $(this).prev('div').find('input[type="checkbox"]').prop('checked',true);
})

$('.deselectAll').on('click',function(){
  $(this).prevAll('div').find('input[type="checkbox"]').prop('checked',false);
})
</script>
<?php echo $footer; ?>