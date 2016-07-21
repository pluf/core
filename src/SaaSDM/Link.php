<?php

class SaaSDM_Link extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'saascdm_link';
        $this->_a['model'] = 'SaaSDM_Link';
        $this->_model = 'SaaSDM_Link';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => false
            ),
            'secure_link' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 50
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
            ),
            'asset' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'SaaSDM_Asset',
                'blank' => false,
                'relate_name' => 'asset'
            )
        );
        
        $this->_a['idx'] = array(
            'link_tenant_idx' => array(
                'col' => 'tenant, secure_link',
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