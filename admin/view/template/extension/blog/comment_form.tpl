<?php //print_r($comment); die();?>
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
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-body">
         <ul style="margin-bottom:10px; background: #F5F5F5; padding: 3px 3px 0;" class="nav nav-tabs">
            <li><a href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/category&amp;token=<?php echo $token; ?>"><?php echo $category_menu; ?></a></li>
            <li><a href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog&amp;token=<?php echo $token; ?>"><?php echo $post_menu; ?></a></li>
            <li class="active"><a href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/comment&amp;token=<?php echo $token; ?>"><?php echo $comment_menu; ?></a></li>
            <li><a href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/setting&amp;token=<?php echo $token; ?>"><?php echo $setting_menu; ?></a></li>
            <li><a href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/help&amp;token=<?php echo $token; ?>"><?php echo $help_menu; ?></a></li>
          </ul>
        <section class="tab-content">

          <div class="row">
            <div class="col-lg-8">
              <h1 style="font-size:21px;font-weight:700;color:#20BFEF;"><?php echo $heading_title; ?></h1>
            </div>
            <div class="col-lg-4">
              <div class="pull-right">
                <button type="submit" form="form-catedit" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $save_btn_tooltip; ?>"><i class="fa fa-edit"></i>&nbsp;<?php echo $save_btn; ?></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="<?php echo $cancel_btn_tooltip; ?>"><i class="fa fa-reply"></i>&nbsp;<?php echo $cancel_btn; ?></a>
              </div>
            </div>
          </div>

          <div class="tab-pane active table-responsive" id="comment">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-catedit" class="form-horizontal" >
              <table class="table table-bordered">
                <tbody>
                    <tr>
                      <td width="15%" class="text-right"><label for="comment_author" class="control-label"><?php echo $col_author; ?></label></td>
                      <td width="85%"><input type="text" name="comment_author" id="comment_author" value="<?php echo isset($comment['comment_author']) && $comment['comment_author'] ? $comment['comment_author'] : ''; ?>" class="form-control">
                        <?php if (isset($form_error['comment_author'])) { ?>
                          <div class="text-danger"><?php echo ucfirst($form_error['comment_author']); ?></div>
                        <?php } ?>
                      </td>
                    </tr>

                    <tr>
                      <td width="15%" class="text-right"><label for="author_email" class="control-label"><?php echo $col_email; ?></label></td>
                      <td width="85%"><input type="email" name="author_email" id="author_email" value="<?php echo isset($comment['author_email']) && $comment['author_email'] ? $comment['author_email'] : ''; ?>" class="form-control">
                        <?php if (isset($form_error['author_email'])) { ?>
                          <div class="text-danger"><?php echo ucfirst($form_error['author_email']); ?></div>
                        <?php } ?>
                      </td>
                    </tr>

                    <tr>
                      <td width="15%" class="text-right"><label for="comment_content" class="control-label"><?php echo $col_content; ?></label></td>
                      <td width="85%"><textarea name="comment_content" id="comment-content" rows="10" class="form-control"><?php echo isset($comment['comment_content']) && $comment['comment_content'] ? $comment['comment_content'] : ''; ?></textarea>
                        <?php if (isset($form_error['comment_content'])) { ?>
                          <div class="text-danger"><?php echo ucfirst($form_error['comment_content']); ?></div>
                        <?php } ?>
                      </td>
                    </tr>

                    <tr>
                      <td width="15%" class="text-right"><label for="comment_approve" class="control-label"><?php echo $col_status ?></label></td>
                      <td width="85%">
                        <select class="form-control" name="comment_approve" id="comment_approve">
                          <option value="publish" <?php echo isset($comment['comment_approve']) && ($comment['comment_approve'] == 'publish') ? 'selected="selected"' : null; ?>>Publish</option>
                          <option value="unpublish" <?php echo isset($comment['comment_approve']) && ($comment['comment_approve'] == 'unpublish') ? 'selected="selected"' : null; ?>>Unpublish</option>
                        </select>
                      </td>
                    </tr>
                  
                </tbody>
              </table>
            </form>
          </div>
          <!-- #COMMENT -->

        </section>
      </div>
    </div>
    </form>
  </div>
</div>

<script type="text/javascript"><!--
  $('#comment-content').summernote({
    height: 300
  });
//--></script> 

<?php echo $footer; ?>