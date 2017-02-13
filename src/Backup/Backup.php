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
 * @author maso <mostafa.barmshory@dpq.co.ir>
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *        
 *        
 */
class Backup_Backup extends Pluf_Model
{

    /**
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'backup';
        $this->_a['cols'] = array(
                // شناسه‌ها
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => false,
                        'verbose' => __('first name'),
                        'help_text' => __('id'),
                        'editable' => false,
                        'readable' => true
                ),
                'title' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'size' => 250,
                        'default' => 'no title',
                        'verbose' => __('title'),
                        'help_text' => __('content title'),
                        'blank' => true,
                        'editable' => true,
                        'readable' => true
                ),
                'description' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'size' => 250,
                        'default' => 'auto created content',
                        'verbose' => __('description'),
                        'help_text' => __('content description'),
                        'blank' => true,
                        'editable' => true,
                        'readable' => true
                ),
                'file_path' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'size' => 250,
                        'verbose' => __('file path'),
                        'help_text' => __('content file path'),
                        'blank' => false,
                        'editable' => false,
                        'readable' => false
                ),
                'creation_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'verbose' => __('creation'),
                        'help_text' => __('content creation time'),
                        'blank' => false,
                        'editable' => false,
                        'readable' => true
                ),
                'modif_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'verbose' => __('modification'),
                        'help_text' => __('content modification time'),
                        'blank' => false,
                        'editable' => false,
                        'readable' => true
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
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }

}