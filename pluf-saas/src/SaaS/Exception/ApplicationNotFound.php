<?php
/**
 * 
 * @see HM_Middleware_Apartment
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class SaaS_Exception_ApplicationNotFound extends Pluf_Exception {
	
	/**
	 * یک نمونه از این کلاس ایجاد می‌کند.
	 *
	 * @param string $message        	
	 * @param string $code        	
	 * @param string $previous        	
	 */
	public function __construct($message = null, $previous = null, $link = null, $developerMessage = null) {
		if (! isset ( $message ) || is_null($message)) {
			$message = __ ( 'Apartment is not found?!' );
		}
		// XXX: maso, 1394: خطاها و پیام‌های آن باید تعیین شود.
		parent::__construct ( $message, 4051, $previous, 400, $link, $developerMessage );
	}
}