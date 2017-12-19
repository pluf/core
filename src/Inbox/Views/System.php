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
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * لایه نمایش مدیریت کاربران را به صورت پیش فرض ایجاد می‌کند
 *
 * @author maso
 *        
 */
class Inbox_Views_System
{

    /**
     * پیش نیازهای حساب کاربری
     *
     * @var unknown
     */
    public $messages_precond = array(
            'User_Precondition::loginRequired'
    );

    /**
     * به روز رسانی و مدیریت اطلاعات خود کاربر
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function messages ($request, $match)
    {
        return new Pluf_HTTP_Response_Json(
                $request->user->getAndDeleteMessages());
    }

    /**
     * یک پیام تست را برای کاربر ایجاد می‌کند.
     *
     * این فراخوانی تنها در حالت رفع خطا قابل استفاده است و در سایر حالت تولید
     * استثنا می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function testMessage ($request, $match)
    {
        if(!Pluf::f ('debug', false)){
            throw new Pluf_Exception(__('not possible to add test message'));
        }
        return new Pluf_HTTP_Response_Json(
                $request->user->setMessage("this is example"));
    }
}
