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
 * ساختار داده‌ای کامنت را ایجاد می‌کند.
 * 
 * @author maso
 *
 */
class KM_Comment extends Pluf_Model
{

    /**
     * {@inheritDoc}
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_model = 'KM_Comment';
        $this->_a['table'] = 'km_comment';
        $this->_a['model'] = 'KM_Comment';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ),
                'model_id' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'verbose' => __('model ID')
                ),
                'model_class' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 50,
                        'verbose' => __('model class')
                ),
                'owner_id' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'verbose' => __('owner ID')
                ),
                'owner_class' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 50,
                        'verbose' => __('owner class'),
                        'help_text' => __(
                                'For example Pluf_User or Pluf_Group.')
                ),
                'visible' => array(
                        'type' => 'Pluf_DB_Field_Boolean',
                        'blank' => false,
                        'default' => false,
                        'verbose' => __('do not have the permission')
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
                ),
                'comment' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 1024,
                        'verbose' => __('comment text')
                ),
                'changes' => array(
                        'type' => 'Pluf_DB_Field_Serialized',
                        'blank' => true,
                        'verbose' => __('changes'),
                        'help_text' => __(
                                'serialized array of the changes in the issue.')
                )
        );
        $this->_a['idx'] = array(
                'common_combo_idx' => array(
                        'type' => 'unique',
                        'col' => 'model_id, model_class, owner_id, owner_class'
                ),
                'creation_dtime_idx' => array(
                        'col' => 'creation_dtime',
                        'type' => 'normal'
                )
        );
    }

    /**
     * پیش ذخیره را انجام می‌دهد
     *
     * در این فرآیند نیازهای ابتدایی سیستم به آن اضافه می‌شود. این نیازها
     * مقادیری هستند که
     * در زمان ایجاد باید تعیین شوند. از این جمله می‌توان به کاربر و تاریخ اشاره
     * کرد.
     *
     * @param $create حالت
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave ($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
            $this->access_count = 0;
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }

    /**
     * یک نمونه جدید از این کلاس ایجاد می‌کند
     *
     * نمونه ایجاد شده به عنوان نتیجه فراخوانی بازگردانده می‌شود.
     *
     * @param unknown $owner            
     * @param unknown $object            
     * @param unknown $comment            
     * @param string $visible            
     */
    public static function add ($owner, $object, $comment, $visible = true)
    {
        $nperm = new Pluf_RowPermission();
        $nperm->owner_id = $owner->id;
        $nperm->owner_class = $owner->_a['model'];
        $nperm->model_id = $object->id;
        $nperm->model_class = $object->_a['model'];
        $nperm->comment = $comment;
        $nperm->visible = $visible;
        $nperm->create();
        return $nperm;
    }
}