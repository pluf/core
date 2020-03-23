<?php
/**
 * عدم مجوز دسترسی به منابع
 * 
 * در صورتی که اجازه دسترسی به منابع وجود نداشته باشد این خطا صادر می‌شود.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Pluf_Exception_MismatchParameter extends \Pluf\Exception {
	
	/**
	 * یک نمونه از این کلاس ایجاد می‌کند.
	 *
	 * @param string $message        	
	 * @param string $code        	
	 * @param string $previous        	
	 */
	public function __construct($message = null, $previous = null, $link = null, $developerMessage = null) {
		if (! isset ( $message ) || is_null($message)) {
			$message = __ ( 'Required parameters are not defined.' );
		}
		parent::__construct ( $message, 4101, $previous, 404, $link, $developerMessage );
	}
}