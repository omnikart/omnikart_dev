<?php if (!isset($redirect)) { ?>
<link rel="stylesheet"
	href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css"
	href="../../catalog/view/theme/default/stylesheet/stylesheet.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css"
	href="<?php echo $style['href']; ?>"
	media="<?php echo $style['media']; ?>" />
<?php } ?>
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script
	src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>

<div class="payment" <?php if($autosubmit) { ?>
	style="visibility: hidden" <?php } ?>><?php echo $payment; ?></div>

<?php if($autosubmit) { ?>
<script type="text/javascript">
$(document).ready(function(){
  $(".btn").click();
  if($('.btn').attr('href')) {
    window.location.href = $('.btn').attr('href');
  }
});
</script>
<?php } else { ?>
<script type="text/javascript">
$('.checkout-content').slideDown(0);
</script>
<?php } ?>

<?php } else { ?>
<script type="text/javascript"><!--
location = '<?php echo $redirect; ?>';
//--></script>
<?php } ?>