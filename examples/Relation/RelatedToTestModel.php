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

/**
 *
 * @author maso
 *        
 */
class RelatedToTestModel extends Pluf_Model
{

    /**
     * load data model
     *
     * {@inheritdoc}
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'relatedtotestmodel';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => Schema::SEQUENCE,
                'nullable' => true
            ), // It is automatically added.
            'dummy' => array(
                'type' => Schema::VARCHAR,
                'nullable' => false,
                'size' => 100
            ),
            /*
             * Relations:
             * 
             * We want to access the foreign key value and the object at
             * the same time. So, We add a foreign key and a many to one
             * relation on one colemne.
             */
            'testmodel_id' => array(
                'type' => Schema::FOREIGNKEY,
                'nullable' => false,
                'inverseJoinModel' => Model::class,
                'columne' => 'testmodel_id'
            ),
            'testmodel' => array(
                'type' => Schema::MANY_TO_ONE,
                'mapped' => true,
                'nullable' => false,
                'joinProperty' => 'testmodel_id',
                'inverseJoinModel' => Model::class,
                'columne' => 'testmodel_id'
            )
        );
    }

    /**
     *
     * {@inheritdoc}
     * @see Pluf_Model::loadIndexes()
     */
    public function loadIndexes(): array
    {
        return array(
            'testmodel_id' => array(
                'type' => 'normal',
                'col' => 'testmodel_id'
            )
        );
    }
}

