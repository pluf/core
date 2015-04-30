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

class Pluf_DB_Field_File extends Pluf_DB_Field
{
    /**
     * See definition in Pluf_DB_Field.
     */
    public $type = 'file';
    public $column = '';
    public $value;
    public $extra = array();
    public $methods = array();

    /**
     * Constructor.
     *
     * @param mixed Value ('')
     * @param string Column name ('')
     */
    function __construct($value='', $column='', $extra=array())
    {
        parent::__construct($value, $column, $extra);
        $this->methods = array(array(strtolower($column).'_url', 'Pluf_DB_Field_File_Url'),
                               array(strtolower($column).'_path', 'Pluf_DB_Field_File_Path')
                               );
    }

    function formField($def, $form_field='Pluf_Form_Field_File')
    {
        return parent::formField($def, $form_field);
    }
}

/**
 * Returns the url to access the file.
 */
function Pluf_DB_Field_File_Url($field, $method, $model, $args=null)
{
    if (strlen($model->$field) != 0) {
        return Pluf::f('upload_url').'/'.$model->$field;
    }
    return  '';
}

/**
 * Returns the path to access the file.
 */
function Pluf_DB_Field_File_Path($field, $method, $model, $args=null)
{
    if (strlen($model->$field) != 0) {
        return Pluf::f('upload_path').'/'.$model->$field;
    }
    return '';
}

