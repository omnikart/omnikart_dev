<?php $olang = $this->registry->get('language');  $ourl = $this->registry->get('url');   ?>
<div class="product-block item-default clearfix"
	itemtype="http://schema.org/Product" itemscope>
    <?php if ($product['thumb']) {    ?>
        <?php if( $product['special'] ) {   ?>
        <span class="product-label ribbon"><span
		class="product-label-special"><?php echo $olang->get( 'text_sale' ); ?></span></span>
        <?php } ?>
        <div class="image clearfix">
		<a class="img" itemprop="url" title="<?php echo $product['name']; ?>"
			href="<?php echo $product['href']; ?>"> <img class="img-responsive"
			itemprop="image" src="<?php echo $product['thumb']; ?>"
			title="<?php echo $product['name']; ?>"
			alt="<?php echo $product['name']; ?>" />
		</a>

            <?php if ( isset($quickview) && $quickview ) { ?>
            <a class="pav-colorbox btn btn-inverse iframe-link"
			data-toggle="tooltip"
			href="<?php echo $ourl->link('themecontrol/product','product_id='.$product['product_id']);?>"
			title="<?php echo $olang->get('quick_view'); ?>"><i class="fa fa-eye"></i></a>
            <?php } ?>

            <?php if( isset($categoryPzoom) && $categoryPzoom ) { $zimage = str_replace( "cache/","", preg_replace("#-\d+x\d+#", "",  $product['thumb'] ));  ?>
            <a href="<?php echo $zimage;?>"
			class="btn btn-inverse colorbox product-zoom cboxElement"
			title="<?php echo $product['name']; ?>"><i class="fa fa-search-plus"></i></a>
            <?php } ?>
        </div>
    <?php } ?>

    <div class="product-meta">
		<div>
			<div class="left">
				<div class="review clearfix">
					<h3 class="name" itemprop="name">
						<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
					</h3>

                    <?php 
/*
						       * if ( isset($product['rating']) ) { ?>
						       * <div class="rating">
						       * <?php for ($is = 1; $is <= 5; $is++) { ?>
						       * <?php if ($product['rating'] < $is) { ?>
						       * <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
						       * <?php } else { ?>
						       * <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i>
						       * </span>
						       * <?php } ?>
						       * <?php } ?>
						       * </div>
						       * <?php }
						       */
																				?>
                </div>

                <?php if ($product['price']) { ?>
                <div class="price clearfix"
					itemtype="http://schema.org/Offer" itemscope itemprop="offers">
                    <?php if (!$product['special']) {  ?>
	                    	<span class="special-price"><?php echo $product['price']; ?></span>
	                    <?php if( preg_match( '#(\d+).?(\d+)#',  $product['price'], $p ) ) { ?>
	                    	<meta content="<?php echo $p[0]; ?>"
						itemprop="price">
	                    <?php } ?>
                    <?php } else { ?>
	                    <span class="price-new"><?php echo $product['special']; ?></span>
					<span class="price-old"><?php echo $product['price']; ?></span>
	                    <?php if( preg_match( '#(\d+).?(\d+)#',  $product['special'], $p ) ) { ?>
	                    <meta content="<?php echo $p[0]; ?>"
						itemprop="price">
	                    <?php } ?>
                    <?php } ?>

                    <meta
						content="<?php // echo $this->currency->getCode(); ?>"
						itemprop="priceCurrency">
				</div>
                <?php } ?>
                <div class="clearfix"></div>
                <?php if( isset($product['description']) ){ ?>
                <p class="description" itemprop="description"><?php echo utf8_substr( strip_tags($product['description']),0,220);?>...</p>
                <?php } ?>
            </div>
		</div>
	</div>
   	<?php if ($product['discount']) { ?>
   		<span class="discount"><span class="text"><?php echo $product['discount']; ?>% Off</span><span
		class="img"></span></span>
   	<?php } ?>
</div>
