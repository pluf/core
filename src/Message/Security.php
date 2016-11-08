<?php

/**
 * متدهایی را برای بررسی شرط‌های امنیتی کار با دیجی‌دکی فراهم می‌کند.
 *
 */
class Message_Security
{

    public static function canAccessMessage ($request, $message)
    {
        if ($request->user->administrator) {
            return true;
        }
        if ($message->user === $request->user->id &&
                 $message->tenant === $request->tenant->id)
            return true;
        throw new Pluf_Exception_PermissionDenied(
                'You are not permited to access this message');
    }
}