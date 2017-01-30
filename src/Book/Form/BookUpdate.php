<?php

/**
 * ایجاد یک صفحه ویکی جدید
 *
 * با استفاده از این فرم می‌توان یک صفحه جدید ویکی را ایجاد کرد.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Book_Form_BookUpdate extends Pluf_Form
{

    public $user = null;

    public $book = null;

    public function initFields ($extra = array())
    {
        $this->user = $extra['user'];
        $this->book = $extra['book'];
        $this->fields['title'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('page title'),
                        'initial' => $this->book->title,
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
                        'initial' => $this->book->summary,
                        'help_text' => __(
                                'this one line description is displayed in the list of pages'),
                        'initial' => '',
                        'widget_attrs' => array(
                                'maxlength' => 200,
                                'size' => 67
                        )
                ));
    }

    function update ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('cannot save the book from an invalid form'));
        }
        // Create the book
        $this->book->setFromFormData($this->cleaned_data);
        if ($commit) {
            $this->book->update();
        }
        return $this->book;
    }
}
