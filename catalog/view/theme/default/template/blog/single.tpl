<?php echo $header; ?><div id="columns">
	<div class="container">
  <?php if(count($breadcrumbs)) { ?>
		<ul class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
			<?php } ?>
		</ul>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
		<?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
		<div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
			<div class="col-sm-12 white" style="padding: 50px">
					<div class="media-body">
						<h2 class="media-heading"><?php echo ucfirst($post['title']); ?></h2>
						<h4>
							<span>
									By&nbsp;&nbsp;&nbsp;<?php echo author($post['post_author'],'firstname').' '.author($post['post_author'],'lastname'); ?>
								</span>
								&nbsp;&nbsp;
								<?php $time=strtotime($post['date_added']);?>
								<span><?php echo month_name(date('m',$time)); ?>&nbsp;<?php echo date('d',$time); ?>,&nbsp;<?php echo date('Y',$time); ?></span>
							&nbsp;&nbsp; <span><i class="fa fa-comment"></i> <?php echo $post['comment_count']; ?></span>
							&nbsp;&nbsp; <span><?php echo sprintf($text_view,$post['view']); ?></span>
						</h4>
						<h4>
							<div>
								<strong><?php echo $text_tag; ?></strong>
									<?php foreach ($post['tag'] as $tag) { ?>
										<a href="<?php echo $tag['link']; ?>"><?php echo $tag['tag']; ?></a>
									<?php } ?>
								</div>
						</h4>
					</div>
					<div>

						<div>
							<div>
							<?php echo html_entity_decode($post['content']); ?>
							<?php if ($post['images']) { ?>
								<div>
									<h2><?php echo $text_gallery_title; ?></h2>
									<ul class="list-unstyled">
										<?php foreach ($post['images'] as $image) { ?>
											<li><a href="<?php echo $image['url']; ?>" title=""><img
												src="<?php echo $image['image']; ?>" alt=""></a></li>
										<?php } ?>
									</ul>
								</div>
							<?php } ?>
							<div class="pull-left">
									<strong><?php echo $text_tag; ?>&nbsp;</strong>
                <?php foreach ($post['tag'] as $tag) { ?>
									<a href="<?php echo $tag['link']; ?>"><?php echo $tag['tag']; ?></a>
                <?php } ?>
              </div>
								<div class="addthis_native_toolbox social-bar pull-right"></div>
							</div>
						</div>
					</div>
				<?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-lg-6 col-md-6 col-sm-12 col-xs-12'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-lg-3 col-md-4 col-sm-6 col-xs-12'; ?>
        <?php } else { ?>
        <?php $class = 'col-lg-3 col-md-3 col-sm-6 col-xs-12'; ?>
        <?php } ?>
				
				<?php if ($related_products) { ?>
						<div class="col-sm-12">
						<h3><?php echo $title_related_product; ?></h3>
					</div>
					<div class="row">
							<?php foreach ($related_products as $product) { ?>
								<div class="product-layout product-grid col-xs-12 <?php echo $class;?>">
							<div class="product-thumb">
								<?php require(DIR_TEMPLATE.'default/template/common/product/productwdbfeatured.tpl'); ?>
							</div>
						</div>
							<?php } ?>
						</div>
				<?php } ?>
				
				<div class="clearfix"></div>  
				<?php if($related_posts) { ?> 
				<div class="panel panel-default">
						<div class="panel-heading">
							<h2 class="panel-title"><?php echo $text_related; ?></h2>
						</div>
						<div class="panel-body">
							<ul class="list-unstyled">
								<?php
					$inc = 0;
					foreach ( $related_posts as $related_post ) {
						?>
										<li class="post-list"><a
									href="<?php echo HTTP_SERVER; ?>index.php?route=blog/single&amp;post_id=<?php echo $post['ID']; ?>">
										<img src="<?php echo $related_post['post_thumbnail']; ?>"
										alt="">
										<h2><?php echo ucfirst($related_post['title']); ?></h2>
										<div>
											<strong><?php echo $text_author; ?></strong><span><?php echo author($related_post['post_author'],'username'); ?></span>
											&nbsp;|&nbsp;<strong><?php echo $text_publish; ?></strong><span><?php echo $post['date_added']; ?></span>
										</div>
										<p><?php echo words_limit(html_entity_decode($related_post['content']),$config['word_limit_in_related_post'],'...'); ?></p>
								</a></li>
								<?php
						$inc ++;
					}
					?>
							</ul>
						</div>
					</div>			
				<?php } ?>

			</div>
			<?php echo $content_bottom; ?>
		</div>
		<?php echo $column_right; ?>
  </div>
	</div>
</div><?php echo $footer; ?>
