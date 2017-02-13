<?php

/**
 * ساختار داده‌ای یک دنبال‌کننده را تعیین می‌کند.
 * 
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *
 */
class SaaSNewspaper_Follower extends Pluf_Model
{

    /**
     * مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_model = 'SaaSNewspaper_Follower';
        
        $this->_a['table'] = 'saasnewspaper_follower';
        $this->_a['model'] = 'SaaSNewspaper_Follower';
        $this->_a['cols'] = array(
            // شناسه‌ها
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => false
            ),
            // فیلدها
            'type' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'unique' => false
            ),
            'address' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'unique' => true
            ),
            'validated' => array(
                'type' => 'Pluf_DB_Field_Boolean',
                'blank' => false
            ),
            'creation_dtime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => false
            )
        );
        
        $this->_a['idx'] = array()
        // maso: 1395: روی کلیدهای خارجی به صورت خودکار اندیس گذاشته می‌شود.
        // 'device_idx' => array(
        // 'type' => 'unique'
        // )
        ;
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
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }

    /**
     * حالت کار ایجاد شده را به روز می‌کند
     *
     * @see Pluf_Model::postSave()
     */
    function postSave($create = false)
    {
        //
    }
}