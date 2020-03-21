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
/**
 * کلاس کلی کش کردن
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
 * 	cfg['cache_engine'] = 'Apc';
 * </code></pre>
 * 
 * انواع متفاوتی که در حال حاضر برای این داده وجود دارد عبارتند از:
 * 
 * - Apc
 * - File
 * - Memcached
 *  
 * هر داده‌ای که در کش قرار می‌گیرد در یک بازه زمانی معتبر است و بعد از آن
 * دور ریخته می‌شود این بازه زمانی به صورت زیر تعیین می‌شود (زمان بر اساس 
 * ثانیه تعیین می‌شود): 
 *  
 * <pre><code>
 * 	cfg['cache_timeout'] = 300;
 * </code></pre>
 * 
 * نمونه کد زیر یک کش را گرفته و یک مقدار را در آن ذخیره می‌کند. این 
 * مقدار در فراخوانی‌های بعد قابل استفاده است.
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
 * نکته: مقداری که در کش قرار می‌گیرد باید قابل سریال شده باشد در غیر این صورت
 * خطا ایجاد خواهد شد.
 *
 * @see http://www.php.net/serialize
 */
class Pluf_Cache {
	/**
	 * فراخوانی سازنده کش
	 *
	 * @return Pluf_Cache_* Cache object
	 */
	public static function factory() {
		if (false === ($engine = Pluf::f ( 'cache_engine', false ))) {
			throw new \Pluf\Exception_SettingError ( '"cache_engine" setting not defined.' );
		}
		if (! isset ( $GLOBALS ['_PX_Pluf_Cache-' . $engine] )) {
			$GLOBALS ['_PX_Pluf_Cache-' . $engine] = new $engine ();
		}
		return $GLOBALS ['_PX_Pluf_Cache-' . $engine];
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
		throw new \Pluf\Exception_NotImplemented ();
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
		throw new \Pluf\Exception_NotImplemented ();
	}
}
