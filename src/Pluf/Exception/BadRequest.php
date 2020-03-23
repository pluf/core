<?php

/**
 * خطای فرم
 * 
 * خطای فرم را ایجاد می‌کند. این خطا علاوه بر اطلاعات معمولی یک آرایه از پیام‌های خطا را ایجاد
 * می‌کند که خطای معادل با پارامترهای ورودی است. برای نمونه اگر یک پارامتر به نام email وجود
 * داشته باشد و کاربر آن را به صورت درستی وارد نکرده باشد، آنگاه در فیلد data یک مقدار به نام
 * email وجود دارد که مقدار آن خطای ایجاد شده است.
 * 
 * @author maso
 *
 */
class Pluf_Exception_BadRequest extends \Pluf\Exception
{
	/**
	 * یک نمونه از این کلاس ایجاد می‌کند.
	 *
	 * @param string $message
	 * @param string $previous
	 */
	public function __construct($message, $link=null, $developerMessage=null) {
	    if (! isset ( $message ) || is_null($message)) {
	        $message = __ ( 'ِData is not valid.' );
	    }
		parent::__construct ( $message, 4000, null, 400, $link, $developerMessage);
	}
}


