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
				<label class="control-label" for="name-of-person" >What did he say?</label>
				<textarea rows="4" cols="50"></textarea> 
			</div>
			<div class="form-group required">
				<label class="control-label" for="date">Calender</label>
				<input name="date" class="form-control" placeholder="" type="date" id="date" /> 
			</div>
			<div class="form-group">
				<label class="control-label" for="reg">Registration</label>
				<input name="reg" class="form-control" placeholder="" type="checkbox" id="reg" /> 
			</div>
			<div class="form-group">
				<label class="control-label" for="pri">Pricelist</label>
				<input name="pri" class="form-control" placeholder="" type="checkbox" id="pri" /> 
			</div>
	        <button class="btn btn-primary">Registration</button>
	        </div>	 
		    </form>
			</div>
		    </div>
		    </div>
		    </div>