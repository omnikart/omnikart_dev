<?php echo $header; ?>
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
	        <?php if($allowedAddEdit && $mp_ap) { ?>
						<a href="<?php echo $insert; ?>"  data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
	        <?php } ?>
			<button data-toggle="tooltip" class="btn btn-primary" id="updateProducts" form="form-product" formaction="<?php echo $addproducts; ?>" title="Update changes for selected products. Page will not refresh after this."><i class="fa fa-save"></i></button>
      	</div> 
    </h1>

    <fieldset>
      <legend><i class="fa fa-list"></i> <?php echo $heading_title; ?></legend>
      <?php if($isMember) { ?>
      <div class="well">
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label class="control-label" for="input-name"><?php echo $column_name; ?></label>
              <div class='input-group'>                
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $column_name; ?>" id="input-name" class="form-control" />                       
                <span class="input-group-addon"><span class="fa fa-angle-double-down"></span></span>
              </div>
            </div>            
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              <label class="control-label" for="input-model"><?php echo $column_model; ?></label>
              <div class='input-group'>                
                <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $column_model; ?>" id="input-model" class="form-control" />                                    
                <span class="input-group-addon"><span class="fa fa-angle-double-down"></span></span>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label class="control-label" for="input-category">Filter by Category</label>
              <input type="text" name="filter_category" value="" placeholder="" id="input-category" class="form-control" />
            </div>
            <a onclick="filter();" class="btn btn-primary pull-right">Find Products</a> 
          </div>
        </div>
      </div>
      
      <div class="col-sm-3">
      	<div class="list-group">
	      <?php if ($categories) { foreach ($categories as $category) { ?>
	    	<a href="<?php echo $category['link']; ?>" class="list-group-item"><?php echo $category['name']; ?></a>   	
          <?php } } ?>
        </div> 
      </div>
      
      <form action="<?php echo $delete; ?>" method="post" class="col-sm-9"enctype="multipart/form-data" id="form-product">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
                <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                  <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo str_replace(' ', '', $column_name); ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_name; ?>"><?php echo str_replace(' ', '', $column_name); ?></a>
                  <?php } ?></td>
                <td class="text-left"><?php if ($sort == 'p.model') { ?>
                  <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                  <?php } ?></td>
                <td class="text-left"><?php echo $column_price; ?></a></td>
                <td class="text-right"><?php echo $column_quantity; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($products) { ?>
              <?php foreach ($products as $product) { ?>
              <tr>
                <td style="text-align: center;"><?php if ($product['selected']) { ?>
                  <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                  <?php } else { ?>
                  <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                  <?php } ?></td>
                <td class="text-left">
                  <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['thumb']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" class="img-thumbnail" />
                  &nbsp;
	              <a href="index.php?route=product/product&product_id=<?php echo $product['product_id']; ?>"> <?php echo $product['name']; ?></a>
                  <input type="hidden" name="products[<?php echo $product['product_id']; ?>][product_id]" value="<?php echo $product['product_id']; ?>" />
                  
                </td>
                <td class="text-left"><?php echo $product['model']; ?></td>
                <td class="text-left">
                	<input type="text" style="width:70px;" class="form-control" name="products[<?php echo $product['product_id']; ?>][price]" value=""/>
                </td>
                <td class="text-right">
				  <input type="text" style="width:50px;" class="form-control" name="products[<?php echo $product['product_id']; ?>][quantity]" value=""/>                  
                  
              	</td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </form>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo isset($pagination)?$pagination:''; ?></div>
        <div class="col-sm-6 text-right"><?php echo isset($results)?$results:''; ?></div>
      </div>
      <?php } else { ?>
        <div class="text-danger">
          Warning: You are not authorised to view this page, Please contact to site administrator!
        </div>
      <?php } ?>
    </fieldset>

    <?php echo $content_bottom; ?>  
  </div> 
  <?php echo $column_right; ?>
  </div>
</div>  
<script type="text/javascript"><!--

$('#form-product').submit(function(){
    if ($(this).attr('action').indexOf('delete',1) != -1) {
        if (!confirm('Do you want to Add Seleced Products')) {
            return false;
        }
    }
});

function filter() {
  url = 'index.php?route=account/customerpartner/addproductlist';
  
  var filter_name = $('input[name=\'filter_name\']').val();
  
  if (filter_name) {
    url += '&filter_name=' + encodeURIComponent(filter_name);
  }
  
  var filter_model = $('input[name=\'filter_model\']').val();
  
  if (filter_model) {
    url += '&filter_model=' + encodeURIComponent(filter_model);
  }
  
  var filter_price = $('input[name=\'filter_price\']').val();
  
  if (filter_price) {
    url += '&filter_price=' + encodeURIComponent(filter_price);
  }
  
  var filter_quantity = $('input[name=\'filter_quantity\']').val();
  
  if (filter_quantity) {
    url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
  }
  
  location = url;
}
//--></script> 
<script type="text/javascript"><!--
$('.row input').keydown($('#updateProducts').on('click',function(){
	$.ajax({
		url: 'index.php?route=account/customerpartner/addproductlist/addProduct',
		type: 'post',
		data: $('#form-product').serialize(),
		dataType: 'json',
		beforeSend: function() {
			$('#button-cart').button('loading');
		},
		complete: function() {
			$('#button-cart').button('reset');
		},
		success: function(json) {
		
		}	
	});
});function(e) {
  if (e.keyCode == 13) {
    filter();
  }
});
//--></script> 
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
  delay: 500,
  source: function(request, response) {
    $.ajax({
      url: 'index.php?route=customerpartner/autocomplete/addproduct&filter_type=customerpartner_&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {   
        response($.map(json, function(item) {
          return {
            label: item.name,
            value: item.product_id
          }
        }));
      }
    });
  }, 
  select: function(item) {
    $('input[name=\'filter_name\']').val(item.label);            
    return false;
  },
  focus: function(item) {
    return false;
  }
});

$('input[name=\'filter_model\']').autocomplete({
  delay: 500,
  source: function(request, response) {
    $.ajax({
      url: 'index.php?route=customerpartner/autocomplete/product&filter_type=customerpartner_&filter_model=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {   
        response($.map(json, function(item) {
          return {
            label: item.model,
            value: item.product_id
          }
        }));
      }
    });
  }, 
  select: function(item) {
    $('input[name=\'filter_model\']').val(item.label);            
    return false;
  },
  focus: function(item) {
    return false;
  }
});
//--></script> 
<script type="text/javascript"><!--
$('input[name=\'product_name\']').autocomplete({
  delay: 500,
  source: function(request, response) {
    $.ajax({
      url: 'index.php?route=customerpartner/autocomplete/addproduct&filter_type=customerpartner_&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {   
        response($.map(json, function(item) {
          return {
            label: item.name,
            value: item.product_id
          }
        }));
      }
    });
  }, 
  select: function(item) {
    $('input[name=\'product_name\']').val(item.label);            
    return false;
  },
  focus: function(item) {
    return false;
  }
});

$('input[name=\'product_model\']').autocomplete({
  delay: 500,
  source: function(request, response) {
    $.ajax({
      url: 'index.php?route=customerpartner/autocomplete/addproduct&filter_type=customerpartner_&filter_model=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {   
        response($.map(json, function(item) {
          return {
            label: item.model,
            value: item.product_id
          }
        }));
      }
    });
  }, 
  select: function(item) {
    $('input[name=\'product_model\']').val(item.label);            
    return false;
  },
  focus: function(item) {
    return false;
  }
});
//--></script> 
<?php echo $footer; ?>
