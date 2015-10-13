<?php if ( ! empty( $_error_warning ) ) { ?>
	<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_error_warning; ?>
		<button type="button" class="close" data-dismiss="alert">&times;</button>
	</div>
<?php } ?>
<?php if ( ! empty( $_success ) ) { ?>
	<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $_success; ?>
		<button type="button" class="close" data-dismiss="alert">&times;</button>
	</div>
<?php } ?>

<div class="pull-left">
	<?php foreach( $languages as $language ) { ?>
		<?php if( $language['language_id'] == $language_id ) { ?>
			<button type="button" class="btn btn-sm btn-primary"><img src="view/image/flags/<?php echo $language['image']; ?>" /> <?php echo $language['name']; ?></button>
		<?php } else { ?>
			<a data-param="language_id" data-val="<?php echo $language['language_id']; ?>" href="<?php echo $aliases_url; ?>&language_id=<?php echo $language['language_id']; ?>&store_id=<?php echo $store_id; ?>" class="btn btn-sm btn-info"><img src="view/image/flags/<?php echo $language['image']; ?>" /> <?php echo $language['name']; ?></a>
		<?php } ?>
	<?php } ?>
</div>

<div class="pull-right">
	<div class="pull-left" style="padding: 5px 5px 0 0;"><?php echo $text_stores; ?>:</div>
	<?php foreach( $stores as $store ) { ?>
		<?php if( $store['store_id'] == $store_id ) { ?>
			<button type="button" class="btn btn-sm btn-primary"><?php echo $store['name']; ?></button>
		<?php } else { ?>
			<a data-param="store_id" data-val="<?php echo $store['store_id']; ?>" href="<?php echo $aliases_url; ?>&store_id=<?php echo $store['store_id']; ?>&language_id=<?php echo $language_id; ?>" class="btn btn-sm btn-info"><?php echo $store['name']; ?></a>
		<?php } ?>
	<?php } ?>
</div>

<div class="clearfix"></div>
		
<br /><br />
<table class="table">
	<thead>
		<tr>
			<th width="50%"><?php echo $text_url; ?></th>
			<th colspan="2"><?php echo $text_seo_url; ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<input type="text" class="form-control" id="val-url" value="<?php echo $val_url; ?>" />
				<small>e.g.: http://your-shop.com/desktops?mfp=price[0,100],manufacturers[8],3-clockspeed[100mhz]</small>
			</td>
			<td>
				<input type="text" class="form-control" id="val-alias" value="<?php echo $val_alias; ?>" />
				<small>e.g.: cheap-apple-desktops</small>
			</td>
			<td width="50">
				<button type="button" class="btn btn-sm btn-success" id="insert-alias"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $text_insert; ?></button>
			</td>
		</tr>
	</tbody>
</table>

<?php if( $records ) { ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="50%"><?php echo $text_url_params; ?></th>
				<th colspan="2"><?php echo $text_seo_url; ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $records as $record ) { ?>
				<?php
				
					$url = HTTPS_CATALOG . $record['path'] . ( $record['path'] ? '/' : '' ) . 'mfp,' . $record['mfp'];
					$alias = HTTPS_CATALOG . $record['path'] . '/' . $record['alias'];
				
				?>
				<tr>
					<td><a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a></td>
					<td><a href="<?php echo $alias; ?>" target="_blank"><?php echo $alias; ?></a></td>
					<td width="100" class="text-center">
						<a href="#" data-id-to-remove="<?php echo $record['mfilter_url_alias_id']; ?>" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>

	<?php echo $pagination; ?>
<?php } ?>

<script type="text/javascript">
	(function(){
		function ajax( action, params ) {
			if( typeof params == 'undefined' ) {
				params = {};
			}
			
			$('#tab-seo-aliases').html('<center><?php echo $text_loading; ?>...</center>')
			
			$.get( '<?php echo $aliases_url; ?>'.replace( /&amp;/g, '&' ), $.extend({
				action: action,
				language_id: '<?php echo $language_id; ?>',
				store_id: '<?php echo $store_id; ?>'
			}, params), function( response ){
				$('#tab-seo-aliases').html( response );
			});
		}
		
		$('#insert-alias').click(function(){
			var url = $('#val-url').val(),
				alias = $('#val-alias').val();

			ajax( 'insert', {
				url: url,
				alias: alias
			});

			return false;
		});
		
		$('a[data-param]').click(function(){
			var param = $(this).attr('data-param'),
				val = $(this).attr('data-val'),
				data = {};
				
			data[param] = val;
			
			ajax( '', data );
			
			return false;
		});
		
		$('a[data-id-to-remove]').click(function(){
			if( confirm( '<?php echo $text_are_you_sure; ?>' ) ) {
				var id = $(this).attr('data-id-to-remove');

				ajax( 'remove', {
					id: id,
					page: <?php echo $page; ?>
				});
			}
			
			return false;
		});
		
		$('.pagination a').click(function(){
			var page = $(this).attr('href').match(/page=([0-9]+)/)[1];
			
			ajax( '', {
				page: page
			});
		
			return false;
		});
	})( jQuery );
</script>