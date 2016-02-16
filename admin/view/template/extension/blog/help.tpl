<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
		</div>
	</div>
	<div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger">
			<i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success">
			<i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
    <?php } ?>
    <div class="panel panel-default">
			<div class="panel-body">
				<ul
					style="margin-bottom: 10px; background: #F5F5F5; padding: 3px 3px 0;"
					class="nav nav-tabs">
					<li><a
						href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/category&amp;token=<?php echo $token; ?>"><?php echo $category_menu; ?></a></li>
					<li><a
						href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog&amp;token=<?php echo $token; ?>"><?php echo $post_menu; ?></a></li>
					<li><a
						href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/comment&amp;token=<?php echo $token; ?>"><?php echo $comment_menu; ?></a></li>
					<li><a
						href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/setting&amp;token=<?php echo $token; ?>"><?php echo $setting_menu; ?></a></li>
					<li class="active"><a
						href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/help&amp;token=<?php echo $token; ?>"><?php echo $help_menu; ?></a></li>
				</ul>
				<section class="tab-content">

					<!-- HELP -->
					<div class="tab-pane active" id="help">

						<div style="margin-bottom: 10px;">
							<div class="row">
								<div class="col-lg-8">
									<h1 style="font-size: 21px; font-weight: 700; color: #20BFEF;"><?php echo $heading_title; ?></h1>
								</div>
								<div class="col-lg-4"></div>
								<div class="clearfix"></div>
							</div>
						</div>

						<table class="table table-hover">

							<tr>
								<td class="text-center" colspan="2"><a target="_blink"
									href="website.com/demo" title="Demo Blog"><?php echo $demosite_text; ?></a></td>
							</tr>

							<tr>
								<td class="text-center" colspan="2"><a target="_blink"
									href="mysite.com/ob/documentation" title="Documentation"><?php echo $doc_text; ?></a></td>
							</tr>

							<tr>
								<td class="text-center" colspan="2"><a target="_blink"
									href="https://www.facebook.com/wdmilon" title="Facebook Page"><?php echo $fb_text; ?></a></td>
							</tr>

							<tr>
								<td class="text-center" colspan="2"><a target="_blink"
									href="https://twitter.com/techbuzz69" title="Twitter"><?php echo $tw_text; ?></a></td>
							</tr>

							<tr>
								<th class="text-right" width="50%"><?php echo $gmail_text; ?> : </th>
								<td width="50%">techbuzz69@gmail.com</td>
							</tr>

							<tr>
								<th class="text-right" width="50%"><?php echo $skype_text; ?> : </th>
								<td width="50%">webXpert24</td>
							</tr>

							<tr>
								<th class="text-right" width="50%"><?php echo $mobile_text; ?> : </th>
								<td width="50%">+8801737346122</td>
							</tr>

						</table>
				
				</section>
			</div>
		</div>
		</form>
	</div>
</div>
<?php echo $footer; ?>