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
        <?php $class = 'col-sm-6'; ?>
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
            <li class="image-additional">
							<a class="thumbnail"	href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"> 
								<img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>"	alt="<?php echo $heading_title; ?>" />
							</a>
						</li>
            <?php } ?>
            <?php } ?>
          </ul>
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
					<h1 class="nmt"><?php echo $heading_title; ?></h1>
					<ul class="list-unstyled">
							<?php if ($manufacturer) { ?>
							<li><?php echo $text_manufacturer; ?> <a
									href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a></li>
							<?php } ?>
					</ul>
			</div>
        <div class="col-sm-12">
<div class="table-responsive">
  <table id="gp-table" class="gp-table table table-bordered">
    <thead>
      <tr class="head-row">
        <?php if ($dbe) { ?>
        	<td rowspan="2" class="gp-col-checkbox">
						<input type="checkbox" name="products[]" id="pcd-00" value="<?php echo $child['child_id']; ?>" onclick="$('input[name*=\'products\']').prop('checked', this.checked);" />
						<label for="pcd-00"></label>
					</td>
        <?php } ?>
				<td id="gp-toggle-info" rowspan="2"><?php echo $column_gp_name; ?></td>
        <?php if ($agnames) { ?>
        	<?php foreach($agnames as $ag) { ?>
				<td class="agc" colspan="<?php echo count($ag['a']); ?>" align="center"><?php echo $ag['name']; ?></td>
        	<?php } ?>
        <?php } ?>
        <?php if ($column_gp_price) { ?>
        <td rowspan="2"><?php echo $column_gp_price; ?></td>
        <?php } ?>
        <?php if ($column_gp_option) { ?>
        <td rowspan="2"><?php echo $column_gp_option; ?></td>
        <?php } ?>
        <td rowspan="2" class="text-center"><?php echo $column_gp_qty; ?></td>
        <td rowspan="2" class="text-center"></td>
      </tr>
      <tr>

        <?php if ($agnames) { ?>
        	<?php foreach($agnames as $ag) { ?>
				<?php foreach($ag['a'] as $a) {  ?>
        			<td class="sortt"><?php echo $a; ?></td>
				<?php } ?>
        	<?php } ?>
        <?php } ?>
      </tr>
		</thead>
		<tbody>
      <?php foreach ($childs as $child) { $child_id = $child['child_id']; ?>
      <tr data-gp-child-row="<?php echo $child_id; ?>">
        <?php if ($dbe) { ?>
        	<td class="gp-col-checkbox"><input type="checkbox" name="products[]" id="pcd-<?php echo $child['child_id']; ?>" value="<?php echo $child['child_id']; ?>" />
						<label for="pcd-<?php echo $child['child_id']; ?>"></label>
					</td>
        <?php } ?>
				<td class="gp-col-name">
					<div class="gp-child-name"><?php echo $child['name']; ?>
						<?php if ($child['enabled']) { ?> 
							<?php if ($child['curr_vendor'] && 0) { ?>
								<div style="display:inline-block;position:relative;">
									<a class="dropdown-toggle" href="#" id="suppliers<?php echo $child['child_id']; ?>" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span><i class="fa fa-users"></i></span>
									</a>
									<ul class="dropdown-menu" aria-labelledby="suppliers<?php echo $child['child_id']; ?>" style="width:300px;">
										<li>Supplied by</li>
										<li class="current"><a href="<?php echo $child['vlink']; ?>"><?php echo $child['curr_vendor']['companyname']; ?></a></li>
										<?php if ($child['vendors']) { ?>
											<li role="separator" class="divider"></li>
											<li>Also supplied by</li>
											<?php foreach($child['vendors'] as $vendor) { ?>
												<li class="others"><a onclick="reloadGp('<?php echo $child['child_id']; ?>','<?php echo $vendor['vendor_id']; ?>');"><?php echo $vendor['companyname']; ?> at <?php echo $vendor['price']; ?></a></li>
											<?php } ?>
										<?php } ?>
									</ul>		
								</div>
							<?php } ?>							
						<?php } ?>
					</div>
				</td>      
        <?php  if ($agnames) { foreach($agnames as $key => $ag) { ?>
        	<?php foreach($ag['a'] as $key2 => $a) {  ?>
						<td class="ag">
							<?php echo isset($child['attributes'][$key]['attribute'][$key2]['text'])?$child['attributes'][$key]['attribute'][$key2]['text']:''; ?>
						</td>
					<?php } ?>
				<?php } } ?>
        
				<?php if ($column_gp_price) { ?>
					<td class="gp-col-price">
						<?php if ($child['enabled']) { ?>
							<?php if (!$child['special']) { ?>
								<?php echo $child['price']; ?>&nbsp;
								<?php if ($child['discounts']) { ?>
									<div class="discounts">
										<a class="pull-right dropdown-toggle" href="#" id="discounts<?php echo $child['child_id']; ?>" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">								
											 <i class="fa fa-th-list"></i>										
										</a>
										<div class="dropdown-menu" aria-labelledby="discounts<?php echo $child['child_id']; ?>">
											<div class="col-sm-12">
												<?php foreach ($child['discounts'] as $discount) { ?>
												<div class="gp-child-discount"><?php echo $discount['quantity']; ?><?php echo $text_discount; ?><?php echo $discount['price']; ?></div>
												<?php } ?>
											</div>
											<div class="clearfix"></div>
										</div>
									</div>
								<?php  } ?>									
							<?php } else { ?>
							<span class="gp-child-price-old"><?php echo $child['price']; ?></span> <?php echo $child['special']; ?>
							<?php } ?>
						<?php } else { echo "<span class=\"no-price\">Not Available.</span>"; } ?>
					</td>
				<?php } ?>
        <?php if ($column_gp_option) { ?>
					<td class="gp-col-option" id="options-<?php echo $child_id; ?>">
					<?php foreach ($child['options'] as $option) { ?>
							<?php if ($option['type'] == 'select') { ?>
							<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
								<label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
								<select	name="option[<?php echo $option['product_option_id']; ?>]" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control">
									<option value=""><?php echo $text_select; ?></option>
									<?php foreach ($option['product_option_value'] as $option_value) { ?>
										<option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
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
								<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
									<label class="control-label"><?php echo $option['name']; ?></label>
									<div id="input-option<?php echo $option['product_option_id']; ?>" class="dropdown fs-dropdown">
										<div class="input-group">
											<div class="input-group-addon" id="fs-dropdown-selected<?php echo $option['product_option_id']; ?>"><?php echo $text_select; ?></div>
											<div class="input-group-btn">
												<button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu">
													<?php foreach ($option['product_option_value'] as $option_value) { ?>
														<li>
															<label onclick="fsDropdown($(this), 'fs-dropdown-selected<?php echo $option['product_option_id']; ?>');">
																<input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" style="display: none; width: 0;" />
																<img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" /> <?php echo $option_value['name']; ?>
																<?php if ($option_value['price']) { ?>
																	(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
																<?php } ?>
															</label>
														</li>
													<?php } ?>
												</ul>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
							<?php if ($option['type'] == 'text') { ?>
								<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
									<label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
									<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
								</div>
							<?php } ?>
							<?php if ($option['type'] == 'textarea') { ?>
							<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
								<label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
								<textarea name="option[<?php echo $option['product_option_id']; ?>]" rows="5" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control"><?php echo $option['value']; ?></textarea>
							</div>
							<?php } ?>
							<?php if ($option['type'] == 'file') { ?>
								<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
									<label class="control-label"><?php echo $option['name']; ?></label>
									<button type="button" id="button-upload<?php echo $option['product_option_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default btn-block"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
									<input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" id="input-option<?php echo $option['product_option_id']; ?>" />
								</div>
							<?php } ?>
							<?php if ($option['type'] == 'date') { ?>
								<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
									<label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
									<div class="input-group date">
										<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-format="YYYY-MM-DD" id="input-option<?php echo $option['product_option_id']; ?>"class="form-control" />
										<span class="input-group-btn">
											<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</div>
							<?php } ?>
							<?php if ($option['type'] == 'datetime') { ?>
								<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
									<label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
									<div class="input-group datetime">
										<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-format="YYYY-MM-DD HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">
												<i class="fa fa-calendar"></i>
											</button>
										</span>
									</div>
								</div>
							<?php } ?>
							<?php if ($option['type'] == 'time') { ?>
								<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
									<label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
									<div class="input-group time">
										<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-format="HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">
												<i class="fa fa-calendar"></i>
											</button>
										</span>
									</div>
								</div>
							<?php } ?>
					<?php } ?>
					</td>
        <?php } ?>
        <td class="gp-col-qty">
          <?php if ($child['nocart']) { ?>
						<input name="" type="text" value="" class="form-control" disabled="disabled" title="<?php echo $text_gp_no_stock; ?>" />
          <?php } else { ?>
						<input name="quantity" id="quantity<?php echo $child_id; ?>" type="text" value="<?php echo $child['qty_now']; ?>" class="form-control" size="2" />
          <?php } ?>
        </td>
				<td class="gp-col-btn"><input type="hidden" name="product_id" id="product<?php echo $child_id; ?>" value="<?php echo $child_id; ?>" />
				<input type="hidden" name="vendor_id" id="vendor<?php echo $child_id; ?>" value="<?php echo $child['curr_vendor']['customer_id']; ?>" />
					<div class="btn-toolbar" role="toolbar" aria-label="...">
						<div class="btn-group justified" role="group" aria-label="...">
							<button type="button"	data-loading-text="<?php echo $text_loading; ?>" <?php if ($child['enabled']) { ?> onclick="addGpGrouped('<?php echo $child_id; ?>');" <?php } else { echo "disabled=\"disabled\""; } ?> class="btn btn-primary"><?php echo $button_add_to_cart; ?></button>
						</div>
						<div class="btn-group" role="group" aria-label="...">
							<button type="button" data-loading-text="<?php echo $text_loading; ?>" onclick="addGpGroupedToQuote('<?php echo $child_id; ?>');" class="btn btn-primary"><?php echo $button_add_to_quote; ?></button>
						</div>
					</div>
				</td>
      </tr>
      <?php } ?>
    </tbody>
							</table>
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab-description" data-toggle="tab"><?php echo $tab_description; ?></a></li>
            <?php if ($attribute_groups) { ?>
            <li><a href="#tab-specification" data-toggle="tab"><?php echo $tab_attribute; ?></a></li>
            <?php } ?>
            <?php if ($review_status) { ?>
            <li><a href="#tab-review" data-toggle="tab"><?php echo $tab_review; ?></a></li>
            <?php } ?>
          </ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tab-description"><?php echo $description; ?></div>
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
            <?php if ($review_status) { ?>
            <div class="tab-pane" id="tab-review">
									<form class="form-horizontal">
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
            <?php } ?>
          </div>

						</div>
					</div>
					<!-- End Grouped Product powered by www.fabiom7.com //-->

				</div>
      <?php if ($products) { ?>
      <h3><?php echo $text_related; ?></h3>
				<div class="row">
        <?php $i = 0; ?>
        <?php foreach ($products as $product) { ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-lg-6 col-md-6 col-sm-12 col-xs-12'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-lg-4 col-md-4 col-sm-6 col-xs-12'; ?>
        <?php } else { ?>
        <?php $class = 'col-lg-3 col-md-3 col-sm-6 col-xs-12'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
						<div class="product-thumb transition">
							<div class="image">
								<a href="<?php echo $product['href']; ?>"><img
									src="<?php echo $product['thumb']; ?>"
									alt="<?php echo $product['name']; ?>"
									title="<?php echo $product['name']; ?>" class="img-responsive" /></a>
							</div>
							<div class="caption">
								<h4>
									<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
								</h4>
								<p><?php echo $product['description']; ?></p>
              <?php if ($product['rating']) { ?>
              <div class="rating">
                <?php for ($i = 1; $i <= 5; $i++) { ?>
                <?php if ($product['rating'] < $i) { ?>
                <span class="fa fa-stack"><i
										class="fa fa-star-o fa-stack-1x"></i></span>
                <?php } else { ?>
                <span class="fa fa-stack"><i
										class="fa fa-star fa-stack-1x"></i><i
										class="fa fa-star-o fa-stack-1x"></i></span>
                <?php } ?>
                <?php } ?>
              </div>
              <?php } ?>
              <?php if ($product['price']) { ?>
              <p class="price">
                <?php if (!$product['special']) { ?>
                <?php echo $product['price']; ?>
                <?php } else { ?>
                <span class="price-new"><?php echo $product['special']; ?></span>
									<span class="price-old"><?php echo $product['price']; ?></span>
                <?php } ?>
                <?php if ($product['tax']) { ?>
                <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                <?php } ?>
              </p>
              <?php } ?>
            </div>
							<div class="button-group">
								<button type="button"
									onclick="cart.add('<?php echo $product['product_id']; ?>');">
									<span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span>
									<i class="fa fa-shopping-cart"></i>
								</button>
								<button type="button" data-toggle="tooltip"
									title="<?php echo $button_wishlist; ?>"
									onclick="wishlist.add('<?php echo $product['product_id']; ?>');">
									<i class="fa fa-heart"></i>
								</button>
								<button type="button" data-toggle="tooltip"
									title="<?php echo $button_compare; ?>"
									onclick="compare.add('<?php echo $product['product_id']; ?>');">
									<i class="fa fa-exchange"></i>
								</button>
							</div>
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
      <?php } ?>
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
/*
$('#button-cart').on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
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
				
				$('#cart-total').html(json['total']);
				
				$('html, body').animate({ scrollTop: 0 }, 'slow');
				
				$('#cart > ul').load('index.php?route=common/cart/info ul li');
			}
		}
	});
});
*/
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

	<!-- Start JS - Grouped Product powered by www.fabiom7.com //-->
	<script type="text/javascript"><!--
function addGpGrouped(child_id) {
	if ($('#quantity' + child_id).val() < 1) {
		$('#quantity' + child_id).val('1');
	}

	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $('#product' + child_id + ', #vendor' + child_id + ', #quantity' + child_id + ', #options-' + child_id + ' input[type=\'text\'], #options-' + child_id + ' input[type=\'hidden\'], #options-' + child_id + ' input[type=\'radio\']:checked, #options-' + child_id + ' input[type=\'checkbox\']:checked, #options-' + child_id + ' select, #options-' + child_id + ' textarea'),
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
				
				$('#cart-total').html(json['total']);
				
				$('html, body').animate({ scrollTop: 0 }, 'slow');
				
				$('#cart_modal .modal-body').load('index.php?route=common/cart/info div#cart-content');
				if (json['redirect']) {
					window.location = json['redirect']; 
				}
			}
		}
	});
}
//--></script>


	<!-- Start JS - Grouped Product powered by www.fabiom7.com //-->
	<script type="text/javascript"><!--
function addGpGroupedToQuote(child_id) {
	if ($('#quantity' + child_id).val() < 1) {
		$('#quantity' + child_id).val('1');
	}
	var data = $(this).data('cart');
	$.ajax({
		url: 'index.php?route=module/enquiry/addProduct',
		type: 'post',
		data: $('#product' + child_id + ', #quantity' + child_id),
		dataType: 'json',
		beforeSend: function() {
			$('#button-quote').button('loading');
		},
		complete: function() {
			$('#button-quote').button('reset');
		},
		success: function(json) {
			if (json['success']) {
				$('#view-enquiry').trigger('click');
				$('#view-enquiry .badge').load('index.php?route=module/enquiry/addProduct');
			}
		}
	});
}

$('#columns').on('click','#view-enquiry',function(){
	$.ajax({
		url : 'index.php?route=module/enquiry/getEnquiry',
	    dataType: "html",
	    success : function (data) {
		    $('#enquiry-products').modal('show');
		    $('#enquiry-products').remove();
			$('body').append(data);
			$('#enquiry-products').modal('show');
	    }
	});
});
//--></script>



	<script type="text/javascript"><!--
$('label[for="input-quantity"], #input-quantity, #button-cart').remove();

function fsDropdown(elm, sel) {
	$('#' + sel).html(elm.html());
}

<?php if ($thumb) { ?>
imageSwap = [];
<?php foreach ($childs as $child) { ?>
imageSwap[<?php echo $child['child_id']; ?>] = "<?php echo $child['image']['swap']; ?>";
<?php } ?>
$('#gp-table tbody tr').on({
	mouseover: function() {
		$('img[src="<?php echo $thumb; ?>"]').attr({'data-gp-parent': '1', 'src': imageSwap[$(this).attr('data-gp-child-row')]});
	},
	mouseout: function() {
		$('img[data-gp-parent="1"]').attr('src', '<?php echo $thumb; ?>');
	}
});
<?php } ?>

$(document).ready(function() {
	$('.gp-col-image').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:true
		}
	});

	$('#gp-toggle-info').prepend('<i id="gp-toggle-info-icon" class="fa fa-list"></i> ').css('cursor', 'pointer').on('click', function() {
		$thisIcon = $('#gp-toggle-info-icon');
		if ($thisIcon.hasClass('fa-list')) {
			$thisIcon.removeClass('fa-list').addClass('fa-list-alt');
		} else {
			$thisIcon.removeClass('fa-list-alt').addClass('fa-list');
		}

		$('.gp-toggle-info').slideToggle('slow');
	});

	$('#gp-toggle-info').click(); //This line collapse infos on load. Comment if unwanted.
});

function reloadGp(child_id,vendor_id){
	var row = $('tr[data-gp-child-row="'+child_id+'"]');
	$.ajax({
		url : 'index.php?route=product/product/getGProw&child_id='+child_id+'&vendor_id='+vendor_id,
	  dataType: 'json',
	  success : function (json) {
			
			var price = '';
			if (json['child']['enabled']){
				if (!json['child']['special']){
					price += json['child']['price'];
					if (json['child']['discounts']){
						price +=' <div class="discounts">';
						price +=' 	<a class="pull-right dropdown-toggle" href="#" id="discounts<?php echo $child['child_id']; ?>" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
						price +='			<i class="fa fa-th-list"></i>';
						price +='		</a>';
						price +='		<div class="dropdown-menu" aria-labelledby="discounts<?php echo $child['child_id']; ?>">';
						price +='			<div class="col-sm-12">';

						$.each(json['child']['discounts'], function(k,v) {
							price +='				<div class="gp-toggle-info gp-child-discount">'+v['quantity']+json['text_discount']+v['price']+'</div>';
						});

						price +='			</div>';
						price +='			<div class="clearfix"></div>';
						price +='		</div>';
						price +='	</div>';
					}
				} else {
					
				}			
			} else {
				price += '<span class=\"no-price\">Not Available.</span>'
			}
			$('tr[data-gp-child-row="'+child_id+'"] > .gp-col-price').html(price);
			$('tr[data-gp-child-row="'+child_id+'"] > .gp-col-option').html(gprowupdate.renderoptions(json['child']['options']));
			$('tr[data-gp-child-row="'+child_id+'"] > .gp-col-name > .gp-child-name > div > .dropdown-menu > .current > a').html(json['child']['curr_vendor']['companyname']);
			$('tr[data-gp-child-row="'+child_id+'"] > .gp-col-name > .gp-child-name > div > .dropdown-menu > .current > a').attr('href',json['child']['vlink']);
			$('tr[data-gp-child-row="'+child_id+'"] > .gp-col-name > .gp-child-name > div > .dropdown-menu > .others').remove();
			var vendors = '';
			
			$.each(json['child']['vendors'], function(k2,v2) {
				vendors +='<li class="others">';
				vendors +='	<a onclick="reloadGp(\''+child_id+'\',\''+v2['vendor_id']+'\');">'+v2['companyname']+' at '+ v2['price'] +'</a>';
				vendors +='</li>';
			});
			
			$('tr[data-gp-child-row="'+child_id+'"] > .gp-col-name > .gp-child-name > div > .dropdown-menu').append(vendors);
			$('tr[data-gp-child-row="'+child_id+'"] > .gp-col-btn > #vendor'+child_id).val(json['child']['curr_vendor']['customer_id']);
	  }
	});	
}

var gprowupdate = {
	'renderoptions':function (options) {
		var option_html = '';
		$.each(options, function(k,v) {
			if (v['type'] == 'select') {
				option_html += '<div class="form-group'+(v['required'] ? ' required' : '')+'">';
				option_html += '	<label class="control-label" for="input-option'+v['product_option_id']+'">'+v['name']+'</label>';
				option_html += '	<select	name="option['+v['product_option_id']+']" id="input-option'+v['product_option_id']+'" class="form-control">';
				option_html += '		<option value=""><?php echo $text_select; ?></option>';

				$.each(v['product_option_value'], function(k1,v1) {
				
				option_html += '		<option value="'+v1['product_option_value_id']+'">'+v1['name'];
					if (v1['price']) {
				option_html += '			('+v1['price_prefix']+''+v1['price']+')';	
					}

				option_html += '		</option>';
				
				});
				option_html += '	</select>';
				option_html += '</div>';
			 }
			 
			if (v['type'] == 'radio') {
				option_html += '<div class="form-group'+(v['required'] ? ' required' : '')+'">';
				option_html += '	<label class="control-label">'+v['name']+'</label>';
				option_html += '	<div id="input-option'+v['product_option_id']+']">';
				
				$.each(v['product_option_value'], function(k1,v1) {
				option_html += '		<div class="radio">';
				option_html += '			<label>';
				
				if (v1['enabled']) {
				option_html += '				<input type="radio" name="option['+v['product_option_id']+']" value="'+v1['product_option_value_id']+'" />';
				} else {
				option_html += '				<input type="radio" name="" value="" disabled/>';
				}
				option_html += v1['name'];
				if (v1['price']) {
				option_html += '				('+v1['price_prefix']+''+v1['price']+')';	
				}
				
				option_html += '			</label>';
				option_html += '		</div>';
				
				});
				option_html += '	</div>';
				option_html += '</div>';
			 }
			 
			if (v['type'] == 'checkbox') {
				option_html += '<div class="form-group'+(v['required'] ? ' required' : '')+'">';
				option_html += '	<label class="control-label">'+v['name']+'</label>';
				option_html += '	<div id="input-option'+v['product_option_id']+']">';
				
				$.each(v['product_option_value'], function(k1,v1) {
				option_html += '		<div class="checkbox">';
				option_html += '			<label>';
				
				if (v1['enabled']) {
				option_html += '				<input type="checkbox" name="option['+v['product_option_id']+'][]" value="'+v1['product_option_value_id']+'" />';
				} else {
				option_html += '				<input type="checkbox" name="" value="" disabled/>';
				}
				option_html += v1['name'];
				if (v1['price']) {
				option_html += '				('+v1['price_prefix']+''+v1['price']+')';	
				}
				
				option_html += '			</label>';
				option_html += '		</div>';
				
				});
				option_html += '	</div>';
				option_html += '</div>';
			 }
			 
		});
		return option_html;
	}
}
	


function sortTable(f,n){
  var rows = $('#gp-table tbody  tr').get();

  rows.sort(function(a, b) {

  var A = $(a).children('td').eq(n).text().toUpperCase();
  var B = $(b).children('td').eq(n).text().toUpperCase();

  if(A < B) {
    return -1*f;
  }
  if(A > B) {
    return 1*f;
  }
  return 0;
  });

  $.each(rows, function(index, row) {
    $('#gp-table').children('tbody').append(row);
  });
}
var f_sl = 1;
var f_nm = 1;
$(".sortt").click(function(){
    f_sl *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_sl,n);
});

//--></script>
	<!-- End JS - Grouped Product powered by www.fabiom7.com //-->
</div><?php echo $footer; ?>
