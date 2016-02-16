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
thead{background:#eee;}
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
		<tr>
			<td style="width:100px">Name</td><td>: <?php echo $firstname.' '.$lastname; ?></td>
		</tr>
		<tr>
			<td >Email</td><td>: <?php echo $email; ?></td>
		</tr>
		<tr>
			<td >Telephone</td><td>: <?php echo $telephone; ?></td>
		</tr>
		<tr>
			<td >Postcode</td><td>: <?php echo $postcode; ?></td>
		</tr>
		<tr>
			<td colspan="2"></td>
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
					<td >Description</td>
					<td class="center" >Quantity</td>
					<td class="center" >Unit Price</td>
					<td class="center" >Tax class</td>
					<td class="center" >Total Price</td>
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
						<td>
						<?php echo round($enquiry['weight'],4); ?> <?php echo $enquiry['weight_class']; ?><hr>
						<?php echo round($enquiry['length'],4); ?> <?php echo $enquiry['length_class']; ?></td>
						<td class="center"><?php echo $enquiry['quantity']; ?></td>
						<td class="center"><?php echo $enquiry['price']; ?> <?php echo $enquiry['unit']; ?> </td>
						<td class="center"><?php echo $enquiry['tax_class']; ?></td>
                   		<td class="center"><?php echo $enquiry['total']; ?></td>
					</tr>
				<?php } ?>
			</tbody>
				<?php foreach ($terms as $term) { ?>
				<tr>
					<td colspan="3" class="right"><?php echo $term['type']; ?></td>
					<td colspan="3">
					<select class="form-control" name="enquiry[term][<?php echo $key; ?>][term_value]">
						<?php foreach($payment_term as $pterm) { ?>
						<option value="<?php echo $pterm['payment_term_id']; ?>" <?php echo ($pterm['payment_term_id']==$term['value']?'selected="selected"':''); ?>  ><?php echo $pterm['name']; ?></option>
						<?php } ?>
					</select>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
  	</div>
</div>
<button id="send" type="button" class="btn btn-default btn-lg">Send</button>
