<div class="row">
	<div class="col-sm-<?php echo 12-$margin_left; ?>" <?php if ($status_side) echo 'style="padding-right:0"';?> >
		<div id="slideshow<?php echo $module; ?>" class="flexslider" style="opacity: 1;">
			<ul class="slides">
			  <?php foreach ($banners as $banner) { ?>
			  <li>
			    <?php if ($banner['link']) { ?>
			    <a href="<?php echo $banner['link']; ?>" ><img src="<?php echo $banner['image']; ?>" alt="<?php echo implode(',',$banner['title']); ?>" class="img-responsive" /></a>
			    <?php } else { ?>
			    <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" />
			    <?php } ?>
			  </li>
			  <?php } ?>			
			</ul>
		</div>
		<div class="flexslider-controls">
		  <ol class="flex-control-nav">
	  		  <?php foreach ($banners as $banner) { ?>
			    <li><?php echo $banner['title'][0]; ?><br/><?php echo (isset($banner['title'][1])?$banner['title'][1]:''); ?></li>
			  <?php } ?>
		  </ol>
		</div>
	</div>
	<?php if ($status_side) { ?>
		<div class="col-sm-<?php echo $margin_left; ?>" style="padding-left:0">
			<?php foreach ($side_banners as $banner) { ?>
			  <div class="item corner-fold">
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
<style>
.flex-control-nav li{
width: <?php echo 100/count($banners); ?>%;
}
</style>

<script type="text/javascript"><!--
$('#slideshow<?php echo $module; ?>').flexslider({
	  animation: "slide",
	  directionNav: false,
	  manualControls: ".flex-control-nav li",
	  useCSS: false /* Chrome fix*/
	});
--></script>
