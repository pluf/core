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
class User_Views_Profile {
	/**
	 * پیش نیازهای دسترسی به فهرست کاربران
	 *
	 * @var unknown
	 */
	public $profile_precond = array (
			'Pluf_Precondition::loginRequired' 
	);
	
	/**
	 * فهرست تمام کاربران را نمایش می‌دهد
	 *
	 * @param unknown_type $request        	
	 * @param unknown_type $match        	
	 */
	public function profile($request, $match) {
		$profile_model = Pluf::f ( 'user_profile_class', false );
		if($profile_model === false){
			throw new Pluf_Exception(__('Profile model is not configured.'));
		}
		try {
			$profile = $request->user->getProfile();
		} catch ( Pluf_Exception_DoesNotExist $ex ) {
			$profile = new $profile_model ();
			$profile->user = $request->user;
			$profile->create ();
		}
		// Return response
		return new Pluf_HTTP_Response_Json ( $profile );
	}

}
