<?php echo $header; ?>

<?php echo $column_left; ?>

<div id="content">

  <div id="page-header">
    <div class="container-fluid">
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <!-- #page-header -->

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
          <li><a href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/category&amp;token=<?php echo $token; ?>"><?php echo $category_menu; ?></a></li>
          <li class="active"><a href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog&amp;token=<?php echo $token; ?>"><?php echo $post_menu; ?></a></li>
          <li><a href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/comment&amp;token=<?php echo $token; ?>"><?php echo $comment_menu; ?></a></li>
          <li><a href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/setting&amp;token=<?php echo $token; ?>"><?php echo $setting_menu; ?></a></li>
          <li><a href="<?php echo HTTP_SERVER; ?>index.php?route=extension/blog/help&amp;token=<?php echo $token; ?>"><?php echo $help_menu; ?></a></li>
        </ul>

        <div id="main-content"> 

          <div class="row">
            <div class="col-lg-12">
              <h1 style="font-size:21px;font-weight:700;color:#20BFEF;border-bottom:1px solid #F5F5F5;margin-bottom:20px;padding:10px 0 5px 0;"><?php echo $heading_title; ?></h1>
            </div>
          </div>

          <div id="post">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-postedit" class="form-horizontal" >
                <div class="pull-right">
                  <button type="submit" form="form-postedit" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $save_btn_tooltip; ?>"><i class="fa fa-save"></i>&nbsp;<?php echo $save_btn; ?></button>
                  <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="<?php echo $cancel_btn_tooltip; ?>"><i class="fa fa-reply"></i>&nbsp;<?php echo $cancel_btn; ?></a>
                </div>
                
                <ul style="margin-bottom:10px;" class="nav nav-tabs">
                  <li class="active"><a href="#general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                  <li><a href="#data" aria-controls="data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
                  <li><a href="#relation" aria-controls="relation" data-toggle="tab"><?php echo $tab_relation; ?></a></li>
                  <li><a href="#image" aria-controls="image" data-toggle="tab"><?php echo $tab_image; ?></a></li>
                  <li><a href="#design" aria-controls="design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
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
                    <?php 
                    foreach ($languages as $language) { ?>
                      <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                        <div class="table-responsive">
                          <table class="table table-bordered">
                            <tbody>
                                <tr>
                                  <td width="15%" class="text-right required"><label for="input-title<?php echo $language['language_id']; ?>" class="control-label"><?php echo $entry_postTitle; ?></label></td>
                                  <td width="85%"><input type="text" name="post_description[<?php echo $language['language_id']; ?>][title]"  value="<?php echo isset($post_description[$language['language_id']]['title']) ? $post_description[$language['language_id']]['title'] : ''; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control">
                                    <?php if (isset($form_error[$language['language_id']]['title'])) { ?>
                                      <div class="text-danger"><?php echo $form_error[$language['language_id']]['title']; ?></div>
                                    <?php } ?>
                                  </td>
                                </tr>

                                <tr>
                                  <td width="15%" class="text-right"><label for="post-content<?php echo $language['language_id']; ?>" class="control-label"><?php echo $entry_content; ?></label></td>
                                  <td width="85%">
                                    <textarea name="post_description[<?php echo $language['language_id']; ?>][content]" cols="30" rows="10" class="form-control content" id="post-content<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($post_description[$language['language_id']]['content']) ? $post_description[$language['language_id']]['content'] : ''; ?></textarea>
                                    <?php if (isset($form_error[$language['language_id']]['content'])) { ?>
                                      <div class="text-danger"><?php echo $form_error[$language['language_id']]['content']; ?></div>
                                    <?php } ?>
                                  </td>
                                </tr>

                                <tr>
                                  <td width="15%" class="text-right"><label for="post-excerpt<?php echo $language['language_id']; ?>" class="control-label"><?php echo $entry_excerpt; ?></label></td>
                                  <td width="85%">
                                    <textarea name="post_description[<?php echo $language['language_id']; ?>][excerpt]" id="post-excerpt<?php echo $language['language_id']; ?>" cols="30" rows="5" class="form-control excerpt"><?php echo isset($post_description[$language['language_id']]['excerpt']) ? $post_description[$language['language_id']]['excerpt'] : ''; ?></textarea>
                                    <?php if (isset($form_error[$language['language_id']]['excerpt'])) { ?>
                                      <div class="text-danger"><?php echo $form_error[$language['language_id']]['excerpt']; ?></div>
                                    <?php } ?>
                                  </td>
                                </tr>

                                 <tr>
                                  <td width="15%" class="text-right"><label for="input-content<?php echo $language['language_id']; ?>" class="control-label"><?php echo $entry_meta_desc; ?></label></td>
                                  <td width="85%">
                                    <textarea name="post_description[<?php echo $language['language_id']; ?>][meta_description]" id="meta-description<?php echo $language['language_id']; ?>" cols="30" rows="5" class="form-control"><?php echo isset($post_description[$language['language_id']]['meta_description']) ? $post_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                                    <?php if (isset($form_error['meta_description'])) { ?>
                                      <div class="text-danger"><?php echo $form_error['meta_description']; ?></div>
                                    <?php } ?>
                                  </td>
                                </tr>

                                <tr>
                                  <td width="15%" class="text-right"><label for="input-content<?php echo $language['language_id']; ?>" class="control-label"><?php echo $entry_meta_key; ?></label></td>
                                  <td width="85%">
                                    <textarea name="post_description[<?php echo $language['language_id']; ?>][meta_keyword]" id="meta-keyword<?php echo $language['language_id']; ?>" cols="30" rows="5" class="form-control"><?php echo isset($post_description[$language['language_id']]['meta_keyword']) ? $post_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                                    <?php if (isset($form_error['meta_keyword'])) { ?>
                                      <div class="text-danger"><?php echo $form_error['meta_keyword']; ?></div>
                                    <?php } ?>
                                  </td>
                                </tr>

                                <tr>
                                  <td width="15%" class="text-right">
                                    <label for="input-tags<?php echo $language['language_id']; ?>" class="control-label">
                                      <span data-toggle="tooltip" title="" data-original-title="<?php echo $help_tags; ?>"><?php echo $entry_tag; ?></span>
                                    </label>
                                  </td>
                                  <td width="85%">
                                    <textarea name="post_description[<?php echo $language['language_id']; ?>][tag]" id="tags<?php echo $language['language_id']; ?>" rows="3" class="form-control"><?php echo isset($post_description[$language['language_id']]['tag']) ? $post_description[$language['language_id']]['tag'] : ''; ?></textarea>
                                    <?php if (isset($form_error['tag'])) { ?>
                                      <div class="text-danger"><?php echo $form_error['tag']; ?></div>
                                    <?php } ?>
                                  </td>
                                </tr>

                            </tbody>
                          </table>
                        </div>
                      </div>
                    <?php } // foreach loop; ?>
                    </div>
                  </div>
                  <!--== #general ==-->

                  <div class="tab-pane table-responsive" id="data">
                    <table class="table table-bordered">
                      <tbody>
                          <tr>
                            <td width="15%" class="text-right"><label for="input-img" class="control-label"><?php echo $entry_thumb; ?></label></td>
                            <td width="85%">
                              <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                              <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                            </td>
                          </tr>

                          <tr>
                            <td width="15%" class="text-right"><label for="input-comment" class="control-label"><?php echo $entry_comment; ?></label></td>
                            <td width="85%">
                              <select class="form-control" name="comment" id="comment">
                                <option value="open" <?php echo isset($post['comment_status']) && ($post['comment_status'] == 'open') ? 'selected="selected"' : null; ?>>Open</option>
                                <option value="close" <?php echo isset($post['comment_status']) && ($post['comment_status'] == 'close') ? 'selected="selected"' : null; ?>>Close</option>
                              </select>
                              <?php if (isset($form_error['comment'])) { ?>
                                <div class="text-danger"><?php echo $form_error['comment']; ?></div>
                              <?php } ?>
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
                            <td width="15%" class="text-right"><label for="input-date-available" class="control-label"><?php echo $entry_date_available; ?></label></td>
                            <td width="85%">
                              <div class="input-group date">
                                <input type="text" name="date_available" value="<?php echo $date_available; ?>" placeholder="<?php echo $entry_date_available; ?>" data-date-format="YYYY-MM-DD" id="input-date-available" class="form-control" />
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                              </div>
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
                            <td width="15%" class="text-right"><label for="input-sort-order" class="control-label"><?php echo $entry_sort_order; ?></label></td>
                            <td width="85%">
                              <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                            </td>
                          </tr>

                        </tbody>
                    </table>
                  </div>
                  <!--== #data ==-->

                  <div class="tab-pane" id="relation">
                    <div class="table-responsive">
                      <table class="table table-bordered">
                        <tbody>
                            <tr>
                              <td width="15%" class="text-right required"><label for="input-category" class="control-label">
                                <span data-toggle="tooltip" title="" data-original-title="<?php echo $help_autocomplete; ?>"><?php echo $entry_category; ?></span>
                              </td>
                              <td width="85%">
                                <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
                                <div id="post-category" class="well well-sm" style="height: 150px; overflow: auto;">
                                 <?php if($post_categories) { ?>  
                                    <?php foreach ($post_categories as $category) { ?>
                                    <div id="post-category<?php echo $category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $category['name']; ?>
                                      <input type="hidden" name="post_category[]" value="<?php echo $category['category_id']; ?>" />
                                    </div>
                                    <?php } ?>
                                  <?php } ?>
                                </div>
                                <?php if (isset($form_error['category'])) { ?>
                                  <div class="text-danger"><?php echo $form_error['category']; ?></div>
                                <?php } ?>
                              </td>
                            </tr>

                            <tr>
                              <td width="15%" class="text-right"><label for="input-filter" class="control-label">
                                <span data-toggle="tooltip" title="" data-original-title="<?php echo $help_autocomplete; ?>"><?php echo $entry_filter; ?></span>
                              </td>
                              <td width="85%">
                                <input type="text" name="filter" value="" placeholder="<?php echo $entry_filter; ?>" id="input-filter" class="form-control" />
                                <div id="post-filter" class="well well-sm" style="height: 150px; overflow: auto;">
                                  <?php foreach ($post_filters as $post_filter) { ?>
                                  <div id="post-filter<?php echo $post_filter['filter_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $post_filter['name']; ?>
                                    <input type="hidden" name="post_filter[]" value="<?php echo $post_filter['filter_id']; ?>" />
                                  </div>
                                  <?php } ?>
                                </div>
                              </td>
                            </tr>

                            <tr>
                              <td width="15%" class="text-right"><label for="input-store" class="control-label"><?php echo $entry_store; ?></label></td>
                              <td width="85%">
                                <div id="post-store" class="well well-sm" style="height: 150px; overflow: auto;">
                                   
                                   <div class="checkbox">
                                    <label>
                                      <?php if (in_array(0, $post_store)) { ?>
                                      <input type="checkbox" name="post_store[]" value="0" checked="checked" />
                                      <?php echo $text_default; ?>
                                      <?php } else { ?>
                                      <input type="checkbox" name="post_store[]" value="0" />
                                      <?php echo $text_default; ?>
                                      <?php } ?>
                                    </label>
                                  </div>
                                  
                                  <?php foreach ($stores as $store) { ?>
                                    <div class="checkbox">
                                      <label>
                                        <?php if (in_array($store['store_id'], $post_store)) { ?>
                                        <input type="checkbox" name="post_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                                        <?php echo $store['name']; ?>
                                        <?php } else { ?>
                                        <input type="checkbox" name="post_store[]" value="<?php echo $store['store_id']; ?>" />
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
                              <td width="15%" class="text-right"><label class="control-label" for="input-related">
                                <span data-toggle="tooltip" title="" data-original-title="<?php echo $help_autocomplete; ?>"><?php echo $entry_related; ?></span>
                              </td>
                              <td width="85%">
                                  <input type="text" name="related" value="" placeholder="<?php echo $entry_related; ?>" id="input-related" class="form-control" />
                                  <div id="product-related" class="well well-sm" style="height: 150px; overflow: auto;">
                                  <?php if(isset($related_products)){ ?>  
                                    <?php foreach ($related_products as $product) { ?>
                                      <div id="product-related<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
                                        <input type="hidden" name="related_product[]" value="<?php echo $product['product_id']; ?>" />
                                      </div>
                                    <?php } ?>
                                  <?php } ?>
                                  </div>
                              </td>
                            </tr>

                        </tbody>
                      </table>
                    </div>
                  </div>

                  <div class="tab-pane" id="image">
                    <div class="table-responsive">
                      <table id="images" class="table table-bordered">
                        <thead>
                          <tr>
                            <td class="text-left"><?php echo $col_image; ?></td>
                            <td class="text-right"><?php echo $col_order; ?></td>
                            <td><?php echo $col_action; ?></td>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $image_row = 0; ?>
                          <?php foreach ($post_images as $post_image) { ?>
                          <tr id="image-row<?php echo $image_row; ?>">
                            <td class="text-left"><a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $post_image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="post_image[<?php echo $image_row; ?>][meta_value]" value="<?php echo $post_image['image']; ?>" id="input-image<?php echo $image_row; ?>" /></td>
                            <td class="text-right"><input type="text" name="post_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $post_image['sort_order']; ?>" placeholder="<?php echo $col_order; ?>" class="form-control" /></td>
                            <td class="text-left"><button type="button" onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                          </tr>
                          <?php $image_row++; ?>
                          <?php } ?>

                        </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="2"></td>
                            <td class="text-left"><button type="button" onclick="addImage();" data-toggle="tooltip" title="<?php echo $image_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                  <!--== #Image ==-->

                  <div class="tab-pane" id="design">
                    <div class="table-responsive">
                      <table class="table table-bordered">
                        <thead>
                          <tr class="active">
                            <td class="text-left"><?php echo $col_store; ?></td>
                            <td class="text-left"><?php echo $col_layout; ?></td>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="text-right" width="15%"><?php echo $text_default; ?></td>
                            <td class="text-left" width="85%">
                              <select name="post_layout[0]" class="form-control">
                                <option value=""></option>
                                <?php foreach ($layouts as $layout) { ?>
                                <?php if (isset($post_layout[0]) && $post_layout[0] == $layout['layout_id']) { ?>
                                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                              </select></td>
                          </tr>
                          <?php foreach ($stores as $store) { ?>
                          <tr>
                            <td class="text-right"><?php echo $store['name']; ?></td>
                            <td class="text-left"><select name="post_layout[<?php echo $store['store_id']; ?>]" class="form-control">
                                <option value=""></option>
                                <?php foreach ($layouts as $layout) { ?>
                                <?php if (isset($post_layout[$store['store_id']]) && $post_layout[$store['store_id']] == $layout['layout_id']) { ?>
                                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                              </select></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!--== #Design ==-->
                </div>
                <!--== #main-content ==-->
            </form>
          </div>
          <!--== #Post ==-->

        </div>
        <!--== .Main-content ==-->

      </div>
      <!-- .panel-body -->
    </div>
    <!--== #panel-parent ==-->

  </div>
  <!--== #page-container ==-->
</div>
<!-- #content -->

<script type="text/javascript"><!--
  $('.date').datetimepicker({
    pickTime: false
  });
  CKEDITOR.replace('post-content<?php echo $language['language_id']; ?>', {
	height:'300'
  });
  CKEDITOR.replace('post-excerpt<?php echo $language['language_id']; ?>', {
	height:'300'
  });
//--></script>

  <script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
  html  = '<tr id="image-row' + image_row + '">';
  html += '  <td class="text-left"><a id="thumbImage' + image_row + '" href="" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="post_image[' + image_row + '][meta_value]" value="" id="inputImage' + image_row + '" /></td>';
  html += '  <td class="text-right"><input type="text" name="post_image[' + image_row + '][sort_order]" value="" placeholder="<?php echo $col_order; ?>" class="form-control" /></td>';
  html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';
  
  $('#images tbody').append(html);
  
  image_row++;
}
//--></script>

<script type="text/javascript"><!--
  $('input[name=\'category\']').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=extension/blog/category_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
        dataType: 'json',     
        success: function(json) {
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
      $('input[name=\'category\']').val('');
      
      $('#post-category' + item['value']).remove();
      
      $('#post-category').append('<div id="post-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="post_category[]" value="' + item['value'] + '" /></div>'); 
    }
  });

  $('#post-category').delegate('.fa-minus-circle', 'click', function() {
    $(this).parent().remove();
});

// Related
$('input[name=\'related\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',     
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['product_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'related\']').val('');
    
    $('#product-related' + item['value']).remove();
    
    $('#product-related').append('<div id="product-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="related_product[]" value="' + item['value'] + '" /></div>');  
  } 
});

$('#product-related').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});

// Filter
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
    
    $('#post-filter' + item['value']).remove();
    
    $('#post-filter').append('<div id="post-filter' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="post_filter[]" value="' + item['value'] + '" /></div>'); 
  } 
});

$('#post-filter').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});

//--></script>

<script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script>

<?php echo $footer; ?>