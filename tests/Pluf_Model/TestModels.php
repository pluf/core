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

class TestModel extends Pluf_Model
{

    function init ()
    {
        $this->_a['table'] = 'testmodel';
        $this->_a['model'] = 'TestModel';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ), // It is automatically added.
                'title' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 100
                ),
                'description' => array(
                        'type' => 'Pluf_DB_Field_Text',
                        'blank' => true
                )
        );
        $this->_a['idx'] = array(
                'title' => array(
                        'type' => 'normal'
                )
        );
        
        $this->_a['views'] = array(
                'simple' => array(
                        'select' => 'testmodel_id, title, description'
                ),
                '__unique__' => array(
                        'select' => 'testmodel_id'
                )
        );
        parent::init();
    }
}

class TestModelRecurse extends Pluf_Model
{

    function init ()
    {
        $this->_a['table'] = 'testmodelrecurse';
        $this->_a['model'] = 'TestModelRecurse';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ), // It is automatically added.
                'title' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 100
                ),
                'parentid' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'blank' => true,
                        'model' => 'TestModelRecurse',
                        'relate_name' => 'children'
                )
        );
        $this->_a['idx'] = array();
        $this->_a['views'] = array();
        parent::init();
    }
}

class RelatedToTestModel2 extends Pluf_Model
{

    function init ()
    {
        $this->_a['table'] = 'relatedtotestmodel2';
        $this->_a['model'] = 'RelatedToTestModel2';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ), // It is automatically added.
                'testmodel_1' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'blank' => false,
                        'model' => 'TestModel',
                        'relate_name' => 'first_rttm'
                ),
                'testmodel_2' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'blank' => false,
                        'model' => 'TestModel',
                        'relate_name' => 'second_rttm'
                ),
                'dummy' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 100
                )
        );
        parent::init();
    }
}

class RelatedToTestModel extends Pluf_Model
{

    function init ()
    {
        $this->_a['table'] = 'relatedtotestmodel';
        $this->_a['model'] = 'RelatedToTestModel';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ), // It is automatically added.
                'testmodel' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'blank' => false,
                        'model' => 'TestModel'
                ),
                'dummy' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
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

class ManyToManyOne extends Pluf_Model
{

    function init ()
    {
        $this->_a['table'] = 'manytomanyone';
        $this->_a['model'] = 'ManyToManyOne';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ), // It is automatically added.
                'two' => array(
                        'type' => 'Pluf_DB_Field_Manytomany',
                        'blank' => true,
                        'model' => 'ManyToManyTwo'
                ),
                'one' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 100
                )
        );
        $this->_a['idx'] = array();
        $this->_a['views'] = array();
        /*
         * 'simple' =>
         * array(
         * 'join' => 'JOIN unit_tests_manytomanyone_manytwomanytwo_assoc ON ',
         * ),
         * );
         */
    }
}

class ManyToManyTwo extends Pluf_Model
{

    function init ()
    {
        $this->_a['table'] = 'manytomanytwo';
        $this->_a['model'] = 'ManyToManyTwo';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ), // It is automatically added.
                'two' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 100
                )
        );
        $this->_a['idx'] = array();
        $this->_a['views'] = array();
    }
}
