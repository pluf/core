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
use Pluf;

/**
 * A file based cache
 *
 * در این مدل تمام داده‌هایی که کش می شود در پرونده‌هایی در یک پوشه قرار می‌گیرد.
 * این پوشه نیز در تنظیم‌ها به صورت زیر تعیین می‌شود:
 *
 * <pre><code>
 * cfg['cache_file_folder'] = 'path';
 * </code></pre>
 *
 * تمام زیر پوشه‌هایی که در این مسیر ایجاد می‌شود با استفاده MD5 تعیین نام خواهد شد.
 */
class File extends \Pluf\Cache
{

    use \Pluf\DiContainerTrait;

    /**
     * Mapping key => md5.
     *
     * @var array
     */
    private $keymap = array();

    private $folder = '/tmp';

    private $timeout = null;

    /**
     * Creates new instance of the file cache
     *
     * @param Options $options
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
        $fname = $this->_keyToFile($key);
        $dir = dirname($fname);
        if (!isset($timeout)) {
            $timeout = $this->timeout;
        }
        if (!isset($timeout)) {
            $timeout = 3000;
        }
        if (! file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $expire = $_SERVER['REQUEST_TIME'] + $timeout;
        $success = file_put_contents($fname, $expire . "\n" . serialize($value), LOCK_EX);
        chmod($fname, 0777);

        return (false === $success) ? false : true;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Cache::get()
     */
    public function get($key, $default = null)
    {
        $fname = $this->_keyToFile($key);
        if (! file_exists($fname)) {
            return $default;
        }

        $data = file_get_contents($fname);
        list ($time, $content) = explode("\n", $data, 2);

        if (isset($this->timeout) && $this->timeout > $_SERVER['REQUEST_TIME'] - $time) {
            @unlink($fname);
            return $default;
        }

        return unserialize($content);
    }

    /**
     * Convert a key into a path to a file.
     *
     * @param
     *            string Key
     * @return string Path to file
     */
    private function _keyToFile($key)
    {
        if (isset($this->_keymap[$key])) {
            $md5 = $this->_keymap[$key];
        } else {
            $md5 = md5($key);
            $this->_keymap[$key] = $md5;
        }

        return $this->folder . '/' . substr($md5, 0, 2) . '/' . substr($md5, 2, 2) . '/' . substr($md5, 4);
    }
}
