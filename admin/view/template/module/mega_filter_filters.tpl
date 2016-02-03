<?php require DIR_TEMPLATE . 'module/' . $_name . '-header.tpl'; ?>

<div class="col-xs-2">
	<ul class="nav nav-tabs tabs-left">
		<li class="active"><a data-toggle="tab" href="#filters-list"><i
				class="glyphicon glyphicon-align-justify"></i> <?php echo $text_list; ?></a></li>
		<li><a data-toggle="tab" href="#filters-images"><i
				class="glyphicon glyphicon glyphicon-picture"></i> <?php echo $text_images; ?></a></li>
	</ul>
</div>

<div class="col-xs-10">
	<div class="tab-content">
		<br />
		<div class="tab-pane active" id="filters-list">
			<br /><?php echo $entry_default_values; ?><br />
			<br />
			<?php
			
			$_filtersName = $_name . '_filters';
			$_filtersValues = $filters;
			
			require DIR_TEMPLATE . 'module/' . $_name . '-filters.tpl';
			
			?>
		</div>
		<div class="tab-pane" id="filters-images">
			<table class="table table-without-thead">
				<tr>
					<td class="vertical-middle" width="150"><i
						class="glyphicon glyphicon glyphicon-picture"></i> <?php echo $entry_set_images; ?>
					</td>
					<td class="vertical-middle" width="200" height="60"><select
						id="filters-language_images" class="form-control">
							<?php foreach( $languages as $language ) { ?>
								<option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
							<?php } ?>
						</select></td>
					<td class="vertical-middle" width="200" height="60"><select
						id="filters-filter_images" class="form-control">
							<option value=""><?php echo $text_select_filter; ?></option>
							<?php foreach( $filterItems as $filter_id => $filter ) { ?>
								<option value="<?php echo $filter_id; ?>"><?php echo $filter['name']; ?></option>
							<?php } ?>
						</select></td>
					<td class="vertical-middle"><a href="#"
						id="save-sort-values_images" class="btn btn-success btn-sm"><i
							class="glyphicon glyphicon-ok"></i> <?php echo $text_save; ?></a>
						<img src="view/stylesheet/mf/img/loading.gif"
						id="save-sort-loader_images" /> <a href="#"
						id="reset-sort-values_images" class="btn btn-danger btn-sm"><i
							class="glyphicon glyphicon-repeat"></i> <?php echo $text_reset; ?></a>
					</td>
				</tr>
				<tr>
					<td colspan="5" id="filters-values-cnt_images"></td>
				</tr>
			</table>
		</div>
		</dvi>
	</div>

	<script type="text/javascript">
	var MF_AJAX_PARAMS = '<?php echo $HTTP_URL ? "&option=com_mijoshop&format=raw" : ""; ?>';
	
	function initByType( _TYPE_ ){
		var $language	= jQuery('#filters-language_' + _TYPE_),
			$filter		= jQuery('#filters-filter_'+_TYPE_),
			$save		= jQuery('#save-sort-values_' + _TYPE_).hide(),
			$reset		= jQuery('#reset-sort-values_' + _TYPE_).hide(),
			$saveLoader	= jQuery('#save-sort-loader_' + _TYPE_).hide(),
			$cnt		= jQuery('#filters-values-cnt_' + _TYPE_),
			loader		= '<img src="view/stylesheet/mf/img/loading.gif" />';
			
		$save.click(function(){
			$save.hide();
			$reset.hide();
			$saveLoader.show();
			
			var data = {
				'filter_id'	: $filter.val(),
				'lang_id'	: $language.val(),
				'type'		: _TYPE_
			};
			
			$cnt.find('> ul > li').each(function(i){
				data['items[' + i + '][key]'] = jQuery(this).attr('data-key');
			});
			$cnt.find('input[name^="image"]').each(function(i){
				var key = jQuery(this).attr('id').split('-')[1],
					val = jQuery(this).val();
			
				if( val != '' ) {
					data['items[' + i + '][key]'] = key;
					data['items[' + i + '][img]'] = val;
				}
			});
			
			jQuery.post( '<?php echo $action_filters_save; ?>'.replace(/&amp;/g, '&')+MF_AJAX_PARAMS, data, function(){
				$saveLoader.hide();
				$reset.show();
				
				if( _TYPE_ == 'images' )
					$save.show();
			});
			
			return false;
		});
		
		$reset.click(function(){
			if( $filter.val() == '' ) return false;
			
			var hSave = $save.is(':visible');
			
			if( hSave )	$save.hide();
			
			$reset.hide();
			$saveLoader.show();
			
			jQuery.post( '<?php echo $action_filters_reset; ?>'.replace(/&amp;/g, '&')+MF_AJAX_PARAMS, {
				'filter_id'	: $filter.val(),
				'lang_id'	: $language.val(),
				'type'		: _TYPE_
			}, function(){
				$saveLoader.hide();
				
				if( hSave )
					$save.show();
				
				changeFilter();
			});
			
			return false;
		});
		
		$language.change(function(){
			$filter.trigger('change');
		});
		
		$filter.change(function(){
			if( $filter.val() == '' ) {
				$reset.hide();
				$save.hide();
				$cnt.html('');
			}
			
			changeFilter();
		}).trigger('change');
			
		function changeFilter() {
			if( $filter.val() == '' ) return;
			
			$cnt.html('<center>'+loader+'</center>');
			
			jQuery.ajax({
				'url'		: '<?php echo $action_values_by_filter; ?>'.replace(/&amp;/g,'&')+MF_AJAX_PARAMS,
				'type'		: 'get',
				'data'		: { 'type' : _TYPE_, 'lang_id'	: $language.val(), 'filter_id' : $filter.val() },
				'success'	: function(response){
					var json	= jQuery.parseJSON(response),
						$cnt2, i;
					
					if( json.length ) {
						$cnt2	= jQuery('<table class="table">')
							.append(jQuery('<thead>')
								.append('<tr><th width="200"><?php echo $text_attribute_value; ?></th><th><?php echo $text_image; ?></th></tr>')
							)
							.append('<tbody>');
							
						for( i = 0; i < json.length; i++ ) {
							$cnt2.find('tbody').append(jQuery('<tr>')
								.append(jQuery('<td>')
									.html( json[i].val )
								)
								.append(jQuery('<td>')
									.append(jQuery('<div class="col-sm-10">')
										.append(jQuery('<a href="" id="thumb-' + json[i].key + '" data-toggle="image" class="img-thumbnail">')
											.append('<img src="' + ( json[i].img ? json[i].img : '<?php echo $no_image; ?>' ) + '" alt="" title="" data-placeholder="<?php echo $no_image; ?>" />')
											.append('<input type="hidden" name="image[' + json[i].key + ']" value="' + ( json[i].ival ) + '" id="input-' + json[i].key + '" />')
										)
									)
								)
							);
						}
							
						$cnt.css('padding', '8px 0');
						$save.show();

						$cnt.html('').append($cnt2);
						$reset.show();
					} else {
						$reset.hide();
						$cnt.html('<?php echo $text_list_is_empty; ?>');
					}
				}
			});
		}
	};
	
	initByType( 'images' );
</script> 
			
<?php require DIR_TEMPLATE . 'module/' . $_name . '-footer.tpl'; ?>