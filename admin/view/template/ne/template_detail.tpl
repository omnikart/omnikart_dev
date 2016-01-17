<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
            </div>
            <h1><?php echo $heading_title; ?></h1>
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
                <h3 class="panel-title"><i class="fa fa-files-o"></i> <?php echo $text_newsletter_template; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
                    <fieldset>
                        <legend><?php echo $text_settings; ?></legend>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-uri"><?php echo $entry_product_template; ?></label>
                            <div class="col-sm-10">
                                <select name="uri" id="input-uri" class="form-control">
                                    <?php foreach ($parts as $key => $part) { ?>
                                        <?php if ($uri == $key) { ?>
                                            <option value="<?php echo $key; ?>" selected="selected"><?php echo $part; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $key; ?>"><?php echo $part; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_image_size; ?>"><?php echo $entry_image_size; ?></span></label>
                            <div class="col-sm-10">
                                <div class="form-inline">
                                    <div class="form-group" style="margin-left:0; margin-right:15px;">
                                        <input type="text" name="product_image_width" size="3" value="<?php echo $product_image_width; ?>" class="form-control" />
                                    </div>
                                    <div class="form-group" style="margin-left:0; margin-right:15px;">
                                        <input type="text" name="product_image_height" size="3" value="<?php echo $product_image_height; ?>" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_show_prices; ?></label>
                            <div class="col-sm-10">
                                <label class="radio-inline">
                                    <?php if ($product_show_prices) { ?>
                                        <input type="radio" name="product_show_prices" value="1" checked="checked" />
                                        <?php echo $entry_yes; ?>
                                    <?php } else { ?>
                                        <input type="radio" name="product_show_prices" value="1" />
                                        <?php echo $entry_yes; ?>
                                    <?php } ?>
                                </label>
                                <label class="radio-inline">
                                    <?php if (!$product_show_prices) { ?>
                                        <input type="radio" name="product_show_prices" value="0" checked="checked" />
                                        <?php echo $entry_no; ?>
                                    <?php } else { ?>
                                        <input type="radio" name="product_show_prices" value="0" />
                                        <?php echo $entry_no; ?>
                                    <?php } ?>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_show_savings; ?></label>
                            <div class="col-sm-10">
                                <label class="radio-inline">
                                    <?php if ($product_show_savings) { ?>
                                        <input type="radio" name="product_show_savings" value="1" checked="checked" />
                                        <?php echo $entry_yes; ?>
                                    <?php } else { ?>
                                        <input type="radio" name="product_show_savings" value="1" />
                                        <?php echo $entry_yes; ?>
                                    <?php } ?>
                                </label>
                                <label class="radio-inline">
                                    <?php if (!$product_show_savings) { ?>
                                        <input type="radio" name="product_show_savings" value="0" checked="checked" />
                                        <?php echo $entry_no; ?>
                                    <?php } else { ?>
                                        <input type="radio" name="product_show_savings" value="0" />
                                        <?php echo $entry_no; ?>
                                    <?php } ?>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-product-description-length"><span data-toggle="tooltip" title="<?php echo $help_description_length; ?>"><?php echo $entry_description_length; ?></span></label>
                            <div class="col-sm-10 col-md-2">
                                <input type="text" name="product_description_length" value="<?php echo $product_description_length; ?>" id="input-product-description-length" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-product-cols"><?php echo $entry_columns; ?></label>
                            <div class="col-sm-10 col-md-2">
                                <input type="text" name="product_cols" value="<?php echo $product_cols; ?>" id="input-product-cols" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-specials-count"><span data-toggle="tooltip" title="<?php echo $help_specials_count; ?>"><?php echo $entry_specials_count; ?></span></label>
                            <div class="col-sm-10 col-md-2">
                                <input type="text" name="specials_count" value="<?php echo $specials_count; ?>" id="input-specials-count" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-latest-count"><span data-toggle="tooltip" title="<?php echo $help_latest_count; ?>"><?php echo $entry_latest_count; ?></span></label>
                            <div class="col-sm-10 col-md-2">
                                <input type="text" name="latest_count" value="<?php echo $latest_count; ?>" id="input-latest-count" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-popular-count"><span data-toggle="tooltip" title="<?php echo $help_popular_count; ?>"><?php echo $entry_popular_count; ?></span></label>
                            <div class="col-sm-10 col-md-2">
                                <input type="text" name="popular_count" value="<?php echo $popular_count; ?>" id="input-popular-count" class="form-control" />
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend><?php echo $text_styles; ?></legend>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-heading-color"><span data-toggle="tooltip" title="<?php echo $help_heading_color; ?>"><?php echo $entry_heading_color; ?></span></label>
                            <div class="col-sm-10 col-md-4">
                                <div class="input-group color">
                                    <input type="text" name="heading_color" value="<?php echo $heading_color; ?>" id="input-heading-color" class="form-control" />
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-heading-style"><span data-toggle="tooltip" title="<?php echo $help_heading_style; ?>"><?php echo $entry_heading_style; ?></span></label>
                            <div class="col-sm-10">
                                <input type="text" name="heading_style" value="<?php echo $heading_style; ?>" id="input-heading-style" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-product-name-color"><span data-toggle="tooltip" title="<?php echo $help_name_color; ?>"><?php echo $entry_name_color; ?></span></label>
                            <div class="col-sm-10 col-md-4">
                                <div class="input-group color">
                                    <input type="text" name="product_name_color" value="<?php echo $product_name_color; ?>" id="input-product-name-color" class="form-control" />
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-product-name-style"><span data-toggle="tooltip" title="<?php echo $help_name_style; ?>"><?php echo $entry_name_style; ?></span></label>
                            <div class="col-sm-10">
                                <input type="text" name="product_name_style" value="<?php echo $product_name_style; ?>" id="input-product-name-style" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-product-model-color"><span data-toggle="tooltip" title="<?php echo $help_model_color; ?>"><?php echo $entry_model_color; ?></span></label>
                            <div class="col-sm-10 col-md-4">
                                <div class="input-group color">
                                    <input type="text" name="product_model_color" value="<?php echo $product_model_color; ?>" id="input-product-model-color" class="form-control" />
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-product-model-style"><span data-toggle="tooltip" title="<?php echo $help_model_style; ?>"><?php echo $entry_model_style; ?></span></label>
                            <div class="col-sm-10">
                                <input type="text" name="product_model_style" value="<?php echo $product_model_style; ?>" id="input-product-model-style" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-product-price-color"><span data-toggle="tooltip" title="<?php echo $help_price_color; ?>"><?php echo $entry_price_color; ?></span></label>
                            <div class="col-sm-10 col-md-4">
                                <div class="input-group color">
                                    <input type="text" name="product_price_color" value="<?php echo $product_price_color; ?>" id="input-product-price-color" class="form-control" />
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-product-price-style"><span data-toggle="tooltip" title="<?php echo $help_price_style; ?>"><?php echo $entry_price_style; ?></span></label>
                            <div class="col-sm-10">
                                <input type="text" name="product_price_style" value="<?php echo $product_price_style; ?>" id="input-product-price-style" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-product-price-color-special"><span data-toggle="tooltip" title="<?php echo $help_price_color_special; ?>"><?php echo $entry_price_color_special; ?></span></label>
                            <div class="col-sm-10 col-md-4">
                                <div class="input-group color">
                                    <input type="text" name="product_price_color_special" value="<?php echo $product_price_color_special; ?>" id="input-product-price-color-special" class="form-control" />
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-product-price-style-special"><span data-toggle="tooltip" title="<?php echo $help_price_style_special; ?>"><?php echo $entry_price_style_special; ?></span></label>
                            <div class="col-sm-10">
                                <input type="text" name="product_price_style_special" value="<?php echo $product_price_style_special; ?>" id="input-product-price-style-special" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-product-price-color-when-special"><span data-toggle="tooltip" title="<?php echo $help_price_color_when_special; ?>"><?php echo $entry_price_color_when_special; ?></span></label>
                            <div class="col-sm-10 col-md-4">
                                <div class="input-group color">
                                    <input type="text" name="product_price_color_when_special" value="<?php echo $product_price_color_when_special; ?>" id="input-product-price-color-when-special" class="form-control" />
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-product-price-style-when-special"><span data-toggle="tooltip" title="<?php echo $help_price_style_when_special; ?>"><?php echo $entry_price_style_when_special; ?></span></label>
                            <div class="col-sm-10">
                                <input type="text" name="product_price_style_when_special" value="<?php echo $product_price_style_when_special; ?>" id="input-product-price-style-when-special" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-product-description-color"><span data-toggle="tooltip" title="<?php echo $help_description_color; ?>"><?php echo $entry_description_color; ?></span></label>
                            <div class="col-sm-10 col-md-4">
                                <div class="input-group color">
                                    <input type="text" name="product_description_color" value="<?php echo $product_description_color; ?>" id="input-product-description-color" class="form-control" />
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-product-description-style"><span data-toggle="tooltip" title="<?php echo $help_description_style; ?>"><?php echo $entry_description_style; ?></span></label>
                            <div class="col-sm-10">
                                <input type="text" name="product_description_style" value="<?php echo $product_description_style; ?>" id="input-product-description-style" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-product-savings-color"><span data-toggle="tooltip" title="<?php echo $help_savings_color; ?>"><?php echo $entry_savings_color; ?></span></label>
                            <div class="col-sm-10 col-md-4">
                                <div class="input-group color">
                                    <input type="text" name="product_savings_color" value="<?php echo $product_savings_color; ?>" id="input-product-savings-color" class="form-control" />
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-product-savings-style"><span data-toggle="tooltip" title="<?php echo $help_savings_style; ?>"><?php echo $entry_savings_style; ?></span></label>
                            <div class="col-sm-10">
                                <input type="text" name="product_savings_style" value="<?php echo $product_savings_style; ?>" id="input-product-savings-style" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-custom-css"><?php echo $entry_custom_css; ?></label>
                            <div class="col-sm-10">
                                <textarea name="custom_css" rows="10" id="input-custom-css" class="form-control"><?php echo $custom_css; ?></textarea>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend><?php echo $text_template; ?></legend>
                        <ul class="nav nav-tabs" id="languages">
                            <?php foreach ($languages as $language) { ?>
                                <li><a href="#language-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                            <?php } ?>
                        </ul>
                        <div class="tab-content">
                            <?php foreach ($languages as $language) { ?>
                                <div class="tab-pane" id="language-<?php echo $language['language_id']; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-subject-<?php echo $language['language_id']; ?>"><?php echo $entry_subject; ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="subject[<?php echo $language['language_id']; ?>]" value="<?php echo isset($subject[$language['language_id']]) ? $subject[$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_subject; ?>" id="input-subject-<?php echo $language['language_id']; ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-defined-text-<?php echo $language['language_id']; ?>"><?php echo $entry_defined_text; ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="defined_text[<?php echo $language['language_id']; ?>]" value="<?php echo isset($defined_text[$language['language_id']]) ? $defined_text[$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_defined_text; ?>" id="input-defined-text-<?php echo $language['language_id']; ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-special-text-<?php echo $language['language_id']; ?>"><?php echo $entry_special_text; ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="special_text[<?php echo $language['language_id']; ?>]" value="<?php echo isset($special_text[$language['language_id']]) ? $special_text[$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_special_text; ?>" id="input-special-text-<?php echo $language['language_id']; ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-latest-text-<?php echo $language['language_id']; ?>"><?php echo $entry_latest_text; ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="latest_text[<?php echo $language['language_id']; ?>]" value="<?php echo isset($latest_text[$language['language_id']]) ? $latest_text[$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_latest_text; ?>" id="input-latest-text-<?php echo $language['language_id']; ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-popular-text-<?php echo $language['language_id']; ?>"><?php echo $entry_popular_text; ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="popular_text[<?php echo $language['language_id']; ?>]" value="<?php echo isset($popular_text[$language['language_id']]) ? $popular_text[$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_popular_text; ?>" id="input-popular-text-<?php echo $language['language_id']; ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-categories-text-<?php echo $language['language_id']; ?>"><?php echo $entry_categories_text; ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="categories_text[<?php echo $language['language_id']; ?>]" value="<?php echo isset($categories_text[$language['language_id']]) ? $categories_text[$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_categories_text; ?>" id="input-categories-text-<?php echo $language['language_id']; ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-savings-text-<?php echo $language['language_id']; ?>"><?php echo $entry_savings_text; ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="savings_text[<?php echo $language['language_id']; ?>]" value="<?php echo isset($savings_text[$language['language_id']]) ? $savings_text[$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_savings_text; ?>" id="input-savings-text-<?php echo $language['language_id']; ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-template-body-<?php echo $language['language_id']; ?>"><?php echo $entry_template_body; ?></label>
                                        <div class="col-sm-10">
                                            <textarea name="body[<?php echo $language['language_id']; ?>]" id="input-template-body-<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($body[$language['language_id']]) ? $body[$language['language_id']] : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-text-message-<?php echo $language['language_id']; ?>"><?php echo $entry_text_message; ?></label>
                                        <div class="col-sm-10">
                                            <textarea name="text_message[<?php echo $language['language_id']; ?>]" id="input-text-message-<?php echo $language['language_id']; ?>" rows="10" class="form-control"><?php echo isset($text_message[$language['language_id']]) ? $text_message[$language['language_id']] : ''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <div class="alert alert-info">
                                    <?php echo $text_message_info; ?>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <p class="text-center small">Newsletter Enhancements OpenCart Module v3.7.2</p>
    </div>
    <script type="text/javascript" src="view/javascript/ne/ckeditor/ckeditor.js"></script>
    <script type="text/javascript"><!--
        <?php foreach ($languages as $language) { ?>
            CKEDITOR.replace('input-template-body-<?php echo $language['language_id']; ?>', {
                height:'500'
            });
        <?php } ?>
        CKEDITOR.scriptLoader.load(CKEDITOR.getUrl('../plugins/imagemap/jquery.simplemodal.1.4.2.min.js'));
    //--></script>
    <script type="text/javascript"><!--
        $(function(){
            $('.color').colorpicker({
                'format': 'hex'
            });
        });
    //--></script>
    <script type="text/javascript"><!--
        $('#languages a:first').tab('show');
    //--></script>
</div>
<?php echo $footer; ?>