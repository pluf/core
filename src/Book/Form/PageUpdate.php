<?php

/**
 * به روز کردن یک صفحه از سند
 *
 * یک صفحه ویکی را به روز می‌کند و در صورتی نیاز یک نسخه از آن نگهداری می‌کند.
 * 
 * @author <mostafa.barmshory@dpq.co.ir>
 *
 */
class Book_Form_PageUpdate extends Book_Form_PageCreate
{

    public $user = null;

    public $page = null;

    public function initFields ($extra = array())
    {
        $this->page = $extra['page'];
        $this->user = $extra['user'];
        
        $this->fields['title'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('Page title'),
                        'initial' => $this->page->title
                ));
        $this->fields['summary'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('Summary'),
                        'initial' => $this->page->summary
                ));
        $this->fields['content'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('Content'),
                        'initial' => $this->page->content,
                        'widget' => 'Pluf_Form_Widget_TextareaInput'
                ));
        $this->fields['content_type'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('content type'),
                        'initial' => $this->page->content_type
                ));
    }

    function update ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Exception(
                    __('Cannot update the page from an invalid form'));
        }
        $this->page->setFromFormData($this->cleaned_data);
        if ($commit) {
            $this->page->update();
        }
        return $this->page;
    }
}
