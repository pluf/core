<?php
namespace Pluf\Template\Tag;

use Pluf\Template\Tag;

class Url extends Tag
{

    public function start($view, $params = array(), $get_params = array())
    {
        echo Pluf_HTTP_URL_urlForView($view, $params, $get_params);
    }
}
 