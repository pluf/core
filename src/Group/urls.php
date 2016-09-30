<?php
return array(
    /*
     * Groups
     */
    array(
        'regex' => '#^/new$#',
        'model' => 'Group_Views',
        'method' => 'create',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::adminRequired'
        )
    ),
    array(
        'regex' => '#^/find$#',
        'model' => 'Group_Views',
        'method' => 'find',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/(?P<id>\d+)$#',
        'model' => 'Group_Views',
        'method' => 'get',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/(?P<id>\d+)$#',
        'model' => 'Group_Views',
        'method' => 'update',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::loginRequired'
        )
    ),
    array(
        'regex' => '#^/(?P<id>\d+)$#',
        'model' => 'Group_Views',
        'method' => 'delete',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::loginRequired'
        )
    ),
    /*
     * Role
     */
    array(
        'regex' => '#^/(?P<groupId>\d+)/role$#',
        'model' => 'Group_Views_Role',
        'method' => 'add',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::loginRequired'
        )
    ),
    array(
        'regex' => '#^/(?P<groupId>\d+)/role/find$#',
        'model' => 'Group_Views_Role',
        'method' => 'find',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/(?P<groupId>\d+)/role/(?P<roleId>\d+)$#',
        'model' => 'Group_Views_Role',
        'method' => 'get',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/(?P<groupId>\d+)/role/(?P<roleId>\d+)$#',
        'model' => 'Group_Views_Role',
        'method' => 'delete',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::loginRequired'
        )
    ),
    /*
     * Users
     */
    array(
        'regex' => '#^/(?P<groupId>\d+)/user$#',
        'model' => 'Group_Views_User',
        'method' => 'add',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::loginRequired'
        )
    ),
    array(
        'regex' => '#^/(?P<groupId>\d+)/user/find$#',
        'model' => 'Group_Views_User',
        'method' => 'find',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/(?P<groupId>\d+)/user/(?P<userId>\d+)$#',
        'model' => 'Group_Views_User',
        'method' => 'get',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/(?P<groupId>\d+)/user/(?P<userId>\d+)$#',
        'model' => 'Group_Views_User',
        'method' => 'delete',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::loginRequired'
        )
    )
);
