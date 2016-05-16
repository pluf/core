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
        'regex' => '#^/users$#',
        'model' => 'SaaS_Views_Application',
        'method' => 'userApplications',
        'http-method' => array(
            'GET'
        )
    ),
    array( // ایجاد
        'regex' => '#^/new$#',
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

    /**
     * *****************************************************************
     * Tenants
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
     * Tenants (owner, member, authorized)
     * *****************************************************************
     */
    array( // فهرستی از تمام اعضا
        'regex' => '#^/(owner|member|authorize)/find$#',
        'model' => 'SaaS_Views_ApplicationMember',
        'method' => 'find',
        'http-method' => array(
            'GET'
        )
    ),
    array( //
        'regex' => '#^/(owner|member|authorize)/(\d+)$#',
        'model' => 'SaaS_Views_ApplicationMember',
        'method' => 'memberAdd',
        'http-method' => array(
            'POST'
        ),
        'precond' => array(
            'SaaS_Precondition::applicationOwner'
        )
    ),
    array( //
        'regex' => '#^/(owner|member|authorize)/(\d+)$#',
        'model' => 'SaaS_Views_ApplicationMember',
        'method' => 'memberRemove',
        'http-method' => array(
            'DELETE'
        ),
        'precond' => array(
            'SaaS_Precondition::applicationOwner'
        )
    ),


    /**
     * *****************************************************************
     * Tenant (SPA)
     * *****************************************************************
     */
    array(
        'regex' => '#^/(\d+)/spa/default$#',
        'model' => 'SaaS_Views_ApplicationSpa',
        'method' => 'getDefaultSpa',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/(\d+)/spa/(\d+)/default$#',
        'model' => 'SaaS_Views_ApplicationSpa',
        'method' => 'setDefaultSpa',
        'http-method' => 'POST'
    ),
    array(
        'regex' => '#^/(\d+)/spa/(\d+)$#',
        'model' => 'SaaS_Views_ApplicationSpa',
        'method' => 'getById',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/(\d+)/spa/(\d+)$#',
        'model' => 'SaaS_Views_ApplicationSpa',
        'method' => 'update',
        'http-method' => 'POST',
        'precond' => array(
            'SaaS_Precondition::applicationOwner'
        )
    ),
    array(
        'regex' => '#^/(\d+)/spa/(\d+)$#',
        'model' => 'SaaS_Views_ApplicationSpa',
        'method' => 'removePermissions',
        'http-method' => 'DELETE',
        'precond' => array(
            'SaaS_Precondition::applicationOwner'
        )
    ),
    array(
        'regex' => '#^/(\d+)/spa/(\d+)/detail$#',
        'model' => 'SaaS_Views_ApplicationSpa',
        'method' => 'detail',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/(\d+)/spa/([^/]+)$#',
        'model' => 'SaaS_Views_ApplicationSpa',
        'method' => 'getByName',
        'http-method' => 'GET'
    ),
    /**
     * *****************************************************************
     * Application resource
     * *****************************************************************
     */
    array(
        'regex' => '#^/resource/new$#',
        'model' => 'SaaS_Views_ApplicationResource',
        'method' => 'create',
        'http-method' => array(
            'POST'
        ),
        'freemium' => array(
            'level' => Pluf::f('saas_freemium_full', 5)
        )
    ),
    array(
        'regex' => '#^/resource/find$#',
        'model' => 'SaaS_Views_ApplicationResource',
        'method' => 'find',
        'http-method' => array(
            'GET'
        )
    ),
    array(
        'regex' => '#^/resource/(\d+)$#',
        'model' => 'SaaS_Views_ApplicationResource',
        'method' => 'get',
        'http-method' => array(
            'GET'
        )
    ),
    array(
        'regex' => '#^/resource/(\d+)$#',
        'model' => 'SaaS_Views_ApplicationResource',
        'method' => 'delete',
        'http-method' => array(
            'DELETE'
        )
    ),
    array(
        'regex' => '#^/resource/(\d+)$#',
        'model' => 'SaaS_Views_ApplicationResource',
        'method' => 'update',
        'http-method' => array(
            'POST'
        )
    ),
    array(
        'regex' => '#^/resource/(\d+)/download$#',
        'model' => 'SaaS_Views_ApplicationResource',
        'method' => 'download',
        'http-method' => array(
            'GET'
        )
    ),
    
    /**
     * **************************************************************************
     * Libs
     * **************************************************************************
     */
    array(
        'regex' => '#^/lib/new$#',
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
    
    /**
     * *****************************************************************
     * Tenant (Configuration)
     * *****************************************************************
     */
    array( // فهرستی از تنظیم‌ها
        'regex' => '#^/config/find$#',
        'model' => 'SaaS_Views_Configuration',
        'method' => 'configurations',
        'http-method' => 'GET'
    ),
    array( // دسترسی به تنظیم‌ها با شناسه
        'regex' => '#^/config/(\d+)$#',
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
        'regex' => '#^/config/byName/(.+)$#',
        'model' => 'SaaS_Views_Configuration',
        'method' => 'getByName',
        'http-method' => 'GET'
    ),
    array( // ایجاد یک تنظیم جدید
        'regex' => '#^/config/new$#',
        'model' => 'SaaS_Views_Configuration',
        'method' => 'create',
        'http-method' => 'POST',
        'freemium' => array(
            'level' => Pluf::f('saas_freemium_full', 5)
        )
    ),
    
    /**
     * *****************************************************************
     * SPA
     * *****************************************************************
     */
    array(
        'regex' => '#^/spa/find$#',
        'model' => 'SaaS_Views_SPA',
        'method' => 'find',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/spa/new$#',
        'model' => 'SaaS_Views_SPA',
        'method' => 'create',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::staffRequired'
        )
    ),
    array(
        'regex' => '#^/spa/refresh$#',
        'model' => 'SaaS_Views_SPA',
        'method' => 'refreshAll',
        'http-method' => array( 'GET', 'POST'),
        'precond' => array(
            'Pluf_Precondition::staffRequired'
        )
    ),
    array(
        'regex' => '#^/spa/(\d+)$#',
        'model' => 'SaaS_Views_SPA',
        'method' => 'get',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/spa/(\d+)$#',
        'model' => 'SaaS_Views_SPA',
        'method' => 'delete',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::staffRequired'
        )
    ),
    array(
        'regex' => '#^/spa/(\d+)/refresh$#',
        'model' => 'SaaS_Views_SPA',
        'method' => 'refresh',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::staffRequired'
        )
    ),
    array(
        'regex' => '#^/spa/(\d+)/package$#',
        'model' => 'SaaS_Views_SPA',
        'method' => 'package',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/spa/(\d+)/appcache$#',
        'model' => 'SaaS_Views_SPA',
        'method' => 'appcache',
        'http-method' => 'GET'
    ),
    
);