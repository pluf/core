<?php

/**
 * Setup of a clean SaaS.
 *
 * It creates all the tables for the application.
 */
function SaaS_Migrations_Install_setup ($params = null)
{
    // ایجاد پایگاه داده
    $models = array(
            'SaaS_Application',
            'SaaS_Configuration',
            'SaaS_SAP',
            'SaaS_Lib',
            'SaaS_Resource'
    );
    $db = Pluf::db();
    $schema = new Pluf_DB_Schema($db);
    foreach ($models as $model) {
        $schema->model = new $model();
        $schema->createTables();
    }
    // ایجاد گواهی‌ها
    $perm = new Pluf_Permission();
    $perm->name = 'Software membership';
    $perm->code_name = 'software-member';
    $perm->description = 'Permission given to software members.';
    $perm->application = 'SaaS';
    $perm->create();
    
    $perm = new Pluf_Permission();
    $perm->name = 'Software ownership';
    $perm->code_name = 'software-owner';
    $perm->description = 'Permission given to software owners.';
    $perm->application = 'SaaS';
    $perm->create();
    
    $perm = new Pluf_Permission();
    $perm->name = 'Software authorized users';
    $perm->code_name = 'software-authorized-user';
    $perm->description = 'Permission given to users allowed to access a software.';
    $perm->application = 'SaaS';
    $perm->create();
    
    $perm = new Pluf_Permission();
    $perm->name = 'SAP anonymous users';
    $perm->code_name = 'sap-anonymous-access';
    $perm->description = 'Permission given to application';
    $perm->application = 'SaaS';
    $perm->create();
    
    $perm = new Pluf_Permission();
    $perm->name = 'SAP authorized users';
    $perm->code_name = 'sap-authorized-access';
    $perm->description = 'Permission given to application';
    $perm->application = 'SaaS';
    $perm->create();
    
    $perm = new Pluf_Permission();
    $perm->name = 'SAP member users';
    $perm->code_name = 'sap-member-access';
    $perm->description = 'Permission given to application';
    $perm->application = 'SaaS';
    $perm->create();
    
    $perm = new Pluf_Permission();
    $perm->name = 'SAP owner users';
    $perm->code_name = 'sap-owner-access';
    $perm->description = 'Permission given to application';
    $perm->application = 'SaaS';
    $perm->create();
}

/**
 * ساختارهای داده‌ای مربوط به پروژه را از بین می‌برد.
 *
 * @param string $params            
 */
function SaaS_Migrations_Install_teardown ($params = null)
{
    // حذف گواهی‌ها
    $perm = Pluf_Permission::getFromString('SaaS.software-member');
    if ($perm)
        $perm->delete();
    $perm = Pluf_Permission::getFromString('SaaS.software-owner');
    if ($perm)
        $perm->delete();
    $perm = Pluf_Permission::getFromString('SaaS.software-authorized-user');
    if ($perm)
        $perm->delete();
        // حذف پایگاه داده
    $models = array(
            'SaaS_Application',
            'SaaS_Configuration',
            'SaaS_SAP',
            'SaaS_Lib',
            'SaaS_Resource'
    );
    $db = Pluf::db();
    $schema = new Pluf_DB_Schema($db);
    foreach ($models as $model) {
        $schema->model = new $model();
        $schema->dropTables();
    }
}