<div class="row">
	<div class="col-md-7">
    <div class="box-heading"><h1>Rackspace CDN Service</h1></div>
    <div class="box-content">
      <table class="form cdnpanetable">
        <tr>
          <td>Rackspace CDN Service<span class="help">Enable/Disable Rackspace CDN.</span></td>
          <td>
          <select name="Nitro[CDNRackspace][Enabled]" class="form-control NitroCDNRackspace">
              <option value="no" <?php echo (empty($data['Nitro']['CDNRackspace']['Enabled']) || $data['Nitro']['CDNRackspace']['Enabled'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
              <option value="yes" <?php echo( (!empty($data['Nitro']['CDNRackspace']['Enabled']) && $data['Nitro']['CDNRackspace']['Enabled'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
          </select>
          <div class="cdn-error rackspace-error">You cannot use Rackspace CDN with the Generic CDN or Amazon CDN. Please enable only one.</div>
          </td>
        </tr>
      </table>
    
    <div class="CDNRackspace-tabbable-parent">
    <div class="tabbable tabs-left"> 
          <ul class="nav nav-tabs">
            <li class="active"><a href="#cdn-rackspace-images" data-toggle="tab">Images</a></li>
            <li><a href="#cdn-rackspace-css" data-toggle="tab">CSS</a></li>
            <li><a href="#cdn-rackspace-js" data-toggle="tab">JavaScript</a></li>
          </ul>
         <div class="tab-content">
         	<div id="cdn-rackspace-images" class="tab-pane active">
                <table class="form cdnrackspace" style="margin-top:-10px;">
                  <tr>
                    <td>Images CDN HTTP URL<span class="help">This is the non-SSL URL of your Rackspace CDN server<br /><strong>http://&lt;CDN-Server&gt;/</strong></span></td>
                    <td>
                        <input class="form-control" type="text" name="Nitro[CDNRackspace][ImagesHttpUrl]" value="<?php echo(!empty($data['Nitro']['CDNRackspace']['ImagesHttpUrl'])) ? $data['Nitro']['CDNRackspace']['ImagesHttpUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Images CDN HTTPS URL<span class="help">This is the SSL URL of your CDN server<br /><strong>https://&lt;CDN-Server&gt;/</strong></span></td>
                    <td>
                        <input class="form-control" type="text" name="Nitro[CDNRackspace][ImagesHttpsUrl]" value="<?php echo(!empty($data['Nitro']['CDNRackspace']['ImagesHttpsUrl'])) ? $data['Nitro']['CDNRackspace']['ImagesHttpsUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Rackspace Images Container<span class="help">This is the Rackspace container of your images</span></td>
                    <td>
                        <input class="form-control" type="text" name="Nitro[CDNRackspace][ImagesContainer]" value="<?php echo(!empty($data['Nitro']['CDNRackspace']['ImagesContainer'])) ? $data['Nitro']['CDNRackspace']['ImagesContainer'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Serve Images from this CDN<span class="help">When enabled, the Synced Images files will get served from the Rackspace CDN. Use the form on the right to sync your Images.</span></td>
                    <td>
                    <select class="form-control" name="Nitro[CDNRackspace][ServeImages]">
                        <option value="no" <?php echo (empty($data['Nitro']['CDNRackspace']['ServeImages']) || $data['Nitro']['CDNRackspace']['ServeImages'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['CDNRackspace']['ServeImages']) && $data['Nitro']['CDNRackspace']['ServeImages'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                </table> 
            </div>
         	<div id="cdn-rackspace-css" class="tab-pane">
                <table class="form cdnrackspace" style="margin-top:-10px;">
                  <tr>
                    <td>CSS CDN HTTP URL<span class="help">This is the non-SSL URL of your CDN server<br /><strong>http://&lt;CDN-Server&gt;/</strong></span></td>
                    <td>
                        <input class="form-control" type="text" name="Nitro[CDNRackspace][CSSHttpUrl]" value="<?php echo(!empty($data['Nitro']['CDNRackspace']['CSSHttpUrl'])) ? $data['Nitro']['CDNRackspace']['CSSHttpUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>CSS CDN HTTPS URL<span class="help">This is the SSL URL of your CDN server<br /><strong>https://&lt;CDN-Server&gt;/</strong></span></td>
                    <td>
                        <input class="form-control" type="text" name="Nitro[CDNRackspace][CSSHttpsUrl]" value="<?php echo(!empty($data['Nitro']['CDNRackspace']['CSSHttpsUrl'])) ? $data['Nitro']['CDNRackspace']['CSSHttpsUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Rackspace CSS Container<span class="help">This is the Rackspace container of your CSS files</span></td>
                    <td>
                        <input class="form-control" type="text" name="Nitro[CDNRackspace][CSSContainer]" value="<?php echo(!empty($data['Nitro']['CDNRackspace']['CSSContainer'])) ? $data['Nitro']['CDNRackspace']['CSSContainer'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Serve CSS from this CDN<span class="help">When enabled, the Synced CSS files will get served from the Rackspace CDN. Use the form on the right to sync your CSS files.<br /><strong>Note:</strong> If you have enabled CSS Minification, you will first need to run the Pre-Cache service before syncing with the Rackspace CDN. Go to Cache Systems &gt; Page cache &gt; Pre-cache sitemap pages</span></td>
                    <td>
                    <select class="form-control" name="Nitro[CDNRackspace][ServeCSS]">
                        <option value="no" <?php echo (empty($data['Nitro']['CDNRackspace']['ServeCSS']) || $data['Nitro']['CDNRackspace']['ServeCSS'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['CDNRackspace']['ServeCSS']) && $data['Nitro']['CDNRackspace']['ServeCSS'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                </table> 
            </div>
         	<div id="cdn-rackspace-js" class="tab-pane">
                <table class="form cdnrackspace" style="margin-top:-10px;">
                  <tr>
                    <td>JavaScript CDN HTTP URL<span class="help">This is the non-SSL URL of your CDN server<br /><strong>http://&lt;CDN-Server&gt;/</strong></span></td>
                    <td>
                        <input class="form-control" type="text" name="Nitro[CDNRackspace][JavaScriptHttpUrl]" value="<?php echo(!empty($data['Nitro']['CDNRackspace']['JavaScriptHttpUrl'])) ? $data['Nitro']['CDNRackspace']['JavaScriptHttpUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>JavaScript CDN HTTPS URL<span class="help">This is the SSL URL of your CDN server<br /><strong>https://&lt;CDN-Server&gt;/</strong></span></td>
                    <td>
                        <input class="form-control" type="text" name="Nitro[CDNRackspace][JavaScriptHttpsUrl]" value="<?php echo(!empty($data['Nitro']['CDNRackspace']['JavaScriptHttpsUrl'])) ? $data['Nitro']['CDNRackspace']['JavaScriptHttpsUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Rackspace JavaScript Container<span class="help">This is the Rackspace container of your JavaScript files</span></td>
                    <td>
                        <input class="form-control" type="text" name="Nitro[CDNRackspace][JavaScriptContainer]" value="<?php echo(!empty($data['Nitro']['CDNRackspace']['JavaScriptContainer'])) ? $data['Nitro']['CDNRackspace']['JavaScriptContainer'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Serve JavaScript from this CDN<span class="help">When enabled, the Synced JavaScript files will get served from the Rackspace CDN. Use the form on the right to sync your JavaScript files.<br /><strong>Note:</strong> If you have enabled JavaScript Minification, you will first need to run the Pre-Cache service before syncing with the Rackspace CDN. Go to Cache Systems &gt; Page cache &gt; Pre-cache sitemap pages</span></td>
                    <td>
                    <select class="form-control" name="Nitro[CDNRackspace][ServeJavaScript]">
                        <option value="no" <?php echo (empty($data['Nitro']['CDNRackspace']['ServeJavaScript']) || $data['Nitro']['CDNRackspace']['ServeJavaScript'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['CDNRackspace']['ServeJavaScript']) && $data['Nitro']['CDNRackspace']['ServeJavaScript'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                </table> 
            </div>
         </div>
    </div>
</div>
    </div>
    </div>
    <div class="col-md-5">
    	<div class="CDNRackspace-tabbable-parent">
            <div class="box-heading"><h1><i class="icon-globe"></i>Synchronize with Rackspace CDN</h1></div>
            <div class="box-content" style="min-height:100px; line-height:20px;">
            	<div class="rackspace-alert-info alert alert-info"><?php echo !empty($data['Nitro']['CDNRackspace']['LastUpload']) ? 'Last synchronization was on ' . $data['Nitro']['CDNRackspace']['LastUpload'] : 'No synchornization has been carried out yet.'; ?></div>
                <input class="form-control" type="hidden" name="Nitro[CDNRackspace][LastUpload]" value="<?php echo !empty($data['Nitro']['CDNRackspace']['LastUpload']) ? $data['Nitro']['CDNRackspace']['LastUpload'] : ''; ?>" />
            	<div class="rackspace-alert alert alert-error"></div>
                <div class="rackspace-alert alert alert-success"></div>
                <table class="form">
                  <tr>
                    <td>Rackspace Username</td>
                    <td>
                    <input class="form-control" type="text" name="Nitro[CDNRackspace][Username]" value="<?php echo !empty($data['Nitro']['CDNRackspace']['Username']) ? $data['Nitro']['CDNRackspace']['Username'] : ''; ?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>API Key</td>
                    <td>
                    <input class="form-control" type="text" name="Nitro[CDNRackspace][APIKey]" value="<?php echo !empty($data['Nitro']['CDNRackspace']['APIKey']) ? $data['Nitro']['CDNRackspace']['APIKey'] : ''; ?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Server Region</td>
                    <td>
                    	<select class="form-control" name="Nitro[CDNRackspace][ServerRegion]">
                        	<option value="DFW" <?php if (!empty($data['Nitro']['CDNRackspace']['ServerRegion']) && $data['Nitro']['CDNRackspace']['ServerRegion'] == 'DFW') echo 'selected="selected"'; ?>>Dallas (DFW)</option>
                            <option value="ORD"<?php if (!empty($data['Nitro']['CDNRackspace']['ServerRegion']) && $data['Nitro']['CDNRackspace']['ServerRegion'] == 'ORD') echo 'selected="selected"'; ?>>Chicago (ORD)</option>
                            <option value="LON"<?php if (!empty($data['Nitro']['CDNRackspace']['ServerRegion']) && $data['Nitro']['CDNRackspace']['ServerRegion'] == 'LON') echo 'selected="selected"'; ?>>London (LON)</option>
                            <option value="SYD"<?php if (!empty($data['Nitro']['CDNRackspace']['ServerRegion']) && $data['Nitro']['CDNRackspace']['ServerRegion'] == 'SYD') echo 'selected="selected"'; ?>>Sydney (SYD)</option>
                        </select>
                    
                    </td>
                  </tr>
                  <tr>
                  	<td colspan="2">
                    	<label class="rackspace-upload-label"><input class="form-control" type="checkbox" value="1" name="Nitro[CDNRackspace][SyncImages]" <?php if (!empty($data['Nitro']['CDNRackspace']['SyncImages'])) echo 'checked="checked"'; ?>/> Upload all local images to Rackspace CDN</label>
                        <label class="rackspace-upload-label"><input class="form-control" type="checkbox" value="1" name="Nitro[CDNRackspace][SyncCSS]" <?php if (!empty($data['Nitro']['CDNRackspace']['SyncCSS'])) echo 'checked="checked"'; ?>/> Upload all local CSS files to Rackspace CDN</label>
                        <label class="rackspace-upload-label"><input class="form-control" type="checkbox" value="1" name="Nitro[CDNRackspace][SyncJavaScript]" <?php if (!empty($data['Nitro']['CDNRackspace']['SyncJavaScript'])) echo 'checked="checked"'; ?>/> Upload all local JavaScript files to Rackspace CDN</label>
                    </td>
                  </tr>
                  <tr>
                  	<td colspan="2">
                    	<a class="rackspace-upload btn btn-primary"><i class="icon-circle-arrow-up icon-white"></i> <span class="rackspace-button-text">Sync with Rackspace CDN</span></a>
                        <a class="rackspace-cancel btn btn-default"><i class="icon-remove icon-white"></i> <span class="rackspace-cancel-text">Abort</span></a>
                        <div class="progress active rackspace-progress">
                          <div class="bar bar-primary" style="width: 0%;"></div>
                        </div>
                      
                        <div class="empty-rackspace-div"></div>
                        <div class="rackspace-log">
                        </div>
                    </td>
                  </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.CDNRackspace {
		
}

</style>

<script type="text/javascript">

$(document).ready(function() {
	$('.cdn-error').hide();
	var showCDNForm = function() {
		if ($('.NitroCDNRackspace').val() == 'yes') {
			$('.CDNRackspace-tabbable-parent').fadeIn();
		} else {
			$('.CDNRackspace-tabbable-parent').hide();
		}
	}
	
	$('.NitroCDNRackspace').change(function() {
		$('.cdn-error').hide();
		if ($('.NitroCDNAmazon').val() == 'yes' || $('.NitroCDNStandard').val() == 'yes') {
			$('.cdn-error.rackspace-error').fadeIn();
			$('.NitroCDNRackspace').val('no');
		} else {
			$('.cdn-error.rackspace-error').hide();
			showCDNForm();
		}
	});
	
	showCDNForm();
	
  $('.rackspace-upload').click(function() {
    nitro.cdn.setConfig({
      message_selector : '.rackspace-log',
      progressbar_selector : '.rackspace-progress div',
      success_wrapper : '<div class="alert alert-success">{MESSAGE}</div>',
      error_wrapper : '<div class="alert alert-error">{MESSAGE}</div>',
      url : 'index.php?route=tool/nitro/cdn&token=' + getURLVar('token'),
      cdn : 'rackspace',
      form_fields : {
        Nitro : {
          CDNStandard : {
            Enabled : $('select[name="Nitro[CDNStandard][Enabled]"]').val()
          },
          CDNAmazon : {
            Enabled : $('select[name="Nitro[CDNAmazon][Enabled]"]').val()
          },
          CDNRackspace : {
            Enabled : $('select[name="Nitro[CDNRackspace][Enabled]"]').val(),
            ImagesContainer : $('input[name="Nitro[CDNRackspace][ImagesContainer]"]').val(),
            ImagesHttpUrl : $('input[name="Nitro[CDNRackspace][ImagesHttpUrl]"]').val(),
            ImagesHttpsUrl : $('input[name="Nitro[CDNRackspace][ImagesHttpsUrl]"]').val(),
            ServeImages : $('select[name="Nitro[CDNRackspace][ServeImages]"]').val(),
            CSSContainer : $('input[name="Nitro[CDNRackspace][CSSContainer]"]').val(),
            CSSHttpUrl : $('input[name="Nitro[CDNRackspace][CSSHttpUrl]"]').val(),
            CSSHttpsUrl : $('input[name="Nitro[CDNRackspace][CSSHttpsUrl]"]').val(),
            ServeCSS : $('select[name="Nitro[CDNRackspace][ServeCSS]"]').val(),
            JavaScriptContainer : $('input[name="Nitro[CDNRackspace][JavaScriptContainer]"]').val(),
            JavaScriptHttpUrl : $('input[name="Nitro[CDNRackspace][JavaScriptHttpUrl]"]').val(),
            JavaScriptHttpsUrl : $('input[name="Nitro[CDNRackspace][JavaScriptHttpsUrl]"]').val(),
            ServeJavaScript : $('select[name="Nitro[CDNRackspace][ServeJavaScript]"]').val(),
            Username : $('input[name="Nitro[CDNRackspace][Username]"]').val(),
            APIKey : $('input[name="Nitro[CDNRackspace][APIKey]"]').val(),
            ServerRegion : $('select[name="Nitro[CDNRackspace][ServerRegion]"]').val(),
            SyncImages : $('input[name="Nitro[CDNRackspace][SyncImages]"]').is(":checked") ? '1' : '',
            SyncCSS : $('input[name="Nitro[CDNRackspace][SyncCSS]"]').is(":checked") ? '1' : '',
            SyncJavaScript : $('input[name="Nitro[CDNRackspace][SyncJavaScript]"]').is(":checked") ? '1' : '',
            LastUpload : $('input[name="Nitro[CDNRackspace][LastUpload]"]').val()
          }
        }
      }
    });

    nitro.cdn.start();
  });

  $('.rackspace-cancel').click(function() {
    nitro.cdn.abort();
  });
});

</script>