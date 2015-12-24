<?php

/**
 * داده‌های مورد نیاز در الگو را نگهداری می‌کند.
 * 
 * تمام داده‌هایی که از لایه نمایش به لایه الگو ارسال می‌شود در این کلاس 
 * نگهداری شده و در اختیار الگوها قرار می‌گیرد.
 */
class Pluf_Template_Context
{

    public $_vars;

    function __construct ($vars = array())
    {
        $this->_vars = new Pluf_Template_ContextVars($vars);
    }

    function get ($var)
    {
        if (isset($this->_vars[$var])) {
            return $this->_vars[$var];
        }
        return '';
    }

    function set ($var, $value)
    {
        $this->_vars[$var] = $value;
    }
}
