<?php

/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. http://dpq.co.ir
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * کش مبتنی بر حافظه را ایجاد می‌کند
 *
 * A special 'cache_memcached_keyprefix' can be set to use one
 * memcached for different applications and avoid conflict.
 *
 * Example of configuration:
 *
 * <pre>
 * $cfg['cache_engine'] = 'Pluf_Cache_Memcached';
 * $cfg['cache_timeout'] = 300;
 * $cfg['cache_memcached_keyprefix'] = 'uniqueforapp';
 * $cfg['cache_memcached_server'] = 'localhost';
 * $cfg['cache_memcached_port'] = 11211;
 * $cfg['cache_memcached_compress'] = 0; (or MEMCACHE_COMPRESSED)
 * </pre>
 */
class Pluf_Cache_Memcached extends Pluf_Cache
{

    private $memcache = null;

    private $keyprefix = '';

    public function __construct ()
    {
        $this->memcache = memcache_connect(
                Pluf::f('cache_memcached_server', 'localhost'), 
                Pluf::f('cache_memcached_port', 11211));
        if (false === $this->memcache) {
            $this->memcache = null;
        }
        $this->keyprefix = Pluf::f('cache_memcached_keyprefix', '');
    }

    /**
     * Set a value in the cache.
     *
     * @param
     *            string Key to store the information
     * @param
     *            mixed Value to store
     * @param
     *            int Timeout in seconds (null)
     * @return bool Success
     */
    public function set ($key, $value, $timeout = null)
    {
        if ($this->memcache) {
            if ($timeout == null)
                $timeout = Pluf::f('cache_timeout', 300);
            $this->memcache->set($this->keyprefix . $key, serialize($value), 
                    Pluf::f('cache_memcached_compress', 0), $timeout);
        }
    }

    /**
     * Get value from the cache.
     *
     * @param
     *            string Key to get the information
     * @param
     *            mixed Default value to return if cache miss (null)
     * @param
     *            mixed Stored value or default
     */
    public function get ($key, $default = null)
    {
        if ($this->memcache) {
            $val = $this->memcache->get($this->keyprefix . $key);
            if (false === $val) {
                return $default;
            } else {
                return unserialize($val);
            }
        } else {
            return $default;
        }
    }
}
