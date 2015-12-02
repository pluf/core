<?php

/**
 * ساختار داده‌ای یک مکان را تعیین می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Jayab_Vote extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'jayab_vote';
        $this->_a['model'] = 'Jayab_Vote';
        $this->_model = 'Jayab_Vote';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ),
                'voter' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'Pluf_User',
                        'blank' => false,
                        'verbose' => __('user'),
                        'help_text' => __('id of a user')
                ),
                'location' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'Jayab_Location',
                        'blank' => false,
                        'verbose' => __('location'),
                        'help_text' => __('id of a location')
                ),
                'like' => array(
                        'type' => 'Pluf_DB_Field_Boolean',
                        'blank' => false,
                        'verbose' => __('like'),
                        'help_text' => __('like or dislike filed of a location')
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
     * حالت کار ایجاد شده را به روز می‌کند
     *
     * @see Pluf_Model::postSave()
     */
    function postSave ($create = false)
    {
        //
    }
}