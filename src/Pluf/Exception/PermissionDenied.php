<?php
/**
 * عدم مجوز دسترسی به منابع
 * 
 * در صورتی که اجازه دسترسی به منابع وجود نداشته باشد این خطا صادر می‌شود.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Pluf_Exception_PermissionDenied extends Pluf_Exception {
	
	/**
	 * یک نمونه از این کلاس ایجاد می‌کند.
	 *
	 * @param string $message        	
	 * @param string $code        	
	 * @param string $previous        	
	 */
	public function __construct($message = null, $previous = null, $link = null, $developerMessage = null) {
		if (! isset ( $message ) || is_null($message)) {
			$message = __ ( 'ِYou are not permited to access the resource.' );
		}
		parent::__construct ( $message, 4041, $previous, 404, $link, $developerMessage );
	}
}