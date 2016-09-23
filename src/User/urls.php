<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. http://dpq.co.ir
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
return array(
        array( // ورود کاربر به سیستم است
                'regex' => '#^/login$#',
                'model' => 'User_Views_Authentication',
                'method' => 'login',
                'http-method' => 'POST'
        ),
        array( // خروج کاربران از سیستم
                'regex' => '#^/logout$#',
                'model' => 'User_Views_Authentication',
                'method' => 'logout',
                'http-method' => array(
                        'POST',
                        'GET'
                )
        ),
//         array( // اطلاعات کاربر را به روز می‌کند
//                 'regex' => '#^/account/verify/(?P<type>.+)$#',
//                 'model' => 'User_Views_User',
//                 'method' => 'changeEmail',
//                 'precond' => array(
//                         'Pluf_Precondition::loginRequired'
//                 ),
//                 'http-method' => array(
//                         'POST',
//                         'GET'
//                 )
//         ),
        array( // دریافت پروفایل کاربر
                'regex' => '#^/profile$#',
                'model' => 'User_Views',
                'method' => 'profile',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
                'http-method' => 'GET'
        ),
        array( // به روز رسانی پروفایل کاربری
                'regex' => '#^/profile$#',
                'model' => 'User_Views',
                'method' => 'updateProfile',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
                'http-method' => 'POST'
        ),
        array( // اطلاعات کاربری را در اختیار شما قرار می‌دهد
                'regex' => '#^/account$#',
                'model' => 'User_Views',
                'method' => 'account',
                'http-method' => 'GET'
        ),
        array( // اطلاعات کاربر را به روز می‌کند
                'regex' => '#^/account$#',
                'model' => 'User_Views',
                'method' => 'updateAccount',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
                'http-method' => 'POST'
        ),
        
        /*
         * مدیریت سیستم
         */
        array( // ثبت یک کاربر جدید
                'regex' => '#^/new$#',
                'model' => 'User_Views_User',
                'method' => 'create',
                'http-method' => 'POST'
        ),
        array( // فهرست کاربران
                'regex' => '#^/find$#',
                'model' => 'User_Views_User',
                'method' => 'find',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
                'http-method' => 'GET'
        ),
        array( // گرفتن اطلاعات کاربر
                'regex' => '#^/(?P<userId>\d+)$#',
                'model' => 'User_Views_User',
                'method' => 'get',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
                'http-method' => 'GET'
        ),
        array( // به روز کردن اطلاعات کاربر
                'regex' => '#^/(?P<userId>\d+)$#',
                'model' => 'User_Views_User',
                'method' => 'update',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
                'http-method' => 'POST'
        ),
        array( // به روز کردن اطلاعات کاربر
                'regex' => '#^/(?P<userId>\d+)$#',
                'model' => 'User_Views_User',
                'method' => 'delete',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
                'http-method' => 'POST'
        ),
        /*
         * Profile
         */
        array( // گرفتن پروفایل کاربر
                'regex' => '#^/(?P<userId>\d+)/profile$#',
                'model' => 'User_Views_Profile',
                'method' => 'get',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
                'http-method' => 'GET'
        ),
        array( // به روز کردن پروفایل کاربر
                'regex' => '#^/(?P<userId>\d+)/profile$#',
                'model' => 'User_Views_Profile',
                'method' => 'update',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
                'http-method' => 'POST'
        ),

        /*
         * Groups
         */
        array(
                'regex' => '#^/(?P<userId>\d+)/group/new$#',
                'model' => 'User_Views_Group',
                'method' => 'create',
                'http-method' => 'POST'
        ),
        array(
                'regex' => '#^/(?P<userId>\d+)/group/find$#',
                'model' => 'User_Views_Group',
                'method' => 'find',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/(?P<userId>\d+)/group/(?P<groupId>\d+)$#',
                'model' => 'User_Views_Group',
                'method' => 'get',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/(?P<userId>\d+)/group/(?P<groupId>\d+)$#',
                'model' => 'User_Views_Group',
                'method' => 'update',
                'http-method' => 'POST'
        ),
        array(
                'regex' => '#^/(?P<userId>\d+)/group/(?P<groupId>\d+)$#',
                'model' => 'User_Views_Group',
                'method' => 'delete',
                'http-method' => 'DELETE'
        ),
        /*
         * Role
         */
        array(
                'regex' => '#^/(?P<userId>\d+)/role/new$#',
                'model' => 'User_Views_Permission',
                'method' => 'find',
                'http-method' => 'POST'
        ),
        array(
                'regex' => '#^/(?P<userId>\d+)/role/find$#',
                'model' => 'User_Views_Permission',
                'method' => 'find',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/(?P<userId>\d+)/role/(?P<roleId>\d+)$#',
                'model' => 'User_Views_Permission',
                'method' => 'get',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/(?P<userId>\d+)/role/(?P<roleId>\d+)$#',
                'model' => 'User_Views_Permission',
                'method' => 'POST',
                'http-method' => 'update'
        ),
        array(
                'regex' => '#^/(?P<userId>\d+)/role/(?P<roleId>\d+)$#',
                'model' => 'User_Views_Permission',
                'method' => 'delete',
                'http-method' => 'DELETE'
        ),
        

        /*
         * Message
         */
        array(
                'regex' => '#^/(?P<userId>\d+)/message/new$#',
                'model' => 'User_Views_Message',
                'method' => 'find',
                'http-method' => 'POST'
        ),
        array(
                'regex' => '#^/(?P<userId>\d+)/message/find$#',
                'model' => 'User_Views_Message',
                'method' => 'find',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/(?P<userId>\d+)/message/(?P<messageId>\d+)$#',
                'model' => 'User_Views_Message',
                'method' => 'get',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/(?P<userId>\d+)/message/(?P<messageId>\d+)$#',
                'model' => 'User_Views_Message',
                'method' => 'POST',
                'http-method' => 'update'
        ),
        array(
                'regex' => '#^/(?P<userId>\d+)/message/(?P<messageId>\d+)$#',
                'model' => 'User_Views_Message',
                'method' => 'delete',
                'http-method' => 'DELETE'
        )
);
