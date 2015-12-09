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

/**
 * Keep track of when a document has been last indexed and the number
 * of indexations.
 */
class Pluf_Search_Stats extends Pluf_Model
{
    public $_model = __CLASS__;

    function init()
    {
        $this->_a['verbose'] = __('search stats');
        $this->_a['table'] = 'pluf_search_stats';
        $this->_a['model'] = __CLASS__;
        $this->_a['cols'] = array(
                             // It is mandatory to have an "id" column.
                            'id' =>
                            array(
                                  'type' => 'Pluf_DB_Field_Sequence',
                                  //It is automatically added.
                                  'blank' => true, 
                                  ),
                            'model_class' =>
                            array(
                                  'type' => 'Pluf_DB_Field_Varchar',
                                  'blank' => false,
                                  'size' => 150,
                                  'verbose' => __('model class'),
                                  ),
                            'model_id' =>
                            array(
                                  'type' => 'Pluf_DB_Field_Integer',
                                  'blank' => false,
                                  'verbose' => __('model id'),
                                  ),
                            'indexations' =>
                            array(
                                  'type' => 'Pluf_DB_Field_Integer',
                                  'blank' => false,
                                  'verbose' => __('number of indexations'),
                                  'default' => 0,
                                  ),
                            'creation_dtime' =>
                            array(
                                  'type' => 'Pluf_DB_Field_Datetime',
                                  'blank' => true,
                                  'verbose' => __('created at'),
                                  ),
                            'modif_dtime' =>
                            array(
                                  'type' => 'Pluf_DB_Field_Datetime',
                                  'blank' => true,
                                  'verbose' => __('modified at'),
                                  ),
                            );
        $this->_a['idx'] = array(                           
                            'model_class_id_combo_idx' =>
                            array(
                                  'type' => 'unique',
                                  'col' => 'model_class, model_id',
                                  ),
                            );

    }

    function preSave($create=false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }

}

