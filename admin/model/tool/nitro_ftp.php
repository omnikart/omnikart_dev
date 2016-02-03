<?php

/*
 *
 * 1) First collect files with cdn_prepare_files(). cdn 1 = amazon, cdn 2 = rackspace, cdn 3 = ftp
 * 2) Map $response to $this->request->post['last']
 * 3) If it is true that (!empty($files) && $response['step'] == 'upload'), then upload the files one by one. Otherwise the necessary data will be saved to $this->request->post['last']
 *
 */
class ModelToolNitroFtp extends ModelToolNitro {
	public function upload() {
		// $last = !empty($this->request->post['last']) ? $this->request->post['last'] : array(
		// 'step' => 'list',
		// 'continue_from' => ''
		// );
		$response = &$this->request->post ['last'];
		
		$valid_extensions = $this->get_valid_extensions ( array (
				'css' => getNitroPersistence ( 'CDNStandardFTP.SyncCSS' ),
				'js' => getNitroPersistence ( 'CDNStandardFTP.SyncJavaScript' ),
				'images' => getNitroPersistence ( 'CDNStandardFTP.SyncImages' ) 
		) );
		
		if (empty ( $valid_extensions )) {
			throw new Exception ( "No file types selected for upload." );
		}
		
		$files = $this->cdn_prepare_files ( array (
				'valid_extensions' => $valid_extensions,
				'cdn' => '3' 
		) );
		
		if (! empty ( $files ) && $response ['step'] == 'upload') {
			$ftp = &$this->get_ftp_connection ();
			
			$data = array (
					'ftp' => $ftp 
			);
			
			np ( $files, 0, FILE_APPEND );
			
			foreach ( $files as $file ) {
				$data ['file'] = $file;
				$this->ftp_upload_file ( $data );
				$this->cdn_after_upload ( $file );
			}
		} else if (empty ( $files ) && $response ['step'] == 'upload') {
			$persistence = getNitroPersistence ();
			$persistence ['Nitro'] ['CDNStandardFTP'] ['LastUpload'] = date ( $this->language->get ( 'date_format_long' ) . ' ' . $this->language->get ( 'time_format' ) );
			setNitroPersistence ( $persistence );
		}
	}
	private function &get_ftp_connection() {
		$protocol = getNitroPersistence ( 'CDNStandardFTP.Protocol' );
		
		if ($protocol == 'ftps' && ! function_exists ( 'ftp_ssl_connect' )) {
			throw new Exception ( 'Your server does not support FTPS.' );
		}
		
		if ($protocol == 'ftp' && ! function_exists ( 'ftp_connect' )) {
			throw new Exception ( 'Your server does not support FTP.' );
		}
		
		$port = getNitroPersistence ( 'CDNStandardFTP.Port' ) ? ( int ) getNitroPersistence ( 'CDNStandardFTP.Port' ) : 21;
		
		$server_url = parse_url ( getNitroPersistence ( 'CDNStandardFTP.Host' ) );
		$server = empty ( $server_url ['scheme'] ) ? $server_url ['path'] : $server_url ['host'];
		
		if (empty ( $server ))
			throw new Exception ( 'Invalid server name.' );
		
		if ($protocol == 'ftps') {
			$connection = ftp_ssl_connect ( $server, $port );
		} else {
			$connection = ftp_connect ( $server, $port );
		}
		
		if ($connection === FALSE) {
			throw new Exception ( 'Could not connect to the specified server and port.' );
		}
		
		try {
			$login = ftp_login ( $connection, getNitroPersistence ( 'CDNStandardFTP.Username' ), getNitroPersistence ( 'CDNStandardFTP.Password' ) );
		} catch ( Exception $e ) {
		}
		
		if (empty ( $login )) {
			throw new Exception ( 'Invalid Username/Password.' );
		}
		
		$root = $this->get_clean_root ();
		
		try {
			$chdir = ftp_chdir ( $connection, $root );
		} catch ( Exception $e ) {
		}
		
		if (empty ( $chdir )) {
			throw new Exception ( 'Unable to access the specified Root directory. Please make sure that it already exists and has read/write permissions.' );
		}
		
		return $connection;
	}
	private function ftp_upload_file($data) {
		$response = &$this->request->post ['last'];
		$ftp = &$data ['ftp'];
		
		$source = $data ['file'] ['realpath'];
		$destination = $data ['file'] ['file'];
		
		$destination_folders = explodeTrim ( '/', dirname ( $destination ) );
		$destination_file = basename ( $destination );
		$destination_folder = $this->get_clean_root ();
		
		foreach ( $destination_folders as $folder ) {
			$listing = ftp_rawlist ( $ftp, $destination_folder );
			
			$has_destination = false;
			
			foreach ( $listing as $element ) {
				if (preg_match ( '~^d(.*?) \d{1,2} \d{1,2}:\d\d ' . $folder . '$~', $element, $matches ) !== FALSE && ! empty ( $matches )) {
					$has_destination = true;
					break;
				}
			}
			
			$destination_folder .= $folder . '/';
			
			if (! $has_destination) {
				ftp_mkdir ( $ftp, $destination_folder );
			}
			
			ftp_chdir ( $ftp, $destination_folder );
		}
		
		if (! ftp_put ( $ftp, $destination_file, $source, FTP_BINARY )) {
			throw new Exception ( 'Could not upload ' . $destination_folder . $destination_file );
		}
	}
	private function get_clean_root() {
		$root = implode ( '/', (explodeTrim ( '/', getNitroPersistence ( 'CDNStandardFTP.Root' ) )) );
		return ! empty ( $root ) ? '/' . $root . '/' : '/';
	}
}