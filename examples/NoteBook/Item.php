<?php
namespace Pluf\NoteBook;

use Pluf_Model;
use Pluf\Data\Schema;

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

/**
 *
 * @author maso
 *        
 */
class Item extends Pluf_Model
{

    function init()
    {
        $this->_a['table'] = 'notebook_item';
        $this->_a['verbose'] = 'Note item';
        $this->_a['cols'] = [
            // It is mandatory to have an "id" column.
            'id' => [
                'type' => 'Sequence',
                // It is automatically added.
                'blank' => true,
                'editable' => false,
                'readable' => true
            ],
            'title' => [
                'type' => 'Text',
                'blank' => false,
                'editable' => true,
                'readable' => true
            ],
            'body' => [
                'type' => 'Text',
                'blank' => false,
                'editable' => true,
                'readable' => true
            ],
            'creation_dtime' => [
                'type' => 'Datetime',
                'blank' => true,
                'editable' => false,
                'readable' => true
            ],
            'book_id' => [
                'type' => Schema::FOREIGNKEY,
                'columne' => 'book_id',
                'blank' => true,
                'editable' => true,
                'readable' => true
            ],
            'book' => [
                'type' => Schema::MANY_TO_ONE,
                'joinProperty' => 'book_id',
                'inverseJoinModel' => Book::class,
                'inverseJoinProperty' => 'id'
            ]
        ];
    }

    /**
     *
     * {@inheritdoc}
     * @see Pluf_Model::preSave()
     */
    function preSave($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
    }
}
