<?php
Pluf::loadFunction('Pluf_Shortcuts_RenderToResponse');

/**
 * یک ساختار داده‌ای برای یک نرم‌افزار ایجاد می‌کند
 *
 * @param unknown $object            
 * @return SaaS_Application|unknown
 */
function SaaS_Shortcuts_applicationFactory ($object)
{
    if ($object == null || ! isset($object))
        return new SaaS_Application();
    return $object;
}

/**
 *
 * @param unknown $object            
 * @return SaaS_Configuration|unknown
 */
function SaaS_Shortcuts_configurationFactory ($object)
{
    if ($object == null || ! isset($object)) {
        $sysConfig = new SaaS_Configuration();
        $sysConfig->type = SaaS_ConfigurationType::GENERAL;
        $sysConfig->owner_write = true;
        $sysConfig->member_write = false;
        $sysConfig->authorized_write = false;
        $sysConfig->other_write = false;
        $sysConfig->owner_read = true;
        $sysConfig->member_read = true;
        $sysConfig->authorized_read = false;
        $sysConfig->other_read = false;
        return $sysConfig;
    }
    return $object;
}

/**
 *
 * @param unknown $object            
 */
function SaaS_Shortcuts_libraryFactory ($object = null)
{
    if ($object == null || ! isset($object))
        return new SaaS_Lib();
    return $object;
}

function SaaS_Shortcuts_GetSPAOr404 ($id)
{
    $item = new SaaS_SPA($id);
    if ((int) $id > 0 && $item->id == $id) {
        return $item;
    }
    throw new Pluf_HTTP_Error404("Application not found (" . $id . ")");
}

function SaaS_Shortcuts_GetApplicationOr404 ($id)
{
    $item = new SaaS_Application($id);
    if ((int) $id > 0 && $item->id == $id) {
        return $item;
    }
    throw new Pluf_HTTP_Error404("Application not found (" . $id . ")");
}

/**
 *
 * @param unknown $request            
 */
function SaaS_Shortcuts_GetLibOr404 ($id)
{
    $item = new SaaS_Lib($id);
    if ((int) $id > 0 && $item->id == $id) {
        return $item;
    }
    throw new Pluf_HTTP_Error404("Library not found (" . $id . ")");
}

/**
 *
 * @param unknown $request            
 */
function SaaS_Shortcuts_LibFindCount ($request)
{
    return 20;
}

function SaaS_Shortcuts_LoadLibs ()
{
    SaaS_Shortcuts_LoadLibFromJson( //
            Pluf::f('saas_lib_repository') . '/' . Pluf::f('saas_lib_index'),  //
            true);
}

/**
 * نصب کتابخانه‌ها بر اساس یک فایل جیسون
 *
 * فهرست تمام کتابخانه‌ها را با قالب جیسون دریافت کرده و آنها را به کلاس‌های
 * معادل
 * تبدیل می‌کند. در صورتی که پارامتر $create مقدار درستی باشد آنها را در پایگاه
 * داده
 * ذخیره نیز می‌کند.
 *
 * @param string $filename            
 * @param boolean $create            
 * @return library list
 */
function SaaS_Shortcuts_LoadLibFromJson ($filename, $create)
{
    $list = array();
    {
        if (is_readable($filename)) {
            $myfile = fopen($filename, "r") or die("Unable to open file!");
            $json = fread($myfile, filesize($filename));
            fclose($myfile);
            $packages = json_decode($json, true);
            foreach ($packages as $package) {
                $lib = new SaaS_Lib();
                $lib->name = $package['name'];
                $lib->version = $package['version'];
                $lib->mode = $package['mode'];
                $lib->type = $package['type'];
                $lib->description = $package['description'];
                $lib->path = $package['path'];
                $list[] = $lib;
            }
        }
    }
    if ($create) {
        foreach ($list as $lib) {
            $lib->create();
        }
    }
    return $list;
}

function SaaS_Shortcuts_LoadSPAFromRepository ()
{
    $repos = Pluf::f('saas_spa_repository');
    if (! is_array($repos)) {
        $repos = array(
                Pluf::f('saas_spa_repository')
        );
    }
    
    foreach ($repos as $repo) {
        $repo = $repo . '/';
        $list = SaaS_Shortcuts_SPAStorageList();
        foreach ($list as $path) {
            $filename = $repo . $path . Pluf::f('saas_spa_package', "/spa.json");
            if (is_readable($filename)) {
                $myfile = fopen($filename, "r") or die("Unable to open file!");
                $json = fread($myfile, filesize($filename));
                fclose($myfile);
                $package = json_decode($json, true);
                
                $mprofile = new SaaS_SPA();
                $mprofile->setFromFormData($package);
                $mprofile->path = '/' . $path;
//                 $mprofile->name = $package['name'];
//                 if (array_key_exists('title', $package))
//                     $mprofile->title = $package['title'];
//                 $mprofile->descritpion = $package['description'];
//                 $mprofile->license = $package['license'];
//                 $mprofile->homepage = $package['homepage'];
//                 $mprofile->version = $package['version'];
                $mprofile->create();
            }
        }
    }
}

/**
 * فهرستی از تمام نرم افزارهایی را تعیین می‌کند که در مخزن ایجاد شده اند.
 *
 * @param unknown $directory            
 */
function SaaS_Shortcuts_SPAStorageList ()
{
    $repos = Pluf::f('saas_spa_repository');
    if (! is_array($repos)) {
        $repos = array(
                Pluf::f('saas_spa_repository')
        );
    }
    
    $results = array();
    foreach ($repos as $directory) {
        $directory = $directory . '/';
        $handler = opendir($directory);
        while ($file = readdir($handler)) {
            if ($file != "." && $file != "..") {
                $spaFile = $directory . $file .
                         Pluf::f('saas_spa_package', "/spa.json");
                if (is_file($spaFile) && is_readable($spaFile)) {
                    $results[] = $file;
                }
            }
        }
        closedir($handler);
    }
    return $results;
}

