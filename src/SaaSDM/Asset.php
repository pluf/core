<?php

class SaaSDM_Asset extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'saascdm_asset';
        $this->_a['model'] = 'SaaSDM_Asset';
        $this->_model = 'SaaSDM_Asset';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => false
            ),
            'name' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 250
            ),
			
        	// TODO: maso, 1395: سایر خصوصیت‌های این مدل باید اضافه شود 
            'creation_dtime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => true,
            ),
            'modif_dtime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => true,
            ),
 
            // relations
            'tenant' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'SaaS_Application',
                'blank' => false,
                'relate_name' => 'tenant'
            )
        );
        
        $this->_a['idx'] = array(
            'page_class_idx' => array(
                'col' => 'tenant, parent, name',
                'type' => 'unique', // normal, unique, fulltext, spatial
                'index_type' => '', // hash, btree
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