<?php

/**
 * پیش شرط‌ها کار با ویکی را ایجاد می‌کند.
 *
 */
class Book_Precondition
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
        return true;
    }

    public static function userCanInterestedInBook ($request, $book)
    {
        return true;
    }

    public static function userCanCreatePage ($request, $book = null)
    {
        return true;
    }

    public static function userCanAccessPage ($request, $page, $book = null)
    {
        return true;
    }

    public static function userCanUpdatePage ($request, $page, $book = null)
    {
        return true;
    }

    public static function userCanDeletePage ($request, $page, $book = null)
    {
        return true;
    }
}