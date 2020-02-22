<?php
namespace Pluf\Template\Tag;

use Pluf\Template\Tag;

class RmediaUrl extends Tag
{

    function start($var, $file = '')
    {
        $this->context->set($var, MediaUrl::url($file));
    }
}

