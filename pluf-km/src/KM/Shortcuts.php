<?php

/**
 * مدل داده‌ای برچسب را ایجاد می‌کند.
 * 
 * @param unknown $object
 * @return Pluf_User
 */
function KM_Shortcuts_labelDateFactory ($object)
{
    if ($object === null || ! isset($object))
        return new KM_Label();
    return $object;
}

/**
 * مدل داده‌ای دسته را ایجاد می‌کند.
 *
 * @param unknown $object            
 * @return Pluf_User
 */
function KM_Shortcuts_categoryDateFactory ($object)
{
    if ($object === null || ! isset($object))
        return new KM_Category();
    return $object;
}
