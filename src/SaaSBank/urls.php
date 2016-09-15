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
        
        // اطلاعات بسته
        array(
                'regex' => '#^/$#',
                'model' => 'SaaSBank_Views_Main',
                'method' => 'modeul',
                'http-method' => array(
                        'GET'
                )
        ),
        // متورهای پرداخت
        array(
                'regex' => '#^/engine/find$#',
                'model' => 'SaaSBank_Views_Engine',
                'method' => 'find',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/engine/(?P<type>.+)$#',
                'model' => 'SaaSBank_Views_Engine',
                'method' => 'get',
                'http-method' => array(
                        'GET'
                )
        ),
        
        // پشتوانه‌ها
        array(
                'regex' => '#^/backend/find$#',
                'model' => 'SaaSBank_Views_Backend',
                'method' => 'find',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/backend/new$#',
                'model' => 'SaaSBank_Views_Backend',
                'method' => 'createParameter',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/backend/new$#',
                'model' => 'SaaSBank_Views_Backend',
                'method' => 'create',
                'http-method' => array(
                        'POST'
                )
        ),
        array(
                'regex' => '#^/backend/(?P<id>\d+)$#',
                'model' => 'SaaSBank_Views_Backend',
                'method' => 'get',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/backend/(?P<id>\d+)$#',
                'model' => 'SaaSBank_Views_Backend',
                'method' => 'update',
                'http-method' => array(
                        'POST'
                )
        ),
        array(
                'regex' => '#^/backend/(?P<id>\d+)$#',
                'model' => 'SaaSBank_Views_Backend',
                'method' => 'delete',
                'http-method' => array(
                        'DELETE'
                )
        ),
        
        /*
         * Receipt
         */
        array(
                'regex' => '#^/receipt/find$#',
                'model' => 'SaaSBank_Views_Receipt',
                'method' => 'find',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/receipt/new$#',
                'model' => 'SaaSBank_Views_Receipt',
                'method' => 'create',
                'http-method' => array(
                        'POST'
                )
        ),
        array(
                'regex' => '#^/receipt/(?P<id>\d+)$#',
                'model' => 'SaaSBank_Views_Receipt',
                'method' => 'get',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/receipt/(?P<secure_id>.+)$#',
                'model' => 'SaaSBank_Views_Receipt',
                'method' => 'getBySecureId',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/receipt/(?P<id>\d+)$#',
                'model' => 'SaaSBank_Views_Receipt',
                'method' => 'update',
                'http-method' => array(
                        'POST'
                )
        ),
        array(
                'regex' => '#^/receipt/(?P<secure_id>.+)$#',
                'model' => 'SaaSBank_Views_Receipt',
                'method' => 'updateBySecureId',
                'http-method' => array(
                        'POST'
                )
        ),
        array(
                'regex' => '#^/receipt/(?P<id>.+)$#',
                'model' => 'SaaSBank_Views_Receipt',
                'method' => 'delete',
                'http-method' => array(
                        'DELETE'
                )
        )
);