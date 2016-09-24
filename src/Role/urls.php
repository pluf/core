<?php
return array(
        array(
                'regex' => '#^/find$#',
                'model' => 'Role_Views',
                'method' => 'find',
                'http-method' => 'GET',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        array(
                'regex' => '#^/new$#',
                'model' => 'Role_Views',
                'method' => 'create',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::adminRequired'
                )
        ),
        array(
                'regex' => '#^/(?P<id>\d+)$#',
                'model' => 'Role_Views',
                'method' => 'get',
                'http-method' => 'GET',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        array(
                'regex' => '#^/(?P<id>\d+)$#',
                'model' => 'Role_Views',
                'method' => 'update',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::adminRequired'
                )
        ),
        array(
                'regex' => '#^/(?P<id>\d+)$#',
                'model' => 'Role_Views',
                'method' => 'delete',
                'http-method' => 'DELETE',
                'precond' => array(
                        'Pluf_Precondition::adminRequired'
                )
        ),
        
        /*
         * 
         */
        array(
                'regex' => '#^/(?P<id>\d+)/user/find$#',
                'model' => 'Role_Views_User',
                'method' => 'find',
                'http-method' => 'GET',
                'precond' => array()
        ),
        array(
                'regex' => '#^/(?P<id>\d+)/user/new$#',
                'model' => 'Role_Views_User',
                'method' => 'create',
                'http-method' => 'POST',
                'precond' => array(
                        // Owner required
                )
        )
);
