
<div class="row">
	<blockquote style="border-left: 0">
		<form role="form">
			<fieldset id="account-guest">
				<legend class="text-info">
					<i class="fa fa-user" style="font-size: 1.5em;"></i>&nbsp;<?php echo $text_your_details; ?></legend>
                    
                <?php if (!$customer_group_style) { ?>
                        <div style="display: <?php echo (count($customer_groups) > 1 ? 'block' : 'none'); ?>;" class="form-group form-group-sm">
					<legend class="text-success" style="font-size: 18px;">
						<i class="fa fa-users"></i> <?php echo $entry_customer_group; ?></legend>
					<div class="customer-groups">
                                <?php foreach ($customer_groups as $customer_group) { ?>
                                    <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                                        <div class="radio">
							<label> <input type="radio" name="customer_group_id"
								value="<?php echo $customer_group['customer_group_id']; ?>"
								id="guest-customer_group_id<?php echo $customer_group['customer_group_id']; ?>"
								checked="checked" /> <strong><?php echo $customer_group['name']; ?></strong>
							</label>
						</div>
                                    <?php } else { ?>
                                        <div class="radio">
							<label> <input type="radio" name="customer_group_id"
								value="<?php echo $customer_group['customer_group_id']; ?>"
								id="guest-customer_group_id<?php echo $customer_group['customer_group_id']; ?>" />
								<strong><?php echo $customer_group['name']; ?></strong>
							</label>
						</div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
				</div>
                    <?php } else { ?>
                        <div style="display: <?php echo (count($customer_groups) > 1 ? 'block' : 'none'); ?>;" class="form-group form-group-sm">
					<legend class="text-success" style="font-size: 18px;">
						<i class="fa fa-users"></i> <?php echo $entry_customer_group; ?></legend>
					<div>
						<strong> <select name="customer_group_id">
                                        <?php foreach ($customer_groups as $customer_group) { ?>
                                            <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                                                <option
									value="<?php echo $customer_group['customer_group_id']; ?>"
									selected="selected"><?php echo $customer_group['name']; ?></option>
                                            <?php } else { ?>
                                                <option
									value="<?php echo $customer_group['customer_group_id']; ?>"
									selected="selected"><?php echo $customer_group['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
						</strong>
					</div>
				</div>
                    <?php } ?>
                
                <div class="form-group form-group-sm required">
					<label class="control-label"> <?php echo $entry_firstname; ?></label>
					<input type="text" name="firstname" id="input-payment-firstname"
						value="<?php echo $firstname; ?>" class="form-control input-sm">
				</div>
				<div class="form-group form-group-sm required">
					<label class="control-label"> <?php echo $entry_lastname; ?></label>
					<input type="text" name="lastname" id="input-payment-lastname"
						value="<?php echo $lastname; ?>" class="form-control input-sm" />
				</div>
				<div class="form-group form-group-sm required">
					<label class="control-label"> <?php echo $entry_email; ?></label> <input
						type="text" name="email" id="input-payment-email"
						value="<?php echo $email; ?>" class="form-control input-sm" />
				</div>
				<div
					class="form-group form-group-sm <?php
					if (empty ( $guest_telephone_tax_display ) && empty ( $guest_telephone_require )) {
						echo 'hidden';
					}
					?>">
                         <?php if (!empty($guest_telephone_require)) { ?>
                        <label class="control-label"> <span
						class="required">*</span> <?php echo $entry_telephone; ?></label>
					<input type="text" name="telephone" id="input-payment-telephone"
						value="<?php echo $telephone; ?>" class="form-control input-sm" />
                    <?php } else { ?>
                        <?php echo $entry_telephone; ?>
                        <input type="text" name="telephone"
						id="input-payment-telephone" value="0000"
						class="form-control input-sm" />
                    <?php } ?>
                </div>
				<div
					class="form-group form-group-sm <?php
					if (empty ( $guest_telephone_tax_display )) {
						echo 'hidden';
					}
					?>">
					<label class="control-label"> <?php echo $entry_fax; ?>
                    <input type="text" name="fax" id="input-payment-fax"
						value="<?php echo $fax; ?>" class="form-control input-sm" />
				
				</div>
                <?php foreach ($custom_fields as $custom_field) { ?>
                <?php if ($custom_field['location'] == 'account') { ?>
                <?php if ($custom_field['type'] == 'select') { ?>
                <div
					id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"
						for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<select
						name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
						id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						class="form-control input-sm">
						<option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <?php if (isset($guest_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $guest_custom_field[$custom_field['custom_field_id']]) { ?>
                    <option
							value="<?php echo $custom_field_value['custom_field_value_id']; ?>"
							selected="selected"><?php echo $custom_field_value['name']; ?></option>
                    <?php } else { ?>
                    <option
							value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'radio') { ?>
                <div
					id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"><?php echo $custom_field['name']; ?></label>
					<div
						id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <div class="radio">
                      <?php if (isset($guest_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $guest_custom_field[$custom_field['custom_field_id']]) { ?>
                      <label> <input type="radio"
								name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
								value="<?php echo $custom_field_value['custom_field_value_id']; ?>"
								checked="checked" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } else { ?>
                      <label> <input type="radio"
								name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
								value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } ?>
                    </div>
                    <?php } ?>
                  </div>
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'checkbox') { ?>
                <div
					id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"><?php echo $custom_field['name']; ?></label>
					<div
						id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <div class="checkbox">
                      <?php if (isset($guest_custom_field[$custom_field['custom_field_id']]) && in_array($custom_field_value['custom_field_value_id'], $guest_custom_field[$custom_field['custom_field_id']])) { ?>
                      <label> <input type="checkbox"
								name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>][]"
								value="<?php echo $custom_field_value['custom_field_value_id']; ?>"
								checked="checked" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } else { ?>
                      <label> <input type="checkbox"
								name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>][]"
								value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } ?>
                    </div>
                    <?php } ?>
                  </div>
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'text') { ?>
                <div
					id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"
						for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<input type="text"
						name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
						value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>"
						placeholder="<?php echo $custom_field['name']; ?>"
						id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						class="form-control input-sm" />
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'textarea') { ?>
                <div
					id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"
						for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<textarea
						name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
						rows="5" placeholder="<?php echo $custom_field['name']; ?>"
						id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						class="form-control"><?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?></textarea>
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'file') { ?>
                <div
					id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"><?php echo $custom_field['name']; ?></label>
					<br />
					<button type="button"
						id="button-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						data-loading-text="<?php echo $text_loading; ?>"
						class="btn btn-sm btn-default">
						<i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
					<input type="hidden"
						name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
						value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : ''); ?>"
						id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" />
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'date') { ?>
                <div
					id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"
						for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<div class="input-group date">
						<input type="text"
							name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
							value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>"
							placeholder="<?php echo $custom_field['name']; ?>"
							data-date-format="YYYY-MM-DD"
							id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
							class="form-control" /> <span class="input-group-btn">
							<button type="button" class="btn btn-default">
								<i class="fa fa-calendar"></i>
							</button>
						</span>
					</div>
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'time') { ?>
                <div
					id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"
						for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<div class="input-group time">
						<input type="text"
							name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
							value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>"
							placeholder="<?php echo $custom_field['name']; ?>"
							data-date-format="HH:mm"
							id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
							class="form-control" /> <span class="input-group-btn">
							<button type="button" class="btn btn-default">
								<i class="fa fa-calendar"></i>
							</button>
						</span>
					</div>
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'datetime') { ?>
                <div
					id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"
						for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<div class="input-group datetime">
						<input type="text"
							name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
							value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>"
							placeholder="<?php echo $custom_field['name']; ?>"
							data-date-format="YYYY-MM-DD HH:mm"
							id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
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
            </fieldset>
		</form>
	</blockquote>
	<blockquote style="border-left: 0">
		<form role="form">
			<fieldset id="address-guest">

                <?php if (!$quick_checkout) { ?>
                    <legend class="text-info">
					<i class="fa fa-home" style="font-size: 1.5em;"></i>&nbsp;<?php echo $text_your_address; ?></legend>
                <?php } else { ?>
                    <label style="display: table; cursor: pointer;"> <legend
						class="text-info bg-info" style="display: table-row;">
						<div style="display: table-cell; width: 99%;">
							<i class="fa fa-home" style="font-size: 1.5em;"></i>&nbsp;<?php echo $text_your_address; ?>
                            </div>
						<div style="display: table-cell; width: 1%;">
                                <?php if ($use_address) { ?>
                                    <input name="use-address"
								type="checkbox" value="1" checked="checked" class="" />
                                <?php } else { ?>
                                    <input name="use-address"
								type="checkbox" value="1" class="" />
                                <?php } ?>
                            </div>

					</legend>
				</label>
                <?php } ?>

                <div id="guest-your-address" style="<?php
																if ($quick_checkout && ! $use_address) {
																	echo 'display:none;';
																}
																?>">
					<div class="form-group form-group-sm ">
						<label class="control-label"> <?php echo $entry_company; ?></label>
						<input type="text" name="company" id="input-payment-company"
							value="<?php echo $company; ?>" class="form-control input-sm" />
					</div>

					<div class="form-group form-group-sm required">
						<label class="control-label"> <?php echo $entry_address_1; ?></label>
						<input type="text" name="address_1" id="input-payment-address-1"
							value="<?php echo $address_1; ?>" class="form-control input-sm">
					</div>
					<div class="form-group form-group-sm ">
						<label class="control-label"> <?php echo $entry_address_2; ?></label>
						<input type="text" name="address_2" id="input-payment-address-2"
							value="<?php echo $address_2; ?>" class="form-control input-sm" />
					</div>
					<div class="form-group form-group-sm required">
						<label class="control-label"> <?php echo $entry_city; ?></label> <input
							type="text" name="city" id="input-payment-city"
							value="<?php echo $city; ?>" class="form-control input-sm">
					</div>
					<div class="form-group form-group-sm ">
						<label class="control-label"> <span
							id="guest-payment-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></label>
						<input type="text" name="postcode" id="input-payment-postcode"
							value="<?php echo $postcode; ?>" class="form-control input-sm">
					</div>

					<div class="form-group form-group-sm required">
						<label class="control-label"> <?php echo $entry_country; ?></label>
						<select name="country_id" id="input-payment-country"
							class="form-control input-sm">
							<option value=""><?php echo $text_select; ?></option>
                            <?php foreach ($countries as $country) { ?>
                                <?php if ($country['country_id'] == $country_id) { ?>
                                    <option
								value="<?php echo $country['country_id']; ?>"
								selected="selected"><?php echo $country['name']; ?></option>
                                <?php } else { ?>
                                    <option
								value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
					</div>

					<div class="form-group form-group-sm required">
						<label class="control-label"> <?php echo $entry_zone; ?></label> <select
							name="zone_id" id="input-payment-zone"
							class="form-control input-sm">
                            <?php if ($zones) { ?>
                                <option value=""><?php echo $text_select; ?></option>
                                <?php foreach ($zones as $zn) { ?>
                                    <?php if ($zn['zone_id'] == $zone_id) { ?>
                                        <option
								value="<?php echo $zn['zone_id']; ?>" selected="selected"><?php echo $zn['name']; ?></option>
                                    <?php } else { ?>
                                        <option
								value="<?php echo $zn['zone_id']; ?>"><?php echo $zn['name']; ?></option>
                                    <?php } ?>

                                <?php } ?>
                            <?php } else { ?>
                                <option value="0" selected="selected"><?php echo $text_none; ?></option>
                            <?php } ?>
                        </select>
					</div>
				</div>
                <?php foreach ($custom_fields as $custom_field) { ?>
                <?php if ($custom_field['location'] == 'address') { ?>
                <?php if ($custom_field['type'] == 'select') { ?>
                <div
					id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"
						for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<select
						name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
						id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						class="form-control input-sm">
						<option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <?php if (isset($guest_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $guest_custom_field[$custom_field['custom_field_id']]) { ?>
                    <option
							value="<?php echo $custom_field_value['custom_field_value_id']; ?>"
							selected="selected"><?php echo $custom_field_value['name']; ?></option>
                    <?php } else { ?>
                    <option
							value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'radio') { ?>
                <div
					id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"><?php echo $custom_field['name']; ?></label>
					<div
						id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <div class="radio">
                      <?php if (isset($guest_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $guest_custom_field[$custom_field['custom_field_id']]) { ?>
                      <label> <input type="radio"
								name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
								value="<?php echo $custom_field_value['custom_field_value_id']; ?>"
								checked="checked" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } else { ?>
                      <label> <input type="radio"
								name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
								value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } ?>
                    </div>
                    <?php } ?>
                  </div>
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'checkbox') { ?>
                <div
					id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"><?php echo $custom_field['name']; ?></label>
					<div
						id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <div class="checkbox">
                      <?php if (isset($guest_custom_field[$custom_field['custom_field_id']]) && in_array($custom_field_value['custom_field_value_id'], $guest_custom_field[$custom_field['custom_field_id']])) { ?>
                      <label> <input type="checkbox"
								name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>][]"
								value="<?php echo $custom_field_value['custom_field_value_id']; ?>"
								checked="checked" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } else { ?>
                      <label> <input type="checkbox"
								name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>][]"
								value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } ?>
                    </div>
                    <?php } ?>
                  </div>
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'text') { ?>
                <div
					id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"
						for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<input type="text"
						name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
						value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>"
						placeholder="<?php echo $custom_field['name']; ?>"
						id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						class="form-control input-sm" />
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'textarea') { ?>
                <div
					id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"
						for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<textarea
						name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
						rows="5" placeholder="<?php echo $custom_field['name']; ?>"
						id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						class="form-control"><?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?></textarea>
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'file') { ?>
                <div
					id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"><?php echo $custom_field['name']; ?></label>
					<br />
					<button type="button"
						id="button-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						data-loading-text="<?php echo $text_loading; ?>"
						class="btn btn-sm btn-default">
						<i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
					<input type="hidden"
						name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
						value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : ''); ?>"
						id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" />
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'date') { ?>
                <div
					id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"
						for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<div class="input-group date">
						<input type="text"
							name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
							value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>"
							placeholder="<?php echo $custom_field['name']; ?>"
							data-date-format="YYYY-MM-DD"
							id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
							class="form-control" /> <span class="input-group-btn">
							<button type="button" class="btn btn-default">
								<i class="fa fa-calendar"></i>
							</button>
						</span>
					</div>
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'time') { ?>
                <div
					id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"
						for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<div class="input-group time">
						<input type="text"
							name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
							value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>"
							placeholder="<?php echo $custom_field['name']; ?>"
							data-date-format="HH:mm"
							id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
							class="form-control" /> <span class="input-group-btn">
							<button type="button" class="btn btn-default">
								<i class="fa fa-calendar"></i>
							</button>
						</span>
					</div>
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'datetime') { ?>
                <div
					id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"
						for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<div class="input-group datetime">
						<input type="text"
							name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
							value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>"
							placeholder="<?php echo $custom_field['name']; ?>"
							data-date-format="YYYY-MM-DD HH:mm"
							id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"
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
                <?php if ($shipping_required) { ?>
                <div class="form-group form-group-sm ">
					<div class="checkbox">
						<label>
                            <?php if (!empty($shipping_address_same)) { ?>
                                <input type="checkbox"
							name="shipping_address_same" value="1" id="guest-shipping"
							checked="checked" />
                            <?php } else { ?>
                                <input type="checkbox"
							name="shipping_address_same" value="1" id="guest-shipping" />
                            <?php } ?>
                            <strong><?php echo $entry_shipping; ?></strong>
						</label>
					</div>
				</div>
                <?php } ?>
            </fieldset>
		</form>
	</blockquote>
</div>


<div class="row">
	<div class="panel-footer">

		<div class="text-right">
			<input type="button" value="<?php echo $button_ok; ?>" id="button-guest" class="btn btn-sm btn-<?php
			if ($css ['checkout_theme'] == 'standar') {
				echo 'warning';
			} else {
				echo $css ['checkout_theme'];
			}
			?>"  style="<?php
																			if (! empty ( $css ['common_btn_color'] )) {
																				echo "background-color:{$css['common_btn_color']}!important; background-image:none;";
																			}
																			?>"/>
		</div>
	</div>
</div>




<script type="text/javascript"><!--
<?php if ($shipping_address_same) { ?>
    $(document).ready(function() {
        //            $('#shipping-address-panel').hide();
    });
<?php } else { ?>
    $(document).ready(function() {
        //            $('#shipping-address-panel').show();
    });
<?php } ?>

$('#account-guest .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#account-guest .form-group').length) {
		$('#account-guest .form-group').eq($(this).attr('data-sort')).before(this);
	} 
	
	if ($(this).attr('data-sort') > $('#account-guest .form-group').length) {
		$('#account-guest .form-group:last').after(this);
	}
		
	if ($(this).attr('data-sort') < -$('#account-guest .form-group').length) {
		$('#account-guest .form-group:first').before(this);
	}
});

$('#address-guest .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#address-guest .form-group').length) {
		$('#address-guest .form-group').eq($(this).attr('data-sort')).before(this);
	} 
	
	if ($(this).attr('data-sort') > $('#address-guest .form-group').length) {
		$('#address-guest .form-group:last').after(this);
	}
		
	if ($(this).attr('data-sort') < -$('#address-guest .form-group').length) {
		$('#address-guest .form-group:first').before(this);
	}
});

$('#guest-form-detail input[name=\'customer_group_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=checkout/checkout_onepage/customfield&customer_group_id=' + this.value,
		dataType: 'json',
                beforeSend: function() {
                      
                },
                complete: function() {
                        unlock_input();
                },
		success: function(json) {
			$('#guest-form-detail .custom-field').hide();
			$('#guest-form-detail .custom-field').removeClass('required');

			for (i = 0; i < json.length; i++) {
				custom_field = json[i];

				$('#payment-custom-field' + custom_field['custom_field_id']).show();

				if (custom_field['required']) {
					$('#payment-custom-field' + custom_field['custom_field_id']).addClass('required');
				} else {
					$('#payment-custom-field' + custom_field['custom_field_id']).removeClass('required');
				}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#guest-form-detail button[id^=\'button-payment-custom-field\']').on('click', function() {
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
                                        lock_input();
				},
				complete: function() {
					$(node).button('reset');
                                        unlock_input();
				},
				success: function(json) {
					$(node).parent().find('.text-danger').remove();
					
					if (json['error']) {
						$(node).parent().find('input[name^=\'custom_field\']').after('<div class="text-danger">' + json['error'] + '</div>');
					}
	
					if (json['success']) {
						alert(json['success']);
	
						$(node).parent().find('input[name^=\'custom_field\']').attr('value', json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});

$('select#input-payment-country').bind('change', function(event, data) {
    if (this.value == '')
        return;
    var zone_id = 0;
    if (data && data.zone_id) {
        zone_id = data.zone_id;
    } else {
        zone_id = '<?php echo $zone_id; ?>';
    }
    $.ajax({
        url: 'index.php?route=checkout/checkout_onepage/country&country_id=' + this.value,
        dataType: 'json',
        beforeSend: function() {
            $('#billing-wait').removeClass('hidden');
        },
        complete: function() {
        },
        success: function(json) {
            if (json['postcode_required'] == '1') {
                $('#guest-payment-postcode-required').show();
            } else {
                $('#guest-payment-postcode-required').hide();
            }

            html = '<option value=""><?php echo $text_select; ?></option>';
            if (json['zone'] != '') {
                for (i = 0; i < json['zone'].length; i++) {
                    html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                    if (json['zone'][i]['zone_id'] == zone_id) {
                        html += ' selected="selected"';
                    }

                    html += '>' + json['zone'][i]['name'] + '</option>';
                }
            } else {
                html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
            }

            $('select#input-payment-zone').html(html);

            $('select#input-payment-zone').attr('disabled', false);
            $('select#input-payment-zone').trigger('change');
            //$('select#input-payment-zone').attr('disabled', true);

        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            $('#checkout-wait-notification').addClass('hidden');
        }
    });
});


$('#payment-address').on('change', '#guest-form-detail input[name=\'use-address\']', function() {
    if ($(this).is(':checked')) {
        $('#guest-your-address').slideDown('fast');
        $('select#input-payment-zone').trigger('change');
    } else {
        $('#guest-your-address').slideUp('fast');
        reset_payment_country_zone();
    }
});

function reset_payment_country_zone() {

    $('select#input-payment-country option[value=\'<?php echo $config_country_id; ?>\']').attr('selected', 'selected');
    $('select#input-payment-country').trigger('change', {zone_id: '<?php echo $config_zone_id; ?>'});
}
//--></script>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.time').datetimepicker({
	pickDate: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script>