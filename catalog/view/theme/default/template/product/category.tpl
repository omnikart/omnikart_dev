<?php echo $header; ?>
<?php if( ! empty( $mfilter_json ) ) { echo '<div id="mfilter-json" style="display:none">' . base64_encode( $mfilter_json ) . '</div>'; } ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?><div id="mfilter-content-container">
      <h2><?php echo $heading_title; ?></h2>
      <?php if ($thumb || $description) { ?>
      <div class="row">
        <?php if ($thumb) { ?>
        <div class="col-sm-2"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" /></div>
        <?php } ?>
        <?php if ($description) { ?>
        <div class="col-sm-10"><?php echo $description; ?></div>
        <?php } ?>
      </div>
      <hr>
      <?php } ?>
      <?php if ($categories) { ?>
      <h3><?php echo $text_refine; ?></h3>
      <div class="row">
        <?php foreach ($categories as $category) { ?>
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="product-thumb transition">
                <div class="image"><a href="<?php echo $category['href']; ?>"><div></div><img src="<?php echo $category['image']; ?>" alt="<?php echo $category['name']; ?>" title="<?php echo $category['name']; ?>" class="img-responsive" /></a></div>
                <div class="caption" style="min-height: 60px">
                    <h4><a style="text-decoration: none" href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></h4>
                </div>
            </div>
        </div>
        <?php } ?>
      </div>
      <?php } ?>
      <?php if ($products) { ?>
      <p><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></p>
      <div class="row">
        <div class="col-md-4">
          <div class="btn-group hidden-xs">
            <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
            <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
          </div>
        </div>
        <div class="col-md-2 text-right">
          <label class="control-label" for="input-sort"><?php echo $text_sort; ?></label>
        </div>
        <div class="col-md-3 text-right">
          <select id="input-sort" class="form-control" onchange="location = this.value;">
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
          <select id="input-limit" class="form-control" onchange="location = this.value;">
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
          	<div class="hover-content"><?php if ($dbe) { ?>
          		<?php if ($product['type']=='1'){ ?>
	          		<input type="checkbox" id="pcd-<?php echo $product['product_id']; ?>" name="<?php echo ($product['type']=='2')?'grouped':'products[]';?>" value="<?php echo $product['product_id']; ?>" />
					<div>
					    <label for="pcd-<?php echo $product['product_id']; ?>"></label>
					</div>
				<?php } else {?>
					<div>
						This Product Contains more variations. Please click on the image below.
					</div>
				<?php } ?>
				<?php } ?>
          	</div>
            <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
            <div>
              <div class="caption">
                <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
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
                  	<?php if ($product['discount']) { ?>
              			<span style="text-decoration: line-through;color:#aaa;"><?php echo $product['original_price']; ?></span>&nbsp;<span style="padding:1px;background:#ddd;border-radius:2px;background:#8FBB6C;color:#fff;">&nbsp;<?php echo $product['discount']; ?>% Off&nbsp;</span>
              		<?php } ?>
                  <h4><?php echo $product['price']; ?></h4>
                  <?php } else { ?>
                  <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
                  <?php } ?>
                </p>
                <?php } ?>
              </div>
              <div class="button-group">
              	<input id="qty-<?php echo $product['product_id']; ?>" type="number" min="<?php echo $product['minimum']; ?>" value="<?php echo $product['minimum']; ?>" placeholder="" id="quantity" class="form-control quantity" />
                <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>', $('#qty-<?php echo $product['product_id']; ?>').val());"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
              </div>
              <!-- hello test change -->
              <div class="button-group button-group-2">
                <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
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
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <?php } ?>
      <?php if (!$categories && !$products) { ?>
      <p><?php echo $text_empty; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      </div><?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
	

</div>
<?php echo $footer; ?>
	<script type="text/javascript">
		$('#button-pcd').on('click', function() {
			$('#modal-db').remove();
			$.ajax({
				url: 'index.php?route=account/cd/getCategories',
				type: 'post',
				dataType: 'json',
				beforeSend: function() {
					$('#button-pcd').button('loading');
				},
				complete: function() {
					$('#button-pcd').button('reset');
				},
				success: function(json) {
					
					html  = '<div id="modal-db" class="modal">';
					html += '  <div class="modal-dialog">';
					html += '    <div class="modal-content">';
					html += '      <div class="modal-header">Hello';
					html += '      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>';
					html += '      <div class="modal-body">'+json+'<br /><br /><input class="form-control col-sm-12" name="category-name" type="text" placeholder="Input Name in case of new category" value=""></input><br /><br />';
					html += '	   <button id="button-update" onclick="updatedb();" class="btn btn-primary btn-lg">Update to DashBoard</button></div>';
					html += '    </div';
					html += '  </div>';
					html += '</div>';
					
					$('body').append(html);
	
					$('#modal-db').modal('show');		
				}
			});
		});
		$('input[name="grouped"]').on('change',function(){
			var product_id = this.value;
			$('#modal-db1').remove();
			 
			$.ajax({
				url: 'index.php?route=product/category/getGPProducts',
				type: 'post',
				data: 'product_id='+product_id,
				dataType: 'json',
				beforeSend: function() {
					$('#button-pcd').button('loading');
				},
				complete: function() {
					$('#button-pcd').button('reset');
				},
				success: function(json) {
					
					html  = '<div id="modal-db1" class="modal">';
					html += '  <div class="modal-dialog">';
					html += '    <div class="modal-content">';
					html += '      <div class="modal-header">Hello';
					html += '      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>';
					html += '      <div class="modal-body">'+json+'<br /><br />';
					html += '	   <button id="button-update" onclick="updatedb();" class="btn btn-primary btn-lg">Update to DashBoard</button></div>';
					html += '    </div';
					html += '  </div>';
					html += '</div>';
					
					$('body').append(html);
	
					$('#modal-db1').modal('show');		
				}
			});
		
		});
		function updatedb() {
				$.ajax({
					url: 'index.php?route=account/cd/addProductCd',
					type: 'post',
					data: $('.product-thumb .hover-content input[type=\'checkbox\']:checked, select[name="category_id"],input[name="category-name"]'),
					dataType: 'json',
					beforeSend: function() {
						$('#button-pcd').button('loading');
					},
					complete: function() {
						$('#button-pcd').button('reset');
					},
					success: function(json) {
						$('.alert').remove();
						if (json['error_text']) $('.modal-header').after('<div class="alert alert-danger"><div class="text-danger">'+ json['error_text'] +'</div></div>');		
					}
				});
		}		
	</script>
