<div class="row">
	<div class="col-sm-12">
			<h2><?php echo $heading_title; ?></h2>
			<div class="row">
				<div class="flexslider featured" id="featured<?php echo $module; ?>">
					<ul class="slides">
	  <?php foreach ($products as $product) { ?>
	  <li>
		  <div class="product-layout col-xs-12">
		    <div class="product-thumb transition">
				<?php require(DIR_TEMPLATE.'default/template/common/product/productwdbfeatured.tpl'); ?>
		    </div>
		  </div>
		 </li>

	  <?php } ?>
	</ul>
				</div>
			</div>
	</div>
</div>
<script type="text/javascript"><!--
$('#featured<?php echo $module; ?>').flexslider({
	  animation: "slide",
	  controlNav: false,
	  directionNav: true,
	  itemWidth: 25,
	  nextText: "",
	  prevText: "",
      itemMargin: -1,
      minItems: 5, // use function to pull in initial value
      maxItems: 5,
	  useCSS: false /* Chrome fix*/
	});
--></script>
<style>
.featured .product-thumb {
	margin-bottom: 0;
}
</style>