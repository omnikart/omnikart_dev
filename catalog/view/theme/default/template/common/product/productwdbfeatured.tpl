<div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
            <div>
              <div class="caption">
                <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
		        <?php if ($product['price']) { ?>
			       <div class="price type-<?php echo $product['type']?>" >
			          <?php if (!$product['special']) { ?>
						<h4>
							<?php echo $product['price']; ?>&nbsp;
							<?php if ($product['discount']) { ?>
				      				<span style="text-decoration: line-through;color:#aaa;"><?php echo $product['original_price']; ?></span><span class="discount"><span class="text"><?php echo $product['discount']; ?>% Off</span><span class="img"></span></span>
		              		<?php } ?>
			            </h4>
	          			<?php } else { ?>
			          		<span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
			          <?php } ?>
			       </div>
		       <?php } ?>
              </div>
              <!-- 
		   	 <?php if ($product['enabled']) { ?>
		      <div class="cart-button">
		    	<div class="input-group">
					<input id="qty-<?php echo $product['product_id']; ?>" type="number" min="<?php echo $product['minimum']; ?>" value="<?php echo $product['minimum']; ?>" placeholder="" id="quantity" class="form-control quantity" />
					<div class="input-group-btn">
						<button class="btn btn-default" type="button" onclick="cart.add('<?php echo $product['product_id']; ?>', $('#qty-<?php echo $product['product_id']; ?>').val());"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
					</div>	
				</div>
			  </div>
			  <?php } elseif ('2'==$product['type']) { ?>
			  	<div class="cart-button">
					<a class="btn btn-default btn-block" type="button" href="<?php echo $product['href']; ?>" ><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md">Browse Products</span></a>
				</div>
			  <?php } else { ?>
			  	<div class="cart-button">
					<a class="btn btn-default btn-block" type="button" href="<?php echo $product['href']; ?>" ><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md">Add to Quotation</span></a>
				</div>
			  <?php } ?>
			   -->
              <!-- hello test change -->
              <div class="button-group button-group-2">
                <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
              </div>
            </div>