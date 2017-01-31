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
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *        
 */
class Calender_Event extends Pluf_Model
{

    /**
     * مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'calender_event';
        $this->_a['cols'] = array(
                // شناسه‌ها
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => false,
                        'verbose' => __('id'),
                        'help_text' => __('event id'),
                        'editable' => false
                ),
                // فیلدها
                'title' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 250,
                        'default' => 'no title',
                        'verbose' => __('title'),
                        'help_text' => __('content title'),
                        'editable' => true
                ),
                'description' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 250,
                        'default' => 'auto created content',
                        'verbose' => __('description'),
                        'help_text' => __('content description'),
                        'editable' => true
                ),
                'location' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 250,
                        'default' => 'empty',
                        'verbose' => __('location'),
                        'help_text' => __('event location'),
                        'editable' => true
                ),
                'from' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'verbose' => __('creation'),
                        'help_text' => __('content creation time'),
                        'blank' => false,
                        'editable' => true
                ),
                'to' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'verbose' => __('modification'),
                        'help_text' => __('content modification time'),
                        'blank' => true,
                        'editable' => true
                ),
                // relations
                'calender' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'Calender_Calender',
                        'blank' => false,
                        'relate_name' => 'calender',
                        'editable' => false,
                        'readable' => true
                )
        );
        
        $this->_a['idx'] = array(
                'calender_idx' => array(
                        'col' => 'calender',
                        'type' => 'normal', // normal, unique, fulltext, spatial
                        'index_type' => '', // hash, btree
                        'index_option' => '',
                        'algorithm_option' => '',
                        'lock_option' => ''
                )
        );
    }

    /**
     * پیش ذخیره را انجام می‌دهد
     *
     * @param $create حالت
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave ($create = false)
    {
        if ($this->id == '') {
            // XXX: maso, 2017: if to date is empty set 1h+
            $toDate =$this->to;
            if(!isset($toDate) || $toDate == ''){
                $this->to = $this->from;
            }
            // Check if to < from
        }
    }

    /**
     * حالت کار ایجاد شده را به روز می‌کند
     *
     * @see Pluf_Model::postSave()
     */
    function postSave ($create = false)
    {
        //
    }
}