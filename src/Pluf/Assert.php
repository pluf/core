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

    public static function assertNull ($value, $message)
    {
        if (! is_null($value)) {
            // TODO: maso, 1395:
        }
    }

    public static function assertNotNull ($value, $message)
    {
        if (is_null($value)) {
            // TODO: maso, 1395:
        }
    }

    public static function assertTrue ($value, $message)
    {
        if (! $value) {
            // TODO: maso, 1395:
        }
    }

    public static function assertFalse ($value, $message)
    {
        if ($value) {
            // TODO: maso, 1395:
        }
    }
}