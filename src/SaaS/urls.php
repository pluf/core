<?php
return array(
        /**
         * *****************************************************************
         * Tenants
         * *****************************************************************
         */
        array( // فهرست
                'regex' => '#^/find$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'applications',
                'http-method' => array(
                        'GET'
                )
        ),
        array( // فهرست
                'regex' => '#^/userFind$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'userApplications',
                'http-method' => array(
                        'GET'
                )
        ),
        array( // ایجاد
                'regex' => '#^/create$#',
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
                'regex' => '#^/(\d+)$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'get',
                'http-method' => array(
                        'GET'
                ),
                'precond' => array()
        ),
        array( // به روز کردن
                'regex' => '#^/(\d+)$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'update',
                'http-method' => array(
                        'POST'
                ),
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        
        /**
         * *****************************************************************
         * Tenants (Current)
         * *****************************************************************
         */
        array( // گرفتن جاری
                'regex' => '#^/curent$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'getCurrent',
                'http-method' => array(
                        'GET'
                ),
                'precond' => array()
        ),
        array( // به روز کردن جاری
                'regex' => '#^/curent$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'updateCurrent',
                'http-method' => array(
                        'POST'
                ),
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        

        /**
         * *****************************************************************
         * Tenants (Owner)
         * *****************************************************************
         */
        array( // فهرستی از تمام اعضا
                'regex' => '#^/owners$#',
                'model' => 'SaaS_Views_ApplicationMember',
                'method' => 'owners',
                'http-method' => array(
                        'GET'
                ),
        ),
        array( //
                'regex' => '#^/owner/(\d+)$#',
                'model' => 'SaaS_Views_ApplicationMember',
                'method' => 'ownerAdd',
                'http-method' => array(
                        'POST'
                ),
        ),
        array( //
                'regex' => '#^/owner/(\d+)$#',
                'model' => 'SaaS_Views_ApplicationMember',
                'method' => 'ownerRemove',
                'http-method' => array(
                        'DELETE'
                ),
        ),
        
        

        /**
         * *****************************************************************
         * Tenants (members)
         * *****************************************************************
         */
        array( // فهرستی از تمام اعضا
                'regex' => '#^/members$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'members',
                'http-method' => array(
                        'GET'
                ),
        ),
        array( // 
                'regex' => '#^/member/(\d+)$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'members',
                'http-method' => array(
                        'POST'
                ),
        ),
        array( // 
                'regex' => '#^/member/(\d+)$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'members',
                'http-method' => array(
                        'DELETE'
                ),
        ),

        /**
         * *****************************************************************
         * Tenants (authorizeds)
         * *****************************************************************
         */
        array( // فهرستی از تمام اعضا
                'regex' => '#^/authorizeds$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'members',
                'http-method' => array(
                        'GET'
                ),
        ),
        array( //
                'regex' => '#^/authorized/(\d+)$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'members',
                'http-method' => array(
                        'POST'
                ),
        ),
        array( //
                'regex' => '#^/authorized/(\d+)$#',
                'model' => 'SaaS_Views_Application',
                'method' => 'members',
                'http-method' => array(
                        'DELETE'
                ),
        ),
        
        

//         /**
//          * *****************************************************************
//          * Application resource
//          * *****************************************************************
//          */
//         array(
//                 'regex' => '#^/app/(\d+)/resource/create$#',
//                 'model' => 'SaaS_Views_ApplicationResource',
//                 'method' => 'create',
//                 'http-method' => array(
//                         'POST'
//                 ),
//                 'freemium' => array(
//                         'level' => Pluf::f('saas_freemium_full', 5)
//                 )
//         ),
//         array(
//                 'regex' => '#^/app/(\d+)/resource/find$#',
//                 'model' => 'SaaS_Views_ApplicationResource',
//                 'method' => 'find',
//                 'http-method' => array(
//                         'GET'
//                 )
//         ),
//         array(
//                 'regex' => '#^/app/(\d+)/resource/(\d+)$#',
//                 'model' => 'SaaS_Views_ApplicationResource',
//                 'method' => 'get',
//                 'http-method' => array(
//                         'GET'
//                 )
//         ),
//         array(
//                 'regex' => '#^/app/(\d+)/resource/(\d+)$#',
//                 'model' => 'SaaS_Views_ApplicationResource',
//                 'method' => 'delete',
//                 'http-method' => array(
//                         'DELETE'
//                 )
//         ),
//         array(
//                 'regex' => '#^/app/(\d+)/resource/(\d+)$#',
//                 'model' => 'SaaS_Views_ApplicationResource',
//                 'method' => 'update',
//                 'http-method' => array(
//                         'POST'
//                 )
//         ),
//         array(
//                 'regex' => '#^/app/(\d+)/resource/(\d+)/download$#',
//                 'model' => 'SaaS_Views_ApplicationResource',
//                 'method' => 'download',
//                 'http-method' => array(
//                         'GET'
//                 )
//         ),

        /**
         * **************************************************************************
         * Libs
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
                'regex' => '#^/lib/find$#',
                'model' => 'SaaS_Views_Lib',
                'method' => 'find',
                'http-method' => 'GET',
                'precond' => array(
                        'Pluf_Precondition::staffRequired',
                        'SaaS_Precondition::userCanAccessLibs'
                )
        ),
        array(
                'regex' => '#^/lib/(\d+)/download$#',
                'model' => 'SaaS_Views_Lib',
                'method' => 'download',
                'http-method' => 'GET',
                'precond' => array()
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
        ),
        
//         /**
//          * *****************************************************************
//          * SPA
//          * *****************************************************************
//          */
//         array(
//                 'regex' => '#^/spa/(\d+)$#',
//                 'model' => 'SaaS_Views_SPA',
//                 'method' => 'getById'
//         ),
//         array(
//                 'regex' => '#^/spa/(\d+)/detail$#',
//                 'model' => 'SaaS_Views_SPA',
//                 'method' => 'detail'
//         ),
//         array(
//                 'regex' => '#^/spa/([^/]+)$#',
//                 'model' => 'SaaS_Views_SPA',
//                 'method' => 'getByName'
//         ),
//         /*
//          * SPA of applications
//          */
//         array(
//                 'regex' => '#^/spa/find$#',
//                 'model' => 'SaaS_Views_Application',
//                 'method' => 'saps',
//                 'http-method' => 'GET'
//         ),

        //         /*
        //          *  تنظیم‌ها
        //          */
        //         array( // فهرستی از تنظیم‌ها
        //                 'regex' => '#^/app/(\d+)/config/list$#',
        //                 'model' => 'SaaS_Views_Configuration',
        //                 'method' => 'configurations',
        //                 'http-method' => 'GET'
        //         ),
        //         array( // دسترسی به تنظیم‌ها با شناسه
        //                 'regex' => '#^/app/(\d+)/config/(\d+)$#',
        //                 'model' => 'SaaS_Views_Configuration',
        //                 'method' => 'get',
        //                 'http-method' => 'GET',
        //                 'saas' => array(
        //                         'match-application' => 1
        //                 ),
        //                 'freemium' => array(
        //                         'level' => Pluf::f('saas_freemium_full', 5)
        //                 )
        //         ),
        //         array( // دسترسی به تنظیم‌ها با نام
        //                 'regex' => '#^/app/(\d+)/configByName/(.+)$#',
        //                 'model' => 'SaaS_Views_Configuration',
        //                 'method' => 'getByName',
        //                 'http-method' => 'GET'
        //         ),
        //         array( // ایجاد یک تنظیم جدید
        //                 'regex' => '#^/app/(\d+)/config/create$#',
        //                 'model' => 'SaaS_Views_Configuration',
        //                 'method' => 'create',
        //                 'http-method' => 'POST',
        //                 'freemium' => array(
        //                         'level' => Pluf::f('saas_freemium_full', 5)
        //                 )
        //         ),
);