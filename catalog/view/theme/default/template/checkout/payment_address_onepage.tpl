<?php if (isset($new_payment_address_id) && $new_payment_address_id !== false) { ?>    <input type="hidden" id="new_payment_address_id" name="new_payment_address_id" value="<?php echo $new_payment_address_id; ?>"/><?php } ?><div id="payment-address-options" style="<?php echo (!($addresses && (!$shipping_required || $trust_exist))?'display:none;':''); ?>">    <div class="radio">        <label for="payment-address-existing">             <input type="radio" name="payment_address" value="existing" id="payment-address-existing"  <?php if ($use_exist) echo 'checked="checked"'; ?>/><?php echo $text_address_existing; ?> 
        </label>    </div>    <div class="radio clearfix">        <label for="payment-address-new">            <input type="radio" name="payment_address" value="new" id="payment-address-new" <?php echo (!$use_exist?'checked="checked"':''); ?> />           <?php echo $text_address_new; ?>
        </label>    </div>    <div class="row">        <hr>      </div></div><?php // if ($addresses && (!$shipping_required || $trust_exist)) { ?><div id="payment-existing" style="<?php echo (!$use_exist?'display:none;':''); ?>">    <!--    <select name="address_id" style="width: 100%; margin-bottom: 15px;" size="5">        <?php if (!$shipping_required) {//## show all trust & fake address ?>            <?php foreach ($addresses as $address) { ?>                <?php if ($address['address_id'] == $address_id) { ?>                    <option value="<?php echo $address['address_id']; ?>" selected="selected"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>                <?php } else { ?>                    <option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>                <?php } ?>            <?php } ?>        <?php } else { //$trust_exist?>            <?php foreach ($trusted_addresses as $address) { ?>                <?php if ($address['address_id'] == $address_id) { ?>                    <option value="<?php echo $address['address_id']; ?>" selected="selected"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>                <?php } else { ?>                    <option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>                <?php } ?>            <?php } ?>              <?php } ?>    </select>     -->  <form> <?php foreach ($addresses as $address) { ?>  <div class="clearfix payment-address"> 		<input type="radio" name="address_id" id="address<?php echo $address['address_id']; ?>" value="<?php echo $address['address_id']; ?>" class="radio" <?php echo (($address['address_id'] == $address_id)?'checked':''); ?> />		<label for="address<?php echo $address['address_id']; ?>">			<?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?><br />			<?php echo $address['address_1']; ?> <?php echo $address['city']; ?><br />			<?php echo $address['zone']; ?> <?php echo $address['country']; ?>		</label>  </div>  </form><?php } ?>     </div><?php // }   ?>


<div id="payment-new"  style="display:<?php echo (!$use_exist?'block;':'none'); ?>">    <form class="form-horizontal" role="form">        <div class="form-group form-group-sm ">            <span class="required">*</span> <?php echo $entry_firstname; ?>            <input type="text" name="firstname"  id="input-payment-firstname" value="<?php            if (!empty($new_address['firstname'])) {                echo $new_address['firstname'];            }            ?>" class="form-control input-sm" />        </div>        <div class="form-group form-group-sm ">            <span class="required">*</span> <?php echo $entry_lastname; ?>            <input type="text" name="lastname"  id="input-payment-lastname" value="<?php echo (!empty($new_address['lastname'])?$new_address['lastname']:''); ?>" class="form-control input-sm" />        </div>        <div class="form-group form-group-sm ">            <?php echo $entry_company; ?>            <input type="text" name="company"  id="input-payment-company" value="<?php echo (!empty($new_address['company'])?$new_address['company']:''); ?>" class="form-control input-sm" />        </div>        <div class="form-group form-group-sm ">            <span class="required">*</span> <?php echo $entry_address_1; ?>            <input type="text" name="address_1"  id="input-payment-address-1" value="<?php echo (!empty($new_address['address_1'])?$new_address['address_1']:''); ?>" class="form-control input-sm" />        </div>        <div class="form-group form-group-sm ">            <?php echo $entry_address_2; ?>            <input type="text" name="address_2"  id="input-payment-address-2" value="<?php echo (!empty($new_address['address_2'])?$new_address['address_2']:''); ?>" class="form-control input-sm" />        </div>        <div class="form-group form-group-sm ">            <span class="required">*</span> <?php echo $entry_city; ?>            <input type="text" name="city"  id="input-payment-city" value="<?php (!empty($new_address['city'])?$new_address['city']:''); ?>" class="form-control input-sm" />        </div>        <div class="form-group form-group-sm ">            <span id="payment-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?>            <input type="text" name="postcode"  id="input-payment-postcode" value="<?php echo (!empty($new_address['postcode'])?$new_address['postcode']:''); ?>" class="form-control input-sm" />        </div>        <div class="form-group form-group-sm ">            <span class="required">*</span> <?php echo $entry_country; ?>            <select name="country_id"  id="input-payment-country" class="form-control input-sm">                <option value=""><?php echo $text_select; ?></option>                <?php foreach ($countries as $country) { ?>                        <option value="<?php echo $country['country_id']; ?>" <?php echo (($country['country_id'] == $country_id)?'selected="selected"':'') ?> ><?php echo $country['name']; ?></option>                <?php } ?>            </select>        </div>        <div class="form-group form-group-sm ">            <span class="required">*</span> <?php echo $entry_zone; ?>            <select name="zone_id"  id="input-payment-zone" class="form-control input-sm">                <?php if ($zones) { ?>                    <option value=""><?php echo $text_select; ?></option>                    <?php foreach ($zones as $zn) { ?>                            <option value="<?php echo $zn['zone_id']; ?>" <?php if (($zn['zone_id'] == $zone_id)?'selected="selected"':''); ?>><?php echo $zn['name']; ?></option>                    <?php } ?>                <?php } else { ?>                    <option value="0" selected="selected"><?php echo $text_none; ?></option>                <?php } ?>            </select>        </div>        <?php foreach ($custom_fields as $custom_field) { ?>        <?php if ($custom_field['location'] == 'address') { ?>        <?php if ($custom_field['type'] == 'select') { ?>        <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> form-group-sm custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">          <label class=" control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>          <div >            <select name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control input-sm">              <option value=""><?php echo $text_select; ?></option>              <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>              <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>              <?php } ?>            </select>          </div>        </div>        <?php } ?>        <?php if ($custom_field['type'] == 'radio') { ?>        <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> form-group-sm custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">          <label class=" control-label"><?php echo $custom_field['name']; ?></label>          <div >            <div id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>">              <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>              <div class="radio">                <label>                  <input type="radio" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />                  <?php echo $custom_field_value['name']; ?></label>              </div>              <?php } ?>            </div>          </div>        </div>        <?php } ?>        <?php if ($custom_field['type'] == 'checkbox') { ?>        <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> form-group-sm custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">          <label class=" control-label"><?php echo $custom_field['name']; ?></label>          <div >            <div id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>">              <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>              <div class="checkbox">                <label>                  <input type="checkbox" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />                  <?php echo $custom_field_value['name']; ?></label>              </div>              <?php } ?>            </div>          </div>        </div>        <?php } ?>        <?php if ($custom_field['type'] == 'text') { ?>        <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> form-group-sm custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">          <label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>          <div >           <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control input-sm" />          </div>        </div>        <?php } ?>        <?php if ($custom_field['type'] == 'textarea') { ?>        <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> form-group-sm custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">          <label class=" control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>          <div >            <textarea name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo $custom_field['value']; ?></textarea>          </div>        </div>        <?php } ?>        <?php if ($custom_field['type'] == 'file') { ?>        <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> form-group-sm custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">          <label class=" control-label"><?php echo $custom_field['name']; ?></label>          <div >            <button type="button" id="button-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-sm btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>            <input type="hidden" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" />          </div>        </div>        <?php } ?>        <?php if ($custom_field['type'] == 'date') { ?>
        <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> form-group-sm custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
          <label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?>fadf</label>
          <div >
            <div class="input-group date">
             <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
              <span class="input-group-btn">
              <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
              </span></div>
          </div>
        </div>
        <?php } ?>
        <?php if ($custom_field['type'] == 'time') { ?>
        <div			class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> form-group-sm custom-field"			data-sort="<?php echo $custom_field['sort_order']; ?>">			<label class=" control-label"				for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>			<div>				<div class="input-group time">					<input type="text"						name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]"						value="<?php echo $custom_field['value']; ?>"						placeholder="<?php echo $custom_field['name']; ?>"						data-date-format="HH:mm"						id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"						class="form-control" /> <span class="input-group-btn">						<button type="button" class="btn btn-default">							<i class="fa fa-calendar"></i>						</button>					</span>				</div>			</div>		</div>
        <?php } ?>
        <?php if ($custom_field['type'] == 'datetime') { ?>
        <div			class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> form-group-sm custom-field"			data-sort="<?php echo $custom_field['sort_order']; ?>">			<label class=" control-label"				for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>			<div>				<div class="input-group datetime">					<input type="text"						name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]"						value="<?php echo $custom_field['value']; ?>"						placeholder="<?php echo $custom_field['name']; ?>"						data-date-format="YYYY-MM-DD HH:mm"						id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"						class="form-control" /> <span class="input-group-btn">						<button type="button" class="btn btn-default">							<i class="fa fa-calendar"></i>						</button>					</span>				</div>			</div>		</div>
        <?php } ?>
        <?php } ?>
        <?php } ?>
    </form>

    <br />

</div>
    <?php if ($shipping_required) { ?>            <div class="form-group form-group-sm">                <?php // echo $shipping_address_same;   ?>                <div class="checkbox">                    <label>                        <?php if ($shipping_address_same) { ?>                            <input type="checkbox" name="shipping_address_same" value="1" checked="checked" />                        <?php } else { ?>                            <input type="checkbox" name="shipping_address_same" value="1" />                        <?php } ?>                        <strong><?php echo $entry_shipping; ?></strong>                    </label>                </div>            </div>        <?php } ?><div class="row">    <div class="panel-footer">        <div class="text-right">            <input type="button" value="<?php echo $button_ok; ?>" id="button-payment-address" class="btn btn-sm btn-<?php                   if ($css['checkout_theme'] == 'standar') {                       echo 'warning';                   } else {                       echo $css['checkout_theme'];                   }                   ?>"  style="<?php                   if (!empty($css['common_btn_color'])) {                       echo "background-color:{$css['common_btn_color']}!important; background-image:none;";                   }                   if ($use_exist) {                       echo 'display:none;';                   }                   ?>"/>        </div>    </div>
</div>

<script type="text/javascript"><!--
    $(document).ready(function() {

        //$shipping_address_same indicate that the shipping detail whether same billing detail or not.
        //ok
        if ($('#payment-address input[name=\'shipping_address_same\']:checked').val()) {// is viewable
//            $('#shipping-address-panel').hide();
        } else {
//            $('#shipping-address-panel').slideDown('fast');
        }

    });

//--></script><script type="text/javascript"><!--

$('#payment-address .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#payment-address .form-group').length) {
		$('#payment-address .form-group').eq($(this).attr('data-sort')).before(this);
	} 
	
	if ($(this).attr('data-sort') > $('#payment-address .form-group').length) {
		$('#payment-address .form-group:last').after(this);
	}
		
	if ($(this).attr('data-sort') < -$('#payment-address .form-group').length) {
		$('#payment-address .form-group:first').before(this);
	}
});

$('#payment-address button[id^=\'button-payment-custom-field\']').on('click', function() {
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
					$(node).parent().find('.text-danger').remove();;
					
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

    $('#payment-address select[name=\'country_id\']').bind('change', function() {
        if (this.value == '')
            return;
        $.ajax({
            url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('#billing-wait').removeClass('hidden');
            },
            complete: function() {
                $('#billing-wait').addClass('hidden');
            },
            success: function(json) {
                if (json['postcode_required'] == '1') {
                    $('#payment-postcode-required').show();
                } else {
                    $('#payment-postcode-required').hide();
                }

                html = '<option value=""><?php echo $text_select; ?></option>';

                if (json['zone'] != '') {
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
                            html += ' selected="selected"';
                        }

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }
                } else {
                    html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
                }

                $('#payment-new select[name=\'zone_id\']').html(html);
                var payment_address = $('#payment-address input[name=\'payment_address\']:checked').attr('value');
                if (payment_address == 'new') {
                    //for enable zone_in trigger event
                    $('#payment-new select[name=\'country_id\']').attr('disabled', true);
                    $('#payment-new select[name=\'zone_id\'], #payment-new select[name=\'country_id\']').attr('disabled', false);
                    $('#payment-address select[name=\'zone_id\']').trigger('change');
                }
            },
//            error: function(xhr, ajaxOptions, thrownError) {
//                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
//                $('#checkout-wait-notification').addClass('hidden');
//            }
        });
    });

    //$('#payment-address select[name=\'country_id\']').trigger('change');


    //--></script><script type="text/javascript"><!--
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