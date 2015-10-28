<div class="row">
	<div class="col-sm-12">
	<h3>Featured Categories</h3>
		<div class="row" style="opacity: 1; display: block;margin-bottom: 0px;" id="cat-wall">
				<?php foreach ($categories as $category) { ?>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
								<div class="product-thumb transition">
										<div class="image"><a href="<?php echo $category['href']; ?>"><img src="<?php echo $category['image']; ?>" alt="<?php echo $category['name']; ?>" title="<?php echo $category['name']; ?>" class="img-responsive" /></a></div>
										<div class="caption" style="min-height: 50px">
												<h4><a style="text-decoration: none" href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></h4>
										</div>
										<div class="subcat hidden-xs hidden-sm">
											<ul>
												<?php foreach ($category['child'] as $child) { ?>
													<li><a href="<?php echo $child['href']; ?>"><h5><?php echo $child['name']; ?></h5></a></li>
												<?php } ?>
											</ul>
										</div>
								</div>
						</div>
				<?php } ?>
		</div>
	</div>
</div>
