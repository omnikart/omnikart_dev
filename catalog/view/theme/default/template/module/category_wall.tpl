<div class="row">
	<div class="col-sm-12">
	<h3>Featured Categories</h3>
		<div class="row" style="opacity: 1; display: block;margin-bottom: 0px;" id="cat-wall">
				<?php foreach ($categories as $category) { ?>
						<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
								<div class="product-thumb transition">
										<a style="text-decoration: none" href="<?php echo $category['href']; ?>">
											<div class="image"><a href="<?php echo $category['href']; ?>"><img src="<?php echo $category['image']; ?>" alt="<?php echo $category['name']; ?>" title="<?php echo $category['name']; ?>" class="img-responsive" /></a></div>
											<div class="caption" style="min-height: 50px">
													<h4><?php echo $category['name']; ?></h4>
											</div>
										</a>
								</div>
						</div>
				<?php } ?>
		</div>
	</div>
</div>
