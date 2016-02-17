<div>
	
		<?php if(!empty($categories)) { ?>
			<div class="list-group">
		<h3 class="list-group-item" style="margin-top: 0;"><?php echo $title; ?></h3>
				<?php foreach ($categories as $category) { ?>
				<?php if ($category['category_id'] == $category_id) { ?>
					<a href="<?php echo $category['href']; ?>" class="list-group-item">
			<i class="fa fa-angle-double-down"></i>&nbsp;&nbsp;<strong><?php echo $category['name']; ?></strong>
		</a>
						<?php if(!empty($category['children'])) { ?>
							<div class="list-group">
								<?php foreach ($category['children'] as $child) { ?>
						      <a
				class="<?php echo (($child['category_id'] == $child_id)?'active':'');  ?> list-group-item"
				href="<?php echo $child['href']; ?>"><i class="fa fa-angle"></i>&nbsp;&nbsp;-&nbsp;<?php echo $child['name']; ?></a>
						    <?php } ?>
						  </div>
					  <?php } ?>
				<?php } else { ?>
						<a href="<?php echo $category['href']; ?>" class="list-group-item">
			<i class="fa fa-angle-double-right"></i>&nbsp;&nbsp;<?php echo $category['name']; ?>
					</a>
					<?php } ?>
				<?php } ?>
			</div>
		<?php } else { ?>
			<div class="alert alert-warning"><?php echo $not_found; ?></div>
		<?php } ?>
	<!-- .widget-body -->
</div>
<!-- .blog-category -->

<style type="text/css">
<?php echo html_entity_decode ( $custom_style ); ?>
</style>

<script type="text/javascript">
	<?php echo html_entity_decode($custom_script); ?>
</script>
