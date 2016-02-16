<?php
if (isNitroEnabled () && (getNitroPersistence ( 'Smush.Method' ) && getNitroPersistence ( 'Smush.Method' ) == 'local') && getNitroPersistence ( 'Smush.OnDemand' )) {
	
	if (! empty ( $new_image ) && file_exists ( DIR_IMAGE . $new_image )) {
		loadNitroLib ( 'NitroSmush/NitroSmush' );
		$smusher = new NitroSmush ();
		global $registry;
		$log = $registry->get ( 'log' );
		
		try {
			$filename = DIR_IMAGE . $new_image;
			$res = $smusher->smush ( $filename );
			if (! empty ( $res ['errors'] ) && NITRO_DEBUG_MODE == 1) {
				$log->write ( "[NitroSmush: $filename] => " . var_export ( $res, true ) );
			}
		} catch ( Exception $e ) {
			set_time_limit ( 30 );
		}
	}
}
