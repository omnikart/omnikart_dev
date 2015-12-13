<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
<link href="catalog/view/theme/default/stylesheet/stylesheet.css?v=1" rel="stylesheet">
<script src="catalog/view/javascript/comboproducts.js" type="text/javascript"></script>
<style type="text/css">
	.combo-section {width: 100%;border-top: 1px solid #CCC;}
	.combo-section .combo-set {padding: 2px;width: 100%;min-height: 180px;}
	.combo-section .combo-set .combo-item {display: block;line-height: 14px;font-weight: bold;min-height: 14px;float: left;width: 14%;}
	.combo-item-img {padding-right: 5px;padding-left: 5px;text-align: center;}
	.combo-item-name,.combo-item-price {text-align: center;font-size: small;}
	.combo-action {float:left;width: 25%;}
	.combo-plus, .combo-save {float: left;font-weight: bold;padding-left:10px;}
	.combo-save {float:none;}
	.combo-plus {line-height: 100px}
	.price_discount {color: #900;}
	.btn-combo {text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.25);border: 1px solid #CCC;border-radius: 4px;box-shadow: 0px 1px 0px rgba(255, 255, 255, 0.2) inset, 0px 1px 2px rgba(0, 0, 0, 0.05);}
	.btn-combo-wishlist {background: linear-gradient(to bottom, #F7DFA5, #F0C14B) repeat scroll 0% 0% transparent;}
	.btn-combo-cart {color: #FFF;background: linear-gradient(to bottom, #23A1D1, #1F90BB) repeat-x scroll 0% 0% transparent;}
	@media only screen and (max-width: 500px) {.combo-plus {display:none;}.combo-action {width: 100%;}.combo-action .btn-combo {width: 100%;height: 40px;display:block;	}.combo-contain {min-height: 85px;}	.combo-save {font-size: 90%;}.combo-item-name,.combo-item-price {font-size: smaller;}}
</style>
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/common.js?v=1" type="text/javascript"></script>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<?php echo $google_analytics; ?>
<!-- Start Alexa Certify Javascript -->
<script type="text/javascript">
_atrk_opts = { atrk_acct:"GTT9m1a47E805T", domain:"omnikart.com",dynamic: true};
(function() { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://d31qbv1cthcecs.cloudfront.net/atrk.js"; var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(as, s); })();
</script>
<noscript><img src="https://d5nxst8fruw4z.cloudfront.net/atrk.gif?account=GTT9m1a47E805T" style="display:none" height="1" width="1" alt="" /></noscript>
<!-- End Alexa Certify Javascript --> 
<!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
$.src="//v2.zopim.com/?3WcMX96RW4gJ31zAU5MjXSgqmKvWIgXf";z.t=+new Date;$.
type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
</script>
<!--End of Zopim Live Chat Script-->
</head>
<body class="<?php echo $class; ?>">
<nav id="top">
  <div class="container">
    <?php //echo $currency; ?>
    <?php //echo $language; ?>
    <div id="top-links" class="nav pull-right">
      <ul class="list-inline">
        <li><a href="<?php echo $contact; ?>"><span class="top-i"><i class="fa fa-phone"></i></span></a><span class="hidden-xs hidden-sm hidden-md top-i"><?php echo $telephone; ?></span></li>
        <li class="dropdown"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><span class="top-i"><i class="fa fa-user"></i></span><span class="hidden-xs hidden-sm hidden-md top-i"><?php echo $text_account; ?></span><span class="caret"></span></a>
          <ul class="dropdown-menu dropdown-menu-right">
            <?php if ($logged) { ?>
            <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
            <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
            <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
            <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
            <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
            <?php } else { ?>
            <li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
            <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
            <?php } ?>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<header>
	<div class="container-fluid">
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<div id="logo">
						<?php if ($logo) { ?>
						<a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
						<?php } else { ?>
						<h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
						<?php } ?>
					</div>
				</div>
				<div class="col-sm-6"><?php echo $search; ?>
				</div>
				<div class="col-sm-1">
					
				</div>
				<div class="col-sm-3 hidden-xs ns1 pull-right"><?php echo $cart; ?></div>				
			</div>
		</div>
	</div>
	
	<?php if ($categories) { ?>
	<div class="container-fluid" id="menu-holder">
	<div class="container">
	  <nav id="menu" class="navbar">
	    <div class="navbar-header"><span id="category" class="visible-xs"><?php echo $text_category; ?></span>
	      <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
	    </div>
	    <div class="collapse navbar-collapse navbar-ex1-collapse">
	      <ul class="nav navbar-nav">
	        <?php foreach (array_slice($categories,0,8) as $category) { ?>
		        <?php if ($category['children']) { ?>
		        <li class="dropdown"><a href="<?php echo $category['href']; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $category['name']; ?> <span class="caret"></span></a>
		          <div class="dropdown-menu">
		            <div class="dropdown-inner">
		              <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
		              <ul class="list-unstyled">
		                <?php foreach ($children as $child) { ?>
		                <li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
		                <?php } ?>
		              </ul>
		              <?php } ?>
		            </div>
		            <a href="<?php echo $category['href']; ?>" class="see-all"><?php echo $text_all; ?> <?php echo $category['name']; ?></a> </div>
		        </li>
		        <?php } else { ?>
		        <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
		        <?php } ?>
	        <?php } ?>
	      </ul>
	    </div>
	  </nav>
	</div>
	</div>
	<?php } ?>	
</header>
