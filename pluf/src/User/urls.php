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
        /**
         * مدیریت داده‌های شخصی
         *
         * دسته‌ای از فراخوانی‌ها برای مدیریت داده‌های کاربر جاری در نظر گرفته
         * شده است. این فراخوانی‌ها
         * برای کاربران نهایی بسیار پرکاربرد هستند.
         */
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
        array( // اطلاعات کاربر را به روز می‌کند
                'regex' => '#^/account/change_user_email/(.+)$#',
                'model' => 'User_Views_User',
                'method' => 'changeEmail',
                'http-method' => array(
                        'POST',
                        'GET'
                )
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
         * 
         * این دسته از فراخوانی‌ها برای مدیریت کاربران و پروفایل‌های آنها در نظر گرفته شده است. تنها کاربر ریشه است که
         * می‌تواند از این فراخوانی‌ها استفاده کند و داده‌های کاربران را دستکاری کند.
         */
        array( // فهرست کاربران
                'regex' => '#^/list$#',
                'model' => 'User_Views_UserAdmin',
                'method' => 'users',
                'http-method' => 'GET'
        ),
        array( // گرفتن اطلاعات کاربر
                'regex' => '#^/(\d+)$#',
                'model' => 'User_Views_UserAdmin',
                'method' => 'getUser',
                'http-method' => 'GET'
        ),
        array( // به روز کردن اطلاعات کاربر
                'regex' => '#^/(\d+)$#',
                'model' => 'User_Views_UserAdmin',
                'method' => 'updateUser',
                'http-method' => 'POST'
        ),
        array( // گرفتن پروفایل کاربر
                'regex' => '#^/(\d+)/profile$#',
                'model' => 'User_Views_ProfileAdmin',
                'method' => 'getProfile',
                'http-method' => 'GET'
        ),
        array( // به روز کردن پروفایل کاربر
                'regex' => '#^/(\d+)/profile$#',
                'model' => 'User_Views_ProfileAdmin',
                'method' => 'updateProfile',
                'http-method' => 'POST'
        )
);