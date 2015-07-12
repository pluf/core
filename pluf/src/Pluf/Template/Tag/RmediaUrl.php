<?php

class Pluf_Template_Tag_RmediaUrl extends Pluf_Template_Tag
{

    function start ($var, $file = '')
    {
        $this->context->set($var, Pluf_Template_Tag_MediaUrl::url($file));
    }
}

