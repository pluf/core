<?php

class Pluf_Template_Tag_MediaUrl extends Pluf_Template_Tag
{

    function start ($file = '')
    {
        echo Pluf_Template_Tag_MediaUrl::url($file);
    }

    public static function url ($file = '')
    {
        if ($file !== '' && Pluf::f('last_update_file', false) &&
                 false !==
                 ($last_update = Pluf::fileExists(Pluf::f('last_update_file')))) {
            $file = $file . '?' . substr(md5(filemtime($last_update)), 0, 5);
        }
        return Pluf::f('url_media', Pluf::f('app_base') . '/media') . $file;
    }
}

