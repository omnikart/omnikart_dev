<?php echo $header; ?>
<?php $class = 'col-sm-12'; ?>
<div class="container">
	<div class="row">
	<form id="supplier_form" class="col-sm-12 collapse">
	<div class="row">
		<div class="col-sm-12">
			<h2 class="text-info text-center">Seller Registration Form</h2>
		</div>
		<div class="col-sm-4">
			<div class="form-group required">
				<label class="control-label" for="name-of-person" >Name of the Contact Person</label>
				<input name="name" class="form-control" placeholder="" type="text" id="name-of-person"/> 
			</div>
			<div class="form-group required">
				<label class="control-label" for="phone-number">Mobile Number</label>
				<input name="number" class="form-control" placeholder="" type="text" id="phone-number" /> 
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group required">
				<label class="control-label" for="company-name">Company Name</label>
        		<input name="company" class="form-control" placeholder="" type="text" id="company-name"/> 
			</div>
			<div class="form-group">
				<label class="control-label" for="alt-number">Alternate Number</label>
        		<input name="number_2" class="form-control" placeholder="" type="text" id="alt-number"/> 
      		</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group required">
				<label class="control-label" for="email">Email Id</label>
				<input name="email" class="form-control" placeholder="" type="text" id="email" />
			</div>
      		<div class="form-group">
				<label class="control-label" for="website">Website Link</label>
        		<input name="website" class="form-control" placeholder="" type="text" id="website"/> 
      		</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group required">
				<label class="control-label" for="alt-number">Trade</label>
				<div class="well well-sm" style="height:150px;overflow:auto">
					<div class="radio">
						<label>
							<input type="radio" value="1" name="trade" />OEM/Manufacturer
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" value="2" name="trade" />Authorized Dealer/Distributer
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" value="3" name="trade" />Dealer/Stockist
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" value="4" name="trade" />Importer
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" value="5" name="trade" />Reseller
						</label>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group required">
				<label class="control-label" for="alt-number">Brands</label>
				<div class="well well-sm" style="height:150px;overflow:auto">
					<div class="checkbox">
						<label>
							<input type="checkbox" value="0" name="manufacturers[]" />Others
						</label>
					</div>
					<?php if (isset($manufacturers)) { foreach($manufacturers as $manufacturer) { ?>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="<?php echo $manufacturer['id']; ?>" name="manufacturers[]" /><?php echo $manufacturer['name']; ?>
							</label>
						</div>
					<?php }} ?>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group required">
				<label class="control-label" for="alt-number">Brands</label>
				<div class="well well-sm" style="height:150px;overflow:auto">
					<div class="checkbox">
						<label>
							<input type="checkbox" value="0" name="categories[]" />Others
						</label>
					</div>
					<?php if (isset($categories)) { foreach($categories as $category) { ?>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="<?php echo $category['category_id']; ?>" name="categories[]" /><?php echo $category['name']; ?>
							</label>
						</div>
					<?php }} ?>
				</div>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="text-center">
	        	<button type="button" class="btn btn-primary btn-lg" id="submit">Register</button>
	      	</div>
      	</div>
	</div>
	</form>	
    <div id="content" class="<?php echo $class; ?>">
      <div class="text-center">
        <h1 class="text-info"><?php echo $sell_header; ?></h1>
		<button type="button" class="btn btn-primary btn-lg" data-toggle="collapse" data-target="#supplier_form">
          <?php echo $sell_title; ?>&nbsp;<i class="fa fa-chevron-down"></i>
        </button>
      	<h4>If you are interested to sell on Omnikart then leave your details here & our business development executive will revert back to you soon.</h4>	
      </div>
      <br/>

      <ul class="nav nav-tabs">
        <?php if($tabs){ ?>
          <?php foreach ($tabs as $key => $value) { ?>
              <li <?php if(!$key){ ?>class="active"<?php } ?>><a href="<?php echo "#tab-".$key; ?>" data-toggle="tab"><?php echo $value['hrefValue']; ?></a></li>
          <?php }?>
        <?php }?>          
      </ul>

      <div class="tab-content">
        <?php foreach ($tabs as $key => $value) { ?>
          <div id="<?php echo "tab-".$key; ?>" class="tab-pane <?php if(!$key){ ?>active<?php } ?>"><?php echo $value['description']; ?></div>
        <?php }?>
      </div>

      <br/>
      <?php if($showpartners) { ?>

        <h3 class="text-info">
          <b><?php echo $text_long_time_seller; ?></b>
        </h3>
        <br/>

        <div class="row">
          <?php foreach ($partners as $partner) { ?>
          <div class="product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="product-thumb">
              
              <div class="text-center">
                <a href="<?php echo $partner['sellerHref']; ?>"><img src="<?php echo $partner['thumb']; ?>" alt="<?php echo $partner['name']; ?>" title="<?php echo $partner['name']; ?>" class="img-circle"/></a>

                <h4>
                  <?php echo $text_seller; ?><span data-toggle="tooltip" title="<?php echo $text_seller; ?>"><i class="fa fa-user"></i></span>
                  <a href="<?php echo $partner['sellerHref']; ?>"><?php echo $partner['name']; ?></a>
                </h4>

                <?php if($partner['country']){ ?>
                  <p>
                    <?php echo $text_from; ?><span data-toggle="tooltip" title="<?php echo $text_from; ?>"><i class="fa fa-home"></i></span>                  
                    <b><?php echo $partner['country']; ?></b>
                  </p>
                <?php } ?>

                <p>
                  <?php echo $text_total_products; ?>                 
                  <b><?php echo $partner['total_products']; ?></b>
                </p>
              </div>

            </div>
          </div>
          <?php } ?>
          <?php //for seller list ?>
        </div>
      <?php } ?>

      <?php if($showproducts) {?>
        
        <h3 class="text-info">
          <b><?php echo $text_latest_product; ?></b>
        </h3>
        <br/>

        <div class="row">
          <?php foreach ($latest as $product) { ?>
          <div class="product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="product-thumb seller-thumb" id="<?php echo $product['product_id']; ?>">
              <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
              <div>
                <div class="caption">
                  <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                  <p><?php echo $product['description']; ?></p>
                  <?php if ($product['rating']) { ?>
                  <div class="rating">
                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                    <?php if ($product['rating'] < $i) { ?>
                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                    <?php } else { ?>
                    <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                    <?php } ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                  <?php if ($product['price']) { ?>
                  <p class="price">
                    <?php if (!$product['special']) { ?>
                    <?php echo $product['price']; ?>
                    <?php } else { ?>
                    <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
                    <?php } ?>
                    <?php if ($product['tax']) { ?>
                    <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                    <?php } ?>
                  </p>
                  <?php } ?>
                </div>

                <div class="seller_info text-white">
                  <img class="img-circle float-left" src="<?php echo $product['avatar']; ?>"/>
                  <p class="float-right">
                    <?php echo $text_seller; ?> 
                    <span data-toggle="tooltip" title="<?php echo $text_seller; ?>"><i class="fa fa-user"></i></span>
                    <a href="<?php echo $product['sellerHref']; ?>" target="_blank"> <b class="text-white" ><?php echo $product['seller_name']; ?></b></a>
                  </p>

                  <?php if($product['country']){ ?>
                    <br/>
                    <p class="float-right">
                      <?php echo $text_from; ?>
                      <span data-toggle="tooltip" title="<?php echo $text_from; ?>"><i class="fa fa-home"></i></span>
                      <b><?php echo $product['country']; ?></b>
                    </p>
                  <?php } ?>
                </div>
                <div class="clear"></div>

                <div class="button-group">
                  <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
                  <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                  <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
     <?php } ?>
    </div>
  </div>
</div>


<script>
var seller_display = function (data){
  thisid = data.currentTarget.id; //get id of current selector
  $('#'+ thisid + ' .seller_info').slideDown(); 
  $('#'+ thisid).unbind('mouseenter');
}
var seller_hide = function (data){
  thisid = data.currentTarget.id; //get id of current selector
  $('#'+ thisid + ' .seller_info').slideUp('slow',function(){
    $('.seller-thumb').bind('mouseenter',seller_display);
  }); 
}

$('.seller-thumb').bind({'mouseenter' : seller_display,'mouseleave':seller_hide });

</script>
<script  type="text/javascript">
 $('#submit').on('click', function(){
    buttont = $(this);
      $.ajax({
        url : 'index.php?route=customerpartner/sell/supplierrequest',
        data: $('#supplier_form').serialize(),
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
            $(buttont).buttont('loading');
        },
        complete: function() {
            $(buttont).buttont('reset');
        },
        success: function(json) {
            $('.text-danger').remove();
            if (json['success']){
		        html ='<div class="alert alert-success"><i class="fa fa-check-circle"></i>Successfully send your query to Omnikart. We\'ll get back to you soon<button  type="button" class="close" data-dismiss="alert">&times;</button></div>';
	        	$('#avc').prepend(html);
	        	/*$('#supplier_form').reset();*/
            } else {
                if (json['company_name']) {
                    $($('#company-name').parent()).append('<div class="text-danger">'+json['company_name']+'</div>');
                }
                if (json['name_of_the_contact_person']) {
                    $($('#name-of-person').parent()).append('<div class="text-danger">'+json['name_of_the_contact_person']+'</div>');
                }
                if (json['phone_number']) {
                    $($('#phone-number').parent()).append('<div class="text-danger">'+json['phone_number']+'</div>');
                }
                if (json['alternate_number']) {
                    $($('#alt-number').parent()).append('<div class="text-danger">'+json['alternate_number']+'</div>');
                }
                if (json['email_id']) {
                    $($('#email').parent()).append('<div class="text-danger">'+json['email_id']+'</div>');
                }
                if (json['product_category']) {
                    $($('#product_category').parent()).append('<div class="text-danger">'+json['product_category']+'</div>');
                }
            }
        }
        
      });
  });
</script>

<?php echo $footer; ?>
