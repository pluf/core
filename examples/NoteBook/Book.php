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
namespace Pluf\NoteBook;

use Pluf_Model;
use Pluf\Data\Schema;
use Pluf\Db\Expression;

/**
 * Data model of a book
 *
 * @author maso
 * @Model(
 *  table='notebook_book',
 *  title='Book',
 *  multitinant=true,
 *  mapped=false,
 * )
 */
class Book extends Pluf_Model
{

    /**
     *
     * {@inheritdoc}
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'notebook_book';
        $this->_a['verbose'] = 'Note book';
        $this->_a['cols'] = [
            // It is mandatory to have an "id" column.
            'id' => [
                'type' => Schema::SEQUENCE,
                'primary' => true,
                // It is automatically added.
                'blank' => true,
                'editable' => false,
                'readable' => true
            ],
            'title' => [
                'type' => Schema::VARCHAR,
                'size' => 100,
                'blank' => false,
                'editable' => false,
                'readable' => true
            ],
            'description' => [
                'type' => 'Text',
                'blank' => false,
                'editable' => false,
                'readable' => true
            ],
            'creation_dtime' => [
                'type' => 'Datetime',
                'blank' => true,
                'editable' => false,
                'readable' => true
            ],
            'items' => [
                'type' => Schema::ONE_TO_MANY,
                // 'joinProperty' => 'id',
                'inverseJoinModel' => Item::class,
                'inverseJoinProperty' => 'book_id'
            ],
            'tags' => [
                'type' => Schema::MANY_TO_MANY,
                'joinProperty' => 'id',
                'inverseJoinModel' => Tag::class,
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

    public function loadViews(): array
    {
        return [
            'nonEmpty' => [
                'join' => [
                    [
                        'joinProperty' => 'id',
                        'inverseJoinModel' => Item::class,
                        'inverseJoinProperty' => 'book_id',
                        'alias' => 'item',
                        'type' => Schema::INNER_JOIN
                    ]
                ],
                'group' => [
                    'item.book_id'
                ],
                'having' => [
                    new Expression('count(*) > 0')
                ]
            ],
            'empty' => [
                'join' => [
                    [
                        'joinProperty' => 'items',
                        'alias' => 'item'
                    ]
                ],
                'having' => [
                    [
                        'item.book_id',
                        null
                    ]
                ]
            ]
        ];
    }
}
