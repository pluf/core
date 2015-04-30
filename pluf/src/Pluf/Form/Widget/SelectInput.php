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
 * Simple checkbox with grouping.
 */
class Pluf_Form_Widget_SelectInput extends Pluf_Form_Widget
{
    public $choices = array();

    public function __construct($attrs=array())
    {
        $this->choices = $attrs['choices'];
        unset($attrs['choices']);
        parent::__construct($attrs);
    }

    /**
     * Renders the HTML of the input.
     *
     * @param string Name of the field.
     * @param mixed Value for the field, can be a non valid value.
     * @param array Extra attributes to add to the input form (array())
     * @param array Extra choices (array())
     * @return string The HTML string of the input.
     */
    public function render($name, $value, $extra_attrs=array(), 
                           $choices=array())
    {
        $output = array();
        if ($value === null) {
            $value = '';
        }
        $final_attrs = $this->buildAttrs(array('name' => $name), $extra_attrs);
        $output[] = '<select'.Pluf_Form_Widget_Attrs($final_attrs).'>';
        $groups = $this->choices + $choices;
        foreach($groups as $option_group => $c) {
            if (!is_array($c)) {
                $subchoices = array($option_group => $c);
            } else {
                $output[] = '<optgroup label="'.htmlspecialchars($option_group, ENT_COMPAT, 'UTF-8').'">';
                $subchoices = $c;
            }
            foreach ($subchoices as $option_label=>$option_value) {
                $selected = ($option_value == $value) ? ' selected="selected"':'';
                $output[] = sprintf('<option value="%s"%s>%s</option>',
                                    htmlspecialchars($option_value, ENT_COMPAT, 'UTF-8'),
                                    $selected, 
                                    htmlspecialchars($option_label, ENT_COMPAT, 'UTF-8'));
            }
            if (is_array($c)) {
                $output[] = '</optgroup>';
            }
        }
        $output[] = '</select>';
        return new Pluf_Template_SafeString(implode("\n", $output), true);
    }
}
