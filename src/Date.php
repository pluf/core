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
 * کلاسی کاربردی برای کار با تاریخ
 *
 * استفاده از تاریخ در سیستم‌های شبکه بسیار مرسوم است. در پیاده سازی‌های متفاوت
 * به دسته‌ای از عملیات‌ها نیاز داریم که تاریخ را دستکاری کنند. در اینجا تمام فراخوانی‌های
 * مورد نیاز برای کار با تاریخ آورده شده است.
 *
 * @author maso
 *        
 */
class Pluf_Date
{

    /**
     * Get a GM Date in the format YYYY-MM-DD HH:MM:SS and returns a
     * string with the given format in the current timezone.
     *
     * @param
     *            string GMDate
     * @param
     *            string Format to be given to strftime ('%Y-%m-%d %H:%M:%S')
     * @return string Formated GMDate into the local time
     */
    public static function gmDateToString($gmdate, $format = '%Y-%m-%d %H:%M:%S')
    {
        $time = strtotime($gmdate . 'Z');
        return strftime($format, $time);
    }

    /**
     * Get a GM Date in the format YYYY-MM-DD HH:MM:SS and returns a
     * string with the given format in GMT.
     *
     * @param
     *            string GMDate
     * @param
     *            string Format to be given to date ('c')
     * @return string Formated GMDate into GMT
     */
    public static function gmDateToGmString($gmdate, $format = 'c')
    {
        $time = strtotime($gmdate . 'Z');
        return date($format, $time);
    }

    /**
     * Day compare.
     *
     * Compare if the first date is before or after the second date.
     * Returns:
     * 0 if the days are the same.
     * 1 if the first date is before the second.
     * -1 if the first date is after the second.
     *
     * @param
     *            string YYYY-MM-DD date.
     * @param
     *            string YYYY-MM-DD date (today local time).
     * @return int
     */
    public static function dayCompare($date1, $date2 = null)
    {
        $date2 = (is_null($date2)) ? date('Y-m-d') : $date2;
        if ($date2 == $date1)
            return 0;
        if ($date1 > $date2)
            return - 1;
        return 1;
    }
}

