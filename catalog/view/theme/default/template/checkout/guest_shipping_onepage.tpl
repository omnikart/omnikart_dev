
<form class="form-horizontal" role="form">

    <div class="form-group form-group-sm required">
        <label class="control-label"> <?php echo $entry_firstname; ?></label>
        <input type="text" name="firstname"  id="input-shipping-firstname" value="<?php echo $firstname; ?>" class="form-control input-sm" />
    </div>

    <div class="form-group form-group-sm required">
        <label class="control-label"> <?php echo $entry_lastname; ?></label>
        <input type="text" name="lastname"  id="input-shipping-lastname" value="<?php echo $lastname; ?>" class="form-control input-sm" />
    </div>

    <div class="form-group form-group-sm ">
        <label class="control-label"> <?php echo $entry_company; ?></label>
        <input type="text" name="company"  id="input-shipping-company" value="<?php echo $company; ?>" class="form-control input-sm" />
    </div>

    <div class="form-group form-group-sm required">
        <label class="control-label"> <?php echo $entry_address_1; ?></label>
        <input type="text" name="address_1"  id="input-shipping-address-1" value="<?php echo $address_1; ?>" class="form-control input-sm" />
    </div>

    <div class="form-group form-group-sm ">
        <label class="control-label"> <?php echo $entry_address_2; ?></label>
        <input type="text" name="address_2"  id="input-shipping-address-2" value="<?php echo $address_2; ?>" class="form-control input-sm" />
    </div>

    <div class="form-group form-group-sm required">
        <label class="control-label"> <?php echo $entry_city; ?></label>
        <input type="text" name="city"  id="input-shipping-city" value="<?php echo $city; ?>" class="form-control input-sm" />
    </div>

    <div class="form-group form-group-sm ">
        <label class="control-label"> <span id="shipping-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></label>
        <input type="text" name="postcode"  id="input-shipping-postcode" value="<?php echo $postcode; ?>" class="form-control input-sm" />
    </div>

    <div class="form-group form-group-sm required">
        <label class="control-label"> <?php echo $entry_country; ?></label>
        <select name="country_id"  id="input-shipping-country" class="form-control input-sm">
            <option value=""><?php echo $text_select; ?></option>
            <?php foreach ($countries as $country) { ?>
                <?php if ($country['country_id'] == $country_id) { ?>
                    <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                <?php } else { ?>
                    <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                <?php } ?>
            <?php } ?>
        </select>
    </div>

    <div class="form-group form-group-sm required">
        <label class="control-label"> <?php echo $entry_zone; ?></label>
        <select name="zone_id"  id="input-shipping-zone" class="form-control input-sm">
            <?php if ($zones) { ?>
                <option value=""><?php echo $text_select; ?></option>
                <?php foreach ($zones as $zn) { ?>
                    <?php if ($zn['zone_id'] == $zone_id) { ?>
                        <option value="<?php echo $zn['zone_id']; ?>" selected="selected"><?php echo $zn['name']; ?></option>
                    <?php } else { ?>
                        <option value="<?php echo $zn['zone_id']; ?>"><?php echo $zn['name']; ?></option>
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
    <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> form-group-sm custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
      <label class=" control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
      <div >
        <select name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control input-sm">
          <option value=""><?php echo $text_select; ?></option>
          <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
          <?php if (isset($address_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $address_custom_field[$custom_field['custom_field_id']]) { ?>
          <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>" selected="selected"><?php echo $custom_field_value['name']; ?></option>
          <?php } else { ?>
          <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
          <?php } ?>
          <?php } ?>
        </select>
      </div>
    </div>
    <?php } ?>
    <?php if ($custom_field['type'] == 'radio') { ?>
    <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> form-group-sm custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
      <label class=" control-label"><?php echo $custom_field['name']; ?></label>
      <div >
        <div id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>">
          <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
          <div class="radio">
            <?php if (isset($address_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $address_custom_field[$custom_field['custom_field_id']]) { ?>
            <label>
              <input type="radio" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
              <?php echo $custom_field_value['name']; ?></label>
            <?php } else { ?>
            <label>
              <input type="radio" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
              <?php echo $custom_field_value['name']; ?></label>
            <?php } ?>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <?php } ?>
    <?php if ($custom_field['type'] == 'checkbox') { ?>
    <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> form-group-sm custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
      <label class=" control-label"><?php echo $custom_field['name']; ?></label>
      <div >
        <div id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>">
          <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
          <div class="checkbox">
            <?php if (isset($address_custom_field[$custom_field['custom_field_id']]) && in_array($custom_field_value['custom_field_value_id'], $address_custom_field[$custom_field['custom_field_id']])) { ?>
            <label>
              <input type="checkbox" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
              <?php echo $custom_field_value['name']; ?></label>
            <?php } else { ?>
            <label>
              <input type="checkbox" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
              <?php echo $custom_field_value['name']; ?></label>
            <?php } ?>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <?php } ?>
    <?php if ($custom_field['type'] == 'text') { ?>
    <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> form-group-sm custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
      <label class=" control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
      <div >
        <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address_custom_field[$custom_field['custom_field_id']]) ? $address_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control input-sm" />
      </div>
    </div>
    <?php } ?>
    <?php if ($custom_field['type'] == 'textarea') { ?>
    <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> form-group-sm custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
      <label class=" control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
      <div >
        <textarea name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo (isset($address_custom_field[$custom_field['custom_field_id']]) ? $address_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?></textarea>
      </div>
    </div>
    <?php } ?>
    <?php if ($custom_field['type'] == 'file') { ?>
    <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> form-group-sm custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
      <label class=" control-label"><?php echo $custom_field['name']; ?></label><br/>
      <div >
        <button type="button" id="button-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-sm btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
        <input type="hidden" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address_custom_field[$custom_field['custom_field_id']]) ? $address_custom_field[$custom_field['custom_field_id']] : ''); ?>" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"/>
      </div>
    </div>
    <?php } ?>
    <?php if ($custom_field['type'] == 'date') { ?>
    <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> form-group-sm custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
      <label class=" control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
      <div >
        <div class="input-group date">
          <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address_custom_field[$custom_field['custom_field_id']]) ? $address_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
          <span class="input-group-btn">
          <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
          </span></div>
      </div>
    </div>
    <?php } ?>
    <?php if ($custom_field['type'] == 'time') { ?>
    <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> form-group-sm custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
      <label class=" control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
      <div >
        <div class="input-group time">
          <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address_custom_field[$custom_field['custom_field_id']]) ? $address_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="HH:mm" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
          <span class="input-group-btn">
          <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
          </span></div>
      </div>
    </div>
    <?php } ?>
    <?php if ($custom_field['type'] == 'datetime') { ?>
    <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> form-group-sm custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
      <label class=" control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
      <div >
        <div class="input-group datetime">
          <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address_custom_field[$custom_field['custom_field_id']]) ? $address_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
          <span class="input-group-btn">
          <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
          </span></div>
      </div>
    </div>
    <?php } ?>
    <?php } ?>
    <?php } ?>

</form>



<br>
<div class="row">
    <div class="panel-footer">
        <div class="text-right">
            <input type="button" value="<?php echo $button_ok; ?>" id="button-guest-shipping" class="btn btn-sm btn-<?php
                   if ($css['checkout_theme'] == 'standar') {
                       echo 'warning';
                   } else {
                       echo $css['checkout_theme'];
                   }
                   ?>"  style="<?php
                   if (!empty($css['common_btn_color'])) {
                       echo "background-color:{$css['common_btn_color']}!important; background-image:none;";
                   }
                   ?>"/>
        </div>
    </div>
</div>


<script type="text/javascript"><!--
    
    $('#shipping-address .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#shipping-address .form-group').length) {
		$('#shipping-address .form-group').eq($(this).attr('data-sort')).before(this);
	} 
	
	if ($(this).attr('data-sort') > $('#shipping-address .form-group').length) {
		$('#shipping-address .form-group:last').after(this);
	}
		
	if ($(this).attr('data-sort') < -$('#shipping-address .form-group').length) {
		$('#shipping-address .form-group:first').before(this);
	}
});

$('#shipping-address button[id^=\'button-shipping-custom-field\']').on('click', function() {
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

    $('#shipping-address select[name=\'country_id\']').bind('change', function() {

        if (this.value == '')
            return;
        $.ajax({
            url: 'index.php?route=checkout/checkout_onepage/country&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('#shipping-wait').removeClass('hidden');
            },
            complete: function() {
            },
            success: function(json) {

                if (json['postcode_required'] == '1') {
                    $('#shipping-postcode-required').show();
                } else {
                    $('#shipping-postcode-required').hide();
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

                $('#shipping-address select[name=\'zone_id\']').html(html);

                $('#shipping-address select[name=\'zone_id\']').attr('disabled', false);//ensure trigger being fired
                $('#shipping-address select[name=\'zone_id\']').trigger('change');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                $('#checkout-wait-notification').addClass('hidden');
            }
        });
    });

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