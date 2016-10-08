<?php

/**
 * ساختار داده‌ای پروفایل کاربر را تعیین می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class User_Profile extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * تمام فیلدهای مورد نیاز برای این مدل داده‌ای در این متد تعیین شده و به
     * صورت کامل ساختار دهی می‌شود.
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'user_profile';
        $this->_a['model'] = 'User_Profile';
        $this->_model = 'User_Profile';
        
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => true,
                'editable' => false
            ),
            'user' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'Pluf_User',
                'blank' => false,
                'unique' => true,
                'editable' => false
            )
            ,
            'level' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'blank' => false,
                'unique' => false,
                'editable' => false
            ),
            'access_count' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'blank' => false,
                'unique' => false
            ),
            'validate' => array(
                'type' => 'Pluf_DB_Field_Boolean',
                'default' => false,
                'blank' => true,
                'verbose' => __('validate'),
                'editable' => false
            ),
            'title' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 50
            ),
            'state' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 50
            ),
            'city' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 50
            ),
            'country' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 50
            ),
            'address' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 200
            ),
            'national_id' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 100,
                'editable' => false
            ),
            'postal_code' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 100,
                'editable' => false
            ),
            'phone_number' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 50,
                'unique' => false
            ),
            'mobile_number' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 50,
                'unique' => false
            ),
            'shaba' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 70,
                'unique' => false,
                'editable' => false
            ),
            'creation_dtime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => true,
                'verbose' => __('creation date'),
                'editable' => false
            ),
            'modif_dtime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => true,
                'verbose' => __('modification date'),
                'editable' => false
            )
        );
    }

    /**
     * پیش ذخیره را انجام می‌دهد
     *
     * در این فرآیند نیازهای ابتدایی سیستم به آن اضافه می‌شود. این نیازها مقادیری هستند که
     * در زمان ایجاد باید تعیین شوند. از این جمله می‌توان به کاربر و تاریخ اشاره کرد.
     *
     * @param $create حالت
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
            $this->access_count = 0;
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }
}