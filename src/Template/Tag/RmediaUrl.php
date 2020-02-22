<?php
namespace Pluf\Template\Tag;


class Pluf_Template_Tag_RmediaUrl extends \Pluf\Template\Tag
{

    function start ($var, $file = '')
    {
        $this->context->set($var, Pluf_Template_Tag_MediaUrl::url($file));
    }
}

