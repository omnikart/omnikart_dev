<?php
// -----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
// -----------------------------------------------------
?>
<?php if ($heading && $products) { ?>
<table width="750" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="padding-bottom: 12px; padding-top: 0px;">
				<table border="0" cellspacing="0" cellpadding="0"
					style="width: 100%;">
					<tbody>
						<tr>
							<td style="color:<?php echo $heading_color; ?>;<?php echo $heading_style; ?>"><?php echo $heading; ?></td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
<?php } ?>
<table width="750" border="0" cellspacing="0" cellpadding="0">
	<tbody>
    <?php foreach (array_chunk($products, $columns_count) as $row) { ?>
    <tr>
      <?php $count = count($row); if ($count < $columns_count) { for (; $count < $columns_count; $count++) { $row[$count] = false; } } ?>
      <?php foreach ($row as $key => $product) { ?>
      <?php if ($product) { ?>
      <td valign="top"
				style="background: #f77341; width: 180px; text-align: center;">
				<table border="0" cellspacing="0" cellpadding="0"
					style="margin: 0 auto; text-align: center;">
					<tbody>
						<tr>
							<td><a href="<?php echo $product['href']; ?>"
								title="<?php echo $product['name']; ?>" target="_blank"><img
									src="<?php echo $product['thumb']; ?>"
									alt="<?php echo $product['name']; ?>"
									width="<?php echo $image_width; ?>"
									height="<?php echo $image_height; ?>" border="0"
									style="background: #fff; padding: 14px 20px;"></a></td>
						</tr>
						<tr>
							<td
								style="padding: 8px 6px 20px 6px; width: 150px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
								<a style="color:<?php echo $name_color; ?>;<?php echo $name_style; ?>" href="<?php echo $product['href']; ?>" target="_blank"><?php echo $product['name']; ?></a>
								<br> <span style="color:<?php echo $model_color; ?>;<?php echo $model_style; ?>"><?php echo $product['model']; ?></span><br>
							<br>
                <?php if ($show_price) { ?>
                  <span style="color:<?php echo $special_color; ?>;<?php echo $special_style; ?>"><?php echo $product['special']; ?></span><br>
								<span style="color:<?php echo $old_price_color; ?>;<?php echo $old_price_style; ?>"><s><?php echo $product['price']; ?></s></span>
                <?php } ?>
				<?php if ($show_saving && $product['saving']) { ?>
					<br>
							<span style="color:<?php echo $saving_color; ?>;<?php echo $saving_style; ?>"><?php echo $product['saving']; ?></span><br>
							<br>
				<?php } elseif ($show_price) { ?>
					<br>
							<br>
				<?php } ?>
                <?php if ($product['description']) { ?>
                <span style="color:<?php echo $description_color; ?>;<?php echo $description_style; ?>"><?php echo $product['description']; ?></span><br>
							<br>
                <?php } ?>
              </td>
						</tr>
					</tbody>
				</table>
      <?php } else { ?>
      
			
			<td style="width: 180px;">
      <?php } ?>
      </td>
      <?php if ($key + 1 < $columns_count) { ?>
      <td style="width: 12px;"></td>
      <?php } ?>
      <?php } ?>
    </tr>
		<tr>
			<td style="height: 12px;">&nbsp;</td>
		</tr>
    <?php } ?>
  </tbody>
</table>
<?php if ($heading && $products) { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="padding-bottom: 12px;">
				<table border="0" cellspacing="0" cellpadding="0"
					style="width: 100%;">
					<tbody>
						<tr>
							<td style="background: url(<?php echo HTTP_SERVER; ?>catalog/view/theme/default/template/ne/templates/summer/images/productbot.png) center no-repeat;" height="32" width="100%"></td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
<?php } ?>