
<div class="widget-html box <?php echo $addition_cls ?>  <?php if( isset($stylecls)&&$stylecls ) { ?>box-<?php echo $stylecls;?><?php } ?>">
    <?php if( $show_title ){ ?>
    <h4 class="widget-heading title">
        <?php echo $heading_title; ?>
    </h4>
    <?php } ?>
	<div class="widget-inner box-content clearfix">
		 <?php echo htmlspecialchars_decode( $html ); ?>
	</div>
</div>
