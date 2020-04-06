<?php

/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
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
namespace Pluf;

use Pluf;

/**
 * Pluf Cach
 *
 * مهم‌ترین نیاز در سیستم‌ها کش کردن داده‌هایی است که با استفاده از پردازش
 * در سیستم ایجاد می‌شوند. این کار باعث بهبود کارایی سیستم خواهد شد. این
 * کلاس ساختار کلی کش را در سیستم تعیین می‌کند.
 *
 * نکته: شما نباید به صورت مستقیم از این کلاس نمونه ایجاد کنید اما نمونه‌های
 * متفاوتی از این کلاس وجود دارد که با روش‌های متفاوتی عمل کش کردن در سیستم
 * را پیاده سازی کرده اند.
 *
 * تعیین مولد مدیریت کش به صورت زیر انجام می‌شود:
 *
 * <pre><code>
 * cfg['cache_engine'] = 'Apc';
 * </code></pre>
 *
 * There are many types of cache engine implemented by Pluf Cache. Here is
 * list of them:
 *
 * - array
 * - apc
 * - file
 * - memcached
 *
 * هر داده‌ای که در کش قرار می‌گیرد در یک بازه زمانی معتبر است و بعد از آن
 * دور ریخته می‌شود این بازه زمانی به صورت زیر تعیین می‌شود (زمان بر اساس
 * ثانیه تعیین می‌شود):
 *
 * <pre><code>
 * cfg['cache_file_timeout'] = 300;
 * </code></pre>
 *
 * نمونه کد زیر یک کش را گرفته و یک مقدار را در آن ذخیره می‌کند. این
 * مقدار در فراخوانی‌های بعد قابل استفاده است.
 *
 * <pre>
 * $cache = new Pluf_Cache::getInstance($options);
 * if (null === ($foo=$cache->get('my-key'))) {
 * $foo = run_complex_operation();
 * $cache->set('my-key', $foo);
 * }
 * return $foo;
 * </pre>
 *
 * نکته: مقداری که در کش قرار می‌گیرد باید قابل سریال شده باشد در غیر این صورت
 * خطا ایجاد خواهد شد.
 *
 * NOTE: It is not possible to push a non serialable object into the cache system.
 *
 * @see http://www.php.net/serialize
 */
abstract class Cache
{

    use \Pluf\DiContainerTrait;

    protected $timeout = null;

    /**
     * Creates new instance of Cache
     *
     * @return Cache Cache object
     */
    public static function getInstance(?Options $options = null): Cache
    {
        if (! isset($options)) {
            $options = Pluf::getConfigByPrifix('cache_', true);
        }
        $type = $options->engine;
        if (! isset($type)) {
            $type = 'array';
        }
        switch ($type) {
            case 'array':
                $engine = new Cache\ArrayCache($options->startsWith('array_', true));
                break;
            case 'apcu':
                $engine = new Cache\Apcu($options->startsWith('apcu_', true));
                break;
            case 'file':
                $engine = new Cache\File($options->startsWith('file_', true));
                break;
            case 'memcached':
                $engine = new Cache\Memcached($options->startsWith('memcached_', true));
                break;
            default:
                throw new Exception('Unsupported cache engine: ' . $options->engine);
        }

        return $engine;
    }

    /**
     * Returns timeout to this class
     *
     * @return mixed
     */
    public function getTimeout()
    {
        return $this->timeout;
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
    public abstract function set($key, $value, $timeout = null);

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
    public abstract function get($key, $default = null);
}
