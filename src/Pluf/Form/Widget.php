<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. http://dpq.co.ir
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */


/**
 * Base class to display a form field.
 *
 */
class Pluf_Form_Widget
{
    public $is_hidden = false; /**< Is an hidden field? */
    public $needs_multipart_form = false; /**< Do we need multipart? */
    public $input_type = ''; /**< Input type of the field. */
    public $attrs = array(); /**< HTML attributes for the widget. */

    public function __construct($attrs=array())
    {
        $this->attrs = $attrs;
    }

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
        throw new Exception('Not Implemented.');
    }

    /**
     * Build the list of attributes for the form.
     * It should be called this way:
     * $this->buildAttrs(array('name'=>$name, 'type'=>$this->input_type),
     *                   $extra_attrs);
     *
     * @param array Contains the name and type attributes.
     * @param array Extra attributes, like 'class' for example.
     * @return array The attributes for the field.
     */
    protected function buildAttrs($attrs, $extra_attrs=array())
    {
        return array_merge($this->attrs, $attrs, $extra_attrs);
    }

    /**
     * A widget can split itself in multiple input form. For example
     * you can have a datetime value in your model and you use 2
     * inputs one for the date and one for the time to input the
     * value. So the widget must know how to get back the values from
     * the submitted form.
     *
     * @param string Name of the form.
     * @param array Submitted form data.
     * @return mixed Value or null if not defined.
     */
    public function valueFromFormData($name, $data)
    {
        if (isset($data[$name])) {
            return $data[$name];
        }
        return null;
    }

    /**
     * Returns the HTML ID attribute of this Widget for use by a
     * <label>, given the ID of the field. Returns None if no ID is
     * available.
     *
     * This hook is necessary because some widgets have multiple HTML
     * elements and, thus, multiple IDs. In that case, this method
     * should return an ID value that corresponds to the first ID in
     * the widget's tags.
     */
    public function idForLabel($id)
    {
        return $id;
    }
}

/**
 * Convert an array in a string ready to use for HTML attributes.
 *
 * As all the widget will extend the Pluf_Form_Widget class, it means
 * that this function is available directly in the extended class.
 */
function Pluf_Form_Widget_Attrs($attrs)
{
    $_tmp = array();
    foreach ($attrs as $attr=>$val) {
        $_tmp[] = $attr.'="'.$val.'"';
    }
    return ' '.implode(' ', $_tmp);
}