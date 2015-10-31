<?php

/**
 * دسترسی به تنظیم‌های عمومی نرم افزار در الگو
 * 
 * @author maso
 *
 */
class SaaS_Template_SapMainView extends Pluf_Template_Tag
{

    /**
     *
     * @param unknown $app            
     * @param unknown $key            
     * @param unknown $default            
     */
    function start ($filename)
    {
        if (is_readable($filename)) {
            $myfile = fopen($filename, "r") or die("Unable to open file!");
            $content = fread($myfile, filesize($filename));
            fclose($myfile);
            echo $content;
        }
    }
}
