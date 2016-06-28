<?php
Pluf::loadFunction('Pluf_Shortcuts_LoadModels');
Pluf::loadFunction('Pluf_Shortcuts_LoadPermissions');

// TODO: maoso, replse with default installation
function SaaSKM_Migrations_Install_setup ($params = null)
{
    $filename = dirname(__FILE__) . '/../module.json';
    $myfile = fopen($filename, "r") or die("Unable to open module.json!");
    $json = fread($myfile, filesize($filename));
    fclose($myfile);
    $moduel = json_decode($json, true);
    
    Pluf_Shortcuts_LoadModels($moduel);
    // Pluf_Shortcuts_LoadPermissions($moduel);
}

