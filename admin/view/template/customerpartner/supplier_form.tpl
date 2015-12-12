   <form id="supplier_form" class="form-horizontal" action="<?php echo $button; ?>" method="post" enctype="multipart/form-data" >
         <div class="form-group required">
				<label class="control-label col-sm-4" for="name-of-person" >What did he say?</label>
				<div class="col-sm-8">
				    <textarea name="what" value="<?php echo $add; ?>"  rows="4" cols="50"></textarea> 
			   </div>
			</div>
			<div class="form-group required">
				<label class="control-label col-sm-4" for="date">Calender</label>
				<div class="col-sm-8">
				     <input name="date" value="<?php echo $date; ?>" class="form-control" placeholder="" type="date" id="date" /> 
			   </div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" for="reg">Registration</label>
				<div class="col-sm-8">
				     <input name="reg" value="<?php echo $reg; ?>" class="form-control" placeholder="" type="checkbox" id="reg" /> 
			   </div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" for="pri">Pricelist</label>
				<div class="col-sm-8">
				     <input name="pri" value="<?php echo $pri; ?>" class="form-control" placeholder="" type="checkbox" id="pri" /> 
			   </div>
			</div>
	        <button class="btn btn-primary">Registration</button>
	     
		    </form>
		 