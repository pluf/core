<?php

/**
 * ارتباط بین دو SDP_Asset را تعریف می‌کند. در یک ارتباط بین دو دارایی موارد زیر باید تعریف شوند:
 * - type: نوع ارتباط. مثلا یک دارایی خلاصه دارایی دیگر است. یا یک دارایی نسخه قبلی دارایی دیگر است.
 * - start: ابتدای ارتباط یا به عبارتی پدر
 * - end: انتهای ارتباط یا به عبارتی فرزند
 
 * مثال: به عنوان مثال اگر یک دارایی با شناسه ۱ داشته باشیم که یک کتاب باشد و 
 * یک دارایی دیگر با شناسه ۲ داشته باشیم که خلاصه آن کتاب باشد می‌توان رابطه‌ای به صورت زیر بین آن‌ها تعریف کرد:
 * {
 *      type : summary
 *      start : 1
 *      end : 2
 * }
 * این یعنی دارایی ۲ خلاصه داریی ۱ است 
 * @author hadi
 *
 */
class SDP_AssetRelation extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'sdp_assetrelation';
        $this->_a['verbose'] = 'AssetRelation';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => false,
                'editable' => false,
                'readable' => true
            ),
            'type' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 250,
                'editable' => true,
                'readable' => true
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
            'description' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 250,
                'editable' => true,
                'readable' => true
            ),
            // relations
            'start' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'SDP_Asset',
                'blank' => false,
                'relate_name' => 'parent_asset',
                'editable' => true,
                'readable' => true
            ),
            'end' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'SDP_Asset',
                'blank' => false,
                'relate_name' => 'child_asset',
                'editable' => true,
                'readable' => true
            ),
        );
        
        $this->_a['idx'] = array(
            'assetrelation_class_idx' => array(
                'col' => 'type, start, end',
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
        //
    }
}