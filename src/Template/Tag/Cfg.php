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
namespace Pluf\Template\Tag;

use Pluf;

/**
 * دسترسی به تنظیمان نرم‌افزار
 *
 * در لایه الگو ممکن است نیاز به تنظیم‌هایی شود که برای سیستم در نظر گرفته شده است. این کلاس یک برچسب را به
 * وجود می‌آورد تا به تنظیم‌ها دسترسی پیدا کرد.
 *
 * نکته اینکه این کلاس امکان دسترسی به داده‌های امنیتی از تنظیم‌ها را نمی‌دهد.
 */
class Cfg extends \Pluf\Template\Tag
{

    /**
     * Display the configuration variable.
     *
     * @param
     *            string Configuration variable.
     * @param
     *            mixed Default value to return display ('').
     * @param
     *            bool Display the value (true).
     * @param
     *            string Prefix to set to the variable if not displayed
     *            ('cfg_').
     */
    function start($cfg, $default = '', $display = true, $prefix = 'cfg_')
    {
        if (0 !== strpos($cfg, 'db_') or 0 !== strpos($cfg, 'secret_')) {
            if ($display) {
                echo Pluf::f($cfg, $default);
            } else {
                $this->context->set($prefix . $cfg, Pluf::f($cfg, $default));
            }
        }
    }
}
