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
 * Default database field.
 */
class Pluf_DB_Field
{
    /**
     * The types are defined in the $mappings member variable of the
     * schema class of your database engine, for example
     * Pluf_DB_Schema_MySQL.
     */
    public $type = '';

    /**
     * The column name of the field.
     */
    public $column = '';

    /**
     * Current value of the field.
     */
    public $value;

    /**
     * All the extra parameters of the field.
     */
    public $extra = array();

    /**
     * The extra methods added to the model by the field.
     */
    public $methods = array();

    /**
     * Constructor.
     *
     * @param mixed Value ('')
     * @param string Column name ('')
     */
    function __construct($value='', $column='', $extra=array())
    {
        $this->value = $value;
        $this->column = $column;
        if ($extra) {
            $this->extra = array_merge($this->extra, $extra);
        }
    }

    /**
     * Get the form field for this field.
     *
     * We put this method at the field level as it allows us to easily
     * create a new DB field and a new Form field and use them without
     * the need to modify another place where the mapping would be
     * performed.
     *
     * @param array Definition of the field.
     * @param string Form field class.
     */
    function formField($def, $form_field='Pluf_Form_Field_Varchar')
    {
        Pluf::loadClass('Pluf_Form_BoundField'); // To get mb_ucfirst
        $defaults = array('required' => !$def['blank'], 
                          'label' => mb_ucfirst($def['verbose']), 
                          'help_text' => $def['help_text']);
        unset($def['blank'], $def['verbose'], $def['help_text']);
        if (isset($def['default'])) {
            $defaults['initial'] = $def['default'];
            unset($def['default']);
        }
        if (isset($def['choices'])) {
            $defaults['widget'] = 'Pluf_Form_Widget_SelectInput';
            if (isset($def['widget_attrs'])) {
                $def['widget_attrs']['choices'] = $def['choices'];
            } else {
                $def['widget_attrs'] = array('choices' => $def['choices']);
            }
        }
        foreach (array_keys($def) as $key) {
            if (!in_array($key, array('widget', 'label', 'required', 'multiple',
                                      'initial', 'choices', 'widget_attrs'))) {
                unset($def[$key]);
            }
        }
        $params = array_merge($defaults, $def);
        return new $form_field($params);
    }

}

