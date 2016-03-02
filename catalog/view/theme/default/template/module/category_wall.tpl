<div class="row" id="category-wall">
	<div class="col-sm-12">
		<h2>Featured Categories</h2>
		<div class="row" style="opacity: 1; display: block; margin-bottom: 0px;" id="cat-wall">
			<?php foreach ($categories as $category) { ?>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
						<div class="product-thumb transition">
							<div class="image">
								<a href="<?php echo $category['href']; ?>"> <img src="<?php echo $category['image']; ?>" alt="<?php echo $category['name']; ?>"	title="<?php echo $category['name']; ?>"/></a>
							</div>
							<div class="caption">
								<a style="text-decoration: none" href="<?php echo $category['href']; ?>">
									<h4><?php echo $category['name']; ?><span class="more">More<span></h4>
								</a>
							</div>
							<div class="sub-category">
								<ul class="list-unstyled">
									<?foreach ($category['children'] as $key => $child) { ?>
										  <li><a href="<?php echo $child['href'] ?>"><?php echo $child['name'] ?></a></li>	
									<?php } ?>
								</ul>
							</div>
						</div>
					</div>
			<?php } ?>
		</div>
	</div>
</div>
<style>

</style>