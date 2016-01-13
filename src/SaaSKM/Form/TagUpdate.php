<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');

/**
 * فرم به روز رسانی اطلاعات کاربر را ایجاد می‌کند.
 */
class SaaSKM_Form_TagUpdate extends Pluf_Form
{
    var $tag = null;
    /**
     * مقدار دهی فیلدها.
     *
     * @see Pluf_Form::initFields()
     */
    public function initFields ($extra = array())
    {
        $this->tag = $extra['tag'];
        $this->fields['tag_title'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('title')
                ));
        
        $this->fields['tag_description'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('description')
                ));
    }

    /**
     *
     * @throws Pluf_Exception
     */
    function update ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('cannot update a tag from an invalid form'));
        }
        $this->tag->setFromFormData($this->cleaned_data);
        if ($commit) {
            $this->tag->update();
        }
        return $this->tag;
    }
}
