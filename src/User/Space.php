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
 * ساختار داده‌ای یک فضای کاربری را تعیین می‌کند
 *
 * @author maso
 *        
 */
class User_Space extends Pluf_Model
{

    public $data = array();

    public $touched = false;

    // /**
    // * یک نمونه جدید از این کلاس ایجاد می‌کند.
    // *
    // * @see Pluf_Model::_init()
    // */
    // function _init ()
    // {
    // parent::_init();
    // }
    
    /**
     * ساختارهای داده‌ای مورد نیاز برای اطلاعات خاص کاربر را تعیین می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $user_model = Pluf::f('pluf_custom_user', 'Pluf_User');
        
        $this->_a['table'] = 'user_space';
        $this->_a['verbose'] = 'user_space';
        $this->_a['multitenant'] = true;
        $this->_a['cols'] = array(
            // It is mandatory to have an "id" column.
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                // It is automatically added.
                'blank' => true
            ),
            'space_data' => array(
                'type' => 'Pluf_DB_Field_Text',
                'blank' => false
            ),
            'user' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => $user_model,
                'blank' => false,
                'unique' => true,
                'editable' => false
            )
        );
        $this->_a['idx'] = array(
            'user_id_idx' => array(
                'type' => 'unique',
                'col' => 'user'
            )
        );
        
        $this->_admin = array();
        $this->_a['views'] = array();
    }

    /**
     * تعیین یک داده در نشست
     *
     * با استفاده از این فراخوانی می‌توان یک داده با کلید جدید در نشست ایجاد
     * کرد. این کلید برای دستیابی‌های
     * بعد مورد استفاده قرار خواهد گرفت.
     *
     * @param
     *            کلید داده
     * @param
     *            داده مورد نظر. در صورتی که مقدار آن تهی باشد به معنی
     *            حذف است.
     */
    function setData($key, $value = null)
    {
        if (is_null($value)) {
            unset($this->data[$key]);
        } else {
            $this->data[$key] = $value;
        }
        $this->touched = true;
    }

    /**
     * داده معادل با کلید تعیین شده را برمی‌گرداند
     *
     * در صورتی که داده تعیین نشده بود مقدار پیش فرض تعیین شده به عنوان نتیجه
     * این فراخوانی
     * برگردانده خواهد شد.
     */
    function getData($key = null, $default = '')
    {
        if (is_null($key)) {
            return parent::getData();
        }
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            return $default;
        }
    }

    /**
     * تمام داده‌های موجود در نشت را پاک می‌کند.
     *
     * تمام داده‌های ذخیره شده در نشست را پاک می‌کند.
     */
    function clear()
    {
        $this->data = array();
        $this->touched = true;
    }

    /**
     * Presave/create function to encode data into session_data.
     */
    function preSave($create = false)
    {
        $this->space_data = serialize($this->data);
    }

    /**
     * Restore function to decode the session_data into $this->data.
     */
    function restore()
    {
        $this->data = unserialize($this->space_data);
    }
}
