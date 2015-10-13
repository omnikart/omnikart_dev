<div id="seller-collection">


<?php if ($products) { ?>
	
	<!-- for category list-->
	<column id="column-left" class="col-sm-3 hidden-xs">
	<div class="list-group">
		<?php foreach ($categories as $category) { ?>
			<?php if ($category['category_id'] == $category_id) { ?>
				<a href="<?php echo $category['href']; ?>" class="list-group-item active"><?php echo $category['name']; ?></a>
				<?php if ($category['children']) { ?>
					<?php foreach ($category['children'] as $child) { ?>
						<?php if ($child['category_id'] == $child_id) { ?>
							<a href="<?php echo $child['href']; ?>" class="list-group-item active">&nbsp;&nbsp;&nbsp;- <?php echo $child['name']; ?></a>
						<?php } else { ?>
							<a href="<?php echo $child['href']; ?>" class="list-group-item">&nbsp;&nbsp;&nbsp;- <?php echo $child['name']; ?></a>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			<?php } else { ?>
				<a href="<?php echo $category['href']; ?>" class="list-group-item"><?php echo $category['name']; ?></a>
			<?php } ?>
		<?php } ?>
	</div>
	</column>

	<?php $class = 'col-sm-9'; ?>
	<div class="<?php echo $class; ?>">
		<p><a href="<?php echo $compare; ?>" id="compare-total" class="default-work"><?php echo $text_compare; ?></a></p>
		<div class="row">
			<div class="col-md-4">
			  <div class="btn-group">
			    <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
			    <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
			  </div>
			</div>
			<div class="col-md-2 text-right">
			  <label class="control-label" for="input-sort"><?php echo $text_sort; ?></label>
			</div>
			<div class="col-md-3 text-right">
			  <select id="input-sort" class="form-control col-sm-3">
			    <?php foreach ($sorts as $sorts) { ?>
			    <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
			    <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
			    <?php } else { ?>
			    <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
			    <?php } ?>
			    <?php } ?>
			  </select>
			</div>
			<div class="col-md-1 text-right">
			  <label class="control-label" for="input-limit"><?php echo $text_limit; ?></label>
			</div>
			<div class="col-md-2 text-right">
			  <select id="input-limit" class="form-control">
			    <?php foreach ($limits as $limits) { ?>
			    <?php if ($limits['value'] == $limit) { ?>
			    <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
			    <?php } else { ?>
			    <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
			    <?php } ?>
			    <?php } ?>
			  </select>
			</div>
		</div>
		<br />

		<div class="row">
			<?php foreach ($products as $product) { ?>
			<div class="product-layout product-list col-xs-12">
			  <div class="product-thumb">
			    <div class="image"><a href="<?php echo $product['href']; ?>" class="default-work"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
			    <div>
			      <div class="caption">
			        <h4><a href="<?php echo $product['href']; ?>" class="default-work"><?php echo $product['name']; ?></a></h4>
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
			        <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
			        <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
			        <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
			      </div>
			    </div>
			  </div>
			</div>
			<?php } ?>
		</div>

		<div class="row">
			<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
			<div class="col-sm-6 text-right"><?php echo $results; ?></div>
		</div>
	</div>
<?php } ?>

<?php if (!$categories && !$products) { ?>
	<p><?php echo $text_empty; ?></p>
<?php } ?>

<script>
// Product List
$('#list-view').click(function() {
	$('#content .product-layout > .clearfix').remove();

	$('#content .product-layout').attr('class', 'product-layout product-list col-xs-12');

	localStorage.setItem('display', 'list');
});

// Product Grid
$('#grid-view').click(function() {
	$('#content .product-layout > .clearfix').remove();

	// What a shame bootstrap does not take into account dynamically loaded columns
	cols = $('#column-right, #column-left').length;

	if (cols == 2) {
		$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12');

		$('#content .product-layout:nth-child(2)').after('<div class="clearfix visible-md visible-sm"></div>');
	} else if (cols == 1) {
		$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12');

		$('#content .product-layout:nth-child(3)').after('<div class="clearfix visible-lg"></div>');
	} else {
		$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12');

		$('#content .product-layout:nth-child(4)').after('<div class="clearfix"></div>');
	}

	 localStorage.setItem('display', 'grid');
});

if (localStorage.getItem('display') == 'list') {
	$('#list-view').trigger('click');
} else {
	$('#grid-view').trigger('click');
}

$('#seller-collection select').on('change',function(){
	thisvalue = this.value;	
	$('a[href=\'#tab-collection\']').append(' <i class="fa fa-spinner fa-spin remove-me"></i>');  
	$('#tab-collection').load(thisvalue,function(){
      $('.remove-me').remove();
    });
});

$('#seller-collection a').on('click',function(e){
	if(!$(this).hasClass('default-work'))
		e.preventDefault();
	else
		return;
	
	thisvalue = this.href;
	$('a[href=\'#tab-collection\']').append(' <i class="fa fa-spinner fa-spin remove-me"></i>');   
	$('#tab-collection').load(thisvalue,function(){
      $('.remove-me').remove();
    });
});
</script>