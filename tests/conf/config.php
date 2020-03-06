<?php

// -------------------------------------------------------------------------
// Database Configurations
// -------------------------------------------------------------------------
// $var = include 'mysql.conf.php';
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

$cfg['log_level'] = \Pluf\Logger::OFF;
$cfg['log_delayed'] = false;
$cfg['log_handler'] = '\Pluf\LoggerHandler\Console';

// log_remote_server' (localhost)
// log_remote_path' (/)
// log_remote_port' (8000)
// log_remote_headers' (array())

// -------------------------------------------------------------------------
// Tenants
// -------------------------------------------------------------------------

// multitenant
return $cfg;
