
<?php if (!isset($redirect)) { ?>
    <style type="text/css">
        #confirm tr>td{
            vertical-align: middle;
        }

  .btn-remove{ padding: 3.5px  8px;}
 
    </style>
    <script type="text/javascript"><!--
        function validate_quantity_keypress(evt) {
            var theEvent = evt || window.event;
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
            var regex = /[0-9]/;
            if (!regex.test(key)) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault)
                    theEvent.preventDefault();
            }
        }
        //--></script>

<div class="row ">
<div class="col-sm-12">
<?php foreach ($vendors as $vendor_id => $vendor) { ?>  
<div class="panel panel-default">
	<div class="panel-heading">
  	<?php echo "Sold By: ".$vendor['details']['companyname']; ?>
	</div>
  <div class="panel-body">
	    
        <div class="table-responsive">
				<table class="table table-striped table-bordered" style="border-bottom: 3px solid #dff0d8;">
            <thead>
                <tr class="">
                    <th colspan="2" class="name"><?php echo $column_name; ?></th>
                    
                     <?php if($mmos_checkout['enable_pmodel'] == 1) { ?>
                   
                    <th class="model" style="width:100px;"><?php echo $column_model; ?></th>  
                     <?php }  ?>
                   
                    <th class="quantity text-center" style="width:100px;"><?php echo $column_quantity; ?></th>
                    <th class="price" style="width:100px;"><?php echo $column_price; ?></th>
                    <th class="total" style="width:100px;"><?php echo $column_total; ?></th>
                    <th class="total" style="width:100px;">Total Tax</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vendor['products'] as $product) { ?>  
                    <?php if ($product['recurring']): ?>
                        <tr>
                            <td colspan="6" style="border:none;"><image src="catalog/view/theme/default/image/reorder.png" alt="" title="" style="float:left;" /><span style="float:left;line-height:18px; margin-left:10px;"> 
                                    <strong><?php echo $text_recurring_item ?></strong>
                                    <?php echo $product['profile_description'] ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="name" style="width:80px;">
                               <img class="product-image" src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />   
                             <input  class="product-name" type="hidden" value="<?php echo $product['name']; ?>"/>
                             <input  class="product-image-popup" type="hidden" value="<?php echo $product['image_popup']; ?>"/>
                        </td>
                        <td class="name">
                            <a href="<?php echo $product['href']; ?>">
                              
                                <span style="padding-left: 1em;"><?php echo $product['name']; ?></span>
                               
                            </a>

                            <?php foreach ($product['option'] as $option) { ?>
                                <br />
                                &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                            <?php } ?>
                            <?php if ($product['recurring']): ?>
                                <br />
                                &nbsp;<small><?php echo $text_payment_profile ?>: <?php echo $product['profile_name'] ?></small>
                            <?php endif; ?>
                        </td>
                        
                        <?php if($mmos_checkout['enable_pmodel'] == 1) { ?>
                        <td class="model"><?php echo $product['model']; ?></td>
                        
                        <?php } ?>
                        <td class="quantity text-center">
													<div class="input-group input-group-sm">
														<span class="input-group-btn">
															<button type="button" class="btn btn-<?php echo ($css['checkout_theme'] == 'standar') ?  'warning' : $css['checkout_theme'];  ?> btn-decrease"><i class="fa fa-minus"></i></button>														
														</span>
														<input type="hidden" class="original-quantity form-control" value="<?php echo $product['quantity']; ?>"/>
														<input type="text" class="product-quantity form-control text-center " name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="3" onkeypress="validate_quantity_keypress(event)" />														
														<span class="input-group-btn">
															<button type="button" class="btn btn-<?php echo ($css['checkout_theme'] == 'standar') ?  'warning' : $css['checkout_theme'];  ?> btn-increase"><i class="fa fa-plus"></i></span></button>
														</span>
													</div>
												</td>
                        <td class="price"><?php echo $product['price']; ?></td>
                        <td class="total"><?php echo $product['total']; ?></td>
                        <td class="total"><?php echo $product['tax']; ?></td>
                        <td class="text-right">
                           
                                <button type="button"  alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" class="btn-remove btn btn-danger btn-xs" >
                                  <span class="glyphicon glyphicon-remove"></span>
                                    <input type="hidden" value="<?php echo $product['key']; ?>"/>
                                </button>
                          

                        </td>
                    </tr>
                <?php } ?>
                </tbody>
							</table>
							<table>
                <?php foreach ($vendor['vouchers'] as $voucher) { ?>
                    <tr>
                        <td class="name" colspan="3"><?php echo $voucher['description']; ?></td>
                         
                        <td class="quantity">
                            <input type="text" value="1" class="form-control text-center" readonly="readonly"/>
                        </td>
                        <td class="price"><?php echo $voucher['amount']; ?></td>
                        <td class="total"><?php echo $voucher['amount']; ?></td>
                        <td>
                           
                                <button type="button"  alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" class="btn-remove btn btn-danger btn-xs" >
                                    <span class="glyphicon glyphicon-remove"></span>
                                    <input type="hidden" value="<?php echo $voucher['key']; ?>"/>
                                </button>
                            
                        </td>
                    </tr>
                <?php } ?>
               </table>
        				<table class="pull-right">
									<?php $i = 0; ?>
									<?php foreach ($vendor['totals'] as $total) { ?>
											<tr class="" style="border: none;">
													<td colspan="<?php echo ($mmos_checkout['enable_pmodel'] == 1) ? '5' : '4'; ?>" class="price text-right "><b><?php echo $total['title']; ?>:</b></td>
													<td class="total" colspan="2"><?php echo $total['text']; ?></td>
											</tr>
											<?php $i++; ?>
									<?php } ?>
               </table>
 
    </div>
	</div>
</div>

					<?php } ?>
   					
					<div class="clearfix"></div><br /><hr />
					<table class="pull-right">
            <tbody>
							<?php foreach ($totals as $total) { ?>
									<tr class="" style="border: none;">
											<td colspan="<?php echo ($mmos_checkout['enable_pmodel'] == 1) ? '5' : '4'; ?>" class="price text-right "><b><?php echo $total['title']; ?>:</b></td>
											<td class="total" colspan="2"><?php echo $total['text']; ?></td>
									</tr>
									<?php $i++; ?>
							<?php } ?>
            </tbody>
					</table>
					</div>
    </div>    



    <div id="order-comment">
        <strong class="text-info"><i class="fa fa-comments"  style="font-size: 2em;"></i>&nbsp;<?php echo $text_comments; ?></strong>

        <div class="form-group">
            <textarea rows="3" name="comment" class="form-control <?php
            if ($css['checkout_theme'] == 'standar') {
                echo 'bg-warning';
            }
            ?>" style="resize: vertical;" placeholder="<?php echo $text_comments; ?>"><?php echo $comment; ?></textarea>
        </div>
    </div>

    <?php if (!$shipping_required && !empty($no_shipping_required)) { ?>
        <div class="alert alert-warning" role="alert">
            <i class="fa fa-exclamation-circle"></i>&nbsp;<span><?php echo $no_shipping_required; ?></span>
        </div>
    <?php } ?>


    <div class="row" style="">
        <div id="confirm-footer-panel" class="panel" style="margin-bottom: 0;border:none;">
            <?php if (!$logged) { ?>
                <?php if ($account == 'guest') { ?>
                    <div class="checkout-overlay checkout-overlay-dark hidden"></div>
                <?php } else { ?>
                    <div class="checkout-overlay checkout-overlay-dark"></div>
                <?php } ?>
            <?php } else { ?>
                <div class="checkout-overlay checkout-overlay-dark hidden"></div>
            <?php } ?>

            <div class="panel-footer">
                <?php if ($text_agree) { ?>
                    <?php if ($agree) { ?>
                        <input type="checkbox" name="agree" value="1" checked="checked" />
                    <?php } else { ?>
                        <input type="checkbox" name="agree" value="1" />
                    <?php } ?>
                    <strong class="text-warning"><i><?php echo $text_agree; ?></i></strong>
                    <div class="text-right">
                        <input type="button" id="btn-make-order" value="<?php echo $make_order_button; ?>" class="btn btn-<?php
                        if ($css['checkout_theme'] == 'standar') {
                            echo 'primary';
                        } else {
                            echo $css['checkout_theme'];
                        }
                        ?>" style="<?php
                               if (!empty($css['make_order_btn_color'])) {
                                   echo "background-color:{$css['make_order_btn_color']}!important;background-image:none;";
                               }
                               ?>">
                    </div>
                <?php } else { ?>

                    <div class="text-right">
                        <input type="button" id="btn-make-order" value="<?php echo $make_order_button; ?>" class="btn btn-<?php
                        if ($css['checkout_theme'] == 'standar') {
                            echo 'primary';
                        } else {
                            echo $css['checkout_theme'];
                        }
                        ?>"style="<?php
                               if (!empty($css['make_order_btn_color'])) {
                                   echo "background-color:{$css['make_order_btn_color']}!important; background-image:none;";
                               }
                               ?>">
                    </div>
                <?php } ?>

            </div>
        </div>
    </div> 


    <!--    <div id="confirm-checkout" style="display: none;">

        </div>-->


    <style>
        .qtip-wiki{
            max-width: none!important;
/*            min-width: <?php echo $config_image_popup_width; ?>px !important;
            min-height: <?php echo $config_image_popup_height; ?>px;*/
            background: #FFF;
            border-radius: 8px;
            /*border-style: none;*/
        } 
    </style>
    <?php if ($mmos_checkout['enable_qtip'] == '1'){ ?>
    <script type="text/javascript"><!--
        $('#confirm img.product-image').each(function() {
            var product_name = $(this).siblings('.product-name').val();
            var urlproduct = $(this).siblings('.product-image-popup').val();
            $(this).qtip({
                content: {
                    text: '<div>'
                            + '<div><h3 class="text-center">' + product_name + '</h3></div>'
                            + '<div style="text-align: center!important;"><img src="' + urlproduct + '" />' + '</div>'

                            + '</div>'
                },
                position: {
                    my: 'right center',
                    target: 'mouse',
                    adjust: {
                        screen: true
                    },
                    viewport: $(window) // Keep it on-screen at all times if possible
                },
                show: {
                    ready: true,
                },
                hide: {
                    when: 'mouseout', fixed: true
                },
                style: 'qtip-wiki'
            });
        });
        //--></script>
    <?php } ?>
<?php } else { ?>
    <script type="text/javascript"><!--
        var redirect = '<?php echo $redirect; ?>';
    <?php if ($mmos_checkout['debug']) { ?>
            var messages = [];
        <?php foreach ($error as $err) { ?>
                messages.push('<?php echo $err; ?>');
        <?php } ?>
            var message = messages.join("\r\n");
            console.log(messages);
            console.log("Redirect: " + redirect);
            if (confirm(message + "\r\nRedirect: " + redirect)) {
                location = redirect;
            }
    <?php } else { ?>
            location = redirect;
    <?php } ?>
        //--></script> 
<?php } ?>

