<?php
Pluf::loadFunction ( 'Pluf_HTTP_URL_urlForView' );
Pluf::loadFunction ( 'Pluf_Shortcuts_GetObjectOr404' );
Pluf::loadFunction ( 'Pluf_Shortcuts_GetFormForModel' );
Pluf::loadFunction ( 'User_Shortcuts_UserJsonResponse' );

/**
 * لایه نمایش مدیریت کاربران را به صورت پیش فرض ایجاد می‌کند
 *
 * @author maso
 *        
 */
class User_Views_User {
	
	/**
	 * پیش نیازهای حساب کاربری
	 *
	 * @var unknown
	 */
	public $account_precond = array ();
	
	/**
	 * به روز رسانی و مدیریت اطلاعات خود کاربر
	 *
	 * @param unknown_type $request        	
	 * @param unknown_type $match        	
	 */
	public function account($request, $match) {
		if ($request->method === 'GET') {
			return User_Shortcuts_UserJsonResponse ( $request->user );
		}
		// XXX: maso, check user is logined
		if ($request->method === 'POST') {
			// initial page data
			$extra = array (
					'user' => $request->user 
			);
			$form = new User_Form_Account ( array_merge ( $request->POST, $request->FILES ), $extra );
			$cuser = $form->update ();
			$request->user->setMessage ( sprintf ( __ ( 'Account data has been updated.' ), ( string ) $cuser ) );
			
			// Return response
			return User_Shortcuts_UserJsonResponse ( $cuser );
		}
		
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
		// Create account
		$extra = array ();
		$form = new User_Form_User ( array_merge ( $request->POST, $request->FILES ), $extra );
		$cuser = $form->save ();
		
		// Create profile
		$profile_model = Pluf::f ( 'user_profile_class', false );
		$profile_form = Pluf::f ( 'user_profile_form', false );
		if ($profile_form === false || $profile_model === false) {
			return User_Shortcuts_UserJsonResponse ( $cuser );
		}
		try {
			$profile = $cuser->getProfile ();
		} catch ( Pluf_Exception_DoesNotExist $ex ) {
			$profile = new $profile_model ();
			$profile->user = $cuser;
			$profile->create ();
		}
		$form = new $profile_form ( array_merge ( $request->POST, $request->FILES ), array (
				'user_profile' => $profile 
		) );
		$profile = $form->update ();
		
		// Return response
		return User_Shortcuts_UserJsonResponse ( $cuser );
	}
	
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
		$user_id = $match [1];
		if ($user_id === $request->user->id) {
			return $this->account ( $request, $match );
		}
		throw new Pluf_Exception_NotImplemented ();
	}
}
