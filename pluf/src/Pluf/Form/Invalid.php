<?php
/**
 * خطای معادل با نامعتبر بودن یک فرم
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Pluf_Form_Invalid extends PLuf_Exception {
	

	/**
	 * یک نمونه از این کلاس ایجاد می‌کند.
	 *
	 * @param string $message
	 * @param string $code
	 * @param string $previous
	 */
	public function __construct(
			$message = "HTTP POST method is suported.",
			$previous = null,
			$link = null,
			$developerMessage = null) {
		parent::__construct ( $message, 4062, $previous, 405, $link, $developerMessage);
	}
}
