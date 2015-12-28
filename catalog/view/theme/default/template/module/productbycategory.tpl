<div class="productbycategory">
  <div class="row">
		<div class="col-sm-12">
			<div class="title">
				<h3 class="pull-left">
						<?php foreach ($categories as $category) { ?>
								<a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
						<?php } ?>
				</h3>
				<div class="subcategory pull-right">
					<ul class="nav nav-pills">
						<?php if (!empty($subcategories)) {
									$i=0;
									foreach ($subcategories as $subcategory) {?>
										<li><a href="<?php echo $subcategory['href']; ?>"><?php echo $subcategory['name']; ?></a></li>
								<?php if (++$i == 6) break; }?>
						<?php } ?>
						<?php foreach ($categories as $category) { ?>
							<li class="viewmore"><a href="<?php echo $category['href']; ?>">View More</a></li>
						<?php } ?>
					</ul>
				</div>
				<div class="clearfix"></div>
			</div>
    </div>
  </div>
  
<div class="row">
<?php if ($style == 0) { ?>

<div id="productbycategory<?php echo $module; ?>" class="flexslider">
<ul class="slides">
	  <?php foreach ($products as $product) { ?>
	  <li>
		  <div class="product-layout col-xs-12">
		    <div class="product-thumb transition">
		      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
		      <div class="caption">
		      	<h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
		        <?php /* if ($product['rating']) { ?>
		        <div class="rating">
		          <?php for ($i = 1; $i <= 5; $i++) { ?>
		          <?php if ($product['rating'] < $i) { ?>
		          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
		          <?php } else { ?>
		          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
		          <?php } ?>
		          <?php } ?>
		        </div>
		        <?php } */?>
		        <?php if ($product['price']) { ?>
			       <div class="price type-<?php echo $product['type']?>" >
			          <?php if (!$product['special']) { ?>
						<?php if ($product['discount']) { ?>
			      				<span style="text-decoration: line-through;color:#aaa;"><?php echo $product['original_price']; ?></span><span class="discount"><span class="text"><?php echo $product['discount']; ?>% Off</span><span class="img"></span></span>
	              		<?php } ?>
			                  <h4><?php echo $product['price']; ?></h4>          
			          
	          			<?php } else { ?>
			          		<span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
			          <?php } ?>
			       </div>
		       <?php } ?>
		      </div>
		      <div class="cart-button">
		    	<div class="input-group">
					<input id="qty-<?php echo $product['product_id']; ?>" type="number" min="<?php echo $product['minimum']; ?>" value="<?php echo $product['minimum']; ?>" placeholder="" id="quantity" class="form-control quantity" />
					<div class="input-group-btn">
						<button class="btn btn-default" type="button" onclick="cart.add('<?php echo $product['product_id']; ?>', $('#qty-<?php echo $product['product_id']; ?>').val());"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
					</div>	
				</div>
			  </div>
		      <div class="button-group button-group-2">
		        <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
		        <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
		      </div>
		    </div>
		  </div>
		 </li>
	  <?php } ?>
  </ul>
</div>
<script type="text/javascript"><!--
$('#productbycategory<?php echo $module; ?>').flexslider({
	  animation: "slide",
	  controlNav: false,
	  directionNav: true,
	  itemWidth: 25,
	  nextText: "next",
	  prevText: "prev",
		itemMargin: -1,
		minItems: 5, // use function to pull in initial value
		maxItems: 5,
	  useCSS: false /* Chrome fix*/
});
--></script>
<?php } else {  ?>
<div class="col-sm-12">
<?php foreach ($products as $product) { ?>
  <div class="product-layout product-grid col-lg-3 col-md-3 col-sm-4 col-xs-6">
      <div class="product-thumb row">
        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive lazyOwl" /></a></div>
        <div>
          <div class="caption">
            <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
            <p><?//php echo $product['description']; ?></p>
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
							<?php if ($product['discount']) { ?>
								<span style="text-decoration: line-through;color:#aaa;"><?php echo $product['original_price']; ?></span><span class="discount"><span class="text"><?php echo $product['discount']; ?>% Off</span><span class="img"></span></span>
							<?php } ?>
            </p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
<?php } ?>
</div>
<?php } ?>
</div>
 </div>



