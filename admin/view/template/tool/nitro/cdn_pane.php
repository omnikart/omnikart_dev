<div class="row">
	<div class="col-md-7">
		<div class="box-heading">
			<h1>Generic CDN</h1>
		</div>
		<div class="box-content">
			<table class="form cdnpanetable">
				<tr>
					<td>Generic CDN URL<span class="help">If you are using a custom CDN
							service like MaxCDN, you can insert the Base URL to which your
							images, CSS and JavaScript files will get rewritten.</span></td>
					<td><input class="form-control" type="text"
						name="Nitro[CDNStandard][GenericURL]"
						value="<?php echo(!empty($data['Nitro']['CDNStandard']['GenericURL'])) ? $data['Nitro']['CDNStandard']['GenericURL'] : ''?>" />
					</td>
				</tr>
			</table>
		</div>

		<div class="box-heading">
			<h1>CDN via FTP</h1>
		</div>
		<div class="box-content">
			<table class="form cdnpanetable">
				<tr>
					<td>CDN Service via FTP<span class="help">If you have a remote FTP
							server, you can use it as a CDN server for your content.</span></td>
					<td><select name="Nitro[CDNStandard][Enabled]"
						class="form-control NitroCDNStandard">
							<option value="no"
								<?php echo (empty($data['Nitro']['CDNStandard']['Enabled']) || $data['Nitro']['CDNStandard']['Enabled'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
							<option value="yes"
								<?php echo( (!empty($data['Nitro']['CDNStandard']['Enabled']) && $data['Nitro']['CDNStandard']['Enabled'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
					</select>
						<div class="cdn-error cdn-generic-error">You cannot use the
							Generic CDN with the Amazon CDN or Rackspace CDN. Please enable
							only one.</div></td>
				</tr>
			</table>

			<div class="cdnstandard-tabbable-parent">
				<div class="tabbable tabs-left">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#cdn-standard-images"
							data-toggle="tab">Images</a></li>
						<li><a href="#cdn-standard-css" data-toggle="tab">CSS</a></li>
						<li><a href="#cdn-standard-js" data-toggle="tab">JavaScript</a></li>
					</ul>
					<div class="tab-content">
						<div id="cdn-standard-images" class="tab-pane active">
							<table class="form cdnstandard" style="margin-top: -10px;">
								<tr>
									<td>Images CDN HTTP URL<span class="help">This is the non-SSL
											URL of your CDN server<br />
										<strong>http://&lt;FTP-Server&gt;/</strong>
									</span></td>
									<td><input class="form-control" type="text"
										name="Nitro[CDNStandard][ImagesHttpUrl]"
										value="<?php echo(!empty($data['Nitro']['CDNStandard']['ImagesHttpUrl'])) ? $data['Nitro']['CDNStandard']['ImagesHttpUrl'] : ''?>" />
									</td>
								</tr>
								<tr>
									<td>Images CDN HTTPS URL<span class="help">This is the SSL URL
											of your CDN server<br />
										<strong>https://&lt;FTP-Server&gt;/</strong>
									</span></td>
									<td><input class="form-control" type="text"
										name="Nitro[CDNStandard][ImagesHttpsUrl]"
										value="<?php echo(!empty($data['Nitro']['CDNStandard']['ImagesHttpsUrl'])) ? $data['Nitro']['CDNStandard']['ImagesHttpsUrl'] : ''?>" />
									</td>
								</tr>
								<tr>
									<td>Serve Images from this CDN<span class="help">When enabled,
											the FTP-Synced images will get served from the FTP CDN. Use
											the form on the right to sync your images.</span></td>
									<td><select class="form-control"
										name="Nitro[CDNStandard][ServeImages]">
											<option value="no"
												<?php echo (empty($data['Nitro']['CDNStandard']['ServeImages']) || $data['Nitro']['CDNStandard']['ServeImages'] == 'no') ? 'selected=selected' : ''?>>No</option>
											<option value="yes"
												<?php echo( (!empty($data['Nitro']['CDNStandard']['ServeImages']) && $data['Nitro']['CDNStandard']['ServeImages'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
									</select></td>
								</tr>
							</table>
						</div>
						<div id="cdn-standard-css" class="tab-pane">
							<table class="form cdnstandard" style="margin-top: -10px;">
								<tr>
									<td>CSS CDN HTTP URL<span class="help">This is the non-SSL URL
											of your CDN server<br />
										<strong>http://&lt;FTP-Server&gt;/</strong>
									</span></td>
									<td><input class="form-control" type="text"
										name="Nitro[CDNStandard][CSSHttpUrl]"
										value="<?php echo(!empty($data['Nitro']['CDNStandard']['CSSHttpUrl'])) ? $data['Nitro']['CDNStandard']['CSSHttpUrl'] : ''?>" />
									</td>
								</tr>
								<tr>
									<td>CSS CDN HTTPS URL<span class="help">This is the SSL URL of
											your CDN server<br />
										<strong>https://&lt;FTP-Server&gt;/</strong>
									</span></td>
									<td><input class="form-control" type="text"
										name="Nitro[CDNStandard][CSSHttpsUrl]"
										value="<?php echo(!empty($data['Nitro']['CDNStandard']['CSSHttpsUrl'])) ? $data['Nitro']['CDNStandard']['CSSHttpsUrl'] : ''?>" />
									</td>
								</tr>
								<tr>
									<td>Serve CSS from this CDN<span class="help">When enabled, the
											FTP-Synced CSS files will get served from the FTP CDN. Use
											the form on the right to sync your CSS files.<br />
										<strong>Note:</strong> If you have enabled CSS Minification,
											you will first need to run the Pre-Cache service before
											syncing with the FTP CDN. Go to Cache Systems &gt; Page cache
											&gt; Pre-cache sitemap pages
									</span></td>
									<td><select class="form-control"
										name="Nitro[CDNStandard][ServeCSS]">
											<option value="no"
												<?php echo (empty($data['Nitro']['CDNStandard']['ServeCSS']) || $data['Nitro']['CDNStandard']['ServeCSS'] == 'no') ? 'selected=selected' : ''?>>No</option>
											<option value="yes"
												<?php echo( (!empty($data['Nitro']['CDNStandard']['ServeCSS']) && $data['Nitro']['CDNStandard']['ServeCSS'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
									</select></td>
								</tr>
							</table>
						</div>
						<div id="cdn-standard-js" class="tab-pane">
							<table class="form cdnstandard" style="margin-top: -10px;">
								<tr>
									<td>JavaScript CDN HTTP URL<span class="help">This is the
											non-SSL URL of your CDN server<br />
										<strong>http://&lt;FTP-Server&gt;/</strong>
									</span></td>
									<td><input class="form-control" type="text"
										name="Nitro[CDNStandard][JavaScriptHttpUrl]"
										value="<?php echo(!empty($data['Nitro']['CDNStandard']['JavaScriptHttpUrl'])) ? $data['Nitro']['CDNStandard']['JavaScriptHttpUrl'] : ''?>" />
									</td>
								</tr>
								<tr>
									<td>JavaScript CDN HTTPS URL<span class="help">This is the SSL
											URL of your CDN server<br />
										<strong>https://&lt;FTP-Server&gt;/</strong>
									</span></td>
									<td><input class="form-control" type="text"
										name="Nitro[CDNStandard][JavaScriptHttpsUrl]"
										value="<?php echo(!empty($data['Nitro']['CDNStandard']['JavaScriptHttpsUrl'])) ? $data['Nitro']['CDNStandard']['JavaScriptHttpsUrl'] : ''?>" />
									</td>
								</tr>
								<tr>
									<td>Serve JavaScript from this CDN<span class="help">When
											enabled, the FTP-Synced JavaScript files will get served from
											the FTP CDN. Use the form on the right to sync your
											JavaScript files.<br />
										<strong>Note:</strong> If you have enabled JavaScript
											Minification, you will first need to run the Pre-Cache
											service before syncing with the FTP CDN. Go to Cache Systems
											&gt; Page cache &gt; Pre-cache sitemap pages
									</span></td>
									<td><select class="form-control"
										name="Nitro[CDNStandard][ServeJavaScript]">
											<option value="no"
												<?php echo (empty($data['Nitro']['CDNStandard']['ServeJavaScript']) || $data['Nitro']['CDNStandard']['ServeJavaScript'] == 'no') ? 'selected=selected' : ''?>>No</option>
											<option value="yes"
												<?php echo( (!empty($data['Nitro']['CDNStandard']['ServeJavaScript']) && $data['Nitro']['CDNStandard']['ServeJavaScript'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
									</select></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="cdnstandard-tabbable-parent">
			<div class="box-heading cdnstandard-ftp-tabbable-parent">
				<h1>
					<i class="icon-globe"></i>FTP Connection
				</h1>
			</div>
			<div class="box-content cdnstandard-ftp-tabbable-parent"
				style="min-height: 100px; line-height: 20px;">
				<div class="ftp-alert-info alert alert-info"><?php echo !empty($data['Nitro']['CDNStandardFTP']['LastUpload']) ? 'Last synchronization was on ' . $data['Nitro']['CDNStandardFTP']['LastUpload'] : 'No synchornization has been carried out yet.'; ?></div>
				<input class="form-control" type="hidden"
					name="Nitro[CDNStandardFTP][LastUpload]"
					value="<?php echo !empty($data['Nitro']['CDNStandardFTP']['LastUpload']) ? $data['Nitro']['CDNStandardFTP']['LastUpload'] : ''; ?>" />
				<div class="ftp-alert alert alert-danger"></div>
				<div class="ftp-alert alert alert-success"></div>
				<table class="form">
					<tr class="cdnstandard-tabbable-parent">
						<td>Protocol<span class="help">Note: Some servers may not support
								FTPS.</span></td>
						<td><select class="form-control"
							name="Nitro[CDNStandardFTP][Protocol]">
								<option value="ftp"
									<?php echo (empty($data['Nitro']['CDNStandardFTP']['Protocol']) || $data['Nitro']['CDNStandardFTP']['Protocol'] == 'ftp') ? 'selected=selected' : ''?>>FTP</option>
								<option value="ftps"
									<?php echo( (!empty($data['Nitro']['CDNStandardFTP']['Protocol']) && $data['Nitro']['CDNStandardFTP']['Protocol'] == 'ftps')) ? 'selected=selected' : ''?>>FTPS</option>
						</select></td>
					</tr>
					<tr>
						<td>FTP Host</td>
						<td><input class="form-control" type="text"
							name="Nitro[CDNStandardFTP][Host]"
							value="<?php echo !empty($data['Nitro']['CDNStandardFTP']['Host']) ? $data['Nitro']['CDNStandardFTP']['Host'] : ''; ?>" />
						</td>
					</tr>
					<tr>
						<td>FTP Port<span class="help">If left empty, 21 will be
								considered as default.</span></td>
						<td><input class="form-control" type="text"
							name="Nitro[CDNStandardFTP][Port]"
							value="<?php echo !empty($data['Nitro']['CDNStandardFTP']['Port']) ? $data['Nitro']['CDNStandardFTP']['Port'] : ''; ?>" />
						</td>
					</tr>
					<tr>
						<td>FTP Username<span class="help">This value will be hidden for
								users without modify access.</span></td>
						<td><input class="form-control" type="text"
							name="Nitro[CDNStandardFTP][Username]"
							value="<?php echo !empty($data['Nitro']['CDNStandardFTP']['Username']) ? $data['Nitro']['CDNStandardFTP']['Username'] : ''; ?>" />
						</td>
					</tr>
					<tr>
						<td>FTP Password<span class="help">This value will be hidden for
								users without modify access.</span></td>
						<td><input class="form-control" type="password"
							name="Nitro[CDNStandardFTP][Password]"
							value="<?php echo !empty($data['Nitro']['CDNStandardFTP']['Password']) ? $data['Nitro']['CDNStandardFTP']['Password'] : ''; ?>" />
						</td>
					</tr>
					<tr>
						<td>FTP Root<span class="help">The directory your content is
								stored in. Normally 'public_html/'.</span></td>
						<td><input class="form-control" type="text"
							name="Nitro[CDNStandardFTP][Root]"
							value="<?php echo !empty($data['Nitro']['CDNStandardFTP']['Root']) ? $data['Nitro']['CDNStandardFTP']['Root'] : ''; ?>" />
						</td>
					</tr>
					<tr>
						<td colspan="2"><label class="ftp-upload-label"><input
								class="form-control" type="checkbox" value="1"
								name="Nitro[CDNStandardFTP][SyncImages]"
								<?php if (!empty($data['Nitro']['CDNStandardFTP']['SyncImages'])) echo 'checked="checked"'; ?> />
								Upload all local images to CDN via FTP</label> <label
							class="ftp-upload-label"><input class="form-control"
								type="checkbox" value="1" name="Nitro[CDNStandardFTP][SyncCSS]"
								<?php if (!empty($data['Nitro']['CDNStandardFTP']['SyncCSS'])) echo 'checked="checked"'; ?> />
								Upload all local CSS files to CDN via FTP</label> <label
							class="ftp-upload-label"><input class="form-control"
								type="checkbox" value="1"
								name="Nitro[CDNStandardFTP][SyncJavaScript]"
								<?php if (!empty($data['Nitro']['CDNStandardFTP']['SyncJavaScript'])) echo 'checked="checked"'; ?> />
								Upload all local JavaScript files to CDN via FTP</label></td>
					</tr>
					<tr>
						<td colspan="2"><a class="ftp-upload btn btn-primary"><i
								class="icon-circle-arrow-up icon-white"></i> <span
								class="ftp-button-text">Upload to CDN Server</span></a> <a
							class="ftp-cancel btn btn-default"><i
								class="icon-remove icon-white"></i> <span
								class="ftp-cancel-text">Abort</span></a>
							<div class="progress active ftp-progress">
								<div class="bar bar-primary" style="width: 0%;"></div>
							</div>

							<div class="empty-div"></div>
							<div class="ftp-log"></div></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="box-heading">
			<h1>
				<i class="icon-info-sign"></i>Benefits of CDN
			</h1>
		</div>
		<div class="box-content" style="min-height: 100px; line-height: 20px;">
			<p>CDN (Content Delivery Network) is a web service used for global
				content delivery. When you integrate with CDN, your content gets
				copied on all the CDN servers around the world. Therefore, when a
				visitor tries to access your webstore, content is delivered from the
				nearest server geographically located to the visitor instead of your
				hosting server which may be on the other part of the globe. This
				makes your webstore load faster and improves overall speed.</p>
		</div>
	</div>
</div>

<style>
.cdnstandard {
	
}
</style>

<script type="text/javascript">

$(document).ready(function() {
	var showCDNForm = function() {
		if ($('.NitroCDNStandard').val() == 'yes') {
			$('.cdnstandard-tabbable-parent').fadeIn();
		} else {
			$('.cdnstandard-tabbable-parent').hide();
		}
	}
	
	$('.NitroCDNStandard').change(function() {
		$('.cdn-error').hide();
		if ($('.NitroCDNAmazon').val() == 'yes' || $('.NitroCDNRackspace').val() == 'yes') {
			$('.cdn-error.cdn-generic-error').fadeIn();
			$('.NitroCDNStandard').val('no');
		} else {
			$('.cdn-error.cdn-generic-error').hide();
			showCDNForm();
		}
	});
	
	showCDNForm();
	
  $('.ftp-upload').click(function() {
    nitro.cdn.setConfig({
      message_selector : '.ftp-log',
      progressbar_selector : '.ftp-progress div',
      success_wrapper : '<div class="alert alert-success">{MESSAGE}</div>',
      error_wrapper : '<div class="alert alert-danger">{MESSAGE}</div>',
      url : 'index.php?route=tool/nitro/cdn&token=' + getURLVar('token'),
      cdn : 'ftp',
      form_fields : {
        Nitro : {
          CDNRackspace : {
            Enabled : $('select[name="Nitro[CDNRackspace][Enabled]"]').val()
          },
          CDNAmazon : {
            Enabled : $('select[name="Nitro[CDNAmazon][Enabled]"]').val()
          },
          CDNStandard : {
            Enabled : $('select[name="Nitro[CDNStandard][Enabled]"]').val(),
            ImagesHttpUrl : $('input[name="Nitro[CDNStandard][ImagesHttpUrl]"]').val(),
            ImagesHttpsUrl : $('input[name="Nitro[CDNStandard][ImagesHttpsUrl]"]').val(),
            ServeImages : $('select[name="Nitro[CDNStandard][ServeImages]"]').val(),
            CSSHttpUrl : $('input[name="Nitro[CDNStandard][CSSHttpUrl]"]').val(),
            CSSHttpsUrl : $('input[name="Nitro[CDNStandard][CSSHttpsUrl]"]').val(),
            ServeCSS : $('select[name="Nitro[CDNStandard][ServeCSS]"]').val(),
            JavaScriptHttpUrl : $('input[name="Nitro[CDNStandard][JavaScriptHttpUrl]"]').val(),
            JavaScriptHttpsUrl : $('input[name="Nitro[CDNStandard][JavaScriptHttpsUrl]"]').val(),
            ServeJavaScript : $('select[name="Nitro[CDNStandard][ServeJavaScript]"]').val()
          },
          CDNStandardFTP : {
            Protocol : $('select[name="Nitro[CDNStandardFTP][Protocol]"]').val(),
            Host : $('input[name="Nitro[CDNStandardFTP][Host]"]').val(),
            Port : $('input[name="Nitro[CDNStandardFTP][Port]"]').val(),
            Username : $('input[name="Nitro[CDNStandardFTP][Username]"]').val(),
            Password : $('input[name="Nitro[CDNStandardFTP][Password]"]').val(),
            Root : $('input[name="Nitro[CDNStandardFTP][Root]"]').val(),
            SyncImages : $('input[name="Nitro[CDNStandardFTP][SyncImages]"]').is(":checked") ? '1' : '',
            SyncCSS : $('input[name="Nitro[CDNStandardFTP][SyncCSS]"]').is(":checked") ? '1' : '',
            SyncJavaScript : $('input[name="Nitro[CDNStandardFTP][SyncJavaScript]"]').is(":checked") ? '1' : '',
            LastUpload : $('input[name="Nitro[CDNStandardFTP][LastUpload]"]').val()
          }
        }
      }
    });

    nitro.cdn.start();
  });

  $('.ftp-cancel').click(function() {
    nitro.cdn.abort();
  });
});

</script>