<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

/**
 * پیش شرط‌های سیستم را ایجاد می‌کند.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaSKM_Precondition
{

    public static function userCanCreateTag ($request)
    {
        return true;
    }

    public static function userCanAccessTags ($request)
    {
        return true;
    }
    
    public static function userCanAccessTag ($request, $tag)
    {
        return true;
    }

    public static function userCanUpdateTag ($request, $tag)
    {
        return true;
    }

    public static function userCanDeleteTag ($request, $tag)
    {
        return true;
    }
}