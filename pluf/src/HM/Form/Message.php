<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('HM_Shortcuts_messageFactory');

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 */
class HM_Form_Message extends Pluf_Form
{

    /**
     *
     * @var unknown
     */
    var $application = null;

    var $message = null;

    /**
     * (non-PHPdoc)
     *
     * @see Pluf_Form::initFields()
     */
    public function initFields ($extra = array())
    {
        $this->application = $extra['application'];
        if (array_key_exists('message', $extra)) {
            $this->message = $extra['message'];
        }
        $this->message = HM_Shortcuts_messageFactory($this->message);
        
        $this->fields['title'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('title'),
                        'initial' => $this->message->title
                ));
        
        $this->fields['message'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('message'),
                        'initial' => $this->message->message
                ));
    }

    /**
     * مدل داده‌ای را ذخیره می‌کند
     *
     * مدل داده‌ای را بر اساس تغییرات تعیین شده توسط کاربر به روز می‌کند. در
     * صورتی
     * که پارامتر ورودی با نا درستی مقدار دهی شود تغییراد ذخیره نمی شود در غیر
     * این
     * صورت داده‌ها در پایگاه داده ذخیره می‌شود.
     *
     * @param $commit داده‌ها
     *            ذخیره شود یا نه
     * @return مدل داده‌ای تغییر یافته
     */
    public function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('Cannot save the message from an invalid form.'));
        }
        // Set attributes
        $this->message->setFromFormData($this->cleaned_data);
        $this->message->apartment = $this->application;
        if ($commit) {
            if (! $this->message->create()) {
                throw new Pluf_Exception(__('Fail to save the message.'));
            }
        }
        return $this->message;
    }

    /**
     * مدل داده‌ای را به روز می‌کند.
     *
     * @throws Pluf_Exception
     */
    public function update ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('Cannot save the message from an invalid form.'));
        }
        // Set attributes
        $this->message->setFromFormData($this->cleaned_data);
        if ($commit) {
            if (! $this->message->update()) {
                throw new Pluf_Exception(__('Fail to save the message.'));
            }
        }
        return $this->message;
    }
}

