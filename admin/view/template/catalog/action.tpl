<div class="pull-right">
   <button type="button" data-toggle="tooltip" title="<?php echo "Delete"; ?>" class="btn btn-danger" onclick="confirm('<?php echo Confirm; ?>') ? $('#form-atr').submit() : false;"><i class="fa fa-trash-o"></i></button>
</div>
<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-atr">
<div class="table-responsive">
     <table  class="table table-bordered table-hover"   border="1px solid black";>
		 <thead>
	 		<tr>
	 		<th></th> 
	 		<?php if ($agnames) { ?>
        				<?php foreach($agnames as $key =>$ag) { ?>
							<th colspan="<?php echo count($ag['a'] ); ?>" class="text-center"><input  name="attribute_group[<?php echo $key; ?>][name]" type="text" value="<?php echo $ag['name'] ;?>"></th>
			 			<?php } ?>
        		  <?php } ?>
			      </tr>
			      <tr>
			      <th>Product Name </th>
			         <?php if ($agnames) { ?>
			          	<?php foreach($agnames as $ag) { ?>
    	      		 	 <?php foreach($ag['a'] as $key => $a) {  ?>
    	      		   <th><input name="attribute[<?php echo $key; ?>][name]" type="text" value="<?php echo $a['name'] ; ?>">  </th>
        		  		<?php } ?>
        		  	<?php } ?>
        		  <?php } ?>	
                 </tr>
			 	</thead>
			 <tbody>
				 <?php foreach($products as $product_id => $product) { ?>
					<tr>
					  <td><?php echo $product['name']?> <input type="hidden" name="product[<?php echo $product_id; ?>][product_id]" value="<?php echo $product_id; ?>" /> </td>
						 <?php if ($product['attributes']) { ?>
							<?php foreach($agnames as $ag) { ?>
    	      					<?php foreach($ag['a'] as $a) {  ?>
    		  						 <td> <input type="hidden" name="product[<?php echo $product_id?>][product_attribute][<?php echo $a['attribute_id']; ?>][attribute_id]" value="<?php echo $a['attribute_id']; ?>" />
    		  						 		<?php if (isset($product['attributes'][$a['attribute_id']]['product_attribute_description'])) { ?>
    		  						 		<?php foreach ($product['attributes'][$a['attribute_id']]['product_attribute_description'] as $key => $desc) { ?>
    		  						 		 <input type="text" name="product[<?php echo $product_id?>][product_attribute][<?php echo $a['attribute_id']; ?>][product_attribute_description][<?php echo $key; ?>][text]" value="<?php echo $desc['text']?>"/>
    		  						 		 <?php } ?> 
    		  						 	<?php } ?>
    		  						 </td>
       							<?php } ?>
			         	 	<?php } ?>
			         	 <?php } ?>
					 </tr>
				 <?php } ?>
			 </tbody>
			 </table>
			 </div>
			 </form> 
			<div class="pull-right">
	        	<button id="saveattr" data-toggle="tooltip" title="<?php echo "Save"; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
	       	</div>			
			
<script type="text/javascript"><!--
var ths;		 
$('input[name^=\'attribute\']').autocomplete({
	'source': function(request, response) {
		ths = $(this);
		$.ajax({
			url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['attribute_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		ths.val(item['label']);
		ths.parent.children('input[type=\'hidden\']').remove();
		html = '<input type="hidden" name="attribute['+item['value']+'][attribute_id]" value="'+item['value']+'"/>'
	}
});

 
$('input[name^='\attribute_group\']').autocomplete({
	'source': function(request, response) {
	ths = $(this);
		$.ajax({
			url: 'index.php?route=catalog/attribute_group/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['attribute_group_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		ths.val(item['label']);
	}
});
//--></script>
			 