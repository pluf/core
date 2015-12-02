<?php

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Jayab_Precondition
{

    /**
     * دسترسی‌های اولیه به سیستم را بررسی می‌کند.
     *
     * @param unknown $request            
     */
    public static function baseAccess ($request)
    {
        /*
         * در آیند دسترسی‌های پایه‌ای را در اینجا تست خواهیم کرد.
         */
        return true;
    }

    /**
     * امکان دسترسی برای ویرایش یک مکان را تعیین می‌کند.
     *
     * در صورتی که دسترسی مجاز باشد مقدار درستی برگردانده می‌شود در غیر این صورت
     * استثنا
     * دسترسی غیر مجاز صادر خواهد شد.
     *
     * @param unknown $user            
     * @param unknown $location            
     * @throws Pluf_Exception_PermissionDenied
     * @return boolean
     */
    public static function canEditLocation ($user, $location)
    {
        if ($user->administrator)
            return true;
        if ($user->staff && ! $location->community)
            return true;
        if ($location->community === true && $location->reporter != $user->id) {
            // FIXME: maso, 1394: بررسی نوع مالک و مالک
            throw new Pluf_Exception_PermissionDenied(
                    __('you are allowed to access the location'));
        }
    }

    public static function canDeleteLocation ($user, $location)
    {
        return Jayab_Precondition::canEditLocation($user, $location);
    }

    public static function canAccessLocation ($user, $location)
    {
        return true;
    }
}
