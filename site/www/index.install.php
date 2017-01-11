<?php
set_include_path( //
        PLUF_BASE . PATH_SEPARATOR . //
SRC_BASE . '/src' . PATH_SEPARATOR . //
get_include_path() . PATH_SEPARATOR);

header('Content-Type: text/plain');
try {
    global $what;
    $what = array(
            'all' => true,
            'app' => '',
            'conf' => dirname(__FILE__) . '/../src/config.php',
            'version' => null,
            'dry_run' => false,
            'un-install' => false,
            'install' => true,
            'backup' => false,
            'restore' => false,
            'debug' => true
    );
    require 'migratew.php';
    
    debug('# User and groups');
    $user = new Pluf_User();
    $user->login = 'admin';
    $user->last_name = 'admin';
    $user->email = 'admin@dpq.co.ir';
    $user->setPassword('admin');
    $user->administrator = true;
    $user->staff = true;
    $user->create();
    debug('User is created');
    debug('Login:admin');
    debug('Password:admin');
    
    debug('# Default tenant');
    $tenant = new Pluf_Tenant();
    $tenant->title = 'Default Tenant';
    $tenant->description = 'Auto generated tenant';
    $tenant->subdomain = Pluf::f('saas_tenant_default', 'main');
    $tenant->domain = Pluf::f('general_domain', 'donate.com');
    $tenant->create();
    
    Pluf_RowPermission::add($user, $tenant, 'Pluf.owner');
} catch (Exception $e) {
    var_export($e);
}