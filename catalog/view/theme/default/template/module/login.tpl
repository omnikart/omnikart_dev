<?php echo $text_email; ?><br />
<input id="marketinsg-email" type="text" name="email" value="" class="form-control" /><br /><br />
<?php echo $text_password; ?><br />
<input id="marketinsg-password" type="password" name="password" value="" class="form-control" /><br />
<a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a><br /><br />
<a class="btn btn-primary" id="button-login"><span><?php echo $text_login; ?></span></a>


<script type="text/javascript"><!--
$('#button-login').on('click', function() {
	$.ajax({
		url: 'index.php?route=module/login/login',
		type: 'post',
		data: $('#marketinsg-email, #marketinsg-password'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-login').attr('disabled', true);
			$('#button-login').after('<i class="fa fa-spinner"></i>');
		},	
		complete: function() {
			$('#button-login').attr('disabled', false);
			$('.fa-spinner').remove();
		},				
		success: function(json) {
			if (json['error']) {
				alert(json['error']);
			} else {
				location = json['success'];
			}
		}
	});	
});	
//--></script>
<script type="text/javascript"><!--
$('#marketinsg-email').on('keydown', function(e) {
	if (e.keyCode == 13) {
		$('#button-login').click();
	}
});
$('#marketinsg-password').on('keydown', function(e) {
	if (e.keyCode == 13) {
		$('#button-login').click();
	}
});
//--></script> 