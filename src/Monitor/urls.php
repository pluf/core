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
                        'Pluf_Precondition::ownerRequired'
                )
        ),
        array(
                'regex' => '#^/(?P<id>\d+)$#',
                'model' => 'Monitor_Views',
                'method' => 'update',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::memberRequired'
                )
        ),
        array(
                'regex' => '#^/(?P<monitor>[^/]+)/(?P<property>[^/]+)$#',
                'model' => 'Monitor_Views',
                'method' => 'call',
                'http-method' => 'GET',
                'precond' => array(
                        'Pluf_Precondition::memberRequired'
                )
        )
);