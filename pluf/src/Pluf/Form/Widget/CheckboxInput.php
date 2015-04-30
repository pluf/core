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
        if (!isset($data[$name]) or false === $data[$name] 
            or (string)$data[$name] === '0' or $data[$name] == '') {
            return false;
        }
        return true;
    }
}