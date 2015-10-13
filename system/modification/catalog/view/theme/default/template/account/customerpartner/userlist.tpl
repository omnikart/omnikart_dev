<?php echo $header; ?><div id="columns">
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>

  <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"> </i> <?php echo $success; ?></div>
  <?php } ?>
  
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>

    <div id="content" class="<?php echo $class; ?>">
      <?php echo $content_top; ?>    
      <h1>
        <?php echo $heading_title; ?>
        <div class="pull-right">
          <a href="<?php echo $insert; ?>" data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
          <a onclick="$('#form-product').submit();" data-toggle="tooltip" class="btn btn-danger"  title="<?php echo $button_delete; ?>"><i class="fa fa-trash-o"></i></a>
        </div>
      </h1>

      <fieldset>
        <legend><i class="fa fa-list"></i> <?php echo $heading_title; ?></legend>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product" >
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td width="1"><input type="checkbox" onclick="$('input[name*=\'selected[]\']').prop('checked', this.checked)"></td>
                  <td class="text-left"><?php echo $entry_name; ?></td>
                  <td class="text-left"><?php echo $entry_email; ?></td>
                  <td class="text-left"><?php echo $entry_rights; ?></td>
                  <td class="text-left"><?php echo $entry_status; ?></td>
                  <td class="text-center"><?php echo $entry_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if($userlist) { ?>
                  <?php foreach ($userlist as $key => $list) { ?>
                    <tr>
                      <td width="1"><input type="checkbox" name="selected[]" value="<?php echo $list['customer_id']; ?>"></td>
                      <td class="text-left"><?php echo $list['firstname']." ".$list['lastname']; ?></td>
                      <td class="text-left"><?php echo $list['email']; ?></td>
                      <td class="text-left text-success"><?php $s = ''; foreach ($list['customerRights']['rights'] as $key => $value) {
                        $s .= $value.", ";
                      } $s = rtrim($s,','); echo $s; ?></td>
                      <td class="text-left" id="user-status"><?php echo $list['status']; ?></td>
                      <td class="text-center">
                          <input type="hidden" id="cg-<?php echo $list['customer_id']; ?>" name="customer_group_id" value="<?php echo $list['customer_group_id']; ?>">
                          <input type="hidden" id="mi-<?php echo $list['customer_id']; ?>" name="manager_id" value="<?php echo $list['manager_id']; ?>">
                          <input type="hidden" id="pl-<?php echo $list['customer_id']; ?>" name="p_limit" value="<?php echo $list['p_limit']; ?>">
                          <button class="btn btn-primary" name="alter_customer_group" type="button" value="<?php echo $list['customer_id']; ?>" data-toggle="modal" data-target="#customergroup">
	                      
                            <i class="fa fa-pencil"></i>
                          </button>
                        <?php if($list['status'] == 'enable') { ?>
                          <a class="btn btn-danger changeStatusSubUser" data-toggle="tooltip" data-original-title="<?php echo $button_disable; ?>" data-value="disable-<?php echo $list['customer_id']; ?>">
                            <i class="fa fa-thumbs-o-down"></i>
                          </a>
                        <?php } else { ?>
                          <a class="btn btn-success changeStatusSubUser" data-toggle="tooltip" data-original-title="<?php echo $button_enable; ?>" data-value="enable-<?php echo $list['customer_id']; ?>">
                            <i class="fa fa-thumbs-o-up"></i>
                          </a>
                          <?php } ?>
                      </td>
                    </tr>
                  <?php } ?>
                <?php } else { ?>
                    <tr>
                      <td colspan="6" class="text-center"><?php echo $text_no_record; ?></td>
                    </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
      </fieldset>
      <div id="customergroup" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel"></h3>
            </div>
            <div class="modal-body">
              <form class="form-horizontal">
                <div class="form-group required">
        		<label class="col-sm-2 control-label" for="customer_group">Customer Group</label>
        		<div class="col-sm-10">
                  <select id="customer_group" class="form-control" name="customer_group">
                    <option value=""></option>
                    <?php if($customer_groups) { ?>
                      <?php foreach ($customer_groups as $key => $customer_group) { ?>
                        <option value="<?php echo $customer_group['id']; ?>"><?php echo $customer_group['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
				</div>                  
				</div>
				                  
                <div class="form-group required">
            	<label class="col-sm-2 control-label" for="manager_id">Manager</label>
            	<div class="col-sm-10">
                  <select id = "manager_id" class="form-control" name="manager_id">
                    <option value="0"></option>
                    <?php if($allusers) { ?>
                      <?php foreach ($allusers as $key => $user) { ?>
                        <option value="<?php echo $user['id']; ?>"><?php echo $user['firstname'].' '.$user['lastname']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>  
                </div>
      			</div>
                <div class="form-group required">
            	<label class="col-sm-2 control-label" for="p_limit">Purchasing Limit</label>
            	<div class="col-sm-10">
					<input type="text" name="p_limit" value="" placeholder="Limit" id="p_limit" class="form-control">  
                </div>
      			</div>
      			      			
              </form>
            </div>
            <div class="modal-footer">
              <button class="btn btn-primary" type="button" data-dismiss="modal" aria-hidden="true" name="update_button" >
                <?php echo $button_update; ?>
              </button>
              <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true" id="closeButton">
                <?php echo $button_close; ?>
              </button>
            </div>
          </div>
        </div>
      </div>
    <?php echo $content_bottom; ?>  
  </div> 
  <?php echo $column_right; ?>
  </div>
</dv>

<script type="text/javascript">

  var customer_id = '';
  $('button[name="alter_customer_group"]').on('click', function(){
    customer_id = $(this).val();
    current_customer_group_id = $('#cg-'+customer_id).val();
    current_manager_id = $('#mi-'+customer_id).val();
    current_p_limit = $('#pl-'+customer_id).val();
    
    $('select[name="customer_group"]').val(current_customer_group_id);
    $('select[name="manager_id"]').val(current_manager_id);
    $('input[type="text"][name="p_limit"]').val(current_p_limit);
  });

  $('button[name="update_button"]').on('click', function(){
    customer_group_id = $('select[name="customer_group"]').val();
    manager_id = $('select[name="manager_id"]').val();
    p_limit = $('input[type="text"][name="p_limit"]').val();
    alert(p_limit);
    if(customer_group_id) {
      $.ajax({
        url : 'index.php?route=account/customerpartner/userlist/changeCustomerGroupId',
        data: '&customer_id='+customer_id+'&customer_group_id='+customer_group_id+'&manager_id='+manager_id+'&p_limit='+p_limit,
        type: 'post',
      })
    } else {
      alert("problem");
    }
  });

  $('body').on('click','.changeStatusSubUser',function(){
    data = '';
    data = $(this).attr('data-value');
    data = data.split('-');
    customer_id = data[1];
    action = data[0];
    $this = $(this);
    $('.alert').remove();
    $.ajax({
      url: 'index.php?route=account/customerpartner/userlist/disableSubUser',
      data:'&customer_id='+customer_id+'&action='+action,
      type: 'post',
      methodType: 'json',
      success : function(json){
        if(json['success']) {
          html = '<div class="alert alert-success"><i class="fa fa-check-circle"></i>'+json['success']+'</div>';
          $('.breadcrumb').after(html);
          if(action == 'disable') {
            $this.attr('data-value','enable-'+customer_id);
            $this.attr('data-original-title','<?php echo $button_enable; ?>');
            $this.removeClass('btn-danger').addClass('btn-success');
            $this.children('i').removeClass('fa-thumbs-o-down').addClass('fa-thumbs-o-up');
            $('#user-status').html('<?php echo "Disable"; ?>');
          } else {
            $this.attr('data-value','disable-'+customer_id);
            $this.attr('data-original-title','<?php echo $button_disable; ?>');
            $this.removeClass('btn-success').addClass('btn-danger');
            $this.children('i').removeClass('fa-thumbs-o-up').addClass('fa-thumbs-o-down');
            $('#user-status').html('<?php echo "Enable"; ?>');
          }
        }
        if(json['warning']) {
          html = '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>'+json['warning']+'</div>';
          $('.breadcrumb').after(html);
        }
      }
    });
  });
</script>

</div><?php echo $footer; ?>