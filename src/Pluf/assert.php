<?php

/**
 * بررسی حالت‌ها
 * 
 * در رفع خطا و یا در بسیاری از پیاده‌سازی‌ها نیاز است که پارامترها و داده‌های
 * سیستم بررسی شده و در صورت نیاز خطای مناسب صادر شود. این کلاس خطاهای پایه ای
 * را بررسی کرده و خطاهای مناسب تولید می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Pluf_Assert
{

    public static function assertNotNull ($value, $message)
    {}

    public static function assertTrue ($value, $message)
    {}

    public static function assertFalse ($value, $message)
    {}
}