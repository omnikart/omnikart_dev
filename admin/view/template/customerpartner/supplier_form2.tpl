<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
   </div>
  <div class="container-fluid">
   
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo  "Supplier Form "; ?></h3>
         </div>
  <div class="panel-body">
    <form id="supplier_form" class="col-sm-12" action="<?php echo $button; ?>" method="post" enctype="multipart/form-data" >
	<div class="row">
	  <div class="col-sm-4">
			<div class="form-group required">
				<label class="control-label" for="company" >Name of company</label>
   <input  class="form-control" placeholder="" type="text" id="company" />
    </div>
			 
			<div class="form-group required">
				<label class="control-label" for="cat">Category</label>
				<input  class="form-control" placeholder="" type="text" id="cat" /> 
			</div>
			<div class="form-group required">
				<label class="control-label" for="area">Area</label>
				<input  class="form-control" placeholder="" type="text" id="area" /> 
			</div>
			<div class="form-group required">
				<label class="control-label" for="name">Name of contact person</label>
				<input  class="form-control" placeholder="" type="text" id="name" /> 
			</div>
			<div class="form-group required">
				<label class="control-label" for="number">Moblie Number</label>
				<input  class="form-control" placeholder="" type="text" id="number" /> 
			</div>
			<div class="form-group required">
				<label class="control-label" for="email">Email Id</label>
				<input  name="email" class="form-control" placeholder="" type="email" id="email" /> 
			</div>
			<div class="form-group required">
				<label class="control-label" for="add">Address</label>
				<input  class="form-control" placeholder="" type="text" id="add" /> 
			</div>
			<div class="form-group required">
				<label class="control-label" for="city">City</label>
				<input  class="form-control" placeholder="" type="text" id="city" /> 
			</div>
			 
		  <form action="upload.php" method="post" enctype="multipart/form-data">
               <label>Upload file</label>
                <input type="file" name="fileToUpload" id="fileToUpload">
                 </form>
           
            <div class="form-group required">
                <button class="btn btn-primary">Registration</button>
			</div>
       </div>	 
		</form>
	 </div>
      </div>
      </div>
      </div>
 