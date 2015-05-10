<?php
Pluf::loadFunction ( 'Pluf_HTTP_URL_urlForView' );
Pluf::loadFunction ( 'Pluf_Shortcuts_GetObjectOr404' );
Pluf::loadFunction ( 'Pluf_Shortcuts_GetFormForModel' );

/**
 * @ingroup views
 * @brief این کلاس نمایش‌های اصلی سیستم را ایجاد می‌کند.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *         @date 1394
 */
class Wiki_Views_Page {

	/**
	 * پیش شرط‌های دستیابی به نرم‌افزار صفحه اصلی
	 * 
	 * @var array $house_precond
	 */
	public $index_precond = array();
	
	/**
	 * نمایش برگه اصلی سایت
	 *
	 * در این نمایش اطلاعات کلی کارگزار نمایش داده می‌شود. این نمایش می‌تواند در حالت واسط
	 * برنامه سازی نیز به کار رود.
	 * این فراخوانی که معادل با ورودی کاربر به سیستم است، منجر به بازیابی نرم‌افزار home
	 * می‌شود.
	 *
	 * @param
	 *        	$request
	 * @param
	 *        	$match
	 */
	public function index($request, $match) {
		throw new Pluf_Exception_NotImplemented();
	}
}