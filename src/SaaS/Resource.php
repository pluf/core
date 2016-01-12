<?php

/**
 * منابع یک نرم افزار را تعیین می‌کند
 * 
 * هر نرم‌افزار می‌تواند شامل منابعی خاص باشد مانند تصویر نماد و یا سایر موارد. این کلاس
 * امکان تعریف منابع برای یک نرم‌افزار کاربردی را فراهم می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class SaaS_Resource extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'saas_resource';
        $this->_a['model'] = 'SaaS_Resource';
        $this->_model = 'SaaS_Resource';
        
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ),
                'file' => array(
                        'type' => 'Pluf_DB_Field_File',
                        'blank' => false
                ),
                'file_path' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 250
                ),
                'file_size' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false
                ),
                'mime_type' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 50
                ),
                'downloads' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => true
                ),
                'description' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 250
                ),
                

                /*
                 * دسترسی‌ها 
                 */
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
                
                /*
                 * تاریخ‌ها
                 */
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
                ),
                
                
                /*
                 * رابطه‌ها
                 */
                'application' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'SaaS_Application',
                        'blank' => false,
                        'relate_name' => 'configuration',
                        'verbose' => __('application'),
                        'help_text' => __('Related application.')
                ),
                'submitter' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'Pluf_User',
                        'blank' => false,
                        'relate_name' => 'submitter',
                        'verbose' => __('submitter'),
                        'help_text' => __('submitter')
                )
        );
        
        $this->_a['idx'] = array();
        $this->_a['views'] = array();
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

    public function getMimeType ()
    {
        // XXX: maso, 1394: تعیین نوع پرونده
        return "application/binary";
    }

    public function getAbslotePath ()
    {
        // XXX: maso, 1394: تعیین نوع پرونده
        return Pluf::f('upload_path') . $this->file_path . '/' . $this->file;
    }
    
    /**
     * Hook run just before deleting a model from the database.
     *
     * Just overwrite it into your model to perform custom actions.
     */
    function preDelete ()
    {
        $filename = $this->getAbslotePath();
        unlink($filename);
    }
    
}