<div class="row" id="otp">
	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
		<div class="input-group">
			<input type="text" value="<?php $phone; ?>" name="otp_number" class="form-control" placeholder="Enter Mobile Number" aria-describedby="sizing-addon1">
			<span class="input-group-btn">
				<button class="btn btn-default" id="send-otp" type="button" data-loading-text="Sending..." data-complete-text="Resend">Send</button>
			</span>
		</div>
	</div>
	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
		<div class="input-group" id="opt-box">
			<input type="text" class="form-control" id="verify-otp-txt" name="otp" placeholder="Enter OTP" aria-describedby="sizing-addon1">
			<span class="input-group-btn">
				<button class="btn btn-default" id="verify-otp" type="button">Submit</button>
			</span>
		</div>
	</div>
</div>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>" disabled/>
  </div>
</div>
<br />
<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
	$.ajax({
		type: 'get',
		url: 'index.php?route=payment/cod/confirm',
		cache: false,
		beforeSend: function() {
			$('#button-confirm').button('loading');
		},
		complete: function() {
			$('#button-confirm').button('reset');
		},
		success: function() {
			location = '<?php echo $continue; ?>';
		}
	});
});
$('#send-otp').on('click', function() {
	$.ajax({
		type: 'get',
		url: 'index.php?route=payment/cod/otp&otp_number='+$('input[name=\'otp_number\']').val(),
		cache: false,
		beforeSend: function() {
			$('#send-otp').button('loading');
		},
		complete: function() {
			$('#send-otp').button('complete');
		},
		success: function() {
			$('#send-otp').button('reset');
		}
	});
});
$('#verify-otp').on('click', function() {
	$.ajax({
		type: 'post',
		url: 'index.php?route=payment/cod/otp',
		data: $('input[name=\'otp\']'),
		dataType: 'json',
		cache: false,
		beforeSend: function() {
			$('#send-otp').button('loading');
		},
		complete: function() {
		},
		success: function(json) {
			if (json['success']){
				$('#otp button, #otp input').prop('disabled', true);
				$('#button-confirm').prop('disabled', false);
				$('#opt-box').prepend('<span class="input-group-addon" id="basic-addon1"><i class="fa fa-check"></i></span>')
			}
		}
	});
});
//--></script>
