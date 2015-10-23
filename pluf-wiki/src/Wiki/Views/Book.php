<?php
Pluf::loadFunction ( 'Pluf_HTTP_URL_urlForView' );
Pluf::loadFunction ( 'Pluf_Shortcuts_GetObjectOr404' );
Pluf::loadFunction ( 'Pluf_Shortcuts_GetFormForModel' );

/**
 * لایه نمایش کتاب‌ها را ایجاد می‌کند.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 */
class Wiki_Views_Book {
	
	/**
	 * پیش شرط‌های دستیابی به نرم‌افزار صفحه اصلی
	 *
	 * @var array $house_precond
	 */
	public $get_precond = array ();
	
	/**
	 * 
	 * @param unknown $request
	 * @param unknown $match
	 * @throws Wiki_BookNotFoundException
	 */
	public function get($request, $match) {
		throw new Wiki_BookNotFoundException(__('requeisted book not found.'));
	}
}