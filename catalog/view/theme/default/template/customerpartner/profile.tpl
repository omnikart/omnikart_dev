<?php echo $header; ?><div id="columns">
	<style>
.banner {
	position: relative;
}

.banner .banner-img {
	max-height: 250px;
	width: 100%
}

.img-div {
	background: none repeat scroll 0 0 #ffffff;
	border: 2px solid #dddddd;
	box-shadow: 0 0 5px 1px #ffffff;
	float: left;
	margin-left: 2%;
	margin-right: 1%;
	margin-top: -12%;
	position: relative;
	width: 15%;
	z-index: 1;
	height: 170px;
}

.img-banner-div {
	margin: auto;
	width: 10%;
}

.row #content .text-white {
	color: <?php
	
echo $partner ['backgroundcolor'] ? $partner ['backgroundcolor'] : '#ffffff';
	?>;
}

.seller_name {
	position: absolute;
	bottom: 0px;
	left: 19%;
}

.mp-tabs {
	margin-top: 8px;
}

.clear {
	clear: both;
}

.banner-data {
	/*position: absolute;*/
	text-align: center;
	top: 5%;
	width: 100%;
	min-height: 135px;
	background: url("<?php echo $partner['companybanner']; ?>");
	background-repeat: no-repeat;
	background-position: center;
	background-size: cover;
}

@media only screen and (max-width: 767px) {
	h1.seller_name {
		font-size: 18px;
	}
	.mp-tabs {
		margin-top: 14px;
	}
	.mp-tabs .nav-tabs li {
		float: none;
	}
	.nav-tabs>li.active, .nav-tabs>li.active>a, .nav-tabs>li.active>a:hover,
		.nav-tabs>li.active>a:focus {
		border: none;
		border-radius: 0px;
		background-color: rgba(31, 141, 183, 0.1);
	}
	.nav-tabs>li.active {
		background-color: rgb(31, 141, 183);
	}
	.img-div {
		float: none;
	}
}

.seller-social-media a .fa {
	font-size: 18px;
}

.addbgtome {
	background-color: <?php echo$partner["backgroundcolor"]; ?>;
	background-image: linear-gradient(to top, #fff, <?php echo $partner ["backgroundcolor"];?>);
	background-image: -webkit-gradient(linear, left bottom, left top, from(#FFFFFF),to(<?php echo $partner [ "backgroundcolor" ]; ?>));
	background-image: -webkit-linear-gradient(top, <?php echo $partner ["backgroundcolor"];
	?>,# FFFFFF );
	background-image: -moz-linear-gradient(top, <?php echo $partner ["backgroundcolor"];
	?>,# FFFFFF );
	background-image: -o-linear-gradient(top, <?php echo $partner ["backgroundcolor"];
	?>,# FFFFFF );
	background-image: linear-gradient(to bottom, <?php echo $partner ["backgroundcolor"];
	?>,# FFFFFF );
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=<?php
	echo $partner ["backgroundcolor"];
	?>, endColorstr =#FFFFFFFF);
	-ms-filter:
		"progid:DXImageTransform.Microsoft.gradient(startColorstr=<?php echo $partner ["backgroundcolor "];?>, endColorstr=#FFFFFFFF)";
}

.banner-data { <?php if($partner['companylogo']) { ?> background: <?php echo	$partner ["companylogo"];	?>; 
	
<?php } else if ($partner ["backgroundcolor "]) {	?>
	background-color: <?php echo$partner["backgroundcolor"]; ?>;
	background-image: linear-gradient(to top, #fff, <?php echo $partner ["backgroundcolor"];
	?>);
	background-image: -webkit-gradient(linear, left bottom, left top, from(#FFFFFF),
		to(<?php echo $partner [ "backgroundcolor" ]; ?>));
	background-image: -webkit-linear-gradient(top, <?php echo $partner ["backgroundcolor"];
	?>,# FFFFFF );
	background-image: -moz-linear-gradient(top, <?php echo $partner ["backgroundcolor"];
	?>,# FFFFFF );
	background-image: -o-linear-gradient(top, <?php echo $partner ["backgroundcolor"];
	?>,# FFFFFF );
	background-image: linear-gradient(to bottom, <?php echo $partner ["backgroundcolor"];
	?>,# FFFFFF );
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=<?php
	echo $partner ["backgroundcolor"];
	?>, endColorstr =#FFFFFFFF);
	-ms-filter:
		"progid:DXImageTransform.Microsoft.gradient(startColorstr=<?php echo $partner ["
		backgroundcolor "];
	?>, endColorstr=#FFFFFFFF)";	<?php } else {	?>
	background: #FFF;
	<?php } ?>
}
</style>
<?php $class = 'col-sm-12'; ?>
<div class="container">

		<div class="row">
			<div id="content" class="<?php echo $class; ?>">
				<div class="banner">
        <?php $partner['companylogo'] ? $banner_class = 'text-white' : $banner_class = 'text-info'; ?>
        <div
						class="text-center banner-data <?php echo $banner_class; ?>">
          <?php if($partner['companylogo']){ ?>
            <div class="img-banner-div">
							<img src="<?php echo $partner['companylogo']; ?>"
								class="img-responsive img-circle" />
						</div>
          <?php }else{ ?>
            <br />
          <?php } ?>

          <?php if($partner['country']){ ?>
            <h4 class="<?php echo $banner_class; ?>">
              <?php echo $text_from; ?><span data-toggle="tooltip"
								title="<?php echo $text_from; ?>"><i class="fa fa-home"></i></span>
							<b><?php echo $partner['country']; ?></b>
						</h4>
          <?php }else{ ?>
            <br />
          <?php } ?>

          <div class="rating">
            <?php for ($i = 1; $i <= 5; $i++) { ?>
              <?php if ($feedback_total['total'] < $i) { ?>
              <span class="fa fa-stack"><i
								class="fa fa-star-o fa-stack-2x <?php echo $banner_class; ?>"></i></span>
              <?php } else { ?>
              <span class="fa fa-stack"><i
								class="fa fa-star fa-stack-2x"></i><i
								class="fa fa-star-o fa-stack-2x"></i></span>
              <?php } ?>
            <?php } ?>
          </div>

						<p>
            <?php echo $text_total_products; ?>                 
            <b><?php echo $seller_total_products; ?></b>
						</p>

						<div class="seller-social-media">
            <?php if($partner['facebookid']){ ?>
              <a
								href="http://facebook.com/<?php echo $partner['facebookid'];?>"
								target="_blank"> <i class="fa fa-facebook-square"></i></a>
            <?php } ?>
            <?php if($partner['twitterid']){ ?>
              <a
								href="http://twitter.com/<?php echo $partner['twitterid'];?>"
								target="_blank"> <i class="fa fa-twitter-square"></i></a>
            <?php } ?>
            <?php if($logged){ ?>
            <a data-toggle="modal" data-target="#myModal-seller-mail"><i
								class="fa fa-envelope"></i></a>
            <?php } else { ?>
            <a href="<?php echo $login; ?>" data-toggle="tooltip"
								data-original-title="Login for contact"><i
								class="fa fa-envelope"></i></a>
            <?php } ?>
          </div>

					</div>


					<h1 class="seller_name text-white">
          <?php echo $partner['firstname'].' '.$partner['lastname']; ?>
        </h1>
				</div>

				<div class="img-div">
        <?php if($partner['avatar']) { ?>
    		  <img src="<?php echo $partner['avatar']; ?>"
						class="img-responsive" />
        <?php } ?>
    	</div>
				<div class="clear-fix"></div>
				<div class="mp-tabs addbgtome">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-profile" data-toggle="tab"><i
								class="fa fa-user"></i> <?php echo $text_profile; ?></a></li>       
          <?php if(isset($public_seller_profile) && in_array('store',$public_seller_profile)) { ?>
            <li><a href="#tab-store" data-toggle="tab"><?php echo $text_store; ?></a></li>   
          <?php } ?>
          <?php if(isset($public_seller_profile) && in_array('collection',$public_seller_profile)) { ?>
            <li><a href="#tab-collection" data-toggle="tab"><?php echo $text_collection.' ('.($collection_total ? $collection_total : '0').')'; ?></a></li>
          <?php } ?>
          <?php if(isset($public_seller_profile) && in_array('review',$public_seller_profile)) { ?>
            <li><a href="#tab-reviews" data-toggle="tab"><i
								class="fa fa-comment-o"></i> <?php echo $text_reviews.' ('.($feedback_total ? $feedback_total : '0').')'; ?></a></li>
          <?php } ?>
          <?php if(isset($public_seller_profile) && in_array('productReview',$public_seller_profile)) { ?>
            <li><a href="#tab-product-reviews" data-toggle="tab"><i
								class="fa fa-comments-o"></i> <?php echo $text_product_reviews.' ('.($product_feedback_total ? $product_feedback_total : '0').')'; ?></a></li>
          <?php } ?>
          <?php if(isset($public_seller_profile) && in_array('location',$public_seller_profile)) { ?>
            <li><a href="#tab-location" data-toggle="tab"><i
								class="fa fa-globe"></i></span> <?php echo $text_location; ?></a></li>
          <?php } ?>
        </ul>
				</div>

				<div class="clear"></div>

				<div class="tab-content">

					<div id="tab-profile" class="tab-pane active">
          <?php echo html_entity_decode($partner['shortprofile']); ?>
        </div>

					<div id="tab-store" class="tab-pane">
          <?php echo html_entity_decode($partner['companydescription']); ?>
        </div>

					<div id="tab-reviews" class="tab-pane">
						<div id="feedback"></div>
          <?php if ($isLogged) { ?>
            <form class="form-horizontal">
							<h2><?php echo $text_write; ?></h2>
							<div class="form-group required">
								<div class="col-sm-12">
									<label class="control-label" for="input-name"><?php echo $text_nickname; ?></label>
									<input type="text" name="name" value="" id="input-name"
										class="form-control" />
								</div>
							</div>

							<div class="form-group required">
								<div class="col-sm-12">
									<label class="control-label" for="input-review"><?php echo $text_review; ?></label>
									<textarea name="text" rows="5" id="input-review"
										class="form-control"></textarea>
									<div class="help-block"><?php echo $text_note; ?></div>
								</div>
							</div>

							<div class="form-group required">
								<div class="col-sm-12">
									<label class="control-label"><?php echo $text_price; ?></label>
                    &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
                    <input type="radio" name="price_rating" value="1" />
									&nbsp; <input type="radio" name="price_rating" value="2" />
									&nbsp; <input type="radio" name="price_rating" value="3" />
									&nbsp; <input type="radio" name="price_rating" value="4" />
									&nbsp; <input type="radio" name="price_rating" value="5" />
                    &nbsp;<?php echo $entry_good; ?></div>
							</div>

							<div class="form-group required">
								<div class="col-sm-12">
									<label class="control-label"><?php echo $text_value; ?></label>
                    &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
                    <input type="radio" name="value_rating" value="1" />
									&nbsp; <input type="radio" name="value_rating" value="2" />
									&nbsp; <input type="radio" name="value_rating" value="3" />
									&nbsp; <input type="radio" name="value_rating" value="4" />
									&nbsp; <input type="radio" name="value_rating" value="5" />
                    &nbsp;<?php echo $entry_good; ?></div>
							</div>

							<div class="form-group required">
								<div class="col-sm-12">
									<label class="control-label"><?php echo $text_quality; ?></label>
                    &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
                    <input type="radio" name="quality_rating" value="1" />
									&nbsp; <input type="radio" name="quality_rating" value="2" />
									&nbsp; <input type="radio" name="quality_rating" value="3" />
									&nbsp; <input type="radio" name="quality_rating" value="4" />
									&nbsp; <input type="radio" name="quality_rating" value="5" />
                    &nbsp;<?php echo $entry_good; ?></div>
							</div>

							<div class="form-group required">
								<div class="col-sm-12">
									<label class="control-label" for="input-captcha"><?php echo $entry_captcha; ?></label>
									<input type="text" name="captcha" value="" id="input-captcha"
										class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<img src="index.php?route=tool/captcha" alt="" id="captcha" />
								</div>
							</div>
							<div class="buttons">
								<div class="pull-right">
									<button type="button" id="button-feedback"
										data-loading-text="<?php echo $text_loading; ?>"
										class="btn btn-primary"><?php echo $button_continue; ?></button>
								</div>
							</div>
						</form>
          <?php } else { ?>
            <a href="<?php echo $login; ?>" class="btn btn-primary"
							target="_blank"><?php echo $text_login; ?></a>
          <?php } ?>
        </div>

					<div id="tab-product-reviews" class="tab-pane"></div>

					<div id="tab-collection" class="tab-pane"></div>

					<div id="tab-location" class="tab-pane"></div>

				</div>

			</div>
		</div>
	</div>

<?php if($logged){ ?>
<div class="modal fade" id="myModal-seller-mail" tabindex="-1"
		role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $text_close; ?></span>
					</button>
					<h3 class="modal-title"><?php echo $text_ask_seller; ?></h3>
				</div>
				<form id="seller-mail-form">
					<div class="modal-body">
						<div class="form-group required">
							<label class="control-label" for="input-subject"><?php echo $text_subject; ?></label>
							<input type="text" name="message" id="input-subject"
								class="form-control" />
              <?php if(isset($partner)){ ?>
                <input type="hidden" name="seller"
								value="<?php echo $seller_id;?>" />           
              <?php } ?>
          </div>
						<div class="form-group required">
							<label class="control-label" for="input-message"><?php echo $text_ask; ?></label>
							<textarea class="form-control" name="message" rows="3"
								id="input-message"></textarea>
						</div>
						<div class="error text-center text-danger"></div>
					</div>
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $text_close; ?></button>
					<button type="button" class="btn btn-primary" id="send-mail"><?php echo $text_send; ?></button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
<?php } ?>

<script type="text/javascript">

<?php if($showCollection) { ?>
  $('a[href="#tab-collection"]').trigger('click');
<?php } ?>
<?php if($logged){ ?>
$('#send-mail').on('click',function(){
  f = 0;
  $('#myModal-seller-mail input[type=\'text\'],#myModal-seller-mail textarea').each(function () {
    if ($(this).val() == '') {
      $(this).parent().addClass('has-error');
      f++;
    }else{
      $(this).parent().removeClass('has-error');
    }
  });

  if (f > 0) {
    $('#myModal-seller-mail .error').text('<?php echo $text_error_mail; ?>').slideDown('slow').delay(2000).slideUp('slow');
  } else {
    $('#send-mail').addClass('disabled');
    $('#myModal-seller-mail').addClass('mail-procss');
    $('#myModal-seller-mail .modal-body').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $text_success_mail; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>');  

    $.ajax({
      url: '<?php echo $send_mail; ?>',
      data: $('#seller-mail-form').serialize()+'<?php echo $mail_for; ?>',
      type: 'post',
      dataType: 'json',
      complete: function () {
        $('#send-mail').removeClass('disabled');
        $('#myModal-seller-mail input,#myModal-seller-mail textarea').each(function () {
          $(this).val(''); 
          $(this).text(''); 
        });
      }
    });
  }
});
<?php } ?>
</script>

	<script type="text/javascript">
localocation = false;
$('a[href=\'#tab-location\']').on('click',function(){
  if(!localocation){
    $(this).append(' <i class="fa fa-spinner fa-spin remove-me"></i>');
    $('#tab-location').load('<?php echo $loadLocation; ?>',function(){
      $('.remove-me').remove();
    });
    localocation = true;
  }
})

$('#feedback').load('<?php echo $feedback; ?>');

$('#tab-product-reviews').load('<?php echo $product_feedback; ?>');

$('#tab-collection').load('<?php echo $collection; ?>');

$('#button-feedback').on('click', function() {
  $.ajax({
    url: '<?php echo $writeFeedback; ?>',
    type: 'post',
    dataType: 'json',
    data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&value_rating=' + encodeURIComponent($('input[name=\'value_rating\']:checked').val() ? $('input[name=\'value_rating\']:checked').val() : '') + '&quality_rating=' + encodeURIComponent($('input[name=\'quality_rating\']:checked').val() ? $('input[name=\'quality_rating\']:checked').val() : '') + '&price_rating=' + encodeURIComponent($('input[name=\'price_rating\']:checked').val() ? $('input[name=\'price_rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
    beforeSend: function() {
      $('#button-feedback').button('loading');
    },
    complete: function() {
      $('#button-feedback').button('reset');
      $('#captcha').attr('src', 'index.php?route=tool/captcha#'+new Date().getTime());
      $('input[name=\'captcha\']').val('');
    },
    success: function(json) {
      $('.alert-success, .alert-danger').remove();
      
      if (json['error']) {
        $('#feedback').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
      }
      
      if (json['success']) {
        $('#feedback').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
        
        $('input[name=\'name\']').val('');
        $('textarea[name=\'text\']').val('');
        $('input[name=\'price_rating\']:checked').prop('checked', false);
        $('input[name=\'quality_rating\']:checked').prop('checked', false);
        $('input[name=\'value_rating\']:checked').prop('checked', false);

        $('input[name=\'captcha\']').val('');
      }
    }
  });
});

</script>
</div><?php echo $footer; ?>
