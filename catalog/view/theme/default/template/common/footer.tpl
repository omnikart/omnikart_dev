<footer style="background-color:#333;">
  <div class="container">
    <div class="row">
      <?php if ($informations) { ?>
      <div class="col-sm-2">
        <h4><?php echo $text_information; ?></h4>
        <ul>
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
      <div class="col-sm-2">
        <h4><?php echo $text_service; ?></h4>
        <ul>
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
          <li><a href="<?php echo $careers; ?>"><?php echo $text_careers; ?></a></li>
          <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
          <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-2">
        <h4><?php echo $text_extra; ?></h4>
        <ul>
          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
          <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
          <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
          <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-2">
        <h4><?php echo $text_account; ?></h4>
        <ul>
          <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
          <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
          <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
        </ul>
      </div>
		 <div class="col-sm-4">
					<?php foreach ($modules as $module) { ?>
						<?php echo $module; ?>
					<?php } ?>
					<ul id="socail">
						<li><a target="_blank" href="https://www.facebook.com/omnikart/"><i style="background:#3b5998;" class="fa fa-facebook"></i></a></li>
						<li><a target="_blank" href="https://www.facebook.com/omnikart"><i style="background:#007bb6" class="fa fa-linkedin"></i></a></li>
						<li><a target="_blank" href="https://twitter.com/OMNIKART_COM/"><i style="background:#00aced" class="fa fa-twitter"></i></a></li>
						<li><a target="_blank" href="https://plus.google.com/+Omnikartengineering/"><i style="background:#dd4b39" class="fa fa-google-plus"></i></a></li>
				 </ul>
			</div>
			<div class="col-sm-12" id="powered" style="color:#FFF;">
					<div><?php echo $powered; ?></div>
					<img src="image/payment.png" style="height:30px;">
			</div>
	 </div>
	</div>
</footer> 
<script type="text/javascript"><!--
$(document).delegate('#marketinsg-login', 'click', function(e) {
	e.preventDefault();

	$('#modal-login').remove();

	var element = this;

	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-login" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
			html += '      </div>';
			html += '      <div class="modal-body">' + data + '</div>';
			html += '    </div';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#modal-login').modal('show');
		}
	});
});
//--></script>
<script  type="text/javascript" >
	var width_search = document.getElementById("search").offsetWidth;
	$('.result-search-autocomplete').css({"width":width_search});
	$('.search-autocomplete').keyup(function(event) {
		/* Act on the event */
		$('.result-search-autocomplete  ul').css({"overflow" : "hidden"});
		var search = $('input[name=search]').val();
		$.ajax({
		  method: "GET",
		  url: "<?php echo $search_action; ?>",
		  data: { search : search }
		}).done(function( result ) {
			var html = '';
			if(result && search != '')
			{
				var count = 0
				$.each(JSON.parse(result), function( index, value ) {
				  	
				  	html += '<li>';
				  	html += '<a href="'+value.href.replace('amp;', '')+'">';
				  	html += '<div class="row">';
				  	html += '<div class="col-md-3 row-result-search-autocomplete-image">';
				  	html += '<img class="result-search-autocomplete-image" src="'+value.thumb+'">';
				  	html += '</div>';
				  	html += '<div class="col-md-6 result-info">';
				  	html += '<h4>'+value.name+'</h4>';
				  	/*if(value.special == false)
				  	{
				  		html += '<h5>'+value.price+' <i></i></h5>';
				  	}else{
				  		html += '<h5>'+value.special+' <i>'+value.price+'</i></h5>';
				  	}
				  	*/
				  	html += '</div>';
				  	html += '<div style="text-align:right" class="col-md-3 result-button">';
				  	html += '<button type="button" class="btn tagdattruoc">Buy Now</button>';
				  	//html += '<h6>Xem them</h6>';
				  	html += '</div>';
				  	html += '</div>';
				  	html += '</a>';
				  	html += '</li>';
				  	count++;
				});
					$('.result-search-autocomplete').css({"display":"block"});
				  	if(count > 5)
					{
						$('.result-search-autocomplete  ul').css({"overflow" : "scroll"});
					}else{
						$('.result-search-autocomplete  ul').css({"overflow" : "hidden"});
					}
			}else{
				html = '';
				$('.result-search-autocomplete').css({"display":"none"});
			}

			$('.show-result').html(html);
		});
	});
</script>
<style type="text/css" media="screen">
.result-search-autocomplete
{
	display: none;
	position: absolute;
	z-index: 1000;
	background-color: #FFF;
	border: 1px solid #ddd;
	top:40px;
	max-height:468px;
}
.result-search-autocomplete h4
{
	  display: block;
	  width: 72%;
	  line-height: 1.3em;
	  color: #333;
	  font-size: 14px;
	  font-weight: 700;
	  overflow: hidden;
	  text-overflow: ellipsis;
	  white-space: nowrap;
}
.result-search-autocomplete h5
{
	font-size: 14px;
    margin-top: 8px;
    color: red;
}
.result-search-autocomplete h5 i
{
	color: #999;
	font-style: normal;
	font-size: 11px;
	text-decoration: line-through;
}
.result-search-autocomplete h6
{
	text-transform: uppercase;
  	font-size: 9px;
  	font-weight: 700;
  	color: #0876e6;
  	display: block;
  	margin-top: 8px;
  	text-align: right;
}
.result-search-autocomplete ul, li
{
	list-style-type: none;
	margin: 0;
	padding: 0;
}
.result-search-autocomplete-image
{
	height: 50px;
	padding-left: 15px;
}
.result-search-autocomplete > ul
{
	max-height:468px;
	overflow: hidden;
	/*overflow: scroll;
	overflow-x:none;*/
}
.result-search-autocomplete > ul >li >a
{
	position: relative;
  	display: block;
  	overflow: hidden;
  	padding: 6px;
  	text-decoration: none;
}
.result-search-autocomplete > ul >li 
{
	display: block;
  	background: #fff;
  	overflow: hidden;
  	list-style: none;
  	border-bottom: 1px dotted #ccc;
  	float: none;
}
.result-search-autocomplete > ul >li > a:hover button
{
	color: #FFF;
}
.tagdattruoc {
  background: #3498db;
  border: 1px solid #0679c6;
  font-size: 11px;
  color: #fff;
  border-radius: 0px;
  margin-top: 18px;
}
.tagdattruoc :hover
{
	color: #FFF;
}
@media (max-width: 767px) {
		.result-button {
			width: 30%;
			float: left;
		}
		.row-result-search-autocomplete-image
		{
			width: 30%;
			float: left;
		}
		.result-info
		{
			width: 40%;
			float: left;
		}
	}
	
  </style>
 
<script type="text/javascript">
var google_tag_params = {
ecomm_prodid: '<?php echo $product_id; ?>',
ecomm_pagetype: '<?php echo $route; ?>',
ecomm_totalvalue: '<?php echo $total; ?>',
};
</script>
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 975481156;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/975481156/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<?php
	require_once DIR_SYSTEM . 'nitro/core/core.php';
	require_once NITRO_INCLUDE_FOLDER . 'pagecache_widget.php';
?>
</body></html>
