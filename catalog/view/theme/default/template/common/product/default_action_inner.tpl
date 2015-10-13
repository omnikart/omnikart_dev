<?php
 $this->language->load('module/themecontrol');
 ?>
 <div class="product-block item-default item-default-action-inner" itemtype="http://schema.org/Product" itemscope>
    
    <div class="block-img">
        <?php if ($product['thumb']) {    ?>
            <?php if( $product['special'] ) {   ?>
            <span class="product-label ribbon1">
                <span class="product-label-special"><?php echo $this->language->get( 'text_sale' ); ?></span>
            </span>
        <?php } ?>

            <div class="image">
                <a class="img" itemprop="url" title="<?php echo $product['name']; ?>" href="<?php echo $product['href']; ?>">
                    <img class="img-responsive" itemprop="image" src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" />
                </a>
            </div>
        <?php } ?>


        <!-- button active -->
         <div class="action">
             <div class="quick-view">
                 <?php //#2 Start fix quickview in fw?>
                 <?php if ( isset($quickview) && $quickview ) { ?>
                     <a data-toggle="tooltip" data-placement="top" title="<?php echo $this->language->get('quick_view'); ?>" class="pav-colorbox btn btn-default" href="<?php echo $this->url->link("themecontrol/product",'product_id='.$product['product_id'] );?>">
                        <i class="fa fa-eye"></i>
                        <!-- <span><?php echo $this->language->get('quick_view'); ?></span> -->
                     </a>
                 <?php } ?>
                 <?php //#2 End fix quickview in fw?>
             </div>
             <div class="wishlist">
                <a data-toggle="tooltip" data-placement="top" onclick="addToWishList('<?php echo $product['product_id']; ?>');" title="<?php echo $this->language->get("button_wishlist"); ?>" class="btn btn-default">
                <i class="fa fa-heart"></i>
                </a>
            </div>

            <div class="compare">
                <a data-toggle="tooltip" data-placement="top" title="<?php echo $this->language->get("button_compare"); ?>" class="btn btn-default" onclick="addToCompare('<?php echo $product['product_id']; ?>');">
                    <i class="fa fa-refresh"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="product-meta">

        <div class="meta">
            <h3 class="name" itemprop="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h3>
            <?php if( isset($product['description']) ){ ?>
                <p class="description" itemprop="description"><?php echo utf8_substr( strip_tags($product['description']),0,220);?>...</p>
            <?php } ?>
            <?php if ( isset($product['rating']) && $product['rating'] ) { ?>
                <div class="rating"><img src="catalog/view/theme/<?php echo $this->config->get('config_template');?>/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['rating']; ?>"></div>
            <?php } else { ?>
            <div class="norating"><img alt="0" src="catalog/view/theme/<?php echo $this->config->get('config_template');?>/image/stars-0.png"></div>
            <?php } ?>

            <?php if ($product['price']) { ?>
            <div class="price" itemtype="http://schema.org/Offer" itemscope itemprop="offers">
                <?php if (!$product['special']) {  ?>
                    <span class="special-price"><?php echo $product['price']; ?></span>
                    <?php if( preg_match( '#(\d+).?(\d+)#',  $product['price'], $p ) ) { ?>
                    <meta content="<?php echo $p[0]; ?>" itemprop="price">
                    <?php } ?>
                <?php } else { ?>
                    <span class="price-old"><?php echo $product['price']; ?></span>
                    <span class="price-new"><?php echo $product['special']; ?></span>

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
        </div>
    </div>

    <?php if( !isset($listingConfig['catalog_mode']) || !$listingConfig['catalog_mode'] ) { ?>
     <div class="cart">
         <button onclick="addToCart('<?php echo $product['product_id']; ?>');" class="btn btn-default">
             <!-- <i class="fa fa-shopping-cart"></i> -->
             <span><?php echo $this->language->get("button_cart"); ?></span>
         </button>
     </div>
    <?php } ?>
</div>