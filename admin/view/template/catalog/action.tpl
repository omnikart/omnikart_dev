<form method="post" enctype="multipart/form-data" id="form-attribute">
	<div class="table-responsive">
		<table class="table table-bordered table-hover"
			border="1px solid black";>
			<thead>
				<tr>
					<th></th> 
	 		<?php if ($agnames) { ?>
        				<?php foreach($agnames as $key =>$ag) { ?>
							<th colspan="<?php echo count($ag['a'] ); ?>" class="text-center"><?php echo $ag['name'] ;?></th>
			 			<?php } ?>
        		  <?php } ?>
			      </tr>
				<tr>
					<th>Product Name</th>
			         <?php if ($agnames) { ?>
			          	<?php foreach($agnames as $ag) { ?>
    	      		 	 <?php foreach($ag['a'] as $key => $a) {  ?>
    	      		   <th><?php echo $a['name'] ; ?></th>
        		  		<?php } ?>
        		  	<?php } ?>
        		  <?php } ?>	
                 </tr>
			</thead>
			<tbody>
				 <?php foreach($products as $product_id => $product) { ?>
					<tr>
					<td><?php echo $product['name']?> <input type="hidden"
						name="product[<?php echo $product_id; ?>][product_id]"
						value="<?php echo $product_id; ?>" /></td>
						 <?php if ($product['attributes']) { ?>
							<?php foreach($agnames as $ag) { ?>
    	      					<?php foreach($ag['a'] as $a) {  ?>
    		  						 <td><input type="hidden"
						name="product[<?php echo $product_id?>][product_attribute][<?php echo $a['attribute_id']; ?>][attribute_id]"
						value="<?php echo $a['attribute_id']; ?>" />
    		  						 		<?php if (isset($product['attributes'][$a['attribute_id']]['product_attribute_description'])) { ?>
    		  						 		<?php foreach ($product['attributes'][$a['attribute_id']]['product_attribute_description'] as $key => $desc) { ?>
    		  						 		 <input type="text"
						name="product[<?php echo $product_id?>][product_attribute][<?php echo $a['attribute_id']; ?>][product_attribute_description][<?php echo $key; ?>][text]"
						value="<?php echo $desc['text']?>" />
    		  						 		 <?php } ?> 
    		  						 	<?php } else { ?>
    		  						 		<?php foreach ($language_ids as $language_id) { ?>
    		  						 	   		<input type="text"
						name="product[<?php echo $product_id?>][product_attribute][<?php echo $a['attribute_id']; ?>][product_attribute_description][<?php echo $language_id; ?>][text]"
						value="" />
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

<script type="text/javascript"><!--
 
 function productUpdates() {
	$.ajax({
		url: '<?php echo $productUpdates; ?>', 
		method: 'post',
		data: $('#form-attribute').serialize(),
		dataType: 'json',
		success: function(json) {
	 	}
	});
 }
 
//--></script>
