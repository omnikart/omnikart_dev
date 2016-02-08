<?php
if (function_exists ( 'passesPageCacheValidation' ) && (passesPageCacheValidation () || isPreCacheRequest ()) && decideToShowFrontWidget ()) {
	?>
<script type="text/javascript">
var xhr;
if (window.XMLHttpRequest){xhr=new XMLHttpRequest();}
else{xhr=new ActiveXObject("Microsoft.XMLHTTP");}
xhr.onreadystatechange=function(){if (xhr.readyState==4 && xhr.status==200){$('body').append(xhr.responseText);}}
xhr.open("GET","index.php?route=tool/nitro/getwidget",true);
xhr.send();
</script>
<?php } ?>