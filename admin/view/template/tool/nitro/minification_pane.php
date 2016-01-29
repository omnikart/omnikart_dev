<div class="row">
	<div class="col-md-8">
		<div class="box-heading">
			<h1>Minification</h1>
		</div>
		<div class="box-content">
			<table class="form minificationtoptable">
				<tr>
					<td>Use Minification</td>
					<td><select name="Nitro[Mini][Enabled]"
						class="form-control NitroMini">
							<option value="no"
								<?php echo (empty($data['Nitro']['Mini']['Enabled']) || $data['Nitro']['Mini']['Enabled'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
							<option value="yes"
								<?php echo( (!empty($data['Nitro']['Mini']['Enabled']) && $data['Nitro']['Mini']['Enabled'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
					</select></td>
				</tr>
			</table>

			<div class="minification-tabbable-parent">
				<div class="tabbable tabs-left">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#mini-css" data-toggle="tab">CSS files</a></li>
						<li><a href="#mini-javascript" data-toggle="tab">JavaScript files</a></li>
						<li><a href="#mini-html" data-toggle="tab">HTML files</a></li>
					</ul>
					<div class="tab-content">
						<div id="mini-css" class="tab-pane active">
							<table class="form minification" style="margin-top: -10px;">
								<tr>
									<td>Minify CSS files</td>
									<td><select class="form-control" name="Nitro[Mini][CSS]">
											<option value="no"
												<?php echo (empty($data['Nitro']['Mini']['CSS']) || $data['Nitro']['Mini']['CSS'] == 'no') ? 'selected=selected' : ''?>>No</option>
											<option value="yes"
												<?php echo( (!empty($data['Nitro']['Mini']['CSS']) && $data['Nitro']['Mini']['CSS'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
									</select></td>
								</tr>
								<tr>
									<td>Combine CSS files<span class="help">This will combine all
											your CSS files loaded dynamically into 1 file called <i>nitro-combined.css</i>
									</span></td>
									<td><select class="form-control" name="Nitro[Mini][CSSCombine]">
											<option value="no"
												<?php echo (empty($data['Nitro']['Mini']['CSSCombine']) || $data['Nitro']['Mini']['CSSCombine'] == 'no') ? 'selected=selected' : ''?>>No</option>
											<option value="yes"
												<?php echo( (!empty($data['Nitro']['Mini']['CSSCombine']) && $data['Nitro']['Mini']['CSSCombine'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
									</select></td>
								</tr>
								<tr>
									<td>Improved CSS detection algorithm<span class="help">*<b>Experimental:</b>
											This will try to find hardcoded CSS resources from the
											generated cache files and process them as well
									</span></td>
									<td><select class="form-control" name="Nitro[Mini][CSSExtract]">
											<option value="no"
												<?php echo (empty($data['Nitro']['Mini']['CSSExtract']) || $data['Nitro']['Mini']['CSSExtract'] == 'no') ? 'selected=selected' : ''?>>No</option>
											<option value="yes"
												<?php echo( (!empty($data['Nitro']['Mini']['CSSExtract']) && $data['Nitro']['Mini']['CSSExtract'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
									</select></td>
								</tr>
								<tr>
									<td>Parse import statements<span class="help">*<b>Experimental:</b>
											This will try to fetch the content of the imported with <b>@import</b>
											CSS resources and include it in the combined file.
									</span></td>
									<td><select class="form-control"
										name="Nitro[Mini][CSSFetchImport]">
											<option value="no"
												<?php echo (empty($data['Nitro']['Mini']['CSSFetchImport']) || $data['Nitro']['Mini']['CSSFetchImport'] == 'no') ? 'selected=selected' : ''?>>No</option>
											<option value="yes"
												<?php echo( (!empty($data['Nitro']['Mini']['CSSFetchImport']) && $data['Nitro']['Mini']['CSSFetchImport'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
									</select></td>
								</tr>
								<tr>
									<td style="vertical-align: top;">Exclude files:<span
										class="help">Each file on a new line. The files you specify
											here will be excluded from minification. This also applies to
											files detected with the improved CSS detection algorithm. You
											can input part of the file name.</span></td>
									<td style="vertical-align: top;"><textarea class="form-control"
											placeholder="e.g. slideshow.css, each file on a new line"
											style="width: 400px; height: 180px;"
											name="Nitro[Mini][CSSExclude]"><?php echo(!empty($data['Nitro']['Mini']['CSSExclude'])) ? $data['Nitro']['Mini']['CSSExclude'] : ''?></textarea>
									</td>
								</tr>
								<tr>
									<td colspan="2"><a href="javascript:void(0)"
										onclick="document.location='index.php?route=tool/nitro/clearcsscache&token=<?php echo $_GET['token']; ?>'"
										class="btn btn-default clearJSCSSCache"><i class="icon-trash"></i>
											Clear minified CSS files cache</a></td>
								</tr>
							</table>
						</div>
						<div id="mini-javascript" class="tab-pane">
							<table class="form minification" style="margin-top: -10px;">
								<tr>
									<td>Minify JavaScript files<span class="help">Enable/Disable
											JavaScript minification. Enabling this may cause slower first
											page loading.</span></td>
									<td><select class="form-control" name="Nitro[Mini][JS]">
											<option value="no"
												<?php echo (empty($data['Nitro']['Mini']['JS']) || $data['Nitro']['Mini']['JS'] == 'no') ? 'selected=selected' : ''?>>No</option>
											<option value="yes"
												<?php echo( (!empty($data['Nitro']['Mini']['JS']) && $data['Nitro']['Mini']['JS'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
									</select>
                    <?php if ($cannotUseMinify) { ?>
                        <div class="alert alert-danger">
                          The NitroPack JavaScript minifier is available only on PHP 5.3 and above. Your PHP version is <?php echo phpversion(); ?>. Please contact your web hosting provider and ask them to upgrade your PHP to version 5.3.
                        </div>
                    <?php } ?>
                    </td>
								</tr>
								<tr>
									<td>Combine JavaScript files<span class="help">This will
											combine all your JS files loaded dynamically into 1 file
											called <i>nitro-combined.js</i>
									</span></td>
									<td><select class="form-control" name="Nitro[Mini][JSCombine]">
											<option value="no"
												<?php echo (empty($data['Nitro']['Mini']['JSCombine']) || $data['Nitro']['Mini']['JSCombine'] == 'no') ? 'selected=selected' : ''?>>No</option>
											<option value="yes"
												<?php echo( (!empty($data['Nitro']['Mini']['JSCombine']) && $data['Nitro']['Mini']['JSCombine'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
									</select></td>
								</tr>
								<tr>
									<td>Improved JavaScript detection<span class="help">*<b>Experimental:</b>
											This will try to find hardcoded JavaScript resources from the
											generated cache files and process them as well
									</span></td>

									<td><select class="form-control" name="Nitro[Mini][JSExtract]">
											<option value="no"
												<?php echo (empty($data['Nitro']['Mini']['JSExtract']) || $data['Nitro']['Mini']['JSExtract'] == 'no') ? 'selected=selected' : ''?>>No</option>
											<option value="yes"
												<?php echo( (!empty($data['Nitro']['Mini']['JSExtract']) && $data['Nitro']['Mini']['JSExtract'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
									</select></td>
								</tr>
								<tr>
									<td>Move detected JavaScript to:<span class="help">Choose where
											to put the detected JavaScript files</span></td>
									<td><select class="form-control" name="Nitro[Mini][JSPosition]">
											<option value="top"
												<?php echo (!empty($data['Nitro']['Mini']['JSPosition']) && $data['Nitro']['Mini']['JSPosition'] == 'top') ? 'selected=selected' : ''?>>Top
												of the page</option>
											<option value="bottom"
												<?php echo (!empty($data['Nitro']['Mini']['JSPosition']) && $data['Nitro']['Mini']['JSPosition'] == 'bottom') ? 'selected=selected' : ''?>>Bottom
												of the page</option>
									</select></td>
								</tr>
								<tr>
									<td>Defer detected JavaScript:<span class="help">Choose whether
											to load your JavaScript resources asynchronously using the
											defer attribute. Usually deferred loading is faster, because
											it loads the scripts concurrently, but it may also cause
											dependency errors.</span></td>
									<td><select class="form-control" name="Nitro[Mini][JSDefer]">
											<option value="no"
												<?php echo (empty($data['Nitro']['Mini']['JSDefer']) || $data['Nitro']['Mini']['JSDefer'] == 'no') ? 'selected=selected' : ''?>>No
												(Recommended)</option>
											<option value="yes"
												<?php echo( (!empty($data['Nitro']['Mini']['JSDefer']) && $data['Nitro']['Mini']['JSDefer'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
									</select></td>
								</tr>
								<tr>
									<td style="vertical-align: top;">Exclude files:<span
										class="help">Each file on a new line. The files you specify
											here will be excluded from minification. This applies to
											files detected with the improved JavaScript detection
											algorithm also. You can input part of the file name.</span></td>
									<td style="vertical-align: top;"><textarea class="form-control"
											placeholder="e.g. slideshow.js, each file on a new line"
											style="width: 400px; height: 180px;"
											name="Nitro[Mini][JSExclude]"><?php echo(!empty($data['Nitro']['Mini']['JSExclude'])) ? $data['Nitro']['Mini']['JSExclude'] : ''?></textarea>
									</td>
								</tr>
								<tr>
									<td style="vertical-align: top;">Exclude inline &lt;script&gt;
										tags containing the phrase:<span class="help">Each phrase on a
											new line. This applies to inline &lt;script&gt; tags detected
											with the improved JS detection algorithm.</span>
									</td>
									<td style="vertical-align: top;"><textarea class="form-control"
											placeholder="e.g. $('.date, .datetime, .time').bgIframe();"
											style="width: 400px; height: 180px;"
											name="Nitro[Mini][JSExcludeInline]"><?php echo(!empty($data['Nitro']['Mini']['JSExcludeInline'])) ? $data['Nitro']['Mini']['JSExcludeInline'] : ''?></textarea>
									</td>
								</tr>
								<tr>
									<td colspan="2"><a href="javascript:void(0)"
										onclick="document.location='index.php?route=tool/nitro/clearjscache&token=<?php echo $_GET['token']; ?>'"
										class="btn btn-default clearJSCSSCache"><i class="icon-trash"></i>
											Clear minified JavaScript files cache</a></td>
								</tr>
							</table>

						</div>
						<div id="mini-html" class="tab-pane">
            <?php if (empty($data['Nitro']['PageCache']['Enabled']) || $data['Nitro']['PageCache']['Enabled'] == 'no'): ?>
            <div class="alert alert-danger">
								<b>Oh snap!</b> This feature requires enabled Page Cache. <a
									href="javascript:void(0)"
									onclick="$('a[href=#pagecache]').trigger('click');">Click here</a>
								to enable it.
							</div>
            <?php endif; ?>
                <table class="form minification"
								style="margin-top: -10px;">
								<tr>
									<td>Minify HTML files<span class="help">This requires enabled
											Page Cache. When enabled, the page cache files will be
											created minified.</span></td>
									<td><select class="form-control" name="Nitro[Mini][HTML]">
											<option value="no"
												<?php echo (empty($data['Nitro']['Mini']['HTML']) || $data['Nitro']['Mini']['HTML'] == 'no') ? 'selected=selected' : ''?>>No</option>
											<option value="yes"
												<?php echo( (!empty($data['Nitro']['Mini']['HTML']) && $data['Nitro']['Mini']['HTML'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
									</select></td>
								</tr>
								<tr>
									<td>Keep HTML comments<span class="help">Enable this option if
											you want to keep the HTML comments &lt;!-- --&gt;. Does not
											apply for <a
											href="http://msdn.microsoft.com/en-us/library/ms537512(v=vs.85).aspx"
											target="_blank">conditional Internet Explorer comments</a>.
									</span></td>
									<td><select class="form-control"
										name="Nitro[Mini][HTMLComments]">
											<option value="yes"
												<?php echo (!empty($data['Nitro']['Mini']['HTMLComments']) && $data['Nitro']['Mini']['HTMLComments'] == 'yes') ? 'selected=selected' : ''?>>Yes</option>
											<option value="no"
												<?php echo (!empty($data['Nitro']['Mini']['HTMLComments']) && $data['Nitro']['Mini']['HTMLComments'] == 'no') ? 'selected=selected' : ''?>>No</option>
									</select></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>



		</div>
	</div>
	<div class="col-md-4">
		<div class="box-heading">
			<h1>
				<i class="icon-info-sign"></i>What is minification?
			</h1>
		</div>
		<div class="box-content" style="min-height: 100px; line-height: 20px;">
			Minification is the process of removing all unnecessary characters
			from source code, without changing its functionality. These
			unnecessary characters usually include white space characters, new
			line characters, comments, and sometimes block delimiters, which are
			used to add readability to the code but are not required for it to
			execute.</div>
	</div>
	<div class="col-md-4">
		<div class="box-heading">
			<h1>Pre-Minify resources</h1>
		</div>
		<div class="box-content">
			<p>This feature will pre-minify the CSS and JavaScript resources
				found in your OpenCart catalog directory. This will speed up the
				initial page cache creation time significantly.</p>
			<p>
				<strong>Note: </strong> Pre-minifying will delete all of the
				NitroPack-generated minify cache before starting.
			</p>

			<div class="spacer10">
				<a id="preminify_start" class="btn btn-primary"><i class="icon-hdd"></i>
					Pre-minify resources</a> <a id="preminify_abort"
					class="btn btn-default"><i class="icon-remove"></i> Abort</a>
			</div>

			<div class="progress spacer10">
				<div id="preminify_progressbar" class="progress-bar"
					style="width: 0%;"></div>
			</div>

			<p id="preminify_details"></p>

			<script type="text/javascript">
        $(document).ready(function() {
          nitro.preminify.setConfig({
            stack_url : 'index.php?route=tool/nitro/get_preminify_stack&token=' + getURLVar('token'),
			minify_url : 'index.php?route=tool/nitro/minify_file&token=' + getURLVar('token'),
            progressbar_selector : '#preminify_progressbar',
            output_selector : '#preminify_details',
            http_header : 'Nitro-Preminify'
          });
		  
		  $('#preminify_start').click(function() {
			nitro.preminify.start();
		  });
		
		  $('#preminify_abort').click(function() {
			nitro.preminify.abort();
		  });
        });
      </script>
		</div>
	</div>
</div>