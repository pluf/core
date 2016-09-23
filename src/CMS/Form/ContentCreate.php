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
    // public $user = null;
    public function initFields ($extra = array())
    {
        $this->tenant = $extra['tenant'];
        $extra['model'] = Pluf::factory('CMS_Content');
        parent::initFields($extra);
    }

    public function clean_name ()
    {
        $name = $this->cleaned_data['name'];
        $q = new Pluf_SQL('tenant=%s and name=%s', 
                array(
                        $this->tenant->id,
                        $name
                ));
        $items = Pluf::factory('CMS_Content')->getOne($q->gen());
        if (! isset($items) || $items->count() == 0) {
            return $name;
        }
        throw new Pluf_Exception(__('content with the same name exist'));
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
        // $content->user = $this->user;
        $content->tenant = $this->tenant;
        if ($commit) {
            $content->create();
        }
        return $content;
    }
}
