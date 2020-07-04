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
namespace Pluf\Relation;

use Pluf\Data\Schema;
use Pluf_Model;

class ModelRecurse extends Pluf_Model
{

    function init()
    {
        $this->_a['table'] = 'testmodelrecurse';
        $this->_a['cols'] = [
            'id' => [
                'type' => Schema::SEQUENCE,
                'blank' => true
            ],
            'title' => [
                'type' => Schema::VARCHAR,
                'nullable' => false,
                'size' => 100
            ],
            'parent_id' => [
                'type' => Schema::FOREIGNKEY,
                'nullable' => true,
                'columne' => 'parent_id'
            ],
            'parent' => [
                'type' => Schema::MANY_TO_ONE,
                'mapped' => true,
                'joinProperty' => 'parent_id',
                'inverseJoinModel' => ModelRecurse::class,
                'inverseJoinProperty' => 'id'
            ],
            'children' => [
                'type' => Schema::ONE_TO_MANY,
                'mapped' => true,
                'joinProperty' => 'id',
                'inverseJoinModel' => ModelRecurse::class,
                'inverseJoinProperty' => 'parent_id'
            ]
        ];
    }
}
