<?php $olang = $this->registry->get('language'); ?>
<div class="product-block auto" itemtype="http://schema.org/Product" itemscope>	
	<?php if ($product['thumb']) {    ?>
		<div class="image">
			<?php if( $product['special'] ) {   ?>
			<span class="product-label product-label-special"><span><?php echo $olang->get( 'text_sale' ); ?></span></span>
			<?php } ?>
			<a class="img" itemprop="url" title="<?php echo $product['name']; ?>" href="<?php echo $product['href']; ?>">
				<img class="img-responsive" itemprop="image" src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" />
			</a>
				<?php if ( isset($quickview) && $quickview ) { ?>
				<a class="pav-colorbox btn btn-dark" href="<?php echo $this->url->link("themecontrol/product",'product_id='.$product['product_id'] );?>" title="<?php echo $olang->get('quick_view'); ?>"><i class="fa fa-eye"></i>
					<span><?php echo $olang->get('quick_view'); ?></span>
				</a>
				<?php } ?>	
		
		</div>
	<?php } ?>
					 
	<div class="product-meta">		  
		<div class="left">
			<h3 class="name" itemprop="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h3>
	        <div class="clearfix"></div>
	        <?php if( isset($product['description']) ){ ?> 
			<p class="description" itemprop="description"><?php echo utf8_substr( strip_tags($product['description']),0,220);?>...</p>
			<?php } ?>						
			<?php if ($product['price']) { ?>
			<div class="price clearfix" itemtype="http://schema.org/Offer" itemscope itemprop="offers">
				<?php if (!$product['special']) {  ?>
					<span class="special-price"><?php echo $product['price']; ?></span>
					<?php if( preg_match( '#(\d+).?(\d+)#',  $product['price'], $p ) ) { ?> 
					<meta content="<?php echo $p[0]; ?>" itemprop="price">
					<?php } ?>
				<?php } else { ?>
					<span class="price-new"><?php echo $product['special']; ?></span>
					<span class="price-old"><?php echo $product['price']; ?></span> 
					<?php if( preg_match( '#(\d+).?(\d+)#',  $product['special'], $p ) ) { ?> 
					<meta content="<?php echo $p[0]; ?>" itemprop="price">
					<?php } ?>

				<?php } ?>
				<?php if ( isset($product['tax']) && $product['tax']) { ?>	        
					<span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
				<?php } ?>

				<meta content="<?php echo $this->currency->getCode(); ?>" itemprop="priceCurrency">
			</div>
			<?php } ?>
			<?php if ( isset($product['rating']) && $product['rating'] ) { ?>
				    <div class="rating">
				    <img src="catalog/view/theme/<?php echo PAVO_THEME_NAME;?>/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['rating']; ?>"></div>
				    <?php } else { ?>
			        <div class="norating"><img alt="0" src="catalog/view/theme/<?php echo PAVO_THEME_NAME;?>/image/stars-0.png"></div>
			<?php } ?>	
		</div>

		<div class="right">		
			<div class="action">
				<?php if( !isset($listingConfig['catalog_mode']) || !$listingConfig['catalog_mode'] ) { ?>
				<div class="cart">						
					<a class="" onclick="addToCart('<?php echo $product['product_id']; ?>');">
						<span><i class="fa fa-shopping-cart"></i></span>
						<span><?php echo $olang->get("button_cart"); ?></span>
					</a>
				</div>
				<?php } ?>
				<div class="compare">			
					<a class="btn btn-dark"  onclick="addToCompare('<?php echo $product['product_id']; ?>');" title="<?php echo $button_compare; ?>">
						<i class="fa fa-retweet"></i>
						<span><?php echo $button_compare; ?></span>
					</a>	
				</div>	
				<div class="wishlist">
					<a class="btn btn-dark" onclick="addToWishList('<?php echo $product['product_id']; ?>');" title="<?php echo $button_wishlist; ?>">
						<i class="fa fa-heart"></i>
						<span><?php echo $button_wishlist; ?></span>
					</a>	
				</div>
				<div class="product-zoom">
				<?php if( isset($categoryPzoom) && $categoryPzoom ) { $zimage = str_replace( "cache/","", preg_replace("#-\d+x\d+#", "",  $product['thumb'] ));  ?>
			<a href="<?php echo $zimage;?>" class="info-view colorbox btn btn-dark cboxElement" title="<?php echo $product['name']; ?>"><i class="fa fa-search-plus"></i><span><?php echo $olang->get('zoom_product'); ?></span></a>
			<?php } ?>	
				</div>
			</div>			 
		</div>

	</div>		 
</div>





