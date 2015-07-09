<?php
return array(
        array( // ورود کاربر به سیستم است
                'regex' => '#^/login$#',
                'base' => $base,
                'model' => 'User_Views_Authentication',
                'method' => 'login',
                'http-method' => 'POST'
        ),
        array( // خروج کاربران از سیستم
                'regex' => '#^/logout$#',
                'base' => $base,
                'model' => 'User_Views_Authentication',
                'method' => 'logout',
                'http-method' => array(
                        'POST',
                        'GET'
                )
        ),
        array( // اطلاعات کاربری را در اختیار شما قرار می‌دهد
                'regex' => '#^/account$#',
                'base' => $base,
                'model' => 'User_Views_User',
                'method' => 'account',
                'http-method' => 'GET'
        ),
        array( // اطلاعات کاربر را به روز می‌کند
                'regex' => '#^/account$#',
                'base' => $base,
                'model' => 'User_Views_User',
                'method' => 'update',
                'http-method' => 'POST'
        ),
        array(
                'regex' => '#^/signup$#',
                'base' => $base,
                'model' => 'User_Views_User',
                'method' => 'signup'
        ),
        array(
                'regex' => '#^/profile$#',
                'base' => $base,
                'model' => 'User_Views_Profile',
                'method' => 'profile'
        )
);