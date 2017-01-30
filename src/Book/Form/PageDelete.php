<?php

/**
 * یک صفحه را حذف می‌کند.
 *
 * این فرم تمام اطلاعات صفحه و تاریخچه آن را از سیستم به صورت کامل حذف می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Book_Form_PageDelete extends Pluf_Form
{

    protected $page = null;

    public function initFields ($extra = array())
    {
        $this->page = $extra['page'];
        $this->fields['confirm'] = new Pluf_Form_Field_Boolean(
                array(
                        'required' => true,
                        'label' => __(
                                'yes, I understand that the page and all its revisions will be deleted'),
                        'initial' => ''
                ));
    }

    /**
     * Check the confirmation.
     */
    public function clean_confirm ()
    {
        if (! $this->cleaned_data['confirm']) {
            throw new Pluf_Form_Invalid(__('you need to confirm the deletion'));
        }
        return $this->cleaned_data['confirm'];
    }

    function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Exception(
                    __('cannot save the model from an invalid form'));
        }
        $this->page->delete();
        return true;
    }
}
