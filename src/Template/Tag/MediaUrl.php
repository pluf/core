<?php
namespace Pluf\Template\Tag;

use Pluf\Bootstrap;
use Pluf\Template\Tag;

class MediaUrl extends Tag
{

    function start($file = '')
    {
        echo self::url($file);
    }

    public static function url($file = '')
    {
        if ($file !== '' && Bootstrap::f('last_update_file', false) && false !== ($last_update = Bootstrap::fileExists(Bootstrap::f('last_update_file')))) {
            $file = $file . '?' . substr(md5(filemtime($last_update)), 0, 5);
        }
        return Bootstrap::f('url_media', Bootstrap::f('app_base') . '/media') . $file;
    }
}

