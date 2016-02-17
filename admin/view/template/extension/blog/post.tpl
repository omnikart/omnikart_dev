<?php echo $header; ?>

<?php echo $column_left; ?>

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
	<!-- .Page-header -->

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
					<li class="active"><a
						href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog&amp;token=<?php echo $token; ?>"><?php echo $post_menu; ?></a></li>
					<li><a
						href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/comment&amp;token=<?php echo $token; ?>"><?php echo $comment_menu; ?></a></li>
					<li><a
						href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/setting&amp;token=<?php echo $token; ?>"><?php echo $setting_menu; ?></a></li>
					<li><a
						href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/help&amp;token=<?php echo $token; ?>"><?php echo $help_menu; ?></a></li>
				</ul>

				<section class="tab-content">

					<div class="tab-pane active" id="post">

						<div style="margin-bottom: 10px;" class="pane-heading">
							<div class="row">
								<div class="col-lg-8">
									<h1 style="font-size: 21px; font-weight: 700; color: #20BFEF;"><?php echo $heading_title; ?></h1 style="font-weight:700;color:#373737">
								</div>
								<div class="col-lg-4">
									<div class="pull-right">
										<a href="<?php echo $post_create_link; ?>"
											data-toggle="tooltip" title="" class="btn btn-sm btn-info"
											data-original-title="<?php echo $create_btn_tooltip; ?>"><i
											class="fa fa-plus"></i>&nbsp;<?php echo $create_btn; ?></a>
										<button class="btn btn-sm btn-danger"
											onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-post').submit() : false;"
											data-toggle="tooltip" title=""
											data-original-title="<?php echo $delete_btn_tooltip; ?>">
											<i class="fa fa-trash-o"></i>&nbsp;<?php echo $delete_btn; ?></button>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>

						<div class="table-responsive">
							<table class="table table-bordered">

								<thead>
									<tr class="active">
										<th class="text-center" width="1%"><input type="checkbox"
											onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
										<th class="text-left" width="5%"><?php echo $col_thumb; ?></th>
										<th class="text-left">
                    <?php if ($sort == 'pd.title') { ?>
                      <a href="<?php echo $sort_title; ?>"
											class="<?php echo strtolower($order); ?>"><?php echo $col_postTitle; ?></a>
                    <?php } else { ?>
                      <a href="<?php echo $sort_title; ?>" class="asc"><?php echo $col_postTitle; ?></a>
                    <?php } ?></th>
										<th class="text-right"><?php echo $col_author; ?></th>
										<th class="text-right">
                    <?php if ($sort == 'p.date_added') { ?>
                      <a href="<?php echo $sort_date; ?>"
											class="<?php echo strtolower($order); ?>"><?php echo $col_date; ?></a>
                    <?php } else { ?>
                      <a href="<?php echo $sort_date; ?>" class="asc"><?php echo $col_date; ?></a>
                    <?php } ?></th>
										<th class="text-right"><?php echo $col_status; ?></th>
										<th class="text-right" width="10%"><?php echo $col_action; ?></th>
									</tr>
								</thead>

								<tbody>

									<!-- Start Filter Bar -->
									<tr class="info">
										<td align="center" colspan="7">
											<div class="form-inline">
												<div style="text-align: left;" class="form-group">
													<label for="input-filter_title"><?php echo $filter_text_title; ?></label><br>
													<input type="text" name="filter_title"
														value="<?php echo $filter_title; ?>"
														placeholder="<?php echo $filter_text_title; ?>"
														class="form-control">
												</div>
												<div style="text-align: left;" class="form-group">
													<label for="input-filter_author"><?php echo $filter_text_author; ?></label><br>
													<input type="text" name="filter_author"
														value="<?php echo $filter_author; ?>"
														placeholder="<?php echo $filter_text_author; ?>"
														class="form-control">
												</div>
												<div style="text-align: left;" class="form-group">
													<label for="input-filter_status"><?php echo $filter_text_status; ?></label><br>
													<select name="filter_status" id="filter_status"
														class="form-control">
														<option value="*">-- <?php echo $filter_text_status; ?> --</option>
														<option value="publish"
															<?php echo ($filter_status == 'publish') ? 'selected="selected"' : ''; ?>><?php echo $filter_text_publish; ?></option>
														<option value="unpublish"
															<?php echo ($filter_status == 'unpublish') ? 'selected="selected"' : ''; ?>><?php echo $filter_text_unpublish; ?></option>
													</select>
												</div>
												<div class="form-group">
													<br>
													<button id="button-filter" type="button"
														class="btn btn-sm btn-warning"><?php echo $button_filter; ?></button>
												</div>
											</div>
										</td>
									</tr>
									<!-- End Filter Bar -->

                <?php if(isset($posts) AND is_array($posts)) : ?>
                <form action="<?php echo $post_delete_multiple; ?>"
										method="post" enctype="multipart/form-data" id="form-post">
                  <?php foreach ($posts as $post) : ?>
                    <tr>
											<td class="text-center">
                        <?php if (in_array($post['ID'], $selected)) { ?>
                          <input type="checkbox" name="selected[]"
												value="<?php echo $post['ID']; ?>" checked="checked" />
                          <?php } else { ?>
                          <input type="checkbox" name="selected[]"
												value="<?php echo $post['ID']; ?>" />
                        <?php } ?>
                      
											
											<td><img class="thumbnail"
												src="<?php echo $post['image']; ?>" /></td>
											<td><?php echo ucfirst($post['title']); ?></td>
											<td class="text-right"><?php echo author($post['post_author'],'username'); ?></td>
											<td class="text-right"><?php echo $post['date_added']; ?></td>
											<td class="text-right"><?php echo ucfirst($post['post_status']); ?></td>
											<td class="text-right">
												<div class="btn-group">
													<a
														href="<?php echo $post_edit_link; ?>&amp;post_id=<?php echo $post['ID']; ?>"
														class="btn btn-success btn-sm"><span class="fa fa-edit"></span></a>
													<a
														onclick="return confirm('<?php echo $text_confirm; ?>');"
														href="<?php echo $post_delete_link; ?>&amp;post_id=<?php echo $post['ID']; ?>"
														class="btn btn-danger btn-sm"><span class="fa fa-trash-o"></span></a>
												</div>
											</td>
										</tr>
                  <?php endforeach; ?>
                </form>
                <?php else: ?>
                  <tr>
										<td colspan="7"><div class="alert alert-warning"><?php echo $text_not_found; ?></div></td>
									</tr>
                <?php endif; ?>
                <?php if($pagination) : ?>
                  <tr>
										<td colspan="7">
											<div class="pagination-info pull-left">
                        <?php echo $results; ?>
                      </div>
											<div id="post-pagination" class="pull-right">
                        <?php echo $pagination; ?>
                      </div>
										</td>
									</tr>
                <?php endif; ?>
              </tbody>
							</table>
						</div>

					</div>
					<!-- #POST Pane-->

				</section>
				<!-- Tab Content-->

			</div>
			<!-- Panel Body-->

		</div>
		<!-- .Panel -->

	</div>
	<!-- Container-fluid -->

</div>
<!-- #content -->

<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
  var url = 'index.php?route=extension/blog&token=<?php echo $token; ?>';

  var filter_title = $('input[name=\'filter_title\']').val();

  if (filter_title) {
    url += '&filter_title=' + encodeURIComponent(filter_title);
  }

  var filter_author = $('input[name=\'filter_author\']').val();

  if (filter_author) {
    url += '&filter_author=' + encodeURIComponent(filter_author);
  }

  var filter_status = $('select[name=\'filter_status\']').val();

  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status);
  }

  location = url;
});
//--></script>

<?php echo $footer; ?>
