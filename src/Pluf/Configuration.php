<?php

/**
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Pluf_Configuration extends Pluf_Model
{
    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'configuration';
        $this->_a['verbose'] = 'Configuration';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true,
                        'editable' => false,
                        'readable' => true
                ),
                'type' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'editable' => false
                ),
                'key' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 250,
                        'editable' => false,
                        'readable' => true
                ),
                'value' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 250,
                        'editable' => true,
                        'readable' => true
                ),
                'description' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 250,
                        'editable' => true,
                        'readable' => true
                ),
                'creation_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('creation date'),
                        'help_text' => __('Creation date of the configuration.'),
                        'editable' => false,
                        'readable' => true
                ),
                'modif_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('modification date'),
                        'help_text' => __(
                                'Modification date of the configuration.'),
                        'editable' => false,
                        'readable' => true
                )
        );
        $this->_a['idx'] = array(
                'key_idx' => array(
                        'type' => 'unique',
                        'col' => 'type, key'
                )
        );
        $this->_a['views'] = array(
                'list' => array(
                        'select' => 'id, configuration.key, description, creation_dtime, modif_dtime'
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

}