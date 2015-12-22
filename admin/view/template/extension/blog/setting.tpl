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
          <li><a href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/comment&amp;token=<?php echo $token; ?>"><?php echo $comment_menu; ?></a></li>
          <li class="active"><a href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/setting&amp;token=<?php echo $token; ?>"><?php echo $setting_menu; ?></a></li>
          <li><a href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/help&amp;token=<?php echo $token; ?>"><?php echo $help_menu; ?></a></li>
        </ul>
        <div class="tab-content">

          <!-- SETTING -->
          <div class="tab-pane active" id="setting">

            <div style="margin-bottom:10px;" class="pane-heading">
              <div class="row">
                <div class="col-lg-8">
                  <h1 style="font-size:21px;font-weight:700;color:#20BFEF;"><?php echo $heading_title; ?></h1>
                </div>
                <div class="col-lg-4">
                  <div class="pull-right">
                    <button type="submit" form="form-setting" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $save_btn_tooltip; ?>"><i class="fa fa-edit"></i>&nbsp;<?php echo $save_btn; ?></button>
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
            </div>

            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal" >

            <ul style="margin-bottom:10px;" class="nav nav-tabs" id="cat-tabs">
              <li class="active"><a href="#general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
              <li><a href="#option" aria-controls="option" data-toggle="tab"><?php echo $tab_option; ?></a></li>
              <li><a href="#image" aria-controls="image" data-toggle="tab"><?php echo $tab_image; ?></a></li>
              <li><a href="#color" aria-controls="color" data-toggle="tab"><?php echo $tab_color; ?></a></li>
              <li><a href="#social" aria-controls="social" data-toggle="tab"><?php echo $tab_social; ?></a></li>
            </ul>  

            <div class="tab-content">
              <div class="tab-pane active table-responsive" id="general"> 
                <ul class="nav nav-tabs" id="language">
                  <?php 
                  foreach ($languages as $language) { ?>
                  <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                  <?php } ?>
                </ul>
                <div class="tab-content">
                    <?php foreach ($languages as $language) { ?>
                      <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                        <table class="table table-bordered" >
                          <tbody>
                            <tr>
                              <td width="15%" class="text-right"><label for="input-name<?php echo $language['language_id']; ?>" class="control-label"><?php echo $entry_name; ?></label></td>
                              <td width="85%"><input type="text" name="general_setting[<?php echo $language['language_id']; ?>][name]"  value="<?php echo isset($general_setting[$language['language_id']]['name']) ? $general_setting[$language['language_id']]['name'] : ''; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control">
                                <?php if (isset($form_error[$language['language_id']]['name'])) { ?>
                                  <div class="text-danger"><?php echo $form_error[$language['language_id']]['name']; ?></div>
                                <?php } ?>
                              </td>
                            </tr>

                            <tr>
                              <td width="15%" class="text-right"><label for="input-title<?php echo $language['language_id']; ?>" class="control-label"><?php echo $entry_title; ?></label></td>
                              <td width="85%"><input type="text" name="general_setting[<?php echo $language['language_id']; ?>][title]"  value="<?php echo isset($general_setting[$language['language_id']]['title']) ? $general_setting[$language['language_id']]['title'] : ''; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control">
                                <?php if (isset($form_error[$language['language_id']]['title'])) { ?>
                                  <div class="text-danger"><?php echo $form_error[$language['language_id']]['title']; ?></div>
                                <?php } ?>
                              </td>
                            </tr>

                            <tr>
                              <td width="15%" class="text-right"><label for="input-meta_description<?php echo $language['language_id']; ?>" class="control-label"><?php echo $entry_meta_desc; ?></label></td>
                              <td width="85%">
                                <textarea name="general_setting[<?php echo $language['language_id']; ?>][meta_description]" id="meta-description<?php echo $language['language_id']; ?>" cols="30" rows="5" class="form-control meta_description"><?php echo isset($general_setting[$language['language_id']]['meta_description']) ? $general_setting[$language['language_id']]['meta_description'] : ''; ?></textarea>
                                <?php if (isset($form_error['meta_description'])) { ?>
                                  <div class="text-danger"><?php echo $form_error['meta_description']; ?></div>
                                <?php } ?>
                              </td>
                            </tr>

                             <tr>
                              <td width="15%" class="text-right"><label for="input-entry_meta_keyword<?php echo $language['language_id']; ?>" class="control-label"><?php echo $entry_meta_keyword; ?></label></td>
                              <td width="85%">
                                <textarea name="general_setting[<?php echo $language['language_id']; ?>][meta_keyword]" id="meta-keyword<?php echo $language['language_id']; ?>" cols="30" rows="5" class="form-control"><?php echo isset($general_setting[$language['language_id']]['meta_keyword']) ? $general_setting[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                                <?php if (isset($form_error['meta_keyword'])) { ?>
                                  <div class="text-danger"><?php echo $form_error['meta_keyword']; ?></div>
                                <?php } ?>
                              </td>
                            </tr>

                          </tbody>
                        </table>
                      </div>
                    <?php } // foreach end; ?>
                </div>
                <!-- .tab-content -->
              </div>
              <!-- #general -->

              <div class="tab-pane table-responsive" id="option"> 
                <table class="table table-bordered">
                    <thead>
                      <tr class="active">
                        <th width="2%">#</th>
                        <th width="20%"><?php echo $col_settingName; ?></th>
                        <th width="70%"><?php echo $col_settingContent; ?></th>
                        <th width="8%"><?php echo $col_settingSortOrder; ?></th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php $inc = 1; ?>
                        <?php foreach ($setting_option as $setting) : ?>                        
                          <tr>
                            <td width="2%"><?php echo $inc; ?></td>
                            <td width="15%" class="text-right"><label form="form-control"><?php echo ucfirst(str_replace('_', ' ', $setting['setting_name'])); ?></label></td>
                            <td width="83%">
                               <?php
                                switch ($setting['setting_name']) {
                                  case 'comment_autoapprove':
                                    ?>
                                      <select name="setting[<?php echo $setting['setting_id']; ?>][name]" id="setting_name<?php echo $setting['setting_id']; ?>">
                                        <option <?php echo $setting['setting_value'] == 1 ? 'selected="selected"':''; ?> value="1">Yes</option>
                                        <option <?php echo $setting['setting_value'] == 0 ? 'selected="selected"':''; ?> value="0">No</option>
                                      </select>
                                    <?php
                                    break;
                                  
                                  default:
                                  ?>
                                    <input type="text" name="setting[<?php echo $setting['setting_id']; ?>][name]" id="setting_name<?php echo $setting['setting_id']; ?>" value="<?php echo isset($setting['setting_value']) && $setting['setting_value'] ? $setting['setting_value'] : ''; ?>" class="form-control">
                                  <?php  
                                    break;
                                }
                                ?>
                              
                            </td>
                            <td><input type="text" name="setting[<?php echo $setting['setting_id']; ?>][position]" id="setting_position<?php echo $setting['setting_id']; ?>" value="<?php echo isset($setting['position']) && $setting['position'] ? $setting['position'] : ''; ?>" class="form-control"></td>
                          </tr>
                          <?php $inc++; ?>
                        <?php endforeach; ?>
                       
                    </tbody>
                  </table>
              </div>
              <!-- #options -->

              <div class="tab-pane table-responsive" id="image"> 
                  <table class="table table-bordered">
                    <thead>
                      <tr class="active">
                        <th width="2%">#</th>
                        <th width="20%"><?php echo $col_settingName; ?></th>
                        <th width="70%"><?php echo $col_settingContent; ?></th>
                        <th width="8%"><?php echo $col_settingSortOrder; ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      
                        <?php $inc = 1; ?>
                        <?php foreach ($setting_image as $setting) : ?>
                          <tr>
                            <td width="2%"><?php echo $inc; ?></td>
                            <td width="15%" class="text-right"><label form="form-control"><?php echo ucfirst(str_replace('_', ' ', $setting['setting_name'])); ?></label></td>
                            <td width="83%">
                              <?php
                              switch ($setting['setting_name']) {
                                case 'blog_logo':
                                  ?>
                                    <a href="" id="blog-logo" data-toggle="image" class="img-thumbnail"><img src="<?php echo $blog_logo; ?>" alt="" title="" /></a>
                                    <input type="hidden" name="setting[<?php echo $setting['setting_id']; ?>][name]" value="<?php echo $image_logo; ?>" id="blog_logo" />
                                  <?php
                                  break;
                                case 'blog_icon':
                                  ?>
                                    <a href="" id="blog-icon" data-toggle="image" class="img-thumbnail"><img src="<?php echo $blog_icon; ?>" alt="" title="" /></a>
                                    <input type="hidden" name="setting[<?php echo $setting['setting_id']; ?>][name]" value="<?php echo $image_icon; ?>" id="blog_icon" />
                                  <?php
                                  break;
                                case 'post_thumbnail_visibility':
                                  ?>
                                    <select name="setting[<?php echo $setting['setting_id']; ?>][name]" id="setting_name<?php echo $setting['setting_id']; ?>">
                                      <option <?php echo $setting['setting_value'] == '1' ? 'selected="selected"':''; ?> value="1">Show</option>
                                      <option <?php echo $setting['setting_value'] == '0' ? 'selected="selected"':''; ?> value="0">Hidden</option>
                                    </select>
                                  <?php
                                  break;
                                case 'post_thumbnail_type':
                                  ?>
                                    <select name="setting[<?php echo $setting['setting_id']; ?>][name]" id="setting_name<?php echo $setting['setting_id']; ?>">
                                      <option <?php echo $setting['setting_value'] == 'slide' ? 'selected="selected"':''; ?> value="slide">Slide</option>
                                      <option <?php echo $setting['setting_value'] == 'static' ? 'selected="selected"':''; ?> value="static">Static</option>
                                    </select>
                                  <?php
                                  break;
                                case 'post_thumbnail_position':
                                  ?>
                                    <select name="setting[<?php echo $setting['setting_id']; ?>][name]" id="setting_name<?php echo $setting['setting_id']; ?>">
                                      <option <?php echo $setting['setting_value'] == 'left' ? 'selected="selected"':''; ?> value="left">Left</option>
                                      <option <?php echo $setting['setting_value'] == 'top' ? 'selected="selected"':''; ?> value="top">Top</option>
                                      <option <?php echo $setting['setting_value'] == 'right' ? 'selected="selected"':''; ?> value="right">Right</option>
                                    </select>
                                  <?php
                                  break;
                                
                                default:
                                ?>
                                  <input type="text" name="setting[<?php echo $setting['setting_id']; ?>][name]" id="setting_name<?php echo $setting['setting_id']; ?>" value="<?php echo isset($setting['setting_value']) && $setting['setting_value'] ? $setting['setting_value'] : ''; ?>" class="form-control">
                                  <?php
                                  break;
                              }
                              ?>
                              </td>
                            <td><input type="text" name="setting[<?php echo $setting['setting_id']; ?>][position]" id="setting_position<?php echo $setting['setting_id']; ?>" value="<?php echo isset($setting['position']) && $setting['position'] ? $setting['position'] : ''; ?>" class="form-control"></td>
                          </tr>
                          <?php $inc++; ?>
                        <?php endforeach; ?>

                    </tbody>
                  </table>
              </div>
              <!-- #images -->

              <div class="tab-pane table-responsive" id="color"> 
                  <table class="table table-bordered">
                    <thead>
                      <tr class="active">
                        <th width="2%">#</th>
                        <th width="20%"><?php echo $col_settingName; ?></th>
                        <th width="70%"><?php echo $col_settingContent; ?></th>
                        <th width="8%"><?php echo $col_settingSortOrder; ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      
                        <?php $inc = 1; ?>
                        <?php foreach ($setting_color as $setting) : ?>
                          <tr>
                            <td width="2%"><?php echo $inc; ?></td>
                            <td width="15%" class="text-right"><label form="form-control"><?php echo ucfirst(str_replace('_', ' ', $setting['setting_name'])); ?></label></td>
                            <td width="83%">
                              <input type="text" name="setting[<?php echo $setting['setting_id']; ?>][name]" id="setting_name<?php echo $setting['setting_id']; ?>" value="<?php echo isset($setting['setting_value']) && $setting['setting_value'] ? $setting['setting_value'] : ''; ?>" class="form-control"></td>
                            <td><input type="text" name="setting[<?php echo $setting['setting_id']; ?>][position]" id="setting_position<?php echo $setting['setting_id']; ?>" value="<?php echo isset($setting['position']) && $setting['position'] ? $setting['position'] : ''; ?>" class="form-control"></td>
                          </tr>
                          <?php $inc++; ?>
                        <?php endforeach; ?>

                    </tbody>
                  </table>
              </div>
              <!-- #colors -->

              <div class="tab-pane table-responsive" id="social"> 
                
                  <table class="table table-bordered">
                    <thead>
                      <tr class="active">
                        <th width="2%">#</th>
                        <th width="20%"><?php echo $col_settingName; ?></th>
                        <th width="70%"><?php echo $col_settingContent; ?></th>
                        <th width="8%"><?php echo $col_settingSortOrder; ?></th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php $inc = 1; ?>
                        <?php foreach ($setting_social as $setting) : ?>
                          <tr>
                            <td width="2%"><?php echo $inc; ?></td>
                            <td width="15%" class="text-right"><label form="form-control"><?php echo ucfirst(str_replace('_', ' ', $setting['setting_name'])); ?></label></td>
                            <td width="83%">
                              <input type="text" name="setting[<?php echo $setting['setting_id']; ?>][name]" id="setting_name<?php echo $setting['setting_id']; ?>" value="<?php echo isset($setting['setting_value']) && $setting['setting_value'] ? $setting['setting_value'] : ''; ?>" class="form-control"></td>
                            <td><input type="text" name="setting[<?php echo $setting['setting_id']; ?>][position]" id="setting_position<?php echo $setting['setting_id']; ?>" value="<?php echo isset($setting['position']) && $setting['position'] ? $setting['position'] : ''; ?>" class="form-control"></td>
                          </tr>
                          <?php $inc++; ?>
                        <?php endforeach; ?>
                       
                    </tbody>
                  </table>
      
              </div>
              <!-- #social -->

              </form>

            </div>
            <!-- .tab-content -->

          </div>
          <!-- .SETTING -->

        </div>
        <!-- tab-content -->

      </div>
      <!-- panel-body -->
    </div>
    <!-- .panel -->
    </form>
  </div>
  <!-- .container-fluid -->
</div>
<!-- #content -->


<script type="text/javascript"><!--
  $('.meta_description').summernote({
    height: 100
  });
//--></script>

<script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script>

<?php echo $footer; ?>