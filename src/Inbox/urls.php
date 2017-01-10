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
return array (
        /*
         * System messages
         */
		array( // پیامها را دریافت کرده و آنها را حذف می‌کند.
                'regex' => '#^/system/list$#',
                'model' => 'Inbox_Views_System',
                'method' => 'messages',
                'http-method' => 'GET'
        ),
        array( // یک پیام تست ایجاد می‌کند.
                'regex' => '#^/system/test$#',
                'model' => 'Inbox_Views_System',
                'method' => 'testMessage',
                'http-method' => array(
                        'GET',
                        'POST'
                )
        ),
        /*
         * Inbox
         */
		array(
                'regex' => '#^/inbox$#',
                'model' => 'User_Views_Authentication',
                'method' => 'login'
        )
);