<?php
$span = 12/$cols;
$id = rand(1,9).substr(md5($title),0,3).$module;
$themeConfig = (array)$objconfig->get('themecontrol');
$listingConfig = array(
	'category_pzoom'                     => 1,
	'quickview'                          => 0,
	'show_swap_image'                    => 0,
	'product_layout'		             => 'default',
	'enable_paneltool'	                 => 0
);
$listingConfig      = array_merge($listingConfig, $themeConfig );
$categoryPzoom 	    = $listingConfig['category_pzoom'];
$quickview          = $listingConfig['quickview'];
$swapimg            = $listingConfig['show_swap_image'];
$categoryPzoom      = isset($themeConfig['category_pzoom']) ? $themeConfig['category_pzoom']:0;
$theme              = $objconfig->get('config_template');
if( $listingConfig['enable_paneltool'] && isset($_COOKIE[$theme.'_productlayout']) && $_COOKIE[$theme.'_productlayout'] ){
	$listingConfig['product_layout'] = trim($_COOKIE[$theme.'_productlayout']);
}
$productLayout = DIR_TEMPLATE.$objconfig->get('config_template').'/template/common/product/'.$listingConfig['product_layout'].'.tpl';
if( !is_file($productLayout) ){
	$listingConfig['product_layout'] = 'default';
}
$productLayout = DIR_TEMPLATE.$objconfig->get('config_template').'/template/common/product/'.$listingConfig['product_layout'].'.tpl';
?>
<div class="row">
<div class="col-sm-12 vertical clearfix <?php echo $prefix_class; ?>">
	<div class="col-sm-12 white">
		<div class="row">
			<div class="col-sm-12">
				<nav class="navbar navbar-default box-heading">
					<div class="navbar-header">
						<span><a class="img-banner" href="<?php echo $category_link; ?>"><?php echo $category_name; ?></a></span>
	                </div>
					<ul class="nav pull-right" id="pav-categorytabs<?php echo $id;?>" role="tablist">
						<?php foreach ($tabs as $key=>$tab): ?>
							<li><a href="<?php echo $tab['href']; ?>"><?php echo $tab['name']; ?></a></li>
						<?php endforeach; ?>
							<li><a href="<?php echo $category_link; ?>">More...</a></li>
					</ul>
				</nav>
			</div>
			<div class="col-sm-12">
			</div>
			<div class="col-sm-3">
				<div class="banner-image hidden-md hidden-sm hidden-xs">
					<a class="img-banner" href="<?php echo $category_link; ?>">
						<?php if (!empty($image) ) { ?><img class="img-responsive" src="<?php echo $image;?>" alt="<?php echo $category_name; ?>"/><?php } ?>
					</a>
				</div>
			</div>
			<div class="col-sm-9">
				<div class="flexslider featured" id="pav<?php echo $module; ?>">
					<ul class="slides">
					<?php foreach( array_chunk($products,4) as $productl ) { ?>
						<li style="margin-right:20px;"><div class="row">
							<?php foreach($productl as $i => $product ) { ?>
								<div class="col-xs-3 product-col">
									<?php require( $productLayout );  ?>
								</div>
							<?php } ?>
							</div>
						</li>
					<?php } //endforeach; ?>
					</ul>
				</div>
			</div>
		</div>	
	</div><!-- end div.box -->
</div>
</div>
<script>
$('#pav<?php echo $module; ?>').flexslider({
	  animation: "slide",
	  controlNav: false,
	  directionNav: true,
	  itemWidth: 25,
	  nextText: "",
	  prevText: "",
    itemMargin: -1,
    minItems: 1, // use function to pull in initial value
    maxItems: 1,
	  useCSS: false /* Chrome fix*/
	});
</script>
<style>
.products-row .product-col:last-child{border-right:none;}
.vertical .nav > li {float:left;}
.vertical .nav > li > a{padding:15px 10px;}
.vertical .product-thumb{margin-bottom:0px;}
.vertical .product-block .img img{width:initial;margin:0px auto;}
</style>
