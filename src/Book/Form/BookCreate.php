<?php

/**
 * ایجاد یک صفحه ویکی جدید
 *
 * با استفاده از این فرم می‌توان یک صفحه جدید ویکی را ایجاد کرد.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Book_Form_BookCreate extends Pluf_Form
{

    public $user = null;

    public function initFields ($extra = array())
    {
        $this->user = $extra['user'];
        $initial = __('empty summary');
        $initname = (! empty($extra['name'])) ? $extra['name'] : __('PageName');
        $this->fields['title'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('page title'),
                        'initial' => $initname,
                        'widget_attrs' => array(
                                'maxlength' => 200,
                                'size' => 67
                        ),
                        'help_text' => __(
                                'the page name must contains only letters, digits and the dash (-) character')
                ));
        $this->fields['summary'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('description'),
                        'initial' => $initial,
                        'help_text' => __(
                                'this one line description is displayed in the list of pages'),
                        'initial' => '',
                        'widget_attrs' => array(
                                'maxlength' => 200,
                                'size' => 67
                        )
                ));
    }

    function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('cannot save the book from an invalid form'));
        }
        // Create the book
        $page = new Book_Book();
        $page->setFromFormData($this->cleaned_data);
        $page->submitter = $this->user;
        if ($commit) {
            $page->create();
        }
        return $page;
    }
}
