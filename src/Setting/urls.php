<?php
return array(
        array(
                'regex' => '#^/find$#',
                'model' => 'Setting_Views',
                'method' => 'find',
                'http-method' => 'GET',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        array(
                'regex' => '#^/new$#',
                'model' => 'Setting_Views',
                'method' => 'create',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        array(
                'regex' => '#^/(?P<key>.+)$#',
                'model' => 'Setting_Views',
                'method' => 'get',
                'http-method' => 'GET',
        ),
        array(
                'regex' => '#^/(?P<key>.+)$#',
                'model' => 'Setting_Views',
                'method' => 'update',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        array(
                'regex' => '#^/(?P<key>.+)$#',
                'model' => 'Setting_Views',
                'method' => 'delete',
                'http-method' => 'DELETE',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
);
