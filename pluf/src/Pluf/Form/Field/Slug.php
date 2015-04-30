<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2010 Loic d'Anterroches and contributors.
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

class Pluf_Form_Field_Slug extends Pluf_Form_Field
{
    /**
     * Name of the widget to use for build the forms.
     *
     * @var string
     */
    public $widget = 'Pluf_Form_Widget_TextInput';

    /**
     * Minimum size of field.
     *
     * Default to 1.
     *
     * @var int
     **/
    public $min_size = 1;

    /**
     * Maximum size of field.
     *
     * Default to 50.
     *
     * @var int
     **/
    public $max_size = 50;

    protected $_error_messages = array();

    public function __construct($params=array())
    {
        if (in_array($this->help_text, $this->empty_values)) {
            $this->help_text = __('The &#8220;slug&#8221; is the URL-friendly'.
                                  ' version of the name, consisting of '.
                                  'letters, numbers, underscores or hyphens.');
        }
        $this->_error_messages = array(
            'min_size' => __('Ensure this value has at most %1$d characters (it has %2$d).'),
            'max_size' => __('Ensure this value has at least %1$d characters (it has %2$d).')
        );

        parent::__construct($params);
    }

    /**
     * Removes any character not allowed and valid the size of the field.
     *
     * @see Pluf_Form_Field::clean()
     * @throws Pluf_Form_Invalid If the lenght of the field has not a valid size.
     */
    public function clean($value)
    {
        parent::clean($value);
        if ($value) {
            $value = Pluf_DB_Field_Slug::slugify($value);
            $len   = mb_strlen($value, Pluf::f('encoding', 'UTF-8'));
            if ($this->max_size < $len) {
                throw new Pluf_Form_Invalid(sprintf($this->_error_messages['max_size'],
                                                    $this->max_size,
                                                    $len));
            }
            if ($this->min_size > $len) {
                throw new Pluf_Form_Invalid(sprintf($this->_error_messages['min_size'],
                                                    $this->min_size,
                                                    $len));
            }
        }
        else
            $value = '';

        return $value;
    }

    /**
     * @see Pluf_Form_Field::widgetAttrs()
     */
    public function widgetAttrs($widget)
    {
        $attrs = array();
        if (!isset($widget->attrs['maxlength'])) {
            $attrs['maxlength'] = $this->max_size;
        } else {
            $this->max_size = $widget->attrs['maxlength'];
        }

        return $attrs;
    }
}
