<?php

/**
 * مدل داده‌ای پیام را ایجاد می‌کند.
 * 
 * @param unknown $object
 * @return Pluf_User
 */
function Inbox_Shortcuts_messageDataFactory ($object = null)
{
    if ($object === null || ! isset($object))
        return new Inbox_Message();
    return $object;
}
