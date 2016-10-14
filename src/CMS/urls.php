<?php
return array(
    array( // Content urls
        'regex' => '#^/new$#',
        'model' => 'CMS_Views',
        'method' => 'create',
        'http-method' => 'POST',
        'precond' => array(
            'SaaS_Precondition::tenantOwner'
        )
    ),
    array(
        'regex' => '#^/find$#',
        'model' => 'CMS_Views',
        'method' => 'find',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/(?P<id>\d+)$#',
        'model' => 'CMS_Views',
        'method' => 'get',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/(?P<id>\d+)$#',
        'model' => 'CMS_Views',
        'method' => 'delete',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::loginRequired',
            'SaaS_Precondition::tenantOwner'
        )
    ),
    array(
        'regex' => '#^/(?P<id>\d+)$#',
        'model' => 'CMS_Views',
        'method' => 'update',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::loginRequired',
            'SaaS_Precondition::tenantMember'
        )
    ),
    
    // Download
    array(
        'regex' => '#^/(?P<id>\d+)/download$#',
        'model' => 'CMS_Views',
        'method' => 'download',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/(?P<id>\d+)/download$#',
        'model' => 'CMS_Views',
        'method' => 'updateFile',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::loginRequired',
            'SaaS_Precondition::tenantMember'
        )
    ),
        
    /*
     * Named content
     */        
    array(
        'regex' => '#^/(?P<name>.+)$#',
        'model' => 'CMS_Views',
        'method' => 'get',
        'http-method' => 'GET'
    )
);