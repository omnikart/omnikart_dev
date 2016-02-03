<?php
// -----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
// -----------------------------------------------------
?>
<?php if ($tracks) { ?>
<div class="table-responsive">
	<table class="table table-condensed ne_stats_recipient_track">
		<thead>
			<tr>
				<th class="text-left"><?php echo $entry_url; ?></th>
				<th class="text-right"><?php echo $entry_clicks; ?></th>
			</tr>
		</thead>
		<tbody>
                <?php foreach ($tracks as $entry) { ?>
                    <tr>
				<td class="text-left"><a href="<?php echo $entry['url']; ?>"
					target="_blank"><?php echo $entry['url']; ?></a></td>
				<td class="text-right"><?php echo $entry['clicks']; ?></td>
			</tr>
                <?php } ?>
            </tbody>
	</table>
</div>
<?php } else { ?>
<p class="text-center"><?php echo $text_no_data ?></p>
<?php } ?>