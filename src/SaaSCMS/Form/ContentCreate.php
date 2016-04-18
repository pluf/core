<?php

/**
 * ایجاد یک محتوای جدید
 *
 * با استفاده از این فرم می‌توان یک محتوای جدید را ایجاد کرد.
 * 
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *
 */
class SaaSCMS_Form_ContentCreate extends Pluf_Form
{

    public $tenant = null;
//     public $user = null;

    public function initFields ($extra = array())
    {
        $this->tenant = $extra['tenant'];
//         $this->user = $extra['user'];

        $this->fields['title'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => 'Title',
                        'help_text' => 'Title of content'
                ));
        
        $this->fields['description'] = new Pluf_Form_Field_Varchar(
        		array(
        				'required' => false,
        				'label' => 'Description',
        				'help_text' => 'Description about content'
        		));
    }

    function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception('cannot save the content from an invalid form');
        }
        // Create the content
        $content = new SaaSCMS_Content();
        $content->setFromFormData($this->cleaned_data);
//         $content->user = $this->user;
        $content->tenant = $this->tenant;
        if ($commit) {
            $content->create();
        }
        return $content;
    }
}
