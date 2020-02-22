<?php

/**
 * Simple checkbox.
 */
class Pluf_Form_Widget_CheckboxInput extends Pluf_Form_Widget_Input
{
    public $input_type = 'checkbox';

    /**
     * Renders the HTML of the input.
     *
     * @param string Name of the field.
     * @param mixed Value for the field, can be a non valid value.
     * @param array Extra attributes to add to the input form (array())
     * @return string The HTML string of the input.
     */
    public function render($name, $value, $extra_attrs=array())
    {
        if ((bool)$value) {
            // We consider that if a value can be boolean casted to
            // true, then we check the box.
            $extra_attrs['checked'] = 'checked';
        }
        // Value of a checkbox is always "1" but when not checked, the
        // corresponding key in the form associative array is not set.
        return parent::render($name, '1', $extra_attrs);
    }

    /**
     * A non checked checkbox is simply not returned in the form array.
     *
     * @param string Name of the form.
     * @param array Submitted form data.
     * @return mixed Value or null if not defined.
     */
    public function valueFromFormData($name, $data)
    {
        if (!isset($data[$name]) or false === $data[$name] or 'false' === $data[$name]
            or (string)$data[$name] === '0' or $data[$name] == '') {
            return false;
        }
        return true;
    }
}