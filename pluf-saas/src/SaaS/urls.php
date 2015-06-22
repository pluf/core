<?php
return array(
        array( // اطلاعات نرم‌افزار جاری
                'regex' => '#^/app$#',
                'base' => $base,
                'model' => 'SaaS_Views_Application',
                'method' => 'currentApplication'
        ),
        array( // فهرستی از تمام نرم‌افزارهای موجود
                'regex' => '#^/app/list$#',
                'base' => $base,
                'model' => 'SaaS_Views_Application',
                'method' => 'applications'
        ),
        array( // فهرستی از تمام اعضا
                'regex' => '#^/app/(\d+)/member/list$#',
                'base' => $base,
                'model' => 'SaaS_Views_Application',
                'method' => 'members'
        ),
        array( // فهرستی از تنظیم‌ها
                'regex' => '#^/app/(\d+)/config/list$#',
                'base' => $base,
                'model' => 'SaaS_Views_Configuration',
                'method' => 'configurations'
        ),
        array( // ایجاد تنظیم‌ها
                'regex' => '#^/app/(\d+)/config/create$#',
                'base' => $base,
                'model' => 'SaaS_Views_Configuration',
                'method' => 'create'
        )
);