<?php

/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. http://dpq.co.ir
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
namespace Pluf\Search;

use Pluf\Model;

/**
 * Keep track of when a document has been last indexed and the number
 * of indexations.
 */
class Stats extends Model
{

    function init()
    {
        $this->_a['verbose'] = 'search stats';
        $this->_a['table'] = 'pluf_search_stats';
        $this->_a['cols'] = array(
            // It is mandatory to have an "id" column.
            'id' => array(
                'type' => '\Pluf\DB\Field\Sequence',
                // It is automatically added.
                'blank' => true
            ),
            'model_class' => array(
                'type' => '\Pluf\DB\Field\Varchar',
                'blank' => false,
                'size' => 150,
                'verbose' => __('model class')
            ),
            'model_id' => array(
                'type' => '\Pluf\DB\Field\Integer',
                'blank' => false,
                'verbose' => __('model id')
            ),
            'indexations' => array(
                'type' => '\Pluf\DB\Field\Integer',
                'blank' => false,
                'verbose' => __('number of indexations'),
                'default' => 0
            ),
            'creation_dtime' => array(
                'type' => '\Pluf\DB\Field\Datetime',
                'blank' => true,
                'verbose' => __('created at')
            ),
            'modif_dtime' => array(
                'type' => '\Pluf\DB\Field\Datetime',
                'blank' => true,
                'verbose' => __('modified at')
            )
        );
        $this->_a['idx'] = array(
            'model_class_id_combo_idx' => array(
                'type' => 'unique',
                'col' => 'model_class, model_id'
            )
        );
    }

    function preSave($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }
}

