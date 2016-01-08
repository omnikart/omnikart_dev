<?php echo $header; ?><div id="columns">
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h2>Customer Dashboard</h2>
    <?php if ($categories) { ?>
      <div class="row">
     	<?php foreach ($categories as $category) { ?>
	        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
	            <div class="product-thumb transition">
	                <div class="image"><a href="<?php echo $category['href']; ?>"><div></div><img src="<?php echo $category['image']; ?>" alt="<?php echo $category['name']; ?>" title="<?php echo $category['name']; ?>" class="img-responsive" /></a></div>
	                <div>
		                <div class="caption" style="min-height: 60px">
		                    <h4><a style="text-decoration: none" href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></h4>
		                </div>
		                <div class="button-group">
	                		<button class="quickaddcart" style="width:100%" type="button" value="<?php echo $category['category_id']; ?>"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md">Quick Add to Cart</span></button>
	                	</div>
	                </div>
	            </div>
      		</div>
     	<?php } ?>
      </div>
      <?php if ($dbe) { ?>
      <div class="buttons">
        <div class="pull-right"><button id="button-pcd" class="btn btn-primary btn-lg">Add Products to DashBoard</button></div>
      </div>
      <?php } ?>
      <div class="row">
        <div class="col-sm-6 text-left"><?php //echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php //echo $results; ?></div>
      </div>
      <?php } ?>
      
     <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>

<script type="text/javascript">
  $('button[class="quickaddcart"]').on('click', function(){
    buttont = $(this);
    category_id = $(this).val();
    $.ajax({
        url : 'index.php?route=account/cd/buycategory',
        data: '&category_id='+category_id,
        type: 'post',
		beforeSend: function() {
			$(buttont).button('loading');
		},
		complete: function() {
			$(buttont).button('reset');
		},
		success: function(json) {
			setTimeout(function () {
				$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
			}, 100);
			$('#cart > ul').load('index.php?route=common/cart/info ul li');
		}
		
      });
  });
  
  
  </script>
</div><?php echo $footer; ?>
