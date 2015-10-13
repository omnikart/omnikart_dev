<footer>
  <div class="container">
    <div class="row">
      <?php if ($informations) { ?>
      <div class="col-sm-3">
        <h5><?php echo $text_information; ?></h5>
        <ul class="list-group">
          <?php foreach ($informations as $information) { ?>
          <li class="list-group-item"><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
      <div class="col-sm-3">
        <h5><?php echo $text_service; ?></h5>
        <ul class="list-group">
          <li class="list-group-item"><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
          <li class="list-group-item"><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
          <li class="list-group-item"><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h5><?php echo $text_extra; ?></h5>
        <ul class="list-group">
          <li class="list-group-item"><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
          <li class="list-group-item"><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
          <li class="list-group-item"><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
          <li class="list-group-item"><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h5><?php echo $text_account; ?></h5>
        <ul class="list-group">
          <li class="list-group-item"><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
          <li class="list-group-item"><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
          <li class="list-group-item"><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
          <li class="list-group-item"><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
        </ul>
      </div>
    </div>
  </div>
    <div class="container">
	<a href="index.php?route=account/order"><div class="col-sm-10 col-sm-offset-1">
		<div class="col-sm-4 col-xs-12 bnotes">
			<i class="fa fa-refresh"></i>
			<span>FREE & EASY <br>RETURNS</span>
		</div>
		<div class="col-sm-4 col-xs-12 bnotes">
			<i class="fa fa-map-marker"></i>
			<span>TRACK YOUR <br> ORDER"</span>
		</div>
		<div class="col-sm-4 col-xs-12 bnotes">
			<i class="fa fa-edit"></i>
			<span>EDIT YOUR <br> ORDERS</span>
		</div>
	</div></a>
  </div>
	<div class="container"><div class="col-sm-2 col-sm-offset-5">
    <ul class="follow-us clearfix" >
      <li class=" left col-sm-4"><a target="_blank" href="https://www.facebook.com/omnikart"><div id="fb"></div></a></li>
      <li class="left col-sm-4"><a target="_blank" href="https://twitter.com/OMNIKART_COM/"><div id="tw">a</div></a></li>
      <li class="left col-sm-4"><a target="_blank" href="https://plus.google.com/+Omnikartengineering/"><div id="gp">a</div></a></li>
     </ul>
	</div>  
	</div>
	<div class="container">  
  	<div id="powered">
		<div><?php echo $powered; ?></div>
		<img src="image/payment methods.png" style="height:30px;">
	</div>
	</div>
</footer>
</body></html>
