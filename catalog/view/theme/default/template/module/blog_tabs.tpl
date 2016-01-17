<?php //print_r($popular_posts); ?>
<div class="row">
	<div class="col-sm-12">
		<div class="col-sm-12 white">
			<div class="row">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#recent-post" data-toggle="tab"><?php echo $title_recent; ?></a></li>
					<li><a href="#popular-post" data-toggle="tab"><?php echo $title_popular; ?></a></li>
				</ul>
			</div>
			<div class="tab-content">

				<div class="tab-pane active tab-post" id="recent-post">
					<?php if( count ($recent_posts) > 0 ) { ?>
					<div class="col-sm-12">
						<?php
						$inc = 0;
						foreach ( $recent_posts as $post ) {
							?>
						<div class="media-left">
							<img class="media-object" src="<?php echo $post['thumb']; ?>" alt="">
						</div>
						<div class="media-body">
							<h3 class="media-heading">
								<a style="color: black" href="<?php echo $post['link']; ?>"><?php echo ucfirst($post['title']); ?></a>
							</h3>
							<h5>
								<span>
											By&nbsp;&nbsp;&nbsp;<?php echo author($post['post_author'],'firstname').' '.author($post['post_author'],'lastname'); ?>
											</span>
											&nbsp;&nbsp;
											<?php $time=strtotime($post['date_added']);?>
											<span><?php echo month_name(date('m',$time)); ?>&nbsp;<?php echo date('d',$time); ?>,&nbsp;<?php echo date('Y',$time); ?></span>
							</h5>
							<p>
	    									<?php echo words_limit(html_entity_decode($post['excerpt']),30,'...'); ?>
	    								</p>
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
					<div class="col-sm-12">
						<?php $inc = 0;	foreach ($popular_posts as $post) { ?>
							<div class="media-left">
								<img class="media-object" src="<?php echo $post['thumb']; ?>" alt="" />
							</div>
							<div class="media-body">
								<h3 class="media-heading">
									<a style="color: black" href="<?php echo $post['link']; ?>"><?php echo ucfirst($post['title']); ?></a>
								</h3>
								<h5>
									<span>
											By&nbsp;&nbsp;&nbsp;<?php echo author($post['post_author'],'firstname').' '.author($post['post_author'],'lastname'); ?>
											</span>
											&nbsp;&nbsp;
											<?php $time=strtotime($post['date_added']);?>
											<span><?php echo month_name(date('m',$time)); ?>&nbsp;<?php echo date('d',$time); ?>,&nbsp;<?php echo date('Y',$time); ?></span>
								</h5>
								<p>
	    									<?php echo words_limit(html_entity_decode($post['excerpt']),30,'...'); ?>
	    								</p>
							</div>
						<?php $inc++;	} ?>

					<?php } else { ?>
						<div class="alert alert-warning"><?php echo $not_found_popular; ?></div>
					<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>