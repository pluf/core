<?php

/**
 * فیلد بولی را ایجاد می‌کند.
 * 
 * @author maso
 *
 */
class Pluf_Form_Field_Boolean extends Pluf_Form_Field
{
    public $widget = 'Pluf_Form_Widget_CheckboxInput';

    public function clean($value)
    {
        //parent::clean($value);
        if (in_array($value, array('on', 'y', '1', 1, true, 'true'))) {
            return true;
        }
        return false;
    }
}
