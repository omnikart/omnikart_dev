<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
          <h1><i class="fa fa-magic"></i>  <?php echo $heading_title; ?></h1>
          <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
          </ul>
          <div class="pull-right">
            <a data-toggle="tooltip" title="<?php echo $text_extensionlink; ?>" class="btn btn-primary" target="_blank" href="http://www.opencart.com/index.php?route=extension/extension/info&extension_id=22647&filter_username=imdevlper18"><i class="fa fa-life-ring"></i> <?php echo $text_extensionlink; ?></a>
            <a data-toggle="tooltip" title="<?php echo $text_support; ?>" class="btn btn-primary" target="_blank" href="http://support.imdevlper18.com/open.php"><i class="fa fa-life-ring"></i> <?php echo $text_support; ?></a>
         </div>
    </div>
  </div>
  <div class="page-header">
    <div class="container-fluid">
          <div class="pull-right">
        <a onclick="$('#form').submit();"  class="btn btn-primary"><i class="fa fa-save"></i></a>
        <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-danger"><i class="fa fa-reply"></i></a>
        </div>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $headerinfo; ?></h3>
    </div>
     <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
          <table class="table table-striped">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-logo"><span data-toggle="tooltip" title="<?php echo $help_logo; ?>"><?php echo $text_logo; ?></span></label>
                <div class="col-sm-10">
                  <select name="pdforders_logo" id="input-logo" class="form-control">
                    <?php if ($pdforders_logo) { ?>
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
                <label class="col-sm-2 control-label" for="input-showimage"><span data-toggle="tooltip" title="<?php echo $help_showimage; ?>"><?php echo $text_showimage; ?></span></label>
                <div class="col-sm-10">
                  <select name="pdforders_showimage" id="input-showimage" class="form-control">
                    <?php if ($pdforders_showimage) { ?>
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
              <label class="col-sm-2 control-label" for="input-orientation"><span data-toggle="tooltip" title="<?php echo $help_orientation; ?>"><?php echo $text_orientation; ?></span></label>
                <div class="col-sm-10">
                  <select name="pdforders_orientation" id="input-" class="form-control">
                    <?php foreach ($orientations as $key => $orientation) { ?>
                    <?php if ($key == $pdforders_orientation) { ?>
                    <option value="<?php echo $key; ?>" selected="selected"><?php echo $orientation; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $key; ?>"><?php echo $orientation; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  </div>
              </div>

             <div class="form-group">
              <label class="col-sm-2 control-label" for="input-fontstyle"><span data-toggle="tooltip" title="<?php echo $help_fontstyle; ?>"><?php echo $text_fontstyle; ?></span></label>
                <div class="col-sm-10">
                  <select name="pdforders_fontstyle" id="input-fontstyle" class="form-control">
                    <?php foreach ($fontstyles as $key => $fontstyle) { ?>
                    <?php if ($key == $pdforders_fontstyle) { ?>
                    <option value="<?php echo $key; ?>" selected="selected"><?php echo $fontstyle; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $key; ?>"><?php echo $fontstyle; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-fontsize"><span data-toggle="tooltip" title="<?php echo $help_fontsize; ?>"><?php echo $text_fontsize; ?></span></label>
                <div class="col-sm-10">
                 <input type="text" name="pdforders_fontsize" value="<?php echo $pdforders_fontsize; ?>" placeholder="Enter font size in px Ex: 14" class="form-control">
                  </div>
              </div> 
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-format"><span data-toggle="tooltip" title="<?php echo $help_format; ?>"><?php echo $text_format; ?></span></label>
                <div class="col-sm-10">
                  <select name="pdforders_format" id="input-format" class="form-control">
                    <?php foreach ($formats as $key => $format) { ?>
                    <?php if ($key == $pdforders_format) { ?>
                    <option value="<?php echo $key; ?>" selected="selected"><?php echo $format; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $key; ?>"><?php echo $format; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" ><span data-toggle="tooltip" title="<?php echo $help_addextrarows; ?>"><?php echo $text_addextrarows; ?></span></label>
                <div class="col-sm-10">
                  <select name="pdforders_addextrarows" id="extrarowsstatus" class="form-control extrarowsstatus">
                    <?php if ($pdforders_addextrarows) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group subextrarows">
                <label class="col-sm-2 control-label" for="input-numberextrarows"><span data-toggle="tooltip" title="<?php echo $help_numberextrarows; ?>"><?php echo $text_numberextrarows; ?></span></label>
                <div class="col-sm-10">
                 <input type="text" name="pdforders_numberextrarows" value="<?php echo $pdforders_numberextrarows; ?>" placeholder="Enter number of blank rows to add Ex: 4" class="form-control">
                  </div>
              </div> 
              <div class="form-group subextrarows">
                <label class="col-sm-2 control-label" for="input-numberproducts"><span data-toggle="tooltip" title="<?php echo $help_numberproducts; ?>"><?php echo $text_numberproducts; ?></span></label>
                <div class="col-sm-10">
                 <input type="text" name="pdforders_numberproducts" value="<?php echo $pdforders_numberproducts; ?>" placeholder="Products purchased is greater then Ex. 4" class="form-control">
                  </div>
              </div> 
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-attachemail"><span data-toggle="tooltip" title="<?php echo $help_attachemail; ?>"><?php echo $text_attachemail; ?></span></label>
                <div class="col-sm-10">
                  <select name="pdforders_attachemail" id="input-attachemail" class="form-control">
                    <?php if ($pdforders_attachemail) { ?>
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
                  <label class="col-sm-2 control-label" for="input-process-status"><span data-toggle="tooltip" title="<?php echo $help_order_status; ?>"><?php echo $text_order_status; ?></span></label>
                  <div class="col-sm-10">
                    <div class="well well-sm" style="height: 150px; overflow: auto;">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <div class="checkbox">
                        <label>
                          <?php if (in_array($order_status['order_status_id'], $pdforders_order_status_customer)) { ?>
                          <input type="checkbox" name="pdforders_order_status_customer[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                          <?php echo $order_status['name']; ?>
                          <?php } else { ?>
                          <input type="checkbox" name="pdforders_order_status_customer[]" value="<?php echo $order_status['order_status_id']; ?>" />
                          <?php echo $order_status['name']; ?>
                          <?php } ?>
                        </label>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                 <div class="form-group">
                <label class="col-sm-2 control-label" ><span data-toggle="tooltip" title="<?php echo $help_vattin; ?>"><?php echo $text_vattin; ?></span></label>
                <div class="col-sm-10">
                 <input type="text" name="pdforders_vattin" value="<?php echo $pdforders_vattin; ?>" placeholder="Enter your vat tin number" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_autogenerate; ?>"><?php echo $text_autogenerate; ?></span></label>
                  <div class="col-sm-10">
                  <select name="pdforders_autogenerateinvoiceno" id="input-autogenerateinvoiceno" class="form-control">
                    <?php if ($pdforders_autogenerateinvoiceno) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div> 
              <div class="form-group required">
                <label class="col-sm-2 control-label"><?php echo $text_footer; ?></label>
                <div class="col-sm-10">
                  <?php foreach ($languages as $language) { ?>
                  <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                    <input type="text" name="pdforders_textfooter[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($pdforders_textfooter[$language['language_id']]) ? $pdforders_textfooter[$language['language_id']]['name'] : ''; ?>" placeholder="Enter message" class="form-control" />
                  </div>
                  <?php } ?>
                </div>
              </div>
          </table>
        </form>
     </div>
    </div>
  </div>
<?php echo $footer; ?>
</div>
<script type="text/javascript" src="view/javascript/jquery/remodal.js"></script>
<link href='http://fonts.googleapis.com/css?family=Open+Sans' async rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="view/stylesheet/imdev.css">
<script type="text/javascript">
function checkstatus() {
  var e = document.getElementById("extrarowsstatus");
  var strUser = e.options[e.selectedIndex].value;
  if(strUser == 0) {
   $("div.subextrarows").hide();
  } else {
    $("div.subextrarows").show();
  }
};
</script>
<script type="text/javascript">
$(document).ready(function(){
checkstatus();
$('.extrarowsstatus').on( 'change', function(){ checkstatus(); } );
});
</script>