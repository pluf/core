<?php

// -------------------------------------------------------------------------
// Database Configurations
// -------------------------------------------------------------------------
// $cfg = include 'mysql.conf.php';
$cfg = include 'sqlite.conf.php';

$cfg['test'] = true;
$cfg['debug'] = true;

$cfg['timezone'] = 'Europe/Berlin';

// Set the debug variable to true to force the recompilation of all
// the templates each time during development
$cfg['installed_apps'] = array(
    'Pluf',
    'HelloWord',
    'NoteBook',
    'Smallest'
);

// Default mimetype of the document your application is sending.
// It can be overwritten for a given response if needed.
$cfg['mimetype'] = 'text/html';

$cfg['app_base'] = '/testapp';
$cfg['url_format'] = 'simple';

// Temporary folder where the script is writing the compiled templates,
// cached data and other temporary resources.
// It must be writeable by your webserver instance.
// It is mandatory if you are using the template system.
$cfg['tmp_folder'] = '/tmp';


// $cfg['upload_max_size'] = 1024;
// $cfg['upload_path'] = '/tmp'

//
// List of Middleware
//
//  Define list of middleware to apply on all requests. Add full path of
// middleware class addres into the list to enable it. The order of the 
// list is important.
//
// $cfg['middleware_classes'] = array();

// -------------------------------------------------------------------------
// Template manager and compiler
// -------------------------------------------------------------------------

// The folder in which the templates of the application are located.
$cfg['templates_folder'] = array(
    dirname(__FILE__) . '/../templates'
);

$cfg['template_tags'] = array(
    'mytag' => 'Pluf_Template_Tag_Mytag'
);

$cfg['template_modifiers'] = array();
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
$cfg['log_level'] = 'error';

$cfg['log_delayed'] = false;

//
// Formatter convert runtime date into a simple writable message.
//
$cfg['log_formater'] = '\Pluf\LoggerFormatter\Plain';

//
// Logger appender get a message and append a log to outputs such as consoel
// file remote server and etc.
//
$cfg['log_appender'] = '\Pluf\LoggerAppender\Console';

//
// Remote
//
// $cfg['log_appender_remote_server'] = 'localhost';
// $cfg['log_appender_remote_path'] = '/';
// $cfg['log_appender_remote_port'] = 8000;
// $cfg['log_appender_remote_headers'] = [];

// -------------------------------------------------------------------------
// View
// -------------------------------------------------------------------------

// $cfg['view_api_prefix'] = '';
// $cfg['view_api_base'] = '';


// -------------------------------------------------------------------------
// Tenants
// -------------------------------------------------------------------------

//
// Default tenant path
// 
//  If a user try to access an undefined tenant, it will be redirect to the 
// following URL. You can create a registration page and inform the user 
// about the bad URL.
//
// $cfg['tenant_notfound_url'] = 'https://pluf.ir/wb/blog/page/how-config-notfound-tenant';

//
// Enable/Disable Mutlitenant system
//
//  By deault system runs based on a single tenant model. If you want to switch
// to multitenant model change th following option. Note that, befor installing
// the application you must switch to multi/single tinant model and do not change
// it anymore.
//
//  $cfg['multitenant'] = false;

return $cfg;
