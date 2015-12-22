<?php echo $header; ?><div id="columns">
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row">
    <?php //echo $column_left; ?>
    <?php //if ($column_left && $column_right) { ?>
    <?php //$class = 'col-sm-6'; ?>
    <?php //} elseif ($column_left || $column_right) { ?>
    <?php //$class = 'col-sm-9'; ?>
    <?php //} else { ?>
    <?php //$class = 'col-sm-12'; ?>
    <?php //} ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="row">

        <div class="col-md-6">
          <div class="left-panel">
            <h1><?php //echo $heading_title; ?></h1>
            <h1 class="not_found_title">404</h1>
            <p class="desc"><span class="fa fa-exclamation-circle"></span>&nbsp;Oops...<?php echo $text_error; ?></p>

            <div class="buttons">
             <a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a>
            </div>

          </div>
          
        </div>

        <div class="col-md-6">
          <div class="right-panel">
            <h1 class="title">You May Like The Following Posts</h1>
            <ul class="list list-unstyled">
              <li><span class="fa fa-angle-double-right"></span>&nbsp;<a href="#">What is the definition of earth?</a></li>
              <li><span class="fa fa-angle-double-right"></span>&nbsp;<a href="#">How to introduce you with a new person?</a></li>
              <li><span class="fa fa-angle-double-right"></span>&nbsp;<a href="#">Introduct with modern science.</a></li>
              <li><span class="fa fa-angle-double-right"></span>&nbsp;<a href="#">What is eCommerce?</a></li>
              <li><span class="fa fa-angle-double-right"></span>&nbsp;<a href="#">Cources of Modern Science.</a></li>
            </ul>
          </div>

          <div class="social">
            <h4 class="title">Follow Us On</h4>&nbsp;
            <a href="#"><span class="fa fa-twitter-square"></span></a>
            <a href="#"><span class="fa fa-facebook-square"></span></a>
            <a href="#"><span class="fa fa-in-square"></span></a>
            <a href="#"><span class="fa fa-youtube-square"></span></a>
          </div>

        </div>

      </div>
    
      <?php //echo $content_bottom; ?>
    </div>
    <?php //echo $column_right; ?></div>
</div>
<?php //echo $footer; ?>