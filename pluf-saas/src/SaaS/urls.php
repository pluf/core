<?php
return array(
        /**
         * *****************************************************************
         * Application
         * *****************************************************************
         */
        array( // ایجاد
                'regex' => '#^/app/create$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'create',
                'http-method' => array(
                        'POST'
                ),
                'precond' => array(
                        'SaaS_Precondition::baseAccess',
                        'Pluf_Precondition::loginRequired'
                )
        ),
        array( // گرفتن
                'regex' => '#^/app/(\d+)$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'get',
                'saas' => array(
                        'match-application' => 1
                ),
                'http-method' => array(
                        'GET'
                ),
                'precond' => array(
                        'SaaS_Precondition::baseAccess'
                )
        ),
        array( // گرفتن جاری
                'regex' => '#^/app$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'get',
                'http-method' => array(
                        'GET'
                ),
                'precond' => array(
                        'SaaS_Precondition::baseAccess'
                )
        ),
        array( // به روز کردن
                'regex' => '#^/app/(\d+)$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'update',
                'http-method' => array(
                        'POST'
                ),
                'saas' => array(
                        'match-application' => 1
                ),
                'precond' => array(
                        'SaaS_Precondition::baseAccess',
                        'SaaS_Precondition::applicationOwner'
                )
        ),
        
        array( // فهرست
                'regex' => '#^/app/list$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'applications',
                'http-method' => array(
                        'GET'
                )
        ),
        
        array( // فهرستی از تمام نرم‌افزارهایی که با کاربر در رابطه است
                'regex' => '#^/app/user/list$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'userApplications'
        ),
        array( // فهرستی از تمام اعضا
                'regex' => '#^/app/(\d+)/member/list$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'members',
                'saas' => array(
                        'match-application' => 1
                )
        ),
        /*
         * SAP of applications
         */
        array(
                'regex' => '#^/app/(\d+)/sap/list$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'saps',
                'http-method' => 'GET',
                'saas' => array(
                        'match-application' => 1
                )
        ),
        /*
         *  تنظیم‌ها 
         */
        array( // فهرستی از تنظیم‌ها
                'regex' => '#^/app/(\d+)/config/list$#',
                'model' => 'SaaS_Views_Configuration',
                'method' => 'configurations',
                'http-method' => 'GET',
                'saas' => array(
                        'match-application' => 1
                )
        ),
        array( // دسترسی به تنظیم‌ها با شناسه
                'regex' => '#^/app/(\d+)/config/(\d+)$#',
                'model' => 'SaaS_Views_Configuration',
                'method' => 'get',
                'http-method' => 'GET',
                'saas' => array(
                        'match-application' => 1
                ),
                'freemium' => array(
                        'level' => Pluf::f('saas_freemium_full', 5)
                )
        ),
        array( // دسترسی به تنظیم‌ها با نام
                'regex' => '#^/app/(\d+)/configByName/(.+)$#',
                'model' => 'SaaS_Views_Configuration',
                'method' => 'getByName',
                'http-method' => 'GET',
                'saas' => array(
                        'match-application' => 1
                )
        ),
        array( // ایجاد یک تنظیم جدید
                'regex' => '#^/app/(\d+)/config/create$#',
                'model' => 'SaaS_Views_Configuration',
                'method' => 'create',
                'http-method' => 'POST',
                'saas' => array(
                        'match-application' => 1
                ),
                'freemium' => array(
                        'level' => Pluf::f('saas_freemium_full', 5)
                )
        ),
        /*
         * کتابخانه‌ها
         */
        array(
                'regex' => '#^/lib/create$#',
                'model' => 'SaaS_Views_Lib',
                'method' => 'create',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::staffRequired',
                        'SaaS_Precondition::userCanCreateLib'
                )
        ),
        array(
                'regex' => '#^/lib/list$#',
                'model' => 'SaaS_Views_Lib',
                'method' => 'find',
                'http-method' => 'GET',
                'precond' => array(
                        'Pluf_Precondition::staffRequired',
                        'SaaS_Precondition::userCanAccessLibs'
                )
        ),
        array(
                'regex' => '#^/lib/(\d+)$#',
                'model' => 'SaaS_Views_Lib',
                'method' => 'get',
                'http-method' => 'GET',
                'precond' => array(
                        'Pluf_Precondition::staffRequired'
                )
        ),
        array(
                'regex' => '#^/lib/(\d+)$#',
                'model' => 'SaaS_Views_Lib',
                'method' => 'update',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::staffRequired'
                )
        ),
        array(
                'regex' => '#^/lib/(\d+)$#',
                'model' => 'SaaS_Views_Lib',
                'method' => 'delete',
                'http-method' => 'DELETE',
                'precond' => array(
                        'Pluf_Precondition::staffRequired'
                )
        )
);