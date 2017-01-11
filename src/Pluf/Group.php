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

/**
 * مدل داده‌ای یک گروه را ایجاد می‌کند.
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *        
 */
class Pluf_Group extends Pluf_Model
{

    function init()
    {
        $this->_a['table'] = 'groups';
        $this->_a['verbose'] = 'group';
        $this->_a['cols'] = array(
            // It is mandatory to have an "id" column.
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => true,
                'readable' => true,
                'editable' => false
            ),
            'version' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'blank' => true,
                'readable' => true,
                'editable' => false
            ),
            'name' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 50,
                'verbose' =>'name',
                'readable' => true,
                'editable' => true
            ),
            'description' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 250,
                'verbose' => 'description',
                'readable' => true,
                'editable' => true
            ),
            'permissions' => array(
                'type' => 'Pluf_DB_Field_Manytomany',
                'blank' => true,
                'model' => 'Pluf_Permission',
                'readable' => true
            )
        );
        $group_table = $this->_con->pfx . $this->_a['table'];
        $group_model = 'Pluf_Group';
        if (Pluf::f('pluf_use_rowpermission', false)) {
            $this->_a['views'] = array(
                'group_permission' => array(
                    'join' => 'LEFT JOIN rowpermissions ON ' . 
                    'rowpermissions.owner_id=' . $group_table . '.id AND rowpermissions.owner_class="' . $group_model . '"'
                )
            );
        } else {
            $hay = array(
                strtolower($group_model),
                strtolower($this->_a['model'])
            );
            sort($hay);
            $asso_table = $this->_con->pfx . $hay[0] . '_' . $hay[1] . '_assoc';
            $this->_a['views'] = array(
                'group_permission' => array(
                    'join' => 'LEFT JOIN ' . $asso_table . ' ON ' 
                    . $group_table . '.id='.strtolower($group_model).'_id'
                )
            );
        }
        if (Pluf::f('pluf_custom_group', false))
            $this->extended_init();
    }

    /**
     * Hook for extended class
     */
    function extended_init()
    {
        return;
    }

    function __toString()
    {
        return $this->name;
    }

    /**
     * تمام دسترسی‌ها حذف می‌شود.
     *
     * تمام دسترسی‌هایی که به این گروه داده شده است از سیستم حذف می‌شود.
     */
    function preDelete()
    {
        if (Pluf::f('pluf_use_rowpermission', false)) {
            $_rpt = Pluf::factory('Pluf_RowPermission')->getSqlTable();
            $sql = new Pluf_SQL('owner_class=%s AND owner_id=%s', array(
                $this->_a['model'],
                $this->_data['id']
            ));
            $this->_con->execute('DELETE FROM ' . $_rpt . ' WHERE ' . $sql->gen());
        }
    }
}
