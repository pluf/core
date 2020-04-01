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
 * APC cache
 *
 * Warning: This extension is considered unmaintained and dead. However,
 * the source code for this extension is still available within PECL
 * GIT here: http://git.php.net/?p=pecl/caching/apc.git.
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
class Apcu extends \Pluf\Cache
{

    use \Pluf\DiContainerTrait;

    /**
     * پیشوندی که به تمام کلیدهای کش اضافه می‌شود.
     * این کلید در تنظیم‌های
     * سیستم تعیین می‌شود.
     */
    private $keyprefix = '';

    /**
     * فشرده کردن داده‌ها را تعیین می‌کند.
     * در صورتی که فشرده سازی فعال شود
     * یک مقدار سربار محاسباتی داریم اما حجم استفاده شده کاهش پیدا می‌کنه.
     */
    private $compress = false;

    private $timeout = null;

    /**
     * Creates new instance of the class
     *
     * نمونه ایجاد شده با استفاده از تنظیم‌هایی که در تنظیم‌های سرور تعیین شده\
     * است مقدار دهی و راه اندازی می‌شود.
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
        if ($timeout == null) {
            $timeout = $this->timeout;
        }
        $value = serialize($value);
        if ($this->compress) {
            $value = gzdeflate($value, 9);
        }
        return apcu_store($this->keyprefix . $key, $value, $timeout);
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Cache::get()
     */
    public function get($key, $default = null)
    {
        $value = apcu_fetch($this->keyprefix . $key);
        if ($value === FALSE) {
            return $default;
        }
        if ($this->compress) {
            $value = gzinflate($value);
        }
        return unserialize($value);
    }
}
