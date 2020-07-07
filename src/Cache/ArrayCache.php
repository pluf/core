<?php
namespace Pluf\Cache;

use Pluf\Options;

/**
 * A basic cache to put objects into the memory
 *
 *
 * @author maso
 *        
 */
class ArrayCache extends \Pluf\Cache

{

    private $cache = [];
    
    public function __construct(?Options $options = null)
    {
        $this->setDefaults($options);
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Cache::set()
     */
    public function set($key, $value, $timeout = null)
    {
        $this->cache[$key] = $value;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Cache::get()
     */
    public function get($key, $default = null)
    {
        if (! array_key_exists($key, $this->cache)) {
            return $default;
        }
        return $this->cache[$key];
    }
}

