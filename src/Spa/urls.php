<?php
return array(
        array(
                'regex' => '#^/find$#',
                'model' => 'Spa_Views',
                'method' => 'find',
                'http-method' => 'GET',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        array(
                'regex' => '#^/new$#',
                'model' => 'Spa_Views',
                'method' => 'create',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        array(
                'regex' => '#^/(?P<id>.+)$#',
                'model' => 'Spa_Views',
                'method' => 'get',
                'http-method' => 'GET',
        ),
        array(
                'regex' => '#^/(?P<id>.+)$#',
                'model' => 'Spa_Views',
                'method' => 'update',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        array(
                'regex' => '#^/(?P<id>.+)$#',
                'model' => 'Spa_Views',
                'method' => 'delete',
                'http-method' => 'DELETE',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
);
