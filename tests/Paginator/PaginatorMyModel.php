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
namespace Pluf\PlufTest\Paginator;

use Pluf\Model;

class PaginatorMyModel extends Model
{

    function init()
    {
        $this->_a['table'] = 'testformmodels';
        $this->_a['model'] = 'TestFormModel';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => '\Pluf\DB\Field\Sequence',
                'blank' => true
            ), // It is automatically added.
            'title' => array(
                'type' => '\Pluf\DB\Field\Varchar',
                'blank' => false,
                'size' => 100,
                'verbose' => 'Title of the item'
            ),
            'description' => array(
                'type' => '\Pluf\DB\Field\Text',
                'blank' => true,
                'help_text' => 'This is a small description'
            ),
            'creation_dtime' => array(
                'type' => '\Pluf\DB\Field\Datetime',
                'blank' => true,
                'is_null' => true,
                'editable' => false,
                'readable' => true
            ),
            'int_field' => array(
                'type' => '\Pluf\DB\Field\Integer',
                'blank' => true,
                'is_null' => true,
                'editable' => true,
                'readable' => true
            ),
            'float_field' => array(
                'type' => '\Pluf\DB\Field\FloatPoint',
                'blank' => true,
                'is_null' => true,
                'editable' => true,
                'readable' => true
            )
        );
        $this->_admin = array(
            'list_display' => array(
                'id',
                array(
                    'title',
                    'TestFormModel_ConvertTitle'
                ),
                array(
                    'title',
                    'TestFormModel_ConvertTitle',
                    'My Title'
                )
            ),
            'search_fields' => array(
                'title',
                'description'
            )
        );
        $this->_a['views'] = array(
            'test_view' => array()
        );
    }

    /**
     * \brief پیش ذخیره را انجام می‌دهد
     *
     * @param $create boolean
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
    }
}

function TestFormModel_ConvertTitle($field, $item)
{
    return '"' . $item->$field . '"';
}

