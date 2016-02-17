        <?php if ($dbe) { ?>
        	<td class="gp-col-checkbox"><input type="checkbox" name="products[]" id="pcd-<?php echo $child['child_id']; ?>" value="<?php echo $child['child_id']; ?>" />
						<label for="pcd-<?php echo $child['child_id']; ?>"></label>
					</td>
        <?php } ?>
				<td class="gp-col-name">
					<div class="gp-child-name"><?php echo $child['name']; ?>
						<?php if ($child['enabled']) { ?> by Seller 
							<a href="<?php echo $child['vlink']; ?>"><?php echo $child['curr_vendor']['companyname']; ?></a> <sup onclick="reloadGp('<?php echo $child['child_id']; ?>');">More Suppliers</sup>
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
					<div class="btn-toolbar" role="toolbar" aria-label="...">
						<div class="btn-group justified" role="group" aria-label="...">
							<button type="button"	data-loading-text="<?php echo $text_loading; ?>" <?php if ($child['enabled']) { ?> onclick="addGpGrouped('<?php echo $child_id; ?>');" <?php } else { echo "disabled=\"disabled\""; } ?> class="btn btn-primary"><?php echo $button_add_to_cart; ?></button>
						</div>
						<div class="btn-group" role="group" aria-label="...">
							<button type="button" data-loading-text="<?php echo $text_loading; ?>" onclick="addGpGroupedToQuote('<?php echo $child_id; ?>');" class="btn btn-primary"><?php echo $button_add_to_quote; ?></button>
						</div>
					</div>
				</td>
      