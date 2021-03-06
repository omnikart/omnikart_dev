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
      <div class="row">
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-4'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-4'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
          <?php if ($thumb || $images) { ?>
          <ul class="thumbnails">
            <?php if ($thumb) { ?>
            <li><a class="thumbnail" href="<?php echo $popup; ?>"
								title="<?php echo $heading_title; ?>"><img
									src="<?php echo $thumb; ?>"
									title="<?php echo $heading_title; ?>"
									alt="<?php echo $heading_title; ?>" /></a></li>
            <?php } ?>
            <?php if ($images) { ?>
            <?php foreach ($images as $image) { ?>
            <li class="image-additional"><a class="thumbnail"
								href="<?php echo $image['popup']; ?>"
								title="<?php echo $heading_title; ?>"> <img
									src="<?php echo $image['thumb']; ?>"
									title="<?php echo $heading_title; ?>"
									alt="<?php echo $heading_title; ?>" /></a></li>
            <?php } ?>
            <?php } ?>
          </ul>
          <?php } ?>
          <?php if ($review_status) { ?>
	          <div class="rating">
							<p>
	              <?php for ($i = 1; $i <= 5; $i++) { ?>
	              <?php if ($rating < $i) { ?>
	              <span class="fa fa-stack"><i
									class="fa fa-star-o fa-stack-1x"></i></span>
	              <?php } else { ?>
	              <span class="fa fa-stack"><i
									class="fa fa-star fa-stack-1x"></i><i
									class="fa fa-star-o fa-stack-1x"></i></span>
	              <?php } ?>
	              <?php } ?>
	              <a href=""
									onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $reviews; ?></a>
								/ <a href=""
									onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $text_write; ?></a>
							</p>
							<hr>
							<!-- AddThis Button BEGIN -->
							<div class="addthis_toolbox addthis_default_style">
								<a class="addthis_button_facebook_like"
									fb:like:layout="button_count"></a> <a
									class="addthis_button_tweet"></a> <a
									class="addthis_button_pinterest_pinit"></a> <a
									class="addthis_counter addthis_pill_style"></a>
							</div>
							<script type="text/javascript"
								src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-515eeaf54693130e"></script>
							<!-- AddThis Button END -->
						</div>
          <?php } ?>
        </div>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-8'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
		<div class="white">
			<div class="col-sm-8">
				<h1 class="nmt"><?php echo $heading_title; ?></h1>
				<p></p>
				<div>
					<span class="label label-primary"
						style="line-height: 32px; font-size: 14px;"><?php echo $text_stock; ?> <?php echo $stock; ?></span>
					<div class="btn-group">
						<button type="button" data-toggle="tooltip"
							class="btn btn-default"
							title="<?php echo $button_wishlist; ?>"
							onclick="wishlist.add('<?php echo $product_id; ?>');">
							<i class="fa fa-heart"></i>
						</button>
						<button type="button" data-toggle="tooltip"
							class="btn btn-default"
							title="<?php echo $button_compare; ?>"
							onclick="compare.add('<?php echo $product_id; ?>');">
							<i class="fa fa-exchange"></i>
						</button>
					</div>
				</div>
	          <?php if ($price && $enabled) { ?>
	          <ul class="list-unstyled">
	            <?php if (!$special) { ?>
	            <li>

										<h2><?php echo $price; ?>
	              	<small>
		              <?php if ($discount) { ?>
		              	<font color="#d9534f"><s><?php echo $original_price; ?></s></font></span><span
												class="label label-success"><?php echo $discount; ?>% Off</span>
		              <?php } ?>
					</small>
										</h2>

									</li>
	            <?php } else { ?>
	            <li><span style="text-decoration: line-through;"><?php echo $price; ?></span></li>
									<li>
										<h2><?php echo $special; ?></h2>
									</li>
	            <?php } ?>
	            <?php if ($tax) { ?>
	            <li><?php echo $text_tax; ?> <?php echo $tax; ?></li>
	            <?php } ?>
	            <?php if ($points) { ?>
	            <li><?php echo $text_points; ?> <?php echo $points; ?></li>
	            <?php } ?>
	            <?php if ($discounts) { ?>
	            <li>
										<hr>
									</li>
	            <?php foreach ($discounts as $discount) { ?>
	            <li><?php echo $discount['quantity']; ?><?php echo $text_discount; ?><?php echo $discount['price']; ?></li>
	            <?php } ?>
	            <?php } ?>
	          </ul>
	          <?php } else { echo "<h2><small>Price is not available for this product. Please add it to quotation.</small></h2>"; }?>
          
          <div id="product">
            <?php if ($options) { ?>
            <hr>
									<h3><?php echo $text_option; ?></h3>
            <?php foreach ($options as $option) { ?>
            <?php if ($option['type'] == 'select') { ?>
            <div
										class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
										<label class="control-label"
											for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
										<select
											name="option[<?php echo $option['product_option_id']; ?>]"
											id="input-option<?php echo $option['product_option_id']; ?>"
											class="form-control">
											<option value=""><?php echo $text_select; ?></option>
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <option
												value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                <?php if ($option_value['price']) { ?>
                (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                <?php } ?>
                </option>
                <?php } ?>
              </select>
									</div>
            <?php } ?>
            <?php if ($option['type'] == 'radio') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
				<label class="control-label"><?php echo $option['name']; ?></label>
				<div id="input-option<?php echo $option['product_option_id']; ?>">
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
	                <div class="radio">
						<label> 
						<?php if ($option_value['enabled']) { ?>
							<input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
						<?php } else {?>
							<input type="radio" name="" value="" disabled/>
						<?php } ?>
		                    <?php echo $option_value['name']; ?>
		                    <?php if ($option_value['price']) { ?>
		                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
		                    <?php } ?>
	                  	</label>
					</div>
                <?php } ?>
              	</div>
			</div>
            <?php } ?>
            <?php if ($option['type'] == 'checkbox') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
				<label class="control-label"><?php echo $option['name']; ?></label>
				<div id="input-option<?php echo $option['product_option_id']; ?>">
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <div class="checkbox">
					<label>
					<?php if ($option_value['enabled']) { ?>
						<input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" />
					<?php } else { ?>
						<input type="checkbox" name="" value="" disabled/>
					<?php } ?>
	                    <?php echo $option_value['name']; ?>
	                    <?php if ($option_value['price']) { ?>
	                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
	                    <?php } ?>
	                </label>
				</div>
                <?php } ?>
              </div>
           </div>
            <?php } ?>
            <?php if ($option['type'] == 'image') { ?>
            <div
										class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
										<label class="control-label"><?php echo $option['name']; ?></label>
										<div
											id="input-option<?php echo $option['product_option_id']; ?>">
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <div class="radio">
												<label> <input type="radio"
													name="option[<?php echo $option['product_option_id']; ?>]"
													value="<?php echo $option_value['product_option_value_id']; ?>" />
													<img src="<?php echo $option_value['image']; ?>"
													alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>"
													class="img-thumbnail" /> <?php echo $option_value['name']; ?>
                    <?php if ($option_value['price']) { ?>
                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                    <?php } ?>
                  </label>
											</div>
                <?php } ?>
              </div>
									</div>
            <?php } ?>
            <?php if ($option['type'] == 'text') { ?>
            <div
										class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
										<label class="control-label"
											for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
										<input type="text"
											name="option[<?php echo $option['product_option_id']; ?>]"
											value="<?php echo $option['value']; ?>"
											placeholder="<?php echo $option['name']; ?>"
											id="input-option<?php echo $option['product_option_id']; ?>"
											class="form-control" />
									</div>
            <?php } ?>
            <?php if ($option['type'] == 'textarea') { ?>
            <div
										class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
										<label class="control-label"
											for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
										<textarea
											name="option[<?php echo $option['product_option_id']; ?>]"
											rows="5" placeholder="<?php echo $option['name']; ?>"
											id="input-option<?php echo $option['product_option_id']; ?>"
											class="form-control"><?php echo $option['value']; ?></textarea>
									</div>
            <?php } ?>
            <?php if ($option['type'] == 'file') { ?>
            <div
										class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
										<label class="control-label"><?php echo $option['name']; ?></label>
										<button type="button"
											id="button-upload<?php echo $option['product_option_id']; ?>"
											data-loading-text="<?php echo $text_loading; ?>"
											class="btn btn-default btn-block">
											<i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
										<input type="hidden"
											name="option[<?php echo $option['product_option_id']; ?>]"
											value=""
											id="input-option<?php echo $option['product_option_id']; ?>" />
									</div>
            <?php } ?>
            <?php if ($option['type'] == 'date') { ?>
            <div
										class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
										<label class="control-label"
											for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
										<div class="input-group date">
											<input type="text"
												name="option[<?php echo $option['product_option_id']; ?>]"
												value="<?php echo $option['value']; ?>"
												data-date-format="YYYY-MM-DD"
												id="input-option<?php echo $option['product_option_id']; ?>"
												class="form-control" /> <span class="input-group-btn">
												<button class="btn btn-default" type="button">
													<i class="fa fa-calendar"></i>
												</button>
											</span>
										</div>
									</div>
            <?php } ?>
            <?php if ($option['type'] == 'datetime') { ?>
            <div
										class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
										<label class="control-label"
											for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
										<div class="input-group datetime">
											<input type="text"
												name="option[<?php echo $option['product_option_id']; ?>]"
												value="<?php echo $option['value']; ?>"
												data-date-format="YYYY-MM-DD HH:mm"
												id="input-option<?php echo $option['product_option_id']; ?>"
												class="form-control" /> <span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<i class="fa fa-calendar"></i>
												</button>
											</span>
										</div>
									</div>
            <?php } ?>
            <?php if ($option['type'] == 'time') { ?>
            <div
										class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
										<label class="control-label"
											for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
										<div class="input-group time">
											<input type="text"
												name="option[<?php echo $option['product_option_id']; ?>]"
												value="<?php echo $option['value']; ?>"
												data-date-format="HH:mm"
												id="input-option<?php echo $option['product_option_id']; ?>"
												class="form-control" /> <span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<i class="fa fa-calendar"></i>
												</button>
											</span>
										</div>
									</div>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            <?php if ($recurrings) { ?>
            <hr>
									<h3><?php echo $text_payment_recurring ?></h3>
									<div class="form-group required">
										<select name="recurring_id" class="form-control">
											<option value=""><?php echo $text_select; ?></option>
                <?php foreach ($recurrings as $recurring) { ?>
                <option value="<?php echo $recurring['recurring_id'] ?>"><?php echo $recurring['name'] ?></option>
                <?php } ?>
              </select>
										<div class="help-block" id="recurring-description"></div>
									</div>
            <?php } ?>
            <div class="row">
										<div class="col-md-12">
											<div class="input-group" id="postcode">
												<span class="input-group-addon"><i class="fa fa-map-marker"></i>
													Delivery at</span> <input type="text" class="form-control"
													placeholder="Zip Code" name="postcode"
													style="min-width: 70px;" aria-describedby="postcode"> <span
													class="input-group-btn">
													<button class="btn btn-default" id="postcode-button"
														type="button">Check</button>
												</span>
											</div>
											<div class="hidden" id="postcode-result"></div>
										</div>
									</div>

									<div class="form-group">
										<input type="hidden" name="product_id"
											value="<?php echo $product_id; ?>" /> <input type="hidden"
											name="products[]" value="<?php echo $product_id; ?>" /> <br />
										<div class="row">

											<div class="col-md-4">
												<div class="input-group">
													<span class="input-group-addon" id="sizing-addon2"> <label
														class="control-label" for="input-quantity">
		            			<?php echo $entry_qty; ?>
		            		</label>
													</span> <input type="text" name="quantity"
														value="<?php echo $minimum; ?>" size="2"
														id="input-quantity" class="form-control" />
												</div>
											</div>
				<?php if ($enabled) { ?>
					<div class="col-md-4">
												<button type="button" id="button-cart" data-cart="cart"
													data-loading-text="<?php echo $text_loading; ?>"
													class="btn btn-info btn-block">
													<i class="fa fa-shopping-cart"></i>&nbsp Add to Cart
												</button>
											</div>
              	<?php } ?>            	
              	<div class="col-md-4">
												<button type="button" id="button-quote" data-cart="quote"
													data-loading-text="<?php echo $text_loading; ?>"
													class="btn btn-info btn-block">
													<i class="fa fa-flip-horizontal fa-file-text-o"></i>&nbsp
													Add to Quotation
												</button>
											</div>
										</div>
									</div>
            
            <?php if ($minimum > 1) { ?>
            <div class="alert alert-info">
										<i class="fa fa-info-circle"></i> <?php echo $text_minimum; ?></div>
            <?php } ?>
          </div>


							</div>

							<div class="col-sm-4">
								<div class="well">
									<ul class="list-unstyled">
	            <?php if ($manufacturer) { ?>
	            <li><h4>
												<i class="fa fa-industry"></i> <?php echo $text_manufacturer; ?> <a
													href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a>
											</h4></li>
	            <?php } ?>
	            <li><h4>
												<i class="fa fa-barcode"></i> <?php echo $text_model; ?> <?php echo $model; ?></li>
										</h4>
	            <?php if ($reward) { ?>
	            <li><h4>
												<i class="fa fa-trophy"></i> <?php echo $text_reward; ?> <?php echo $reward; ?></li>
										</h4>
	            <?php } ?>
	          </ul>
  			  
			  <?php if ($curr_vendor && 0) {  ?>
	    		<div class="vendor">
										<h4>
											<i class="fa fa-user"></i> Sold by: <a
												href="<?php echo $vlink; ?>"><?php echo $curr_vendor['companyname']; ?></a>
										</h4>
										<input type="hidden" name="vendor_id"
											value="<?php echo $curr_vendor['customer_id']; ?>">
									</div>      	
	          <?php } ?>
	          <?php if ($vendors && 0) {  ?>
				<button type="button" id="view-vendors"
										data-loading-text="<?php echo $text_loading; ?>"
										class="btn btn-default btn-block" data-toggle="modal"
										data-target="#supplier-list">View other suppliers</button>
	          <?php } ?>
  			</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<div class="col-sm-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="nmt"><?php echo $tab_description; ?></h3>
							</div>
							<div class="panel-body">
	      		<?php echo $description; ?>
			  </div>
						</div>
			<?php if ($attribute_groups) { ?>
       		<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="nmt"><?php echo $tab_attribute; ?></h3>
							</div>
							<div class="panel-body">
				<?php if ($attribute_groups) { ?>
	            <div class="tab-pane" id="tab-specification">
									<table class="table table-bordered">
	                <?php foreach ($attribute_groups as $attribute_group) { ?>
	                <thead>
											<tr>
												<td colspan="2"><strong><?php echo $attribute_group['name']; ?></strong></td>
											</tr>
										</thead>
										<tbody>
	                  <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
	                  <tr>
												<td><?php echo $attribute['name']; ?></td>
												<td><?php echo $attribute['text']; ?></td>
											</tr>
	                  <?php } ?>
	                </tbody>
	                <?php } ?>
	              </table>
								</div>
	            <?php } ?>
			  </div>
						</div>
			<?php } ?>
			<?php if ($combo) { ?>
			<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="nmt"><?php echo $combo_title; ?></h3>
							</div>
							<div class="panel-body">
			      <?php echo $combo; ?>
			  </div>
						</div>		
			<?php }?>
			
			<?php if ($review_status) { ?>
			<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="nmt"><?php echo $tab_review; ?></h3>
							</div>
							<div class="panel-body">
								<form class="form-horizontal" id="form-review">
									<div id="review"></div>
									<h2><?php echo $text_write; ?></h2>
                <?php if ($review_guest) { ?>
                <div class="form-group required">
										<div class="col-sm-12">
											<label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
											<input type="text" name="name" value="" id="input-name"
												class="form-control" />
										</div>
									</div>
									<div class="form-group required">
										<div class="col-sm-12">
											<label class="control-label" for="input-review"><?php echo $entry_review; ?></label>
											<textarea name="text" rows="5" id="input-review"
												class="form-control"></textarea>
											<div class="help-block"><?php echo $text_note; ?></div>
										</div>
									</div>
									<div class="form-group required">
										<div class="col-sm-12">
											<label class="control-label"><?php echo $entry_rating; ?></label>
                    &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
                    <input type="radio" name="rating" value="1" />
											&nbsp; <input type="radio" name="rating" value="2" /> &nbsp;
											<input type="radio" name="rating" value="3" /> &nbsp; <input
												type="radio" name="rating" value="4" /> &nbsp; <input
												type="radio" name="rating" value="5" />
                    &nbsp;<?php echo $entry_good; ?></div>
									</div>
                <?php if ($site_key) { ?>
                  <div class="form-group">
										<div class="col-sm-12">
											<div class="g-recaptcha"
												data-sitekey="<?php echo $site_key; ?>"></div>
										</div>
									</div>
                <?php } ?>
                <div class="buttons clearfix">
										<div class="pull-right">
											<button type="button" id="button-review"
												data-loading-text="<?php echo $text_loading; ?>"
												class="btn btn-primary"><?php echo $button_continue; ?></button>
										</div>
									</div>
                <?php } else { ?>
                <?php echo $text_login; ?>
                <?php } ?>
              </form>
							</div>
						</div>
            <?php } ?>


	      <?php if ($products) { ?>
			<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="nmt"><?php echo $text_related; ?></h3>
							</div>
							<div class="panel-body">
								<div class="row">
			        <?php $i = 0; ?>
			        <?php foreach ($products as $product) { ?>
			        <?php if ($column_left && $column_right) { ?>
			        <?php $class = 'col-lg-6 col-md-6 col-sm-12 col-xs-12'; ?>
			        <?php } elseif ($column_left || $column_right) { ?>
			        <?php $class = 'col-lg-4 col-md-4 col-sm-6 col-xs-12'; ?>
			        <?php } else { ?>
			        <?php $class = 'col-lg-20 col-md-3 col-sm-6 col-xs-12'; ?>
			        <?php } ?>
			        <div class="<?php echo $class; ?>">
										<div class="product-thumb transition">
						<?php require(DIR_TEMPLATE.'default/template/common/product/product.tpl'); ?>
			          </div>
									</div>
			        <?php if (($column_left && $column_right) && ($i % 2 == 0)) { ?>
			        <div class="clearfix visible-md visible-sm"></div>
			        <?php } elseif (($column_left || $column_right) && ($i % 3 == 0)) { ?>
			        <div class="clearfix visible-md"></div>
			        <?php } elseif ($i % 4 == 0) { ?>
			        <div class="clearfix visible-md"></div>
			        <?php } ?>
			        <?php $i++; ?>
			        <?php } ?>
			      </div>
							</div>
						</div>
		      <?php } ?>
		  </div>
				</div>      
      <?php if ($tags) { ?>
      <p><?php echo $text_tags; ?>
        <?php for ($i = 0; $i < count($tags); $i++) { ?>
        <?php if ($i < (count($tags) - 1)) { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
        <?php } else { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
        <?php } ?>
        <?php } ?>
      </p>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
	</div>
	<div class="modal fade bs-example-modal-sm" id="supplier-list"
		tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<h4>Also sold by</h4>
				</div>
				<div class="modal-body">
			<?php if ($vendors) {  ?>
				<?php foreach($vendors as $vendor) {  ?>
					<h4>
						<a href="<?php echo $vendor['link']; ?>"><?php echo $vendor['companyname']; ?> at <?php echo $vendor['price']; ?></a>
					</h4>
				<?php } ?>
			<?php } ?>
		</div>
			</div>
		</div>
	</div>
	<script type="text/javascript"><!--
$('select[name=\'recurring_id\'], input[name="quantity"]').change(function(){
	$.ajax({
		url: 'index.php?route=product/product/getRecurringDescription',
		type: 'post',
		data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#recurring-description').html('');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['success']) {
				$('#recurring-description').html(json['success']);
			}
		}
	});
});
//--></script>
	<script type="text/javascript"><!--
$('#postcode-button').on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/servicecheck',
		type: 'post',
		data: $('#product input[name=\'postcode\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#postcode-button').button('loading');
		},
		complete: function() {
			$('#postcode-button').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
  				html  = '<div class="alert alert-error" style="nmt">';
  				html +=	'<span class="msg">'+json['error']['text']+'</span>';
  				html +=	'<button type="button" class="close" onclick="$(\'#postcode\').removeClass(\'hidden\');$(\'#postcode-result\').addClass(\'hidden\');">×</button>';
  				html +=	'</div>';
				
				$('#postcode-result').html(html);
				$('#postcode').addClass('hidden');
				$('#postcode-result').removeClass('hidden');
			}

			if (json['success']) {
  				html  = '<div class="alert alert-info" style="nmt">';
  				html +=	'<span class="msg">'+json['success']['text']+'</span>';
  				html +=	'<button type="button" class="close" onclick="$(\'#postcode\').removeClass(\'hidden\');$(\'#postcode-result\').addClass(\'hidden\');">×</button>';
  				html +=	'</div>';
				
				$('#postcode-result').html(html);
				$('#postcode').addClass('hidden');
				$('#postcode-result').removeClass('hidden');
			}
		}
	});
});
//--></script>
	<script type="text/javascript"><!--
$('#button-cart').on('click', function() {
	var data = $(this).data('cart');
	$.ajax({
		url: 'index.php?route=checkout/cart/add&cart='+data,
		type: 'post',
		data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], .vendor input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-cart').button('loading');
		},
		complete: function() {
			$('#button-cart').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						var element = $('#input-option' + i.replace('_', '-'));

						if (element.parent().hasClass('input-group')) {
							element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						} else {
							element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						}
					}
				}

				if (json['error']['recurring']) {
					$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
				}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			}

			if (json['success']) {
				$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i>' + json['total'] + '</span>');

				$('html, body').animate({ scrollTop: 0 }, 'slow');

				$('#cart_modal .modal-body').load('index.php?route=common/cart/info div#cart-content');
				if (json['redirect']) {
					window.location = json['redirect']; 
				}
			}
		}
	});
});

$('#button-quote').on('click', function() {
	var data = $(this).data('cart');
	$.ajax({
		url: 'index.php?route=module/enquiry/addProduct',
		type: 'post',
		data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], .vendor input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-quote').button('loading');
		},
		complete: function() {
			$('#button-quote').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						var element = $('#input-option' + i.replace('_', '-'));

						if (element.parent().hasClass('input-group')) {
							element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						} else {
							element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						}
					}
				}

				if (json['error']['recurring']) {
					$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
				}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			}
			if (json['success']) {
				$('#view-enquiry').trigger('click');
				$('#view-enquiry .badge').load('index.php?route=module/enquiry/addProduct');
			}
		}
	});
});
//--></script>
	<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});

$('.time').datetimepicker({
	pickDate: false
});

$('button[id^=\'button-upload\']').on('click', function() {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=tool/upload',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).button('loading');
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$('.text-danger').remove();

					if (json['error']) {
						$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);

						$(node).parent().find('input').attr('value', json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});

$('#postcode-button').on('click',function(){
	
	
});

//--></script>
	<script type="text/javascript"><!--
$('#review').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

    $('#review').fadeOut('slow');

    $('#review').load(this.href);

    $('#review').fadeIn('slow');
});

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

$('#button-review').on('click', function() {
	$.ajax({
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		type: 'post',
		dataType: 'json',
		data: $("#form-review").serialize(),
		beforeSend: function() {
			$('#button-review').button('loading');
		},
		complete: function() {
			$('#button-review').button('reset');
		},
		success: function(json) {
			$('.alert-success, .alert-danger').remove();

			if (json['error']) {
				$('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
				$('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').prop('checked', false);
			}
		}
	});
});
$(document).ready(function() {
	$('.thumbnails').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:true
		}
	});
});
//--></script>
</div><?php echo $footer; ?>
