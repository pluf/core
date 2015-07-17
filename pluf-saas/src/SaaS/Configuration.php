<?php

/**
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class SaaS_Configuration extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'saas_configuration';
        $this->_a['model'] = 'SaaS_Configuration';
        $this->_model = 'SaaS_Configuration';
        
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ),
                'application' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'SaaS_Application',
                        'blank' => false,
                        'relate_name' => 'configuration',
                        'verbose' => __('application'),
                        'help_text' => __('Related application.')
                ),
                'key' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 250
                ),
                'value' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 250
                ),
                'type' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false
                ),
                'owner_write' => array( // owner can write
                        'type' => 'Pluf_DB_Field_Boolean',
                        'blank' => false
                ),
                'owner_read' => array( // owner can read
                        'type' => 'Pluf_DB_Field_Boolean',
                        'blank' => false
                ),
                'member_write' => array( // member can write
                        'type' => 'Pluf_DB_Field_Boolean',
                        'blank' => false
                ),
                'member_read' => array( // member can read
                        'type' => 'Pluf_DB_Field_Boolean',
                        'blank' => false
                ),
                'authorized_write' => array( // authorized can write
                        'type' => 'Pluf_DB_Field_Boolean',
                        'blank' => false
                ),
                'authorized_read' => array( // authorized can read
                        'type' => 'Pluf_DB_Field_Boolean',
                        'blank' => false
                ),
                'other_write' => array( // other can write
                        'type' => 'Pluf_DB_Field_Boolean',
                        'blank' => false
                ),
                'other_read' => array( // other can read
                        'type' => 'Pluf_DB_Field_Boolean',
                        'blank' => false
                ),
                'description' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 250
                ),
                'creation_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('creation date'),
                        'help_text' => __('Creation date of the configuration.')
                ),
                'modif_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('modification date'),
                        'help_text' => __(
                                'Modification date of the configuration.')
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

    /**
     * Check if a user is anonymous.
     *
     * @return bool True if the user is anonymous.
     */
    function isStored ()
    {
        return (0 === (int) $this->id);
    }
}