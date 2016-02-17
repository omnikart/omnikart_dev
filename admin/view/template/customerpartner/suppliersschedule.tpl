<div class="table-responsive">
	<table class="table table-bordered table-hover"
		border="1px solid black";>
		<thead>
			<tr>
				<td class="text-center">Id</td>
				<td class="text-center">Name</td>
				<td class="text-center">Comment</td>
				<td class="text-center">Date</td>
				<td class="text-center">List</td>
				<td class="text-center">Status</td>
			</tr>
		</thead>
		<tbody>
         <?php if (isset($histories)) { ?>
		       <?php foreach($histories  as $history) { ?>
	                    <tr>
				<td class="text-center"><?php echo $history['history_id']; ?></td>
				<td class="text-center"><?php echo $history['user_id']['firstname']; ?></td>
				<td class="text-center"><?php echo $history['comment']; ?></td>
				<td class="text-center"><?php echo $history['date_scheduled']; ?></td>
				<td class="text-center"><?php foreach($history['fields'] as $field) { ?>
							   <?php echo $field['field_type'].''.($field['value_text']=='on'?' <i class="fa fa-check-square-o"></i>':'') ; ?><br>
		                       <?php } ?> 
						 	 </td>
				<td class="text-center"><?php echo $history['status']; ?></td>
			</tr>
			   <?php } ?>
	      <?php } ?>
  </tbody>
	</table>
</div>