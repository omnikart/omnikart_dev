<div class="widget blog-recentcomment">
	<div class="widget-header">
		<h2 class="widget-title">
			<span class="<?php echo $titleicon; ?>"></span>&nbsp;<?php echo $title; ?></h2>
	</div>
	<div class="widget-body content">
		<?php if(is_array($recent_comments) && count($recent_comments) > 0): ?>
			<ul class="list list-unstyled">
				<?php foreach ($recent_comments as $comment) : ?>
					<li>
				<h4>
					<span class="<?php echo $listicon; ?>"></span>&nbsp;

							<?php
				$decoded_content = html_entity_decode ( $comment ['comment_content'] );
				$stripted_content = strip_tags ( $decoded_content );
				$limited_content = words_limit ( $stripted_content, 15, '...' );
				?>

							<?php echo $limited_content; ?>

						</h4>
				<div class="meta">
					<span><?php echo $author; ?><?php echo ucfirst($comment['comment_author']); ?></span>
					<span><?php echo $date; ?><?php echo ucfirst($comment['comment_date']); ?></span>
				</div>
			</li>
				<?php endforeach; ?>
			</ul>
		<?php else: ?>
			<div class="alert alert-warning"><?php echo $not_found; ?></div>
		<?php endif; ?>
	</div>
	<!-- .widget-body -->
</div>
<!-- .recent-comment -->

<style type="text/css">
<?php echo html_entity_decode ( $custom_style ); ?>
</style>

<script type="text/javascript">
	<?php echo html_entity_decode($custom_script); ?>
</script>