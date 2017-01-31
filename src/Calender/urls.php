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
        // Calender
        array(
                'regex' => '#^/calenders/find$#',
                'model' => 'Pluf_Views',
                'method' => 'findObject',
                'http-method' => array(
                        'GET'
                ),
                'params' => array(
                        'model' => 'Calender_Calender',
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
                'regex' => '#^/calenders/new$#',
                'model' => 'Pluf_Views',
                'method' => 'createObject',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::ownerRequired'
                ),
                'params' => array(
                        'model' => 'Calender_Calender'
                )
        ),
        array(
                'regex' => '#^/calenders/(?P<modelId>\d+)$#',
                'model' => 'Pluf_Views',
                'method' => 'getObject',
                'http-method' => 'GET',
                'params' => array(
                        'model' => 'Calender_Calender'
                )
        ),
        array(
                'regex' => '#^/calenders/(?P<modelId>\d+)$#',
                'model' => 'Pluf_Views',
                'method' => 'deleteObject',
                'http-method' => 'DELETE',
                'precond' => array(
                        'Pluf_Precondition::ownerRequired'
                ),
                'params' => array(
                        'model' => 'Calender_Calender'
                )
        ),
        array(
                'regex' => '#^/calenders/(?P<modelId>\d+)$#',
                'model' => 'Pluf_Views',
                'method' => 'updateObject',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::ownerRequired'
                ),
                'params' => array(
                        'model' => 'Calender_Calender'
                )
        ),
        
        
        
        
        

        array(
                'regex' => '#^/calenders/(?P<calenderId>\d+)/events/new$#',
                'model' => 'Calender_Views_Event',
                'method' => 'create',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::ownerRequired'
                )
        ),
        array(
                'regex' => '#^/calenders/(?P<calenderId>\d+)/events/find$#',
                'model' => 'Calender_Views_Event',
                'method' => 'find',
                'http-method' => 'GET',
        ),
        array(
                'regex' => '#^/calenders/(?P<calenderId>\d+)/events/(?<eventId>\d+)$#',
                'model' => 'Calender_Views_Event',
                'method' => 'get',
                'http-method' => 'GET',
        ),
        array(
                'regex' => '#^/calenders/(?P<calenderId>\d+)/events/(?<eventId>\d+)$#',
                'model' => 'Calender_Views_Event',
                'method' => 'update',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::ownerRequired'
                )
        ),
        array(
                'regex' => '#^/calenders/(?P<calenderId>\d+)/events/(?<eventId>\d+)$#',
                'model' => 'Calender_Views_Event',
                'method' => 'delete',
                'http-method' => 'DELETE',
                'precond' => array(
                        'Pluf_Precondition::ownerRequired'
                )
        ),
);