
<div id="nitroCacheBar">
	<div id="nitroCacheWrap">
		<form id="clearCacheForm" method="post">
			<input type="hidden" name="cacheFileToClear"
				value="<?php echo $nameOfCacheFile; ?>" />
		</form>
		<img id="nitroCacheGaugeIcon"
			src="data:image/png ;base64,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">
		<span id="nitroCacheStats">NitroCache® served this page in <?php printf('%.16f', $renderTime);?> seconds.<br />
			That was <b><?php echo $faster; ?> times faster</b> than the default
			loading speed.
		</span>
		<div id="nitroCacheClearCache"
			onclick="$('#clearCacheForm').submit();">Clear Cache</div>

	</div>
</div>


<style scoped>
body {
	margin: 0 !important;
	padding: 0 !important;
}

#nitroCacheBar {
	position: fixed !important;
	bottom: 0 !important;
	margin: 0 !important;
	padding: 0 !important;
	background: #272727 !important;
	width: 100% !important;
	left: 0 !important;
	right: auto !important;
	top: auto !important;
	z-index: 9999999 !important;
}

#nitroCacheBar #nitroCacheWrap {
	width: 960px !important;
	margin: 0 auto !important;
	position: relative !important;
	font-size: 0 !important;
}

#nitroCacheGaugeIcon {
	margin: 7px 0 !important;
	vertical-align: top !important;
}

#nitroCacheStats {
	margin: 7px 0 7px 10px !important;
	padding: 0 !important;
	display: inline-block !important;
	line-height: 13px !important;
	font-size: 11px !important;
	font-family: Arial, Helvetica, sans-serif !important;
	color: #fff !important;
	vertical-align: top !important;
}

#nitroCacheStats b {
	font-size: 12px !important;
	font-family: Arial, Helvetica, sans-serif !important;
	color: #add95e !important;
}

#nitroCacheClearCache {
	-moz-border-radius: 2px !important;
	-webkit-border-radius: 2px !important;
	border-radius: 2px !important; /* border radius */
	-moz-background-clip: padding !important;
	-webkit-background-clip: padding-box !important;
	background-clip: padding-box !important;
	/* prevents bg color from leaking outside the border */
	background-color: #11ada7 !important; /* layer fill content */
	-moz-box-shadow: 0 0 5px rgba(0, 0, 0, .75) !important;
	/* drop shadow */
	-webkit-box-shadow: 0 0 5px rgba(0, 0, 0, .75) !important;
	/* drop shadow */
	box-shadow: 0 0 5px rgba(0, 0, 0, .75) !important; /* drop shadow */
	background-image:
		url(data:image/svg+xml !important;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEwMCAxMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjxsaW5lYXJHcmFkaWVudCBpZD0iaGF0MCIgZ3JhZGllbnRVbml0cz0ib2JqZWN0Qm91bmRpbmdCb3giIHgxPSI1MCUiIHkxPSIxMDAlIiB4Mj0iNTAlIiB5Mj0iLTEuNDIxMDg1NDcxNTIwMmUtMTQlIj4KPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iIzg0OTkzOCIgc3RvcC1vcGFjaXR5PSIxIi8+CjxzdG9wIG9mZnNldD0iMTAwJSIgc3RvcC1jb2xvcj0iIzkxYmI0OCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgIDwvbGluZWFyR3JhZGllbnQ+Cgo8cmVjdCB4PSIwIiB5PSIwIiB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0idXJsKCNoYXQwKSIgLz4KPC9zdmc+)
		!important; /* gradient overlay */
	background-image: -moz-linear-gradient(bottom, #849938 0%, #91bb48 100%)
		!important; /* gradient overlay */
	background-image: -o-linear-gradient(bottom, #849938 0%, #91bb48 100%)
		!important; /* gradient overlay */
	background-image: -webkit-linear-gradient(bottom, #849938 0%, #91bb48 100%)
		!important; /* gradient overlay */
	background-image: linear-gradient(bottom, #849938 0%, #91bb48 100%)
		!important; /* gradient overlay */
	color: #283012 !important;
	font-family: Arial, Helvetica, sans-serif !important;
	font-size: 10px !important;
	font-weight: bold !important;
	text-shadow: 0 1px 0 #b0d14d !important; /* drop shadow */
	border: none !important;
	line-height: 22px !important;
	height: 22px !important;
	padding: 0 10px !important;
	display: inline-block !important;
	position: absolute !important;
	top: 10px !important;
	right: 0 !important;
}

#nitroCacheClearCache:hover {
	cursor: pointer !important;
	background-image:
		url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEwMCAxMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjxsaW5lYXJHcmFkaWVudCBpZD0iaGF0MCIgZ3JhZGllbnRVbml0cz0ib2JqZWN0Qm91bmRpbmdCb3giIHgxPSI1MCUiIHkxPSIxMDAlIiB4Mj0iNTAlIiB5Mj0iLTEuNDIxMDg1NDcxNTIwMmUtMTQlIj4KPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iIzkxYmI0OCIgc3RvcC1vcGFjaXR5PSIxIi8+CjxzdG9wIG9mZnNldD0iMTAwJSIgc3RvcC1jb2xvcj0iIzg0OTkzOCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgIDwvbGluZWFyR3JhZGllbnQ+Cgo8cmVjdCB4PSIwIiB5PSIwIiB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0idXJsKCNoYXQwKSIgLz4KPC9zdmc+)
		!important; /* gradient overlay */
	background-image: -moz-linear-gradient(bottom, #91bb48 0%, #849938 100%)
		!important; /* gradient overlay */
	background-image: -o-linear-gradient(bottom, #91bb48 0%, #849938 100%)
		!important; /* gradient overlay */
	background-image: -webkit-linear-gradient(bottom, #91bb48 0%, #849938 100%)
		!important; /* gradient overlay */
	background-image: linear-gradient(bottom, #91bb48 0%, #849938 100%)
		!important; /* gradient overlay */
}

#clearCacheForm {
	margin: 0 !important;
	padding: 0 !important;
}
</style>
