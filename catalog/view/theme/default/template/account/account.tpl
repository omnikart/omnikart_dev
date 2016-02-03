<?php echo $header; ?><div id="columns">
	<div class="container">
		<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success">
			<i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <ul class="list-unstyled col-md-4 col-sm-6">
					<h2><?php echo $text_my_account; ?></h2>
					<li><a href="<?php echo $edit; ?>"><i class="fa fa-pencil-square-o"></i><?php echo $text_edit; ?></a></li>
					<li><a href="<?php echo $password; ?>"><i class="fa fa-user-secret"></i><?php echo $text_password; ?></a></li>
					<li><a href="<?php echo $address; ?>"><i class="fa fa-map-o"></i><?php echo $text_address; ?></a></li>
					<li><a href="<?php echo $wishlist; ?>"><i class="fa fa-heart-o"></i><?php echo $text_wishlist; ?></a></li>
				</ul>
				<ul class="list-unstyled col-md-4 col-sm-6">
					<h2><?php echo $text_my_orders; ?></h2>
					<li><a href="<?php echo $order; ?>"><i class="fa fa-history"></i><?php echo $text_order; ?></a></li>
					<li><a href="<?php echo $download; ?>"><i class="fa fa-download"></i><?php echo $text_download; ?></a></li>
        <?php if ($reward) { ?>
        <li><a href="<?php echo $reward; ?>"><i class="fa fa-gift"></i><?php echo $text_reward; ?></a></li>
        <?php } ?>
        <li><a href="<?php echo $return; ?>"><i class="fa fa-repeat"></i><?php echo $text_return; ?></a></li>
					<li><a href="<?php echo $transaction; ?>"><i
							class="fa fa-credit-card"></i><?php echo $text_transaction; ?></a></li>
					<li><a href="<?php echo $recurring; ?>"><i
							class="fa fa-refresh fa-spin "></i><?php echo $text_recurring; ?></a></li>
				</ul>
				<ul class="list-unstyled col-md-4 col-sm-6">
					<h2><?php echo $text_my_newsletter; ?></h2>
					<li><a href="<?php echo $newsletter; ?>"><i
							class="fa fa-newspaper-o"></i><?php echo $text_newsletter; ?></a></li>
					<li><a href="<?php echo $product; ?>"><i class="fa fa-newspaper-o"></i>Product</a></li>
				</ul>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
	</div>
</div><?php echo $footer; ?>
