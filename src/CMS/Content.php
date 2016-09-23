<?php

/**
 * ساختار داده‌ای یک دستگاه را تعیین می‌کند.
 * 
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *
 */
class CMS_Content extends Pluf_Model
{

    /**
     * مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_model = 'CMS_Content';
        
        $this->_a['table'] = 'cms_content';
        $this->_a['model'] = 'CMS_Content';
        $this->_a['cols'] = array(
                // شناسه‌ها
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => false,
                        'verbose' => __('first name'),
                        'help_text' => __('id'),
                        'editable' => false
                ),
                // فیلدها
                'name' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 64,
                        'verbose' => __('name'),
                        'help_text' => __('content name'),
                        'editable' => true
                ),
                'title' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 250,
                        'default' => 'no title',
                        'verbose' => __('title'),
                        'help_text' => __('content title'),
                        'editable' => true
                ),
                'description' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 250,
                        'default' => 'auto created content',
                        'verbose' => __('description'),
                        'help_text' => __('content description'),
                        'editable' => true
                ),
                'mime_type' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 64,
                        'default' => 'application/octet-stream',
                        'verbose' => __('mime type'),
                        'help_text' => __('content mime type'),
                        'editable' => false
                ),
                'tag' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 20,
                        'default' => 'binary',
                        'verbose' => __('tag'),
                        'help_text' => __('content tag'),
                        'editable' => true
                ),
                'file_path' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 250,
                        'verbose' => __('file path'),
                        'help_text' => __('content file path'),
                        'editable' => false
                ),
                'file_name' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 250,
                        'default' => 'unknown',
                        'verbose' => __('file name'),
                        'help_text' => __('content file name'),
                        'editable' => false
                ),
                'file_size' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'default' => 'no title',
                        'verbose' => __('file size'),
                        'help_text' => __('content file size'),
                        'editable' => false
                ),
                'downloads' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'default' => 0,
                        'default' => 'no title',
                        'verbose' => __('downloads'),
                        'help_text' => __('content downloads number'),
                        'editable' => false
                ),
                'creation_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('creation'),
                        'help_text' => __('content creation time'),
                        'editable' => false
                ),
                'modif_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('modification'),
                        'help_text' => __('content modification time'),
                        'editable' => false
                ),
                // رابطه‌ها
                'tenant' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'SaaS_Application',
                        'blank' => false,
                        'relate_name' => 'tenant',
                        'default' => 'no title',
                        'verbose' => __('tenant'),
                        'help_text' => __('content tenant'),
                        'editable' => false
                ),
                'submitter' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'Pluf_User',
                        'blank' => false,
                        'relate_name' => 'content_submitter',
                        'verbose' => __('submitter'),
                        'help_text' => __('content submitter'),
                        'editable' => false
                )
        );
        
        $this->_a['idx'] = array(
                'content_idx' => array(
                        'col' => 'tenant, name',
                        'type' => 'normal', // normal, unique, fulltext, spatial
                        'index_type' => '', // hash, btree
                        'index_option' => '',
                        'algorithm_option' => '',
                        'lock_option' => ''
                ),
                'content_mime_filter_idx' => array(
                        'col' => 'tenant, mime_type',
                        'type' => 'normal', // normal, unique, fulltext, spatial
                        'index_type' => '', // hash, btree
                        'index_option' => '',
                        'algorithm_option' => '',
                        'lock_option' => ''
                ),
                'content_tag_filter_idx' => array(
                        'col' => 'tenant, tag',
                        'type' => 'normal', // normal, unique, fulltext, spatial
                        'index_type' => '', // hash, btree
                        'index_option' => '',
                        'algorithm_option' => '',
                        'lock_option' => ''
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