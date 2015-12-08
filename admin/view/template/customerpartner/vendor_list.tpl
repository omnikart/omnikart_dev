<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      <button type="button" data-toggle="tooltip" title="<?php echo "delete"; ?>" class="btn btn-danger" onclick="confirm('<?php echo "confirm"; ?>') ? $('#form-attribute1').submit() : false;"><i class="fa fa-trash-o"></i></button>         
        <button type="button" class="btn btn-primary" onClick="location.href='http://localhost/~omnikartadmin/omnikart/admin/index.php?route=customerpartner/supplier_form&token=fcd47489dae36b09abafa6dafaaaddb8'">Supplier Form1</button>
        <button type="button1" class="btn btn-primary" onClick="location.href='http://localhost/~omnikartadmin/omnikart/admin/index.php?route=customerpartner/supplier_form2&token=fcd47489dae36b09abafa6dafaaaddb8'">Supplier Form2</button>

        </div>
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
                  <td class="text-center"><?php echo $enquiry['trade']; ?></td>
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
      </div>