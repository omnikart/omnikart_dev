<?php //print_r($popular_posts); ?>
<div class="row">
	<div class="col-sm-12">
	<h2>Recent Blogs</h2>
	</div>

	<?php if( count ($recent_posts) > 0 ) { ?>
		<?php	$inc = 0;	foreach ( $recent_posts as $post ) {?>
		  <div class="col-sm-6 col-md-4">
	    	<div class="thumbnail">
		      <a style="color: black" href="<?php echo $post['link']; ?>"><img src="<?php echo $post['thumb']; ?>" alt="..."></a>
		      <div class="caption">
		        <a style="color: black" href="<?php echo $post['link']; ?>"><h3><?php echo ucfirst($post['title']); ?></h3></a>
		        <p>
		        	<span>By&nbsp;&nbsp;&nbsp;<?php echo author($post['post_author'],'firstname').' '.author($post['post_author'],'lastname'); ?></span>
					&nbsp;&nbsp;<?php $time=strtotime($post['date_added']);?><span><?php echo month_name(date('m',$time)); ?>&nbsp;<?php echo date('d',$time); ?>,&nbsp;<?php echo date('Y',$time); ?></span>
		        </p>
		        <p>
		        	<?php echo words_limit(html_entity_decode($post['excerpt']),40,'...'); ?>
		        </p>
		      </div>
		    </div>
		  </div>
		<?php $inc++;	} ?>
	<?php } else {  ?>
	<div class="alert alert-warning"><?php echo $not_found_recent; ?></div>
	<?php } ?>
</div>