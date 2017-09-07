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
 * لایه نمایش احراز اصالت را ایجاد می‌کند
 *
 * @date 1394 یک پیاده سازی اولیه از این کلاس ارائه شده است که در آن دو واسط
 * RESR برای ورود و خروج در نظر گرفته شده است.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class User_Views_Mail
{

    /**
     * با استفاده از این فراخوانی ایمیل کاربر تغییر می‌کند.
     *
     * زمانی که کاربر ایمیل خود را تغییر دهد یک پیام برای ایمیل ارسال می‌شود در
     * صورتی که ایمیل درست باشد، ایمیل کاربر تغییر می‌کند.
     */
    public function changeEmail ($request, $match)
    {
        $key = $match[1];
        list ($email, $id, $time) = User_Form_UserChangeEmail::validateKey($key);
        if ($id != $request->user->id) {
            throw new Pluf_Exception('user not match');
        }
        // Now we have a change link coming from the right user.
        if ($request->user->email == $email) {
            return new Pluf_HTTP_Response_Json($request->user);
        }
        
        $request->user->email = $email;
        $request->user->update();
        $request->user->setMessage(
                sprintf(
                        __(
                                'Your new email address "%s" has been validated. Thank you!'), 
                        Pluf_esc($email)));
        User_Shortcuts_UpdateLeveFor($request->user, 'user_email_registerd');
        // Return response
        return new Pluf_HTTP_Response_Json($request->user);
    }
}
