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

$cfg = array();

// Set the debug variable to true to force the recompilation of all
// the templates each time during development
$cfg['debug'] = true;

// The unit tests are using the simpletest unittesting framework, you
// need to download it: http://simpletest.org/ and provide the path to
// it here.
$cfg['simple_test_path'] = '/home/loa/Vendors/simpletest';

// When your application returns an error in production, the error
// message with the error trace is automatically sent to the
// administrators.
$cfg['admins'] = array(
                       array('Admin', 'admin@example.com'),
                       );

// If your models are using models from applications outside of the
// base objects of the framework, you need to list them here. 
// You need to include the name of the current application.
$cfg['installed_apps'] = array('Pluf', 'Todo');

// Base URL of the application. Note that it is not something like
// 'root_base' not to overwrite the base of another application when
// several application are running on the same host.
// If with Apache you access the index.php file with:
//  http://localhost/path/to/index.php
// Just put '/path/to/index.php' or '/path/to/index'
// For the unit tests, this is not needed.
$cfg['todo_base'] = '/path/to/index.php';

// URLs mapping of the Todo application. They can be hardcoded in the 
// Dispatcher, but it is often better to have them in a separated file
// for readability/maintainability.
$cfg['todo_urls'] = dirname(__FILE__).'/urls.php';

// Temporary folder where the script is writing the compiled templates,
// cached data and other temporary resources.
// It must be writeable by your webserver instance.
// It is mandatory if you are using the template system.
// You need to create this folder if needed. If you are using Windows
// you can create a 'tmp' folder on you C: drive and put 'c:/tmp'
// (note the forward slash / and not \)
$cfg['tmp_folder'] = '/tmp';

// The folder in which the templates of the application are located.
$cfg['template_folders'] = array(
                                 dirname(__FILE__).'/../templates',
                                 );

$cfg['template_tags'] = array(
                              'url' => 'Pluf_Template_Tag_Url',
                              );

// Default database configuration. The database defined here will be
// directly accessible from Pluf::db() of course it is still possible
// to open any other number of database connections through Pluf_DB
$cfg['db_login'] = '';
$cfg['db_password'] = '';
$cfg['db_server'] = '';
// For testing purpose, the SQLite memory database is the best thing
// to use.
$cfg['db_database'] = ':memory:'; 

// Must be shared by all the installed_apps and the core framework.
// That way you can have several installations of the core framework.
$cfg['db_table_prefix'] = 'test_'; 
$cfg['db_engine'] = 'SQLite';
return $cfg;
