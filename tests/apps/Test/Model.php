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
namespace Pluf\Test;

/**
 *
 * @author maso
 *        
 */
class Model extends \Pluf\Model
{

    /**
     *
     * {@inheritdoc}
     * @see Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'test_model';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => '\Pluf\DB\Field\Sequence',
                'blank' => true
            ), // It is automatically added.
            'title' => array(
                'type' => '\Pluf\DB\Field\Varchar',
                'blank' => false,
                'size' => 100
            ),
            'description' => array(
                'type' => '\Pluf\DB\Field\Text',
                'blank' => true
            )
        );
        $this->_a['idx'] = array(
            'title' => array(
                'col' => 'title',
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
    }
}

