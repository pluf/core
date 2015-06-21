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
	static public function baseAccess($request, $app = null) {
		if ($request->application == null && $app === null) {
			throw new Pluf_Exception_PermissionDenied ();
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
	static public function applicationOwner($request, $app = null) {
		$res = Pluf_Precondition::loginRequired ( $request, $app );
		if (true !== $res) {
			return $res;
		}
		if ($app === null) {
			$app = $request->application;
		}
		if ($request->user->administrator) {
			return true;
		}
		if ($request->user->hasPerm ( 'SaaS.software-owner', $app )) {
			return true;
		}
		throw new Pluf_Exception_PermissionDenied ();
	}
	
	/**
	 * بررسی این که عضو و یا مالک یک نرم‌افزار
	 *
	 * @param
	 *        	Pluf_HTTP_Request
	 * @return mixed
	 */
	static public function applicationMemberOrOwner($request, $app = null) {
		$res = Pluf_Precondition::loginRequired ( $request );
		if (true !== $res) {
			return $res;
		}
		if ($app === null) {
			$app = $request->application;
		}
		if ($request->user->administrator) {
			return true;
		}
		if ($request->user->hasPerm ( 'SaaS.software-owner', $app ) || $request->user->hasPerm ( 'SaaS.software-member', $app )) {
			return true;
		}
		throw new Pluf_Exception_PermissionDenied ();
	}
}