<?php
/**
 * مشخص نبودن دامنه فعالیت را تعیین می‌کند
 * 
 * هر دامنه معادل با یک آپارتمان است. بسیاری از کاربردهای سیستم بر اساس 
 * دامنه کار می‌کنند. از این رو مشخص بودن دامنه در آنها بسیار مهم است، در
 * این موارد اگر دامنه تعیین نشده باشد این استثنا صادر می‌شود.
 * 
 * @see HM_Middleware_Apartment
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class HM_Exception_EmptyApartment extends Pluf_Exception {
	
	/**
	 * یک نمونه از این کلاس ایجاد می‌کند.
	 *
	 * @param string $message        	
	 * @param string $code        	
	 * @param string $previous        	
	 */
	public function __construct($message = null, $previous = null, $link = null, $developerMessage = null) {
		if (! isset ( $message ) || is_null($message)) {
			$message = __ ( 'Apartment is not set?!' );
		}
		// XXX: maso, 1394: خطاها و پیام‌های آن باید تعیین شود.
		parent::__construct ( $message, 4051, $previous, 400, $link, $developerMessage );
	}
}