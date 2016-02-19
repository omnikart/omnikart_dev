<style>
table {
    border-collapse: collapse;
    width:100%;
}
table, th, td {
    border: 1px solid #888;
    font-size: 90%;
    text-align: left;
}
table,tr{
border:0;
}
table.noborder {
margin:10 0px;
}
table.noborder th, table.noborder td {
    border:none;
}
table.noborder th, table.noborder td {
	padding:0px 10px;
}
thead,#customer{background:#eee;}
tbody tr:hover td{background:#eef;}
th, td {padding:5px;}

/* `border-box`... ALL THE THINGS! */
html {
  box-sizing: border-box;
}

*,
*:before,
*:after {
}
#id{
   border:0;
   font-size: 70%;
 }
body {
  margin: 64px auto;
  max-width: 640px;
  width: 94%;
  border:4px solid #ddd;
  padding: 35px;
}
.container{
font-size: 70%;
margin-top: 30px;
margin-left: 10px;
margin-bottom: 21px;
}
a:hover {
  text-decoration: none;
}
header,
.demo,
.demo p {
  margin: 4em 0;
  text-align: center;
}
p{
font-size: 60%;
}
input{
display: block;
width: 100;
}
select{
display: block;
width: 100;
}
#send{
display: block;
width: 100;
margin-top: 50;
float:right;
}
</style>

<table class="table table-bordered">
		<tbody>
		<tr>
			<td colspan="2"  style=" border-style:hidden;"><h1>Omnikart Price Quote</h1></td>
			<td style=" text-align: right; border-style:hidden;">
			Date: 11/11/1111</br>
			Quotation No: 12345</br>	
			Expiration Date: 12458		
			</td>
		</tr>
		</tbody>
</table>	
<hr>
<table class="table table-bordered">
	<tbody>
	<tr>
		<td style="width: 33%; border-style:hidden;">
				<label for="address<?php echo $supplier_address['supplier_address_id']; ?>">
					From,</br>
					<?php echo $supplier_address['firstname']; ?> <?php echo $supplier_address['lastname']; ?><br />
					<?php echo $supplier_address['address_1']; ?><br />
					<?php echo $supplier_address['city']; ?> <?php echo $supplier_address['postcode']; ?><br />
					<?php echo $supplier_address['zone']; ?> <?php echo $supplier_address['country']; ?>
				</label>
		</td>

		<td  style="border-style:hidden; text-align: right;">
			To,</br>
			<?php echo $firstname; ?> <?php echo $lastname; ?><br />
			<?php echo $address_1;?><br />
			<?php echo $city;?><br />
			<?php echo $zone;?> <?php echo $country;?>
			
		</td>
		</tr>
	</tbody>
</table>
<hr>
		<table  class="table table-bordered table-hover" >
			<thead>
				<tr>
					<td class="center" >Sr No.</td>
					<td style="text-align: center;">Name</td>
					<td class="center" >Quantity</td>
					<td >Description</td>
					<td class="center">Unit Price</td>
					<td class="center" >Discount</td>
					<td  style="width: 90px;text-align: center;">Tax class</td>
					<td class="center" >Total(INR)</td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($enquiries as $key=>$enquiry) { ?>
					<tr>
						<td style="text-align: center;"><?php echo $key + 1; ?></td>
						<?php if (isset($enquiry['link'])) { ?>
							<td><a href="<?php echo $enquiry['link']; ?>" > <?php echo $enquiry['name']; ?> </a></td>
						<?php } else { ?>
							<td><?php echo $enquiry['name']; ?></td>
						<?php } ?>
						<td style="text-align: center;"><?php echo $enquiry['quantity']; ?></td>
						<td>
						<?php echo round($enquiry['weight'],4); ?> <?php echo $enquiry['weight_class']; ?><hr>
						<?php echo round($enquiry['length'],4); ?> x <?php echo round($enquiry['width'],4); ?> x <?php echo round($enquiry['height'],4); ?> <?php echo $enquiry['length_class']; ?></td>
						<td style="text-align: center;"><?php echo $enquiry['price']; ?> <?php echo $enquiry['unit']; ?> </td>
						<td style="text-align: center;"></td>
						<td style="text-align: center;"><?php echo $enquiry['tax_class']; ?>
						</td>
                   		<td style="text-align: center;"><?php echo $enquiry['total']; ?></td>
					</tr>
				<?php } ?>
			</tbody>
			<tbody >
				<tr >
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id">Total discount</td>
					<td>0.00</td>
					
				</tr>
					<tr>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id">Tax Total</td>
					<td>0.00</td>
				</tr>
			
				<tr>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id">Freight Total</td>
					<td>0.00</td>
				</tr>
			
				<tr >
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id">Sub Total</td>
					<td>0.00</td>
					
				</tr>
				<tr>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id"></td>
					<td id="id">Total</td>
					<td>0.00</td>
				</tr>
			
			</tbody>
		</table>
		
	<div class="container">
		Term & Condition</br>
			This is a quotation on the goods named, subject to the conditions noted below:</br> 
			Payment Terms :</br>
				<?php foreach ($terms as $term) { ?>
				<?php echo $term['type']; ?> <?php echo $term['value']; ?></br>
				<?php }?>
			
				<div class="form-group">&nbsp;</div>			
			
			Omnikart Bank Details</br>
			Bank Name & Branch : HDFC Bank, Powai</br>
			Acoount Name : Omnikart Engineering Pvt Ltd</br>
			Account Number : 50200008446091</br>
			IFSC Code : HDFC0000239
	 </div>	
	<button id="send" type="button" class="btn btn-default btn-lg">Send</button>

