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
th, td {padding:10px;}

/* `border-box`... ALL THE THINGS! */
html {
  box-sizing: border-box;
}

*,
*:before,
*:after {
  box-sizing: inherit;
}

body {
  margin: 64px auto;
  max-width: 640px;
  width: 94%;
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
margin-left: 600;
}
</style>

<div class="panel panel-default">
<table class="table table-bordered">
	<tbody>
		<tr><td colspan="2">
			<div class="clearfix quote-address pull-left">
				<label for="address<?php echo $supplier_address['supplier_address_id']; ?>">
					<?php echo $supplier_address['firstname']; ?> <?php echo $supplier_address['lastname']; ?></td>
					<td colspan="2">
					<?php echo $supplier_address['address_1']; ?>,
					<?php echo $supplier_address['city']; ?> <?php echo $supplier_address['postcode']; ?>,
					<?php echo $supplier_address['zone']; ?> <?php echo $supplier_address['country']; ?>
				</label>
			  </div>
		</td></tr>
		<tr><td colspan="4" class="pull-left" >Quotation No: 12345</td></tr>
		<tr id="customer"><td colspan="2"  class="pull-left">Customer Details</td>
		<td colspan="2">Information</td></tr>
		<tr>
			<td style="width:100px">Name</td><td>: <?php echo $firstname.' '.$lastname; ?></td>
			<td style="width:100px">Date</td><td>:11/10/1993</td>
		</tr>
		<tr>
			<td style="width:100px">Email</td><td>:<?php echo $email; ?></td>
			<td style="width:100px">Quote Expiration Date</td><td>:11/10/1993</td>
		</tr>
		<tr>
			<td style="width:100px">Telephone</td><td>: <?php echo $telephone; ?></td>
			<td style="width:100px">Delivery Lead Time</td><td>:1 week</td>
		</tr>
		<tr>
			<td rowspan="2" >Address</td><td rowspan="2" > 
			<?php echo $address_1;?><br />
			<?php echo $city;?><br />
			<?php echo $zone;?><br />
			<?php echo $country;?>
			</td>
			<td style="width:100px">Contact Name </td><td>:shailesh</td>
		</tr>
		<tr><td style="width:100px">Contact No </td><td>:9004458077</td></tr>
		<tr>
			<td colspan="4"></td>
		</tr>
	</tbody>
</table>
</div>
<div class="panel panel-default">
  <div class="panel-body">
		<table  class="table table-bordered table-hover"  border="1px solid black";>
			<thead>
				<tr>
					<td class="center" >Sr No.</td>
					<td>Name</td>
					<td class="center" >Quantity</td>
					<td >Description</td>
					<td class="center" >Unit Price</td>
					<td class="center" >Tax class</td>
					<td class="center" >Total(INR)</td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($enquiries as $key=>$enquiry) { ?>
					<tr>
						<td class="center"><?php echo $key; ?></td>
						<?php if (isset($enquiry['link'])) { ?>
							<td><a href="<?php echo $enquiry['link']; ?>" > <?php echo $enquiry['name']; ?> </a></td>
						<?php } else { ?>
							<td><?php echo $enquiry['name']; ?></td>
						<?php } ?>
						<td class="center"><?php echo $enquiry['quantity']; ?></td>
						<td>
						<?php echo round($enquiry['weight'],4); ?> <?php echo $enquiry['weight_class']; ?><hr>
						<?php echo round($enquiry['length'],4); ?> x <?php echo round($enquiry['width'],4); ?> x <?php echo round($enquiry['height'],4); ?> <?php echo $enquiry['length_class']; ?></td>
						<td class="center"><?php echo $enquiry['price']; ?> <?php echo $enquiry['unit']; ?> </td>
						<td class="center"><?php echo $enquiry['tax_class']; ?></td>
                   		<td class="center"><?php echo $enquiry['total']; ?></td>
					</tr>
				<?php } ?>
			</tbody>
				<?php foreach ($terms as $term) { ?>
				<tr>
					<td colspan="4"></td>
					<td colspan="1" class="right"><?php echo $term['type']; ?></td>
					<td colspan="2">
					<?php echo $term['value']; ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
  	</div>
</div>
<button id="send" type="button" class="btn btn-default btn-lg">Send</button>
