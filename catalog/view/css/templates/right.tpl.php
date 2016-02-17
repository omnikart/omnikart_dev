<?php
$selectorMaxWidth = ( int ) self::$options->getValue ( 'selector-max-width' );
$dataOptions = '';
if (! empty ( $magicscroll )) {
	$dataOptions .= 'orientation: vertical;';
	$dataOptions = " data-options=\"{$dataOptions}\"";
}
?>
<!-- Begin magiczoom -->
<div class="MagicToolboxContainer selectorsRight minWidth">
	<div class="MagicToolboxMainContainer">
        <?php echo $main; ?>
    </div>
<?php
if (count ( $thumbs ) > 1) {
	?>
    <div class="MagicToolboxSelectorsContainer" style="width: <?php echo $selectorMaxWidth ?>px;">
		<div id="MagicToolboxSelectors<?php echo $pid ?>"
			class="<?php echo $magicscroll ?>" <?php echo $dataOptions ?>>
            <?php echo join("\n\t", $thumbs); ?>
        </div>
	</div>
    <?php
	if (! empty ( $magicscroll ) && ! is_numeric ( self::$options->getValue ( 'height' ) )) {
		?>
            <script type="text/javascript">
                mzOptions = mzOptions || {};
                mzOptions.onUpdate = function() {
                    MagicScroll.resize('MagicToolboxSelectors<?php echo $pid ?>');
                };
            </script>
            <?php
	}
	?>
    <?php
}
?>
</div>
<!-- End magiczoom -->
