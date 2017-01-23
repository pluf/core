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
 * The List model.
 *
 * A Todo_List is storing a list of Todo_Item(s)
 */
class Todo_List extends Pluf_Model
{

    public $_model = __CLASS__;

    /**
     * The init method is used to define your model.
     * It is very important to call "parent::init()" at the end.
     */
    function init ()
    {
        /**
         * The database table to store the model.
         * The table name will be prefixed with the prefix define
         * in the global configuration.
         */
        $this->_a['table'] = 'todo_lists';
        
        /**
         * The name of the model in the class definition.
         */
        $this->_a['model'] = 'Todo_List';
        
        /**
         * The definition of the model.
         * Each key of the associative array
         * corresponds to a "column" and the definition of the column is
         * given in the corresponding array.
         */
        $this->_a['cols'] = array(
                // It is mandatory to have an "id" column.
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        // It is automatically added.
                        'blank' => true
                ),
                'name' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 100,
                        // The verbose name is all lower case
                        'verbose' => __('name')
                )
        );
        /**
         * You can define the indexes.
         * Indexes are you to sort and find elements. Here we define
         * an index on the completed column to easily select the list
         * of completed or not completed elements.
         */
        $this->_a['idx'] = array();
        $this->_a['views'] = array();
    }

    /**
     * To nicely render the list in the option boxes.
     */
    function __toString ()
    {
        return $this->name;
    }
}
