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
					<li class="active"><a
						href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/comment&amp;token=<?php echo $token; ?>"><?php echo $comment_menu; ?></a></li>
					<li><a
						href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/setting&amp;token=<?php echo $token; ?>"><?php echo $setting_menu; ?></a></li>
					<li><a
						href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/help&amp;token=<?php echo $token; ?>"><?php echo $help_menu; ?></a></li>
				</ul>
				<section class="tab-content">

					<!-- COMMENT -->
					<div class="tab-pane active" id="comment">
						<div style="margin-bottom: 10px;" class="pane-heading">
							<div class="row">
								<div class="col-lg-8">
									<h1 style="font-size: 21px; font-weight: 700; color: #20BFEF;"><?php echo $heading_title; ?></h1>
								</div>
								<div class="col-lg-4">
									<div class="pull-right">
										<button class="btn btn-sm btn-danger"
											onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-comment').submit() : false;"
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
										<th class="text-left"><?php echo $col_postTitle; ?></th>
										<th class="text-left"><?php echo $col_excerpt; ?></th>
										<th class="text-right"><?php echo $col_author; ?></th>
										<th class="text-right"><?php if ($sort == 'comment_date') { ?>
                    <a href="<?php echo $sort_date; ?>"
											class="<?php echo strtolower($order); ?>"><?php echo $col_date; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date; ?>" class="asc"><?php echo $col_date; ?></a>
                    <?php } ?></th>

										<th width="8%" class="text-right"><?php if ($sort == 'comment_approve') { ?>
                    <a href="<?php echo $sort_status; ?>"
											class="<?php echo strtolower($order); ?>"><?php echo $col_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>" class="asc"><?php echo $col_status; ?></a>
                    <?php } ?></th>

										<th class="text-right" width="10%"><?php echo $col_action; ?></th>
									</tr>
								</thead>
								<tbody>

									<!-- Start Filter Bar -->
									<tr class="info">
										<td align="center" colspan="7">
											<div class="form-inline">
												<div style="text-align: left;" class="form-group">
													<label for="input-filter_title"><?php echo $filter_text_author; ?></label><br>
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
											</div> <!-- form-inline -->
										</td>
									</tr>
									<!-- End Filter Bar -->

              <?php if(isset($comments) && $comments) : ?>
                  <form action="<?php echo $comment_delete_multiple; ?>"
										method="post" enctype="multipart/form-data" id="form-comment">
                    <?php foreach ($comments as $comment) : ?>
                      <tr>
											<td class="text-center">
                          <?php if (in_array($comment['comment_ID'], $selected)) { ?>
                            <input type="checkbox" name="selected[]"
												value="<?php echo $comment['comment_ID']; ?>"
												checked="checked" />
                            <?php } else { ?>
                            <input type="checkbox" name="selected[]"
												value="<?php echo $comment['comment_ID']; ?>" />
                          <?php } ?>
                        </td>
											<td class="text-left"><?php echo ucfirst(words_limit($comment['title'],10,'...')); ?></td>
											<td class="text-left"><?php echo ucfirst(strip_tags(html_entity_decode(words_limit($comment['comment_content'],10,'...')))); ?></td>
											<td class="text-left"><?php echo ucfirst($comment['comment_author']); ?></td>
											<td class="text-left"><?php echo $comment['comment_date']; ?></td>
											<td class="text-right"><?php echo ucfirst($comment['comment_approve']); ?></td>
											<td class="text-right">
												<div class="btn-group">
													<a
														href="<?php echo $comment_edit_link; ?>&amp;comment_id=<?php echo $comment['comment_ID']; ?>"
														class="btn btn-success btn-sm"><span class="fa fa-edit"></span></a>
													<a
														onclick="return confirm('<?php echo $text_confirm; ?>');"
														href="<?php echo $comment_delete_link; ?>&amp;comment_id=<?php echo $comment['comment_ID']; ?>"
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
					<!-- .COMMENT -->

				</section>
			</div>
		</div>
		</form>
	</div>
</div>
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
  var url = 'index.php?route=extension/blog/comment&token=<?php echo $token; ?>';

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