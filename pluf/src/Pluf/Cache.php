<?php

/**
 * Cache class.
 *
 * You should not use this class directly, but one of the subclasses
 * implementing a given engine. This is done automatically when using
 * the factory. It will use the engine defined by the 'cache_engine'
 * configuration variable.
 *
 * Default timeout in seconds is defined by the 'cache_timeout'
 * configuration variable.
 *
 * <pre>
 * $cache = new Pluf_Cache::factory();
 * if (null === ($foo=$cache->get('my-key'))) {
 *     $foo = run_complex_operation();
 *     $cache->set('my-key', $foo);
 * }
 * return $foo;
 * </pre>
 *
 * The value to be stored in the cache must be serializable.
 *
 * @see http://www.php.net/serialize
 */
class Pluf_Cache
{
    /**
     * Factory.
     *
     * @return Pluf_Cache_* Cache object
     */
    public static function factory()
    {
        if (false === ($engine=Pluf::f('cache_engine', false))) {
            throw new Pluf_Exception_SettingError('"cache_engine" setting not defined.');
        }
        if (!isset($GLOBALS['_PX_Pluf_Cache-'.$engine])) {
            $GLOBALS['_PX_Pluf_Cache-'.$engine] = new $engine();
        } 
        return $GLOBALS['_PX_Pluf_Cache-'.$engine];
    }

    /**
     * Set a value in the cache.
     *
     * @param string Key to store the information
     * @param mixed Value to store
     * @param int Timeout in seconds (null)
     * @return bool Success
     */
    public function set($key, $value, $timeout=null)
    {
        throw new Pluf_Exception_NotImplemented();
    }

    /**
     * Get value from the cache.
     *
     * @param string Key to get the information
     * @param mixed Default value to return if cache miss (null)
     * @param mixed Stored value or default
     */
    public function get($key, $default=null)
    {
        throw new Pluf_Exception_NotImplemented();
    }
}
