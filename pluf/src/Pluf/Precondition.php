<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
 * # ***** BEGIN LICENSE BLOCK *****
 * # This file is part of Plume Framework, a simple PHP Application Framework.
 * # Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
 * #
 * # Plume Framework is free software; you can redistribute it and/or modify
 * # it under the terms of the GNU Lesser General Public License as published by
 * # the Free Software Foundation; either version 2.1 of the License, or
 * # (at your option) any later version.
 * #
 * # Plume Framework is distributed in the hope that it will be useful,
 * # but WITHOUT ANY WARRANTY; without even the implied warranty of
 * # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * # GNU Lesser General Public License for more details.
 * #
 * # You should have received a copy of the GNU Lesser General Public License
 * # along with this program; if not, write to the Free Software
 * # Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 * #
 * # ***** END LICENSE BLOCK *****
 */

/**
 * پیش شرط‌های استاندارد را ایجاد می‌کند.
 *
 * در بسیاری از موارد لایه نمایش تنها با در نظر گرفتن برخی پیش شرط‌ها قابل دست رسی است
 * در این کلاس پیش شرطهای استاندارد تعریف شده است.
 */
class Pluf_Precondition {
	/**
	 * Check if the user is logged in.
	 *
	 * Returns a redirection to the login page, but if not active
	 * returns a forbidden error.
	 *
	 * @param
	 *        	Pluf_HTTP_Request
	 * @return mixed
	 */
	static public function loginRequired($request) {
		if (! isset ( $request->user ) or $request->user->isAnonymous ()) {
			return new Pluf_HTTP_Response_RedirectToLogin ( $request );
		}
		if (! $request->user->active) {
			return new Pluf_HTTP_Response_Forbidden ( $request );
		}
		return true;
	}
	
	/**
	 * Check if the user is admin or staff.
	 *
	 * @param
	 *        	Pluf_HTTP_Request
	 * @return mixed
	 */
	static public function staffRequired($request) {
		$res = Pluf_Precondition::loginRequired ( $request );
		if (true !== $res) {
			return $res;
		}
		if ($request->user->administrator or $request->user->staff) {
			return true;
		}
		return new Pluf_HTTP_Response_Forbidden ( $request );
	}
	
	/**
	 * Check if the user is administrator..
	 *
	 * @param
	 *        	Pluf_HTTP_Request
	 * @return mixed
	 */
	static public function adminRequired($request) {
		$res = Pluf_Precondition::loginRequired ( $request );
		if (true !== $res) {
			return $res;
		}
		if ($request->user->administrator) {
			return true;
		}
		return new Pluf_HTTP_Response_Forbidden ( $request );
	}
	
	/**
	 * Check if the user has a given permission..
	 *
	 * @param
	 *        	Pluf_HTTP_Request
	 * @param
	 *        	string Permission
	 * @return mixed
	 */
	static public function hasPerm($request, $permission) {
		$res = Pluf_Precondition::loginRequired ( $request );
		if (true !== $res) {
			return $res;
		}
		if ($request->user->hasPerm ( $permission )) {
			return true;
		}
		return new Pluf_HTTP_Response_Forbidden ( $request );
	}
	
	/**
	 * Requires SSL to access the view.
	 *
	 * It will redirect the user to the same URL but over SSL if the
	 * user is not using SSL, if POST request, the data are lost, so
	 * handle it with care.
	 *
	 * @param
	 *        	Pluf_HTTP_Request
	 * @return mixed
	 */
	static public function sslRequired($request) {
		if (empty ( $_SERVER ['HTTPS'] ) or $_SERVER ['HTTPS'] == 'off') {
			return new Pluf_HTTP_Response_Redirect ( 'https://' . $request->http_host . $request->uri );
		}
		return true;
	}
}