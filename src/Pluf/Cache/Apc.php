<?php

/**
 * APC based cache.
 *
 * You need APC installed on your server for this cache system to
 * work. You can install APC with <code>$ sudo pecl install apc</code>
 * on most systems.
 *
 * A special 'cache_apc_keyprefix' can be set to use APC for different
 * applications and avoid conflict. Compression is performed at the
 * PHP level using the gz(in|de)flate functions.
 *
 * Example of configuration:
 *
 * <pre>
 * $cfg['cache_engine'] = 'Pluf_Cache_Apc';
 * $cfg['cache_timeout'] = 300;
 * $cfg['cache_apc_keyprefix'] = 'uniqueforapp';
 * $cfg['cache_apc_compress'] = true;
 * </pre>
 *
 * @see Pluf_Cache
 * @see http://www.php.net/gzdeflate
 * @see http://www.php.net/gzinflate
 */
class Pluf_Cache_Apc extends Pluf_Cache {
	/**
	 * Prefix added to all the keys.
	 */
	private $keyprefix = '';
	
	/**
	 * Auto compress the data to save memory against a small
	 * performance loss.
	 */
	private $compress = false;
	
	/**
	 * Create the cache object and initialize it from the
	 * configuration.
	 */
	public function __construct() {
		$this->keyprefix = Pluf::f ( 'cache_apc_keyprefix', '' );
		$this->compress = Pluf::f ( 'cache_apc_compress', false );
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
		if ($timeout == null)
			$timeout = Pluf::f ( 'cache_timeout', 300 );
		$value = serialize ( $value );
		if ($this->compress)
			$value = gzdeflate ( $value, 9 );
		return apc_store ( $this->keyprefix . $key, $value, $timeout );
	}
	
	/**
	 * Get value from the cache.
	 *
	 * @param
	 *        	string Key to get the information
	 * @param
	 *        	mixed Default value to return if cache miss (null)
	 * @return mixed Stored value or default
	 */
	public function get($key, $default = null) {
		$success = false;
        $value = apc_fetch($this->keyprefix.$key, &$success);
		if (! $success)
			return $default;
		if ($this->compress)
			$value = gzinflate ( $value );
		return unserialize ( $value );
	}
}
