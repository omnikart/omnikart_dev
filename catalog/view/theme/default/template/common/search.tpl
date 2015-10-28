<div id="search" class="input-group">
  <input type="text" name="search" value="<?php echo $search; ?>" placeholder="<?php echo $text_search; ?>" class="form-control input-lg search-autocomplete" />
  <span class="input-group-btn">
    <button type="button" class="btn btn-default btn-lg"><i class="fa fa-search"></i></button>
  </span>
</div>
<div id="result-search-autocomplete" class="result-search-autocomplete">
	<ul class="show-result">
	</ul>
</div>
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
				  	html += '<button type="button" class="btn tagdattruoc"><?php echo $button_cart; ?></button>';
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
