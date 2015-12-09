<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
#
# Plume Framework is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation; either version 2.1 of the License, or
# (at your option) any later version.
#
# Plume Framework is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

/**
 * Default form field.
 *
 * A form field is providing a defined set of methods and properties
 * to be used in the rendering of the fields in forms, in the
 * conversion of the data from the user input to a form usable by the
 * models.
 */
class Pluf_Form_Field
{
    /**
     * Store the name of the class.
     */
    public $class = 'Pluf_Form_Field';

    /**
     * Widget. The way to "present" the field to the user.
     */
    public $widget = 'Pluf_Form_Widget_TextInput';
    public $label = ''; /**< The label of the field. */
    public $required = false; /**< Allowed to be blank. */
    public $help_text = ''; /**< Help text for the field. */
    public $initial = ''; /**< Default value when empty. */
    public $choices = null; /**< Predefined choices for the field. */

    /*
     * Following member variables are more for internal cooking.
     */
    public $hidden_widget = 'Pluf_Form_Widget_HiddenInput';
    public $value = ''; /**< Current value of the field. */
    /**
     * Returning multiple values (select multiple etc.)
     */
    public $multiple = false; 
    protected $empty_values = array('', null, array());

    /**
     * Constructor.
     *
     * Example:
     * $field = new Your_Field(array('required'=>true, 
     *                               'widget'=>'Pluf_Form_Widget_TextInput',
     *                               'initial'=>'your name here',
     *                               'label'=>__('Your name'),
     *                               'help_text'=>__('You are?'));
     *
     * @param array Params of the field.
     */
    function __construct($params=array())
    {
        // We basically take the parameters, for each one we grab the
        // corresponding member variable and populate the $default
        // array with. Then we merge with the values given in the
        // parameters and update the member variables.
        // This allows to pass extra parameters likes 'min_size'
        // etc. and update the member variables accordingly. This is
        // practical when you extend this class with your own class.
        $default = array();
        foreach ($params as $key=>$in) {
            if ($key !== 'widget_attrs')
                $default[$key] = $this->$key; // Here on purpose it
                                              // will fail if a
                                              // parameter not needed
                                              // for this field is
                                              // passed.
        }
        $m = array_merge($default, $params);
        foreach ($params as $key=>$in) {
            if ($key !== 'widget_attrs')
                $this->$key = $m[$key];
        }
        // Set the widget to be an instance and not the string name.
        $widget_name = $this->widget;
        if (isset($params['widget_attrs'])) {
            $attrs = $params['widget_attrs'];
        } else {
            $attrs = array();
        }
        $widget = new $widget_name($attrs);
        $attrs = $this->widgetAttrs($widget);
        if (count($attrs)) {
            $widget->attrs = array_merge($widget->attrs, $attrs);
        }
        $this->widget = $widget;
    }

    /**
     * Validate some possible input for the field.
     *
     * @param mixed Value to clean.
     * @return mixed Cleaned data or throw a Pluf_Form_Invalid exception.
     */
    function clean($value)
    {
        if (!$this->multiple and $this->required 
            and in_array($value, $this->empty_values)) {
            throw new Pluf_Form_Invalid(__('This field is required.'));
        }
        if ($this->multiple and $this->required and empty($value)) {
            throw new Pluf_Form_Invalid(__('This field is required.'));
        }
        return $value;
    }

    /**
     * Set the default empty value for a field.
     *
     * @param mixed Value
     * @return mixed Value
     */
    function setDefaultEmpty($value) 
    {
        if (in_array($value, $this->empty_values) and !$this->multiple) {
            $value = '';
        }
        if (in_array($value, $this->empty_values) and $this->multiple) {
            $value = array();
        }
        return $value;
    }

    /**
     * Multi-clean a value.
     *
     * If you are getting multiple values, you need to go through all
     * of them and validate them against the requirements. This will
     * do that for you. Basically, it is cloning the field, marking it
     * as not multiple and validate each value. It will throw an
     * exception in case of failure.
     *
     * If you are implementing your own field which could be filled by
     * a "multiple" widget, you need to perform a check on
     * $this->multiple.
     *
     * @see Pluf_Form_Field_Integer::clean
     *
     * @param array Values
     * @return array Values
     */
    public function multiClean($value)
    {
        $field = clone($this);
        $field->multiple = false;
        reset($value);
        while (list($i, $val) = each($value)) {
            $value[$i] = $field->clean($val);
        }
        reset($value);
        return $value;        
    }

    /**
     * Returns the HTML attributes to add to the field.
     *
     * @param object Widget
     * @return array HTML attributes.
     */
    public function widgetAttrs($widget)
    {
        return array();
    }

}

