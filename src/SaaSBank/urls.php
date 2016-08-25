<?php
return array(
        
        // اطلاعات بسته
        array(
                'regex' => '#^/$#',
                'model' => 'SaaSBank_Views_Main',
                'method' => 'modeul',
                'http-method' => array(
                        'GET'
                )
        ),
        // متورهای پرداخت
        array(
                'regex' => '#^/engine/find$#',
                'model' => 'SaaSBank_Views_Engine',
                'method' => 'find',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/engine/(?P<type>.+)$#',
                'model' => 'SaaSBank_Views_Engine',
                'method' => 'get',
                'http-method' => array(
                        'GET'
                )
        ),
        
        // پشتوانه‌ها
        array(
                'regex' => '#^/backend/find$#',
                'model' => 'SaaSBank_Views_Backend',
                'method' => 'find',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/backend/new$#',
                'model' => 'SaaSBank_Views_Backend',
                'method' => 'createParameter',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/backend/new$#',
                'model' => 'SaaSBank_Views_Backend',
                'method' => 'create',
                'http-method' => array(
                        'POST'
                )
        ),
        array(
                'regex' => '#^/backend/(?P<id>\d+)$#',
                'model' => 'SaaSBank_Views_Backend',
                'method' => 'get',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/backend/(?P<id>\d+)$#',
                'model' => 'SaaSBank_Views_Backend',
                'method' => 'update',
                'http-method' => array(
                        'POST'
                )
        ),
        array(
                'regex' => '#^/backend/(?P<id>\d+)$#',
                'model' => 'SaaSBank_Views_Backend',
                'method' => 'delete',
                'http-method' => array(
                        'DELETE'
                )
        ),
        
);