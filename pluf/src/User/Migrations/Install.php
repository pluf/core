<?php

Pluf::loadFunction('Pluf_Shortcuts_LoadModels');
Pluf::loadFunction('Pluf_Shortcuts_LoadPermissions');
/**
 * ساختارهای اولیه داده‌ای و پایگاه داده را ایجاد می‌کند.
 * 
 * @param string $params
 */
function User_Migrations_Install_setup ($params = '')
{
    $filename = dirname(__FILE__).'/../module.json';
    $myfile = fopen($filename, "r") or die("Unable to open module.json!");
    $json = fread($myfile, filesize($filename));
    fclose($myfile);
    $moduel = json_decode($json, true);

    Pluf_Shortcuts_LoadModels($moduel);
    Pluf_Shortcuts_LoadPermissions($moduel);
}

