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
function addmodal(var_id,var_class) {
		$('#'+var_id).remove();
		html  = '<div id="'+var_id+'" class="modal tabindex="-1"  '+var_class+'" role="dialog" aria-labelledby="'+var_id+'">';
		html += '  <div class="modal-dialog">';
		html += '    <div class="modal-content">';
		html += '      <div class="modal-header">';
		html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
		html += '        <h4 class="modal-title"></h4>';
		html += '      </div>';
		html += '      <div class="modal-body"></div>';
		html += '      <div class="modal-footer"></div>';
		html += '    </div>';
		html += '  </div>';
		html += '</div>';
		$('body').append(html);
		return $('#'+var_id);
}

--></script>


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
		var search = $('input[name=search]').val().trim().toLowerCase();
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
				  	var first = 0
				  	html += '<li>';
				  	html += '<div class="row">';
				  	html += '<div class="col-sm-12">';
				  	html += '<div class="col-sm-12">';
				  	html += '<ul>';
					html += '<li><a href="'+value.href.replace('amp;', '')+'">'+value.name+'</a></li>';
					$.each(value.categories, function( index2, value2 ) {
						html += '<li class="search-cat"><a href="'+value2.href.replace('amp;', '')+'">'+value2.name+'</a></li>';
					});
					html += '</ul>';
				  	html += '</div>';
				  	html += '</div>';
				  	html += '</div>';
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
.search-cat{padding-left:15px;}
.search-cat a:before{content:'in ';}

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

<style type="text/css">
		#ToTop {display: none;text-decoration: none;position: fixed;bottom: 240px;right: 20px;overflow: hidden;width: 51px;height: 51px;border: none;text-indent: -999px;background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADMAAAAzCAMAAAANf8AYAAAAn1BMVEVHR0dHR0dGRkZERERHR0dHR0dHR0dDQ0NDQ0NHR0dFRUVCQkJHR0dHR0dHR0dHR0dHR0dHR0dHR0cAAABkZGS+vr5NTU2fn5/X19fFxcVra2vCwsLl5eVKSkrn5+dLS0uqqqp8fHy9vb1ISEhQUFBGRkbMzMyZmZnS0tJdXV1UVFRERETJyclOTk5bW1vq6upJSUllZWX////u7u5HR0esulOUAAAAFHRSTlMemqHMbBZK4OR2sO9XkD0Ri0agAGQsLyYAAAF9SURBVHjardbXcoJQFIVhpVcppndjLIFg4LDf/9ky2dGELQuFIf+FIxefwBlPmcyH908mSlxN9/NV7uuam0Q9jBXa1MwOrTMm8KidF5wws5Rw6azLGA515RjQmDGdKjbbZjKl000nx8YUBCPzyMR0vlgag/pkNM3M6WWcWcOk1K/0zwTUt+DXeATa3ZfUyjsYC5LP+gMga29CTGqEwh8T2ZhAZEdsEkw6UMLGxWR9Bx/PZaNh8kjvCGlsdEhKIoh0Nn4HOaC1QD6bXJAr8duMLqlRzmYlzMWeNBE1WoH7lDf1LZMDeqiv2/fxSfZGolJe+3DccHLctEFGY+MOMi6bZJBJxP+6V3YE5s9CfbfbKPVaUKaU2hZi/qB5uiwWqig22yx7eaKKv4p5iteDShGpiuhZZZXiS7AezANkvj9a9wnk+oaNfJ+0tY6iZ8PrKGccmc224jGQxujcFzJFtNyPtTDxiP1n6D43fD8dv2+fPx8MP4eMP++MP1fhxpsvsOKLcC9wW/YAAAAASUVORK5CYII=) no-repeat left top;}
		#ToTop:hover{cursor:pointer;}
				</style>
					<script type="text/javascript">
				/* toTop jQuery */
				jQuery(document).ready(function(){$().UItoTop({easingType:'easeOutQuint'});});
				(function($){
					$.fn.UItoTop = function(options) {
						var defaults = {
							text: 'To Top',
							min: 200,
							inDelay:200,
							outDelay:100,
							containerID: 'ToTop',
							containerHoverID: 'ToTopHover',
							scrollSpeed: 50,
							easingType: 'linear'
							};
							var settings = $.extend(defaults, options);
							var containerIDhash = '#' + settings.containerID;
							var containerHoverIDHash = '#'+settings.containerHoverID;
							$('body').append('<span id="'+settings.containerID+'">'+settings.text+'</span>');
							$(containerIDhash).hide().click(function(event){
								$('html, body').animate({scrollTop: 0}, settings.scrollSpeed);
								event.preventDefault();
							})
							.prepend('<span id="'+settings.containerHoverID+'"></span>')
									
							$(window).scroll(function() {
								var sd = $(window).scrollTop();
								if(typeof document.body.style.maxHeight === "undefined") {
									$(containerIDhash).css({
										'position': 'absolute',
										'top': $(window).scrollTop() + $(window).height() - 50
									});
								}
								if ( sd > settings.min ) 
									$(containerIDhash).fadeIn(settings.inDelay);
								else 
									$(containerIDhash).fadeOut(settings.Outdelay);
							});
					};
				})(jQuery);
		</script>
</body></html>
