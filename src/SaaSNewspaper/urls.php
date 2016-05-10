<?php
return array(
        /*
         * کار با دنبال‌کننده‌ها
         */
        array(
        'regex' => '#^/follower/new$#',
        'model' => 'SaaSNewspaper_Views_Follower',
        'method' => 'create',
        'http-method' => array(
            'POST'
        )
    ),
    array(
        'regex' => '#^/follower/find$#',
        'model' => 'SaaSNewspaper_Views_Follower',
        'method' => 'find',
        'http-method' => array(
            'GET'
        )
    ),
    array(
        'regex' => '#^/follower/(\d+)$#',
        'model' => 'SaaSNewspaper_Views_Follower',
        'method' => 'get',
        'http-method' => 'GET',
        'precond' => array(
            'Pluf_Precondition::loginRequired',
            'SaaS_Precondition::tenantOwner'
        )
    ),
    array(
        'regex' => '#^/follower/(\d+)$#',
        'model' => 'SaaSNewspaper_Views_Follower',
        'method' => 'update',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::loginRequired',
            'SaaS_Precondition::tenantOwner'
        )
    ),
    array(
        'regex' => '#^/follower/(\d+)$#',
        'model' => 'SaaSNewspaper_Views_Follower',
        'method' => 'delete',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::loginRequired',
            'SaaS_Precondition::tenantOwner'
        )
    )
);