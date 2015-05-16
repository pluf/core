<?php
Pluf::loadFunction ( 'Pluf_Shortcuts_GetObjectOr404' );
Pluf::loadFunction ( 'Pluf_Shortcuts_GetFormForModel' );

/**
 * لایه نمایش مدیریت گروه‌ها را به صورت پیش فرض ایجاد می‌کند
 *
 * @author maso
 *        
 */
class User_Views_Group {
	/**
	 * پیش نیازهای دسترسی به فهرست گروه‌ها
	 *
	 * @var unknown
	 */
	public $groups_precond = array (
			'Pluf_Precondition::staffRequired' 
	);
	
	/**
	 * فهرست تمام گروه‌ها را نمایش می‌دهد
	 *
	 * @param unknown_type $request        	
	 * @param unknown_type $match        	
	 */
	public function groups($request, $match) {
		throw new Pluf_Exception_NotImplemented ();
	}

}
