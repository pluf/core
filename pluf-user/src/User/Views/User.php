<?php
Pluf::loadFunction ( 'Pluf_HTTP_URL_urlForView' );
Pluf::loadFunction ( 'Pluf_Shortcuts_GetObjectOr404' );
Pluf::loadFunction ( 'Pluf_Shortcuts_GetFormForModel' );

/**
 * لایه نمایش مدیریت کاربران را به صورت پیش فرض ایجاد می‌کند
 *
 * @author maso
 *        
 */
class User_Views_User {
	/**
	 * پیش نیازهای دسترسی به فهرست کاربران
	 *
	 * @var unknown
	 */
	public $users_precond = array (
			'Pluf_Precondition::staffRequired' 
	);
	
	/**
	 * فهرست تمام کاربران را نمایش می‌دهد
	 *
	 * @param unknown_type $request        	
	 * @param unknown_type $match        	
	 */
	public function users($request, $match) {
		throw new Pluf_Exception_NotImplemented ();
	}
	
	/**
	 * پیش نیازهای ثبت کاربران
	 *
	 * @var unknown
	 */
	public $signup_precond = array ();
	
	/**
	 * ثبت کاربران
	 *
	 * @param unknown_type $request        	
	 * @param unknown_type $match        	
	 */
	public function signup($request, $match) {
		// initial page data
		$extra = array ();
		$form = new User_Form_User ( array_merge ( $request->POST, $request->FILES ), $extra );
		$cuser = $form->save ();
		$request->user->setMessage ( sprintf ( __ ( 'The user %s has been created.' ), ( string ) $cuser ) );
		
		// Return response
		return new Pluf_HTTP_Response_Json ( $cuser );
	}
	
	/**
	 * پیش نیازهای فهرست کردن کاربران فعال
	 *
	 * @var unknown
	 */
	public $activeUsers_precond = array (
			'Pluf_Precondition::staffRequired' 
	);
	
	/**
	 * فهرست تمام کاربران فعال را نمایش می‌دهد
	 *
	 * @param unknown_type $request        	
	 * @param unknown_type $match        	
	 */
	public function activeUsers($request, $match) {
		throw new Pluf_Exception_NotImplemented ();
	}
	
	/**
	 * پیش نیازهای فهرست کردن کاربران غیر فعال
	 *
	 * @var unknown
	 */
	public $unactiveUsers_precond = array (
			'Pluf_Precondition::staffRequired' 
	);
	
	/**
	 * فهرست تمام کاربران غیر فعال را نمایش می‌دهد
	 *
	 * @param unknown_type $request        	
	 * @param unknown_type $match        	
	 */
	public function unactiveUsers($request, $match) {
		throw new Pluf_Exception_NotImplemented ();
	}
	
	/**
	 * پیش نیازهای فهرست کردن کاربران
	 *
	 * @var unknown
	 */
	public $user_precond = array (
			'Pluf_Precondition::staffRequired' 
	);
	
	/**
	 * مدیریت یک کاربر را در سیستم ایجاد می‌کند
	 *
	 * @param unknown_type $request        	
	 * @param unknown_type $match        	
	 */
	public function user($request, $match) {
		throw new Pluf_Exception_NotImplemented ();
	}
}
