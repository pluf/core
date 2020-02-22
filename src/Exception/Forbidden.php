<?php
/**
 * عدم مجوز دسترسی به منابع
 * 
 * در صورتی که اجازه دسترسی به منابع وجود نداشته باشد این خطا صادر می‌شود.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Exception_Forbidden extends Exception {
	
	/**
	 * یک نمونه از این کلاس ایجاد می‌کند.
	 *
	 * @param string $message        	
	 * @param string $code        	
	 * @param string $previous        	
	 */
	public function __construct($message = null, $previous = null, $link = null, $developerMessage = null) {
		if (! isset ( $message ) || is_null($message)) {
			$message = __ ( 'ِYou are not permited to perform the requested operation.' );
		}
		parent::__construct ( $message, 4031, $previous, 403, $link, $developerMessage );
	}
}