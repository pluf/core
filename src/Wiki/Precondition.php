<?php

/**
 * پیش شرط‌ها کار با ویکی را ایجاد می‌کند.
 *
 */
class Wiki_Precondition
{

    public static function userCanCreateBook ($request)
    {
        return true;
    }

    public static function userCanAccessBook ($request, $book)
    {
        return true;
    }

    public static function userCanUpdateBook ($request, $book)
    {
        return true;
    }

    public static function userCanDeleteBook ($request, $book)
    {
        // User is not owner of the tenant
        return true;
    }

    public static function userCanInterestedInBook ($request, $book)
    {
        return true;
    }

    public static function userCanCreatePage ($request)
    {
        return true;
    }

    public static function userCanAccessPage ($request, $page)
    {
        return true;
    }

    public static function userCanUpdatePage ($request, $page)
    {
        return true;
    }

    public static function userCanDeletePage ($request, $page)
    {
        return true;
    }
}