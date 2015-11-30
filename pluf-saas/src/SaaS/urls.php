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
                        'Pluf_Precondition::loginRequired',
                        'SaaS_Precondition::userCanCreateApplication'
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
                'precond' => array()
        ),
        array( // گرفتن جاری
                'regex' => '#^/app$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'get',
                'http-method' => array(
                        'GET'
                ),
                'precond' => array()
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
                        'Pluf_Precondition::loginRequired'
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
        array( // فهرست بر اساس کاربران
                'regex' => '#^/app/userList$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'userApplications',
                'http-method' => array(
                        'GET'
                ),
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
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
        /**
         * *****************************************************************
         * Application resource
         * *****************************************************************
         */
        array(
                'regex' => '#^/app/(\d+)/resource/create$#',
                'model' => 'SaaS_Views_ApplicationResource',
                'method' => 'create',
                'http-method' => array(
                        'POST'
                ),
                'saas' => array(
                        'match-application' => 1
                ),
                'freemium' => array(
                        'level' => Pluf::f('saas_freemium_full', 5)
                )
        ),
        array(
                'regex' => '#^/app/(\d+)/resource/find$#',
                'model' => 'SaaS_Views_ApplicationResource',
                'method' => 'find',
                'http-method' => array(
                        'GET'
                ),
                'saas' => array(
                        'match-application' => 1
                )
        ),
        array(
                'regex' => '#^/app/(\d+)/resource/(\d+)$#',
                'model' => 'SaaS_Views_ApplicationResource',
                'method' => 'get',
                'http-method' => array(
                        'GET'
                ),
                'saas' => array(
                        'match-application' => 1
                )
        ),
        array(
                'regex' => '#^/app/(\d+)/resource/(\d+)$#',
                'model' => 'SaaS_Views_ApplicationResource',
                'method' => 'delete',
                'http-method' => array(
                        'DELETE'
                ),
                'saas' => array(
                        'match-application' => 1
                )
        ),
        array(
                'regex' => '#^/app/(\d+)/resource/(\d+)$#',
                'model' => 'SaaS_Views_ApplicationResource',
                'method' => 'update',
                'http-method' => array(
                        'POST'
                ),
                'saas' => array(
                        'match-application' => 1
                )
        ),
        array(
                'regex' => '#^/app/(\d+)/resource/(\d+)/download$#',
                'model' => 'SaaS_Views_ApplicationResource',
                'method' => 'download',
                'http-method' => array(
                        'GET'
                ),
                'saas' => array(
                        'match-application' => 1
                )
        ),
        /**
         * **************************************************************************
         * کتابخانه‌ها
         * **************************************************************************
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