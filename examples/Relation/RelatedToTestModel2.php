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

use Pluf_Model;
use Pluf\Data\Schema;

class RelatedToTestModel2 extends Pluf_Model
{

    /**
     * Load data model
     *
     * {@inheritdoc}
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'relatedtotestmodel2';
        $this->_a['cols'] = [
            'id' => [
                'type' => Schema::SEQUENCE,
                'nullable' => true
            ],
            'dummy' => [
                'type' => Schema::VARCHAR,
                'nullable' => false,
                'size' => 100
            ],
            'testmodel_1' => [
                'type' => Schema::MANY_TO_ONE,
                'nullable' => false,
                'inverseJoinModel' => Model::class,
                'columne' => 'testmodel_1'
            ],
            'testmodel_2' => [
                'type' => Schema::MANY_TO_ONE,
                'nullable' => false,
                'inverseJoinModel' => Model::class,
                'columne' => 'testmodel_2'
            ]
        ];
    }
}

