<div class="table-responsive col-sm-12 white">
	<table id="gp-table" class="gp-table table table-hover table-stripped">
		<thead>
			<tr>
				<td rowspan="2">Name</td>
        <?php if ($agnames) { ?>
        	<?php foreach($agnames as $ag) { ?>
				<td colspan="<?php echo count($ag['a']); ?>" align="center"><?php echo $ag['name']; ?></td>
        	<?php } ?>
        <?php } ?>
        <td rowspan="2"><?php echo $column_gp_price; ?></td>
        <?php if ($column_gp_option) { ?>
        <td rowspan="2" style="width: 300px;"><?php echo $column_gp_option; ?></td>
        <?php } ?>
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
				<td><a href="#"
					onclick="addGpProduct('<?php echo $child_id; ?>','<?php echo $child['name']; ?>')"><?php echo $child['name']; ?></a></td>
      
        <?php  if ($agnames) {foreach($agnames as $key => $ag) { ?>
        	<?php foreach($ag['a'] as $key2 => $a) {  ?>
				<td>
					<?php echo isset($child['attributes'][$key]['attribute'][$key2]['text'])?$child['attributes'][$key]['attribute'][$key2]['text']:''; ?>
				</td>
				
			<?php } ?>
    	<?php } } ?>
        
        <td class="gp-col-price">
					<div class="gp-child-price">
            <?php if (!$child['special']) { ?>
            <?php echo $child['price']; ?>
            <?php } else { ?>
            <span class="gp-child-price-old"><?php echo $child['price']; ?></span> <?php echo $child['special']; ?>
            <?php } ?>
          </div>
          <?php foreach ($child['discounts'] as $discount) { ?>
          <div class="gp-toggle-info gp-child-discount"><?php echo $discount['quantity']; ?><?php echo $text_discount; ?><?php echo $discount['price']; ?></div>
          <?php } ?>
        </td>
        <?php if ($column_gp_option) { ?>
        <td class="gp-col-option" id="options-<?php echo $child_id; ?>"><?php foreach ($child['options'] as $option) { ?>
            <?php if ($option['type'] == 'select') { ?>
            <div
						class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
						<label class="control-label"
							for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
						<select name="option[<?php echo $option['product_option_id']; ?>]"
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
            <div
						class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
						<label class="control-label"><?php echo $option['name']; ?></label>
						<div id="input-option<?php echo $option['product_option_id']; ?>">
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <div class="radio">
								<label> <input type="radio"
									name="option[<?php echo $option['product_option_id']; ?>]"
									value="<?php echo $option_value['product_option_value_id']; ?>" />
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
            <div
						class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
						<label class="control-label"><?php echo $option['name']; ?></label>
						<div id="input-option<?php echo $option['product_option_id']; ?>">
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <div class="checkbox">
								<label> <input type="checkbox"
									name="option[<?php echo $option['product_option_id']; ?>][]"
									value="<?php echo $option_value['product_option_value_id']; ?>" />
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
						<div id="input-option<?php echo $option['product_option_id']; ?>"
							class="dropdown fs-dropdown">
							<div class="input-group">
								<div class="input-group-addon"
									id="fs-dropdown-selected<?php echo $option['product_option_id']; ?>"><?php echo $text_select; ?></div>
								<div class="input-group-btn">
									<button type="button" class="btn dropdown-toggle"
										data-toggle="dropdown">
										<span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
                      <?php foreach ($option['product_option_value'] as $option_value) { ?>
                      <li><label
											onclick="fsDropdown($(this), 'fs-dropdown-selected<?php echo $option['product_option_id']; ?>');">
												<input type="radio"
												name="option[<?php echo $option['product_option_id']; ?>]"
												value="<?php echo $option_value['product_option_value_id']; ?>"
												style="display: none; width: 0;" /> <img
												src="<?php echo $option_value['image']; ?>"
												alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" /> <?php echo $option_value['name']; ?>
                          <?php if ($option_value['price']) { ?>
                          (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                          <?php } ?>
                        </label></li>
                      <?php } ?>
                    </ul>
								</div>
							</div>
						</div>
					</div>
            <?php 
/*
										       * <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
										       * <label class="control-label"><?php echo $option['name']; ?></label>
										       * <div id="input-option<?php echo $option['product_option_id']; ?>">
										       * <?php foreach ($option['product_option_value'] as $option_value) { ?>
										       * <div class="radio">
										       * <label>
										       * <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
										       * <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" /> <?php echo $option_value['name']; ?>
										       * <?php if ($option_value['price']) { ?>
										       * (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
										       * <?php } ?>
										       * </label>
										       * </div>
										       * <?php } ?>
										       * </div>
										       * </div>
										       */
										?>
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
								value="<?php echo $option['value']; ?>" data-format="YYYY-MM-DD"
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
								data-format="YYYY-MM-DD HH:mm"
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
								value="<?php echo $option['value']; ?>" data-format="HH:mm"
								id="input-option<?php echo $option['product_option_id']; ?>"
								class="form-control" /> <span class="input-group-btn">
								<button type="button" class="btn btn-default">
									<i class="fa fa-calendar"></i>
								</button>
							</span>
						</div>
					</div>
            <?php } ?>
        <?php } ?></td>
        <?php } ?>
      </tr>
      <?php } ?>
    </tbody>
	</table>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
function addGpProduct(product_id,name) {
	$('#enquiry-product-details input[name=\'product\']').val(name);
	$('#enquiry-product-details input[name=\'product_id\']').val(product_id);
	$.ajax({
		url : 'index.php?route=product/product/getdescription&product_id='+product_id,
	    dataType: "text",
	    success : function (data) {
	    	$("#enquiry-description").val(data);
	    }
	});
	$('#modal-GP').modal('hide');
}
</script>