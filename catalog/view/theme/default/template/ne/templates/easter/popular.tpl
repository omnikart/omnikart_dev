<?php
// -----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
// -----------------------------------------------------
?>
<?php if ($heading && $products) { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="padding-bottom: 12px; padding-top: 15px;">
				<table border="0" cellspacing="0" cellpadding="0"
					style="width: 100%;">
					<tbody>
						<tr>
							<td style="color:<?php echo $heading_color; ?>;<?php echo $heading_style; ?>"><span
								style="display: block; padding-top: 15px; height: 25px;"><?php echo $heading; ?></span></td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
<?php } ?>
<table width="700" border="0" cellspacing="0" cellpadding="0">
	<tbody>
    <?php foreach (array_chunk($products, $columns_count) as $row) { ?>
    <tr>
      <?php $count = count($row); if ($count < $columns_count) { for (; $count < $columns_count; $count++) { $row[$count] = false; } } ?>
      <?php foreach ($row as $key => $product) { ?>
      <?php if ($product) { ?>
      <td valign="top" style="border:1px <?php echo $description_color; ?> dashed;width:168px;text-align:center;">
				<table border="0" cellspacing="0" cellpadding="0"
					style="margin: 0 auto; text-align: center;">
					<tbody>
						<tr>
							<td style="padding-top: 13px;"><a
								href="<?php echo $product['href']; ?>"
								title="<?php echo $product['name']; ?>" target="_blank"><img
									src="<?php echo $product['thumb']; ?>"
									alt="<?php echo $product['name']; ?>"
									width="<?php echo $image_width; ?>"
									height="<?php echo $image_height; ?>" border="0"
									style="-moz-box-shadow: 0px 3px 5px 5px #ccc; -webkit-box-shadow: 0px 3px 5px 5px #ccc; box-shadow: 0px 3px 5px 5px #ccc; border: 1px #ccc solid;"></a>
							</td>
						</tr>
						<tr>
							<td
								style="padding: 8px 6px 20px 6px; width: 150px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
								<a style="color:<?php echo $name_color; ?>;<?php echo $name_style; ?>" href="<?php echo $product['href']; ?>" target="_blank"><?php echo $product['name']; ?></a>
								<br> <span style="color:<?php echo $model_color; ?>;<?php echo $model_style; ?>"><?php echo $product['model']; ?></span><br>
							<br>
                <?php if ($show_price) { ?>
                  <?php if (isset($product['special']) && $product['special']) { ?>
                    <span style="color:<?php echo $special_color; ?>;<?php echo $special_style; ?>"><?php echo $product['special']; ?></span><br>
								<span style="color:<?php echo $old_price_color; ?>;<?php echo $old_price_style; ?>"><s><?php echo $product['price']; ?></s></span>
                  <?php } else { ?>
                    <span style="color:<?php echo $price_color; ?>;<?php echo $price_style; ?>"><?php echo $product['price']; ?></span>
                  <?php } ?>
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
      
			
			<td style="width: 168px;">
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