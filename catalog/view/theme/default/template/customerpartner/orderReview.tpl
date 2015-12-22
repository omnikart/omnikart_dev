<?php echo $header; ?><div id="columns">
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>

 <!--  <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><?php echo $error_warning; ?></div>
  <?php } ?> -->
  <?php if ($warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><?php echo $warning; ?></div>
  <?php } ?>
  <?php if ($product_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><?php echo $product_warning; ?></div>
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
      </h1>

      <fieldset>
        <legend><i class="fa fa-list"></i> <?php echo $heading_title; ?></legend>
        <?php if(isset($forReview) && $forReview) { ?>
          <form action="<?php echo $review; ?>" method="post" enctype="multipart/form-data" id="form-order-review">
            <div class="responsive-table">
              <input type="hidden" name="admin_id" value="<?php echo $admin_id; ?>" />
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <td><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked',this.checked);" /></td>
                    <td><?php echo $entry_product_name; ?></td>
                    <td><?php echo $entry_model; ?></td>
                    <td><?php echo $entry_quantity; ?></td>
                  </tr>
                </thead>
                <tbody>
                  <?php if($productsToReview) { ?>
                    <?php foreach ($productsToReview as $key => $product) { ?>
                      <tr>
                        <td><input type="checkbox" name="selected[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" ></td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $product['model']; ?></td>
                        <td><?php echo $product['quantity']; ?></td>
                      </tr>
                    <?php } ?>
                  <?php } else { ?>
                      <tr>
                        <td colspan="4" class="text-center"><?php echo $entry_nocart; ?></td>
                      </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </form>
          <?php if($abortEnable) { ?>
            <div class="col-sm-6 text-center">
              <button class="btn btn-danger" type="button" name="abortReview" ><?php echo $button_abort; ?></button>
            </div>
          <?php } ?>
          <?php if($reviewEnable) { ?>
            <div class="col-sm-6 text-center">
              <button class="btn btn-warning" type="button" name="sendToReview" ><?php echo $button_sendtoreview; ?></button>
            </div>
          <?php } ?>
        <?php } elseif(isset($reviewRequests) && $reviewRequests) { ?>
            <div class="responsive-table">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <td class="text-left"><?php echo $entry_subuser_name; ?></td>
                    <td class="text-left"><?php echo $entry_email; ?></td>
                    <td class="text-center"><?php echo $entry_action; ?></td>
                  </tr>
                </thead>
                <tbody>
                  <?php if(isset($reviewRequests) && $reviewRequests) { ?>
                    <?php foreach ($reviewRequests as $key => $request) { ?>
                      <tr>
                        <td class="text-left"><?php echo $request['firstname']." ".$request['lastname']; ?></td>
                        <td class="text-left"><?php echo $request['email']; ?></td>
                        <td class="text-center">
                          <button class="btn btn-warning" type="button" data-toggle="tooltip" data-original-title="<?php echo $entry_toottip_getproductlist; ?>" value="<?php echo $request['customer_id']; ?>" name="cartGetter">
                            <i class="fa fa-list"></i>
                          </button>
                        </td>
                      </tr>
                    <?php } ?>
                  <?php } else { ?>
                    <tr>
                      <td colspan="5" class="text-center"><?php echo $entry_norequestfound; ?></td>
                    </tr>
                  <?php } ?>
                  </tbody>
              </table>            
            </div>
        <?php } else if($isSubUser) { ?>
          <div class="row">
            <h3 class="text-warning">You have already submitted products to get reviewed. Let them approve and then send request for review.</h3>
          </div>
        <?php } ?>
      </fieldset>
      <div id="productModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel"></h3>
              </div>
            <div class="modal-body">
              <span id="alert" class="text-danger"></span>
            <h3 id="wk-open-list"></h3>
              <form action="<?php echo $approveAction; ?>" method="POST" enctype="multipart/form-data" id="approve-form">
                <table class="table table-bordered table-hover" id="details">
                  <thead>
                    <tr>
                      <td><input type="checkbox" onclick="$('input[name*=\'select\']').prop('checked',this.checked);"></td>
                      <td><?php echo $entry_product_name; ?></td>
                      <td><?php echo $entry_model; ?></td>
                      <td><?php echo $entry_quantity; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                          
                  </tbody>
              </table>
            </form>
          </div>
            <div class="modal-footer">
              <button class="btn btn-primary" type="button" data-dismiss="modal" aria-hidden="true" onclick="$('#approve-form').attr('action','<?php echo $approveAction; ?>');$('#approve-form').submit();">
                <?php echo $button_approve; ?>
              </button>
              <button class="btn btn-primary" type="submit" data-dismiss="modal" aria-hidden="true" onclick="$('#approve-form').attr('action','<?php echo $disapproveAction; ?>');$('#approve-form').submit();">
                Disapprove
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
</div>

<script type="text/javascript">
  $('button[name="cartGetter"]').on('click', function(){
    customer_id = $(this).val();
    $.ajax({
      url: 'index.php?route=account/customerpartner/orderReview/getProductsToReview',
      data: '&customer_id='+customer_id,
      type: 'post',
      methodType: 'json',
      success: function(json) {
        if(json['success']) {
          html = '';
          html += '<input type="hidden" value="'+customer_id+'" name="customer_id" />';
          $.each(json['data'],function(key,value){
			if (value.approved) {
				alert = 'alert-success';
			} else if (value.disapproved) {
				alert = 'alert-danger';
			} else {
				alert  = '';
			}
			name = 'select['+value.key+']';
			html += '<tr>';
            html += '<td><input type="checkbox" '+status+' name="'+name+'" value="'+value.quantity+'" ></td>';
            html += '<td class="'+alert+'">'+value.name+'</td>';
            html += '<td>'+value.model+'</td>';
            html += '<td>'+value.quantity+'</td>';
            html += '</tr>';
            order_review_id = value.order_review_id;
          });
          html += '<input type="hidden" value="'+order_review_id+'" name="order_review_id" />';
          $('#details > tbody').html(html);
          $('#productModal').modal();
        }
      }
    })
  });

  $('button[name="abortReview"]').on('click', function(){
    confirmation = confirm("<?php echo $warning_emptycart; ?>");
    if(confirmation) {
      $.ajax({
        url: 'index.php?route=account/customerpartner/orderReview/abortReview',
        type: 'post',
        success : function(json){
          if(json['success']) {
            html  = '<div class="alert alert-success"><i class="fa fa-check-circle"></i>'+json['success'];
            html += '</div>';
            location.reload();
            $('.breadcrumb').after(html);
          }
        }
      });
    }
  });

  $('button[name="sendToReview"]').on('click', function(){
    confirmation = confirm("<?php echo $warning_rusure; ?>");
    if(confirmation) {
      $('#form-order-review').submit();
    }
  });
</script>


</div><?php echo $footer; ?>