<style>
table {
    border-collapse: collapse;
    width:100%;
}
table, th, td {
    border: 1px solid #888;
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


.right{text-align:right;}
.center{text-align:center;}
/**
 * Demo styles
 * Not needed for tooltips to work
 */

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
  font-size: 100%;
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

/**
 * Tooltip Styles
 */

/* Add this attribute to the element that needs a tooltip */
[data-tooltip] {
  position: relative;
  z-index: 2;
  cursor: pointer;
}

/* Hide the tooltip content by default */
[data-tooltip]:before,
[data-tooltip]:after {
  visibility: hidden;
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
  filter: progid: DXImageTransform.Microsoft.Alpha(Opacity=0);
  opacity: 0;
  pointer-events: none;
}

/* Position tooltip above the element */
[data-tooltip]:before {
  position: absolute;
  bottom: 150%;
  left: 50%;
  margin-bottom: 5px;
  margin-left: -80px;
  padding: 7px;
  width: 160px;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
  background-color: #000;
  background-color: hsla(0, 0%, 20%, 0.9);
  color: #fff;
  content: attr(data-tooltip);
  text-align: center;
  font-size: 14px;
  line-height: 1.2;
}

/* Triangle hack to make tooltip look like a speech bubble */
[data-tooltip]:after {
  position: absolute;
  bottom: 150%;
  left: 50%;
  margin-left: -5px;
  width: 0;
  border-top: 5px solid #000;
  border-top: 5px solid hsla(0, 0%, 20%, 0.9);
  border-right: 5px solid transparent;
  border-left: 5px solid transparent;
  content: " ";
  font-size: 0;
  line-height: 0;
}

/* Show tooltip content on hover */
[data-tooltip]:hover:before,
[data-tooltip]:hover:after {
  visibility: visible;
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
  filter: progid: DXImageTransform.Microsoft.Alpha(Opacity=100);
  opacity: 1;
}
</style>

<p>Dear {suppliername},<br /><br />
Our customer has enquired about followed products. Please <a href="{supplierquote}" data-tooltip="Please use the login credentials provided to login or use forgot password to reset your password.">click here</a> to reply to this enquiry with smart quotation.
</p>
<table class="table noborder">
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
<table class="table">
	<thead>
		<tr>
			<td class="center" style="max-width:50px;">Sr No.</td>
			<td style="max-width:100px;" data-tooltip="I’m the tooltip text.">Name
			</td>
			<td style="min-width:250px;">Description</td>
			<td class="center" style="max-width:70px;">Quantity</td>
			<td class="center" style="max-width:50px;">Unit</td>
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
				<td><?php echo $enquiry['description']; ?><br />
				<?php foreach ($enquiry['filenames'] as $file) { ?>
					<a href="<?php echo 'system/upload/queries/'.$file; ?>" target="_Blank"><?php echo substr($file,0,strrpos($file,'.',-1)); ?></a>
				<?php } ?>
				</td>
				<td class="center"><?php echo $enquiry['quantity']; ?></td>
				<td class="center"><?php echo $enquiry['unit_title']; ?></td>
			</tr>
		<?php } ?>
	</tbody>
	<tbody>
		<tr>
			<td colspan="5"></td>
		</tr>
		<?php foreach ($terms as $term) { ?>
		<tr>
			<td colspan="3" class="right"><?php echo $term['type']; ?></td>
			<td colspan="2"><?php echo $term['value']; ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
