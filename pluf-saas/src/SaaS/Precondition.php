<?php

/**
 * پیش شرط‌های سیستم را ایجاد می‌کند.
 * 
 * @author maso
 *
 */
class SaaS_Precondition {
	/**
	 * بررسی دسترسی پایه به نرم‌افزار
	 *
	 * در برخی موارد نیاز است که سایت به صورت موقت بسته شود. این فراخوانی برای
	 * تعیین بسته بودن سایت است.
	 *
	 * @param
	 *        	Pluf_HTTP_Request
	 * @return mixed
	 */
	static public function baseAccess($request) {
		if($request->application == null){
			return new Pluf_HTTP_Response_Forbidden ( $request );
		}
		return true;
	}
	
	/**
	 * بررسی مالک نرم‌افزار
	 *
	 * @param
	 *        	Pluf_HTTP_Request
	 * @return mixed
	 */
	static public function applicationOwner($request) {
		$res = Pluf_Precondition::loginRequired ( $request );
		if (true !== $res) {
			return $res;
		}
		if ($request->user->hasPerm ( 'SaaS.software-owner', $request->application )) {
			return true;
		}
		return new Pluf_HTTP_Response_Forbidden ( $request );
	}
	
	/**
	 * بررسی این که عضو و یا مالک یک نرم‌افزار
	 *
	 * @param
	 *        	Pluf_HTTP_Request
	 * @return mixed
	 */
	static public function applicationMemberOrOwner($request) {
		$res = Pluf_Precondition::loginRequired ( $request );
		if (true !== $res) {
			return $res;
		}
		if ($request->user->hasPerm ( 'SaaS.software-owner', $request->application ) or $request->user->hasPerm ( 'SaaS.software-member', $request->application )) {
			return true;
		}
		return new Pluf_HTTP_Response_Forbidden ( $request );
	}
}