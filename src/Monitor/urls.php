<?php
return array(
        array(
                'regex' => '#^/find$#',
                'model' => 'Monitor_Views',
                'method' => 'find',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/(?P<id>\d+)$#',
                'model' => 'Monitor_Views',
                'method' => 'get',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/(?P<id>\d+)$#',
                'model' => 'Monitor_Views',
                'method' => 'delete',
                'http-method' => 'DELETE',
                'precond' => array(
                        'SaaS_Precondition::tenantOwner'
                )
        ),
        array(
                'regex' => '#^/(?P<id>\d+)$#',
                'model' => 'Monitor_Views',
                'method' => 'update',
                'http-method' => 'POST',
                'precond' => array(
                        'SaaS_Precondition::tenantMember'
                )
        ),
        array(
                'regex' => '#^/(?P<monitor>[^/]+)/(?P<property>[^/]+)$#',
                'model' => 'Monitor_Views',
                'method' => 'call',
                'http-method' => 'GET',
                'precond' => array(
                        'SaaS_Precondition::tenantMember'
                )
        )
);