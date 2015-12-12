		<form id="supplier_form" class="form-horizontal" action="<?php echo $registration; ?>" method="POST" enctype="multipart/form-data" >
					<div class="form-group required">
						<label class="control-label col-sm-4" for="company" >Name of company</label>
						<div class="col-sm-8">
	                		<input  name="company_name" value="<?php echo $company_name;?>" class="form-control" placeholder="" type="text" id="company" />		
	                	</div>
            		</div>
	 				<div class="form-group required">
						<label class="control-label col-sm-4" for="cat">Category</label>
					  <div class="col-sm-8">
						   <input name="category" value="<?php echo $category ;?>" class="form-control" placeholder="" type="text" id="cat" /> 
					</div>
					</div>
					<div class="form-group required">
						<label class="control-label col-sm-4" for="area">Area</label>
					   <div class="col-sm-8">
						    <input name="area" value="<?php echo $area;?>"  class="form-control" placeholder="" type="text" id="area" /> 
					</div>
					</div>
					<div class="form-group required">
						<label class="control-label col-sm-4" for="name">Name of contact person</label>
					  <div class="col-sm-8">
						   <input  name="name" value="<?php echo $name;?>" class="form-control" placeholder="" type="text" id="name" /> 
					</div>
					</div>
					<div class="form-group required">
						<label class="control-label col-sm-4" for="number">Moblie Number</label>
					  <div class="col-sm-8">
						   <input  name="number" value="<?php echo $number;?>" class="form-control" placeholder="" type="text" id="number" /> 
					</div>
					</div>
					<div class="form-group required">
						<label class="control-label col-sm-4" for="email">Email Id</label>
					  <div class="col-sm-8">
						   <input  name="email" value="<?php echo $email;?>" class="form-control" placeholder="" type="email" id="email" /> 
					</div>
					</div>
					<div class="form-group required">
						<label class="control-label col-sm-4" for="add">Address</label>
					 <div class="col-sm-8">
						   <input name="add" value="<?php echo $add;?>"  class="form-control" placeholder="" type="text" id="add" /> 
					</div>
					</div>
					<div class="form-group required">
						<label class="control-label col-sm-4" for="city">City</label>
					 	<div class="col-sm-8">
						   <input name="city" value="<?php echo $city;?>"  class="form-control" placeholder="" type="text" id="city" /> 
						</div>
					</div>
					<div class="form-group">
		            	<label class="col-sm-4 control-label" for="input-filename"><span data-original-title="You can upload via the upload button or use FTP to upload to the download directory and enter the details below." data-toggle="tooltip" title="">Upload front image</span></label>
		            	<div class="col-sm-8">
			               		<input type="file" name="front" id="input-filename" class="form-control"/>
			            </div>
			           	<label class="col-sm-4 control-label" for="input-filename1"><span data-original-title="You can upload via the upload button or use FTP to upload to the download directory and enter the details below." data-toggle="tooltip" title="">Upload back image</span></label>
		            	<div class="col-sm-8">
			               		<input type="file" name="back" id="input-filename1" class="form-control"/>
			            	</div> 	
          			</div>
	 	    		<!-- fileupload -->
		             <div class="text-right">
		                 <input type="submit" class="btn btn-primary"></input>
					</div>
		</form>