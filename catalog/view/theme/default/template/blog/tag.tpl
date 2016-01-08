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
					<div class="col-sm-12">
						<h2 class="nmt"><a href="<?php echo $post['link']; ?>"><i class="fa fa-pencil"></i> <?php echo ucfirst($post['title']); ?></a></h2>
						<?php $time = strtotime($post['date_added']); ?>
						<div>
							<span><?php echo date('d',$time); ?>, <?php echo month_name(date('m',$time)); ?>, <?php echo date('Y',$time); ?></span>
							<span><?php echo date('h',$time); ?>:<?php echo date('i',$time); ?>:<?php echo date('s',$time); ?></span>
						</div>
						<div>
							<div>
								<span><?php echo $text_author; ?><?php echo author($post['post_author'],'username'); ?></span>
								<span><?php echo $text_comment; ?><?php echo $post['comment_count']; ?></span>
								<span><?php echo sprintf($text_view,$post['view']); ?></span>
							</div>
							<div class="row">
								<div class="col-sm-3">
									<a class="image" href="<?php echo $post['link']; ?>">
										<img src="<?php echo $post['post_thumbnail']; ?>" alt="">
									</a>
									<div class="images">
										<?php foreach($post['images'] as $image) { ?>
											<img src="<?php echo $image; ?>" height="30px" width="30px" alt="">
										<?php } ?>
									</div>
								</div>
								<div class="col-sm-9">
									<?php echo $post['content']; ?>
								</div>
							</div>
							<div>
									<strong><?php echo $text_tag; ?></strong>
									<?php foreach ($post['tag'] as $tag) { ?>
										<a href="<?php echo $tag['link']; ?>"><?php echo $tag['tag']; ?></a>
									<?php } ?>
							</div>
						</div>
							<a href="<?php echo $post['link']; ?>"><?php echo $text_readmore; ?></a>
					</div>
					<hr/ >
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
</div><?php echo $footer; ?>
