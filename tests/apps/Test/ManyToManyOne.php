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
class ManyToManyOne extends \Pluf\Model
{

    function init()
    {
        $this->_a['table'] = 'test_manytomanyone';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => '\Pluf\DB\Field\Sequence',
                'blank' => true
            ), // It is automatically added.
            'twos' => array(
                'type' => '\Pluf\DB\Field\Manytomany',
                'blank' => true,
                'model' => '\Pluf\Test\ManyToManyTwo',
                'relate_name' => 'ones'
            ),
            'one' => array(
                'type' => '\Pluf\DB\Field\Varchar',
                'blank' => false,
                'size' => 100
            )
        );
        $this->_a['idx'] = array();
        $this->_a['views'] = array();
    }
}