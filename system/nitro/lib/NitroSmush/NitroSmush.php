<?php
class NitroSmush {
	private $_temp_file_prefix = 'nitrosmush_temp';
	private $_file_loaded = 'loaded.temp';
	private $_output_errors = 'output_errors.temp';
	private $_filesize_limit = 524288000; // 500 MB - Nobody is that brave to upload an image > 500 MB, right?
	private $_debug = false;
	private $_temp_dir = '';
	private $_session = '';
	private $_api = array (
			'http_url' => 'http://nitrosmush.com/api.php' 
	);
	private $_config = array (
			'~\.gif$~i' => array (
					'gifsicle' => array (
							'linux32' => '',
							'linux64' => 'gifsicle_linux',
							'mac32' => '',
							'mac64' => 'gifsicle_mac',
							'win32' => '',
							'win64' => 'gifsicle_win.exe',
							'help_test' => array (
									'flag' => '--help',
									'expect' => 'manipulates GIF images' 
							),
							'extension' => 'gif',
							'settings' => array (
									'{EXE} --no-warnings --careful --optimize=3 --output={DESTINATION} {SOURCE} 2> {STDERR}' 
							) 
					) 
			),
			'~\.jpe?g$~i' => array (
					'jpegoptim' => array (
							'linux32' => '',
							'linux64' => 'jpegoptim_linux',
							'mac32' => '',
							'mac64' => 'jpegoptim_mac',
							'win32' => '',
							'win64' => 'jpegoptim_win.exe',
							'help_test' => array (
									'flag' => '-V',
									'expect' => 'jpegoptim' 
							),
							'extension' => 'jpg',
							'settings' => array (
									'{EXE} --dest={DESTINATION_DIR} -o --strip-all --all-normal {SOURCE} 2> {STDERR}',
									'{EXE} --dest={DESTINATION_DIR} -o --strip-all --all-progressive {SOURCE} 2> {STDERR}' 
							) 
					),
					'jpegtran' => array (
							'linux32' => '',
							'linux64' => 'jpegtran_linux',
							'mac32' => '',
							'mac64' => 'jpegtran_mac',
							'win32' => '',
							'win64' => 'jpegtran_win.exe',
							'extension' => 'jpg',
							'settings' => array (
									'{EXE} -copy none -progressive -optimize -outfile {DESTINATION} {SOURCE} 2> {STDERR}',
									'{EXE} -copy none -optimize -outfile {DESTINATION} {SOURCE} 2> {STDERR}',
									'{EXE} -copy none -progressive -outfile {DESTINATION} {SOURCE} 2> {STDERR}',
									'{EXE} -copy none -outfile {DESTINATION} {SOURCE} 2> {STDERR}' 
							) 
					) 
			),
			'~\.png$~i' => array (
					'optipng' => array (
							'linux32' => '',
							'linux64' => 'optipng_linux',
							'mac32' => '',
							'mac64' => 'optipng_mac',
							'win32' => '',
							'win64' => 'optipng_win.exe',
							'help_test' => array (
									'flag' => '--help',
									'expect' => 'chosen heuristically' 
							),
							'extension' => 'png',
							'settings' => array (
									'{EXE} -silent -clobber -out {DESTINATION} {SOURCE} 2> {STDERR}' 
							) 
					),
					'pngcrush' => array (
							'linux32' => '',
							'linux64' => 'pngcrush_linux',
							'mac32' => '',
							'mac64' => '',
							'win32' => '',
							'win64' => 'pngcrush_win.exe',
							'extension' => 'png',
							'settings' => array (
									'{EXE} -z {FOR2} -w {FOR32} -ow -s -d {DESTINATION_DIR} -force {SOURCE} 2> {STDERR}' 
							) 
					) 
			)
			// 'pngout' => array(
			// 'linux32' => '',
			// 'linux64' => 'pngout_linux',
			// 'mac32' => '',
			// 'mac64' => 'pngout_mac',
			// 'win32' => '',
			// 'win64' => 'pngout_win.exe',
			// 'extension' => 'png',
			// 'settings' => array(
			// '{EXE} -c6 -f2 -s0 -b192 -y -q -force {SOURCE} {DESTINATION} 2> {STDERR}'
			// )
			// )
			 
	);
	public function __construct() {
		$this->_session = md5 ( strval ( microtime ( true ) ) );
	}
	public function setTempDir($tmp_dir) {
		$this->_temp_dir = $tmp_dir;
	}
	public function debug($state) {
		$this->_debug = $state;
	}
	
	/*
	 * Run this in the admin panel, best to do it with an AJAX call, since it takes time and might timeout the admin panel on slower servers.
	 * Returns: Array() with all errors encountered.
	 */
	public function loadExecutables($reload = false) {
		if (! file_exists ( $this->_temp_path ( $this->_file_loaded, true ) ) || $reload) {
			return $this->_load_executables ();
		}
		return array ();
	}
	
	/*
	 * $source: The source of the image. Can be a relative ot absolute path. Accepted extensions: GIF, PNG, JP(E)G
	 * $destination: The destination image. Can be a different name, or the same name. If not specified, the smusher tries to overwrite the original image
	 *
	 * Returns:
	 *
	 * Array (
	 * smushed: Boolean value, indicating if the image has been smushed
	 * savings_b: Bytes saved
	 * savings_percent: How many percent have been saved
	 * errors: Array() containing errors
	 * )
	 */
	public function smush($source, $destination = false, $use_api = false) {
		$loadErrors = $this->loadExecutables ();
		if (empty ( $destination )) {
			$destination = $source;
		}
		
		$this->_set_error_handler ();
		
		$source = realpath ( $source );
		touch ( $destination );
		$destination = realpath ( $destination );
		
		$temp_outputs = array ();
		$index = 0;
		$errors = array ();
		
		if (! $use_api) {
			try {
				$this->_check_compatibility ();
				
				$os = $this->_get_os_suffix ();
				
				foreach ( $this->_config as $filetype_regex => $filetype_config ) {
					if (! preg_match ( $filetype_regex, $source ))
						continue;
					
					foreach ( $filetype_config as $tool_name => $tool_config ) {
						if (! $this->_executable_is_loaded ( $tool_name ))
							continue;
						
						if (empty ( $tool_config [$os] ))
							continue;
						
						$exe = $this->_get_exe ( $tool_name, $tool_config [$os] );
						
						foreach ( $this->_parse_config_for ( $tool_config ['settings'] ) as $setting ) {
							try {
								$temp_dest = $this->_temp_path ( basename ( $source ) );
								$this->_process_image ( $exe, $setting, $source, $temp_dest );
								
								if ($this->_is_readable ( $temp_dest ) && $this->_is_writable ( $this->_temp_path ( '' ), false )) {
									copy ( $temp_dest, $temp_dest . '.' . $index );
									
									$temp_outputs [] = $temp_dest . '.' . $index;
									
									$index ++;
								}
							} catch ( Exception $e ) {
								$errors [] = $e->getMessage ();
							}
						}
					}
				}
			} catch ( Exception $e ) {
				$errors [] = $e->getMessage ();
			}
		}
		
		$min = $this->_filesize_limit;
		$winner = false;
		
		foreach ( $temp_outputs as $temp_output ) {
			$temp_size = filesize ( $temp_output );
			
			if ($temp_size > 0 && $temp_size < $min) {
				$min = $temp_size;
				$winner = $temp_output;
			}
		}
		
		$result = array (
				'smushed' => false,
				'savings_b' => 0,
				'savings_percent' => 0,
				'errors' => $errors 
		);
		
		try {
			if ($use_api) {
				$api_result = $this->_smush_api ( array (
						$this,
						'_curl_api' 
				), array (
						$this,
						'_file_api' 
				), $source );
				
				$winner = $api_result ['result_file'];
				$min = $api_result ['result_size'];
			}
			
			if (! empty ( $winner )) {
				$previous_size = filesize ( $source );
				
				if ($this->_is_readable ( $winner ) && $this->_is_writable ( $destination, false ) && $min < $previous_size) {
					copy ( $winner, $destination );
					
					$result ['smushed'] = true;
					$result ['savings_b'] = $previous_size - $min;
					$result ['savings_percent'] = round ( (1 - ($min / $previous_size)) * 100, 2 );
				}
			}
			
			$this->_clean_temp ();
		} catch ( Exception $e ) {
			$result ['errors'] [] = $e->getMessage ();
		}
		
		if (! empty ( $loadErrors )) {
			$result ['errors'] = array_merge ( $loadErrors, $result ['errors'] );
		}
		
		$this->_restore_error_handler ();
		
		return $result;
	}
	private function _set_error_handler() {
		set_error_handler ( create_function ( '$severity, $message, $file, $line', 'throw new Exception($message . " in file " . $file . " on line " . $line);' ) );
	}
	private function _restore_error_handler() {
		restore_error_handler ();
	}
	private function _get_mime($file) {
		if (preg_match ( '~\.gif$~i', $file )) {
			return 'image/gif';
		} else if (preg_match ( '~\.jpe?g$~', $file )) {
			return 'image/jpeg';
		} else if (preg_match ( '~\.png$~i', $file )) {
			return 'image/png';
		} else {
			throw new Exception ( "Invalid extension of " . $file );
		}
	}
	private function _curl_api($source) {
		$ch = curl_init ( $this->_api ['http_url'] );
		
		if (class_exists ( 'CURLFile' )) {
			$post_file = new CURLFile ( $source, $this->_get_mime ( $source ), basename ( $source ) );
		} else {
			$post_file = '@' . $source;
		}
		
		curl_setopt_array ( $ch, array (
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array (
						'image' => $post_file 
				),
				CURLOPT_RETURNTRANSFER => 1 
		) );
		
		$json = curl_exec ( $ch );
		
		$response = json_decode ( $json, true );
		
		curl_close ( $ch );
		
		if (! empty ( $response ['error'] )) {
			throw new Exception ( $response ['error'] );
		}
		
		return $this->_download_file ( $response ['result_file'] );
	}
	private function _file_api($source) {
		$data = "";
		
		$boundary = "---------------------" . substr ( md5 ( mt_rand ( 0, 32000 ) ), 0, 10 );
		
		$data .= "--" . $boundary . "\n";
		
		$fileContents = file_get_contents ( $source );
		
		$data .= "Content-Disposition: form-data; name=\"image\"; filename=\"" . basename ( $source ) . "\"\n";
		$data .= "Content-Type: " . $this->_get_mime ( $source ) . "\n";
		$data .= "Content-Transfer-Encoding: binary\n\n";
		$data .= $fileContents . "\n";
		
		$data .= "--" . $boundary . "\n";
		
		$params = array (
				'http' => array (
						'method' => 'POST',
						'header' => 'Content-Type: multipart/form-data; boundary=' . $boundary,
						'content' => $data 
				) 
		);
		
		$url = $this->_api ['http_url'];
		
		$ctx = stream_context_create ( $params );
		$fp = fopen ( $url, 'rb', false, $ctx );
		
		if (! $fp) {
			throw new Exception ( "There was a problem with $url" );
		}
		
		$json = @stream_get_contents ( $fp );
		
		fclose ( $fp );
		
		if ($json === false) {
			throw new Exception ( "Problem reading data from $url" );
		}
		
		$response = json_decode ( $json, true );
		
		if (! empty ( $response ['error'] )) {
			throw new Exception ( $response ['error'] );
		}
		
		return $this->_download_file ( $response ['result_file'] );
	}
	private function _download_file($url) {
		$result = array (
				'result_file' => false,
				'result_size' => 0 
		);
		
		$download = $this->_smush_api ( array (
				$this,
				'_curl_download' 
		), array (
				$this,
				'_file_download' 
		), $url );
		
		$this->_is_readable ( $download );
		
		$result ['result_file'] = $download;
		$result ['result_size'] = filesize ( $download );
		
		return $result;
	}
	private function _curl_download($file) {
		$destination = $this->_temp_path ( 'downloaded' );
		
		$ch = curl_init ( $file );
		
		touch ( $destination );
		$this->_is_writable ( $destination );
		$fh = fopen ( $destination, 'wb' );
		
		curl_setopt_array ( $ch, array (
				CURLOPT_FILE => $fh 
		) );
		
		curl_exec ( $ch );
		fclose ( $fh );
		
		if (filesize ( $destination ) == 0) {
			throw new Exception ( "An error has been encountered while downloading " . $file );
		}
		
		curl_close ( $ch );
		
		return $destination;
	}
	private function _file_download($file) {
		$destination = $this->_temp_path ( 'downloaded' );
		
		touch ( $destination );
		$this->_is_writable ( $destination );
		
		file_put_contents ( $destination, file_get_contents ( $file ) );
		
		return $destination;
	}
	private function _smush_api($callable_curl, $callable_file, $arg) {
		if (function_exists ( 'curl_init' )) {
			return call_user_func ( $callable_curl, $arg );
		} else if (ini_get ( 'allow_url_fopen' ) == '1') {
			return call_user_func ( $callable_file, $arg );
		} else {
			throw new Exception ( "Your server does not support CURL and allow_url_fopen is disalbed. At least one of these options must be enabled on your server. Please contact your web hosting provider." );
		}
	}
	private function _clean_temp() {
		$dir = $this->_temp_path ( '' );
		
		$items = scandir ( $dir );
		
		foreach ( $items as $item ) {
			if (! in_array ( $item, array (
					'.',
					'..' 
			) )) {
				unlink ( $dir . $item );
			}
		}
		
		rmdir ( $dir );
	}
	private function _check_compatibility() {
		if (! $this->_exec_enabled ()) {
			throw new Exception ( "exec() function not allowed! Please contact your web hosting provider to enable it." );
		}
	}
	private function _exec_enabled() {
		$command = function_exists ( 'exec' ) && ! in_array ( 'exec', array_map ( 'trim', explode ( ', ', ini_get ( 'disable_functions' ) ) ) ) && ! (strtolower ( ini_get ( 'safe_mode' ) ) != 'off' && ini_get ( 'safe_mode' ) != 0);
		
		if ($command) {
			$result = array ();
			exec ( 'whoami', $result );
			return ! empty ( $result );
		}
		
		return false;
	}
	private function _substr_caps($text, $length) {
		return strtoupper ( substr ( $text, 0, $length ) );
	}
	private function _os() {
		return php_uname ( 's' );
	}
	private function _check_dir_create($tmp_dir, $target_dir) {
		if (! is_dir ( $target_dir )) {
			if ($this->_is_writable ( $tmp_dir, false )) {
				mkdir ( $target_dir, 0755 );
				
				$this->_is_writable ( $target_dir, false );
				$this->_is_readable ( $target_dir, false );
			}
		}
	}
	private function _temp_path($file, $base = false) {
		$tmp_dir = ! empty ( $this->_temp_dir ) ? $this->_temp_dir : sys_get_temp_dir ();
		$target_dir = rtrim ( $tmp_dir, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . $this->_temp_file_prefix;
		
		$this->_check_dir_create ( $tmp_dir, $target_dir );
		
		if (! empty ( $this->_session ) && ! $base) {
			$target_dir .= DIRECTORY_SEPARATOR . $this->_session;
			
			$this->_check_dir_create ( $tmp_dir, $target_dir );
		}
		
		$path = $target_dir . DIRECTORY_SEPARATOR . $file;
		
		return $path;
	}
	private function _chmod($path, $mode = false) {
		$path = realpath ( $path );
		
		$uname = $this->_os ();
		
		if ($this->_substr_caps ( $uname, 5 ) != 'LINUX' && $this->_substr_caps ( $uname, 6 ) != 'DARWIN') {
			return false;
		}
		
		if ($mode === false) {
			if (is_file ( $path )) {
				$mode = 0644;
			} else if (is_dir ( $path )) {
				$mode = 0755;
			} else {
				return false;
			}
		}
		
		return chmod ( $path, $mode );
	}
	private function _is_readable($path, $touch = true) {
		if ($touch) {
			touch ( $path );
		}
		
		$path = realpath ( $path );
		
		if (is_readable ( $path )) {
			return true;
		} else {
			$this->_chmod ( $path );
			
			if (is_readable ( $path )) {
				return true;
			} else {
				throw new Exception ( "Path is not readable: " . $path );
			}
		}
	}
	private function _is_writable($path, $touch = true) {
		if ($touch) {
			touch ( $path );
		}
		
		$path = realpath ( $path );
		
		if (is_writable ( $path )) {
			return true;
		} else {
			$this->_chmod ( $path );
			
			if (is_writable ( $path )) {
				return true;
			} else {
				throw new Exception ( "Path is not writable: " . $path );
			}
		}
	}
	private function _arch() {
		return 64;
		
		// $arch = php_uname('m');
		
		// if ($arch == 'x86_64' || $arch == 'amd64') {
		// return 64;
		// } else {
		// return 32;
		// }
	}
	private function _process_image($exe, $setting, $source_path, $destination_path) {
		$output_errors = $this->_temp_path ( $this->_output_errors );
		
		$this->_is_readable ( $source_path );
		$this->_is_writable ( dirname ( $source_path ), false );
		$this->_is_writable ( dirname ( $destination_path ), false );
		$this->_is_readable ( $output_errors );
		$this->_is_writable ( $output_errors );
		
		if (filesize ( $source_path ) > $this->_filesize_limit) {
			throw new Exception ( "File " . $source_path . " is larger than 500 MB!" );
		}
		
		$find = array (
				'{EXE}',
				'{SOURCE}',
				'{DESTINATION}',
				'{DESTINATION_DIR}',
				'{STDERR}' 
		);
		
		$replace = array (
				$exe,
				escapeshellarg ( $source_path ),
				escapeshellarg ( $destination_path ),
				escapeshellarg ( dirname ( $destination_path ) ),
				escapeshellarg ( $output_errors ) 
		);
		
		$command = str_replace ( $find, $replace, $setting );
		
		exec ( $command );
		
		$errors = file_get_contents ( $output_errors );
		
		if (! empty ( $errors )) {
			throw new Exception ( 'Executing ' . $command . ' returned the following error(s): ' . $errors );
		}
	}
	private function _test_config($exe, $extension, $setting) {
		$source_path = dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . 'Images' . DIRECTORY_SEPARATOR . 'source.' . $extension;
		$destination_path = $this->_temp_path ( 'result.' . $extension );
		
		$this->_process_image ( $exe, $setting, $source_path, $destination_path );
	}
	private function _parse_config_for($settings) {
		$new_settings = array ();
		
		foreach ( $settings as $setting ) {
			$for_matches = array ();
			
			preg_match_all ( '~{FOR(.*?)}~', $setting, $for_matches );
			
			if (! empty ( $for_matches [1] [0] )) {
				$for_match = $for_matches [1] [0];
				
				$search = '{FOR' . $for_match . '}';
				
				$delimiter = stripos ( $for_match, '-' ) !== FALSE ? '-' : ',';
				
				$bounds = explode ( $delimiter, $for_match );
				
				if ($delimiter == '-') {
					for($i = ( int ) $bounds [0]; $i <= ( int ) $bounds [1]; $i ++) {
						$new_settings [] = str_replace ( $search, $i, $setting );
					}
				} else {
					foreach ( $bounds as $replace ) {
						$new_settings [] = str_replace ( $search, $replace, $setting );
					}
				}
				
				$new_settings = $this->_parse_config_for ( $new_settings );
			} else {
				$new_settings [] = $setting;
			}
		}
		
		return $new_settings;
	}
	private function _get_exe($name, $os_suffix) {
		return dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . 'Executables' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $os_suffix;
	}
	private function _load_executable($name, $os, $config) {
		if (empty ( $config [$os] ))
			return;
		
		$exe = $this->_get_exe ( $name, $config [$os] );
		
		if (! is_executable ( $exe )) {
			$this->_chmod ( $exe, 0555 );
			
			if (! is_executable ( $exe )) {
				throw new Exception ( "File is not executable: " . $exe );
			}
		}
		
		foreach ( $this->_parse_config_for ( $config ['settings'] ) as $setting ) {
			$this->_test_config ( $exe, $config ['extension'], $setting );
		}
		
		$this->_executable_is_loaded ( $name, true );
	}
	private function _load_contents($file) {
		$key = 'nitrosmush.' . md5 ( $file );
		
		if (! isset ( $GLOBALS [$key] )) {
			if ($this->_is_readable ( $file )) {
				$GLOBALS [$key] = file_get_contents ( $file );
			}
		}
		
		return $GLOBALS [$key];
	}
	private function _executable_is_loaded($name, $load_write = false) {
		$file_loaded = $this->_temp_path ( $this->_file_loaded, true );
		
		$contents = $this->_load_contents ( $file_loaded );
		
		$loaded = stripos ( $contents, $name ) !== FALSE;
		
		if (! $loaded && $load_write) {
			if ($this->_is_writable ( $file_loaded )) {
				file_put_contents ( $file_loaded, $name . PHP_EOL, FILE_APPEND );
				
				$key = 'nitrosmush.' . md5 ( $file_loaded );
				unset ( $GLOBALS [$key] );
				
				return true;
			}
		}
		
		return $loaded;
	}
	private function _get_os_suffix() {
		$uname = $this->_os ();
		$arch = $this->_arch ();
		
		if ($this->_substr_caps ( $uname, 3 ) == 'WIN') {
			$os_suffix = 'win' . $arch;
		} else if ($this->_substr_caps ( $uname, 5 ) == 'LINUX') {
			$os_suffix = 'linux' . $arch;
		} else if ($this->_substr_caps ( $uname, 6 ) == 'DARWIN') {
			$os_suffix = 'mac' . $arch;
		} else {
			throw new Exception ( "php_uname('s') returned a non-supported OS: " . $uname . "! Valid values are: WIN, LINUX, DARWIN" );
		}
		
		return $os_suffix;
	}
	private function _load_executables() {
		$folder = dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . 'Executables' . DIRECTORY_SEPARATOR;
		
		$load_errors = array ();
		
		$this->_set_error_handler ();
		
		try {
			$this->_check_compatibility ();
			
			$os_suffix = $this->_get_os_suffix ();
			
			$file_loaded = $this->_temp_path ( $this->_file_loaded, true );
			if ($this->_is_writable ( $file_loaded )) {
				file_put_contents ( $file_loaded, '' );
			}
			
			foreach ( $this->_config as $filetype_regex => $filetype_config ) {
				foreach ( $filetype_config as $tool_name => $tool_config ) {
					try {
						$this->_load_executable ( $tool_name, $os_suffix, $tool_config );
					} catch ( Exception $e ) {
						$load_errors [] = $e->getMessage ();
					}
				}
			}
			
			$this->_clean_temp ();
		} catch ( Exception $e ) {
			$load_errors [] = $e->getMessage ();
		}
		
		$this->_restore_error_handler ();
		
		return $load_errors;
	}
}

// $smusher = new NitroSmush();
// $smusher->loadExecutables();
// $result = $smusher->smush('./Images/source.jpg', '../nitrosmush.result.jpg');
