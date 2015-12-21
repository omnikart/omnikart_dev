	 
		<form id="supplier_form" class=" " action="<?php echo $registration; ?>" method="POST" enctype="multipart/form-data" >
					<div class="col-sm-4">
					<div class="form-group required">
						<label class="control-label " for="name">Name of contact person</label>
					    <input  name="name" value="<?php echo $name;?>" class="form-control" placeholder="" type="text" id="name" /> 
					</div>
					<div class="form-group required">
						<label class="control-label" for="number">Moblie Number</label>
					 	<input  name="number" value="<?php echo $number;?>" class="form-control" placeholder="" type="text" id="number" /> 
				 	</div>
					<div class="form-group required">
						<label class="control-label" for="email">Email Id</label>
				 		<input  name="email" value="<?php echo $email;?>" class="form-control" placeholder="" type="email" id="email" /> 
				 	</div>
					<div class="form-group required">
						<label class="control-label" for="address_1">Address1</label>
				 		<input name="address_1" value="<?php echo(isset($address_1)?$address_1:'') ;?>"  class="form-control" placeholder="" type="text" id="address_1" /> 
				 	</div>
				 	</div>
				 	<div class="col-sm-4">
					<div class="form-group ">
						<label class="control-label" for="address_2">Address2</label>
					    <input name="address_2" value="<?php echo(isset($address_2)?$address_2:'') ;?>"  class="form-control" placeholder="" type="text" id="address_2" /> 
					 </div>
					  <div class="form-group required">
						<label class="control-label" for="city">City</label>
					 	<input name="city" value="<?php echo(isset($city)?$city:'') ;?>"  class="form-control" placeholder="" type="text" id="city" /> 
					 </div>
			       	<div class="form-group">
						<label class="control-label" for="postcode" >postcode</label>
					    <input name="postcode" value="<?php echo(isset($postcode)?$postcode:'') ;?>" class="form-control" placeholder="" type="text" id="postcode" />		
	                	</div>
            		   <div class="form-group required">
                        <label class=" control-label" for="input-country"><?php echo  "Country"; ?></label>
                         <select name="country_id" id="input-country " onchange="$('#input-zone').load('index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&zone_id=0&country_id=' + this.value);" class="form-control">
                            <option value="*"></option>
                            <?php foreach ($countries as $country) { ?>
                            <?php if ($country['country_id'] == $country_id) { ?>
                            <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select> 
                          </div>
                      </div>
                      <div class="col-sm-4">
					   <div class="form-group required">
                        <label class="control-label" for="input-zone "><?php echo "Region/State"; ?></label>
                        <select name="zone_id" id="input-zone" class="form-control">
                          </select>
                         </div>
                      <div class="form-group required">
						<label class="control-label" for="company" >Name of company</label>
						<input  name="company" value="<?php echo (isset($company)?$company:'');?>" class="form-control" placeholder="" type="text" id="company" />		
	                	</div>
            	 	 <div class="form-group">
		            	<label class="control-label" for="input-filename"><span data-original-title="upload files" data-toggle="tooltip" title="">Upload front image</span></label>
		                <input type="file" name="front" id="input-filename" class=" "/>
			            </div>
			           	<label class="control-label" for="input-filename1"><span data-original-title=" upload files" data-toggle="tooltip" title="">Upload back image</span></label>
		            	<input type="file" name="back" id="input-filename1" class=" "/>
			          </div> 	
          			</div>
	 	    		<!-- fileupload -->
		             <div class="text-right">
		                 <input type="submit" class="btn btn-primary"></input>
					</div>
		     </form>
		
<script type="text/javascript"><!--
			$('#input-zone').load('index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id=' + <?php echo (isset($country_id)?$country_id:'0'); ?>+'&zone_id=' + <?php echo (isset($zone_id)?$zone_id:'0'); ?>);
//--></script> 