<?php
return array(
        array( // اطلاعات نرم‌افزار مورد نظر
                'regex' => '#^/app/(\d+)$#',
                'base' => $base,
                'model' => 'SaaS_Views_Application',
                'method' => 'get',
                'http-method' => 'GET'
        ),
        array( // اطلاعات نرم‌افزار مورد نظر
                'regex' => '#^/app/(\d+)$#',
                'base' => $base,
                'model' => 'SaaS_Views_Application',
                'method' => 'update',
                'http-method' => 'POST'
        ),
        array( // فهرستی از تمام نرم‌افزارهای موجود
                'regex' => '#^/app/list$#',
                'base' => $base,
                'model' => 'SaaS_Views_Application',
                'method' => 'applications'
        ),
        /* دسترسی‌ها به نرم‌افزار */
        array( // اطلاعات نرم‌افزار جاری
                'regex' => '#^/app$#',
                'base' => $base,
                'model' => 'SaaS_Views_Application',
                'method' => 'currentApplication',
                'http-method' => 'GET'
        ),
        array( // ایجاد یک نرم‌افزار جدید
                'regex' => '#^/app$#',
                'base' => $base,
                'model' => 'SaaS_Views_Application',
                'method' => 'create',
                'http-method' => 'POST'
        ),
        array( // فهرستی از تمام اعضا
                'regex' => '#^/app/(\d+)/member/list$#',
                'base' => $base,
                'model' => 'SaaS_Views_Application',
                'method' => 'members'
        ),
        /* تنظیم‌ها */
        array( // فهرستی از تنظیم‌ها
                'regex' => '#^/app/(\d+)/config/list$#',
                'base' => $base,
                'model' => 'SaaS_Views_Configuration',
                'method' => 'configurations',
                'http-method' => 'GET'
        ),
        array( // دسترسی به تنظیم‌ها
                'regex' => '#^/app/(\d+)/config/(\d+)$#',
                'base' => $base,
                'model' => 'SaaS_Views_Configuration',
                'method' => 'get',
                'http-method' => 'GET'
        )
);