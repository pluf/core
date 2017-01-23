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
    // url format for SPA main page or a Resource-File of default spa
    // main page of a spa:               /spa-name
    // resource-file from default spa:   /resource-file
    array(
        'regex' => '#^/(?P<path>[^/]+)$#',
        'model' => 'Spa_Views_Run',
        'method' => 'loadSpaOrResource'
    ),
    // url format for SPA resources:    
    // resource from default spa: /path/to/resource
    // resource form specified spa: /spa-name/path/to/resource
    array(
        'regex' => '#^/(?P<spa>[^/]+)/(?P<resource>.*)$#',
        'model' => 'Spa_Views_Run',
        'method' => 'getResource'
    ),    
    // main page of default SPA
    array(
        'regex' => '#^/$#',
        'model' => 'Pluf_Views_Run',
        'method' => 'defaultSpa'
    )
);