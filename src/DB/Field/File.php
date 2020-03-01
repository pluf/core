<?php

/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
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
namespace Pluf\DB\Field;

use Pluf\DB\Field;
use Pluf\Bootstrap;

/**
 * Defines a file in the DB
 *
 * @author maso
 *        
 */
class File extends Field
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
     * @param
     *            mixed Value ('')
     * @param
     *            string Column name ('')
     */
    function __construct($value = '', $column = '', $extra = array())
    {
        parent::__construct($value, $column, $extra);
        $this->methods = array(
            array(
                strtolower($column) . '_url',
                '\\Pluf\\DB\\Field\\File_Url'
            ),
            array(
                strtolower($column) . '_path',
                '\\Pluf\\DB\\Field\\File_Path'
            )
        );
    }

    function formField($def, $form_field = 'Pluf_Form_Field_File')
    {
        return parent::formField($def, $form_field);
    }

    /**
     * Returns the url to access the file.
     */
    public static function url($field, $method, $model, $args = null)
    {
        if (strlen($model->$field) != 0) {
            return Bootstrap::f('upload_url') . '/' . $model->$field;
        }
        return '';
    }

    /**
     * Returns the path to access the file.
     */
    public static function path($field, $method, $model, $args = null)
    {
        if (strlen($model->$field) != 0) {
            return Bootstrap::f('upload_path') . '/' . $model->$field;
        }
        return '';
    }
}

