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
namespace Pluf\Cache;

use Pluf\Options;

/**
 * Cache in memory
 *
 * A special 'cache_memcached_keyprefix' can be set to use one
 * memcached for different applications and avoid conflict.
 *
 * Example of configuration:
 *
 * <pre>
 * $cfg['cache_engine'] = 'memcached';
 *
 * $cfg['cache_memcached_timeout'] = 300;
 * $cfg['cache_memcached_keyprefix'] = 'uniqueforapp';
 * $cfg['cache_memcached_server'] = 'localhost';
 * $cfg['cache_memcached_port'] = 11211;
 * $cfg['cache_memcached_compress'] = 0; (or MEMCACHE_COMPRESSED)
 * </pre>
 */
class Memcached extends \Pluf\Cache
{
    use \Pluf\DiContainerTrait;

    private $memcache = null;

    private $keyprefix = '';

    private $server = 'localhost';

    private $port = 11211;

    private $timeout = null;

    private $compress = 0;

    /**
     * Creates new instance of the cache
     */
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
        if ($this->memcache) {
            if ($timeout == null) {
                $timeout = $this->timeout;
            }
            $this->memcache->set($this->keyprefix . $key, serialize($value), $this->compress, $timeout);
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Cache::get()
     */
    public function get($key, $default = null)
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
