<?php
class NitroFiles {
	private $root;
	private $start;
	private $batch;
	private $ext;
	private $result;
	private $total_size;
	private $rules;
	private $_now;
	private $continue_from;
	private $current_dir;
	private $current_dir_name;
	private $debug;
	
	/*
	 * Init and clean variables
	 *
	 * $config = array(
	 * 'root' => './', // (Optional) Some directory - regarded as root of the search. Resolved to the real directory on the server. If omitted the directory of the executing file __FILE__ will be used.
	 * 'start' => 'dir_1/dir_2/file_1.txt', // (Optional) Some file or directory which serves to calculate the start point of the iteration. Note that results will be all files AFTER this file. The path of this start file is relative to the root dir. If omitted or not existing or is a dir, the returning of the files will begin from the root dir.
	 * 'batch' => 2000, // (Optional) How many files should we return. Default value is 0, which means "all files"
	 * 'ext' => array('jpg', 'jpeg', 'css'), // (Optional) Extensions to filter files
	 * );
	 */
	public function __construct($config = array()) {
		// Init root
		$this->root = ! empty ( $config ['root'] ) ? realpath ( $config ['root'] ) : dirname ( __FILE__ );
		$this->root .= DIRECTORY_SEPARATOR;
		
		// Init ext
		$this->ext = ! empty ( $config ['ext'] ) && is_array ( $config ['ext'] ) ? $config ['ext'] : array ();
		
		// Init start
		$start_rel_path = ! empty ( $config ['start'] ) ? ltrim ( $config ['start'], DIRECTORY_SEPARATOR ) : '';
		$start_path = realpath ( $this->root . $start_rel_path );
		$this->start = ! is_file ( $start_path ) ? $this->root : $start_path;
		
		// Init batch
		$this->batch = (isset ( $config ['batch'] ) && is_numeric ( $config ['batch'] )) ? ( int ) $config ['batch'] : 0;
		
		// Init result
		$this->result = array ();
		
		// Init total_size
		$this->total_size = 0;
		
		// Init continue_from
		$this->continue_from = ! empty ( $config ['continue_from'] ) ? $config ['continue_from'] : '';
		
		$this->current_dir = null;
		$this->current_dir_name = '';
		
		// Init rules
		$this->rules = ! empty ( $config ['rules'] ) && is_array ( $config ['rules'] ) ? $config ['rules'] : array ();
		
		$this->_now = time ();
		
		$this->debug = ! empty ( $config ['debug'] );
		
		$this->prepareFolders ();
	}
	public function __destruct() {
		$this->closeCurrentDir ();
	}
	public function closeCurrentDir() {
		if ($this->current_dir) {
			closedir ( $this->current_dir );
			$this->current_dir = false;
		}
	}
	public function getTempFileName() {
		return sys_get_temp_dir () . DIRECTORY_SEPARATOR . 'isense_nitropack';
	}
	public function prepareFolders() {
		$file = $this->getTempFileName ();
		
		if (isExecEnabled ( $file )) {
			exec ( 'find "' . rtrim ( $this->root, DIRECTORY_SEPARATOR ) . '" -type d -exec echo {}/ \\; > ' . $file );
		} else {
			file_put_contents ( $file, '' );
			$this->readDirRecursive ( $this->root );
		}
	}
	public function readDirRecursive($dir) {
		file_put_contents ( $this->getTempFileName (), $dir . PHP_EOL, FILE_APPEND );
		
		$dh = opendir ( $dir );
		
		if (! $dh)
			return;
		
		while ( ($file = readdir ( $dh )) !== false ) {
			if (in_array ( $file, array (
					'.',
					'..' 
			) ))
				continue;
			
			if (is_dir ( $dir . DIRECTORY_SEPARATOR . $file )) {
				$this->readDirRecursive ( $dir . DIRECTORY_SEPARATOR . $file . DIRECTORY_SEPARATOR );
			}
		}
		
		closedir ( $dh );
	}
	public function clearStatCache() {
		clearstatcache ();
	}
	public function isEmpty() {
		$this->clearResult ();
		
		$result = $this->handleProgressive ( false, true );
		
		$this->closeCurrentDir ();
		
		return $result;
	}
	public function delete() {
		$this->clearResult ();
		
		$this->handleProgressive ( true );
		
		$this->closeCurrentDir ();
	}
	public function find() {
		$this->clearResult ();
		
		$this->handleProgressive ();
		
		$this->closeCurrentDir ();
		
		return $this->getResult ();
	}
	public function findDir() {
		if ($this->getContinueFrom () == '') {
			$this->setContinueFrom ( $this->root );
		} else {
			$fh = fopen ( $this->getTempFileName (), 'r' );
			
			$located = false;
			$item = false;
			
			while ( $item = trim ( fgets ( $fh ) ) ) {
				if ($located) {
					$this->setContinueFrom ( $item );
					break;
				}
				
				if ($item == $this->getContinueFrom () && is_readable ( $item )) {
					$located = true;
				}
			}
			
			fclose ( $fh );
			
			if ($item == FALSE) {
				$this->setContinueFrom ( false );
			}
		}
		
		$next_dir = $this->getContinueFrom ();
		
		$this->closeCurrentDir ();
		
		if ($next_dir) {
			$this->current_dir_name = $next_dir;
			$this->current_dir = opendir ( $next_dir );
		}
	}
	public function nextFileInDir() {
		if (! $this->current_dir) {
			$this->findDir ();
		}
		
		if ($this->current_dir) {
			do {
				$valid = true;
				$item_path = false;
				
				while ( false !== $item = readdir ( $this->current_dir ) ) {
					if (! $this->filterDots ( $item ))
						continue;
					
					$item_path = $this->current_dir_name . $item;
					
					if (! is_file ( $item_path ))
						continue;
					
					break;
				}
				
				if ($item_path === false)
					break;
				
				$valid = $this->isValidFile ( $item_path );
				
				if ($valid) {
					return $item_path;
				}
			} while ( ! $valid );
		}
		
		return false;
	}
	public function handleProgressive($delete = false, $locate = false) {
		$count = 0;
		$dirs_iterated = false;
		
		do {
			$this->findDir ();
			
			if (! $this->current_dir) {
				break;
			}
			
			while ( $item = $this->nextFileInDir () ) {
				if ($locate) {
					return false;
				}
				
				if ($delete) {
					unlink ( $item );
				} else {
					$this->addFileToResult ( $item );
				}
				
				$count ++;
			}
			
			$count_in_bounds = true;
			
			if (! empty ( $this->batch ) && ! $delete) {
				$count_in_bounds = $count < $this->batch;
			}
		} while ( ! $dirs_iterated && $count_in_bounds );
		
		if ($locate) {
			return true;
		}
	}
	public function clearResult() {
		$this->clearStatCache ();
		$this->result = array ();
		$this->total_size = 0;
	}
	public function getResult() {
		return $this->result;
	}
	public function setContinueFrom($item) {
		$this->continue_from = $item;
	}
	public function getContinueFrom() {
		return $this->continue_from;
	}
	private function addFileToResult($item) {
		$this->result [] = array (
				'full_path' => $item,
				'rel_path' => implode ( DIRECTORY_SEPARATOR, $this->getScope ( $this->root, $item ) ),
				'size' => filesize ( $item ) 
		);
	}
	private function isValidFile($item) {
		if (! is_file ( $item )) {
			return false;
		}
		
		$extension = strtolower ( pathinfo ( $item, PATHINFO_EXTENSION ) );
		$rel_item = implode ( DIRECTORY_SEPARATOR, $this->getScope ( $this->root, $item ) );
		
		if (empty ( $extension )) {
			return false;
		} else {
			$validates_rules = true;
			
			if (! empty ( $this->rules )) {
				foreach ( $this->rules as $rule ) {
					if ((! empty ( $rule ['ext'] ) && is_array ( $rule ['ext'] ) && in_array ( $extension, $rule ['ext'] )) || empty ( $rule ['ext'] )) {
						if (isset ( $rule ['match'] ) && $rule ['match'] == false) {
							$validates_rule = ! preg_match ( $rule ['rule'], $rel_item );
						} elseif (isset ( $rule ['match'] )) {
							$validates_rule = preg_match ( $rule ['rule'], $rel_item );
						} else {
							$validates_rule = true;
						}
						
						$validates_rules = $validates_rules && $validates_rule;
						
						if (isset ( $rule ['delete_time'] )) {
							$validates_rule = $this->_now - filemtime ( $item ) > $rule ['delete_time'];
						}
						
						$validates_rules = $validates_rules && $validates_rule;
					}
				}
			}
			
			return $validates_rules && (! empty ( $this->ext ) ? in_array ( $extension, $this->ext ) : true);
		}
	}
	private function getScope($var_root, $var_start) {
		$start = array_filter ( explode ( DIRECTORY_SEPARATOR, $var_start ) );
		$root = array_filter ( explode ( DIRECTORY_SEPARATOR, $var_root ) );
		
		return array_slice ( $start, count ( $root ) );
	}
	private function items($path, $offset = '') {
		$values = array ();
		
		$path = realpath ( $path ) . DIRECTORY_SEPARATOR;
		
		if (! is_readable ( $path ))
			return $values;
		
		$handle = opendir ( $path );
		
		while ( false !== ($entry = readdir ( $handle )) ) {
			if (! $this->filterDots ( $entry ) || $offset >= $entry)
				continue;
			
			$this->applyPath ( $entry, 0, $path );
			
			array_push ( $values, $entry );
			
			sort ( $values, SORT_STRING );
			
			if ($this->batch > 0 && count ( $values ) > $this->batch) {
				$values = array_slice ( $values, 0, $this->batch );
			}
		}
		
		closedir ( $handle );
		
		return $values;
	}
	private function filterDots($var) {
		return ! in_array ( $var, array (
				'.',
				'..' 
		) );
	}
	private function applyPath(&$var, $index, $path) {
		$var = $path . $var;
	}
}