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
 * ساختار داده‌ای یک خانه را تعیین می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Inbox_Message extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'inbox_message';
        $this->_a['model'] = 'Inbox_Message';
        $this->_model = 'Inbox_Message';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ),
                'from_id' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'verbose' => __('model ID')
                ),
                'from_class' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 50,
                        'verbose' => __('model class')
                ),
                'to_id' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'verbose' => __('owner ID')
                ),
                'to_class' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 50,
                        'verbose' => __('owner class'),
                        'help_text' => __(
                                'For example User or Group.')
                ),
                'title' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 250,
                        'verbose' => __('title'),
                        'help_text' => __(
                                'The title of the page must only contain letters, digits or the dash character. For example: My-new-Wiki-Page.')
                ),
                'summery' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 250,
                        'verbose' => __('summery'),
                        'help_text' => __(
                                'A one line description of the message content.')
                ),
                'content' => array(
                        'type' => 'Pluf_DB_Field_Compressed',
                        'blank' => false,
                        'verbose' => __('content')
                ),
                'creation_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('creation date')
                ),
                'modif_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('modification date')
                )
        );
    }

    /**
     * \brief پیش ذخیره را انجام می‌دهد
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
    
    /**
     * یک نمونه جدید پیام ایجاد می‌کند.
     * 
     * @param unknown $owner
     * @param unknown $object
     * @param unknown $title
     * @param unknown $content
     */
    public static function add ($owner, $object, $title, $content)
    {
        $nperm = new Inbox_Message();
        $nperm->from_id = $owner->id;
        $nperm->from_class = $owner->_a['model'];
        $nperm->to_id = $object->id;
        $nperm->to_class = $object->_a['model'];
        $nperm->title = $title;
        $nperm->content = $content;
        $nperm->create();
        return $nperm;
    }

}