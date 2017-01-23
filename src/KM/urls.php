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
         * کار با برچسب‌ها
         */
        array(
                'regex' => '#^/label/find$#',
                'model' => 'KM_Views_Label',
                'method' => 'find',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/label/new$#',
                'model' => 'KM_Views_Label',
                'method' => 'create',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
                'http-method' => array(
                        'POST'
                )
        ),
        array(
                'regex' => '#^/label/(\d+)$#',
                'model' => 'KM_Views_Label',
                'method' => 'get',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/label/(\d+)$#',
                'model' => 'KM_Views_Label',
                'method' => 'update',
                'precond' => array(
                        'Pluf_Precondition::ownerRequired'
                ),
                'http-method' => array(
                        'POST'
                )
        ),
        array(
                'regex' => '#^/label/(\d+)$#',
                'model' => 'KM_Views_Label',
                'method' => 'delete',
                'precond' => array(
                        'Pluf_Precondition::ownerRequired'
                ),
                'http-method' => array(
                        'DELETE'
                )
        ),
        /*
         * کار با دسته‌ها
         */
        array(
                'regex' => '#^/category/find$#',
                'model' => 'KM_Views_Category',
                'method' => 'find',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/category/new$#',
                'model' => 'KM_Views_Category',
                'method' => 'create',
                'precond' => array(
                        'Pluf_Precondition::ownerRequired'
                ),
                'http-method' => array(
                        'POST'
                )
        ),
        array(
                'regex' => '#^/category/(\d+)$#',
                'model' => 'KM_Views_Category',
                'method' => 'update',
                'precond' => array(
                        'Pluf_Precondition::adminRequired'
                ),
                'http-method' => array(
                        'POST'
                )
        ),
        array(
                'regex' => '#^/category/(\d+)$#',
                'model' => 'KM_Views_Category',
                'method' => 'delete',
                'precond' => array(
                        'Pluf_Precondition::adminRequired'
                ),
                'http-method' => array(
                        'DELETE'
                )
        ),
        array(
                'regex' => '#^/category/(\d+)$#',
                'model' => 'KM_Views_Category',
                'method' => 'get',
                'http-method' => array(
                        'GET'
                )
        )
);