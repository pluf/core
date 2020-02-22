<?php
namespace Pluf\Template;

class Context
{

    public $_vars;

    function __construct($vars = array())
    {
        $this->_vars = new ContextVars($vars);
    }

    function get($var)
    {
        if (isset($this->_vars[$var])) {
            return $this->_vars[$var];
        }
        return '';
    }

    function set($var, $value)
    {
        $this->_vars[$var] = $value;
    }
}
