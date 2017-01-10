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
 * ساختارهای پیام سیستم را ایجاد می‌کند
 * 
 * این پیام‌ها توسط سیستم ایجاد شده و برای کاربران ارسال می‌شود. این پیام‌ها
 * تغییراتی که مربوط به داده‌ها و یا موجودیت‌های کاربر باشد صادر می‌شود و باید
 * به مرور زمان از بین برود.
 * 
 * @author maso
 *
 */
class Pluf_Message extends Pluf_Model
{

    public $_model = 'Pluf_Message';

    function init()
    {
        $this->_a['table'] = 'messages';
        $this->_a['model'] = 'Pluf_Message';
        $this->_a['cols'] = array(
            // It is mandatory to have an "id" column.
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                // It is automatically added.
                'blank' => true,
                'editable' => false,
                'readable' => true
            ),
            'version' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'blank' => true,
                'editable' => false,
                'readable' => true
            ),
            'user' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => Pluf::f('pluf_custom_user', 'Pluf_User'),
                'blank' => false,
                'verbose' => __('user'),
                'editable' => false,
                'readable' => false
            ),
            'message' => array(
                'type' => 'Pluf_DB_Field_Text',
                'blank' => false,
                'editable' => false,
                'readable' => true
            ),
            'creation_dtime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => true,
                'editable' => false,
                'readable' => true
            ),
            'tenant' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'blank' => false,
                'editable' => false,
                'readable' => false
            )
        );
        $this->_a['idx'] = array(
            'message_user_idx' => array(
                'type' => 'normal',
                'col' => 'tenant, user'
            )
        );
    }

    function __toString()
    {
        return $this->message;
    }

    /**
     * پیش ذخیره را انجام می‌دهد
     *
     * @param $create حالت
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
    }
}
