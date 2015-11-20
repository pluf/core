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

function SaaS_Shortcuts_GetSAPOr404($id){
    $item = new SaaS_SAP($id);
    if ((int) $id > 0 && $item->id == $id) {
        return $item;
    }
    throw new Pluf_HTTP_Error404("SAP not found (" . $id . ")");
}

function SaaS_Shortcuts_GetApplicationOr404($id){
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


/**
 * نصب کتابخانه‌ها بر اساس یک فایل جیسون
 * 
 * فهرست تمام کتابخانه‌ها را با قالب جیسون دریافت کرده و آنها را به کلاس‌های معادل
 * تبدیل می‌کند. در صورتی که پارامتر $create مقدار درستی باشد آنها را در پایگاه داده
 * ذخیره نیز می‌کند.
 * 
 * @param string $filename
 * @param boolean $create
 * @return library list
 */
function SaaS_Shortcuts_LoadLibFromJson($filename, $create){
    $list = array();
    {
        if (is_readable($filename)) {
            $myfile = fopen($filename, "r") or die("Unable to open file!");
            $json = fread($myfile, filesize($filename));
            fclose($myfile);
            $packages = json_decode($json, true);
            foreach($packages as $package){
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
    if($create){
        foreach ($list as $lib){
            $lib->create();
        }
    }
    return $list;
}