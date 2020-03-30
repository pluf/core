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

/**
 *
 * @author maso
 *        
 */
class Test_RelatedToTestModel extends Pluf_Model
{

    function init()
    {
        $this->_a['table'] = 'relatedtotestmodel';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Sequence',
                'blank' => true
            ), // It is automatically added.
            'testmodel' => array(
                'type' => 'Foreignkey',
                'blank' => false,
                'model' => 'Test_Model'
            ),
            'dummy' => array(
                'type' => 'Varchar',
                'blank' => false,
                'size' => 100
            )
        );
        $this->_a['idx'] = array(
            'testmodel_id' => array(
                'type' => 'normal',
                'col' => 'testmodel'
            )
        );
        $this->_a['views'] = array();
        parent::init();
    }
}

