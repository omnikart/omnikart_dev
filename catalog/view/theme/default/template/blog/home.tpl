<?php echo $header; ?><div id="columns">
<div class="container">
	<ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		<?php } ?>
	</ul>
    <div class="row"><?php echo $column_left; ?>
		<?php if ($column_left && $column_right) { ?>
    	<?php $class = 'col-sm-6'; ?>
    	<?php } elseif ($column_left || $column_right) { ?>
    	<?php $class = 'col-sm-9'; ?>
    	<?php } else { ?>
    	<?php $class = 'col-sm-12'; ?>
    	<?php } ?>
		<div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
			<div class="col-sm-12 white">
				<?php if(isset($posts) && $posts) { ?>
				<div class="row">
				<?php $inc = 0;
				foreach ($posts as $post) { ?>
					<div class="media">
  						<div class="media-left">
      						<img class="media-object" src="<?php echo $post['post_thumbnail']; ?>" alt="">
  						</div>
  						<div class="media-body">
    						<h2 class="media-heading"><a style="color:black" href="<?php echo $post['link']; ?>"><?php echo ucfirst($post['title']); ?></a></h2>
    						<h4>
								<span>
									By&nbsp;&nbsp;&nbsp;<?php echo author($post['post_author'],'firstname').' '.author($post['post_author'],'lastname'); ?>
								</span>
								&nbsp;&nbsp;
								<?php $time=strtotime($post['date_added']);?>
								<span><?php echo month_name(date('m',$time)); ?>&nbsp;<?php echo date('d',$time); ?>,&nbsp;<?php echo date('Y',$time); ?></span>
								&nbsp;&nbsp;
								<span><i class="fa fa-comment"></i> <?php echo $post['comment_count']; ?></span>
								&nbsp;&nbsp;
								<span><?php echo sprintf($text_view,$post['view']); ?></span>
							</h4>
							<p>
    							<?php echo $post['excerpt']; ?>
    						</p>
    						<h4>
    							<div>
									<strong><?php echo $text_tag; ?></strong>
									<?php foreach ($post['tag'] as $tag) { ?>
										<a href="<?php echo $tag['link']; ?>"><?php echo $tag['tag']; ?></a>
									<?php } ?>
								</div>
							</h4>
							<h4>						
								<i><a href="<?php echo $post['link']; ?>"><?php echo $text_readmore; ?></a></i>
							</h4>
  						</div>
					</div>			
				<?php $inc++;	} ?>
				<?php echo $pagination; ?>
				</div>
				<?php } else { ?>
					<div class="alert alert-danger">
						<?php echo $not_found; ?>
					</div>
				<?php } ?>
			</div>
		<?php echo $content_bottom; ?>
		</div>
	<?php echo $column_right; ?>
	</div>
</div>
<script><!--

--></script>
</div><?php echo $footer; ?>
