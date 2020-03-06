<?php

// -------------------------------------------------------------------------
// Database Configurations
// -------------------------------------------------------------------------
// $var = include 'mysql.conf.php';
// $var = include 'pluf.config.php';
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

// -------------------------------------------------------------------------
// Tenants
// -------------------------------------------------------------------------

// multitenant
return $cfg;
