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

function SaaS_Shortcuts_GetResourceOr404 ($id)
{
    $item = new SaaS_Resource($id);
    if (!$item->isAnonymous()) {
        return $item;
    }
    throw new Pluf_HTTP_Error404("Resource not found (" . $id . ")");
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


