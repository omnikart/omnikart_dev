<?php
if (isNitroEnabled () && getNitroPersistence ( 'PageCache.Enabled' )) {
	require_once (DIR_SYSTEM . 'nitro/core/bottom.php');
}