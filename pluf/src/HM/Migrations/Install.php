<?php
Pluf::loadFunction('SaaS_Shortcuts_LoadLibFromJson');
Pluf::loadFunction('SaaS_Shortcuts_LoadSPAFromRepository');

/**
 * ساختارهای اولیه داده‌ای و پایگاه داده را ایجاد می‌کند.
 * 
 * @param string $params
 */
function HM_Migrations_Install_setup ($params = '')
{
    $models = array(
            'HM_Profile',
            'HM_Message',
            'HM_Part',
            'HM_Payment'
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
    $user = new Pluf_User();
    $user->login = 'admin';
    $user->last_name = 'admin';
    $user->email = 'admin@dpq.co.ir';
    $user->setPassword('admin');
    $user->administrator = true;
    $user->staff = true;
    $user->create();
    
    SaaS_Shortcuts_LoadLibFromJson(dirname(__FILE__) . "/lib.json", true);
    SaaS_Shortcuts_LoadSPAFromRepository();
    
    
    $apartment = new SaaS_Application();
    $apartment->title = 'Admin demo apartment';
    $apartment->description = 'Auto generated application';
    $apartment->create();
    
    $message = new HM_Message();
    $message->title = 'Welcome to Apartment manager ';
    $message->message = 'This is the welcome message from DPQ';
    $message->apartment = $apartment;
    $message->create();
    
    $part = new HM_Part();
    $part->title = 'Example part';
    $part->count = 1;
    $part->part_number = 1;
    $part->apartment = $apartment;
    $part->create();
    for ($j = 1; $j < 21; $j ++) {
        $pay = new HM_Payment();
        $pay->part = $part;
        $pay->amount = 1000;
        $pay->title = 'یک نمونه پرداخت از پیش ساخته شده.';
        $pay->create();
    }
    
    $sysConfig = new SaaS_Configuration();
    $sysConfig->application = $apartment;
    $sysConfig->key = 'system';
    $sysConfig->type = SaaS_ConfigurationType::SYSTEM;
    $sysConfig->setData("level", 0);
    $sysConfig->owner_write = false;
    $sysConfig->member_write = false;
    $sysConfig->authorized_write = false;
    $sysConfig->other_write = false;
    $sysConfig->owner_read = true;
    $sysConfig->member_read = true;
    $sysConfig->authorized_read = false;
    $sysConfig->other_read = false;
    $sysConfig->create();
    
    $themeConfig = new SaaS_Configuration();
    $themeConfig->application = $apartment;
    $themeConfig->key = 'theme';
    $themeConfig->type = SaaS_ConfigurationType::GENERAL;
    $themeConfig->setData("id", "g1");
    $themeConfig->setData("style", "default");
    $themeConfig->owner_write = false;
    $themeConfig->member_write = false;
    $themeConfig->authorized_write = false;
    $themeConfig->other_write = false;
    $themeConfig->owner_read = true;
    $themeConfig->member_read = true;
    $themeConfig->authorized_read = true;
    $themeConfig->other_read = true;
    $themeConfig->create();
    
    Pluf_RowPermission::add($user, $apartment, 'SaaS.software-owner');
}

/**
 * تمام داده‌های ایجاد شده را از سیستم حذف می‌کند.
 *
 * @param string $params            
 */
function HM_Migrations_Install_teardown ($params = '')
{
    $models = array(
            'HM_Profile',
            'HM_Message',
            'HM_Part',
            'HM_Payment'
    );
    $db = Pluf::db();
    $schema = new Pluf_DB_Schema($db);
    foreach ($models as $model) {
        $schema->model = new $model();
        $schema->dropTables();
    }
}
