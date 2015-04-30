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
 * Textarea.
 */
class Pluf_Form_Widget_TextareaInput extends Pluf_Form_Widget
{

    public function __construct($attrs=array())
    {
        $this->attrs = array_merge(array('cols' => '40', 'rows' => '10'), 
                                   $attrs);
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
        if ($value === null) $value = '';
        $final_attrs = $this->buildAttrs(array('name' => $name),
                                         $extra_attrs);
        return new Pluf_Template_SafeString(
                       sprintf('<textarea%s>%s</textarea>',
                               Pluf_Form_Widget_Attrs($final_attrs),
                               htmlspecialchars($value, ENT_COMPAT, 'UTF-8')),
                       true);
    }
}