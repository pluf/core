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
                'model' => 'SaaS_Views',
                'method' => 'findObject',
                'http-method' => 'GET',
                'precond' => array(
                        'Pluf_Precondition::adminRequired'
                ),
                'params' => array(
                        'model' => 'SaaS_Configuration',
                        'sql' => new Pluf_SQL('type=0'),
                        'listFilters' => array(
                                'id',
                                'key',
                                'value',
                                'description'
                        ),
                        'listDisplay' => array(
                                'key' => 'key',
                                'description' => 'description'
                        ),
                        'searchFields' => array(
                                'title',
                                'symbol',
                                'description'
                        ),
                        'sortFields' => array(
                                'title',
                                'symbol',
                                'description',
                                'creation_date',
                                'modif_dtime'
                        )
                )
        ),
        array(
                'regex' => '#^/(?P<key>[^/]+)$#',
                'model' => 'Config_Views',
                'method' => 'get',
                'http-method' => 'GET',
                'precond' => array(
                        'Pluf_Precondition::adminRequired'
                )
        ),
        array(
                'regex' => '#^/(?P<key>[^/]+)$#',
                'model' => 'Config_Views',
                'method' => 'delete',
                'http-method' => 'DELETE',
                'precond' => array(
                        'Pluf_Precondition::adminRequired'
                )
        ),
        array(
                'regex' => '#^/(?P<key>[^/]+)$#',
                'model' => 'Config_Views',
                'method' => 'update',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::adminRequired'
                )
        )
);
