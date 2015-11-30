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
<link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
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
</head>
<body class="<?php echo $class; ?>">
<header>
<nav id="menu" class="navbar">
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
					<div class="col-sm-3 hidden-xs ns1"><?php echo $cart; ?></div>				
					<div class="col-sm-3 ns ns1">
						<div id="main-menu" class="clearfix">
						    <div class="navbar-header"  navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><span id="category" class="visible-xs visible-sm">All Products</span>
						      <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-chevron-down"></i></button>
						    </div>
						    <div class="navbar-header">
							    <div class="navbar-header visible-lg visible-md"  navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><span id="category">All Products</span>
							      <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-chevron-down"></i></button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-5 ns">
						<div id="pavmenu" class="visible-lg visible-md">
							<?php echo $pavmegamenu; ?>
						</div>
					</div>
					<div class="col-lg-4 ns">
						<div class="nav pull-right headlink">
							<ul class="clearfix">
					        <li class="dropdown"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><span class="hidden-lg"><i class="fa fa-user"></i></span><span class="hidden-xs hidden-sm hidden-md"><?php echo $text_account; ?></span> <span class="caret"></span></a>
					          <ul class="dropdown-menu dropdown-menu-right">
					            <?php if ($logged) { ?>
					            <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
					            <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
					            <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
					            <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
					            <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
								<li><a href="<?php echo $wishlist; ?>" id="wishlist-total" title="<?php echo $text_wishlist; ?>"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_wishlist; ?></span></a></li>
					        	<li><a href="<?php echo $shopping_cart; ?>" title="<?php echo $text_shopping_cart; ?>"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_shopping_cart; ?></span></a></li>
					        	<li><a href="<?php echo $checkout; ?>" title="<?php echo $text_checkout; ?>"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_checkout; ?></span></a></li>            
					            <?php } else { ?>
					            <li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
					            <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
					            <?php } ?>
					          </ul>
					        </li>
					      </ul>
						</div>
					</div>
				</div>
			</div>
			<div class="collapse navbar-ex1-collapse" id="megamenu">
				<div class="nav-bgd clearfix">
				<div class="container">
					<div class="row">
					<div class="clearfix"></div>
					<?php foreach (array_chunk($categories,ceil(count($categories)/4)) as $categoryl) { ?>
					<ul class="nav navbar-nav">
					<?php foreach ($categoryl as $category) { ?>
					<?php if ($category['childern']) { ?>
					<li class="col-sm-12"><h4><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></h4></a>
							<ul class="list-unstyled sub-menu">
									<?php foreach ($category['childern'] as $child) { ?>
									<li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
									<?php } ?>
									<li><a href="<?php echo $category['href']; ?>" class="see-all"><?php echo $text_all; ?> <?php echo $category['name']; ?></a></li>
							</ul>
					</li>
					<?php } else { ?>
					<li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
					<?php } ?>
					<?php } ?>
					</ul>
					<?php } ?>
					</div>
				</div>
				</div>
			</div>			
		</div>
	</nav>  
</header>
