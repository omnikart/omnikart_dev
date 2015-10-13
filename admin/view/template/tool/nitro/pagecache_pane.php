<div class="row">
  <div class="col-md-7">
    <div class="box-heading"><h1>Page Cache Settings</h1></div>
    <div class="box-content">
    <table class="form pagecache">
      <tr>
        <td>Page Cache Status<span class="help">Enables caching of the rendered HTML of your site on the first page load. The subsequent requests will be served directly from the cached HTML.</span></td>
        <td>
          <select name="Nitro[PageCache][Enabled]" class="form-control NitroPageCacheEnabled">
            <option value="yes" <?php echo( (!empty($data['Nitro']['PageCache']['Enabled']) && $data['Nitro']['PageCache']['Enabled'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
            <option value="no" <?php echo (empty($data['Nitro']['PageCache']['Enabled']) || $data['Nitro']['PageCache']['Enabled'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>Expire Time (seconds)<span class="help">If the cache files get older than this time, they will be re-cached automatically. <strong>Default: </strong>86400</span></span></td>
        <td>
    		<input class="form-control" name="Nitro[PageCache][ExpireTime]" type="text" value="<?php echo (!empty($data['Nitro']['PageCache']['ExpireTime'])) ? $data['Nitro']['PageCache']['ExpireTime'] : NITRO_PAGECACHE_TIME ?>" />
        </td>
      </tr>
      <tr>
        <td>Add width/height attributes to images<span class="help">Enabling this help for faster image rendering by your browser. Note: This option is not compatible with some OpenCart themes. If your images get stretched because of this option, it is recommended to disable it.</span></td>
        <td>
        <select class="form-control" name="Nitro[PageCache][AddWHImageAttributes]">
            <option value="yes" <?php echo( (!empty($data['Nitro']['PageCache']['AddWHImageAttributes']) && $data['Nitro']['PageCache']['AddWHImageAttributes'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
            <option value="no" <?php echo (empty($data['Nitro']['PageCache']['AddWHImageAttributes']) || $data['Nitro']['PageCache']['AddWHImageAttributes'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
        </select>
        </td>
      </tr>
      <tr>
        <td>Clear cache on product edit<span class="help">When enabled, the PageCache for a specific product will get cleared on one of the following conditions:<br />- after you modify this product from the admin panel<br />- after a customer purchases this product<br />- after you edit an order containing the product</span></td>
        <td>
        <select class="form-control" name="Nitro[PageCache][ClearCacheOnProductEdit]">
            <option value="yes" <?php echo( (!empty($data['Nitro']['PageCache']['ClearCacheOnProductEdit']) && $data['Nitro']['PageCache']['ClearCacheOnProductEdit'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
            <option value="no" <?php echo (empty($data['Nitro']['PageCache']['ClearCacheOnProductEdit']) || $data['Nitro']['PageCache']['ClearCacheOnProductEdit'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
        </select>
        </td>
      </tr>
      <tr>
        <td style="vertical-align:top;">Ignored Routes<span class="help">Routes (e.g. common/home) to be ignored from the page cache. One route per line.</span></td>
        <td>
    		<textarea class="form-control" name="Nitro[PageCache][IgnoredRoutes]" style="width:400px; height:180px;" placeholder="One route per line, e.g. information/sitemap"><?php echo (!empty($data['Nitro']['PageCache']['IgnoredRoutes'])) ? $data['Nitro']['PageCache']['IgnoredRoutes'] : '' ?></textarea>
        </td>
      </tr>
      <tr>
        <td>Store Front Widget<span class="help">This is a small stripe in the very bottom of your website showing useful data. Allows to clear the cache for the current page from the front end.</span></td>
        <td>
        <select name="Nitro[PageCache][StoreFrontWidget]" class="form-control NitroPageCacheStoreFrontWidget">
            <option value="showOnlyWhenAdminIsLogged" <?php echo( (!empty($data['Nitro']['PageCache']['StoreFrontWidget']) && $data['Nitro']['PageCache']['StoreFrontWidget'] == 'showOnlyWhenAdminIsLogged')) ? 'selected=selected' : ''?>>Show Only When Admin is Logged In</option>
            <option value="showAlways" <?php echo ( (!empty($data['Nitro']['PageCache']['StoreFrontWidget']) && $data['Nitro']['PageCache']['StoreFrontWidget'] == 'showAlways')) ? 'selected=selected' : ''?>>Show Always</option>
            <option value="showNever" <?php echo ( (!empty($data['Nitro']['PageCache']['StoreFrontWidget']) && $data['Nitro']['PageCache']['StoreFrontWidget'] == 'showNever')) ? 'selected=selected' : ''?>>Show Never</option>
        </select>
        </td>
      </tr>
    </table> 
    </div>          
  </div>
  <div class="col-md-5">
    <div class="box-heading"><h1>Manually Pre-Cache pages</h1></div>
    <div class="box-content">
      <p>
        NitroPack PageCache is a fast render output cache mechanism that serves already processed content directly to your visitors. By default, the cache is created on the first page visit by the first visitor of the store, and on this very first visit by the first visitor, the page is not load from cache. After that all other visitors will get this page loaded from the cache.
      </p>
      <p>
        Pre-caching will do these initial requests to all links in your standard OpenCart sitemap. These include: home page, categories (up to level 3), information pages, special offers.
      </p>
      <p>
      <strong>Note: </strong> This pre-caching tool is an optional way to simulate the first-visitor of your store to create the cache. It is not required to run this tool after each clear cache. It will just make the cache serving to the first visitor of your store possible, since the cache will be already created for her/him.
      </p>

      <div class="spacer10">
        <a id="precache_start" class="btn btn-primary"><i class="icon-hdd"></i> Pre-cache sitemap pages</a>
        <a id="precache_abort" class="btn btn-default"><i class="icon-remove"></i> Abort</a>
      </div>

      <div class="progress spacer10"><div id="precache_progressbar" class="progress-bar" style="width: 0%;"></div></div>

      <p id="precache_details"></p>

      <script type="text/javascript">
        $(document).ready(function() {
          nitro.precache.setConfig({
            stack_url : '../index.php?route=tool/nitro/get_pagecache_stack&token=' + getURLVar('token'),
            progressbar_selector : '#precache_progressbar',
            output_selector : '#precache_details',
            http_header : 'Nitro-Precache'
          });
		  
		  $('#precache_start').click(function() {
			nitro.precache.start();
		  });
		
		  $('#precache_abort').click(function() {
			nitro.precache.abort();
		  });
        });
      </script>
    </div>
  </div>
</div>