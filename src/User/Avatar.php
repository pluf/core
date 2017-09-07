<?php

/**
 * ساختار داده‌ای پروفایل کاربر را تعیین می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class User_Avatar extends Pluf_Model
{

    /**
     * مدل داده‌ای را بارگذاری می‌کند.
     *
     * تمام فیلدهای مورد نیاز برای این مدل داده‌ای در این متد تعیین شده و به
     * صورت کامل ساختار دهی می‌شود.
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'user_avatar';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => true,
                'editable' => false
            ),
            'user' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'Pluf_User',
                'blank' => false,
                'editable' => false
            ),
            'fileName' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'unique' => false,
                'editable' => false
            ),
            'filePath' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'unique' => false,
                'editable' => false
            ),
            'fileSize' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'blank' => false,
                'verbose' => __('validate'),
                'editable' => false
            ),
            'mimeType' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 50,
                'editable' => false
            ),
            'creationTime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => false,
                'verbose' => __('creation date'),
                'help_text' => __('Creation date of the avatar.'),
                'editable' => false
            ),
            'modifTime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => false,
                'verbose' => __('modification date'),
                'help_text' => __('Modification date of the avatar.'),
                'editable' => false
            )
        );
        
        $this->_a['idx'] = array(
                'user_avatar_idx' => array(
                        'col' => 'user',
                        'type' => 'unique'
                )
        );
    }

    /**
     * پیش ذخیره را انجام می‌دهد
     *
     * در این فرآیند نیازهای ابتدایی سیستم به آن اضافه می‌شود. این نیازها
     * مقادیری هستند که
     * در زمان ایجاد باید تعیین شوند. از این جمله می‌توان به کاربر و تاریخ اشاره
     * کرد.
     *
     * @param $create حالت
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave($create = false)
    {
        if ($this->id == '') {
            $this->creationTime = gmdate('Y-m-d H:i:s');
        }
        $this->modifTime = gmdate('Y-m-d H:i:s');
        // File path
        $path = $this->getAbsloutPath();
        // file size
        if (file_exists($path)) {
            $this->fileSize = filesize($path);
        } else {
            $this->fileSize = 0;
        }
        // mime type (based on file name)
        $fileInfo = Pluf_FileUtil::getMimeType($this->fileName);
        $this->mimeType = $fileInfo[0];
    }

    /**
     * مسیر کامل محتوی را تعیین می‌کند.
     *
     * @return string
     */
    public function getAbsloutPath()
    {
        return $this->filePath . '/' . $this->user;
    }

    /**
     *
     * @see Pluf_Model::postSave()
     */
    function preDelete($create = false)
    {
        unlink($this->getAbsloutPath());
    }
}