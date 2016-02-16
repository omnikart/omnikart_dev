<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html xmlns:og="http://ogp.me/ns#"
      xmlns:fb="https://www.facebook.com/2008/fbml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta property="fb:app_id" content="797536807017110">
<title><?php echo $title; ?></title>
<meta property="og:title" content="<?php echo $title; ?>" />
<meta property="og:type" content="testingomnikart:tool" />
<meta property="og:url" content="<?php echo $og_url; ?>" />
<meta property="og:description" content="<?php echo $description; ?>" />
<meta property="og:image" content="<?php echo $og_image; ?>" />
<meta property="og:image:type" content="image/jpeg" />
<meta property="og:image:width" content="470" />
<meta property="og:image:height" content="394" />
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>"
	rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<?php if (getNitroPersistence('GoogleJQuery')) { ?>
                <script
	src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
            <?php } else { ?>
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js?v=7"
	type="text/javascript"></script>
<?php } ?>
<script src="catalog/view/javascript/mf/jquery-ui.min.js?v=7"
	type="text/javascript"></script>
<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css?v=7"
	rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js"
	type="text/javascript"></script>
<link
	href="catalog/view/javascript/font-awesome/css/font-awesome.min.css?v=7"
	rel="stylesheet" type="text/css" />
<link
	href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700"
	rel="stylesheet" type="text/css" />
<link href="catalog/view/theme/default/stylesheet/stylesheet.css?v=7"
	rel="stylesheet">
<script src="catalog/view/javascript/comboproducts.js"
	type="text/javascript"></script>
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css"
	rel="<?php echo $style['rel']; ?>"
	media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/common.js?v=7"
	type="text/javascript"></script>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<?php echo $google_analytics; ?>
<!-- Start Alexa Certify Javascript -->
<script type="text/javascript">
_atrk_opts = { atrk_acct:"GTT9m1a47E805T", domain:"omnikart.com",dynamic: true};
(function() { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://d31qbv1cthcecs.cloudfront.net/atrk.js"; var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(as, s); })();
</script>
<noscript>
	<img
		src="https://d5nxst8fruw4z.cloudfront.net/atrk.gif?account=GTT9m1a47E805T"
		style="display: none" height="1" width="1" alt="" />
</noscript>
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
<?php if (isset($facebook)) { ?>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');

fbq('init', '1399996446990885');
fbq('track', "PageView");
</script>
<noscript>
	<img height="1" width="1" style="display: none"
		src="https://www.facebook.com/tr?id=1399996446990885&ev=PageView&noscript=1" />
</noscript>
<!-- End Facebook Pixel Code -->
<?php } elseif (isset($facebook_cart)) { ?>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');

fbq('init', '1399996446990885');
fbq('track', 'AddToCart');
</script>
<noscript>
	<img height="1" width="1" style="display: none"
		src="https://www.facebook.com/tr?id=1399996446990885&ev=PageView&noscript=1" />
</noscript>
<!-- End Facebook Pixel Code -->
<?php } elseif (isset($facebook_success)) { ?>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');

fbq('init', '1399996446990885');
fbq('track', 'Purchase', {value: '0.00', currency: 'USD'});
</script>
<noscript>
	<img height="1" width="1" style="display: none"
		src="https://www.facebook.com/tr?id=1399996446990885&ev=PageView&noscript=1" />
</noscript>
<!-- End Facebook Pixel Code -->
<?php } ?>


</head>
<body class="<?php echo $class; ?>">
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '797536807017110',
      xfbml      : true,
      version    : 'v2.5'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
	<!-- nav id="top">
  <div class="container">
    <?php //echo $currency; ?>
    <?php //echo $language; ?>

  </div>
</nav -->
	<!-- div class="background">
</div>
<div class="background-b">
</div -->
	<header>
		<div class="container-fluid">
			<div class="container">
				<div class="row" id="header">
					<div class="col-sm-3">
						<div id="logo">
						<?php if ($logo) { ?>
						<a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>"
								title="<?php echo $name; ?>" alt="<?php echo $name; ?>"
								class="img-responsive" /></a>
						<?php } else { ?>
						<h1>
								<a href="<?php echo $home; ?>"><?php echo $name; ?></a>
							</h1>
						<?php } ?>
					</div>
					</div>
					<div class="col-sm-9">
						<div class="row">
							<div class="col-sm-12">
								<div id="top-links" class="nav pull-right">
									<ul class="list-inline">
										<li><a href="<?php echo $contact; ?>"><span class="top-i"><i
													class="fa fa-phone"></i></span></a><span
											class="hidden-xs hidden-sm hidden-md top-i"><?php echo $telephone; ?></span></li>

						<?php if ($logged && ($rights || $chkIsPartner)) { ?>
							<li class="dropdown"><a href="<?php echo $menusell; ?>" title="<?php echo $menusell; ?>" class="dropdown-toggle" data-toggle="dropdown"><span class="top-i"><i class="fa fa-th"></i></span><span class="hidden-sm hidden-xs hidden-md top-i">Menu</span><span class="caret"></span></a>
									<div class="dropdown-menu dropdown-menu-right">
										<div class="dropdown-inner">
											<ul class="list-unstyled">
												<div class="col-sm-12 hb">
													<li><a class="btn btn-info" href="<?php echo $mp_profile; ?>"><?php echo $text_my_profile; ?></a></li>
													<li><a class="btn btn-info" href="<?php echo $mp_dashboard; ?>"><?php echo $text_dashboard; ?></a></li>
													<li><a class="btn btn-info" href="<?php echo $mp_orderhistory; ?>"><?php echo $text_orderhistory; ?></a></li>
													<li><a class="btn btn-info" href="<?php echo $mp_transaction; ?>"><?php echo $text_transaction; ?></a></li>
													<li><a class="btn btn-info" href="<?php echo $mp_productlist; ?>"><?php echo $text_productlist; ?></a></li>
													<li><a class="btn btn-info" href="<?php echo $mp_enquiry; ?>"><?php echo $text_enquiry; ?></a></li>													
													<li><a class="btn btn-info" href="<?php echo $mp_download; ?>"><?php echo $text_download; ?></a></li>
													<li><a class="btn btn-info" href="<?php echo $mp_add_shipping_mod; ?>"><?php echo $text_wkshipping; ?></a></li>
												</div>
											</ul>
											<?php if ($rights) { ?>
											<ul id="dashboard" class="list-unstyled">
												<div class="col-sm-12 hb">
												<?php if (in_array('db',$rights)) {?>
													<li><a class="btn btn-primary" href="<?php echo $b_db; ?>"><i class="fa fa-line-chart"></i><?php echo $t_db; ?></a></li>
													<li><a class="btn btn-primary" href="<?php echo $b_so; ?>"><i class="fa fa-calendar-plus-o"></i><?php echo $t_so; ?></a></li>
													<li><a class="btn btn-primary" href="<?php echo $b_se;?>" ><i class="fa fa-file-text-o"></i><?php echo $t_se;?></a></li>
												<?php } ?>
												</div>
											</ul>
											<?php } ?>
									</div>
								</div>
							</li>
						<?php } else { ?>
							<li class="dropdown"><a href="<?php echo $menusell; ?>"><span
												class="hidden-sm hidden-xs hidden-md top-i">Sell Online</span></a></li>
						<?php } ?>
						
									<li class="dropdown"><a href="<?php echo $account; ?>"
											title="<?php echo $text_account; ?>" class="dropdown-toggle"
											data-toggle="dropdown"><span class="top-i"><i
													class="fa fa-user"></i></span><span
												class="hidden-xs hidden-sm hidden-md top-i"><?php echo $text_account; ?></span><span
												class="caret"></span></a>
											<ul class="dropdown-menu dropdown-menu-right">
											<?php if ($logged) { ?>
											<li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
												<li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
												<li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
												<li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
												<li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
											<?php } else { ?>
											<li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
												<li><a href="<?php echo $login; ?>" id="marketinsg-login"><?php echo $text_login; ?></a></li>
											<?php } ?>
										</ul></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-9"><?php echo $search; ?>
						</div>
							<div class="col-sm-3 hidden-xs ns1 pull-right"><?php echo $cart; ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php if ($categories) { ?>
	<div class="container-fluid" id="menu-holder">
			<div class="container">
				<nav id="menu" class="navbar">
					<div class="navbar-header">
						<span id="category" class="visible-xs"><?php echo $text_category; ?></span>
						<button type="button" class="btn btn-navbar navbar-toggle"
							data-toggle="collapse" data-target=".navbar-ex1-collapse">
							<i class="fa fa-bars"></i>
						</button>
					</div>
					<div class="collapse navbar-collapse navbar-ex1-collapse">
						<ul class="nav navbar-nav">
	        <?php foreach (array_slice($categories,0,8) as $category) { ?>
		        <?php if ($category['children']) { ?>
		        <li class="dropdown"><a
								href="<?php echo $category['href']; ?>"
								class="dropdown-toggle disabled" data-toggle="dropdown"><?php echo $category['name']; ?> <span
									class="caret"></span></a>
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
									<a href="<?php echo $category['href']; ?>" class="see-all"><?php echo $text_all; ?> <?php echo $category['name']; ?></a>
								</div></li>
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