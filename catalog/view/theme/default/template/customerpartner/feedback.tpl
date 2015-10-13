<?php if ($feedbacks) { ?>
	<?php foreach ($feedbacks as $feedback) { ?>
	<table class="table table-striped">
	  <tr>
	    <td style="width: 50%;"><strong><?php echo $feedback['nickname']; ?></strong></td>
	    <td class="text-right"><?php echo $feedback['createdate']; ?></td>
	  </tr>

	  <tr>
	  	<td>
	      <?php echo $text_price; ?>
	  	</td>

	  	<td class="text-right">
	      <?php for ($i = 1; $i <= 5; $i++) { ?>
	      <?php if ($feedback['feedprice'] < $i) { ?>
	      	<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
	      <?php } else { ?>
	      	<span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
	      <?php } ?>
	      <?php } ?>
	      <br/>
	  	</td>
	  </tr>

	  <tr>
	  	<td>
	      <?php echo $text_value; ?>
	  	</td>

	  	<td class="text-right">
	      <?php for ($i = 1; $i <= 5; $i++) { ?>
	      <?php if ($feedback['feedvalue'] < $i) { ?>
	      	<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
	      <?php } else { ?>
	      	<span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
	      <?php } ?>
	      <?php } ?>
	      <br/>
	  	</td>
	  </tr>

	  <tr>
	  	<td>
	      <?php echo $text_quality; ?>	
	  	</td>

	  	<td class="text-right">
	      <?php for ($i = 1; $i <= 5; $i++) { ?>
	      <?php if ($feedback['feedquality'] < $i) { ?>
	      	<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
	      <?php } else { ?>
	      	<span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
	      <?php } ?>
	      <?php } ?>			      
	    </td>
	  </tr>

	  <tr>
	    <td colspan="2">
	      <p><?php echo $feedback['review']; ?></p>
	    </td>
	  </tr>
	</table>
	<?php } ?>
	<div class="text-right"><?php echo $results; ?></div>

<?php } else { ?>
	<p><?php echo $text_no_feedbacks; ?></p>
<?php } ?>	