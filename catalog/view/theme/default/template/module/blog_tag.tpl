<?php if($tag_layout == 'rotation'): ?>
<div class="widget blog-tags">
	<div class="widget-header">
		<h2 class="widget-title">
			<span class="<?php echo $titleicon; ?>"></span>&nbsp;<?php echo $title; ?></h2>
	</div>
	<div class="widget-body content">

    		<?php if(is_array($tags) && count($tags) > 0): ?>
            <ul class="weighted" style="font-size: 50%; display: none;"
			id="weightTags0">   
    			<?php for ($i=0; $i < count($tags); $i++) : ?> 
    				<?php
			
if (isset ( $tags [$i] )) :
				$data_weight = rand ( 1, 100 );
				?>
    					<li><a
				href="<?php echo HTTP_SERVER; ?>index.php?route=blog/tag&amp;tag=<?php echo $tags[$i]; ?>"
				title="" data-weight="<?php echo $data_weight; ?>"
				style="font-size: 3.16ex"><?php echo ucfirst($tags[$i]); ?></a></li>
    				<?php endif; ?>
    			<?php endfor; ?>
    		</ul>
		<canvas id="tagcanvas0"
			style="margin-bottom: 20px; width: 100%; min-height: 200px"></canvas>             
    		<?php else : ?>
    			<div class="alert alert-warning"><?php echo $not_found; ?></div>
    		<?php endif; ?>

    	</div>
	<!-- .widget-body -->
</div>
<!-- .tags -->

<script type="text/javascript"> 
        window.onload = function() {
            TagCanvas.interval = 20;
            TagCanvas.textFont = 'oswald,Arial Black,sans-serif';
            TagCanvas.textColour = '#00f';
            TagCanvas.textHeight = 15;
            TagCanvas.outlineColour = '#f96';
            TagCanvas.outlineThickness = 5;
            TagCanvas.maxSpeed = 0.04;
            TagCanvas.minBrightness = 0.1;
            TagCanvas.depth = 0.92;
            TagCanvas.pulsateTo = 0.2;
            TagCanvas.pulsateTime = 0.75;
            TagCanvas.initial = [0.1, -0.1];
            TagCanvas.decel = 0.98;
            TagCanvas.reverse = true;
            TagCanvas.hideTags = false;
            TagCanvas.shadow = '#ccf';
            TagCanvas.shadowBlur = 3;
            TagCanvas.weight = true;
            TagCanvas.weightFrom = 'data-weight';
            TagCanvas.fadeIn = 800;
            try {
                TagCanvas.Start('tagcanvas0', 'weightTags0', wOpts.colour);           
            } catch (e) {
        }
        };
        var g1 = {
            0: 'red',
            0.5: 'orange',
            1: 'rgba(0,0,0,0.1)'
        }, wOpts = {
            none: {weight: false},
            size: 12,
            colour: {weightMode: 'colour'},
            both: {weightMode: 'both'},
            bgcolour: {weightMode: 'bgcolour', padding: 2, bgRadius: 5},
            bgoutline: {weightMode: 'bgoutline', bgOutlineThickness: 3, padding: 2, bgRadius: 5},
        };
    </script>
<?php else: ?>

<div class="widget blog-tags">
	<div class="widget-header">
		<h2 class="widget-title">
			<span class="<?php echo $titleicon; ?>"></span>&nbsp;<?php echo $title; ?></h2>
	</div>
	<div class="widget-body content">

            <?php if(is_array($tags) && count($tags) > 0): ?>
            <ul class="list-unstyled">   
                <?php for ($i=0; $i < count($tags); $i++) : ?> 
                    <?php
			
if (isset ( $tags [$i] )) :
				$data_weight = rand ( 1, 100 );
				?>

                    <?php $random_fontsize = rand(12, 18); ?>
                    <li
				class="<?php echo ($tags[$i] == $selected) ? 'active' : null; ?>"><a style="font-size: <?php echo $random_fontsize; ?>px" href="<?php echo HTTP_SERVER; ?>index.php?route=blog/tag&amp;tag=<?php echo $tags[$i]; ?>" title="" data-weight="<?php echo $data_weight; ?>"><?php echo ucfirst($tags[$i]); ?></a>
			</li>
                    <?php endif; ?>
                <?php endfor; ?>
            </ul>            
            <?php else : ?>
                <div class="alert alert-warning"><?php echo $not_found; ?></div>
            <?php endif; ?>

        </div>
	<!-- .widget-body -->
</div>
<!-- .tags -->

<?php endif; ?>

<style type="text/css">
<?
php
 
echo 

html_entity_decode ( $custom_style );

?>
</style>

<script type="text/javascript">
	<?php echo html_entity_decode($custom_script); ?>
</script>
