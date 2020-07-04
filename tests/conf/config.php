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
return [

    // -------------------------------------------------------------------------
    // Develop
    // -------------------------------------------------------------------------
    'test' => true,
    'debug' => true,

    // -------------------------------------------------------------------------
    // Application
    // -------------------------------------------------------------------------
    'timezone' => 'Europe/Berlin',

    // Set the debug variable to true to force the recompilation of all
    // the templates each time during development
    'installed_apps' => array(
        'Pluf',
        'HelloWord',
        'NoteBook',
        'Smallest',
        'Relation'
    ),

    // Temporary folder where the script is writing the compiled templates,
    // cached data and other temporary resources.
    // It must be writeable by your webserver instance.
    // It is mandatory if you are using the template system.
    'tmp_folder' => '/tmp',

    // -------------------------------------------------------------------------
    // Database
    // -------------------------------------------------------------------------
    /*
     * Connection
     *
     * Connection allow a data layer to connect directly into a DB and store data
     * into.
     */
    'db_dsn' => 'sqlite::memory:',
    'db_user' => 'testpluf',
    'db_password' => 'testpluf',

    /*
     * Enables Pluf default database dumper
     */
    'db_dumper' => true,
    
    /*
     * Data Schema
     * 
     * Data Schema defines a mechanisme to map Object to Data base table and 
     * attributes.
     */
    'db_schema_engine' => 'sqlite',
    'db_schema_table_prefix' => rand() . '_',

    // -------------------------------------------------------------------------
    // Template manager and compiler
    // -------------------------------------------------------------------------

    // The folder in which the templates of the application are located.
    'templates_folder' => [
        dirname(__FILE__) . '/../templates'
    ],

    'template_tags' => [
        'mytag' => '\\Pluf\\Template\\Tag\\Mytag'
    ],

    'template_modifiers' => [],

    // -------------------------------------------------------------------------
    // Logger
    // -------------------------------------------------------------------------

    //
    // All possible levels
    //
    // - all
    // - debug
    // - info
    // - notice
    // - warning
    // - error
    // - critical
    // - alert
    // - emergency
    // - off
    //
    'log_level' => 'error',

    'log_delayed' => false,

    //
    // Formatter convert runtime date into a simple writable message.
    //
    'log_formater' => '\Pluf\LoggerFormatter\Plain',

    //
    // Logger appender get a message and append a log to outputs such as consoel
    // file remote server and etc.
    //
    'log_appender' => '\Pluf\LoggerAppender\Console',

    //
    // Remote
    //
    // 'log_appender_remote_server' => 'localhost',
    // 'log_appender_remote_path' => '/',
    // 'log_appender_remote_port' => 8000,
    // 'log_appender_remote_headers' => [],

    // -------------------------------------------------------------------------
    // cache
    // -------------------------------------------------------------------------
    'cache_engine' => 'array',

    'cache_arraya_timeout' => null,

    'cache_file_folder' => '/tmp',
    'cache_file_timeout' => null,

    'cache_apcu_keyprefix' => '/tmp',
    'cache_apcu_compress' => false,
    'cache_apcu_timeout' => null,

    'cache_memcached_timeout' => 300,
    'cache_memcached_keyprefix' => 'uniqueforapp',
    'cache_memcached_server' => 'localhost',
    'cache_memcached_port' => 11211,
    'cache_memcached_compress' => 0, // (or MEMCACHE_COMPRESSED)

    // -------------------------------------------------------------------------
    // View
    // -------------------------------------------------------------------------

    // Default mimetype of the document your application is sending.
    // It can be overwritten for a given response if needed.
    'mimetype' => 'text/html',
    'app_base' => '/testapp',
    'url_format' => 'simple'
    //
    // 'upload_max_size' => 1024,
    // 'upload_path' => '/tmp'
    //
    // List of Middleware
    //
    // Define list of middleware to apply on all requests. Add full path of
    // middleware class addres into the list to enable it. The order of the
    // list is important.
    //
    // 'middleware_classes' => array(),

    // 'view_api_prefix' => '',
    // 'view_api_base' => '',

    // -------------------------------------------------------------------------
    // Tenants
    // -------------------------------------------------------------------------

    //
    // Default tenant path
    //
    // If a user try to access an undefined tenant, it will be redirect to the
    // following URL. You can create a registration page and inform the user
    // about the bad URL.
    //
    // 'tenant_notfound_url' => 'https://pluf.ir/wb/blog/page/how-config-notfound-tenant';

    //
    // Enable/Disable Mutlitenant system
    //
    // By deault system runs based on a single tenant model. If you want to switch
    // to multitenant model change th following option. Note that, befor installing
    // the application you must switch to multi/single tinant model and do not change
    // it anymore.
    //
    // 'multitenant' => false;
];


