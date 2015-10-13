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
    <div class="row">
    	<div class="col-sm-6">
      		<h2><?php echo $category['name']; ?></h2>
      	</div>
    </div>
    <?php if ($products) { ?>
	 <div class="row">
      	<div class="col-md-4">
          <div class="btn-group hidden-xs">
            <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
            <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
          </div>
        </div>
        <div class="col-md-4 pull-right">
          <div class="btn-group hidden-xs pull-right">
          <button type="button" id="quickaddcart" class="btn btn-default" data-toggle="tooltip" title="Quick Add Products"><i class="fa fa-shopping-cart"></i>&nbsp;Quick Cart</button>
            <button type="button" id="category-view" class="btn btn-default" data-toggle="tooltip" title="Open Following Form"><i class="fa fa-caret-square-o-down"></i></button>
          </div>
        </div>
      </div>
      <br />
      <div class="row" id="category-d" style="display:none;">
      <form id="category-form" class="form-horizontal" >
      	<input type="hidden" name="category_id" value="<?php echo $category['category_id']; ?>" />
		<div class="form-group required">
			<div class="col-sm-12">
    			<button class="updatecategory btn btn-default pull-right" type="button" value="<?php echo $category['category_id']; ?>"><i class="fa fa-check"></i> <span class="hidden-xs hidden-sm hidden-md">Update Category</span></button>
    		</div>
		</div>
	  </form>
      </div>
      <h2>Products</h2>
      <div class="row">
        <?php foreach ($products as $product) { ?>
        <div class="product-layout product-list col-xs-12">
          <div class="product-thumb">
          	<div class="hover-content"><input type="checkbox" id="pcd-<?php echo $product['product_id']; ?>" name="products[]" value="<?php echo $product['product_id']; ?>" />
				<div>
				    <?php if ($dbe) { ?><label for="pcd-<?php echo $product['product_id']; ?>"></label><?php } ?>
				</div>
          	</div>
            <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
            <div>
              <div class="caption">
                <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                <p><?php echo $product['description']; ?></p>
                <?php if ($product['rating']) { ?>
                <div class="rating">
                  <?php for ($i = 1; $i <= 5; $i++) { ?>
                  <?php if ($product['rating'] < $i) { ?>
                  <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } else { ?>
                  <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } ?>
                  <?php } ?>
                </div>
                <?php } ?>
                <?php if ($product['price']) { ?>
                <p class="price">
                  <?php if (!$product['special']) { ?>
                  <?php echo $product['price']; ?>
                  <?php } else { ?>
                  <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
                  <?php } ?>
                  <?php if ($product['tax']) { ?>
                  <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                  <?php } ?>
                </p>
                <?php } ?>
              </div>
              <div class="button-group">
                <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
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
  $('.updatecategory').on('click', function(){
	buttont = $(this);
    $.ajax({
        url : 'index.php?route=account/cdp/updatecategory',
        data: $('#category-form input,#category-form select,#category-form checkbox:checked'),
        type: 'post',
		beforeSend: function() {
			$(buttont).button('loading');
		},
		complete: function() {
			$(buttont).button('reset');
		},
		success: function(json) {
		
		}
		
      });
  });  
  $('#category-view').click(function(){
  	$('#category-d').slideToggle("fast");
  });
  
  </script>


</div><?php echo $footer; ?>
