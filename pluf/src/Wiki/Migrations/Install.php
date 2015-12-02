<?php

/**
 * ساختارهای اولیه داده‌ای و پایگاه داده را ایجاد می‌کند.
 * 
 * @param string $params
 */
function Wiki_Migrations_Install_setup ($params = '')
{
    $models = array(
            'Wiki_Page',
            'Wiki_Book'
    );
    $db = Pluf::db();
    $schema = new Pluf_DB_Schema($db);
    foreach ($models as $model) {
        $schema->model = new $model();
        $schema->createTables();
    }
    
    /*
     * موجودیت‌های پیش فرض سیستم
     */

    // Install the permissions
    $perm = new Pluf_Permission();
    $perm->name = 'Wiki book ownership';
    $perm->code_name = 'book-owner';
    $perm->description = 'Permission given to wiki book owners.';
    $perm->application = 'Wiki';
    $perm->create();
    
    $perm = new Pluf_Permission();
    $perm->name = 'Wiki page ownership';
    $perm->code_name = 'page-owner';
    $perm->description = 'Permission given to wiki page owners.';
    $perm->application = 'Wiki';
    $perm->create();
}

/**
 * تمام داده‌های ایجاد شده را از سیستم حذف می‌کند.
 *
 * @param string $params            
 */
function Wiki_Migrations_Install_teardown ($params = '')
{
    $models = array(
            'Wiki_Page',
            'Wiki_Book'
    );
    $db = Pluf::db();
    $schema = new Pluf_DB_Schema($db);
    foreach ($models as $model) {
        $schema->model = new $model();
        $schema->dropTables();
    }
}
