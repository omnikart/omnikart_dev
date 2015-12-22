<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-blog_tabs" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-blog_tabs" class="form-horizontal">

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          
          <ul id="myTab" class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#commentlist" id="commentlist-tab" role="tab" data-toggle="tab" aria-controls="commentlist" aria-expanded="true">Comment List</a></li>
            <li role="presentation"><a href="#commentbox" role="tab" id="commentbox-tab" data-toggle="tab" aria-controls="commentbox">Comment Box</a></li>
          </ul>
          
          <div id="myTabContent" class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="commentlist" aria-labelledBy="commentlist-tab">

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-title_list"><?php echo $entry_title; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="title_list" value="<?php echo $title_list; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title_list" class="form-control" />
                  <?php if ($error_title_list) { ?>
                  <div class="text-danger"><?php echo $error_title_list; ?></div>
                  <?php } ?>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-titleicon_list"><?php echo $entry_titleicon; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="titleicon_list" value="<?php echo $titleicon_list; ?>" placeholder="<?php echo $entry_titleicon; ?>" id="input-titleicon_list" class="form-control" />
                  <?php if ($error_titleicon_list) { ?>
                  <div class="text-danger"><?php echo $error_titleicon_list; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-author_photo_size"><?php echo $entry_author_photo_size; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="author_photo_size" value="<?php echo $author_photo_size; ?>" placeholder="<?php echo $entry_author_photo_size; ?>" id="input-author_photo_size" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-author_photo_position"><?php echo $entry_author_photo_position; ?></label>
                <div class="col-sm-10">                
                  <select name="author_photo_position" id="input-author_photo_display" class="form-control">
                    <option value=""><?php echo $text_select; ?></option>
                    <option value="left" <?php echo ($author_photo_position == 'left') ? 'selected="selected"' : ''; ?>><?php echo $text_left; ?></option>
                    <option value="right" <?php echo ($author_photo_position == 'right') ? 'selected="selected"' : ''; ?>><?php echo $text_right; ?></option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-author_photo_display"><?php echo $entry_author_photo_display; ?></label>
                <div class="col-sm-10">
                  <select name="author_photo_display" id="input-author_photo_display" class="form-control">
                    <?php if ($author_photo_display) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-limit"><?php echo $entry_limit; ?></label>
                <div class="col-sm-10">
                  <input type="number" name="limit" value="<?php echo $limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
                  <?php if ($error_limit) { ?>
                  <div class="text-danger"><?php echo $error_limit; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-custom_style_comment"><?php echo $entry_custom_style; ?></label>
                <div class="col-sm-10">
                  <textarea name="custom_style_comment" id="input-custom_style_comment" cols="30" rows="10" class="form-control">
                    <?php echo $custom_style_comment; ?>
                  </textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-custom_script_comment"><?php echo $entry_custom_script; ?></label>
                <div class="col-sm-10">
                  <textarea name="custom_script_comment" id="input-custom_script_comment" cols="30" rows="10" class="form-control"><?php echo $custom_script_comment; ?></textarea>
                </div>
              </div>

            </div>
            <!-- Comment List Tab -->

            <div role="tabpanel" class="tab-pane fade in" id="commentbox" aria-labelledBy="commentbox-tab">             
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-title_comment"><?php echo $entry_title; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="title_comment" value="<?php echo $title_comment; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title_comment" class="form-control" />
                  <?php if ($error_title_comment) { ?>
                  <div class="text-danger"><?php echo $error_title_comment; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-titleicon_comment"><?php echo $entry_titleicon; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="titleicon_comment" value="<?php echo $titleicon_comment; ?>" placeholder="<?php echo $entry_titleicon; ?>" id="input-titleicon_comment" class="form-control" />
                  <?php if ($error_titleicon_comment) { ?>
                  <div class="text-danger"><?php echo $error_titleicon_comment; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-custom_style_commentbox"><?php echo $entry_custom_style; ?></label>
                <div class="col-sm-10">
                  <textarea name="custom_style_commentbox" id="input-custom_style_commentbox" cols="30" rows="10" class="form-control">
                    <?php echo $custom_style_commentbox; ?>
                  </textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-custom_script_commentbox"><?php echo $entry_custom_script; ?></label>
                <div class="col-sm-10">
                  <textarea name="custom_script_commentbox" id="input-custom_script_commentbox" cols="30" rows="10" class="form-control"><?php echo $custom_script_commentbox; ?></textarea>
                </div>
              </div>

            </div>
            <!-- comment Tab -->
          </div>
          <!-- Tab Contnt -->

        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>