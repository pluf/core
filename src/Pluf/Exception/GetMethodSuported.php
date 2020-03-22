<?php

/**
 * متد GET حمایت نمی‌شود.
 * 
 * در صورتیکه متد GET حمایت نشود و کاربر تلاش کند این متد را فراخوانی کند، این خطا
 * صادر خواهد شد.
 * 
 * @author maso
 *
 */
class Pluf_Exception_GetMethodSuported extends \Pluf\Exception
{
	/**
	 * یک نمونه از این کلاس ایجاد می‌کند.
	 *
	 * @param string $message
	 * @param string $code
	 * @param string $previous
	 */
	public function __construct(
			$message = "HTTP GET method is just suported.", 
			$previous = null, 
			$link = null, 
			$developerMessage = null) {
		parent::__construct ( $message, 4051, $previous, 405, $link, $developerMessage);
	}
}