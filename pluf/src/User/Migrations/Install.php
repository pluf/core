<?php

/**
 * ساختارهای اولیه داده‌ای و پایگاه داده را ایجاد می‌کند.
 * 
 * @param string $params
 */
function User_Migrations_Install_setup ($params = '')
{
    $models = array(
            'User_Profile'
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
    $users = new Pluf_User ();
    $users->login = 'admin';
    $users->last_name = 'admin';
    $users->email = 'admin@dpq.co.ir';
    $users->setPassword ( 'admin' );
    $users->administrator = true;
    $users->staff = true;
    $users->create ();
}

/**
 * تمام داده‌های ایجاد شده را از سیستم حذف می‌کند.
 *
 * @param string $params            
 */
function User_Migrations_Install_teardown ($params = '')
{
    $models = array(
            'User_Profile'
    );
    $db = Pluf::db();
    $schema = new Pluf_DB_Schema($db);
    foreach ($models as $model) {
        $schema->model = new $model();
        $schema->dropTables();
    }
}
