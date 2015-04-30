<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
#
# Plume Framework is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation; either version 2.1 of the License, or
# (at your option) any later version.
#
# Plume Framework is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

class Pluf_Group extends Pluf_Model
{
    public $_model = 'Pluf_Group';

    function init()
    {
        $this->_a['verbose'] = __('group');
        $this->_a['table'] = 'groups';
        $this->_a['model'] = 'Pluf_Group';
        $this->_a['cols'] = array(
                             // It is mandatory to have an "id" column.
                            'id' =>
                            array(
                            	'type' => 'Pluf_DB_Field_Sequence',
                            	'blank' => true, 
                            	),
			        		'version' =>
			        		array(
			        			'type' => 'Pluf_DB_Field_Integer',
			        			'blank' => true,
			        			),
                            'name' =>
                            array(
                                  'type' => 'Pluf_DB_Field_Varchar',
                                  'blank' => false,
                                  'size' => 50,
                                  'verbose' => __('name'),
                                  ),
                            'description' => 
                            array(
                                  'type' => 'Pluf_DB_Field_Varchar',
                                  'blank' => false,
                                  'size' => 250,
                                  'verbose' => __('description'),
                                  ),
                            'permissions' =>
                            array(
                                  'type' => 'Pluf_DB_Field_Manytomany', 
                                  'blank' => true,
                                  'model' => 'Pluf_Permission',
                                  ),
                            );
        if (Pluf::f('pluf_custom_group',false)) $this->extended_init();
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
     * Predelete to drop the row level permissions.
     */
    function preDelete()
    {
        if (Pluf::f('pluf_use_rowpermission', false)) {
            $_rpt = Pluf::factory('Pluf_RowPermission')->getSqlTable();
            $sql = new Pluf_SQL('owner_class=%s AND owner_id=%s',
                                array($this->_a['model'], $this->_data['id']));
            $this->_con->execute('DELETE FROM '.$_rpt.' WHERE '.$sql->gen());
        }
    }
}
