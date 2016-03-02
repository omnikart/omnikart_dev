<div class="row">
	<div class="col-sm-<?php echo 12-$margin_left; ?>">
		<div id="slideshow<?php echo $module; ?>" class="flexslider" style="opacity: 1;">
			<ul class="slides">
			  <?php foreach ($banners as $banner) { ?>
			  <li>
			    <?php if ($banner['link']) { ?>
			    <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo implode(',',$banner['title']); ?>" class="img-responsive" /></a>
			    <?php } else { ?>
			    <img src="<?php echo $banner['image']; ?>" alt="<?php echo implode(',',$banner['title']); ?>" class="img-responsive" />
			    <?php } ?>
			  </li>
			  <?php } ?>			
			</ul>

		</div>
		<?php if ($status_side) { ?>
			<div id="side-slide" class="" style="padding-left: 0">
				<?php foreach ($side_banners as $banner) { ?>
				  <div class="item corner-fold">
				    <?php if ($banner['link']) { ?>
				  		<?php if (substr($banner['link'], 0, 1) === '#') { ?>
				  			<a href="#" data-toggle="modal" data-target="<?php echo $banner['link']; ?>">
				  				<img src="<?php echo $banner['image']; ?>"	alt="<?php echo $banner['title']; ?>" class="img-responsive" /></a>
				  		<?php } else { ?>
				    		<a href="<?php echo $banner['link']; ?>">
				    			<img	src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" /></a>
				    	<?php } ?>
				    <?php } else { ?>
				    	<img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" />
				    <?php } ?>
				  </div>
			    <?php } ?>
			</div>
		<?php } ?>
	</div>
</div>

<script type="text/javascript"><!--
$('#slideshow<?php echo $module; ?>').flexslider({
	  animation: "slide",
	  directionNav: true,
	  controlNav: true,
	  useCSS: false /* Chrome fix*/
	});
--></script>
