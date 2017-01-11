<?php

class SDP_Asset extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'sdp_asset';
        $this->_a['model'] = 'SDP_Asset';
        $this->_a['verbose'] = 'SDP Asset';
        $this->_model = 'SDP_Asset';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => false,
                'editable' => false,
                'readable' => true
            ),
            'name' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 250,
                'editable' => true,
                'readable' => true
            ),
            'path' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 250,
                'editable' => false,
                'readable' => false
            ),
            'size' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'blank' => false,
                'default' => 0,
                'editable' => false,
                'readable' => true
            ),
            'download' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'blank' => false,
                'default' => 0,
                'editable' => false,
                'readable' => true
            ),
            'driver_type' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 250,
                'editable' => false,
                'readable' => false
            ),
            'driver_id' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'blank' => false,
                'editable' => false,
                'readable' => false
            ),
            'creation_dtime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => true,
                'editable' => false,
                'readable' => true
            ),
            'modif_dtime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => true,
                'editable' => false,
                'readable' => true
            ),
            'type' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 250,
                'editable' => false,
                'readable' => true
            ),
//             'content_name' => array(
//                 'type' => 'Pluf_DB_Field_Varchar',
//                 'blank' => false,
//                 'size' => 2500,
//                 'editable' => true,
//                 'readable' => true
//             ),
            'description' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 250,
                'editable' => true,
                'readable' => true
            ),
            'mime_type' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 250,
                'editable' => false,
                'readable' => true
            ),
            'price' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'blank' => true,
                'default' => 0,
                'editable' => true,
                'readable' => true
            ),
            // relations
            'tenant' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'Pluf_Tenant',
                'blank' => false,
                'relate_name' => 'tenant',
                'editable' => false,
                'readable' => false,
            ),
            'parent' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'SDP_Asset',
                'blank' => false,
                'relate_name' => 'parent',
                'editable' => true,
                'readable' => true
            ),
            'content' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'CMS_Content',
                'blank' => true,
                'relate_name' => 'content',
                'editable' => true,
                'readable' => true,
            ),
            'thumbnail' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'CMS_Content',
                'blank' => true,
                'relate_name' => 'content',
                'editable' => true,
                'readable' => true,
            )
//             ,
//             'categories' => array(
//                 'type' => 'Pluf_DB_Field_Manytomany',
//                 'model' => 'SDP_Category',
//                 'relate_name' => 'categories',
//                 'blank' => false,
//                 'editable' => false,
//                 'readable' => false
//             ),
//             'tags' => array(
//                 'type' => 'Pluf_DB_Field_Manytomany',
//                 'model' => 'SDP_Tag',
//                 'relate_name' => 'tags',
//                 'blank' => false,
//                 'editable' => false,
//                 'readable' => false
//             )
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

    /**
     * \brief عملیاتی که قبل از پاک شدن است انجام می‌شود
     *
     * عملیاتی که قبل از پاک شدن است انجام می‌شود
     * در این متد فایل مربوط به است حذف می شود. این عملیات قابل بازگشت نیست
     */
    function preDelete()
    {
        if (file_exists($this->path . '/' . $this->id)) {
            unlink($this->path . '/' . $this->id);
        }
    }
}