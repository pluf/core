<?php

/**
 * به روزرسانی یک صفحه
 *
 * با استفاده از این فرم می‌توان اطلاعات یک صفحه را به روزرسانی کرد.
 *
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *
 */
class CMS_Form_PageUpdate extends Pluf_Form
{
    
    // public $user = null;
    public $page = null;

    public function initFields($extra = array())
    {
        // $this->user = $extra['user'];
        $this->page = $extra['page'];
        
        $this->fields['name'] = new Pluf_Form_Field_Varchar(array(
            'required' => false,
            'label' => 'Name',
            'initial' => $this->page->name,
            'help_text' => 'Name of page'
        ));
                
        $this->fields['content'] = new Pluf_Form_Field_Integer(array(
            'required' => false,
            'label' => 'Content Id',
            'help_text' => 'Content id related to page'
        ));
    }

    function update($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception('cannot save the page from an invalid form');
        }
        // update the page
        $this->page->setFromFormData($this->cleaned_data);
        if ($commit) {
            $this->page->update();
        }
        return $this->page;
    }
}
