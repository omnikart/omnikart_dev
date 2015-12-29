<?php echo $header; ?><div id="columns">
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
	<?php if ($error_warning) { ?>
	<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
	<div class="alert alert-success"><i class="fa fa-check-circle"> </i> <?php echo $success; ?></div>
	<?php } ?>

  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>

  <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
	<h1>
		<?php echo $heading_title; ?>
	</h1>
    <fieldset>      
	    <legend><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></legend>
		<?php if($isMember) { ?>
		    <?php if($chkIsPartner){ ?>
			<div class="row">
			  <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $order; ?></div>
			  <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $sale; ?></div>
			  <div class="col-lg-4 col-md-4 col-sm-6"><?php echo $customer; ?></div>
			</div>
			<div class="row">
			  <!-- <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php //echo $map; ?></div> -->
			  <div class="col-lg-12 col-md-12 col-sx-12 col-sm-12"><?php echo $chart; ?></div>
			</div>
			<div class="row">
			  <div class="col-lg-12 col-md-12 col-sm-12 col-sx-12"> <?php echo $recent; ?> </div>
			</div>
	    	<?php } ?>
	    <?php } else { ?>
	    <div class="text-danger">
	    	Warning: You are not authorised to view this page, Please contact to site administrator!
	    </div>
	    <?php } ?>
    </fieldset>   
  </div>  <!--content-->
<?php echo $column_right; ?></div>   <!--row-->
</div>  <!--container-->
</div><?php echo $footer; ?>
