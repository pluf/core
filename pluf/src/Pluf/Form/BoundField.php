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
 * A class to store field, widget and data.
 *
 * Used when rendering a form.
 */
class Pluf_Form_BoundField
{
    public $form = null;
    public $field = null;
    public $name = null;
    public $html_name = null;
    public $label = null;
    public $help_text = null;
    public $errors = array();

    public function __construct($form, $field, $name)
    {
        $this->form = $form;
        $this->field = $field;
        $this->name = $name;
        $this->html_name = $this->form->addPrefix($name);
        if ($this->field->label == '') {
            $this->label = mb_ereg_replace('/\_/', '/ /', mb_ucfirst($name));
        } else {
            $this->label = $this->field->label;
        }
        $this->help_text = ($this->field->help_text) ? $this->field->help_text : '';
        if (isset($this->form->errors[$name])) {
            $this->errors = $this->form->errors[$name];
        }
    }

    public function render_w($widget=null, $attrs=array())
    {
        if ($widget === null) {
            $widget = $this->field->widget;
        }
        $id = $this->autoId();
        if ($id and !array_key_exists('id', $attrs) 
            and !array_key_exists('id', $widget->attrs)) {
            $attrs['id'] = $id;
        }
        if (!$this->form->is_bound) {
            $data = $this->form->initial($this->name);
        } else {
            $data = $this->field->widget->valueFromFormData($this->html_name, $this->form->data);
        }
        return $widget->render($this->html_name, $data, $attrs);
    }

    /**
     * Returns the HTML of the label tag.  Wraps the given contents in
     * a <label>, if the field has an ID attribute. Does not
     * HTML-escape the contents. If contents aren't given, uses the
     * field's HTML-escaped label. If attrs are given, they're used as
     * HTML attributes on the <label> tag.
     *
     * @param string Content of the label, will not be escaped (null).
     * @param array Extra attributes.
     * @return string HTML of the label.
     */
    public function labelTag($contents=null, $attrs=array())
    {
        $contents = ($contents) ? $contents : htmlspecialchars($this->label);
        $widget = $this->field->widget;
        $id = (isset($widget->attrs['id'])) ? $widget->attrs['id'] : $this->autoId();
        $_tmp = array();
        foreach ($attrs as $attr=>$val) {
            $_tmp[] = $attr.'="'.$val.'"';
        }
        if (count($_tmp)) {
            $attrs = ' '.implode(' ', $_tmp);
        } else {
            $attrs = '';
        } 
        return new Pluf_Template_SafeString(sprintf('<label for="%s"%s>%s</label>',
                                                    $widget->idForLabel($id), $attrs, $contents), true);
    }


    /**
     * Calculates and returns the ID attribute for this BoundField, if
     * the associated Form has specified auto_id. Returns an empty
     * string otherwise.
     *
     * @return string Id or empty string if no auto id defined.
     */
    public function autoId()
    {
        $id_fields = $this->form->id_fields;
        if (false !== strpos($id_fields, '%s')) {
            return sprintf($id_fields, $this->html_name);
        } elseif ($id_fields) {
            return $this->html_name;
        }
        return '';
    }

    /**
     * Return HTML to display the errors.
     */
    public function fieldErrors()
    {
        Pluf::loadFunction('Pluf_Form_renderErrorsAsHTML');
        return new Pluf_Template_SafeString(Pluf_Form_renderErrorsAsHTML($this->errors), true);
    }

    /**
     * Overloading of the property access.
     */
    public function __get($prop)
    {
        if (!in_array($prop, array('labelTag', 'fieldErrors', 'render_w'))) {
            return $this->$prop;
        }
        return $this->$prop();
    }


    /**
     * Render as string.
     */
    public function __toString()
    {
        return (string)$this->render_w();
    }
}

if (!function_exists('mb_ucfirst')) {
    function mb_ucfirst($str) {
        return mb_strtoupper(mb_substr($str, 0, 1)).mb_substr($str, 1);
    }
}
