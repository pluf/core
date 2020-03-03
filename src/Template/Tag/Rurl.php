<?php
namespace Pluf\Template\Tag;

use Pluf\HTTP\URL;

/**
 * Assign the url to a template variable.
 */
class Rurl extends \Pluf\Template\Tag
{

    function start($var, $view, $params = array(), $get_params = array())
    {
        $this->context->set($var, URL::urlForView($view, $params, $get_params));
    }
}
