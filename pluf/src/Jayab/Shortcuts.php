<?php

/**
 * مدل داده‌ای مکان را ایجاد می‌کند.
 * 
 * @param unknown $object
 * @return Pluf_User
 */
function Jayab_Shortcuts_locationFactory ($object)
{
    if ($object == null || ! isset($object))
        return new Jayab_Location();
    return $object;
}

/**
 * یک نمون جدید برچسب ایجاد می‌کند.
 *
 * @param unknown $object            
 */
function Jayab_Shortcuts_tagFactory ($object)
{
    if ($object == null || ! isset($object))
        return new Jayab_Tag();
    return $object;
}

/**
 * یک ساختار داده‌ای برای رای ایجاد می‌کند
 *
 * @param unknown $object            
 * @return Jayab_Vote|unknown
 */
function Jayab_Shortcuts_voteFactory ($object)
{
    if ($object == null || ! isset($object))
        return new Jayab_Vote();
    return $object;
}

/**
 * بیشترین و کمترین مقدار در نقاط جستجو را تعیین می‌کند.
 *
 * @param unknown $request            
 * @param unknown $lat            
 * @param unknown $long            
 * @param unknown $meters            
 * @return number
 */
function Jayab_Shortcuts_locationBound ($request, $lat, $long, $meters = 1000)
{
    $equator_circumference = 6371000; // meters
    $polar_circumference = 6356800; // meters
    
    $m_per_deg_long = 360 / $polar_circumference;
    
    $rad_lat = ($lat * M_PI / 180);
    $m_per_deg_lat = 360 / (cos($rad_lat) * $equator_circumference);
    
    $deg_diff_long = $meters * $m_per_deg_long;
    $deg_diff_lat = $meters * $m_per_deg_lat;
    
    $coordinates['max']['lat'] = $lat + $deg_diff_lat;
    $coordinates['max']['long'] = $long + $deg_diff_long;
    
    $coordinates['min']['lat'] = $lat - $deg_diff_lat;
    $coordinates['min']['long'] = $long - $deg_diff_long;
    
    return $coordinates;
}

/**
 * بر اساس سطح دسترسی کاربر تعداد مکان‌های قابل دسترسی را تعیین می‌کند.
 *
 * @param unknown $request            
 * @param number $count            
 * @return number
 */
function Jayab_Shortcuts_locationCount ($request, $count = 10)
{
    if ($count <= 10)
        return 10;
    if ($request->user->isAnonymous())
        return 10;
    $MAX_LEVEL = 10000.0;
    $MAX = 50.0;
    $level = 0;
    try {
        $level = $request->user->getProfile()->level;
    } catch (Exception $e) {}
    $c = ($MAX * $level / $MAX_LEVEL) + 10;
    if ($c > $MAX)
        $c = $MAX;
    if ($count < $c)
        return $count;
    return $c;
}

/**
 * محدوده جستجو را تعیین می‌کند.
 *
 * @param unknown $request            
 * @param number $radius            
 */
function Jayab_Shortcuts_locationRadios ($request, $radius = 1000)
{
    if ($radius < 1000)
        return $radius;
    if ($request->user->isAnonymous())
        return 1000;
    $MAX_LEVEL = 10000.0;
    $MAX = 15000.0;
    $level = 0;
    try {
        $level = $request->user->getProfile()->level;
    } catch (Exception $e) {}
    $c = ($MAX * $level / $MAX_LEVEL) + 1000;
    if ($c > $MAX)
        $c = $MAX;
    if ($radius < $c)
        return $radius;
    return $c;
}

/**
 *
 * @param unknown $id            
 * @throws Pluf_HTTP_Error404
 */
function Jayab_Shortcuts_GetLocationOr404 ($id)
{
    $item = new Jayab_Location($id);
    if ((int) $id > 0 && $item->id == $id) {
        return $item;
    }
    throw new Pluf_HTTP_Error404(sprintf(__("location not found (%s)"), $id), 
            4311);
}

function Jayab_Shortcuts_GetTagOr404 ($id)
{
    $item = new Jayab_Location($id);
    if ((int) $id > 0 && $item->id == $id) {
        return $item;
    }
    throw new Pluf_HTTP_Error404(sprintf(__("tag not found (%s)"), $id), 4312);
}


