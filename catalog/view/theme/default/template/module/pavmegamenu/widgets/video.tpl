<?php if ( isset($video_link) ) { ?>
<div class="widget box widget-video">
	<?php if ( $show_title ) {?>
	<div class="widget-heading">
		<?php echo $heading_title; ?>
	</div>
	<?php } ?>
	<div class="widget-inner  video-responsive">
		<iframe src="<?php echo $video_link ?>" style="width:<?php echo $width ?>;height:<?php echo $height ?>px;" allowfullscreen="true"></iframe>
		<?php if ( $subinfo ) { ?>
		<div><?php $subinfo ?></div>
		<?php } ?>
	</div>
</div>
<?php } ?>