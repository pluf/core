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
namespace Pluf\DB;

use Pluf\Model;

class SchemaInfo extends Model
{

    function init()
    {
        $this->_a['verbose'] = 'permission';
        $this->_a['table'] = 'schema_info';
        $this->_a['multitenant'] = false;
        $this->_a['cols'] = array(
            // It is mandatory to have an "id" column.
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                // It is automatically added.
                'blank' => true
            ),
            'application' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'unique' => true
            ),
            'version' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'blank' => false
            )
        );
    }
}
