<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C] 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir]
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option] any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY, without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
return [
    [
        'regex' => '#^/$#',
        'priority' => 4,
        'model' => 'Todo_Views',
        'method' => 'main'
    ],

    [
        'regex' => '#^/install/$#',
        'priority' => 4,
        'model' => 'Todo_Views',
        'method' => 'install'
    ],

    [
        'regex' => '#^/uninstall/$#',
        'priority' => 4,
        'model' => 'Todo_Views',
        'method' => 'uninstall'
    ],

    [
        'regex' => '#^/item/(\d+]/$#',
        'priority' => 4,
        'model' => 'Todo_Views',
        'method' => 'viewItem'
    ],

    [
        'regex' => '#^/list/(\d+]/item/add/$#',
        'priority' => 4,
        'model' => 'Todo_Views',
        'method' => 'addItem'
    ],

    [
        'regex' => '#^/item/(\d+]/update/$#',
        'priority' => 4,
        'model' => 'Todo_Views',
        'method' => 'updateItem'
    ],

    [
        'regex' => '#^/item/(\d+]/delete/$#',
        'priority' => 4,
        'model' => 'Todo_Views',
        'method' => 'deleteItem'
    ],

    [
        'regex' => '#^/list/$#',
        'priority' => 4,
        'model' => 'Todo_Views',
        'method' => 'listLists'
    ],

    [
        'regex' => '#^/list/(\d+]/$#',
        'priority' => 4,
        'model' => 'Todo_Views',
        'method' => 'viewList'
    ],

    [
        'regex' => '#^/list/(\d+]/update/$#',
        'priority' => 4,
        'model' => 'Todo_Views',
        'method' => 'updateList'
    ],

    [
        'regex' => '#^/list/(\d+]/delete/$#',
        'priority' => 4,
        'model' => 'Todo_Views',
        'method' => 'deleteList'
    ],

    [
        'regex' => '#^/list/add/$#',
        'priority' => 4,
        'model' => 'Todo_Views',
        'method' => 'addList'
    ]
];
