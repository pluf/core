<?php
Pluf::loadFunction ( 'Pluf_HTTP_URL_urlForView' );
Pluf::loadFunction ( 'Pluf_Shortcuts_GetObjectOr404' );
Pluf::loadFunction ( 'Pluf_Shortcuts_GetFormForModel' );

/**
 * لایه نمایش احراز اصالت را ایجاد می‌کند
 *
 * @date 1394 یک پیاده سازی اولیه از این کلاس ارائه شده است که در آن دو واسط
 * RESR برای ورود و خروج در نظر گرفته شده است.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class User_Views_Authentication {
	
	/**
	 * نمایش ورود کاربران به سیستم را ایجاد می‌کند
	 *
	 * مبنای پیاده سازی این نمایش ورود فراخوانی REST است از این رو با فراخوانی این نمایش
	 * یک JSON به عنوان نتیجه برگردانده می‌شود.
	 */
	public function login($request, $match) {
		if ($request->method == 'GET') {
			throw new Pluf_Exception_PostMethodSuported ();
		}
		
		if (! $request->user->isAnonymous ()) {
			return new Pluf_HTTP_Response_Json ( $request->user );
		}
		
		$backends = Pluf::f ( 'auth_backends', array (
				'Pluf_Auth_ModelBackend' 
		) );
		foreach ( $backends as $backend ) {
			$user = call_user_func ( array (
					$backend,
					'authenticate' 
			), $request->POST );
			if ($user !== false) {
				break;
			}
		}
		
		if (false === $user) {
			throw new Pluf_Exception ( __ ( 'The login or the password is not valid. The login and the password are case sensitive.' ) );
		}
		
		$request->user = $user;
		$request->session->clear ();
		$request->session->setData ( 'login_time', gmdate ( 'Y-m-d H:i:s' ) );
		$user->last_login = gmdate ( 'Y-m-d H:i:s' );
		$user->update ();
		$request->session->deleteTestCookie ();
		
		return new Pluf_HTTP_Response_Json ( $user );
	}
	
	/**
	 * کاربر را از سیستم خارج می‌کند.
	 * 
	 */
	function logout($request, $match) {
		$views = new Pluf_Views ();
		$views->logout ( $request, $match, Pluf::f ( 'after_logout_page' ) );

		return new Pluf_HTTP_Response_Json ( array() );
	}
}
