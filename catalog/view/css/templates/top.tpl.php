<?php
$dataOptions = '';
if (! empty ( $magicscroll )) {
	$dataOptions .= 'orientation: horizontal;';
	$dataOptions = " data-options=\"{$dataOptions}\"";
}
?>
<!-- Begin magiczoom -->
<div class="MagicToolboxContainer selectorsTop minWidth">
<?php
if (count ( $thumbs ) > 1) {
	?>
    <div class="MagicToolboxSelectorsContainer">
		<div id="MagicToolboxSelectors<?php echo $pid ?>"
			class="<?php echo $magicscroll ?>" <?php echo $dataOptions ?>>
        <?php echo join("\n\t", $thumbs); ?>
        </div>
	</div>
    <?php
}
?>
    <?php echo $main; ?>
</div>
<!-- End magiczoom -->
