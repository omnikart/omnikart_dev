<div class="row">
	<div class="col-sm-<?php echo 12-$margin_left; ?>" <?php if ($status_side) echo 'style="padding-right:0"';?> >
		<div id="slideshow<?php echo $module; ?>" class="owl-carousel" style="opacity: 1;">
		  <?php foreach ($banners as $banner) { ?>
		  <div class="item">
		    <?php if ($banner['link']) { ?>
		    <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" /></a>
		    <?php } else { ?>
		    <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" />
		    <?php } ?>
		  </div>
		  <?php } ?>
		</div>
	</div>
	<?php if ($status_side) { ?>
		<div class="col-sm-<?php echo $margin_left; ?>" style="padding-left:0">
			<?php foreach ($side_banners as $banner) { ?>
			  <div class="item">
			    <?php if ($banner['link']) { ?>
			  		<?php if (substr($banner['link'], 0, 1) === '#') { ?>
			  			<a href="#" data-toggle="modal" data-target="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" /></a>
			  		<?php } else { ?>
			    		<a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" /></a>
			    	<?php } ?>
			    <?php } else { ?>
			    <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" />
			    <?php } ?>
			  </div>
		    <?php } ?>
		</div>
	<?php } ?>
</div>
<script type="text/javascript"><!--
$('#slideshow<?php echo $module; ?>').owlCarousel({
	items: 6,
	autoPlay: 3000,
	singleItem: true,
	navigation: true,
	navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
	pagination: true
});
--></script>
