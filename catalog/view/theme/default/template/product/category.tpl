<?php echo $header; ?><div id="columns">
<?php if( ! empty( $mfilter_json ) ) { echo '<div id="mfilter-json" style="display:none">' . base64_encode( $mfilter_json ) . '</div>'; } ?>
<div class="container">
  <?php if ($banners) { ?>
  <h2 class="nmt"><?php echo $heading_title; ?></h2>
  <?php } else { ?>	
   <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <h2><?php echo $heading_title; ?></h2><?php } ?>
  <div class="row">
    <?php /* if (!$banners && $thumb) { ?>
        <div class="col-sm-12"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" /></div>
    <?php } */?>
  	<div class="col-sm-12">
    <?php if ($categories) { ?>
      <div class="col-sm-12 white">
	 		<?php if ($banners) { ?>
		      	<div class="row">
		      		<div class="col-sm-12">
						<div id="slideshow" class="flexslider" style="opacity: 1;">
							<ul class="slides">
						  		<?php foreach ($banners as $banner) { ?>
						  			<li>
										<?php if ($banner['link']) { ?>
							   				<a href="<?php echo $banner['link']; ?>" ><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" /></a>
							   			<?php } else { ?>
							   				<img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" />
							   			<?php } ?>
							 		</li>
							 	<?php } ?>			
							</ul>
						</div>
					</div>
				</div>
	      	<?php } ?>
	      <h3>Browse Categories</h3><hr />
	      <div class="row category-container">
	      	<?php foreach ($categories as $category) { ?>
	        <div class="category-layout col-lg-2 col-md-3 col-sm-4 col-xs-6">
	            <div class="product-thumb transition">
	                <div class="image"><a href="<?php echo $category['href']; ?>"><img src="<?php echo $category['image']; ?>" alt="<?php echo $category['name']; ?>" title="<?php echo $category['name']; ?>" class="img-responsive" /></a></div>
	                <div class="caption">
	                    <h4><a style="text-decoration: none" href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></h4>
	                </div>
	            </div>
	        </div>
	        <?php } ?>
      	</div>
      	<div class="col-sm-12 text-center arrowdown">
			<a class="showmore" data-showmore="category-container"><i class="fa fa-chevron-down"></i>&nbsp;Show More Categories&nbsp;<i class="fa fa-chevron-down"></i></a>
	      </div>
      </div>
    <?php } ?>
	
    <!-- <div class="row">
        <?php if ($description) { ?>
        <div class="col-sm-12"><div class="col-sm-12 white"><?php echo $description; ?></div></div>
        <?php } ?>    
    </div>
     -->
    <div class="row">
        <?php if ($combo) { ?>
        <div class="col-sm-12"><div class="col-sm-12 white"><?php echo $combo; ?></div></div>
        <?php } ?>    
    </div>
    </div>
  </div>
  
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
    <div class="col-sm-12 white">
    <div id="mfilter-content-container">
      <?php if ($products) { ?>
      <div class="row">
        <div class="col-md-2">
          <div class="btn-group hidden-xs">
            <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
            <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
          </div>
        </div>
        <div class="col-md-2">
        	<a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a>
        </div>
        <div class="col-md-2 text-right">
          <label class="control-label" for="input-sort"><?php echo $text_sort; ?></label>
        </div>
        <div class="col-md-3 text-right">
          <select id="input-sort" class="form-control" onchange="location = this.value;">
            <?php foreach ($sorts as $sorts) { ?>
            <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
            <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
        <div class="col-md-1 text-right">
          <label class="control-label" for="input-limit"><?php echo $text_limit; ?></label>
        </div>
        <div class="col-md-2 text-right">
          <select id="input-limit" class="form-control" onchange="location = this.value;">
            <?php foreach ($limits as $limits) { ?>
            <?php if ($limits['value'] == $limit) { ?>
            <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>
      <br />
      <div class="row">
        <?php foreach ($products as $product) { ?>
        <div class="product-layout product-list col-xs-12">
          <div class="product-thumb">
				<?php require(DIR_TEMPLATE.'default/template/common/product/product.tpl'); ?>
          </div>
        </div>
        <?php } ?>
      </div>

      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <?php } ?>
      <?php if (!$categories && !$products) { ?>
      <p><?php echo $text_empty; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      </div><?php echo $content_bottom; ?></div>
    </div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript">
	var menu = '<li role="presentation" class="divider"></li>';
	menu += '<li><a class="btn btn-primary" id="button-pcd"><i class="fa fa-plus"></i> DashBoard</a></li>'; 
	$('#dashboard > div').append(menu);
		
	$('#button-pcd').on('click', function() {
			$('#modal-db').remove();
			$.ajax({
				url: 'index.php?route=account/cd/getCategories',
				type: 'post',
				dataType: 'json',
				beforeSend: function() {
					$('#button-pcd').button('loading');
				},
				complete: function() {
					$('#button-pcd').button('reset');
				},
				success: function(json) {
					
					html  = '<div id="modal-db" class="modal">';
					html += '  <div class="modal-dialog">';
					html += '    <div class="modal-content">';
					html += '      <div class="modal-header">Hello';
					html += '      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>';
					html += '      <div class="modal-body">';
					html += '		<div class="row">'+json+'<br /><br />';
					html += '			<div class="col-sm-4"><div class="radio"><label><input type="radio" name="category_id" value="0"/>New Category</label></div></div>';
					html += '      		<div class="col-sm-8"><input class="form-control" id="newcat" name="category-name" type="text" placeholder="Input Name in case of new category" value=""></input></div><br /><br />';
					html += '	   		<div class="col-sm-12"><button id="button-update" onclick="updatedb();" class="btn btn-primary btn-lg">Update to DashBoard</button></div></div>';
					html += '    	</div>';
					html += '    </div>';
					html += '  </div>';
					html += '</div>';
					
					$('body').append(html);
	
					$('#modal-db').modal('show');		
				}
			});
		});
	function updatedb() {
		$.ajax({
			url: 'index.php?route=account/cd/addProductCd',
			type: 'post',
			data: $('input[type=\'hidden\'][name^=\'products\'],input[name^=\'products\']:checked,.radio input[type=\'radio\']:checked,input[name="category-name"]'),
			dataType: 'json',
			beforeSend: function() {
				$('#button-pcd').button('loading');
			},
			complete: function() {
				$('#button-pcd').button('reset');
			},
			success: function(json) {
				$('.alert').remove();
				if (json['error_text']) $('.modal-header').after('<div class="alert alert-danger"><div class="text-danger">'+ json['error_text'] +'</div></div>');
				if (json['success']) { 
					$('#modal-db .modal-header button').click();
					$('#modal-db').remove();
					$('input[name^=\'products\']:checked').prop('checked',false);
				}
			}
		});
	}		
</script>
	<script type="text/javascript">
		$('input[name="grouped"]').on('change',function(){
			var product_id = this.value;
			$('#modal-db1').remove();
			 
			$.ajax({
				url: 'index.php?route=product/category/getGPProducts',
				type: 'post',
				data: 'product_id='+product_id,
				dataType: 'json',
				beforeSend: function() {
					$('#button-pcd').button('loading');
				},
				complete: function() {
					$('#button-pcd').button('reset');
				},
				success: function(json) {
					
					html  = '<div id="modal-db1" class="modal">';
					html += '  <div class="modal-dialog">';
					html += '    <div class="modal-content">';
					html += '      <div class="modal-header">Hello';
					html += '      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>';
					html += '      <div class="modal-body">'+json+'<br /><br />';
					html += '	   <button id="button-update" onclick="updatedb();" class="btn btn-primary btn-lg">Update to DashBoard</button></div>';
					html += '    </div';
					html += '  </div>';
					html += '</div>';
					
					$('body').append(html);
	
					$('#modal-db1').modal('show');		
				}
			});
		
		});
		var element = $(".category-container");
		element.height(150); 
		category_container_curHeight = 150;
		$("#columns").on('click','a.showmore',function(e){
			var target = $(this).attr('data-showmore');
			var ths = $("."+target);
		    if($(ths).hasClass("open")){
		        $(ths).animate({"height":category_container_curHeight}).removeClass("open");
		        $(this).html("<i class=\"fa fa-chevron-down\"></i>&nbsp;Show More Categories&nbsp;<i class=\"fa fa-chevron-down\"></i>");
		    }else{
		    	var category_container_autoHeight = element.css('height','auto').height();
				element.height(category_container_curHeight); 
		        $(ths).animate({"height":category_container_autoHeight}).addClass("open");
		        $(this).html("<i class=\"fa fa-chevron-up\"></i>&nbsp;Show Less Categories&nbsp;<i class=\"fa fa-chevron-up\"></i>");
		    }
		    e.preventDefault();
		});
		
	</script>
	<script type="text/javascript"><!--
		$('#slideshow').flexslider({
			  animation: "slide",
			  controlNav: false,
			  directionNav: true,
			  nextText: "",
			  prevText: "",
		     useCSS: false /* Chrome fix*/
			});
		--></script>
</div><?php echo $footer; ?>
