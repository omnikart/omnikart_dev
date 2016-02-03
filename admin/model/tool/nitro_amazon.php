<?php

/*
 *
 * 1) First collect files with cdn_prepare_files(). cdn 1 = amazon, cdn 2 = rackspace, cdn 3 = ftp
 * 2) Map $response to $this->request->post['last']
 * 3) If it is true that (!empty($files) && $response['step'] == 'upload'), then upload the files one by one. Otherwise the necessary data will be saved to $this->request->post['last']
 *
 */
class ModelToolNitroAmazon extends ModelToolNitro {
	public function upload() {
		// $last = !empty($this->request->post['last']) ? $this->request->post['last'] : array(
		// 'step' => 'list',
		// 'continue_from' => ''
		// );
		$response = &$this->request->post ['last'];
		
		$valid_extensions = $this->get_valid_extensions ( array (
				'css' => getNitroPersistence ( 'CDNAmazon.SyncCSS' ),
				'js' => getNitroPersistence ( 'CDNAmazon.SyncJavaScript' ),
				'images' => getNitroPersistence ( 'CDNAmazon.SyncImages' ) 
		) );
		
		if (empty ( $valid_extensions )) {
			throw new Exception ( "No file types selected for upload." );
		}
		
		$files = $this->cdn_prepare_files ( array (
				'valid_extensions' => $valid_extensions,
				'cdn' => '1' 
		) );
		
		if (! empty ( $files ) && $response ['step'] == 'upload') {
			if (! class_exists ( 'S3' ))
				require_once (NITRO_LIB_FOLDER . 'S3.php');
			
			$s3 = new S3 ( getNitroPersistence ( 'CDNAmazon.AccessKeyID' ), getNitroPersistence ( 'CDNAmazon.SecretAccessKey' ) );
			
			$data = array (
					's3' => &$s3 
			);
			
			foreach ( $files as $file ) {
				$data ['file'] = $file;
				$this->amazon_upload_file ( $data );
				$this->cdn_after_upload ( $file );
			}
		} else if (empty ( $files ) && $response ['step'] == 'upload') {
			$persistence = getNitroPersistence ();
			$persistence ['Nitro'] ['CDNAmazon'] ['LastUpload'] = date ( $this->language->get ( 'date_format_long' ) . ' ' . $this->language->get ( 'time_format' ) );
			setNitroPersistence ( $persistence );
		}
	}
	private function amazon_upload_file($data) {
		$response = &$this->request->post ['last'];
		$s3 = &$data ['s3'];
		
		$buckets = $s3->listBuckets ();
		
		$cssjs_browser_cache = 0;
		$images_browser_cache = 0;
		
		if (getNitroPersistence ( 'BrowserCache.Enabled' ) && getNitroPersistence ( 'BrowserCache.Headers.Pages.Expires' )) {
			if (getNitroPersistence ( 'BrowserCache.CSSJS.Period' )) {
				switch (getNitroPersistence ( 'BrowserCache.CSSJS.Period' )) {
					case '1 week' :
						{
							$cssjs_browser_cache = 7 * 24 * 3600;
						}
						break;
					case '1 month' :
						{
							$cssjs_browser_cache = 30 * 24 * 3600;
						}
						break;
					case '6 months' :
						{
							$cssjs_browser_cache = 6 * 30 * 24 * 3600;
						}
						break;
					case '1 year' :
						{
							$cssjs_browser_cache = 365 * 24 * 3600;
						}
						break;
					default :
						{
							$cssjs_browser_cache = 0;
						}
				}
			}
			
			if (getNitroPersistence ( 'BrowserCache.Images.Period' )) {
				switch (getNitroPersistence ( 'BrowserCache.Images.Period' )) {
					case '1 week' :
						{
							$images_browser_cache = 7 * 24 * 3600;
						}
						break;
					case '1 month' :
						{
							$images_browser_cache = 30 * 24 * 3600;
						}
						break;
					case '6 months' :
						{
							$images_browser_cache = 6 * 30 * 24 * 3600;
						}
						break;
					case '1 year' :
						{
							$images_browser_cache = 365 * 24 * 3600;
						}
						break;
					default :
						{
							$images_browser_cache = 0;
						}
				}
			}
		}
		
		$source = $data ['file'] ['realpath'];
		$destination = $data ['file'] ['file'];
		
		$ext = strtolower ( pathinfo ( $source, PATHINFO_EXTENSION ) );
		
		if (getNitroPersistence ( 'CDNAmazon.SyncCSS' ) && in_array ( $ext, unserialize ( NITRO_EXTENSIONS_CSS ) )) {
			$bucket = getNitroPersistence ( 'CDNAmazon.CSSBucket' );
			if (is_array ( $buckets ) && in_array ( $bucket, $buckets )) {
				$compress = getNitroPersistence ( 'Compress.CSS' ) && getNitroPersistence ( 'Compress.CSSLevel' ) ? ( int ) getNitroPersistence ( 'Compress.CSSLevel' ) : false;
				$cache_time = $cssjs_browser_cache;
			} else
				throw new Exception ( 'The CSS bucket does not exist. Please create it.' );
		}
		
		if (getNitroPersistence ( 'CDNAmazon.SyncJavaScript' ) && in_array ( $ext, unserialize ( NITRO_EXTENSIONS_JS ) )) {
			$bucket = getNitroPersistence ( 'CDNAmazon.JavaScriptBucket' );
			if (is_array ( $buckets ) && in_array ( $bucket, $buckets )) {
				$compress = getNitroPersistence ( 'Compress.JS' ) && getNitroPersistence ( 'Compress.JSLevel' ) ? ( int ) getNitroPersistence ( 'Compress.JSLevel' ) : false;
				$cache_time = $cssjs_browser_cache;
			} else
				throw new Exception ( 'The JS bucket does not exist. Please create it.' );
		}
		
		if (getNitroPersistence ( 'CDNAmazon.SyncImages' ) && in_array ( $ext, unserialize ( NITRO_EXTENSIONS_IMG ) )) {
			$bucket = getNitroPersistence ( 'CDNAmazon.ImageBucket' );
			if (is_array ( $buckets ) && in_array ( $bucket, $buckets )) {
				$compress = false;
				$cache_time = $images_browser_cache;
			} else
				throw new Exception ( 'The images bucket does not exist. Please create it.' );
		}
		
		if (empty ( $bucket ))
			return;
		
		$req = new S3Request ( 'HEAD', $bucket, $destination );
		$res = $req->getResponse ();
		
		$to_upload = $res->code != 200 || // Either the file does not exist
($res->code == 200 && // Or the file exists and meets one of the following criteria:
(( // Option A) The compression settings differ between NitroPack and the file
! empty ( $compress ) xor ! empty ( $res->headers ['x-amz-meta-compressed'] )) || ( // Option B) The expires header settings differ between NitroPack and the file
! empty ( $cache_time ) xor ! empty ( $res->headers ['x-amz-meta-expires-time'] )) || ( // Option C) The expires header is set, but the expiration values are different
! empty ( $cache_time ) && ! empty ( $res->headers ['x-amz-meta-expires-time'] ) && ( int ) $cache_time != $res->headers ['x-amz-meta-expires-time'])));
		
		if ($to_upload) {
			$headers = array ();
			$meta_headers = array ();
			
			if (! empty ( $cache_time )) {
				$headers ['Expires'] = gmdate ( 'D, d M Y H:i:s \G\M\T', time () + $cache_time );
				$meta_headers ['expires-time'] = $cache_time;
			}
			
			switch ($ext) {
				case 'css' :
					{
						$headers ['Content-Type'] = 'text/css';
					}
					break;
				case 'js' :
					{
						$headers ['Content-Type'] = 'text/javascript';
					}
					break;
			}
			
			if (! empty ( $compress ) && is_readable ( $source ) && ! empty ( $headers ['Content-Type'] )) {
				$headers ['Content-Encoding'] = 'gzip';
				$meta_headers ['compressed'] = '1';
				
				$contents = gzencode ( file_get_contents ( $source ), $compress );
				
				$temp_file = tempnam ( sys_get_temp_dir (), 'Nitro' );
				$temp_handle = fopen ( $temp_file, "w" );
				
				if (empty ( $temp_handle ) || ! fwrite ( $temp_handle, $contents ))
					throw new Exception ( 'There was a problem writing to ' . $temp_file );
				
				fclose ( $temp_handle );
				
				$source = $temp_file;
			}
			
			if ($s3->putObject ( $s3->inputFile ( $source ), $bucket, $destination, S3::ACL_PUBLIC_READ, $meta_headers, $headers )) {
				if (! empty ( $temp_file )) {
					unlink ( $temp_file );
					unset ( $temp_file );
				}
			} else {
				throw new Exception ( 'Could not upload ' . $destination );
			}
		}
	}
}