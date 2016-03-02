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
			<?php echo $date;?></br>
			Quotation No: <?php echo $quote_number;?></br>	
			Expiration Date: <?php echo $expiration_date; ?>		
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
			<?php echo $address['firstname']; ?> <?php echo $lastname; ?><br />
			<?php echo $address['address_1'];?><br />
			<?php echo $address['city'];?><br />
			<?php echo $address['zone'];?> <?php echo $address['country'];?>
			
		</td>
		</tr>
	</tbody>
</table>
<hr>
		<table  class="table table-bordered table-hover">
			<thead>
				<tr>
					<td class="center" >Sr No.</td>
					<td style="text-align: center;">Name</td>
					<td class="center" style="width:100px;">Qty.</td>
					<td class="center" style="width:100px;">Unit Price</td>
					<td class="center"  style="width:100px;">Discount</td>
					<td  style="width: 90px;text-align: center;">Tax class</td>
					<td class="center" >Total(INR)</td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($enquiries as $key=>$enquiry) { ?>
					<tr>
						<td style="text-align: center;"><?php echo $key + 1; ?></td>
						<?php if (isset($enquiry['link'])) { ?>
							<td><a href="<?php echo $enquiry['link']; ?>" > <?php echo $enquiry['name']; ?> </a>
							<br />
							<?php echo $enquiry['description']; ?>
							</td>
						<?php } else { ?>
							<td><?php echo $enquiry['name']; ?><br />
							<?php echo $enquiry['description']; ?>
							</td>
						<?php } ?>
						<td style="text-align: center;"><?php echo $enquiry['quantity']; ?> <?php echo $enquiry['unit']; ?></td>
						<td style="text-align: center;"><?php echo $enquiry['text_price']; ?> </td>
						<td style="text-align: center;"><?php echo $enquiry['discount']; ?></td>
						<td style="text-align: center;"><?php echo $enquiry['tax_class']; ?>
						</td>
                   		<td style="text-align: center;"><?php echo $enquiry['total']; ?></td>
					</tr>
				<?php } ?>
			</tbody>
			<?php if ($totals) { ?>
				<tbody >
					<?php foreach ($totals as $total) { if (0!=$total['value']) { ?>
						<tr >
							<td colspan="5" id="id"></td>
							<td><?php echo $total['title']?></td>
							<td><?php echo $total['text']?></td>
						</tr>
					<?php } } ?>
				</tbody>
			<?php } ?>
		</table>
		
	<div class="container">
		Term & Condition</br>
			This is a quotation on the goods named, subject to the conditions noted below:</br> 
			<?php foreach ($terms as $term) { ?>
			<?php echo $term['type']; ?> : <?php echo $term['value']; ?></br>
			<?php }?>
			</br>
			Omnikart Bank Details : </br>
			<?php echo $profile['otherpayment']; ?>
	 </div>	
	<button id="send" type="button" class="btn btn-default btn-lg">Send</button>

