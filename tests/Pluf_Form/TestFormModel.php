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
Pluf::loadClass('Pluf_Model');

class TestFormModel extends Pluf_Model
{

    function init ()
    {
        $this->_a['table'] = 'testformmodels';
        $this->_a['model'] = 'TestFormModel';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Sequence',
                        'blank' => true
                ), // It is automatically added.
                'title' => array(
                        'type' => 'Varchar',
                        'blank' => false,
                        'size' => 100,
                        'verbose' => 'Title of the item'
                ),
                'description' => array(
                        'type' => 'Text',
                        'blank' => true,
                        'help_text' => 'This is a small description'
                )
        );
        $this->_admin = array(
                'list_display' => array(
                        'id',
                        array(
                                'title',
                                'TestFormModel::ConvertTitle'
                        ),
                        array(
                                'title',
                                'TestFormModel::ConvertTitle',
                                'My Title'
                        )
                ),
                'search_fields' => array(
                        'title',
                        'description'
                )
        );
        parent::init();
    }
    
    public static function ConvertTitle ($field, $item)
    {
        return '"' . $item->$field . '"';
    }
}

