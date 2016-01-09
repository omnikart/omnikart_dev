<div class="table-responsive">
	<table  class="table table-bordered table-hover"   border="1px solid black";>
	<thead>
	  <tr>
         <?php if ($agnames) { ?>
        	<?php foreach($agnames as $ag) { ?>
				  <?php foreach($ag['name'] as $b) {  ?>
				  <th class="text-center"><?php echo $b['name'] ;?></th>
			 <?php } ?>
			 <?php } ?>
        <?php } ?>
       </tr>
       <tr>
	     <?php if ($agnames) { ?>
        	<?php foreach($agnames as $ag) { ?>
        	      <?php foreach($ag['a'] as $a) {  ?>
        		  <th><?php echo $a['name']; ?></th>
			   <?php } ?>
           <?php } ?>
         <?php } ?>
	  </tr>
	 </thead>
	 <tbody>
	 <?php  if ($agnames) { ?>  
	      <?php foreach($agnames as $key => $ag) { ?>
        	 <?php foreach($ag['a'] as $key3 => $a) { ?>
		     		<?php foreach($a["product_attribute_description"] as $b){ ?>
							<?php foreach($b as $c){ ?>
							<td><?php echo $c; ?></td>
			 		<?php }}}}} ?>
	   </tbody>
	 </table>
	</div>