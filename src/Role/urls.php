<?php
return array(
    /*
     * Roles
     */
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
        'regex' => '#^/find$#',
        'model' => 'Role_Views',
        'method' => 'find',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/(?P<id>\d+)$#',
        'model' => 'Role_Views',
        'method' => 'get',
        'http-method' => 'GET'
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
     * Users of role
     */
    array(
        'regex' => '#^/(?P<id>\d+)/user$#',
        'model' => 'Role_Views_User',
        'method' => 'add',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::loginRequired'
        )
    ),
    array(
        'regex' => '#^/(?P<id>\d+)/user/find$#',
        'model' => 'Role_Views_User',
        'method' => 'find',
        'http-method' => 'GET',
        'precond' => array()
    ),
    array(
        'regex' => '#^/(?P<id>\d+)/user/(?P<userId>\d+)$#',
        'model' => 'Role_Views_User',
        'method' => 'get',
        'http-method' => 'GET',
        'precond' => array()
    ),
    array(
        'regex' => '#^/(?P<id>\d+)/user/(?P<userId>\d+)$#',
        'model' => 'Role_Views_User',
        'method' => 'delete',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::loginRequired'
        )
    ),
    /*
     * Groups of role
     */
    array(
        'regex' => '#^/(?P<id>\d+)/group$#',
        'model' => 'Role_Views_Group',
        'method' => 'add',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::adminRequired'
        )
    ),
    array(
        'regex' => '#^/(?P<id>\d+)/group/find$#',
        'model' => 'Role_Views_Group',
        'method' => 'find',
        'http-method' => 'GET',
        'precond' => array()
    ),
    array(
        'regex' => '#^/(?P<id>\d+)/group/(?P<groupId>\d+)$#',
        'model' => 'Role_Views_Group',
        'method' => 'get',
        'http-method' => 'GET',
        'precond' => array()
    ),
    array(
        'regex' => '#^/(?P<id>\d+)/group/(?P<groupId>\d+)$#',
        'model' => 'Role_Views_Group',
        'method' => 'delete',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::adminRequired'
        )
    )
)
;
