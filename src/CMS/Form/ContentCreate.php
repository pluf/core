<?php

/**
 * ایجاد یک محتوای جدید
 *
 * با استفاده از این فرم می‌توان یک محتوای جدید را ایجاد کرد.
 * 
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *
 */
class CMS_Form_ContentCreate extends Pluf_Form_Model
{

    public $tenant = null;
    public $user = null;

    public function initFields ($extra = array())
    {
        $this->tenant = $extra['tenant'];
        $this->user = $extra['user'];
        parent::initFields($extra);       
    }

    public function clean_name ()
    {
        $name = $this->cleaned_data['name'];
        if (empty($name))
            return null;
        return CMS_Shortcuts_CleanName($name, $this->tenant);
    }

    function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    'cannot save the content from an invalid form');
        }
        // Create the content
        $content = new CMS_Content();
        $content->setFromFormData($this->cleaned_data);
        $content->file_path = Pluf::f('upload_path') . '/' . $this->tenant->id .
                 '/cms';
        if (! is_dir($content->file_path)) {
            if (false == @mkdir($content->file_path, 0777, true)) {
                throw new Pluf_Form_Invalid(
                        'An error occured when creating the upload path. Please try to send the file again.');
            }
        }
        $content->submitter = $this->user;
        $content->tenant = $this->tenant;
        if ($commit) {
            $content->create();
        }
        return $content;
    }
}
