<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
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
            'Pluf_Precondition::loginRequired',
            'SaaS_Precondition::tenantOwner'
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
            'Pluf_Precondition::loginRequired',
            'SaaS_Precondition::tenantOwner'
        )
    ),
    array(
        'regex' => '#^/(?P<id>\d+)$#',
        'model' => 'Group_Views',
        'method' => 'delete',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::loginRequired',
            'SaaS_Precondition::tenantOwner'
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
            'Pluf_Precondition::loginRequired',
            'SaaS_Precondition::tenantOwner'
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
            'Pluf_Precondition::loginRequired',
            'SaaS_Precondition::tenantOwner'
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
            'Pluf_Precondition::loginRequired',
            'SaaS_Precondition::tenantOwner'
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
