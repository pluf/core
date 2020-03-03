<?php
// $var = include 'mysql.conf.php';
$cfg = include 'sqlite.conf.php';

/*
 * **************************************************************************
 * Core
 * **************************************************************************
 */
$cfg['test'] = true;
$cfg['debug'] = true;
$cfg['timezone'] = 'Europe/Berlin';
$cfg['installed_apps'] = array(
    'Pluf',
    'Smallest',
    'Note',
    'Test'
);

//
// Main domain of the application
//
// $cfg['general_domain'] = 'pluf.ir';

$cfg['app_base'] = '/testapp';

//
// You can controll how to manage path. In simple mode all actions are encoded
// in url parameters while in the mod_rewrite actions are the request path.
//
// - simple: using index.php in all pathes
// - mod_rewrite: remove index.php from path
//
// $cfg['url_format'] = 'mod_rewrite';

// Temporary folder where the script is writing the compiled templates,
// cached data and other temporary resources.
// It must be writeable by your webserver instance.
// It is mandatory if you are using the template system.
$cfg['tmp_folder'] = dirname(__FILE__) . '/../tmp';

// Default mimetype of the document your application is sending.
// It can be overwritten for a given response if needed.
$cfg['mimetype'] = 'text/html';

/*
 * **************************************************************************
 * Template engine
 * **************************************************************************
 */

$cfg['template_tags'] = array(
    'mytag' => 'Pluf_Template_Tag_Mytag'
);

// The folder in which the templates of the application are located.
$cfg['templates_folder'] = array(
    dirname(__FILE__) . '/../templates'
);

/*
 * **************************************************************************
 * Tenants
 * **************************************************************************
 */

//
// The ID of default tenant
//
$cfg['tenant_root_level'] = 10;
$cfg['tenant_root_title'] = 'Tenant title';
$cfg['tenant_root_description'] = 'Default tenant in single mode';
$cfg['tenant_root_domain'] = 'pluf.ir';
$cfg['tenant_root_subdomain'] = 'www';
$cfg['tenant_root_validate'] = 1;

//
// Enalbe the application in multitenant
//
// $cfg['tenant_multi_enable'] = false;

/*
 * **************************************************************************
 * Logger
 * **************************************************************************
 */
$cfg['log_level'] = \Pluf\Log::INFO;
$cfg['log_delayed'] = false;
$cfg['log_handler'] = '\Pluf\Log\Console';

return $cfg;
