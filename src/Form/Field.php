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
namespace Pluf\Form;

use Pluf\FormInvalidException;
use Pluf\Utils;
use Pluf\Bootstrap;

/**
 * Default form field.
 *
 * A form field is providing a defined set of methods and properties
 * to be used in the rendering of the fields in forms, in the
 * conversion of the data from the user input to a form usable by the
 * models.
 */
class Field
{

    /**
     * Store the name of the class.
     */
    public $class = 'Pluf_Form_Field';

    /**
     * Widget.
     * The way to "present" the field to the user.
     */
    public $widget = 'Pluf_Form_Widget_TextInput';

    public $label = '';

    /**
     * < The label of the field.
     */
    public $required = false;

    /**
     * < Allowed to be blank.
     */
    public $help_text = '';

    /**
     * < Help text for the field.
     */
    public $initial = '';

    /**
     * < Default value when empty.
     */
    public $choices = null;

    /**
     * < Predefined choices for the field.
     */

    /*
     * Following member variables are more for internal cooking.
     */
    public $hidden_widget = 'Pluf_Form_Widget_HiddenInput';

    public $value = '';

    /**
     * < Current value of the field.
     */
    /**
     * Returning multiple values (select multiple etc.)
     */
    public $multiple = false;

    protected $empty_values = array(
        '',
        null,
        array()
    );

    /**
     * Constructor.
     *
     * Example:
     * $field = new Your_Field(array('required'=>true,
     * 'widget'=>'Pluf_Form_Widget_TextInput',
     * 'initial'=>'your name here',
     * 'label'=>__('Your name'),
     * 'help_text'=>__('You are?'));
     *
     * @param
     *            array Params of the field.
     */
    function __construct($params = array())
    {
        // We basically take the parameters, for each one we grab the
        // corresponding member variable and populate the $default
        // array with. Then we merge with the values given in the
        // parameters and update the member variables.
        // This allows to pass extra parameters likes 'min_size'
        // etc. and update the member variables accordingly. This is
        // practical when you extend this class with your own class.
        $default = array();
        foreach ($params as $key => $in) {
            if ($key !== 'widget_attrs')
                // Here on purpose it will fail if a parameter not needed for
                // this field is passed.
                $default[$key] = $this->$key;
        }
        $m = array_merge($default, $params);
        foreach ($params as $key => $in) {
            if ($key !== 'widget_attrs')
                $this->$key = $m[$key];
        }

        // Widget is not supported anymore
        // Set the widget to be an instance and not the string name.
        // $widget_name = $this->widget;
        // if (isset($params['widget_attrs'])) {
        // $attrs = $params['widget_attrs'];
        // } else {
        // $attrs = array();
        // }
        // $widget = new $widget_name($attrs);
        // $attrs = $this->widgetAttrs($widget);
        // if (count($attrs)) {
        // $widget->attrs = array_merge($widget->attrs, $attrs);
        // }
        // $this->widget = $widget;
    }

    /**
     * Validate some possible input for the field.
     *
     * @param
     *            mixed Value to clean.
     * @return mixed Cleaned data or throw a Pluf_Form_Invalid exception.
     */
    function clean($value)
    {
        if (! $this->multiple and $this->required and in_array($value, $this->empty_values)) {
            throw new FormInvalidException('This field is required.');
        }
        if ($this->multiple and $this->required and empty($value)) {
            throw new FormInvalidException('This field is required.');
        }
        return $value;
    }

    /**
     * Set the default empty value for a field.
     *
     * @param
     *            mixed Value
     * @return mixed Value
     */
    function setDefaultEmpty($value)
    {
        if (in_array($value, $this->empty_values) and ! $this->multiple) {
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
     * @see Field\Integer::clean
     *
     * @param
     *            array Values
     * @return array Values
     */
    public function multiClean($value)
    {
        $field = clone ($this);
        $field->multiple = false;
        reset($value);
        // XXX: hadi 1397-09: The each() function is deprecated from PHP 7.2
        // Ref: http://php.net/manual/en/function.each.php
        while (list ($i, $val) = each($value)) {
            $value[$i] = $field->clean($val);
        }
        reset($value);
        return $value;
    }

    /**
     * Returns the HTML attributes to add to the field.
     *
     * @param
     *            object Widget
     * @return array HTML attributes.
     */
    public function widgetAttrs($widget)
    {
        return array();
    }

    /**
     * Default move function.
     * The file name is sanitized.
     *
     * In the extra parameters, options can be used so that this function is
     * matching most of the needs:
     *
     * * 'upload_path': The path in which the uploaded file will be
     * stored.
     * * 'upload_path_create': If set to true, try to create the
     * upload path if not existing.
     *
     * * 'upload_overwrite': Set it to true if you want to allow overwritting.
     *
     * * 'file_name': Force the file name to this name and do not use the
     * original file name. If this name contains '%s' for
     * example 'myid-%s', '%s' will be replaced by the
     * original filename. This can be used when for
     * example, you want to prefix with the id of an
     * article all the files attached to this article.
     *
     * If you combine those options, you can dynamically generate the path
     * name in your form (for example date base) and let this upload
     * function create it on demand.
     *
     * @param
     *            array Upload value of the form.
     * @param
     *            array Extra parameters. If upload_path key is set, use it. (array())
     * @return string Name relative to the upload path.
     */
    public static function moveToUploadFolder($value, $params = array())
    {
        $name = Utils::cleanFileName($value['name']);
        $upload_path = Bootstrap::f('upload_path', '/tmp');
        if (isset($params['file_name'])) {
            if (false !== strpos($params['file_name'], '%s')) {
                $name = sprintf($params['file_name'], $name);
            } else {
                $name = $params['file_name'];
            }
        }
        if (isset($params['upload_path'])) {
            $upload_path = $params['upload_path'];
        }
        $dest = $upload_path . '/' . $name;
        if (isset($params['upload_path_create']) and ! is_dir(dirname($dest))) {
            if (false == @mkdir(dirname($dest), 0777, true)) {
                throw new FormInvalidException('An error occured when creating the upload path. Please try to send the file again.');
            }
        }
        if ((! isset($params['upload_overwrite']) or $params['upload_overwrite'] == false) and file_exists($dest)) {
            throw new FormInvalidException(sprintf('A file with the name "%s" has already been uploaded.', $name));
        }
        if (@! move_uploaded_file($value['tmp_name'], $dest)) {
            throw new FormInvalidException('An error occured when uploading the file. Please try to send the file again.');
        }
        @chmod($dest, 0666);
        return $name;
    }

    /**
     * A widget can split itself in multiple input form.
     * For example
     * you can have a datetime value in your model and you use 2
     * inputs one for the date and one for the time to input the
     * value. So the widget must know how to get back the values from
     * the submitted form.
     *
     * @param
     *            string Name of the form.
     * @param
     *            array Submitted form data.
     * @return mixed Value or null if not defined.
     */
    public function valueFromFormData($name, $data)
    {
        if (isset($data[$name])) {
            return $data[$name];
        }
        return null;
    }
}

