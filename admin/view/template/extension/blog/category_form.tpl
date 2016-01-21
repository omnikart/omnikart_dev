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

  <div class="container-fluid" id="page-container">
    
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

    <div class="panel panel-default" id="panel-parent">
      <div class="panel-body">  
          
          <ul class="nav nav-tabs" style="margin-bottom:10px; background: #F5F5F5; padding: 3px 3px 0;">
            <li class="active"><a href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/category&amp;token=<?php echo $token; ?>"><?php echo $category_menu; ?></a></li>
            <li><a href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog&amp;token=<?php echo $token; ?>"><?php echo $post_menu; ?></a></li>
            <li><a href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/comment&amp;token=<?php echo $token; ?>"><?php echo $comment_menu; ?></a></li>
            <li><a href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/setting&amp;token=<?php echo $token; ?>"><?php echo $setting_menu; ?></a></li>
            <li><a href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/help&amp;token=<?php echo $token; ?>">
            <li><a href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/help&amp;token=<?php echo $token; ?>"><?php echo $help_menu; ?></a></li>
          </ul>

        <div id="main-content">

          <div class="row">
            <div class="col-lg-8">
              <h1 style="font-size:21px;font-weight:700;color:#20BFEF;"><?php echo $heading_title; ?></h1>
            </div>
            <div class="col-lg-4">
              <div class="pull-right">
                <button type="submit" form="form-catedit" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $save_btn_tooltip; ?>"><i class="fa fa-save"></i>&nbsp;<?php echo $save_btn; ?></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="<?php echo $cancel_btn_tooltip; ?>"><i class="fa fa-reply"></i>&nbsp;<?php echo $cancel_btn; ?></a>
              </div>
            </div>
          </div>

          <div id="category">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-catedit" class="form-horizontal" >
              
               <ul style="margin-bottom:10px;" class="nav nav-tabs">
                <li class="active"><a href="#general"  aria-controls="general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                <li><a href="#data" aria-controls="data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
                <li><a href="#design" aria-controls="design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
              </ul>   

              <div class="tab-content">

                  <div class="tab-pane active" id="general"> 

                    <ul class="nav nav-tabs" id="language">
                      <?php 
                      foreach ($languages as $language) { ?>
                      <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                      <?php } ?>
                    </ul>

                    <div class="tab-content">
                      <?php foreach ($languages as $language) { ?>
                      <div class="tab-pane table-responsiv" id="language<?php echo $language['language_id']; ?>">
                        <table class="table table-bordered" >
                          <tbody>
                            <tr>
                              <td width="15%" class="text-right required"><label for="input-name<?php echo $language['language_id']; ?>" class="control-label"><?php echo $entry_name; ?></label></td>
                              <td width="85%"><input type="text" name="cat_description[<?php echo $language['language_id']; ?>][name]"  value="<?php echo isset($cat_description[$language['language_id']]['name']) ? $cat_description[$language['language_id']]['name'] : ''; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control">
                                <?php if (isset($form_error[$language['language_id']]['name'])) { ?>
                                  <div class="text-danger"><?php echo $form_error[$language['language_id']]['name']; ?></div>
                                <?php } ?>
                              </td>
                            </tr>

                            <tr>
                              <td width="15%" class="text-right"><label for="input-slug<?php echo $language['language_id']; ?>" class="control-label"><?php echo $entry_slug; ?></label></td>
                              <td width="85%">
                                <input type="text" name="cat_description[<?php echo $language['language_id']; ?>][slug]"  value="<?php echo isset($cat_description[$language['language_id']]['slug']) ? $cat_description[$language['language_id']]['slug'] : ''; ?>" id="input-slug<?php echo $language['language_id']; ?>" class="form-control">
                                <?php if (isset($form_error[$language['language_id']]['slug'])) { ?>
                                  <div class="text-danger"><?php echo $form_error[$language['language_id']]['slug']; ?></div>
                                <?php } ?>
                              </td>
                            </tr>

                             <tr>
                              <td width="15%" class="text-right"><label for="input-content<?php echo $language['language_id']; ?>" class="control-label"><?php echo $entry_meta_desc; ?></label></td>
                              <td width="85%">
                                <textarea name="cat_description[<?php echo $language['language_id']; ?>][meta_description]" id="meta-description<?php echo $language['language_id']; ?>" cols="30" rows="5" class="form-control meta_description"><?php echo isset($cat_description[$language['language_id']]['meta_description']) ? $cat_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                                <?php if (isset($form_error['meta_description'])) { ?>
                                  <div class="text-danger"><?php echo $form_error['meta_description']; ?></div>
                                <?php } ?>
                              </td>
                            </tr>

                             <tr>
                              <td width="15%" class="text-right"><label for="input-col_meta_keyword<?php echo $language['language_id']; ?>" class="control-label"><?php echo $entry_meta_keyword; ?></label></td>
                              <td width="85%">
                                <textarea name="cat_description[<?php echo $language['language_id']; ?>][meta_keyword]" id="meta-keyword<?php echo $language['language_id']; ?>" cols="30" rows="5" class="form-control"><?php echo isset($cat_description[$language['language_id']]['meta_keyword']) ? $cat_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                                <?php if (isset($form_error['meta_keyword'])) { ?>
                                  <div class="text-danger"><?php echo $form_error['meta_keyword']; ?></div>
                                <?php } ?>
                              </td>
                            </tr>

                            <tr>
                              <td width="15%" class="text-right"><label for="post-content<?php echo $language['language_id']; ?>" class="control-label"><?php echo $entry_description; ?></label></td>
                              <td width="85%">
                                <textarea name="cat_description[<?php echo $language['language_id']; ?>][description]" id="descriptions<?php echo $language['language_id']; ?>" rows="3" class="form-control description"><?php echo isset($cat_description[$language['language_id']]['description']) ? $cat_description[$language['language_id']]['description'] : ''; ?></textarea>
                                <?php if (isset($form_error['description'])) { ?>
                                  <div class="text-danger"><?php echo $form_error['description']; ?></div>
                                <?php } ?>
                              </td>
                            </tr> 
                          </tbody>
                        </table>
                      </div>
                      <?php } // foreach end; ?>
                    </div>
                  </div>
                  <!-- #General -->

                <div class="tab-pane table-responsive" id="data">
                  <table class="table table-bordered">
                    <tbody>

                      <tr>
                        <td width="15%" class="text-right"><label for="input-parent" class="control-label"><?php echo $entry_parent; ?></label></td>
                        <!-- <label class="col-sm-2 control-label" for="input-parent"><?php echo $entry_parent; ?></label> -->
                        <td width="85%">
                          <input type="text" name="path" value="<?php echo $path; ?>" placeholder="<?php echo $entry_parent; ?>" id="input-parent" class="form-control" />
                          <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
                        </td>
                      </tr>

                      <tr>
                        <td width="15%" class="text-right"><label for="input-thumb" class="control-label"><?php echo $entry_thumb; ?></label></td>
                        <td width="85%">
                          <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" /></a>
                          <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                        </td>
                      </tr>

                      <tr>
                        <td width="15%" class="text-right"><label for="input-top" class="control-label"><?php echo $entry_top; ?></label></td>
                        <td width="85%">
                          <div class="checkbox">
                            <label>
                              <?php if ($top) { ?>
                              <input type="checkbox" name="top" value="1" checked="checked" id="input-top" />
                              <?php } else { ?>
                              <input type="checkbox" name="top" value="1" id="input-top" />
                              <?php } ?>
                              &nbsp; </label>
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <td width="15%" class="text-right"><label for="input-column" class="control-label"><?php echo $entry_column; ?></label></td>
                        <td width="85%">
                          <input type="text" name="column" value="<?php echo $column; ?>" placeholder="<?php echo $entry_column; ?>" id="input-column" class="form-control" />
                        </td>
                      </tr>

                      <tr>
                        <td width="15%" class="text-right"><label for="input-filter" class="control-label">
                          <span data-toggle="tooltip" title="" data-original-title="<?php echo $help_autocomplete; ?>"><?php echo $entry_filter; ?></span>
                        </td>
                        <td>
                          <input type="text" name="filter" value="" placeholder="<?php echo $entry_filter; ?>" id="input-filter" class="form-control" />
                          <div id="category-filter" class="well well-sm" style="height: 150px; overflow: auto;">
                            <?php foreach ($category_filters as $category_filter) { ?>
                            <div id="category-filter<?php echo $category_filter['filter_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $category_filter['name']; ?>
                              <input type="hidden" name="category_filter[]" value="<?php echo $category_filter['filter_id']; ?>" />
                            </div>
                            <?php } ?>
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <td width="15%" class="text-right"><label for="input-store" class="control-label"><?php echo $entry_store; ?></label></td>
                        <td>
                          <div id="category-store" class="well well-sm" style="height: 150px; overflow: auto;">
                            <div class="checkbox">
                              <label>
                                <?php if (in_array(0, $category_store)) { ?>
                                <input type="checkbox" name="category_store[]" value="0" checked="checked" />
                                <?php echo $text_default; ?>
                                <?php } else { ?>
                                <input type="checkbox" name="category_store[]" value="0" />
                                <?php echo $text_default; ?>
                                <?php } ?>
                              </label>
                            </div>
                            
                            <?php foreach ($stores as $store) { ?>
                            <div class="checkbox">
                              <label>
                                <?php if (in_array($store['store_id'], $category_store)) { ?>
                                <input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                                <?php echo $store['name']; ?>
                                <?php } else { ?>
                                <input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" />
                                <?php echo $store['name']; ?>
                                <?php } ?>
                              </label>
                            </div>
                            <?php } ?>
                          </div>
                          <!-- .well -->
                        </td>
                      </tr>

                      <tr>
                        <td width="15%" class="text-right"><label for="input-keyword" class="control-label">
                          <span data-toggle="tooltip" title="" data-original-title="<?php echo $help_keyword; ?>"><?php echo $entry_keyword; ?></span>
                        </td>
                        <td width="85%">
                          <input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
                          <?php if (isset($form_error['keyword'])) { ?>
                              <div class="text-danger"><?php echo $form_error['keyword']; ?></div>
                          <?php } ?>                
                        </td>
                      </tr>

                      <tr>
                        <td width="15%" class="text-right"><label for="input-status" class="control-label"><?php echo $entry_status; ?></label></td>
                        <td width="85%">
                          <select class="form-control" name="status" id="status">
                            <option value="publish" <?php echo ($status == 'publish') ? 'selected="selected"' : ''; ?>>Publish</option>
                            <option value="unpublish" <?php echo ($status == 'unpublish') ? 'selected="selected"' : ''; ?>>Unpublish</option>
                          </select>
                        </td>
                      </tr>

                      <tr>
                        <td width="15%" class="text-right"><label for="input-sort_order" class="control-label"><?php echo $entry_sort_order; ?></label></td>
                        <td width="85%">
                          <input type="number" name="sort_order" id="sort_order" value="<?php echo $sort_order; ?>" class="form-control">
                        </td>
                      </tr>
                      
                    </tbody>
                  </table>
                </div>
                <!--== #data ==-->

                <div class="tab-pane table-responsive" id="design">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-left"><?php echo $col_store; ?></td>
                        <td class="text-left"><?php echo $col_layout; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="text-right" width="15%"><label><?php echo $text_default; ?></label></td>
                        <td class="text-left" width="85%">
                          <select name="category_layout[0]" class="form-control">
                            <option value=""></option>
                            <?php foreach ($layouts as $layout) { ?>
                            <?php if (isset($category_layout[0]) && $category_layout[0] == $layout['layout_id']) { ?>
                            <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </td>
                      </tr>
                      <?php foreach ($stores as $store) { ?>
                      <tr>
                        <td class="text-left"><label for="category_layout"><?php echo $store['name']; ?></label>></td>
                        <td class="text-left">
                          <select name="category_layout[<?php echo $store['store_id']; ?>]" class="form-control">
                            <option value=""></option>
                            <?php foreach ($layouts as $layout) { ?>
                            <?php if (isset($category_layout[$store['store_id']]) && $category_layout[$store['store_id']] == $layout['layout_id']) { ?>
                            <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
                <!--== #design ==-->

              </div>
              <!-- tab-content -->

            </form>
          </div>
          <!--== #category ==-->

        </div>
        <!--== #main-content ==-->

      </div>
      <!-- panle-body -->
    </div>
    <!--== #panel-parent ==-->

  </div>
  <!--== #page-container ==-->
</div>
<!--== #content ==-->

<script type="text/javascript"><!--
CKEDITOR.replace('descriptions<?php echo $language['language_id']; ?>', {
	height:'200'
});
//--></script>

<script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script>

<script type="text/javascript"><!--
$('input[name=\'path\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=extension/blog/category_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        json.unshift({
          category_id: 0,
          name: '<?php echo $text_none; ?>'
        });

        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['category_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'path\']').val(item['label']);
    $('input[name=\'parent_id\']').val(item['value']);
  }
});
//--></script> 

  <script type="text/javascript"><!--
$('input[name=\'filter\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['filter_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter\']').val('');

    $('#category-filter' + item['value']).remove();

    $('#category-filter').append('<div id="category-filter' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="category_filter[]" value="' + item['value'] + '" /></div>');
  }
});

$('#category-filter').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});
//--></script> 

<?php echo $footer; ?>