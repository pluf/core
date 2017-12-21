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
class Note_Item extends Pluf_Model
{

    function init ()
    {
        $this->_a['table'] = 'note_item';
        $this->_a['verbose'] = 'Note item';
        $this->_a['cols'] = array(
                // It is mandatory to have an "id" column.
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        // It is automatically added.
                        'blank' => true,
                        'editable' => false,
                        'readable' => true
                ),
                'title' => array(
                        'type' => 'Pluf_DB_Field_Text',
                        'blank' => false,
                        'editable' => true,
                        'readable' => true
                ),
                'body' => array(
                        'type' => 'Pluf_DB_Field_Text',
                        'blank' => false,
                        'editable' => true,
                        'readable' => true
                ),
                'creation_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'editable' => false,
                        'readable' => true
                )
        );
    }


    /**
     * 
     * {@inheritDoc}
     * @see Pluf_Model::preSave()
     */
    function preSave ($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
    }
}
