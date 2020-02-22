<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Pluf;

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