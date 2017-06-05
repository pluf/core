<?php

/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * پیش شرط‌های استاندارد را ایجاد می‌کند.
 *
 * در بسیاری از موارد لایه نمایش تنها با در نظر گرفتن برخی پیش شرط‌ها قابل دست
 * رسی است
 * در این کلاس پیش شرطهای استاندارد تعریف شده است.
 */
class Pluf_Precondition
{

    /**
     * Check if the user is logged in.
     *
     * Returns a redirection to the login page, but if not active
     * returns a forbidden error.
     *
     * @param
     *            Pluf_HTTP_Request
     * @return mixed
     */
    static public function loginRequired ($request)
    {
        if (! isset($request->user) or $request->user->isAnonymous()) {
            throw new Pluf_Exception_Unauthorized('Login required', null, '', 
                    'login is required, or cocki is not enabled');
        }
        if (! $request->user->active) {
            throw new Pluf_Exception('user is not active', 4002, null, 400, '', 
                    'user is not active');
        }
        return true;
    }
    
    /**
     * Check if the user is logged in.
     *
     * Returns true if user is loged in and is active
     *
     * @param
     *            Pluf_HTTP_Request
     * @return boolean
     */
    static public function isLogedIn ($request)
    {
        if (! isset($request->user) or $request->user->isAnonymous()) {
            return false;
        }
        if (! $request->user->active) {
            return false;
        }
        return true;
    }

    /**
     * Check if the user is admin or staff.
     *
     * @param
     *            Pluf_HTTP_Request
     * @return mixed
     */
    static public function staffRequired ($request)
    {
        $res = Pluf_Precondition::loginRequired($request);
        if (true !== $res) {
            return $res;
        }
        if ($request->user->administrator or $request->user->staff) {
            return true;
        }
        throw new Pluf_Exception('staff required', 4003, null, 400, '', 
                'staff required');
    }

    /**
     * Check if the user is administrator..
     *
     * @param
     *            Pluf_HTTP_Request
     * @return mixed
     */
    static public function adminRequired ($request)
    {
        $res = Pluf_Precondition::loginRequired($request);
        if (true !== $res) {
            return $res;
        }
        if ($request->user->administrator) {
            return true;
        }
        throw new Pluf_Exception('admin required', 4004, null, 400, '', 
                'admin required');
    }
    
    /**
     * Check if the user is admin or staff.
     *
     * @param
     *            Pluf_HTTP_Request
     * @return boolean
     */
    static public function isStaff ($request)
    {
        $res = Pluf_Precondition::isLogedIn($request);
        if (true !== $res) {
            return $res;
        }
        if ($request->user->administrator or $request->user->staff) {
            return true;
        }
        return false;
    }

    /**
     * Check if the user is administrator..
     *
     * @param
     *            Pluf_HTTP_Request
     * @return mixed
     */
    static public function isAdministrator ($request)
    {
        $res = Pluf_Precondition::isLogedIn($request);
        if (true !== $res) {
            return $res;
        }
        if ($request->user->administrator) {
            return true;
        }
        return false;
    }
    
    /**
     * Check if the user has a given permission..
     *
     * @param
     *            Pluf_HTTP_Request
     * @param
     *            string Permission
     * @return mixed
     */
    static public function hasPerm ($request, $permission)
    {
        $res = Pluf_Precondition::loginRequired($request);
        if (true !== $res) {
            return $res;
        }
        if ($request->user->hasPerm($permission)) {
            return true;
        }
        throw new Pluf_Exception('you do not have permission', 4005, null, 400, 
                '', 'you do not have permission');
    }

    /**
     * Requires SSL to access the view.
     *
     * It will redirect the user to the same URL but over SSL if the
     * user is not using SSL, if POST request, the data are lost, so
     * handle it with care.
     *
     * @param
     *            Pluf_HTTP_Request
     * @return mixed
     */
    static public function sslRequired ($request)
    {
        if (empty($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off') {
            return new Pluf_HTTP_Response_Redirect(
                    'https://' . $request->http_host . $request->uri);
        }
        return true;
    }

    /**
     * بررسی می‌کند که آیا درخواست داده شده توسط کاربری ارسال شده که مالک tenant
     * است یا نه.
     * در صورتی که کاربر مالک tenant نباشد استثنای
     * Pluf_Exception_PermissionDenied صادر می‌شود
     *
     * @param unknown $request            
     * @throws Pluf_Exception_PermissionDenied
     */
    static public function ownerRequired ($request)
    {
        $res = Pluf_Precondition::loginRequired($request);
        if (true !== $res) {
            return $res;
        }
        if ($request->user->administrator) {
            return true;
        }
        if ($request->user->hasPerm('Pluf.owner', null, $request->tenant->id)) {
            return true;
        }
        throw new Pluf_Exception_PermissionDenied();
    }

    /**
     * بررسی می‌کند که آیا درخواست داده شده توسط کاربری ارسال شده که عضو tenant
     * است یا نه.
     * در صورتی که کاربر عضو tenant نباشد استثنای
     * Pluf_Exception_PermissionDenied صادر می‌شود
     *
     * @param unknown $request            
     * @throws Pluf_Exception_PermissionDenied
     */
    static public function memberRequired ($request)
    {
        $res = Pluf_Precondition::loginRequired($request);
        if (true !== $res) {
            return $res;
        }
        if ($request->user->administrator) {
            return true;
        }
        if ($request->user->hasPerm('Pluf.owner', null, $request->tenant->id) || $request->user->hasPerm(
                'Pluf.member', null, $request->tenant->id)) {
            return true;
        }
        throw new Pluf_Exception_PermissionDenied();
    }

    /**
     * بررسی می‌کند که آیا درخواست داده شده توسط کاربری ارسال شده که در tenant
     * مجاز است یا نه.
     * در صورتی که کاربر در tenant مجاز نباشد استثنای
     * Pluf_Exception_PermissionDenied صادر می‌شود
     *
     * @param unknown $request            
     * @throws Pluf_Exception_PermissionDenied
     */
    static public function authorizedRequired ($request)
    {
        $res = Pluf_Precondition::loginRequired($request);
        if (true !== $res) {
            return $res;
        }
        if ($request->user->administrator) {
            return true;
        }
        if ($request->user->hasPerm('Pluf.owner', null, $request->tenant->id) || $request->user->hasPerm(
                'Pluf.member', null, $request->tenant->id) || $request->user->hasPerm(
                'Pluf.authorized', null, $request->tenant->id)) {
            return true;
        }
        throw new Pluf_Exception_PermissionDenied();
    }

    /**
     * بررسی می‌کند که آیا درخواست داده شده توسط کاربری ارسال شده که مالک tenant
     * است یا نه.
     * در صورتی که کاربر مالک tenant نباشد مقدار false برگردانده می‌شود.
     *
     * @param Pluf_HTTP_Request $request            
     * @return اگر کاربر مالک tenant باشد مقدار true وگرنه مقدار false برگردانده
     *         می‌شود
     */
    static public function isOwner ($request)
    {
        if (! isset($request->user) or $request->user->isAnonymous()) {
            return false;
        }
        // Precondition::baseAccess($request, $app);
        if ($request->user->administrator) {
            return true;
        }
        if ($request->user->hasPerm('Pluf.owner')) {
            return true;
        }
        return false;
    }

    /**
     * بررسی می‌کند که آیا درخواست داده شده توسط کاربری ارسال شده که عضو tenant
     * است یا نه.
     * در صورتی که کاربر عضو tenant نباشد مقدار false برگردانده می‌شود.
     *
     * @param Pluf_HTTP_Request $request            
     * @return اگر کاربر عضو tenant باشد مقدار true وگرنه مقدار false برگردانده
     *         می‌شود
     */
    static public function isMember ($request)
    {
        if (! isset($request->user) or $request->user->isAnonymous()) {
            return false;
        }
        if ($request->user->administrator) {
            return true;
        }
        if ($request->user->hasPerm('Pluf.owner', $request->application) || $request->user->hasPerm(
                'Pluf.member')) {
            return true;
        }
        return false;
    }

    /**
     * بررسی می‌کند که آیا درخواست داده شده توسط کاربری ارسال شده که در tenant
     * مجاز است یا نه.
     * در صورتی که کاربر عضو tenant نباشد مقدار false برگردانده می‌شود.
     *
     * @param Pluf_HTTP_Request $request            
     * @return اگر کاربر در tenant مجاز باشد مقدار true وگرنه مقدار false
     *         برگردانده می‌شود
     */
    static public function isAuthorized ($request)
    {
        if (! isset($request->user) or $request->user->isAnonymous()) {
            return false;
        }
        if ($request->user->administrator) {
            return true;
        }
        if ($request->user->hasPerm('Pluf.owner', $request->application) ||
                 $request->user->hasPerm('Pluf.member', $request->application) || $request->user->hasPerm(
                        'Pluf.authorized')) {
            return true;
        }
        return false;
    }

    /**
     * 
     * @param Pluf_HTTP_Request $request
     * @param int $userId id of user who role will be granted.
     * @param int $roldId id of permission to grant
     * @return mixed|boolean
     */
    static public function couldAddRole($request, $userId, $roleId){
        $res = Pluf_Precondition::loginRequired($request);
        if (true !== $res) {
            return $res;
        }
        if ($request->user->administrator) {
            return true;
        }
        if ($request->user->hasPerm('Pluf.owner', null, $request->tenant->id)) {
            return true;
        }
        // XXX: hadi, 1396-03: It is assumed that permission with id 3 is 'authorized'
        if ($request->user->id === $userId && $roleId === 3) {
            return true;
        }
        throw new Pluf_Exception_PermissionDenied('You have not permission to add such role.');
    }
    
    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param int $userId id of user who role will be granted.
     * @param int $roldId id of permission to grant
     * @return mixed|boolean
     */
    static public function couldRemoveRole($request, $userId, $roleId){
        $res = Pluf_Precondition::loginRequired($request);
        if (true !== $res) {
            return false;
        }
        if ($request->user->administrator) {
            return true;
        }
        if ($request->user->hasPerm('Pluf.owner', null, $request->tenant->id)) {
            return true;
        }
        if ($request->user->id === $userId) {
            return true;
        }
        throw new Pluf_Exception_PermissionDenied('You have not permission to remove such role.');
    }
}