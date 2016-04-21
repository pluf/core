<?php

/**
 * ساختار یک صفحه را تعیین می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class SaaSCMS_Page extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'saascms_page';
        $this->_a['model'] = 'SaaSCMS_Page';
        $this->_model = 'SaaSCMS_Page';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => true
            ),
            'name' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 250
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
            // relations
            'content' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'SaaSCMS_Content',
                'blank' => false,
                'relate_name' => 'page_content'
            ),
            'tenant' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'SaaS_Application',
                'blank' => false,
                'relate_name' => 'tenant'
            )
        );
        
        $this->_a['idx'] = array(
            'page_class_idx' => array(
                'col' => 'tenant, name',
                'type' => 'unique', // fulltext, spatial
                'index_type' => 'btree', // hash
                'index_option' => '',
                'algorithm_option' => '',
                'lock_option' => ''
            )
        );
    }

    /**
     * \brief پیش ذخیره را انجام می‌دهد
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