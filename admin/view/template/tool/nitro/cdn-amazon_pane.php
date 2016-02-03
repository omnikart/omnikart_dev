<div class="row">
	<div class="col-md-7">
		<div class="box-heading">
			<h1>Amazon CloudFront/S3 CDN Service</h1>
		</div>
		<div class="box-content">
			<table class="form cdnpanetable">
				<tr>
					<td>Amazon CloudFront/S3 CDN Service<span class="help">Enable/Disable
							Amazon CloudFront/S3 CDN.</span></td>
					<td><select name="Nitro[CDNAmazon][Enabled]"
						class="form-control NitroCDNAmazon">
							<option value="no"
								<?php echo (empty($data['Nitro']['CDNAmazon']['Enabled']) || $data['Nitro']['CDNAmazon']['Enabled'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
							<option value="yes"
								<?php echo( (!empty($data['Nitro']['CDNAmazon']['Enabled']) && $data['Nitro']['CDNAmazon']['Enabled'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
					</select>
						<div class="cdn-error amazon-error">You cannot use Amazon CDN with
							the Generic CDN or Rackspace CDN. Please enable only one.</div></td>
				</tr>
			</table>

			<div class="CDNAmazon-tabbable-parent">
				<div class="tabbable tabs-left">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#cdn-amazon-images" data-toggle="tab">Images</a></li>
						<li><a href="#cdn-amazon-css" data-toggle="tab">CSS</a></li>
						<li><a href="#cdn-amazon-js" data-toggle="tab">JavaScript</a></li>
					</ul>
					<div class="tab-content">
						<div id="cdn-amazon-images" class="tab-pane active">
							<table class="form cdnamazon" style="margin-top: -10px;">
								<tr>
									<td>Image Bucket Name<span class="help">This should be a valid
											Amazon S3 bucket. You can create one in <a target="_blank"
											href="https://console.aws.amazon.com/?nc2=h_m_mc">Amazon
												Management Console</a>
									</span></td>
									<td><input class="form-control" type="text"
										name="Nitro[CDNAmazon][ImageBucket]"
										value="<?php echo !empty($data['Nitro']['CDNAmazon']['ImageBucket']) ? $data['Nitro']['CDNAmazon']['ImageBucket'] : ''; ?>" />
									</td>
								</tr>
								<tr>
									<td>Amazon CloudFront/S3 CDN HTTP URL<span class="help">Use the
											bucket Domain Name from <a target="_blank"
											href="https://console.aws.amazon.com/?nc2=h_m_mc">Amazon
												CloudFront</a> in the following way:<br />
										<strong>http://&lt;Bucket-Domain-Name&gt;/</strong>
									</span></td>
									<td><input class="form-control" type="text"
										name="Nitro[CDNAmazon][ImageHttpUrl]"
										value="<?php echo(!empty($data['Nitro']['CDNAmazon']['ImageHttpUrl'])) ? $data['Nitro']['CDNAmazon']['ImageHttpUrl'] : ''?>" />
									</td>
								</tr>
								<tr>
									<td>Amazon CloudFront/S3 CDN HTTPS URL<span class="help">Use
											the bucket Domain Name from <a target="_blank"
											href="https://console.aws.amazon.com/?nc2=h_m_mc">Amazon
												CloudFront</a> in the following way:<br />
										<strong>https://&lt;Bucket-Domain-Name&gt;/</strong>
									</span></td>
									<td><input class="form-control" type="text"
										name="Nitro[CDNAmazon][ImageHttpsUrl]"
										value="<?php echo(!empty($data['Nitro']['CDNAmazon']['ImageHttpsUrl'])) ? $data['Nitro']['CDNAmazon']['ImageHttpsUrl'] : ''?>" />
									</td>
								</tr>
								<tr>
									<td>Serve Images from this CDN<span class="help">When enabled,
											the Synced Images files will get served from the Amazon CDN.
											Use the form on the right to sync your Images.</td>
									<td><select class="form-control"
										name="Nitro[CDNAmazon][ServeImages]">
											<option value="no"
												<?php echo (empty($data['Nitro']['CDNAmazon']['ServeImages']) || $data['Nitro']['CDNAmazon']['ServeImages'] == 'no') ? 'selected=selected' : ''?>>No</option>
											<option value="yes"
												<?php echo( (!empty($data['Nitro']['CDNAmazon']['ServeImages']) && $data['Nitro']['CDNAmazon']['ServeImages'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
									</select></td>
								</tr>
							</table>
						</div>
						<div id="cdn-amazon-css" class="tab-pane">
							<table class="form cdnamazon" style="margin-top: -10px;">
								<tr>
									<td>CSS Bucket Name<span class="help">This should be a valid
											Amazon S3 bucket. You can create one in <a target="_blank"
											href="https://console.aws.amazon.com/?nc2=h_m_mc">Amazon
												Management Console</a>
									</span></td>
									<td><input class="form-control" type="text"
										name="Nitro[CDNAmazon][CSSBucket]"
										value="<?php echo !empty($data['Nitro']['CDNAmazon']['CSSBucket']) ? $data['Nitro']['CDNAmazon']['CSSBucket'] : ''; ?>" />
									</td>
								</tr>
								<tr>
									<td>Amazon CloudFront/S3 CDN HTTP URL<span class="help">Use the
											bucket Domain Name from <a target="_blank"
											href="https://console.aws.amazon.com/?nc2=h_m_mc">Amazon
												CloudFront</a> in the following way:<br />
										<strong>http://&lt;Bucket-Domain-Name&gt;/</strong>
									</span></td>
									<td><input class="form-control" type="text"
										name="Nitro[CDNAmazon][CSSHttpUrl]"
										value="<?php echo(!empty($data['Nitro']['CDNAmazon']['CSSHttpUrl'])) ? $data['Nitro']['CDNAmazon']['CSSHttpUrl'] : ''?>" />
									</td>
								</tr>
								<tr>
									<td>Amazon CloudFront/S3 CDN HTTPS URL<span class="help">Use
											the bucket Domain Name from <a target="_blank"
											href="https://console.aws.amazon.com/?nc2=h_m_mc">Amazon
												CloudFront</a> in the following way:<br />
										<strong>https://&lt;Bucket-Domain-Name&gt;/</strong>
									</span></td>
									<td><input class="form-control" type="text"
										name="Nitro[CDNAmazon][CSSHttpsUrl]"
										value="<?php echo(!empty($data['Nitro']['CDNAmazon']['CSSHttpsUrl'])) ? $data['Nitro']['CDNAmazon']['CSSHttpsUrl'] : ''?>" />
									</td>
								</tr>
								<tr>
									<td>Serve CSS from this CDN<span class="help">When enabled, the
											Synced CSS files will get served from the Amazon CDN. Use the
											form on the right to sync your CSS files.<br />
										<strong>Note:</strong> If you have enabled CSS Minification,
											you will first need to run the Pre-Cache service before
											syncing with the Amazon CDN. Go to Cache Systems &gt; Page
											cache &gt; Pre-cache sitemap pages
									</span></td>
									<td><select class="form-control"
										name="Nitro[CDNAmazon][ServeCSS]">
											<option value="no"
												<?php echo (empty($data['Nitro']['CDNAmazon']['ServeCSS']) || $data['Nitro']['CDNAmazon']['ServeCSS'] == 'no') ? 'selected=selected' : ''?>>No</option>
											<option value="yes"
												<?php echo( (!empty($data['Nitro']['CDNAmazon']['ServeCSS']) && $data['Nitro']['CDNAmazon']['ServeCSS'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
									</select></td>
								</tr>
							</table>
						</div>
						<div id="cdn-amazon-js" class="tab-pane">
							<table class="form cdnamazon" style="margin-top: -10px;">
								<tr>
									<td>JavaScript Bucket Name<span class="help">This should be a
											valid Amazon S3 bucket. You can create one in <a
											target="_blank"
											href="https://console.aws.amazon.com/?nc2=h_m_mc">Amazon
												Management Console</a>
									</span></td>
									<td><input class="form-control" type="text"
										name="Nitro[CDNAmazon][JavaScriptBucket]"
										value="<?php echo !empty($data['Nitro']['CDNAmazon']['JavaScriptBucket']) ? $data['Nitro']['CDNAmazon']['JavaScriptBucket'] : ''; ?>" />
									</td>
								</tr>
								<tr>
									<td>Amazon CloudFront/S3 CDN HTTP URL<span class="help">Use the
											bucket Domain Name from <a target="_blank"
											href="https://console.aws.amazon.com/?nc2=h_m_mc">Amazon
												CloudFront</a> in the following way:<br />
										<strong>http://&lt;Bucket-Domain-Name&gt;/</strong>
									</span></td>
									<td><input class="form-control" type="text"
										name="Nitro[CDNAmazon][JavaScriptHttpUrl]"
										value="<?php echo(!empty($data['Nitro']['CDNAmazon']['JavaScriptHttpUrl'])) ? $data['Nitro']['CDNAmazon']['JavaScriptHttpUrl'] : ''?>" />
									</td>
								</tr>
								<tr>
									<td>Amazon CloudFront/S3 CDN HTTPS URL<span class="help">Use
											the bucket Domain Name from <a target="_blank"
											href="https://console.aws.amazon.com/?nc2=h_m_mc">Amazon
												CloudFront</a> in the following way:<br />
										<strong>https://&lt;Bucket-Domain-Name&gt;/</strong>
									</span></td>
									<td><input class="form-control" type="text"
										name="Nitro[CDNAmazon][JavaScriptHttpsUrl]"
										value="<?php echo(!empty($data['Nitro']['CDNAmazon']['JavaScriptHttpsUrl'])) ? $data['Nitro']['CDNAmazon']['JavaScriptHttpsUrl'] : ''?>" />
									</td>
								</tr>
								<tr>
									<td>Serve JavaScript from this CDN<span class="help">When
											enabled, the Synced JavaScript files will get served from the
											Amazon CDN. Use the form on the right to sync your JavaScript
											files.<br />
										<strong>Note:</strong> If you have enabled JavaScript
											Minification, you will first need to run the Pre-Cache
											service before syncing with the Amazon CDN. Go to Cache
											Systems &gt; Page cache &gt; Pre-cache sitemap pages
									</span></td>
									<td><select class="form-control"
										name="Nitro[CDNAmazon][ServeJavaScript]">
											<option value="no"
												<?php echo (empty($data['Nitro']['CDNAmazon']['ServeJavaScript']) || $data['Nitro']['CDNAmazon']['ServeJavaScript'] == 'no') ? 'selected=selected' : ''?>>No</option>
											<option value="yes"
												<?php echo( (!empty($data['Nitro']['CDNAmazon']['ServeJavaScript']) && $data['Nitro']['CDNAmazon']['ServeJavaScript'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
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
		<div class="CDNAmazon-tabbable-parent">
			<div class="box-heading">
				<h1>
					<i class="icon-globe"></i>Synchronize with Amazon CloudFront/S3 CDN
				</h1>
			</div>
			<div class="box-content"
				style="min-height: 100px; line-height: 20px;">
				<div class="amazon-alert-info alert alert-info"><?php echo !empty($data['Nitro']['CDNAmazon']['LastUpload']) ? 'Last synchronization was on ' . $data['Nitro']['CDNAmazon']['LastUpload'] : 'No synchornization has been carried out yet.'; ?></div>
				<input class="form-control" type="hidden"
					name="Nitro[CDNAmazon][LastUpload]"
					value="<?php echo !empty($data['Nitro']['CDNAmazon']['LastUpload']) ? $data['Nitro']['CDNAmazon']['LastUpload'] : ''; ?>" />
				<div class="amazon-alert alert alert-danger"></div>
				<div class="amazon-alert alert alert-success"></div>
				<table class="form">
					<tr>
						<td>Access Key ID<span class="help">Your Amazon S3 Access Key ID.
								Obtained from the <a target="_blank"
								href="https://console.aws.amazon.com/iam/home?#security_credential">Amazon
									Security Credentials</a>
						</span></td>
						<td><input class="form-control" type="text"
							name="Nitro[CDNAmazon][AccessKeyID]"
							value="<?php echo !empty($data['Nitro']['CDNAmazon']['AccessKeyID']) ? $data['Nitro']['CDNAmazon']['AccessKeyID'] : ''; ?>" />
						</td>
					</tr>
					<tr>
						<td>Secret Access Key<span class="help">Your Amazon S3 Secret
								Access Key. Obtained from the <a target="_blank"
								href="https://console.aws.amazon.com/iam/home?#security_credential">Amazon
									Security Credentials</a>
						</span></td>
						<td><input class="form-control" type="text"
							name="Nitro[CDNAmazon][SecretAccessKey]"
							value="<?php echo !empty($data['Nitro']['CDNAmazon']['SecretAccessKey']) ? $data['Nitro']['CDNAmazon']['SecretAccessKey'] : ''; ?>" />
						</td>
					</tr>
					<tr>
						<td colspan="2"><label class="amazon-upload-label"><input
								class="form-control" type="checkbox" value="1"
								name="Nitro[CDNAmazon][SyncImages]"
								<?php if (!empty($data['Nitro']['CDNAmazon']['SyncImages'])) echo 'checked="checked"'; ?> />
								Upload all local images to Amazon CloudFront/S3 CDN</label> <label
							class="amazon-upload-label"><input class="form-control"
								type="checkbox" value="1" name="Nitro[CDNAmazon][SyncCSS]"
								<?php if (!empty($data['Nitro']['CDNAmazon']['SyncCSS'])) echo 'checked="checked"'; ?> />
								Upload all local CSS files to Amazon CloudFront/S3 CDN</label> <label
							class="amazon-upload-label"><input class="form-control"
								type="checkbox" value="1"
								name="Nitro[CDNAmazon][SyncJavaScript]"
								<?php if (!empty($data['Nitro']['CDNAmazon']['SyncJavaScript'])) echo 'checked="checked"'; ?> />
								Upload all local JavaScript files to Amazon CloudFront/S3 CDN</label>
						</td>
					</tr>
					<tr>
						<td colspan="2"><a class="amazon-upload btn btn-primary"><i
								class="icon-circle-arrow-up icon-white"></i> <span
								class="amazon-button-text">Sync with Amazon CDN</span></a> <a
							class="amazon-cancel btn btn-default"><i
								class="icon-remove icon-white"></i> <span
								class="amazon-cancel-text">Abort</span></a>
							<div class="progress active amazon-progress">
								<div class="bar bar-primary" style="width: 0%;"></div>
							</div>

							<div class="empty-amazon-div"></div>
							<div class="amazon-log"></div></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="box-heading">
			<h1>
				<i class="icon-info-sign"></i>Amazon CloudFront/S3 CDN
			</h1>
		</div>
		<div class="box-content" style="min-height: 100px; line-height: 20px;">
			<p>Amazon CloudFront is a web service for content delivery. Requests
				for your content are automatically routed to the nearest edge
				location, so content is delivered with the best possible
				performance. Amazon CloudFront is optimized to work with Amazon
				Simple Storage Service (Amazon S3).</p>
			<p>There are no contracts or monthly commitments for using Amazon
				CloudFront – you pay only for as much or as little content as you
				actually deliver through the service.</p>
			<p>
				Learn about Amazon CloudFront's latest features on the <a
					href="http://aws.amazon.com/cloudfront/whats-new" target="_blank">Amazon
					CloudFront What's New page</a>.
			</p>
		</div>
	</div>
</div>

<script type="text/javascript">

$(document).ready(function() {

  var showCDNForm = function() {
    if ($('.NitroCDNAmazon').val() == 'yes') {
      $('.CDNAmazon-tabbable-parent').fadeIn();
    } else {
      $('.CDNAmazon-tabbable-parent').hide();
    }
  }

  $('.NitroCDNAmazon').change(function() {
    $('.cdn-error').hide();
    if ($('.NitroCDNRackspace').val() == 'yes' || $('.NitroCDNStandard').val() == 'yes') {
      $('.cdn-error.amazon-error').fadeIn();
      $('.NitroCDNAmazon').val('no');
    } else {
      $('.cdn-error.amazon-error').hide();
      showCDNForm();
    }
  });

  showCDNForm();

  $('.amazon-upload').click(function() {
    nitro.cdn.setConfig({
      message_selector : '.amazon-log',
      progressbar_selector : '.amazon-progress div',
      success_wrapper : '<div class="alert alert-success">{MESSAGE}</div>',
      error_wrapper : '<div class="alert alert-danger">{MESSAGE}</div>',
      url : 'index.php?route=tool/nitro/cdn&token=' + getURLVar('token'),
      cdn : 'amazon',
      form_fields : {
        Nitro : {
          CDNStandard : {
            Enabled : $('select[name="Nitro[CDNStandard][Enabled]"]').val()
          },
          CDNRackspace : {
            Enabled : $('select[name="Nitro[CDNRackspace][Enabled]"]').val()
          },
          CDNAmazon : {
            Enabled : $('select[name="Nitro[CDNAmazon][Enabled]"]').val(),
            ImageBucket : $('input[name="Nitro[CDNAmazon][ImageBucket]"]').val(),
            ImageHttpUrl : $('input[name="Nitro[CDNAmazon][ImageHttpUrl]"]').val(),
            ImageHttpsUrl : $('input[name="Nitro[CDNAmazon][ImageHttpsUrl]"]').val(),
            ServeImages : $('select[name="Nitro[CDNAmazon][ServeImages]"]').val(),
            CSSBucket : $('input[name="Nitro[CDNAmazon][CSSBucket]"]').val(),
            CSSHttpUrl : $('input[name="Nitro[CDNAmazon][CSSHttpUrl]"]').val(),
            CSSHttpsUrl : $('input[name="Nitro[CDNAmazon][CSSHttpsUrl]"]').val(),
            ServeCSS : $('select[name="Nitro[CDNAmazon][ServeCSS]"]').val(),
            JavaScriptBucket : $('input[name="Nitro[CDNAmazon][JavaScriptBucket]"]').val(),
            JavaScriptHttpUrl : $('input[name="Nitro[CDNAmazon][JavaScriptHttpUrl]"]').val(),
            JavaScriptHttpsUrl : $('input[name="Nitro[CDNAmazon][JavaScriptHttpsUrl]"]').val(),
            ServeJavaScript : $('select[name="Nitro[CDNAmazon][ServeJavaScript]"]').val(),
            AccessKeyID : $('input[name="Nitro[CDNAmazon][AccessKeyID]"]').val(),
            SecretAccessKey : $('input[name="Nitro[CDNAmazon][SecretAccessKey]"]').val(),
            SyncImages : $('input[name="Nitro[CDNAmazon][SyncImages]"]').is(":checked") ? '1' : '',
            SyncCSS : $('input[name="Nitro[CDNAmazon][SyncCSS]"]').is(":checked") ? '1' : '',
            SyncJavaScript : $('input[name="Nitro[CDNAmazon][SyncJavaScript]"]').is(":checked") ? '1' : '',
            LastUpload : $('input[name="Nitro[CDNAmazon][LastUpload]"]').val()
          }
        }
      }
    });

    nitro.cdn.start();
  });

  $('.amazon-cancel').click(function() {
    nitro.cdn.abort();
  });

});

</script>