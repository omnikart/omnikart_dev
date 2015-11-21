<script type="text/javascript">
	var menu = '<li role="presentation" class="divider"></li>';
	menu += '<li><button id="button-pcd" class="btn">Add Products to DashBoard</button></li>'; 
	$('#dashboard').append(menu);
		
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