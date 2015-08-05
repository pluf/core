<?php
return array(
        /*
         * کاربردهای عمومی
         */
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
        array( // اطلاعات کاربری را در اختیار شما قرار می‌دهد
                'regex' => '#^/account$#',
                'model' => 'User_Views_User',
                'method' => 'account',
                'http-method' => 'GET'
        ),
        array( // اطلاعات کاربر را به روز می‌کند
                'regex' => '#^/account$#',
                'model' => 'User_Views_User',
                'method' => 'update',
                'http-method' => 'POST'
        ),
        array( // ثبت یک کاربر جدید
                'regex' => '#^/signup$#',
                'model' => 'User_Views_User',
                'method' => 'signup',
                'http-method' => 'POST'
        ),
        array( // دریافت پروفایل کاربر
                'regex' => '#^/profile$#',
                'model' => 'User_Views_Profile',
                'method' => 'get',
                'http-method' => 'GET'
        ),
        array( // به روز رسانی پروفایل کاربری
                'regex' => '#^/profile$#',
                'model' => 'User_Views_Profile',
                'method' => 'update',
                'http-method' => 'POST'
        ),
        /*
         * مدیریت سیستم
         */
        array( // ورود کاربر به سیستم است
                'regex' => '#^/user/list$#',
                'model' => 'User_Views_User',
                'method' => 'users',
                'http-method' => 'GET'
        )
);