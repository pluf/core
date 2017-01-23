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
 * کش مبتنی بر APC
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
	
	/**
	 * یک نمونه جدید از این کلاس ایجاد می‌کند
	 *
	 * نمونه ایجاد شده با استفاده از تنظیم‌هایی که در تنظیم‌های سرور تعیین شده\
	 * است مقدار دهی و راه اندازی می‌شود.
	 */
	public function __construct() {
		$this->keyprefix = Pluf::f ( 'cache_apc_keyprefix', '' );
		$this->compress = Pluf::f ( 'cache_apc_compress', false );
	}
	
	/**
	 * یک مقدار را در کش قرار می‌دهد
	 *
	 * @param
	 *        	string Key کلیدی که برای ذخیره سازی استفاده می‌شود
	 * @param
	 *        	mixed Value مقداری که باید کش شود
	 * @param
	 *        	int Timeout زمان انقضا را بر اساس ثانیه تعیین می‌کند
	 * @return bool حالت موفقیت انجام این عمل را تعیین می‌کند.
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
		// NOTE: maso, 1395: این نوع ارسال اشاره‌گر در نسخه ۷ php حذف شده است.
		$value = apc_fetch ( $this->keyprefix . $key, &$success );
		if (! $success)
			return $default;
		if ($this->compress)
			$value = gzinflate ( $value );
		return unserialize ( $value );
	}
}
