<?php
Pluf::loadFunction('Pluf_Shortcuts_LoadModels');
Pluf::loadFunction('Pluf_Shortcuts_LoadPermissions');
Pluf::loadFunction('SaaS_Shortcuts_LoadLibs');
Pluf::loadFunction('SaaS_Shortcuts_LoadSPAFromRepository');

function SaaS_Migrations_Install_setup ($params = null)
{
    $filename = dirname(__FILE__).'/../module.json';
    $myfile = fopen($filename, "r") or die("Unable to open module.json!");
    $json = fread($myfile, filesize($filename));
    fclose($myfile);
    $moduel = json_decode($json, true);
    
    Pluf_Shortcuts_LoadModels($moduel);
    Pluf_Shortcuts_LoadPermissions($moduel);
    SaaS_Shortcuts_LoadLibs();
    SaaS_Shortcuts_LoadSPAFromRepository();
    
    $apartment = new SaaS_Application();
    $apartment->title = 'Default SaaS';
    $apartment->description = 'Auto generated application';
    $apartment->create();
}

