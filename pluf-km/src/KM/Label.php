<?php

/**
 * مدل داده‌ای برای برچسب گذاری ایجاد می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 */
class KM_Label extends Pluf_Model
{

    /**
     *
     * {@inheritDoc}
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_model = 'KM_Label';
        $this->_a['table'] = 'km_label';
        $this->_a['model'] = 'KM_Label';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ),
                'user' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'Pluf_User',
                        'blank' => false
                ),
                'community' => array(
                        'type' => 'Pluf_DB_Field_Boolean',
                        'blank' => false,
                        'verbose' => __('created by community'),
                        'help_text' => __(
                                'Define wether the location created by the community or not.')
                ),
                'title' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 250,
                        'verbose' => __('title'),
                        'help_text' => __(
                                'The title of the label must only contain letters, digits or the dash character. For example: My-new-Wiki-Page.')
                ),
                'description' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 500,
                        'verbose' => __('description'),
                        'help_text' => __(
                                'The description of the label must only contain letters. For example: en.')
                ),
                'color' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 100,
                        'verbose' => __('color'),
                        'help_text' => __(
                                'A one line description of the page content.')
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
     * پیش ذخیره را انجام می‌دهد
     *
     * @param $create حالت
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave ($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
            $this->community = true;
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