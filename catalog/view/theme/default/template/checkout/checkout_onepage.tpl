<?php echo $header; ?>

<?php
/*Mijoshop detector */
 $url_inlude = defined('JPATH_MIJOSHOP_OC') ? 'components/com_mijoshop/opencart/' : '';

 ?>

<link href="<?php echo $url_inlude; ?>catalog/view/javascript/mmosolution/bootstrap/css/bootstrap_custom.css" rel="stylesheet" media="screen" /> 
<script type="text/javascript" src="<?php echo $url_inlude; ?>catalog/view/javascript/mmosolution/jquery.qtip.custom/jquery.qtip.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $url_inlude; ?>catalog/view/javascript/mmosolution/jquery.qtip.custom/jquery.qtip.min.css" />

<style>
    #mmosolution select option {
        word-wrap: break-word;
        padding: 5px 10px;
    }
    <?php if($config_template == 'journal2'){ ?>
    #mmosolution fieldset label{
        width: 100%;
    }
    #mmosolution .form-horizontal .control-label {
        width: 100%;
        text-align: left; 
    }
    #mmosolution .text-danger {
        width: 100%;
        color: #ffffff;
    }
    #mmosolution .alert {
        padding: 0;
    }
    <?php } ?>
    #mmosolution button.btn-decrease {
        border-radius: 0px 0px 0px 3px;
    }
     #mmosolution button.btn-increase {
        border-radius: 0px 0px 3px 0px;
    }
    #mmosolution .form-group-sm .form-control {
        height: auto;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
    }
    #billing-shipping-details .custom-field .input-sm {
        height: 25px;
        padding: 0;
    }  
    .step-number{
        width: 40px;
        height: 40px;
        display: block;
        background-color: orange;
        border-radius: 40px;
        border: 4px solid #fff;
        padding: 8px;
        font-size: x-large;
        font-weight: bolder;
        font-family: cursive;
        color: #fff;
    }
    .wait-processing-onepage{
        position: absolute;
        top:-30px;
        right: 0px;
        z-index: 999;
        width: 190px;
        height: 24px; 
        border-radius: 2px;
        background-image: url('<?php echo $url_inlude; ?>catalog/view/theme/default/image/loading_bar_icon/<?php
        if (!empty($css['loading_bar_icon'])) {
            echo $css['loading_bar_icon'] . '.gif';
        } else {
            echo 'loading_1.gif';
        }
        ?>');
        /*background-image: url('<?php echo $url_inlude; ?>catalog/view/theme/default/image/loading_bar_icon/loading_1.gif');*/
        background-size: contain;
        background-size: round auto;
        background-repeat: no-repeat;
        -webkit-background-size: 100%;
        -moz-background-size: 100%;
        -o-background-size: 100%;
        background-size: 100% 100%;
        background-position: center center;
    }
    .wait-processing-onepage-fixed{
        position: fixed;
        top:5px;
    }
    .panel{
        position: relative;
    }
    .panel .panel-heading{
        position: relative;
    }
    .panel .panel-footer{
        margin-bottom: -15px;
    }
    .wait1{
        /*position: relative;*/
    }
    .wait1:before{
        /*background-image: url('<?php echo $url_inlude; ?>catalog/view/theme/default/image/loading_checkout_onepage.gif');*/
        background-image: url('<?php echo $url_inlude; ?>catalog/view/theme/default/image/loading_4.gif');
        background-repeat: no-repeat;
        position: absolute;
        /*right: 0px;*/
        top:-15px;
        z-index: 888;
        width: 30px;
        height: 15px;
        border-radius: 1px;
        content: '';
    }
    .border-none{
        border:none;
    }
    .border-top-none{
        border-top: none!important;
    }
    .border-right-none{
        border-bottom: none!important;
    }
    .border-bottom-none{
        border-right: none!important;
    }
    .border-bottom-none{
        border-bottom: none!important;
    }
    .border-left-none{
        border-left: none!important;
    }
    .margin-none{
        margin: 0!important;
    }
    .margin-top-none{
        margin-top: 0!important;
    }
    .margin-right-none{
        margin-right: 0!important;
    }
    .margin-bottom-none{
        margin-bottom: 0!important;
    }
    .margin-left-none{
        margin-left: 0!important;
    }




    @-webkit-keyframes spin {/*for Chrome 4.0, Safari 4.0, O 15.0*/
        from {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
        /*transform: scale(1) rotate(0deg);*/
    }
    to { 
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
        /*transform: scale(1) rotate(360deg);*/
    }
    }
    @-moz-keyframes spin {/*for FF 5.0*/
        from {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
        /*transform: scale(1) rotate(0deg);*/
    }
    to { 
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
        /*transform: scale(1) rotate(360deg);*/
    }
    }
    @-o-keyframes spin {/*for O 12.0*/
        from {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
        /*transform: scale(1) rotate(0deg);*/
    }
    to { 
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
        /*transform: scale(1) rotate(360deg);*/
    }
    }
    @keyframes spin {/*for IE 10, FF 16, O 12.1*/
        from {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
        /*transform: scale(1) rotate(0deg);*/
    }
    to { 
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
        /*transform: scale(1) rotate(360deg);*/
    }
    }

    #mmosolution .glyphicon-spin-animate{
        position: absolute;
        right: 0px;
        font-size: 1.5em;

        -webkit-animation: spin .5s infinite linear;
        -moz-animation: spin .5s infinite linear;
        -o-animation: spin .5s infinite linear;
        animation: spin .5s infinite linear;
    }

    #checkout-overlay{
        /*        position: absolute;
                z-index: 100;
                width: 100%;
                height: 100%;
                background: #000;
                opacity: 0.1;*/
    }
    .checkout-overlay{
        position: absolute;
        z-index: 100;
        width: 100%;
        height: 100%;
        background: #000;
        opacity: 0.02;
        cursor: not-allowed;
    }
    .checkout-overlay.checkout-overlay-transparent{
        opacity: 0;
    }
    .checkout-overlay.checkout-overlay-light{
        opacity: 0.02;
    }
    .checkout-overlay.checkout-overlay-dark{
        opacity: 0.07;
    }
    .checkout-overlay.checkout-overlay-solid{
        opacity: 1;
    }

    .customer-groups{
        padding-left: 1em;
    }
    #checkout-panel{
        border-width: 3px;
    }
    #checkout-panel .radio{
        padding-top: 0;
        min-height: inherit;
        margin: 0;
    }
    #checkout-panel .checkbox{
        padding-top: 0;
        min-height: inherit;
        margin: 0;
    }
    #checkout-panel input[type=radio]{
        margin-top: 0;
    }
    #checkout-panel input[type=checkbox]{
        margin-top: 0;
    }
    legend{
        margin-bottom: 5px;
    }
    select{
        background-color: #ffffff;
    }



    #billing-shipping-details .form-group.form-group-sm{
        margin-bottom: 5px; 
        font-size: 12px;
        margin-right: 0; 
        margin-left: 0; 
    }
    #billing-shipping-details .control-label{
        padding-top: 0;
    }
    #billing-shipping-details .form-group-sm .input-sm{
        height: 25px;
        padding: 0;
    }
    #billing-shipping-details .row .panel-footer label{
        display: inline;
    }
    #confirm table{
        background-color: #ffffff;
    }
    /*
    #tbl_pament_methods label,#register-form-detail label, #guest-form-detail label{
        white-space: nowrap;
    } */

</style>
<!--[if gte IE 8]>
<style type="text/css">
     
</style>
<![endif]-->
<script type="text/javascript">
    $(window).bind('scroll', function() {
        if ($(window).scrollTop() > $('#checkout-panel').offset().top - $('#checkout-wait-notification').outerHeight()) {
            $('#checkout-wait-notification').addClass('wait-processing-onepage-fixed');
            $('#checkout-wait-notification').css('left', $('#checkout-panel').offset().left + $('#checkout-panel').outerWidth() - $('#checkout-wait-notification').outerWidth() - 5 + 'px');
            //$('#checkout-panel').offset().left+$('#checkout-panel').outerWidth();
        }
        else {
            $('#checkout-wait-notification').removeClass('wait-processing-onepage-fixed');
            $('#checkout-wait-notification').css('left', '');
        }
    });

</script>
<?php if($config_template == 'journal2'){ ?>
<div id="container" class="container j-container">
<?php } else { ?>
<div class="container">
<?php } ?>
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <?php if (!empty($error_warning)) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>

    <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
            <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
            <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
            <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>">
            <?php echo $content_top; ?>
            <?php if (!empty($tips['checkout_tip'][$config_language_id])) { ?>
                
                <div class="alert alert-warning" role="alert">
                    <i class="fa fa-lightbulb-o" style="font-size: 1.5em;"></i>&nbsp;<?php echo html_entity_decode($tips['checkout_tip'][$config_language_id]); ?>
                </div>
            <?php } ?>
<div id="mmosolution">
            <div class="panel panel-<?php
            if ($css['checkout_theme'] == 'standar') {
                echo 'warning';
            } else {
                echo $css['checkout_theme'];
            }
            ?> " id="checkout-panel" style="<?php
                 if (!empty($css['checkout_panel_color'])) {
                     echo "border-color:{$css['checkout_panel_color']}!important;";
                 }
                 ?>">

<!--<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>-->
                <div id="checkout-overlay" class="checkout-overlay hidden"></div>

                <div id='checkout-wait-notification' class="wait-processing-onepage hidden"></div>

                <div class="panel-heading " style="<?php
                if (empty($css['checkout_panel_color']) && $css['checkout_theme'] == 'standar') {
                    echo 'background-color: #FDEBBD; border-color: #FDEBBD;background-image:none;';
                } else if (!empty($css['checkout_panel_color'])) {
                    echo "background-color:{$css['checkout_panel_color']}!important;border-color:{$css['checkout_panel_color']}!important;background-image:none;";
                }
                ?>border-radius: 0;">
                    <div class="row">
                        <div class="col-xs-8">
                            <h1 class="panel-title" style="font-size: 20px;">
                                <i class="fa fa-cogs"   style="font-size: 1.5em;"></i>
                                <strong><?php echo $heading_title; ?></strong>
                            </h1>
                        </div>
                        <div class="col-xs-4 text-right">
                            <?php if (!empty($mmos_checkout['show_refresh_button'])) { ?>
                            <button id='button-reload'  type="button" class="btn btn-default"
                                        style="<?php
                                        if (!empty($css['refresh_button_color'])) {
                                            echo "background-color:{$css['refresh_button_color']}!important;border-color:{$css['refresh_button_color']}!important;background-image:none;";
                                        }
                                        ?>">
                                    <strong><span class="glyphicon glyphicon-refresh"></span></strong>
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                    <!--            <h1 class="panel-title" style="font-size: 20px;">
                                    <span class="glyphicon glyphicon-certificate"></span>&nbsp;<strong><?php echo $heading_title; ?></strong>
                                    <button id='button-reload'  type="button" class="btn btn-default pull-right" title='Refresh'><strong><span class="glyphicon glyphicon-refresh"></span></strong></button>
                                </h1>-->
                </div>

                <div class="panel-body" id="checkout-container">
                    <?php echo $content; ?>
                </div>

            </div>

</div>
            <?php echo $content_bottom; ?></div>
        <?php echo $column_right; ?></div>
</div>


<script type="text/javascript"><!--



    $('.glyphicon-spin-animate').addClass('hidden');

//    console.log($('#shipping-address-options input:checked'));
//    console.log($('#shipping-address-options input[checked=\'checked\']'));
// correct check state of radios, checkboxs
    $('#input[checked=\'checked\']').prop('checked', true);

    function lock_input() {
        $('#checkout-overlay').removeClass('hidden');
        $('#checkout-wait-notification').removeClass('hidden');
    }
    function unlock_input() {
        $('#checkout-overlay').addClass('hidden');
        $('#checkout-wait-notification').addClass('hidden');
    }

    function json_request(ajaxSetting) {
        var deferred = $.Deferred();
        var promise = deferred.promise();

        var jqxhr = $.ajax(ajaxSetting);

        jqxhr.success(function(json, status, xhr) {
            if (!json || json['error']) {
                unlock_input();
                deferred.reject(jqxhr, 'error');
            } else if (json['redirect']) {
                location = json['redirect'];
                deferred.reject(jqxhr, 'error');
            } else {
                deferred.resolve(json, status, xhr);
            }
        });

        jqxhr.error(function(jqXHR, status, error) {
            unlock_input();
            deferred.reject(jqXHR, status, error);
        });

        return promise;
    }



    //COMMON FUNCTIONS

    function load_shipping_method(notview) {
        var jqxhr = $.ajax({
            url: 'index.php?route=checkout/shipping_method_onepage',
            dataType: 'html',
            beforeSend: function() {
                $('#shipping-method-wait').removeClass('hidden');
            },
            complete: function() {

                $('#shipping-method-wait').addClass('hidden');
            },
            success: function(html) {

                if (!notview) {
                    $('#shipping-method .checkout-content-onepage').html(html);
                }
            }
        });
        return jqxhr;
    }
    function load_payment_method(notview) {
        var jqxhr = $.ajax({
            url: 'index.php?route=checkout/payment_method_onepage',
            dataType: 'html',
            beforeSend: function() {
                $('#payment-method-wait').removeClass('hidden');
            },
            complete: function() {
                $('#payment-method-wait').addClass('hidden');
            },
            success: function(html) {

                if (!notview) {
                    $('#payment-method .checkout-content-onepage').html(html);
                }
            }
        });
        return jqxhr;
    }

    function load_order_summary(endProcess) {
        var jqxhr = $.ajax({
            type: 'get',
            url: 'index.php?route=checkout/confirm_onepage/orderSummary',
            dataType: 'html',
            data: $('#order-comment textarea, #confirm input[name=\'agree\']:checked'),
            beforeSend: function() {
                $('#summary-wait').removeClass('hidden');
            },
            complete: function() {

                $('#summary-wait').addClass('hidden');
                if (endProcess) {
                    $('#checkout-wait-notification').addClass('hidden');
                    unlock_input();

                }
            },
            success: function(html) {

                $('#confirm').html(html);
            }
        });
        return jqxhr;
    }

    function process_order() {
        var jqxhr = $.ajax({
            url: 'index.php?route=checkout/confirm_onepage/confirmOrder',
            dataType: 'html',
            success: function(html) {
                //confirm serial steps
                $('#confirm').hide();
                var $html = $(html);
                $('#confirm-checkout').html($html);

                unlock_input();
            }
        });
        return jqxhr;
    }

    function load_right_content() {
        $('#shipping-method-wait').removeClass('hidden');
        $('#payment-method-wait').removeClass('hidden');
        $('#summary-wait').removeClass('hidden');

        var jqxhr = $.ajax({
            url: 'index.php?route=checkout/checkout_onepage_right_content',
            dataType: 'html',
            beforeSend: function() {
                //$('#payment-method-wait').removeClass('hidden');
            },
            complete: function() {
                //$('#payment-method-wait').addClass('hidden');
            },
            success: function(html) {
                $('#methods-summary-panel').html(html);
                unlock_input();
            }
        });

        return jqxhr;
    }

    function load_left_content() {
        //lock some part
        $('#shipping-method-wait').removeClass('hidden');
        $('#payment-method-wait').removeClass('hidden');
        $('#summary-wait').removeClass('hidden');
        $('#billing-wait').removeClass('hidden');
        $('#shipping-wait').removeClass('hidden');

        var jqxhr = $.ajax({
            url: 'index.php?route=checkout/checkout_onepage_left_content',
            dataType: 'html',
            beforeSend: function() {
                //$('#payment-method-wait').removeClass('hidden');
            },
            complete: function() {
                //$('#payment-method-wait').addClass('hidden');
            },
            success: function(html) {
                $('#billing-shipping-details').html(html);
                unlock_input();
            }
        });

    }

    function load_checkout_content() {
        //lock some part
        $('#shipping-method-wait').removeClass('hidden');
        $('#payment-method-wait').removeClass('hidden');
        $('#summary-wait').removeClass('hidden');
        $('#billing-wait').removeClass('hidden');
        $('#shipping-wait').removeClass('hidden');

        var jqxhr = $.ajax({
            url: 'index.php?route=checkout/checkout_onepage_content',
            dataType: 'html',
            beforeSend: function() {
                //$('#payment-method-wait').removeClass('hidden');
            },
            complete: function() {
                //$('#payment-method-wait').addClass('hidden');
            },
            success: function(html) {
                $('#checkout-container').html(html);
                unlock_input();
            }
        });

        return jqxhr;
    }

<?php if (!$logged) { ?>


        function validate_guest_zone() {
            var ajaxSetting = {
                url: 'index.php?route=checkout/guest_onepage/validate_zone',
                type: 'post',
                data: $('#guest-form-detail select[name=\'zone_id\'], #guest-form-detail select[name=\'country_id\'],#guest-form-detail input[name=\'shipping_address_same\']:checked, #guest-form-detail input[name=\'use-address\']:checked'),
                dataType: 'json',
                beforeSend: function() {
                    $('#billing-wait').removeClass('hidden');
                },
                success: function(json) {
                    $('#guest-form-detail select').parent().find('.error, .warning, .success').remove();
                    $('#guest-form-detail .has-error').removeClass('has-error');
                    if (json['redirect']) {
                        location = json['redirect'];
                    } else if (json['error']) {
                        if (json['error']['country']) {
                            $('#guest-form-detail select[name=\'country_id\']').after('<div class="text-danger">' + json['error']['country'] + '</div>').parent().addClass('has-error');
                        }
                        if (json['error']['zone']) {
                            $('#guest-form-detail select[name=\'zone_id\']').after('<div class="text-danger">' + json['error']['zone'] + '</div>').parent().addClass('has-error');
                        }
                    }
                },
                complete: function() {
                    $('#billing-wait').addClass('hidden');
                }
            };

            var promise = json_request(ajaxSetting);
            return promise;
        }
        function validate_guest_detail() {
            var ajaxSetting = {
                url: 'index.php?route=checkout/guest_onepage/validate',
                type: 'post',
                data: $('#guest-form-detail input[type=\'text\'], #guest-form-detail input[type=\'date\'], #guest-form-detail input[type=\'datetime-local\'], #guest-form-detail input[type=\'time\'], #guest-form-detail input[type=\'checkbox\']:checked, #guest-form-detail input[type=\'radio\']:checked, #guest-form-detail input[type=\'hidden\'], #guest-form-detail textarea, #guest-form-detail select'),
                dataType: 'json',
                beforeSend: function() {
                    $('#billing-wait').removeClass('hidden');
                },
                complete: function() {
                    $('#billing-wait').addClass('hidden');
                },
                success: function(json) {
                    $('#guest-form-detail').find('.error, .warning, .success').remove();
                    $('#guest-form-detail .has-error').removeClass('has-error');
                    if (json['redirect']) {
                        location = json['redirect'];
                    } else if (json['error']) {
                        if (json['error']['warning']) {
                            $('#guest-form-detail').prepend('<div class="warning alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                        }

                        for (i in json['error']) {
                            var element = $('#input-payment-' + i.replace('_', '-'));

                            if ($(element).parent().hasClass('input-group')) {
                                $(element).parent().after('<div class="error text-danger">' + json['error'][i] + '</div>');
                            } else {
                                $(element).after('<div class="error text-danger">' + json['error'][i] + '</div>');
                            }
                        }

                        // Highlight any found errors
                        $('#guest-form-detail div.error').parent().addClass('has-error');

                    }
                }
            };
            var promise = json_request(ajaxSetting);
            return promise;
        }

        function validate_register_zone() {
            var ajaxSetting = {
                url: 'index.php?route=checkout/register_onepage/validate_zone',
                type: 'post',
                data: $('#register-form-detail select[name=\'zone_id\'], #register-form-detail select[name=\'country_id\'], #register-form-detail input[name=\'use-address\']:checked'),
                dataType: 'json',
                beforeSend: function() {
                    $('#billing-wait').removeClass('hidden');
                },
                success: function(json) {
                    $('#register-form-detail select').parent().find('.error, .warning, .success').remove();
                    $('#register-form-detail .has-error').removeClass('has-error');
                    if (json['redirect']) {
                        location = json['redirect'];
                    } else if (json['error']) {
                        if (json['error']['country']) {
                            $('#register-form-detail select[name=\'country_id\']')
                                    .after('<div class="error text-danger">' + json['error']['country'] + '</div>')
                                    .parent().addClass('has-error');
                        }
                        if (json['error']['zone']) {
                            $('#register-form-detail select[name=\'zone_id\']')
                                    .after('<div class="error text-danger">' + json['error']['zone'] + '</div>')
                                    .parent().addClass('has-error');
                        }
                    }

                },
                complete: function() {
                    $('#billing-wait').addClass('hidden');
                }
            };

            var promise = json_request(ajaxSetting);
            return promise;
        }
        function validate_register() {
            var ajaxSetting = {
                url: 'index.php?route=checkout/register_onepage/validate',
                type: 'post',
                data: $('#register-form-detail input[type=\'text\'], #register-form-detail input[type=\'password\'], #register-form-detail input[type=\'checkbox\']:checked, #register-form-detail input[type=\'radio\']:checked, #register-form-detail input[type=\'hidden\'], #register-form-detail select, #register-form-detail input[type=\'date\'], #register-form-detail input[type=\'datetime-local\'], #register-form-detail input[type=\'time\'], #register-form-detail textarea'),
                dataType: 'json',
                beforeSend: function() {
                    $('#billing-wait').removeClass('hidden');
                },
                complete: function() {
                    $('#billing-wait').addClass('hidden');
                },
                success: function(json) {
                    $('#register-form-detail').find('.error, .warning, .success').remove();
                    $('#register-form-detail .has-error').removeClass('has-error');
                    if (json['redirect']) {
                        location = json['redirect'];
                    } else if (json['error']) {
                        if (json['error']['warning']) {
                            $('#register-form-detail').prepend('<div class="warning alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                        }

                        for (i in json['error']) {
                            var element = $('#input-register-' + i.replace('_', '-'));

                            if ($(element).parent().hasClass('input-group')) {
                                $(element).parent().after('<div class="error text-danger">' + json['error'][i] + '</div>');
                            } else {
                                $(element).after('<div class="error text-danger">' + json['error'][i] + '</div>');
                            }
                        }

                        // Highlight any found errors
                        $('#register-form-detail div.error').parent().addClass('has-error');

                    }
                }
            };

            var promise = json_request(ajaxSetting);
            return promise;
        }
        
        function validate_register_non_redirect() {
            var ajaxSetting = {
                url: 'index.php?route=checkout/register_onepage/validate',
                type: 'post',
                data: $('#register-form-detail input[type=\'text\'], #register-form-detail input[type=\'password\'], #register-form-detail input[type=\'checkbox\']:checked, #register-form-detail input[type=\'radio\']:checked, #register-form-detail input[type=\'hidden\'], #register-form-detail select, #register-form-detail input[type=\'date\'], #register-form-detail input[type=\'datetime-local\'], #register-form-detail input[type=\'time\'], #register-form-detail textarea'),
                dataType: 'json',
                beforeSend: function() {
                    $('#billing-wait').removeClass('hidden');
                },
                complete: function() {
                    $('#billing-wait').addClass('hidden');
                },
                success: function(json) {
                    $('#register-form-detail').find('.error, .warning, .success').remove();
                    $('#register-form-detail .has-error').removeClass('has-error');
                    if (json['error']) {
                        if (json['error']['warning']) {
                            $('#register-form-detail').prepend('<div class="warning alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                        }

                        for (i in json['error']) {
                            var element = $('#input-register-' + i.replace('_', '-'));

                            if ($(element).parent().hasClass('input-group')) {
                                $(element).parent().after('<div class="error text-danger">' + json['error'][i] + '</div>');
                            } else {
                                $(element).after('<div class="error text-danger">' + json['error'][i] + '</div>');
                            }
                        }

                        // Highlight any found errors
                        $('#register-form-detail div.error').parent().addClass('has-error');

                    }
                }
            };

            var promise = json_request(ajaxSetting);
            return promise;
        }

    <?php if ($shipping_required) { ?>
            function validate_guest_shipping_address() {
                var ajaxSetting = {
                    url: 'index.php?route=checkout/guest_shipping_onepage/validate',
                    type: 'post',
                    data: $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'hidden\'], #shipping-address input[type=\'date\'], #shipping-address input[type=\'datetime-local\'], #shipping-address input[type=\'time\'], #shipping-address input[type=\'password\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address textarea, #shipping-address select'),
                    dataType: 'json',
                    beforeSend: function() {
                        $('#shipping-wait').removeClass('hidden');
                    },
                    complete: function() {
                        $('#shipping-wait').addClass('hidden');
                        unlock_input();
                    },
                    success: function(json) {
                        $('#shipping-address').find('.error, .warning, .success').remove();
                        $('#shipping-address .has-error').removeClass('has-error');
                        if (json['redirect']) {
                            location = json['redirect'];
                        } else if (json['error']) {
                            if (json['error']['warning']) {
                                $('#shipping-address .checkout-content-onepage').prepend('<div class="warning alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                            }

                            for (i in json['error']) {
                                var element = $('#input-shipping-' + i.replace('_', '-'));

                                if ($(element).parent().hasClass('input-group')) {
                                    $(element).parent().after('<div class="error text-danger">' + json['error'][i] + '</div>');
                                } else {
                                    $(element).after('<div class="error text-danger">' + json['error'][i] + '</div>');
                                }
                            }

                            // Highlight any found errors
                            $('#shipping-address .checkout-content-onepage div.error').parent().addClass('has-error');

                        }
                    }
                };

                var promise = json_request(ajaxSetting);
                return promise;
            }
            function load_guest_shipping(endProcess) {
                var shipping_address_same = $('#guest-form-detail input[name=\'shipping_address_same\']:checked').attr('value');
                var jqxhr = $.ajax({
                    url: 'index.php?route=checkout/guest_shipping_onepage&shipping_address_same=' + (shipping_address_same ? '1' : '0'),
                    dataType: 'html',
                    beforeSend: function() {
                        $('#shipping-wait').removeClass('hidden');
                    },
                    complete: function() {

                        $('#shipping-wait').addClass('hidden');
                        if (endProcess) {
                            $('#checkout-wait-notification').addClass('hidden');
                            unlock_input();
                        }
                    },
                    success: function(html) {

                        $('#shipping-address .checkout-content-onepage').html(html);
                    }
                });
                return jqxhr;
            }
            function validate_guest_shipping_zone() {
                var ajaxSetting = {
                    url: 'index.php?route=checkout/guest_shipping_onepage/validate_zone',
                    type: 'post',
                    data: $('#shipping-address select[name=\'zone_id\'], #shipping-address select[name=\'country_id\']'),
                    dataType: 'json',
                    beforeSend: function() {
                        $('#shipping-wait').removeClass('hidden');
                    },
                    success: function(json) {
                        $('#shipping-address select').parent().find('.error, .warning, .success').remove();
                        $('#shipping-address .has-error').removeClass('has-error');
                        if (json['redirect']) {
                            location = json['redirect'];
                        } else if (json['error']) {
                            if (json['error']['country']) {
                                $('#shipping-address select[name=\'country_id\']').after('<div class="text-danger">' + json['error']['country'] + '</div>')
                                        .parent().addClass('has-error');

                            }
                            if (json['error']['zone']) {
                                $('#shipping-address select[name=\'zone_id\']').after('<div class="text-danger">' + json['error']['zone'] + '</div>').parent().addClass('has-error');
                            }
                        }
                    },
                    complete: function() {
                        $('#shipping-wait').addClass('hidden');
                    }
                };
                var promise = json_request(ajaxSetting);
                return promise;
            }
    <?php } ?>


<?php } else { ?>
        function load_payment_address() {
            var jqxhr = $.ajax({
                url: 'index.php?route=checkout/payment_address_onepage',
                dataType: 'html',
                beforeSend: function() {
                    $('#billing-wait').removeClass('hidden');
                },
                complete: function() {
                    $('#billing-wait').addClass('hidden');
                },
                success: function(html) {
                    $('#payment-address .checkout-content-onepage').html(html);
                }
            });
            return jqxhr;
        }
        function validate_payment_zone() {
            var ajaxSetting = {
                url: 'index.php?route=checkout/payment_address_onepage/validate_zone',
                type: 'post',
                data: $('#payment-new select[name=\'zone_id\'], #payment-new select[name=\'country_id\'], #payment-address input[name=\'shipping_address_same\']:checked'),
                dataType: 'json',
                beforeSend: function() {
                    $('#billing-wait').removeClass('hidden');
                },
                success: function(json) {

                    if (json['redirect']) {
                        location = json['redirect'];
                    } else if (json['error']) {
                        unlock_input();
                        $('#payment-new').find('.warning, .error').remove();
                        $('#payment-new .has-error').removeClass('has-error');
                        if (json['error']['country']) {

                            $('#payment-new  select[name=\'country_id\']').after('<div class="text-danger">' + json['error']['country'] + '</div>').parent().addClass('has-error');
                        }
                        if (json['error']['zone']) {

                            $('#payment-new select[name=\'zone_id\']').after('<div class="text-danger">' + json['error']['country'] + '</div>').parent().addClass('has-error');
                        }
                    }
                    else {
                    }

                },
                complete: function() {
                    $('#billing-wait').addClass('hidden');
                }
            };

            var promise = json_request(ajaxSetting);
            return promise;
        }
        function validate_payment_address() {
            //            var key=(new Date()).getTime();
            var ajaxSetting = {
                url: 'index.php?route=checkout/payment_address_onepage/validate',
                type: 'post',
                data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'password\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select, #payment-address input[type=\'date\'], #payment-address input[type=\'datetime-local\'], #payment-address input[type=\'time\'], #new_payment_address_id, #payment-address textarea, #payment-address select'), //, #payment-address input[name=\'shipping_address_same\']:checked
                dataType: 'json',
                beforeSend: function() {
                    $('#billing-wait').removeClass('hidden');
                },
                complete: function() {
                    $('#billing-wait').addClass('hidden');
                },
                success: function(json) {
                    $('#payment-address').find('.warning, .error').remove();
                    $('#payment-address .has-error').removeClass('has-error');
                    if (json['redirect']) {
                        location = json['redirect'];
                    }
                    else if (json['error']) {
                        if (json['error']['warning']) {
                            $('#payment-address .checkout-content-onepage').prepend('<div class="warning alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                        }

                        for (i in json['error']) {
                            var element = $('#input-payment-' + i.replace('_', '-'));

                            if ($(element).parent().hasClass('input-group')) {
                                $(element).parent().after('<div class="error text-danger">' + json['error'][i] + '</div>');
                            } else {
                                $(element).after('<div class="error text-danger">' + json['error'][i] + '</div>');
                            }
                        }

                        // Highlight any found errors
                        $('#payment-address .checkout-content-onepage div.error').parent().addClass('has-error');

                    }
                    else {//success
                        if ($('#payment-address input[name=\'payment_address\']:checked').val() == 'new') {
                            // load_payment_address();////??????????
                        }
                    }
                }
            };

            var promise = json_request(ajaxSetting);
            return promise;
        }

        function sync_payment_shipping_addresses() {
            var $payments = $('#payment-existing select[name=\'address_id\']'),
                    $shippings = $('#shipping-existing select[name=\'address_id\']');
            var $source = null, $target = null;
            if ($payments.children('option').length > $shippings.children('option').length) {
                $source = $payments;
                $target = $shippings;
            } else {
                $source = $shippings;
                $target = $payments;
            }
            var $select = $source.clone(),
                    $target_selected = $target.children('option:selected').clone(),
                    target_id = $target_selected.val();

            $select.children('option:selected').attr('selected', false);
            $select.children('option[value=\'' + target_id + '\']').attr('selected', true);
            if (!$select.children('option:selected').length) {
                $select.prepend($target_selected);
            }

            $target.html($select.html());

            if ($('#payment-existing select[name=\'address_id\']>option').length) {
                $('#payment-address-options').slideDown();
            }
            if ($('#shipping-existing select[name=\'address_id\']>option').length) {
                $('#shipping-address-options').slideDown();
            }
        }
    <?php if ($shipping_required) { ?>
            function load_shipping_address() {
                var jqxhr = $.ajax({
                    url: 'index.php?route=checkout/shipping_address_onepage', // + (shipping_address ? '&shipping_address=1' : ''),
                    dataType: 'html',
                    beforeSend: function() {
                        $('#shipping-wait').removeClass('hidden');
                    },
                    complete: function() {

                        $('#shipping-wait').addClass('hidden');
                        //
                        //$('#shipping-address-panel').show();
                    },
                    success: function(html) {
                        $('#shipping-address .checkout-content-onepage').html(html);
                    }
                });
                return jqxhr;
            }

            function validate_shipping_zone() {
                var ajaxSetting = {
                    url: 'index.php?route=checkout/shipping_address_onepage/validate_zone',
                    type: 'post',
                    data: $('#shipping-address select[name=\'zone_id\'], #shipping-address select[name=\'country_id\'], #payment-address input[name=\'shipping_address_same\']:checked'),
                    dataType: 'json',
                    beforeSend: function() {
                        $('#shipping-wait').removeClass('hidden');
                    },
                    success: function(json) {
                        if (json['redirect']) {
                            location = json['redirect'];
                        } else if (json['error']) {
                            unlock_input();
                            $('#shipping-address').find('.warning, .error').remove();
                            $('#shipping-address .has-error').removeClass('has-error');
                            if (json['error']['country']) {
                                $('#shipping-address select[name=\'country_id\']').after('<div class="text-danger">' + json['error']['country'] + '</div>').parent().addClass('has-error');
                            }
                            if (json['error']['zone']) {
                                $('#shipping-address select[name=\'zone_id\']').after('<div class="text-danger">' + json['error']['zone'] + '</div>').parent().addClass('has-error');
                            }
                        }
                        else {

                        }

                    },
                    complete: function() {
                        $('#shipping-wait').addClass('hidden');
                    }
                };

                var promise = json_request(ajaxSetting);
                return promise;
            }
            function validate_shipping_address() {
                var ajaxSetting = {
                    url: 'index.php?route=checkout/shipping_address_onepage/validate',
                    type: 'post',
                    data: $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'hidden\'], #shipping-address input[type=\'date\'], #shipping-address input[type=\'datetime-local\'], #shipping-address input[type=\'time\'], #shipping-address input[type=\'password\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address select, #new_shipping_address_id, #payment-address input[name=\'shipping_address_same\']:checked, #payment-address textarea'),
                    dataType: 'json',
                    beforeSend: function() {
                        $('#shipping-wait').removeClass('hidden');
                    },
                    complete: function() {
                        $('#shipping-wait').addClass('hidden');
                        unlock_input();
                    },
                    success: function(json) {
                        $('#shipping-address').find('.warning, .error .has-error').remove();
                        if (json['redirect']) {
                            location = json['redirect'];
                        }
                        else if (json['error']) {
                            if (json['error']['warning']) {
                                $('#shipping-address .checkout-content-onepage').prepend('<div class="warning alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                            }

                            for (i in json['error']) {
                                var element = $('#input-shipping-' + i.replace('_', '-'));

                                if ($(element).parent().hasClass('input-group')) {
                                    $(element).parent().after('<div class="error text-danger">' + json['error'][i] + '</div>');
                                } else {
                                    $(element).after('<div class="error text-danger">' + json['error'][i] + '</div>');
                                }
                            }

                            // Highlight any found errors
                            $('#shipping-address .checkout-content-onepage div.error').parent().addClass('has-error');

                        }
                        else {
                        }
                    }
                };

                var promise = json_request(ajaxSetting);
                return promise;
            }
    <?php } ?>

<?php } ?>

<?php if ($shipping_required) { ?>
        function validate_shipping_method() {
            var ajaxSetting = {
                url: 'index.php?route=checkout/shipping_method_onepage/validate',
                type: 'post',
                data: $('#shipping-method input[type=\'radio\']:checked, #shipping-method textarea'),
                dataType: 'json',
                beforeSend: function() {
                    $('#shipping-method-wait').removeClass('hidden');
                },
                complete: function() {
                    $('#shipping-method-wait').addClass('hidden');
                },
                success: function(json) {
                    $('#shipping-method').find('.error, .warning, .success').remove();
                    if (json['redirect']) {
                        location = json['redirect'];
                    } else if (json['error']) {
                        if (json['error']['warning']) {
                            $('#shipping-method .checkout-content-onepage').prepend('<div class="warning alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                        }
                    }
                }
            };

            var promise = json_request(ajaxSetting);
            return promise;
        }
        function apply_shipping_method() {
            var ajaxSetting = {
                url: 'index.php?route=checkout/shipping_method_onepage/quick_validate',
                type: 'post',
                data: $('#shipping-method input[type=\'radio\']:checked'),
                dataType: 'json',
                beforeSend: function() {
                    $('#shipping-method-wait').removeClass('hidden');
                },
                complete: function() {
                    $('#shipping-method-wait').addClass('hidden');
                },
                success: function(json) {

                    $('#shipping-method').find('.warning, .error, .success').remove();
                    if (json['redirect']) {
                        location = json['redirect'];
                    } else if (json['error']) {
                        if (json['error']['warning']) {
                            $('#shipping-method .checkout-content-onepage').prepend('<div class="warning alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                        }
                    }
                }
            };
            var promise = json_request(ajaxSetting);
            return promise;
        }
<?php } ?>

    function validate_payment_method() {
        var ajaxSetting = {
            url: 'index.php?route=checkout/payment_method_onepage/validate',
            type: 'post',
            data: $('#payment-method input[type=\'radio\']:checked, #payment-method input[type=\'checkbox\']:checked, #payment-method textarea'),
            dataType: 'json',
            beforeSend: function() {
                $('#payment-method-wait').removeClass('hidden');
            },
            complete: function() {
                $('#payment-method-wait').addClass('hidden');
            },
            success: function(json) {
                $('#payment-method').find('.error, .warning, .success').remove();
                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    if (json['error']['warning']) {
                        $('#payment-method .checkout-content-onepage').prepend('<div class="warning alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    }
                }
            }
        };

        var promise = json_request(ajaxSetting);
        return promise;
    }
    function apply_payment_method() {
        var ajaxSetting = {
            url: 'index.php?route=checkout/payment_method_onepage/quick_validate',
            type: 'post',
            data: $('input[name=\'payment_method\']:checked'),
            dataType: 'json',
            beforeSend: function() {
                $('#payment-method-wait').removeClass('hidden');
            },
            success: function(json) {
                $('#payment-method').find('.warning, .error, .success').remove();
                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    if (json['error']['warning']) {
                        $('#payment-method .checkout-content-onepage').prepend('<div class="warning alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    }
                }
            },
            complete: function() {
                $('#payment-method-wait').addClass('hidden');
            }
        };
        var promise = json_request(ajaxSetting);
        return promise;
    }



    function validate_confirm() {
        var ajaxSetting = {
            url: 'index.php?route=checkout/confirm_onepage/validate_order',
            type: 'post',
            data: $('#confirm input[type=checkbox]:checked, #order-comment textarea[name=\'comment\']'),
            dataType: 'json',
            beforeSend: function() {
                //$('#shipping-method-wait').removeClass('hidden');
            },
            complete: function() {
                //$('#shipping-method-wait').addClass('hidden');
            },
            success: function(json) {
                $('#confirm').find('.error, .warning, .success').remove();
                if (json['error']) {
                    if (json['error']['warning']) {
                        $('#confirm-footer-panel div.panel-footer').prepend('<div class="warning alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    }
                }
            }

        };

//            var promise = json_request(ajaxSetting, data);//?
        var promise = json_request(ajaxSetting);//?
        return promise;
    }

    $.ajaxSetup({
        error: function(xhr, ajaxOptions, thrownError) {
<?php if (!empty($mmos_checkout['debug'])) { ?>
                if (confirm(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText)) {
                    location = location.href;
                }
<?php } else { ?>
                location = location.href;
<?php } ?>

        }
    });
    //    $('#content').bind("ajaxSend", function(event, jqXHR,ajaxOptions ) {
    $(document).ajaxSend(function(event, jqXHR, ajaxOptions) {//only trigger for user interactions.
        if (event && event.target && event.target.activeElement) {
            if ($.contains($('#content').get(0), event.target.activeElement)) {
                lock_input();
            }
        }
    });
 
	$(document).ajaxStop(function() {
        unlock_input();
      });
	
<?php if (!$logged) { ?>

        $('#checkout-container').on('change', '#checkout-options input[name=\'account\']', function() {
            $('#checkout-options .list-group-item').removeClass('active');
            $(this).closest('.list-group-item').addClass('active');
            if ($(this).attr('value') == 'register') {
                $('#checkout-option-title').html('<?php echo $text_checkout_account; ?>');
                //alert($('#guest-form-detail input[name=\'customer_group_id\']:checked').attr('value'));

            } else if ($(this).attr('value') == 'guest') {
                $('#checkout-option-title').html('<?php echo $text_checkout_payment_address; ?>');
            }
            else if (this.value == 'returning-customer') {
                $('#checkout-option-title').html('<?php echo $text_returning_customer; ?>');
            }

            if (this.value == 'register') {
                $('#login-form').hide();
                $('#checkout-container').show();
                var shipping_address = $('#guest-form-detail input[name=\'shipping_address_same\']:checked').attr('value');

                $('#guest-form-detail').hide();
                $('#shipping-address-panel').hide();
                //                $('#methods-summary-panel').hide();
                $('#register-form-detail').show();

                //overlay
                $('#shipping-method-panel div.checkout-overlay').removeClass('hidden');
                $('#payment-method-panel div.checkout-overlay').removeClass('hidden');
                $('#voucher-coupon-panel div.checkout-overlay').removeClass('hidden');
                $('#confirm-footer-panel div.checkout-overlay').removeClass('hidden');
                $('#confirm-panel div.checkout-overlay').removeClass('hidden');
            } else if (this.value == 'guest') {
                $('#login-form').hide();
                $('#checkout-container').show();
                $('#register-form-detail').hide();
                var shipping_address = $('#guest-form-detail input[name=\'shipping_address_same\']:checked').attr('value');

                if (shipping_address) {
                    $('#shipping-address-panel').hide();
                    $('#guest-form-detail').show();
                } else {
                    $('#guest-form-detail').show();
                    $('#shipping-address-panel').show();
                }
                $('#methods-summary-panel').show();

                //overlay
                $('#shipping-method-panel div.checkout-overlay').removeClass('hidden');
                $('#payment-method-panel div.checkout-overlay').removeClass('hidden');
                $('#voucher-coupon-panel div.checkout-overlay').removeClass('hidden');
                $('#confirm-footer-panel div.checkout-overlay').removeClass('hidden');
                $('#confirm-panel div.checkout-overlay').removeClass('hidden');
            }
            else if (this.value == 'returning-customer') {
                //                $('#checkout-container').hide();
                $('#login-form').show();
                $('#shipping-address-panel').hide();
                $('#register-form-detail').hide();
                $('#guest-form-detail').hide();

                //overlay
                $('#shipping-method-panel div.checkout-overlay').removeClass('hidden');
                $('#payment-method-panel div.checkout-overlay').removeClass('hidden');
                $('#voucher-coupon-panel div.checkout-overlay').removeClass('hidden');
                $('#confirm-panel div.checkout-overlay').addClass('hidden');
                $('#confirm-footer-panel div.checkout-overlay').removeClass('hidden');
            }


            if (this.value == 'register') {
                //do trigger register zone_id
                $('#input-register-zone').trigger('change');
            }
            else if (this.value == 'guest') {
                //do trigger guest payment & shipping zone_id
    <?php if (!$shipping_required) { ?>
                    //$('#payment_zone_id').trigger('change');
                    var promise_validate_guest_zone = validate_guest_zone();
                    promise_validate_guest_zone.done(function() {
                        load_right_content();
                    });
    <?php } else { ?>
                    var shipping_address = $('#guest-form-detail input[name=\'shipping_address_same\']:checked').val();
                    if (shipping_address) {
                        //$('#payment_zone_id').trigger('change');
                        var promise_validate_guest_zone = validate_guest_zone();
                        promise_validate_guest_zone.done(function() {
                            load_right_content();
                        });
                    }
                    else {
                        var promise_validate_guest_zone = validate_guest_zone();
                        var promise_validate_guest_shipping_zone = validate_guest_shipping_zone();
                        $.when(promise_validate_guest_zone, promise_validate_guest_shipping_zone).done(function() {
                            load_right_content();
                        });
                    }
    <?php } ?>
            }
            else if (this.value == 'returning-customer') {
                //ajax
                 $.ajax({
                    url: 'index.php?route=checkout/login_onepage/set_account_view',
                    type: 'post',
                    data: '',
                    beforeSend: function() {
                        $('#login-wait').removeClass('hidden');
                    },
                    complete: function() {
                        $('#login-wait').addClass('hidden');
                        unlock_input();
                    },
                    success: function() {
                       
                    }
                });
            }

        });

        //$('#checkout-options input[name=\'account\']:checked').trigger('change');

        // Login
        // ok
        $('#checkout-container').on('click', '#button-login', function() {
            $.ajax({
                url: 'index.php?route=checkout/login_onepage/validate',
                type: 'post',
                data: $('#login-form :input'),
                dataType: 'json',
                beforeSend: function() {
                    $('#login-wait').removeClass('hidden');
                },
                complete: function() {
                    $('#login-wait').addClass('hidden');
                },
                success: function(json) {
                    $('#login-form').find('.warning, .error, .success').remove();

                    if (json['redirect']) {
                        location = json['redirect'];
                    } else if (json['error']) {

                        unlock_input();
                        $('#login-form .checkout-content-onepage').prepend('<div class="warning alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                        //unlock_input();
                    }
                }
            });
        });

        // $('#payment-address input[name=\'quick-checkout\']').trigger('change');

        // Guest Payment Address
        //ok
        $('#checkout-container').on('click', '#button-register', function() {
            validate_register();
        });

        //ok
        $('#checkout-container').on('click', '#button-guest', function() {
            var promise_guest_validate = validate_guest_detail();
            promise_guest_validate.done(function() {
                load_right_content();
            });
        });

        $('#checkout-container').on('change', '#guest-form-detail select[name=\'zone_id\']', function() {
            var promise_validate_guest_zone = validate_guest_zone();

            promise_validate_guest_zone.done(function() {
                load_right_content();
            });
        });

        $('#checkout-container').on('change', '#register-form-detail select[name=\'zone_id\']', function() {
            var promise_validate_register_zone = validate_register_zone();
            promise_validate_register_zone.done(function() {
                load_right_content().done(function() {
                    $('#confirm-footer-panel .checkout-overlay').removeClass('hidden');
                    $('#register-form-detail input[name=\'customer_group_id\']:checked').trigger('change');
                });
            });
        });


        //ok
        $('#checkout-container').on('change', '#guest-form-detail input[name=\'shipping_address_same\']', function() {
            if ($(this).is(':checked')) {
                $('#shipping-address-panel').slideUp();
                $('#guest-form-detail select[name=\'zone_id\']').trigger('change');
            } else {
                var jqxhr_guest_shipping = load_guest_shipping(true);//true=endProcess
                jqxhr_guest_shipping.done(function() {
                    $('#shipping-address-panel').show();
                });
            }
        });

        // Guest Shipping Address
        //ok
        $('#checkout-container').on('click', '#button-guest-shipping', function() {

            var promise_guest_shipping_validate = validate_guest_shipping_address();
            promise_guest_shipping_validate.done(function() {
                load_right_content();
            });
        });

        //ok
        $('#checkout-container').on('change', '#shipping-address select[name=\'zone_id\']', function() {
            var promise_validate_guest_shipping_zone = validate_guest_shipping_zone();
            promise_validate_guest_shipping_zone.done(function() {
                var jqxhr = load_shipping_method(); //with shipping_zone_id just selected
                jqxhr.done(function() {
                    load_order_summary(true);
                });
            });
        });

        //ok
        $('#checkout-container').on('click', '#btn-make-order', function() {
            var promise_validate_confirm = validate_confirm();
            var promise_validate_guest_detail = validate_guest_detail();
         
            
            if ($('#checkout-options input[name=\'account\']:checked').val() != 'guest') {
                 var promise_validate_register = validate_register_non_redirect();
                $.when(promise_validate_confirm, promise_validate_register).done(function() {
                    $.when(validate_shipping_method(), validate_payment_method()).done(function() {
                        process_order();
                    });
                });
            }
             else if (!$('#confirm input[name=\'agree\']:checked').val() == 'undefined' && !$('#confirm input[name=\'agree\']:checked').val()) {

                <?php if (!empty($text_error_agree)) { ?>
                    $('#confirm-footer-panel').find('.warning, .error, .success').remove();
                    $('#confirm-footer-panel div.panel-footer').prepend('<div class="warning alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + '<?php echo $text_error_agree; ?>' + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                <?php } ?>
                return;
            }

            $('#checkout-overlay').removeClass('hidden');

//            var promise_validate_confirm = validate_confirm();
//           var promise_validate_guest_detail = validate_guest_detail();

            <?php if ($shipping_required) { ?>
                var shipping_address = $('#guest-form-detail input[name=\'shipping_address_same\']:checked').attr('value');
                if (shipping_address) {
                    $.when(promise_validate_confirm, promise_validate_guest_detail).done(function() {
                        $.when(validate_shipping_method(), validate_payment_method())
                                .done(function() {
                            process_order();
                        });
                    });
                } else {
                    $.when(promise_validate_confirm, promise_validate_guest_detail, validate_guest_shipping_address()).done(function() {
                        $.when(validate_shipping_method(), validate_payment_method()).done(function() {
                            process_order();
                        });
                    });
                }
            <?php } else { ?>
                $.when(promise_validate_confirm, promise_validate_guest_detail).done(function() {
                    validate_payment_method().done(function() {
                        process_order();
                    });
                });
            <?php } ?>
        });


<?php } else { ?>
        //Payment Address
        $('#checkout-container').on('click', '#button-payment-address', function(event, args) {
            validate_payment_address().done(function() {//now may new address created

                load_payment_address().done(function() {
                    sync_payment_shipping_addresses();
                    load_right_content();
                });
                //load_checkout_content();
            });
        });
        //ok
        $('#checkout-container').on('change', '#payment-new select[name=\'zone_id\']', function() {
            //$('#payment-new select[name=\'zone_id\']').change( function () {
            var shipping_address_same = $('#payment-address input[name=\'shipping_address_same\']:checked').attr('value');
            if (shipping_address_same) {//SHOW/HIDE before back-process to server, make better feeling to customers.
                $('#shipping-address-panel').slideUp('fast');
            } else {
                $('#shipping-address-panel').show();
            }


            validate_payment_zone().done(function() {
                load_right_content();
            });

        });

        //ok
        $('#checkout-container').on('change', '#payment-existing select[name=\'address_id\']', function(event, args) {
        
            validate_payment_address().done(function() {
                load_right_content();
            });
        });

        //ok
        $('#checkout-container').on('change', '#payment-address input[name=\'payment_address\']', function() {
            if (this.value == 'new') {
                $('#payment-existing').hide();
                $('#button-payment-address').show();
                $('#payment-new').show();

                $('#payment-new select[name=\'zone_id\']').trigger('change');

            } else {//ok
                $('#payment-existing').show();
                $('#button-payment-address').hide();
                $('#payment-new').hide();
                //load shipping address & show
                //# $('#shipping-address-panel').show();//show before back-process to server, make better feeling to customers.
                //uncheck the same address
                //                $('#payment-existing select[name=\'address_id\']').trigger('change',{shipping_address:true});//mark should be processed for shipping address
                if (!$('#payment-existing select[name=\'address_id\']>option:selected').length) {
                    $('#payment-existing select[name=\'address_id\']>option:first').attr('selected', true);
                }
                $('#payment-existing select[name=\'address_id\']').trigger('change');//mark should be processed for shipping address

            }
        });

        // Shipping Address			
        $('#checkout-container').on('click', '#button-shipping-address', function() {
            validate_shipping_address().done(function() {

                load_shipping_address().done(function() {
                    sync_payment_shipping_addresses();
                    load_right_content();
                });
                //load_checkout_content();
            });
        });

        $('#checkout-container').on('change', '#shipping-new select[name=\'zone_id\']', function() { //
            validate_shipping_zone().done(function() {
                load_right_content();
            });
        });

        $('#checkout-container').on('change', '#shipping-existing select[name=\'address_id\']', function() {
            validate_shipping_address().done(function() {
                load_right_content();
            });

        });

        $('#checkout-container').on('change', '#shipping-address input[name=\'shipping_address\']', function() {//new or existing
            if (this.value == 'new') {
                $('#shipping-existing').hide();
                $('#button-shipping-address').show();
                $('#shipping-new').show();

                $('#shipping-address select[name=\'zone_id\']').attr('disabled', false).trigger('change');
            } else {
                $('#shipping-existing').show();
                $('#button-shipping-address').hide();
                $('#shipping-new').hide();

                if (!$('#shipping-existing select[name=\'address_id\']>option:selected').length) {
                    $('#shipping-existing select[name=\'address_id\']>option:first').attr('selected', true);
                }
                $('#shipping-existing select[name=\'address_id\']').attr('disabled', false).trigger('change');//duan
            }
        });

        $('#checkout-container').on('change', '#payment-address input[name=\'shipping_address_same\']', function() {
            if ($(this).is(':checked')) {
                $('#shipping-address-panel').slideUp('fast');//ok
                //validate payment address to accept the same shipping address
                var payment_address = $('#payment-address input[name=\'payment_address\']:checked').val()
                if (payment_address == 'existing') {
                    validate_payment_address().done(function() {
                        load_right_content();
                    });
                } else if (payment_address == 'new') {
                    validate_payment_zone().done(function() {
                        load_right_content();
                    });
                }
            } else {
                $('#shipping-address-panel').slideDown('fast');//ok
                var shipping_address = $('#shipping-address input[name=\'shipping_address\']:checked').attr('value');

                if (shipping_address == 'existing') {
                    //do validate selected existing address
                    validate_shipping_address().done(function() {
                        load_right_content();
                    });
                } else if (shipping_address == 'new') {
                    //validate shipping zone
                    validate_shipping_zone().done(function() {
                        load_right_content();
                    });
                }
            }
        });

        // re-validate all enter infos before confirm.
        $('#checkout-container').on('click', '#btn-make-order', function() {
            $('#checkout-overlay').removeClass('hidden');

            var promise_validate_confirm,
                    promise_validate_payment_address,
                    promise_validate_shipping_address,
                    promise_validate_shipping_method,
                    promise_validate_payment_method;

            var promise_validate_confirm = validate_confirm();
            var promise_validate_payment_address = validate_payment_address();

            //            var promise_validate_shipping_address = validate_shipping_address();
    <?php if ($shipping_required) { ?>
                var shipping_address_same = $('#payment-address input[name=\'shipping_address_same\']:checked').length;
                var payment_address = $('#payment-address input[name=\'payment_address\']:checked').val();

                if (shipping_address_same) {
                    $.when(promise_validate_confirm, promise_validate_payment_address).done(function() {
                        $.when(validate_shipping_method(), validate_payment_method()).done(function() {
                            process_order();
                        })
                                .fail(function(arg) {
                            /*
                             * reload addresses
                             */
                        });
                    })
                            .fail(function(arg) {
                        promise_validate_payment_address.done(function() {
                            load_payment_address().done(function() {
                                sync_payment_shipping_addresses();
                            });
                        });
                    });
                } else {
                    promise_validate_shipping_address = validate_shipping_address();
                    $.when(promise_validate_confirm, promise_validate_payment_address, promise_validate_shipping_address)
                            .done(function() {
                        $.when(validate_shipping_method(), validate_payment_method()).done(function() {
                            process_order();
                        });
                    })
                            .fail(function(arg) {
                        promise_validate_payment_address.done(function() {
                            load_payment_address().done(function() {
                                sync_payment_shipping_addresses();
                            });
                        });
                        promise_validate_shipping_address.done(function() {
                            load_shipping_address().done(function() {
                                sync_payment_shipping_addresses();
                            });
                        });
                    });
                }
    <?php } else { ?>
                $.when(promise_validate_confirm, promise_validate_payment_address)
                        .done(function() {
                    validate_payment_method().done(function() {
                        process_order();
                    });
                })
                        .fail(function(arg) {
                    promise_validate_payment_address.done(function() {
                        load_payment_address();
                    });
                });
    <?php } ?>

        });
<?php } ?>

    //COMMON EVENT HANDLERS.
    $('#checkout-container').on('click', '#button-shipping-method', function() {
        validate_shipping_method().done(function() {
            load_order_summary(true);
        });
    });
    $('#checkout-container').on('click', '#button-payment-method', function() {
        validate_payment_method().done(function() {
            load_order_summary(true);
        });
    });

    /*-----------------------------------------------
     ---------------------------------------*/
    $('#checkout-container').on('change', '#shipping-method input[name=\'shipping_method\']', function() {

        apply_shipping_method().done(function() {
            load_order_summary(true);
        });
    });
    $('#checkout-container').on('change', '#payment-method input[name=\'payment_method\']', function() {
        apply_payment_method().done(function() {
            load_order_summary(true);
        });
    });


    /**************************************************************
     * 
     * REMOVE/UPDATE CART
     * 
     ****************************************************************/

    // Cart add remove functions
    var cart_checkout = {
        'update': function($data) {
            var ajaxSetting =
                    {
                        url: 'index.php?route=checkout/cart_onepage/edit_cart',
                        type: 'post',
                        data: $data,
                        dataType: 'json',
                        beforeSend: function() {
                            $('#cart > button').button('loading');
                            $('#summary-wait').removeClass('hidden');
                        },
                        success: function(json) {
                            if (json['redirect']) {
                                location = json['redirect'];
                            } else if (json['error']) {
                                unlock_input();
                            }
                            else {
                                $('#cart > button').button('reset');
                                if(typeof(json['total'])!='undefined'){
                                    $('#cart-total').html(json['total']);
                                }

                                $('#cart > ul').load('index.php?route=common/cart/info ul li');
                            }
                        },
                        complete: function() {
                            $('#summary-wait').addClass('hidden');
                        }
                    };

            var promise = json_request(ajaxSetting);
            return promise;
        },
        'remove': function(key) {
            var ajaxSetting = {
                url: 'index.php?route=checkout/cart_onepage/remove_cart',
                type: 'post',
                data: 'key=' + key,
                dataType: 'json',
                beforeSend: function() {
                    $('#cart > button').button('loading');
                    $('#summary-wait').removeClass('hidden');
                },
                success: function(json) {

                    if (json['redirect']) {
                        location = json['redirect'];
                    } else if (json['error']) {
                        unlock_input();
                    }
                    else {
                        $('#cart > button').button('reset');
                        if(typeof(json['total'])!='undefined'){
                            $('#cart-total').html(json['total']);
                        }

                        $('#cart > ul').load('index.php?route=common/cart/info ul li');
                    }

                },
                complete: function() {
                    $('#summary-wait').addClass('hidden');
                }
            };
            var promise = json_request(ajaxSetting);
            return promise;
        },
        load_mini_cart: function() {
            var ajaxSetting = {
                url: 'index.php?route=checkout/cart_onepage/minicart_total',
                type: 'post',
                //			data: 'key=' + key,//
                dataType: 'json',
                beforeSend: function() {
                    $('#cart > button').button('loading');
                },
                complete: function() {
                    unlock_input();
                },
                success: function(json) {

                    if (json['redirect']) {
                        location = json['redirect'];
                    } else if (json['error']) {
                        //unlock_input();
                    }
                    else {
                        $('#cart > button').button('reset');

                        $('#cart-total').html(json['total']);

                        $('#cart > ul').load('index.php?route=common/cart/info ul li');
                    }

                }
            };
            var promise = json_request(ajaxSetting);
            return promise;
        }
    };
    var timeout_quantity_id = null;
    function isInt(n) {
        return Number(n) == n && Number(n) % 1 === 0;
    }
    function validate_quantity_change($input) {

        //validate value number
        if (!isInt($input.val()) || $input.val() <= 0) {
            $input.val($input.closest('.quantity').find('input.original-quantity:hidden').val());
            return false;
        }
        //detect no change value
        if ($input.val() == $input.closest('.quantity').find('input.original-quantity:hidden').val()) {
            return false;
        }
        return true;
    }

    function update_quantity() {
        var $inputs = $('#confirm input[name^=\'quantity[\']').filter(function() {
            if ($(this).val() == $(this).siblings('input.original-quantity:hidden').val())
                return false;
            else
                return true;
        });

        var promise = cart_checkout.update($inputs);
        return promise;
    }

    // //remove product
    $('#checkout-container').on('click', '#confirm button.btn-remove', function() {

        clearTimeout(timeout_quantity_id);
        $('#btn-make-order').attr('disabled', true);
        var key = $(this).children('input:hidden').val();
        var promise_remove_product = cart_checkout.remove(key);
        promise_remove_product.done(function() {
           load_right_content();
        });
    });
    //update quantity
    $('#checkout-container').on('click', '#confirm button.btn-quantity', function() {

        var $input = $(this).closest('.quantity').find('input[name^=\'quantity[\']');
        if (!validate_quantity_change($input)) {
            return;
        }
        clearTimeout(timeout_quantity_id);
        update_quantity().done(function() {
            load_right_content();
        });
    });

    $('#checkout-container').on('click', '#confirm button.btn-increase', function() {

        clearTimeout(timeout_quantity_id);
        var $input = $(this).closest('.quantity').find('input[name^=\'quantity[\']');
        $input.val(parseInt($input.val()) + 1);
        $('#btn-make-order').attr('disabled', true);
        timeout_quantity_id = setTimeout(function() {
            timeout_quantity_id = null;

            lock_input();
            update_quantity().done(function() {
                //load_order_summary(true);
                load_right_content();
            });
        }, 700);

    });
    $('#checkout-container').on('click', '#confirm button.btn-decrease', function() {


        var $input = $(this).closest('.quantity').find('input[name^=\'quantity[\']');
        if ($input.val() == 1)
            return;
        clearTimeout(timeout_quantity_id);
        $input.val(parseInt($input.val()) - 1);
        $('#btn-make-order').attr('disabled', true);
        timeout_quantity_id = setTimeout(function() {
            timeout_quantity_id = null;

            lock_input();
            update_quantity().done(function() {
//                load_order_summary(true);
                load_right_content();
            });
        }, 700);
    });

    $('#checkout-container').on('blur', '#confirm input[name^=\'quantity[\']', function() {

        var $input = $(this);
        if (!validate_quantity_change($input)) {
            return;
        }
        clearTimeout(timeout_quantity_id);
        $('#btn-make-order').attr('disabled', true);
        timeout_quantity_id = setTimeout(function() {
            //clearTimeout(timeout_quantity_id);
            timeout_quantity_id = null;
            lock_input();
            update_quantity().done(function() {
                load_order_summary(true);
				load_right_content();
            });
        }, 700);


        //        $(this).siblings('button.btn-quantity').trigger('click');
    });
    $('#checkout-container').on('focus', '#confirm input[name^=\'quantity[\']', function() {
        clearTimeout(timeout_quantity_id);
        timeout_quantity_id = null;

    });

    /*****************************
     * BUTTON RELOAD
     * 
     *********************************/
    $('#button-reload').click(function() {
        load_checkout_content().done(function() {
            cart_checkout.load_mini_cart();
        });
    });
    /*****************************************
     * 
     * ************VOUCHER/COUPON*************
     * 
     ******************************************/

    function applyVoucher() {
        var ajaxSetting = {
            url: 'index.php?route=checkout/cart_onepage/voucher',
            type: 'post',
            data: 'voucher=' + encodeURIComponent($('#voucher-coupon input[name=\'voucher\']').val()),
            dataType: 'json',
            beforeSend: function() {
                $('#apply-voucher').button('loading');
            },
            complete: function() {
                $('#apply-voucher').button('reset');

            },
            success: function(json) {
                $('#voucher-coupon').find('.error, .warning, .success').remove();

                if (json['error']) {
                    if (json['error']['warning']) {
                        $('#voucher-coupon .panel-body').prepend('<div class="warning alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    }
                } else if (json['success']) {
                    $('#voucher-coupon .panel-body').prepend('<div class="success alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }
            }
        };
        var jqXhr = $.ajax(ajaxSetting);
        return jqXhr;
    }

    function applyCoupon() {
        var ajaxSetting = {
            url: 'index.php?route=checkout/cart_onepage/coupon',
            type: 'post',
            data: 'coupon=' + encodeURIComponent($('#voucher-coupon input[name=\'coupon\']').val()),
            dataType: 'json',
            beforeSend: function() {
                $('#apply-coupon').button('loading');
            },
            complete: function() {
                $('#apply-coupon').button('reset');
            },
            success: function(json) {

                $('#voucher-coupon').find('.error, .warning, .success').remove();
                if (json['error']) {
                    if (json['error']['warning']) {
                        $('#voucher-coupon  .panel-body').prepend('<div class="warning alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    }

                } else if (json['success']) {
                    $('#voucher-coupon  .panel-body').prepend('<div class="success alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }
            }
        };

        var jqXhr = $.ajax(ajaxSetting);
        return jqXhr;
    }
    
    function applyReward() {
        var ajaxSetting = {
            url: 'index.php?route=checkout/cart_onepage/reward',
            type: 'post',
            data: 'reward=' + encodeURIComponent($('#reward input[name=\'reward\']').val()),
            dataType: 'json',
            beforeSend: function() {
                $('#apply-reward').button('loading');
            },
            complete: function() {
                $('#apply-reward').button('reset');
            },
            success: function(json) {

                $('#reward').find('.error, .warning, .success').remove();
                if (json['error']) {
                    if (json['error']) {
                        $('#reward  .panel-body').prepend('<div class="warning alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    }

                } else if (json['success']) {
                    $('#reward  .panel-body').prepend('<div class="success alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }
            }
        };

        var jqXhr = $.ajax(ajaxSetting);
        return jqXhr;
    }

    //voucher-coupon
    $('#checkout-container').on('click', '#apply-voucher', function() {
        applyVoucher().done(function() {
            load_order_summary(true);
            cart_checkout.load_mini_cart();
        });

    });
    $('#checkout-container').on('click', '#apply-coupon', function() {
        applyCoupon().done(function() {
            load_order_summary(true);
            cart_checkout.load_mini_cart();
        });
    });
    $('#checkout-container').on('click', '#apply-reward', function() {
        applyReward().done(function() {
            load_order_summary(true);
            cart_checkout.load_mini_cart();
        });
    });
    //--></script> 
<script type="text/javascript"><!--


                             /* Agree to Terms */
                            $(document).on('click','.mmos-agree', function(e) {
                                    e.preventDefault();

                                    $('#modal-agree').remove();

                                    var element = this;
                                    var link_get = $(element).attr('dala-link');
                                    lock_input();    
                                    $.ajax({
                                            url: link_get,
                                            type: 'get',
                                            dataType: 'html',
                                            success: function(data) {
                                                    html  = '<div id="modal-agree" class="modal">';
                                                    html += '  <div class="modal-dialog">';
                                                    html += '    <div class="modal-content">';
                                                    html += '      <div class="modal-header">';
                                                    html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
                                                    html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
                                                    html += '      </div>';
                                                    html += '      <div class="modal-body">' + data + '</div>';
                                                    html += '    </div>';
                                                    html += '  </div>';
                                                    html += '</div>';

                                                    $('body').append(html);

                                                    $('#modal-agree').modal('show');
                                                  
                                                    unlock_input();
                                                     return false;    
                                               
                                            }
                                    });
                          return false;
                            });   
                     //--></script>

<?php echo $footer; ?>