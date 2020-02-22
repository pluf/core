<?php

/**
 * دسترسی به تنظیمان نرم‌افزار
 * 
 * در لایه الگو ممکن است نیاز به تنظیم‌هایی شود که برای سیستم در نظر گرفته شده است. این کلاس یک برچسب را به
 * وجود می‌آورد تا به تنظیم‌ها دسترسی پیدا کرد.
 *
 * نکته اینکه این کلاس امکان دسترسی به داده‌های امنیتی از تنظیم‌ها را نمی‌دهد.
 */
class Pluf_Template_Tag_Cfg extends Pluf_Template_Tag
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
    function start ($cfg, $default = '', $display = true, $prefix = 'cfg_')
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
