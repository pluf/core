<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');

class Pluf_Template_Tag_Url extends Pluf_Template_Tag
{

    function start ($view, $params = array(), $get_params = array())
    {
        echo Pluf_HTTP_URL_urlForView($view, $params, $get_params);
    }
}
