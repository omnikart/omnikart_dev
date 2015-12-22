<?php //print_r($popular_posts); ?>
<div class="row">
	<div class="col-sm-12">
		<div class="col-sm-12 white">
		<div class="row">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#recent-post" data-toggle="tab"><?php echo $title_recent; ?></a></li>
				<li>
						<a href="#popular-post" data-toggle="tab"><?php echo $title_popular; ?></a></li>
			</ul>
		</div>	
			<div class="tab-content">

				<div class="tab-pane active tab-post" id="recent-post">
					<?php if( count ($recent_posts) > 0 ) { ?>
						<div class="col-sm-12">
						<?php 
						$inc = 0;
						foreach ($recent_posts as $post) { ?>
						<div class="col-sm-4 white">
							<h3 class="post-title"><a href="index.php?route=blog/single&amp;pid=<?php echo $post['ID']; ?>" title=""><?php echo words_limit($post['title'],$limit_title,'...'); ?></a></h3>
							
							<?php if($thumbnail_show_recent) { ?>
								<div class="pull-left clearfix" style="padding:10px;">
										<?php if($thumbnail_type_recent == 'static') : ?>
											<img class="post-img" src="<?php echo $thumbnail_recent[$inc]; ?>" alt="...">
										<?php elseif($thumbnail_type_recent == 'slide') : ?>
												<?php if(isset($post_images_recent[$post['ID']])) : ?>
													<ul class='thumbslider list-unstyled'>
														<?php for ($i=0; $i < count($post_images_recent[$post['ID']]); $i++) : ?>
															<li><img src="<?php echo $post_images_recent[$post['ID']][$i]; ?>" alt=""></li>
														<?php endfor; ?>
													</ul>
												<?php else: ?>
														<img class="post-img" src="<?php echo $thumbnail_recent[$inc]; ?>" alt="...">
												<?php endif; ?>
										<?php endif; ?>
								</div>
								<?php } ?>
								
							<div>
								<?php 
								$decoded_content = html_entity_decode($post['content']);
								$stripted_content = strip_tags($decoded_content);
								$limited_content = words_limit($stripted_content, $limit_content, '...');
								?>
								<?php echo $limited_content; ?>
							</div>
						</div>
						<?php $inc++;	} ?>
						</div>
					<?php } else {  ?>
						<div class="alert alert-warning"><?php echo $not_found_recent; ?></div>
					<?php } ?>
				</div>
				<!-- #recent-post -->

				<div class="tab-pane tab-post" id="popular-post">
					<?php if( count ($popular_posts) > 0 ) { ?>

						<?php $inc = 0;	foreach ($popular_posts as $post) { ?>
						<div>
							<div class="row">
								<?php if($thumbnail_show_recent) { ?>
										<div class="col-sm-2 clearfix">
												<?php if($thumbnail_type_recent == 'static') : ?>
													<img class="post-img" src="<?php echo $post['thumb']; ?>" alt="...">
												<?php elseif($thumbnail_type_recent == 'slide') : ?>
														<?php if(isset($post_images_recent[$post['ID']])) : ?>
															<ul class='thumbslider list-unstyled'>
																<?php for ($i=0; $i < count($post_images_recent[$post['ID']]); $i++) : ?>
																	<li><img src="<?php echo $post_images_recent[$post['ID']][$i]; ?>" alt=""></li>
																<?php endfor; ?>
															</ul>
														<?php else: ?>
																<img class="post-img" src="<?php echo $thumbnail_recent[$inc]; ?>" alt="...">
														<?php endif; ?>
												<?php endif; ?>
										</div>
								<?php } ?>
								<div class="col-sm-10">
									<h3><a href="index.php?route=blog/single&amp;pid=<?php echo $post['ID']; ?>" title=""><?php echo words_limit($post['title'],$limit_title,'...'); ?></a></h3>
									
									<div class="text">
										<?php 
										$decoded_content = html_entity_decode($post['content']);
										$stripted_content = strip_tags($decoded_content);
										$limited_content = words_limit($stripted_content, 35, '...');
										?>
										<?php echo $limited_content; ?>
									</div>
								</div>
							</div>
						</div>
						<?php $inc++;	} ?>

					<?php } else { ?>
						<div class="alert alert-warning"><?php echo $not_found_popular; ?></div>
					<?php } ?>
				</div>
		</div>
	</div>
</div>
