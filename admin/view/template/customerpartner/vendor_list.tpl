<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      <button type="button" data-toggle="tooltip" title="<?php echo "delete"; ?>" class="btn btn-danger" onclick="confirm('<?php echo "confirm"; ?>') ? $('#form-attribute1').submit() : false;"><i class="fa fa-trash-o"></i></button>         
     <a href="<?php echo $button1; ?>" data-toggle="tooltip" title="<?php echo $add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>  </div>
      <h1><?php echo "$vendor"; ?></h1>
      <ul class="breadcrumb">
       </ul>
    </div>
  </div>
  <div class="container-fluid">
     <div class="panel panel-default">
     <div class="panel-heading">
     <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo  "Vendor List"; ?></h3></div>
     <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name"   placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-mail"><?php echo $entry_mail; ?></label>
                <input type="text" name="filter_mail"   placeholder="<?php echo $entry_mail; ?>" id="input-mail" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-category"><?php echo $entry_category; ?></label>
                <input type="text" name="filter_category"   placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-brand"><?php echo $entry_brand; ?></label>
                <input type="text" name="filter_brand"   placeholder="<?php echo $entry_brand; ?>" id="input-brand" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
               <div class="form-group">
                <label class="control-label" for="input-trade"><?php echo $entry_trade; ?></label>
                <input type="text" name="filter_trade"   placeholder="<?php echo $entry_trade; ?>" id="input-trade" class="form-control" />
              </div>
              <div class="form-group">
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
			  </div>
			  </div>
		      </div>
              </div>
                 <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-attribute1">
				 <div class="table-responsive">
				 <table  class="table table-bordered table-hover"   border="1px solid black";>
				 <thead>
					<tr> 
					    <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
						<td class="text-center col-sm-2">company name</td>
						<td class="text-center col-sm-2">phone number</td>
						<td class="text-center col-sm-2">email id</td>
						<td class="text-center col-sm-2">name of the contact person</td>
						<td class="text-center col-sm-2">alternate number</td>
						<td class="text-center col-sm-2">product category</td>
						<td class="text-center col-sm-2">Brands</td>
						<td class="text-center col-sm-2">Trade</td>
						</tr>
					</thead>
					<tbody>
        <?php if ($enquiries) { ?>
                <?php foreach ($enquiries  as $enquiry) { ?>
                <tr>
                  <td class="text-center"><input type="checkbox" name="selected[]" value="<?php echo $enquiry['id']; ?>"></td>
                  <td class="text-center"><?php echo $enquiry['user_info']['company']; ?></td>
                  <td class="text-center"><?php echo $enquiry['user_info']['number']; ?></td>
                  <td class="text-center"><?php echo $enquiry['user_info']['email']; ?></td>
                  <td class="text-center"><?php echo $enquiry['user_info']['name']; ?></td>
                  <td class="text-center"><?php echo $enquiry['user_info']['number_2']; ?></td>
                  <td class="text-center">
                  <?php foreach ($enquiry['categories']  as $category) { ?>
                  <?php echo $category; ?></td>
                  <?php } ?>                  
                   </td>
                   <td class="text-center">
                  <?php foreach ($enquiry['manufacturers']  as $manufacturer) { ?>
                  <?php echo $manufacturer; ?>
                  <?php } ?>
                   </td>
                  <td class="text-center">
                  <?php echo $enquiry['trade']; ?> 
                  </td>
                  <td class=text-right>    
                    <input type="hidden" name="enquiry_id" value="<?php echo $enquiry['id']; ?>" />
         			<button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#myModal">Supplier Form</button>
          		  </td>
                  </tr>
                  <?php } ?>
                   <?php } ?>
    </tbody>
	</table>
	</div>
	</form>
	</div>
	      </div>
	      </div>
	   		<div class="modal fade" id="myModal" role="dialog">
	    	<div class="modal-dialog">
	     	<div class="modal-content">
	        <div class="modal-header bg-primary">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          	<h4 class="modal-title">Supplier Form</h4>
        </div>
        <div class="modal-body bgColorWhite">
        <div class="panel-body">
	    <form id="supplier_form" class="col-sm-12" action="" method="post" enctype="multipart/form-data" >
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
	        <button class="btn btn-primary" onClick="location.href='<?php echo $filterLink; ?>'">Registration</button>
			</div>	 
			 </form>
			 </div 
			 </div>
		     <div  class="modal-footer ">
		     <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
		     </div>
		     </div>
			 </div>
			 </div> 
			 </div>
    <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	var url = "<?php echo $filterLink; ?>";

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_mail = $('input[name=\'filter_mail\']').val();

	if (filter_mail) {
		url += '&filter_mail=' + encodeURIComponent(filter_mail);
	}

	var filter_category = $('input[name=\'filter_category\']').val();

	if (filter_category) {
		url += '&filter_category=' + encodeURIComponent(filter_category);
	}

	var filter_brand = $('input[name=\'filter_brand\']').val();

	if (filter_brand) {
		url += '&filter_brand=' + encodeURIComponent(filter_brand);
	}
    
    var filter_trade = $('input[name=\'filter_trade\']').val();

	if (filter_trade) {
		url += '&filter_trade=' + encodeURIComponent(filter_trade);
	}
    
  location = url;
});
//--></script>   