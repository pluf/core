<?php
/**
 * File based cache.
 *
 * Info are stored in the 'cache_file_folder' folder. In the folder, 2
 * subdirectories are created based on the md5 of the key.
 */
class Pluf_Cache_File extends Pluf_Cache {
	/**
	 * Is debug mode?
	 *
	 * @var boolean
	 */
	private $_debug;
	
	/**
	 * Mapping key => md5.
	 *
	 * @var array
	 */
	private $_keymap = array ();
	public function __construct() {
		if (! Pluf::f ( 'cache_file_folder', false )) {
			throw new Pluf_Exception_SettingError ( '"cache_file_folder" setting not defined.' );
		}
		
		$this->_debug = Pluf::f ( 'debug', false );
	}
	
	/**
	 * Set a value in the cache.
	 *
	 * @param
	 *        	string Key to store the information
	 * @param
	 *        	mixed Value to store
	 * @param
	 *        	int Timeout in seconds (null)
	 * @return bool Success
	 */
	public function set($key, $value, $timeout = null) {
		$fname = $this->_keyToFile ( $key );
		$dir = dirname ( $fname );
		if (null === $timeout) {
			$timeout = Pluf::f ( 'cache_timeout', 300 );
		}
		if (! file_exists ( $dir )) {
			mkdir ( $dir, 0777, true );
		}
		$expire = $_SERVER ['REQUEST_TIME'] + $timeout;
		$success = file_put_contents ( $fname, $expire . "\n" . serialize ( $value ), LOCK_EX );
		chmod ( $fname, 0777 );
		
		return (false === $success) ? false : true;
	}
	
	/**
	 * Get value from the cache.
	 *
	 * @param
	 *        	string Key to get the information
	 * @param
	 *        	mixed Default value to return if cache miss (null)
	 * @param
	 *        	mixed Stored value or default
	 */
	public function get($key, $default = null) {
		$fname = $this->_keyToFile ( $key );
		if (! file_exists ( $fname )) {
			return $default;
		}
		
		if ($this->_debug) {
			ob_start ();
			include $fname;
			$data = ob_get_contents ();
			ob_end_clean ();
		} else {
			$data = file_get_contents ( $fname );
		}
		list ( $timeout, $content ) = explode ( "\n", $data, 2 );
		
		if ($timeout < $_SERVER ['REQUEST_TIME']) {
			@unlink ( $fname );
			return $default;
		}
		
		return unserialize ( $content );
	}
	
	/**
	 * Convert a key into a path to a file.
	 *
	 * @param
	 *        	string Key
	 * @return string Path to file
	 */
	public function _keyToFile($key) {
		if (isset ( $this->_keymap [$key] )) {
			$md5 = $this->_keymap [$key];
		} else {
			$md5 = md5 ( $key );
			$this->_keymap [$key] = $md5;
		}
		
		return Pluf::f ( 'cache_file_folder' ) . '/' . substr ( $md5, 0, 2 ) . '/' . substr ( $md5, 2, 2 ) . '/' . substr ( $md5, 4 );
	}
}
