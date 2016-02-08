<div class="row">
	<blockquote style="border-left: 0">
		<form role="form">

			<fieldset id="account-register">
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
								id="register-customer_group_id<?php echo $customer_group['customer_group_id']; ?>"
								checked="checked" /> <strong><?php echo $customer_group['name']; ?></strong>
							</label>
						</div>
                                    <?php } else { ?>
                                        <div class="radio">
							<label> <input type="radio" name="customer_group_id"
								value="<?php echo $customer_group['customer_group_id']; ?>"
								id="register-customer_group_id<?php echo $customer_group['customer_group_id']; ?>" />
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
					<input type="text" name="firstname" id="input-register-firstname"
						value="<?php echo $firstname; ?>" class="form-control input-sm" />
				</div>
				<div class="form-group form-group-sm required">
					<label class="control-label"> <?php echo $entry_lastname; ?></label>
					<input type="text" name="lastname" id="input-register-lastname"
						value="<?php echo $lastname; ?>" class="form-control input-sm" />
				</div>
				<div class="form-group form-group-sm required">
					<label class="control-label"> <?php echo $entry_email; ?></label> <input
						type="text" name="email" id="input-register-email"
						value="<?php echo $email; ?>" class="form-control input-sm" />
				</div>
				<div
					class="form-group form-group-sm <?php
					if (empty ( $register_telephone_tax_display ) && empty ( $register_telephone_require )) {
						echo 'hidden';
					}
					?>">
                         <?php if (!empty($register_telephone_require)) { ?>
                        <span class="required">*</span> <?php echo $entry_telephone; ?>
                        <input type="text" name="telephone"
						id="input-register-telephone" value="<?php echo $telephone; ?>"
						class="form-control input-sm" />
                    <?php } else { ?>
                        <?php echo $entry_telephone; ?>
                        <input type="text" name="telephone"
						id="input-register-telephone" value="0000"
						class="form-control input-sm" />
                    <?php } ?>
                </div>
				<div
					class="form-group form-group-sm <?php
					if (empty ( $register_telephone_tax_display )) {
						echo 'hidden';
					}
					?>">
                         <?php echo $entry_fax; ?>
                    <input type="text" name="fax"
						id="input-register-fax" value="<?php echo $fax; ?>"
						class="form-control input-sm" />
				</div>
                <?php foreach ($custom_fields as $custom_field) { ?>
                <?php if ($custom_field['location'] == 'account') { ?>
                <?php if ($custom_field['type'] == 'select') { ?>
                <div
					id="register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"
						for="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<select
						name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
						id="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						class="form-control input-sm">
						<option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <option
							value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
                    <?php } ?>
                  </select>
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'radio') { ?>
                <div
					id="register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"><?php echo $custom_field['name']; ?></label>
					<div
						id="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <div class="radio">
							<label> <input type="radio"
								name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
								value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                        <?php echo $custom_field_value['name']; ?></label>
						</div>
                    <?php } ?>
                  </div>
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'checkbox') { ?>
                <div
					id="register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"><?php echo $custom_field['name']; ?></label>
					<div
						id="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <div class="checkbox">
							<label> <input type="checkbox"
								name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>][]"
								value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                        <?php echo $custom_field_value['name']; ?></label>
						</div>
                    <?php } ?>
                  </div>
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'text') { ?>
                <div
					id="register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"
						for="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<input type="text"
						name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
						value="<?php echo $custom_field['value']; ?>"
						placeholder="<?php echo $custom_field['name']; ?>"
						id="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						class="form-control input-sm" />
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'textarea') { ?>
                <div
					id="register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"
						for="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<textarea
						name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
						rows="5" placeholder="<?php echo $custom_field['name']; ?>"
						id="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						class="form-control"><?php echo $custom_field['value']; ?></textarea>
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'file') { ?>
                <div
					id="register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"><?php echo $custom_field['name']; ?></label>
					<br />
					<button type="button"
						id="button-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						data-loading-text="<?php echo $text_loading; ?>"
						class="btn btn-sm btn-default">
						<i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
					<input type="hidden"
						name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
						value=""
						id="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>" />
				</div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'date') { ?>
                <div
					id="register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"
						for="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<div class="input-group date">
						<input type="text"
							name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
							value="<?php echo $custom_field['value']; ?>"
							placeholder="<?php echo $custom_field['name']; ?>"
							data-date-format="YYYY-MM-DD"
							id="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
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
					id="register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"
						for="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<div class="input-group time">
						<input type="text"
							name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
							value="<?php echo $custom_field['value']; ?>"
							placeholder="<?php echo $custom_field['name']; ?>"
							data-date-format="HH:mm"
							id="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
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
					id="register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
					class="form-group custom-field"
					data-sort="<?php echo $custom_field['sort_order']; ?>">
					<label class="control-label"
						for="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
					<div class="input-group datetime">
						<input type="text"
							name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
							value="<?php echo $custom_field['value']; ?>"
							placeholder="<?php echo $custom_field['name']; ?>"
							data-date-format="YYYY-MM-DD HH:mm"
							id="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
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
			<fieldset>
				<legend class="text-info">
					<i class="fa fa-lock" style="font-size: 1.5em;"></i>&nbsp;<?php echo $text_your_password; ?></legend>
				<div class="form-group form-group-sm required">
					<label class="control-label"> <?php echo $entry_password; ?></label>
					<input type="password" name="password" id="input-register-password"
						value="" class="form-control input-sm" />
				</div>
				<div class="form-group form-group-sm required">
					<label class="control-label"> <?php echo $entry_confirm; ?></label>
					<input type="password" name="confirm" id="input-register-confirm"
						value="" class="form-control input-sm" />
				</div>
			</fieldset>
		</form>
	</blockquote>
	<blockquote style="border-left: 0">
		<form role="form">

			<fieldset id="address-register">
                <?php if (!$quick_register) { ?>
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


                <div id="register-your-address" style="<?php
																if ($quick_register && ! $use_address) {
																	echo 'display:none;';
																}
																?>">
					<div class="form-group form-group-sm ">
						<label class="control-label"><?php echo $entry_company; ?></label>
						<input type="text" name="company" id="input-register-company"
							value="<?php echo $company; ?>" class="form-control input-sm" />
					</div>

					<div class="form-group form-group-sm required">
						<label class="control-label"> <?php echo $entry_address_1; ?></label>
						<input type="text" name="address_1" id="input-register-address-1"
							value="<?php echo $address_1; ?>" class="form-control input-sm" />
					</div>
					<div class="form-group form-group-sm ">
						<label class="control-label"><?php echo $entry_address_2; ?></label>
						<input type="text" name="address_2" id="input-register-address-2"
							value="<?php echo $address_2; ?>" class="form-control input-sm" />
					</div>

					<div class="form-group form-group-sm required">
						<label class="control-label"> <?php echo $entry_city; ?></label> <input
							type="text" name="city" id="input-register-city"
							value="<?php echo $city; ?>" class="form-control input-sm" />
					</div>
					<div class="form-group form-group-sm ">
						<label class="control-label"><span
							id="register-register-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></label>
						<input type="text" name="postcode" id="input-register-postcode"
							value="<?php echo $postcode; ?>" class="form-control input-sm" />
					</div>
					<div class="form-group form-group-sm required">
						<label class="control-label"> <?php echo $entry_country; ?></label>
						<select id="register_country_id" name="country_id"
							id="input-register-country" class="form-control input-sm">
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
							name="zone_id" id="input-register-zone"
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
                    <?php foreach ($custom_fields as $custom_field) { ?>
                    <?php if ($custom_field['location'] == 'address') { ?>
                    <?php if ($custom_field['type'] == 'select') { ?>
                    <div
						id="register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						class="form-group custom-field"
						data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label"
							for="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
						<select
							name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
							id="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
							class="form-control input-sm">
							<option value=""><?php echo $text_select; ?></option>
                        <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                        <option
								value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
                        <?php } ?>
                      </select>
					</div>
                    <?php } ?>
                    <?php if ($custom_field['type'] == 'radio') { ?>
                    <div
						id="register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						class="form-group custom-field"
						data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label"><?php echo $custom_field['name']; ?></label>
						<div
							id="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                        <div class="radio">
								<label> <input type="radio"
									name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
									value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                            <?php echo $custom_field_value['name']; ?></label>
							</div>
                        <?php } ?>
                      </div>
					</div>
                    <?php } ?>
                    <?php if ($custom_field['type'] == 'checkbox') { ?>
                    <div
						id="register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						class="form-group custom-field"
						data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label"><?php echo $custom_field['name']; ?></label>
						<div
							id="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                        <div class="checkbox">
								<label> <input type="checkbox"
									name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>][]"
									value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                            <?php echo $custom_field_value['name']; ?></label>
							</div>
                        <?php } ?>
                      </div>
					</div>
                    <?php } ?>
                    <?php if ($custom_field['type'] == 'text') { ?>
                    <div
						id="register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						class="form-group custom-field"
						data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label"
							for="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
						<input type="text"
							name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
							value="<?php echo $custom_field['value']; ?>"
							placeholder="<?php echo $custom_field['name']; ?>"
							id="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
							class="form-control input-sm" />
					</div>
                    <?php } ?>
                    <?php if ($custom_field['type'] == 'textarea') { ?>
                    <div
						id="register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						class="form-group custom-field"
						data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label"
							for="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
						<textarea
							name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
							rows="5" placeholder="<?php echo $custom_field['name']; ?>"
							id="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
							class="form-control"><?php echo $custom_field['value']; ?></textarea>
					</div>
                    <?php } ?>
                    <?php if ($custom_field['type'] == 'file') { ?>
                    <div
						id="register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						class="form-group custom-field"
						data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label"><?php echo $custom_field['name']; ?></label>
						<br />
						<button type="button"
							id="button-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
							data-loading-text="<?php echo $text_loading; ?>"
							class="btn btn-sm btn-default">
							<i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
						<input type="hidden"
							name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
							value=""
							id="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>" />
					</div>
                    <?php } ?>
                    <?php if ($custom_field['type'] == 'date') { ?>
                    <div
						id="register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						class="form-group custom-field"
						data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label"
							for="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
						<div class="input-group date">
							<input type="text"
								name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
								value="<?php echo $custom_field['value']; ?>"
								placeholder="<?php echo $custom_field['name']; ?>"
								data-date-format="YYYY-MM-DD"
								id="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
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
						id="register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						class="form-group custom-field"
						data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label"
							for="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
						<div class="input-group time">
							<input type="text"
								name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
								value="<?php echo $custom_field['value']; ?>"
								placeholder="<?php echo $custom_field['name']; ?>"
								data-date-format="HH:mm"
								id="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
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
						id="register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
						class="form-group custom-field"
						data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label"
							for="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
						<div class="input-group datetime">
							<input type="text"
								name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]"
								value="<?php echo $custom_field['value']; ?>"
								placeholder="<?php echo $custom_field['name']; ?>"
								data-date-format="YYYY-MM-DD HH:mm"
								id="input-register-custom-field<?php echo $custom_field['custom_field_id']; ?>"
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
                </div>
			</fieldset>

		</form>
	</blockquote>
</div>

<div class="row">

	<div class="col-xs-12">
		<div class="form-group form-group-sm">
			<div class="checkbox">
				<label>
                        <?php if ($newsletter) { ?>
                            <input type="checkbox" name="newsletter"
					id="input-register-newsletter" value="1" checked="checked" /> 
                        <?php } else { ?>
                            <input type="checkbox" name="newsletter"
					id="input-register-newsletter" value="1" /> 
                        <?php } ?>
                        <strong><?php echo $entry_newsletter; ?></strong>
				</label>
			</div>
		</div>

            <?php if ($shipping_required) { ?>
                <div class="form-group form-group-sm ">
			<div class="checkbox">
				<label>
                            <?php if ($shipping_address) { ?>
                                <input type="checkbox"
					name="shipping_address" id="input-register-shipping-address"
					value="1" checked="checked" />
                            <?php } else { ?>
                                <input type="checkbox"
					name="shipping_address" id="input-register-shipping-address"
					value="1" />
                            <?php } ?>

                            <strong><?php echo $entry_shipping; ?></strong>
				</label>
			</div>
		</div>
            <?php } ?>

    </div>
</div>

<div class="row">
	<div class="panel-footer">
        <?php if ($text_agree) { ?>
            <?php if ($agree) { ?>
                <input type="checkbox" name="agree"
			id="input-register-agree" value="1" checked="checked" />
            <?php } else { ?>
                <input type="checkbox" name="agree"
			id="input-register-agree" value="1" />
            <?php } ?>

            <strong class="text-warning"><i><?php echo $text_agree; ?></i></strong>
		<div class="text-right">
			<input type="button" value="<?php echo $button_continue; ?>" id="button-register" class="btn btn-<?php
									if ($css ['checkout_theme'] == 'standar') {
										echo 'primary';
									} else {
										echo $css ['checkout_theme'];
									}
									?>"  style="<?php
									if (! empty ( $css ['common_btn_color'] )) {
										echo "background-color:{$css['common_btn_color']}!important; background-image:none;";
									}
									?>"/>
		</div>
        <?php } else { ?>
            <div class="text-right">
			<input type="button" value="<?php echo $button_continue; ?>" id="button-register" class="btn btn-<?php
									if ($css ['checkout_theme'] == 'standar') {
										echo 'primary';
									} else {
										echo $css ['checkout_theme'];
									}
									?>"  style="<?php
									if (! empty ( $css ['common_btn_color'] )) {
										echo "background-color:{$css['common_btn_color']}!important; background-image:none;";
									}
									?>"/>
		</div>
        <?php } ?>
    </div>
</div>




<script type="text/javascript"><!--
    
    $('#account-register .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#account-register .form-group').length) {
		$('#account-register .form-group').eq($(this).attr('data-sort')).before(this);
	} 
	
	if ($(this).attr('data-sort') > $('#account-register .form-group').length) {
		$('#account-register .form-group:last').after(this);
	}
		
	if ($(this).attr('data-sort') < -$('#account-register .form-group').length) {
		$('#account-register .form-group:first').before(this);
	}
});

$('#address-register .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#address-register .form-group').length) {
		$('#address-register .form-group').eq($(this).attr('data-sort')).before(this);
	} 
	
	if ($(this).attr('data-sort') > $('#address-register .form-group').length) {
		$('#address-register .form-group:last').after(this);
	}
		
	if ($(this).attr('data-sort') < -$('#address-register .form-group').length) {
		$('#address-register .form-group:first').before(this);
	}
});

//$('#register-form-detail input[name=\'customer_group_id\']').on('change', function() {
//});

$('#register-form-detail input[name=\'customer_group_id\']').on('change', function() {
        $.ajax({
		url: 'index.php?route=checkout/checkout_onepage/group_approval&customer_group_id=' + this.value,
		dataType: 'json',
                beforeSend: function() {
                   
                },
                complete: function() {
                        unlock_input();
                },
		success: function(json) {
			if(json['gourp_approval'] == 0){
                            //$('#button-register').css('display','none');
                            $('#confirm-footer-panel div.checkout-overlay').addClass('hidden');
                            $('#confirm-footer-panel span.alert').remove();
                        } else {
                            //$('#button-register').css('display','');
                            $('#confirm-footer-panel div.checkout-overlay').removeClass('hidden');
                            $('#confirm-footer-panel div.text-right').after('<span class="alert alert-danger"><?php echo $error_approved;?></span>');
                        }
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
	$.ajax({
		url: 'index.php?route=checkout/checkout_onepage/customfield&customer_group_id=' + this.value,
		dataType: 'json',
                beforeSend: function() {
                   
                },
                complete: function() {
                        unlock_input();
                },
		success: function(json) {
			$('#register-form-detail .custom-field').hide();
			$('#register-form-detail .custom-field').removeClass('required');

			for (i = 0; i < json.length; i++) {
				custom_field = json[i];

				$('#register-custom-field' + custom_field['custom_field_id']).show();

				if (custom_field['required']) {
					$('#register-custom-field' + custom_field['custom_field_id']).addClass('required');
				}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
//$('#register-form-detail input[name=\'customer_group_id\']:checked').trigger('change');

$('#register-form-detail button[id^=\'button-register-custom-field\']').on('click', function() {
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
					$('.text-danger').remove();
					
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

$('select#register_country_id').bind('change', function(event, data) {

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
                $('#confirm-footer-panel div.checkout-overlay').addClass('hidden');
                unlock_input();
            },
            success: function(json) {
                if (json['postcode_required'] == '1') {
                    $('#register-register-postcode-required').show();
                } else {
                    $('#register-register-postcode-required').hide();
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

                $('select#input-register-zone').html(html);
                $('select#input-register-zone').attr('disabled', false);
                $('select#input-register-zone').trigger('change');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    //$('select#register_country_id').trigger('change');

    $('#register-address').on('change', '#register-form-detail input[name=\'use-address\']', function() {
        if ($(this).is(':checked')) {
            $('#register-your-address').slideDown('fast');
            $('#register-form-detail select[name=\'zone_id\']').trigger('change');
        } else {
            $('#register-your-address').slideUp('fast');
            reset_register_country_zone();
        }
    });
    function reset_register_country_zone() {
        $('#register-form-detail select[name=\'country_id\'] option[value=\'<?php echo $config_country_id; ?>\']').attr('selected', 'selected');
        $('#register-form-detail select[name=\'zone_id\']').trigger('change', {zone_id: '<?php echo $config_zone_id; ?>'});
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